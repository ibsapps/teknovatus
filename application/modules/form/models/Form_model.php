<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('general');
		$this->user = $this->session->userdata('user_name');
		$this->email = $this->session->userdata('user_email');
		$this->date = date('Y-m-d H:i:s');
		$this->today = date('Y-m-d');
		$this->year = date('Y');
	}

	public function getFormType()
	{
		$output = '';
		$output .= '<option value=""></option>';

		$formType = $this->db->select('code, description')
							// ->where('code', '1')
							->where('is_active', '1')
							->order_by('description', 'ASC')
							->get('form_type')->result_array();
		$employee_id 				= $this->session->userdata('employee_id');
		$sql 				= "SELECT * FROM hris_employee WHERE nik = '$employee_id' ORDER BY id_employee DESC";
		$query 				= $this->db->query($sql);
		$res 				= $query->result();
		$action 			= decrypt($res[0]->action);
		// dumper($action);
		foreach ($formType as $key) {
			$code		= $key['code'];
			if($action != "Leaving" || $code != "MDCR"){
					$output .= '<option value="' . $key['code'] . '" >' . $key['description'] . '</option>';
			}
		}
		return $output;
	}

	public function getAll($field, $table, $where = null)
	{
		return $this->db->select($field)->where($where)->get($table)->result_array();
	}

	public function getOneById($field, $table, $where = null)
	{
		return $this->db->select($field)->where($where)->get($table)->row_array()[$field];
	}

	public function get_data_employee($employee_id){

		$sql = "SELECT * FROM hris_employee WHERE nik = '$employee_id' ORDER BY id_employee DESC LIMIT 1";
		$query = $this->db->query($sql);
		$res = $query->result();
		// var_dump($res);
		return $res;
	}

	public function cek_employee($hak_pengajuan=""){
		$sql = "SELECT 	
					complete_name
				FROM		
					hris_employee
				WHERE nik = '$hak_pengajuan'
				ORDER BY id_employee DESC LIMIT 1";

		$query = $this->db->query($sql);
		$res = $query->result();
		$res = decrypt($res[0]->complete_name);
		return $res;
	}

	public function initial_create($formType, $formData = '',$requestNumber = '', $table = '')
	{

		switch ($formType) {

			case 'KPI':

				$eval_year = $this->year - 1;
				$formData = array(
					'request_number' => $requestNumber,
					'is_status' => 0,
					'employee_name' => $formData[0]['employee_name'],
					'employee_nik' => $formData[0]['employee_nik'],
					'position' => $formData[0]['position'],
					'division' => $formData[0]['division'],
					'join_date'	=> $formData[0]['join_date'],
					'office_location'	=> $formData[0]['office_location'],
					'employment_status' => $formData[0]['employment_status'],
					'sub_total_kpi' => 0,
					'sub_total_weight' => 0,
					'sub_total_qualitative' => 0,
					'pre_final_score' => 0,
					'final_score' => 0,
					'evaluation_period_start' => $eval_year.'-01-01',
					'evaluation_period_end' => $eval_year.'-12-12',
					'employee_id' => $this->session->userdata('employee_id'),
					'new_employee_flag' => 0,
					'created_by' => $this->email,
					'created_at' => $this->date);
				break;

			case 'PLAN':

				$eval_year = $this->year - 1;
				$formData = array(
					'request_number' => $requestNumber,
					'is_status' => 0,
					'employee_name' => $formData[0]['employee_name'],
					'employee_nik' => $formData[0]['employee_nik'],
					'position' => $formData[0]['position'],
					'division' => $formData[0]['division'],
					'join_date'	=> $formData[0]['join_date'],
					'office_location'	=> $formData[0]['office_location'],
					'employment_status' => $formData[0]['employment_status'],
					'sub_total_kpi' => 0,
					'sub_total_weight' => 0,
					'sub_total_qualitative' => 0,
					'pre_final_score' => 0,
					'final_score' => 0,
					'evaluation_period_start' => $eval_year.'-01-01',
					'evaluation_period_end' => $eval_year.'-12-12',
					'employee_id' => $this->session->userdata('employee_id'),
					'new_employee_flag' => 1,
					'created_by' => $this->email,
					'created_at' => $this->date);
				break;

			case 'MDCR':
				$id_employee = $this->session->userdata('id_hr_emp');
				$id_hr_emp 		= encrypt("$id_employee");

				$today = $this->today;
				$formRequest = array(
					'request_number' => $requestNumber,
					'is_status' => 0,
					'form_type' => 'MDCR',
					'employee_id' => $this->session->userdata('employee_id'),
					'created_by' => $this->email,
					'created_at' => $this->date
				);

				$this->db->insert('form_request', $formRequest);
				$requestId = $this->db->insert_id();

				$employee_id = $this->session->userdata('employee_id');
				// --old
				$sql = "SELECT * FROM hris_employee WHERE nik = '$employee_id' ORDER BY id_employee DESC LIMIT 1";
				$query = $this->db->query($sql);
				$res = $query->result();
				$complete_name 			= $res[0]->complete_name;
				$employee_group 		= $res[0]->employee_group;
				$phone_number	 		= $res[0]->phone_number;
				$department		 		= $res[0]->department;
				$personnel_area	 		= $res[0]->personnel_area;
				$marital_status	 		= $res[0]->marital_status;
				$eg 					= decrypt($employee_group);

				// old
				// $sql2 = "SELECT TOP 1 id FROM hris_medical_pagu_rawat_jalan WHERE grade LIKE '$eg' AND start_date <= '$today' AND end_date > '$today' ORDER BY id DESC";

				$sql2 = "SELECT id FROM hris_medical_pagu_rawat_jalan WHERE grade LIKE '$eg' AND start_date <= '$today' AND end_date > '$today' ORDER BY id DESC LIMIT 1";

				$query2 = $this->db->query($sql2);
				$res2 = $query2->result();
				$id_eg_prj	 		= encrypt((string)$res2[0]->id);

				// $sql3 = "SELECT TOP 1 id FROM hris_medical_pagu_rawat_inap WHERE grade LIKE '$eg' AND start_date <= '$today' AND end_date > '$today' ORDER BY id DESC";

				$sql3 = "SELECT id FROM hris_medical_pagu_rawat_inap WHERE grade LIKE '$eg' AND start_date <= '$today' AND end_date > '$today' ORDER BY id DESC LIMIT 1";
				$query3 = $this->db->query($sql3);
				$res3 = $query3->result();
				$id_eg_pri	 		= encrypt((string)$res3[0]->id);
				
				// $sql4 = "SELECT TOP 1 id FROM hris_medical_pagu_kacamata WHERE grade LIKE '$eg' AND start_date <= '$today' AND end_date > '$today' ORDER BY id DESC";

				$sql4 = "SELECT id FROM hris_medical_pagu_kacamata WHERE grade LIKE '$eg' AND start_date <= '$today' AND end_date > '$today' ORDER BY id DESC LIMIT 1";

				$query4 = $this->db->query($sql4);
				$res4 = $query4->result();
				$id_eg_pk	 		= encrypt((string)$res4[0]->id);

				$formData = array(
					'complete_name' => $complete_name,
					'employee_group'=> $employee_group,
					'phone_number'	=> $phone_number,
					'department'	=> $department,
					'personnel_area'=> $personnel_area,
					'employee_id'	=> $employee_id,
					'request_id' 	=> $requestId,
					'created_by' 	=> $this->email,
					'created_at' 	=> $this->date,
					'id_eg_prj'		=> $id_eg_prj,
					'id_eg_pri'		=> $id_eg_pri,
					'id_eg_pk'		=> $id_eg_pk,
					'marital_status'=> $marital_status,
					'id_hr_emp'		=> $id_hr_emp
				);

				if ($requestId  != '') {
					$this->db->insert($table, $formData);
				}

				break;

			case 'PPD':

				$formRequest = array(
		            'request_number' => $requestNumber,
		            'form_type' => $formType,
		            'employee_id' => $formData[0]['nik'],
		            'is_status' => 0,
		            'created_by' => $this->email,
		            'created_at' => $this->date);

				$this->db->insert('form_request', $formRequest);
				$requestId = $this->db->insert_id();

				$formData = array(
                    'request_id'  			=> $requestId,
                    'ca_type'  				=> 'Travel',
                    'matrix_approval_id'  	=> '0',
                    'is_status'  			=> '0',
                    'matrix_type'  			=> 'ppd',
                    'created_by' 			=> $this->email,
                    'created_at'			=> $this->date);

				$this->db->insert('bussiness_trip', $formData);

				break;

			case 'LPD':

				$formRequest = array(
		            'request_number' => $requestNumber,
		            'form_type' => $formType,
		            'employee_id' => $formData[0]['nik'],
		            'is_status' => 0,
		            'created_by' => $this->email,
		            'created_at' => $this->date);

				$this->db->insert('form_request', $formRequest);
				$requestId = $this->db->insert_id();

				$formData = array(
                    'request_id'  			=> $requestId,
                    'ca_type'  				=> 'Travel',
                    'matrix_approval_id'  	=> '0',
                    'matrix_type'  			=> 'lpd',
                    'is_status'  			=> '0',
                    'created_by' 			=> $this->email,
                    'created_at'			=> $this->date);

				$this->db->insert('bussiness_trip_settlement', $formData);

				break;

			default:
				break;

		}
		
		return $requestId;
	}

	public function save_form($formType, $formData = '', $table = '')
	{

		switch ($formType) {

			case 'KPI':

				$formData = array(
					'is_status' => 1,
					'sub_total_kpi' => $this->input->post('sub_total_kpi'),
					'sub_total_weight' => $this->input->post('sub_total_weight'),
					'sub_total_qualitative' => $this->input->post('sub_total_qualitative'),
					'departement' => $this->input->post('kpi_departemen'),
					'direct_manager' => $this->input->post('atasan_langsung'),
					'work_efficiency' => $this->input->post('work_efficiency'),
					'work_quality' => $this->input->post('work_quality'),
					'communication' => $this->input->post('communication'),
					'planing' => $this->input->post('planing'),
					'problem_solving' => $this->input->post('problem_solving'),
					'team_work' => $this->input->post('team_work'),
					'potential' => $this->input->post('potential'),
					'initiative' => $this->input->post('initiative'),
					'leadership' => $this->input->post('leadership'),
					'work_efficiency_result' => $this->input->post('result_work_efficiency'),
					'work_quality_result' => $this->input->post('result_work_quality'),
					'communication_result' => $this->input->post('result_communication'),
					'planing_result' => $this->input->post('result_planing'),
					'problem_solving_result' => $this->input->post('result_problem_solving'),
					'team_work_result' => $this->input->post('result_team_work'),
					'potential_result' => $this->input->post('result_potential'),
					'initiative_result' => $this->input->post('result_initiative'),
					'leadership_result' => $this->input->post('result_leadership'),
					'grand_total_kpi' => $this->input->post('grand_total_kpi'),
					'grand_total_qualitative' => $this->input->post('grand_total_qualitative'),
					'pre_final_score' => $this->input->post('pre_final_score'),
					'final_score' => $this->input->post('pre_final_score'),
					'comment_employee' => $this->input->post('comment_employee'),
					'comment_head_1' => $this->input->post('comment_head_1'),
					'comment_head_2' => $this->input->post('comment_head_2'),
					'plan_total_weight' => $this->input->post('plan_total_weight'),
					'area_improvement' => $this->input->post('area_improvement'),
					'development_plan' => $this->input->post('development_plan'),
					'performance_plan_flag' => ($this->input->post('plan_total_weight') != 0) ? 1 : 0,
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);

				$this->db->where('id', $this->input->post('id'));
				if ($this->db->update('performance_appraisal', $formData)) {
					return true;
				} else {
					return false;
				}
					
				break;

			case 'PLAN':

				$formData = array(
					'is_status' => 1,
					'sub_total_kpi' => 0,
					'sub_total_weight' => 0,
					'sub_total_qualitative' => 0,
					'departement' => $this->input->post('kpi_departemen'),
					'direct_manager' => $this->input->post('atasan_langsung'),
					'work_efficiency' => 0,
					'work_quality' => 0,
					'communication' => 0,
					'planing' => 0,
					'problem_solving' => 0,
					'team_work' => 0,
					'potential' => 0,
					'initiative' => 0,
					'leadership' => 0,
					'work_efficiency_result' => 0,
					'work_quality_result' => 0,
					'communication_result' => 0,
					'planing_result' => 0,
					'problem_solving_result' => 0,
					'team_work_result' => 0,
					'potential_result' => 0,
					'initiative_result' => 0,
					'leadership_result' => 0,
					'grand_total_kpi' => 0,
					'grand_total_qualitative' => 0,
					'pre_final_score' => 0,
					'final_score' => 0,
					'comment_employee' => '',
					'comment_head_1' => '',
					'comment_head_2' => '',
					'plan_total_weight' => $this->input->post('plan_total_weight'),
					'area_improvement' => '',
					'development_plan' => '',
					'performance_plan_flag' => ($this->input->post('plan_total_weight') != 0) ? 1 : 0,
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);

				$this->db->where('id', $this->input->post('id'));
				if ($this->db->update('performance_appraisal', $formData)) {
					return true;
				} else {
					return false;
				}
					
				break;
			
			default:
				break;

		}
		
	}

	public function getCity()
	{
		$output = '';
		$output .= '<option value=""></option>';

		$list_city = $this->db->select('IDPROVINSI, PROVINSI, NAMA_KABUPATEN_KOTA')
							->where('NAMA_KABUPATEN_KOTA !=', null)
							->order_by('IDPROVINSI', 'ASC')
							->get('master_city')->result_array();

		foreach ($list_city as $key) {
			$output .= '<option value="' . $key['IDPROVINSI'] . '" >' . $key['NAMA_KABUPATEN_KOTA'] . '</option>';
		}
		return $output;
	}

	public function getRequestNotes($request_id)
	{
		$this->db->select('id, notes, created_at, created_by');
     	$this->db->from('request_notes');
      	$this->db->where('request_id', $request_id);
      	$this->db->order_by('created_at', 'ASC');
      	return $this->db->get()->result_array();
	}

	public function getUserList($field, $table, $where)
	{
		// var_dump($this->session);
		$output = '';
		$output .= '<option value=""></option>';

		$userList = $this->db->select($field)->where($where)->get($table)->result_array();
		foreach ($userList as $key) {
			$output .= '<option value="' . $key[$field] . '" >' . $key[$field] . '</option>';
		}
		return $output;
	}

	public function saveApproval($email, $requestId, $layer_nik)
	{
		$i = 0;
		$priority = 1;
		$count = count($email);
		$cek = $this->db->get_where('form_approval', array('request_id' => $requestId));
		
		$sql = "select id, request_number, form_type, is_status, no_req_mdcr, is_status_admin_hr, is_status_divhead_hr, revise_after_f1 from form_request where id='$requestId' ";
		$query = $this->db->query($sql);
		$res = $query->result();
		if ($res[0]->revise_after_f1 == 1) {
			for ($i = 0; $i < $count; $i++) {

				if ($email[$i] === 'farida@ibstower.com') {
					$alias = 'Commitee';
				} else {
					$alias = str_replace('@ibstower.com', '', $email[$i]);
				}
				if ($priority == 1 && $layer_nik[$i] !='00000000' ) {
					$approval = array(
						'request_id' => $requestId,
						'approval_priority' => $priority,
						'approval_employee_id' => $layer_nik[$i],
						'approval_email' => $email[$i],
						'approval_alias' => $alias,
						'approval_status' => 'Approved',
						'approval_note' => '',
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $this->email
					);
				} elseif ($priority == 1 && $layer_nik[$i] == '00000000' ) {
					$approval = array(
						'request_id' => $requestId,
						'approval_priority' => $priority,
						'approval_employee_id' => $layer_nik[$i],
						'approval_email' => $email[$i],
						'approval_alias' => $alias,
						'approval_status' => 'In Progress',
						'approval_note' => '',
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $this->email
					);
				} elseif ($priority == 2 && $layer_nik[$i] == '00000000' ) {
					$approval = array(
						'request_id' => $requestId,
						'approval_priority' => $priority,
						'approval_employee_id' => $layer_nik[$i],
						'approval_email' => $email[$i],
						'approval_alias' => $alias,
						'approval_status' => 'In Progress',
						'approval_note' => '',
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $this->email
					);
				}	else {
					$approval = array(
						'request_id' => $requestId,
						'approval_priority' => $priority,
						'approval_employee_id' => $layer_nik[$i],
						'approval_email' => $email[$i],
						'approval_alias' => $alias,
						'approval_status' => '',
						'approval_note' => '',
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $this->email
					);
				}

				$this->db->where('request_id', $requestId);
				$this->db->where('approval_priority', $priority);
				$this->db->update('form_approval', $approval);
				// $layer[] = $approval;
				$priority++;
			}

			return true;
			// $this->db->where('request_id', $requestId);
			// if ($this->db->update('form_approval', $layer)) {
			// 	return true;
			// } else {
			// 	return false;
			// }
		} else {

			if($cek->num_rows() > 0){
				$this->db->delete('form_approval',array('request_id' => $requestId));
			}

			//print_r($cek->num_rows());die;
			for ($i = 0; $i < $count; $i++) {

				if ($email[$i] === 'farida@ibstower.com') {
					$alias = 'Commitee';
				} else {
					$alias = str_replace('@ibstower.com', '', $email[$i]);
				}

				if ($priority == 1) {
					$approval = array(
						'request_id' => $requestId,
						'approval_priority' => $priority,
						'approval_employee_id' => $layer_nik[$i],
						'approval_email' => $email[$i],
						'approval_alias' => $alias,
						'approval_status' => 'In Progress',
						'approval_note' => '',
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $this->email
					);
				} else {
					$approval = array(
						'request_id' => $requestId,
						'approval_priority' => $priority,
						'approval_employee_id' => $layer_nik[$i],
						'approval_email' => $email[$i],
						'approval_alias' => $alias,
						'approval_status' => '',
						'approval_note' => '',
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $this->email
					);
				}

				$layer[] = $approval;
				$priority++;
			}

			if ($this->db->insert_batch('form_approval', $layer)) {
				return true;
			} else {
				return false;
			}
		}

	}

	public function updateApproval($approval_id, $approval_priority, $approval_status, $email, $requestId)
    {
        $transok = false;
        $count = count($email);

        # check approval layer
        // $sql = "SELECT TOP 1 approval_priority, approval_status FROM form_approval WHERE approval_priority < '$approval_priority' AND request_id = '$requestId'";

        $sql = "SELECT approval_priority, approval_status FROM form_approval WHERE approval_priority < '$approval_priority' AND request_id = '$requestId' LIMIT 1";
        $checkbefore = $this->db->query($sql);

        switch ($approval_status) {

            case 'Revise':

                if ($count == 0) {

                    if ($this->db->delete('form_approval', array('request_id' => $requestId, 'approval_priority >' => $approval_priority))) {
                        $transok = true;
                    }
                } else {

                    if ($this->db->delete('form_approval', array('request_id' => $requestId, 'approval_priority >' => $approval_priority))) {

                        for ($i = 0; $i < $count; $i++) {

                            $approval_priority++;

                            if ($email[$i] === 'farida@ibstower.com') {
                                $alias = 'Commitee';
                            } else {
                                $alias = $email[$i];
                            }

                            $approval = array(
                                'request_id' => $requestId,
                                'approval_priority' => $approval_priority,
                                'approval_status' => '',
                                'approval_email' => $email[$i],
                                'approval_alias' => $alias,
                                'approval_note' => '',
                                'created_at' => date('Y-m-d H:i:s'),
                                'created_by' => $this->email
                            );

                            $layer[] = $approval;
                        }

                        if ($this->db->insert_batch('form_approval', $layer)) {
                            $transok = true;
                        }
                    }
                }

                break;

            case 'Canceled':

                if ($count == 0) {

                    if ($checkbefore->num_rows() > 0 && $checkbefore->row_array()['approval_status'] == 'Approved') {

                        if ($this->db->delete('form_approval', array('request_id' => $requestId, 'approval_priority >=' => $approval_priority))) {
                            $transok = true;
                        }
                    } 

                } else {

                    if ($this->db->delete('form_approval', array('request_id' => $requestId, 'approval_priority >' => $approval_priority))) {

                        for ($i = 0; $i < $count; $i++) {

                            if ($email[$i] === 'farida@ibstower.com') {
                                $alias = 'Commitee';
                            } else {
                                $alias = $email[$i];
                            }

                            $app_status = ($i == 0) ? 'Canceled' : '';

                            $approval = array(
                                'request_id' => $requestId,
                                'approval_priority' => $approval_priority,
                                'approval_status' => $app_status,
                                'approval_email' => $email[$i],
                                'approval_alias' => $alias,
                                'approval_note' => '',
                                'created_at' => date('Y-m-d H:i:s'),
                                'created_by' => $this->email
                            );

                            $layer[] = $approval;
                            $approval_priority++;

                        }

                        if ($this->db->insert_batch('form_approval', $layer)) {
                            $transok = true;
                        }
                    }
                }

                break;
            
            default:
                break;
        }

        if ($transok) {
            return true;
        } else {
            return false;
        }
    }

	public function getTypeOfRembursement($request_id){

		$sql = "SELECT 	
					a.id as id,
					a.request_id as request_id,
					e.is_status as is_status,
					b.grandparent as tor_grandparent,
					c.parent as tor_parent,
					d.child as tor_child,
					a.jumlah_kuitansi as jumlah_kuitansi,
					a.total_nominal_kuitansi as total_kuitansi,
					a.penggantian as penggantian,
					a.keterangan as keterangan,
					a.harga_kamar as harga_kamar,
					a.additional as additional,
					a.docter as docter,
					a.diagnosa as diagnosa,
					a.tanggal_kuitansi as tanggal_kuitansi
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_type_of_reimbursment_grandparent b ON a.tor_grandparent = b.id
				LEFT JOIN hris_medical_type_of_reimbursment_parent c ON a.tor_parent = c.id
				LEFT JOIN hris_medical_type_of_reimbursment_child d ON a.tor_child = d.id
				LEFT JOIN form_request e ON a.request_id = e.id
				WHERE a.request_id = '$request_id'";

		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;

	}

	public function cekTypeOfRembursement($request_id){

		$sql = "SELECT 	
					a.id as id,
					a.tor_grandparent,
					a.tor_parent,
					a.tor_child
				FROM		
					hris_medical_reimbursment_item a
				WHERE a.request_id = '$request_id'";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return $res;

	}

	public function tambah_tor($request_id, $tor_grandparent, $tor_parent, $tor_child, $jumlah_kuitansi, $total_kuitansi, $penggantian, $request_family, $additional, $docter="", $diagnosa, $tanggal_kuitansi, $harga_kamar=""){
		$employee_id 				= $this->session->userdata('nik');
		$date = strtotime($tanggal_kuitansi);
		$receiptDate = date('Y-m-d',$date);
		$formData = array(
			'request_id' => $request_id,
			'tor_grandparent' => $tor_grandparent,
			'tor_parent' => $tor_parent,
			'tor_child' => $tor_child,
			'jumlah_kuitansi' => $jumlah_kuitansi,
			'total_nominal_kuitansi' => $total_kuitansi,
			'penggantian' => $penggantian,
			'keterangan' => $request_family,
			'additional' => $additional,
			'docter' => $docter,
			'diagnosa' => $diagnosa,
			'tanggal_kuitansi' => $receiptDate,
			'harga_kamar' => $harga_kamar,
			'employee_id' => $employee_id,
			'create_date' => date('Y-m-d H:i:s')
		);

		$this->db->insert("hris_medical_reimbursment_item", $formData);
		$query = $this->db->insert_id();
		if($query){
			return true;
		}else{
			return false;
		}
	}

	public function update_tor($request_id, $tor_grandparent, $tor_parent, $tor_child, $jumlah_kuitansi, $total_kuitansi, $penggantian, $request_family, $additional, $docter="", $diagnosa, $tanggal_kuitansi, $harga_kamar=""){
		$employee_id 				= $this->session->userdata('nik');
		$date = strtotime($tanggal_kuitansi);
		$receiptDate = date('Y-m-d',$date);
		$formData = array(
			'tor_grandparent' => $tor_grandparent,
			'tor_parent' => $tor_parent,
			'tor_child' => $tor_child,
			'jumlah_kuitansi' => $jumlah_kuitansi,
			'total_nominal_kuitansi' => $total_kuitansi,
			'penggantian' => $penggantian,
			'keterangan' => $request_family,
			'additional' => $additional,
			'docter' => $docter,
			'diagnosa' => $diagnosa,
			'tanggal_kuitansi' => $receiptDate,
			'harga_kamar' => $harga_kamar,
		);
		// =================================================================
		// $sql = "UPDATE hris_medical_reimbursment_item SET 
		// 			tor_grandparent='$tor_grandparent', tor_parent='$tor_parent', tor_child='$tor_child', 
		// 			jumlah_kuitansi='$jumlah_kuitansi', total_nominal_kuitansi = '$total_kuitansi',
		// 			penggantian = '$penggantian', keterangan = '$request_family', additional = '$additional', 
		// 			docter = '$docter', diagnosa = '$diagnosa', tanggal_kuitansi = '$receiptDate',
		// 			harga_kamar = '$harga_kamar' 
		// 			WHERE id='$request_id'";
		// $query = $this->db->query($sql);

		// if($query){
		// 	return true;
		// }else{
		// 	return false;
		// }
		// =================================================================

		$this->db->where('id', $request_id);
		if ($this->db->update('hris_medical_reimbursment_item', $formData)) {
			return true;
		} else {
			return false;
		}
	}

	public function update_price($record_id, $penggantian_old, $penggantian_revisi, $note_penggantian){
		$employee_id 				= $this->session->userdata('nik');
		// $date = strtotime($tanggal_kuitansi);
		// $receiptDate = date('Y-m-d',$date);
		$formData = array(
			'penggantian_sebelum' => $penggantian_old,
			'penggantian' => $penggantian_revisi,
			'note_penggantian' => $note_penggantian
		);
		// =================================================================
		// $sql = "UPDATE hris_medical_reimbursment_item SET 
		// 			tor_grandparent='$tor_grandparent', tor_parent='$tor_parent', tor_child='$tor_child', 
		// 			jumlah_kuitansi='$jumlah_kuitansi', total_nominal_kuitansi = '$total_kuitansi',
		// 			penggantian = '$penggantian', keterangan = '$request_family', additional = '$additional', 
		// 			docter = '$docter', diagnosa = '$diagnosa', tanggal_kuitansi = '$receiptDate',
		// 			harga_kamar = '$harga_kamar' 
		// 			WHERE id='$request_id'";
		// $query = $this->db->query($sql);

		// if($query){
		// 	return true;
		// }else{
		// 	return false;
		// }
		// =================================================================

		$this->db->where('id', $record_id);
		if ($this->db->update('hris_medical_reimbursment_item', $formData)) {
			return true;
		} else {
			return false;
		}
	}

	public function cek_record($id)
	{
		$sql = "SELECT * FROM form_request WHERE id='$id' LIMIT 1";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;	
	}
	

	public function delete_tor($id){
		$sql = "DELETE FROM hris_medical_reimbursment_item where id = '".$id."'";
		$query = $this->db->query($sql);
		if($query){
			return true;
		}else{
			return false;
		}
	}

	public function edit_tor($id)
	{
		$sql = "SELECT * FROM hris_medical_reimbursment_item WHERE id='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function get_sum_penggantian_jalan($request_id){
		// $sql = "SELECT 	
		// 			sum( CONVERT(INT,penggantian) ) as sum_penggantian
		// 		FROM		
		// 			hris_medical_reimbursment_item
		// 		WHERE request_id = '$request_id' and tor_grandparent = '1'";

		$sql = "SELECT 	
					sum(penggantian) as sum_penggantian
				FROM		
					hris_medical_reimbursment_item
				WHERE request_id = '$request_id' and tor_grandparent = '1'";
				// WHERE request_id = '$request_id' and tor_grandparent = '1' and YEAR(tanggal_kuitansi) = '$this->year'";

		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function get_sum_penggantian_inap($request_id){
		// $sql = "SELECT 	
		// 			sum( CONVERT(INT,penggantian) ) as sum_penggantian
		// 		FROM		
		// 			hris_medical_reimbursment_item
		// 		WHERE request_id = '$request_id' and tor_grandparent = '2'";

		$sql = "SELECT 	
					sum(penggantian) as sum_penggantian
				FROM		
					hris_medical_reimbursment_item
				WHERE request_id = '$request_id' and tor_grandparent = '2'";

		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function get_sum_penggantian_kacamata($request_id){
		$sql = "SELECT 	
					SUM(CASE
						WHEN tor_child = '12'
							THEN penggantian
						ELSE 0
					END)  as sum_frame,
					SUM(CASE
						WHEN tor_child = '13'
							THEN penggantian
						ELSE 0
					END)  as sum_one_focus,
					SUM(CASE
						WHEN tor_child = '14'
							THEN penggantian
						ELSE 0
					END)  as sum_two_focus
				FROM		
					hris_medical_reimbursment_item
				WHERE request_id = '$request_id' and tor_grandparent = '3'";

		$query = $this->db->query($sql);
		$res = $query->result();

		$sql2 = "SELECT * FROM hris_medical_reimbursment_item
						WHERE request_id = '$request_id' and tor_grandparent ='3'";
		$query = $this->db->query($sql2);
		$res2 = $query->result();

		return $res;
	}

	public function get_reimaning_pagu($request_created_at, $employee_id, $eg_prj, $eg_pri, $eg_pk, $request_id = null){
		//dumper($request_id);
		$year_request	=	strtotime($request_created_at);
		$year_request	=	date("Y",$year_request);
		$today = $this->today;

		$sql = "SELECT 	
					employee_group as employee_group,
					start_date as start_date,
					join_date as join_date,
					reason_of_action as reason_of_action
				FROM		
					hris_employee
				WHERE nik = '$employee_id' ORDER BY id_employee DESC LIMIT 2";		
		$query = $this->db->query($sql);
		$res 		= $query->result();
		$res = $this->my_array_unique($res);
		//$id_eg_prj 	= $res[0]->employee_group;
		$res_eg_new 		= decrypt($res[0]->employee_group);
		$res_sd_new 		= decrypt($res[0]->start_date);
		// dumper($res_sd_new);

		$reason_of_action	= decrypt($res[0]->reason_of_action);
	 	$res_jd_new    	= decrypt($res[0]->join_date);
		$res_eg_old 		= (!empty(($res[1]->employee_group))) ? decrypt(($res[1]->employee_group)) : '';

		$sql2new = "SELECT 	
					pagu_tahun as pagu_tahun
				FROM		
					hris_medical_pagu_rawat_jalan
				WHERE grade = '$res_eg_new'  AND start_date <= '$today' AND end_date > '$today'";
		$query2new 	= $this->db->query($sql2new);
		$res2new 	= $query2new->result();
		//$pagu_jalan_tahun_new 	= $res2new[0]->pagu_tahun;
		$pagu_jalan_tahun_new 	= (!empty(($res2new[0]->pagu_tahun))) ? ($res2new[0]->pagu_tahun) : 0;
		$sql3new = "SELECT 	
					pagu_kamar_hari as pagu_kamar_hari,
					pagu_tahun as pagu_tahun
				FROM		
					hris_medical_pagu_rawat_inap
				WHERE grade LIKE '$res_eg_new'  AND start_date <= '$today' AND end_date > '$today'";
		$query3new 	= $this->db->query($sql3new);
		$res3new 	= $query3new->result();
		// $pagu_inap_tahun_new 	= $res3new[0]->pagu_tahun;
		// $pagu_inap_kamar_new 	= $res3new[0]->pagu_kamar_hari;
		$pagu_inap_tahun_new 	= (!empty(($res3new[0]->pagu_tahun))) ? ($res3new[0]->pagu_tahun) : 0;
		$pagu_inap_kamar_new 	= (!empty(($res3new[0]->pagu_kamar_hari))) ? ($res3new[0]->pagu_kamar_hari) : 0;
		
		
		$sql2old = "SELECT 	
					pagu_tahun as pagu_tahun
				FROM		
					hris_medical_pagu_rawat_jalan
				WHERE grade = '$res_eg_old' AND start_date <= '$today' AND end_date > '$today'";
		$query2old 	= $this->db->query($sql2old);
		$res2old 	= $query2old->result();
		$pagu_jalan_tahun_old 	= (!empty(($res2old[0]->pagu_tahun))) ? ($res2old[0]->pagu_tahun) : 0;

		$sql3old = "SELECT 	
					pagu_kamar_hari as pagu_kamar_hari,
					pagu_tahun as pagu_tahun
				FROM		
					hris_medical_pagu_rawat_inap
				WHERE grade LIKE '$res_eg_old' AND start_date <= '$today' AND end_date > '$today'";
		$query3old 	= $this->db->query($sql3old);
		$res3old 	= $query3old->result();
		$pagu_inap_tahun_old 	= (!empty(($res3old[0]->pagu_tahun))) ? ($res3old[0]->pagu_tahun) : 0;
		$pagu_inap_kamar_old 	= (!empty(($res3old[0]->pagu_kamar_hari))) ? ($res3old[0]->pagu_kamar_hari) : 0;

    	$join_date         = DateTime::createFromFormat('Ymd', $res_jd_new);
		$start_date = DateTime::createFromFormat('Ymd', $res_sd_new);
		//$start_date = DateTime::createFromFormat('Ymd', '20220518');
		$start_date = $start_date->format('Y');
		$year 		= date("Y");


		// $test = [
		// 	'a' => $res_eg_new,
		// 	'b' => $res_sd_new,
		// 	'c' => $reason_of_action,
		// 	'd' => $res_eg_old,
		// 	'e' => $start_date
		// ];

		if( ($start_date == $year) AND (!empty($res_eg_old)) AND ($reason_of_action == 'Promotion') ){
			$yearOld = $year-1;
			$yearNew = $year+1;
			$tgl1 = $yearOld."-12-31";
			$tgl2 = DateTime::createFromFormat('Ymd', $res_sd_new);
			$tgl2 = $tgl2->format('Y-m-d');
			$tgl22 = date('Y-m-d', strtotime('-1 days', strtotime($tgl2)));
    		$tgl3 = $yearNew."-01-01";

			$diff_old                = abs(strtotime($tgl22) - strtotime($tgl1));
			$join_years_old          = floor($diff_old / (365*60*60*24));
			$join_months_old         = floor(($diff_old - $join_years_old * 365*60*60*24) / (30*60*60*24));
			// $join_days_old         	 = floor(($diff - $join_years_old * 365*60*60*24 - $join_months_old*30*60*60*24)/ (60*60*24));
			$join_days_old         	 = round($diff_old / (60 * 60 * 24));

			
			$diff_new                = abs(strtotime($tgl3) - strtotime($tgl2));
			$join_years_new          = floor($diff_new / (365*60*60*24));
			$join_months_new         = floor(($diff_new - $join_years_new * 365*60*60*24) / (30*60*60*24));
			// $join_days_new         	 = floor(($diff - $join_years_new * 365*60*60*24 - $join_months_new*30*60*60*24)/ (60*60*24));
			$join_days_new         	 = round($diff_new / (60 * 60 * 24));
			
			$pagu_pro_inap_tahun_new = ($join_days_new/365)* $pagu_inap_tahun_new;
			$pagu_inap_tahun_old = ((!empty(($pagu_inap_tahun_old))) ? ($pagu_inap_tahun_old) : 0);
			$pagu_pro_inap_tahun_old = ($join_days_old/365)* $pagu_inap_tahun_old;
			$pagu_inap_tahun = $pagu_pro_inap_tahun_new + $pagu_pro_inap_tahun_old;
			
			$pagu_pro_jalan_tahun_new = ($join_days_new/365) * $pagu_jalan_tahun_new;
			$pagu_jalan_tahun_old = ((!empty($pagu_jalan_tahun_old)) ? ($pagu_jalan_tahun_old) : 0);
			$pagu_pro_jalan_tahun_old = ($join_days_old/365) * $pagu_jalan_tahun_old;
			$pagu_jalan_tahun = $pagu_pro_jalan_tahun_new + $pagu_pro_jalan_tahun_old;

			$pagu_inap_kamar 	= $pagu_inap_kamar_new;
				// dumper('test1');
			
		}else if( ($join_date->format('Ymd') > $year.'0101') ){
			$yearNew = $year+1;
			$tgl2 = DateTime::createFromFormat('Ymd', $res_jd_new);
			$tgl2 = $tgl2->format('Y-m-d');
    		$tgl3 = $yearNew."-01-01";

			// $diff_old                = abs(strtotime($tgl2) - strtotime($tgl1));
			// $join_years_old          = floor($diff_old / (365*60*60*24));
			// $join_months_old         = floor(($diff_old - $join_years_old * 365*60*60*24) / (30*60*60*24));
			// // $join_days_old         	 = floor(($diff - $join_years_old * 365*60*60*24 - $join_months_old*30*60*60*24)/ (60*60*24));
			// $join_days_old         	 = round($diff_old / (60 * 60 * 24));

			
			$diff_new                = abs(strtotime($tgl3) - strtotime($tgl2));
			$join_years_new          = floor($diff_new / (365*60*60*24));
			$join_months_new         = floor(($diff_new - $join_years_new * 365*60*60*24) / (30*60*60*24));
			// $join_days_new         	 = floor(($diff - $join_years_new * 365*60*60*24 - $join_months_new*30*60*60*24)/ (60*60*24));
			$join_days_new         	 = round($diff_new / (60 * 60 * 24));

			
			$pagu_pro_inap_tahun_new = ($join_days_new/365)* $pagu_inap_tahun_new;
			// $pagu_inap_tahun_old = ((!empty(($pagu_inap_tahun_old))) ? ($pagu_inap_tahun_old) : 0);
			// $pagu_pro_inap_tahun_old = ($join_days_old/365)* $pagu_inap_tahun_old;
			// $pagu_inap_tahun = $pagu_pro_inap_tahun_new + $pagu_pro_inap_tahun_old;
			$pagu_inap_tahun = $pagu_pro_inap_tahun_new;
			
			$pagu_pro_jalan_tahun_new = ($join_days_new/365) * $pagu_jalan_tahun_new;
			// $pagu_jalan_tahun_old = ((!empty($pagu_jalan_tahun_old)) ? ($pagu_jalan_tahun_old) : 0);
			// $pagu_pro_jalan_tahun_old = ($join_days_old/365) * $pagu_jalan_tahun_old;
			//$pagu_jalan_tahun = $pagu_pro_jalan_tahun_new + $pagu_pro_jalan_tahun_old;
			$pagu_jalan_tahun = $pagu_pro_jalan_tahun_new;

			$pagu_inap_kamar 	= $pagu_inap_kamar_new;
				// dumper($join_date->format('Ymd'));
				// dumper($pagu_pro_jalan_tahun_new);
			
		}else{

			$pagu_jalan_tahun	= $pagu_jalan_tahun_new;
			$pagu_inap_tahun	= $pagu_inap_tahun_new;
			$pagu_inap_kamar 	= $pagu_inap_kamar_new;
				// dumper('test3');
		
		}

		// dumper($pagu_jalan_tahun);
		$sql4 = "SELECT 	
					pagu_one_focus_tahun as pagu_one_focus_tahun,
					pagu_two_focus_tahun as pagu_two_focus_tahun,
					pagu_frame_dua_tahun as pagu_frame_dua_tahun
				FROM		
					hris_medical_pagu_kacamata
				WHERE grade = '$res_eg_new' AND start_date <= '$today' AND end_date > '$today'";
		$query4 = $this->db->query($sql4);
		$res4 	= $query4->result();
		$pagu_one_focus_tahun 	= $res4[0]->pagu_one_focus_tahun;
		$pagu_two_focus_tahun 	= $res4[0]->pagu_two_focus_tahun;
		$pagu_frame_dua_tahun 	= $res4[0]->pagu_frame_dua_tahun;
		
		// $sql5 = "SELECT 	
		// 			SUM(cast(a.penggantian AS INTEGER)) as penggantian_rawat_jalan
		// 		FROM		
		// 			hris_medical_reimbursment_item a
		// 		LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
		// 		LEFT JOIN form_request c ON b.request_id = c.id
		// 		WHERE b.employee_id = '$employee_id' and b.id_eg_prj = '$eg_prj' and a.tor_grandparent = '1' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' )";

		$sql5 = "SELECT 	
					SUM(a.penggantian) as penggantian_rawat_jalan
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
				LEFT JOIN form_request c ON b.request_id = c.id
				WHERE b.employee_id = '$employee_id' and b.id_eg_prj = '$eg_prj' and a.tor_grandparent = '1' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' )";
		// dumper($sql5);
		$query5 = $this->db->query($sql5);
		$res5 	= $query5->result();
		$res5 	= $res5[0]->penggantian_rawat_jalan;

		// $sql6 = "SELECT 	
		// 			SUM(cast(a.penggantian AS INTEGER)) as penggantian_rawat_inap
		// 		FROM		
		// 			hris_medical_reimbursment_item a
		// 		LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
		// 		LEFT JOIN form_request c ON b.request_id = c.id
		// 		WHERE b.employee_id = '$employee_id' and b.id_eg_pri = '$eg_pri' and a.tor_grandparent = '2' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' )";


		$sql6 = "SELECT 	
					SUM(a.penggantian ) as penggantian_rawat_inap
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
				LEFT JOIN form_request c ON b.request_id = c.id
				WHERE b.employee_id = '$employee_id' and b.id_eg_pri = '$eg_pri' and a.tor_grandparent = '2' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' )";

		$query6 = $this->db->query($sql6);
		$res6 	= $query6->result();
		$res6 	= $res6[0]->penggantian_rawat_inap;

		//=================== old ==========================

		// $sql7 = "SELECT 	
		// 			SUM(CASE
		// 				WHEN a.tor_child = '12'
		// 					THEN a.penggantian
		// 				ELSE 0
		// 			END)  as frame,
		// 			SUM(CASE
		// 				WHEN a.tor_child = '13'
		// 					THEN a.penggantian
		// 				ELSE 0
		// 			END)  as one_focus,
		// 			SUM(CASE
		// 				WHEN a.tor_child = '14'
		// 					THEN a.penggantian
		// 				ELSE 0
		// 			END)  as two_focus
		// 			FROM		
		// 				hris_medical_reimbursment_item a
		// 			LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
		// 			LEFT JOIN form_request c ON b.request_id = c.id
		// 			WHERE b.employee_id = '$employee_id' and b.id_eg_pk = '$eg_pri' and a.tor_grandparent = '3' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' ) and (a.tor_child = '12' or a.tor_child = '13' or a.tor_child = '14	') and year(c.created_at) LIKE '%$year_request%'";
		//=================== new ==========================
		if ($request_id != null) {
			$sql7 = "SELECT 	
				SUM(CASE
					WHEN a.tor_child = '12'
						THEN a.penggantian
					ELSE 0
				END)  as frame,
				SUM(CASE
					WHEN a.tor_child = '13'
						THEN a.penggantian
					ELSE 0
				END)  as one_focus,
				SUM(CASE
					WHEN a.tor_child = '14'
						THEN a.penggantian
					ELSE 0
				END)  as two_focus
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
				LEFT JOIN form_request c ON b.request_id = c.id
				WHERE b.employee_id = '$employee_id' and b.id_eg_pk = '$eg_pri' and a.tor_grandparent = '3' and a.request_id <= '$request_id' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' ) and (a.tor_child = '12' or a.tor_child = '13' or a.tor_child = '14	') and year(a.tanggal_kuitansi) LIKE '%$this->year%'";
		} else {
			$sql7 = "SELECT 	
				SUM(CASE
					WHEN a.tor_child = '12'
						THEN a.penggantian
					ELSE 0
				END)  as frame,
				SUM(CASE
					WHEN a.tor_child = '13'
						THEN a.penggantian
					ELSE 0
				END)  as one_focus,
				SUM(CASE
					WHEN a.tor_child = '14'
						THEN a.penggantian
					ELSE 0
				END)  as two_focus
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
				LEFT JOIN form_request c ON b.request_id = c.id
				WHERE b.employee_id = '$employee_id' and b.id_eg_pk = '$eg_pri' and a.tor_grandparent = '3' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' ) and (a.tor_child = '12' or a.tor_child = '13' or a.tor_child = '14	') and year(a.tanggal_kuitansi) LIKE '%$this->year%'";
		}


		$query7 = $this->db->query($sql7);
		$res7 	= $query7->result();
		$frame 		= $res7[0]->frame;
		$one_focus 	= $res7[0]->one_focus;
		$two_focus 	= $res7[0]->two_focus;

		// $sqlhmr = "SELECT 	
		// 			TOP 2 id_eg_prj as id_eg_prj,
		// 			id_eg_pri as id_eg_pri,
		// 			id_eg_pk as id_eg_pk
		// 		FROM		
		// 			hris_medical_reimbursment
		// 		WHERE employee_id = '$employee_id' ORDER BY id DESC";

		$sqlhmr = "SELECT 	
					id_eg_prj as id_eg_prj,
					id_eg_pri as id_eg_pri,
					id_eg_pk as id_eg_pk
				FROM		
					hris_medical_reimbursment
				WHERE employee_id = '$employee_id' ORDER BY id DESC LIMIT 2";
		$queryhmr = $this->db->query($sqlhmr);
		$reshmr 		= $queryhmr->result();
		$id_eg_prj_old 		= (!empty(($reshmr[1]->id_eg_prj))) ? (($reshmr[1]->id_eg_prj)) : '';
		$id_eg_pri_old 		= (!empty(($reshmr[1]->id_eg_pri))) ? (($reshmr[1]->id_eg_pri)) : '';
		$id_eg_pk_old 		= (!empty(($reshmr[1]->id_eg_pk))) ? (($reshmr[1]->id_eg_pk)) : '';

		// $sql8 = "SELECT 	
		// 			SUM(cast(a.penggantian AS INTEGER)) as penggantian_rawat_jalan
		// 		FROM		
		// 			hris_medical_reimbursment_item a
		// 		LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
		// 		LEFT JOIN form_request c ON b.request_id = c.id
		// 		WHERE b.employee_id = '$employee_id' and a.tor_grandparent = '1' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' ) and year(c.created_at) LIKE '%$year_request%'";
		//============= old ====================
		// $sql8 = "SELECT 	
		// 			SUM(a.penggantian) as penggantian_rawat_jalan
		// 		FROM		
		// 			hris_medical_reimbursment_item a
		// 		LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
		// 		LEFT JOIN form_request c ON b.request_id = c.id
		// 		WHERE b.employee_id = '$employee_id' and a.tor_grandparent = '1' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' ) and year(c.created_at) LIKE '%$year_request%'";
		//============= new ====================
		if ($request_id != null) {
			$sql8 = "SELECT 	
					SUM(a.penggantian) as penggantian_rawat_jalan
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
				LEFT JOIN form_request c ON b.request_id = c.id
				WHERE b.employee_id = '$employee_id' and a.tor_grandparent = '1' and a.request_id <= '$request_id' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' ) and year(a.tanggal_kuitansi) LIKE '%$this->year%'";
		} else {
			$sql8 = "SELECT 	
					SUM(a.penggantian) as penggantian_rawat_jalan
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
				LEFT JOIN form_request c ON b.request_id = c.id
				WHERE b.employee_id = '$employee_id' and a.tor_grandparent = '1' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' ) and year(a.tanggal_kuitansi) LIKE '%$this->year%'";
		}
		
		
		$query8 = $this->db->query($sql8);
		$res8 	= $query8->result();
		$res8 	= (!empty(($res8[0]->penggantian_rawat_jalan))) ? (($res8[0]->penggantian_rawat_jalan)) : 0;


		// $sql9 = "SELECT 	
		// 			SUM(cast(a.penggantian AS INTEGER)) as penggantian_rawat_inap
		// 		FROM		
		// 			hris_medical_reimbursment_item a
		// 		LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
		// 		LEFT JOIN form_request c ON b.request_id = c.id
		// 		WHERE b.employee_id = '$employee_id' and a.tor_grandparent = '2' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' ) and year(c.created_at) LIKE '%$year_request%'";
		//=================== old ==========================
		// $sql9 = "SELECT 	
		// 			SUM(a.penggantian) as penggantian_rawat_inap
		// 		FROM		
		// 			hris_medical_reimbursment_item a
		// 		LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
		// 		LEFT JOIN form_request c ON b.request_id = c.id
		// 		WHERE b.employee_id = '$employee_id' and a.tor_grandparent = '2' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' ) and year(c.created_at) LIKE '%$year_request%'";
		//=================== new ==========================
		if ($request_id != null) {
			$sql9 = "SELECT 	
					SUM(a.penggantian) as penggantian_rawat_inap
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
				LEFT JOIN form_request c ON b.request_id = c.id
				WHERE b.employee_id = '$employee_id' and a.tor_grandparent = '2' and a.request_id <= '$request_id' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' ) and year(a.tanggal_kuitansi) LIKE '%$this->year%'";
		} else {
			$sql9 = "SELECT 	
					SUM(a.penggantian) as penggantian_rawat_inap
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
				LEFT JOIN form_request c ON b.request_id = c.id
				WHERE b.employee_id = '$employee_id' and a.tor_grandparent = '2' and ( c.is_status not like '0' and c.is_status not like '2' and c.is_status not like '4' ) and year(a.tanggal_kuitansi) LIKE '%$this->year%'";

		}		

		$query9 = $this->db->query($sql9);
		$res9 	= $query9->result();
		$res9 	= (!empty(($res9[0]->penggantian_rawat_inap))) ? (($res9[0]->penggantian_rawat_inap)) : 0;

		if($id_eg_prj_old == $eg_prj){
			$penggunaan_jalan = $res8;
		}else{
			$penggunaan_jalan = $res8;
		}

		if($id_eg_pri_old == $eg_pri){
			$penggunaan_inap = $res9;
		}else{
			$penggunaan_inap = ($res9);
		}
		
		// dumper($pagu_jalan_tahun);
		// dumper($penggunaan_jalan);
		$data = array(
			'pagu_jalan_tahun' => ( $pagu_jalan_tahun - $penggunaan_jalan),
			'pagu_inap_tahun' => ( $pagu_inap_tahun - $penggunaan_inap),
			'pagu_inap_kamar' => $pagu_inap_kamar,
			'pagu_one_focus_tahun' => ($pagu_one_focus_tahun - $one_focus),
			'pagu_two_focus_tahun' => ($pagu_two_focus_tahun - $two_focus),
			'pagu_frame_dua_tahun' => ($pagu_frame_dua_tahun - $frame)
		);

		return $data;
	}
	//
		public function my_array_unique($array, $keep_key_assoc = false){
			$duplicate_keys = array();
			$tmp = array();       
		
			foreach ($array as $key => $val){
				if (is_object($val))
					$val = (array)$val;
		
				if (!in_array($val, $tmp))
					$tmp[] = $val;
				else
					$duplicate_keys[] = $key;
			}
		
			foreach ($duplicate_keys as $key)
				unset($array[$key]);
		
			return $keep_key_assoc ? $array : array_values($array);
		}


		public function request_submited_mdcr($id){
			$sql = "UPDATE form_request SET is_status='1' WHERE id = '".$id."'";
			$query = $this->db->query($sql);

			// $nik 				= $this->session->userdata('employee_id');
			// $sql 				= "SELECT TOP 1 id_employee FROM hris_employee WHERE nik = '$nik' ORDER BY id_employee DESC";
			// $query 				= $this->db->query($sql);
			// $res 				= $query->result();
			// $id_employee			= encrypt($res[0]->id_employee);
			$date = $this->date;
			$sql2 = "UPDATE hris_medical_reimbursment SET is_status='1', created_at = '$date' WHERE request_id = '".$id."'";
			$this->db->query($sql2);

			$sql3 = "UPDATE request_notes SET is_status= null WHERE request_id = '".$id."'";
			$this->db->query($sql3);
			
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function getFamilyChild($tanggal_kuitansi){
			$employee_id 	= $this->session->userdata('nik');
			$f_members		= encrypt('Child');
			$sql 			= "SELECT * FROM hris_family_employee WHERE nik='$employee_id' AND family_members = '$f_members' AND status_act = 'Y' ORDER BY id_family ASC LIMIT 3";
			$query  		= $this->db->query($sql);
			$output 		= '<option value="">Pilih Anak</option>';
			foreach($query->result() as $row)
			{
				$ulang_tahun 	=	decrypt($row->member_birthdate);
				$umur 			= $this->hitung_umur_dari_kuitansi($ulang_tahun, $tanggal_kuitansi);
				if($umur <= 21){
				$member_names 	= str_replace("||","'", decrypt($row->member_names));
				$output .= '<option value="'.$row->member_names.'">'.$member_names.' - Th '.$umur.'</option>';
				}
			}
			return $output;
		}

		public function getDetailFamilyChild($id, $tanggal_kuitansi, $employee_id){
			$f_members		= encrypt('Child');
			$sql 			= "SELECT * FROM hris_family_employee WHERE nik='$employee_id' AND family_members = '$f_members' AND status_act = 'Y' ORDER BY id_family ASC LIMIT 3";
			$query  		= $this->db->query($sql);
			$output 		= '';
			foreach($query->result() as $row)
			{
				$ulang_tahun 	=	decrypt($row->member_birthdate);
				$umur 			= $this->hitung_umur_dari_kuitansi($ulang_tahun, $tanggal_kuitansi);
				if($umur <= 21){
					$member_names 	= str_replace("||","'", decrypt($row->member_names));
					if ($row->member_names == $id){
						$output .= '<option value="'.$row->member_names.'" selected>'.$member_names.' - Th '.$umur.'</option>';
					} else {
						$output .= '<option value="'.$row->member_names.'">'.$member_names.' - Th '.$umur.'</option>';
					}
				}
			}

			return $output;
		}
		
		public function getFamilySpouse($tanggal_kuitansi){
			$employee_id 	= $this->session->userdata('employee_id');
			$f_members		= encrypt('Spouse');
			$sql 			= "SELECT * FROM hris_family_employee WHERE nik='$employee_id' AND family_members = '$f_members' ORDER BY id_family ASC LIMIT 1";
			$query  		= $this->db->query($sql);
			$output 		= '<option value="">Pilih Pasangan</option>';
			foreach($query->result() as $row)
			{
				$member_names 	= str_replace("||","'", decrypt($row->member_names));
				$output .= '<option value="'.$row->member_names.'">'.$member_names.'</option>';
			}

			return $output;
		}

		public function getDetailFamilySpouse($id, $employee_id){
			$f_members		= encrypt('Spouse');
			$sql 			= "SELECT * FROM hris_family_employee WHERE nik='$employee_id' AND family_members = '$f_members' ORDER BY id_family ASC LIMIT 1";
			$query  		= $this->db->query($sql);
			$output = '';
			foreach($query->result() as $row)
			{
				$member_names 	= str_replace("||","'", decrypt($row->member_names));
				if ($row->member_names == $id){
					$output .= '<option value="'.$row->member_names.'" selected>' .$member_names. '</option>';
				}else{
					$output .= '<option value="'.$row->member_names.'">'.$member_names.'</option>';
				}
			}
			return $output;
		}

		public function save_additional_mdcr($data){
			
			try{
			  $this->db->insert('hris_medical_reimbursment_additional', $data);
			  return true;
			}catch(Exception $e){
			}
		}

		public function update_additional_mdcr($data){
			$this->db->where('request_id', $data['request_id']);
			if ($this->db->update('hris_medical_reimbursment_additional', $data)) {
				return true;
			} else {
				return false;
			}
		}

		public function cek_additional_table($id_request){
			$sql 		= "SELECT * FROM hris_medical_reimbursment_additional WHERE request_id ='$id_request'";
			$query 		= $this->db->query($sql);
			$res 		= $query->result();

			return $res;
		}

		public function cek_limit_harga_kamar(){
			$today				= $this->today;
			$employee_id 				= $this->session->userdata('employee_id');
			$sql 				= "SELECT employee_group FROM hris_employee WHERE nik = $employee_id ORDER BY id_employee DESC LIMIT 1";
			$query 				= $this->db->query($sql);
			$res 				= $query->result();
			$employee_group 	= $res[0]->employee_group;
			$eg 				= decrypt($employee_group);

			$sql2 				= "SELECT pagu_kamar_hari as pagu_kamar_hari FROM hris_medical_pagu_rawat_inap WHERE grade LIKE '$eg' AND start_date <= '$today' AND end_date > '$today'";
			$query2 			= $this->db->query($sql2);
			$res2 				= $query2->result();
			$pagu_inap_kamar 	= $res2[0]->pagu_kamar_hari;

			return $pagu_inap_kamar;
		}

		public function cek_limit_maternity($status){
			$today				= $this->today;
			$employee_id 				= $this->session->userdata('employee_id');
			$sql 				= "SELECT employee_group FROM hris_employee WHERE nik = $employee_id ORDER BY id_employee DESC LIMIT 1";
			$query 				= $this->db->query($sql);
			$res 				= $query->result();
			$employee_group 	= $res[0]->employee_group;
			$eg 				= decrypt($employee_group);

			$sql2 				= "SELECT pagu_tahun as pagu_tahun_maternity FROM hris_medical_pagu_maternity WHERE grade LIKE '$eg' AND melahirkan LIKE '$status' AND start_date <= '$today' AND end_date > '$today'";
			$query2 			= $this->db->query($sql2);
			$res2 				= $query2->result();
			$pagu_tahun_maternity 	= $res2[0]->pagu_tahun_maternity;

			return $pagu_tahun_maternity;
		}

		public function cek_tanggal_pengambilan_kacamata($request_id, $request_grandparent, $request_parent, $request_child){

			$employee_id 				= $this->session->userdata('employee_id');
			$sql 				= "SELECT a.tanggal_kuitansi as tanggal_req FROM hris_medical_reimbursment_item a LEFT JOIN form_request b ON a.request_id = b.id	WHERE a.employee_id LIKE '$employee_id' AND a.tor_grandparent LIKE '$request_grandparent' AND a.tor_parent LIKE '$request_parent' AND b.is_status NOT LIKE '4' ORDER BY a.id DESC LIMIT 1";
			$query 				= $this->db->query($sql);
			$res 				= $query->result();
			
			if($res){
				$tanggal_req 		= $res[0]->tanggal_req;
				return $tanggal_req;
			}else{
				$tanggal_req = 'Kosong';
				return $tanggal_req;
			}

			// if(!empty($tanggal_req)){
			// 	return $tanggal_req;	
			// }else{
			// 	$tanggal_req = 'Kosong';
			// 	return $tanggal_req;
			// }
			
			//return $tanggal_req;
		}

		function hitung_umur_dari_kuitansi($tanggal_lahir, $tanggal_kuitansi){
			$birthDate = new DateTime($tanggal_lahir);
			$tanggal_kuitansi = new DateTime($tanggal_kuitansi);
			if ($birthDate > $tanggal_kuitansi) { 
				exit("0");
			}
			$y = $tanggal_kuitansi->diff($birthDate)->y;
			return $y;
		}

		public function get_Grandparent(){
			$sql = "SELECT * FROM hris_medical_type_of_reimbursment_grandparent";
			$query  = $this->db->query($sql);
			$output = '<option value="">Jenis Penggantian</option>';
			foreach($query->result() as $row)
			{
				$output .= '<option value="'.$row->id.'">'.$row->grandparent.'</option>';
			}
			return $output;
		}

		public function edit_get_Grandparent($id){
			$sql = "SELECT * FROM hris_medical_type_of_reimbursment_grandparent";
			$query  = $this->db->query($sql);

			$output = '';
			foreach($query->result() as $row)
			{
				if ($row->id == $id){
					$output .= '<option value="'.$row->id.'" selected>' .$row->grandparent. '</option>';
				}else{
					$output .= '<option value="'.$row->id.'">'.$row->grandparent.'</option>';
				}
				
			}
			return $output;
		}

		public function get_Parent($id_grandparent, $emp_group = null){
			if ($emp_group == 'GOL A' || $emp_group == 'GOL B' || $emp_group == 'GOL C' || $emp_group == 'GOL D' || $emp_group == 'GOL E') {
				$sql = "SELECT * FROM hris_medical_type_of_reimbursment_parent WHERE grandparent='$id_grandparent' and is_active = 1";
			} else {
				$sql = "SELECT * FROM hris_medical_type_of_reimbursment_parent WHERE grandparent='$id_grandparent' and is_active = 1 and parent != 'Medical Check Up'";
			} 
			$query  = $this->db->query($sql);
			$output = '<option value="">Sub Penggantian</option>';
			foreach($query->result() as $row)
			{
			$output .= '<option value="'.$row->id.'">'.$row->parent.'</option>';
			}
			return $output;
		}

		public function edit_get_Parent($record, $emp_group = null){
			$id = $record[0]->tor_parent;
			$id_grandparent = $record[0]->tor_grandparent;
			if ($emp_group == 'GOL A' || $emp_group == 'GOL B' || $emp_group == 'GOL C' || $emp_group == 'GOL D' || $emp_group == 'GOL E') {
				$sql = "SELECT * FROM hris_medical_type_of_reimbursment_parent where grandparent = '$id_grandparent' and is_active = 1";
			} else {
				$sql = "SELECT * FROM hris_medical_type_of_reimbursment_parent WHERE grandparent='$id_grandparent' and is_active = 1 and parent != 'Medical Check Up'";
			}
			$query  = $this->db->query($sql);

			$output = '';
			foreach($query->result() as $row)
			{
				if ($row->id == $id){
					$output .= '<option value="'.$row->id.'" selected>' .$row->parent. '</option>';
				}else{
					$output .= '<option value="'.$row->id.'">'.$row->parent.'</option>';
				}
				
			}
			return $output;
		}

		public function get_Child($id_parent){
			$sql = "SELECT * FROM hris_medical_type_of_reimbursment_child WHERE parent='$id_parent' and is_active = 1";
			$query  = $this->db->query($sql);
			$output = '<option value="">Penggantian</option>';
			foreach($query->result() as $row)
			{
			$output .= '<option value="'.$row->id.'">'.$row->child.'</option>';
			}
			return $output;
		}

		public function edit_get_Child($record){
			$id = $record[0]->tor_child;
			$id_parent = $record[0]->tor_parent;
			$sql = "SELECT * FROM hris_medical_type_of_reimbursment_child WHERE parent = '$id_parent' and is_active = 1 ";
			$query  = $this->db->query($sql);

			$output = '';
			foreach($query->result() as $row)
			{
				if ($row->id == $id){
					$output .= '<option value="'.$row->id.'" selected>' .$row->child. '</option>';
				}else{
					$output .= '<option value="'.$row->id.'">'.$row->child.'</option>';
				}
				
			}
			return $output;
		}

		public function get_header_mdcr($request_id){

			$sql = "SELECT 	
						a.id as id_fr,
						b.id as id_hmr,
						a.request_number as request_number,
						a.form_type as form_type,
						a.is_status as is_status,
						a.created_by as created_by,
						a.created_at as created_at,
	          a.updated_by as updated_by,
	          a.updated_at as updated_at,
						a.employee_id as employee_id,
						c.position as position,
						b.complete_name as complete_name,
						b.request_id as request_id,
						b.employee_group as employee_group,
						b.phone_number as phone_number,
						b.department as department,
						b.personnel_area as personnel_area,
						b.id_eg_prj as id_eg_prj,
						b.id_eg_pri as id_eg_pri,
						b.id_eg_pk as id_eg_pk,
						b.marital_status as marital_status,
						b.gender as gender,
						a.is_status_admin_hr as is_status_admin_hr,
						a.revise as revise
					FROM		
						form_request a
					LEFT JOIN hris_medical_reimbursment b ON b.request_id = a.id
					LEFT JOIN hris_employee c ON c.nik = a.employee_id
					WHERE a.id = '$request_id'";

			$query = $this->db->query($sql);
			$res = $query->result();
			return $res;

			
		}

		public function get_data_couple_employee($employee_id=""){
			$sql 				= "SELECT * FROM hris_couple_employee WHERE male_nik LIKE '$employee_id' OR female_nik LIKE '$employee_id' ORDER BY id DESC LIMIT 1";
			$query 				= $this->db->query($sql);
			$res 				= $query->result();
			
			$male_nik 			= (!empty(($res[0]->male_nik))) ? $res[0]->male_nik : '';
			$male_employee		= (!empty(($res[0]->male_employee))) ? decrypt($res[0]->male_employee) : '';
			$female_nik			= (!empty(($res[0]->female_nik))) ? $res[0]->female_nik : '';
			$female_employee		= (!empty(($res[0]->female_employee))) ? decrypt($res[0]->female_employee) : '';

			$sql_male			= "SELECT * FROM hris_employee WHERE nik = '$male_nik' ORDER BY id_employee DESC LIMIT 1";
			$query_male			= $this->db->query($sql_male);
			$res_male			= $query_male->result();
			$male_employee_group = (!empty(($res_male[0]->employee_group))) ? decrypt($res_male[0]->employee_group) : '';
			
			$sql_female			= "SELECT * FROM hris_employee WHERE nik = '$female_nik' ORDER BY id_employee DESC LIMIT 1";
			$query_female		= $this->db->query($sql_female);
			$res_female			= $query_female->result();
			$female_employee_group = (!empty(($res_female[0]->employee_group))) ? decrypt($res_female[0]->employee_group) : '';

			$sql_male_gol		= "SELECT * FROM hris_employee_group WHERE employee_group = '$male_employee_group'";
			$query_male_gol		= $this->db->query($sql_male_gol);
			$res_male_gol		= $query_male_gol->result();
			$res_male_gol		= (!empty(($res_male_gol[0]->id))) ? $res_male_gol[0]->id : '';
			
			$sql_female_gol		= "SELECT * FROM hris_employee_group WHERE employee_group = '$female_employee_group'";
			$query_female_gol	= $this->db->query($sql_female_gol);
			$res_female_gol		= $query_female_gol->result();
			$res_female_gol		= (!empty(($res_female_gol[0]->id))) ? $res_female_gol[0]->id : '';
			
			if($res_male_gol > $res_female_gol){
				$hak_pengajuan = $female_nik;
			}else if($res_female_gol > $res_male_gol){
				$hak_pengajuan = $male_nik;
			}else if($res_male_gol = $res_female_gol){
				$hak_pengajuan = $male_nik;
			}else{
				$hak_pengajuan = $employee_id;
			}

			$DataCouple = array(
				'male_nik'  			=> $male_nik,
				'male_employee'			=> $male_employee,
				'male_gol'				=> $male_employee_group,
				'female_nik'  			=> $female_nik,
				'female_employee'		=> $female_employee,
				'female_gol'			=> $female_employee_group,
				'hak_pengajuan'			=> $hak_pengajuan);
			
			if($DataCouple){
				return $DataCouple;
			}

		}

		public function getFamilyActSpouse($employee_id){
			$spouse			= encrypt('Spouse');
			$sql 			= "SELECT * FROM hris_family_employee WHERE nik='$employee_id' and family_members = '$spouse' ORDER BY id_family DESC LIMIT 1";
			$query  		= $this->db->query($sql);
			$res 			= $query->result();
			$member_names	= (!empty(($res[0]->member_names))) ? $res[0]->member_names : '';
			return $member_names;
		}
		
		// public function get_detail_mdcr($request_id){

		// 	$sql = "SELECT 	
		// 				*
		// 			FROM		
		// 				hris_medical_reimbursment_item
		// 			WHERE request_id = '$request_id'
		// 			LEFT JOIN ";

		// 	$query = $this->db->query($sql);
		// 	$res = $query->result();
		// 	return $res;

			
		// }

		public function del_draft_request_mdcr($id_request){
			$sql_hmr = "DELETE FROM hris_medical_reimbursment where request_id = '".$id_request."'";
			$this->db->query($sql_hmr);

			$sql = "DELETE FROM form_request where id = '".$id_request."'";
			$query = $this->db->query($sql);
			if($query){
				return true;
			}else{
				return false;
			}
		}

		public function cek_efektifitas_kuitansi($tanggal_kuitansi){
			$date = strtotime($tanggal_kuitansi);
			$dateformat = date('Y-m-d',$date);
			$sql = "SELECT 	
						efektif_kuitansi
					FROM		
						hris_efektifitas_kuitansi
					WHERE start_date <= '$dateformat' and end_date >= '$dateformat'
					ORDER BY id DESC";

			$query = $this->db->query($sql);
			$res = $query->result();
			if($res){
				$res = $res[0]->efektif_kuitansi;
				return $res;
			}else{
				return false;
			}
		}


		public function responseRequestFromAdminHR($request_id, $response){
			$sql = "UPDATE form_request SET is_status_admin_hr='1' WHERE id = '".$request_id."'";
			$query = $this->db->query($sql);
			if($query){
			
				$sql = "select id from form_request where id='$request_id' and is_status_divhead_hr='1'";
				$query = $this->db->query($sql);
				$res = $query->result();

				$request_id_form 	= (!empty(($res[0]->id))) ? ($res[0]->id) : 0;
				if($request_id_form != 0){

					$update_layer = array(
						'approval_status' => 'Approved', 
						'updated_at' => $this->date, 
						'updated_by' => $this->email
					);

					$this->db->where('request_id', $request_id);
					if ($this->db->update('form_approval', $update_layer)) {
						$this->db->where('request_id', $request_id);
						$this->db->update('hris_medical_reimbursment', array('is_status' => 3));

						$data_approved_layer = array(
							'is_status' => 3
						);
						$this->db->where('id', $request_id);
						$this->db->update('form_request', $data_approved_layer);
						
					}
				}


				return true;
			}else{
				return false;
			}
		}

		public function get_approval_mdcr($requestId){

			$sql = "SELECT 
						b.id_employee as employee_id,
						b.complete_name as complete_name,
						b.position as position,
						a.updated_at as approval_date
					FROM form_approval a
					LEFT JOIN hris_employee b ON a.approval_employee_id = b.nik
					WHERE a.request_id = '$requestId' and b.is_active = 1";
	        $query= $this->db->query($sql);
			$res = $query->result();
			if($res){
				return $res;
			}else{
				return false;
			}
		}
		
		public function get_data_claim($no_req_mdcr){

			$sql = "SELECT
						*
					FROM hris_no_req_mdcr
					WHERE no_req_mdcr = '$no_req_mdcr'";
	        $query= $this->db->query($sql);
			// dumper($sql);
			$res = $query->result();
			$no_req_mdcr = (!empty(($res[0]->no_req_mdcr))) ? ($res[0]->no_req_mdcr) : '';
			// $sqlForm = "SELECT 
			// 			*
			// 		FROM form_request
			// 		WHERE no_req_mdcr = '$no_req_mdcr' AND is_status_admin_hr = 1 AND is_status_divhead_hr = 1 ORDER BY created_at ASC";
			//===================== new =========================
			// $sqlForm = "SELECT 
			// 			*
			// 		FROM form_request
			// 		WHERE no_req_mdcr = '$no_req_mdcr' AND is_status_admin_hr = 1 ORDER BY created_at ASC";


			$sqlForm = "SELECT 
						*
				FROM form_request 
				WHERE no_req_mdcr = '$no_req_mdcr' AND is_status_admin_hr = 1 ORDER BY employee_id ASC";
       
      $queryForm= $this->db->query($sqlForm);
			
			$res_form = $queryForm->result();	
			// dumper($sqlForm);

			if (!empty($res_form)) {

				foreach ($res_form as $key) {
					$row   = array();

					$sql 		= "SELECT * FROM hris_medical_reimbursment WHERE request_id ='$key->id'";
					$query 		= $this->db->query($sql);
					$res 		= $query->result();
					$id_hr_emp	= (!empty(($res[0]->id_hr_emp))) ? (decrypt($res[0]->id_hr_emp)) : '';
					
					$sql 		= "SELECT * FROM hris_employee WHERE id_employee ='$id_hr_emp'";
					$query 		= $this->db->query($sql);
					$res 		= $query->result();
					$complete_name 		= (!empty(($res[0]->complete_name))) ? (decrypt($res[0]->complete_name)) : '';
					$cost_center 		= (!empty(($res[0]->cost_center))) ? (decrypt($res[0]->cost_center)) : '';
					$bankn 				= (!empty(($res[0]->bankn))) ? (decrypt($res[0]->bankn)) : '';

					// $sql7 = "SELECT 	
					// 	SUM(cast(penggantian AS INTEGER)) as total_pembayaran_kacamata,
					// 	SUM(cast(total_nominal_kuitansi AS INTEGER)) as total_claim_kacamata
					// FROM		
					// 	hris_medical_reimbursment_item 
					// WHERE tor_grandparent = '3' AND request_id = '$key->id'";

					$sql7 = "SELECT 	
						SUM(penggantian) as total_pembayaran_kacamata,
						SUM(total_nominal_kuitansi) as total_claim_kacamata
					FROM		
						hris_medical_reimbursment_item 
					WHERE tor_grandparent = '3' AND request_id = '$key->id'";

					$query7 = $this->db->query($sql7);
					$res7 	= $query7->result();
					$res7PembayaranKacamata 	= (!empty(($res7[0]->total_pembayaran_kacamata))) ? (($res7[0]->total_pembayaran_kacamata)) : 0;
					$res7ClaimKacamata 		= (!empty(($res7[0]->total_claim_kacamata))) ? (($res7[0]->total_claim_kacamata)) : 0;
					
					// $sql8 = "SELECT 	
					// 	SUM(cast(penggantian AS INTEGER)) as total_pembayaran_rawat_jalan,
					// 	SUM(cast(total_nominal_kuitansi AS INTEGER)) as total_claim_rawat_jalan
					// FROM		
					// 	hris_medical_reimbursment_item 
					// WHERE tor_grandparent = '1' AND request_id = '$key->id'";

					$sql8 = "SELECT 	
						SUM(penggantian) as total_pembayaran_rawat_jalan,
						SUM(total_nominal_kuitansi) as total_claim_rawat_jalan
					FROM		
						hris_medical_reimbursment_item 
					WHERE tor_grandparent = '1' AND request_id = '$key->id'";

					$query8 = $this->db->query($sql8);
					$res8 	= $query8->result();
					$res8PembayaranJalan 	= (!empty(($res8[0]->total_pembayaran_rawat_jalan))) ? (($res8[0]->total_pembayaran_rawat_jalan)) : 0;
					$res8ClaimJalan 		= (!empty(($res8[0]->total_claim_rawat_jalan))) ? (($res8[0]->total_claim_rawat_jalan)) : 0;
					
					// $sql9 = "SELECT 	
					// 	SUM(cast(penggantian AS INTEGER)) as total_pembayaran_rawat_inap,
					// 	SUM(cast(total_nominal_kuitansi AS INTEGER)) as total_claim_rawat_inap
					// FROM		
					// 	hris_medical_reimbursment_item 
					// WHERE tor_grandparent = '2' AND request_id = '$key->id'";

					$sql9 = "SELECT 	
						SUM(penggantian) as total_pembayaran_rawat_inap,
						SUM(total_nominal_kuitansi) as total_claim_rawat_inap
					FROM		
						hris_medical_reimbursment_item 
					WHERE tor_grandparent = '2' AND request_id = '$key->id'";

					$query9 = $this->db->query($sql9);
					$res9 	= $query9->result();
					$res9PembayaranInap 	= (!empty(($res9[0]->total_pembayaran_rawat_inap))) ? (($res9[0]->total_pembayaran_rawat_inap)) : 0;
					$res9ClaimInap 		= (!empty(($res9[0]->total_claim_rawat_inap))) ? (($res9[0]->total_claim_rawat_inap)) : 0;

					$grand_total = $res8PembayaranJalan + $res9PembayaranInap + $res7PembayaranKacamata;

					$row['request_id'] = $key->id;
					$row['employee_id'] = $key->employee_id;
					$row['complete_name'] = $complete_name;
					$row['cost_center'] = $cost_center;
					$row['bankn'] = $bankn;
					$row['request_number'] = $key->request_number;
					$row['no_mdcr'] = $key->no_req_mdcr;
					$row['claim_jalan'] = $res8ClaimJalan;
					$row['pembayaran_jalan'] = $res8PembayaranJalan;
					$row['claim_inap'] = $res9ClaimInap;
					$row['pembayaran_inap'] = $res9PembayaranInap;
					$row['claim_kacamata'] = $res7ClaimKacamata;
					$row['pembayaran_kacamata'] = $res7PembayaranKacamata;
					$row['total'] = $grand_total;
					$data[] = (object)$row;
					
				}
				$outputF = $data;
				
			} else {
				$outputF = new ArrayObject();
			}
			
			if($outputF){
				return $outputF;
			}else{
				return false;
			}
		}
		
		public function get_total_data_claim($no_req_mdcr){

			$sql = "SELECT 
						*
					FROM hris_no_req_mdcr
					WHERE no_req_mdcr = '$no_req_mdcr'";
	        $query= $this->db->query($sql);
			$res = $query->result();
			$no_req_mdcr = (!empty(($res[0]->no_req_mdcr))) ? ($res[0]->no_req_mdcr) : '';

			$sqlForm = "SELECT 
						*
					FROM form_request
					WHERE no_req_mdcr = '$no_req_mdcr' AND is_status_admin_hr LIKE '1'";
	        $queryForm= $this->db->query($sqlForm);
			$res_form = $queryForm->result();

			if (!empty($res_form)) {

				foreach ($res_form as $key) {
					$row   = array();

					$sql 		= "SELECT * FROM hris_medical_reimbursment WHERE request_id ='$key->id'";
					$query 		= $this->db->query($sql);
					$res 		= $query->result();
					$id_hr_emp	= (!empty(($res[0]->id_hr_emp))) ? (decrypt($res[0]->id_hr_emp)) : '';
					
					$sql 		= "SELECT * FROM hris_employee WHERE id_employee ='$id_hr_emp'";
					$query 		= $this->db->query($sql);
					$res 		= $query->result();
					$complete_name 		= (!empty(($res[0]->complete_name))) ? (decrypt($res[0]->complete_name)) : '';
					$cost_center 		= (!empty(($res[0]->cost_center))) ? (decrypt($res[0]->cost_center)) : '';


					// $sql7 = "SELECT 	
					// 	SUM(cast(penggantian AS INTEGER)) as total_pembayaran_kacamata,
					// 	SUM(cast(total_nominal_kuitansi AS INTEGER)) as total_claim_kacamata
					// FROM		
					// 	hris_medical_reimbursment_item 
					// WHERE tor_grandparent = '3' AND request_id = '$key->id'";

					$sql7 = "SELECT 	
						SUM(penggantian) as total_pembayaran_kacamata,
						SUM(total_nominal_kuitansi) as total_claim_kacamata
					FROM		
						hris_medical_reimbursment_item 
					WHERE tor_grandparent = '3' AND request_id = '$key->id'";

					$query7 = $this->db->query($sql7);
					$res7 	= $query7->result();
					$res7PembayaranKacamata 	= (!empty(($res7[0]->total_pembayaran_kacamata))) ? (($res7[0]->total_pembayaran_kacamata)) : 0;
					$res7ClaimKacamata 		= (!empty(($res7[0]->total_claim_kacamata))) ? (($res7[0]->total_claim_kacamata)) : 0;
					
					// $sql8 = "SELECT 	
					// 	SUM(cast(penggantian AS INTEGER)) as total_pembayaran_rawat_jalan,
					// 	SUM(cast(total_nominal_kuitansi AS INTEGER)) as total_claim_rawat_jalan
					// FROM		
					// 	hris_medical_reimbursment_item 
					// WHERE tor_grandparent = '1' AND request_id = '$key->id'";

					$sql8 = "SELECT 	
						SUM(penggantian) as total_pembayaran_rawat_jalan,
						SUM(total_nominal_kuitansi) as total_claim_rawat_jalan
					FROM		
						hris_medical_reimbursment_item 
					WHERE tor_grandparent = '1' AND request_id = '$key->id'";

					$query8 = $this->db->query($sql8);
					$res8 	= $query8->result();
					$res8PembayaranJalan 	= (!empty(($res8[0]->total_pembayaran_rawat_jalan))) ? (($res8[0]->total_pembayaran_rawat_jalan)) : 0;
					$res8ClaimJalan 		= (!empty(($res8[0]->total_claim_rawat_jalan))) ? (($res8[0]->total_claim_rawat_jalan)) : 0;
					
					// $sql9 = "SELECT 	
					// 	SUM(cast(penggantian AS INTEGER)) as total_pembayaran_rawat_inap,
					// 	SUM(cast(total_nominal_kuitansi AS INTEGER)) as total_claim_rawat_inap
					// FROM		
					// 	hris_medical_reimbursment_item 
					// WHERE tor_grandparent = '2' AND request_id = '$key->id'";

					$sql9 = "SELECT 	
						SUM(penggantian) as total_pembayaran_rawat_inap,
						SUM(total_nominal_kuitansi) as total_claim_rawat_inap
					FROM		
						hris_medical_reimbursment_item 
					WHERE tor_grandparent = '2' AND request_id = '$key->id'";

					$query9 = $this->db->query($sql9);
					$res9 	= $query9->result();
					$res9PembayaranInap 	= (!empty(($res9[0]->total_pembayaran_rawat_inap))) ? (($res9[0]->total_pembayaran_rawat_inap)) : 0;
					$res9ClaimInap 		= (!empty(($res9[0]->total_claim_rawat_inap))) ? (($res9[0]->total_claim_rawat_inap)) : 0;

					$grand_total = $res8PembayaranJalan + $res9PembayaranInap + $res7PembayaranKacamata;
					$row['total'] = $grand_total;
					$data[] = (object)$row;
					
				}
				$total = 0;
				$count = count($data);
				
				for ($i = 0; $i < $count; $i++) {
					$total = $total + $data[$i]->total;
				}
				$outputF = $total;
				
			} else {
				$total = 0;
				$outputF = $total;
			}

			if($outputF){
				return $outputF;
			}else{
				return false;
			}
		}

		public function get_date_no_req_mdcr($no_req_mdcr){
			// $sql = "SELECT 
			// 			*
			// 		FROM hris_no_req_mdcr
			// 		WHERE no_req_mdcr = '$no_req_mdcr'";
			//================== old =====================
			// $sql = "SELECT DATE(created_at) dateonly FROM form_request WHERE no_req_mdcr = '$no_req_mdcr' AND is_status_admin_hr = 1 AND is_status_divhead_hr = 1
			// GROUP BY dateonly";
			//================== new =====================
			$sql = "SELECT DATE(created_at) dateonly FROM form_request WHERE no_req_mdcr = '$no_req_mdcr' AND is_status_admin_hr = 1 
			GROUP BY dateonly";
	    	$query= $this->db->query($sql);
			$res = $query->result();
			// if (count($res) > 1) {
			// 	$dt1 = $res[array_key_first($res)];
			// 	$dt2 = $res[array_key_last($res)];
			// 	$tgl = ''.$dt1.'...'.$dt2.'';
			// } else if (count($res) == 1) {
			// 	$tgl = $res[0];
			// } else {
			// 	$tgl = '';
			// }

			if (count($res) >= 1) {
				$dt = $res;
			} else {
				$dt = '';
			}
			
			
			if($dt){
				return $dt;
			}else{
				return false;
			}
		}

		public function get_data_claim_per_request($request_id){

			// $sql8 = "SELECT 	
			// 	SUM(cast(penggantian AS INTEGER)) as total_pembayaran_rawat_jalan,
			// 	SUM(cast(total_nominal_kuitansi AS INTEGER)) as total_claim_rawat_jalan
			// FROM		
			// 	hris_medical_reimbursment_item 
			// WHERE tor_grandparent = '1' AND request_id = '$request_id'";

			$sql8 = "SELECT 	
				SUM(penggantian) as total_pembayaran_rawat_jalan,
				SUM(total_nominal_kuitansi) as total_claim_rawat_jalan
			FROM		
				hris_medical_reimbursment_item 
			WHERE tor_grandparent = '1' AND request_id = '$request_id'";

			$query8 = $this->db->query($sql8);
			$res8 	= $query8->result();
			$res8PembayaranJalan 	= (!empty(($res8[0]->total_pembayaran_rawat_jalan))) ? (($res8[0]->total_pembayaran_rawat_jalan)) : 0;
			$res8ClaimJalan 		= (!empty(($res8[0]->total_claim_rawat_jalan))) ? (($res8[0]->total_claim_rawat_jalan)) : 0;
			
			// $sql9 = "SELECT 	
			// 	SUM(cast(penggantian AS INTEGER)) as total_pembayaran_rawat_inap,
			// 	SUM(cast(total_nominal_kuitansi AS INTEGER)) as total_claim_rawat_inap
			// FROM		
			// 	hris_medical_reimbursment_item 
			// WHERE tor_grandparent = '2' AND request_id = '$request_id'";

			$sql9 = "SELECT 	
				SUM(penggantian) as total_pembayaran_rawat_inap,
				SUM(total_nominal_kuitansi) as total_claim_rawat_inap
			FROM		
				hris_medical_reimbursment_item 
			WHERE tor_grandparent = '2' AND request_id = '$request_id'";

			$query9 = $this->db->query($sql9);
			$res9 	= $query9->result();
			$res9PembayaranInap 	= (!empty(($res9[0]->total_pembayaran_rawat_inap))) ? (($res9[0]->total_pembayaran_rawat_inap)) : 0;
			$res9ClaimInap 		= (!empty(($res9[0]->total_claim_rawat_inap))) ? (($res9[0]->total_claim_rawat_inap)) : 0;
			
			// $sql10 = "SELECT 	
			// 	SUM(cast(penggantian AS INTEGER)) as total_pembayaran_kacamata,
			// 	SUM(cast(total_nominal_kuitansi AS INTEGER)) as total_claim_kacamata
			// FROM		
			// 	hris_medical_reimbursment_item 
			// WHERE tor_grandparent = '3' AND request_id = '$request_id'";

			$sql10 = "SELECT 	
				SUM(penggantian) as total_pembayaran_kacamata,
				SUM(total_nominal_kuitansi) as total_claim_kacamata
			FROM		
				hris_medical_reimbursment_item 
			WHERE tor_grandparent = '3' AND request_id = '$request_id'";

			$query10 = $this->db->query($sql10);
			$res10 	= $query10->result();
			$res10PembayaranKacamata 	= (!empty(($res10[0]->total_pembayaran_kacamata))) ? (($res10[0]->total_pembayaran_kacamata)) : 0;
			$res10ClaimKacamata 		= (!empty(($res10[0]->total_claim_kacamata))) ? (($res10[0]->total_claim_kacamata)) : 0;

			$grand_total = $res8PembayaranJalan + $res9PembayaranInap + $res10PembayaranKacamata;

			if($grand_total){
				return $grand_total;
			}else{
				return false;
			}
		}

		public function get_data_claim_per_employee($request_id){

			$sql = "SELECT *
			FROM		
				form_request 
			WHERE id LIKE '$request_id' AND is_status LIKE '3'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			$form_request_id = (!empty(($res[0]->id))) ? (($res[0]->id)) : '';
			$employee_id = (!empty(($res[0]->employee_id))) ? (($res[0]->employee_id)) : '';

			$sql = "SELECT *
			FROM		
				hris_medical_reimbursment
			WHERE request_id LIKE '$form_request_id'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			$created_at = (!empty(($res[0]->created_at))) ? (($res[0]->created_at)) : '';
			$year = date("Y",strtotime($res[0]->created_at));
	    	$start_date 	= $year."-01-01";

			$sql = "SELECT 	
					a.id as id,
					a.request_id as request_id,
					e.is_status as is_status,
					b.grandparent as tor_grandparent,
					c.parent as tor_parent,
					d.child as tor_child,
					a.jumlah_kuitansi as jumlah_kuitansi,
					a.total_nominal_kuitansi as total_kuitansi,
					a.penggantian as penggantian,
					a.keterangan as keterangan,
					a.additional as additional,
					a.docter as docter,
					a.diagnosa as diagnosa,
					a.tanggal_kuitansi as tanggal_kuitansi,
					a.create_date as create_date
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_type_of_reimbursment_grandparent b ON a.tor_grandparent = b.id
				LEFT JOIN hris_medical_type_of_reimbursment_parent c ON a.tor_parent = c.id
				LEFT JOIN hris_medical_type_of_reimbursment_child d ON a.tor_child = d.id
				LEFT JOIN form_request e ON a.request_id = e.id
				WHERE a.request_id LIKE '$form_request_id'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			if($res){
				return $res;
			}else{
				return false;
			}
		}

		public function get_data_total_claim_per_employee($request_id){

			$sql = "SELECT *
			FROM		
				form_request 
			WHERE id LIKE '$request_id' AND is_status LIKE '3'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			$form_request_id = (!empty(($res[0]->id))) ? (($res[0]->id)) : '';
			$employee_id = (!empty(($res[0]->employee_id))) ? (($res[0]->employee_id)) : '';

			$sql = "SELECT *
			FROM		
				hris_medical_reimbursment
			WHERE request_id LIKE '$form_request_id'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			$created_at = (!empty(($res[0]->created_at))) ? (($res[0]->created_at)) : '';
			
			$year_created_at = date("Y",strtotime($res[0]->created_at));
			$year = date("Y",strtotime($res[0]->created_at));
	    	$start_date 	= $year."-01-01";

			$year_request	=	strtotime($created_at);
			$year_request	=	date("Y",$year_request);
			$today = $this->today;

			// $sql = "SELECT 	
			// 			TOP 2 employee_group as employee_group,
			// 			start_date as start_date,
			// 			reason_of_action as reason_of_action
			// 		FROM		
			// 			hris_employee
			// 		WHERE nik = '$employee_id' ORDER BY id_employee DESC";
			$sql = "SELECT 	
						employee_group as employee_group,
						start_date as start_date,
						reason_of_action as reason_of_action
					FROM		
						hris_employee
					WHERE nik = '$employee_id' ORDER BY id_employee DESC";		
			$query = $this->db->query($sql);
			$res 		= $query->result();
			// 'penambahan baru'
			$res = $this->my_array_unique($res);
			//$id_eg_prj 	= $res[0]->employee_group;
			$res_eg_new 		= decrypt($res[0]->employee_group);
			$res_sd_new 		= decrypt($res[0]->start_date);
			$reason_of_action	= decrypt($res[0]->reason_of_action);
			$res_eg_old 		= (!empty(($res[1]->employee_group))) ? decrypt(($res[1]->employee_group)) : '';

			$sql2new = "SELECT 	
						pagu_tahun as pagu_tahun
					FROM		
						hris_medical_pagu_rawat_jalan
					WHERE grade = '$res_eg_new'  AND start_date <= '$today' AND end_date > '$today'";
			$query2new 	= $this->db->query($sql2new);
			$res2new 	= $query2new->result();
			//$pagu_jalan_tahun_new 	= $res2new[0]->pagu_tahun;
			$pagu_jalan_tahun_new 	= (!empty(($res2new[0]->pagu_tahun))) ? ($res2new[0]->pagu_tahun) : 0;

			$sql3new = "SELECT 	
						pagu_kamar_hari as pagu_kamar_hari,
						pagu_tahun as pagu_tahun
					FROM		
						hris_medical_pagu_rawat_inap
					WHERE grade LIKE '$res_eg_new'  AND start_date <= '$today' AND end_date > '$today'";
			$query3new 	= $this->db->query($sql3new);
			$res3new 	= $query3new->result();
			// $pagu_inap_tahun_new 	= $res3new[0]->pagu_tahun;
			// $pagu_inap_kamar_new 	= $res3new[0]->pagu_kamar_hari;
			$pagu_inap_tahun_new 	= (!empty(($res3new[0]->pagu_tahun))) ? ($res3new[0]->pagu_tahun) : 0;
			$pagu_inap_kamar_new 	= (!empty(($res3new[0]->pagu_kamar_hari))) ? ($res3new[0]->pagu_kamar_hari) : 0;
			
			
			$sql2old = "SELECT 	
						pagu_tahun as pagu_tahun
					FROM		
						hris_medical_pagu_rawat_jalan
					WHERE grade = '$res_eg_old' AND start_date <= '$today' AND end_date > '$today'";
			$query2old 	= $this->db->query($sql2old);
			$res2old 	= $query2old->result();
			$pagu_jalan_tahun_old 	= (!empty(($res2old[0]->pagu_tahun))) ? ($res2old[0]->pagu_tahun) : 0;

			$sql3old = "SELECT 	
						pagu_kamar_hari as pagu_kamar_hari,
						pagu_tahun as pagu_tahun
					FROM		
						hris_medical_pagu_rawat_inap
					WHERE grade LIKE '$res_eg_old' AND start_date <= '$today' AND end_date > '$today'";
			$query3old 	= $this->db->query($sql3old);
			$res3old 	= $query3old->result();
			$pagu_inap_tahun_old 	= (!empty(($res3old[0]->pagu_tahun))) ? ($res3old[0]->pagu_tahun) : 0;
			$pagu_inap_kamar_old 	= (!empty(($res3old[0]->pagu_kamar_hari))) ? ($res3old[0]->pagu_kamar_hari) : 0;

			$start_date = DateTime::createFromFormat('Ymd', $res_sd_new);
			//$start_date = DateTime::createFromFormat('Ymd', '20220518');
			$start_date = $start_date->format('Y');
			$year 		= date("Y");

			if( ($start_date == $year) && (!empty($res_eg_old)) && ($reason_of_action == 'Promotion') ){

				$yearOld = $year-1;
				$yearNew = $year+1;
				$tgl1 = $yearOld."-12-31";
				$tgl2 = DateTime::createFromFormat('Ymd', $res_sd_new);
				$tgl2 = $tgl2->format('Y-m-d');
				$tgl22 = date('Y-m-d', strtotime('-1 days', strtotime($tgl2)));
	    		$tgl3 = $yearNew."-01-01";

				$diff_old                = abs(strtotime($tgl22) - strtotime($tgl1));
				$join_years_old          = floor($diff_old / (365*60*60*24));
				$join_months_old         = floor(($diff_old - $join_years_old * 365*60*60*24) / (30*60*60*24));
				// $join_days_old         	 = floor(($diff - $join_years_old * 365*60*60*24 - $join_months_old*30*60*60*24)/ (60*60*24));
				$join_days_old         	 = round($diff_old / (60 * 60 * 24));

				
				$diff_new                = abs(strtotime($tgl3) - strtotime($tgl2));
				$join_years_new          = floor($diff_new / (365*60*60*24));
				$join_months_new         = floor(($diff_new - $join_years_new * 365*60*60*24) / (30*60*60*24));
				// $join_days_new         	 = floor(($diff - $join_years_new * 365*60*60*24 - $join_months_new*30*60*60*24)/ (60*60*24));
				$join_days_new         	 = round($diff_new / (60 * 60 * 24));
				
				$pagu_pro_inap_tahun_new = ($join_days_new/365)* $pagu_inap_tahun_new;
				$pagu_inap_tahun_old = ((!empty(($pagu_inap_tahun_old))) ? ($pagu_inap_tahun_old) : 0);
				$pagu_pro_inap_tahun_old = ($join_days_old/365)* $pagu_inap_tahun_old;
				$pagu_inap_tahun = $pagu_pro_inap_tahun_new + $pagu_pro_inap_tahun_old;
				
				$pagu_pro_jalan_tahun_new = ($join_days_new/365) * $pagu_jalan_tahun_new;
				$pagu_jalan_tahun_old = ((!empty($pagu_jalan_tahun_old)) ? ($pagu_jalan_tahun_old) : 0);
				$pagu_pro_jalan_tahun_old = ($join_days_old/365) * $pagu_jalan_tahun_old;
				$pagu_jalan_tahun = $pagu_pro_jalan_tahun_new + $pagu_pro_jalan_tahun_old;

				$pagu_inap_kamar 	= $pagu_inap_kamar_new;
				

			}else if( ($start_date == $year) AND ($reason_of_action == 'Hiring') ){

				//$yearOld = $year-1;
				$yearNew = $year+1;
				//$tgl1 = $yearOld."-12-31";
				$tgl2 = DateTime::createFromFormat('Ymd', $res_sd_new);
				$tgl2 = $tgl2->format('Y-m-d');
	    		$tgl3 = $yearNew."-01-01";

				
				$diff_new                = abs(strtotime($tgl3) - strtotime($tgl2));
				$join_years_new          = floor($diff_new / (365*60*60*24));
				$join_months_new         = floor(($diff_new - $join_years_new * 365*60*60*24) / (30*60*60*24));
				$join_days_new         	 = round($diff_new / (60 * 60 * 24));
				
				$pagu_pro_inap_tahun_new = ($join_days_new/365)* $pagu_inap_tahun_new;
				$pagu_inap_tahun = $pagu_pro_inap_tahun_new;
				
				$pagu_pro_jalan_tahun_new = ($join_days_new/365) * $pagu_jalan_tahun_new;
				$pagu_jalan_tahun = $pagu_pro_jalan_tahun_new;

				$pagu_inap_kamar 	= $pagu_inap_kamar_new;
				

			}else{

				$pagu_jalan_tahun	= $pagu_jalan_tahun_new;
				$pagu_inap_tahun	= $pagu_inap_tahun_new;
				$pagu_inap_kamar 	= $pagu_inap_kamar_new;

			}

			$sql4 = "SELECT 	
						pagu_one_focus_tahun as pagu_one_focus_tahun,
						pagu_two_focus_tahun as pagu_two_focus_tahun,
						pagu_frame_dua_tahun as pagu_frame_dua_tahun
					FROM		
						hris_medical_pagu_kacamata
					WHERE grade = '$res_eg_new' AND start_date <= '$today' AND end_date > '$today'";
			$query4 = $this->db->query($sql4);
			$res4 	= $query4->result();
			$pagu_one_focus_tahun 	= $res4[0]->pagu_one_focus_tahun;
			$pagu_two_focus_tahun 	= $res4[0]->pagu_two_focus_tahun;
			$pagu_frame_dua_tahun 	= $res4[0]->pagu_frame_dua_tahun;

			// $sql = "SELECT 
			// 	SUM( CONVERT(INT,a.total_nominal_kuitansi) ) as total_nominal_kuitansi_rawat_jalan,
			// 	SUM( CONVERT(INT,a.penggantian) ) as total_penggantian_rawat_jalan
			// FROM		
			// 	hris_medical_reimbursment_item a
			// LEFT JOIN form_request b ON a.request_id = b.id
			// WHERE b.is_status = '3' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '1' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
			$sql = "SELECT 
				SUM(a.total_nominal_kuitansi) as total_nominal_kuitansi_rawat_jalan,
				SUM(a.penggantian) as total_penggantian_rawat_jalan
			FROM		
				hris_medical_reimbursment_item a
			LEFT JOIN form_request b ON a.request_id = b.id
			WHERE b.is_status = '3' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '1' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			$total_nominal_kuitansi_rawat_jalan = (!empty(($res[0]->total_nominal_kuitansi_rawat_jalan))) ? (($res[0]->total_nominal_kuitansi_rawat_jalan)) : 0;
			$total_penggantian_rawat_jalan = (!empty(($res[0]->total_penggantian_rawat_jalan))) ? (($res[0]->total_penggantian_rawat_jalan)) : 0;
			
			// $sql = "SELECT 
			// 	SUM( CONVERT(INT,a.total_nominal_kuitansi) ) as total_nominal_kuitansi_rawat_inap,
			// 	SUM( CONVERT(INT,a.penggantian) ) as total_penggantian_rawat_inap
			// FROM		
			// 	hris_medical_reimbursment_item a
			// LEFT JOIN form_request b ON a.request_id = b.id
			// WHERE b.is_status = '3' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '2' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
			$sql = "SELECT 
				SUM(a.total_nominal_kuitansi) as total_nominal_kuitansi_rawat_inap,
				SUM(a.penggantian) as total_penggantian_rawat_inap
			FROM		
				hris_medical_reimbursment_item a
			LEFT JOIN form_request b ON a.request_id = b.id
			WHERE b.is_status = '3' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '2' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			$total_nominal_kuitansi_rawat_inap = (!empty(($res[0]->total_nominal_kuitansi_rawat_inap))) ? (($res[0]->total_nominal_kuitansi_rawat_inap)) : 0;
			$total_penggantian_rawat_inap = (!empty(($res[0]->total_penggantian_rawat_inap))) ? (($res[0]->total_penggantian_rawat_inap)) : 0;
			
			// $sql = "SELECT 
			// 	SUM( CONVERT(INT,a.total_nominal_kuitansi) ) as total_nominal_kuitansi_kacamata,
			// 	SUM( CONVERT(INT,a.penggantian) ) as total_penggantian_kacamata
			// FROM		
			// 	hris_medical_reimbursment_item a
			// LEFT JOIN form_request b ON a.request_id = b.id
			// WHERE b.is_status = '3' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '3' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
			$sql = "SELECT 
				SUM(a.total_nominal_kuitansi) as total_nominal_kuitansi_kacamata,
				SUM(a.penggantian) as total_penggantian_kacamata
			FROM		
				hris_medical_reimbursment_item a
			LEFT JOIN form_request b ON a.request_id = b.id
			WHERE b.is_status = '3' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '3' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			$total_nominal_kuitansi_kacamata = (!empty(($res[0]->total_nominal_kuitansi_kacamata))) ? (($res[0]->total_nominal_kuitansi_kacamata)) : 0;
			$total_penggantian_kacamata = (!empty(($res[0]->total_penggantian_kacamata))) ? (($res[0]->total_penggantian_kacamata)) : 0;

			$pagu_optic_tahun = ($pagu_one_focus_tahun + $pagu_two_focus_tahun + $pagu_frame_dua_tahun);




			// $sql = "SELECT 
			// 	SUM( CONVERT(INT,a.total_nominal_kuitansi) ) as total_nominal_kuitansi_rawat_jalan_per_request,
			// 	SUM( CONVERT(INT,a.penggantian) ) as total_penggantian_rawat_jalan_per_request
			// FROM		
			// 	hris_medical_reimbursment_item a
			// LEFT JOIN form_request b ON a.request_id = b.id
			// WHERE b.is_status = '3' AND a.request_id LIKE '$form_request_id' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '1' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
			$sql = "SELECT 
				SUM(a.total_nominal_kuitansi) as total_nominal_kuitansi_rawat_jalan_per_request,
				SUM(a.penggantian) as total_penggantian_rawat_jalan_per_request
			FROM		
				hris_medical_reimbursment_item a
			LEFT JOIN form_request b ON a.request_id = b.id
			WHERE b.is_status = '3' AND a.request_id LIKE '$form_request_id' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '1' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			$total_nominal_kuitansi_rawat_jalan_per_request = (!empty(($res[0]->total_nominal_kuitansi_rawat_jalan_per_request))) ? (($res[0]->total_nominal_kuitansi_rawat_jalan_per_request)) : 0;
			$total_penggantian_rawat_jalan_per_request = (!empty(($res[0]->total_penggantian_rawat_jalan_per_request))) ? (($res[0]->total_penggantian_rawat_jalan_per_request)) : 0;
			
			// $sql = "SELECT 
			// 	SUM( CONVERT(INT,a.total_nominal_kuitansi) ) as total_nominal_kuitansi_rawat_inap_per_request,
			// 	SUM( CONVERT(INT,a.penggantian) ) as total_penggantian_rawat_inap_per_request
			// FROM		
			// 	hris_medical_reimbursment_item a
			// LEFT JOIN form_request b ON a.request_id = b.id
			// WHERE b.is_status = '3' AND a.request_id LIKE '$form_request_id' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '2' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
			$sql = "SELECT 
				SUM(a.total_nominal_kuitansi) as total_nominal_kuitansi_rawat_inap_per_request,
				SUM(a.penggantian) as total_penggantian_rawat_inap_per_request
			FROM		
				hris_medical_reimbursment_item a
			LEFT JOIN form_request b ON a.request_id = b.id
			WHERE b.is_status = '3' AND a.request_id LIKE '$form_request_id' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '2' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			$total_nominal_kuitansi_rawat_inap_per_request = (!empty(($res[0]->total_nominal_kuitansi_rawat_inap_per_request))) ? (($res[0]->total_nominal_kuitansi_rawat_inap_per_request)) : 0;
			$total_penggantian_rawat_inap_per_request = (!empty(($res[0]->total_penggantian_rawat_inap_per_request))) ? (($res[0]->total_penggantian_rawat_inap_per_request)) : 0;
			
			// $sql = "SELECT 
			// 	SUM( CONVERT(INT,a.total_nominal_kuitansi) ) as total_nominal_kuitansi_kacamata_per_request,
			// 	SUM( CONVERT(INT,a.penggantian) ) as total_penggantian_kacamata_per_request
			// FROM		
			// 	hris_medical_reimbursment_item a
			// LEFT JOIN form_request b ON a.request_id = b.id
			// WHERE b.is_status = '3' AND a.request_id LIKE '$form_request_id' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '3' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
			$sql = "SELECT 
				SUM(a.total_nominal_kuitansi) as total_nominal_kuitansi_kacamata_per_request,
				SUM(a.penggantian)as total_penggantian_kacamata_per_request
			FROM		
				hris_medical_reimbursment_item a
			LEFT JOIN form_request b ON a.request_id = b.id
			WHERE b.is_status = '3' AND a.request_id LIKE '$form_request_id' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '3' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			$total_nominal_kuitansi_kacamata_per_request = (!empty(($res[0]->total_nominal_kuitansi_kacamata_per_request))) ? (($res[0]->total_nominal_kuitansi_kacamata_per_request)) : 0;
			$total_penggantian_kacamata_per_request = (!empty(($res[0]->total_penggantian_kacamata_per_request))) ? (($res[0]->total_penggantian_kacamata_per_request)) : 0;


			$data = array(
				'pagu_jalan_tahun' => ( $pagu_jalan_tahun ),
				'total_nominal_kuitansi_rawat_jalan' => ( $total_nominal_kuitansi_rawat_jalan),
				'total_nominal_kuitansi_rawat_jalan_per_request' => ( $total_nominal_kuitansi_rawat_jalan_per_request),
				'total_penggantian_rawat_jalan' => ( $total_penggantian_rawat_jalan),
				'total_penggantian_rawat_jalan_per_request' => ( $total_penggantian_rawat_jalan_per_request),
				'balancing_jalan_tahun' => ( $pagu_jalan_tahun - $total_penggantian_rawat_jalan),
				'pagu_inap_tahun' => ( $pagu_inap_tahun ),
				'total_nominal_kuitansi_rawat_inap' => ( $total_nominal_kuitansi_rawat_inap),
				'total_nominal_kuitansi_rawat_inap_per_request' => ( $total_nominal_kuitansi_rawat_inap_per_request),
				'total_penggantian_rawat_inap' => ( $total_penggantian_rawat_inap),
				'total_penggantian_rawat_inap_per_request' => ( $total_penggantian_rawat_inap_per_request),
				'balancing_inap_tahun' => ( $pagu_inap_tahun - $total_penggantian_rawat_inap),
				'pagu_optic_tahun' => ( $pagu_optic_tahun ),
				'total_nominal_kuitansi_kacamata' => ( $total_nominal_kuitansi_kacamata),
				'total_nominal_kuitansi_kacamata_per_request' => ( $total_nominal_kuitansi_kacamata_per_request),
				'total_penggantian_kacamata' => ( $total_penggantian_kacamata),
				'total_penggantian_kacamata_per_request' => ( $total_penggantian_kacamata_per_request),
				'balancing_optic_tahun' => ( $pagu_optic_tahun - $total_penggantian_kacamata),
				'year_created_at' => $year_created_at
			);
			
			return $data;

		}

		public function get_data_employee_current($request_id){

			$sql = "SELECT *
			FROM		
				form_request 
			WHERE id LIKE '$request_id' AND is_status LIKE '3'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			$form_request_id = (!empty(($res[0]->id))) ? (($res[0]->id)) : '';

			$sql = "SELECT *
			FROM		
				hris_medical_reimbursment
			WHERE request_id LIKE '$form_request_id'";
			$query = $this->db->query($sql);
			$res 	= $query->result();
			$id_hr_emp = (!empty(($res[0]->id_hr_emp))) ? (decrypt($res[0]->id_hr_emp)) : '';

			$sql = "SELECT * FROM hris_employee WHERE id_employee = '$id_hr_emp'";
			$query = $this->db->query($sql);
			$res = $query->result();
			
			return $res[0];
		}

		public function get_approval_priority($request_id, $email){

			$sql = "SELECT approval_priority FROM form_approval WHERE request_id LIKE '$request_id' AND approval_email LIKE '$email'";
	    	$query = $this->db->query($sql);
			$res = $query->result();
			if ($res == null || $res == '') {
				return $res;
			} else {
				return $res[0];
			}
		}
	//

	public function get_data_total_claim_for_print_iso($request_id){

		$sql = "SELECT *
		FROM		
			form_request 
		WHERE id LIKE '$request_id' AND is_status LIKE '3'";
		$query = $this->db->query($sql);
		$res 	= $query->result();
		$form_request_id = (!empty(($res[0]->id))) ? (($res[0]->id)) : '';
		$employee_id = (!empty(($res[0]->employee_id))) ? (($res[0]->employee_id)) : '';

		$sql = "SELECT *
		FROM		
			hris_medical_reimbursment
		WHERE request_id LIKE '$form_request_id'";
		$query = $this->db->query($sql);
		$res 	= $query->result();
		$created_at = (!empty(($res[0]->created_at))) ? (($res[0]->created_at)) : '';
		
		$year_created_at = date("Y",strtotime($res[0]->created_at));
		$year = date("Y",strtotime($res[0]->created_at));
    	$start_date 	= $year."-01-01";

		$year_request	=	strtotime($created_at);
		$year_request	=	date("Y",$year_request);
		$today = $this->today;

		// $sql = "SELECT 	
		// 			TOP 2 employee_group as employee_group,
		// 			start_date as start_date,
		// 			reason_of_action as reason_of_action
		// 		FROM		
		// 			hris_employee
		// 		WHERE nik = '$employee_id' ORDER BY id_employee DESC";
		$sql = "SELECT 	
			employee_group as employee_group,
			start_date as start_date,
			reason_of_action as reason_of_action
		FROM		
			hris_employee
		WHERE nik = '$employee_id' ORDER BY id_employee DESC";
		$query = $this->db->query($sql);
		$res 		= $query->result();
		// dumper($res);
		//penambahan baru
		$res = $this->my_array_unique($res);

		//$id_eg_prj 	= $res[0]->employee_group;
		$res_eg_new 		= decrypt($res[0]->employee_group);
		$res_sd_new 		= decrypt($res[0]->start_date);
		
		$reason_of_action	= decrypt($res[0]->reason_of_action);
		$res_eg_old 		= (!empty(($res[1]->employee_group))) ? decrypt(($res[1]->employee_group)) : '';

		$sql2new = "SELECT 	
					pagu_tahun as pagu_tahun
				FROM		
					hris_medical_pagu_rawat_jalan
				WHERE grade = '$res_eg_new'  AND start_date <= '$today' AND end_date > '$today'";
		$query2new 	= $this->db->query($sql2new);
		$res2new 	= $query2new->result();
		//$pagu_jalan_tahun_new 	= $res2new[0]->pagu_tahun;
		$pagu_jalan_tahun_new 	= (!empty(($res2new[0]->pagu_tahun))) ? ($res2new[0]->pagu_tahun) : 0;

		$sql3new = "SELECT 	
					pagu_kamar_hari as pagu_kamar_hari,
					pagu_tahun as pagu_tahun
				FROM		
					hris_medical_pagu_rawat_inap
				WHERE grade LIKE '$res_eg_new'  AND start_date <= '$today' AND end_date > '$today'";
		$query3new 	= $this->db->query($sql3new);
		$res3new 	= $query3new->result();
		// $pagu_inap_tahun_new 	= $res3new[0]->pagu_tahun;
		// $pagu_inap_kamar_new 	= $res3new[0]->pagu_kamar_hari;
		$pagu_inap_tahun_new 	= (!empty(($res3new[0]->pagu_tahun))) ? ($res3new[0]->pagu_tahun) : 0;
		$pagu_inap_kamar_new 	= (!empty(($res3new[0]->pagu_kamar_hari))) ? ($res3new[0]->pagu_kamar_hari) : 0;
		
		
		$sql2old = "SELECT 	
					pagu_tahun as pagu_tahun
				FROM		
					hris_medical_pagu_rawat_jalan
				WHERE grade = '$res_eg_old' AND start_date <= '$today' AND end_date > '$today'";
		$query2old 	= $this->db->query($sql2old);
		$res2old 	= $query2old->result();
		$pagu_jalan_tahun_old 	= (!empty(($res2old[0]->pagu_tahun))) ? ($res2old[0]->pagu_tahun) : 0;

		$sql3old = "SELECT 	
					pagu_kamar_hari as pagu_kamar_hari,
					pagu_tahun as pagu_tahun
				FROM		
					hris_medical_pagu_rawat_inap
				WHERE grade LIKE '$res_eg_old' AND start_date <= '$today' AND end_date > '$today'";
		$query3old 	= $this->db->query($sql3old);
		$res3old 	= $query3old->result();
		$pagu_inap_tahun_old 	= (!empty(($res3old[0]->pagu_tahun))) ? ($res3old[0]->pagu_tahun) : 0;
		$pagu_inap_kamar_old 	= (!empty(($res3old[0]->pagu_kamar_hari))) ? ($res3old[0]->pagu_kamar_hari) : 0;

		$start_date = DateTime::createFromFormat('Ymd', $res_sd_new);
		//$start_date = DateTime::createFromFormat('Ymd', '20220518');
		$start_date = $start_date->format('Y');
		$year 		= date("Y");


		if( ($start_date == $year) && (!empty($res_eg_old)) && ($reason_of_action == 'Promotion') ){

			$yearOld = $year-1;
			$yearNew = $year+1;
			$tgl1 = $yearOld."-12-31";
			$tgl2 = DateTime::createFromFormat('Ymd', $res_sd_new);
			$tgl2 = $tgl2->format('Y-m-d');
			$tgl22 = date('Y-m-d', strtotime('-1 days', strtotime($tgl2)));
    		$tgl3 = $yearNew."-01-01";

			$diff_old                = abs(strtotime($tgl22) - strtotime($tgl1));
			$join_years_old          = floor($diff_old / (365*60*60*24));
			$join_months_old         = floor(($diff_old - $join_years_old * 365*60*60*24) / (30*60*60*24));
			// $join_days_old         	 = floor(($diff - $join_years_old * 365*60*60*24 - $join_months_old*30*60*60*24)/ (60*60*24));
			$join_days_old         	 = round($diff_old / (60 * 60 * 24));

			
			$diff_new                = abs(strtotime($tgl3) - strtotime($tgl2));
			$join_years_new          = floor($diff_new / (365*60*60*24));
			$join_months_new         = floor(($diff_new - $join_years_new * 365*60*60*24) / (30*60*60*24));
			// $join_days_new         	 = floor(($diff - $join_years_new * 365*60*60*24 - $join_months_new*30*60*60*24)/ (60*60*24));
			$join_days_new         	 = round($diff_new / (60 * 60 * 24));
			
			$pagu_pro_inap_tahun_new = ($join_days_new/365)* $pagu_inap_tahun_new;
			$pagu_inap_tahun_old = ((!empty(($pagu_inap_tahun_old))) ? ($pagu_inap_tahun_old) : 0);
			$pagu_pro_inap_tahun_old = ($join_days_old/365)* $pagu_inap_tahun_old;
			$pagu_inap_tahun = $pagu_pro_inap_tahun_new + $pagu_pro_inap_tahun_old;
			
			$pagu_pro_jalan_tahun_new = ($join_days_new/365) * $pagu_jalan_tahun_new;
			$pagu_jalan_tahun_old = ((!empty($pagu_jalan_tahun_old)) ? ($pagu_jalan_tahun_old) : 0);
			$pagu_pro_jalan_tahun_old = ($join_days_old/365) * $pagu_jalan_tahun_old;
			$pagu_jalan_tahun = $pagu_pro_jalan_tahun_new + $pagu_pro_jalan_tahun_old;

			$pagu_inap_kamar 	= $pagu_inap_kamar_new;

		}else if( ($start_date == $year) AND ($reason_of_action == 'Hiring') ){

			//$yearOld = $year-1;
			$yearNew = $year+1;
			//$tgl1 = $yearOld."-12-31";
			$tgl2 = DateTime::createFromFormat('Ymd', $res_sd_new);
			$tgl2 = $tgl2->format('Y-m-d');
    		$tgl3 = $yearNew."-01-01";

			
			$diff_new                = abs(strtotime($tgl3) - strtotime($tgl2));
			$join_years_new          = floor($diff_new / (365*60*60*24));
			$join_months_new         = floor(($diff_new - $join_years_new * 365*60*60*24) / (30*60*60*24));
			$join_days_new         	 = round($diff_new / (60 * 60 * 24));
			
			$pagu_pro_inap_tahun_new = ($join_days_new/365)* $pagu_inap_tahun_new;
			$pagu_inap_tahun = $pagu_pro_inap_tahun_new;
			
			$pagu_pro_jalan_tahun_new = ($join_days_new/365) * $pagu_jalan_tahun_new;
			$pagu_jalan_tahun = $pagu_pro_jalan_tahun_new;

			$pagu_inap_kamar 	= $pagu_inap_kamar_new;
			

		}else{

			// $start_date = $year.'-01-01 00-00-00';
			$pagu_jalan_tahun	= $pagu_jalan_tahun_new;
			$pagu_inap_tahun	= $pagu_inap_tahun_new;
			$pagu_inap_kamar 	= $pagu_inap_kamar_new;

		}

		// dumper($pagu_jalan_tahun);

		$sql4 = "SELECT 	
					pagu_one_focus_tahun as pagu_one_focus_tahun,
					pagu_two_focus_tahun as pagu_two_focus_tahun,
					pagu_frame_dua_tahun as pagu_frame_dua_tahun
				FROM		
					hris_medical_pagu_kacamata
				WHERE grade = '$res_eg_new' AND start_date <= '$today' AND end_date > '$today'";
		$query4 = $this->db->query($sql4);
		$res4 	= $query4->result();
		$pagu_one_focus_tahun 	= $res4[0]->pagu_one_focus_tahun;
		$pagu_two_focus_tahun 	= $res4[0]->pagu_two_focus_tahun;
		$pagu_frame_dua_tahun 	= $res4[0]->pagu_frame_dua_tahun;

		// $sql = "SELECT 
		// 	SUM( CONVERT(INT,a.total_nominal_kuitansi) ) as total_nominal_kuitansi_rawat_jalan,
		// 	SUM( CONVERT(INT,a.penggantian) ) as total_penggantian_rawat_jalan
		// FROM		
		// 	hris_medical_reimbursment_item a
		// LEFT JOIN form_request b ON a.request_id = b.id
		// WHERE b.is_status = '3' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '1' AND a.create_date BETWEEN '$start_date' AND '$created_at'";

		//old mau di revisi zulvan//

		// $sql = "SELECT 
		// 	SUM(a.total_nominal_kuitansi) as total_nominal_kuitansi_rawat_jalan,
		// 	SUM(a.penggantian) as total_penggantian_rawat_jalan
		// FROM		
		// 	hris_medical_reimbursment_item a
		// LEFT JOIN form_request b ON a.request_id = b.id
		// WHERE b.is_status = '3' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '1' AND a.create_date BETWEEN '$start_date' AND '$created_at'";

		//baru yang mau di ganti//

		$sql = "SELECT 	
			SUM(a.total_nominal_kuitansi) as total_nominal_kuitansi_rawat_jalan,
			SUM(a.penggantian) as total_penggantian_rawat_jalan
		FROM		
			hris_medical_reimbursment_item a
		LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
		LEFT JOIN form_request c ON b.request_id = c.id
		WHERE b.employee_id = '$employee_id' and a.tor_grandparent = '1' and c.is_status = '3' and year(a.tanggal_kuitansi) LIKE '%$this->year%' and a.request_id <= '$request_id'";
		// dumper($sql);

		$query = $this->db->query($sql);
		$res 	= $query->result();
		$total_nominal_kuitansi_rawat_jalan = (!empty(($res[0]->total_nominal_kuitansi_rawat_jalan))) ? (($res[0]->total_nominal_kuitansi_rawat_jalan)) : 0;
		$total_penggantian_rawat_jalan = (!empty(($res[0]->total_penggantian_rawat_jalan))) ? (($res[0]->total_penggantian_rawat_jalan)) : 0;
		

		//old mau direvisi zulvan//
			// $sql = "SELECT 
			// 	SUM(a.total_nominal_kuitansi) as total_nominal_kuitansi_rawat_inap,
			// 	SUM(a.penggantian) as total_penggantian_rawat_inap
			// FROM		
			// 	hris_medical_reimbursment_item a
			// LEFT JOIN form_request b ON a.request_id = b.id
			// WHERE b.is_status = '3' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '2' AND a.create_date BETWEEN '$start_date' AND '$created_at'";

		//baru yang mau di ganti 24-02-2023//

			$sql = "SELECT 	
						SUM(a.total_nominal_kuitansi) as total_nominal_kuitansi_rawat_inap,
						SUM(a.penggantian) as total_penggantian_rawat_inap
					FROM		
						hris_medical_reimbursment_item a
					LEFT JOIN hris_medical_reimbursment b ON a.request_id = b.request_id
					LEFT JOIN form_request c ON b.request_id = c.id
					WHERE b.employee_id = '$employee_id' and a.tor_grandparent = '2' and c.is_status = '3' and year(a.tanggal_kuitansi) LIKE '%$this->year%' and a.request_id <= '$request_id'";

			$query = $this->db->query($sql);
			$res 	= $query->result();
			$total_nominal_kuitansi_rawat_inap = (!empty(($res[0]->total_nominal_kuitansi_rawat_inap))) ? (($res[0]->total_nominal_kuitansi_rawat_inap)) : 0;
			$total_penggantian_rawat_inap = (!empty(($res[0]->total_penggantian_rawat_inap))) ? (($res[0]->total_penggantian_rawat_inap)) : 0;
		//

		$sql = "SELECT 
			SUM(a.total_nominal_kuitansi) as total_nominal_kuitansi_kacamata,
			SUM(a.penggantian) as total_penggantian_kacamata
		FROM		
			hris_medical_reimbursment_item a
		LEFT JOIN form_request b ON a.request_id = b.id
		WHERE b.is_status = '3' AND a.employee_id LIKE '$employee_id' AND a.tor_grandparent = '3' AND a.create_date BETWEEN '$start_date' AND '$created_at'";
		$query = $this->db->query($sql);
		$res 	= $query->result();
		$total_nominal_kuitansi_kacamata = (!empty(($res[0]->total_nominal_kuitansi_kacamata))) ? (($res[0]->total_nominal_kuitansi_kacamata)) : 0;
		$total_penggantian_kacamata = (!empty(($res[0]->total_penggantian_kacamata))) ? (($res[0]->total_penggantian_kacamata)) : 0;

		$pagu_optic_tahun = ($pagu_one_focus_tahun + $pagu_two_focus_tahun + $pagu_frame_dua_tahun);


		//cek senin zul 24-02-2023//
		// $sqlhmr = "SELECT 	
		// 			id_eg_prj as id_eg_prj,
		// 			id_eg_pri as id_eg_pri,
		// 			id_eg_pk as id_eg_pk
		// 		FROM		
		// 			hris_medical_reimbursment
		// 		WHERE employee_id = '$employee_id' ORDER BY id DESC LIMIT 2";
		// $queryhmr = $this->db->query($sqlhmr);
		// $reshmr 		= $queryhmr->result();
		// $id_eg_prj_old 		= (!empty(($reshmr[1]->id_eg_prj))) ? (($reshmr[1]->id_eg_prj)) : '';
		// $id_eg_pri_old 		= (!empty(($reshmr[1]->id_eg_pri))) ? (($reshmr[1]->id_eg_pri)) : '';
		// $id_eg_pk_old 		= (!empty(($reshmr[1]->id_eg_pk))) ? (($reshmr[1]->id_eg_pk)) : '';

		// if($id_eg_prj_old == $eg_prj){
		// 	$penggunaan_jalan = $res8;
		// }else{
		// 	$penggunaan_jalan = $res8;
		// }

		// if($id_eg_pri_old == $eg_pri){
		// 	$penggunaan_inap = $res9;
		// }else{
		// 	$penggunaan_inap = ($res9);
		// }
		


		// dumper($pagu_jalan_tahun);
		// dumper($total_penggantian_rawat_jalan);
		// dumper($total_penggantian_rawat_jalan);
		// dumper($pagu_jalan_tahun);
		// dumper($total_nominal_kuitansi_rawat_jalan);
		$data = array(
			'pagu_jalan_tahun' => ( $pagu_jalan_tahun ),
			'total_nominal_kuitansi_rawat_jalan' => ( $total_nominal_kuitansi_rawat_jalan),
			'total_penggantian_rawat_jalan' => ( $total_penggantian_rawat_jalan),
			'balancing_jalan_tahun' => ( $pagu_jalan_tahun - $total_penggantian_rawat_jalan),
			'pagu_inap_tahun' => ( $pagu_inap_tahun ),
			'total_nominal_kuitansi_rawat_inap' => ( $total_nominal_kuitansi_rawat_inap),
			'total_penggantian_rawat_inap' => ( $total_penggantian_rawat_inap),
			'balancing_inap_tahun' => ( $pagu_inap_tahun - $total_penggantian_rawat_inap),
			'pagu_optic_tahun' => ( $pagu_optic_tahun ),
			'total_nominal_kuitansi_kacamata' => ( $total_nominal_kuitansi_kacamata),
			'total_penggantian_kacamata' => ( $total_penggantian_kacamata),
			'balancing_optic_tahun' => ( $pagu_optic_tahun - $total_penggantian_kacamata),
			'year_created_at' => $year_created_at
		);
		
		// dumper($pagu_jalan_tahun - $total_penggantian_rawat_jalan);
		// dumper($data);
		return $data;

	}
}