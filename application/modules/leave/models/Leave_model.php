<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leave_model extends CI_Model {
 
    public function viewWeekly()
    {
        return $this->db->select('*')    
            ->from('weekly_holiday')
            ->order_by('wk_id', 'desc')
            ->get()
            ->result();
    }

    public function weekleave_create($data = array()){
       $this->db->insert('weekly_holiday',$data);
    }

    public function weekleave_delete($id = null){
        $this->db->where('wk_id',$id)
            ->delete('weekly_holiday');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    } 

    public function update_weeklev($data = array()){
        return $this->db->where('wk_id',$data["wk_id"])
            ->update("weekly_holiday", $data);
    }

    public function weekleave_updateForm($id){
        $this->db->where('wk_id',$id);
        $query = $this->db->get('weekly_holiday');
        return $query->row();
    }
    
    public function viewholiday(){
        return $this->db->select('*')    
            ->from('payroll_holiday')
            ->order_by('payrl_holi_id', 'desc')
            ->get()
            ->result();
    }
        
    public function holiday_create($data = array()){
        return $this->db->insert('payroll_holiday', $data);
    }

    public function holiday_delete($id = null){
        $this->db->where('payrl_holi_id',$id)
            ->delete('payroll_holiday');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    } 

    public function update_holiday($data = array()){
        return $this->db->where('payrl_holi_id', $data["payrl_holi_id"])
            ->update("payroll_holiday", $data);
    }

    public function holiday_updateForm($id){
        $this->db->where('payrl_holi_id',$id);
        $query = $this->db->get('payroll_holiday');
        return $query->row();
    }

    public function application_create($data = array())
    {
        $result = $this->db->insert('leave_apply', $data);
        
        // Update leave balance after successful leave application
        if ($result && !empty($data['num_aprv_day']) && $data['num_aprv_day'] > 0) {
            $this->update_leave_balance_on_application($data);
        }
        
        return $result;
    }

    public function dropdown(){
        $this->db->select('*');
        $this->db->from('employee_history');
        $query=$this->db->get();
        $data=$query->result();
        $list = array('' => 'Select One...');
        if(!empty($data)){
            foreach ($data as  $value) {
                $list[$value->employee_id]=$value->first_name." ".$value->last_name;
            }
        }
        return $list;
    }

    public function manageleave()
    {
        return $this->db->select('count(DISTINCT(ap.leave_appl_id)) as leave_appl_id,ap.*,p.employee_id,p.first_name,p.last_name, type.leave_type')   
            ->from('leave_apply ap')
            ->join('employee_history p', 'ap.employee_id = p.employee_id', 'left')
            ->join('leave_type as type', 'type.leave_type_id = ap.leave_type_id', 'left')
            ->group_by('ap.leave_appl_id')
            ->order_by('ap.leave_appl_id', 'desc')
            ->get()
            ->result();
    }

    public function application_delete($id = null)
    {
        // Get leave application details before deleting
        $leave_app = $this->db->select('*')
            ->from('leave_apply')
            ->where('leave_appl_id', $id)
            ->get()
            ->row();

        if ($leave_app && !empty($leave_app->num_aprv_day) && $leave_app->num_aprv_day > 0) {
            // Restore the leave balance
            $this->restore_leave_balance_on_deletion($leave_app);
        }

        $this->db->where('leave_appl_id',$id)
            ->delete('leave_apply');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    } 

    public function update_application($data = array())
    {
        // Get old leave application data
        $old_data = $this->db->select('*')
            ->from('leave_apply')
            ->where('leave_appl_id', $data['leave_appl_id'])
            ->get()
            ->row();

        $result = $this->db->where('leave_appl_id', $data["leave_appl_id"])
            ->update("leave_apply", $data);

        // Update leave balance if approved days changed
        if ($result && $old_data) {
            $old_days = !empty($old_data->num_aprv_day) ? $old_data->num_aprv_day : 0;
            $new_days = !empty($data['num_aprv_day']) ? $data['num_aprv_day'] : 0;

            if ($old_days != $new_days) {
                // Restore old balance
                if ($old_days > 0) {
                    $this->restore_leave_balance_on_deletion($old_data);
                }
                // Deduct new balance
                if ($new_days > 0) {
                    $this->update_leave_balance_on_application($data);
                }
            }
        }

        return $result;
    }

    public function application_updateForm($id){
        $this->db->where('leave_appl_id',$id);
        $query = $this->db->get('leave_apply');
        return $query->row();
    }

    public function get_id($id)
    {
        $query=$this->db->get_where('leave_apply',array('leave_appl_id'=>$id));
        return $query->row_array();
    } 

    public function save_leave_type($data = array()){
        return $this->db->insert('leave_type', $data);
    }  

    public function get_leave_type(){
        $this->db->select('*');
        $this->db->from('leave_type');
        $query=$this->db->get();
        $data=$query->result();
        $list = array('' => 'Select One...');
        if(!empty($data)){
            foreach ($data as  $value) {
                $list[$value->leave_type_id]=$value->leave_type;
            }
        }
        return $list;
    }

    public function get_all_leave_type(){
        $this->db->select('*');
        $this->db->from('leave_type');
        $query=$this->db->get();
        return $query->result();
    }

    public function get_leave_type_by_id($id){
        $this->db->where('leave_type_id',$id);
        $query = $this->db->get('leave_type');
        return $query->row();
    }

    public function save_update_leave_type($data = array()){
        $this->db->where('leave_type_id', $data['leave_type_id']);
        $this->db->update('leave_type', [
            'leave_type' => $data['leave_type'],
            'leave_days' => $data['leave_days']
        ]);

        if ($this->db->error()['code'] != 0) {
            return false;
        }
        return true;
    }

    public function delete_leave_type($id = null)
    {
        $this->db->where('leave_type_id',$id)
            ->delete('leave_type');

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    } 

    public function supervisorList(){
        return $result = $this->db->select('first_name,last_name,employee_id')
            ->from('employee_history')
            ->where('is_super_visor',1)
            ->get()
            ->result();
    }

    /**
     * ✅ CORRECTED: Ensure monthly balance exists for employee
     * Handles all 3 leave types correctly:
     * - CL (ID 7): Accumulates (previous + 1)
     * - LOP (ID 8): Carries forward remaining
     * - SL (ID 9): Resets to 1 monthly
     */
    public function ensure_monthly_balance($employee_id, $leave_type_id, $year, $month)
    {
        // Check if balance already exists
        $exists = $this->db->get_where('employee_leave_balance', [
            'employee_id'   => $employee_id,
            'leave_type_id' => $leave_type_id,
            'year'          => $year,
            'month'         => $month
        ])->row();

        if ($exists) {
            return true; // Already exists
        }

        // Calculate previous month
        $prevMonth = $month - 1;
        $prevYear  = $year;

        if ($prevMonth == 0) {
            $prevMonth = 12;
            $prevYear--;
        }

        // Get previous month's balance
        $prev = $this->db->get_where('employee_leave_balance', [
            'employee_id'   => $employee_id,
            'leave_type_id' => $leave_type_id,
            'year'          => $prevYear,
            'month'         => $prevMonth
        ])->row();

        // ✅ Calculate opening balance based on leave type
        $opening = 0;

        if ($leave_type_id == 7) { 
            // ===== CASUAL LEAVE (CL) - ACCUMULATE =====
            if ($prev) {
                $opening = $prev->closing_balance + 1; // Accumulate: previous + monthly credit
            } else {
                $opening = 1; // First month
            }
        } 
        elseif ($leave_type_id == 9) { 
            // ===== SICK LEAVE (SL) - RESET MONTHLY =====
            $opening = 1; // Always reset to 1 day every month
        } 
        elseif ($leave_type_id == 8) { 
            // ===== LOP - CARRY FORWARD REMAINING =====
            if ($prev) {
                $opening = $prev->closing_balance; // Carry forward remaining balance
            } else {
                $opening = 20; // First month starts with 20
            }
        }

        // Insert new monthly balance
        $this->db->insert('employee_leave_balance', [
            'employee_id'     => $employee_id,
            'leave_type_id'   => $leave_type_id,
            'year'            => $year,
            'month'           => $month,
            'opening_balance' => $opening,
            'used_leave'      => 0,
            'closing_balance' => $opening,
            'created_at'      => date('Y-m-d H:i:s')
        ]);

        return true;
    }

    /**
     * ✅ CORRECTED: Update leave balance when leave application is approved
     */
    private function update_leave_balance_on_application($leave_data)
    {
        $employee_id = $leave_data['employee_id'];
        $leave_type_id = !empty($leave_data['leave_type_id']) ? $leave_data['leave_type_id'] : $leave_data['leave_type'];
        $approved_days = $leave_data['num_aprv_day'];
        
        // Get the start date of approved leave
        $start_date = !empty($leave_data['leave_aprv_strt_date']) ? $leave_data['leave_aprv_strt_date'] : $leave_data['apply_strt_date'];
        
        if (empty($start_date) || $start_date == '0000-00-00') {
            $start_date = date('Y-m-d');
        }
        
        $year = date('Y', strtotime($start_date));
        $month = date('n', strtotime($start_date));

        // Ensure balance exists
        $this->ensure_monthly_balance($employee_id, $leave_type_id, $year, $month);
        
        // ✅ Fetch current balance for validation
        $current_balance = $this->db->get_where('employee_leave_balance', [
            'employee_id'   => $employee_id,
            'leave_type_id' => $leave_type_id,
            'year'          => $year,
            'month'         => $month
        ])->row();

        // ✅ Validate: Prevent negative balance for SL
        if ($leave_type_id == 9 && $approved_days > $current_balance->closing_balance) {
            // Sick Leave cannot go negative
            log_message('error', "SL validation failed: Requested {$approved_days} but only {$current_balance->closing_balance} available");
            return false;
        }

        // ✅ Update the balance (CORRECTED FORMULA)
        $this->db->query("
            UPDATE employee_leave_balance 
            SET used_leave = used_leave + ?,
                closing_balance = closing_balance - ?
            WHERE employee_id = ? 
            AND leave_type_id = ? 
            AND year = ? 
            AND month = ?
        ", [$approved_days, $approved_days, $employee_id, $leave_type_id, $year, $month]);

        return true;
    }

    /**
     * ✅ CORRECTED: Restore leave balance when leave application is deleted
     */
    private function restore_leave_balance_on_deletion($leave_data)
    {
        $employee_id = $leave_data->employee_id;
        $leave_type_id = !empty($leave_data->leave_type_id) ? $leave_data->leave_type_id : $leave_data->leave_type;
        
        // ✅ Skip restoration for LOP (ID 8) - optional business rule
        // Uncomment if you don't want to restore LOP when deleted
        // if ($leave_type_id == 8) {
        //     return true;
        // }
        
        $approved_days = $leave_data->num_aprv_day;
        
        // Get the start date of approved leave
        $start_date = !empty($leave_data->leave_aprv_strt_date) ? $leave_data->leave_aprv_strt_date : $leave_data->apply_strt_date;
        
        if (empty($start_date) || $start_date == '0000-00-00') {
            return false;
        }
        
        $year = date('Y', strtotime($start_date));
        $month = date('n', strtotime($start_date));

        // ✅ Restore the balance (CORRECTED FORMULA)
        $this->db->query("
            UPDATE employee_leave_balance 
            SET used_leave = GREATEST(0, used_leave - ?),
                closing_balance = closing_balance + ?
            WHERE employee_id = ? 
            AND leave_type_id = ? 
            AND year = ? 
            AND month = ?
        ", [$approved_days, $approved_days, $employee_id, $leave_type_id, $year, $month]);

        return true;
    }

    /**
     * Process monthly leave for all employees (CRON JOB)
     * Run on the 1st of every month
     */
    public function process_monthly_leave($year, $month)
    {
        // Get all active employees
        $employees = $this->db->select('employee_id')
            ->from('employee_history')
            ->get()
            ->result();

        // Get all leave types
        $leaveTypes = $this->db->select('*')
            ->from('leave_type')
            ->get()
            ->result();

        $count = 0;
        foreach ($employees as $emp) {
            foreach ($leaveTypes as $lt) {
                if ($this->ensure_monthly_balance(
                    $emp->employee_id,
                    $lt->leave_type_id,
                    $year,
                    $month
                )) {
                    $count++;
                }
            }
        }

        log_message('info', "Monthly leave processed: {$count} balances created for {$year}-{$month}");
        return true;
    }

    /**
     * ✅ Reset yearly leave balances (CRON JOB)
     * Run on January 1st to reset LOP to 20
     */
    public function reset_yearly_leave($year)
    {
        // Only reset LOP (ID 8) to 20 days on January 1st
        $this->db->query("
            UPDATE employee_leave_balance
            SET opening_balance = 20,
                used_leave = 0,
                closing_balance = 20
            WHERE leave_type_id = 8
              AND year = ?
              AND month = 1
        ", [$year]);

        log_message('info', "Yearly reset completed: LOP reset to 20 for year {$year}");
        return true;
    }

    /**
     * Get employee leave balance for display
     */
    public function get_employee_leave_balance($employee_id, $leave_type_id, $year = null, $month = null)
    {
        if ($year === null) {
            $year = date('Y');
        }
        if ($month === null) {
            $month = date('n');
        }

        // Ensure balance exists
        $this->ensure_monthly_balance($employee_id, $leave_type_id, $year, $month);

        // Fetch balance
        $balance = $this->db->get_where('employee_leave_balance', [
            'employee_id'   => $employee_id,
            'leave_type_id' => $leave_type_id,
            'year'          => $year,
            'month'         => $month
        ])->row();

        return $balance;
    }

    /**
     * ✅ NEW: Check if employee has sufficient leave balance
     */
    public function check_leave_balance($employee_id, $leave_type_id, $days_requested, $year = null, $month = null)
    {
        if ($year === null) {
            $year = date('Y');
        }
        if ($month === null) {
            $month = date('n');
        }

        // Ensure balance exists
        $this->ensure_monthly_balance($employee_id, $leave_type_id, $year, $month);

        // Get current balance
        $balance = $this->get_employee_leave_balance($employee_id, $leave_type_id, $year, $month);

        if (!$balance) {
            return [
                'status' => 'error',
                'sufficient' => false,
                'message' => 'Balance record not found',
                'available' => 0,
                'requested' => $days_requested,
                'shortage' => $days_requested
            ];
        }

        $available = (float)$balance->closing_balance;
        $requested = (float)$days_requested;

        if ($available >= $requested) {
            return [
                'status' => 'success',
                'sufficient' => true,
                'message' => 'Sufficient balance available',
                'available' => $available,
                'requested' => $requested,
                'remaining' => $available - $requested
            ];
        } else {
            return [
                'status' => 'insufficient',
                'sufficient' => false,
                'message' => 'Insufficient leave balance',
                'available' => $available,
                'requested' => $requested,
                'shortage' => $requested - $available
            ];
        }
    }
}