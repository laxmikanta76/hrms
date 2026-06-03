<?php
// module directory name
$HmvcConfig['client_billing']["_title"]       = "Client Billing";
$HmvcConfig['client_billing']["_description"] = "Client Service Invoice & Billing Management";

// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['client_billing']['_database'] = true;
$HmvcConfig['client_billing']["_tables"]   = array(
    'cb_company',
    'cb_bank_accounts',
    'cb_clients',
    'cb_services',
    'cb_invoices',
    'cb_invoice_items',
    'cb_payments',
    'cb_invoice_logs',
);