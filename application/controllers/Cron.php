<?php
class Cron extends CI_Controller { 
    
    public function monthly_leave_credit() { 
    $employees=$this->db->get('employee_leave')->result();

    foreach ($employees as $row) {

    // Sick Leave → reset every month
    $new_sick = 1;

    // Casual Leave → carry forward +2
    $new_casual = $row->casual_balance + 1;

    $this->db->where('id', $row->id)->update('employee_leave', [
    'sick_balance' => $new_sick,
    'casual_balance' => $new_casual,
    'last_updated' => date('Y-m-d')
    ]);
    }
    }
    }