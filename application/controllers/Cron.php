<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function monthly_leave_process()
    {
        // security token
        if ($this->input->get('token') !== 'SECURE123') {
            show_error('Unauthorized', 401);
        }

        $this->load->model('Leave_model');

        $year  = date('Y');
        $month = date('n');

        $this->Leave_model->process_monthly_leave($year, $month);

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