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
		return $this->db->insert('leave_apply', $data);
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
		return $this->db->where('leave_appl_id', $data["leave_appl_id"])
			->update("leave_apply", $data);


	}
	public function application_updateForm($id){
        $this->db->where('leave_appl_id',$id);
        $query = $this->db->get('leave_apply');
        return $query->row();
    }
     public  function get_id($id)
    {
        $query=$this->db->get_where('leave_apply',array('leave_appl_id'=>$id));
        return $query->row_array();
    } 

    // insert others leave type 
    public function save_leave_type($data = array()){
    	return $this->db->insert('leave_type', $data);
    }  

     // get all leave type for apply leave
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

    // get all leave type 
     public function get_all_leave_type(){
    	$this->db->select('*');
    	$this->db->from('leave_type');
    	$query=$this->db->get();
    	return $query->result();
    }

    //get leave type by leave type id
    public function get_leave_type_by_id($id){
        $this->db->where('leave_type_id',$id);
        $query = $this->db->get('leave_type');
        return $query->row();
    }

    // update leave type 
    public function save_update_leave_type($data = array()){
    	$this->db->where('leave_type_id', $data["leave_type_id"])
  			     ->update('leave_type', $data);
    }

    // delete leave type by id 
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
        return $result = $this->db->select('first_name,last_name,employee_id')->from('employee_history')->where('is_super_visor',1)->get()->result();
    }
	
// 	public function process_monthly_leave($year, $month)
//     {
//        $employees = $this->db->get('employee')->result();
//        $leaveTypes = $this->db->get('leave_type')->result();

//        foreach ($employees as $emp) {

//         foreach ($leaveTypes as $lt) {

//             // previous month
//             $prevMonth = $month - 1;
//             $prevYear  = $year;

//             if ($prevMonth == 0) {
//                 $prevMonth = 12;
//                 $prevYear--;
//             }

//             $prev = $this->db->get_where('employee_leave_balance', [
//                 'employee_id' => $emp->employee_id,
//                 'leave_type_id' => $lt->leave_type_id,
//                 'year' => $prevYear,
//                 'month' => $prevMonth
//             ])->row();

//             $opening = 0;

//             if ($lt->carry_forward == 1 && $prev) {
//                 // Casual Leave → carry forward
//                 $opening = $prev->closing_balance;
//             }

//             // Sick Leave → no carry forward → opening = 0

//             $data = [
//                 'employee_id' => $emp->employee_id,
//                 'leave_type_id' => $lt->leave_type_id,
//                 'year' => $year,
//                 'month' => $month,
//                 'opening_balance' => $opening,
//                 'used_leave' => 0,
//                 'closing_balance' => $opening
//             ];

//             $this->db->insert('employee_leave_balance', $data);
//          }
//       }
//     }

// 	public function ensure_monthly_balance($employee_id, $leave_type_id, $year, $month)
// {
//     $exists = $this->db->get_where('employee_leave_balance', [
//         'employee_id'   => $employee_id,
//         'leave_type_id' => $leave_type_id,
//         'year'          => $year,
//         'month'         => $month
//     ])->row();

//     if ($exists) return;

//     $leaveType = $this->db->get_where('leave_type', [
//         'leave_type_id' => $leave_type_id
//     ])->row();

//     $prevMonth = $month - 1;
//     $prevYear  = $year;

//     if ($prevMonth == 0) {
//         $prevMonth = 12;
//         $prevYear--;
//     }

//     $prev = $this->db->get_where('employee_leave_balance', [
//         'employee_id'   => $employee_id,
//         'leave_type_id' => $leave_type_id,
//         'year'          => $prevYear,
//         'month'         => $prevMonth
//     ])->row();

//     // ✅ CORRECT OPENING LOGIC
//     if ($prev) {
//         if ($leaveType->carry_forward == 1) {
//             $opening = $prev->closing_balance;   // CL
//         } else {
//             $opening = $leaveType->leave_days;   // SL
//         }
//     } else {
//         $opening = $leaveType->leave_days;       // First month
//     }

//     $this->db->insert('employee_leave_balance', [
//         'employee_id'     => $employee_id,
//         'leave_type_id'   => $leave_type_id,
//         'year'            => $year,
//         'month'           => $month,
//         'opening_balance' => $opening,
//         'used_leave'      => 0,
//         'closing_balance' => $opening
//     ]);
//  }
}