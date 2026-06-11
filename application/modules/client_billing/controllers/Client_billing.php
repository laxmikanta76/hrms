<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Client_billing Controller
 * URL pattern: client_billing/Client_billing/method
 */
class Client_billing extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->load->model(array('Client_billing_model'));
        $this->load->helper(array('url', 'form'));
        if (!$this->session->userdata('isLogIn'))
            redirect('login');
        // Auto-mark overdue invoices
        $this->Client_billing_model->mark_overdue();
    }

    // ── Dashboard ─────────────────────────────────────────────
    public function dashboard() {
        $this->permission->module('client_billing', 'read')->redirect();
        $data['title']   = display('cb_dashboard');
        $data['module']  = 'client_billing';
        $data['page']    = 'dashboard/index';
        $data['stats']   = $this->Client_billing_model->dashboard_stats();
        echo Modules::run('template/layout', $data);
    }

    // ── Invoice List ──────────────────────────────────────────
    public function invoices() {
        $this->permission->module('client_billing', 'read')->redirect();
        $f = [
            'status'    => $this->input->get('status'),
            'client_id' => $this->input->get('client_id'),
            'date_from' => $this->input->get('date_from'),
            'date_to'   => $this->input->get('date_to'),
            'search'    => $this->input->get('search'),
        ];
        $page     = max(1, (int)$this->input->get('page'));
        $per_page = 20;
        $data['title']        = display('cb_invoices');
        $data['module']       = 'client_billing';
        $data['page']         = 'invoice/list';
        $data['invoices']     = $this->Client_billing_model->get_invoices($f, $per_page, ($page - 1) * $per_page);
        $data['total']        = $this->Client_billing_model->count_invoices($f);
        $data['filters']      = $f;
        $data['clients']      = $this->Client_billing_model->get_clients(true);
        $data['per_page']     = $per_page;
        $data['current_page'] = $page;
        echo Modules::run('template/layout', $data);
    }

    // ── Create Invoice ────────────────────────────────────────
    public function create_invoice() {
          echo "Step 1<br>";
        $this->permission->module('client_billing', 'create')->redirect();
        $data['title']       = display('cb_create_invoice');
         echo "Step 2<br>";
        $data['module']      = 'client_billing';
        $data['page']        = 'invoice/form';
        $data['invoice']     = null;
        $data['items']       = [];
        $data['clients']     = $this->Client_billing_model->get_clients(true);
         echo "Step 3<br>";
        $data['services']    = $this->Client_billing_model->get_services();
        echo "Step 4<br>";
        $data['banks']       = $this->Client_billing_model->get_banks();
        $data['next_number'] = $this->Client_billing_model->next_invoice_number();
        $data['preload_client'] = $this->input->get('client_id');
         echo "Step 5<br>";
        echo Modules::run('template/layout', $data);
    }

    // ── Edit Invoice ──────────────────────────────────────────
    public function edit_invoice($id = 0) {
        $this->permission->module('client_billing', 'read')->redirect();
        $inv = $this->Client_billing_model->get_invoice_full($id);
        if (!$inv) {
            $this->session->set_flashdata('error', 'Invoice not found.');
            redirect('client_billing/Client_billing/invoices');
        }
        $data['title']       = 'Edit Invoice #' . $inv->invoice_number;
        $data['module']      = 'client_billing';
        $data['page']        = 'invoice/form';
        $data['invoice']     = $inv;
        $data['items']       = $inv->items;
        $data['clients']     = $this->Client_billing_model->get_clients(true);
        $data['services']    = $this->Client_billing_model->get_services();
        $data['banks']       = $this->Client_billing_model->get_banks();
        $data['next_number'] = $inv->invoice_number;
        $data['preload_client'] = null;
        echo Modules::run('template/layout', $data);
    }

    // ── Save Invoice (POST) ───────────────────────────────────
    public function save_invoice() {
        if ($this->input->method() !== 'post')
            redirect('client_billing/Client_billing/invoices');

        $id = (int)$this->input->post('invoice_id');
        $d  = [
            'invoice_number'   => $this->input->post('invoice_number'),
            'client_id'        => (int)$this->input->post('client_id'),
            'bank_account_id'  => $this->input->post('bank_account_id') ?: null,
            'invoice_type'     => $this->input->post('invoice_type') ?: 'tax_invoice',
            'invoice_date'     => $this->input->post('invoice_date'),
            'due_date'         => $this->input->post('due_date') ?: null,
            'payment_terms'    => $this->input->post('payment_terms'),
            'status'           => $this->input->post('status') ?: 'draft',
            'mode_of_payment'  => $this->input->post('mode_of_payment'),
            'reference_number' => $this->input->post('reference_number'),
            'po_number'        => $this->input->post('po_number'),
            'dispatch_through' => $this->input->post('dispatch_through'),
            'destination'      => $this->input->post('destination'),
            'delivery_note'    => $this->input->post('delivery_note'),
            'place_of_supply'  => $this->input->post('place_of_supply'),
            'is_igst'          => $this->input->post('is_igst') ? 1 : 0,
            'subtotal'         => (float)$this->input->post('subtotal'),
            'total_discount'   => (float)$this->input->post('total_discount'),
            'taxable_amount'   => (float)$this->input->post('taxable_amount'),
            'cgst_amount'      => (float)$this->input->post('cgst_amount'),
            'sgst_amount'      => (float)$this->input->post('sgst_amount'),
            'igst_amount'      => (float)$this->input->post('igst_amount'),
            'total_tax'        => (float)$this->input->post('total_tax'),
            'round_off'        => (float)$this->input->post('round_off'),
            'grand_total'      => (float)$this->input->post('grand_total'),
            'balance_due'      => (float)$this->input->post('grand_total'),
            'notes'            => $this->input->post('notes'),
            'terms_conditions' => $this->input->post('terms_conditions'),
            'internal_notes'   => $this->input->post('internal_notes'),
            'created_by'       => $this->session->userdata('fullname'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ];
        $d['amount_in_words'] = $this->Client_billing_model->amount_in_words($d['grand_total']);
        if (!$id) $d['created_at'] = date('Y-m-d H:i:s');

        // Build items
        $descs = $this->input->post('item_description') ?: [];
        $items = [];
        foreach ($descs as $i => $desc) {
            if (empty(trim($desc))) continue;
            $qty     = (float)($this->input->post('quantity')[$i]      ?? 1);
            $rate    = (float)($this->input->post('rate')[$i]          ?? 0);
            $disc    = (float)($this->input->post('discount')[$i]      ?? 0);
            $dtype   = $this->input->post('discount_type')[$i]         ?? 'flat';
            $discAmt = $dtype === 'percent' ? ($qty * $rate * $disc / 100) : $disc;
            $taxable = ($qty * $rate) - $discAmt;
            $cr      = (float)($this->input->post('cgst_rate')[$i]     ?? 0);
            $sr      = (float)($this->input->post('sgst_rate')[$i]     ?? 0);
            $ir      = (float)($this->input->post('igst_rate')[$i]     ?? 0);
            $items[] = [
                'service_id'       => $this->input->post('service_id')[$i] ?? null ?: null,
                'sl_no'            => $i + 1,
                'item_description' => $desc,
                'hsn_sac_code'     => $this->input->post('hsn_sac_code')[$i] ?? '',
                'quantity'         => $qty,
                'unit'             => $this->input->post('unit')[$i]         ?? 'Nos',
                'rate'             => $rate,
                'discount_type'    => $dtype,
                'discount'         => $disc,
                'discount_amount'  => round($discAmt, 2),
                'taxable_amount'   => round($taxable, 2),
                'cgst_rate'        => $cr, 'cgst_amount' => round($taxable * $cr / 100, 2),
                'sgst_rate'        => $sr, 'sgst_amount' => round($taxable * $sr / 100, 2),
                'igst_rate'        => $ir, 'igst_amount' => round($taxable * $ir / 100, 2),
                'total_amount'     => round($taxable * (1 + ($cr + $sr + $ir) / 100), 2),
            ];
        }

        if ($id) {
            $ok = $this->Client_billing_model->update_invoice($id, $d, $items);
        } else {
            $new_id = $this->Client_billing_model->create_invoice($d, $items);
            $ok     = $new_id !== false;
            $id     = $new_id;
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => $ok, 'invoice_id' => $id]);
            return;
        }
        $this->session->set_flashdata($ok ? 'message' : 'error', $ok ? 'Invoice saved successfully.' : 'Save failed.');
        redirect('client_billing/Client_billing/view_invoice/' . $id);
    }

    // ── View Invoice ──────────────────────────────────────────
    public function view_invoice($id = 0) {
        $this->permission->module('client_billing', 'read')->redirect();
        $inv = $this->Client_billing_model->get_invoice_full($id);
        if (!$inv) {
            $this->session->set_flashdata('error', 'Not found.');
            redirect('client_billing/Client_billing/invoices');
        }
        $data['title']   = 'Invoice #' . $inv->invoice_number;
        $data['module']  = 'client_billing';
        $data['page']    = 'invoice/view';
        $data['invoice'] = $inv;
        echo Modules::run('template/layout', $data);
    }

    // ── Delete Invoice ────────────────────────────────────────
    public function delete_invoice($id = 0) {
        $this->permission->module('client_billing', 'delete')->redirect();
        $this->Client_billing_model->delete_invoice($id);
        $this->session->set_flashdata('message', 'Invoice deleted.');
        redirect('client_billing/Client_billing/invoices');
    }

    // ── Duplicate Invoice ─────────────────────────────────────
    public function duplicate_invoice($id = 0) {
        $new_id = $this->Client_billing_model->duplicate_invoice($id, $this->session->userdata('fullname'));
        $this->session->set_flashdata($new_id ? 'message' : 'error', $new_id ? 'Invoice duplicated as draft.' : 'Duplication failed.');
        if ($new_id) redirect('client_billing/Client_billing/edit_invoice/' . $new_id);
        else         redirect('client_billing/Client_billing/invoices');
    }

    // ── Update Status ─────────────────────────────────────────
    public function update_status($id = 0) {
        $status = $this->input->post('status');
        $ok     = $this->Client_billing_model->update_status($id, $status, $this->session->userdata('fullname'));
        if ($this->input->is_ajax_request()) { echo json_encode(['success' => $ok]); return; }
        $this->session->set_flashdata($ok ? 'message' : 'error', $ok ? 'Status updated.' : 'Failed.');
        redirect('client_billing/Client_billing/view_invoice/' . $id);
    }

    // ── Print Invoice (A4 HTML) ───────────────────────────────
    public function print_invoice($id = 0) {
        $inv = $this->Client_billing_model->get_invoice_full($id);
        if (!$inv) show_404();
        $this->load->view('client_billing/pdf/invoice_print', ['invoice' => $inv]);
    }

    // ── Download PDF ──────────────────────────────────────────
    public function download_pdf($id = 0) {
        $inv = $this->Client_billing_model->get_invoice_full($id);
        if (!$inv) show_404();
        if (class_exists('Mpdf\Mpdf')) {
            $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_left' => 8, 'margin_right' => 8, 'margin_top' => 8, 'margin_bottom' => 8]);
            $html = $this->load->view('client_billing/pdf/invoice_print', ['invoice' => $inv], true);
            $mpdf->WriteHTML($html);
            $mpdf->Output('Invoice_' . $inv->invoice_number . '.pdf', 'D');
        } else {
            $this->load->view('client_billing/pdf/invoice_print', ['invoice' => $inv]);
        }
    }

    // ── Clients ────────────────────────────────────────────────
    public function clients() {
        $this->permission->module('client_billing', 'read')->redirect();
        $data['title']   = display('cb_clients');
        $data['module']  = 'client_billing';
        $data['page']    = 'client/list';
        $data['clients'] = $this->Client_billing_model->get_clients();
        echo Modules::run('template/layout', $data);
    }

    public function add_client() {
        $this->permission->module('client_billing', 'create')->redirect();
        $data['title']  = 'Add Client';
        $data['module'] = 'client_billing';
        $data['page']   = 'client/form';
        $data['client'] = null;
        echo Modules::run('template/layout', $data);
    }

    public function edit_client($id = 0) {
        $data['title']  = 'Edit Client';
        $data['module'] = 'client_billing';
        $data['page']   = 'client/form';
        $data['client'] = $this->Client_billing_model->get_client($id);
        echo Modules::run('template/layout', $data);
    }

    public function save_client() {
        $id = (int)$this->input->post('client_id');
        $d  = [
            'client_code'      => $this->input->post('client_code'),
            'company_name'     => $this->input->post('company_name'),
            'contact_person'   => $this->input->post('contact_person'),
            'gstin'            => strtoupper($this->input->post('gstin')),
            'pan'              => strtoupper($this->input->post('pan')),
            'email'            => $this->input->post('email'),
            'phone'            => $this->input->post('phone'),
            'mobile'           => $this->input->post('mobile'),
            'billing_address'  => $this->input->post('billing_address'),
            'billing_city'     => $this->input->post('billing_city'),
            'billing_state'    => $this->input->post('billing_state'),
            'billing_pincode'  => $this->input->post('billing_pincode'),
            'shipping_address' => $this->input->post('shipping_address'),
            'shipping_city'    => $this->input->post('shipping_city'),
            'shipping_state'   => $this->input->post('shipping_state'),
            'shipping_pincode' => $this->input->post('shipping_pincode'),
            'payment_terms'    => $this->input->post('payment_terms'),
            'credit_limit'     => (float)$this->input->post('credit_limit'),
            'notes'            => $this->input->post('notes'),
            'created_by'       => $this->session->userdata('fullname'),
        ];
        $cid = $this->Client_billing_model->save_client($d, $id ?: null);
        if ($this->input->is_ajax_request()) { echo json_encode(['success' => true, 'client_id' => $cid]); return; }
        $this->session->set_flashdata('message', 'Client saved.');
        redirect('client_billing/Client_billing/clients');
    }

    public function delete_client($id = 0) {
        $this->Client_billing_model->delete_client($id);
        $this->session->set_flashdata('message', 'Client deleted.');
        redirect('client_billing/Client_billing/clients');
    }

    public function client_detail($id = 0) {
        $data['title']    = 'Client Profile';
        $data['module']   = 'client_billing';
        $data['page']     = 'client/detail';
        $data['client']   = $this->Client_billing_model->get_client($id);
        $data['stats']    = $this->Client_billing_model->client_stats($id);
        $data['invoices'] = $this->Client_billing_model->get_invoices(['client_id' => $id], 20, 0);
        $data['payments'] = $this->Client_billing_model->get_payments(['client_id' => $id], 20, 0);
        echo Modules::run('template/layout', $data);
    }

    // ── Services ────────────────────────────────────────────────
    public function services() {
        $this->permission->module('client_billing', 'read')->redirect();
        $data['title']    = display('cb_services');
        $data['module']   = 'client_billing';
        $data['page']     = 'service/list';
        $data['services'] = $this->Client_billing_model->get_services(false);
        echo Modules::run('template/layout', $data);
    }

    public function save_service() {
        $id = (int)$this->input->post('service_id');
        $d  = [
            'name'         => $this->input->post('name'),
            'description'  => $this->input->post('description'),
            'hsn_sac'      => $this->input->post('hsn_sac'),
            'unit'         => $this->input->post('unit'),
            'default_rate' => (float)$this->input->post('default_rate'),
            'cgst_rate'    => (float)$this->input->post('cgst_rate'),
            'sgst_rate'    => (float)$this->input->post('sgst_rate'),
            'igst_rate'    => (float)$this->input->post('igst_rate'),
            'category'     => $this->input->post('category'),
            'is_active'    => 1,
        ];
        $this->Client_billing_model->save_service($d, $id ?: null);
        $this->session->set_flashdata('message', 'Service saved.');
        redirect('client_billing/Client_billing/services');
    }

    public function delete_service($id = 0) {
        $this->Client_billing_model->delete_service($id);
        $this->session->set_flashdata('message', 'Service deactivated.');
        redirect('client_billing/Client_billing/services');
    }

    // ── Payments ────────────────────────────────────────────────
    public function payments() {
        $this->permission->module('client_billing', 'read')->redirect();
        $f = [
            'client_id' => $this->input->get('client_id'),
            'date_from' => $this->input->get('date_from'),
            'date_to'   => $this->input->get('date_to'),
        ];
        $data['title']    = display('cb_payments');
        $data['module']   = 'client_billing';
        $data['page']     = 'payment/list';
        $data['payments'] = $this->Client_billing_model->get_payments($f);
        $data['clients']  = $this->Client_billing_model->get_clients(true);
        $data['filters']  = $f;
        echo Modules::run('template/layout', $data);
    }

    public function record_payment() {
        $invoice_id = $this->input->get('invoice_id');
        $inv = $invoice_id ? $this->Client_billing_model->get_invoice($invoice_id) : null;
        $data['title']    = 'Record Payment';
        $data['module']   = 'client_billing';
        $data['page']     = 'payment/form';
        $data['invoice']  = $inv;
        $data['invoices'] = $this->Client_billing_model->get_invoices(['status' => 'unpaid'], 100, 0);
        $data['banks']    = $this->Client_billing_model->get_banks();
        echo Modules::run('template/layout', $data);
    }

    public function save_payment() {
        $d = [
            'invoice_id'      => (int)$this->input->post('invoice_id'),
            'client_id'       => (int)$this->input->post('client_id'),
            'amount'          => (float)$this->input->post('amount'),
            'payment_date'    => $this->input->post('payment_date'),
            'payment_method'  => $this->input->post('payment_method'),
            'transaction_ref' => $this->input->post('transaction_ref'),
            'bank_account_id' => $this->input->post('bank_account_id') ?: null,
            'notes'           => $this->input->post('notes'),
            'status'          => 'success',
            'created_by'      => $this->session->userdata('fullname'),
        ];
        $pid = $this->Client_billing_model->record_payment($d);
        if ($this->input->is_ajax_request()) { echo json_encode(['success' => $pid !== false, 'id' => $pid]); return; }
        $this->session->set_flashdata($pid ? 'message' : 'error', $pid ? 'Payment recorded.' : 'Failed.');
        redirect('client_billing/Client_billing/payments');
    }

    // ── Reports ─────────────────────────────────────────────────
    public function gst_report() {
        $this->permission->module('client_billing', 'read')->redirect();
        $year = $this->input->get('year') ?: date('Y');
        $data['title']    = display('cb_gst_report');
        $data['module']   = 'client_billing';
        $data['page']     = 'reports/gst';
        $data['gst_data'] = $this->Client_billing_model->gst_report($year);
        $data['year']     = $year;
        echo Modules::run('template/layout', $data);
    }

    public function revenue_report() {
        $this->permission->module('client_billing', 'read')->redirect();
        $data['title']   = display('cb_revenue_report');
        $data['module']  = 'client_billing';
        $data['page']    = 'reports/revenue';
        $data['monthly'] = $this->Client_billing_model->monthly_revenue(12);
        echo Modules::run('template/layout', $data);
    }

    public function outstanding_report() {
        $this->permission->module('client_billing', 'read')->redirect();
        $data['title']    = display('cb_outstanding');
        $data['module']   = 'client_billing';
        $data['page']     = 'reports/outstanding';
        $data['invoices'] = $this->Client_billing_model->get_invoices(['status' => 'unpaid'],  100, 0);
        $data['overdue']  = $this->Client_billing_model->get_invoices(['status' => 'overdue'], 100, 0);
        echo Modules::run('template/layout', $data);
    }

    public function service_report() {
        $year = $this->input->get('year') ?: date('Y');
        $data['title']  = 'Service Revenue';
        $data['module'] = 'client_billing';
        $data['page']   = 'reports/services';
        $data['data']   = $this->Client_billing_model->service_wise_report($year);
        $data['year']   = $year;
        echo Modules::run('template/layout', $data);
    }

    // ── Settings ─────────────────────────────────────────────────
    public function settings() {
        $this->permission->module('client_billing', 'read')->redirect();
        $data['title']    = display('cb_settings');
        $data['module']   = 'client_billing';
        $data['page']     = 'dashboard/settings';
        $data['company']  = $this->Client_billing_model->get_company();
        $data['banks']    = $this->Client_billing_model->get_banks();
        $data['services'] = $this->Client_billing_model->get_services(false);
        echo Modules::run('template/layout', $data);
    }

    public function save_settings() {
        $d = [
            'name'           => $this->input->post('name'),
            'address'        => $this->input->post('address'),
            'city'           => $this->input->post('city'),
            'state'          => $this->input->post('state'),
            'pincode'        => $this->input->post('pincode'),
            'gstin'          => $this->input->post('gstin'),
            'pan'            => $this->input->post('pan'),
            'phone'          => $this->input->post('phone'),
            'email'          => $this->input->post('email'),
            'website'        => $this->input->post('website'),
            'invoice_prefix' => $this->input->post('invoice_prefix'),
            'upi_id'         => $this->input->post('upi_id'),
            'terms'          => $this->input->post('terms'),
            'footer_note'    => $this->input->post('footer_note'),
        ];
        $this->Client_billing_model->update_company($d);
        $this->session->set_flashdata('message', 'Settings saved.');
        redirect('client_billing/Client_billing/settings');
    }

    // ── AJAX ────────────────────────────────────────────────────
    public function ajax_client($id = 0) {
        echo json_encode($this->Client_billing_model->get_client($id));
    }

    public function ajax_service($id = 0) {
        echo json_encode($this->Client_billing_model->get_service($id));
    }

    public function ajax_services_json() {
        echo json_encode($this->Client_billing_model->get_services());
    }
}