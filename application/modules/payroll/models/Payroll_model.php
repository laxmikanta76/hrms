<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll_model extends CI_Model {
 
    public function salary_setupView()
	{
		return $this->db->select('*')	
			->from('salary_type')
			->order_by('salary_type_id', 'desc')
			->get()
			->result();
	}
public function emp_salsetup_create($data = array())
	{
		return $this->db->insert('salary_type', $data);//
	}
public function emp_salstup_delete($id = null)
	{
		$this->db->where('employee_id',$id)
			->delete('employee_salary_setup');

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 

	public function update_em_salstup($data = array())//
	{
		return $this->db->where('salary_type_id', $data["salary_type_id"])
			->update("salary_type", $data);
	}
	public function salarysetup_updateForm($id){
        $this->db->where('salary_type_id',$id);
        $query = $this->db->get('salary_type');
        return $query->row();
    }
/* Salary Setup start ****/

     public function salary_setupindex()
	{

			 return $this->db->select('count(DISTINCT(sstp.e_s_s_id)) as e_s_s_id,sstp.*,p.employee_id,p.*,pos.position_name,dpt.department_name')   
            ->from('employee_salary_setup sstp')
            ->join('employee_history p', 'sstp.employee_id = p.employee_id', 'left')
            ->join('position pos', 'pos.pos_id = p.pos_id', 'left')
            ->join('department dpt', 'dpt.dept_id = p.dept_id', 'left')
            ->group_by('sstp.employee_id')
            ->order_by('sstp.salary_type_id', 'desc')
            ->get()
            ->result();
	}
	public function salary_setup_create($data = array())
	{
		return $this->db->insert('employee_salary_setup', $data);//
	}

	 public function salary_typeName()
	{
		return $this->db->select('*')	
			->from('salary_type')
	         ->where('emp_sal_type',1)
			->get()
			->result();
	}
	 public function salary_typedName()
	{
		return $this->db->select('*')	
			->from('salary_type')
	         ->where('emp_sal_type',0)
			->get()
			->result();
	}

	public function s_delete($id = null)
	{
		$this->db->where('employee_id',$id)
			->delete('employee_salary_setup');

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 

	public function empdropdown()
	{
		$this->db->select('*');
        $this->db->from('employee_history');
        $query = $this->db->get();
        $data = $query->result();
       
        $list = array('' => 'Select One...');
       	if (!empty($data) ) {
       		foreach ($data as $value) {
       			$list[$value->employee_id] = $value->first_name." ".$value->last_name;
       		} 
       	}
       	return $list;
	}

	/* salary sheet generate  */
	public function salary_genrate_create($data = array())
	{
		return $this->db->insert('salary_sheet_generate', $data);//
	}	

	public function salary_generateView($limit = null, $start = null)
	{
			 return $this->db->select('*')   
            ->from('salary_sheet_generate')
            ->group_by('ssg_id')
            ->order_by('ssg_id', 'desc')
            ->limit($limit, $start)
            ->get()
            ->result();
	}

	public function salary_gen_delete($id = null,$salname = null)
	{
		$this->db->where('ssg_id',$id)
			->delete('salary_sheet_generate');
		$this->db->where('salary_name',$salname)
			->delete('employee_salary_payment');
		$this->db->where('VNo',$salname)
			->delete('acc_transaction');	

		if ($this->db->affected_rows()) {
			return true;
		} else {
			return false;
		}
	} 

	public function update_sal_gen($data = array())//
	{
		return $this->db->where('ssg_id', $data["ssg_id"])
			->update("salary_sheet_generate", $data);
	}
	public function salargen_updateForm($id){
        $this->db->where('ssg_id',$id);
        $query = $this->db->get('salary_sheet_generate');
        return $query->row();
    }
    public function salary_head_create($data = array())
	{
		return $this->db->insert('salary_setup_header', $data);//
	}

/* salary setup Update ********************************************/

public function update_sal_stup($data = array())//
	{
		$term = array('employee_id' => $data['employee_id'], 'salary_type_id' => $data['salary_type_id']);

		return $this->db->where($term)
			->update("employee_salary_setup", $data);
	}

	public function update_sal_head($data = array())
	{
		return $this->db->where('employee_id', $data["employee_id"])
			->update("salary_setup_header", $data);
	}

	public function salary_s_updateForm($id){
        $this->db->where('employee_id',$id);
        $query = $this->db->get('employee_salary_setup','salary_setup_header');
        return $query->row();
    }
/* salary setup Update ********************************************/



    public function salary_amount($id)
	{
		return $result = $this->db->select('employee_salary_setup.*,salary_type.*')	
			 ->from('employee_salary_setup')
			 ->join('salary_type','salary_type.salary_type_id=employee_salary_setup.salary_type_id')
	         ->where('employee_salary_setup.employee_id',$id)
	         ->where('emp_sal_type',1)
			 ->get()
			 ->result();
	}
	 public function salary_amountlft($id)
	{
		return $result = $this->db->select('employee_salary_setup.*,salary_type.*')	
			 ->from('employee_salary_setup')
			 ->join('salary_type','salary_type.salary_type_id=employee_salary_setup.salary_type_id')
	         ->where('employee_salary_setup.employee_id',$id)
	         ->where('emp_sal_type',0)
			 ->get()
			 ->result();
	}




	public  function get_empid($id)
    {
        $query=$this->db->get_where('employee_salary_setup',array('employee_id'=>$id));
        return $query->row_array();
    } 
    public  function get_type($id)
    {
       
        return $result = $this->db->select('sal_type')
                       ->from('employee_salary_setup')
                       ->where('employee_id',$id)
                       ->get()
                       ->row_array();
    } 


    public function type()
	{
		$this->db->select('*');
        $this->db->from('employee_salary_setup');
        $query = $this->db->get();
        $data = $query->result();
       
        $list = array('' => 'Select One...');
       	if (!empty($data) ) {
       		foreach ($data as $value) {
       			$list[$value->sal_type] = $value->sal_type;
       		} 
       	}
       	return $list;
	}

	public function payable()
	{
		$this->db->select('*');
        $this->db->from('salary_setup_header');
        $query = $this->db->get();
        $data = $query->result();
       
         $list = array('' => 'Select One...');
       	if (!empty($data) ) {
       		foreach ($data as $value) {
       			$list[$value->salary_payable] = $value->salary_payable;
       		} 
       	}
       	return $list;
	}
	public  function get_payable($id)
    {
        
        return $result = $this->db->select('salary_payable')
                       ->from('salary_setup_header')
                       ->where('employee_id',$id)
                       ->get()
                       ->row_array();
    } 


public function create_employee_payment($data = array())
	{
		return $this->db->insert('employee_salary_payment', $data);

	}
	// employee Information
	public function employee_informationId($id)
	{
		return $result = $this->db->select('rate,rate_type')
                       ->from('employee_history')
                       ->where('employee_id',$id)
                       ->get()
                       ->row();

	}


	    public function salary_addition_fields($id)
	{
		return $result = $this->db->select('employee_salary_setup.*,salary_type.*')	
			 ->from('employee_salary_setup')
			 ->join('salary_type','salary_type.salary_type_id=employee_salary_setup.salary_type_id')
	         ->where('employee_salary_setup.employee_id',$id)
	         ->where('emp_sal_type',1)
			 ->get()
			 ->result();
	}

		 public function salary_deduction_fields($id)
	{
		return $result = $this->db->select('employee_salary_setup.*,salary_type.*')	
			 ->from('employee_salary_setup')
			 ->join('salary_type','salary_type.salary_type_id=employee_salary_setup.salary_type_id')
	         ->where('employee_salary_setup.employee_id',$id)
	         ->where('emp_sal_type',0)
			 ->get()
			 ->result();
	}

	public function salary_paymentinfo($id = null){
			return $this->db->select('count(DISTINCT(pment.emp_sal_pay_id)) as emp_sal_pay_id,pment.*,p.employee_id,p.first_name,p.last_name,desig.position_name,p.rate as basic,p.rate_type as salarytype')   
            ->from('employee_salary_payment pment')
            ->join('employee_history p', 'pment.employee_id = p.employee_id', 'left')
            ->join('position desig', 'desig.pos_id = p.pos_id', 'left')
            ->where('pment.emp_sal_pay_id',$id)
            ->group_by('pment.emp_sal_pay_id')
            ->get()
            ->result_array();

	}
public function setting()
	{
		return $this->db->get('setting')->row();
	}


	/**
	 * NEW METHOD: Calculate LOP (Loss on Pay) deduction for an employee
	 * 
	 * @param int $employee_id - Employee ID
	 * @param string $start_date - Salary period start date (Y-m-d)
	 * @param string $end_date - Salary period end date (Y-m-d)
	 * @param float $basic_salary - Employee's basic salary
	 * @return array - Contains lop_days, lop_amount, and per_day_salary
	 */
	public function calculate_lop_deduction($employee_id, $start_date, $end_date, $basic_salary)
	{
		// Get LOP leave type ID (assuming 8 as per your Leave_model.php)
		$lop_leave_type_id = 8;
		
		// Get all approved LOP leaves for this employee in the salary period
		$this->db->select('SUM(num_aprv_day) as total_lop_days');
		$this->db->from('leave_apply');
		$this->db->where('employee_id', $employee_id);
		$this->db->where('leave_type_id', $lop_leave_type_id);
		$this->db->where('leave_aprv_strt_date >=', $start_date);
		$this->db->where('leave_aprv_strt_date <=', $end_date);
		$this->db->where('num_aprv_day >', 0); // Only approved leaves
		
		$query = $this->db->get();
		$result = $query->row();
		
		$lop_days = !empty($result->total_lop_days) ? $result->total_lop_days : 0;
		
		// Calculate working days in the month (approximately 30 days)
		$working_days_in_month = 30;
		
		// Calculate per day salary
		$per_day_salary = $basic_salary / $working_days_in_month;
		
		// Calculate LOP deduction amount
		$lop_amount = $per_day_salary * $lop_days;
		
		return array(
			'lop_days' => $lop_days,
			'lop_amount' => round($lop_amount, 2),
			'per_day_salary' => round($per_day_salary, 2)
		);
	}
	
	/**
	 * NEW METHOD: Get LOP details for payslip display
	 * 
	 * @param int $employee_id - Employee ID
	 * @param string $payment_date - Payment date from employee_salary_payment
	 * @return array - LOP details
	 */
	public function get_lop_details_for_payslip($employee_id, $payment_date)
	{
		// Extract year and month from payment date
		$year = date('Y', strtotime($payment_date));
		$month = date('m', strtotime($payment_date));
		
		// Calculate salary period (1st to last day of month)
		$start_date = $year . '-' . $month . '-01';
		$end_date = date('Y-m-t', strtotime($start_date)); // Last day of month
		
		// Get employee basic salary
		$emp_data = $this->db->select('rate')
			->from('employee_history')
			->where('employee_id', $employee_id)
			->get()
			->row();
		
		$basic_salary = !empty($emp_data->rate) ? $emp_data->rate : 0;
		
		// Calculate LOP deduction
		return $this->calculate_lop_deduction($employee_id, $start_date, $end_date, $basic_salary);
	}

}