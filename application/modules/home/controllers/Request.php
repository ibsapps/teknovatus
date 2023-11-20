<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('curl');
		$this->load->library('enc');
		$this->enc->check_session();

		$this->email = $this->session->userdata('user_email');
		$this->emp_id = $this->session->userdata('employee_id');
		
		// if ($this->emp_id == '') {
		// 	print_r("You are not authorized to access this apps.");
		// 	exit();
		// }
		
		$this->load->helper('general');
		$this->load->model('form/form_model');
		$this->load->model('inbox/inbox_model');
		$this->load->model('home_model');
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
		$data['header'] = $this->home_model->getMyRequest();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'home/list_request';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function view($id)
	{
		$formType = 'MDCR';
		$request_id = decode_url($id);
		$header = $this->m_global->getRow('header_table', 'form_type', array('code' => $formType));
		$detail = $this->m_global->getRow('detail_table', 'form_type', array('code' => $formType));
		$additional = $this->m_global->getRow('additional_table', 'form_type', array('code' => $formType));
		
		$data['header'] = $this->m_global->find('request_id', $request_id, $header)->row_array();
		$employee_id = $data['header']['employee_id'];
		$data['detail'] = $this->m_global->find('request_id', $request_id, $detail)->result_array();
		$data['sum_penggantian'] = $this->form_model->get_sum_penggantian($request_id);
		$data['reimaning_pagu'] = $this->form_model->get_reimaning_pagu($employee_id);

		// $request_id = decode_url($id);
		// $data['header'] = $this->m_global->find('id', $request_id, 'performance_appraisal')->row_array();
		// $data['detail'] = $this->m_global->find('request_id', $request_id, 'performance_appraisal_measurement')->result_array();
		// $data['additional'] = $this->m_global->find('request_id', $request_id, 'performance_appraisal_plan')->result_array();
		$data['approval'] = $this->m_global->find('request_id', $request_id, 'form_approval')->result_array();
		$data['notes']   = $this->m_global->find('request_id', $request_id, 'request_notes')->result_array();
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'home/form/details';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function delete($request_id)
    {
    	$transok = false;
		$form_type = $this->m_global->find('id', $request_id, 'form_request')->row_array()['form_type'];
		$header_table = $this->m_global->find('code', $form_type, 'form_type')->row_array()['header_table'];
		$detail_table = $this->m_global->find('code', $form_type, 'form_type')->row_array()['detail_table'];
		$additional_table = $this->m_global->find('code', $form_type, 'form_type')->row_array()['additional_table'];
        if ($this->db->where('id', $request_id)->delete('form_request')) {

	    	$this->db->trans_begin();
        	$this->db->where('request_id', $request_id)->delete($header_table);
        	$this->db->where('request_id', $request_id)->delete($detail_table);
			$this->db->where('request_id', $request_id)->delete($additional_table);

        	if ($this->db->trans_status() === FALSE) {
			    $this->db->trans_rollback();
			    $transok = false;
			    $type = 'system';
			    $desc = 'Failed while deleting draft request.';

			} else {
			    $this->db->trans_commit();
			    $transok = true;
			    $type = 'delete_draft';
			    $desc = 'Success delete draft.';
			}
        }

	    $this->logs($type, $form_type, $request_id, 'Delete Draft', $desc);

        if ($transok) {
        	echo json_encode(array('status' => 1));
        } else {
        	echo json_encode(array('status' => 0));
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

			case 'delete_draft':
				$log['activity'] = $activity;
				$log['description'] = $description;
				$this->db->insert('logs', $log);
				break;

			default:
				break;
		}
	}

}
