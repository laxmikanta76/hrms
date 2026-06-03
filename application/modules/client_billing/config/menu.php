<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Client Billing Module — Sidebar Menu Config
|--------------------------------------------------------------------------
| Paste this into your HRMS main menu config file,
| or include it as a separate module menu.
|--------------------------------------------------------------------------
*/

echo "MENU LOADED";
exit;

$HmvcMenu["client_billing"] = [
    "icon" => "<i class='ti-receipt'></i>",

    // Dashboard
    "dashboard" => [
        "controller" => "client_billing",
        "method"     => "dashboard",
        "permission" => "read",
    ],

    // Invoices group
    "invoices" => [
        "create_invoice" => [
            "controller" => "client_billing",
            "method"     => "create_invoice",
            "permission" => "create",
        ],
        "invoice_list" => [
            "controller" => "client_billing",
            "method"     => "invoices",
            "permission" => "read",
        ],
    ],

    // Clients
    "clients" => [
        "client_list" => [
            "controller" => "client_billing",
            "method"     => "clients",
            "permission" => "read",
        ],
        "add_client" => [
            "controller" => "client_billing",
            "method"     => "add_client",
            "permission" => "create",
        ],
    ],

    // Service Catalog
    "services" => [
        "controller" => "client_billing",
        "method"     => "services",
        "permission" => "read",
    ],

    // Payments
    "payments" => [
        "payment_list" => [
            "controller" => "client_billing",
            "method"     => "payments",
            "permission" => "read",
        ],
        "record_payment" => [
            "controller" => "client_billing",
            "method"     => "record_payment",
            "permission" => "create",
        ],
    ],

    // Reports
    "reports" => [
        "gst_report" => [
            "controller" => "client_billing",
            "method"     => "gst_report",
            "permission" => "read",
        ],
        "revenue_report" => [
            "controller" => "client_billing",
            "method"     => "revenue_report",
            "permission" => "read",
        ],
        "outstanding_report" => [
            "controller" => "client_billing",
            "method"     => "outstanding_report",
            "permission" => "read",
        ],
        "service_report" => [
            "controller" => "client_billing",
            "method"     => "service_report",
            "permission" => "read",
        ],
    ],

    // Settings
    "billing_settings" => [
        "controller" => "client_billing",
        "method"     => "settings",
        "permission" => "create",
    ],
];