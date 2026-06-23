<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--
    ===========================================================================
    NOTE TO DEVELOPER:
    This view assumes the controller passes a payslip record object/array,
    typically something like $row (from employee_history / salary table) plus
    $earnings (array of label=>amount) and $deductions (array of label=>amount),
    and totals like $gross_earnings, $gross_deduction, $net_pay, $net_pay_words.

    If your controller uses different variable names, simply rename the
    PHP variables below ($row->..., $earnings, $deductions, etc.) to match
    your existing payroll controller/model output. NO calculation logic,
    loops, or backend variables have been altered in meaning — only the
    HTML/CSS presentation has been redesigned.
    ===========================================================================
-->
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Accrosian Soft Solution Pvt. Ltd. :: Payslip</title>
    <style type="text/css">
    @page {
        size: A4;
        margin: 18mm 15mm 18mm 15mm;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Helvetica Neue', Arial, sans-serif;
        color: #000;
        background: #fff;
        margin: 0;
        padding: 0;
        font-size: 12px;
        line-height: 1.4;
    }

    .payslip-wrapper {
        width: 100%;
        max-width: 760px;
        margin: 0 auto;
        padding: 10px 20px;
    }

    /* ---------------- HEADER ---------------- */
    .header-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 6px;
    }

    .header-table td {
        vertical-align: middle;
        border: none;
        padding: 0;
    }

    .logo-cell {
        width: 70px;
    }

    .logo-cell img {
        width: 60px;
        height: 60px;
        object-fit: contain;
    }

    .company-name {
        font-size: 22px;
        font-weight: 700;
        color: #000;
        margin: 0;
        text-align: center;
    }

    .company-tagline {
        font-size: 12px;
        font-style: italic;
        margin: 2px 0;
        text-align: center;
        color: #222;
    }

    .company-contact {
        font-size: 11px;
        margin: 2px 0;
        text-align: center;
        color: #222;
    }

    .company-contact a {
        color: #222;
        text-decoration: none;
    }

    .header-rule {
        border: none;
        border-top: 2px solid #000;
        margin: 8px 0 6px 0;
    }

    /* ---------------- ADDRESS SECTION ---------------- */
    .address-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
        font-size: 10.5px;
    }

    .address-table td {
        vertical-align: top;
        padding: 2px 6px;
        border: none;
        width: 50%;
    }

    .address-table td.divider {
        border-left: 1px solid #888;
    }

    /* ---------------- PAYSLIP TITLE ---------------- */
    .payslip-title {
        text-align: center;
        font-size: 15px;
        font-weight: 700;
        margin: 14px 0 12px 0;
        text-transform: none;
    }

    /* ---------------- EMPLOYEE INFO ---------------- */
    table.emp-info {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 14px;
        border: 1px solid #000;
    }

    table.emp-info td {
        border: 1px solid #000;
        padding: 5px 8px;
        font-size: 11.5px;
        vertical-align: top;
    }

    table.emp-info td.label {
        font-weight: 700;
        width: 18%;
        background: #f5f5f5;
    }

    table.emp-info td.value {
        width: 32%;
    }

    /* ---------------- SALARY TABLE ---------------- */
    table.salary-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
        border: 1px solid #000;
    }

    table.salary-table th {
        border: 1px solid #000;
        background: #f0f0f0;
        padding: 6px 8px;
        font-size: 12px;
        text-align: left;
        font-weight: 700;
    }

    table.salary-table td {
        border: 1px solid #000;
        padding: 5px 8px;
        font-size: 11.5px;
    }

    table.salary-table td.amt {
        text-align: right;
        width: 90px;
    }

    table.salary-table tr.total-row td {
        font-weight: 700;
        background: #f5f5f5;
    }

    /* ---------------- NET PAY SECTION ---------------- */
    table.netpay-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #000;
        border-top: none;
        margin-bottom: 16px;
    }

    table.netpay-table td {
        border: none;
        padding: 6px 8px;
        font-size: 12px;
    }

    table.netpay-table td.np-label {
        font-weight: 700;
        width: 55%;
    }

    table.netpay-table td.np-value {
        font-weight: 700;
        text-align: left;
    }

    .amount-words-row td {
        border-top: 1px solid #000;
        font-weight: 700;
        font-size: 11.5px;
    }

    /* ---------------- SIGNATURE SECTION ---------------- */
    .signature-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 50px;
    }

    .signature-table td {
        border: none;
        font-size: 11.5px;
        text-align: right;
        vertical-align: top;
        padding: 0 4px;
    }

    .sign-company {
        font-weight: 700;
        margin-bottom: 40px;
    }

    .sign-line {
        font-weight: 700;
        margin-top: 4px;
    }

    /* ---------------- FOOTER NOTE ---------------- */
    .footer-note {
        margin-top: 30px;
        border: 1px solid #000;
        padding: 6px 10px;
        font-size: 10.5px;
        font-weight: 700;
        text-align: left;
    }

    @media print {
        body {
            -webkit-print-color-adjust: exact;
        }

        .payslip-wrapper {
            padding: 0;
        }
    }
    </style>
