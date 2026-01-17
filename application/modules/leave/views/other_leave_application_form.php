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
    <a href="<?php echo base_url();?>/leave/Leave/application_view"
        class="btn btn-primary"><?php echo display('manage_application');?></a>
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
                                        <!-- ADMIN / HR / SUPERVISOR - Show Dropdown -->
                                        <?php echo form_dropdown('employee_id',$dropdownatn,(!empty($editdata)?$editdata->uid:''),'class="form-control" id="employee_id" style="width:100%"'); ?>
                                        <?php else: ?>
                                        <!-- REGULAR EMPLOYEE - Show Name and Hidden Field -->
                                        <input type="text" name="employee_name" class="form-control"
                                            value="<?php echo $emp_name; ?>" readonly>
                                        <input type="hidden" name="employee_id" id="employee_id_hidden"
                                            value="<?php echo $emp_id; ?>">
                                        <?php endif; ?>
                                    </div>

                                    <label for="leave_type" class="col-sm-2 col-form-label">Select
                                        <?php echo display('leave_type') ?></label>
                                    <div class="col-sm-4">
                                        <?php echo form_dropdown('leave_type_id',$type,null,'class="form-control" id="leave_type_id" style="width:100%" onchange="leavetypechange(this.value)"') ?>
                                        <div style="margin-top: 5px;">
                                            <span id="enjoy" style="color: red; font-weight: bold;"></span><br>
                                            <span id="checkleave" style="color: green; font-weight: bold;"></span>
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
                                            placeholder="<?php echo display('apply_strt_date') ?>">
                                    </div>
                                    <label for="apply_end_date" class="col-sm-2 col-form-label">
                                        <?php echo display('apply_end_date') ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="apply_end_date"
                                            class="datepicker form-control apply_end" id="apply_end"
                                            placeholder="<?php echo display('apply_end_date') ?>">

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
                                <?php  if($this->session->userdata('isAdmin')==1 || $this->session->userdata('supervisor')==1){?>
                                <div class="form-group row">
                                    <label for="leave_aprv_strt_date" class="col-sm-2 col-form-label">
                                        <?php echo display('leave_aprv_strt_date') ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="leave_aprv_strt_date"
                                            class="datepicker form-control leave_aprv_strt_date"
                                            id="leave_aprv_strt_date"
                                            placeholder="<?php echo display('leave_aprv_strt_date') ?>">

                                    </div>
                                    <label for="leave_aprv_end_date" class="col-sm-2 col-form-label">
                                        <?php echo display('leave_aprv_end_date') ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="leave_aprv_end_date"
                                            class="datepicker form-control leave_aprv_end_date" id="leave_aprv_end_date"
                                            placeholder="<?php echo display('leave_aprv_end_date') ?>">

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
                                            placeholder="<?php echo display('reason') ?>"></textarea>
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
</script>
<script language="javascript">
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
        var w = weekend.split(',')
        //alert(w[0]);
        while (d <= to) {
            d = new Date(d.getTime() + (24 * 60 * 60 * 1000));
            if (DAYS[d.getDay()] == w[0] || DAYS[d.getDay()] == w[1] || DAYS[d.getDay()] == w[2]) {
                count += 1;
            }
        }

        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) - count;
        $('.num_aprv_day').val(diffDays + 1);
    }
    $('.leave_aprv_strt_date,.leave_aprv_end_date').change(calculation);
});
$(document).ready(function(e) {
    function applyday() {

        var from = new Date($('.apply_start').val());
        var to = new Date($('.apply_end').val());
        var DAYS = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        var d = from;
        var count = 0;
        var weekend = "<?php echo $weekend ?>";
        var w = weekend.split(',')
        //alert(w[0]);
        while (d <= to) {
            d = new Date(d.getTime() + (24 * 60 * 60 * 1000));
            if (DAYS[d.getDay()] == w[0] || DAYS[d.getDay()] == w[1] || DAYS[d.getDay()] == w[2]) {
                count += 1;
            }
        }
        var date1 = new Date($('.apply_start').val());
        var date2 = new Date($('.apply_end').val());
        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) - count;
        $('.apply_day').val(diffDays + 1);
    }
    $('.apply_start,.apply_end').change(applyday);
});

