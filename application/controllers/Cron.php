<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function monthly_leave_process()
    {
        if ($this->input->get('token') !== 'SECURE123') {
            show_error('Unauthorized', 401);
        }

        // HMVC model load
        $this->load->model('leave/Leave_model');

        // 🔥 USE CORRECT EMPLOYEE TABLE HERE
        $employees = $this->db
            ->select('id as employee_id')
            ->from('user')
            ->where('status', 1)
            ->where('is_admin', 0)
            ->get()
            ->result();

        $leaveTypes = $this->db->get('leave_type')->result();

        $year  = date('Y');
        $month = date('n');

        foreach ($employees as $emp) {
            foreach ($leaveTypes as $lt) {
                $this->Leave_model->ensure_monthly_balance(
                    $emp->employee_id,
                    $lt->leave_type_id,
                    $year,
                    $month
                );
            }
        }

        echo "Monthly leave processed successfully";
    }   

    public function yearly_reset()
    {
        if ($this->input->get('token') !== 'SECURE123') {
            show_error('Unauthorized', 401);
        }

        $this->db->update('employee_leave_balance', [
            'opening_balance' => 0,
            'used_leave'      => 0,
            'closing_balance' => 0
        ]);

        echo "Yearly leave reset completed";
    }
    
}