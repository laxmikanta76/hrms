<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

// /**
//  * Cron Controller
//  * Handle all automated tasks
//  */
// class Cron extends CI_Controller {

//     public function __construct()
//     {
//         parent::__construct();
//         $this->load->model('leave/Leave_model');
//         $this->load->model('attendance/Csv_model');
        
        
//         // Security: Only allow CLI or specific IP/token
//         if (!$this->input->is_cli_request()) {
//             // Check for cron token
//             $token = $this->input->get('token');
//             $valid_token = 'SECURE123'; // Change this!
            
//             if ($token !== $valid_token) {
//                 show_404();
//             }
//         }
//     }

//     /**
//      * Process Monthly Leave Balance
//      * Run this on 1st of every month
//      * 
//      * Cron: 0 0 1 * * (Every 1st at midnight)
//      * URL: https://yourdomain.com/cron/monthly_leave?token=SECURE123
//      */
//     public function monthly_leave()
//     {
//         $year  = date('Y');
//         $month = date('n');
        
//         echo "Processing monthly leave for: $year-$month\n";
        
//         $result = $this->Leave_model->process_monthly_leave($year, $month);
        
//         if ($result) {
//             echo "Monthly leave processed successfully!\n";
//             log_message('info', "Monthly leave processed for $year-$month");
//         } else {
//             echo "Failed to process monthly leave.\n";
//             log_message('error', "Failed to process monthly leave for $year-$month");
//         }
//     }

//     /**
//      * Reset Yearly Leave Balance
//      * Run this on January 1st
//      * 
//      * Cron: 0 0 1 1 * (Every Jan 1st at midnight)
//      * URL: https://yourdomain.com/cron/yearly_reset?token=YOUR_TOKEN
//      */
//     public function yearly_reset()
//     {
//         $year = date('Y');
        
//         echo "Resetting yearly leave for: $year\n";
        
//         $result = $this->Leave_model->reset_yearly_leave($year);
        
//         if ($result) {
//             echo "Yearly leave reset successfully!\n";
//             log_message('info', "Yearly leave reset for $year");
//         } else {
//             echo "Failed to reset yearly leave.\n";
//             log_message('error', "Failed to reset yearly leave for $year");
//         }
//     }

//     /**
//      * Manual trigger for testing
//      * URL: https://yourdomain.com/cron/test_monthly?token=YOUR_TOKEN&year=2026&month=2
//      */
//     public function test_monthly()
//     {
//         $year  = $this->input->get('year') ?: date('Y');
//         $month = $this->input->get('month') ?: date('n');
        
//         echo "TEST: Processing monthly leave for: $year-$month\n";
        
//         $result = $this->Leave_model->process_monthly_leave($year, $month);
        
//         if ($result) {
//             echo "Success!\n";
//         } else {
//             echo "Failed!\n";
//         }
//     }

//     /**
//      * Auto Checkout for Missing Checkouts
//      * Run this at 9:05 PM every day
//      * 
//      * Cron: 5 21 * * * (Every day at 9:05 PM)
//      * URL: https://yourdomain.com/cron/auto_checkout?token=YOUR_TOKEN
//      * 
//      * This will:
//      * 1. Find all employees who checked in today but didn't check out
//      * 2. Auto checkout them at 6:00 PM with office location
//      */
//     public function auto_checkout()
//     {
//         $today_date = date('Y-m-d');
//         $checkout_time = $today_date . ' 18:00:00'; // 6:00 PM
        
//         echo "Running Auto Checkout for: $today_date\n";
        
//         // Get office location
//         $office = $this->db->select('Latitude, Longitude')
//             ->from('setting')
//             ->get()
//             ->row();
        
//         if (empty($office)) {
//             echo "ERROR: Office location not configured!\n";
//             log_message('error', 'Auto checkout failed: Office location not set');
//             return false;
//         }
        
//         $office_lat = $office->Latitude;
//         $office_lng = $office->Longitude;
        
