<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends Admin_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('curl');
		// $this->load->library('enc');
		// $this->enc->check_session();

		$this->email = $this->session->userdata('user_email');
		$this->division = $this->session->userdata('division');
		$this->second_division = $this->session->userdata('second_division');
		$this->emp_id = $this->session->userdata('employee_id');

		// if ($this->emp_id == '') {
		// 	print_r("You are not authorized to access this apps.");
		// 	exit();
		// }
		
		$this->load->helper('general');
		$this->load->model('master/master_model');
		$this->load->model('inbox/inbox_model');
		$this->load->model('form/form_model');
		$this->load->model('home/home_model');
		$this->load->model('m_global');
		$this->date = date('Y-m-d H:i:s');
		$this->year = date('Y');

		if(empty($this->session->userdata('nik'))){
            $this->session->set_flashdata('failure', 'Login failed');
            redirect('login');
        }
	}

	public function index()
	{
		print_r('Please go back to login.');die;
	}

	public function users()
	{
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'master/users';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function regional_pm()
	{
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'master/regional_pm';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	
	public function mod_edit_rawat_jalan()
	{
		$this->load->view('master/mod/mod_edit_rawat_jalan');
	}
	
	public function employee()
	{
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'master/employee';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function medical_plafon()
	{
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'master/medical_plafon';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function medical_type_of_reimbursment()
	{
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'master/medical_type_of_reimbursment';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function read($table)
	{
		switch ($table) {

			case 'employee':

				$listForm = $this->master_model->getEmployee();
				// dumper($listForm);
		        if (!empty($listForm)) {
				// dumper($listForm);

		        foreach ($listForm as $key) {
						// $start_date = decrypt($key->start_date);
						// $start_date = DateTime::createFromFormat('Ymd', $start_date);
						// $start_date = $start_date->format('d.m.Y');
						$start_date = decrypt($key->start_date);
						$start_date = date("Y-m-d",strtotime($start_date));
						$start_date = DateTime::createFromFormat('Y-m-d', $start_date)->format('d.m.Y');
						//dumper($start_date);

						// $date_of_birth = decrypt($key->date_of_birth);
						// $date_of_birth = DateTime::createFromFormat('Ymd', $date_of_birth);
						// $date_of_birth = $date_of_birth->format('d.m.Y');
						$date_of_birth = decrypt($key->date_of_birth);
						$date_of_birth = date("Y-m-d",strtotime($date_of_birth));
						$date_of_birth = DateTime::createFromFormat('Y-m-d', $date_of_birth)->format('d.m.Y');
						// dumper($date_of_birth);
						// $join_date = decrypt($key->join_date);
						// $join_date = DateTime::createFromFormat('Ymd', $join_date);
						// $join_date = $join_date->format('d.m.Y');
						$join_date = decrypt($key->join_date);
						$join_date = date("d.m.Y",strtotime($join_date));
						// $join_date = DateTime::createFromFormat('Y-m-d', $join_date)->format('d.m.Y');
						//dumper($join_date);

						if((decrypt($key->marital_status)) == 'Marr.'){
							$marital_status = 'Married';
						}else if(((decrypt($key->marital_status)) == 'Div.') || ((decrypt($key->marital_status)) == 'Wid.')){
							////Updating date 16/10/2023
							$marital_status = 'Divorce';
							////End - Mengaktifkan Marital Status Divorce//////////
						}else{
							$marital_status = 'Single';
						}


						$complete_name = str_replace("||","'", decrypt($key->complete_name));

						$superior_name = str_replace("||","'", decrypt($key->superior_name));
						
						$rpm_name 		= str_replace("||","'", decrypt($key->rpm_name));

						$depthead_name = str_replace("||","'", decrypt($key->depthead_name));

						$divhead_name = str_replace("||","'", decrypt($key->divhead_name));

						$director_name = str_replace("||","'", decrypt($key->director_name));

            $row   = array();
            $row[] = $key->nik;
            $row[] = $complete_name;
						$row[] = $start_date;
						$row[] = decrypt($key->action);
						$row[] = decrypt($key->reason_of_action);
						$row[] = decrypt($key->gender);
						$row[] = decrypt($key->birthplace);
						$row[] = $date_of_birth;
						$row[] = decrypt($key->religion);
						$row[] = $marital_status;
						$row[] = $join_date;
						$row[] = decrypt($key->permanent_address);
						$row[] = decrypt($key->temporary_address);
						$row[] = decrypt($key->phone_number);
						$row[] = decrypt($key->sf_phone_number);
						$row[] = decrypt($key->personal_email);
						$row[] = decrypt($key->email);
						$row[] = decrypt($key->no_ktp);
						$row[] = decrypt($key->npwp_id);
						$row[] = decrypt($key->bpjs_ketenagakerjaan);
						$row[] = decrypt($key->bpjs_kesehatan);
						$row[] = decrypt($key->status_ptkp);
						$row[] = decrypt($key->company_code);
						$row[] = decrypt($key->company_name);
						$row[] = decrypt($key->personnel_area);
						$row[] = decrypt($key->personnel_subarea);
						$row[] = decrypt($key->employee_group);
						$row[] = decrypt($key->employee_subgroup);
						$row[] = decrypt($key->cost_center);
						$row[] = decrypt($key->bankn);
						$row[] = decrypt($key->emftx);
						$row[] = decrypt($key->bankn1);
						$row[] = decrypt($key->emftx1);
						$row[] = decrypt($key->position);
						$row[] = decrypt($key->department);
						$row[] = decrypt($key->division);
						$row[] = decrypt($key->directorate);
						$row[] = decrypt($key->superior);
						$row[] = $superior_name;
						$row[] = decrypt($key->usrid_long1);
						$row[] = decrypt($key->rpm);
						$row[] = decrypt($key->usrid_long5);
						$row[] = $rpm_name;
						$row[] = decrypt($key->department_head);
						$row[] = $depthead_name;
						$row[] = decrypt($key->usrid_long2);
						$row[] = decrypt($key->division_head);
						$row[] = $divhead_name;
						$row[] = decrypt($key->usrid_long3);
						$row[] = decrypt($key->director);
						$row[] = $director_name;
						$row[] = decrypt($key->usrid_long4);
		                // $row[] = '<div class="btn-group btn-group-sm">
		                //             <a href="' . base_url('inbox/hr_view_details/' . encode_url($key->id_employee)) . '" class="btn btn-icon btn-trigger">
		                //                 <em class="icon ni ni-eye"></em>
		                //             </a>
		                //     	</div>';

		                $data[] = $row;
		            }
		            $output = array('data' => $data);
		        } else {
		            $output = array('data' => new ArrayObject());
		        }
		        // dumper($output);
		        echo json_encode($output);
				break;
			

			case 'family_employee':

				$listF = $this->master_model->getFamilyEmployee();
		        if (!empty($listF)) {
		            foreach ($listF as $key) {

						$member_birthdate = decrypt($key->member_birthdate);
						//dumper(encrypt('01062020'));
						if($member_birthdate == ''){
							// dumper($key);
						}

						$member_birthdate = decrypt($key->member_birthdate);
						$member_birthdate = date("Y-m-d",strtotime($member_birthdate));
						$member_birthdate = DateTime::createFromFormat('Y-m-d', $member_birthdate)->format('d.m.Y');

						// // $member_birthdate = DateTime::createFromFormat('Ymd', $member_birthdate);
						// $member_birthdate = $member_birthdate->format('d.m.Y');
						// dumper($member_birthdate);

						
						//

						$member_names = str_replace("||","'", decrypt($key->member_names));
						
						if($key->status_act == "Y"){
							$checked = "checked";
						}else if($key->status_act == "N"){
							$checked = "";
						}else{
							$checked = "";
						}

		                $row   = array();
						$row[] = $key->nik;
		                $row[] = decrypt($key->family_members);
		                $row[] = decrypt($key->seqno);
						$row[] = $member_names;
						$row[] = $member_birthdate;
						$f_member	= encrypt('Spouse');
						if($key->family_members == $f_member){
							$row[] = '';
						}else{
							$row[] = '<div class="custom-control custom-switch act_child"><input type="checkbox" '.$checked.' class="custom-control-input" id="'.$key->id_family.'" data="'.$key->id_family.'" onClick="act_child('.$key->id_family.')"><label class="custom-control-label" for="'.$key->id_family.'">Active</label></div>';
						}
		                $data[] = $row;
		            }
		            $outputF = array('data' => $data);
		        } else {
		            $outputF = array('data' => new ArrayObject());
		        }
		        echo json_encode($outputF);
				break;

			case 'couple_employee':

				$listCE = $this->master_model->getCoupleEmployee();
		        if (!empty($listCE)) {
		            foreach ($listCE as $key) {

		                $row   = array();
						$row[] = $key->male_nik;
		                $row[] = decrypt($key->male_employee);
		                $row[] = $key->female_nik;
						$row[] = decrypt($key->female_employee);
						$row[] = '<div class="btn-group btn-group-sm">
										<a class="text-danger btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalDeleteCouple" data-offset="-4,0" id="'.$key->id.'" onClick="delete_couple('.$key->id.')">
		                                <em class="icon ni ni-trash"></em>
		                            	</a>
		                    	</div>';
		                $data[] = $row;
		            }
		            $outputCE = array('data' => $data);
		        } else {
		            $outputCE = array('data' => new ArrayObject());
		        }
		        echo json_encode($outputCE);
				break;

			case 'pagu_rawat_jalan':

				$listRJ = $this->master_model->getPaguRawatJalan();
				if (!empty($listRJ)) {
					foreach ($listRJ as $key) {

						$row   = array();
						$row[] = $key->start_date;
						$row[] = $key->end_date;
						$row[] = $key->grade;
						$row[] = number_format($key->pagu_tahun);
						$row[] = '<div class="btn-group btn-group-sm">
										<a class="text-primary btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalEditPaguRawatJalan" data-offset="-4,0" id="'.$key->id.'" onClick="edit_pagu_rawat_jalan('.$key->id.')">
										<em class="icon ni ni-edit"></em>
										</a>
										<a class="text-danger btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalDeletePaguRawatJalan" data-offset="-4,0" id="'.$key->id.'" onClick="delete_pagu_rawat_jalan('.$key->id.')">
		                                <em class="icon ni ni-trash"></em>
		                            	</a>
		                    	</div>
								';
						$data[] = $row;
					}
					$outputRJ = array('data' => $data);
				} else {
					$outputRJ = array('data' => new ArrayObject());
				}
				echo json_encode($outputRJ);
				break;

				case 'pagu_rawat_inap':

				$listRI = $this->master_model->getPaguRawatInap();
				if (!empty($listRI)) {
					foreach ($listRI as $key) {

						$row   = array();
						$row[] = $key->start_date;
						$row[] = $key->end_date;
						$row[] = $key->grade;
						$row[] = number_format($key->pagu_kamar_hari);
						$row[] = number_format($key->pagu_tahun);
						$row[] = '<div class="btn-group btn-group-sm">
										<a class="text-primary btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalEditPaguRawatInap" data-offset="-4,0" id="'.$key->id.'" onClick="edit_pagu_rawat_inap('.$key->id.')">
										<em class="icon ni ni-edit"></em>
										</a>
										<a class="text-danger btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalDeletePaguRawatInap" data-offset="-4,0" id="'.$key->id.'" onClick="delete_pagu_rawat_inap('.$key->id.')">
		                                <em class="icon ni ni-trash"></em>
		                            	</a>
		                    	</div>';
						$data[] = $row;
					}
					$outputRI = array('data' => $data);
				} else {
					$outputRI = array('data' => new ArrayObject());
				}
				echo json_encode($outputRI);
				break;

				case 'pagu_maternity':

					$listM = $this->master_model->getPaguMaternity();
					if (!empty($listM)) {
						foreach ($listM as $key) {
	
							$row   = array();
							$row[] = $key->start_date;
							$row[] = $key->end_date;
							$row[] = $key->melahirkan;
							$row[] = $key->grade;
							$row[] = number_format($key->pagu_tahun);
							$row[] = '<div class="btn-group btn-group-sm">
											<a class="text-primary btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalEditPaguMaternity" data-offset="-4,0" id="'.$key->id.'" onClick="edit_pagu_maternity('.$key->id.')">
											<em class="icon ni ni-edit"></em>
											</a>
											<a class="text-danger btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalDeletePaguMaternity" data-offset="-4,0" id="'.$key->id.'" onClick="delete_pagu_maternity('.$key->id.')">
											<em class="icon ni ni-trash"></em>
											</a>
									</div>';
							$data[] = $row;
						}
						$outputM = array('data' => $data);
					} else {
						$outputM = array('data' => new ArrayObject());
					}
					echo json_encode($outputM);
					break;

				case 'pagu_kacamata':

				$listK = $this->master_model->getPaguKacamata();
				if (!empty($listK)) {
					foreach ($listK as $key) {

						$row   = array();
						$row[] = $key->start_date;
						$row[] = $key->end_date;
						$row[] = $key->grade;
						$row[] = number_format($key->pagu_one_focus_tahun);
						$row[] = number_format($key->pagu_two_focus_tahun);
						$row[] = number_format($key->pagu_frame_dua_tahun);
						$row[] = '<div class="btn-group btn-group-sm">
										<a class="text-primary btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalEditPaguKacamata" data-offset="-4,0" id="'.$key->id.'" onClick="edit_pagu_kacamata('.$key->id.')">
										<em class="icon ni ni-edit"></em>
										</a>
										<a class="text-danger btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalDeletePaguKacamata" data-offset="-4,0"  id="'.$key->id.'" onClick="delete_pagu_kacamata('.$key->id.')">
		                                <em class="icon ni ni-trash"></em>
		                            	</a>
		                    	</div>';
						$data[] = $row;
					}
					$outputK = array('data' => $data);
				} else {
					$outputK = array('data' => new ArrayObject());
				}
				echo json_encode($outputK);
				break;

				case 'grandparent':

					$listGP = $this->master_model->getGrandparent();
					if (!empty($listGP)) {
						foreach ($listGP as $key) {
	
							$row   = array();
							$row[] = $key->grandparent;
							$row[] = $key->description;
							$row[] = '<div class="btn-group btn-group-sm">
											<a class="text-primary btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalEditGrandParent" data-offset="-4,0" id="'.$key->id.'" onClick="edit_grandparent('.$key->id.')">
											<em class="icon ni ni-edit"></em>
											</a>
											<a class="text-danger btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalDeleteGrandParent" data-offset="-4,0" id="'.$key->id.'" onClick="delete_grandparent('.$key->id.')">
											<em class="icon ni ni-trash"></em>
											</a>
									</div>';
							$data[] = $row;
						}
						$outputGP = array('data' => $data);
					} else {
						$outputGP = array('data' => new ArrayObject());
					}
					echo json_encode($outputGP);
					break;

				case 'parent':

					$listP = $this->master_model->getParent();
					if (!empty($listP)) {
						foreach ($listP as $key) {
	
							$row   = array();
							$row[] = $key->grandparent;
							$row[] = $key->parent;
							$row[] = $key->description;
							$row[] = '<div class="btn-group btn-group-sm">
											<a class="text-primary btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalEditParent" data-offset="-4,0" id="'.$key->id.'" onClick="edit_parent('.$key->id.')">
											<em class="icon ni ni-edit"></em>
											</a>
											<a class="text-danger btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalDeleteParent" data-offset="-4,0" id="'.$key->id.'" onClick="delete_parent('.$key->id.')">
											<em class="icon ni ni-trash"></em>
											</a>
									</div>';
							$data[] = $row;
						}
						$outputP = array('data' => $data);
					} else {
						$outputP = array('data' => new ArrayObject());
					}
					echo json_encode($outputP);
					break;
				
				case 'child':

					$listC = $this->master_model->getChild();
					if (!empty($listC)) {
						foreach ($listC as $key) {
	
							$row   = array();
							$row[] = $key->start_date;
							$row[] = $key->end_date;
							$row[] = $key->grandparent;
							$row[] = $key->parent;
							$row[] = $key->child;
							$row[] = $key->claim_percentage;
							$row[] = number_format($key->claim_value);
							$row[] = $key->description;
							$row[] = '<div class="btn-group btn-group-sm">
											<a class="text-primary btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalEditChild" data-offset="-4,0" id="'.$key->id.'" onClick="edit_child('.$key->id.')">
											<em class="icon ni ni-edit"></em>
											</a>
											<a class="text-danger btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalDeleteChild" data-offset="-4,0" id="'.$key->id.'" onClick="delete_child('.$key->id.')">
											<em class="icon ni ni-trash"></em>
											</a>
									</div>';
							$data[] = $row;
						}
						$outputC = array('data' => $data);
					} else {
						$outputC = array('data' => new ArrayObject());
					}
					echo json_encode($outputC);
					break;
				
					case 'efektifitas_kuitansi':

					$listEK = $this->master_model->getEKuitansi();
					if (!empty($listEK)) {
						foreach ($listEK as $key) {
	
							$row   = array();
							$row[] = $key->start_date;
							$row[] = $key->end_date;
							$row[] = $key->efektif_kuitansi;
							if($key->active == 1){
							$row[] = '<div class="btn-group btn-group-sm">
							<a class="text-danger btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalEKuitansi" data-offset="-4,0" id="'.$key->id.'" onClick="delete_ekuitansi('.$key->id.')">
												<em class="icon ni ni-trash"></em>
												</a>
										</div>';
										
							}else{
								$row[] = '';
							}
							$data[] = $row;
						}
						$outputEK = array('data' => $data);
					} else {
						$outputEK = array('data' => new ArrayObject());
					}
					echo json_encode($outputEK);
					break;

					case 'users':

						$listU = $this->master_model->getUsers();
						//dumper($listU);
						if (!empty($listU)) {
							foreach ($listU as $key) {
		
								$row   = array();
								$row[] = $key->id_user;
								$row[] = $key->employee_id;
								$row[] = $key->full_name;
								$row[] = $key->user_email;
								$row[] = $key->phone_number;
								$row[] = $key->user_role;
								$row[] = $key->access_level;
								$row[] = $key->verification_status;
								$row[] = '<div class="btn-group btn-group-sm">
												<a class="text-primary btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalEditUser" data-offset="-4,0">
												<em class="icon ni ni-edit"></em>
												</a>
												<a class="text-danger btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalDeleteUser" data-offset="-4,0">
												<em class="icon ni ni-trash"></em>
												</a>
										</div>';
								$data[] = $row;
							}
							$outputU = array('data' => $data);
						} else {
							$outputU = array('data' => new ArrayObject());
						}
						echo json_encode($outputU);
						break;

					case 'rpm':

						$listRPM = $this->master_model->getRPM();
						//dumper($listRPM);
						if (!empty($listRPM)) {
							foreach ($listRPM as $key) {
		
								$row   = array();
								$row[] = $key->employee_id;
								$row[] = $key->full_name;
								$row[] = $key->user_email;
								$row[] = $key->region;
								$row[] = '<div class="btn-group btn-group-sm">
												<a class="text-primary btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalEditRPM" data-offset="-4,0">
												<em class="icon ni ni-edit"></em>
												</a>
												<a class="text-danger btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalDeleteRPM" data-offset="-4,0">
												<em class="icon ni ni-trash"></em>
												</a>
										</div>';
								$data[] = $row;
							}
							$outputU = array('data' => $data);
						} else {
							$outputU = array('data' => new ArrayObject());
						}
						echo json_encode($outputU);
						break;

			default:
				break;
		}
	}

	public function tambah_pagu_rawat_jalan(){
		$start_date 	= $_POST['start_date'];
		$end_date 		= $_POST['end_date'];
		$grade 			= $_POST['grade'];
		$pagu_tahun		= $_POST['pagu_tahun'];

		$data = $this->master_model->setPaguRawatJalan($start_date, $end_date, $grade, $pagu_tahun);

		//dumper($data);
		echo json_encode($data);

	}

	public function ubah_pagu_rawat_jalan(){
		$id 			= $_POST['id'];
		$start_date 	= $_POST['start_date'];
		$end_date 		= $_POST['end_date'];
		$grade 			= $_POST['grade'];
		$pagu_tahun		= $_POST['pagu_tahun'];
		$data = $this->master_model->updatePaguRawatJalan($id, $start_date, $end_date, $grade, $pagu_tahun);
		echo json_encode($data);

	}
	
	public function tambah_pagu_rawat_inap(){
		$start_date 	= $_POST['start_date'];
		$end_date 		= $_POST['end_date'];
		$grade 			= $_POST['grade'];
		$pagu_kamar		= $_POST['pagu_kamar'];
		$pagu_tahun		= $_POST['pagu_tahun'];
		$data = $this->master_model->setPaguRawatInap($start_date, $end_date, $grade, $pagu_kamar, $pagu_tahun);
		echo json_encode($data);

	}

	public function tambah_pagu_maternity(){
		$start_date 	= $_POST['start_date'];
		$end_date 		= $_POST['end_date'];
		$melahirkan		= $_POST['melahirkan'];
		$grade 			= $_POST['grade'];
		$pagu_tahun		= $_POST['pagu_tahun'];
		$data = $this->master_model->setPaguMaternity($start_date, $end_date, $melahirkan, $grade, $pagu_tahun);
		echo json_encode($data);

	}

	public function ubah_pagu_rawat_inap(){
		$id 			= $_POST['id'];
		$start_date 	= $_POST['start_date'];
		$end_date 		= $_POST['end_date'];
		$grade 			= $_POST['grade'];
		$pagu_kamar		= $_POST['pagu_kamar'];
		$pagu_tahun		= $_POST['pagu_tahun'];
		$data = $this->master_model->updatePaguRawatInap($id, $start_date, $end_date, $grade, $pagu_kamar, $pagu_tahun);
		echo json_encode($data);

	}

	public function ubah_pagu_maternity(){
		$id 			= $_POST['id'];
		$start_date 	= $_POST['start_date'];
		$end_date 		= $_POST['end_date'];
		$melahirkan		= $_POST['melahirkan'];
		$grade 			= $_POST['grade'];
		$pagu_tahun		= $_POST['pagu_tahun'];
		$data = $this->master_model->updatePaguMaternity($id, $start_date, $end_date, $melahirkan, $grade, $pagu_tahun);
		echo json_encode($data);

	}

	public function tambah_pagu_kacamata(){
		$start_date 	= $_POST['start_date'];
		$end_date 		= $_POST['end_date'];
		$grade 			= $_POST['grade'];
		$pagu_one_focus	= $_POST['pagu_one_focus'];
		$pagu_two_focus	= $_POST['pagu_two_focus'];
		$pagu_frame		= $_POST['pagu_frame'];

		$data = $this->master_model->setPaguKacamata($start_date, $end_date, $grade, $pagu_one_focus, $pagu_two_focus, $pagu_frame);
		echo json_encode($data);

	}

	public function ubah_pagu_kacamata(){
		$id 	 		= $_POST['id'];
		$start_date 	= $_POST['start_date'];
		$end_date 		= $_POST['end_date'];
		$grade 			= $_POST['grade'];
		$pagu_one_focus	= $_POST['pagu_one_focus'];
		$pagu_two_focus	= $_POST['pagu_two_focus'];
		$pagu_frame		= $_POST['pagu_frame'];

		$data = $this->master_model->updatePaguKacamata($id, $start_date, $end_date, $grade, $pagu_one_focus, $pagu_two_focus, $pagu_frame);
		echo json_encode($data);

	}

	public function tambah_grandparent(){
		
		$grandparent 		= $_POST['grandparent_tambah'];
		$description		= $_POST['description_grandparent_tambah'];

		$data = $this->master_model->setGrandparent($grandparent, $description);

		//dumper($data);
		echo json_encode($data);

	}

	public function tambah_parent(){

		$parent_grandparent 		= $_POST['parent_grandparent_tambah'];
		$parent			 			= $_POST['parent_tambah'];
		$description_parent			= $_POST['description_parent_tambah'];

		$data = $this->master_model->setParent($parent_grandparent, $parent, $description_parent);

		//dumper($data);
		echo json_encode($data);

	}

	public function tambah_child(){

		$start_date					= $_POST['start_date'];
		$end_date					= $_POST['end_date'];
		$child_grandparent			= $_POST['child_grandparent_tambah'];
		$child_parent				= $_POST['child_parent_tambah'];
		$child						= $_POST['child_tambah'];
		$claim_percentage_child		= $_POST['claim_percentage_child_tambah'];
		$description_child			= $_POST['description_child_tambah'];

		$data = $this->master_model->setChild($start_date, $end_date, $child_grandparent, $child_parent, $child, $claim_percentage_child, $description_child);

		//dumper($data);
		echo json_encode($data);

	}
	
	public function tambah_efektifitas_kuitansi(){

		$efektif_kuitansi_tambah	= $_POST['efektif_kuitansi_tambah'];
		$start_date_tambah_efektif_kuitansi	= $_POST['start_date_tambah_efektif_kuitansi'];
		//dumper($start_date_tambah_efektif_kuitansi);

		$data = $this->master_model->tambah_efektifitas_kuitansi($efektif_kuitansi_tambah, $start_date_tambah_efektif_kuitansi);

		echo json_encode($data);

	}



	public function logs($type, $formType, $id, $activity = '', $description = '')
	{
		$log['request_id'] = $id;
		$log['form_type'] = $formType;
		$log['created_by'] = ($type == 'system') ? 'system' : $this->email;
		$log['created_at'] = $this->date;

		switch ($type) {

			case 'system':
				$log['activity'] = $activity;
				$log['description'] = $description;
				$this->db->insert('logs', $log);
				break;

			case 'add_employee':
				$log['activity'] = $activity;
				$log['description'] = $description;
				$this->db->insert('logs', $log);
				break;

			case 'add_rpm':
				$log['activity'] = $activity;
				$log['description'] = $description;
				$this->db->insert('logs', $log);
				break;

			default:
				break;
		}
	}


	public function get_Grandparent(){
		$data = $this->master_model->get_Grandparent();
		echo json_encode($data);
	}

	public function edit_get_Grandparent(){
		$id = $_POST['grandparent'];
		$data = $this->master_model->edit_get_Grandparent($id);
		echo json_encode($data);
	}

	public function edit_get_Parent(){
		$id = $_POST['parent'];
		$data = $this->master_model->edit_get_Parent($id);
		echo json_encode($data);
	}

	public function get_Parent(){
		if($this->input->post('grandparent'))
		{
			echo $this->master_model->get_Parent($this->input->post('grandparent'), $this->input->post('employee_group'));
		}
	}

	public function get_Child(){
		if($this->input->post('parent'))
		{
			echo $this->master_model->get_Child($this->input->post('parent'));
		}
	}

	public function getEditPaguRawatJalan(){
		$id = $_POST['id'];
		$data = $this->master_model->getEditPaguRawatJalan($id);
		echo json_encode($data);
	}

	public function getDeletePaguRawatJalan(){
		$id = $_POST['id'];
		$data = $this->master_model->getDeletePaguRawatJalan($id);
		echo json_encode($data);
	}

	public function getEditPaguRawatInap(){
		$id = $_POST['id'];
		$data = $this->master_model->getEditPaguRawatInap($id);
		echo json_encode($data);
	}

	public function getDeletePaguRawatInap(){
		$id = $_POST['id'];
		$data = $this->master_model->getDeletePaguRawatInap($id);
		echo json_encode($data);
	}

	public function getEditPaguMaternity(){
		$id = $_POST['id'];
		$data = $this->master_model->getEditPaguMaternity($id);
		echo json_encode($data);
	}

	public function getDeletePaguMaternity(){
		$id = $_POST['id'];
		$data = $this->master_model->getDeletePaguMaternity($id);
		echo json_encode($data);
	}

	public function getEditPaguKacamata(){
		$id = $_POST['id'];
		$data = $this->master_model->getEditPaguKacamata($id);
		echo json_encode($data);
	}

	public function getDeletePaguKacamata(){
		$id = $_POST['id'];
		$data = $this->master_model->getDeletePaguKacamata($id);
		echo json_encode($data);
	}

	public function getEditGrandparent(){
		$id = $_POST['id'];
		$data = $this->master_model->getEditGrandparent($id);
		echo json_encode($data);
	}

	public function ubah_grandparent(){
		$id = $_POST['id'];
		$grandparent = $_POST['grandparent'];
		$description = $_POST['description'];
		$data = $this->master_model->ubah_grandparent($id, $grandparent, $description);
		echo json_encode($data);
	}

	public function getDeleteGrandparent(){
		$id = $_POST['id'];
		$data = $this->master_model->getDeleteGrandparent($id);
		echo json_encode($data);
	}

	public function getEditParent(){
		$id = $_POST['id'];
		$data = $this->master_model->getEditParent($id);
		echo json_encode($data);
	}

	public function ubah_parent(){
		$id = $_POST['id'];
		$grandparent 	= $_POST['grandparent'];
		$parent 		= $_POST['parent'];
		$description 	= $_POST['description'];
		$data = $this->master_model->ubah_parent($id, $grandparent, $parent, $description);
		echo json_encode($data);
	}

	public function getDeleteParent(){
		$id = $_POST['id'];
		$data = $this->master_model->getDeleteParent($id);
		echo json_encode($data);
	}

	public function getEditChild(){
		$id = $_POST['id'];
		$data = $this->master_model->getEditChild($id);
		echo json_encode($data);
	}

	public function ubah_child(){

		$id							= $_POST['id'];
		$start_date					= $_POST['start_date'];
		$end_date					= $_POST['end_date'];
		$child_grandparent			= $_POST['child_grandparent'];
		$child_parent				= $_POST['child_parent'];
		$child						= $_POST['child'];
		$claim_percentage_child		= $_POST['claim_percentage_child'];
		$claim_value_child			= $_POST['claim_value_child'];
		$description_child			= $_POST['description_child'];

		$data = $this->master_model->ubah_child($id, $start_date, $end_date, $child_grandparent, $child_parent, $child, $claim_percentage_child, $claim_value_child, $description_child);

		//dumper($data);
		echo json_encode($data);

	}

	public function getDeleteChild(){
		$id = $_POST['id'];
		$data = $this->master_model->getDeleteChild($id);
		echo json_encode($data);
	}
	
	public function getDeleteEkuitansi(){
		$id = $_POST['id'];
		$data = $this->master_model->getDeleteEkuitansi($id);
		echo json_encode($data);
	}
	
	public function delete_couple(){
		$id = $_POST['id'];
		$data = $this->master_model->delete_couple($id);
		echo json_encode($data);
	}


	public function getEmployeeToUsers(){
		$data = $this->master_model->getEmployeeToUsers();
		echo json_encode($data);
	}

	public function get_DataEmployeeToUsers(){
		$full_name_tambah_users = $_POST['full_name_tambah_users'];
		$data = $this->master_model->get_DataEmployeeToUsers($full_name_tambah_users);
		echo json_encode($data);
	}

	public function tambah_user(){

		$nik				= $_POST['nik'];
		$complete_name		= $_POST['complete_name'];
		$email				= $_POST['email'];
		$phone_number		= $_POST['phone_number'];
		$password			= $_POST['password'];
		$role				= $_POST['role'];
		$access				= $_POST['access'];
		$verification		= $_POST['verification'];

		$data = $this->master_model->setUsers($nik, $complete_name, $email, $phone_number, $password, $role, $access, $verification);

		echo json_encode($data);

	}


	public function getMaleEmployee(){
		$data = $this->master_model->getMaleEmployee();
		echo json_encode($data);
	}

	public function getFemaleEmployee(){
		$data = $this->master_model->getFemaleEmployee();
		echo json_encode($data);
	}

	public function get_DataEmployeeMale(){
		$full_name_male_add_couple = $_POST['full_name_male_add_couple'];
		$data = $this->master_model->get_DataEmployeeMale($full_name_male_add_couple);
		echo json_encode($data);
	}

	public function get_DataEmployeeFemale(){
		$full_name_female_add_couple = $_POST['full_name_female_add_couple'];
		//dumper($full_name_female_add_couple);
		$data = $this->master_model->get_DataEmployeeFemale($full_name_female_add_couple);
		echo json_encode($data);
	}

	public function add_couple_employee(){

		$employee_id_male				= $_POST['employee_id_male'];
		$employee_id_female				= $_POST['employee_id_female'];
		$complete_name_male				= $_POST['complete_name_male'];
		$complete_name_female			= $_POST['complete_name_female'];

		$data = $this->master_model->add_couple_employee($employee_id_male, $employee_id_female, $complete_name_male, $complete_name_female);

		echo json_encode($data);

	}

	public function save_act_child(){
		$id_family				= $_POST['id'];
		$st_act					= $_POST['st_act'];
		
		$data = $this->master_model->save_act_child($id_family, $st_act);

		echo json_encode($data);

	}


	// RPM
	public function add_rpm(){

		$data = array(
				'employee_id' => $this->input->post('rpm_employee_id'),
				'full_name' => $this->input->post('rpm_full_name'),
				'user_email' => $this->input->post('rpm_email'),
				'region' => $this->input->post('rpm_region'),
				'created_at' => $this->date,
				'created_by' => $this->email);

		if ($this->db->insert('hris_rpm1', $data)) {
			$response = array('status' => 1);
		} else {
			$response = array('status' => 0);
		}

		echo json_encode($response);
	}

}
