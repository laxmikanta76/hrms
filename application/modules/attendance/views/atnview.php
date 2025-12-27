<style type="text/css">
.attendance .nav li.active a,
.attendance .nav li a:hover,
.attendance .nav li.active a:focus,
.attendance .nav li.active a:hover {
    background-color: green;
    color: #fff;
}

.attendance .nav li a {
    background-color: #8FBC8F;
    color: #fff;
}

.attendance {
    margin-left: 20px;
}
</style>
<?php  $timezone = $this->db->select('timezone')->from('setting')->get()->row();
                               date_default_timezone_set($timezone->timezone); ?>
<div class="form-group text-right">
    <?php if($this->permission->method('attendance','create')->access()): ?>
    <?php  if($this->session->userdata('isAdmin')==1 || $this->session->userdata('supervisor')==1){?>
    <button type="button" class="btn btn-primary btn-md" data-target="#add1" data-toggle="modal">
        <i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo display('bulk_checkin')?></button>
    <?php } ?>
    <?php endif; ?>
</div>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel ">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo display('attendance') ?></h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row attendance" style="margin-bottom: 50px;">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#checkin"><?php echo display('check_in') ?></a>
                        </li>
                        <li><a data-toggle="tab" href="#checkout"><?php echo display('check_out') ?></a></li>
                    </ul>
                </div>
                <div class="tab-content">
                    <!-- CHECK IN TAB -->
                    <div id="checkin" class="tab-pane fade in active">
                        <?= form_open('attendance/Home/create_atten') ?>
                        <input type="hidden" name="punch_type" value="in">
                        <div class="form-group row">
                            <input type="hidden" name="attendanc_id"
                                value="<?php echo (!empty($editdata)?$editdata->atten_his_id:'')?>">
                            <label for="employee_id_in" class="col-sm-3 col-form-label"><?php echo display('emp_id') ?>
                                *</label>
                            <div class="col-sm-4">
                                <?php if (can_select_employee()): ?>
                                <!-- ADMIN / HR / SUPERVISOR -->
                                <?php echo form_dropdown('employee_id',$dropdownatn,(!empty($editdata)?$editdata->uid:''),'class="form-control" id="employee_id_in" style="width:100%"'); ?>
                                <?php else: ?>
                                <!-- EMPLOYEE -->
                                <input type="text" name="employee_name" class="form-control"
                                    value="<?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name'); ?>"
                                    readonly>
                                <input type="hidden" name="employee_id"
                                    value="<?php echo $this->session->userdata('employee_id'); ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="intime" class="col-sm-3 col-form-label"><?php echo display('time')?>*</label>
                            <div class="col-sm-4">
                                <input type="text" name="intime" class="form-control"
                                    value="<?php echo date('Y-m-d H:i:s'); ?>" readonly
                                    style="background-color:#f5f5f5; cursor:not-allowed;">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Location (Auto)</label>
                            <div class="col-sm-4">
                                <input type="text" id="location_display_in" class="form-control" readonly
                                    placeholder="Getting location...">
                            </div>
                        </div>
                        <input type="hidden" name="latitude" id="latitude_in">
                        <input type="hidden" name="longitude" id="longitude_in">
                        <div class="form-group text-center">
                            <button type="submit" id="checkin_btn" class="btn btn-success w-md m-b-5">
                                <?php echo display('check_in') ?>
                            </button>
                        </div>
                        <?php echo form_close() ?>
                    </div>

                    <!-- CHECK OUT TAB -->
                    <div id="checkout" class="tab-pane fade">
                        <?= form_open('attendance/Home/checkout_atten') ?>
                        <input type="hidden" name="punch_type" value="out">
                        <div class="form-group row">
                            <label for="employee_id_out" class="col-sm-3 col-form-label"><?php echo display('emp_id') ?>
                                *</label>
                            <div class="col-sm-4">
                                <?php if (can_select_employee()): ?>
                                <!-- ADMIN / HR / SUPERVISOR -->
                                <?php echo form_dropdown('employee_id',$dropdownatn,'','class="form-control" id="employee_id_out" style="width:100%"'); ?>
                                <?php else: ?>
                                <!-- EMPLOYEE -->
                                <input type="text" name="employee_name" class="form-control"
                                    value="<?php echo $this->session->userdata('first_name').' '.$this->session->userdata('last_name'); ?>"
                                    readonly>
                                <input type="hidden" name="employee_id"
                                    value="<?php echo $this->session->userdata('employee_id'); ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="outtime" class="col-sm-3 col-form-label"><?php echo display('time')?>*</label>
                            <div class="col-sm-4">
                                <input type="text" name="outtime" class="form-control"
                                    value="<?php echo date('Y-m-d H:i:s'); ?>" readonly
                                    style="background-color:#f5f5f5; cursor:not-allowed;">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Location (Auto)</label>
                            <div class="col-sm-4">
                                <input type="text" id="location_display_out" class="form-control" readonly
                                    placeholder="Getting location...">
                            </div>
                        </div>
                        <input type="hidden" name="latitude" id="latitude_out">
                        <input type="hidden" name="longitude" id="longitude_out">
                        <div class="form-group text-center">
                            <button type="submit" id="checkout_btn" class="btn btn-danger w-md m-b-5">
                                <?php echo display('check_out') ?>
                            </button>
                        </div>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div id="add1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color:green;color:white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <strong><?php echo display('add_attendance')?></strong>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top:50px">
                    <br>
                    <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($this->session->flashdata('success') == TRUE): ?>
                    <div class="form-control alert alert-success"><?php echo $this->session->flashdata('success'); ?>
                    </div>
                    <?php endif; ?>
                    <h3><?php echo display('download_sample_file')?>
                        <a href="<?php echo base_url('assets/data/bulkdata/bulkattendance.xlsx') ?>"
                            class="btn btn-primary">
                            <i class="fa fa-download"></i> <?php echo display('download_sample_file')?>
                        </a>
                    </h3>
                    <?php echo form_open_multipart('attendance/Home/importcsv',array('class' => 'form-vertical', 'id' => 'validate','name' => 'insert_attendance'))?>
                    <input type="file" name="upload_csv_file" id="userfile"><br><br>
                    <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
                    <?php echo form_close()?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    $('.datetimepicker').datetimepicker({
        use24hours: true,
        format: 'yyyy-mm-dd HH:mm'
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // CHECK IN - Get Location
    const checkinBtn = document.getElementById('checkin_btn');
    if (checkinBtn) {
        checkinBtn.disabled = true;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('latitude_in').value = position.coords.latitude;
                    document.getElementById('longitude_in').value = position.coords.longitude;
                    document.getElementById('location_display_in').value =
                        position.coords.latitude.toFixed(6) + ', ' + position.coords.longitude.toFixed(6);
                    checkinBtn.disabled = false;
                },
                function(error) {
                    console.log("Check-in location error:", error);
                    document.getElementById('location_display_in').value = "Location unavailable";
                    // Allow check-in even without location
                    checkinBtn.disabled = false;
                }
            );
        } else {
            document.getElementById('location_display_in').value = "Geolocation not supported";
            checkinBtn.disabled = false;
        }
    }

    // CHECK OUT - Get Location
    const checkoutBtn = document.getElementById('checkout_btn');
    if (checkoutBtn) {
        checkoutBtn.disabled = true;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('latitude_out').value = position.coords.latitude;
                    document.getElementById('longitude_out').value = position.coords.longitude;
                    document.getElementById('location_display_out').value =
                        position.coords.latitude.toFixed(6) + ', ' + position.coords.longitude.toFixed(6);
                    checkoutBtn.disabled = false;
                },
                function(error) {
                    console.log("Check-out location error:", error);
                    document.getElementById('location_display_out').value = "Location unavailable";
                    // Allow check-out even without location
                    checkoutBtn.disabled = false;
                }
            );
        } else {
            document.getElementById('location_display_out').value = "Geolocation not supported";
            checkoutBtn.disabled = false;
        }
    }
});
</script>