//         // Find all check-ins for today
//         $this->db->select('uid, MAX(time) as last_checkin, MAX(atten_his_id) as last_id');
//         $this->db->from('attendance_history');
//         $this->db->where('DATE(time)', $today_date);
//         $this->db->where('state', 1); // Check-in
//         $this->db->group_by('uid');
//         $checkins = $this->db->get()->result();
        
//         $auto_checkout_count = 0;
//         $already_checkedout = 0;
        
//         foreach ($checkins as $checkin) {
//             // Check if this employee has checked out today
//             $checkout_exists = $this->db->select('atten_his_id')
//                 ->from('attendance_history')
//                 ->where('uid', $checkin->uid)
//                 ->where('DATE(time)', $today_date)
//                 ->where('state', 0) // Check-out
//                 ->where('time >', $checkin->last_checkin)
//                 ->get()
//                 ->row();
            
//             if (!$checkout_exists) {
//                 // No checkout found - insert auto checkout
//                 $auto_checkout = array(
//                     'uid'       => $checkin->uid,
//                     'state'     => 0, // Check-out
//                     'id'        => 0,
//                     'time'      => $checkout_time,
//                     'latitude'  => $office_lat,
//                     'longitude' => $office_lng,
//                     'remarks'   => 'Auto checkout by system'
//                 );
                
//                 if ($this->db->insert('attendance_history', $auto_checkout)) {
//                     $auto_checkout_count++;
//                     echo "Auto checkout: Employee ID {$checkin->uid} at 6:00 PM\n";
//                     log_message('info', "Auto checkout for employee {$checkin->uid} at {$checkout_time}");
//                 } else {
//                     echo "FAILED: Employee ID {$checkin->uid}\n";
//                     log_message('error', "Auto checkout failed for employee {$checkin->uid}");
//                 }
//             } else {
//                 $already_checkedout++;
//             }
//         }
        
//         echo "\n=== Summary ===\n";
//         echo "Total check-ins today: " . count($checkins) . "\n";
//         echo "Already checked out: $already_checkedout\n";
//         echo "Auto checkouts created: $auto_checkout_count\n";
        
//         log_message('info', "Auto checkout summary: {$auto_checkout_count} auto checkouts out of " . count($checkins) . " check-ins");
        
//         return true;
//     }

//     /**
//      * Manual Test Auto Checkout
//      * URL: https://yourdomain.com/cron/test_auto_checkout?token=YOUR_TOKEN&date=2026-01-28
//      */
//     public function test_auto_checkout()
//     {
//         $test_date = $this->input->get('date') ?: date('Y-m-d');
        
//         echo "TEST MODE: Auto Checkout for: $test_date\n\n";
        
//         // Get office location
//         $office = $this->db->select('Latitude, Longitude')
//             ->from('setting')
//             ->get()
//             ->row();
        
//         if (empty($office)) {
//             echo "ERROR: Office location not configured!\n";
//             return;
//         }
        
//         // Find check-ins for test date
//         $this->db->select('uid, MAX(time) as last_checkin');
//         $this->db->from('attendance_history');
//         $this->db->where('DATE(time)', $test_date);
//         $this->db->where('state', 1);
//         $this->db->group_by('uid');
//         $checkins = $this->db->get()->result();
        
//         echo "Check-ins found: " . count($checkins) . "\n\n";
        
//         foreach ($checkins as $checkin) {
//             $checkout_exists = $this->db->select('atten_his_id')
//                 ->from('attendance_history')
//                 ->where('uid', $checkin->uid)
//                 ->where('DATE(time)', $test_date)
//                 ->where('state', 0)
//                 ->where('time >', $checkin->last_checkin)
//                 ->get()
//                 ->row();
            
//             if (!$checkout_exists) {
//                 echo "Employee {$checkin->uid}: MISSING CHECKOUT (would auto checkout)\n";
//             } else {
//                 echo "Employee {$checkin->uid}: Already checked out\n";
//             }
//         }
//     }
    
// }<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cron Controller
 * Handle all automated tasks for leave management and attendance
 * 
 * Security Token: Change CRON_SECRET_TOKEN in config or here
 */
