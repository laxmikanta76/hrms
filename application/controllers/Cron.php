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
        $this->load->model('attendance/Csv_model');
        
        
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

    /**
     * Auto Checkout for Missing Checkouts
     * Run this at 9:05 PM every day
     * 
     * Cron: 5 21 * * * (Every day at 9:05 PM)
     * URL: https://yourdomain.com/cron/auto_checkout?token=YOUR_TOKEN
     * 
     * This will:
     * 1. Find all employees who checked in today but didn't check out
     * 2. Auto checkout them at 6:00 PM with office location
     */
    public function auto_checkout()
    {
        $today_date = date('Y-m-d');
        $checkout_time = $today_date . ' 18:00:00'; // 6:00 PM
        
        echo "Running Auto Checkout for: $today_date\n";
        
        // Get office location
        $office = $this->db->select('Latitude, Longitude')
            ->from('setting')
            ->get()
            ->row();
        
        if (empty($office)) {
            echo "ERROR: Office location not configured!\n";
            log_message('error', 'Auto checkout failed: Office location not set');
            return false;
        }
        
        $office_lat = $office->Latitude;
        $office_lng = $office->Longitude;
        
        // Find all check-ins for today
        $this->db->select('uid, MAX(time) as last_checkin, MAX(atten_his_id) as last_id');
        $this->db->from('attendance_history');
        $this->db->where('DATE(time)', $today_date);
        $this->db->where('state', 1); // Check-in
        $this->db->group_by('uid');
        $checkins = $this->db->get()->result();
        
        $auto_checkout_count = 0;
        $already_checkedout = 0;
        
        foreach ($checkins as $checkin) {
            // Check if this employee has checked out today
            $checkout_exists = $this->db->select('atten_his_id')
                ->from('attendance_history')
                ->where('uid', $checkin->uid)
                ->where('DATE(time)', $today_date)
                ->where('state', 0) // Check-out
                ->where('time >', $checkin->last_checkin)
                ->get()
                ->row();
            
            if (!$checkout_exists) {
                // No checkout found - insert auto checkout
                $auto_checkout = array(
                    'uid'       => $checkin->uid,
                    'state'     => 0, // Check-out
                    'id'        => 0,
                    'time'      => $checkout_time,
                    'latitude'  => $office_lat,
                    'longitude' => $office_lng,
                    'remarks'   => 'Auto checkout by system'
                );
                
                if ($this->db->insert('attendance_history', $auto_checkout)) {
                    $auto_checkout_count++;
                    echo "Auto checkout: Employee ID {$checkin->uid} at 6:00 PM\n";
                    log_message('info', "Auto checkout for employee {$checkin->uid} at {$checkout_time}");
                } else {
                    echo "FAILED: Employee ID {$checkin->uid}\n";
                    log_message('error', "Auto checkout failed for employee {$checkin->uid}");
                }
            } else {
                $already_checkedout++;
            }
        }
        
        echo "\n=== Summary ===\n";
        echo "Total check-ins today: " . count($checkins) . "\n";
        echo "Already checked out: $already_checkedout\n";
        echo "Auto checkouts created: $auto_checkout_count\n";
        
        log_message('info', "Auto checkout summary: {$auto_checkout_count} auto checkouts out of " . count($checkins) . " check-ins");
        
        return true;
    }

    /**
     * Manual Test Auto Checkout
     * URL: https://yourdomain.com/cron/test_auto_checkout?token=YOUR_TOKEN&date=2026-01-28
     */
    public function test_auto_checkout()
    {
        $test_date = $this->input->get('date') ?: date('Y-m-d');
        
        echo "TEST MODE: Auto Checkout for: $test_date\n\n";
        
        // Get office location
        $office = $this->db->select('Latitude, Longitude')
            ->from('setting')
            ->get()
            ->row();
        
        if (empty($office)) {
            echo "ERROR: Office location not configured!\n";
            return;
        }
        
        // Find check-ins for test date
        $this->db->select('uid, MAX(time) as last_checkin');
        $this->db->from('attendance_history');
        $this->db->where('DATE(time)', $test_date);
        $this->db->where('state', 1);
        $this->db->group_by('uid');
        $checkins = $this->db->get()->result();
        
        echo "Check-ins found: " . count($checkins) . "\n\n";
        
        foreach ($checkins as $checkin) {
            $checkout_exists = $this->db->select('atten_his_id')
                ->from('attendance_history')
                ->where('uid', $checkin->uid)
                ->where('DATE(time)', $test_date)
                ->where('state', 0)
                ->where('time >', $checkin->last_checkin)
                ->get()
                ->row();
            
            if (!$checkout_exists) {
                echo "Employee {$checkin->uid}: MISSING CHECKOUT (would auto checkout)\n";
            } else {
                echo "Employee {$checkin->uid}: Already checked out\n";
            }
        }
    }
    
}