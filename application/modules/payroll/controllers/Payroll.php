<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->db->query('SET SESSION sql_mode = ""');
		$this->load->model(array(
			'Payroll_model',
			'employee/Employees_model'
		));	
		$this->load->library('numbertowords');

		if (! $this->session->userdata('isLogIn'))
			redirect('login');	 
	}

	public function emp_salary_setup_view(){   
		$this->permission->module('payroll','read')->redirect();
		$data['title']    = display('view_salary_setup');  ;
		$data['emp_sl']   = $this->Payroll_model->salary_setupView();
		$data['module']   = "payroll";
		$data['page']     = "emp_sal_setupview";   
		echo Modules::run('template/layout', $data); 
	} 


	public function create_salary_setup(){ 
		$data['title'] = display('selectionlist');
		#-------------------------------#
		$this->form_validation->set_rules('sal_name',display('sal_name'),'required|max_length[50]');
		$this->form_validation->set_rules('emp_sal_type',display('emp_sal_type'));
		
		#-------------------------------#
		if ($this->form_validation->run() === true) {

			$postData = [
				'sal_name'        => $this->input->post('sal_name',true),
				'emp_sal_type' 	  => $this->input->post('emp_sal_type',true),
				//'default_amount'  => $this->input->post('default_amount',true),
			];   

			if ($this->Payroll_model->emp_salsetup_create($postData)) { 
				$this->session->set_flashdata('message', display('successfully_saved'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("payroll/Payroll/create_salary_setup");
		} else {
			$data['title']  = display('salary_type');
			$data['module'] = "payroll";
			$data['page']   = "emp_salarysetup_form";
			$data['emp_sl'] = $this->Payroll_model->salary_setupView(); 
			echo Modules::run('template/layout', $data);   
			
		}   
	}
	
	public function delete_emp_salarysetup($id = null){ 
		$this->permission->module('payroll','delete')->redirect();

		if ($this->Payroll_model->emp_salstup_delete($id)) {
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect("payroll/Payroll/emp_salary_setup_view");
	}




	public function update_salsetup_form($id = null){
		$this->form_validation->set_rules('salary_type_id',null,'required|max_length[11]');
		$this->form_validation->set_rules('sal_name',display('sal_name'),'required|max_length[50]');
		$this->form_validation->set_rules('emp_sal_type',display('emp_sal_type')  ,'max_length[20]');
		#-------------------------------#
		if ($this->form_validation->run() === true) {

			$postData = [
				'salary_type_id' 	             => $this->input->post('salary_type_id',true),
				'sal_name' 	                     => $this->input->post('sal_name',true),
				'emp_sal_type' 		             => $this->input->post('emp_sal_type',true),
				
			]; 
			
			if ($this->Payroll_model->update_em_salstup($postData)) { 
				$this->session->set_flashdata('message', display('successfully_updated'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("payroll/Payroll/update_salsetup_form/". $id);

		} else {
			$data['title']  = display('update');
			$data['data']   =$this->Payroll_model->salarysetup_updateForm($id);
			$data['module'] = "payroll";
			$data['page']   = "update_salarysetup_form";   
			echo Modules::run('template/layout', $data); 
		}

	}


	public function salary_setup_view()
	{   
		$this->permission->module('payroll','read')->redirect();
		$data['title']         = display('view_salary_setup');  ;
		$data['emp_sl_setup']  = $this->Payroll_model->salary_setupindex();
		$data['module']        = "payroll";
		$data['page']          = "sal_setupview";   
		echo Modules::run('template/layout', $data); 
	} 


	public function create_s_setup(){ 
		$data['title'] = display('selectionlist');
		#-------------------------------#
		$this->form_validation->set_rules('employee_id',display('employee_id'),'required|max_length[50]');
		$this->form_validation->set_rules('sal_type',display('sal_type'));
		$this->form_validation->set_rules('amount[]',display('amount'));
		$this->form_validation->set_rules('salary_payable',display('salary_payable'));
		$this->form_validation->set_rules('absent_deduct',display('absent_deduct'));
		$this->form_validation->set_rules('tax_manager',display('tax_manager'));
		$amount=$this->input->post('amount');
		$calculation_type=$this->input->post('calculation_type'); // NEW: Get calculation types
		
		#-------------------------------#
		if ($this->form_validation->run() === true) {
			$date=date('Y-m-d');

			foreach($amount as $key=>$value)
			{	
				$postData = [
					'employee_id'           => $this->input->post('employee_id',true),
					'sal_type'              => $this->input->post('sal_type',true),
					'salary_type_id' 	    => $key,
					'amount' 	            => (!empty($value)?$value:0),
					'calculation_type'      => (!empty($calculation_type[$key])?$calculation_type[$key]:0), // NEW: Save calculation type
					'create_date'           => $date,
					'gross_salary'          => $this->input->post('gross_salary',true),
				]; 
			
					$this->Payroll_model->salary_setup_create($postData);
				
			}

			if($this->input->post('absent_deduct',true)==1)
			{
				$absent_deduct=1;	
			}
			else
			{
				$absent_deduct=0;
			}
			if($this->input->post('tax_manager',true)==1)
			{
				$tax_manager=1;	
			}
			else
			{
				$tax_manager=0;
			}
			$Data1 = [
				'employee_id'                => $this->input->post('employee_id',true),
				'salary_payable' 	         => $this->input->post('salary_payable',true),
				'absent_deduct' 	         => $absent_deduct,
				'tax_manager' 	             => $tax_manager,	
			];   
			$this->Payroll_model->salary_head_create($Data1);
			$this->session->set_flashdata('message', display('successfully_saved_saletup'));
			redirect("payroll/Payroll/create_s_setup");
		} else {
			$data['title']      = display('create');
			$data['module']     = "payroll";
			$data['slname']     = $this->Payroll_model->salary_typeName();
			$data['sldname']    = $this->Payroll_model->salary_typedName();
			$data['employee']   = $this->Payroll_model->empdropdown();
			$data['emp_sl_setup']   = $this->Payroll_model->salary_setupindex();
			$data['page']       = "salarysetup_form"; 
			echo Modules::run('template/layout', $data);   
			
		}   
	}
	
	public function delete_salsetup($id = null) 
	{ 
		$this->permission->module('payroll','delete')->redirect();

		if ($this->Payroll_model->emp_salstup_delete($id)) {
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect("payroll/Payroll/salary_setup_view");
	}



	public function salary_generate_view($id = null)
	{   
		$data['title']    = display('view_salary_generate');  
		$config["base_url"] = base_url('payroll/Payroll/salary_generate_view');
        $config["total_rows"]  = $this->db->count_all('salary_sheet_generate');
        $config["per_page"]    = 10;
        $config["uri_segment"] = 4;
        $config["last_link"] = "Last"; 
        $config["first_link"] = "First"; 
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';  
        $config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
        $config['full_tag_close'] = "</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tag_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        /* ends of bootstrap */
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["links"] = $this->pagination->create_links();
		$data['salgen']   = $this->Payroll_model->salary_generateView($config["per_page"], $page);
		$data['module']   = "payroll";
		$data['page']     = "sal_genview";   
		echo Modules::run('template/layout', $data); 
	} 

	public function create_salary_generate()
	{ 
		$data['title'] = display('selectionlist'); 
		#-------------------------------# 
		$this->form_validation->set_rules('name',display('salar_month'),'required|max_length[50]');
		#-------------------------------#

		if ($this->form_validation->run() === true) {

			list($month,$year) = explode(' ',$this->input->post('name'));
			$query = $this->db->select('*')->from('salary_sheet_generate')->where('name',$this->input->post('name'))->get()->num_rows();
			
			if ($query > 0) {
				$this->session->set_userdata(array('exception' => display('the_salary_of').$month. display('already_generated')));
				$this->session->set_flashdata('exception','Salary of '.$this->input->post('name').' Already Generated');
				redirect(base_url('payroll/Payroll/create_salary_generate'));
			}
           
			switch ($month)
			{
				case "January":
					$month = '1';
					break;
				case "February":
					$month = '2';
					break;
				case "March":
					$month = '3';
					break;
				case "April":
					$month = '4';
					break;
				case "May":
					$month = '5';
					break;
				case "June":
					$month = '6';
					break;
				case "July":
					$month = '7';
					break;
				case "August":
					$month = '8';
					break;
				case "September":
					$month = '9';
					break;
				case "October":
					$month = '10';
					break;
				case "November":
					$month = '11';
					break;
				case "December":
					$month = '12';
					break;
			}
			
			$fdate = $year.'-'.$month.'-'.'1';
			$lastday = date('t',strtotime($fdate));
			$edate = $year.'-'.$month.'-'.$lastday;
			$startd = $fdate;

			$employee = $this->db->select('employee_id')->from('employee_salary_setup')->group_by('employee_id')->get()->result();
			
			$ab = date('Y-m-d');
			$postData = [
				'name'                =>  $this->input->post('name',true),
				'gdate'               =>  $ab,
				'start_date' 	      =>  $startd, 
				'end_date' 	          =>  $edate, 
				'generate_by' 	      =>  $this->session->userdata('fullname'), 
			]; 

			$this->db->insert('salary_sheet_generate', $postData);
			
			if (sizeof($employee) > 0) {
				foreach($employee as $key=>$value) { 
		
					$aAmount = $this->db->select('a.gross_salary,a.sal_type,a.employee_id,b.first_name,b.last_name,b.rate')->from('employee_salary_setup a')->join('employee_history b','b.employee_id=a.employee_id')->where('a.employee_id', $value->employee_id)->get()->row();
					$Amount = $aAmount->gross_salary;
					$startd = $startd;
					$end = $edate;
					
					$att_in = $this->db->select('a.time,MIN(a.time) as intime,MAX(a.time) as outtime,a.uid, DATE(time) as mydate')
						->from('attendance_history a')
						->where('a.uid',$value->employee_id)
						->where('DATE(a.time) >=',date('Y-m-d', strtotime($startd)))
						->where('DATE(a.time) <=',date('Y-m-d', strtotime($end)))
						->group_by('DATE(a.time)')
						->get()
						->result();
						
					$idx = 1;
					$totalhour = [];
					$totalday = [];
					
					foreach ($att_in as $attendancedata) { 
						$date_a = new DateTime($attendancedata->outtime);
						$date_b = new DateTime($attendancedata->intime);
						$interval = date_diff($date_a,$date_b);

						$totalwhour = $interval->format('%h:%i:%s');
						$totalhour[$idx] = $totalwhour;
						$totalday[$idx] = $attendancedata->mydate;
						$idx++;
					}
					
					$seconds = 0;
					foreach($totalhour as $t) {
						$timeArr = array_reverse(explode(":", $t));

						foreach ($timeArr as $key => $tv) {
							if ($key > 2) break;
							$seconds += pow(60, $key) * $tv;
						}
					}

					$hours = floor($seconds / 3600);
					$mins = floor(($seconds - ($hours*3600)) / 60);
					$secs = floor($seconds % 60);
					$times = $hours * 3600 + $mins * 60 + $secs;

					$wormin = ($times/60);
					$worhour = number_format($wormin/60,2);
					
					if($aAmount->sal_type == 1) {
						// Hourly salary
						$dStart = new DateTime($startd);
						$dEnd  = new DateTime($end);
						$dDiff = $dStart->diff($dEnd);
						$numberofdays = $dDiff->days+1;
						$totamount = $Amount*$worhour;
						$PYI = ($totamount/$numberofdays)*365;
						$PossibleYearlyIncome = round($PYI);
						
						$this->db->select('*');
						$this->db->from('payroll_tax_setup');
						$this->db->where("start_amount <",$PossibleYearlyIncome);
						$query = $this->db->get();
						$taxrate = $query->result_array();
						$TotalTax = 0;
						
						foreach($taxrate as $row) {
							if($PossibleYearlyIncome > $row['start_amount'] && $PossibleYearlyIncome > $row['end_amount']) {
								$diff = $row['end_amount']-$row['start_amount'];
							}
							if($PossibleYearlyIncome > $row['start_amount'] && $PossibleYearlyIncome < $row['end_amount']) {
								$diff = $PossibleYearlyIncome-$row['start_amount'];
							}
							$tax = (($row['rate']/100)*$diff);
							$TotalTax += $tax;	
						} 
						
						$TaxAmount = ($TotalTax/365)*$numberofdays;
						$netAmount = number_format(($totamount-$TaxAmount),2);
						
					} else if($aAmount->sal_type == 2) {
						// Monthly salary
						$netAmount = $Amount;
					}
					
					// =====================================================
					// NEW: Calculate LOP (Loss on Pay) Deduction
					// =====================================================
					$lop_details = $this->Payroll_model->calculate_lop_deduction(
						$value->employee_id,
						$startd,
						$end,
						$aAmount->rate  // Use basic salary from employee_history
					);
					
					$lop_days = $lop_details['lop_days'];
					$lop_amount = $lop_details['lop_amount'];
					
					// Deduct LOP from net salary
					if ($lop_amount > 0) {
						$netAmount = $netAmount - $lop_amount;
					}
					// =====================================================
					
					$workingper = count($totalday);
					$paymentData = array(
						'employee_id'           => $value->employee_id,
						'total_salary'          => $netAmount,
						'total_working_minutes' => $worhour,
						'salary_name'           => $this->input->post('name',true),
						'working_period'        => $workingper,
						'lop_days'              => $lop_days,      // NEW: Store LOP days
						'lop_amount'            => $lop_amount,    // NEW: Store LOP amount
					);

					if(!empty($aAmount->employee_id)) {
						$this->db->insert('employee_salary_payment', $paymentData);
						
						$c_code = $aAmount->employee_id;
						$c_name = $aAmount->first_name.$aAmount->last_name;
						$c_acc = $c_code.'-'.$c_name;
						$headcode = $this->db->select('HeadCode')->from('acc_coa')->where('HeadName',$c_acc)->get()->row()->HeadCode;
						$createby = $this->session->userdata('fullname');
						$createdate = date('Y-m-d H:i:s');

						$accpayable = array(
							'VNo'            => $this->input->post('name',true),
							'Vtype'          => 'Generated Salary',
							'VDate'          => date('Y-m-d'),
							'COAID'          => $headcode,
							'Narration'      => 'Salary For Employee Id '.$aAmount->employee_id.($lop_days > 0 ? ' (LOP: '.$lop_days.' days)' : ''),
							'Debit'          => 0,
							'Credit'         => intval(str_replace(',', '', $netAmount)),
							'IsPosted'       => 1,
							'CreateBy'       => $this->session->userdata('id'),
							'CreateDate'     => date('Y-m-d H:i:s'),
							'IsAppove'       => 1
						); 

						$this->db->insert('acc_transaction', $accpayable);
					}
				}
			}
			
			$this->session->set_flashdata('message', display('successfully_saved_saletup'));
			redirect("payroll/Payroll/create_salary_generate");
			
		} else {
			$data['title']  = display('create');
			$config["base_url"] = base_url('payroll/Payroll/create_salary_generate');
			$config["total_rows"]  = $this->db->count_all('salary_sheet_generate');
			$config["per_page"]    = 3;
			$config["uri_segment"] = 4;
			$config["last_link"] = "Last"; 
			$config["first_link"] = "First"; 
			$config['next_link'] = 'Next';
			$config['prev_link'] = 'Prev';  
			$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
			$config['full_tag_close'] = "</ul>";
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
			$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
			$config['next_tag_open'] = "<li>";
			$config['next_tag_close'] = "</li>";
			$config['prev_tag_open'] = "<li>";
			$config['prev_tagl_close'] = "</li>";
			$config['first_tag_open'] = "<li>";
			$config['first_tagl_close'] = "</li>";
			$config['last_tag_open'] = "<li>";
			$config['last_tagl_close'] = "</li>";
			/* ends of bootstrap */
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$data["links"] = $this->pagination->create_links();
			$data['module'] = "payroll";
			$data['page']   = "salary_generate_form"; 
			$data['salgen'] = $this->Payroll_model->salary_generateView($config["per_page"], $page);
			echo Modules::run('template/layout', $data);   
		}   
	}
	
	public function delete_sal_gen($id = null) 
	{ 
		$sal_name = $this->db->select('name')->from('salary_sheet_generate')->where('ssg_id',$id)->get()->row()->name;

		if ($this->Payroll_model->salary_gen_delete($id,$sal_name)) {
			#set success message
			$this->session->set_flashdata('message',display('delete_successfully'));
		} else {
			#set exception message
			$this->session->set_flashdata('exception',display('please_try_again'));
		}
		redirect("payroll/Payroll/create_salary_generate");
	}

	public function update_salgen_form($id = null){
		$this->form_validation->set_rules('ssg_id',null,'max_length[11]');
		$this->form_validation->set_rules('name',display('name'),'max_length[50]');
		$this->form_validation->set_rules('start_date',display('start_date'));
		$this->form_validation->set_rules('end_date',display('end_date'));
		
		#-------------------------------#
		if ($this->form_validation->run() === true) {
			$postData = [
				'ssg_id' 	             => $this->input->post('ssg_id',true),
				'name'                   => $this->input->post('name',true),
				'start_date' 	         => $this->input->post('start_date',true),
				'end_date' 	             => $this->input->post('end_date',true),
			]; 
			
			if ($this->Payroll_model->update_sal_gen($postData)) { 
				$this->session->set_flashdata('message', display('successfully_updated'));
			} else {
				$this->session->set_flashdata('exception',  display('please_try_again'));
			}
			redirect("payroll/Payroll/salary_generate_view");
		} else {
			$data['title']  = display('update');
			$data['data']   = $this->Payroll_model->salargen_updateForm($id);
			$data['module'] = "payroll";
			$data['page']   = "update_salarygenerate_form";   
			echo Modules::run('template/layout', $data); 
		}
	}
	
	/* salary setup update form  start*/
	public function updates_salstup_form($id = null){

		#-------------------------------#
		$this->form_validation->set_rules('employee_id',display('employee_id'),'required|max_length[50]');
		$this->form_validation->set_rules('sal_type',display('sal_type'));
		$this->form_validation->set_rules('amount[]',display('amount'));
		$this->form_validation->set_rules('salary_payable',display('salary_payable'));
		$this->form_validation->set_rules('absent_deduct',display('absent_deduct'));
		$this->form_validation->set_rules('tax_manager',display('tax_manager'));
		$amount = $this->input->post('amount');
		$calculation_type = $this->input->post('calculation_type'); // NEW: Get calculation types

		#-------------------------------#
		if ($this->form_validation->run() === true) {

			foreach($amount as $key=>$value) {

				$postData = array(
					'employee_id'        => $this->input->post('employee_id',true),
					'sal_type'           => $this->input->post('sal_type',true),
					'salary_type_id' 	 => $key,
					'amount' 	         => $value,
					'calculation_type'   => (!empty($calculation_type[$key])?$calculation_type[$key]:0), // NEW: Update calculation type
					'gross_salary'       => $this->input->post('gross_salary',true),
				);

				$this->Payroll_model->update_sal_stup($postData);
			}

			if($this->input->post('absent_deduct',true)==1) {
				$absent_deduct = 1;	
			} else {
				$absent_deduct = 0;
			}

			if($this->input->post('tax_manager',true)==1) {
				$tax_manager = 1;	
			} else {
				$tax_manager = 0;
			}

			$Data = [
				'employee_id'         => $this->input->post('employee_id',true),
				'salary_payable' 	  => $this->input->post('salary_payable',true),
				'absent_deduct' 	  => $absent_deduct,
				'tax_manager' 	      => $tax_manager,
			];   

			$this->Payroll_model->update_sal_head($Data);

			$this->session->set_flashdata('message', display('successfully_saved_saletup'));
			redirect("payroll/Payroll/updates_salstup_form/". $id);
			
		} else {

			$data['title']       = display('update');
			$data['data']        = $this->Payroll_model->salary_s_updateForm($id);
			$data['samlft']      = $this->Payroll_model->salary_amountlft($id);
			$data['amo']         = $this->Payroll_model->salary_amount($id);
			$data['bb']          = $this->Payroll_model->get_empid($id);
			$data['gt']          = $this->Payroll_model->get_type($id);
			$data['employee']    = $this->Payroll_model->empdropdown();
			$data['type']        = $this->Payroll_model->type();
			$data['payable']     = $this->Payroll_model->payable();
			$data['gt_pay']      = $this->Payroll_model->get_payable($id);
			$data['EmpRate']     = $this->Payroll_model->employee_informationId($id);
			$data['module']      = "payroll";
			$data['page']        = "update_sal_setup_form";   
			echo Modules::run('template/layout', $data); 
		}
	}
	
	// salary with tax calculation
	public function salarywithtax(){
		$tamount = $this->input->post('amount');
		$amount = $tamount*12;
		
		$this->db->select('*');
		$this->db->from('payroll_tax_setup');
		$this->db->where("start_amount <",$amount);
		$query = $this->db->get();
		$taxrate = $query->result_array();
		$TotalTax = 0;
		
		foreach($taxrate as $row) {
			if($amount > $row['start_amount'] && $amount > $row['end_amount']) {
				$diff = $row['end_amount']-$row['start_amount'];
			}
			if($amount > $row['start_amount'] && $amount < $row['end_amount']) {
				$diff = $amount-$row['start_amount'];
			}
			$tax = (($row['rate']/100)*$diff);
			$TotalTax += $tax;	
		} 
		
		$salary = $TotalTax/12;
		echo json_encode(round($salary));
	}

	//employee Basic Salary get
	public function employeebasic(){
		$id = $this->input->post('employee_id');
		$data = $this->db->select('rate,rate_type')->from('employee_history')->where('employee_id',$id)->get()->row();
		$basic = $data->rate;
		
		if($data->rate_type ==1) {
			$type = 'Hourly';
		} else {
			$type = 'Salary';	
		}
		
		$sent = array(
			'rate'      => $data->rate,
			'rate_type' => $data->rate_type,
			'stype'     => $type
		);
		echo json_encode($sent);
	}
	
	public function emp_payment_view()
	{   
		$data['title']         = display('view_employee_payment'); 
		$config["base_url"]    = base_url('payroll/Payroll/emp_payment_view');
		$config["total_rows"]  = $this->db->count_all('employee_salary_payment');
		$config["per_page"]    = 25;
		$config["uri_segment"] = 4;
		$config["last_link"] = "Last"; 
		$config["first_link"] = "First"; 
		$config['next_link'] = 'Next';
		$config['prev_link'] = 'Prev';  
		$config['full_tag_open'] = "<ul class='pagination col-xs pull-right'>";
		$config['full_tag_close'] = "</ul>";
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
		$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
		$config['next_tag_open'] = "<li>";
		$config['next_tag_close'] = "</li>";
		$config['prev_tag_open'] = "<li>";
		$config['prev_tagl_close'] = "</li>";
		$config['first_tag_open'] = "<li>";
		$config['first_tagl_close'] = "</li>";
		$config['last_tag_open'] = "<li>";
		$config['last_tagl_close'] = "</li>";
		/* ends of bootstrap */
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data["links"]     = $this->pagination->create_links();
		$data['module']    = "payroll"; 
		$data['emp_pay']   = $this->Employees_model->emp_paymentView($config["per_page"], $page);
		$data['bank_list'] = $this->Employees_model->bank_list();
		$data['module']    = "payroll";
		$data['page']      = "paymentview";   
		echo Modules::run('template/layout', $data); 
	} 

	public function payslip($id = null){
		$data['title']         = display('salary_slip');
		$data['paymentdata']   = $this->Payroll_model->salary_paymentinfo($id);  
		$data['addition']      = $this->Payroll_model->salary_addition_fields($data['paymentdata'][0]['employee_id']);
		$data['deduction']     = $this->Payroll_model->salary_deduction_fields($data['paymentdata'][0]['employee_id']);
		$data['amountinword']  = $this->numbertowords->convert_number(intval(str_replace(',', '', $data['paymentdata'][0]['total_salary'])));
		$data['setting']       = $this->Payroll_model->setting();
		$data['module']        = "payroll";
		$data['page']          = "payslip";   
		echo Modules::run('template/layout', $data); 
	}
	
	/**
	 * NEW METHOD: Verify LOP calculation for testing
	 * Access via: your-domain.com/payroll/Payroll/verify_lop_calculation/EMPLOYEE_ID
	 */
	public function verify_lop_calculation($employee_id = null)
	{
		if (empty($employee_id)) {
			echo "<h3>Error: Please provide employee_id</h3>";
			echo "<p>Usage: /payroll/Payroll/verify_lop_calculation/EMPLOYEE_ID</p>";
			return;
		}
		
		// Get current month dates
		$start_date = date('Y-m-01');
		$end_date = date('Y-m-t');
		
		// Get employee basic salary
		$employee = $this->db->select('rate, first_name, last_name')
			->from('employee_history')
			->where('employee_id', $employee_id)
			->get()
			->row();
		
		if (empty($employee)) {
			echo "<h3>Error: Employee not found</h3>";
			return;
		}
		
		// Calculate LOP
		$lop_details = $this->Payroll_model->calculate_lop_deduction(
			$employee_id,
			$start_date,
			$end_date,
			$employee->rate
		);
		
		// Display results
		echo "<div style='font-family: Arial, sans-serif; padding: 20px;'>";
		echo "<h2>LOP Calculation Verification</h2>";
		echo "<hr>";
		echo "<p><strong>Employee:</strong> {$employee->first_name} {$employee->last_name} ({$employee_id})</p>";
		echo "<p><strong>Basic Salary:</strong> ₹" . number_format($employee->rate, 2) . "</p>";
		echo "<p><strong>Period:</strong> {$start_date} to {$end_date}</p>";
		echo "<hr>";
		echo "<p><strong>LOP Days:</strong> <span style='color: #ff9800; font-size: 18px;'>{$lop_details['lop_days']}</span></p>";
		echo "<p><strong>Per Day Salary:</strong> ₹" . number_format($lop_details['per_day_salary'], 2) . "</p>";
		echo "<p><strong>LOP Deduction:</strong> <span style='color: red; font-weight: bold; font-size: 20px;'>₹" . 
			 number_format($lop_details['lop_amount'], 2) . "</span></p>";
		
		// Get LOP leave details
		$lop_leaves = $this->db->select('*')
			->from('leave_apply')
			->where('employee_id', $employee_id)
			->where('leave_type_id', 8)
			->where('leave_aprv_strt_date >=', $start_date)
			->where('leave_aprv_strt_date <=', $end_date)
			->where('num_aprv_day >', 0)
			->get()
			->result();
		
		if (!empty($lop_leaves)) {
			echo "<hr>";
			echo "<h3>LOP Leave Details:</h3>";
			echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
			echo "<tr style='background-color: #f0f0f0;'>
					<th>Leave ID</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Days</th>
					<th>Applied Date</th>
				  </tr>";
			foreach ($lop_leaves as $leave) {
				echo "<tr>";
				echo "<td>{$leave->leave_appl_id}</td>";
				echo "<td>{$leave->leave_aprv_strt_date}</td>";
				echo "<td>{$leave->leave_aprv_end_date}</td>";
				echo "<td style='text-align: center; font-weight: bold;'>{$leave->num_aprv_day}</td>";
				echo "<td>{$leave->apply_date}</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "<hr>";
			echo "<p style='color: green;'><em>✓ No LOP leaves found for this period.</em></p>";
		}
		
		echo "</div>";
	}

}