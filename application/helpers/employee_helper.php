<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function can_select_employee()
{
    $CI =& get_instance();

    $is_admin = $CI->session->userdata('is_admin');
    $role_id  = $CI->session->userdata('role_id');
    $supervisor=$CI->session->userdata('supervisor');

    // Roles that can select employee
    $allowed_roles = [7, 8, 9];
    // 7 = Employee
    // 8 = HR
    // 9 = Admin

    return ($is_admin == 1 || $supervisor == 1 || in_array($role_id, $allowed_roles));
}