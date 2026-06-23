<style>
/* ... (keep all existing CSS styles) ... */
#scope>.scope-entry {
    text-align: center;
    padding-bottom: 10px;
}

#payslip {
    background: #fff;
    color: #000;
    padding: 30px 40px;
}

#title {
    margin-bottom: 0px;
    font-size: 38px;
    font-weight: 600;
}

#scope {
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #ccc;
    padding: 7px 0 4px 0;
    display: flex;

}

#scope>.scope-entry {
    text-align: center;
}

.scope-entry>.title {
    font-size: 15px;
    font-weight: 700;
    text-align: left;
}

#scope>.scope-entry>.value {
    font-size: 14px;
    font-weight: 700;
}

.content {
    display: flex;
    height: 100%;
}

.content .left-panel {
    border-right: 1px solid #ccc;
    width: 50%;
    padding: 9px 16px 0 0;
}

#payslip #panel-footer {
    width: 100%;
    padding: 9px 16px 0 0;
}

.content .right-panel {
    width: 50%;
    padding: 10px 0 0 16px;
}

.employee {
    text-align: center;
    margin-bottom: 20px;
}

.employee .name {
    font-size: 15px;
    font-weight: 700;
    border-bottom: 1px solid #ccc;
}

#employee #email {
    font-size: 11px;
    font-weight: 300;
}

.details,
.contributions,
.ytd,
.gross {
    margin-bottom: 20px;
}

.details .entry,
.contributions .entry,
.ytd .entry {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
}

.details .entry .value,
.contributions .entry .value,
.ytd .entry .value {
    font-weight: 700;
    max-width: 130px;
    text-align: right;
}

.gross .entry .value {
    font-weight: 700;
    text-align: right;
    font-size: 16px;
}

.contributions .title,
.ytd .title,
.gross .title {
    font-size: 20px;
    font-weight: 700;
    border-bottom: 1px solid #ccc;
    text-align: left;
    padding-bottom: 4px;
    margin-bottom: 6px;
}

.content .right-panel .details {
    width: 100%;
}

.content .right-panel .details .entry {
    display: flex;
    padding: 0 10px;
    margin: 6px 0;
}

.content .right-panel .details .label {
    font-weight: 700;
    width: 120px;
}

.content .right-panel .details .detail {
    font-weight: 600;
    width: 130px;
}

.content .right-panel .details .rate {
    font-weight: 400;
    width: 80px;
    font-style: italic;
    letter-spacing: 1px;
}

.content .right-panel .details .amount {
    text-align: right;
    font-weight: 700;
    width: 90px;
}

.content .right-panel .details .net_pay div,
.content .right-panel .details .nti div {
    font-weight: 600;
    font-size: 12px;
}

.content .right-panel .details .net_pay,
.content .right-panel .details .nti {
    padding: 3px 0 2px 0;
    margin-bottom: 10px;
    color: #000;
    background: rgba(0, 0, 0, 0.04);
}

.content .left-panel .details .net_pay,
.content .left-panel .details .nti {
    padding: 3px 0 2px 0;
    margin-bottom: 10px;
    color: #000;
    background: rgba(0, 0, 0, 0.04);
}

.content .right-panel .details .label {
    font-weight: 600;
    width: 130px;
    color: #000;
    font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
}

#payslip .footer {
    padding: 3px 0 2px 0;
    margin-bottom: 10px;
    color: #000;
    background: rgba(0, 0, 0, 0.04);
}


.footertext {
    font-weight: 600;
    color: #000;
    font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 20px;
}


.left-panel .details .nti {
    padding: 3px 0 2px 0;
    margin-bottom: 10px;
    font-weight: 800;
    color: #000;
    background: rgba(0, 0, 0, 0.04);
}

.right-panel .details .nti {
    padding: 3px 0 2px 0;
    margin-bottom: 10px;
    font-weight: 800;
    color: #000;
    background: rgba(0, 0, 0, 0.04);
}

