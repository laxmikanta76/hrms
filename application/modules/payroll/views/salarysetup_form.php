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
                                        class="col-sm-3 col-form-label"><?php echo display('employee_name') ?> *</label>
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
                                                    <td colspan="2"><input type="text" id="basic" name="basic"
                                                            class="form-control" disabled=""></td>
                                                </tr>
                                                <?php
                    $x=0;
                    foreach ($slname as $ab){
                    ?>
                                                <tr>
                                                    <th style="padding:10px"><?php echo $ab->sal_name;?></th>
                                                    <td>
                                                        <select
                                                            name="calculation_type[<?php echo $ab->salary_type_id; ?>]"
                                                            class="form-control calc-type" data-index="<?php echo $x;?>"
                                                            data-side="add"
                                                            onchange="toggleInputType(this, 'add_<?php echo $x;?>')">
                                                            <option value="0">Percentage (%)</option>
                                                            <option value="1">Amount</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            name="amount[<?php echo $ab->salary_type_id; ?>]"
                                                            class="form-control addamount" onkeyup="summary()"
                                                            id="add_<?php echo $x;?>" placeholder="Enter %">
                                                    </td>
                                                </tr>
                                                <?php
                    $x++;
                    }
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
                                                    <th style="padding:10px"><?php echo $row->sal_name;?></th>
                                                    <td>
                                                        <select
                                                            name="calculation_type[<?php echo $row->salary_type_id; ?>]"
                                                            class="form-control calc-type" data-index="<?php echo $y;?>"
                                                            data-side="dd"
                                                            onchange="toggleInputType(this, 'dd_<?php echo $y;?>')">
                                                            <option value="0">Percentage (%)</option>
                                                            <option value="1">Amount</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            name="amount[<?php echo $row->salary_type_id; ?>]"
                                                            onkeyup="summary()" class="form-control deducamount"
                                                            id="dd_<?php echo $y;?>" placeholder="Enter %">
                                                    </td>
                                                </tr>
                                                <?php
                    $y++;
                    }
                    ?>
                                                <tr>
                                                    <th style="padding:10px"><?php echo display('tax')?> (%)</th>
                                                    <td colspan="2">
                                                        <input type="text" name="amount[]" onkeyup="summary()"
                                                            class="form-control deducamount" id="taxinput">
                                                        <input type="checkbox" name="tax_manager" id="taxmanager"
                                                            onchange='handletax(this);' value="1"> Tax Manager
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>

                                    </div>
                                </table>

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
</div>

<script type="text/javascript">
// Toggle input placeholder based on calculation type
function toggleInputType(selectElement, inputId) {
    var inputField = document.getElementById(inputId);
    if (selectElement.value == '1') {
        inputField.placeholder = 'Enter Amount';
    } else {
        inputField.placeholder = 'Enter %';
    }
    summary(); // Recalculate when type changes
}

function summary() {
    var addper = 0;
    $(".addamount").each(function() {
        isNaN(this.value) || 0 == this.value.length || (addper += parseFloat(this.value))
    });

    var b = parseInt($('#basic').val()) || 0;
    var add = 0;
    var deduct = 0;

    // Calculate additions
    $(".addamount").each(function(index) {
        var value = parseFloat(this.value) || 0;
        var basic = parseInt($('#basic').val()) || 0;
        var calcType = $(this).closest('tr').find('.calc-type').val();

        if (calcType == '1') {
            // Amount-based calculation
            add += value;
        } else {
            // Percentage-based calculation
            add += (value * basic / 100);
        }
    });

    // Calculate deductions
    $(".deducamount").each(function() {
        var value = parseFloat(this.value) || 0;
        var basic = parseInt($('#basic').val()) || 0;
        var calcType = $(this).closest('tr').find('.calc-type').val();

        if (calcType == '1') {
            // Amount-based calculation
            deduct += value;
        } else {
            // Percentage-based calculation
            deduct += (value * basic / 100);
        }
    });

    document.getElementById('grsalary').value = Math.round(add + b - deduct);
}

function handletax(checkbox) {
    var deduct = 0;
    var add = 0;
    var b = parseInt($('#basic').val()) || 0;

    $(".deducamount").each(function() {
        var value = parseFloat(this.value) || 0;
        var basic = parseInt($('#basic').val()) || 0;
        var calcType = $(this).closest('tr').find('.calc-type').val();

        if (calcType == '1') {
            deduct += value;
        } else {
            deduct += (value * basic / 100);
        }
    });

    $(".addamount").each(function() {
        var value = parseFloat(this.value) || 0;
        var basic = parseInt($('#basic').val()) || 0;
        var calcType = $(this).closest('tr').find('.calc-type').val();

        if (calcType == '1') {
            add += value;
        } else {
            add += (value * basic / 100);
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
                document.getElementById('grsalary').value = add + b - data - deduct;
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
            var count = $('#add tr').length;
            for (var i = 0; i < count; i++) {
                var elem = document.getElementById('add_' + i);
                if (elem) elem.value = '';
            }

            var dt = $('#dduct tr').length;
            for (var i = 0; i < dt; i++) {
                var elem = document.getElementById('dd_' + i);
                if (elem) elem.value = '';
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}
</script>