<div class="form-group text-right">
    <?php if($this->permission->method('payroll','create')->access()): ?>
    <button type="button" class="btn btn-primary btn-md" data-target="#add0" data-toggle="modal"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('add_salary_setup') ?></button>
    <?php endif; ?>
    <?php if($this->permission->method('payroll','read')->access()): ?>
    <a href="<?php echo base_url();?>/payroll/Payroll/salary_setup_view"
        class="btn btn-primary"><?php echo display('manage_salary_setup') ?></a>
    <?php endif; ?>
</div>


<div id="add0" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('salary_setup')?></strong>
            </div>
            <div class="modal-body">


                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">

                            <div class="panel-body">

                                <?= form_open('payroll/Payroll/create_s_setup') ?>
                                <div class="form-group row">
                                    <label for="employee_id"
                                        class="col-sm-3 col-form-label"><?php echo display('employee_name') ?>
                                        *</label>
                                    <div class="col-sm-9">
                                        <?php echo form_dropdown('employee_id',$employee,null,'class="form-control" id="employee_id" style="width:615px" onchange="employechange(this.value)"') ?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="payment_period"
                                        class="col-sm-3 col-form-label"><?php echo display('salary_type_id') ?>
                                        *</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="sal_type_name" id="sal_type_name"
                                            readonly="">
                                        <input type="hidden" class="form-control" name="sal_type" id="sal_type">
                                    </div>
                                </div>
                                <table border="1" width="100%">
                                    <div class="row">

                                        <td class="col-sm-6" style="text-align: center;">
                                            <h4 style="text-decoration: underline;font-weight: bold;padding-top:20px">
                                                <?php echo display('addition')?></h4><br>
                                            <table id="add">
                                                <tr>
                                                    <th style="padding:10px"><?php echo display('basic')?></th>
                                                    <td><input type="text" id="basic" name="basic" class="form-control"
                                                            disabled=""></td>
                                                    <td></td>
                                                </tr>
                                                <?php
                 $x=0;
                 foreach ($slname as $ab){
                  ?>
                                                <tr>
                                                    <th style="padding:10px"><?php echo $ab->sal_name ;?></th>
                                                    <td><input type="text"
                                                            name="amount[<?php echo $ab->salary_type_id; ?>]"
                                                            class="form-control addamount" onkeyup="summary()"
                                                            id="add_<?php echo $x;?>"></td>
                                                    <td style="padding:10px">
                                                        <select
                                                            name="calculation_type[<?php echo $ab->salary_type_id; ?>]"
                                                            class="form-control calc-type"
                                                            id="calc_type_add_<?php echo $x;?>"
                                                            onchange="handleCalculationType(this, 'add_<?php echo $x;?>')">
                                                            <option value="0">%</option>
                                                            <option value="1">Amount</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?php
                $x++;}
                ?>
                                            </table>
                                        </td>
                                        <td class="col-sm-6" style="text-align: center;">
                                            <h4 style="text-decoration: underline;font-weight: bold;">
                                                <?php echo display('deduction')?></h4><br>
                                            <table id="dduct">
                                                <?php
                $y=0;
                foreach ($sldname as $row){
                  ?>
                                                <tr>
                                                    <th style="padding:10px"><?php echo $row->sal_name ;?></th>
                                                    <td><input type="text"
                                                            name="amount[<?php echo $row->salary_type_id; ?>]"
                                                            onkeyup="summary()" class="form-control deducamount"
                                                            id="dd_<?php echo $y;?>"></td>
                                                    <td style="padding:10px">
                                                        <select
                                                            name="calculation_type[<?php echo $row->salary_type_id; ?>]"
                                                            class="form-control calc-type"
                                                            id="calc_type_dd_<?php echo $y;?>"
                                                            onchange="handleCalculationType(this, 'dd_<?php echo $y;?>')">
                                                            <option value="0">%</option>
                                                            <option value="1">Amount</option>
                                                        </select>
                                                    </td>
                                                </tr><?php
               $y++; }
                ?>
                                                <tr>
                                                    <th style="padding:10px"><?php echo display('tax')?></th>
                                                    <td><input type="text" name="amount[]" onkeyup="summary()"
                                                            class="form-control deducamount" id="taxinput"></td>
                                                    <td style="padding:10px">
                                                        <input type="checkbox" name="tax_manager" id="taxmanager"
                                                            onchange='handletax(this);' value="1">Tax
                                                        Manager
                                                        <select name="calculation_type[]" class="form-control calc-type"
                                                            id="calc_type_tax"
                                                            onchange="handleCalculationType(this, 'taxinput')"
                                                            style="display:inline-block; width:80px; margin-left:10px;">
                                                            <option value="0">%</option>
                                                            <option value="1">Amount</option>
                                                        </select>
                                                    </td>
                                                </tr>

                                            </table>

                                        </td>

                                    </div>

                                </table>
                            </div>
                            <div class="form-group row">
                                <label for="payable" class="col-sm-3 col-form-label"
                                    style="text-align: center;"><?php echo display('gross_salary')?></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="gross_salary" id="grsalary"
                                        readonly="">
                                </div>
                            </div>

                            <div class="form-group text-right">
                                <button type="reset"
                                    class="btn btn-primary w-md m-b-5"><?php echo display('reset') ?></button>
                                <button type="submit"
                                    class="btn btn-success w-md m-b-5"><?php echo display('set') ?></button>
                            </div>
                            <?php echo form_close() ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-default thumbnail">

            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('cid') ?></th>
                            <th><?php echo display('employee_name') ?></th>
                            <th><?php echo display('designation') ?></th>
                            <th><?php echo display('division') ?></th>
                            <th><?php echo display('sal_type') ?></th>
                            <th><?php echo display('basic') ?></th>
                            <th><?php echo display('gross_salary') ?></th>
                            <th><?php echo display('date') ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($emp_sl_setup)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($emp_sl_setup as $que) { ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $que->first_name.' '.$que->last_name; ?></td>
                            <td><?php echo $que->position_name; ?></td>
                            <td><?php echo $que->department_name; ?></td>
                            <td><?php if($que->rate_type == 1){
              echo 'Hourly';
          }else{
            echo 'Salary';
        }?></td>
                            <td><?php echo $que->rate; ?></td>
                            <td><?php echo $que->gross_salary; ?></td>
                            <td><?php echo $que->create_date; ?></td>
                        </tr>
                        <?php $sl++; ?>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table> <!-- /.table-responsive -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
// Handle calculation type change
function handleCalculationType(selectElement, inputId) {
    var inputElement = document.getElementById(inputId);
    if (selectElement.value == '1') {
        // Amount selected - remove any % validation
        inputElement.setAttribute('data-calc-type', 'amount');
    } else {
        // Percentage selected
        inputElement.setAttribute('data-calc-type', 'percentage');
    }
    summary(); // Recalculate on change
}

function summary() {
    var b = parseFloat($('#basic').val()) || 0;
    var add = 0;
    var deduct = 0;
    var addper = 0;

    // Calculate additions
    $(".addamount").each(function() {
        var value = parseFloat(this.value) || 0;
        if (value > 0) {
            var calcType = $(this).closest('tr').find('.calc-type').val();

            if (calcType == '0') { // Percentage
                addper += value;
                if (addper > 100) {
                    alert('Total addition percentage cannot exceed 100%');
                    this.value = '';
                    return false;
                }
                add += (b * value / 100);
            } else { // Amount
                add += value;
            }
        }
    });

    // Calculate deductions
    $(".deducamount").each(function() {
        var value = parseFloat(this.value) || 0;
        if (value > 0) {
            var calcType = $(this).closest('tr').find('.calc-type').val();

            if (calcType == '0') { // Percentage
                deduct += (b * value / 100);
            } else { // Amount
                deduct += value;
            }
        }
    });

    var grossSalary = b + add - deduct;
    document.getElementById('grsalary').value = grossSalary.toFixed(2);
}

function handletax(checkbox) {
    var deduct = 0;
    var add = 0;
    var b = parseInt($('#basic').val());

    $(".deducamount").each(function() {
        var value = parseFloat(this.value) || 0;
        if (value > 0) {
            var calcType = $(this).closest('tr').find('.calc-type').val();
            if (calcType == '0') {
                deduct += (b * value / 100);
            } else {
                deduct += value;
            }
        }
    });

    $(".addamount").each(function() {
        var value = parseFloat(this.value) || 0;
        if (value > 0) {
            var calcType = $(this).closest('tr').find('.calc-type').val();
            if (calcType == '0') {
                add += (b * value / 100);
            } else {
                add += value;
            }
        }
    });

    var amount = b - deduct;

    if (checkbox.checked == true) {
        $.ajax({
            url: '<?php echo site_url('payroll/Payroll/salarywithtax/')?>',
            method: 'post',
            dataType: 'json',
            data: {
                'amount': amount,
            },
            success: function(data) {
                document.getElementById('grsalary').value = (add + b - data - deduct).toFixed(2);
                document.getElementById('taxinput').value = '';
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    } else {
        summary();
    }
}

// Onchange employee id information
function employechange(id) {
    $.ajax({
        url: "<?php echo base_url('payroll/Payroll/employeebasic/')?>",
        method: 'post',
        dataType: 'json',
        data: {
            'employee_id': id,
        },
        success: function(data) {
            document.getElementById('basic').value = data.rate;
            document.getElementById('sal_type').value = data.rate_type;
            document.getElementById('sal_type_name').value = data.stype;
            document.getElementById('grsalary').value = '';

            if (data.rate_type == 1) {
                document.getElementById("taxinput").disabled = true;
                document.getElementById("taxmanager").checked = true;
                document.getElementById("taxmanager").setAttribute('disabled', 'disabled');
            } else {
                document.getElementById("taxinput").disabled = false;
                document.getElementById("taxmanager").checked = false;
                document.getElementById("taxmanager").removeAttribute('disabled');
            }

            // Clear all input fields
            var i;
            var count = $('#add tr').length;
            for (i = 0; i < count; i++) {
                if (document.getElementById('add_' + i)) {
                    document.getElementById('add_' + i).value = '';
                    if (document.getElementById('calc_type_add_' + i)) {
                        document.getElementById('calc_type_add_' + i).value = '0';
                    }
                }
            }

            var dt = $('#dduct tr').length;
            for (i = 0; i < dt; i++) {
                if (document.getElementById('dd_' + i)) {
                    document.getElementById('dd_' + i).value = '';
                    if (document.getElementById('calc_type_dd_' + i)) {
                        document.getElementById('calc_type_dd_' + i).value = '0';
                    }
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}
</script>