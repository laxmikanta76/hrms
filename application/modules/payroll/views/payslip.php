<style>
#scope>.scope-entry {
    text-align: center;
    padding-bottom: 10px;
}

#payslip {
    /* background: #fff;
    color: #000;
    padding: 20px 30px;
    font-family: 'Helvetica Neue', Arial, sans-serif;
    font-size: 12px;
    line-height: 1.5; */
    position: relative;
    background: #fff;
    color: #000;
    padding: 20px 30px;
    font-family: 'Helvetica Neue', Arial, sans-serif;
    font-size: 12px;
    line-height: 1.5;
    overflow: hidden;
}

#title {
    margin-bottom: 0px;
    font-size: 38px;
    font-weight: 600;
}

#payslip table,
#payslip h2,
#payslip h3,
#payslip p,
#payslip div {
    position: relative;
    z-index: 2;
}

#payslip .payslip-title {
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
    // document.body.style.marginTop="-45px";
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
<!-- Printable area end -->

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd">
            <div class="panel title text-right">
                <button class="btn btn-warning" onclick="printDiv('printableArea')"><span
                        class="fa fa-print"></span></button>
            </div>
            <div id="printableArea">
                <div class="panel-body" id="payslip">
                    <img src="<?= base_url('assets/img/icons/waterM_log.png'); ?>" style="
        position:absolute;
        top:55%;
        left:50%;
        transform:translate(-50%,-50%);
        width:600px;
        opacity:0.12;
        z-index:0;
        pointer-events:none;
    ">

                    <!-- ================= HEADER ================= -->
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>

                            <!-- Logo -->
                            <td width="120" valign="top">
                                <img src="<?= base_url('assets/img/icons/accroLogo.png'); ?>" style="width:70px;">
                            </td>

                            <!-- Empty Space -->
                            <td width="240">
                                &nbsp;
                            </td>

                            <!-- Company Details -->
                            <td valign="top">

                                <div style="font-size:20px;font-weight:bold;">
                                    Accrosian Soft Solution Pvt. Ltd.
                                </div>

                                <div style="font-size:15px;font-style:italic;color:#555;">
                                    Turning ideas into reality
                                </div>

                                <div style="font-size:14px;">
                                    www.accrosian.com | info@accrosian.com
                                </div>

                            </td>

                        </tr>
                    </table>

                    <div style="border-bottom:2px solid #f57c00;margin-top:8px;"></div>
                    <!--=================PAYSLIP TITLE=================-->
                    <h3 class=" payslip-title" align="center" style="text-align:center; margin-bottom:25px;">
                        Payslip for the Month of <?= $paymentdata[0]['salary_name']; ?>
                    </h3>

                    <!-- ================= EMPLOYEE INFORMATION ================= -->
                    <table width="100%" cellspacing="0" cellpadding="6"
                        style="margin-bottom:15px;font-size:12px; border:1px ridge #afa9a9; border-collapse:collapse;">
                        <tr>
                            <td width="21%" style="padding-left:5px; border:1px ridge #afa9a9;"><b>Name</b></td>
                            <td width="32%" style="padding-left:5px; border:1px ridge #afa9a9;">
                                <?= $paymentdata[0]['first_name'].' '.$paymentdata[0]['last_name']?></td>
                            <td width="20%" style="padding-left:5px; border:1px ridge #afa9a9;"><b>Employee Id</b></td>
                            <td width="32%" style="padding-left:5px; border:1px ridge #afa9a9;">
                                <?= $paymentdata[0]['employee_id']?></td>
                        </tr>
                        <tr>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;"><b>Joining Date</b></td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">
                                <?= !empty($paymentdata[0]['joining_date']) ? date('d F Y', strtotime($paymentdata[0]['joining_date'])) : '-' ?>
                            </td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;"><b>Bank Name</b></td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">
                                <?= !empty($paymentdata[0]['employee_bank_name']) ? $paymentdata[0]['employee_bank_name'] : '-' ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;"><b>Designation</b></td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">
                                <?= $paymentdata[0]['position_name']?></td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;"><b>Bank Account No</b></td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">
                                <?= !empty($paymentdata[0]['bank_account_no']) ? $paymentdata[0]['bank_account_no'] : '-' ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;"><b>Department</b></td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">
                                <?= !empty($paymentdata[0]['department_name']) ? $paymentdata[0]['department_name'] : '-' ?>
                            </td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;"><b>PAN Number</b></td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">
                                <?= !empty($paymentdata[0]['pan_number']) ? $paymentdata[0]['pan_number'] : '-' ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;"><b>Location</b></td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">Bhubaneswar</td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;"><b>PF Number</b></td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">
                                <?= !empty($paymentdata[0]['pf_number']) ? $paymentdata[0]['pf_number'] : '-' ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;"><b>Effective Work Days</b></td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">
                                <?= !empty($paymentdata[0]['working_period']) ? $paymentdata[0]['working_period'] : '-' ?>
                            </td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;"><b>UAN Number</b></td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">
                                <?= !empty($paymentdata[0]['uan_number']) ? $paymentdata[0]['uan_number'] : '-' ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;"><b>LOP</b></td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">
                                <?= isset($paymentdata[0]['lop_days']) ? $paymentdata[0]['lop_days'] : '0' ?>
                            </td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">&nbsp;</td>
                            <td style="padding-left:5px; border:1px ridge #afa9a9;">&nbsp;</td>
                        </tr>
                    </table>

                    <!-- ================= SALARY TABLE (LOGIC UNCHANGED) ================= -->
                    <table width="100%" cellspacing="0" cellpadding="6"
                        style="font-size:12px; border:1px ridge #afa9a9;">
                        <tr>
                            <th width="35%" align="left" style="padding-left:5px; border:1px ridge #afa9a9;">Earnings
                            </th>
                            <th width="15%" align="right" style="padding-left:5px; border:1px ridge #afa9a9;">Amount
                            </th>
                            <th width=" 35%" align="left" style="padding-left:5px; border:1px ridge #afa9a9;">Deductions
                            </th>
                            <th width="15%" align="right" style="padding-left:5px; border:1px ridge #afa9a9;">Amount
                            </th>
                        </tr>
                        <tr>
                            <td style="padding-left:5px;">
                                <?php if($paymentdata[0]['salarytype'] == 1){ echo display('basic_salary');}else{echo display('basic_salary');}?>
                            </td>
                            <td align="right">
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
                            <td style="padding-left:5px;">
                                <?= $first_deduction ? $first_deduction['label'] : '' ?></td>
                            <td align="right">
                                <?= $first_deduction ? number_format($first_deduction['amount'], 2) : '' ?>
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
                            <td style="padding-left:5px;"><?= $e_row ? $e_row['label'] : '' ?></td>
                            <td align="right"><?= $e_row ? number_format($e_row['amount'], 2) : '' ?></td>
                            <td style="padding-left:5px;"><?= $d_row ? $d_row['label'] : '' ?></td>
                            <td align="right"><?= $d_row ? number_format($d_row['amount'], 2) : '' ?></td>
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
                        <tr>
                            <td style="padding-left:5px;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="padding-left:5px;"><strong>LOP (<?= $paymentdata[0]['lop_days']; ?>
                                    days)</strong>
                            </td>
                            <td align="right"><strong><?php echo number_format($lop_amount, 2); ?></strong>
                            </td>
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
                            <td style="padding-left:5px;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="padding-left:5px;"><?= display('tax')?></td>
                            <td align="right"><?= $totaltax ?></td>
                        </tr>
                        <?php endif; ?>

                        <tr style="background:#f5f5f5;font-weight:bold;">
                            <td><?= display('total_addition')?></td>
                            <td align="right" style="padding-left:5px;">
                                <?php echo number_format($totalAddition+$basicsal, 2); ?></td>
                            <td style="padding-left:5px;"><?= display('total_deduction')?></td>
                            <td align="right">
                                <?php echo number_format($totalDeduction+(!empty($totaltax)?$totaltax:0), 2); ?>
                            </td>
                        </tr>
                    </table>

                    <!-- ================= NET PAY SECTION ================= -->
                    <table width="100%" border="1" cellspacing="0" cellpadding="6"
                        style="border-top:none;font-size:12px;margin-bottom:15px;">
                        <tr>
                            <td style="font-weight:bold;width:60%;">Net Pay for the month:</td>
                            <td style="font-weight:bold;">&#8377;
                                <?= number_format($paymentdata[0]['total_salary'],2); ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;">Amount Paid:</td>
                            <td style="font-weight:bold;">&#8377;
                                <?= number_format($paymentdata[0]['total_salary'],2); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-weight:bold;border-top:1px solid #000;">
                                Amount Paid in Words: <strong><?= $amountinword; ?> Only</strong>
                            </td>
                        </tr>
                    </table>

                    <!-- ================= SIGNATURE SECTION ================= -->
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

                    <!-- ================= FOOTER: REFERENCE / BANK ================= -->
                    <table width="100%" style="margin-top:10px;font-weight:bold;font-size:11.5px;">
                        <tr>
                            <td><?= display('ref_number')?>: .........</td>
                            <td align="right"><?= display('name_of_bank')?>:
                                <?php echo (!empty($paymentdata[0]['bank_name'])?$paymentdata[0]['bank_name']:'..........')?>
                            </td>
                        </tr>
                    </table>

                                            </tbody>
                                            <!--                                    <tfoot>
                                    
                                    </tfoot>-->
                                        </table>
                                    </td>
                                    <td class="right-panel">
                                        <table class="" width="100%">



                                            <thead>
                                                <tr class="employee">
                                                    <th class="name text-center" colspan="2"
                                                        style="border-bottom: 1px solid #ccc;">
                                                        <?php echo display('deduction'); ?></th>


                                                </tr>
                                            </thead>
                                            <tbody class="details">
                                                <?php 
                                      $totalDeduction = 0;
                                      foreach($deduction as $deductions){?>
                                                <tr class="entry">
                                                    <td class="value"><?= $deductions->sal_name; ?></td>
                                                    <td class="value">
                                                        <div><?php echo  $basicsal*($deductions->amount)/100;
                                            $totalDeduction +=$basicsal*($deductions->amount)/100;
                                            ?></div>
                                                    </td>

                                                </tr>
                                                <?php }?>
                                                <?php $gross = $totalAddition+($basicsal-$totalDeduction);
                                     if($paymentdata[0]['total_salary'] < $gross){
                                    ?>
                                                <tr class="entry">
                                                    <td class="value"><?= display('tax')?></td>
                                                    <td class="value">
                                                        <div><?php  $tax = $gross - intval(str_replace(',', '', $paymentdata[0]['total_salary']));
                                            echo $totaltax = number_format($tax,2);
                                            ?></div>
                                                    </td>

                                                </tr>
                                                <?php }?>

                                                <tr class="entry nti">
                                                    <td class="value" style="float:left; font-weight: bold">
                                                        <?= display('total_deduction')?></td>
                                                    <td class="value" style="font-weight: bold">
                                                        <?php echo $totalDeduction+(!empty($totaltax)?$totaltax:0); ?>
                                                    </td>
                                                </tr>

                                            </tbody>

                                        </table>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>


                    <div class="row">


                        <div class="col-sm-12">

                            <table class="table">


                                <tr class="details">
                                    <tbody class="nti">
                                        <th class="value"><?php echo display('net_salary'); ?> :
                                            <?php echo display('in_word').':'.$amountinword; ?></th>
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
                    <div class="row">
                        <div class="col-sm-6">
                            <div
                                style="float:left;width:40%;text-align:center;border-top:1px solid #e4e5e7;font-weight: bold;">
                                <?php echo display('employee_signature'); ?>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div
                                style="float:right;width:40%;text-align:center;border-top:1px solid #e4e5e7;font-weight: bold;">
                                <?php echo display('paid_by'); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>