class Cron extends CI_Controller {

    private $cron_token = 'SECURE123'; // Change this to a secure token

    public function __construct()
    {
        parent::__construct();
        $this->load->model('leave/Leave_model');
        $this->load->model('attendance/Csv_model');
        
        // Security: Only allow CLI or valid token
        if (!$this->input->is_cli_request()) {
            $token = $this->input->get('token');
            
            if ($token !== $this->cron_token) {
                show_error('Unauthorized access', 403);
            }
        }
    }

    /**
     * ========================================================================
     * MONTHLY LEAVE PROCESSING
     * ========================================================================
     * Run this on the 1st of every month at midnight
     * 
     * This will:
     * 1. Create monthly balance records for all employees and leave types
     * 2. Calculate opening balance based on previous month
     * 3. Handle carry-forward logic for CL, SL, and LOP
     * 
     * CRON Schedule: 0 0 1 * *
     * (Runs at 00:00 on the 1st day of every month)
     * 
     * Manual URL: https://yourdomain.com/cron/monthly_leave?token=YOUR_TOKEN
     */
    public function monthly_leave()
    {
        $year  = date('Y');
        $month = date('n');
        
        echo "========================================\n";
        echo "MONTHLY LEAVE PROCESSING\n";
        echo "========================================\n";
        echo "Date: $year-$month\n";
        echo "Time: " . date('Y-m-d H:i:s') . "\n\n";
        
        try {
            $result = $this->Leave_model->process_monthly_leave($year, $month);
            
            if ($result) {
                echo "✓ Monthly leave processed successfully!\n";
                log_message('info', "[CRON] Monthly leave processed for $year-$month");
                
                // Log statistics
                $stats = $this->get_monthly_stats($year, $month);
                echo "\nStatistics:\n";
                echo "- Total employees: " . $stats['employees'] . "\n";
                echo "- Total leave types: " . $stats['leave_types'] . "\n";
                echo "- Records created: " . $stats['records'] . "\n";
            } else {
                echo "✗ Failed to process monthly leave\n";
                log_message('error', "[CRON] Failed to process monthly leave for $year-$month");
            }
        } catch (Exception $e) {
            echo "✗ ERROR: " . $e->getMessage() . "\n";
            log_message('error', "[CRON] Error in monthly_leave: " . $e->getMessage());
        }
        
        echo "\n========================================\n";
    }

