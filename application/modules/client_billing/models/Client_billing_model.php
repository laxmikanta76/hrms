<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Client_billing_model
 * Full DB layer for Client Service Billing System
 */
class Client_billing_model extends CI_Model {

    // =========================================================
    // INVOICE NUMBER GENERATOR
    // =========================================================
    public function next_invoice_number() {
        $co = $this->db->get('cb_company')->row();
        if (!$co) return 'INV-2026-0001';
        $prefix  = $co->invoice_prefix ?? 'INV';
        $counter = $co->invoice_counter ?? 1001;
        return $prefix . '-' . date('Y') . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);
    }

    private function _bump_counter() {
        $this->db->set('invoice_counter', 'invoice_counter + 1', FALSE)
                 ->update('cb_company');
    }

    // =========================================================
    // INVOICES
    // =========================================================
    public function create_invoice($data, $items) {
        $this->db->trans_start();
        $this->db->insert('cb_invoices', $data);
        $id = $this->db->insert_id();
        foreach ($items as &$it) { $it['invoice_id'] = $id; }
        $this->db->insert_batch('cb_invoice_items', $items);
        $this->_log($id, null, $data['status'], 'Invoice created', $data['created_by'] ?? '');
        $this->_bump_counter();
        $this->db->trans_complete();
        return $this->db->trans_status() ? $id : false;
    }

    public function update_invoice($id, $data, $items) {
        $this->db->trans_start();
        $old = $this->db->get_where('cb_invoices', ['id' => $id])->row();
        $this->db->where('id', $id)->update('cb_invoices', $data);
        $this->db->where('invoice_id', $id)->delete('cb_invoice_items');
        foreach ($items as &$it) { $it['invoice_id'] = $id; }
        $this->db->insert_batch('cb_invoice_items', $items);
        if ($old && $old->status !== ($data['status'] ?? $old->status))
            $this->_log($id, $old->status, $data['status'], 'Invoice updated');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_invoice($id) {
        return $this->db->get_where('cb_invoices', ['id' => $id, 'deleted_at' => null])->row();
    }

    public function get_invoice_full($id) {
        $inv = $this->get_invoice($id);
        if (!$inv) return false;
        $inv->items    = $this->get_items($id);
        $inv->client   = $this->get_client($inv->client_id);
        $inv->company  = $this->get_company();
        $inv->bank     = $inv->bank_account_id ? $this->get_bank($inv->bank_account_id) : $this->default_bank();
        $inv->payments = $this->get_invoice_payments($id);
        $inv->logs     = $this->get_logs($id);
        return $inv;
    }

    public function get_items($invoice_id) {
        return $this->db->where('invoice_id', $invoice_id)->order_by('sl_no')->get('cb_invoice_items')->result();
    }

    public function get_invoices($f = [], $limit = 20, $offset = 0) {
        $this->db->select('i.*, c.company_name, c.contact_person, c.gstin AS client_gstin')
                 ->from('cb_invoices i')
                 ->join('cb_clients c', 'c.id = i.client_id', 'left')
                 ->where('i.deleted_at', null);
        if (!empty($f['status']))       $this->db->where('i.status', $f['status']);
        if (!empty($f['client_id']))    $this->db->where('i.client_id', $f['client_id']);
        if (!empty($f['invoice_type'])) $this->db->where('i.invoice_type', $f['invoice_type']);
        if (!empty($f['date_from']))    $this->db->where('i.invoice_date >=', $f['date_from']);
        if (!empty($f['date_to']))      $this->db->where('i.invoice_date <=', $f['date_to']);
        if (!empty($f['search'])) {
            $this->db->group_start()
                     ->like('i.invoice_number', $f['search'])
                     ->or_like('c.company_name', $f['search'])
                     ->or_like('c.contact_person', $f['search'])
                     ->group_end();
        }
        $this->db->order_by('i.created_at', 'DESC');
        if ($limit) $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_invoices($f = []) {
        $this->db->from('cb_invoices i')
                 ->join('cb_clients c', 'c.id = i.client_id', 'left')
                 ->where('i.deleted_at', null);
        if (!empty($f['status']))    $this->db->where('i.status', $f['status']);
        if (!empty($f['client_id'])) $this->db->where('i.client_id', $f['client_id']);
        if (!empty($f['search'])) {
            $this->db->group_start()
                     ->like('i.invoice_number', $f['search'])
                     ->or_like('c.company_name', $f['search'])
                     ->group_end();
        }
        return $this->db->count_all_results();
    }

    public function delete_invoice($id) {
        return $this->db->where('id', $id)->update('cb_invoices', ['deleted_at' => date('Y-m-d H:i:s')]);
    }

    public function update_status($id, $status, $user = '') {
        $old = $this->db->get_where('cb_invoices', ['id' => $id])->row();
        $this->db->where('id', $id)->update('cb_invoices', ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
        $this->_log($id, $old ? $old->status : null, $status, 'Status updated', $user);
        return $this->db->affected_rows() > 0;
    }

    public function duplicate_invoice($id, $user = '') {
        $orig = $this->get_invoice_full($id);
        if (!$orig) return false;
        $d = (array)$orig;
        unset($d['id'], $d['items'], $d['client'], $d['company'], $d['bank'], $d['payments'], $d['logs']);
        $d['invoice_number'] = $this->next_invoice_number();
        $d['invoice_date']   = date('Y-m-d');
        $d['due_date']       = date('Y-m-d', strtotime('+30 days'));
        $d['status']         = 'draft';
        $d['amount_paid']    = 0;
        $d['balance_due']    = $orig->grand_total;
        $d['sent_at']        = null;
        $d['created_by']     = $user;
        $d['created_at']     = date('Y-m-d H:i:s');
        $d['updated_at']     = date('Y-m-d H:i:s');
        $d['deleted_at']     = null;

        $items = [];
        foreach ($orig->items as $it) {
            $row = (array)$it;
            unset($row['id'], $row['invoice_id']);
            $items[] = $row;
        }
        return $this->create_invoice($d, $items);
    }

    public function mark_overdue() {
        $this->db->where_in('status', ['unpaid','sent'])
                 ->where('due_date <', date('Y-m-d'))
                 ->where('deleted_at', null)
                 ->update('cb_invoices', ['status' => 'overdue']);
    }

    // =========================================================
    // CLIENTS
    // =========================================================
    public function get_clients($active = false) {
        if ($active) $this->db->where('is_active', 1);
        $this->db->where('deleted_at', null)->order_by('company_name');
        return $this->db->get('cb_clients')->result();
    }

    public function get_client($id) {
        return $this->db->get_where('cb_clients', ['id' => $id])->row();
    }

    public function save_client($data, $id = null) {
        if ($id) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id)->update('cb_clients', $data);
            return $id;
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('cb_clients', $data);
        return $this->db->insert_id();
    }

    public function delete_client($id) {
        return $this->db->where('id', $id)->update('cb_clients', ['deleted_at' => date('Y-m-d H:i:s'), 'is_active' => 0]);
    }

    public function client_stats($client_id) {
        $s = new stdClass;
        $s->total_invoices = $this->db->where('client_id', $client_id)->where('deleted_at', null)->count_all_results('cb_invoices');
        $s->total_billed   = $this->db->select_sum('grand_total')->where('client_id', $client_id)->where('deleted_at', null)->get('cb_invoices')->row()->grand_total ?? 0;
        $s->total_paid     = $this->db->select_sum('amount')->where('client_id', $client_id)->get('cb_payments')->row()->amount ?? 0;
        $s->balance_due    = $s->total_billed - $s->total_paid;
        return $s;
    }

    // =========================================================
    // SERVICE CATALOG
    // =========================================================
    public function get_services($active = true) {
        if ($active) $this->db->where('is_active', 1);
        return $this->db->order_by('category, name')->get('cb_services')->result();
    }

    public function get_service($id) {
        return $this->db->get_where('cb_services', ['id' => $id])->row();
    }

    public function save_service($data, $id = null) {
        if ($id) { $this->db->where('id', $id)->update('cb_services', $data); return $id; }
        $this->db->insert('cb_services', $data);
        return $this->db->insert_id();
    }

    public function delete_service($id) {
        return $this->db->where('id', $id)->update('cb_services', ['is_active' => 0]);
    }

    public function get_service_categories() {
        return $this->db->select('DISTINCT category')->where('is_active', 1)->where('category IS NOT NULL', null, false)->get('cb_services')->result();
    }

    // =========================================================
    // PAYMENTS
    // =========================================================
    public function record_payment($data) {
        $this->db->trans_start();
        $data['payment_number'] = 'PAY-' . date('Y') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $this->db->insert('cb_payments', $data);
        $pid = $this->db->insert_id();

        $inv = $this->get_invoice($data['invoice_id']);
        if ($inv) {
            $paid    = $inv->amount_paid + $data['amount'];
            $balance = max(0, $inv->grand_total - $paid);
            $status  = $balance <= 0 ? 'paid' : 'partial';
            $this->db->where('id', $data['invoice_id'])->update('cb_invoices', [
                'amount_paid' => $paid,
                'balance_due' => $balance,
                'status'      => $status,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
            $this->_log($data['invoice_id'], $inv->status, $status, 'Payment ₹'.number_format($data['amount'],2).' recorded');
        }
        $this->db->trans_complete();
        return $this->db->trans_status() ? $pid : false;
    }

    public function get_invoice_payments($invoice_id) {
        return $this->db->where('invoice_id', $invoice_id)->order_by('payment_date', 'DESC')->get('cb_payments')->result();
    }

    public function get_payments($f = [], $limit = 30, $offset = 0) {
        $this->db->select('p.*, i.invoice_number, c.company_name')
                 ->from('cb_payments p')
                 ->join('cb_invoices i', 'i.id = p.invoice_id', 'left')
                 ->join('cb_clients c',  'c.id = p.client_id',  'left');
        if (!empty($f['client_id'])) $this->db->where('p.client_id', $f['client_id']);
        if (!empty($f['date_from'])) $this->db->where('p.payment_date >=', $f['date_from']);
        if (!empty($f['date_to']))   $this->db->where('p.payment_date <=', $f['date_to']);
        $this->db->order_by('p.created_at', 'DESC');
        if ($limit) $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    // =========================================================
    // COMPANY & BANK
    // =========================================================
    public function get_company()    { return $this->db->get('cb_company')->row(); }
    public function update_company($data) { return $this->db->where('id', 1)->update('cb_company', $data); }
    public function get_bank($id)    { return $this->db->get_where('cb_bank_accounts', ['id' => $id])->row(); }
    public function default_bank()   { return $this->db->get_where('cb_bank_accounts', ['is_default' => 1])->row(); }
    public function get_banks()      { return $this->db->where('is_active', 1)->get('cb_bank_accounts')->result(); }
    public function save_bank($data) { $this->db->insert('cb_bank_accounts', $data); return $this->db->insert_id(); }

    // =========================================================
    // DASHBOARD STATS
    // =========================================================
    public function dashboard_stats() {
        $s = [];
        $s['revenue_this_month'] = $this->db->select_sum('grand_total')
            ->where('status', 'paid')->where('MONTH(invoice_date)', date('m'))
            ->where('YEAR(invoice_date)', date('Y'))->where('deleted_at', null)
            ->get('cb_invoices')->row()->grand_total ?? 0;

        $s['outstanding'] = $this->db->select_sum('balance_due')
            ->where_in('status', ['unpaid','partial','overdue','sent'])
            ->where('deleted_at', null)->get('cb_invoices')->row()->balance_due ?? 0;

        $s['total_invoices'] = $this->db->where('deleted_at', null)->count_all_results('cb_invoices');
        $s['overdue_count']  = $this->db->where('status','overdue')->where('deleted_at',null)->count_all_results('cb_invoices');
        $s['draft_count']    = $this->db->where('status','draft')->where('deleted_at',null)->count_all_results('cb_invoices');
        $s['total_clients']  = $this->db->where('is_active',1)->where('deleted_at',null)->count_all_results('cb_clients');
        $s['paid_count']     = $this->db->where('status','paid')->where('deleted_at',null)->count_all_results('cb_invoices');

        $gst = $this->db->select('SUM(cgst_amount) c, SUM(sgst_amount) s, SUM(igst_amount) i')
            ->where('MONTH(invoice_date)', date('m'))->where('YEAR(invoice_date)', date('Y'))
            ->where('deleted_at', null)->get('cb_invoices')->row();
        $s['gst_this_month'] = ($gst->c ?? 0) + ($gst->s ?? 0) + ($gst->i ?? 0);

        $s['monthly_revenue'] = $this->monthly_revenue(6);

        $s['recent_invoices'] = $this->db
            ->select('i.*, c.company_name, c.contact_person')
            ->from('cb_invoices i')->join('cb_clients c','c.id = i.client_id','left')
            ->where('i.deleted_at', null)->order_by('i.created_at','DESC')->limit(8)->get()->result();

        $s['top_clients'] = $this->db
            ->select('c.company_name, SUM(i.grand_total) total, COUNT(i.id) cnt')
            ->from('cb_invoices i')->join('cb_clients c','c.id = i.client_id','left')
            ->where('i.deleted_at', null)->where('YEAR(i.invoice_date)', date('Y'))
            ->group_by('i.client_id')->order_by('total','DESC')->limit(5)->get()->result();

        $s['service_revenue'] = $this->db
            ->select('ii.item_description, SUM(ii.taxable_amount) total')
            ->from('cb_invoice_items ii')
            ->join('cb_invoices i', 'i.id = ii.invoice_id')
            ->where('i.deleted_at', null)->where('YEAR(i.invoice_date)', date('Y'))
            ->group_by('ii.item_description')->order_by('total','DESC')->limit(5)->get()->result();

        return $s;
    }

    public function monthly_revenue($months = 6) {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $y = date('Y', strtotime("-$i months"));
            $m = date('m', strtotime("-$i months"));
            $row = $this->db->select_sum('grand_total')
                ->where('YEAR(invoice_date)', $y)->where('MONTH(invoice_date)', $m)
                ->where('deleted_at', null)->get('cb_invoices')->row();
            $data[] = ['month' => date('M Y', strtotime("-$i months")), 'revenue' => $row->grand_total ?? 0];
        }
        return $data;
    }

    public function gst_report($year) {
        return $this->db->select('MONTH(invoice_date) mo, MONTHNAME(invoice_date) month_name,
            SUM(taxable_amount) taxable, SUM(cgst_amount) cgst, SUM(sgst_amount) sgst,
            SUM(igst_amount) igst, SUM(total_tax) total_gst, SUM(grand_total) total')
            ->where('YEAR(invoice_date)', $year)->where('deleted_at', null)
            ->group_by('MONTH(invoice_date)')->order_by('mo')->get('cb_invoices')->result();
    }

    public function service_wise_report($year) {
        return $this->db->select('ii.item_description, ii.hsn_sac_code,
            SUM(ii.quantity) total_qty, SUM(ii.taxable_amount) taxable,
            SUM(ii.total_amount) total, COUNT(DISTINCT ii.invoice_id) invoices')
            ->from('cb_invoice_items ii')
            ->join('cb_invoices i', 'i.id = ii.invoice_id')
            ->where('i.deleted_at', null)->where('YEAR(i.invoice_date)', $year)
            ->group_by('ii.item_description, ii.hsn_sac_code')
            ->order_by('total', 'DESC')->get()->result();
    }

    // =========================================================
    // LOGS
    // =========================================================
    private function _log($inv_id, $old, $new, $remarks = '', $user = '') {
        $this->db->insert('cb_invoice_logs', [
            'invoice_id' => $inv_id, 'old_status' => $old, 'new_status' => $new,
            'remarks'    => $remarks, 'by_user' => $user ?: ($this->session->userdata('fullname') ?? 'system'),
            'logged_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    public function get_logs($invoice_id) {
        return $this->db->where('invoice_id', $invoice_id)->order_by('logged_at','DESC')->get('cb_invoice_logs')->result();
    }

    // =========================================================
    // AMOUNT IN WORDS
    // =========================================================
    public function amount_in_words($n) {
        $n = (int)round(abs($n));
        if ($n === 0) return 'Zero Rupees Only';
        $ones = ['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine',
                 'Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen',
                 'Sixteen','Seventeen','Eighteen','Nineteen'];
        $tens = ['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
        $w = function($n) use (&$w, $ones, $tens) {
            if ($n < 20)  return $ones[$n];
            if ($n < 100) return $tens[(int)($n/10)] . ($n%10 ? ' '.$ones[$n%10] : '');
            return $ones[(int)($n/100)] . ' Hundred' . ($n%100 ? ' '.$w($n%100) : '');
        };
        $res = '';
        if ($n >= 10000000) { $res .= $w((int)($n/10000000)).' Crore '; $n %= 10000000; }
        if ($n >= 100000)   { $res .= $w((int)($n/100000)).' Lakh ';   $n %= 100000; }
        if ($n >= 1000)     { $res .= $w((int)($n/1000)).' Thousand '; $n %= 1000; }
        if ($n > 0)         { $res .= $w($n); }
        return trim($res) . ' Rupees Only';
    }
}