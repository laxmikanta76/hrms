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
    max-width: 760px;
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
    width: 70px;
}

#payslip .logo-cell img {
    width: 60px;
    height: 60px;
    object-fit: contain;
}

#payslip .company-name {
    font-size: 22px;
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
    margin: 8px 0 6px 0;
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
    padding: 2px 6px;
    border: none;
    width: 50%;
}

#payslip .address-table td.divider {
    border-left: 1px solid #888;
}

/* ---------------- PAYSLIP TITLE ---------------- */
#payslip .payslip-title {
    text-align: center;
    font-size: 15px;
    font-weight: 700;
    margin: 14px 0 12px 0;
}

/* ---------------- EMPLOYEE INFO ---------------- */
#payslip table.emp-info {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 14px;
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
}

#payslip table.salary-table td.amt {
    text-align: right;
    width: 90px;
}

#payslip table.salary-table tr.total-row td {
    font-weight: 700;
    background: #f5f5f5;
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
}

#payslip table.netpay-table td.np-label {
    font-weight: 700;
    width: 55%;
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
    margin-top: 50px;
}

#payslip .signature-table td {
    border: none;
    font-size: 11.5px;
    vertical-align: top;
    padding: 0 4px;
    width: 50%;
}

#payslip .signature-table td.left {
    text-align: center;
}

#payslip .signature-table td.right {
    text-align: right;
}

#payslip .sign-company {
    font-weight: 700;
    margin-bottom: 40px;
}

#payslip .sign-line {
    font-weight: 700;
    margin-top: 4px;
    border-top: 1px solid #000;
    padding-top: 4px;
    display: inline-block;
    min-width: 160px;
    text-align: center;
}

/* ---------------- FOOTER NOTE ---------------- */
#payslip .footer-note {
    margin-top: 30px;
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

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd">
            <div class="panel title text-right print-btn-row">
                <button class="btn btn-warning" onclick="printDiv('printableArea')"><span
                        class="fa fa-print"></span></button>
            </div>
            <div id="printableArea">
                <div class="panel-body" id="payslip">

                    <!-- ================= HEADER ================= -->
                    <table class="header-table">
                        <tr>
                            <td class="logo-cell">
                                <img src="http://newhrm.bdtask.com/hrm_demo/assets/img/icons/2017-07-22/HRM.png"
                                    alt="Logo">
                            </td>
                            <td>
                                <p class="company-name">Bdtask Ltd</p>
                                <p class="company-tagline"><?php echo 'Salary Slip - November 2019'; ?></p>
                            </td>
                            <td style="width:70px;"></td>
                        </tr>
                    </table>
                    <hr class="header-rule">

                    <!-- ================= ADDRESS SECTION ================= -->
                    <table class="address-table">
                        <tr>
                            <td colspan="2" style="text-align:center;">
                                4th Floor Mannan Plaza, Khilkhet, Dhaka-1229
                            </td>
                        </tr>
                    </table>

                    <!-- ================= EMPLOYEE INFORMATION ================= -->
                    <table class="emp-info">
                        <tr>
                            <td class="label">Employee Name:</td>
                            <td class="value" colspan="3"><?php echo 'Mohammad Abul Kalam'; ?></td>
                        </tr>
                        <tr>
                            <td class="label">Designation:</td>
                            <td class="value" colspan="3"><?php echo 'Web Developer'; ?></td>
                        </tr>
                        <tr>
                            <td class="label">Salary Date:</td>
                            <td class="value" colspan="3"><?php echo '6 November 2019'; ?></td>
                        </tr>
                    </table>

                    <!-- ================= SALARY TABLE ================= -->
                    <table class="salary-table">
                        <tr>
                            <th style="width:60%;">Earnings</th>
                            <th style="width:15%;">Amount</th>
                            <th style="width:60%;">Deduction</th>
                            <th style="width:15%;">Amount</th>
                        </tr>
                        <tr>
                            <td>Basic</td>
                            <td class="amt"><?php echo 40000; ?></td>
                            <td>Provident Fund</td>
                            <td class="amt"><?php echo 5000; ?></td>
                        </tr>
                        <tr>
                            <td>House Rent</td>
                            <td class="amt"><?php echo 10000; ?></td>
                            <td>Tax</td>
                            <td class="amt"><?php echo 2000; ?></td>
                        </tr>
                        <tr>
                            <td>Health</td>
                            <td class="amt"><?php echo 5000; ?></td>
                            <td></td>
                            <td class="amt"></td>
                        </tr>
                        <tr class="total-row">
                            <td>Total Addition</td>
                            <td class="amt"><?php echo 55000; ?></td>
                            <td>Total Deduction</td>
                            <td class="amt"><?php echo 7000; ?></td>
                        </tr>
                    </table>

                    <!-- ================= NET PAY SECTION ================= -->
                    <table class="netpay-table">
                        <tr>
                            <td class="np-label">Net Salary:</td>
                            <td class="np-value">&#8377; <?php echo 48000; ?></td>
                        </tr>
                        <tr class="amount-words-row">
                            <td colspan="2">
                                In Word: <strong><?php echo 'Forty Eight Thousands Only'; ?></strong>
                            </td>
                        </tr>
                    </table>

                    <!-- ================= BANK / REFERENCE ================= -->
                    <table class="bank-ref-table">
                        <tr>
                            <td>Check No: <?php echo '234252342'; ?></td>
                            <td class="right">Name of Bank: <?php echo 'Bangladesh Bank'; ?></td>
                        </tr>
                    </table>

                    <!-- ================= SIGNATURE SECTION ================= -->
                    <table class="signature-table">
                        <tr>
                            <td class="left">
                                <span class="sign-line"><?php echo 'Employee Signature'; ?></span>
                            </td>
                            <td class="right">
                                <div class="sign-company">For Bdtask Ltd</div>
                                <span class="sign-line"><?php echo 'Paid By'; ?></span>
                            </td>
                        </tr>
                    </table>

                    <!-- ================= FOOTER NOTE ================= -->
                    <div class="footer-note">
                        Note: This is a Computer-Generated Payslip. Signature Not Required.
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
</script>