function getEmployeeId() {
    // Try select dropdown first (admin)
    var empId = $('#employee_id').val();

    if (!empId || empId === '') {
        // Try hidden input (regular user)
        empId = $('#employee_id_hidden').val();
    }

    if (!empId || empId === '') {
        // Last resort - try any input with name employee_id
        empId = $('input[name="employee_id"]').val();
    }

    return empId;
}

/**
 * Main function to fetch and display leave balance
 */
function leavetypechange(leave_type_id) {
    console.log('=== leavetypechange called ===');
    console.log('Leave Type ID:', leave_type_id);

    var employee_id = getEmployeeId();
    console.log('Employee ID:', employee_id);

    // Clear previous messages
    document.getElementById('enjoy').innerHTML = '';
    document.getElementById('checkleave').innerHTML = '';

    // Validate employee ID
    if (!employee_id || employee_id === '') {
        console.error('ERROR: Employee ID is empty');
        document.getElementById('enjoy').innerHTML = '<span style="color: red;">Error: Employee ID not found</span>';
        return false;
    }

    // Validate leave type
    if (!leave_type_id || leave_type_id === '') {
        console.warn('Leave type not selected');
        return false;
    }

    // Show loading indicator
    document.getElementById('enjoy').innerHTML = '<i class="fa fa-spinner fa-spin"></i> Loading...';

    // AJAX call
    $.ajax({
        url: "<?php echo base_url('leave/Leave/free_leave'); ?>",
        method: 'POST',
        dataType: 'json',
        data: {
            'employee_id': employee_id,
            'leave_type': leave_type_id
        },
        beforeSend: function() {
            console.log('Sending AJAX request:', {
                url: "<?php echo base_url('leave/Leave/free_leave'); ?>",
                employee_id: employee_id,
                leave_type: leave_type_id
            });
        },
        success: function(response) {
            console.log('AJAX Success Response:', response);

            // Handle different response formats
            var enjoy = 0;
            var due = 0;

            if (response.status === 'success' || response.status === 'warning') {
                enjoy = response.enjoy || 0;
                due = response.due || 0;
            } else if (response.status === 'error') {
                // Show error message
                document.getElementById('enjoy').innerHTML =
                    '<span style="color: red;">Error: ' + (response.message || 'Unknown error') + '</span>';
                document.getElementById('checkleave').innerHTML = '';
                return;
            } else {
                // Fallback for old format
                enjoy = response.enjoy || 0;
                due = response.due || 0;
            }

            // Display results
            document.getElementById('enjoy').innerHTML =
                '<span style="color: #d9534f; font-weight: bold;">You Enjoyed: ' + enjoy + ' Days</span>';

            var dueColor = due < 3 ? '#f0ad4e' : '#5cb85c'; // orange if low, green if good
            document.getElementById('checkleave').innerHTML =
                '<span style="color: ' + dueColor + '; font-weight: bold;">Available Leave: ' + due +
                ' Days</span>';

            console.log('Balance displayed successfully');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('=== AJAX ERROR ===');
            console.error('Status:', textStatus);
            console.error('Error:', errorThrown);
            console.error('HTTP Status Code:', jqXHR.status);
            console.error('Response Text:', jqXHR.responseText);

            var errorMsg = 'Error loading leave balance';

            // Try to parse JSON error response
            try {
                var errorData = JSON.parse(jqXHR.responseText);
                if (errorData.message) {
                    errorMsg = errorData.message;
                }
            } catch (e) {
                // Not JSON, determine error from status code
                if (jqXHR.status === 404) {
                    errorMsg = 'URL not found (404). Check configuration.';
                } else if (jqXHR.status === 500) {
                    errorMsg = 'Server error (500). Check server logs.';
                } else if (jqXHR.status === 403) {
                    errorMsg = 'Access denied (403). Check permissions.';
                } else if (jqXHR.status === 0) {
                    errorMsg = 'Network error. Check your connection.';
                } else if (textStatus === 'parsererror') {
                    errorMsg = 'Invalid response format. Expected JSON.';
                }
            }

            document.getElementById('enjoy').innerHTML =
                '<span style="color: red; font-weight: bold;">' + errorMsg + '</span>';
            document.getElementById('checkleave').innerHTML =
                '<span style="color: red;">Please contact support if issue persists</span>';
        }
    });

    return false;
}

/**
 * Initialize on page load
 */
