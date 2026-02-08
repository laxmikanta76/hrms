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
        
        // ONLY update balance if leave is APPROVED (num_aprv_day > 0)
        if ($result && !empty($data['num_aprv_day']) && $data['num_aprv_day'] > 0) {
            // Update balance for current leave ONLY
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

    public function manageleave($employee_id = null)
    {
        $this->db->select('
                count(DISTINCT(ap.leave_appl_id)) as leave_appl_id,
                ap.*,
                p.employee_id,
                p.first_name,
                p.last_name,
                type.leave_type
            ')
            ->from('leave_apply ap')
            ->join('employee_history p', 'ap.employee_id = p.employee_id', 'left')
            ->join('leave_type as type', 'type.leave_type_id = ap.leave_type_id', 'left');

        // Filter only for logged-in employee if not admin
        if (!empty($employee_id)) {
            $this->db->where('ap.employee_id', $employee_id);
        }

        return $this->db
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
            
            $old_leave_type = !empty($old_data->leave_type_id) ? $old_data->leave_type_id : $old_data->leave_type;
            $new_leave_type = !empty($data['leave_type_id']) ? $data['leave_type_id'] : $data['leave_type'];

            // Case 1: Changing from unapproved (0) to approved (>0)
            if ($old_days == 0 && $new_days > 0) {
                $this->update_leave_balance_on_application($data);
            }
            // Case 2: Changing from approved to unapproved
            elseif ($old_days > 0 && $new_days == 0) {
                $this->restore_leave_balance_on_deletion($old_data);
            }
            // Case 3: Both approved but days or leave type changed
            elseif ($old_days > 0 && $new_days > 0 && ($old_days != $new_days || $old_leave_type != $new_leave_type)) {
                $this->restore_leave_balance_on_deletion($old_data);
                $this->update_leave_balance_on_application($data);
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

        // Check for DB error
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
     * ========================================================================
     * ENSURE MONTHLY BALANCE - Create monthly record if not exists
     * ========================================================================
     * CRITICAL FIX: ALWAYS use 1 as opening balance for current month
     * Only use previous month for NEXT month's cron job
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
            return $exists; // Already exists
        }

        // Get leave type details
        $leaveType = $this->db->get_where('leave_type', [
            'leave_type_id' => $leave_type_id
        ])->row();

        if (!$leaveType) {
            return null; // Invalid leave type
        }

        // ✅ CRITICAL FIX: For CL, ALWAYS use 1 for current month
        // Only carry forward when cron creates NEXT month
        $current_month = date('n');
        $current_year = date('Y');
        
        $opening = 0;

        // 7 = CL (Casual Leave)
        if ($leave_type_id == 7) {
            // ✅ FIXED LOGIC: Only look at previous month if we're creating FUTURE month
            if ($year > $current_year || ($year == $current_year && $month > $current_month)) {
                // Creating future month - can carry forward
                $prevMonth = $month - 1;
                $prevYear  = $year;
                if ($prevMonth == 0) {
                    $prevMonth = 12;
                    $prevYear--;
                }
                
                $prev = $this->db->get_where('employee_leave_balance', [
                    'employee_id'   => $employee_id,
                    'leave_type_id' => $leave_type_id,
                    'year'          => $prevYear,
                    'month'         => $prevMonth
                ])->row();
                
                if ($prev) {
                    $opening = $prev->closing_balance + 1;
                } else {
                    $opening = 1;
                }
            } else {
                // ✅ Creating CURRENT or PAST month - use 1
                $opening = 1;
            }
        }
        // 9 = SL (Sick Leave) - Reset to 1 every month
        elseif ($leave_type_id == 9) {
            $opening = 1; // Always 1
        }
        // 8 = LOP (Loss of Pay)
        elseif ($leave_type_id == 8) {
            // For LOP, we can check previous month regardless
            $prevMonth = $month - 1;
            $prevYear  = $year;
            if ($prevMonth == 0) {
                $prevMonth = 12;
                $prevYear--;
            }
            
            $prev = $this->db->get_where('employee_leave_balance', [
                'employee_id'   => $employee_id,
                'leave_type_id' => $leave_type_id,
                'year'          => $prevYear,
                'month'         => $prevMonth
            ])->row();
            
            if ($prev) {
                $opening = $prev->closing_balance;
            } else {
                $opening = 20;
            }
        }
        // Other leave types
        else {
            $opening = $leaveType->leave_days;
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
        
        // Return the newly created record
        return $this->db->get_where('employee_leave_balance', [
            'employee_id'   => $employee_id,
            'leave_type_id' => $leave_type_id,
            'year'          => $year,
            'month'         => $month
        ])->row();
    }

    /**
     * Update leave balance when leave application is approved
     */
    private function update_leave_balance_on_application($leave_data)
    {
        $employee_id = $leave_data['employee_id'];
        $leave_type_id = !empty($leave_data['leave_type_id']) ? $leave_data['leave_type_id'] : $leave_data['leave_type'];
        $approved_days = $leave_data['num_aprv_day'];
        
        // Get the start date of approved leave
        $start_date = !empty($leave_data['leave_aprv_strt_date']) ? $leave_data['leave_aprv_strt_date'] : $leave_data['apply_strt_date'];
        $year = date('Y', strtotime($start_date));
        $month = date('n', strtotime($start_date));

        // Ensure balance exists for this month
        $this->ensure_monthly_balance($employee_id, $leave_type_id, $year, $month);
        
        // Fetch current balance
        $row = $this->db->get_where('employee_leave_balance', [
            'employee_id'   => $employee_id,
            'leave_type_id' => $leave_type_id,
            'year'          => $year,
            'month'         => $month
        ])->row();

        if (!$row) {
            log_message('error', "Balance record not found for employee: $employee_id");
            return false;
        }

        // Validate: SL cannot go negative
        if ($leave_type_id == 9 && $approved_days > $row->closing_balance) {
            log_message('error', "SL balance insufficient for employee: $employee_id");
            return false;
        }

        // Update the balance
        $this->db->query("
            UPDATE employee_leave_balance 
            SET used_leave = used_leave + ?,
                closing_balance = opening_balance - (used_leave + ?)
            WHERE employee_id = ? 
            AND leave_type_id = ? 
            AND year = ? 
            AND month = ?
        ", [$approved_days, $approved_days, $employee_id, $leave_type_id, $year, $month]);
        
        return true;
    }

    /**
     * Restore leave balance when leave application is deleted
     */
    private function restore_leave_balance_on_deletion($leave_data)
    {
        $employee_id = $leave_data->employee_id;
        $leave_type_id = !empty($leave_data->leave_type_id) ? $leave_data->leave_type_id : $leave_data->leave_type;
        
        // Don't restore LOP balance on deletion
        if ($leave_type_id == 8) {
            return;
        }
        
        $approved_days = $leave_data->num_aprv_day;
        
        // Get the start date of approved leave
        $start_date = !empty($leave_data->leave_aprv_strt_date) ? $leave_data->leave_aprv_strt_date : $leave_data->apply_strt_date;
        $year = date('Y', strtotime($start_date));
        $month = date('n', strtotime($start_date));

        // Update the balance (restore)
        $this->db->query("
            UPDATE employee_leave_balance 
            SET used_leave = used_leave - ?,
                closing_balance = opening_balance - (used_leave - ?)
            WHERE employee_id = ? 
            AND leave_type_id = ? 
            AND year = ? 
            AND month = ?
        ", [$approved_days, $approved_days, $employee_id, $leave_type_id, $year, $month]);
    }

    /**
     * Process monthly leave for all employees (CRON JOB)
     */
    public function process_monthly_leave($year, $month)
    {
        $employees = $this->db->select('employee_id')
            ->from('employee_history')
            ->get()
            ->result();

        $leaveTypes = $this->db->select('*')
            ->from('leave_type')
            ->get()
            ->result();

        foreach ($employees as $emp) {
            foreach ($leaveTypes as $lt) {
                $this->ensure_monthly_balance(
                    $emp->employee_id,
                    $lt->leave_type_id,
                    $year,
                    $month
                );
            }
        }

        return true;
    }

    /**
     * Reset yearly leave balances (CRON JOB)
     */
    public function reset_yearly_leave($year)
    {
        $this->db->query("
            UPDATE employee_leave_balance
            SET opening_balance = 20,
                used_leave = 0,
                closing_balance = 20
            WHERE leave_type_id = 8
              AND year = ?
              AND month = 1
        ", [$year]);

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

        // Ensure balance exists for current month
        $this->ensure_monthly_balance($employee_id, $leave_type_id, $year, $month);

        // Fetch balance
        return $this->db->get_where('employee_leave_balance', [
            'employee_id'   => $employee_id,
            'leave_type_id' => $leave_type_id,
            'year'          => $year,
            'month'         => $month
        ])->row();
    }
}