<style>
@page {
    size: A4;
    margin: 15mm 14mm 15mm 14mm;
}

* {
    box-sizing: border-box;
}

#payslip {
    background: #fff;
    color: #000;
    padding: 10px 20px;
    font-family: 'Helvetica Neue', Arial, sans-serif;
    font-size: 12px;
    line-height: 1.4;
    max-width: 800px;
    margin: 0 auto;
}

/* ---------------- HEADER ---------------- */
#payslip .header-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 6px;
}

#payslip .header-table td {
    vertical-align: middle;
    border: none;
    padding: 0;
}

#payslip .logo-cell {
    width: 80px;
}

#payslip .logo-cell img {
    width: 65px;
    height: 65px;
    object-fit: contain;
}

#payslip .company-name {
    font-size: 24px;
    font-weight: 700;
    color: #000;
    margin: 0;
    text-align: center;
}

#payslip .company-tagline {
    font-size: 12px;
    font-style: italic;
    margin: 2px 0;
    text-align: center;
    color: #222;
}

#payslip .company-contact {
    font-size: 11px;
    margin: 2px 0;
    text-align: center;
    color: #222;
}

#payslip .company-contact a {
    color: #222;
    text-decoration: none;
}

#payslip .header-rule {
    border: none;
    border-top: 2px solid #000;
    margin: 8px 0 10px 0;
}

/* ---------------- ADDRESS SECTION ---------------- */
#payslip .address-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 8px;
    font-size: 10.5px;
}

#payslip .address-table td {
    vertical-align: top;
    padding: 2px 10px;
    border: none;
    width: 50%;
}

#payslip .address-table td.divider {
    border-left: 1px solid #888;
}

/* ---------------- PAYSLIP TITLE ---------------- */
#payslip .payslip-title {
    text-align: center;
    font-size: 17px;
    font-weight: 700;
    margin: 16px 0 14px 0;
}

/* ---------------- EMPLOYEE INFO ---------------- */
#payslip table.emp-info {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 16px;
    border: 1px solid #000;
}

#payslip table.emp-info td {
    border: 1px solid #000;
    padding: 5px 8px;
    font-size: 11.5px;
    vertical-align: top;
}

#payslip table.emp-info td.label {
    font-weight: 700;
    width: 18%;
    background: #f5f5f5;
}

#payslip table.emp-info td.value {
    width: 32%;
}

/* ---------------- SALARY TABLE ---------------- */
#payslip table.salary-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 0;
    border: 1px solid #000;
}

#payslip table.salary-table th {
    border: 1px solid #000;
    background: #f0f0f0;
    padding: 6px 8px;
    font-size: 12px;
    text-align: left;
    font-weight: 700;
}

#payslip table.salary-table td {
    border: 1px solid #000;
    padding: 5px 8px;
    font-size: 11.5px;
    vertical-align: top;
}

#payslip table.salary-table td.amt {
    text-align: right;
    width: 100px;
}

#payslip table.salary-table tr.total-row td {
    font-weight: 700;
    background: #f5f5f5;
}

#payslip table.salary-table tr.lop-entry td {
    font-weight: 700;
}

/* ---------------- NET PAY SECTION ---------------- */
#payslip table.netpay-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #000;
    border-top: none;
    margin-bottom: 16px;
}

#payslip table.netpay-table td {
    border: none;
    padding: 6px 8px;
    font-size: 12px;
    vertical-align: top;
}

#payslip table.netpay-table td.np-label {
    font-weight: 700;
    width: 60%;
}

#payslip table.netpay-table td.np-value {
    font-weight: 700;
    text-align: left;
}

#payslip .amount-words-row td {
    border-top: 1px solid #000;
    font-weight: 700;
    font-size: 11.5px;
}

/* ---------------- BANK / REFERENCE ROW ---------------- */
#payslip .bank-ref-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
    font-size: 11.5px;
}

