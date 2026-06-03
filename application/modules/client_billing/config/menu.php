<?php

// module name
$HmvcMenu["client_billing"] = array(
    // set icon
    "icon" => "<i class='fa fa-file-text-o'></i>",

    // Dashboard
    "cb_dashboard" => array(
        "controller" => "Client_billing",
        "method"     => "dashboard",
        "permission" => "read"
    ),

    // Invoices
    "cb_invoices" => array(
        "controller" => "Client_billing",
        "method"     => "invoices",
        "permission" => "read"
    ),

    // Create Invoice
    "cb_create_invoice" => array(
        "controller" => "Client_billing",
        "method"     => "create_invoice",
        "permission" => "create"
    ),

    // Clients
    "cb_clients" => array(
        "controller" => "Client_billing",
        "method"     => "clients",
        "permission" => "read"
    ),

    // Service Catalog
    "cb_services" => array(
        "controller" => "Client_billing",
        "method"     => "services",
        "permission" => "read"
    ),

    // Payments
    "cb_payments" => array(
        "controller" => "Client_billing",
        "method"     => "payments",
        "permission" => "read"
    ),

    // GST Report
    "cb_gst_report" => array(
        "controller" => "Client_billing",
        "method"     => "gst_report",
        "permission" => "read"
    ),

    // Revenue Report
    "cb_revenue_report" => array(
        "controller" => "Client_billing",
        "method"     => "revenue_report",
        "permission" => "read"
    ),

    // Outstanding
    "cb_outstanding" => array(
        "controller" => "Client_billing",
        "method"     => "outstanding_report",
        "permission" => "read"
    ),

    // Settings
    "cb_settings" => array(
        "controller" => "Client_billing",
        "method"     => "settings",
        "permission" => "read"
    ),

);