<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inbox_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('general');
		$this->email = $this->session->userdata('user_email');
		$this->employee_id = $this->session->userdata('employee_id');
		$this->division = $this->session->userdata('division');
		$this->second_division = $this->session->userdata('second_division');
		$this->date = date('Y-m-d H:i:s');
		$this->year = date('Y');
	}

	public function getApprovalList()
	{
		
		$this->db->where('approval_email', $this->email);
		$this->db->where("approval_status IN ('In Progress', 'Hold', 'Review')");
		$this->db->order_by('id', 'ASC');
		$approval = $this->db->get('form_approval')->result_array();
		//dumper($approval);
		$data = array();

		if (!empty($approval)) {

			foreach ($approval as $key => $value) {
				$reqId = $value['request_id'];
				$list[] = $reqId;
			}
			
			$listApproval = implode(",", $list);
			//dumper($listApproval);
			$listForm = $this->getListForm($listApproval);
			return $listForm;

		} else {
			return $data;
		}
	}
	
	public function getApprovalListMDCRCek()
	{
		$this->db->where('approval_email', $this->email);
		$this->db->where("approval_status IN ('In Progress')");
		$this->db->order_by('id', 'ASC');
		$approval = $this->db->get('form_approval')->result_array();
		//dumper($approval);
		$data = array();

		if (!empty($approval)) {

			foreach ($approval as $key => $value) {
				$reqId = $value['request_id'];
				$list[] = $reqId;
			}
			
			$listApproval = implode(",", $list);
			$listForm = $this->getListFormMDCRCek($listApproval);
			// dumper($listForm);

			return $listForm;

		} else {
			return $data;
		}
	}

	public function getApprovalListMDCRRevised()
	{
		$this->db->where('approval_email', $this->email);
		$this->db->where("approval_status IN ('Revised', 'Revised to previous layer')");
		$this->db->order_by('id', 'ASC');
		$approval = $this->db->get('form_approval')->result_array();
		// dumper($approval);
		$data = array();

		if (!empty($approval)) {

			foreach ($approval as $key => $value) {
				$reqId = $value['request_id'];
				$list[] = $reqId;
			}
			
			$listApproval = implode(",", $list);
			$listForm = $this->getListFormMDCRRevised($listApproval);
			return $listForm;

		} else {
			return $data;
		}
	}

	public function getApprovalListMDCRApproved()
	{
		$this->db->where('approval_email', $this->email);
		$this->db->where("approval_status IN ('Approved')");
		$this->db->order_by('id', 'ASC');
		$approval = $this->db->get('form_approval')->result_array();
		//dumper($approval);
		$data = array();

		if (!empty($approval)) {

			foreach ($approval as $key => $value) {
				$reqId = $value['request_id'];
				$list[] = $reqId;
			}
			
			$listApproval = implode(",", $list);
			$listForm = $this->getListFormMDCRApproved($listApproval);
			// dumper($listApproval);
			return $listForm;
		} else {
			return $data;
		}
	}
	
	public function getApprovalListMDCRAfterCek()
	{
		$this->db->where('approval_email', $this->email);
		$this->db->where("approval_status IN ('In Progress')");
		$this->db->order_by('id', 'ASC');
		$approval = $this->db->get('form_approval')->result_array();
		//dumper($approval);
		$data = array();

		if (!empty($approval)) {

			foreach ($approval as $key => $value) {
				$reqId = $value['request_id'];
				$list[] = $reqId;
			}
			
			$listApproval = implode(",", $list);
			$listForm = $this->getListFormMDCRAfterCek($listApproval);
			return $listForm;

		} else {
			return $data;
		}
	}

	public function getApprovalListMDCRAfterGrouping()
	{
		// $sqll = "SELECT no_req_mdcr, form_type FROM form_request WHERE no_req_mdcr IS NOT NULL AND no_req_mdcr <> '' AND no_req_mdcr <> ' '  GROUP BY no_req_mdcr, form_type ORDER BY no_req_mdcr DESC";
		// $queryy = $this->db->query($sqll);
		// $group = $queryy->result_array();
		$sqll = "SELECT * FROM hris_no_req_mdcr ORDER BY id DESC";
		$queryy = $this->db->query($sqll);
		$group = $queryy->result_array();
		$data = array();

		if ($group) {
			return $group;
		} else {
			return $data;
		}
	}

	public function getReqMDCRAfterGroupingNeedApproved()
	{
		$sqll = "SELECT * FROM hris_no_req_mdcr WHERE is_status = '1'";
		$queryy = $this->db->query($sqll);
		$group = $queryy->result_array();
		$data = array();
		//dumper($group);
		if ($group) {
			return $group;
		} else {
			return $data;
		}
	}
	
	public function getApprovalListMDCRAGroupingItem($no_req)
	{
		$sqll = "SELECT * FROM form_request WHERE no_req_mdcr = '$no_req' AND (is_status = '3' OR is_status = '1')";
		$queryy = $this->db->query($sqll);
		$group = $queryy->result_array();
		$data = array();

		if ($group) {
			return $group;
		} else {
			return $data;
		}
	}

	public function getAllDataPA($eval_year)
	{
		$this->db->select("*");
		$this->db->where("is_status !=", "0");
		$this->db->where("evaluation_period_start", $eval_year);
		return $this->db->get('performance_appraisal')->result();
	}

	public function getPAList()
	{
		$this->db->where('approval_email', $this->email);
		$this->db->where("approval_status IN ('Approved')");
		$this->db->order_by('id', 'ASC');
		$approval = $this->db->get('form_approval')->result_array();

		$data = array();

		if (!empty($approval)) {

			foreach ($approval as $key => $value) {
				$reqId = $value['request_id'];
				$list[] = $reqId;
			}

			$listApproval = implode(",", $list);
			$listForm = $this->getListForm($listApproval, 'pa_list');
			return $listForm;

		} else {
			return $data;
		}
	}

	public function getReviewList()
	{
		$this->db->where('approval_email', $this->email);
		$this->db->where("approval_status IN ('Approved')");
		$this->db->order_by('id', 'ASC');
		$approval = $this->db->get('form_approval')->result_array();

		$data = array();

		if (!empty($approval)) {

			foreach ($approval as $key => $value) {
				$reqId = $value['request_id'];
				$list[] = $reqId;
			}

			$listApproval = implode(",", $list);
			$listForm = $this->getListForm($listApproval, 'pa_list');
			return $listForm;

		} else {
			return $data;
		}
	}

	public function getDivHeadList($user = '')
	{
		if (!empty($user)) {
			$email = $user;
		} else {
			$email = $this->email;
		}

		$this->db->select('id as approval_id, request_id');
		$this->db->where("approval_email", $email);
		$this->db->where("request_id !=", '');
		$ListApproval = $this->db->get('form_approval')->result_array();

		if (!empty($ListApproval)) {

			foreach ($ListApproval as $key => $value) {
				$reqId = $value['request_id'];
				$list[] = $reqId;
			}

			$approval = implode(",", $list);
			$this->db->select("*");
			$this->db->where("id IN ($approval)");
			$this->db->where("is_status", "3");
			$result = $this->db->get('performance_appraisal')->result();
		} else {
			$result = '';
		}

		return $result;
	}

	public function getDivHeadListByDivision($division)
	{
		$this->db->select('id as approval_id, request_id');
		$this->db->where("approval_email", $this->email);
		$this->db->where("request_id !=", '');
		$ListApproval = $this->db->get('form_approval')->result_array();

		if (!empty($ListApproval)) {

			foreach ($ListApproval as $key => $value) {
				$reqId = $value['request_id'];
				$list[] = $reqId;
			}

			$approval = implode(",", $list);
			$this->db->select("*");
			$this->db->where("id IN ($approval)");
			$this->db->where("is_status", "3");
			$this->db->where("division", $division);
			$result = $this->db->get('performance_appraisal')->result();
		} else {
			$result = '';
		}

		return $result;
	}

	public function getHRConfirmed()
	{
		// $year = $this->year - 1;
		// $eval_year = $year.'-01-01';
		$eval_year = '2020-01-01';

		$this->db->select('division_name');
		$this->db->where("is_status", '3');
		$this->db->where("evaluation_period", $eval_year);
		$division_name = $this->db->get('performance_division_status')->result_array();

		if (!empty($division_name)) {

			foreach ($division_name as $key => $value) {
				$list_div = $value['division_name'];
				$list[] = $list_div;
			}

			$div_name = implode("','", $list);
			$this->db->select("*");
			$this->db->where("division IN ('$div_name')");
			$this->db->where("is_status", "3");
			$result = $this->db->get('performance_appraisal')->result();
		} else {
			$result = '';
		}

		return $result;
	}

	public function getHRReview($division_name)
	{
		if ($division_name == 'Management') {
			$this->db->where("division IN ('Management', 'Technology', 'Finance', 'Operations', 'Assets')");
		} else {
			$this->db->where("division", $division_name);
		}
		$this->db->where("is_status", "3");
		$result = $this->db->get('performance_appraisal')->result();

		if (!empty($result)) {
			$output = $result;
		} else {
			$output = '';
		}

		return $output;
	}

	public function getListForm($listId = '', $flag = '')
	{
		
		if (!empty($listId)) {
			// $this->db->where("a.id IN ($listId) ");
			$sql = "SELECT *
						FROM form_request
						WHERE id IN ($listId) AND is_status = '1'
						ORDER BY id ASC";
			// var_dump($sql);
		} else {
			// $this->db->where("a.created_by", $this->email);
			$sql = "SELECT *
						FROM form_request
						WHERE employee_id LIKE '$this->employee_id' AND is_status = '1'
						ORDER BY id ASC";
		}

		$query = $this->db->query($sql);
		$res = $query->result_array();
		// var_dump($res);
		//dumper($listId);

		// if (!empty($flag)) {

		// 	if ($flag == 'review') {
		// 		$this->db->where("is_status", '6');
		// 	} elseif ($flag == 'pa_list') {
		// 		$this->db->where("is_status", '3');
		// 	}

		// } else {
			//$this->db->where("is_status !=", '1');
			// $this->db->where("is_status !=", '2');
			// $this->db->where("is_status !=", '3');
			// $this->db->where("is_status !=", '4');
			//$this->db->where("is_status !=", '0');
		//}
		
		$data = array();
		foreach ($res as $key) {
			$nik = $key['employee_id'];
			// var_dump($key['employee_id']);
			$request_number = $key['request_number'];
			// $sqll = "SELECT TOP 1 id_employee, complete_name FROM hris_employee WHERE nik = '$nik' ORDER BY id_employee DESC";
			$sqll = "SELECT id_employee, complete_name FROM hris_employee WHERE nik = '$nik' ORDER BY id_employee DESC LIMIT 1";
			$queryy = $this->db->query($sqll);
			$complete_name = $queryy->result();
			$row   = array();
			// var_dump($sqll);

			$row['id'] =  $key['id'];
			$row['request_number'] =  $key['request_number'];
			$row['form_type'] =  $key['form_type'];
			$row['form_purpose'] =  $key['form_purpose'];
			$row['form_notes'] =  $key['form_notes'];
			$row['approval_form_scanned'] =  $key['approval_form_scanned'];
			$row['approved_date'] =  $key['approved_date'];
			$row['result_document'] =  $key['result_document'];
			$row['is_status'] =  $key['is_status'];
			$row['created_by'] =  $key['created_by'];
			$row['created_at'] =  $key['created_at'];
			$row['updated_by'] =  $key['updated_by'];
			$row['updated_at'] =  $key['updated_at'];
			$row['deleted_by'] =  $key['deleted_by'];
			$row['deleted_at'] =  $key['deleted_at'];
			$row['employee_id'] =  $key['employee_id'];
			$row['complete_name'] =  $complete_name[0]->complete_name;
			
			$data[] = $row;
		}
		//$output = array('data' => $data);
		//dumper($data);
		return $data;
		
		// $this->db->where("is_status", '1');
		// $this->db->order_by('created_at', 'DESC');
		// return $this->db->get('form_request')->result_array();
	}

	public function getListFormMDCRCek($listId = '', $flag = '')
	{
		
		if (!empty($listId)) {
			$sql = "SELECT *
						FROM form_request
						WHERE id IN ($listId) AND is_status = '1' AND form_type = 'MDCR' AND (is_status_admin_hr = '0' or is_status_admin_hr is null)
						ORDER BY id ASC";
		} else {
			$sql = "SELECT *
						FROM form_request
						WHERE employee_id LIKE '$this->employee_id' AND is_status = '1' AND form_type = 'MDCR' AND (is_status_admin_hr = '0' or is_status_admin_hr is null)
						ORDER BY id ASC";
		}

		$query = $this->db->query($sql);
		$res = $query->result_array();
		
		$data = array();
		foreach ($res as $key) {
			$nik = $key['employee_id'];
			$request_number = $key['request_number'];
			// $sqll = "SELECT TOP 1 id_employee, complete_name FROM hris_employee WHERE nik = '$nik' ORDER BY id_employee DESC";
			$sqll = "SELECT id_employee, complete_name FROM hris_employee WHERE nik = '$nik' ORDER BY id_employee DESC LIMIT 1";
			$queryy = $this->db->query($sqll);
			$complete_name = $queryy->result();
			$row   = array();

			$row['id'] =  $key['id'];
			$row['request_number'] =  $key['request_number'];
			$row['form_type'] =  $key['form_type'];
			$row['form_purpose'] =  $key['form_purpose'];
			$row['form_notes'] =  $key['form_notes'];
			$row['approval_form_scanned'] =  $key['approval_form_scanned'];
			$row['approved_date'] =  $key['approved_date'];
			$row['result_document'] =  $key['result_document'];
			$row['is_status'] =  $key['is_status'];
			$row['created_by'] =  $key['created_by'];
			$row['created_at'] =  $key['created_at'];
			$row['updated_by'] =  $key['updated_by'];
			$row['updated_at'] =  $key['updated_at'];
			$row['deleted_by'] =  $key['deleted_by'];
			$row['deleted_at'] =  $key['deleted_at'];
			$row['employee_id'] =  $key['employee_id'];
			$row['complete_name'] =  $complete_name[0]->complete_name;
			
			$data[] = $row;
		}
		// dumper($data);
		return $data;

	}
	
	
	public function getListFormMDCRRevised($listId = '', $flag = '')
	{
		
		if (!empty($listId)) {
			$sql = "SELECT *
						FROM form_request
						WHERE id IN ($listId) AND ( is_status = '2' OR is_status = '1' ) AND form_type = 'MDCR'
						ORDER BY id ASC";
		} else {
			$sql = "SELECT *
						FROM form_request
						WHERE employee_id LIKE '$this->employee_id' AND is_status = '2' AND form_type = 'MDCR'
						ORDER BY id ASC";
		}

		$query = $this->db->query($sql);
		$res = $query->result_array();
		//dumper($listId);
		
		$data = array();
		foreach ($res as $key) {
			$nik = $key['employee_id'];
			$request_number = $key['request_number'];
			// $sqll = "SELECT TOP 1 id_employee, complete_name FROM hris_employee WHERE nik = '$nik' ORDER BY id_employee DESC";
			$sqll = "SELECT id_employee, complete_name FROM hris_employee WHERE nik = '$nik' ORDER BY id_employee DESC LIMIT 1";
			$queryy = $this->db->query($sqll);
			$complete_name = $queryy->result();
			$row   = array();

			$row['id'] =  $key['id'];
			$row['request_number'] =  $key['request_number'];
			$row['form_type'] =  $key['form_type'];
			$row['form_purpose'] =  $key['form_purpose'];
			$row['form_notes'] =  $key['form_notes'];
			$row['approval_form_scanned'] =  $key['approval_form_scanned'];
			$row['approved_date'] =  $key['approved_date'];
			$row['result_document'] =  $key['result_document'];
			$row['is_status'] =  $key['is_status'];
			$row['created_by'] =  $key['created_by'];
			$row['created_at'] =  $key['created_at'];
			$row['updated_by'] =  $key['updated_by'];
			$row['updated_at'] =  $key['updated_at'];
			$row['deleted_by'] =  $key['deleted_by'];
			$row['deleted_at'] =  $key['deleted_at'];
			$row['employee_id'] =  $key['employee_id'];
			$row['complete_name'] =  $complete_name[0]->complete_name;
			
			$data[] = $row;
		}
		return $data;
	}
	
	public function getListFormMDCRApproved($listId = '', $flag = '')
	{
		
		if (!empty($listId)) {
			$sql = "SELECT *
						FROM form_request
						WHERE id IN ($listId) AND ( is_status = '1' OR is_status = '3') AND form_type = 'MDCR'
						ORDER BY id DESC";
		} else {
			$sql = "SELECT *
						FROM form_request
						WHERE employee_id LIKE '$this->employee_id' AND ( is_status = '1' OR is_status = '3') AND form_type = 'MDCR'
						ORDER BY id DESC";
		}

		$query = $this->db->query($sql);
		$res = $query->result_array();
		//dumper($listId);
		
		$data = array();
		foreach ($res as $key) {
			$nik = $key['employee_id'];
			$request_number = $key['request_number'];
			// $sqll = "SELECT TOP 1 id_employee, complete_name FROM hris_employee WHERE nik = '$nik' ORDER BY id_employee DESC";
			$sqll = "SELECT id_employee, complete_name FROM hris_employee WHERE nik = '$nik' ORDER BY id_employee DESC LIMIT 1";
			$queryy = $this->db->query($sqll);
			$complete_name = $queryy->result();
			$row   = array();

			$row['id'] =  $key['id'];
			$row['request_number'] =  $key['request_number'];
			$row['form_type'] =  $key['form_type'];
			$row['form_purpose'] =  $key['form_purpose'];
			$row['form_notes'] =  $key['form_notes'];
			$row['approval_form_scanned'] =  $key['approval_form_scanned'];
			$row['approved_date'] =  $key['approved_date'];
			$row['result_document'] =  $key['result_document'];
			$row['is_status'] =  $key['is_status'];
			$row['created_by'] =  $key['created_by'];
			$row['created_at'] =  $key['created_at'];
			$row['updated_by'] =  $key['updated_by'];
			$row['updated_at'] =  $key['updated_at'];
			$row['deleted_by'] =  $key['deleted_by'];
			$row['deleted_at'] =  $key['deleted_at'];
			$row['employee_id'] =  $key['employee_id'];
			$row['complete_name'] =  $complete_name[0]->complete_name;
			
			$data[] = $row;
		}
		return $data;
	}
	
	public function getApprovalListMDCRRejected()
	{
		$sql = "SELECT *
						FROM form_request
						WHERE is_status = '4' AND form_type = 'MDCR'
						ORDER BY id ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		//dumper($listId);
		
		$data = array();
		foreach ($res as $key) {
			$nik = $key['employee_id'];
			$request_number = $key['request_number'];
			// $sqll = "SELECT TOP 1 id_employee, complete_name FROM hris_employee WHERE nik = '$nik' ORDER BY id_employee DESC";
			$sqll = "SELECT id_employee, complete_name FROM hris_employee WHERE nik = '$nik' ORDER BY id_employee DESC LIMIT 1";
			$queryy = $this->db->query($sqll);
			$complete_name = $queryy->result();
			$row   = array();

			$row['id'] =  $key['id'];
			$row['request_number'] =  $key['request_number'];
			$row['form_type'] =  $key['form_type'];
			$row['form_purpose'] =  $key['form_purpose'];
			$row['form_notes'] =  $key['form_notes'];
			$row['approval_form_scanned'] =  $key['approval_form_scanned'];
			$row['approved_date'] =  $key['approved_date'];
			$row['result_document'] =  $key['result_document'];
			$row['is_status'] =  $key['is_status'];
			$row['created_by'] =  $key['created_by'];
			$row['created_at'] =  $key['created_at'];
			$row['updated_by'] =  $key['updated_by'];
			$row['updated_at'] =  $key['updated_at'];
			$row['deleted_by'] =  $key['deleted_by'];
			$row['deleted_at'] =  $key['deleted_at'];
			$row['employee_id'] =  $key['employee_id'];
			$row['complete_name'] =  $complete_name[0]->complete_name;
			
			$data[] = $row;
		}
		return $data;
	}
	
	
	public function getListFormMDCRAfterCek($listId = '', $flag = '')
	{
		
		if (!empty($listId)) {
			$sql = "SELECT *
						FROM form_request
						WHERE id IN ($listId) AND is_status = '1' AND form_type = 'MDCR' AND ( no_req_mdcr IS NULL OR no_req_mdcr = '' ) AND is_status_admin_hr = '1'
						ORDER BY id ASC";
		} else {
			$sql = "SELECT *
						FROM form_request
						WHERE employee_id LIKE '$this->employee_id' AND is_status = '1' AND form_type = 'MDCR' AND ( no_req_mdcr IS NULL OR no_req_mdcr = '') AND is_status_admin_hr = '1'
						ORDER BY id ASC";
		}

		$query = $this->db->query($sql);
		$res = $query->result_array();
		// var_dump($res);
		
		$data = array();
		foreach ($res as $key) {
			$nik = $key['employee_id'];
			$request_number = $key['request_number'];
			$sqll = "SELECT id_employee, complete_name FROM hris_employee WHERE nik = '$nik' ORDER BY id_employee DESC LIMIT 1";
			$queryy = $this->db->query($sqll);
			$complete_name = $queryy->result();
			$row   = array();

			$row['id'] =  $key['id'];
			$row['request_number'] =  $key['request_number'];
			$row['form_type'] =  $key['form_type'];
			$row['form_purpose'] =  $key['form_purpose'];
			$row['form_notes'] =  $key['form_notes'];
			$row['approval_form_scanned'] =  $key['approval_form_scanned'];
			$row['approved_date'] =  $key['approved_date'];
			$row['result_document'] =  $key['result_document'];
			$row['is_status'] =  $key['is_status'];
			$row['created_by'] =  $key['created_by'];
			$row['created_at'] =  $key['created_at'];
			$row['updated_by'] =  $key['updated_by'];
			$row['updated_at'] =  $key['updated_at'];
			$row['deleted_by'] =  $key['deleted_by'];
			$row['deleted_at'] =  $key['deleted_at'];
			$row['employee_id'] =  $key['employee_id'];
			$row['complete_name'] =  $complete_name[0]->complete_name;
			
			$data[] = $row;
		}
		return $data;
	}

	public function getLastActivity()
	{
		$this->db->select("id, approval_
			, request_id, approval_notes, created_at, created_by");
		$this->db->limit(5);
		return $this->db->get('logs')->result_array();
	}

	public function countTeam($division)
	{
		$total = $this->db->get_where('employee', array('division' => $division))->result_array();
		return count($total);
	}

	public function countRequest($division, $status, $not_include = '')
	{	
		$where = array('division' => $division, 'is_status' => $status);
		$total = $this->db->get_where('performance_appraisal', $where)->result_array();
		return count($total);
	}

	public function getTotalGrade($division, $grade)
	{
		if ($grade == 'a') {
			$where = array('division' => $division, 'is_status' => 3, 'final_score >=' => '9.1');
		} elseif ($grade == 'b') {
			$where = array('division' => $division, 'is_status' => 3, 'final_score >=' => '8.1', 'final_score <' => '9.1');
		} elseif ($grade == 'c') {
			$where = array('division' => $division, 'is_status' => 3, 'final_score >=' => '6.9', 'final_score <' => '8.1');
		} elseif ($grade == 'd') {
			$where = array('division' => $division, 'is_status' => 3, 'final_score >=' => '5.6', 'final_score <' => '6.9');
		} elseif ($grade == 'e') {
			$where = array('division' => $division, 'is_status' => 3, 'final_score >=' => '0.0', 'final_score <' => '5.6');
		}


		$total = $this->db->get_where('performance_appraisal', $where)->result_array();
		return count($total);
	}

	public function find_select($select, $where = '', $table)
	{
		$this->db->select($select);
		if ($where != '') { $this->db->where($where);}
		return $this->db->get($table);
	}

	public function checkApproval($id, $email)
	{
		return $this->db->get_where('form_approval', array('request_id' => $id, 'approval_email' => $email))->row_array();
	}
	
	public function cekNote($request_id)
	{
		
		// $sql = "SELECT 	
		// 			TOP 1 *
		// 		FROM		
		// 			request_notes
		// 		WHERE request_id = '$request_id'
		// 		ORDER BY id DESC";

		$sql = "SELECT 	
					*
				FROM		
					request_notes
				WHERE request_id = '$request_id' AND is_status IS NOT NULL
				ORDER BY id DESC LIMIT 1";

		// dumper($sql);		

		$query = $this->db->query($sql);
		$res = $query->result();
		$email_created = (!empty(($res[0]->created_by))) ? $res[0]->created_by : '';
		$email_login = $this->email;
		// if(($email_created == $email_login) || ($this->session->userdata('nik') == '00000000') ){
		if(($email_created == $email_login)){
			$cek = true;
		}else{
			$cek = false;
		}
		
		if($cek){
			return $cek;
		}else{
			return false;
		}
	}

	public function save_grouping_req_mdcr($no_ref="", $request_id=""){

		$sql2 	= "UPDATE form_request SET no_req_mdcr='$no_ref' WHERE id = '".$request_id."' and form_type = 'MDCR'";
		$query 	= $this->db->query($sql2);
		
		if($query){
			return $query;
		}else{
			return false;
		}
	}

	public function save_no_grouping_req_mdcr($no_ref=""){
		$formRequest = array(
			'no_req_mdcr' => $no_ref,
			'created_at' => date("Y-m-d H:i:s"),
			'is_status' => '1'
		);
		$query = $this->db->insert('hris_no_req_mdcr', $formRequest);
		
		if($query){
			return $query;
		}else{
			return false;
		}
	}

}