#payslip .bank-ref-table td {
    border: none;
    padding: 2px 8px;
    font-weight: 700;
}

#payslip .bank-ref-table td.right {
    text-align: right;
}

/* ---------------- SIGNATURE SECTION ---------------- */
#payslip .signature-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 40px;
}

#payslip .signature-table td {
    border: none;
    font-size: 11.5px;
    text-align: right;
    vertical-align: top;
    padding: 0 4px;
}

#payslip .sign-company {
    font-weight: 700;
    margin-bottom: 36px;
}

#payslip .sign-line {
    font-weight: 700;
    margin-top: 4px;
    border-top: 1px solid #000;
    padding-top: 4px;
    display: inline-block;
    min-width: 200px;
    text-align: center;
}

/* ---------------- FOOTER NOTE ---------------- */
#payslip .footer-note {
    margin-top: 26px;
    border: 1px solid #000;
    padding: 6px 10px;
    font-size: 10.5px;
    font-weight: 700;
    text-align: left;
}

#payslip .print-btn-row {
    text-align: right;
    margin-bottom: 8px;
}

@media print {
    body {
        -webkit-print-color-adjust: exact;
    }

    .print-btn-row {
        display: none;
    }
}
</style>
<!-- Printable area start -->
<script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
<!-- Printable area end -->