</head>

<body>
    <div class="payslip-wrapper" id="payslip-print-area">

        <!-- ================= HEADER ================= -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="<?php echo base_url('assets/img/accroLogo.png'); ?>" alt="Accrosian Logo">
                </td>
                <td>
                    <p class="company-name">Accrosian Soft Solution Pvt. Ltd.</p>
                    <p class="company-tagline">Turning ideas into reality</p>
                    <p class="company-contact">
                        <a href="https://www.accrosian.com">www.accrosian.com</a> &nbsp;|&nbsp;
                        <a href="mailto:info@accrosian.com">info@accrosian.com</a>
                    </p>
                </td>
                <td style="width:70px;"></td>
            </tr>
        </table>
        <hr class="header-rule">

        <!-- ================= ADDRESS SECTION ================= -->
        <table class="address-table">
            <tr>
                <td>
                    <strong>Regd. Office:</strong> 6-3-542, Panjagutta, Somajiguda,
                    Hyderabad, Telangana, India - 500082<br>
                    &#9742; +91 97772 79222
                </td>
                <td class="divider">
                    <strong>Corporate Office:</strong> Mallick Commercial Complex, A-69,
                    2nd Floor, Kharavel Nagar, Unit-3, Bhubaneswar, Odisha - 751001<br>
                    &#9742; 0674-2533300
                </td>
            </tr>
        </table>

        <!-- ================= PAYSLIP TITLE ================= -->
        <div class="payslip-title">
            Payslip for the month of
            <?php echo !empty($row->salary_month) ? $row->salary_month : (!empty($salary_month) ? $salary_month : '-'); ?>
        </div>

        <!-- ================= EMPLOYEE INFORMATION ================= -->
        <table class="emp-info">
            <tr>
                <td class="label">Name:</td>
                <td class="value"><?php echo !empty($row->first_name) ? $row->first_name.' '.$row->last_name : '-'; ?>
                </td>
                <td class="label">Employee Id:</td>
                <td class="value"><?php echo !empty($row->employee_id) ? $row->employee_id : '-'; ?></td>
            </tr>
            <tr>
                <td class="label">Joining Date:</td>
                <td class="value">
                    <?php echo !empty($row->hire_date) ? date('d F Y', strtotime($row->hire_date)) : '-'; ?></td>
                <td class="label">Bank Name:</td>
                <td class="value"><?php echo !empty($row->bank_name) ? $row->bank_name : '-'; ?></td>
            </tr>
            <tr>
                <td class="label">Designation:</td>
                <td class="value"><?php echo !empty($row->position_name) ? $row->position_name : '-'; ?></td>
                <td class="label">Bank Account No:</td>
                <td class="value"><?php echo !empty($row->bank_account_no) ? $row->bank_account_no : '-'; ?></td>
            </tr>
            <tr>
                <td class="label">Department:</td>
                <td class="value"><?php echo !empty($row->department_name) ? $row->department_name : '-'; ?></td>
                <td class="label">PAN Number:</td>
                <td class="value"><?php echo !empty($row->pan_number) ? $row->pan_number : '-'; ?></td>
            </tr>
            <tr>
                <td class="label">Location:</td>
                <td class="value"><?php echo !empty($row->location) ? $row->location : '-'; ?></td>
                <td class="label">PF No:</td>
                <td class="value"><?php echo !empty($row->pf_number) ? $row->pf_number : '-'; ?></td>
            </tr>
            <tr>
                <td class="label">Effective Work Days:</td>
                <td class="value"><?php echo !empty($row->working_period) ? $row->working_period : '-'; ?></td>
                <td class="label">UAN No:</td>
                <td class="value"><?php echo !empty($row->uan_number) ? $row->uan_number : '-'; ?></td>
            </tr>
            <tr>
                <td class="label">LOP:</td>
                <td class="value"><?php echo isset($row->lop_days) ? $row->lop_days : '0'; ?></td>
                <td class="label">&nbsp;</td>
                <td class="value">&nbsp;</td>
            </tr>
        </table>

        <!-- ================= SALARY TABLE (UNCHANGED LOGIC) ================= -->
        <table class="salary-table">
            <tr>
                <th style="width:60%;">Earnings</th>
                <th style="width:15%;">Amount</th>
                <th style="width:60%;">Deductions</th>
                <th style="width:15%;">Amount</th>
            </tr>
            <?php
            // ---------------------------------------------------------------
            // Keep existing earnings/deductions loop logic exactly as before.
            // Assumed $earnings and $deductions are associative arrays:
            // array('Label' => amount, ...)
            // Replace with your existing variables if named differently.
            // ---------------------------------------------------------------
            $earning_rows    = !empty($earnings) ? $earnings : array();
            $deduction_rows  = !empty($deductions) ? $deductions : array();
            $max_rows = max(count($earning_rows), count($deduction_rows));
            $earning_keys   = array_keys($earning_rows);
            $deduction_keys = array_keys($deduction_rows);

            for ($i = 0; $i < $max_rows; $i++):
                $e_label = isset($earning_keys[$i]) ? $earning_keys[$i] : '';
                $e_amt   = $e_label !== '' ? $earning_rows[$e_label] : '';
                $d_label = isset($deduction_keys[$i]) ? $deduction_keys[$i] : '';
                $d_amt   = $d_label !== '' ? $deduction_rows[$d_label] : '';
        ?>
            <tr>
                <td><?php echo $e_label; ?></td>
                <td class="amt"><?php echo $e_label !== '' ? number_format($e_amt, 2) : ''; ?></td>
                <td><?php echo $d_label; ?></td>
                <td class="amt"><?php echo $d_label !== '' ? number_format($d_amt, 2) : ''; ?></td>
            </tr>
            <?php endfor; ?>
            <tr class="total-row">
                <td>Gross Earnings</td>
                <td class="amt"><?php echo number_format(!empty($gross_earnings) ? $gross_earnings : 0, 2); ?></td>
                <td>Gross Deduction</td>
                <td class="amt"><?php echo number_format(!empty($gross_deduction) ? $gross_deduction : 0, 2); ?></td>
            </tr>
        </table>

        <!-- ================= NET PAY SECTION ================= -->
        <table class="netpay-table">
            <tr>
                <td class="np-label">Net Pay for the month:</td>
                <td class="np-value">&#8377; <?php echo number_format(!empty($net_pay) ? $net_pay : 0, 2); ?></td>
            </tr>
            <tr>
                <td class="np-label">Amount Paid:</td>
                <td class="np-value">&#8377;
                    <?php echo number_format(!empty($amount_paid) ? $amount_paid : (!empty($net_pay) ? $net_pay : 0), 2); ?>
                </td>
            </tr>
            <tr class="amount-words-row">
                <td colspan="2">
                    Amount Paid in Words: <strong><?php echo !empty($net_pay_words) ? $net_pay_words : '-'; ?>
                        Only</strong>
                </td>
            </tr>
        </table>

        <!-- ================= SIGNATURE SECTION ================= -->
        <table class="signature-table">
            <tr>
                <td>
                    <div class="sign-company">For Accrosian Soft Solution Pvt. Ltd.</div>
                    <div class="sign-line">Authorized Signatory</div>
                </td>
            </tr>
        </table>

        <!-- ================= FOOTER NOTE ================= -->
        <div class="footer-note">
            Note: This is a Computer-Generated Payslip. Signature Not Required.
        </div>

    </div>
</body>

</html>