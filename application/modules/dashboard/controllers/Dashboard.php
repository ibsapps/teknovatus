<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('curl');
		$this->load->library('enc');
		$this->enc->check_session();
		$this->email = $this->session->userdata('user_email');
		$this->division = $this->session->userdata('division');
		$this->second_division = $this->session->userdata('second_division');
        $this->emp_id = $this->session->userdata('employee_id');

        // if ($this->emp_id == '') {
        //     print_r("You are not authorized to access this apps.");
        //     exit();
        // }
        
        $this->load->helper('general');
        $this->load->model('dashboard_model');
        $this->load->model('inbox/inbox_model');
        $this->load->model('home/home_model');
        $this->load->model('form/form_model');
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
		//dumper($this->session->userdata('access_employee'));

		if (($this->session->userdata('access_employee') != '1') && ($this->session->userdata('access_employee') != '12') && ($this->session->userdata('access_employee') != '13')) {
            print_r('You are not authorized to access this page');die;
        }
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
        $data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
        $data['count_review'] = count($this->inbox_model->getReviewList());
        $data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['InfoEmployee'] = $this->dashboard_model->InfoEmployee();
        $data['formType'] = $this->form_model->getFormType();
        // dumper($data['formType']);
		//$data['content'] = 'dashboard/dashboard';
		if($this->session->userdata('access_employee') == '13'){
			$data['content'] = 'dashboard/dashboardAPFCT';
		}else{
			$data['content'] = 'dashboard/dashboardHRIS';
		}
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function pa_management()
	{
		if (($this->session->userdata('access_employee') != '3') && ($this->session->userdata('access_employee') != '4')) {
			print_r('You are not authorized to access this page');die;
		}
		$data['header'] = $this->inbox_model->getPAList();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'dashboard/list_pa_approved';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function gradeView($grade)
	{
		if (($this->session->userdata('access_employee') != '3') && ($this->session->userdata('access_employee') != '4')) {
			print_r('You are not authorized to access this page');die;
		}
		$data['fullapproved'] = $this->dashboard_model->countByStatus('3');
		$data['total_a'] = $this->dashboard_model->getTotalGradeAll('a');
		$data['total_b'] = $this->dashboard_model->getTotalGradeAll('b');
		$data['total_c'] = $this->dashboard_model->getTotalGradeAll('c');
		$data['total_d'] = $this->dashboard_model->getTotalGradeAll('d');
		$data['total_e'] = $this->dashboard_model->getTotalGradeAll('e');
		$data['totalEmployee'] = $this->dashboard_model->countEmployee();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'dashboard/gradeView';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function gradeViewC($grade)
	{
		if (($this->session->userdata('access_employee') != '3') && ($this->session->userdata('access_employee') != '4')) {
			print_r('You are not authorized to access this page');die;
		}
		$data['fullapproved'] = $this->dashboard_model->countByStatusC($this->division, '3');
		$data['revise'] = $this->dashboard_model->countByStatusC($this->division, '2');
		$data['waiting'] = $this->dashboard_model->countByStatusC($this->division, '1');

		$data['total_a'] = $this->dashboard_model->getTotalGradeAllC($this->division, 'a');
		$data['total_b'] = $this->dashboard_model->getTotalGradeAllC($this->division, 'b');
		$data['total_c'] = $this->dashboard_model->getTotalGradeAllC($this->division, 'c');
		$data['total_d'] = $this->dashboard_model->getTotalGradeAllC($this->division, 'd');
		$data['total_e'] = $this->dashboard_model->getTotalGradeAllC($this->division, 'e');
		$data['totalEmployee'] = $this->dashboard_model->countEmployeeC($this->division);

		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'dashboard/gradeViewC';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function gradeViewHr($grade)
	{
		if (($this->session->userdata('access_employee') != '11') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}
		$data['fullapproved'] = $this->dashboard_model->countByStatus('3');
		$data['total_a'] = $this->dashboard_model->getTotalGradeAll('a');
		$data['total_b'] = $this->dashboard_model->getTotalGradeAll('b');
		$data['total_c'] = $this->dashboard_model->getTotalGradeAll('c');
		$data['total_d'] = $this->dashboard_model->getTotalGradeAll('d');
		$data['total_e'] = $this->dashboard_model->getTotalGradeAll('e');
		$data['totalEmployee'] = $this->dashboard_model->countEmployee();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'dashboard/gradeViewHr';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function m()
	{
		if (($this->session->userdata('access_employee') != '4') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}
		$data['eligible'] = $this->dashboard_model->countEligible('1');
		$data['not_eligible'] = $this->dashboard_model->countEligible('0');

		$data['unsubmitted'] = $this->dashboard_model->countUnsubmitted();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['fullapproved'] = $this->dashboard_model->countByStatus('3');
		$data['revise'] = $this->dashboard_model->countByStatus('2');
		$data['waiting'] = $this->dashboard_model->countByStatus('1');
		$data['total_a'] = $this->dashboard_model->getTotalGradeAll('a');
		$data['total_b'] = $this->dashboard_model->getTotalGradeAll('b');
		$data['total_c'] = $this->dashboard_model->getTotalGradeAll('c');
		$data['total_d'] = $this->dashboard_model->getTotalGradeAll('d');
		$data['total_e'] = $this->dashboard_model->getTotalGradeAll('e');
		$data['totalEmployee'] = $this->dashboard_model->countEmployee();
		$data['totalDivision'] = $this->dashboard_model->countDivision();
		$data['totalConfirmed'] = $this->dashboard_model->countConfirmed();
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'dashboard/dashboard';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function c()
	{
		if (($this->session->userdata('access_employee') != '3') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}

		$data['eligible'] = $this->dashboard_model->countEligibleC($this->division, '1');
		$data['not_eligible'] = $this->dashboard_model->countEligibleC($this->division, '0');

		$data['unsubmitted'] = $this->dashboard_model->countUnsubmittedC($this->division);

		$data['fullapproved'] = $this->dashboard_model->countByStatusC($this->division, '3');
		$data['revise'] = $this->dashboard_model->countByStatusC($this->division, '2');
		$data['waiting'] = $this->dashboard_model->countByStatusC($this->division, '1');
		$data['total_a'] = $this->dashboard_model->getTotalGradeAllC($this->division, 'a');
		$data['total_b'] = $this->dashboard_model->getTotalGradeAllC($this->division, 'b');
		$data['total_c'] = $this->dashboard_model->getTotalGradeAllC($this->division, 'c');
		$data['total_d'] = $this->dashboard_model->getTotalGradeAllC($this->division, 'd');
		$data['total_e'] = $this->dashboard_model->getTotalGradeAllC($this->division, 'e');

		$data['totalEmployee'] = $this->dashboard_model->countEmployeeC($this->division);
		$data['totalDivision'] = $this->dashboard_model->countDivisionC($this->division);
		$data['totalConfirmed'] = $this->dashboard_model->countConfirmedC($this->division);
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'dashboard/dashboard_c';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function h()
	{
		if (($this->session->userdata('access_employee') != '11') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}
		$data['eligible'] = $this->dashboard_model->countEligible('1');
		$data['not_eligible'] = $this->dashboard_model->countEligible('0');

		$data['unsubmitted'] = $this->dashboard_model->countUnsubmitted();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['fullapproved'] = $this->dashboard_model->countByStatus('3');
		$data['revise'] = $this->dashboard_model->countByStatus('2');
		$data['waiting'] = $this->dashboard_model->countByStatus('1');
		$data['total_a'] = $this->dashboard_model->getTotalGradeAll('a');
		$data['total_b'] = $this->dashboard_model->getTotalGradeAll('b');
		$data['total_c'] = $this->dashboard_model->getTotalGradeAll('c');
		$data['total_d'] = $this->dashboard_model->getTotalGradeAll('d');
		$data['total_e'] = $this->dashboard_model->getTotalGradeAll('e');
		$data['totalEmployee'] = $this->dashboard_model->countEmployee();
		$data['totalDivision'] = $this->dashboard_model->countDivision();
		$data['totalConfirmed'] = $this->dashboard_model->countConfirmed();
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'dashboard/dashboard_hr';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function read_data_employee($table)
	{
		switch ($table) {

			case 'family_employee':

				$listF = $this->dashboard_model->getFamilyEmployee();
		        if (!empty($listF)) {
		            foreach ($listF as $key) {

						$member_birthdate = decrypt($key->member_birthdate);
						$member_birthdate = DateTime::createFromFormat('Ymd', $member_birthdate);
						$member_birthdate = $member_birthdate->format('d.m.Y');

						$member_names = str_replace("||","'", decrypt($key->member_names));

		                $row   = array();
		                $row[] = decrypt($key->family_members);
		                $row[] = decrypt($key->seqno);
						$row[] = $member_names;
						$row[] = decrypt($key->member_gender);
						$row[] = decrypt($key->member_birthplace);
						$row[] = $member_birthdate;
		                $data[] = $row;
		            }
		            $outputF = array('data' => $data);
		        } else {
		            $outputF = array('data' => new ArrayObject());
		        }
		        echo json_encode($outputF);
				break;

			default:
				break;
		}
	}

	public function read($type)
    {
    	$year = $this->year - 1;
		$eval_year = $year.'-01-01';

    	switch ($type) {

    		case 'all':
    			$listForm = $this->inbox_model->getAllDataPA($eval_year);
    			break;

    		case 'a':
    			$listForm = $this->dashboard_model->getAllbyGrade($eval_year, 'a');
    			break;

    		case 'b':
    			$listForm = $this->dashboard_model->getAllbyGrade($eval_year, 'b');
    			break;

    		case 'c':
    			$listForm = $this->dashboard_model->getAllbyGrade($eval_year, 'c');
    			break;

    		case 'd':
    			$listForm = $this->dashboard_model->getAllbyGrade($eval_year, 'd');
    			break;

    		case 'e':
    			$listForm = $this->dashboard_model->getAllbyGrade($eval_year, 'e');
    			break;
    		
    		default:
    			break;
    	}

    	if (!empty($listForm)) {
            foreach ($listForm as $key) {
                $row   = array();
                $row[] = $key->employee_nik;
                $row[] = $key->employee_name;
                $row[] = $key->division;
                $row[] = $key->departement;
                $row[] = $key->position;
                $row[] = status_text($key->is_status);
                $row[] = $key->direct_manager;
                $row[] = $key->office_location;
                $row[] = $key->join_date;
                $row[] = $key->employment_status;
                $row[] = $key->final_score;
                $row[] = grade_pa($key->final_score);
                $row[] = $key->full_approved_date;
                $row[] = $key->request_number;

                if ($key->is_status == 3) {
	            	$row[] = '<div class="btn-group btn-group-sm">
	                            <a class="btn btn-icon btn-trigger" id="'.$key->id.'" onclick="return quickViewDashboard(this.id)">
	                                <em class="icon ni ni-edit"></em>
	                            </a>
	                    	</div>';
                } else {
					$row[] = '';                	
                }

                $data[] = $row;
            }
            $output = array('data' => $data);
        } else {
            $output = array('data' => new ArrayObject());
        }

        echo json_encode($output);
    }

    public function read_c($type)
    {
    	$year = $this->year - 1;
		$eval_year = $year.'-01-01';

    	switch ($type) {

    		case 'all':
    			$listForm = $this->dashboard_model->getAllDataPAC($this->division, $eval_year);
    			break;

    		case 'a':
    			$listForm = $this->dashboard_model->getAllbyGradeC($this->division, $eval_year, 'a');
    			break;

    		case 'b':
    			$listForm = $this->dashboard_model->getAllbyGradeC($this->division, $eval_year, 'b');
    			break;

    		case 'c':
    			$listForm = $this->dashboard_model->getAllbyGradeC($this->division, $eval_year, 'c');
    			break;

    		case 'd':
    			$listForm = $this->dashboard_model->getAllbyGradeC($this->division, $eval_year, 'd');
    			break;

    		case 'e':
    			$listForm = $this->dashboard_model->getAllbyGradeC($this->division, $eval_year, 'e');
    			break;
    		
    		default:
    			break;
    	}

    	if (!empty($listForm)) {
            foreach ($listForm as $key) {
                $row   = array();
                $row[] = $key->employee_nik;
                $row[] = $key->employee_name;
                $row[] = $key->division;
                $row[] = $key->departement;
                $row[] = $key->position;
                $row[] = status_text($key->is_status);
                $row[] = $key->direct_manager;
                $row[] = $key->office_location;
                $row[] = $key->join_date;
                $row[] = $key->employment_status;
                $row[] = $key->final_score;
                $row[] = grade_pa($key->final_score);
                $row[] = $key->full_approved_date;
                $row[] = $key->request_number;
                if ($key->is_status == 3) {
	            	$row[] = '<div class="btn-group btn-group-sm">
	                            <a class="btn btn-icon btn-trigger" id="'.$key->id.'" onclick="return quickViewDashboard(this.id)">
	                                <em class="icon ni ni-edit"></em>
	                            </a>
	                    	</div>';
                } else {
					$row[] = '';                	
                }

                $data[] = $row;
            }
            $output = array('data' => $data);
        } else {
            $output = array('data' => new ArrayObject());
        }

        echo json_encode($output);
    }

    public function read_hr($type)
    {
    	$year = $this->year - 1;
		$eval_year = $year.'-01-01';

    	switch ($type) {

    		case 'all':
    			$listForm = $this->inbox_model->getAllDataPA($eval_year);
    			break;

    		case 'a':
    			$listForm = $this->dashboard_model->getAllbyGrade($eval_year, 'a');
    			break;

    		case 'b':
    			$listForm = $this->dashboard_model->getAllbyGrade($eval_year, 'b');
    			break;

    		case 'c':
    			$listForm = $this->dashboard_model->getAllbyGrade($eval_year, 'c');
    			break;

    		case 'd':
    			$listForm = $this->dashboard_model->getAllbyGrade($eval_year, 'd');
    			break;

    		case 'e':
    			$listForm = $this->dashboard_model->getAllbyGrade($eval_year, 'e');
    			break;
    		
    		default:
    			break;
    	}

    	if (!empty($listForm)) {
            foreach ($listForm as $key) {
                $row   = array();
                $row[] = $key->employee_nik;
                $row[] = $key->employee_name;
                $row[] = $key->division;
                $row[] = $key->departement;
                $row[] = $key->position;
                $row[] = status_text($key->is_status);
                $row[] = $key->direct_manager;
                $row[] = $key->office_location;
                $row[] = $key->join_date;
                $row[] = $key->employment_status;
                $row[] = $key->final_score;
                $row[] = grade_pa($key->final_score);
                $row[] = $key->full_approved_date;
                $row[] = $key->request_number;
                $row[] = ''; 

                $data[] = $row;
            }
            $output = array('data' => $data);
        } else {
            $output = array('data' => new ArrayObject());
        }

        echo json_encode($output);
    }

    public function mgmt()
	{
		if (($this->session->userdata('access_employee') != '4') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}
		
		$year = $this->year - 1;
		$eval_year = $year.'-01-01';

		$data['division_status'] = $this->db->get_where('performance_division_status', array('division_name' => $this->division, 'evaluation_period' => $eval_year))->row_array();

		if ($this->session->userdata('second_division') != '') {

			$data['second_division_status'] = $this->db->get_where('performance_division_status', array('division_name' => $this->second_division, 'evaluation_period' => $eval_year))->row_array();
			
			$data['second_total_team'] = $this->inbox_model->countTeam($this->second_division);
			$data['second_total_inprogress'] = $this->inbox_model->countRequest($this->second_division, '1');
			$data['second_total_approved'] = $this->inbox_model->countRequest($this->second_division, '3');
			$data['second_total_revise'] = $this->inbox_model->countRequest($this->second_division, '2');
			$data['second_total_a'] = $this->inbox_model->getTotalGrade($this->second_division, 'a');
			$data['second_total_b'] = $this->inbox_model->getTotalGrade($this->second_division, 'b');
			$data['second_total_c'] = $this->inbox_model->getTotalGrade($this->second_division, 'c');
			$data['second_total_d'] = $this->inbox_model->getTotalGrade($this->second_division, 'd');
			$data['second_total_e'] = $this->inbox_model->getTotalGrade($this->second_division, 'e');
			$content = 'dashboard/pa_multi_division';
			
		} else {
			$content = 'dashboard/pa_management';
		}


		// c level
		$data['total_team'] = $this->dashboard_model->countTeam();
		$data['total_inprogress'] = $this->dashboard_model->countRequest('1');
		$data['total_approved'] = $this->dashboard_model->countRequest('3');
		$data['total_revise'] = $this->dashboard_model->countRequest('2');
		$data['total_a'] = $this->dashboard_model->getTotalGrade('a');
		$data['total_b'] = $this->dashboard_model->getTotalGrade('b');
		$data['total_c'] = $this->dashboard_model->getTotalGrade('c');
		$data['total_d'] = $this->dashboard_model->getTotalGrade('d');
		$data['total_e'] = $this->dashboard_model->getTotalGrade('e');
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = $content;
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function read_by_division($div)
    {
    	$year = $this->year - 1;
		$eval_year = $year.'-01-01';

		$division = str_replace('%20', ' ', $div);
		$listForm = $this->dashboard_model->getDivHeadListByDivision($division);
		
		$second_division_status = $this->db->get_where('performance_division_status', array('division_name' => $division, 'evaluation_period' => $eval_year))->row_array()['is_status'];
       
        if (!empty($listForm)) {
            foreach ($listForm as $key) {

            	$count_layer = $this->db->get_where('form_approval', array('request_id' => $key->id))->num_rows();
            	$approval_priority = $this->db->get_where('form_approval', array('request_id' => $key->id, 'approval_email' => $this->email))->row_array()['approval_priority'];

            	$show = ($count_layer == $approval_priority) ? '' : 'none';

                $row  = array();
                $row[] = $key->employee_nik;
                $row[] = $key->employee_name;
                $row[] = $key->division;
                $row[] = $key->departement;
                $row[] = $key->position;
                $row[] = $key->direct_manager;
                $row[] = $key->office_location;
                $row[] = $key->join_date;
                $row[] = $key->employment_status;
                $row[] = $key->final_score;
                $row[] = grade_pa($key->final_score);
                $row[] = status_text($key->is_status);
                $row[] = $key->full_approved_date;
                $row[] = $key->request_number;
                $row[] = '<div class="btn-group btn-group-sm">
                            <a class="btn btn-icon btn-trigger" id="'.$key->id.'" onclick="return quickView(this.id)">
                                <em class="icon ni ni-edit"></em>
                            </a>
                    	</div>';

                // if ($second_division_status == 1 || $second_division_status == 3) {
                // 	$row[] = '';
                // } else {
                // 	$row[] = '<div class="btn-group btn-group-sm" style="display:'.$show.'">
                //             <a class="btn btn-icon btn-trigger" id="'.$key->id.'" onclick="return quickView(this.id)">
                //                 <em class="icon ni ni-edit"></em>
                //             </a>
                //     	</div>';
                // }

                $data[] = $row;
            }
            $output = array('data' => $data);
        } else {
            $output = array('data' => new ArrayObject());
        }

        echo json_encode($output);
    }

	public function viewSummary($type)
	{
		$status = $this->input->post('status');

		switch ($type) {
			case 'division':
				$data = $this->db->get_where('performance_appraisal', array('division_root' => '1', 'is_status' => $status))->result_array();
				break;

			case 'second_division':
				$data = $this->db->get_where('performance_appraisal', array('division' => $this->second_division, 'is_status' => $status))->result_array();
				break;
			
			default:
				# code...
				break;
		}
		
		$output = array('data' => $data);
		echo json_encode($output);
	}

	public function m_division()
	{
		if (($this->session->userdata('access_employee') != '4') && ($this->session->userdata('access_employee') != '11') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}
		
		$data['totalDivision'] = $this->dashboard_model->countDivision();
		$data['hr_confirm'] = $this->dashboard_model->countDivisionM('3');
		$data['revise'] = $this->dashboard_model->countDivisionM('2');
		$data['submitted'] = $this->dashboard_model->countDivisionM('1');
		$data['draft'] = $this->dashboard_model->countDivisionM('0');
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'dashboard/list_division_m';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function c_division()
	{
		if (($this->session->userdata('access_employee') != '3') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}

		$year = $this->year - 1;
		$eval_year = $year.'-01-01';
		$this->db->where("evaluation_period", $eval_year);

		if ($this->division == 'Finance') {
			$this->db->where("division_name IN ('Finance & Accounting', 'Procurement')");
		} else if ($this->division == 'Technology') {
			$this->db->where("division_name IN ('Engineering', 'Master Planning', 'Product Development', 'Transmission Development')");
		} else if ($this->division == 'Assets') {
			$this->db->where("division_name IN ('Assets Management','Tower Operation & Property Management','NOC & Helpdesk','Strategic Acquisition','Property Management Support')");
		} else if ($this->division == 'Operations') {
			$this->db->where("division_name IN ('Regional Central')");
		}
		$data['division'] = $this->db->get('performance_division_status')->result_array();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'dashboard/list_division';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function m_view($division)
	{
		if (($this->session->userdata('access_employee') != '4') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}

		$year = $this->year - 1;
		$eval_year = $year.'-01-01';

		$division_name = str_replace(array('%20', '-'), array(' ', '&'), $division);

		$data['division_status'] = $this->db->get_where('performance_division_status', array('division_name' => $division_name, 'evaluation_period' => $eval_year))->row_array();

		$data['total_team'] = $this->inbox_model->countTeam($division_name);
		$data['total_approved'] = $this->inbox_model->countRequest($division_name, '3');
		$data['total_inprogress'] = $this->inbox_model->countRequest($division_name, '1');
		$data['total_revise'] = $this->inbox_model->countRequest($division_name, '2');
		$data['total_a'] = $this->inbox_model->getTotalGrade($division_name, 'a');
		$data['total_b'] = $this->inbox_model->getTotalGrade($division_name, 'b');
		$data['total_c'] = $this->inbox_model->getTotalGrade($division_name, 'c');
		$data['total_d'] = $this->inbox_model->getTotalGrade($division_name, 'd');
		$data['total_e'] = $this->inbox_model->getTotalGrade($division_name, 'e');
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'dashboard/detail_division';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function c_view($division)
	{
		if (($this->session->userdata('access_employee') != '3') && ($this->session->userdata('access_employee') != '4') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}

		$year = $this->year - 1;
		$eval_year = $year.'-01-01';

		$division_name = str_replace(array('%20', '-'), array(' ', '&'), $division);
		$data['division_status'] = $this->db->get_where('performance_division_status', array('division_name' => $division_name, 'evaluation_period' => $eval_year))->row_array();

		$data['total_team'] = $this->inbox_model->countTeam($division_name);
		$data['total_approved'] = $this->inbox_model->countRequest($division_name, '3');
		$data['total_inprogress'] = $this->inbox_model->countRequest($division_name, '1');
		$data['total_revise'] = $this->inbox_model->countRequest($division_name, '2');
		$data['total_a'] = $this->inbox_model->getTotalGrade($division_name, 'a');
		$data['total_b'] = $this->inbox_model->getTotalGrade($division_name, 'b');
		$data['total_c'] = $this->inbox_model->getTotalGrade($division_name, 'c');
		$data['total_d'] = $this->inbox_model->getTotalGrade($division_name, 'd');
		$data['total_e'] = $this->inbox_model->getTotalGrade($division_name, 'e');
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'dashboard/detail_division';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function read_c_division($division)
    {
    	$year = $this->year - 1;
		$eval_year = $year.'-01-01';

		$division_name = str_replace(array('%20', '-'), array(' ', '&'), $division);

		$listForm = $this->inbox_model->getHRReview($division_name);
        if (!empty($listForm)) {
            foreach ($listForm as $key) {
                $row   = array();
                $row[] = $key->employee_nik;
                $row[] = $key->employee_name;
                $row[] = $key->division;
                $row[] = $key->departement;
                $row[] = $key->position;
                $row[] = $key->direct_manager;
                $row[] = $key->office_location;
                $row[] = $key->join_date;
                $row[] = $key->employment_status;
                $row[] = $key->final_score;
                $row[] = grade_pa($key->final_score);
                $row[] = status_text($key->is_status);
                $row[] = $key->full_approved_date;
                $row[] = $key->request_number;
            	if ($key->is_status == 3) {
	            	$row[] = '<div class="btn-group btn-group-sm">
	                            <a class="btn btn-icon btn-trigger" id="'.$key->id.'" onclick="return quickViewDashboard(this.id)">
	                                <em class="icon ni ni-edit"></em>
	                            </a>
	                    	</div>';
                } else {
					$row[] = '';                	
                }

                $data[] = $row;
            }
            $output = array('data' => $data);
        } else {
            $output = array('data' => new ArrayObject());
        }

        echo json_encode($output);
    }

    public function read_all_division()
    {
    	$year = $this->year - 1;
		$eval_year = $year.'-01-01';

		$listForm = $this->db->get('performance_division_status')->result();
        if (!empty($listForm)) {
            foreach ($listForm as $key) {
                $row   = array();
                $row[] = $key->division_name;
                $row[] = $key->updated_by;
                $row[] = status_division($key->is_status);
                $row[] = $key->response_by;
                $row[] = $key->response_at;
            	$row[] = '<div class="btn-group btn-group-sm">
                            <a href="' . base_url('dashboard/m_view/' . str_replace(array('%20', '&'), array(' ', '-'), $key->division_name)) . '" class="btn btn-icon btn-trigger">
                                <em class="icon ni ni-eye"></em>
                            </a>
                    	</div>';

                $data[] = $row;
            }
            $output = array('data' => $data);
        } else {
            $output = array('data' => new ArrayObject());
        }

        echo json_encode($output);
    }

	public function viewDivision()
	{

		if ($this->division == 'Finance') {
			$this->db->where("division_name IN ('Finance & Accounting', 'Procurement')");

		} else if ($this->division == 'Technology') {
			$this->db->where("division_name IN ('Engineering', 'Master Planning', 'Product Development', 'Transmission Development')");

		} else if ($this->division == 'Assets') {
			$this->db->where("division_name IN ('Assets Management','Tower Operation & Property Management','NOC & Helpdesk','Strategic Acquisition','Property Management Support')");

		} else if ($this->division == 'Operations') {
			$this->db->where("division_name IN ('Regional Central')");
		}

		$data = $this->db->get('performance_division_status')->result_array();
		$output = array('data' => $data);
		echo json_encode($output);
	}

	public function viewDivision_M()
	{
		$data = $this->db->get('performance_division_status')->result_array();
		$output = array('data' => $data);
		echo json_encode($output);
	}

	public function viewNotSubmitted($type)
	{
		switch ($type) {

			case 'all_submission':

				$sql = "SELECT a.employee_name, a.eligible_status, a.division, a.position, b.is_status
							FROM employee a
							LEFT JOIN performance_appraisal b ON a.employee_name = b.employee_name
							WHERE a.employee_name != 'TIS' AND a.employee_name NOT IN
							(
							    SELECT employee_name FROM
							    (
							        SELECT employee_name
									FROM performance_appraisal
									WHERE is_status IN ('1','2','3')
							    ) AS subquery
							)";

				$data = $this->db->query($sql)->result_array();
				$output = array('data' => $data);
				break;

			case 'Assets':

				$sql = "SELECT
							a.employee_name, a.eligible_status, a.division, a.position, b.is_status
						FROM
							employee a
						LEFT JOIN performance_appraisal b ON a.employee_name = b.employee_name
						WHERE
							a.employee_name != 'TIS'
						AND a.division IN ('Assets', 'Assets Management', 'Tower Operation & Property Management', 'NOC & Helpdesk', 'Strategic Acquisition','Property Management Support')
						AND a.employee_name NOT IN (
							SELECT
								employee_name
							FROM
								(
									SELECT
										employee_name
									FROM
										performance_appraisal
									WHERE
										is_status IN ('1', '2', '3')
								) AS subquery
						)";

				$data = $this->db->query($sql)->result_array();
				$output = array('data' => $data);
				break;

			case 'Finance':

				$sql = "SELECT
							a.employee_name, a.eligible_status, a.division, a.position, b.is_status
						FROM
							employee a
						LEFT JOIN performance_appraisal b ON a.employee_name = b.employee_name
						WHERE
							a.employee_name != 'TIS'
						AND a.division IN ('Finance', 'Finance & Accounting', 'Procurement')
						AND a.employee_name NOT IN (
							SELECT
								employee_name
							FROM
								(
									SELECT
										employee_name
									FROM
										performance_appraisal
									WHERE
										is_status IN ('1', '2', '3')
								) AS subquery
						)";

				$data = $this->db->query($sql)->result_array();
				$output = array('data' => $data);
				break;

			case 'Operations':

				$sql = "SELECT
							a.employee_name, a.eligible_status, a.division, a.position, b.is_status
						FROM
							employee a
						LEFT JOIN performance_appraisal b ON a.employee_name = b.employee_name
						WHERE
							a.employee_name != 'TIS'
						AND a.division IN ('Operations', 'Regional Central')
						AND a.employee_name NOT IN (
							SELECT
								employee_name
							FROM
								(
									SELECT
										employee_name
									FROM
										performance_appraisal
									WHERE
										is_status IN ('1', '2', '3')
								) AS subquery
						)";

				$data = $this->db->query($sql)->result_array();
				$output = array('data' => $data);
				break;

			case 'Technology':

				$sql = "SELECT
							a.employee_name, a.eligible_status, a.division, a.position, b.is_status
						FROM
							employee a
						LEFT JOIN performance_appraisal b ON a.employee_name = b.employee_name
						WHERE
							a.employee_name != 'TIS'
						AND a.division IN ('Technology','Engineering', 'Master Planning', 'Product Development', 'Transmission Development')
						AND a.employee_name NOT IN (
							SELECT
								employee_name
							FROM
								(
									SELECT
										employee_name
									FROM
										performance_appraisal
									WHERE
										is_status IN ('1', '2', '3')
								) AS subquery
						)";

				$data = $this->db->query($sql)->result_array();
				$output = array('data' => $data);
				break;

			
			default:
				break;
		}
		
		echo json_encode($output);
	}

}
