<?php

$HmvcMenu["leave"] = array(
    "icon" => "<i class='fa fa-plane'></i>",
);

// WEEKLY HOLIDAY (Employee can only read)
if ($this->permission->check_label("weekly_holiday")->read()->access()) {
    $HmvcMenu["leave"]["weekly_holiday"] = array(
        "controller" => "Leave",
        "method"     => "weekly_leave_view",
        "permission" => "read"
    );
}

// HOLIDAY LIST (Employee can only read)
if ($this->permission->check_label("holiday")->read()->access()) {
    $HmvcMenu["leave"]["holiday"] = array(
        "controller" => "Leave",
        "method"     => "holiday_view",
        "permission" => "read"
    );
}

// LEAVE APPLICATION (All roles)
if ($this->permission->check_label("leave_application")->read()->access()) {
    $HmvcMenu["leave"]["leave_application"] = array(
        "controller" => "Leave",
        "method"     => "others_leave",
        "permission" => "read"
    );
}

// ADD LEAVE TYPE – Only for Admin + HR
if ($this->session->userdata('isAdmin') == 1 ||
    $this->session->userdata('role_id') == 8)
{
    $HmvcMenu["leave"]["add_leave_type"] = array(
        "controller" => "Leave",
        "method"     => "add_leave_type",
        "permission" => "create"
    );
}

?>