<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function can_select_employee()
{
    $CI =& get_instance();

    $is_admin = $CI->session->userdata('is_admin');
    $role_id  = $CI->session->userdata('role_id');

    // Roles that can select employee
    $allowed_roles = [7, 8, 9];
    // 8 = HR
    // 9 = Supervisor
    // 10 = Manager

    return ($is_admin == 1 || in_array($role_id, $allowed_roles));
}