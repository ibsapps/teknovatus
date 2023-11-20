<?php
defined('BASEPATH') or exit('No direct script access allowed');
	require '/var/www/html/application/vendor/phpmailer/phpmailer/src/Exception.php';
	require '/var/www/html/application/vendor/phpmailer/phpmailer/src/PHPMailer.php';
	require '/var/www/html/application/vendor/phpmailer/phpmailer/src/SMTP.php';

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

class Inbox extends Admin_Controller
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

		if ($this->emp_id == '') {
			print_r("You are not authorized to access this apps.");
			exit();
		}
		
		$this->load->helper('general');
		$this->load->model('inbox_model');
		$this->load->model('form/form_model');
		$this->load->model('home/home_model');
		$this->load->model('m_global');
		$this->date = date('Y-m-d H:i:s');
		$this->year = date('Y');
	}

	public function index()
	{
		print_r('Please go back to ibsapps.');die;
	}

	public function approval()
	{
		$data['header'] = $this->inbox_model->getApprovalList();
		//dumper($data['header']);
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'inbox/list_approval';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}
	
	public function approval_mdcr()
	{
		$data['header'] = $this->inbox_model->getApprovalList();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['header_mdcr_cek'] = $this->inbox_model->getApprovalListMDCRCek();
		// dumper($data['header_mdcr_cek']);
		$data['header_mdcr_after_cek'] = $this->inbox_model->getApprovalListMDCRAfterCek();
		$data['header_mdcr_after_grouping'] = $this->inbox_model->getApprovalListMDCRAfterGrouping();
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['header_mdcr_revised'] = $this->inbox_model->getApprovalListMDCRRevised();
		$data['header_mdcr_approved'] = $this->inbox_model->getApprovalListMDCRApproved();
		// dumper($data['header_mdcr_approved']);
		$data['header_mdcr_rejected'] = $this->inbox_model->getApprovalListMDCRRejected();
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'inbox/list_approval_mdcr';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}
	
	public function approval_mdcr_to_fi()
	{
		$data['header'] = $this->inbox_model->getApprovalList();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['header_mdcr_cek'] = $this->inbox_model->getApprovalListMDCRCek();
		$data['header_mdcr_after_cek'] = $this->inbox_model->getApprovalListMDCRAfterCek();
		$data['header_mdcr_after_grouping'] = $this->inbox_model->getApprovalListMDCRAfterGrouping();
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['header_mdcr_revised'] = $this->inbox_model->getApprovalListMDCRRevised();
		$data['header_mdcr_approved'] = $this->inbox_model->getApprovalListMDCRApproved();
		$data['header_mdcr_rejected'] = $this->inbox_model->getApprovalListMDCRRejected();
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'inbox/list_approval_mdcr_to_fi';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function pa_management()
	{
		if (($this->session->userdata('access_employee') != '2') && ($this->session->userdata('access_employee') != '3') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}
		$data['header'] = $this->inbox_model->getPAList();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'inbox/list_pa_approved';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function mgmt()
	{
		if (($this->session->userdata('access_employee') != '2') && ($this->session->userdata('access_employee') != '3') && ($this->session->userdata('access_employee') != '4') && ($this->session->userdata('access_employee') != '99')) {
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
			$content = 'inbox/pa_multi_division';
			
		} else {
			$content = 'inbox/pa_management';
		}

		$data['total_team'] = $this->inbox_model->countTeam($this->division);
		$data['total_inprogress'] = $this->inbox_model->countRequest($this->division, '1');
		$data['total_approved'] = $this->inbox_model->countRequest($this->division, '3');
		$data['total_revise'] = $this->inbox_model->countRequest($this->division, '2');
		$data['total_a'] = $this->inbox_model->getTotalGrade($this->division, 'a');
		$data['total_b'] = $this->inbox_model->getTotalGrade($this->division, 'b');
		$data['total_c'] = $this->inbox_model->getTotalGrade($this->division, 'c');
		$data['total_d'] = $this->inbox_model->getTotalGrade($this->division, 'd');
		$data['total_e'] = $this->inbox_model->getTotalGrade($this->division, 'e');
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

	public function hr_confirmed()
	{
		if (($this->session->userdata('access_employee') != '11') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'inbox/hr_confirmed';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function review()
	{
		$data['header'] = $this->inbox_model->getReviewList();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'inbox/list_approval';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function read()
    {
    	$year = $this->year - 1;
		$eval_year = $year.'-01-01';

		$listForm = $this->inbox_model->getDivHeadListByDivision($this->session->userdata('division'));

		$division_status = $this->db->get_where('performance_division_status', array('division_name' => $this->session->userdata('division'), 'evaluation_period' => $eval_year))->row_array()['is_status'];
       
        if (!empty($listForm)) {
            foreach ($listForm as $key) {

            	$count_layer = $this->db->get_where('form_approval', array('request_id' => $key->id))->num_rows();
            	$approval_priority = $this->db->get_where('form_approval', array('request_id' => $key->id, 'approval_email' => $this->email))->row_array()['approval_priority'];

            	$show = ($count_layer == $approval_priority) ? '' : 'none';

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

                if ($division_status == 1 || $division_status == 3) {
                	$row[] = '';
                } else {
                	$row[] = '<div class="btn-group btn-group-sm" style="display:'.$show.'">
                            <a class="btn btn-icon btn-trigger" id="'.$key->id.'" onclick="return quickView(this.id)">
                                <em class="icon ni ni-edit"></em>
                            </a>
                    	</div>';
                }

                $data[] = $row;
            }
            $output = array('data' => $data);
        } else {
            $output = array('data' => new ArrayObject());
        }

        echo json_encode($output);
    }

    public function read_by_division($div)
    {
    	$year = $this->year - 1;
		$eval_year = $year.'-01-01';

		$division = str_replace('%20', ' ', $div);
		$listForm = $this->inbox_model->getDivHeadListByDivision($division);
		
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
                // $row[] = $count_layer;

                if ($second_division_status == 1 || $second_division_status == 3) {
                	$row[] = '';
                } else {
                	$row[] = '<div class="btn-group btn-group-sm" style="display:'.$show.'">
                            <a class="btn btn-icon btn-trigger" id="'.$key->id.'" onclick="return quickView(this.id)">
                                <em class="icon ni ni-edit"></em>
                            </a>
                    	</div>';
                }

                $data[] = $row;
            }
            $output = array('data' => $data);
        } else {
            $output = array('data' => new ArrayObject());
        }

        echo json_encode($output);
    }

    public function hr_view_details($id)
	{
		$request_id = decode_url($id);
		$data['header'] = $this->m_global->find('id', $request_id, 'performance_appraisal')->row_array();
		$data['detail'] = $this->m_global->find('request_id', $request_id, 'performance_appraisal_measurement')->result_array();
		$data['additional'] = $this->m_global->find('request_id', $request_id, 'performance_appraisal_plan')->result_array();
		$data['employee'] = $this->m_global->find('id', $data['header']['employee_id'], 'employee')->row_array();
		$data['notes']   = $this->m_global->find('request_id', $request_id, 'request_notes')->result_array();
		$data['approval'] = $this->m_global->find('request_id', $request_id, 'form_approval')->result_array();
		$data['approval_id'] = $this->inbox_model->find_select("id", array('request_id' => $request_id, 'approval_email' => $this->email), 'form_approval')->row_array();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'inbox/form/hr_view_details';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

    public function read_hr_confirmed()
    {
    	//$year = $this->year - 1;
		//$eval_year = $year.'-01-01';
		$eval_year = '2020-01-01';

		$listForm = $this->inbox_model->getHRConfirmed();
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
                $row[] = $key->area_improvement;
                $row[] = $key->development_plan;
                $row[] = '<div class="btn-group btn-group-sm">
                            <a href="' . base_url('inbox/hr_view_details/' . encode_url($key->id)) . '" class="btn btn-icon btn-trigger">
                                <em class="icon ni ni-eye"></em>
                            </a>
                    	</div> <div class="btn-group btn-group-sm">
                            <a target="_blank" href="' . site_url('services/generate/result_document/kpi/'.encode_url($key->id)).'/'.$key->request_number.'" class="btn btn-icon btn-trigger"><em class="icon ni ni-printer"></em>
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

    public function read_hr_review($division)
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
            	$row[] = '';

                $data[] = $row;
            }
            $output = array('data' => $data);
        } else {
            $output = array('data' => new ArrayObject());
        }

        echo json_encode($output);
    }

	public function view($id)
	{
		$request_id = decode_url($id);
		$data['header'] = $this->m_global->find('id', $request_id, 'performance_appraisal')->row_array();
		$data['detail'] = $this->m_global->find('request_id', $request_id, 'performance_appraisal_measurement')->result_array();
		$data['additional'] = $this->m_global->find('request_id', $request_id, 'performance_appraisal_plan')->result_array();
		$data['employee'] = $this->m_global->find('id', $data['header']['employee_id'], 'employee')->row_array();
		$data['notes']   = $this->m_global->find('request_id', $request_id, 'request_notes')->result_array();
		$data['approval'] = $this->m_global->find('request_id', $request_id, 'form_approval')->result_array();
		$data['approval_id'] = $this->inbox_model->find_select("id", array('request_id' => $request_id, 'approval_email' => $this->email), 'form_approval')->row_array();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'inbox/form/details';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function hrd()
	{
		if (($this->session->userdata('access_employee') != '11') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}
		$data['header'] = $this->inbox_model->getPAList();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'inbox/hr_summary';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function hr_division()
	{
		if (($this->session->userdata('access_employee') != '11') && ($this->session->userdata('access_employee') != '99')) {
			print_r('You are not authorized to access this page');die;
		}

		$year = $this->year - 1;
		$eval_year = $year.'-01-01';
		$where = "is_status = 1 OR is_status = 3";
		$this->db->where($where);
		$this->db->where("evaluation_period", $eval_year);
		$data['division'] = $this->db->get('performance_division_status')->result_array();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'inbox/list_division';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function hr_view($division)
	{
		if (($this->session->userdata('access_employee') != '11') && ($this->session->userdata('access_employee') != '99')) {
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
		$data['content'] = 'inbox/form/details_division';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function quickView()
	{
		$id = $this->input->post('id');
		$data = $this->m_global->find('id', $id, 'performance_appraisal')->result_array();
		echo json_encode($data);
	}

	public function viewSummary($type)
	{
		$status = $this->input->post('status');

		switch ($type) {
			case 'division':
				$data = $this->db->get_where('performance_appraisal', array('division' => $this->division, 'is_status' => $status))->result_array();
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

	public function responseRequest()
	{
		$output = array('status' => 0, 'message' => 'Something went wrong. Please refresh and try again.');
		
		$request_id = $this->input->post('id');
		
		
		$sql = "select id from form_request where id='$request_id' and is_status_admin_hr='1'";
		$query = $this->db->query($sql);
		$res = $query->result();
		$request_id_form 	= (!empty(($res[0]->id))) ? ($res[0]->id) : 0;
		// dumper($request_id_form);
		if($request_id_form == 0){
			// dumper('Test1');
			$sql = "select id from form_approval where request_id='$request_id' and approval_status='In Progress'";
			$query = $this->db->query($sql);
			$res = $query->result();
			$approval_id = $res[0]->id;
		}else{
			$sql = "select id from form_approval where request_id='$request_id' and approval_status='Approved' and approval_email='hr.support@ibsmulti.com'";
			$query = $this->db->query($sql);
			$res = $query->result();
			// dumper($res);
			if ($res == null || $res == '') {
				$approval_id = '';
			} else {
				$approval_id = $res[0]->id;
			}
		}
		$response = $this->input->post('resp');

		// previous layer
		$priority = $this->m_global->find('id', $approval_id, 'form_approval')->row_array()['approval_priority'];
		$prev_priority = $priority-1;
		$prev_id = $this->inbox_model->find_select("id",array('approval_priority'=>$prev_priority,'request_id'=>$request_id),'form_approval')->row_array();

		$prev_email = $this->inbox_model->find_select("approval_email",array('approval_priority'=>$prev_priority,'request_id'=>$request_id),'form_approval')->row_array();

		$data_prev_layer = array(
			'approval_status' => 'In Progress', 
			'updated_at' => $this->date, 
			'updated_by' => $this->email
		);

		switch ($response) {

			// case 'Revised':

			// 	if ($priority != 1) {

			// 		if($request_id_form == 0){
			// 			$current_layer = array(
			// 				'approval_status' => 'Revised to previous layer', 
			// 				'updated_at' => $this->date, 
			// 				'updated_by' => $this->email
			// 			);

			// 			$this->db->where('id', $approval_id);
			// 			if ($this->db->update('form_approval', $current_layer)) {

			// 				$this->db->where('id', $prev_id['id']);
			// 				if ($this->db->update('form_approval', $data_prev_layer)) {
			// 					$this->db->where('request_id', $request_id);
			// 					$this->db->update('hris_medical_reimbursment', array('is_status' => 2));
			// 					//$this->sendEmail('revise', $request_id, $prev_email['approval_email']);
			// 					$this->logs('revised', 'MDCR', $request_id, 'Response revised', 'Success');
			// 					$output = array('status' => 1);
			// 				}
							
			// 			}
			// 		}else{
			// 			$current_layer = array(
			// 				'approval_status' => 'Revised', 
			// 				'updated_at' => $this->date, 
			// 				'updated_by' => $this->email
			// 			);

			// 			$this->db->where('id', $approval_id);
			// 			if ($this->db->update('form_approval', $current_layer)) {
							
			// 				$data_revise_layer = array(
			// 					'is_status_admin_hr' => 0, 
			// 					'is_status' => 2, 
			// 					'updated_at' => $this->date, 
			// 					'updated_by' => $this->email
			// 				);
			// 				$this->db->where('id', $request_id);
			// 				if ($this->db->update('form_request', $data_revise_layer)) {
			// 					$this->db->where('request_id', $request_id);
			// 					$this->db->update('hris_medical_reimbursment', array('is_status' => 2));
			// 					//$this->sendEmail('revise', $request_id, $prev_email['approval_email']);
			// 					$this->logs('revised', 'MDCR', $request_id, 'Response revised', 'Success');
			// 					$output = array('status' => 1);
			// 				}
							
			// 			}
			// 		}

			// 	} else {

			// 			if($request_id_form != 0){

			// 				$data_revise_layer = array(
			// 					'is_status_admin_hr' => 0, 
			// 					'is_status' => 2, 
			// 					'updated_at' => $this->date, 
			// 					'updated_by' => $this->email
			// 				);
			// 				$this->db->where('id', $request_id);
			// 				$this->db->update('form_request', $data_revise_layer);
			// 			}
			// 			$revise_layer = array(
			// 				'approval_status' => 'Revised', 
			// 				'updated_at' => $this->date, 
			// 				'updated_by' => $this->email
			// 			);

			// 			$this->db->where('id', $request_id);
			// 			if ($this->db->update('form_request', array('is_status' => 2, 'updated_by' => $this->email, 'updated_at' => $this->date))) {
			// 				//$this->sendEmail('revise', $request_id, $requestor);
			// 				$this->db->where('request_id', $request_id);
			// 				$this->db->update('hris_medical_reimbursment', array('is_status' => 2));

			// 				$data['form_request'] = $this->m_global->find('id', $request_id, 'form_request')->row_array();
			// 				$data['data_employee'] = $this->form_model->get_data_employee($data['form_request']['employee_id']);
			// 				$this->sendEmail('revised_mdcr', $request_id, decrypt($data['data_employee']->email));
			// 				//$this->sendEmail('revised_mdcr', $request_id, 'luffi.putra@gmail.com');

			// 				$this->db->where('id', $approval_id);
			// 				if ($this->db->update('form_approval', $revise_layer)) {
			// 					//$this->sendEmail('revise', $request_id, $prev_email['approval_email']);
			// 					$this->logs('revised', 'MDCR', $request_id, 'Response revised', 'Success');
			// 					$output = array('status' => 1);
			// 				}
			// 			}

			// 	}

			// 	break;
			case 'Revised':
				// dumper($request_id_form);
				$data['form_approval'] = $this->m_global->find('request_id', $request_id, 'form_approval')->row_array();
				if ($priority != 1) {

					if($request_id_form == 0){
						
						$current_layer = array(
							'approval_status' => 'Revised', 
							'updated_at' => $this->date, 
							'updated_by' => $this->email
						);

						$this->db->where('id', $approval_id);
						if ($this->db->update('form_approval', $current_layer)) {
							$this->db->where('id', $request_id);
							if($this->db->update('form_request', array('is_status' => 2, 'is_status_admin_hr' => 0, 'updated_by' => $this->email, 'updated_at' => $this->date))){
								$this->db->where('request_id', $request_id);
								$this->db->update('hris_medical_reimbursment', array('is_status' => 2));

								$data['form_request'] = $this->m_global->find('id', $request_id, 'form_request')->row_array();
								$data['data_employee'] = $this->form_model->get_data_employee($data['form_request']['employee_id']);
								$data['form_approval'] = $this->m_global->find('request_id', $request_id, 'form_approval')->row_array();
								$this->sendEmail('revised_mdcr', $request_id, decrypt($data['data_employee'][0]->email));
								$this->sendEmail('revised_mdcr', $request_id, $data['form_approval']['approval_email']);


								$this->logs('revised', 'MDCR', $request_id, 'Response revised', 'Success');
								$output = array('status' => 1);
							}
						}

					}else{
						// dumper($request_id);
						$current_layer = array(
							'approval_status' => 'Revised', 
							'updated_at' => $this->date, 
							'updated_by' => $this->email
						);

						$this->db->where('id', $approval_id);
						if ($this->db->update('form_approval', $current_layer)) {
							$sql2 = "select id, request_number, form_type, is_status, no_req_mdcr, is_status_admin_hr, is_status_divhead_hr, revise from form_request where id='$request_id' ";
							$query = $this->db->query($sql2);
							$res2 = $query->result();

							// $this->db->where('request_id', $request_id);
							// $this->db->update('request_notes', array('is_status' => null));
							if ($res2[0]->no_req_mdcr != null) {
								$old_no_req_mdcr = $res2[0]->no_req_mdcr;
								$data_revise_layer = array(
									'no_req_mdcr' => null,
									'is_status_admin_hr' => 0, 
									'is_status_divhead_hr' => null, 
									'revise_after_f1' => 1,
									'revise' => ($res2[0]->revise == null ? 1 : $res2[0]->revise++),
									'is_status' => 2, 
									'updated_at' => $this->date, 
									'updated_by' => $this->email
								);								
							} else {
								$data_revise_layer = array(
									'is_status_admin_hr' => 0, 
									'is_status' => 2,
									'revise' => ($res2[0]->revise == null ? 1 : $res2[0]->revise++), 
									'updated_at' => $this->date, 
									'updated_by' => $this->email
								);
							}
								// $data_revise_layer = array(
								// 	'is_status_admin_hr' => 0, 
								// 	'is_status' => 2, 
								// 	'updated_at' => $this->date, 
								// 	'updated_by' => $this->email
								// );
							
							$this->db->where('id', $request_id);
							if ($this->db->update('form_request', $data_revise_layer)) {

								$this->db->where('request_id', $request_id);
								$this->db->update('hris_medical_reimbursment', array('is_status' => 2));

								$sql3 = "select * from form_request where no_req_mdcr='$old_no_req_mdcr' ";
								$query = $this->db->query($sql3);
								$res3 = $query->result();

								if ($res3 == null) {
									$this->db->where('no_req_mdcr', $old_no_req_mdcr);
									$this->db->update('hris_no_req_mdcr', array('is_status' => 4));
								} 
								
								$data['form_request'] = $this->m_global->find('id', $request_id, 'form_request')->row_array();
								$data['data_employee'] = $this->form_model->get_data_employee($data['form_request']['employee_id']);
								$data['form_approval'] = $this->m_global->find('request_id', $request_id, 'form_approval')->row_array();
								$this->sendEmail('revised_mdcr', $request_id, decrypt($data['data_employee'][0]->email));
								$this->sendEmail('revised_mdcr', $request_id, $data['form_approval']['approval_email']);


								$this->logs('revised', 'MDCR', $request_id, 'Response revised', 'Success');
								$output = array('status' => 1);
							}
							
						}
					}

				} else {

						if($request_id_form != 0){

							$data_revise_layer = array(
								'is_status_admin_hr' => 0, 
								'is_status' => 2, 
								'updated_at' => $this->date, 
								'updated_by' => $this->email
							);
							$this->db->where('id', $request_id);
							$this->db->update('form_request', $data_revise_layer);
						}

						$revise_layer = array(
							'approval_status' => 'Revised', 
							'updated_at' => $this->date, 
							'updated_by' => $this->email
						);

						$this->db->where('id', $request_id);
						if ($this->db->update('form_request', array('is_status' => 2, 'updated_by' => $this->email, 'updated_at' => $this->date))) {
							$this->db->where('request_id', $request_id);
							$this->db->update('hris_medical_reimbursment', array('is_status' => 2));

							$data['form_request'] = $this->m_global->find('id', $request_id, 'form_request')->row_array();
							$data['data_employee'] = $this->form_model->get_data_employee($data['form_request']['employee_id']);
							$data['form_approval'] = $this->m_global->find('request_id', $request_id, 'form_approval')->row_array();
							$this->sendEmail('revised_mdcr', $request_id, decrypt($data['data_employee'][0]->email));
							$this->sendEmail('revised_mdcr', $request_id, $data['form_approval']['approval_email']);

							$this->db->where('id', $approval_id);
							if ($this->db->update('form_approval', $revise_layer)) {
								$this->logs('revised', 'MDCR', $request_id, 'Response revised', 'Success');
								$output = array('status' => 1);
							}
						}

				}

				break;

			case 'Reject':			

					$this->db->where('request_id', $request_id);
					$this->db->delete('form_approval');
					$this->db->where('id', $request_id);
					if ($this->db->update('form_request', array('is_status' => 4, 'updated_by' => $this->email, 'updated_at' => $this->date))) {
						$this->db->where('request_id', $request_id);
						$this->db->update('hris_medical_reimbursment', array('is_status' => 4));
						
						$data['form_request'] = $this->m_global->find('id', $request_id, 'form_request')->row_array();
						$data['data_employee'] = $this->form_model->get_data_employee($data['form_request']['employee_id']);
						// var_dump($data['data_employee']);
						$this->sendEmail('rejected_mdcr', $request_id, decrypt($data['data_employee'][0]->email));
						
						//$this->sendEmail('revise', $request_id, $prev_email['approval_email']);
						$this->logs('reject', 'MDCR', $request_id, 'Response Rejected', 'Rejected');
						$output = array('status' => 1);
					}
				break;

			case 'Approved':
				
					#check current approver list
					$sql = "SELECT * FROM form_approval WHERE id = '$approval_id' AND request_id = '$request_id'";
					$checkleftcurrent = $this->db->query($sql);

					if(($checkleftcurrent->row_array()['approval_email'] != 'hr.support@ibsmulti.com') and ($checkleftcurrent->row_array()['approval_priority'] != 3)){
						$this->sendEmail('approved_spv_mdcr', $request_id, $checkleftcurrent->row_array()['approval_email'], $checkleftcurrent->row_array()['approval_employee_id']);
					}

					#check approver list

					// $sql = "SELECT * FROM form_approval WHERE id >= '$approval_id' AND request_id = '$request_id' ORDER BY approval_priority ASC OFFSET 1 ROWS FETCH NEXT 1 ROWS ONLY";
					// $sql = "SELECT * FROM form_approval WHERE id >= '$approval_id' AND request_id = '$request_id' and approval_status = '' ORDER BY approval_priority ASC LIMIT 1";
					$sql = "SELECT * FROM form_approval WHERE id >= '$approval_id' AND request_id = '$request_id' ORDER BY approval_priority ASC LIMIT 1,1";
					// dumper($sql);
					$checkleft = $this->db->query($sql);

					if ($checkleft->num_rows() > 0) {
						
						$current_approval = array(
							'approval_status' => 'Approved', 
							'updated_at' => $this->date, 
							'updated_by' => $this->email
						);

						#update response approval
						$this->db->where('id', $approval_id);
						if ($this->db->update('form_approval', $current_approval)) {

							#set in progress for next approver
							$this->db->where('id', $checkleft->row_array()['id']);
							
							if ($this->db->update('form_approval', array('approval_status' => 'In Progress'))) {

								$this->logs('approved', $request_id, 'Approved successfully');
								$output = array('status' => 1, 'message' => 'Approved successfully.', 'id' => encode_url($request_id));

							} else {
								$this->logs('system', $request_id, 'Authentication success, but failed while updating the next approver.');
								$output = array('status' => 0, 'message' => 'Authentication success, but failed while updating the next approver.');
							}

						} else {
							$this->logs('system', $request_id, 'Authentication success, but failed while updating response approval.');
							$output = array('status' => 0, 'message' => 'Authentication success, but failed while updating response approval. ');
						}

					} else {	

						#update header request
						$this->db->where('id', $request_id);
						if ($this->db->update('form_request', array('is_status' => 3, 'updated_by' => $this->email, 'updated_at' => $this->date))) {
							$approval = array(
								'approval_status' => 'Approved', 
								'updated_at' => $this->date, 
								'updated_by' => $this->email
							);

							#update response approval
							$this->db->where('id', $approval_id);
							if ($this->db->update('form_approval', $approval)) {

								//$this->sendEmail('approved_eapp', $request_id, $requestor);
								$this->logs('approved', $request_id, 'Approved successfully.');
								
								$output = array('status' => 1, 'message' => 'Approved successfully.', 'id' => encode_url($request_id));

							} else {
								$this->logs('system', $request_id, 'Authentication success, but failed while updating response approval [Full Approved].');
								$output = array('status' => 1, 'message' => 'Authentication success, but failed updating response approval.');
							}

						} else {
							$this->logs('system', $request_id, 'Authentication success, but failed while updating header request [Full Approved].');
							$output = array('status' => 1, 'message' => 'Authentication success, but failed updating response approval.');
						}

					}
	
					break;
			
			default:
				break;
		}
		//dumper($output);
		echo json_encode($output);
	}

	public function save($type = "", $id_table = "")
    {
      switch ($type) {

     	case 'final_score':

				$request_id = $this->input->post('id');

				$formData = array(
					'final_score' => $this->input->post('final_score'),
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);

				$this->db->where('id', $request_id);
				$updateHeader = $this->db->update('performance_appraisal', $formData);

				if ($updateHeader) {
					$this->logs('update_final_score', 'KPI', $request_id, 'Update Final Score');
					$response = array('status' => 1);
				} else {
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			case 'dashboard_final_score':

				$request_id = $this->input->post('id');

				$formData = array(
					'final_score' => $this->input->post('final_score'),
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);

				$this->db->where('id', $request_id);
				$updateHeader = $this->db->update('performance_appraisal', $formData);

				if ($updateHeader) {
					$this->logs('update_final_score', 'KPI', $request_id, 'Update Final Score from dashboard');
					$this->sendEmail('update_final_score', $request_id, 'dawan.malafi@ibstower.com');
					$response = array('status' => 1);
				} else {
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			case 'submit_to_hr':

				$transok = 0;
				$year = $this->year - 1;
				$eval_year = $year.'-01-01';

				$formData = array(
					'evaluation_period' => $eval_year,
					'division_name' => $this->session->userdata('division'),
					'is_status' => 1,
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);
				
				$check = $this->db->get_where('performance_division_status', 
					array('division_name' => $this->session->userdata('division'), 'evaluation_period' => $eval_year));

				if ($check->num_rows() == 1) {
					$this->db->where('id', $check->row_array()['id']);
					$this->db->update('performance_division_status', $formData);
					
					$this->logs('submit_to_hr', 'KPI', $check->row_array()['id'], 'Submit to HR');

					$this->sendEmail('submit_to_hr', $check->row_array()['id'], 'dawan.malafi@ibstower.com');
					$transok = 1;

				} else {
					$this->db->insert('performance_division_status', $formData);
					$division_status_id = $this->db->insert_id();
					$this->logs('submit_to_hr', 'KPI', $division_status_id, 'Submit to HR');
					$this->sendEmail('submit_to_hr', $division_status_id, 'dawan.malafi@ibstower.com');
					$transok = 1;
				}


				if ($transok) {
					$response = array('status' => 1);
				} else {
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			case 'submit_to_hr_ops':

				$transok = 0;
				$year = $this->year - 1;
				$eval_year = $year.'-01-01';

				$formData = array(
					'evaluation_period' => $eval_year,
					'division_name' => 'Regional Central',
					'is_status' => 1,
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);
				
				$check = $this->db->get_where('performance_division_status', 
					array('division_name' => 'Regional Central', 'evaluation_period' => $eval_year));

				if ($check->num_rows() == 1) {
					$this->db->where('id', $check->row_array()['id']);
					$this->db->update('performance_division_status', $formData);
					
					$this->logs('submit_to_hr', 'KPI', $check->row_array()['id'], 'Submit to HR');

					// $this->sendEmail('submit_to_hr', $check->row_array()['id'], 'dawan.malafi@ibstower.com');
					$transok = 1;

				} else {
					$this->db->insert('performance_division_status', $formData);
					$division_status_id = $this->db->insert_id();
					$this->logs('submit_to_hr', 'KPI', $division_status_id, 'Submit to HR');
					$this->sendEmail('submit_to_hr', $division_status_id, 'dawan.malafi@ibstower.com');
					$transok = 1;
				}


				if ($transok) {
					$response = array('status' => 1);
				} else {
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			case 'submit_to_hr_second_division':

				$transok = 0;
				$year = $this->year - 1;
				$eval_year = $year.'-01-01';

				$formData = array(
					'evaluation_period' => $eval_year,
					'division_name' => $this->session->userdata('second_division'),
					'is_status' => 1,
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);
				
				$check = $this->db->get_where('performance_division_status', 
					array('division_name' => $this->session->userdata('second_division'), 'evaluation_period' => $eval_year));

				if ($check->num_rows() == 1) {
					$this->db->where('id', $check->row_array()['id']);
					$this->db->update('performance_division_status', $formData);
					
					$this->logs('submit_to_hr', 'KPI', $check->row_array()['id'], 'Submit to HR');

					$this->sendEmail('submit_to_hr', $check->row_array()['id'], 'dawan.malafi@ibstower.com');
					$transok = 1;

				} else {
					$this->db->insert('performance_division_status', $formData);
					$division_status_id = $this->db->insert_id();
					$this->logs('submit_to_hr', 'KPI', $division_status_id, 'Submit to HR');
					$this->sendEmail('submit_to_hr', $division_status_id, 'dawan.malafi@ibstower.com');
					$transok = 1;
				}


				if ($transok) {
					$response = array('status' => 1);
				} else {
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			case 'Revised':

				$formData = array(
					'is_status' => 2,
					'response_by' => $this->email,
					'response_at' => $this->date
				);

				$div_head_email = $this->db->get_where('performance_division_status', array('id' => $id_table))->row_array()['updated_by'];

				$this->db->where('id', $id_table);
				if ($this->db->update('performance_division_status', $formData)) {
					$this->sendEmail('revised_by_hr', $id_table, $div_head_email);
					$this->logs('revised_by_hr', 'KPI', $id_table, 'Revised by HR');
					$response = array('status' => 1);

				} else {
					$this->logs('system', 'KPI', $id_table, 'Revised by HR');
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			case 'Confirm':

				$formData = array(
					'is_status' => 3,
					'response_by' => $this->email,
					'response_at' => $this->date
				);
				
				$div_head_email = $this->db->get_where('performance_division_status', array('id' => $id_table))->row_array()['updated_by'];

				$this->db->where('id', $id_table);
				if ($this->db->update('performance_division_status', $formData)) {
					$this->sendEmail('confirm_by_hr', $id_table, $div_head_email);
					$this->logs('confirm_by_hr', 'KPI', $id_table, 'Confirm by HR');
					$response = array('status' => 1);

				} else {
					$this->logs('system', 'KPI', $id_table, 'Confirm by HR');
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

      case 'notes':
                $id = $this->input->post('request_id');
                $field['request_id'] = $id;
                $field['notes'] = $this->input->post('notes');
                $field['is_status'] = $this->input->post('type');
                $field['created_by'] = $this->email;
                $field['created_at'] = $this->date;

      		// dumper($field);

				$form_type = $this->input->post('form_type');

                if ($this->db->insert('request_notes', $field)) {
                    $this->logs('save_notes', $form_type, $id, 'Add notes', $this->input->post('notes'));
                    $response = array('status' => 1, 'id' => encode_url($id), 'messages' => 'Notes has been saved.');
                } else {
                    $this->logs('system', $id, 'Failed while saving invoices notes.');
                    $response = array('status' => 0, 'messages' => 'There\s something wrong. Please try again.');
                }

                echo json_encode($response);
                break;

            default:
                break;
        }
    }

    public function delete_notes($notes_id, $request_id)
    {
        if ($this->db->where('id', $notes_id)->delete('request_notes')) {
            echo json_encode(array('status' => 1, 'id' => encode_url($request_id), 'messages' => "Notes has been deleted."));
        } else {
            echo json_encode(array('status' => 0, 'messages' => "Oops! There is something wrong. Please refresh the page and try again."));
        }
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

			case 'save_notes':
				$log['activity'] = $activity;
				$log['description'] = $description;
				$this->db->insert('logs', $log);
				break;

			case 'submit_to_hr':
				$log['activity'] = $activity;
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'revised_by_hr':
				$log['activity'] = $activity;
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'confirm_by_hr':
				$log['activity'] = $activity;
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'update_final_score':
				$log['activity'] = $activity;
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'revised':
				$log['activity'] = $activity;
				$log['description'] = $description;
				$this->db->insert('logs', $log);
				break;
			
			case 'reject':
				$log['activity'] = $activity;
				$log['description'] = $description;
				$this->db->insert('logs', $log);
				break;

			default:
				break;
		}
	}


	public function cekNote()
	{

		$request_id		= $_POST['id'];
		$data = $this->inbox_model->cekNote($request_id);
		echo json_encode($data);
	}
	public function grouping_req_mdcr(){
		$req_mdcr			= $_POST['req_mdcr'];
		//dumper($req_mdcr);
		$tanggal_input = date("Y-m-d");
		$tanggal = date("dmy");
		$sql = "SELECT id, no_req_mdcr FROM hris_no_req_mdcr WHERE (YEAR(created_at) = YEAR('$tanggal_input') AND MONTH(created_at) = MONTH('$tanggal_input') AND DAY(created_at) = DAY('$tanggal_input')) ORDER BY id DESC LIMIT 1";
    $noref = $this->db->query($sql);
		$noref = $noref->result();
		// dumper($noref);
		if(empty($noref[0]->no_req_mdcr)){
			// dumper('buat baru');
			$nomer	='001';
			$no_ref = "HRIS_MDCR".$tanggal.$nomer;
		}else{
			// dumper('auto increment');

			$noref 	= $noref[0]->no_req_mdcr;
			$nn 	= $noref;
			$nn 	= explode("HRIS_MDCR", $nn);
			//dumper($nn);
			$nn 	= $nn[1];
			$tanggal2 = (int) substr($nn, 0, 6);
				if ($tanggal == $tanggal2){
					$noUrut = (int) substr($nn, 6, 3);
					$noUrut++;
					$nomer 	= sprintf("%03s", $noUrut);
					$no_ref = "HRIS_MDCR".$tanggal.$nomer;
				}else{
					$nomer	='001';
					$no_ref = "HRIS_MDCR".$tanggal.$nomer;
				}
		}

		if(!empty($req_mdcr)){
			$n 				= count($req_mdcr);

			if($n >= 15){
				$data = '15_lebih';
			}else{
				$index=0;
				foreach($req_mdcr as $key => $value){
					//dumper($value);
					$info = array(
					'request_id_mdcr' 		=>  $req_mdcr[$index],
					);
					$data[] = $info;
					$index++;
				}
			
				for ($i = 0; $i <= $n-1; $i++) {
					$this->responseRequestGrouping($no_ref, $data[$i]['request_id_mdcr']);
					//$this->inbox_model->save_grouping_req_mdcr($no_ref, $data[$i]['request_id_mdcr']);
				}

				$data = $this->inbox_model->save_no_grouping_req_mdcr($no_ref);
			}
		}
		//dumper($data);
		echo json_encode($data);

	}

	public function mod_resume_no_req_to_fi($no_req)
	{
			$no_req = $this->uri->segment(3);
			$data['header_mdcr_after_grouping_per_item'] = $this->inbox_model->getApprovalListMDCRAGroupingItem($no_req);
			$data['content'] = 'inbox/mod/mod_resume_no_req_to_fi';
			$this->templates->show('index', 'templates/eapp/eapp_main_pop_up', $data);
			//$this->load->view('inbox/mod/mod_resume_no_req_to_fi', $data);
	}
	
	public function responseRequestGrouping($no_ref, $request_id)
	{
		//dumper($request_id);
		$output = array('status' => 0, 'message' => 'Something went wrong. Please refresh and try again.');
		
		$request_id = $request_id;
		
		$sql = "select id from form_approval where request_id='$request_id' and approval_status='In Progress'";
		$query = $this->db->query($sql);
		$res = $query->result();
		$approval_id = $res[0]->id;

		// previous layer
		$priority = $this->m_global->find('id', $approval_id, 'form_approval')->row_array()['approval_priority'];
		$prev_priority = $priority-1;
		$prev_id = $this->inbox_model->find_select("id",array('approval_priority'=>$prev_priority,'request_id'=>$request_id),'form_approval')->row_array();

		$prev_email = $this->inbox_model->find_select("approval_email",array('approval_priority'=>$prev_priority,'request_id'=>$request_id),'form_approval')->row_array();

		$data_prev_layer = array(
			'approval_status' => 'In Progress', 
			'updated_at' => $this->date, 
			'updated_by' => $this->email
		);
		#check approver list
		// $sql = "SELECT * FROM form_approval WHERE id >= '$approval_id' AND request_id = '$request_id' ORDER BY approval_priority ASC OFFSET 1 ROWS FETCH NEXT 1 ROWS ONLY";
		$sql = "SELECT * FROM form_approval WHERE id >= '$approval_id' AND request_id = '$request_id' AND approval_status = '' ORDER BY approval_priority ASC LIMIT 1";
		$checkleft = $this->db->query($sql);

			// dumper($no_ref);

		if ($checkleft->num_rows() > 0) {
			$current_approval = array(
				'approval_status' => 'Approved', 
				'updated_at' => $this->date, 
				'updated_by' => $this->email
			);

			#update response approval
			$this->db->where('id', $approval_id);
			if ($this->db->update('form_approval', $current_approval)) {
				// dumper($checkleft->row_array()['approval_email']);
				$sql2 	= "UPDATE form_request SET no_req_mdcr='$no_ref', revise_after_f1 = null WHERE form_type = 'MDCR' AND id = '".$request_id."'";
				// dumper($sql2);
				$this->db->query($sql2);

				#set in progress for next approver
				$this->db->where('id', $checkleft->row_array()['id']);
				
				if ($this->db->update('form_approval', array('approval_status' => 'In Progress'))) {

					//$this->sendEmail('approved_mdcr', $request_id, 'luffi.utomo@ibsmulti.com');
					$this->sendEmail('request_approve_mdcr', $request_id, $checkleft->row_array()['approval_email']);
					$this->logs('approved', $request_id, 'Approved successfully');
					$output = array('status' => 1, 'message' => 'Approved successfully.', 'id' => encode_url($request_id));

				} else {
					$this->logs('system', $request_id, 'Authentication success, but failed while updating the next approver.');
					$output = array('status' => 0, 'message' => 'Authentication success, but failed while updating the next approver.');
				}

			} else {
				// dumper('Wakakaka');

				$this->logs('system', $request_id, 'Authentication success, but failed while updating response approval.');
				$output = array('status' => 0, 'message' => 'Authentication success, but failed while updating response approval. ');
			}

		} else {	

			// dumper('sdadwa');

			#update header request
			$this->db->where('id', $request_id);
			if ($this->db->update('form_request', array('is_status' => 3, 'revise_after_f1' => null, 'updated_by' => $this->email, 'updated_at' => $this->date))) {
				$approval = array(
					'approval_status' => 'Approved', 
					'updated_at' => $this->date, 
					'updated_by' => $this->email
				);

				#update response approval
				$this->db->where('id', $approval_id);
				if ($this->db->update('form_approval', $approval)) {

					//$this->sendEmail('approved_eapp', $request_id, $requestor);
					$this->logs('approved', $request_id, 'Approved successfully.');
					
					$output = array('status' => 1, 'message' => 'Approved successfully.', 'id' => encode_url($request_id));

				} else {
					$this->logs('system', $request_id, 'Authentication success, but failed while updating response approval [Full Approved].');
					$output = array('status' => 1, 'message' => 'Authentication success, but failed updating response approval.');
				}

			} else {
				$this->logs('system', $request_id, 'Authentication success, but failed while updating header request [Full Approved].');
				$output = array('status' => 1, 'message' => 'Authentication success, but failed updating response approval.');
			}

		}
		//echo json_encode($output);
	}

	public function sendEmail($type, $requestId, $email_to, $employee_id="")
	{
		$data['form_request'] = $this->m_global->find('id', $requestId, 'form_request')->row_array();
		$data['data_employee'] = $this->form_model->get_data_employee($data['form_request']['employee_id']);
		$data['get_data_claim'] = $this->form_model->get_data_claim_per_request($requestId);
		$data['approval'] = $this->m_global->find('request_id', $requestId, 'form_approval')->result_array();
		
		// dumper($data['form_request']);
		// dumper($data['data_employee']);
		// var_dump($data['email']);

		if ($type == 'approved_spv_mdcr') {
			$data['data_employee_approver'] = $this->form_model->get_data_employee($employee_id);
			$data['email'] 	= decrypt($data['data_employee'][0]->email);
			$html 			= $this->load->view('services/email/approved_spv_mdcr', $data, TRUE);
			$email_subject 	= 'IBSW-Medical Claim Approved';
			$email_to 		= decrypt($data['data_employee'][0]->email);

		} elseif ($type == 'approved_mdcr') {
			$data['email'] = $email_to;
			$html = $this->load->view('services/email/approvedMDCR', $data, TRUE);
			$email_subject = 'IBSW-Medical Claim Approved';
		} elseif ($type == 'request_approve_mdcr') {
			$data['email'] = $email_to;
			$data['data_employee_approver'] = $this->form_model->get_data_employee($employee_id);
			$html = $this->load->view('services/email/request_approve_mdcr', $data, TRUE);
			$email_subject = 'IBSW-Medical Claim Request Approve';
		}  elseif ($type == 'rejected_mdcr') {
			$data['email'] = $email_to;
			$html = $this->load->view('services/email/reject_mdcr', $data, TRUE);
			$email_subject = 'IBSW-Medical Claim Rejected';
		}  elseif ($type == 'revised_mdcr') {
			$data['email'] = $email_to;
			$html = $this->load->view('services/email/revised_mdcr', $data, TRUE);
			$email_subject = 'IBSW-Medical Claim Revised';
		} 

		// $url = 'https://api.ibstower.com/email_service';
		// $params = array(
		// 	'app_name'      => 'IBST-HRIS',
		// 	'ip_address'    => $_SERVER['SERVER_ADDR'],
		// 	'email_to'      => $email_to,
		// 	'email_subject' => $email_subject,
		// 	'email_content' => $html,
		// 	'is_status' 	=> 0,
		// 	'created_at' 	=> $this->date,
		// 	'created_by' 	=> $this->email
		// );

		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_POST, 1);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		// curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// $result = curl_exec($ch);
		// if (curl_errno($ch) !== 0) {
		// 	print_r('Oops! cURL error when connecting to ' . $url . ': ' . curl_error($ch));
		// }

		// curl_close($ch);


		$mail = new PHPMailer();
		// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->isSMTP();
		$mail->Host       = 'mail.ibsmulti.com';
		$mail->SMTPAuth   = true;
		$mail->Username   = 'no.reply@ibsmulti.com'; // ubah dengan alamat email Anda
		$mail->Password   = '2023@54321No.Reply'; // ubah dengan password email Anda
		$mail->SMTPSecure = 'tls';
		$mail->Port       = 587;

		$mail->setFrom('no.reply@ibsmulti.com', 'Notification System'); // ubah dengan alamat email Anda
		$mail->addAddress($email_to);
		$mail->AddBCC('tiopan.wahyudi@ibsmulti.com');
		// Isi Email
		$mail->isHTML(true);
		$mail->Subject = $email_subject;
		$mail->Body    = $html;

		$mail->send();
	}

	
}