<div class="content-wrapper">
    <section class="content-header">

        <div class="row">
            <div class="col-sm-12 text-center print-btn-row">
                <button class="btn btn-warning" onclick="printDiv('printableArea')"><span
                        class="fa fa-print"></span></button>
            </div>
            <div id="printableArea">
                <div class="panel-body" id="payslip">

                    <!-- ================= HEADER ================= -->
                    <table class="header-table">
                        <tr>
                            <td class="logo-cell">
                                <img src="<?php echo base_url('assets/img/icons/accroLogo.png'); ?>" alt="Logo">
                            </td>
                            <td>
                                <p class="company-name">Accrosian Soft Solution Pvt. Ltd.</p>
                                <p class="company-tagline">Turning ideas into reality</p>
                                <p class="company-contact">
                                    <a href="https://www.accrosian.com">www.accrosian.com</a> &nbsp;|&nbsp;
                                    <a href="mailto:info@accrosian.com">info@accrosian.com</a>
                                </p>
                            </td>
                            <td style="width:80px;"></td>
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
                        Payslip for the month of <?= $paymentdata[0]['salary_name']; ?>
                    </div>

                    <!-- ================= EMPLOYEE INFORMATION ================= -->
                    <table class="emp-info">
                        <tr>
                            <td class="label">Name:</td>
                            <td class="value"><?= $paymentdata[0]['first_name'].' '.$paymentdata[0]['last_name']?></td>
                            <td class="label">Employee Id:</td>
                            <td class="value"><?= $paymentdata[0]['employee_id']?></td>
                        </tr>
                        <tr>
                            <td class="label">Joining Date:</td>
                            <td class="value">
                                <?= !empty($paymentdata[0]['joining_date']) ? date('d F Y', strtotime($paymentdata[0]['joining_date'])) : '-' ?>
                            </td>
                            <td class="label">Bank Name:</td>
                            <td class="value">
                                <?= !empty($paymentdata[0]['bank_name']) ? $paymentdata[0]['bank_name'] : '-' ?></td>
                        </tr>
                        <tr>
                            <td class="label">Designation:</td>
                            <td class="value"><?= $paymentdata[0]['position_name']?></td>
                            <td class="label">Bank Account No:</td>
                            <td class="value">
                                <?= !empty($paymentdata[0]['bank_account_no']) ? $paymentdata[0]['bank_account_no'] : '-' ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Department:</td>
                            <td class="value">
                                <?= !empty($paymentdata[0]['department_name']) ? $paymentdata[0]['department_name'] : '-' ?>
                            </td>
                            <td class="label">PAN Number:</td>
                            <td class="value">
                                <?= !empty($paymentdata[0]['pan_number']) ? $paymentdata[0]['pan_number'] : '-' ?></td>
                        </tr>
                        <tr>
                            <td class="label">Location:</td>
                            <td class="value">
                                <?= !empty($paymentdata[0]['location']) ? $paymentdata[0]['location'] : '-' ?></td>
                            <td class="label">PF Number:</td>
                            <td class="value">
                                <?= !empty($paymentdata[0]['pf_number']) ? $paymentdata[0]['pf_number'] : '-' ?></td>
                        </tr>
                        <tr>
                            <td class="label">Effective Work Days:</td>
                            <td class="value">
                                <?= !empty($paymentdata[0]['working_period']) ? $paymentdata[0]['working_period'] : '-' ?>
                            </td>
                            <td class="label">UAN Number:</td>
                            <td class="value">
                                <?= !empty($paymentdata[0]['uan_number']) ? $paymentdata[0]['uan_number'] : '-' ?></td>
                        </tr>
                        <tr>
                            <td class="label">LOP:</td>
                            <td class="value">
                                <?= isset($paymentdata[0]['lop_days']) ? $paymentdata[0]['lop_days'] : '0' ?></td>
                            <td class="label">&nbsp;</td>
                            <td class="value">&nbsp;</td>
                        </tr>
                    </table>

                    <!-- ================= SALARY TABLE (LOGIC UNCHANGED) ================= -->
                    <table class="salary-table">
                        <tr>
                            <th style="width:35%;">Earnings</th>
                            <th style="width:15%;">Amount</th>
                            <th style="width:35%;">Deductions</th>
                            <th style="width:15%;">Amount</th>
                        </tr>
                        <tr>
                            <td>
                                <?php if($paymentdata[0]['salarytype'] == 1){ echo display('basic_salary');}else{echo display('basic_salary');}?>
                            </td>
                            <td class="amt">
                                <?php if($paymentdata[0]['salarytype'] == 1){ echo $basicsal = $paymentdata[0]['basic']*$paymentdata[0]['total_working_minutes'];}else{echo $basicsal = $paymentdata[0]['basic'];}?>
                            </td>
                            <?php
                                $totalDeduction = 0;
                                $deduction_rows = array();
                                foreach($deduction as $deductions){
                                    if($deductions->calculation_type == 1) {
                                        $deductionAmount = $deductions->amount;
                                    } else {
                                        $deductionAmount = $basicsal * ($deductions->amount) / 100;
                                    }
                                    $label = $deductions->sal_name;
                                    if($deductions->calculation_type == 0) $label .= ' ('.number_format($deductions->amount, 2).'%)';
                                    $deduction_rows[] = array('label' => $label, 'amount' => $deductionAmount);
                                    $totalDeduction += $deductionAmount;
                                }
                                $first_deduction = !empty($deduction_rows) ? array_shift($deduction_rows) : null;
                            ?>
                            <td><?= $first_deduction ? $first_deduction['label'] : '' ?></td>
                            <td class="amt"><?= $first_deduction ? number_format($first_deduction['amount'], 2) : '' ?>
                            </td>
                        </tr>
                        <?php
                            $totalAddition = 0;
                            $addition_rows = array();
                            foreach($addition as $additions){
                                if($additions->calculation_type == 1) {
                                    $additionAmount = $additions->amount;
                                } else {
                                    $additionAmount = $basicsal * ($additions->amount) / 100;
                                }
                                $label = $additions->sal_name;
                                if($additions->calculation_type == 0) $label .= ' ('.number_format($additions->amount, 2).'%)';
                                $addition_rows[] = array('label' => $label, 'amount' => $additionAmount);
                                $totalAddition += $additionAmount;
                            }
                            $max_rows = max(count($addition_rows), count($deduction_rows));
                            for ($i = 0; $i < $max_rows; $i++):
                                $e_row = isset($addition_rows[$i]) ? $addition_rows[$i] : null;
                                $d_row = isset($deduction_rows[$i]) ? $deduction_rows[$i] : null;
                        ?>
                        <tr>
                            <td><?= $e_row ? $e_row['label'] : '' ?></td>
                            <td class="amt"><?= $e_row ? number_format($e_row['amount'], 2) : '' ?></td>
                            <td><?= $d_row ? $d_row['label'] : '' ?></td>
                            <td class="amt"><?= $d_row ? number_format($d_row['amount'], 2) : '' ?></td>
                        </tr>
                        <?php endfor; ?>

                        <?php
                            $lop_exists = false;
                            $lop_amount = 0;
                            if(isset($paymentdata[0]['lop_days']) && $paymentdata[0]['lop_days'] > 0) {
                                $lop_exists = true;
                                $lop_amount = $paymentdata[0]['lop_deduction'];
                                $totalDeduction += $lop_amount;
                            }
                        ?>
                        <?php if($lop_exists): ?>
                        <tr class="lop-entry">
                            <td></td>
                            <td class="amt"></td>
                            <td><strong>LOP (<?= $paymentdata[0]['lop_days']; ?> days)</strong></td>
                            <td class="amt"><strong><?php echo number_format($lop_amount, 2); ?></strong></td>
                        </tr>
                        <?php endif; ?>

                        <?php
                            $gross = $totalAddition+($basicsal-$totalDeduction);
                            $totaltax = 0;
                            if($paymentdata[0]['total_salary'] < $gross){
                                $tax = $gross - intval(str_replace(',', '', $paymentdata[0]['total_salary']));
                                $totaltax = number_format($tax,2);
                            }
                        ?>
                        <?php if(!empty($totaltax)): ?>
                        <tr>
                            <td></td>
                            <td class="amt"></td>
                            <td><?= display('tax')?></td>
                            <td class="amt"><?= $totaltax ?></td>
                        </tr>
                        <?php endif; ?>

                        <tr class="total-row">
                            <td><?= display('total_addition')?></td>
                            <td class="amt"><?php echo number_format($totalAddition+$basicsal, 2); ?></td>
                            <td><?= display('total_deduction')?></td>
                            <td class="amt">
                                <?php echo number_format($totalDeduction+(!empty($totaltax)?$totaltax:0), 2); ?></td>
                        </tr>
                    </table>

                    <!-- ================= NET PAY SECTION ================= -->
                    <table class="netpay-table">
                        <tr>
                            <td class="np-label">Net Pay for the month:</td>
                            <td class="np-value">&#8377; <?= number_format($paymentdata[0]['total_salary'],2); ?></td>
                        </tr>
                        <tr>
                            <td class="np-label">Amount Paid:</td>
                            <td class="np-value">&#8377; <?= number_format($paymentdata[0]['total_salary'],2); ?></td>
                        </tr>
                        <tr class="amount-words-row">
                            <td colspan="2">
                                Amount Paid in Words: <strong><?= $amountinword; ?> Only</strong>
                            </td>
                        </tr>
                    </table>

                    <!-- ================= BANK / REFERENCE ================= -->
                    <table class="bank-ref-table">
                        <tr>
                            <td><?= display('ref_number')?>: .........</td>
                            <td class="right"><?= display('name_of_bank')?>:
                                <?php echo (!empty($paymentdata[0]['bank_name'])?$paymentdata[0]['bank_name']:'..........')?>
                            </td>
                        </tr>
                    </table>

                    <!-- ================= SIGNATURE SECTION ================= -->
                    <table class="signature-table">
                        <tr>
                            <td>
                                <div class="sign-company">For Accrosian Soft Solution Pvt. Ltd.</div>
                                <span class="sign-line">Authorized Signatory</span>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

        </div>
</div>

<!-- ================= FOOTER NOTE ================= -->
<div class="footer-note" style="max-width:800px;margin:0 auto;">
    Note: This is a Computer-Generated Payslip. Signature Not Required.
</div>

</div>