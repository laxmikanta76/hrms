<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Client Service Billing Module — Routes
|--------------------------------------------------------------------------
| Append these routes to: application/config/routes.php
|
| Module controller: client_billing/client_billing
| Base URL prefix:   client-billing/
|--------------------------------------------------------------------------
*/

// ── Dashboard ──────────────────────────────────────────────────────────
$route['client-billing']                            = 'client_billing/client_billing/dashboard';
$route['client-billing/dashboard']                  = 'client_billing/client_billing/dashboard';

// ── Invoices ───────────────────────────────────────────────────────────
$route['client-billing/invoices']                   = 'client_billing/client_billing/invoices';
$route['client-billing/invoices/create']            = 'client_billing/client_billing/create_invoice';
$route['client-billing/invoices/edit/(:num)']       = 'client_billing/client_billing/edit_invoice/$1';
$route['client-billing/invoices/view/(:num)']       = 'client_billing/client_billing/view_invoice/$1';
$route['client-billing/invoices/save']              = 'client_billing/client_billing/save_invoice';
$route['client-billing/invoices/delete/(:num)']     = 'client_billing/client_billing/delete_invoice/$1';
$route['client-billing/invoices/duplicate/(:num)']  = 'client_billing/client_billing/duplicate_invoice/$1';
$route['client-billing/invoices/status/(:num)']     = 'client_billing/client_billing/update_status/$1';
$route['client-billing/invoices/print/(:num)']      = 'client_billing/client_billing/print_invoice/$1';
$route['client-billing/invoices/pdf/(:num)']        = 'client_billing/client_billing/download_pdf/$1';

// ── Clients ────────────────────────────────────────────────────────────
$route['client-billing/clients']                    = 'client_billing/client_billing/clients';
$route['client-billing/clients/add']                = 'client_billing/client_billing/add_client';
$route['client-billing/clients/edit/(:num)']        = 'client_billing/client_billing/edit_client/$1';
$route['client-billing/clients/save']               = 'client_billing/client_billing/save_client';
$route['client-billing/clients/delete/(:num)']      = 'client_billing/client_billing/delete_client/$1';
$route['client-billing/clients/detail/(:num)']      = 'client_billing/client_billing/client_detail/$1';

// ── Service Catalog ────────────────────────────────────────────────────
$route['client-billing/services']                   = 'client_billing/client_billing/services';
$route['client-billing/services/save']              = 'client_billing/client_billing/save_service';
$route['client-billing/services/delete/(:num)']     = 'client_billing/client_billing/delete_service/$1';

// ── Payments ───────────────────────────────────────────────────────────
$route['client-billing/payments']                   = 'client_billing/client_billing/payments';
$route['client-billing/payments/record']            = 'client_billing/client_billing/record_payment';
$route['client-billing/payments/save']              = 'client_billing/client_billing/save_payment';

// ── Reports ────────────────────────────────────────────────────────────
$route['client-billing/reports/gst']                = 'client_billing/client_billing/gst_report';
$route['client-billing/reports/revenue']            = 'client_billing/client_billing/revenue_report';
$route['client-billing/reports/outstanding']        = 'client_billing/client_billing/outstanding_report';
$route['client-billing/reports/services']           = 'client_billing/client_billing/service_report';

// ── Settings ───────────────────────────────────────────────────────────
$route['client-billing/settings']                   = 'client_billing/client_billing/settings';
$route['client-billing/settings/save']              = 'client_billing/client_billing/save_settings';

// ── AJAX Endpoints ─────────────────────────────────────────────────────
$route['client-billing/ajax/client/(:num)']         = 'client_billing/client_billing/ajax_client/$1';
$route['client-billing/ajax/service/(:num)']        = 'client_billing/client_billing/ajax_service/$1';
$route['client-billing/ajax/services']              = 'client_billing/client_billing/ajax_services_json';