$(document).ready(function() {
    console.log('=== Page Loaded - Initializing Leave Balance System ===');

    // Debug: Check what employee ID elements exist
    console.log('Employee ID dropdown exists:', $('#employee_id').length > 0);
    console.log('Employee ID hidden exists:', $('#employee_id_hidden').length > 0);
    console.log('Employee ID input exists:', $('input[name="employee_id"]').length > 0);

    var employee_id = getEmployeeId();
    console.log('Current Employee ID:', employee_id);

    // Determine if admin or user
    var isAdmin = $('#employee_id').is('select');
    console.log('User Type:', isAdmin ? 'Admin/HR' : 'Regular Employee');

    if (isAdmin) {
        // === ADMIN MODE ===
        console.log('Setting up Admin mode event handlers');

        // When admin selects an employee
        $('#employee_id').on('change', function() {
            var empId = $(this).val();
            var leaveType = $('#leave_type_id').val();
            console.log('Admin selected employee:', empId);

            if (empId && leaveType) {
                leavetypechange(leaveType);
            }
        });
    } else {
        // === USER MODE ===
        console.log('Setting up User mode');

        // Auto-load if leave type already selected
        var initialLeaveType = $('#leave_type_id').val();
        if (employee_id && initialLeaveType) {
            console.log('Auto-loading balance for initial selection');
            leavetypechange(initialLeaveType);
        }
    }

    // When leave type changes (works for both admin and user)
    $('#leave_type_id').on('change', function() {
        var leaveType = $(this).val();
        console.log('Leave type changed to:', leaveType);

        if (leaveType) {
            leavetypechange(leaveType);
        }
    });

    console.log('=== Initialization Complete ===');
});

// function leavetypechange(id) {


//     console.log('leavetypechange called with id:', id);

//     var leave_type = id;
//     var employee_id = $('#employee_id').val();

//     console.log('Employee ID:', employee_id);
//     console.log('Leave Type:', leave_type);

//     // Clear previous messages
//     $('#enjoy').html('');
//     $('#checkleave').html('');

//     // Validate inputs
//     if (!employee_id) {
//         $('#enjoy').html('<span style="color: red;">Please select an employee first</span>');
//         return;
//     }

//     if (!leave_type) {
//         $('#enjoy').html('<span style="color: red;">Please select a leave type</span>');
//         return;
//     }

//     // Show loading
//     $('#enjoy').html('Loading...');
//     $('#checkleave').html('');

//     // Make AJAX call
//     $.ajax({
//         url: " echo base_url('leave/Leave/free_leave'); ?>",
//         method: 'POST',
//         dataType: 'json',
//         data: {
//             'employee_id': employee_id,
//             'leave_type': leave_type
//         },
//         beforeSend: function() {
//             console.log('Sending AJAX request...');
//         },
//         success: function(data) {
//             console.log('AJAX Response:', data);

//             if (data.status === 'success' || data.status === 'warning') {
//                 $('#enjoy').html('You Enjoyed: ' + data.enjoy + ' Days');
//                 $('#checkleave').html('Available Leave: ' + data.due + ' Days');

//                 // Color coding based on balance
//                 if (data.due < 3) {
//                     $('#checkleave').css('color', 'orange');
//                 } else {
//                     $('#checkleave').css('color', 'green');
//                 }
//             } else {
//                 $('#enjoy').html('<span style="color: red;">Error: ' + data.message + '</span>');
//                 $('#checkleave').html('');
//             }
//         },
//         error: function(jqXHR, textStatus, errorThrown) {
//             console.error('AJAX Error Details:');
//             console.error('Status:', textStatus);
//             console.error('Error:', errorThrown);
//             console.error('Response Text:', jqXHR.responseText);
//             console.error('Status Code:', jqXHR.status);

//             // Try to parse error response
//             var errorMsg = 'Error loading leave balance';
//             try {
//                 var errorData = JSON.parse(jqXHR.responseText);
//                 errorMsg = errorData.message || errorMsg;
//             } catch (e) {
//                 // If not JSON, show status text
//                 if (jqXHR.status === 404) {
//                     errorMsg = 'URL not found (404). Check your base_url configuration.';
//                 } else if (jqXHR.status === 500) {
//                     errorMsg = 'Server error (500). Check PHP error logs.';
//                 } else if (jqXHR.status === 0) {
//                     errorMsg = 'Network error. Check your internet connection.';
//                 }
//             }

