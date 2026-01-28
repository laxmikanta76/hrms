<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cron Controller
 * Handle all automated tasks
 */
class Cron extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('leave/Leave_model');
        
        
        // Security: Only allow CLI or specific IP/token
        if (!$this->input->is_cli_request()) {
            // Check for cron token
            $token = $this->input->get('token');
            $valid_token = 'SECURE123'; // Change this!
            
            if ($token !== $valid_token) {
                show_404();
            }
        }
    }

    /**
     * Process Monthly Leave Balance
     * Run this on 1st of every month
     * 
     * Cron: 0 0 1 * * (Every 1st at midnight)
     * URL: https://yourdomain.com/cron/monthly_leave?token=YOUR_TOKEN
     */
    public function monthly_leave()
    {
        $year  = date('Y');
        $month = date('n');
        
        echo "Processing monthly leave for: $year-$month\n";
        
        $result = $this->Leave_model->process_monthly_leave($year, $month);
        
        if ($result) {
            echo "Monthly leave processed successfully!\n";
            log_message('info', "Monthly leave processed for $year-$month");
        } else {
            echo "Failed to process monthly leave.\n";
            log_message('error', "Failed to process monthly leave for $year-$month");
        }
    }

    /**
     * Reset Yearly Leave Balance
     * Run this on January 1st
     * 
     * Cron: 0 0 1 1 * (Every Jan 1st at midnight)
     * URL: https://yourdomain.com/cron/yearly_reset?token=YOUR_TOKEN
     */
    public function yearly_reset()
    {
        $year = date('Y');
        
        echo "Resetting yearly leave for: $year\n";
        
        $result = $this->Leave_model->reset_yearly_leave($year);
        
        if ($result) {
            echo "Yearly leave reset successfully!\n";
            log_message('info', "Yearly leave reset for $year");
        } else {
            echo "Failed to reset yearly leave.\n";
            log_message('error', "Failed to reset yearly leave for $year");
        }
    }

    /**
     * Manual trigger for testing
     * URL: https://yourdomain.com/cron/test_monthly?token=YOUR_TOKEN&year=2026&month=2
     */
    public function test_monthly()
    {
        $year  = $this->input->get('year') ?: date('Y');
        $month = $this->input->get('month') ?: date('n');
        
        echo "TEST: Processing monthly leave for: $year-$month\n";
        
        $result = $this->Leave_model->process_monthly_leave($year, $month);
        
        if ($result) {
            echo "Success!\n";
        } else {
            echo "Failed!\n";
        }
    }

    
    
}