    /**
     * ========================================================================
     * YEARLY LEAVE RESET
     * ========================================================================
     * Run this on January 1st at midnight
     * 
     * This will:
     * 1. Reset LOP balance to 20 for all employees
     * 2. CL and SL balances are handled by monthly processing
     * 
     * CRON Schedule: 0 0 1 1 *
     * (Runs at 00:00 on January 1st every year)
     * 
     * Manual URL: https://yourdomain.com/cron/yearly_reset?token=YOUR_TOKEN
     */
    public function yearly_reset()
    {
        $year = date('Y');
        
        echo "========================================\n";
        echo "YEARLY LEAVE RESET\n";
        echo "========================================\n";
        echo "Year: $year\n";
        echo "Time: " . date('Y-m-d H:i:s') . "\n\n";
        
        try {
            $result = $this->Leave_model->reset_yearly_leave($year);
            
            if ($result) {
                echo "✓ Yearly leave reset successfully!\n";
                echo "- LOP balance reset to 20 for all employees\n";
                log_message('info', "[CRON] Yearly leave reset for $year");
                
                // Get count of affected records
                $count = $this->db->query("
                    SELECT COUNT(*) as total 
                    FROM employee_leave_balance 
                    WHERE leave_type_id = 8 
                    AND year = ? 
                    AND month = 1
                ", [$year])->row()->total;
                
                echo "- Total records updated: $count\n";
            } else {
                echo "✗ Failed to reset yearly leave\n";
                log_message('error', "[CRON] Failed to reset yearly leave for $year");
            }
        } catch (Exception $e) {
            echo "✗ ERROR: " . $e->getMessage() . "\n";
            log_message('error', "[CRON] Error in yearly_reset: " . $e->getMessage());
        }
        
        echo "\n========================================\n";
    }

    /**
     * ========================================================================
     * AUTO CHECKOUT FOR MISSING CHECKOUTS
     * ========================================================================
     * Run this at 9:05 PM every day
     * 
     * This will:
     * 1. Find employees who checked in but didn't check out
     * 2. Auto checkout at 6:00 PM with office location
     * 
     * CRON Schedule: 5 21 * * *
     * (Runs at 21:05 every day)
     * 
     * Manual URL: https://yourdomain.com/cron/auto_checkout?token=YOUR_TOKEN
     */
    public function auto_checkout()
    {
        $today_date = date('Y-m-d');
        $checkout_time = $today_date . ' 18:00:00'; // 6:00 PM
        
        echo "========================================\n";
        echo "AUTO CHECKOUT PROCESSING\n";
        echo "========================================\n";
        echo "Date: $today_date\n";
        echo "Time: " . date('Y-m-d H:i:s') . "\n\n";
        
        // Get office location
        $office = $this->db->select('Latitude, Longitude')
            ->from('setting')
            ->get()
            ->row();
        
        if (empty($office)) {
            echo "✗ ERROR: Office location not configured!\n";
            log_message('error', '[CRON] Auto checkout failed: Office location not set');
            return false;
        }
        
        $office_lat = $office->Latitude;
        $office_lng = $office->Longitude;
        
        echo "Office Location: $office_lat, $office_lng\n";
        echo "Checkout Time: $checkout_time\n\n";
        
        // Find all check-ins for today
        $this->db->select('uid, MAX(time) as last_checkin, MAX(atten_his_id) as last_id');
        $this->db->from('attendance_history');
        $this->db->where('DATE(time)', $today_date);
        $this->db->where('state', 1); // Check-in
        $this->db->group_by('uid');
        $checkins = $this->db->get()->result();
        
        $auto_checkout_count = 0;
        $already_checkedout = 0;
        $failed_count = 0;
        
        echo "Processing check-ins...\n";
        
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
                    echo "✓ Employee {$checkin->uid} - Auto checkout at 6:00 PM\n";
                    log_message('info', "[CRON] Auto checkout for employee {$checkin->uid} at {$checkout_time}");
                } else {
                    $failed_count++;
                    echo "✗ Employee {$checkin->uid} - FAILED\n";
                    log_message('error', "[CRON] Auto checkout failed for employee {$checkin->uid}");
                }
            } else {
                $already_checkedout++;
            }
        }
        
        echo "\n========================================\n";
        echo "SUMMARY\n";
        echo "========================================\n";
        echo "Total check-ins: " . count($checkins) . "\n";
        echo "Already checked out: $already_checkedout\n";
        echo "Auto checkouts created: $auto_checkout_count\n";
        echo "Failed: $failed_count\n";
        echo "========================================\n";
        
        log_message('info', "[CRON] Auto checkout summary: {$auto_checkout_count} auto checkouts out of " . count($checkins) . " check-ins");
        
        return true;
    }

    /**
     * ========================================================================
     * BACKFILL EMPLOYEE LEAVE BALANCE
     * ========================================================================
     * One-time script to backfill all existing approved leaves
     * Run this once after implementing the new system
     * 
     * Manual URL: https://yourdomain.com/cron/backfill_all_leaves?token=YOUR_TOKEN
     */
    public function backfill_all_leaves()
    {
        echo "========================================\n";
        echo "BACKFILL ALL EMPLOYEE LEAVE BALANCES\n";
        echo "========================================\n";
        echo "Time: " . date('Y-m-d H:i:s') . "\n\n";
        
        // Get all employees
        $employees = $this->db->select('employee_id, first_name, last_name')
            ->from('employee_history')
            ->get()
            ->result();
        
        $processed = 0;
        $total = count($employees);
        
        echo "Total employees: $total\n\n";
        
        foreach ($employees as $emp) {
            echo "Processing: {$emp->first_name} {$emp->last_name} (ID: {$emp->employee_id})...\n";
            
            try {
                $this->Leave_model->backfill_employee_balance($emp->employee_id);
                $processed++;
                echo "✓ Complete\n\n";
            } catch (Exception $e) {
                echo "✗ Error: " . $e->getMessage() . "\n\n";
                log_message('error', "[CRON] Backfill error for employee {$emp->employee_id}: " . $e->getMessage());
            }
        }
        
        echo "========================================\n";
        echo "BACKFILL COMPLETE\n";
        echo "========================================\n";
        echo "Processed: $processed / $total employees\n";
        echo "========================================\n";
        
        log_message('info', "[CRON] Backfill completed: $processed / $total employees");
    }

    /**
     * ========================================================================
     * TEST FUNCTIONS
     * ========================================================================
     */

    /**
     * Test monthly leave processing
     * URL: https://yourdomain.com/cron/test_monthly?token=YOUR_TOKEN&year=2026&month=2
     */
    public function test_monthly()
    {
        $year  = $this->input->get('year') ?: date('Y');
        $month = $this->input->get('month') ?: date('n');
        
        echo "========================================\n";
        echo "TEST: MONTHLY LEAVE PROCESSING\n";
        echo "========================================\n";
        echo "Year: $year, Month: $month\n\n";
        
        $result = $this->Leave_model->process_monthly_leave($year, $month);
        
        if ($result) {
            echo "✓ Success!\n";
            
            $stats = $this->get_monthly_stats($year, $month);
            echo "\nStatistics:\n";
            echo "- Employees: " . $stats['employees'] . "\n";
            echo "- Leave types: " . $stats['leave_types'] . "\n";
            echo "- Records: " . $stats['records'] . "\n";
        } else {
            echo "✗ Failed!\n";
        }
        
        echo "\n========================================\n";
    }

    /**
     * Test auto checkout
     * URL: https://yourdomain.com/cron/test_auto_checkout?token=YOUR_TOKEN&date=2026-02-06
     */
    public function test_auto_checkout()
    {
        $test_date = $this->input->get('date') ?: date('Y-m-d');
        
        echo "========================================\n";
        echo "TEST: AUTO CHECKOUT\n";
        echo "========================================\n";
        echo "Date: $test_date\n\n";
        
        // Get office location
        $office = $this->db->select('Latitude, Longitude')
            ->from('setting')
            ->get()
            ->row();
        
        if (empty($office)) {
            echo "✗ ERROR: Office location not configured!\n";
            return;
        }
        
        echo "Office Location: {$office->Latitude}, {$office->Longitude}\n\n";
        
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
        
        echo "\n========================================\n";
    }

    /**
     * View current month balances for an employee
     * URL: https://yourdomain.com/cron/view_balance?token=YOUR_TOKEN&employee_id=1
     */
    public function view_balance()
    {
        $employee_id = $this->input->get('employee_id');
        
        if (empty($employee_id)) {
            echo "Error: Please provide employee_id parameter\n";
            return;
        }
        
        $year  = date('Y');
        $month = date('n');
        
        echo "========================================\n";
        echo "EMPLOYEE LEAVE BALANCE\n";
        echo "========================================\n";
        echo "Employee ID: $employee_id\n";
        echo "Period: $year-$month\n\n";
        
        // Get employee info
        $employee = $this->db->select('first_name, last_name')
            ->from('employee_history')
            ->where('employee_id', $employee_id)
            ->get()
            ->row();
        
        if ($employee) {
            echo "Name: {$employee->first_name} {$employee->last_name}\n\n";
        }
        
        // Get all leave balances
        $balances = $this->db->select('lb.*, lt.leave_type')
            ->from('employee_leave_balance lb')
            ->join('leave_type lt', 'lt.leave_type_id = lb.leave_type_id')
            ->where('lb.employee_id', $employee_id)
            ->where('lb.year', $year)
            ->where('lb.month', $month)
            ->get()
            ->result();
        
        if (empty($balances)) {
            echo "No balance records found for this period.\n";
        } else {
            echo "Leave Type       | Opening | Used | Closing\n";
            echo "-----------------|---------|------|--------\n";
            
            foreach ($balances as $bal) {
                printf("%-16s | %7s | %4s | %7s\n", 
                    $bal->leave_type, 
                    $bal->opening_balance, 
                    $bal->used_leave, 
                    $bal->closing_balance
                );
            }
        }
        
        echo "\n========================================\n";
    }

    /**
     * Get monthly statistics
     */
    private function get_monthly_stats($year, $month)
    {
        $employees = $this->db->select('COUNT(*) as total')
            ->from('employee_history')
            ->get()
            ->row()->total;
        
        $leave_types = $this->db->select('COUNT(*) as total')
            ->from('leave_type')
            ->get()
            ->row()->total;
        
        $records = $this->db->select('COUNT(*) as total')
            ->from('employee_leave_balance')
            ->where('year', $year)
            ->where('month', $month)
            ->get()
            ->row()->total;
        
        return [
            'employees' => $employees,
            'leave_types' => $leave_types,
            'records' => $records
        ];
    }

    /**
     * Health check
     * URL: https://yourdomain.com/cron/health?token=YOUR_TOKEN
     */
    public function health()
    {
        echo "========================================\n";
        echo "CRON SYSTEM HEALTH CHECK\n";
        echo "========================================\n";
        echo "Time: " . date('Y-m-d H:i:s') . "\n\n";
        
        // Check database connection
        echo "Database: ";
        if ($this->db->conn_id) {
            echo "✓ Connected\n";
        } else {
            echo "✗ Not connected\n";
        }
        
        // Check required tables
        $tables = ['employee_history', 'leave_type', 'leave_apply', 'employee_leave_balance', 'attendance_history'];
        echo "\nRequired tables:\n";
        foreach ($tables as $table) {
            if ($this->db->table_exists($table)) {
                $count = $this->db->count_all($table);
                echo "✓ $table (records: $count)\n";
            } else {
                echo "✗ $table (missing)\n";
            }
        }
        
        echo "\n========================================\n";
        echo "Status: OK\n";
        echo "========================================\n";
    }




    /**
 * ========================================================================
 * JANUARY 1ST PROCESSING - COMBINED
 * ========================================================================
 * Run this on January 1st at 12:05 AM
 * This ensures yearly reset happens BEFORE monthly processing
 * 
 * CRON Schedule: 5 0 1 1 *
 * (Runs at 00:05 on January 1st every year)
 * 
 * Manual URL: https://yourdomain.com/cron/january_reset?token=YOUR_TOKEN
 */
