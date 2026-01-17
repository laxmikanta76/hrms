<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Debug AJAX Controller
 * URL: debug_ajax/test_leave_balance
 */
class Debug_ajax extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Allow only logged-in users or admin
        if (!$this->session->userdata('isLogIn')) {
            echo json_encode(['error' => 'Not logged in']);
            exit;
        }
    }

    /**
     * Test leave balance function
     * URL: debug_ajax/test_leave_balance?employee_id=E001&leave_type=3
     */
    public function test_leave_balance()
    {
        header('Content-Type: application/json');
        
        echo "<h2>Leave Balance Debug Tool</h2>";
        echo "<pre>";
        
        // Get parameters
        $employee_id = $this->input->get('employee_id') ?: $this->input->post('employee_id');
        $leave_type = $this->input->get('leave_type') ?: $this->input->post('leave_type');
        
        echo "Testing with:\n";
        echo "Employee ID: $employee_id\n";
        echo "Leave Type: $leave_type\n\n";
        
        if (empty($employee_id) || empty($leave_type)) {
            echo "ERROR: Missing parameters\n";
            echo "Usage: debug_ajax/test_leave_balance?employee_id=E001&leave_type=3\n";
            echo "</pre>";
            return;
        }
        
        // Load model
        $this->load->model('leave/Leave_model');
        
        // 1. Check if employee exists
        echo "1. Checking employee...\n";
        $employee = $this->db->get_where('employee_history', ['employee_id' => $employee_id])->row();
        if ($employee) {
            echo "   ✓ Employee found: {$employee->first_name} {$employee->last_name}\n\n";
        } else {
            echo "   ✗ Employee NOT found\n\n";
        }
        
        // 2. Check if leave type exists
        echo "2. Checking leave type...\n";
        $leaveType = $this->db->get_where('leave_type', ['leave_type_id' => $leave_type])->row();
        if ($leaveType) {
            echo "   ✓ Leave type found: {$leaveType->leave_type}\n";
            echo "   Days: {$leaveType->leave_days}\n";
            echo "   Carry Forward: " . ($leaveType->carry_forward ? 'Yes' : 'No') . "\n\n";
        } else {
            echo "   ✗ Leave type NOT found\n\n";
        }
        
        // 3. Check current balance
        $year = date('Y');
        $month = date('n');
        
        echo "3. Checking current balance ($year-$month)...\n";
        $balance = $this->db->get_where('employee_leave_balance', [
            'employee_id' => $employee_id,
            'leave_type_id' => $leave_type,
            'year' => $year,
            'month' => $month
        ])->row();
        
        if ($balance) {
            echo "   ✓ Balance found:\n";
            echo "   Opening: {$balance->opening_balance}\n";
            echo "   Used: {$balance->used_leave}\n";
            echo "   Closing: {$balance->closing_balance}\n\n";
        } else {
            echo "   ✗ Balance NOT found. Creating...\n";
            $this->Leave_model->ensure_monthly_balance($employee_id, $leave_type, $year, $month);
            
            // Try again
            $balance = $this->db->get_where('employee_leave_balance', [
                'employee_id' => $employee_id,
                'leave_type_id' => $leave_type,
                'year' => $year,
                'month' => $month
            ])->row();
            
            if ($balance) {
                echo "   ✓ Balance created:\n";
                echo "   Opening: {$balance->opening_balance}\n";
                echo "   Used: {$balance->used_leave}\n";
                echo "   Closing: {$balance->closing_balance}\n\n";
            } else {
                echo "   ✗ Failed to create balance\n\n";
            }
        }
        
        // 4. Test AJAX response
        echo "4. AJAX Response would be:\n";
        if ($balance) {
            $response = [
                'status' => 'success',
                'enjoy' => (float)$balance->used_leave,
                'due' => (float)$balance->closing_balance,
                'opening' => (float)$balance->opening_balance
            ];
            echo json_encode($response, JSON_PRETTY_PRINT);
        } else {
            echo "   ERROR: No balance available\n";
        }
        
        echo "\n</pre>";
    }

    /**
     * Test free_leave function directly
     * URL: debug_ajax/test_free_leave
     */
    public function test_free_leave()
    {
        // Simulate POST data
        $_POST['employee_id'] = $this->input->get('employee_id') ?: 'E001';
        $_POST['leave_type'] = $this->input->get('leave_type') ?: '3';
        
        echo "<h2>Testing free_leave() function</h2>";
        echo "<pre>";
        echo "Calling Leave->free_leave() with:\n";
        echo "Employee: " . $_POST['employee_id'] . "\n";
        echo "Leave Type: " . $_POST['leave_type'] . "\n\n";
        echo "Response:\n";
        echo "</pre>";
        
        // Call the actual function
        $this->load->module('leave');
        $this->leave->free_leave();
    }

    /**
     * List all available employees and leave types
     * URL: debug_ajax/list_data
     */
    public function list_data()
    {
        echo "<h2>Available Data</h2>";
        echo "<pre>";
        
        echo "=== EMPLOYEES ===\n";
        $employees = $this->db->select('employee_id, first_name, last_name')->from('employee_history')->get()->result();
        foreach ($employees as $emp) {
            echo "{$emp->employee_id} - {$emp->first_name} {$emp->last_name}\n";
        }
        
        echo "\n=== LEAVE TYPES ===\n";
        $leave_types = $this->db->select('*')->from('leave_type')->get()->result();
        foreach ($leave_types as $lt) {
            echo "{$lt->leave_type_id} - {$lt->leave_type} ({$lt->leave_days} days, CF: " . ($lt->carry_forward ? 'Yes' : 'No') . ")\n";
        }
        
        echo "\n=== SAMPLE TEST URLS ===\n";
        if (!empty($employees) && !empty($leave_types)) {
            $emp = $employees[0];
            $lt = $leave_types[0];
            echo base_url("debug_ajax/test_leave_balance?employee_id={$emp->employee_id}&leave_type={$lt->leave_type_id}") . "\n";
            echo base_url("debug_ajax/test_free_leave?employee_id={$emp->employee_id}&leave_type={$lt->leave_type_id}") . "\n";
        }
        
        echo "</pre>";
    }
}