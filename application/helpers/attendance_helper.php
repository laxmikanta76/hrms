<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function can_manage_attendance()
{
    $CI =& get_instance();

    $is_admin = $CI->session->userdata('is_admin');
    $role_id  = $CI->session->userdata('role_id');
    $supervisor=$this->session->userdata('supervisor');
    

    // Roles allowed to edit/delete attendance
    $allowed_roles = [7, 8, 9]; // HR, Supervisor, Manager

    return ($is_admin == 1 ||  $supervisor == 1 ||in_array($role_id, $allowed_roles));
}