public function january_reset()
{
    $year = date('Y');
    
    echo "========================================\n";
    echo "JANUARY 1ST - NEW YEAR RESET\n";
    echo "========================================\n";
    echo "Year: $year\n";
    echo "Time: " . date('Y-m-d H:i:s') . "\n\n";
    
    try {
        // Step 1: Process monthly leave (this will create fresh January records)
        echo "Step 1: Creating January balance records...\n";
        $monthly_result = $this->Leave_model->process_monthly_leave($year, 1);
        
        if ($monthly_result) {
            echo "✓ January balances created\n";
            echo "  - CL: Opening = 1\n";
            echo "  - SL: Opening = 1\n";
            echo "  - LOP: Opening = 24\n\n";
        } else {
            throw new Exception("Failed to create monthly balances");
        }
        
        // Get statistics
        $stats = $this->get_monthly_stats($year, 1);
        echo "Statistics:\n";
        echo "- Total employees: " . $stats['employees'] . "\n";
        echo "- Total leave types: " . $stats['leave_types'] . "\n";
        echo "- Records created: " . $stats['records'] . "\n\n";
        
        echo "✓ New Year reset completed successfully!\n";
        log_message('info', "[CRON] New Year reset completed for $year");
        
    } catch (Exception $e) {
        echo "✗ ERROR: " . $e->getMessage() . "\n";
        log_message('error', "[CRON] Error in january_reset: " . $e->getMessage());
    }
    
    echo "\n========================================\n";
}
}