//             $('#enjoy').html('<span style="color: red;">' + errorMsg + '</span>');
//             $('#checkleave').html('<span style="color: red;">Please try again or contact support</span>');
//         }
//     });
// }

// // Trigger when employee is selected (for admin)
// $(document).ready(function() {
//     console.log('Document ready');

//     // When employee changes
//     $('#employee_id').on('change', function() {
//         var leave_type_id = $('select[name="leave_type_id"]').val();
//         console.log('Employee changed, leave type:', leave_type_id);
//         if (leave_type_id) {
//             leavetypechange(leave_type_id);
//         }
//     });

//     // When leave type changes
//     $('select[name="leave_type_id"]').on('change', function() {
//         var leave_type_id = $(this).val();
//         console.log('Leave type changed:', leave_type_id);
//         leavetypechange(leave_type_id);
//     });
// });

//     var leave_type = id;
//     var employee_id = $('#employee_id').val();
//     $.ajax({
//         url: " echo base_url('leave/Leave/free_leave')?>",
//         method: 'post',
//         dataType: 'json',
//         data: {
//             'employee_id': employee_id,
//             'leave_type': id
//         },
//         success: function(data) {
//             document.getElementById('enjoy').innerHTML = 'You Enjoyed : ' + data.enjoy + ' Ds';
//             document.getElementById('checkleave').innerHTML = 'Total Leave : ' + data.due + ' Ds';
//         },
//         error: function(jqXHR, textStatus, errorThrown) {
//             alert('Error get data from ajax');
//         }
//     });
// }

// $(document).ready(function(e) {
//     function datecheck() {
//         var date = new Date($('#apply_start').val());
//         var date1 = new Date($('#leave_aprv_strt_date').val());
//         var date2 = new Date($('#leave_aprv_end_date').val());
//         if (date > date1 || date > date2) {
//             alert('Can not greater than');
//             document.getElementById('leave_aprv_strt_date').value = '';
//             document.getElementById('leave_aprv_end_date').value = '';
//         }
//     }
//     $('.leave_aprv_strt_date,.leave_aprv_end_date').change(datecheck);
// });
//
</script>
<!-- Add this at the bottom of other_leave_application_form.php for debugging -->
<script>
// Quick test button (remove after debugging)
$(document).ready(function() {
    // Add test button after page loads
    setTimeout(function() {
        var testButton = $('<button>')
            .text('🔍 Test Leave Balance')
            .css({
                'position': 'fixed',
                'bottom': '20px',
                'right': '20px',
                'padding': '10px 20px',
                'background': '#337ab7',
                'color': 'white',
                'border': 'none',
                'border-radius': '5px',
                'cursor': 'pointer',
                'z-index': '9999',
                'font-weight': 'bold'
            })
            .click(function(e) {
                e.preventDefault();

                console.log('=== MANUAL TEST TRIGGERED ===');

                // Get current values
                var empId = getEmployeeId();
                var leaveType = $('#leave_type_id').val();

                console.log('Employee ID:', empId);
                console.log('Leave Type:', leaveType);

                if (!empId) {
                    alert('Employee ID is missing!\n\nCheck console for details.');
                    return;
                }

                if (!leaveType) {
                    alert('Please select a leave type first');
                    return;
                }

                // Trigger the function
                leavetypechange(leaveType);
            });

        $('body').append(testButton);

        // Add debug info panel
        var debugInfo = $('<div>')
            .css({
                'position': 'fixed',
                'bottom': '70px',
                'right': '20px',
                'padding': '10px',
                'background': '#f8f9fa',
                'border': '1px solid #ddd',
                'border-radius': '5px',
                'font-size': '11px',
                'max-width': '300px',
                'z-index': '9998'
            })
            .html(
                '<strong>Debug Info:</strong><br>' +
                'Employee ID: <span id="debug_emp">' + getEmployeeId() + '</span><br>' +
                'Leave Type: <span id="debug_leave">' + $('#leave_type_id').val() + '</span><br>' +
                'User Type: ' + ($('#employee_id').is('select') ? 'Admin' : 'User') + '<br>' +
                '<small>Press F12 to see console logs</small>'
            );

        $('body').append(debugInfo);

        // Update debug info when values change
        $('#leave_type_id').on('change', function() {
            $('#debug_leave').text($(this).val());
        });

        $('#employee_id').on('change', function() {
            $('#debug_emp').text($(this).val());
        });

    }, 1000);
});
</script>