<style type="text/css">
.close {
    color: red;
}

.close:hover {
    color: red;
}
</style>
<div class="form-group text-right">
    <?php if($this->permission->method('leave','create')->access()): ?>
    <button type="button" class="btn btn-primary btn-md" data-target="#add" data-toggle="modal"><i
            class="fa fa-plus-circle" aria-hidden="true"></i>
        <?php echo display('others_leave_application');?></button>
    <?php endif; ?>
    <?php if($this->permission->method('leave','read')->access()): ?>
    <?php  $is_admin = $this->session->userdata('is_admin');
           $role_id  = $this->session->userdata('role_id');
           $supervisor=$this->session->userdata('supervisor');
           // 8 = HR, 9 = Supervisor
        if ($is_admin == 1 || $supervisor==1 || in_array($role_id, [8, 9])) {?>
    <a href="<?php echo base_url();?>/leave/Leave/application_view"
        class="btn btn-primary"><?php echo display('manage_application');?></a>
    <?php } ?>
    <?php endif; ?>
</div>
<!--  -->

<div class="row">
    <!--  table area -->
    <div class="col-sm-12">

        <div class="panel panel-default thumbnail">

            <div class="panel-body">
                <table width="100%" class="datatable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><?php echo display('cid') ?></th>
                            <th><?php echo display('name') ?></th>
                            <th><?php echo display('leave_type') ?></th>
                            <th><?php echo display('apply_strt_date') ?></th>
                            <th><?php echo display('apply_end_date') ?></th>
                            <th><?php echo display('leave_aprv_strt_date') ?></th>
                            <th><?php echo display('leave_aprv_end_date') ?></th>
                            <th><?php echo display('apply_day') ?></th>
                            <th><?php echo display('num_aprv_day') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($mang)) { ?>
                        <?php $sl = 1; ?>
                        <?php foreach ($mang as $row) { ?>
                        <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $row->first_name.' '.$row->last_name?></td>
                            <td><?php echo $row->leave_type; ?></td>
                            <td><?php echo $row->apply_strt_date; ?></td>
                            <td><?php echo $row->apply_end_date; ?></td>
                            <td><?php echo $row->leave_aprv_strt_date; ?></td>
                            <td><?php echo $row->leave_aprv_end_date; ?></td>
                            <td><?php echo $row->apply_day; ?></td>
                            <td><?php echo $row->num_aprv_day; ?></td>
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


<div id="add" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: green;color:white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <center><strong>Leave Application Form</strong></center>
            </div>
            <div class="modal-body">


                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-title">

                                </div>
                            </div>
                            <div class="panel-body">

                                <?= form_open_multipart('leave/Leave/others_leave') ?>


                                <div class="form-group row">
                                    <label for="employee_id" class="col-sm-2 col-form-label">Select
                                        <?php echo display('employee_name') ?></label>
                                    <div class="col-sm-4">
                                        <?php
                                         $this->load->helper('employee');
                                         $emp_id   = $this->session->userdata('employee_id');
                                         $emp_name = $this->session->userdata('first_name').' '.$this->session->userdata('last_name');
                                        ?>

                                        <?php if (can_select_employee()): ?>
                                        <!-- ADMIN / HR / SUPERVISOR -->
                                        <?php echo form_dropdown('employee_id',$dropdownatn,(!empty($editdata)?$editdata->uid:''),'class="form-control" id="employee_id" style="width:100%"'); ?>
                                        <?php else: ?>
                                        <!-- REGULAR EMPLOYEE -->
                                        <input type="text" name="employee_name" class="form-control"
                                            value="<?php echo $emp_name; ?>" readonly>
                                        <input type="hidden" name="employee_id" id="employee_id_hidden"
                                            value="<?php echo $emp_id; ?>">
                                        <?php endif; ?>
                                    </div>

                                    <label for="leave_type" class="col-sm-2 col-form-label">Select
                                        <?php echo display('leave_type') ?></label>
                                    <div class="col-sm-4">
                                        <?php echo form_dropdown('leave_type_id',$type,null,'class="form-control" id="leave_type_id" style="width:100%"'); ?>
                                        <div style="margin-top: 5px;">
                                            <span id="enjoy" style="color: #d9534f; font-weight: bold;"></span><br>
                                            <span id="checkleave" style="color: #5cb85c; font-weight: bold;"></span>
                                        </div>
                                    </div>

                                    <input type="hidden" name="apply_date" class="form-control" id="f"
                                        value="<?php echo date('Y-m-d')?>">
                                </div>
                                <div class="form-group row">
                                    <!-- for leave leave type -->

                                    <label for="apply_strt_date" class="col-sm-2 col-form-label">
                                        <?php echo display('apply_strt_date') ?> </label>
                                    <div class="col-sm-4">
                                        <input type="text" name="apply_strt_date"
                                            class="datepicker form-control apply_start" id="apply_start"
                                            placeholder="<?php echo display('apply_strt_date') ?>" required>
                                    </div>
                                    <label for="apply_end_date" class="col-sm-2 col-form-label">
                                        <?php echo display('apply_end_date') ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="apply_end_date"
                                            class="datepicker form-control apply_end" id="apply_end"
                                            placeholder="<?php echo display('apply_end_date') ?>" required>

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="apply_day" class="col-sm-2 col-form-label">
                                        <?php echo display('apply_day') ?> </label>
                                    <div class="col-sm-4">
                                        <input type="text" name="apply_day" class="form-control apply_day"
                                            id="apply_day" placeholder="<?php echo display('apply_day') ?>" readonly>
                                    </div>
                                    <label for="apply_hard_copy" class="col-sm-2 col-form-label">
                                        <?php echo display('apply_hard_copy') ?></label>
                                    <div class="col-sm-4">
                                        <input type="file" name="apply_hard_copy" class="form-control">

                                    </div>
                                </div>
                                <?php  $is_admin = $this->session->userdata('is_admin');
                                       $role_id  = $this->session->userdata('role_id');

                                      // 8 = HR, 9 = Supervisor
                                      if ($is_admin == 1 || in_array($role_id, [8, 9])) {?>
                                <div class="form-group row">
                                    <label for="leave_aprv_strt_date" class="col-sm-2 col-form-label">
                                        <?php echo display('leave_aprv_strt_date') ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="leave_aprv_strt_date"
                                            class="datepicker form-control leave_aprv_strt_date"
                                            id="leave_aprv_strt_date"
                                            placeholder="<?php echo display('leave_aprv_strt_date') ?>" required>

                                    </div>
                                    <label for="leave_aprv_end_date" class="col-sm-2 col-form-label">
                                        <?php echo display('leave_aprv_end_date') ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="leave_aprv_end_date"
                                            class="datepicker form-control leave_aprv_end_date" id="leave_aprv_end_date"
                                            placeholder="<?php echo display('leave_aprv_end_date') ?>" required>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="num_aprv_day" class="col-sm-2 col-form-label">
                                        <?php echo display('num_aprv_day') ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="num_aprv_day" class="form-control num_aprv_day"
                                            placeholder="<?php echo display('num_aprv_day') ?>" readonly>

                                    </div>
                                    <label for="approved_by" class="col-sm-2 col-form-label">
                                        <?php echo display('approved_by') ?></label>
                                    <div class="col-sm-4">
                                        <select name="approved_by" class="form-control" style="width:100%">
                                            <option value="">Select One</option>
                                            <?php foreach($supr as $supervisor){?>
                                            <option value="<?php echo $supervisor->employee_id;?>">
                                                <?php echo $supervisor->first_name.' '.$supervisor->last_name;?>
                                            </option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group row">

                                    <label for="reason"
                                        class="col-sm-2 col-form-label"><?php echo display('reason') ?></label>
                                    <div class="col-sm-10">
                                        <textarea name="reason" class="form-control"
                                            placeholder="<?php echo display('reason') ?>" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <input type="hidden" name="approve_date" class="form-control"
                                            value="<?php echo date('Y-m-d')?>">
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="reset"
                                        class="btn btn-primary w-md m-b-5"><?php echo display('reset') ?></button>
                                    <button type="submit"
                                        class="btn btn-success w-md m-b-5"><?php echo display('save') ?></button>
                                </div>
                                <?php echo form_close() ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">

        </div>

    </div>

</div>



<script language="javascript">
$(function() {
    $("#f").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $("#e").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $("#a").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $("#c").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $("#d").datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $("#b").datepicker({
        dateFormat: 'yy-mm-dd'
    });
});

// Calculate approved days (excluding weekends)
$(document).ready(function(e) {
    function calculation() {
        var date1 = new Date($('.leave_aprv_strt_date').val());
        var date2 = new Date($('.leave_aprv_end_date').val());
        var from = new Date($('.leave_aprv_strt_date').val());
        var to = new Date($('.leave_aprv_end_date').val());
        var DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var d = from;
        var count = 0;
        var weekend = "<?php echo $weekend ?>";
        var w = weekend.split(',');

        while (d <= to) {
            d = new Date(d.getTime() + (24 * 60 * 60 * 1000));
            if (DAYS[d.getDay()] == w[0] || DAYS[d.getDay()] == w[1] || DAYS[d.getDay()] == w[2]) {
                count += 1;
            }
        }

        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        $('.num_aprv_day').val(diffDays + 1);
    }
    $('.leave_aprv_strt_date,.leave_aprv_end_date').change(calculation);
});

// Calculate apply days (excluding weekends)
$(document).ready(function(e) {
    function applyday() {
        var from = new Date($('.apply_start').val());
        var to = new Date($('.apply_end').val());
        var DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var d = from;
        var count = 0;
        var weekend = "<?php echo $weekend ?>";
        var w = weekend.split(',');

        while (d <= to) {
            d = new Date(d.getTime() + (24 * 60 * 60 * 1000));
            if (DAYS[d.getDay()] == w[0] || DAYS[d.getDay()] == w[1] || DAYS[d.getDay()] == w[2]) {
                count += 1;
            }
        }

        var date1 = new Date($('.apply_start').val());
        var date2 = new Date($('.apply_end').val());
        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        $('.apply_day').val(diffDays + 1);
    }
    $('.apply_start,.apply_end').change(applyday);
});

// Date validation
$(document).ready(function(e) {
    function datecheck() {
        var date = new Date($('#apply_start').val());
        var date1 = new Date($('#leave_aprv_strt_date').val());
        var date2 = new Date($('#leave_aprv_end_date').val());
        if (date > date1 || date > date2) {
            alert('Approved date cannot be less than apply date');
            document.getElementById('leave_aprv_strt_date').value = '';
            document.getElementById('leave_aprv_end_date').value = '';
        }
    }
    $('.leave_aprv_strt_date,.leave_aprv_end_date').change(datecheck);
});

/**
 * Get Employee ID - works for both admin dropdown and user hidden field
 */
function getEmployeeId() {
    return $('#employee_id').val() ||
        $('#employee_id_hidden').val() ||
        $('input[name="employee_id"]').val() ||
        '';
}

/**
 * Fetch and display leave balance
 */
function leavetypechange(leave_type_id) {
    var employee_id = getEmployeeId();

    // Clear previous messages
    $('#enjoy').html('');
    $('#checkleave').html('');

    if (!employee_id) {
        $('#enjoy').html('<span style="color: red;">Employee ID not found</span>');
        return;
    }

    if (!leave_type_id) {
        return;
    }

    // Show loading
    $('#enjoy').html('Loading...');

    // Dynamic AJAX call
    $.ajax({
        url: "<?php echo base_url('leave/Leave/free_leave'); ?>",
        method: 'POST',
        dataType: 'json',
        data: {
            'employee_id': employee_id,
            'leave_type': leave_type_id
        },
        success: function(data) {
            $('#enjoy').html('You Enjoyed: ' + data.enjoy + ' Days');
            $('#checkleave').html('Available Leave: ' + data.due + ' Days');
        }
    });
}

// Initialize on page load
$(document).ready(function() {
    var isAdmin = $('#employee_id').is('select');

    if (isAdmin) {
        // Admin: reload on employee change
        $('#employee_id').on('change', function() {
            var leaveType = $('#leave_type_id').val();
            if (leaveType) {
                leavetypechange(leaveType);
            }
        });
    } else {
        // User: auto-load if leave type selected
        var initialLeaveType = $('#leave_type_id').val();
        var empId = getEmployeeId();
        if (empId && initialLeaveType) {
            leavetypechange(initialLeaveType);
        }
    }

    // When leave type changes (both)
    $('#leave_type_id').on('change', function() {
        var leaveType = $(this).val();
        if (leaveType) {
            leavetypechange(leaveType);
        }
    });
});
</script>