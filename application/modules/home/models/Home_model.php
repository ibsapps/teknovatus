<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('general');
		$this->email = $this->session->userdata('user_email');
		$this->nik = $this->session->userdata('nik');
		$this->date = date('Y-m-d H:i:s');
		$this->year = date('Y');
	}

	public function getMyRequest()
	{
		$this->db->where("created_by", $this->email);
		$this->db->order_by('created_at', 'DESC');
		return $this->db->get('form_request')->result_array();
	}

	public function getMySubmissionList(){
		$this->db->where('employee_id', $this->nik);
		$this->db->where('is_status', '1');
		$approval = $this->db->get('form_request')->result_array();
		$data = array();

		if ($approval) {
			return $approval;
		} else {
			return $data;
		}
	}

}