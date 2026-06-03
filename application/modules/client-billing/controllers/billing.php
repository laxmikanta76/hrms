<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_billing extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode=""');
        $this->load->model('client_billing_model');
        $this->load->helper(['url','form']);
        if (!$this->session->userdata('isLogIn')) redirect('login');
        $this->client_billing_model->mark_overdue();
    }

    // ── Dashboard ─────────────────────────────────────────
    public function dashboard() {
        echo Modules::run('template/layout', [
            'title'  => 'Billing Dashboard',
            'module' => 'client_billing',
            'page'   => 'dashboard/index',
            'stats'  => $this->client_billing_model->dashboard_stats(),
        ]);
    }

    // ── Invoice List ──────────────────────────────────────
    public function invoices() {
        $f = [
            'status'       => $this->input->get('status'),
            'client_id'    => $this->input->get('client_id'),
            'invoice_type' => $this->input->get('invoice_type'),
            'date_from'    => $this->input->get('date_from'),
            'date_to'      => $this->input->get('date_to'),
            'search'       => $this->input->get('search'),
        ];
        $page     = max(1, (int)$this->input->get('page'));
        $per_page = 20;
        echo Modules::run('template/layout', [
            'title'        => 'Invoices',
            'module'       => 'client_billing',
            'page'         => 'invoice/list',
            'invoices'     => $this->client_billing_model->get_invoices($f, $per_page, ($page-1)*$per_page),
            'total'        => $this->client_billing_model->count_invoices($f),
            'filters'      => $f,
            'clients'      => $this->client_billing_model->get_clients(true),
            'per_page'     => $per_page,
            'current_page' => $page,
        ]);
    }

    // ── Create / Edit Invoice Form ────────────────────────
    public function create_invoice() {
        $client_id = $this->input->get('client_id');
        echo Modules::run('template/layout', [
            'title'        => 'Create Invoice',
            'module'       => 'client_billing',
            'page'         => 'invoice/form',
            'invoice'      => null,
            'items'        => [],
            'clients'      => $this->client_billing_model->get_clients(true),
            'services'     => $this->client_billing_model->get_services(),
            'banks'        => $this->client_billing_model->get_banks(),
            'next_number'  => $this->client_billing_model->next_invoice_number(),
            'preload_client' => $client_id,
        ]);
    }

    public function edit_invoice($id) {
        $inv = $this->client_billing_model->get_invoice_full($id);
        if (!$inv) { $this->session->set_flashdata('error','Invoice not found.'); redirect('client_billing/client_billing/invoices'); }
        echo Modules::run('template/layout', [
            'title'       => 'Edit Invoice #'.$inv->invoice_number,
            'module'      => 'client_billing',
            'page'        => 'invoice/form',
            'invoice'     => $inv,
            'items'       => $inv->items,
            'clients'     => $this->client_billing_model->get_clients(true),
            'services'    => $this->client_billing_model->get_services(),
            'banks'       => $this->client_billing_model->get_banks(),
            'next_number' => $inv->invoice_number,
            'preload_client' => null,
        ]);
    }

    public function save_invoice() {
        if ($this->input->method() !== 'post') redirect('client_billing/client_billing/invoices');
        $id = (int)$this->input->post('invoice_id');

        $d = [
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
        $d['amount_in_words'] = $this->client_billing_model->amount_in_words($d['grand_total']);
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
            $ok = $this->client_billing_model->update_invoice($id, $d, $items);
        } else {
            $new_id = $this->client_billing_model->create_invoice($d, $items);
            $ok = $new_id !== false;
            $id = $new_id;
        }

        if ($this->input->is_ajax_request()) { echo json_encode(['success' => $ok, 'invoice_id' => $id]); return; }
        $this->session->set_flashdata($ok ? 'message' : 'error', $ok ? 'Invoice saved successfully.' : 'Save failed.');
        redirect('client_billing/client_billing/view_invoice/'.$id);
    }

    public function view_invoice($id) {
        $inv = $this->client_billing_model->get_invoice_full($id);
        if (!$inv) { $this->session->set_flashdata('error','Not found.'); redirect('client_billing/client_billing/invoices'); }
        echo Modules::run('template/layout', [
            'title'   => 'Invoice #'.$inv->invoice_number,
            'module'  => 'client_billing',
            'page'    => 'invoice/view',
            'invoice' => $inv,
        ]);
    }

    public function delete_invoice($id) {
        $this->client_billing_model->delete_invoice($id);
        $this->session->set_flashdata('message', 'Invoice deleted.');
        redirect('client_billing/client_billing/invoices');
    }

    public function duplicate_invoice($id) {
        $new_id = $this->client_billing_model->duplicate_invoice($id, $this->session->userdata('fullname'));
        $this->session->set_flashdata($new_id ? 'message' : 'error', $new_id ? 'Invoice duplicated as draft.' : 'Duplication failed.');
        if ($new_id) redirect('client_billing/client_billing/edit_invoice/'.$new_id);
        else redirect('client_billing/client_billing/invoices');
    }

    public function update_status($id) {
        $status = $this->input->post('status');
        $ok = $this->client_billing_model->update_status($id, $status, $this->session->userdata('fullname'));
        if ($this->input->is_ajax_request()) { echo json_encode(['success' => $ok]); return; }
        $this->session->set_flashdata($ok ? 'message' : 'error', $ok ? 'Status updated.' : 'Failed.');
        redirect('client_billing/client_billing/view_invoice/'.$id);
    }

    public function print_invoice($id) {
        $inv = $this->client_billing_model->get_invoice_full($id);
        if (!$inv) show_404();
        $this->load->view('client_billing/pdf/invoice_print', ['invoice' => $inv]);
    }

    public function download_pdf($id) {
        $inv = $this->client_billing_model->get_invoice_full($id);
        if (!$inv) show_404();
        if (class_exists('Mpdf\Mpdf')) {
            $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_left' => 8, 'margin_right' => 8, 'margin_top' => 8, 'margin_bottom' => 8]);
            $html = $this->load->view('client_billing/pdf/invoice_print', ['invoice' => $inv], true);
            $mpdf->WriteHTML($html);
            $mpdf->Output('Invoice_'.$inv->invoice_number.'.pdf', 'D');
        } else {
            $this->load->view('client_billing/pdf/invoice_print', ['invoice' => $inv]);
        }
    }

    // ── Clients ────────────────────────────────────────────
    public function clients() {
        echo Modules::run('template/layout', [
            'title'   => 'Clients',
            'module'  => 'client_billing',
            'page'    => 'client/list',
            'clients' => $this->client_billing_model->get_clients(),
        ]);
    }

    public function add_client() {
        echo Modules::run('template/layout', ['title'=>'Add Client','module'=>'client_billing','page'=>'client/form','client'=>null]);
    }

    public function edit_client($id) {
        echo Modules::run('template/layout', ['title'=>'Edit Client','module'=>'client_billing','page'=>'client/form','client'=>$this->client_billing_model->get_client($id)]);
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
        $cid = $this->client_billing_model->save_client($d, $id ?: null);
        if ($this->input->is_ajax_request()) { echo json_encode(['success' => true, 'client_id' => $cid]); return; }
        $this->session->set_flashdata('message', 'Client saved.');
        redirect('client_billing/client_billing/clients');
    }

    public function delete_client($id) {
        $this->client_billing_model->delete_client($id);
        $this->session->set_flashdata('message','Client deleted.');
        redirect('client_billing/client_billing/clients');
    }

    public function client_detail($id) {
        echo Modules::run('template/layout', [
            'title'    => 'Client Profile',
            'module'   => 'client_billing',
            'page'     => 'client/detail',
            'client'   => $this->client_billing_model->get_client($id),
            'stats'    => $this->client_billing_model->client_stats($id),
            'invoices' => $this->client_billing_model->get_invoices(['client_id' => $id], 20, 0),
            'payments' => $this->client_billing_model->get_payments(['client_id' => $id], 20, 0),
        ]);
    }

    // ── Services ────────────────────────────────────────────
    public function services() {
        echo Modules::run('template/layout', [
            'title'    => 'Service Catalog',
            'module'   => 'client_billing',
            'page'     => 'service/list',
            'services' => $this->client_billing_model->get_services(false),
        ]);
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
        $sid = $this->client_billing_model->save_service($d, $id ?: null);
        if ($this->input->is_ajax_request()) { echo json_encode(['success' => true, 'id' => $sid]); return; }
        $this->session->set_flashdata('message','Service saved.');
        redirect('client_billing/client_billing/services');
    }

    public function delete_service($id) {
        $this->client_billing_model->delete_service($id);
        $this->session->set_flashdata('message','Service deactivated.');
        redirect('client_billing/client_billing/services');
    }

    // ── Payments ────────────────────────────────────────────
    public function payments() {
        echo Modules::run('template/layout', [
            'title'    => 'Payments',
            'module'   => 'client_billing',
            'page'     => 'payment/list',
            'payments' => $this->client_billing_model->get_payments(),
            'clients'  => $this->client_billing_model->get_clients(true),
            'filters'  => [],
        ]);
    }

    public function record_payment() {
        $invoice_id = $this->input->get('invoice_id');
        $inv = $invoice_id ? $this->client_billing_model->get_invoice($invoice_id) : null;
        echo Modules::run('template/layout', [
            'title'    => 'Record Payment',
            'module'   => 'client_billing',
            'page'     => 'payment/form',
            'invoice'  => $inv,
            'invoices' => $this->client_billing_model->get_invoices(['status' => 'unpaid'], 100, 0),
            'banks'    => $this->client_billing_model->get_banks(),
        ]);
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
        $pid = $this->client_billing_model->record_payment($d);
        if ($this->input->is_ajax_request()) { echo json_encode(['success' => $pid !== false, 'id' => $pid]); return; }
        $this->session->set_flashdata($pid ? 'message' : 'error', $pid ? 'Payment recorded.' : 'Failed.');
        redirect('client_billing/client_billing/payments');
    }

    // ── Reports ─────────────────────────────────────────────
    public function gst_report() {
        $year = $this->input->get('year') ?: date('Y');
        echo Modules::run('template/layout', ['title'=>'GST Report','module'=>'client_billing','page'=>'reports/gst','gst_data'=>$this->client_billing_model->gst_report($year),'year'=>$year]);
    }

    public function revenue_report() {
        echo Modules::run('template/layout', ['title'=>'Revenue Report','module'=>'client_billing','page'=>'reports/revenue','monthly'=>$this->client_billing_model->monthly_revenue(12)]);
    }

    public function outstanding_report() {
        echo Modules::run('template/layout', [
            'title'    => 'Outstanding',
            'module'   => 'client_billing',
            'page'     => 'reports/outstanding',
            'invoices' => $this->client_billing_model->get_invoices(['status'=>'unpaid'], 100, 0),
            'overdue'  => $this->client_billing_model->get_invoices(['status'=>'overdue'], 100, 0),
        ]);
    }

    public function service_report() {
        $year = $this->input->get('year') ?: date('Y');
        echo Modules::run('template/layout', ['title'=>'Service Revenue','module'=>'client_billing','page'=>'reports/services','data'=>$this->client_billing_model->service_wise_report($year),'year'=>$year]);
    }

    // ── Settings ─────────────────────────────────────────────
    public function settings() {
        echo Modules::run('template/layout', ['title'=>'Settings','module'=>'client_billing','page'=>'dashboard/settings','company'=>$this->client_billing_model->get_company(),'banks'=>$this->client_billing_model->get_banks(),'services'=>$this->client_billing_model->get_services(false)]);
    }

    public function save_settings() {
        $d = ['name'=>$this->input->post('name'),'address'=>$this->input->post('address'),'city'=>$this->input->post('city'),'state'=>$this->input->post('state'),'pincode'=>$this->input->post('pincode'),'gstin'=>$this->input->post('gstin'),'pan'=>$this->input->post('pan'),'phone'=>$this->input->post('phone'),'email'=>$this->input->post('email'),'website'=>$this->input->post('website'),'invoice_prefix'=>$this->input->post('invoice_prefix'),'upi_id'=>$this->input->post('upi_id'),'terms'=>$this->input->post('terms'),'footer_note'=>$this->input->post('footer_note')];
        $this->client_billing_model->update_company($d);
        $this->session->set_flashdata('message','Settings saved.');
        redirect('client_billing/client_billing/settings');
    }

    // ── AJAX ────────────────────────────────────────────────
    public function ajax_client($id) {
        echo json_encode($this->client_billing_model->get_client($id));
    }

    public function ajax_service($id) {
        echo json_encode($this->client_billing_model->get_service($id));
    }

    public function ajax_services_json() {
        echo json_encode($this->client_billing_model->get_services());
    }
}