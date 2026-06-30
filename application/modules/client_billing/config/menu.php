<?php

// module name — MUST match folder name exactly: client_billing
$HmvcMenu["client_billing"] = array(
    // set icon — fa icons work in this HRMS
    "icon" => "<i class='fa fa-file-text-o'></i>",

    // ── Each key MUST match:
    // 1. sec_menu_item.menu_title  in database
    // 2. language.phrase           in database
    // ── "controller" MUST be class name: Client_billing (capital C)
    // ── "method" MUST be the actual function name in controller

    "cb_dashboard" => array(
        "controller" => "Client_billing",
        "method"     => "dashboard",
        "permission" => "read"
    ),

    "cb_invoices" => array(
        "controller" => "Client_billing",
        "method"     => "invoices",
        "permission" => "read"
    ),

    "cb_create_invoice" => array(
        "controller" => "Client_billing",
        "method"     => "create_invoice",
        "permission" => "create"
    ),

    "cb_clients" => array(
        "controller" => "Client_billing",
        "method"     => "clients",
        "permission" => "read"
    ),

    "cb_services" => array(
        "controller" => "Client_billing",
        "method"     => "services",
        "permission" => "read"
    ),

    "cb_payments" => array(
        "controller" => "Client_billing",
        "method"     => "payments",
        "permission" => "read"
    ),

    "cb_gst_report" => array(
        "controller" => "Client_billing",
        "method"     => "gst_report",
        "permission" => "read"
    ),

    "cb_revenue_report" => array(
        "controller" => "Client_billing",
        "method"     => "revenue_report",
        "permission" => "read"
    ),

    "cb_outstanding" => array(
        "controller" => "Client_billing",
        "method"     => "outstanding_report",
        "permission" => "read"
    ),

    "cb_settings" => array(
        "controller" => "Client_billing",
        "method"     => "settings",
        "permission" => "read"
    ),

);
