<?php

defined('BASEPATH') or exit('No direct script access allowed');
	require '/var/www/html/application/vendor/phpmailer/phpmailer/src/Exception.php';
	require '/var/www/html/application/vendor/phpmailer/phpmailer/src/PHPMailer.php';
	require '/var/www/html/application/vendor/phpmailer/phpmailer/src/SMTP.php';

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception ;

class Form extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->date = date('Y-m-d H:i:s');
		$this->year = date('Y');
		// $this->load->library('curl');
		$this->load->library('email');
		$this->load->library('enc');
		// $this->enc->check_session();

		$this->email = $this->session->userdata('user_email');
		//dumper($this->email);
		$this->emp_id = $this->session->userdata('employee_id');
		$this->emp_name = $this->session->userdata('employee_name');
		$this->emp_grade = $this->session->userdata('employee_group');

		//dumper($this->emp_id);

		// if ($this->emp_id == '') {
		// 	print_r("You are not authorized to access this apps.");
		// 	exit();
		// }

		$this->load->helper('general');
		$this->load->model('form_model');
		$this->load->model('inbox/inbox_model');
		$this->load->model('home/home_model');
		$this->load->model('m_global');

		if (empty($this->session->userdata('nik'))) {
			$this->session->set_flashdata('failure', 'Login failed');
			redirect('login');
		}
	}

	public function index()
	{
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'form/index';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function initial_create($formType)
	{
		// print_r('here');die;
		$table = $this->m_global->getRow('header_table', 'form_type', array('code' => $formType));
		$requestNumber = $this->getRequestNumber(strtoupper($formType), $table['header_table']);

		switch ($formType) {

			case 'KPI':

				$dataEmployee = $this->m_global->get(array(
					'employee_name',
					'employee_nik',
					'position',
					'division',
					'join_date',
					'employment_status',
					'office_location'
				), 'employee',  array('id' => $this->emp_id));

				$requestId = $this->form_model->initial_create($formType, $dataEmployee, $requestNumber, $table['header_table']);
				break;

			case 'PLAN':

				$dataEmployee = $this->m_global->get(array(
					'employee_name',
					'employee_nik',
					'position',
					'division',
					'join_date',
					'employment_status',
					'office_location'
				), 'employee',  array('id' => $this->emp_id));

				$requestId = $this->form_model->initial_create($formType, $dataEmployee, $requestNumber, $table['header_table']);
				break;

			case 'MDCR':

				$dataEmployee = $this->m_global->get(array(
					'complete_name',
					'nik'
				), 'hris_employee',  array('nik' => $this->emp_id));

				$requestId = $this->form_model->initial_create($formType, $dataEmployee, $requestNumber, $table['header_table']);
				break;

			case 'PPD':

				$dataEmployee = $this->m_global->get(array(
					'complete_name',
					'nik'
				), 'hris_employee',  array('nik' => $this->emp_id));

				$requestId = $this->form_model->initial_create($formType, $dataEmployee, $requestNumber, $table['header_table']);
				break;

			case 'LPD':

				$dataEmployee = $this->m_global->get(array(
					'complete_name',
					'nik'
				), 'hris_employee',  array('nik' => $this->emp_id));

				$requestId = $this->form_model->initial_create($formType, $dataEmployee, $requestNumber, $table['header_table']);
				break;

			default:
				break;
		}

		if ($requestId) {
			$this->logs('create', $formType, $requestId);
			$response = array('status' => 1, 'message' => 'Redirecting..', 'form' => $formType, 'id' => encode_url($requestId));
		} else {
			$this->logs('system', $formType, $requestId, 'Initial Create', 'Something went wrong with request id.');
			$response = array('status' => 0, 'message' => 'Something went wrong with request id.');
		}

		header('Content-type: application/json');
		echo json_encode($response);
	}

	public function detail($formType, $id)
	{
		$request_id = decode_url($id);
		$data['form_request'] = $this->m_global->find('id', $request_id, 'form_request')->row_array();
		$header = $this->m_global->getRow('header_table', 'form_type', array('code' => $formType));
		$detail = $this->m_global->getRow('detail_table', 'form_type', array('code' => $formType));
		$additional = $this->m_global->getRow('additional_table', 'form_type', array('code' => $formType));
		$data['header'] = $this->m_global->find('request_id', $request_id, $header)->row_array();

		switch ($formType) {

			case 'MDCR':

				$data['detail'] = $this->m_global->find('request_id', $request_id, $detail)->result_array();
				$employee_id = $data['header']['employee_id'];

				$id_eg_prj = $data['header']['id_eg_prj'];
				$id_eg_pri = $data['header']['id_eg_pri'];
				$id_eg_pk  = $data['header']['id_eg_pk'];
				$data['couple'] = $this->form_model->get_data_couple_employee($employee_id);
				$data['spouse'] = $this->form_model->getFamilyActSpouse($employee_id);
				//dumper($data['header']);
				$request_created_at = $data['form_request']['created_at'];
				$data['sum_penggantian_jalan'] = $this->form_model->get_sum_penggantian_jalan($request_id);
				$data['sum_penggantian_inap'] = $this->form_model->get_sum_penggantian_inap($request_id);
				$data['sum_penggantian_kacamata'] = $this->form_model->get_sum_penggantian_kacamata($request_id);
				$data['reimaning_pagu'] = $this->form_model->get_reimaning_pagu($request_created_at, $employee_id, $id_eg_prj, $id_eg_pri, $id_eg_pk);
				// dumper($data['reimaning_pagu']);
				$data['additional'] = $this->m_global->find('request_id', $request_id, $additional)->result_array();
				//dumper($data['reimaning_pagu']);
				$config['cacheable']    = true;
				$config['cachedir']     = './assets/';
				$config['errorlog']     = './assets/';
				$config['imagedir']     = './assets/images/qrcode_mdcr/';
				$config['quality']      = true;
				$config['size']         = '1024';
				$config['black']        = array(224, 255, 255);
				$config['white']        = array(70, 130, 180);
				$this->ciqrcode->initialize($config);
				$image_name = $data['form_request']['request_number'] . '-' . $data['form_request']['employee_id'] . '.png';
				$params['data'] = $data['form_request']['request_number'] . '-' . $data['form_request']['employee_id'];
				$params['level'] = 'H'; //H=High
				$params['size'] = 10;
				$params['savename'] = FCPATH . $config['imagedir'] . $image_name;
				$this->ciqrcode->generate($params);

				break;

			case 'PPD':

				$dir =  './uploaded_files/' . $data['form_request']['request_number'] . '/supporting_files';
				$data['uploaded_document'] = $this->doScan($dir);
				$data['list_city'] = $this->form_model->getCity();
				$data['detail'] = $this->m_global->find('header_id', $data['header']['id'], $detail)->result_array();

				if (!empty($data['detail'][0]['kota_berangkat'])) {
					$data['kota_berangkat'] = $this->db->get_where('master_city', array('IDPROVINSI' => $data['detail'][0]['kota_berangkat']))->row_array()['NAMA_KABUPATEN_KOTA'];
					$data['kota_tujuan'] = $this->db->get_where('master_city', array('IDPROVINSI' => $data['detail'][0]['kota_tujuan']))->row_array()['NAMA_KABUPATEN_KOTA'];
					$data['category_city'] = $this->db->get_where('master_city', array('IDPROVINSI' => $data['detail'][0]['kota_tujuan']))->row_array()['CATEGORY'];
				} else {
				}

				$data['approval_progress'] = $this->m_global->find('request_id', $request_id, 'form_approval')->result_array();
				$count = count($data['approval_progress']);
				$data['requestor_layer'] = '';
				$data['layer_1'] = '';
				$data['layer_2'] = '';
				$data['layer_3'] = '';

				// if ($data['approval_progress'][0]['approval_email'] === $data['form_request']['created_by']) {
				// 	$data['requestor_layer'] = $data['approval_progress'][0]['approval_email'];
				// 	$data['layer_1'] = $data['approval_progress'][1]['approval_email'];
				// 	$data['layer_2'] = $data['approval_progress'][2]['approval_email'];
				// 	$data['layer_3'] = $data['approval_progress'][3]['approval_email'];
				// } else {
				// 	$data['requestor_layer'] = '';
				// 	$data['layer_1'] = $data['approval_progress'][0]['approval_email'];
				// 	$data['layer_2'] = $data['approval_progress'][1]['approval_email'];
				// 	$data['layer_3'] = $data['approval_progress'][2]['approval_email'];
				// }

				// print_r($data['approval_progress']);die;
				// if (!empty($data['approval_progress'])) {

				// 	if ($count === 4) {
				// 		$data['requestor_layer'] = $data['approval_progress'][0]['approval_email'];
				// 		$data['layer_1'] = $data['approval_progress'][1]['approval_email'];
				// 		$data['layer_2'] = $data['approval_progress'][2]['approval_email'];
				// 		$data['layer_3'] = $data['approval_progress'][3]['approval_email'];
				// 	} else {
				// 		$data['requestor_layer'] = '';
				// 		$data['layer_1'] = $data['approval_progress'][0]['approval_email'];
				// 		$data['layer_2'] = $data['approval_progress'][1]['approval_email'];
				// 		$data['layer_3'] = $data['approval_progress'][2]['approval_email'];
				// 	}

				// } 

				break;

			case 'LPD':

				$dir =  './uploaded_files/' . $data['form_request']['request_number'] . '/supporting_files';
				$data['uploaded_document'] = $this->doScan($dir);
				$data['list_city'] = $this->form_model->getCity();
				$data['detail'] = $this->m_global->find('header_id', $data['header']['id'], $detail)->row_array();
				$data['additional'] = $this->m_global->find('header_id', $data['header']['id'], $additional)->result_array();

				$data['approval_progress'] = $this->m_global->find('request_id', $request_id, 'form_approval')->result_array();
				$count = count($data['approval_progress']);
				$data['requestor_layer'] = '';
				$data['layer_1'] = '';
				$data['layer_2'] = '';
				$data['layer_3'] = '';

				// print_r($data['approval_progress']);die;
				// if (!empty($data['approval_progress'])) {

				// 	if ($count === 4) {
				// 		$data['requestor_layer'] = $data['approval_progress'][0]['approval_email'];
				// 		$data['layer_1'] = $data['approval_progress'][1]['approval_email'];
				// 		$data['layer_2'] = $data['approval_progress'][2]['approval_email'];
				// 		$data['layer_3'] = $data['approval_progress'][3]['approval_email'];
				// 	} else {
				// 		$data['requestor_layer'] = '';
				// 		$data['layer_1'] = $data['approval_progress'][0]['approval_email'];
				// 		$data['layer_2'] = $data['approval_progress'][1]['approval_email'];
				// 		$data['layer_3'] = $data['approval_progress'][2]['approval_email'];
				// 	}

				// } 

				break;

			default:
				break;
		}

		$data['userList'] = $this->form_model->getUserList("user_email", "users", "is_active = 1 AND user_email != '$this->email'");

		$data['employee_list'] = $this->form_model->getUserList("complete_name", "hris_employee", "is_active = 1 AND access_employee != 1 AND complete_name != '$this->emp_name'");
		$data['approval'] = $this->m_global->find('request_id', $request_id, 'form_approval')->result_array();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['request_notes'] = $this->form_model->getRequestNotes($request_id);
		$data['notes']   = $this->m_global->find('request_id', $request_id, 'request_notes')->result_array();
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'form/' . $formType;
		//dumper($data);
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function detail_full_approve($formType, $id)
	{
		$request_id = decode_url($id);
		$data['form_request'] = $this->m_global->find('id', $request_id, 'form_request')->row_array();
		$header = $this->m_global->getRow('header_table', 'form_type', array('code' => $formType));
		$detail = $this->m_global->getRow('detail_table', 'form_type', array('code' => $formType));
		$data['header'] = $this->m_global->find('request_id', $request_id, $header)->row_array();
		switch ($formType) {

			case 'MDCR':
				$employee_id = $data['header']['employee_id'];
				$data['couple'] = $this->form_model->get_data_couple_employee($employee_id);
				$additional = $this->m_global->getRow('additional_table', 'form_type', array('code' => $formType));
				$data['detail'] = $this->m_global->find('request_id', $request_id, $detail)->result_array();
				$employee_id = $data['header']['employee_id'];
				$request_created_at = $data['form_request']['created_at'];
				//dumper($data['form_request']);
				$id_eg_prj = $data['header']['id_eg_prj'];
				$id_eg_pri = $data['header']['id_eg_pri'];
				$id_eg_pk  = $data['header']['id_eg_pk'];
				$data['sum_penggantian_jalan'] = $this->form_model->get_sum_penggantian_jalan($request_id);
				$data['sum_penggantian_inap'] = $this->form_model->get_sum_penggantian_inap($request_id);
				$data['sum_penggantian_kacamata'] = $this->form_model->get_sum_penggantian_kacamata($request_id);
				$data['reimaning_pagu'] = $this->form_model->get_reimaning_pagu($request_created_at, $employee_id, $id_eg_prj, $id_eg_pri, $id_eg_pk);
				$data['additional'] = $this->m_global->find('request_id', $request_id, $additional)->result_array();
				// dumper($data['reimaning_pagu']);
				break;

			case 'PPD':

				$dir =  './uploaded_files/' . $data['form_request']['request_number'] . '/supporting_files';
				$data['uploaded_document'] = $this->doScan($dir);
				$data['list_city'] = $this->form_model->getCity();
				$data['detail'] = $this->m_global->find('header_id', $data['header']['id'], $detail)->result_array();
				if (!empty($data['detail'][0]['kota_berangkat'])) {
					$data['kota_berangkat'] = $this->db->get_where('master_city', array('IDPROVINSI' => $data['detail'][0]['kota_berangkat']))->row_array()['NAMA_KABUPATEN_KOTA'];
					$data['kota_tujuan'] = $this->db->get_where('master_city', array('IDPROVINSI' => $data['detail'][0]['kota_tujuan']))->row_array()['NAMA_KABUPATEN_KOTA'];
					$data['category_city'] = $this->db->get_where('master_city', array('IDPROVINSI' => $data['detail'][0]['kota_tujuan']))->row_array()['CATEGORY'];
				} else {
				}

				$data['approval_progress'] = $this->m_global->find('request_id', $request_id, 'form_approval')->result_array();
				$count = count($data['approval_progress']);
				$data['requestor_layer'] = '';
				$data['layer_1'] = '';
				$data['layer_2'] = '';
				$data['layer_3'] = '';

				// print_r($data['approval_progress']);die;
				if (!empty($data['approval_progress'])) {

					if ($count === 4) {
						$data['requestor_layer'] = $data['approval_progress'][0]['approval_email'];
						$data['layer_1'] = $data['approval_progress'][1]['approval_email'];
						$data['layer_2'] = $data['approval_progress'][2]['approval_email'];
						$data['layer_3'] = $data['approval_progress'][3]['approval_email'];
					} else {
						$data['requestor_layer'] = '';
						$data['layer_1'] = $data['approval_progress'][0]['approval_email'];
						$data['layer_2'] = $data['approval_progress'][1]['approval_email'];
						$data['layer_3'] = $data['approval_progress'][2]['approval_email'];
					}
				}

				break;

			case 'LPD':

				$dir =  './uploaded_files/' . $data['form_request']['request_number'] . '/supporting_files';
				$data['uploaded_document'] = $this->doScan($dir);
				$data['list_city'] = $this->form_model->getCity();
				$data['detail'] = $this->m_global->find('header_id', $data['header']['id'], $detail)->row_array();
				$data['additional'] = $this->m_global->find('header_id', $data['header']['id'], $additional)->result_array();

				$data['approval_progress'] = $this->m_global->find('request_id', $request_id, 'form_approval')->result_array();
				$count = count($data['approval_progress']);
				$data['requestor_layer'] = '';
				$data['layer_1'] = '';
				$data['layer_2'] = '';
				$data['layer_3'] = '';

				break;

			default:
				break;
		}

		$data['userList'] = $this->form_model->getUserList("user_email", "users", "is_active = 1 AND user_email != '$this->email'");
		$data['employee_list'] = $this->form_model->getUserList("complete_name", "hris_employee", "is_active = 1 AND access_employee != 1 AND complete_name != '$this->emp_name'");
		$data['approval'] = $this->m_global->find('request_id', $request_id, 'form_approval')->result_array();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['notes']   = $this->m_global->find('request_id', $request_id, 'request_notes')->result_array();
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'form/full_approval/' . $formType;
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function read($table, $id)
	{
		//dumper($id);
		$request_id = $id;
		switch ($table) {

			case 'ToR':

				$listToR = $this->form_model->getTypeOfRembursement($request_id);
				$checkRecord = $this->form_model->cek_record($id);

				// dumper($checkRecord);
				if (!empty($listToR)) {
					foreach ($listToR as $key) {
						if ($key->is_status == 1 || $key->is_status == 3 || $key->is_status == 4) {
							if($this->session->userdata('access_employee') == '12' && $checkRecord[0]->is_status_admin_hr == null) {
								$edit = '<a class="text-primary btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalEditPrice" data-offset="-4,0" id="'.$key->id.'" onClick="edit_price('.$key->id.')"><em class="icon ni ni-edit"></em></a>';
								$show = '';	
							} else {
								$show = 'none';
								$edit = '';
							}
						} else {
							$edit = '<a class="text-primary btn btn-icon btn-trigger" data-toggle="modal" data-target="#modalEditToR" data-offset="-4,0" id="'.$key->id.'" onClick="edit_tor('.$key->id.')"><em class="icon ni ni-edit"></em></a>';
							$show = '';
						}

						if (($key->additional == 'Diri Sendiri')) {
							$additional = $key->additional;
						} else {
							$additional = decrypt($key->additional);
						}
						if (($key->harga_kamar == 'NaN') || (empty($key->harga_kamar)) || ($key->harga_kamar == '') || ($key->harga_kamar == ' ') || ($key->harga_kamar == NULL)) {
							$harga_kamar = 0;
						} else {
							$harga_kamar = $key->harga_kamar;
						}
						$row   = array();
						// $row[] =  '<a class="btn btn-icon btn-trigger delete_tor" style="display: ' . $show . '" id="' . $key->id . '" data="' . $key->request_id . '" onClick="delete_tor(' . $key->id . ')"><em class="icon ni ni-cross-circle-fill"></em></a>';
						// if ($key->is_status == ) {
						// 	# code...
						// } else {
						// 	# code...
						// }
					
						$row[] = '<div class="btn-group btn-group-sm">
												<a class="btn btn-icon btn-trigger delete_tor" style="display: ' . $show . '" id="' . $key->id . '" data="' . $key->request_id . '" onClick="delete_tor(' . $key->id . ')"><em class="icon ni ni-cross-circle-fill"></em></a>
												'.$edit.'	
		                  </div>'
								;
						$row[] =  $key->tor_grandparent . ' - ' . $key->tor_parent . ' - ' . $key->tor_child;
						$row[] =  $key->jumlah_kuitansi;
						$row[] =  number_format($key->total_kuitansi);
						$row[] =  $key->tanggal_kuitansi;
						$row[] =  number_format($key->penggantian);
						$row[] =  $key->keterangan . ' - ' . ucwords(strtolower($additional));
						$row[] =  number_format($harga_kamar);
						$row[] =  $key->docter;
						$row[] =  $key->diagnosa;

						$data[] = $row;
					}
					$output = array('data' => $data);
				} else {
					$output = array('data' => new ArrayObject());
				}
				echo json_encode($output);
				break;


			default:
				break;
		}
	}

	public function save($method, $formType = "")
	{
		switch ($method) {

			case 'request_notes':

				$id = $this->input->post('request_id');
				$field['request_id'] = $id;
				$field['notes'] = $this->input->post('notes');
				$field['created_by'] = $this->email;
				$field['created_at'] = $this->date;

				if ($this->db->insert('request_notes', $field)) {
					$this->logs('save_request_notes', $id, 'Request Notes');
					$response = array('status' => 1, 'request_id' => encode_url($id), 'messages' => 'Notes has been saved.');
				} else {
					$this->logs('system', $id, 'Failed while saving Request notes.');
					$response = array('status' => 0, 'messages' => 'There\s something wrong. Please try again.');
				}

				echo json_encode($response);
				 break;

			case 'delete_request_notes':

				$id_request = $this->input->post('id_request');
				$id_notes = $this->input->post('id_notes');

				if ($this->db->where(array('id' => $id_notes, 'request_id' => $id_request))->delete('request_notes')) {
					$this->logs('delete_request_notes', $id, 'Request Notes');
					$response = array('status' => 1, 'messages' => 'Notes has been deleted.');
				} else {
					$response = array('status' => 0, 'messages' => 'There\s something wrong. Please refresh the page and try again.');
				}

				echo json_encode($response);
				break;

			case 'add_kpi':

				$request_id = $this->input->post('request_id');
				$kpi_row = $this->m_global->find('id', $request_id, 'performance_appraisal')->row_array()['count_row_kpi'];
				$total_row_kpi = ($kpi_row + 1);

				$detail = array(
					'request_id' => $request_id,
					'objective' => $this->input->post('kpi_objective'),
					'measurement' => $this->input->post('kpi_measurement'),
					'target_per_year' => $this->input->post('kpi_target'),
					'achievement' => $this->input->post('kpi_achievement'),
					'target_vs_achievement' => $this->input->post('kpi_target_vs_achievement'),
					'score' => $this->input->post('kpi_score'),
					'time' => $this->input->post('kpi_time'),
					'total' => $this->input->post('kpi_total_row'),
					'created_by' => $this->email,
					'created_at' => $this->date
				);

				$header = array(
					'sub_total_weight' => $this->input->post('total_weight'),
					'sub_total_kpi' => $this->input->post('total_kpi'),
					'grand_total_kpi' => $this->input->post('grand_total_kpi'),
					'pre_final_score' => $this->input->post('pre_final_score'),
					'final_score' => $this->input->post('pre_final_score'),
					'count_row_kpi' => $total_row_kpi,
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);

				$this->db->insert('performance_appraisal_measurement', $detail);
				$kpi_id = $this->db->insert_id();

				if ($kpi_id != '') {
					$this->logs('add_kpi_row', 'KPI', $request_id);

					$this->db->where('id', $request_id);
					$updateHeader = $this->db->update('performance_appraisal', $header);

					if ($updateHeader) {
						$response = array('status' => 1, 'id' => $kpi_id);
					} else {
						$this->db->delete('performance_appraisal_measurement', array('id' => $kpi_id));
						$response = array('status' => 0);
					}
				} else {
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			case 'update_kpi':

				$request_id = $this->input->post('request_id');
				$id_detail = $this->input->post('id_detail');

				$detail = array(
					'objective' => $this->input->post('kpi_objective'),
					'measurement' => $this->input->post('kpi_measurement'),
					'target_per_year' => $this->input->post('kpi_target'),
					'achievement' => $this->input->post('kpi_achievement'),
					'target_vs_achievement' => $this->input->post('kpi_target_vs_achievement'),
					'score' => $this->input->post('kpi_score'),
					'time' => $this->input->post('kpi_time'),
					'total' => $this->input->post('kpi_total_row'),
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);

				$header = array(
					'sub_total_weight' => $this->input->post('total_weight'),
					'sub_total_kpi' => $this->input->post('total_kpi'),
					'grand_total_kpi' => $this->input->post('grand_total_kpi'),
					'pre_final_score' => $this->input->post('pre_final_score'),
					'final_score' => $this->input->post('pre_final_score'),
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);

				$this->db->where('id', $id_detail);
				$updateDetail = $this->db->update('performance_appraisal_measurement', $detail);

				if ($updateDetail) {
					$this->logs('update_kpi_row', 'KPI', $request_id, 'Update KPI item', 'Success update detail');

					$this->db->where('id', $request_id);
					$updateHeader = $this->db->update('performance_appraisal', $header);

					if ($updateHeader) {
						$this->logs('update_kpi_row', 'KPI', $request_id, 'Update KPI item', 'Success update header');
						$response = array('status' => 1);
					} else {
						$this->logs('update_kpi_row', 'KPI', $request_id, 'Update KPI item', 'Failed update header');
						$response = array('status' => 0);
					}
				} else {
					$this->logs('update_kpi_row', 'KPI', $request_id, 'Update KPI item', 'Failed update detail');
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			case 'add_plan':

				$request_id = $this->input->post('request_id');
				$perspective = $this->input->post('plan_perspective');

				switch ($perspective) {
					case 'financial_perspective':
						$count_plan = $this->m_global->find('id', $request_id, 'performance_appraisal')->row_array()['count_plan_financial'];
						$field = 'financial';
						break;
					case 'intern_perspective':
						$count_plan = $this->m_global->find('id', $request_id, 'performance_appraisal')->row_array()['count_plan_internal'];
						$field = 'internal';
						break;
					case 'cust_perspective':
						$count_plan = $this->m_global->find('id', $request_id, 'performance_appraisal')->row_array()['count_plan_customer'];
						$field = 'customer';
						break;
					case 'learn_perspective':
						$count_plan = $this->m_global->find('id', $request_id, 'performance_appraisal')->row_array()['count_plan_learning'];
						$field = 'learning';
						break;
					default:
						break;
				}

				$total_row_plan = ($count_plan + 1);

				$detail = array(
					'request_id' => $request_id,
					'objective' => $this->input->post('objective'),
					'measurement' => $this->input->post('measurement'),
					'time' => $this->input->post('time'),
					'unit' => $this->input->post('unit'),
					'target' => $this->input->post('target'),
					'semester_1' => $this->input->post('semester_1'),
					'semester_2' => $this->input->post('semester_2'),
					'total' => $this->input->post('total'),
					'plan_perspective' => $this->input->post('plan_perspective'),
					'created_by' => $this->email,
					'created_at' => $this->date
				);

				$header = array(
					'plan_total_weight' => $this->input->post('plan_total_weight'),
					'count_plan_' . $field => $total_row_plan,
					'created_by' => $this->email,
					'created_at' => $this->date
				);

				$this->db->insert('performance_appraisal_plan', $detail);
				$plan_id = $this->db->insert_id();

				if ($plan_id != '') {

					$this->db->where('id', $request_id);
					$updateHeader = $this->db->update('performance_appraisal', $header);

					if ($updateHeader) {
						$this->logs('add_plan_row', 'PLAN', $request_id);
						$response = array('status' => 1, 'id' => $plan_id);
					} else {
						$this->db->delete('performance_appraisal_plan', array('id' => $plan_id));
						$response = array('status' => 0);
					}
				} else {
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			case 'update_plan':

				$request_id = $this->input->post('request_id');
				$id_detail = $this->input->post('id_detail');

				$detail = array(
					'objective' => $this->input->post('plan_objective'),
					'measurement' => $this->input->post('plan_measurement'),
					'time' => $this->input->post('plan_new_time'),
					'unit' => $this->input->post('plan_unit'),
					'target' => $this->input->post('plan_target'),
					'semester_1' => $this->input->post('plan_semester_1'),
					'semester_2' => $this->input->post('plan_semester_2'),
					'total' => $this->input->post('plan_total'),
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);

				$header = array(
					'plan_total_weight' => $this->input->post('new_total_weight'),
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);

				$this->db->where('id', $id_detail);
				$updateDetail = $this->db->update('performance_appraisal_plan', $detail);

				if ($updateDetail) {
					$this->logs('update_plan_row', 'PLAN', $request_id, 'Update Plan item', 'Success update detail');

					$this->db->where('id', $request_id);
					$updateHeader = $this->db->update('performance_appraisal', $header);

					if ($updateHeader) {
						$this->logs('update_plan_row', 'PLAN', $request_id, 'Update Plan item', 'Success update header');
						$response = array('status' => 1);
					} else {
						$this->logs('update_plan_row', 'PLAN', $request_id, 'Update Plan item', 'Failed update header');
						$response = array('status' => 0);
					}
				} else {
					$this->logs('update_plan_row', 'PLAN', $request_id, 'Update Plan item', 'Failed update detail');
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			case 'submit':

				$request_id = $this->input->post('id');
				$is_status = $this->input->post('is_status');

				$save = $this->form_model->save_form($formType, $this->input->post());

				if ($save) {

					if ($this->saveApproval($request_id)) {
						$email = $this->input->post('approval_layer');
						$first_layer = $email[0];
						$this->sendEmail('need_response', $request_id, $first_layer);

						$this->logs('submit_request', $formType, $request_id);
						$response = array('status' => 1);
					} else {
						$this->logs('system', $formType, $request_id, 'Failed when saving approval layer');
						$response = array('status' => 0);
					}
				} else {
					$this->logs('system', $formType, $requestId, 'Failed when saving data request');
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			case 'submit_new_employee':

				$request_id = $this->input->post('id');
				$is_status = $this->input->post('is_status');

				$save = $this->form_model->save_form($formType, $this->input->post());

				if ($save) {

					if ($this->saveApproval($request_id)) {

						$email = $this->input->post('approval_layer');
						$first_layer = $email[0];
						$this->sendEmail('need_response', $request_id, $first_layer);

						$this->logs('submit_request', $formType, $request_id);
						$response = array('status' => 1);
					} else {
						$this->logs('system', $formType, $request_id, 'Failed when saving approval layer');
						$response = array('status' => 0);
					}
				} else {
					$this->logs('system', $formType, $requestId, 'Failed when saving data request');
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			case 'pre_final_score':

				$request_id = $this->input->post('request_id');

				$formData = array(
					'pre_final_score' => $this->input->post('pre_final_score'),
					'grand_total_kpi' => $this->input->post('grand_total_kpi'),
					'grand_total_qualitative' => $this->input->post('grand_total_qualitative'),
					'updated_by' => $this->email,
					'updated_at' => $this->date
				);

				$this->db->where('id', $request_id);
				$updateHeader = $this->db->update('performance_appraisal', $formData);

				if ($updateHeader) {
					$response = array('status' => 1);
				} else {
					$response = array('status' => 0);
				}

				echo json_encode($response);
				break;

			default:
				break;
		}
	}

	public function saveApproval($request_id)
	{
		$transok = false;
		$layer = $this->input->post('approval_layer');
		if (!empty($layer)) {
			return $this->form_model->saveApproval($layer, $request_id);
		} else {
			return false;
		}
	}

	public function saveApprovalMDCR($tor, $request_id, $is_status)
	{
		$data['form_request'] = $this->m_global->find('id', $request_id, 'form_request')->row_array();

		switch ($tor) {
			case 'rawat_inap':
				$this->db->order_by('id_employee', 'DESC');
				$data_layer = $this->db->get_where('hris_employee', array('nik' => $this->emp_id))->row_array();
				// dumper(decrypt($data_layer['department_head']));
				// dumper(encrypt('00000000'));
				$list_email = array();
				// if ($data_layer['usrid_long1'] != '') { array_push($list_email, strtolower(decrypt($data_layer['usrid_long1']))); } // superior
				// if ($data_layer['usrid_long2'] != '') { array_push($list_email, strtolower(decrypt($data_layer['usrid_long2']))); } // dept head
				// if ($data_layer['usrid_long3'] != '') { array_push($list_email, strtolower(decrypt($data_layer['usrid_long3']))); } // div head
				// if ($data_layer['usrid_long4'] != '') { array_push($list_email, strtolower(decrypt($data_layer['usrid_long4']))); } // director


				$list_nik = array();
				if (($data_layer['superior'] != '') && ($data_layer['superior'] != ' ') && ($data_layer['superior'] != '88888888') && ($data_layer['superior'] != '00000000') && ($data_layer['superior'] != '8') && ($data_layer['superior'] != '0')) {
					array_push($list_nik, strtolower(decrypt($data_layer['superior'])));
					array_push($list_email, strtolower(decrypt($data_layer['usrid_long1'])));
				} // superior
				if (($data_layer['department_head'] != '') && ($data_layer['department_head'] != '88888888') && ($data_layer['department_head'] != '00000000') && ($data_layer['department_head'] != '8') && ($data_layer['department_head'] != '0')) {
					array_push($list_nik, strtolower(decrypt($data_layer['department_head'])));
					array_push($list_email, strtolower(decrypt($data_layer['usrid_long2'])));
				} // dept head
				// dumper($list_nik);
				if (($data_layer['division_head'] != '') && ($data_layer['division_head'] != '88888888') && ($data_layer['division_head'] != '00000000') && ($data_layer['division_head'] != '8') && ($data_layer['division_head'] != '0')) {
					array_push($list_nik, strtolower(decrypt($data_layer['division_head'])));
					array_push($list_email, strtolower(decrypt($data_layer['usrid_long3'])));
				} // div head
				if (($data_layer['director'] != '') && ($data_layer['director'] != '88888888') && ($data_layer['director'] != '00000000') && ($data_layer['director'] != '8') && ($data_layer['director'] != '0')) {
					array_push($list_nik, strtolower(decrypt($data_layer['director'])));
					array_push($list_email, strtolower(decrypt($data_layer['usrid_long4'])));
				} // director

				//dumper($data_layer);
				if (count($list_email) > 0 && count($list_nik) > 0) {
					$layer = array($list_email[0],'hr.support@ibsmulti.com', 'gladys.christine@ibsmulti.com');
					// $layer_nik = array($list_nik[0],'00000000', '00000000');
					// $layer_nik = array($list_nik[0],'00000000', $list_nik[2]);
					// $layer_nik = array($list_nik[0],'00000000', '20221003');
					$layer_nik = array($list_nik[0],'00000000', '20210086');

					$this->sendEmail('request_approve_mdcr', $request_id, $list_email[0], $list_nik[0]);
					// $this->sendEmail('request_approve_mdcr', $request_id, 'muhammad.zulvan@ibsmulti.com', '');

					$config['cacheable']    = true;
					$config['cachedir']     = './assets/';
					$config['errorlog']     = './assets/';
					$config['imagedir']     = './assets/images/qrcode_mdcr/';
					$config['quality']      = true;
					$config['size']         = '1024';
					$config['black']        = array(224, 255, 255);
					$config['white']        = array(70, 130, 180);
					$this->ciqrcode->initialize($config);

					$image_name_SPR = $data['form_request']['request_number'] . '-ApprovedBySPR.png';
					$paramsSPR['data'] = $data['form_request']['request_number'] . '-ApprovedBySPR-' . $list_email[0];
					$paramsSPR['level'] = 'H'; //H=High
					$paramsSPR['size'] = 10;
					$paramsSPR['savename'] = FCPATH . $config['imagedir'] . $image_name_SPR;
					$this->ciqrcode->generate($paramsSPR);

					$image_name_HR = $data['form_request']['request_number'] . '-ApprovedByHR.png';
					$paramsHR['data'] = $data['form_request']['request_number'] . '-ApprovedByHR-hr.support@ibsmulti.com';
					$paramsHR['level'] = 'H'; //H=High
					$paramsHR['size'] = 10;
					$paramsHR['savename'] = FCPATH . $config['imagedir'] . $image_name_HR;
					$this->ciqrcode->generate($paramsHR);
				}

				break;
			case 'non_rawat_inap':
				$layer = array('hr.support@ibsmulti.com', 'gladys.christine@ibsmulti.com');
				$layer_nik = array('00000000', '20210086');
				$this->sendEmail('request_approve_mdcr', $request_id, 'hr.support@ibsmulti.com', '00000000');
				// $this->sendEmail('request_approve_mdcr', $request_id, 'muhammad.zulvan@ibsmulti.com', '00000000');

				$config['cacheable']    = true;
				$config['cachedir']     = './assets/';
				$config['errorlog']     = './assets/';
				$config['imagedir']     = './assets/images/qrcode_mdcr/';
				$config['quality']      = true;
				$config['size']         = '1024';
				$config['black']        = array(224, 255, 255);
				$config['white']        = array(70, 130, 180);
				$this->ciqrcode->initialize($config);

				$image_name_HR = $data['form_request']['request_number'] . '-ApprovedByHR.png';
				$paramsHR['data'] = $data['form_request']['request_number'] . '-ApprovedByHR-hr.support@ibsmulti.com';
				$paramsHR['level'] = 'H'; //H=High
				$paramsHR['size'] = 10;
				$paramsHR['savename'] = FCPATH . $config['imagedir'] . $image_name_HR;
				$this->ciqrcode->generate($paramsHR);

				break;
		}
		//dumper($layer);
		if (!empty($layer)) {
			return $this->form_model->saveApproval($layer, $request_id, $layer_nik);
		} else {
			return false;
		}
	}

	public function delete($type)
	{
		switch ($type) {

			case 'kpi_item':

				$header_id = $this->input->post('request_id');
				$detail_id = $this->input->post('measurement_id');
				$kpi_row = $this->m_global->find('id', $header_id, 'performance_appraisal')->row_array()['count_row_kpi'];
				$total_row_kpi = ($kpi_row - 1);

				$updateHeader = array(
					'sub_total_weight' => $this->input->post('total_weight'),
					'sub_total_kpi' => $this->input->post('total_kpi'),
					'grand_total_kpi' => $this->input->post('grand_total_kpi'),
					'pre_final_score' => $this->input->post('pre_final_score'),
					'final_score' => $this->input->post('pre_final_score'),
					'count_row_kpi' => $total_row_kpi,
				);

				if ($this->db->where('id', $detail_id)->delete('performance_appraisal_measurement')) {
					// update Header
					$this->db->where('id', $header_id);
					if ($this->db->update('performance_appraisal', $updateHeader)) {

						$this->logs('delete_kpi_row', 'KPI', $header_id);
						$response = array('status' => 1, 'messages' => 'Delete item succesfully.');
					} else {
						$this->logs('system', 'KPI', $header_id, 'Delete KPI item', 'Failed');
						$response = array('status' => 0, 'messages' => 'Delete KPI item failed.');
					}
				} else {
					$this->logs('system', 'KPI', $header_id, 'Delete KPI item', 'Failed');
					$response = array('status' => 0, 'messages' => 'Delete KPI item failed.');
				}

				echo json_encode($response);
				break;

			case 'plan_item':

				$header_id = $this->input->post('request_id');
				$detail_id = $this->input->post('plan_id');
				$plan_perspective = $this->m_global->find('id', $detail_id, 'performance_appraisal_plan')->row_array()['plan_perspective'];

				switch ($plan_perspective) {
					case 'financial_perspective':
						$count_plan = $this->m_global->find('id', $header_id, 'performance_appraisal')->row_array()['count_plan_financial'];
						$field = 'financial';
						break;
					case 'intern_perspective':
						$count_plan = $this->m_global->find('id', $header_id, 'performance_appraisal')->row_array()['count_plan_internal'];
						$field = 'internal';
						break;
					case 'cust_perspective':
						$count_plan = $this->m_global->find('id', $header_id, 'performance_appraisal')->row_array()['count_plan_customer'];
						$field = 'customer';
						break;
					case 'learn_perspective':
						$count_plan = $this->m_global->find('id', $header_id, 'performance_appraisal')->row_array()['count_plan_learning'];
						$field = 'learning';
						break;
					default:
						break;
				}

				$total_plan_row = ($count_plan - 1);

				$updateHeader = array(
					'plan_total_weight' => $this->input->post('plan_total_weight'),
					'count_plan_' . $field => $total_plan_row
				);

				if ($this->db->where('id', $detail_id)->delete('performance_appraisal_plan')) {

					$this->db->where('id', $header_id);
					if ($this->db->update('performance_appraisal', $updateHeader)) {

						$this->logs('delete_plan_row', 'KPI', $header_id);
						$response = array('status' => 1, 'perspective' => $field, 'messages' => 'Delete Plan item succesfully.');
					} else {
						$this->logs('system', 'KPI', $header_id, 'Delete KPI item', 'Failed');
						$response = array('status' => 0, 'messages' => 'Delete Plan item failed.');
					}
				} else {
					$this->logs('system', 'KPI', $header_id, 'Delete Plan item', 'Failed');
					$response = array('status' => 0, 'messages' => 'Delete Plan item failed.');
				}

				echo json_encode($response);
				break;

			default:
				break;
		}
	}

	public function pullback()
	{
		$responses = array('status' => 0, 'message' => 'Failed pulling back your request. Please try again.');
		$id = $this->input->post('id');
		$approval_id = $this->form_model->getOneById('id', 'form_approval', array('request_id' => $id, 'approval_status' => 'In Progress'));

		$this->db->where('id', $id);
		if ($this->db->update('performance_appraisal', array('is_status' => 7))) {
			if ($this->db->delete('form_approval', array('request_id' => $id))) {
				$this->logs('pullback', 'KPI', $id);
				$responses = array('status' => 1, 'id' => encode_url($id));
			}
		}

		echo json_encode($responses);
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

			case 'create':
				$log['activity'] = 'Create new';
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'approved':
				$log['activity'] = 'Approved';
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'revised':
				$log['activity'] = 'Revised';
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'submit_request':
				$log['activity'] = 'Submit request';
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'pullback':
				$log['activity'] = 'Pullback request';
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'delete_kpi_row':
				$log['activity'] = 'Delete KPI item';
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'add_kpi_row':
				$log['activity'] = 'Add KPI item';
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'add_plan_row':
				$log['activity'] = 'Add Plan item';
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'update_kpi_row':
				$log['activity'] = $activity;
				$log['description'] = $description;
				$this->db->insert('logs', $log);
				break;

			case 'update_plan_row':
				$log['activity'] = $activity;
				$log['description'] = $description;
				$this->db->insert('logs', $log);
				break;

			case 'submit_ppd':
				$log['activity'] = 'Submit PPD';
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			case 'save_draft_ppd':
				$log['activity'] = 'Save Draft PPD';
				$log['description'] = 'Success';
				$this->db->insert('logs', $log);
				break;

			default:
				break;
		}
	}

	public function get_plan()
	{
		$id = $this->input->post('plan_id');
		$plan_perspective = $this->form_model->getOneById("plan_perspective", "performance_appraisal_plan", "id = '$id'");
		echo json_encode($plan_perspective);
	}

	public function getUserList()
	{
		$userList = $this->form_model->getAll("user_email", "users", "is_active = 1 AND user_email != '$this->email'");
		echo json_encode($userList);
	}

	private function getRequestNumber($formType, $table)
	{
		$this->db->select_max('id');
		$eid = $this->db->get($table)->row_array();
		$reqnum = $eid['id'] + 1;
		$requestNumber = 'EAPP_' . $formType . '_V2' . str_pad($reqnum, 6, 0, STR_PAD_LEFT);
		return $requestNumber;
	}

	private function getEmailSuperior($nik)
	{
		// print_r($nik);die;
		// $superior_nik = decrypt($this->db->get_where('hris_employee', array('nik' => $nik))->row_array()['superior']);
		//$superior_nik = '20131111';
		$superior_email = decrypt($this->db->get_where('hris_employee', array('nik' => $nik))->row_array()['email']);
		return $superior_email;
	}

	public function getDetailEmployee()
	{
		$nik = $this->input->post('employee_nik');
		$data = $this->db->get_where('hris_employee', array('nik' => $nik))->row_array();
		$grade = decrypt($data['employee_group']);

		$pagu = $this->m_global->find('grade', $grade, 'hris_trip_pagu')->row_array();
		$diem = $pagu['diem'];
		$hotel_primary_cities = $pagu['hotel_primary_cities'];
		$hotel_secondary_cities = $pagu['hotel_secondary_cities'];
		$tipe_penerbangan = $pagu['tipe_penerbangan'];
		$laundry = $pagu['laundry'];
		$bagasi = $pagu['bagasi'];

		$data_layer = $this->db->get_where('hris_employee', array('nik' => $nik))->row_array();
		$list_email = array();

		if ($data_layer['usrid_long5'] != '') {
			array_push($list_email, strtolower(decrypt($data_layer['usrid_long5'])));
		} // rpm
		if ($data_layer['usrid_long1'] != '') {
			array_push($list_email, strtolower(decrypt($data_layer['usrid_long1'])));
		} // superior
		if ($data_layer['usrid_long2'] != '') {
			array_push($list_email, strtolower(decrypt($data_layer['usrid_long2'])));
		} // dept head
		if ($data_layer['usrid_long3'] != '') {
			array_push($list_email, strtolower(decrypt($data_layer['usrid_long3'])));
		} // div head
		if ($data_layer['usrid_long4'] != '') {
			array_push($list_email, strtolower(decrypt($data_layer['usrid_long4'])));
		} // director
		$data['approval_layer'] = $list_email;

		// print_r($data['approval_layer']);die;
		if ($nik != $this->emp_id) {
		}

		$data['layer_1'] = !empty($data['approval_layer'][0]) ? $data['approval_layer'][0] : '';
		$data['layer_2'] = !empty($data['approval_layer'][1]) ? $data['approval_layer'][1] : '';
		$data['hr_layer_1'] = 'hr.support@ibsmulti.com';
		$data['hr_layer_2'] = 'hr.support@ibsmulti.com';

		if (!empty($data)) {

			$response = array(
				'status' => true,
				'name' => decrypt($data['complete_name']),
				'email' => strtolower(decrypt($data['email'])),
				'division' => decrypt($data['division']),
				'position' => decrypt($data['position']),
				'cost_center' => decrypt($data['cost_center']),
				'lokasi_kantor' => decrypt($data['personnel_area']),
				'range_grade' => range_grade($grade),
				'nom_diem' => $diem,
				'nom_hotel' => $hotel_primary_cities,
				'tipe_penerbangan' => $tipe_penerbangan,
				'laundry' => $laundry,
				'bagasi' => $bagasi,
				'layer_1' => $data['layer_1'],
				'layer_2' => $data['layer_2'],
				'hr_layer_1' => $data['hr_layer_1'],
				'hr_layer_2' => $data['hr_layer_2'],
			);
		} else {
			$response = array('status' => false, 'message' => 'NIK Not Found');
		}
		echo json_encode($response);
	}

	public function update($flag = '')
	{
		switch ($flag) {
			case 'show-form':

				$id = $this->input->post('id');
				$detail_request = $this->m_approval->find_select("id, formPurpose, formNotes", array('id' => $id), 'form_request')->row_array();

				header('Content-type: application/json');
				echo json_encode(array(
					"request" => $detail_request,
				));

				break;

			case 'save':

				$response = array('status' => 0, 'message' => 'Failed while updating your request. Please try again.');

				if (isset($_POST['modalrequest_id'])) {

					$id = $_POST['modalrequest_id'];
					$requestNumber = $_POST['modalrequest_number'];
					$uploadDir = './upload/' . $requestNumber . '/';

					if (isset($_POST['modalformPurpose']) || isset($_POST['modalformNotes']) || isset($_FILES['afsUpload']['name']) || isset($_FILES['multiSupportingFiles']['name'])) {

						$formPurpose = $_POST['modalformPurpose'];
						$formNotes = $_POST['modalformNotes'];

						if (!empty($formPurpose) && !empty($formNotes)) {

							$uploadStatus = 1;

							$data = array('formPurpose' => $formPurpose, 'formNotes' => $formNotes);
							$this->db->where('id', $id);

							if ($this->db->update('form_request', $data)) {
								$uploadStatus = 1;
								$response['message'] = 'Request updated successfully.';
							} else {
								$uploadStatus = 0;
								$response['message'] = 'Sorry, there was an error while updating request data.';
							}

							// Upload file 
							$uploadedFile = '';
							if (!empty($_FILES['afsUpload']['name'])) {

								$type  = explode('.', $_FILES['afsUpload']['name']);
								$types = strtolower($type[count($type) - 1]);
								$uploadname = $requestNumber . '_' . uniqid() . '.' . $types;

								// File path config 
								$fileName = basename($uploadname);
								$targetFilePath = $uploadDir . $fileName;
								$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

								$allowTypes = array('pdf');
								if (in_array($fileType, $allowTypes)) {

									if (move_uploaded_file($_FILES["afsUpload"]["tmp_name"], $targetFilePath)) {

										$pdfVersion = "1.4";
										$newFile = './upload/' . $requestNumber . '/convert/' . $fileName;
										$currentFile = './upload/' . $requestNumber . '/' . $fileName;

										$gsCmd = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=$pdfVersion -dNOPAUSE -dBATCH -sOutputFile=$newFile $currentFile";

										$this->db->where('id', $id)->update('form_request', array('approvalFormScanned' => $fileName));

										// if (exec($gsCmd)) {

										// 	$uploadedFile = $fileName;
										// 	$this->db->where('id', $id)->update('form_request', array('approvalFormScanned' => $fileName));
										// } else {

										// 	$uploadStatus = 0;
										// 	$response['message'] = 'File uploaded successfully but failed to convert.';
										// }
									} else {
										$uploadStatus = 0;
										$response['message'] = 'Sorry, there was an error uploading Approval FIle Scanned file.';
									}
								} else {
									$uploadStatus = 0;
									$response['message'] = 'Sorry, only PDF & Excel files are allowed to upload.';
								}
							}

							if (!empty($_FILES['multiSupportingFiles']['name'])) {

								if (!is_dir('upload/' . $requestNumber . '/supporting_files/')) {
									mkdir('./upload/' . $requestNumber . '/supporting_files/', 0777, TRUE);
								}

								$count = count($_FILES['multiSupportingFiles']['name']);

								for ($i = 0; $i < $count; $i++) {

									if (!empty($_FILES['multiSupportingFiles']['name'][$i])) {

										$allowTypes = array('xlsx', 'xls', 'jpg', 'jpeg', 'png', 'pptx', 'doc', 'docx', 'pdf');
										$path =  getcwd() . '/upload/' . $requestNumber . '/supporting_files/';
										$names  = $_FILES['multiSupportingFiles']['name'][$i];
										$uploadname = str_replace(' ', '_', $names);

										// File path config 
										$fileName = basename($uploadname);
										$targetFilePath = $path . $fileName;
										$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

										if (in_array($fileType, $allowTypes)) {

											if (move_uploaded_file($_FILES["multiSupportingFiles"]["tmp_name"][$i], $targetFilePath)) {
												$uploadedFile = $fileName;
											} else {
												$uploadStatus = 0;
												$response['message'] = 'There was an error uploading supporting files. Please refresh the page and try again.';
											}
										} else {
											$uploadStatus = 0;
											$response['message'] = 'Sorry, please check your file format again.';
										}
									}
								}
							}

							if ($uploadStatus == 1) {
								$response['status'] = 1;
								$response['message'] = 'Request form updated successfully!';
							}
						} else {
							$response['message'] = 'Please fill all the mandatory fields (Purpose and Notes).';
						}
					}
				}

				echo json_encode($response);
				break;

			case 'add-layer':

				$id = $this->input->post('id');
				header('Content-type: application/json');
				echo json_encode(array("id" => $id));
				break;

			case 'save-add-layer':

				$response = array('status' => 0, 'message' => 'Failed while saving new layer.');
				$request_id = $_POST['req_id_add_layer'];
				$layer = $this->input->post('emailuser');

				## Get last layer
				$sql = "SELECT TOP 1 id, approval_priority, approval_status FROM form_approval WHERE request_id = $request_id ORDER BY approval_priority DESC ";
				$last_layer = $this->db->query($sql)->row_array();
				$approval_priority = $last_layer['approval_priority'];
				$approval_status = $last_layer['approval_status'];

				if (empty($approval_status)) {
					$approval_status = 'empty';
				}

				if ($this->m_approval->addLayer($approval_priority, $approval_status, $layer, $request_id)) {
					$transok = true;
				} else {
					$this->logs('system', $request_id, 'Failed while saving new layer.');
					$transok = false;
				}

				if ($transok) {
					$request_status = $this->m_approval->find('id', $request_id, 'form_request')->row_array()['is_status'];
					$response = array('status' => 1, 'message' => 'New layer has been added.', 'request_status' => $request_status);
				}

				header('Content-type: application/json');
				echo json_encode($response);
				break;

			case 'save-change-layer':

				$response = array('status' => 0, 'message' => 'Failed while changing layer.');
				$id = $this->input->post('req_id_change_layer');
				$approval_id = $this->input->post('app_id_change_layer');
				$email = $this->input->post('email_user');

				if ($email === 'handra@ibstower.com' || $email === 'farida@ibstower.com') {
					$alias = 'Commitee';
				} else {
					$alias = str_replace('@ibstower.com', '', $email);
				}

				if ($this->db->where('id', $approval_id)->update('form_approval', array('approval_email' => $email, 'approval_alias' => $alias))) {
					$transok = true;
				} else {
					$this->logs('system', $id, 'Failed while changing layer.');
					$transok = false;
				}

				if ($transok) {
					$response = array('status' => 1, 'message' => 'Layer changed successfully.');
				}

				header('Content-type: application/json');
				echo json_encode($response);
				break;

			case 'remove-layer':

				$response = array('status' => 0, 'message' => 'Failed while removing layer.');
				$request_id = $this->input->post('id');
				$approval_id = $this->input->post('approval_id');

				$sql = "SELECT approval_email, approval_priority, approval_status FROM form_approval WHERE id = $approval_id";
				$current_layer = $this->db->query($sql)->row_array();

				$approval_priority = $current_layer['approval_priority'];
				$approval_status = $current_layer['approval_status'];
				$approval_email = $current_layer['approval_email'];

				if ($this->m_approval->removeLayer($request_id, $approval_id, $approval_email, $approval_priority, $approval_status)) {
					$transok = true;
				} else {
					$this->logs('system', $request_id, 'Failed while removing layer.');
					$transok = false;
				}

				if ($transok) {
					$request_status = $this->m_approval->find('id', $request_id, 'form_request')->row_array()['is_status'];
					$response = array('status' => 1, 'message' => 'Selected layer has been removed.', 'request_status' => $request_status);
				}

				header('Content-type: application/json');
				echo json_encode($response);
				break;

			default:
				break;
		}
	}

	public function add_notes()
	{
		$transok = 0;
		$notes = array(
			'request_id' => $this->input->post('request_id'),
			'created_by' => $this->email,
			'created_at' => $this->date,
			'approval_response' => 'Add Notes',
			'approval_notes' => $this->input->post('requestor_notes'),
		);

		if ($this->db->insert('logs', $notes)) {
			$transok = 1;
		}

		echo json_encode($transok);
	}

	// public function sendMail()
	// {
	// 	// $to                 = $this->request->getPost('to');
	// 	//   $subject            = $this->request->getPost('subject');
	// 	//   $message            = $this->request->getPost('message');


	// 	// try {
	// 	$mail = new PHPMailer();
	// 	// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
	// 	$mail->isSMTP();
	// 	$mail->Host       = 'mail.ibsmulti.com';
	// 	$mail->SMTPAuth   = true;
	// 	$mail->Username   = 'no.reply@ibsmulti.com'; // ubah dengan alamat email Anda
	// 	$mail->Password   = '12345@2022No.Reply'; // ubah dengan password email Anda
	// 	$mail->SMTPSecure = 'tls';
	// 	$mail->Port       = 587;

	// 	$mail->setFrom('no.reply@ibsmulti.com', 'Notification System'); // ubah dengan alamat email Anda
	// 	$mail->addAddress('muhammad.zulvan@ibsmulti.com');

	// 	// Isi Email
	// 	$mail->isHTML(true);
	// 	$mail->Subject = 'test';
	// 	$mail->Body    = 'email test';

	// 	$mail->send();

	// 	// Pesan Berhasil Kirim Email/Pesan Error

	// 	//     session()->setFlashdata('success', 'Selamat, email berhasil terkirim!');
	// 	//     return redirect()->to('/email');
	// 	// } catch (Exception $e) {
	// 	//     session()->setFlashdata('error', "Gagal mengirim email. Error: " . $mail->ErrorInfo);
	// 	//     return redirect()->to('/email');

	// }

	public function sendEmail($type, $requestId, $email_to, $employee_id = "")
	{
		$data['form_request'] = $this->m_global->find('id', $requestId, 'form_request')->row_array();
		$data['data_employee'] = $this->form_model->get_data_employee($data['form_request']['employee_id']);
		$data['get_data_claim'] = $this->form_model->get_data_claim_per_request($requestId);
		$data['approval'] = $this->m_global->find('request_id', $requestId, 'form_approval')->result_array();

		// var_dump(decrypt($data['data_employee'][0]->email));
		//dumper($type." - ".$requestId." - ".$email_to." - ".$employee_id);
		if ($type == 'checked_mdcr_hr') {
			$data['email'] = decrypt($data['data_employee'][0]->email);
			$email_to 	   = decrypt($data['data_employee'][0]->email);
			
			$data['complete_name'] = decrypt($data['data_employee'][0]->complete_name);
			
			$html = $this->load->view('services/email/checked_mdcr_hr', $data, TRUE);
			$email_subject = 'IBSW-Medical Claim Checked By HR Support';
		} elseif ($type == 'request_approve_mdcr') {
			$data['email'] = $email_to;
			$data['data_employee_approver'] = $this->form_model->get_data_employee($employee_id);
			$html = $this->load->view('services/email/request_approve_mdcr', $data, TRUE);
			$email_subject = 'IBSW-Medical Claim Request Approve';
		} elseif ($type == 'approved_spv_mdcr') {
			// $email_to 	   = decrypt($data['data_employee'][0]->email);
			// $data['email'] = $email_to;

			// $email_to 	   = $email_to;
			$data['email'] = decrypt($data['data_employee'][0]->email);
			$data['data_employee_approver'] = $this->form_model->get_data_employee($employee_id);
			$html = $this->load->view('services/email/request_approve_mdcr', $data, TRUE);
			$email_subject = 'IBSW-Medical Claim Request Approved';
		}

		// $url = 'http://dev-medclaim.ibsmulti.com';
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

		// 	$htmlContent = '<h3 align=center>EMAIL AM ONLINE </h3>';
		// $htmlContent .= '<hr>';
		// $htmlContent .= '<br>';
		// $htmlContent .= '<div style=border:1px solid black;';

		// $htmlContent .= '<p><b>NOTIFICATION</b></p>';
		// $htmlContent .= '<p>New notification, from AM Online</p>';
		// $htmlContent .= '<p>AM NUMBER : '.$am.'</p>';
		// $htmlContent .= '<p>PURPOSE   : '.$description.'</p>';
		// $htmlContent .= '<hr>';
		// $htmlContent .= '<p>STATUS :'.$status.'</p>';

		// $htmlContent .= '<p>please check the am online application, <a href=http://mis.ibsmulti.com/amonline/>HERE</a></p>';


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

		// Isi Email
		$mail->isHTML(true);
		$mail->Subject = $email_subject;
		$mail->Body    = $html;

		$mail->send();
		// $this->email->print_debugger(array('headers'));

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
	}

	public function getRF($region_code)
	{
		if ($region_code == 'CJ') {
			$office_location = 'IBST - SEMARANG';
		} elseif ($region_code == 'EJ') {
			$office_location = 'IBST - SURABAYA';
		} elseif ($region_code == 'WJ') {
			$office_location = 'IBST - BANDUNG';
		} elseif ($region_code == 'NS') {
			$office_location = 'IBST - MEDAN';
		} elseif ($region_code == 'SS') {
			$office_location = 'IBST - PALEMBANG';
		} elseif ($region_code == 'SUL') {
			$office_location = 'IBST - MAKASAR';
		} elseif ($region_code == 'JABO') {
			$office_location = 'IBST - JAKARTA';
		}

		$regional_finance = $this->form_model->getRow("employee_name, user_email", "employee", "is_active = 1 AND position_level = 'RF' AND office_location = '$office_location'");
		echo json_encode(array('region_finance' => $regional_finance, 'status' => 1));
	}

	// SCAN DOCUMENTS UPLOAD
	public function read_documents($request_number)
	{
		$dir = './uploaded_files/' . $request_number . '/supporting_files/';
		$response = $this->doScan($dir);

		if (!empty($response)) {
			foreach ($response as $key => $value) {
				$row   = array();
				$row[] = $value['name'];
				$row[] = $value['size'];
				$row[] = '<div class="btn-group btn-group-sm">
                                <a class="btn btn-sm btn-danger" style="cursor:pointer;" href="' . site_url('home/archive/details/' . encode_url($value['name'])) . '">
                                    X
                                </a>
                        </div>';
				$data[] = $row;
			}
			$output = array('data' => $data);
		} else {
			$output = array('data' => new ArrayObject());
		}

		header('Content-type: application/json');
		echo json_encode($output);
	}

	public function scan()
	{
		$id = $this->input->post('id');
		$requestNumber = $this->form_model->find('id', $id, 'form_request')->row_array()['requestNumber'];
		$status = $this->form_model->find('id', $id, 'form_request')->row_array()['is_status'];

		$dir =  './uploaded_files/' . $requestNumber . '/supporting_files/';
		$path =  '/uploaded_files/' . $requestNumber . '/supporting_files/';
		$response = $this->doScan($dir);

		header('Content-type: application/json');
		echo json_encode(array(
			// "name" => "files",
			"id" => $id,
			"flag" => $status,
			"items" => $response
		));
	}

	public function doScan($dir)
	{

		$files = array();

		// Is there actually such a folder/file?

		if (file_exists($dir)) {

			foreach (scandir($dir) as $f) {

				if (!$f || $f[0] == '.') {
					continue; // Ignore hidden files
				}

				if (is_dir($dir . '/' . $f)) {

					// The path is a folder

					$files[] = array(
						"name" => $f,
						"type" => "folder",
						"path" => $dir . '/' . $f,
						"items" => scan($dir . '/' . $f) // Recursively get the contents of the folder
					);
				} else {

					// It is a file

					$files[] = array(
						"name" => $f,
						"type" => "file",
						"path" => $dir . '/' . $f,
						"size" => $this->formatSizeUnits(filesize($dir . '/' . $f)) // Gets the size of this file
					);
				}
			}
		}

		return $files;
	}

	// UPLOAD SUPPORTING FILES
	public function upload_documents($apps, $requestNumber)
	{
		$transok = true;
		if (!is_dir('uploaded_files/' . $requestNumber . '/supporting_files/')) {
			mkdir('./uploaded_files/' . $requestNumber . '/supporting_files/', 0777, TRUE);
		}

		if (isset($_FILES['file'])) {
			$path =  getcwd() . '/uploaded_files/' . $requestNumber . '/supporting_files/';
			$fileName = $_FILES['file']['name'];
			$new_filename = str_replace(' ', '_', $fileName);
			$tempFile = $_FILES['file']['tmp_name'];
			$targetFile = $path . $new_filename;

			if (!move_uploaded_file($tempFile, $targetFile)) {
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_flashdata('error', $error['error']);
				$transok = false;
			}
		}

		return $transok;
	}

	public function formatSizeUnits($bytes)
	{
		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' bytes';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' byte';
		} else {
			$bytes = '0 bytes';
		}

		return $bytes;
	}



	////////////////////////////////////////////Tambah TOR Medical//////////////////////////////////////

	public function tambah_tor()
	{
		$request_id 			= $_POST['request_id'];
		$tor_grandparent 		= $_POST['tor_grandparent'];
		$tor_parent 			= $_POST['tor_parent'];
		$tor_child 				= $_POST['tor_child'];
		$jumlah_kuitansi		= $_POST['jumlah_kuitansi'];
		$total_kuitansi			= $_POST['total_kuitansi'];
		$tanggal_kuitansi		= $_POST['tanggal_kuitansi'];
		$penggantian			= $_POST['penggantian'];
		$request_family			= $_POST['request_family'];
		$additional 			= $_POST['additional'];
		$docter		 			= $_POST['docter'];
		$harga_kamar			= $_POST['harga_kamar'];
		$diagnosa	 			= $_POST['diagnosa'];

		//dumper($request_id." - ".$tor_grandparent." - ".$tor_parent." - ".$tor_child." - ".$jumlah_kuitansi." - ".$total_kuitansi." - ".$penggantian." - ".$request_family." - ".$additional);

		$data = $this->form_model->tambah_tor($request_id, $tor_grandparent, $tor_parent, $tor_child, $jumlah_kuitansi, $total_kuitansi, $penggantian, $request_family, $additional, $docter, $diagnosa, $tanggal_kuitansi, $harga_kamar);
		echo json_encode($data);
	}

	public function delete_tor()
	{
		$id				= $_POST['id'];
		$data = $this->form_model->delete_tor($id);
		echo json_encode($data);
	}

	public function edit_tor()
	{
		$id = $_POST['id'];
		$data = $this->form_model->edit_tor($id);
		// dumper($data);
		echo json_encode($data);
	}

	public function get_sum_penggantian_jalan()
	{
		$request_id				= $_POST['request_id'];
		
		$data = $this->form_model->get_sum_penggantian_jalan($request_id);
		
		echo json_encode($data);
	}

	public function get_sum_penggantian_inap()
	{
		$request_id				= $_POST['request_id'];
		$data = $this->form_model->get_sum_penggantian_inap($request_id);
		echo json_encode($data);
	}

	public function get_sum_penggantian_kacamata()
	{
		$request_id				= $_POST['request_id'];
		$data = $this->form_model->get_sum_penggantian_kacamata($request_id);
		echo json_encode($data);
	}

	public function request_submited_mdcr()
	{
		$request_id				= $_POST['request_id'];
		$is_status				= $_POST['is_status'];
		$data = $this->form_model->request_submited_mdcr($request_id);
		$detail = $this->form_model->cekTypeOfRembursement($request_id);
		$tor = 'non_rawat_inap';
		foreach ($detail as $key => $value) {
			if ($value['tor_grandparent'] == '2') {
				$tor = 'rawat_inap';
			}
		}
		if ($data) {
			$this->saveApprovalMDCR($tor, $request_id, $is_status);
		}
		echo json_encode($data);
	}

	public function getFamilyChild()
	{
		$tanggal_kuitansi				= $_POST['tanggal_kuitansi'];
		$data = $this->form_model->getFamilyChild($tanggal_kuitansi);
		echo json_encode($data);
	}

	public function getFamilySpouse()
	{
		$tanggal_kuitansi				= $_POST['tanggal_kuitansi'];
		$data = $this->form_model->getFamilySpouse($tanggal_kuitansi);
		echo json_encode($data);
	}

	public function getDetailFamilySpouse() {
		$id = $_POST['id'];
		$employee_id = $_POST['employee_id'];
		// $id = decrypt($_POST['id']);
		// dumper($id);
		// $record = $this->form_model->edit_tor($id);
		$data = $this->form_model->getDetailFamilySpouse($id, $employee_id);
		echo json_encode($data);
	}

	public function getDetailFamilyChild()
	{
		$id = $_POST['id'];
		$tanggal_kuitansi = $_POST['tanggal_kuitansi'];
		$employee_id = $_POST['employee_id'];
		// $id = decrypt($_POST['id']);
		// dumper($id);
		// $record = $this->form_model->edit_tor($id);
		// dumper($record);
		$data = $this->form_model->getDetailFamilyChild($id, $tanggal_kuitansi, $employee_id);
		// dumper($data);
		echo json_encode($data);
	}

	public function cek_tanggal_pengambilan_kacamata()
	{
		$request_id				= $_POST['request_id'];
		$kind				= $_POST['kind'];
		if ($kind == 'tambah') {
			$request_grandparent 	= $_POST['request_grandparent_tambah'];
			$request_parent			= $_POST['request_parent_tambah'];
			$request_child 			= $_POST['request_child_tambah'];
		} else {
			$request_grandparent 	= $_POST['request_grandparent_edit'];
			$request_parent			= $_POST['request_parent_edit'];
			$request_child 			= $_POST['request_child_edit'];
		}
		
		$data = $this->form_model->cek_tanggal_pengambilan_kacamata($request_id, $request_grandparent, $request_parent, $request_child);
		echo json_encode($data);
	}

	public function get_Grandparent()
	{
		$data = $this->form_model->get_Grandparent();
		echo json_encode($data);
	}

	public function edit_Grandparent(){
		$id = $_POST['id'];
		$data = $this->form_model->edit_get_Grandparent($id);
		echo json_encode($data);
	}

	public function get_Parent()
	{
		if ($this->input->post('grandparent')) {
			// echo $this->form_model->get_Parent($this->input->post('grandparent'));
			$data = $this->form_model->get_Parent($this->input->post('grandparent'), $this->input->post('employee_group'));
			echo json_encode($data);
		}
	}

	public function edit_Parent(){
		$id = $_POST['id'];
		$record = $this->form_model->edit_tor($id);
		$data = $this->form_model->edit_get_Parent($record, $this->session->userdata('employee_group'));
		echo json_encode($data);
	}

	public function get_Child()
	{
		if ($this->input->post('parent')) {
			echo $this->form_model->get_Child($this->input->post('parent'));
			// $data = $this->form_model->get_Child($this->input->post('parent'));
			// echo json_encode($data);
		}
	}

	public function edit_Child(){
		$id = $_POST['id'];
		$record = $this->form_model->edit_tor($id);
		$data = $this->form_model->edit_get_Child($record);
		echo json_encode($data);
	}

	public function update_tor()
	{
		$request_id 			= $_POST['request_id'];
		$tor_grandparent 		= $_POST['tor_grandparent'];
		$tor_parent 			= $_POST['tor_parent'];
		$tor_child 				= $_POST['tor_child'];
		$jumlah_kuitansi		= $_POST['jumlah_kuitansi'];
		$total_kuitansi			= $_POST['total_kuitansi'];
		$tanggal_kuitansi		= $_POST['tanggal_kuitansi'];
		$penggantian			= $_POST['penggantian'];
		$request_family			= $_POST['request_family'];
		$additional 			= $_POST['additional'];
		$docter		 			= $_POST['docter'];
		$harga_kamar			= $_POST['harga_kamar'];
		$diagnosa	 			= $_POST['diagnosa'];

		//dumper($request_id." - ".$tor_grandparent." - ".$tor_parent." - ".$tor_child." - ".$jumlah_kuitansi." - ".$total_kuitansi." - ".$penggantian." - ".$request_family." - ".$additional);

		$data = $this->form_model->update_tor($request_id, $tor_grandparent, $tor_parent, $tor_child, $jumlah_kuitansi, $total_kuitansi, $penggantian, $request_family, $additional, $docter, $diagnosa, $tanggal_kuitansi, $harga_kamar);
		echo json_encode($data);
	}

	public function update_price()
	{
		$record_id 			= $_POST['record_id'];
		$penggantian_old 		= $_POST['old_penggantian'];
		$penggantian_revisi 			= $_POST['penggantian_revisi'];
		$note_penggantian 			= $_POST['note_penggantian'];

		//dumper($request_id." - ".$tor_grandparent." - ".$tor_parent." - ".$tor_child." - ".$jumlah_kuitansi." - ".$total_kuitansi." - ".$penggantian." - ".$request_family." - ".$additional);

		$data = $this->form_model->update_price($record_id, $penggantian_old, $penggantian_revisi, $note_penggantian);
		echo json_encode($data);
	}

	//////////////////////////////////////////Additional Form MDCR/////////////////////////////////////

	public function cek_addtional_mdcr()
	{
		$request_id		= $_POST['request_id'];
		$data 			= $this->form_model->cek_additional_table($request_id);
		echo json_encode($data);
	}

	public function cek_limit_harga_kamar()
	{
		$data 			= $this->form_model->cek_limit_harga_kamar();
		echo json_encode($data);
	}

	public function cek_limit_maternity()
	{
		$status			= $_POST['status'];
		$data 			= $this->form_model->cek_limit_maternity($status);
		echo json_encode($data);
	}

	public function save_additional_mdcr()
	{

		$request_id = $_POST['request_id_additional'];
		$kuitansi 	= $_POST['kuitansi'];
		$resep 		= $_POST['resep'];


		if ($resep == "on") {
			$resep = 1;
		} else {
			$resep = 0;
		};

		$cek = $this->form_model->cek_additional_table($request_id);

		if (empty($cek)) {

			$config = [
				'upload_path' => './assets/documents/documents_hris/',
				'allowed_types' => '*',
				'max_size' => 10000000000, 'max_width' => 10000000000,
				'max_height' => 10000000000
			];

			if (($_FILES['FileDocumentsClaim']['error'] == 4)) {
				// echo "<script>
				// 		alert('No file was uploaded');
				// 		javascript:history.back();
				// 	</script>";
				$result = 0;
				echo json_encode($result);
			} else if (($_FILES['FileDocumentsClaim']['error'] == 7)) {
				// echo "<script>
				// 		alert('Failed to write file to disk');
				// 		javascript:history.back();
				// 	</script>";
				$result = 0;
				echo json_encode($result);
			} else if (($_FILES['FileDocumentsClaim']['error'] == 1) || ($_FILES['FileDocumentsClaim']['error'] == 2)) {
				// echo "<script>
				// 		alert('Upload Max File Size 10Mb or Format Not Support');
				// 		javascript:history.back();
				// 	</script>";
				$result = 0;
				echo json_encode($result);
			}

			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('FileDocumentsClaim')) //jika gagal upload
			{
				// dumper($this->upload->display_errors());
				$error = array('error' => $this->upload->display_errors()); //tampilkan error

			} else {

				$file = $this->upload->data();
				$data = [
					'documents' => $file['file_name'],
					'request_id' => $request_id,
					'kuitansi' => $kuitansi,
					'resep' => $resep
				];
				$result = $this->form_model->save_additional_mdcr($data);
				//dumper($result);
				if ($result == '1') {
					// echo "<script>
					// alert('Success submitting form');
					// javascript:history.back();
					// </script>";
					echo json_encode($result);
				} else {
					// echo "<script>
					// alert('Error submitting form');
					// javascript:history.back();
					// </script>";
					echo json_encode($result);
				}
			}
		} else {


			if ($_FILES['FileDocumentsClaim']['name'] == '') {

				$file_update = $cek[0]->documents;
			} else {

				$config = [
					'upload_path' => './assets/documents/documents_hris/',
					'allowed_types' => '*',
					'max_size' => 10000000000, 'max_width' => 10000000000,
					'max_height' => 10000000000
				];
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				$this->upload->do_upload('FileDocumentsClaim');
				$file = $this->upload->data();
				//dumper($file);
				$file_update = $file['file_name'];
			}

			//dumper($cek[0]);

			$data = [
				'documents' => $file_update,
				'request_id' => $request_id,
				'kuitansi' => $kuitansi,
				'resep' => $resep
			];
			//dumper($data);
			$result = $this->form_model->update_additional_mdcr($data);
			//dumper($result);
			if ($result == '1') {
				// echo "<script>
				// alert('Success submitting form');
				// javascript:history.back();
				// </script>";
				echo json_encode($result);
			} else {
				// echo "<script>
				// alert('Error submitting form');
				// javascript:history.back();
				// </script>";
				echo json_encode($result);
			}
		}
		//dumper($_FILES['FileDocumentsClaim']);

	}

	public function print_out_req_mdcr()
	{

		$data['request_id'] = $this->uri->segment(3);
		$data['form_request'] = $this->m_global->find('id', $data['request_id'], 'form_request')->row_array();
		$data['header'] = $this->form_model->get_header_mdcr($data['request_id']);
		// dumper($data['header']);

		$data['detail'] = $this->form_model->getTypeOfRembursement($data['request_id']);
		// dumper($data['header']);
		$header_req = $this->m_global->getRow('header_table', 'form_type', array('code' => 'MDCR'));
		$additional = $this->m_global->getRow('additional_table', 'form_type', array('code' => 'MDCR'));
		$data['header_req'] = $this->m_global->find('request_id', $data['request_id'], $header_req)->row_array();

		$employee_id 	= $data['header_req']['employee_id'];
		$id_eg_prj 		= $data['header_req']['id_eg_prj'];
		$id_eg_pri 		= $data['header_req']['id_eg_pri'];
		$id_eg_pk  		= $data['header_req']['id_eg_pk'];
		$detail 		= $this->form_model->cekTypeOfRembursement($data['request_id']);
		$tor = 'non_rawat_inap';
		foreach ($detail as $key => $value) {
			if ($value['tor_grandparent'] == '2') {
				$tor = 'rawat_inap';
			}
		}
		// dumper($tor);

		$data['no_req_mdcr'] = $data['form_request']['request_number'];
		$data['approver'] = $this->form_model->get_approval_mdcr($data['request_id']);
		// dumper(decrypt($data['approver'][3]->complete_name));
		// dumper($data['approver']);
		$data['tor'] = $tor;
		$request_created_at = $data['form_request']['created_at'];
		$data['sum_penggantian_jalan'] = $this->form_model->get_sum_penggantian_jalan($data['request_id']);
		$data['sum_penggantian_inap'] = $this->form_model->get_sum_penggantian_inap($data['request_id']);
		$data['sum_penggantian_kacamata'] = $this->form_model->get_sum_penggantian_kacamata($data['request_id']);
		$data['reimaning_pagu'] = $this->form_model->get_reimaning_pagu($request_created_at, $employee_id, $id_eg_prj, $id_eg_pri, $id_eg_pk, $data['request_id']);

		// dumper($data['reimaning_pagu']);
		$data['additional'] = $this->m_global->find('request_id', $data['request_id'], $additional)->result_array();
		$data['listToR'] = $this->form_model->getTypeOfRembursement($data['request_id']);

		// dumper($data['approver']);
		$is_status = $data['form_request']['is_status'];
		if ($is_status == 3) {
			$data['data_total_claim']		= $this->form_model->get_data_total_claim_for_print_iso($data['request_id']);
			// dumper($data['data_total_claim']);
			$this->load->view('form/print_out/print_req_mdcr_full_approved', $data);
		} else {
			$this->load->view('form/print_out/print_req_mdcr', $data);
		}
	}

	public function print_out_req_mdcr_all_per_day()
	{

		$data['request_number']	= $this->uri->segment(3);
		$data['data_claim']		= $this->form_model->get_data_claim($data['request_number']);
		// dumper($data['data_claim']);
		$data['total_data_claim']		= $this->form_model->get_total_data_claim($data['request_number']);
		$data['date_form']		= $this->form_model->get_date_no_req_mdcr($data['request_number']);
		// dumper($data['date_form']);
		$this->load->view('form/print_out/print_req_mdcr_all_per_day', $data);
	}

	public function print_out_req_mdcr_all_per_employee()
	{

		$data['request_id']	= $this->uri->segment(3);
		$data['data_employee_current']  = $this->form_model->get_data_employee_current($data['request_id']);
		$data['data_claim']				= $this->form_model->get_data_claim_per_employee($data['request_id']);
		$data['data_total_claim']		= $this->form_model->get_data_total_claim_per_employee($data['request_id']);
		$this->load->view('form/print_out/print_req_mdcr_all_per_employee', $data);
	}


	public function detail_approval($formType, $id)
	{
		$request_id = decode_url($id);
		$data['form_request'] = $this->m_global->find('id', $request_id, 'form_request')->row_array();
		$header = $this->m_global->getRow('header_table', 'form_type', array('code' => $formType));
		$detail = $this->m_global->getRow('detail_table', 'form_type', array('code' => $formType));
		$data['header'] = $this->m_global->find('request_id', $request_id, $header)->row_array();

		switch ($formType) {
			case 'MDCR':

				$additional = $this->m_global->getRow('additional_table', 'form_type', array('code' => $formType));
				$data['detail'] = $this->m_global->find('request_id', $request_id, $detail)->result_array();
				$employee_id = $data['header']['employee_id'];
				$data['couple'] = $this->form_model->get_data_couple_employee($employee_id);
				
				$id_eg_prj = $data['header']['id_eg_prj'];
				$id_eg_pri = $data['header']['id_eg_pri'];
				$id_eg_pk  = $data['header']['id_eg_pk'];
				$request_created_at = $data['form_request']['created_at'];
				$data['approval_priority'] = $this->form_model->get_approval_priority($request_id, $this->email);
				$data['sum_penggantian_jalan'] = $this->form_model->get_sum_penggantian_jalan($request_id);
				$data['sum_penggantian_inap'] = $this->form_model->get_sum_penggantian_inap($request_id);
				$data['sum_penggantian_kacamata'] = $this->form_model->get_sum_penggantian_kacamata($request_id);
				$data['reimaning_pagu'] = $this->form_model->get_reimaning_pagu($request_created_at, $employee_id, $id_eg_prj, $id_eg_pri, $id_eg_pk);
				// dumper($data['reimaning_pagu']);
				$data['additional'] = $this->m_global->find('request_id', $request_id, $additional)->result_array();
				//dumper($data['approval_priority']);
				break;

			case 'PPD':

				$dir =  './uploaded_files/' . $data['form_request']['request_number'] . '/supporting_files';
				$data['uploaded_document'] = $this->doScan($dir);
				$data['list_city'] = $this->form_model->getCity();
				$data['detail'] = $this->m_global->find('header_id', $data['header']['id'], $detail)->result_array();
				$data['kota_berangkat'] = $this->db->get_where('master_city', array('IDPROVINSI' => $data['detail'][0]['kota_berangkat']))->row_array()['NAMA_KABUPATEN_KOTA'];
				$data['kota_tujuan'] = $this->db->get_where('master_city', array('IDPROVINSI' => $data['detail'][0]['kota_tujuan']))->row_array()['NAMA_KABUPATEN_KOTA'];
				$data['category_city'] = $this->db->get_where('master_city', array('IDPROVINSI' => $data['detail'][0]['kota_tujuan']))->row_array()['CATEGORY'];

				$data['approval_progress'] = $this->m_global->find('request_id', $request_id, 'form_approval')->result_array();
				$count = count($data['approval_progress']);
				$data['requestor_layer'] = '';
				$data['layer_1'] = '';
				$data['layer_2'] = '';
				$data['layer_3'] = '';

				//print_r($data['approval_progress']);die;
				if (!empty($data['approval_progress'])) {

					if ($count === 4) {
						$data['requestor_layer'] = $data['approval_progress'][0]['approval_email'];
						$data['layer_1'] = $data['approval_progress'][1]['approval_email'];
						$data['layer_2'] = $data['approval_progress'][2]['approval_email'];
						$data['layer_3'] = $data['approval_progress'][3]['approval_email'];
					} else {
						$data['requestor_layer'] = '';
						$data['layer_1'] = $data['approval_progress'][0]['approval_email'];
						$data['layer_2'] = $data['approval_progress'][1]['approval_email'];
						$data['layer_3'] = $data['approval_progress'][2]['approval_email'];
					}
				}

				break;

			case 'LPD':

				$dir =  './uploaded_files/' . $data['form_request']['request_number'] . '/supporting_files';
				$data['uploaded_document'] = $this->doScan($dir);
				$data['list_city'] = $this->form_model->getCity();
				$data['detail'] = $this->m_global->find('header_id', $data['header']['id'], $detail)->row_array();
				$data['additional'] = $this->m_global->find('header_id', $data['header']['id'], $additional)->result_array();

				$data['approval_progress'] = $this->m_global->find('request_id', $request_id, 'form_approval')->result_array();
				$count = count($data['approval_progress']);
				$data['requestor_layer'] = '';
				$data['layer_1'] = '';
				$data['layer_2'] = '';
				$data['layer_3'] = '';

				break;

			default:
				break;
		}

		$data['userList'] = $this->form_model->getUserList("user_email", "users", "is_active = 1 AND user_email != '$this->email'");
		$data['employee_list'] = $this->form_model->getUserList("complete_name", "hris_employee", "is_active = 1 AND access_employee != 1 AND complete_name != '$this->emp_name'");
		$data['approval'] = $this->m_global->find('request_id', $request_id, 'form_approval')->result_array();
		// dumper($data['approval']);
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());
		$data['notes']   = $this->m_global->find('request_id', $request_id, 'request_notes')->result_array();
		$data['formType'] = $this->form_model->getFormType();
		$data['content'] = 'form/approval/' . $formType;
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function cek_hak_pengajuan()
	{
		$hak_pengajuan		= $_POST['hak_pengajuan'];
		$id_request			= $_POST['id_request'];
		$this->form_model->del_draft_request_mdcr($id_request);
		$data 				= $this->form_model->cek_employee($hak_pengajuan);
		echo json_encode($data);
	}

	public function cek_detail_tor_for_apotik()
	{
		$request_id			= $_POST['request_id'];
		$detail = $this->form_model->cekTypeOfRembursement($request_id);
		$tor = 'non_apotik';
		foreach ($detail as $key => $value) {
			if (($value['tor_child'] == '2') || ($value['tor_child'] == '8') || ($value['tor_child'] == '20')) {
				$tor = 'apotik';
			} else if (($value['tor_grandparent'] == '2') && (($value['tor_child'] != '5') && ($value['tor_child'] != '6') && ($value['tor_child'] != '7') && ($value['tor_child'] != '10'))) {
				$tor = 'apotik';
			}
		}

		echo json_encode($tor);
	}

	public function cek_detail_add_info_for_doc()
	{
		$request_id			= $_POST['request_id'];
		$detail = $this->form_model->cek_additional_table($request_id);
		//dumper($detail[0]->documents);
		$doc = 'no_doc';

		if (!empty($detail[0]->documents)) {
			$doc = 'doc';
		}

		echo json_encode($doc);
	}

	public function cek_tor()
	{
		$request_id			= $_POST['request_id'];
		$listToR = $this->form_model->getTypeOfRembursement($request_id);
		$cek = 0;
		if (!empty($listToR)) {
			$cek = 1;
		}
		echo json_encode($cek);
	}

	public function cek_efektifitas_kuitansi()
	{
		$tanggal_kuitansi = $_POST['tanggal_kuitansi'];
		$data 				= $this->form_model->cek_efektifitas_kuitansi($tanggal_kuitansi);
		echo json_encode($data);
	}

	public function responseRequestFromAdminHR()
	{
		$request_id = $this->input->post('id');
		$response = $this->input->post('resp');

		$respone = $this->form_model->responseRequestFromAdminHR($request_id, $response);
		if ($respone == true) {
			$this->sendEmail('checked_mdcr_hr', $request_id, '');
			echo json_encode($respone);
		} else {
			echo json_encode($respone);
		}
	}

	public function responseRequestMDCRtoFI()
	{
		$no_req = $this->input->post('no_req');
		// dumper($no_req);
		$sql = "select * from form_request where no_req_mdcr='$no_req' and is_status='1' and is_status_admin_hr='1' and is_status_divhead_hr is NULL";
		$query = $this->db->query($sql);
		$res = $query->result();

		$count = count($res);

		for ($i = 0; $i < $count; $i++) {

			$data = array(
				'is_status_divhead_hr' => 1
			);
			$this->db->where('id', $res[$i]->id);
			$this->db->update('form_request', $data);
			$this->responseRequestMDCRFI($res[$i]->id, 'Approved');
		}

		$data = array(
			'is_status' => 3
		);
		$this->db->where('no_req_mdcr', $no_req);
		$result = $this->db->update('hris_no_req_mdcr', $data);

		echo json_encode($result);
	}

	public function responseRequestMDCRFI($request_id, $response)
	{
		//dumper('tes');
		//$output = array('status' => 0, 'message' => 'Something went wrong. Please refresh and try again.');
		$request_id = $request_id;

		$sql = "select id from form_approval where request_id='$request_id' and approval_status='In Progress'";
		$query = $this->db->query($sql);
		$res = $query->result();

		$approval_id = $res[0]->id;
		//print_r($approval_id);die;
		$response = $response;
		//dumper($response);
		// previous layer
		$priority = $this->m_global->find('id', $approval_id, 'form_approval')->row_array()['approval_priority'];
		$prev_priority = $priority - 1;
		$prev_id = $this->inbox_model->find_select("id", array('approval_priority' => $prev_priority, 'request_id' => $request_id), 'form_approval')->row_array();

		$prev_email = $this->inbox_model->find_select("approval_email", array('approval_priority' => $prev_priority, 'request_id' => $request_id), 'form_approval')->row_array();

		$data_prev_layer = array(
			'approval_status' => 'In Progress',
			'updated_at' => $this->date,
			'updated_by' => $this->email
		);

		switch ($response) {

			case 'Revised':

				if ($priority != 1) {

					$current_layer = array(
						'approval_status' => 'Revised to previous layer',
						'updated_at' => $this->date,
						'updated_by' => $this->email
					);

					$this->db->where('id', $approval_id);
					if ($this->db->update('form_approval', $current_layer)) {

						$this->db->where('id', $prev_id['id']);
						if ($this->db->update('form_approval', $data_prev_layer)) {
							$this->db->where('request_id', $request_id);
							$this->db->update('hris_medical_reimbursment', array('is_status' => 2));
							//$this->sendEmail('revise', $request_id, $prev_email['approval_email']);
							$this->logs('revised', 'MDCR', $request_id, 'Response revised', 'Success');
							$output = array('status' => 1);
						}
					}
				} else {

					$revise_layer = array(
						'approval_status' => 'Revised',
						'updated_at' => $this->date,
						'updated_by' => $this->email
					);

					$this->db->where('id', $request_id);
					if ($this->db->update('form_request', array('is_status' => 2, 'updated_by' => $this->email, 'updated_at' => $this->date))) {
						//$this->sendEmail('revise', $request_id, $requestor);
						$this->db->where('request_id', $request_id);
						$this->db->update('hris_medical_reimbursment', array('is_status' => 2));

						$data['form_request'] = $this->m_global->find('id', $request_id, 'form_request')->row_array();
						$data['data_employee'] = $this->form_model->get_data_employee($data['form_request']['employee_id']);
						//$this->sendEmail('revised_mdcr', $request_id, decrypt($data['data_employee']->email));

						$this->db->where('id', $approval_id);
						if ($this->db->update('form_approval', $revise_layer)) {
							//$this->sendEmail('revise', $request_id, $prev_email['approval_email']);
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
					//$this->sendEmail('rejected_mdcr', $request_id, decrypt($data['data_employee']->email));

					//$this->sendEmail('revise', $request_id, $prev_email['approval_email']);
					$this->logs('reject', 'MDCR', $request_id, 'Response Rejected', 'Rejected');
					$output = array('status' => 1);
				}
				break;

			case 'Approved':

				#check current approver list
				$sql = "SELECT * FROM form_approval WHERE id = '$approval_id' AND request_id = '$request_id'";
				$checkleftcurrent = $this->db->query($sql);

				if (($checkleftcurrent->row_array()['approval_email'] != 'hr.support@ibsmulti.com') and ($checkleftcurrent->row_array()['approval_priority'] != 3)) {
					$this->sendEmail('approved_spv_mdcr', $request_id, $checkleftcurrent->row_array()['approval_email'], $checkleftcurrent->row_array()['approval_employee_id']);
				}

				#check approver list
				// $sql = "SELECT * FROM form_approval WHERE id >= '$approval_id' AND request_id = '$request_id' ORDER BY approval_priority ASC OFFSET 1 ROWS FETCH NEXT 1 ROWS ONLY";
				// $sql = "SELECT * FROM form_approval WHERE id >= '$approval_id' AND request_id = '$request_id' and approval_status = 'In Progress' ORDER BY approval_priority ASC";
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
						// dumper('iki lho');

						#set in progress for next approver
						$this->db->where('id', $checkleft->row_array()['id']);

						if ($this->db->update('form_approval', array('approval_status' => 'In Progress'))) {
							// dumper('aaa');
							$this->logs('approved', 'MDCR', $request_id, 'Approved successfully', 'Approved successfully');
							$output = array('status' => 1, 'message' => 'Approved successfully.', 'id' => encode_url($request_id));
						} else {
							// dumper('bbb');
							$this->logs('system', 'MDCR', $request_id, 'Authentication success, but failed while updating the next approver.', 'Authentication success, but failed while updating the next approver.');
							$output = array('status' => 0, 'message' => 'Authentication success, but failed while updating the next approver.');
						}
					} else {
						$this->logs('system', 'MDCR', $request_id, 'Authentication success, but failed while updating response approval.', 'Authentication success, but failed while updating response approval.');
						$output = array('status' => 0, 'message' => 'Authentication success, but failed while updating response approval. ');
					}
				} else {
					// dumper('hoho');

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
							$this->logs('approved', 'MDCR', $request_id, 'Approved successfully', 'Approved successfully');

							$output = array('status' => 1, 'message' => 'Approved successfully.', 'id' => encode_url($request_id));
						} else {
							$this->logs('system', 'MDCR', $request_id, 'Authentication success, but failed while updating response approval [Full Approved].', 'Authentication success, but failed while updating response approval [Full Approved].');
							$output = array('status' => 1, 'message' => 'Authentication success, but failed updating response approval.');
						}
					} else {
						$this->logs('system', 'MDCR', $request_id, 'Authentication success, but failed while updating response approval [Full Approved].', 'Authentication success, but failed while updating response approval [Full Approved].');
						$output = array('status' => 1, 'message' => 'Authentication success, but failed updating response approval.');
					}
				}

				break;

			default:
				break;
		}
		//dumper($output);
		//echo json_encode($output);
	}
}
