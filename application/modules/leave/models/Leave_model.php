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

    // public function manageleave()
    // {
    //     return $this->db->select('count(DISTINCT(ap.leave_appl_id)) as leave_appl_id,ap.*,p.employee_id,p.first_name,p.last_name, type.leave_type')   
    //         ->from('leave_apply ap')
    //         ->join('employee_history p', 'ap.employee_id = p.employee_id', 'left')
    //         ->join('leave_type as type', 'type.leave_type_id = ap.leave_type_id', 'left')
    //         ->group_by('ap.leave_appl_id')
    //         ->order_by('ap.leave_appl_id', 'desc')
    //         ->get()
    //         ->result();
    // }
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

    // 🔐 Filter only for logged-in employee
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

    // Check for DB error
    if ($this->db->error()['code'] != 0) {
        return false;
    }
    return true;
        // $this->db->where('leave_type_id', $data["leave_type_id"])
        //          ->update('leave_type', $data);
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
     * Update leave balance when leave application is approved
     */
    private function update_leave_balance_on_application($leave_data)
    {
        $employee_id = $leave_data['employee_id'];
    $leave_type_id = $leave_data['leave_type_id'];
    $approved_days = $leave_data['num_aprv_day'];
    
    $start_date = $leave_data['leave_aprv_strt_date'];
    $year = date('Y', strtotime($start_date));
    $month = date('n', strtotime($start_date));

    // ✅ STEP 1: Check if balance record exists for this month
    $existing = $this->db->get_where('employee_leave_balance', [
        'employee_id'   => $employee_id,
        'leave_type_id' => $leave_type_id,
        'year'          => $year,
        'month'         => $month
    ])->row();

    if ($existing) {
        // ✅ STEP 2A: Record exists - UPDATE it
        $new_used = $existing->used_leave + $approved_days;
        $new_closing = $existing->opening_balance - $new_used;
        
        $this->db->where('id', $existing->id)
            ->update('employee_leave_balance', [
                'used_leave' => $new_used,
                'closing_balance' => $new_closing,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    } else {
        // ✅ STEP 2B: Record doesn't exist - INSERT new record
        
        // Get opening balance from previous month or default
        $opening_balance = $this->get_opening_balance($employee_id, $leave_type_id, $year, $month);
        
        $closing_balance = $opening_balance - $approved_days;
        
        $this->db->insert('employee_leave_balance', [
            'employee_id'     => $employee_id,
            'leave_type_id'   => $leave_type_id,
            'year'            => $year,
            'month'           => $month,
            'opening_balance' => $opening_balance,
            'used_leave'      => $approved_days,
            'closing_balance' => $closing_balance,
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s')
        ]);
    }
    }

    private function get_opening_balance($employee_id, $leave_type_id, $year, $month)
{
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

    $opening = 0;

    // ✅ CL (Casual Leave - ID 7) - Carry forward + monthly credit
    if ($leave_type_id == 7) {
        if ($prev) {
            $opening = $prev->closing_balance + 1; // Add 1 day per month
        } else {
            $opening = 1; // First time
        }
    }
    
    // ✅ SL (Sick Leave - ID 9) - Monthly reset
    elseif ($leave_type_id == 9) {
        $opening = 1; // Fixed 1 day every month (no carry forward)
    }
    
    // ✅ LOP (Loss of Pay - ID 8) - Carry forward remaining
    elseif ($leave_type_id == 8) {
        if ($prev) {
            $opening = $prev->closing_balance; // Carry forward
        } else {
            $opening = 20; // Initial allocation (yearly)
        }
    }
    
    // ✅ Default for other leave types
    else {
        // Get leave type default
        $leaveType = $this->db->get_where('leave_type', [
            'leave_type_id' => $leave_type_id
        ])->row();
        
        if ($leaveType) {
            if ($prev) {
                $opening = $prev->closing_balance;
            } else {
                $opening = !empty($leaveType->leave_days) ? $leaveType->leave_days : 0;
            }
        }
    }

    return $opening;
}

    /**
     * Restore leave balance when leave application is deleted
     */
    private function restore_leave_balance_on_deletion($leave_data)
    {
        $employee_id = is_object($leave_data) ? $leave_data->employee_id : $leave_data['employee_id'];
    $leave_type_id = is_object($leave_data) 
        ? (!empty($leave_data->leave_type_id) ? $leave_data->leave_type_id : $leave_data->leave_type)
        : (!empty($leave_data['leave_type_id']) ? $leave_data['leave_type_id'] : $leave_data['leave_type']);
    
    $approved_days = is_object($leave_data) ? $leave_data->num_aprv_day : $leave_data['num_aprv_day'];
    
    $start_date = is_object($leave_data)
        ? (!empty($leave_data->leave_aprv_strt_date) && $leave_data->leave_aprv_strt_date != '0000-00-00' 
            ? $leave_data->leave_aprv_strt_date 
            : $leave_data->apply_strt_date)
        : (!empty($leave_data['leave_aprv_strt_date']) && $leave_data['leave_aprv_strt_date'] != '0000-00-00'
            ? $leave_data['leave_aprv_strt_date']
            : $leave_data['apply_strt_date']);
    
    $year = date('Y', strtotime($start_date));
    $month = date('n', strtotime($start_date));

    // ✅ Find the balance record
    $balance = $this->db->get_where('employee_leave_balance', [
        'employee_id'   => $employee_id,
        'leave_type_id' => $leave_type_id,
        'year'          => $year,
        'month'         => $month
    ])->row();

    if ($balance) {
        // ✅ Restore the balance
        $new_used = $balance->used_leave - $approved_days;
        $new_closing = $balance->opening_balance - $new_used;
        
        $this->db->where('id', $balance->id)
            ->update('employee_leave_balance', [
                'used_leave' => max(0, $new_used), // Never go negative
                'closing_balance' => $new_closing,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }
    }

    /**
     * Process monthly leave for all employees (CRON JOB)
     * This should be run on the 1st of every month
     */
    public function process_monthly_leave($year, $month)
    {
         // ✅ Get all active employees
    $employees = $this->db->select('employee_id')
        ->from('employee_history')
        ->get()
        ->result();

    // ✅ Get all leave types
    $leaveTypes = $this->db->select('*')
        ->from('leave_type')
        ->get()
        ->result();

    $count = 0;

    foreach ($employees as $emp) {
        foreach ($leaveTypes as $lt) {
            
            // ✅ Check if balance already exists
            $exists = $this->db->get_where('employee_leave_balance', [
                'employee_id'   => $emp->employee_id,
                'leave_type_id' => $lt->leave_type_id,
                'year'          => $year,
                'month'         => $month
            ])->row();

            if (!$exists) {
                // ✅ Get opening balance
                $opening = $this->get_opening_balance(
                    $emp->employee_id, 
                    $lt->leave_type_id, 
                    $year, 
                    $month
                );

                // ✅ Insert new month balance
                $this->db->insert('employee_leave_balance', [
                    'employee_id'     => $emp->employee_id,
                    'leave_type_id'   => $lt->leave_type_id,
                    'year'            => $year,
                    'month'           => $month,
                    'opening_balance' => $opening,
                    'used_leave'      => 0,
                    'closing_balance' => $opening,
                    'created_at'      => date('Y-m-d H:i:s')
                ]);

                $count++;
            }
        }
    }

    log_message('info', "Monthly leave processed: $count records created for $year-$month");
    return true;
    }

    /**
     * Reset yearly leave balances (CRON JOB)
     * Run this on January 1st of every year
     */
    public function reset_yearly_leave($year)
    {
        // For non-carry forward leaves, reset happens monthly
        // For carry forward leaves, reset to base allocation
        
        // $leaveTypes = $this->db->select('*')
        //     ->from('leave_type')
        //     ->where('leave_type_id !=', 9) // exclude SL
        //     ->get()
        //     ->result();

        // foreach ($leaveTypes as $lt) {
        //     // Reset all balances for this leave type to monthly allocation
        //     $this->db->query("
        //         UPDATE employee_leave_balance 
        //         SET opening_balance = ?,
        //             used_leave = 0,
        //             closing_balance = ?
        //         WHERE leave_type_id = ? 
        //         AND year = ? 
        //         AND month = 1
        //     ", [$lt->leave_days, $lt->leave_days, $lt->leave_type_id, $year]);
        // }

        // return true;
        $this->db->query("
        UPDATE employee_leave_balance
        SET opening_balance = 20,
            used_leave = 0,
            closing_balance = 20,
            updated_at = NOW()
        WHERE leave_type_id = 8
          AND year = ?
          AND month = 1
    ", [$year]);

    log_message('info', "Yearly leave reset completed for year: $year");
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

    // ✅ Try to get existing balance
    $balance = $this->db->get_where('employee_leave_balance', [
        'employee_id'   => $employee_id,
        'leave_type_id' => $leave_type_id,
        'year'          => $year,
        'month'         => $month
    ])->row();

    // ✅ If not found, calculate opening balance
    if (!$balance) {
        $opening = $this->get_opening_balance($employee_id, $leave_type_id, $year, $month);
        
        // Return virtual balance object
        return (object)[
            'employee_id'     => $employee_id,
            'leave_type_id'   => $leave_type_id,
            'year'            => $year,
            'month'           => $month,
            'opening_balance' => $opening,
            'used_leave'      => 0,
            'closing_balance' => $opening
        ];
    }

    return $balance;
}

}