.details .nti {
    padding: 3px 0 2px 0;
    margin-bottom: 10px;
    font-weight: 800;
    color: #000;
    background: rgba(0, 0, 0, 0.04);
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
            <div class="col-sm-12 text-center">
                <button class="btn btn-warning" onclick="printDiv('printableArea')"><span
                        class="fa fa-print"></span></button>
            </div>
            <div id="printableArea">
                <div class="panel-body" id="payslip">
                    <div class="row" style="border-bottom:1px solid #ccc;">

                        <div class="col-sm-12">

                            <table width="100%"
                                style="border-bottom:2px solid #000;padding-bottom:10px;margin-bottom:10px;">
                                <tr>
                                    <td width="15%">
                                        <img src="<?php echo base_url('assets/img/icons/accroLogo.png'); ?>"
                                            style="width:90px;">
                                    </td>

                                    <td width="70%" align="left">
                                        <h2 style="margin:0;">Accrosian Soft Solution Pvt. Ltd.</h2>
                                        <div style="font-size:14px; font-style:italic;">Turning ideas into reality</div>
                                        <div>www.accrosian.com | info@accrosian.com</div>
                                    </td>

                                    <td width="15%"></td>
                                </tr>
                            </table>
                            <h3 align="center" style="margin-top:20px;">
                                Payslip for the Month of <?= $paymentdata[0]['salary_name']; ?>
                            </h3>
                            <table width="100%" style="margin-top:20px;font-size:14px;">
                                <tr>
                                    <td width="50%" valign="top">

                                        <b>Employee Name</b> :
                                        <?= $paymentdata[0]['first_name'].' '.$paymentdata[0]['last_name'] ?><br><br>

                                        <b>Joining Date</b> :
                                        <?= !empty($paymentdata[0]['joining_date']) ? $paymentdata[0]['joining_date'] : '-' ?><br><br>

                                        <b>Designation</b> :
                                        <?= $paymentdata[0]['position_name'] ?><br><br>

                                        <b>Department</b> :
                                        <?= !empty($paymentdata[0]['department_name']) ? $paymentdata[0]['department_name'] : '-' ?><br><br>

                                        <b>Location</b> :
                                        Bhubaneswar<br><br>

                                        <b>Effective Work Days</b> :
                                        <?= !empty($paymentdata[0]['working_days']) ? $paymentdata[0]['working_days'] : '30' ?><br><br>

                                        <b>LOP (Days)</b> :
                                        <?= !empty($paymentdata[0]['lop_days']) ? $paymentdata[0]['lop_days'] : '0' ?>

                                    </td>

                                    <td width="50%" valign="top">

                                        <b>Employee ID</b> :
                                        <?= $paymentdata[0]['employee_id'] ?><br><br>

                                        <b>Bank Name</b> :
                                        <?= !empty($paymentdata[0]['bank_name']) ? $paymentdata[0]['bank_name'] : '-' ?><br><br>

                                        <b>Bank Account No</b> :
                                        <?= !empty($paymentdata[0]['bank_account_no']) ? $paymentdata[0]['bank_account_no'] : '-' ?><br><br>

                                        <b>PAN Number</b> :
                                        <?= !empty($paymentdata[0]['pan_number']) ? $paymentdata[0]['pan_number'] : '-' ?><br><br>

                                        <b>PF Number</b> :
                                        <?= !empty($paymentdata[0]['pf_number']) ? $paymentdata[0]['pf_number'] : '-' ?><br><br>

                                        <b>UAN Number</b> :
                                        <?= !empty($paymentdata[0]['uan_number']) ? $paymentdata[0]['uan_number'] : '-' ?>

                                    </td>
                                </tr>
                            </table>

                        </div>




                        <div class="col-sm-12">
                            <table class="table">
                                <div class="col-sm-12">

                                    <table width="100%" border="1" cellspacing="0" cellpadding="5"
                                        style="border-collapse:collapse;margin-top:15px;">

                                        <tr style="font-weight:bold;background:#f5f5f5;">
                                            <td width="35%">EARNINGS</td>
                                            <td width="15%" align="right">AMOUNT</td>
                                            <td width="35%">DEDUCTIONS</td>
                                            <td width="15%" align="right">AMOUNT</td>
                                        </tr>

                                        <?php
$totalAddition = 0;
$totalDeduction = 0;

$maxRows = max(count($addition), count($deduction));
?>

                                        <tr>
                                            <td valign="top">

                                                <table width="100%">
                                                    <tr>
                                                        <td><strong>Basic Salary</strong></td>
                                                        <td align="right">
                                                            <?php
if($paymentdata[0]['salarytype'] == 1){
    echo number_format($basicsal = $paymentdata[0]['basic'] * $paymentdata[0]['total_working_minutes'],2);
}else{
    echo number_format($basicsal = $paymentdata[0]['basic'],2);
}
?>
                                                        </td>
                                                    </tr>

                                                    <?php foreach($addition as $additions){ ?>

                                                    <tr>
                                                        <td>
                                                            <?= $additions->sal_name; ?>
                                                            <?php
if($additions->calculation_type == 0){
    echo ' ('.number_format($additions->amount,2).'%)';
}
?>
                                                        </td>

                                                        <td align="right">
                                                            <?php

if($additions->calculation_type == 1){
    $additionAmount = $additions->amount;
}else{
    $additionAmount = $basicsal * $additions->amount / 100;
}

echo number_format($additionAmount,2);

$totalAddition += $additionAmount;

?>
                                                        </td>
                                                    </tr>

                                                    <?php } ?>

                                                </table>

                                            </td>

                                            <td valign="top"></td>

                                            <td valign="top">

                                                <table width="100%">

                                                    <?php foreach($deduction as $deductions){ ?>

                                                    <tr>
                                                        <td>
                                                            <?= $deductions->sal_name; ?>

                                                            <?php
if($deductions->calculation_type == 0){
    echo ' ('.number_format($deductions->amount,2).'%)';
}
?>
                                                        </td>

                                                        <td align="right">

                                                            <?php

if($deductions->calculation_type == 1){
    $deductionAmount = $deductions->amount;
}else{
    $deductionAmount = $basicsal * $deductions->amount / 100;
}

echo number_format($deductionAmount,2);

$totalDeduction += $deductionAmount;

?>

                                                        </td>
                                                    </tr>

                                                    <?php } ?>

                                                    <?php if(isset($paymentdata[0]['lop_days']) && $paymentdata[0]['lop_days'] > 0){ ?>

                                                    <tr>
                                                        <td>
                                                            LOP (<?= $paymentdata[0]['lop_days']; ?> Days)
                                                        </td>

                                                        <td align="right">
                                                            <?= number_format($paymentdata[0]['lop_deduction'],2); ?>
                                                        </td>
                                                    </tr>

                                                    <?php
$totalDeduction += $paymentdata[0]['lop_deduction'];
} ?>

                                                </table>

                                            </td>

                                            <td valign="top"></td>

                                        </tr>

                                        <tr style="font-weight:bold;background:#f5f5f5;">
                                            <td>GROSS EARNINGS</td>

                                            <td align="right">
                                                <?= number_format($totalAddition + $basicsal,2); ?>
                                            </td>

                                            <td>GROSS DEDUCTION</td>

                                            <td align="right">
                                                <?= number_format($totalDeduction + (!empty($totaltax)?$totaltax:0),2); ?>
                                            </td>
                                        </tr>

                                    </table>

                                </div>
                        </div>
                    </div>


                    <div class="row">


                        <div class="col-sm-12">

                            <table class="table">


                                <tr class="details">
                                    <tbody class="nti">
                                        <td style="font-weight:bold;">
                                            Amount In Words:
                                            <br>
                                            <?= $amountinword; ?>
                                        </td>

                                        <td style="text-align:right;font-weight:bold;">
                                            Net Pay
                                            <br>
                                            ₹ <?= number_format($paymentdata[0]['total_salary'],2); ?>
                                        </td>
                                        <td class="value" style="float: right;font-weight: bold">
                                            <?= $paymentdata[0]['total_salary']?> </td>
                                    </tbody>
                                </tr>


                            </table>



                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" style="padding-bottom: 50px;">

                            <div class="col-sm-6" style="float:left;font-weight: bold;"><?= display('ref_number')?>:
                                .........</div>
                            <div class="col-sm-6" style="float:right;font-weight: bold;"><?= display('name_of_bank')?>:
                                <?php echo (!empty($paymentdata[0]['bank_name'])?$paymentdata[0]['bank_name']:'..........')?>
                            </div>

                        </div>

                    </div>
                    <table width="100%">
                        <tr>

                            <td></td>

                            <td align="right">

                                For Accrosian Soft Solution Pvt. Ltd.

                                <br><br><br>

                                _________________________

                                <br>

                                Authorized Signatory

                            </td>

                        </tr>
                    </table>

                </div>
            </div>


        </div>
</div>
<div style="border:1px solid #ccc;padding:8px;margin-top:20px;">

    Note:
    This is a Computer Generated Payslip.
    Signature is not required.

</div>
</div>