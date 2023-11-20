<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('general');
		$this->email = $this->session->userdata('user_email');
		$this->division = $this->session->userdata('division');
		$this->second_division = $this->session->userdata('second_division');
		$this->date = date('Y-m-d H:i:s');
		$this->year = date('Y');
	}

	public function InfoEmployee(){
		$employee_id = $this->session->userdata('nik');
		//dumper($nik);
		$sql = "SELECT 	
					*
				FROM		
					hris_employee
				WHERE nik = '$employee_id'
				ORDER BY id_employee DESC LIMIT 1";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function getDivHeadListByDivision($division)
	{
		$this->db->where("is_status", "3");
		$this->db->where("division", $division);
		$result = $this->db->get('performance_appraisal')->result();
		return $result;
	}

	public function countUnsubmitted()
	{
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
				)
			";

		$total = $this->db->query($sql)->result_array();
		return count($total);
	}

	public function countUnsubmittedC($division)
	{
		switch ($division) {

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

				break;
			
			default:
				break;
		}

		$total = $this->db->query($sql)->result_array();
		return count($total);
	}

	public function countByStatus($status)
	{	
		$where = array('is_status' => $status);
		$total = $this->db->get_where('performance_appraisal', $where)->result_array();
		return count($total);
	}

	public function countEmployee()
	{
		$total = $this->db->get_where('employee', array('employment_status !=' => 'TIS'))->result_array();
		return count($total);
	}

	public function countDivision()
	{
		$this->db->select('division');
		$this->db->where("division NOT IN ('TIS','Finance', 'Technology', 'Assets', 'Operations')");
		$this->db->group_by('division');
		$total = $this->db->get('employee')->result_array();
		return count($total);
	}

	public function countConfirmed()
	{
		$this->db->select('division_name');
		$this->db->where('is_status', '3');
		$total = $this->db->get('performance_division_status')->result_array();
		return count($total);
	}

	public function countEligible($status)
	{	
		$this->db->where('employment_status !=', 'TIS');
		$this->db->where('eligible_status', $status);
		$total = $this->db->get('employee')->result_array();
		return count($total);
	}

	// C level
	public function countEligibleC($division, $status)
	{	
		if ($division == 'Finance') {
			$this->db->where("division IN ('Finance', 'Finance & Accounting', 'Procurement')");
		} else if ($division == 'Technology') {
			$this->db->where("division IN ('Technology','Engineering', 'Master Planning', 'Product Development', 'Transmission Development')");
		} else if ($division == 'Assets') {
			$this->db->where("division IN ('Assets','Assets Management','Tower Operation & Property Management','NOC & Helpdesk','Strategic Acquisition','Property Management Support')");
		} else if ($division == 'Operations') {
			$this->db->where("division IN ('Operations', 'Regional Central')");
		}

		$this->db->where('employment_status !=', 'TIS');
		$this->db->where('eligible_status', $status);
		$total = $this->db->get('employee')->result_array();
		return count($total);
	}

	public function countByStatusC($division, $status)
	{	
		if ($division == 'Finance') {
			$this->db->where("division IN ('Finance', 'Finance & Accounting', 'Procurement')");
		} else if ($division == 'Technology') {
			$this->db->where("division IN ('Technology','Engineering', 'Master Planning', 'Product Development', 'Transmission Development')");
		} else if ($division == 'Assets') {
			$this->db->where("division IN ('Assets','Assets Management','Tower Operation & Property Management','NOC & Helpdesk','Strategic Acquisition','Property Management Support')");
		} else if ($division == 'Operations') {
			$this->db->where("division IN ('Operations', 'Regional Central')");
		}

		$where = array('is_status' => $status);
		$total = $this->db->get_where('performance_appraisal', $where)->result_array();
		return count($total);
	}

	public function getTotalGradeAllC($division, $grade)
	{		
		if ($grade == 'a') {
			$where = array('is_status' => 3, 'final_score >=' => '9.1');
		} elseif ($grade == 'b') {
			$where = array('is_status' => 3, 'final_score >=' => '8.1', 'final_score <' => '9.1');
		} elseif ($grade == 'c') {
			$where = array('is_status' => 3, 'final_score >=' => '6.9', 'final_score <' => '8.1');
		} elseif ($grade == 'd') {
			$where = array('is_status' => 3, 'final_score >=' => '5.6', 'final_score <' => '6.9');
		} elseif ($grade == 'e') {
			$where = array('is_status' => 3, 'final_score >=' => '0.0', 'final_score <' => '5.6');
		}

		if ($division == 'Finance') {
			$this->db->where("division IN ('Finance', 'Finance & Accounting', 'Procurement')");
		} else if ($division == 'Technology') {
			$this->db->where("division IN ('Technology','Engineering', 'Master Planning', 'Product Development', 'Transmission Development')");
		} else if ($division == 'Assets') {
			$this->db->where("division IN ('Assets','Assets Management','Tower Operation & Property Management','NOC & Helpdesk','Strategic Acquisition','Property Management Support')");
		} else if ($division == 'Operations') {
			$this->db->where("division IN ('Operations', 'Regional Central')");
		}

		$this->db->where($where);
		$total = $this->db->get('performance_appraisal')->result_array();
		return count($total);
	}

	public function countEmployeeC($division)
	{
		if ($division == 'Finance') {
			$this->db->where("division IN ('Finance', 'Finance & Accounting', 'Procurement')");
		} else if ($division == 'Technology') {
			$this->db->where("division IN ('Technology','Engineering', 'Master Planning', 'Product Development', 'Transmission Development')");
		} else if ($division == 'Assets') {
			$this->db->where("division IN ('Assets','Assets Management','Tower Operation & Property Management','NOC & Helpdesk','Strategic Acquisition','Property Management Support')");
		} else if ($division == 'Operations') {
			$this->db->where("division IN ('Operations', 'Regional Central')");
		}

		$this->db->where('employment_status !=', 'TIS');
		$total = $this->db->get('employee')->result_array();
		return count($total);
	}

	public function countDivisionC($division)
	{
		$this->db->select('division');

		if ($division == 'Finance') {
			$this->db->where("division IN ('Finance & Accounting', 'Procurement')");
		} else if ($division == 'Technology') {
			$this->db->where("division IN ('Engineering', 'Master Planning', 'Product Development', 'Transmission Development')");
		} else if ($division == 'Assets') {
			$this->db->where("division IN ('Assets Management','Tower Operation & Property Management','NOC & Helpdesk','Strategic Acquisition','Property Management Support')");
		} else if ($division == 'Operations') {
			$this->db->where("division IN ('Regional Central')");
		}

		$this->db->group_by('division');
		$this->db->where('employment_status !=', 'TIS');
		$total = $this->db->get('employee')->result_array();
		return count($total);
	}

	public function countConfirmedC($division)
	{
		$this->db->select('division_name');

		if ($division == 'Finance') {
			$this->db->where("division_name IN ('Finance & Accounting', 'Procurement')");
		} else if ($division == 'Technology') {
			$this->db->where("division_name IN ('Engineering', 'Master Planning', 'Product Development', 'Transmission Development')");
		} else if ($division == 'Assets') {
			$this->db->where("division_name IN ('Assets Management','Tower Operation & Property Management','NOC & Helpdesk','Strategic Acquisition','Property Management Support')");
		} else if ($division == 'Operations') {
			$this->db->where("division_name IN ('Regional Central')");
		}

		$this->db->where('is_status', '3');
		$total = $this->db->get('performance_division_status')->result_array();
		return count($total);
	}

	public function getAllDataPAC($division, $eval_year)
	{
		if ($division == 'Finance') {
			$this->db->where("division IN ('Finance', 'Finance & Accounting', 'Procurement')");
		} else if ($division == 'Technology') {
			$this->db->where("division IN ('Technology','Engineering', 'Master Planning', 'Product Development', 'Transmission Development')");
		} else if ($division == 'Assets') {
			$this->db->where("division IN ('Assets','Assets Management','Tower Operation & Property Management','NOC & Helpdesk','Strategic Acquisition','Property Management Support')");
		} else if ($division == 'Operations') {
			$this->db->where("division IN ('Operations', 'Regional Central')");
		}
		$this->db->where("is_status !=", "0");
		$this->db->where("evaluation_period_start", $eval_year);
		return $this->db->get('performance_appraisal')->result();
	}

	public function getAllbyGradeC($division, $eval_year, $grade)
	{
		if ($division == 'Finance') {
			$this->db->where("division IN ('Finance', 'Finance & Accounting', 'Procurement')");
		} else if ($division == 'Technology') {
			$this->db->where("division IN ('Technology','Engineering', 'Master Planning', 'Product Development', 'Transmission Development')");
		} else if ($division == 'Assets') {
			$this->db->where("division IN ('Assets','Assets Management','Tower Operation & Property Management','NOC & Helpdesk','Strategic Acquisition','Property Management Support')");
		} else if ($division == 'Operations') {
			$this->db->where("division IN ('Operations', 'Regional Central')");
		}

		if ($grade == 'a') {
			$where = array('final_score >=' => '9.1');
		} elseif ($grade == 'b') {
			$where = array('final_score >=' => '8.1', 'final_score <' => '9.1');
		} elseif ($grade == 'c') {
			$where = array('final_score >=' => '6.9', 'final_score <' => '8.1');
		} elseif ($grade == 'd') {
			$where = array('final_score >=' => '5.6', 'final_score <' => '6.9');
		} elseif ($grade == 'e') {
			$where = array('final_score >=' => '0.0', 'final_score <' => '5.6');
		}

		$this->db->where($where);
		$this->db->where("is_status", "3");
		$this->db->where("evaluation_period_start", $eval_year);
		return $this->db->get('performance_appraisal')->result();
	}
	// End C level

	public function countTeam()
	{
		$total = $this->db->get_where('employee', array('division_root' => '1'))->result_array();
		return count($total);
	}

	public function countRequest($status)
	{	
		$where = array('division_root' => '1', 'is_status' => $status);
		$total = $this->db->get_where('performance_appraisal', $where)->result_array();
		return count($total);
	}

	public function getTotalGrade($grade)
	{
		if ($grade == 'a') {
			$where = array('division_root' => '1', 'is_status' => 3, 'final_score >=' => '9.1');
		} elseif ($grade == 'b') {
			$where = array('division_root' => '1', 'is_status' => 3, 'final_score >=' => '8.1', 'final_score <' => '9.1');
		} elseif ($grade == 'c') {
			$where = array('division_root' => '1', 'is_status' => 3, 'final_score >=' => '6.9', 'final_score <' => '8.1');
		} elseif ($grade == 'd') {
			$where = array('division_root' => '1', 'is_status' => 3, 'final_score >=' => '5.6', 'final_score <' => '6.9');
		} elseif ($grade == 'e') {
			$where = array('division_root' => '1', 'is_status' => 3, 'final_score >=' => '0.0', 'final_score <' => '5.6');
		}


		$total = $this->db->get_where('performance_appraisal', $where)->result_array();
		return count($total);
	}

	public function getTotalGradeAll($grade)
	{
		if ($grade == 'a') {
			$where = array('is_status' => 3, 'final_score >=' => '9.1');
		} elseif ($grade == 'b') {
			$where = array('is_status' => 3, 'final_score >=' => '8.1', 'final_score <' => '9.1');
		} elseif ($grade == 'c') {
			$where = array('is_status' => 3, 'final_score >=' => '6.9', 'final_score <' => '8.1');
		} elseif ($grade == 'd') {
			$where = array('is_status' => 3, 'final_score >=' => '5.6', 'final_score <' => '6.9');
		} elseif ($grade == 'e') {
			$where = array('is_status' => 3, 'final_score >=' => '0.0', 'final_score <' => '5.6');
		}

		$total = $this->db->get_where('performance_appraisal', $where)->result_array();
		return count($total);
	}

	public function getAllbyGrade($eval_year, $grade)
	{
		if ($grade == 'a') {
			$where = array('final_score >=' => '9.1');
		} elseif ($grade == 'b') {
			$where = array('final_score >=' => '8.1', 'final_score <' => '9.1');
		} elseif ($grade == 'c') {
			$where = array('final_score >=' => '6.9', 'final_score <' => '8.1');
		} elseif ($grade == 'd') {
			$where = array('final_score >=' => '5.6', 'final_score <' => '6.9');
		} elseif ($grade == 'e') {
			$where = array('final_score >=' => '0.0', 'final_score <' => '5.6');
		}

		$this->db->where($where);
		$this->db->where("is_status", "3");
		$this->db->where("evaluation_period_start", $eval_year);
		return $this->db->get('performance_appraisal')->result();
	}

	public function countDivisionM($status)
	{
		$this->db->select('division_name');
		$this->db->where('is_status', $status);
		$total = $this->db->get('performance_division_status')->result_array();
		return count($total);
	}

	public function getFamilyEmployee()
	{
		$nik 		= $this->session->userdata('employee_id');
		$this->db->order_by('family_members', 'DESC');
		$this->db->where('nik', $nik);
		$result = $this->db->get('hris_family_employee')->result();
		return $result;
	}

}