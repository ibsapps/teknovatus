<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH.'libraries/FPDF-master/fpdf.php');
require_once(APPPATH.'libraries/FPDI-master/src/autoload.php');
use setasign\Fpdi\Fpdi;

class Generate extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('curl');
		$this->load->library('enc');
		$this->load->model('m_global');
		$this->email = $this->session->userdata('user_email');
		$this->date = date('Y-m-d H:i:s');
		$this->year = date('Y');
	}

	public function index()
	{
		print_r('hello');die;
	}

	##### Generate Result Document
	function result_document($form_url, $request_id, $request_number)
	{
		if ($form_url == 'kpi') {
			$form_name = 'PA';
			$form_path = 'kpi';
		} 

		$id = decode_url($request_id);

		$api_endpoint  = "https://selectpdf.com/api2/convert/";
		$key = ''; 
		$test_url 	   = 'https://e-approval.ibstower.com/services/generate/print/' . $form_url . '/' . $id;
		$filename      = 'IBS-' . $form_name . '-' . time() . '.pdf';
		$local_file    = './result_document/'. $request_number .'/'. $filename;
		$rpath   	   = './result_document/'. $request_number .'/';

		if (!is_dir($rpath)) {
			mkdir($rpath, 0777, TRUE);
		}

		$parameters = array('key' => $key, 'url' => $test_url);
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json\r\n",
				'method'  => 'POST',
				'content' => json_encode($parameters),
			),
		);

		$context  = stream_context_create($options);
		$result = @file_get_contents($api_endpoint, false, $context);

		if (!$result) {
			echo "HTTP Response: " . $http_response_header[0] . "<br/>";
			$error = error_get_last();
			echo "Error Message: " . $error['message'];
		} else {

			file_put_contents($local_file, $result);

			if ($this->db->where('id', $id)->update('performance_appraisal', array('result_document' => $local_file, 'count_print' => '1'))) {
				redirect(base_url($local_file));
			}
		}
	}

	function print($type, $id)
	{
		switch ($type) {

			case 'kpi':
				$data['header'] = $this->m_global->find('id', $id, 'performance_appraisal')->row_array();
				$data['detail_kpi'] = $this->m_global->find('request_id', $id, 'performance_appraisal_measurement')->result_array();
				$data['additional'] = $this->m_global->find('request_id', $id, 'performance_appraisal_plan')->result_array();
				$data['approval'] = $this->m_global->find('request_id', $id, 'form_approval')->result_array();
				$data['count_approval'] = count($data['approval']);
				$data['employee'] = $this->m_global->find('id', $data['header']['employee_id'], 'employee')->row_array();

				$this->templates->show('detail', 'form/result_document/kpi_approved', $data);
				break;
			
			default:
				break;
		}

	}


}
