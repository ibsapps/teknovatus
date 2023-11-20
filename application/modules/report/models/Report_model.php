<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('general');
		$this->email = $this->session->userdata('user_email');
		$this->nik = $this->session->userdata('nik');
		$this->date = date('Y-m-d H:i:s');
		$this->year = date('Y');
		$this->today = date('Y-m-d');
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

	public function getFamilyEmployee($nik="", $no_ref="")
	{
		$no_ref = encrypt($no_ref);
		$this->db->where('seqno', $no_ref);
		$this->db->where('nik', $nik);
		$result = $this->db->get('hris_family_employee')->result();
		return $result;
	}

	public function getMedicalFullApproved($nik, $no_ref){
		//dumper($no_ref);
		$sql = "SELECT *
				FROM form_request
				WHERE (employee_id LIKE '$nik' OR no_req_mdcr LIKE '$no_ref') AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
				ORDER BY id ASC";
		$query = $this->db->query($sql);
		$res = $query->result_array();
		//dumper($listId);
		//dumper($res);
		
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
			$row['no_req_mdcr'] =  $key['no_req_mdcr'];
			$row['complete_name'] =  $complete_name[0]->complete_name;
			
			//$data[] = $row;
			$data[] = (object)$row;
		}
		
		return $data;
	}

	public function getMedicalFullApproved_fix($nik, $no_ref){
		
		//dumper($nik." - ".$no_ref);
		if((!empty($nik)) && (!empty($no_ref))){
			$sql = "SELECT *
				FROM form_request
				WHERE (employee_id LIKE '$nik' AND no_req_mdcr LIKE '$no_ref') AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
				ORDER BY id ASC";
		}else if((!empty($nik)) && (empty($no_ref))){
			$sql = "SELECT *
				FROM form_request
				WHERE (employee_id LIKE '$nik') AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
				ORDER BY id ASC";
		}else if((empty($nik)) && (!empty($no_ref))){
			$sql = "SELECT *
				FROM form_request
				WHERE (no_req_mdcr LIKE '$no_ref') AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
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
			$row['no_req_mdcr'] =  $key['no_req_mdcr'];
			$row['complete_name'] =  $complete_name[0]->complete_name;
			
			$data[] = $row;
			//$data[] = (object)$row;
		}
		//dumper($data);
		return $data;
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
		//dumper($res);
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
		//dumper($year_request);
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
		//penambahan baru
		$res = $this->my_array_unique($res);
		//$id_eg_prj 	= $res[0]->employee_group;
		$res_eg_new 		= decrypt($res[0]->employee_group);
		$res_sd_new 		= decrypt($res[0]->start_date);
		$reason_of_action	= decrypt($res[0]->reason_of_action);
		$res_eg_old 		= (!empty(($res[1]->employee_group))) ? decrypt(($res[1]->employee_group)) : '';
		//dumper($res_eg_new);

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
		//dumper($start_date." - ".$year);

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
			
			$pagu_pro_inap_tahun_new = ($join_days_new/366)* $pagu_inap_tahun_new;
			$pagu_inap_tahun_old = ((!empty(($pagu_inap_tahun_old))) ? ($pagu_inap_tahun_old) : 0);
			$pagu_pro_inap_tahun_old = ($join_days_old/366)* $pagu_inap_tahun_old;
			$pagu_inap_tahun = $pagu_pro_inap_tahun_new + $pagu_pro_inap_tahun_old;
			
			$pagu_pro_jalan_tahun_new = ($join_days_new/366) * $pagu_jalan_tahun_new;
			$pagu_jalan_tahun_old = ((!empty($pagu_jalan_tahun_old)) ? ($pagu_jalan_tahun_old) : 0);
			$pagu_pro_jalan_tahun_old = ($join_days_old/366) * $pagu_jalan_tahun_old;
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
		//dumper($res_eg_new);
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
			SUM(a.penggantian) as total_penggantian_kacamata_per_request
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
		
		//dumper($data);
		return $data;

	}

	public function get_data_employee_current($employee_id, $nik = null){
		if ($nik != null) {
			$sql = "SELECT * FROM hris_employee WHERE nik LIKE '$nik'";
		} else {
			$sql = "SELECT * FROM hris_employee WHERE id_employee LIKE '$employee_id'";
		}
		
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function get_data_employee_base_on_request($request_id){

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

	public function get_data_claim_per_employee_base_on_nik_or_noreq($nik, $no_ref){

		if((!empty($nik)) && (!empty($no_ref))){
			$sql = "SELECT *
				FROM form_request
				WHERE (employee_id LIKE '$nik' AND no_req_mdcr LIKE '$no_ref') AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
				ORDER BY id ASC";
		}else if((!empty($nik)) && (empty($no_ref))){
			$sql = "SELECT *
				FROM form_request
				WHERE (employee_id LIKE '$nik') AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
				ORDER BY id ASC";
		}else if((empty($nik)) && (!empty($no_ref))){
			$sql = "SELECT *
				FROM form_request
				WHERE (no_req_mdcr LIKE '$no_ref') AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
				ORDER BY id ASC";
		}

		$query = $this->db->query($sql);
		$res 	= $query->result();

		$form_request_id = array();
		foreach ($res as $key) {
			$form_request_id[] = $key->id;
		}
		
		$form_request_no_ref = array();
		foreach ($res as $key) {
			$form_request_no_ref[] = $key->no_req_mdcr;
		}

		$form_request_id = implode(", ",$form_request_id);
		$form_request_no_ref = implode(", ",$form_request_no_ref);
		//dumper($form_request_id);
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
				a.create_date as create_date,
				f.id_hr_emp as id_hr_emp,
				e.request_number as request_number,
				e.no_req_mdcr as no_req_mdcr,
				e.employee_id as employee_id
			FROM		
				hris_medical_reimbursment_item a
			LEFT JOIN hris_medical_type_of_reimbursment_grandparent b ON a.tor_grandparent = b.id
			LEFT JOIN hris_medical_type_of_reimbursment_parent c ON a.tor_parent = c.id
			LEFT JOIN hris_medical_type_of_reimbursment_child d ON a.tor_child = d.id
			LEFT JOIN form_request e ON a.request_id = e.id
			LEFT JOIN hris_medical_reimbursment f ON e.id = f.request_id
			WHERE a.request_id IN($form_request_id) AND e.is_status = '3' AND e.form_type = 'MDCR' AND e.is_status_admin_hr LIKE '1' AND e.is_status_divhead_hr LIKE '1'";
		$query = $this->db->query($sql);
		$res 	= $query->result();
		//dumper($res);
		if($res){
			return $res;
		}else{
			return false;
		}
	}

	public function get_data_fi($nik, $no_ref, $tahun = ''){
		// dumper('jeje');
		$test = [
			'nik' => $nik,
			'no_ref' => $no_ref,
			'tahun' => $tahun,
		];

		// dumper($test);
		if (($tahun != null || $tahun != '') && ($no_ref == null || $no_ref == '' || $no_ref == 'HRIS_MDCR')) {
			// dumper('1');

			//================ old ====================
			// $thn = substr($tahun,2,2);
			// $sql = "SELECT *
			// 	FROM form_request
			// 	WHERE employee_id LIKE '$nik' AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
			// 	AND SUBSTRING(no_req_mdcr, 14, 2) = '$thn' ORDER BY id ASC";
				// dumper($sql);
			//================ new ====================
			$sql = "SELECT *
				FROM form_request
				WHERE employee_id LIKE '$nik' AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
				ORDER BY id ASC";

				// dumper($sql);

			$query = $this->db->query($sql);
			$res = $query->result();

			$listReq_id = array();
			foreach ($res as $key) {
				$listReq_id[] = $key->id;
			}

			$form_request_id = join("','",$listReq_id); 
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
					a.create_date as create_date,
					f.id_hr_emp as id_hr_emp,
					e.request_number as request_number,
					e.no_req_mdcr as no_req_mdcr,
					e.employee_id as employee_id
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_type_of_reimbursment_grandparent b ON a.tor_grandparent = b.id
				LEFT JOIN hris_medical_type_of_reimbursment_parent c ON a.tor_parent = c.id
				LEFT JOIN hris_medical_type_of_reimbursment_child d ON a.tor_child = d.id
				LEFT JOIN form_request e ON a.request_id = e.id
				LEFT JOIN hris_medical_reimbursment f ON e.id = f.request_id
				WHERE a.request_id IN('$form_request_id') AND e.is_status = '3' AND e.form_type = 'MDCR' AND e.is_status_admin_hr LIKE '1' AND e.is_status_divhead_hr LIKE '1'
				AND YEAR(a.tanggal_kuitansi) = '$tahun'";
			$query = $this->db->query($sql);
			// dumper($sql);
			$res 	= $query->result();
		} else if (($tahun != null || $tahun != '') && ($no_ref != null || $no_ref != '' || $no_ref != 'HRIS_MDCR')) {

			$old_no_ref = $no_ref;
			// $year_request	=	$tahun;
			//================ old ====================
			// $sql 	= "SELECT id, no_req_mdcr FROM hris_no_req_mdcr WHERE id <= (SELECT a.id FROM hris_no_req_mdcr a LEFT JOIN form_request b ON a.no_req_mdcr LIKE b.no_req_mdcr WHERE b.no_req_mdcr LIKE '$no_ref' AND b.employee_id LIKE '$nik' LIMIT 1) AND is_status LIKE '3' AND year(created_at) LIKE '%$tahun%'";
			//================ new ====================
			$sql 	= "SELECT id, no_req_mdcr FROM hris_no_req_mdcr WHERE id <= (SELECT a.id FROM hris_no_req_mdcr a LEFT JOIN form_request b ON a.no_req_mdcr LIKE b.no_req_mdcr WHERE b.no_req_mdcr LIKE '$no_ref' AND b.employee_id LIKE '$nik' LIMIT 1) AND is_status LIKE '3'";
			$query 	= $this->db->query($sql);
			$res 	= $query->result();
			// dumper($sql);

			$listNoReq = array();
			foreach ($res as $key) {
				$listNoReq[] = $key->no_req_mdcr;
			}

			$no_ref = join("','",$listNoReq);   

			$sql = "SELECT *
					FROM form_request
					WHERE ((employee_id LIKE '$nik') AND (no_req_mdcr IN ('$no_ref'))) AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
					ORDER BY id ASC";
			// dumper($sql);

			$query = $this->db->query($sql);
			$res = $query->result();

			// $thn = substr($tahun,2,2);
			// $sql = "SELECT *
			// 		FROM form_request
			// 		WHERE ((employee_id LIKE '$nik') AND (no_req_mdcr IN ('$no_ref'))) AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1' AND SUBSTRING(no_req_mdcr, 14, 2) = '$thn'
			// 		ORDER BY id ASC";
			// 		// dumper($sql);
			// $query = $this->db->query($sql);
			// $res = $query->result_array();

			// dumper($res[0]['id']);
			$listReq_id = array();
			foreach ($res as $key) {
				$listReq_id[] = $key->id;
			}

			$form_request_id = join("','",$listReq_id); 
			// $form_request_id = $res[0]['id']; 
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
					a.create_date as create_date,
					f.id_hr_emp as id_hr_emp,
					e.request_number as request_number,
					e.no_req_mdcr as no_req_mdcr,
					e.employee_id as employee_id
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_type_of_reimbursment_grandparent b ON a.tor_grandparent = b.id
				LEFT JOIN hris_medical_type_of_reimbursment_parent c ON a.tor_parent = c.id
				LEFT JOIN hris_medical_type_of_reimbursment_child d ON a.tor_child = d.id
				LEFT JOIN form_request e ON a.request_id = e.id
				LEFT JOIN hris_medical_reimbursment f ON e.id = f.request_id
				WHERE a.request_id IN('$form_request_id') AND e.is_status = '3' AND e.form_type = 'MDCR' AND e.is_status_admin_hr LIKE '1' AND e.is_status_divhead_hr LIKE '1'
				AND YEAR(a.tanggal_kuitansi) = '$tahun'";
				// dumper($sql);
			$query = $this->db->query($sql);
			$res 	= $query->result();
		} else {
			// dumper('3');
			$old_no_ref = $no_ref;
			$sql 	= "SELECT * FROM hris_no_req_mdcr WHERE no_req_mdcr LIKE '$no_ref' AND is_status LIKE '3'";
			$query 	= $this->db->query($sql);
			$res 	= $query->result();
			$year_request	=	strtotime($res[0]->created_at);
			$year_request	=	date("Y",$year_request);
			$sql 	= "SELECT id, no_req_mdcr FROM hris_no_req_mdcr WHERE id <= (SELECT a.id FROM hris_no_req_mdcr a LEFT JOIN form_request b ON a.no_req_mdcr LIKE b.no_req_mdcr WHERE b.no_req_mdcr LIKE '$no_ref' AND b.employee_id LIKE '$nik' LIMIT 1) AND is_status LIKE '3' AND year(created_at) LIKE '%$year_request%'";
			$query 	= $this->db->query($sql);
			$res 	= $query->result();

			$listNoReq = array();
			foreach ($res as $key) {
				$listNoReq[] = $key->no_req_mdcr;
			}

			$no_ref = join("','",$listNoReq);   

			$sql = "SELECT *
					FROM form_request
					WHERE ((employee_id LIKE '$nik') AND (no_req_mdcr IN ('$no_ref'))) AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
					ORDER BY id ASC";

			$query = $this->db->query($sql);
			$res = $query->result();

			$listReq_id = array();
			foreach ($res as $key) {
				$listReq_id[] = $key->id;
			}

			$form_request_id = join("','",$listReq_id); 
			// ---- old ----;

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
				a.create_date as create_date,
				f.id_hr_emp as id_hr_emp,
				e.request_number as request_number,
				e.no_req_mdcr as no_req_mdcr,
				e.employee_id as employee_id
				FROM		
					hris_medical_reimbursment_item a
				LEFT JOIN hris_medical_type_of_reimbursment_grandparent b ON a.tor_grandparent = b.id
				LEFT JOIN hris_medical_type_of_reimbursment_parent c ON a.tor_parent = c.id
				LEFT JOIN hris_medical_type_of_reimbursment_child d ON a.tor_child = d.id
				LEFT JOIN form_request e ON a.request_id = e.id
				LEFT JOIN hris_medical_reimbursment f ON e.id = f.request_id
				WHERE a.request_id IN('$form_request_id') AND e.is_status = '3' AND e.form_type = 'MDCR' AND e.is_status_admin_hr LIKE '1' AND e.is_status_divhead_hr LIKE '1' 
				AND year(a.tanggal_kuitansi) LIKE '%$year_request%'";
				// ---- new ----;

				// $sql = "SELECT 	
				// 		a.id as id,
				// 		a.request_id as request_id,
				// 		e.is_status as is_status,
				// 		b.grandparent as tor_grandparent,
				// 		c.parent as tor_parent,
				// 		d.child as tor_child,
				// 		a.jumlah_kuitansi as jumlah_kuitansi,
				// 		a.total_nominal_kuitansi as total_kuitansi,
				// 		a.penggantian as penggantian,
				// 		a.keterangan as keterangan,
				// 		a.additional as additional,
				// 		a.docter as docter,
				// 		a.diagnosa as diagnosa,
				// 		a.tanggal_kuitansi as tanggal_kuitansi,
				// 		a.create_date as create_date,
				// 		f.id_hr_emp as id_hr_emp,
				// 		e.request_number as request_number,
				// 		e.no_req_mdcr as no_req_mdcr,
				// 		e.employee_id as employee_id
				// 	FROM		
				// 		hris_medical_reimbursment_item a
				// 	LEFT JOIN hris_medical_type_of_reimbursment_grandparent b ON a.tor_grandparent = b.id
				// 	LEFT JOIN hris_medical_type_of_reimbursment_parent c ON a.tor_parent = c.id
				// 	LEFT JOIN hris_medical_type_of_reimbursment_child d ON a.tor_child = d.id
				// 	LEFT JOIN form_request e ON a.request_id = e.id
				// 	LEFT JOIN hris_medical_reimbursment f ON e.id = f.request_id
				// 	WHERE a.request_id IN('$form_request_id') AND e.is_status = '3' AND e.form_type = 'MDCR' AND e.is_status_admin_hr LIKE '1' AND e.is_status_divhead_hr LIKE '1' AND  e.no_req_mdcr = '$old_no_ref'";

			$query = $this->db->query($sql);
			$res 	= $query->result();
		}
		
		// ---- old ----;
		// $sql = "SELECT 	
		// 		a.id as id,
		// 		a.request_id as request_id,
		// 		e.is_status as is_status,
		// 		b.grandparent as tor_grandparent,
		// 		c.parent as tor_parent,
		// 		d.child as tor_child,
		// 		a.jumlah_kuitansi as jumlah_kuitansi,
		// 		a.total_nominal_kuitansi as total_kuitansi,
		// 		a.penggantian as penggantian,
		// 		a.keterangan as keterangan,
		// 		a.additional as additional,
		// 		a.docter as docter,
		// 		a.diagnosa as diagnosa,
		// 		a.tanggal_kuitansi as tanggal_kuitansi,
		// 		a.create_date as create_date,
		// 		f.id_hr_emp as id_hr_emp,
		// 		e.request_number as request_number,
		// 		e.no_req_mdcr as no_req_mdcr,
		// 		e.employee_id as employee_id
		// 	FROM		
		// 		hris_medical_reimbursment_item a
		// 	LEFT JOIN hris_medical_type_of_reimbursment_grandparent b ON a.tor_grandparent = b.id
		// 	LEFT JOIN hris_medical_type_of_reimbursment_parent c ON a.tor_parent = c.id
		// 	LEFT JOIN hris_medical_type_of_reimbursment_child d ON a.tor_child = d.id
		// 	LEFT JOIN form_request e ON a.request_id = e.id
		// 	LEFT JOIN hris_medical_reimbursment f ON e.id = f.request_id
		// 	WHERE a.request_id IN('$form_request_id') AND e.is_status = '3' AND e.form_type = 'MDCR' AND e.is_status_admin_hr LIKE '1' AND e.is_status_divhead_hr LIKE '1' AND year(a.tanggal_kuitansi) LIKE '%$year_request%'";

		// ---- new ----;

		// 	$sql = "SELECT 	
		// 		a.id as id,
		// 		a.request_id as request_id,
		// 		e.is_status as is_status,
		// 		b.grandparent as tor_grandparent,
		// 		c.parent as tor_parent,
		// 		d.child as tor_child,
		// 		a.jumlah_kuitansi as jumlah_kuitansi,
		// 		a.total_nominal_kuitansi as total_kuitansi,
		// 		a.penggantian as penggantian,
		// 		a.keterangan as keterangan,
		// 		a.additional as additional,
		// 		a.docter as docter,
		// 		a.diagnosa as diagnosa,
		// 		a.tanggal_kuitansi as tanggal_kuitansi,
		// 		a.create_date as create_date,
		// 		f.id_hr_emp as id_hr_emp,
		// 		e.request_number as request_number,
		// 		e.no_req_mdcr as no_req_mdcr,
		// 		e.employee_id as employee_id
		// 	FROM		
		// 		hris_medical_reimbursment_item a
		// 	LEFT JOIN hris_medical_type_of_reimbursment_grandparent b ON a.tor_grandparent = b.id
		// 	LEFT JOIN hris_medical_type_of_reimbursment_parent c ON a.tor_parent = c.id
		// 	LEFT JOIN hris_medical_type_of_reimbursment_child d ON a.tor_child = d.id
		// 	LEFT JOIN form_request e ON a.request_id = e.id
		// 	LEFT JOIN hris_medical_reimbursment f ON e.id = f.request_id
		// 	WHERE a.request_id IN('$form_request_id') AND e.is_status = '3' AND e.form_type = 'MDCR' AND e.is_status_admin_hr LIKE '1' AND e.is_status_divhead_hr LIKE '1' AND  e.no_req_mdcr = '$old_no_ref'";
		// $query = $this->db->query($sql);
		// $res 	= $query->result();
		// dumper($res);

		if($res){
			return $res;
		}else{
			return false;
		}

	}


	public function get_data_fi_awal($nik, $no_ref, $tahun=''){
		if (($tahun != '' || $tahun != null) && ($no_ref == null || $no_ref == '' || $no_ref == 'HRIS_MDCR')) {
			// dumper('jeje');
			//================ old ====================
			// $thn = substr($tahun,2,2);
			// $sql3 = "SELECT *
			// 	FROM form_request
			// 	WHERE employee_id LIKE '$nik' AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
			// 	AND SUBSTRING(no_req_mdcr, 14, 2) = '$thn' ORDER BY id ASC";
			//================ new ====================
			$sql3 = "SELECT *
				FROM form_request a
				LEFT JOIN hris_medical_reimbursment_item b on a.id = b.request_id
				WHERE a.employee_id LIKE '$nik' AND a.is_status = '3' AND a.form_type = 'MDCR' AND a.is_status_admin_hr LIKE '1' AND a.is_status_divhead_hr LIKE '1'
				AND YEAR(b.tanggal_kuitansi) = '$tahun'
				ORDER BY a.id ASC";
			$query = $this->db->query($sql3);
			$res3 = $query->result_array();
		} else if (($tahun != '' || $tahun != null) && ($no_ref != null || $no_ref != '' || $no_ref != 'HRIS_MDCR')) {
			// dumper('ciojw');

			// $year_request	=	$tahun;
			//=================== old ===================
			// $sql2 	= "SELECT id, no_req_mdcr FROM hris_no_req_mdcr WHERE id <= (SELECT a.id FROM hris_no_req_mdcr a LEFT JOIN form_request b ON a.no_req_mdcr LIKE b.no_req_mdcr WHERE b.no_req_mdcr LIKE '$no_ref' AND b.employee_id LIKE '$nik' LIMIT 1) AND is_status LIKE '3' AND year(created_at) LIKE '%$year_request%'";
			// $query 	= $this->db->query($sql2);
			// $res2 	= $query->result();
			//=================== new ===================
				$sql2 	= "SELECT id, no_req_mdcr FROM hris_no_req_mdcr WHERE id <= (SELECT a.id FROM hris_no_req_mdcr a LEFT JOIN form_request b ON a.no_req_mdcr LIKE b.no_req_mdcr WHERE b.no_req_mdcr LIKE '$no_ref' AND b.employee_id LIKE '$nik' LIMIT 1) AND is_status LIKE '3'";
			$query 	= $this->db->query($sql2);
			$res2 	= $query->result();
			$listNoReq = array();
			foreach ($res2 as $key) {
				$listNoReq[] = $key->no_req_mdcr;
			}

			$no_ref = join("','",$listNoReq);  
			//=================== old =================== 
			// $thn = substr($tahun,2,2);
			// $sql3 = "SELECT *
			// 		FROM form_request
			// 		WHERE ((employee_id LIKE '$nik') AND (no_req_mdcr IN ('$no_ref'))) AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1' AND SUBSTRING(no_req_mdcr, 14, 2) = '$thn'
			// 		ORDER BY id ASC";
			//=================== new ===================
			$sql3 = "SELECT *
					FROM form_request a
					LEFT JOIN hris_medical_reimbursment_item b on a.id = b.request_id
					WHERE ((a.employee_id LIKE '$nik') AND (a.no_req_mdcr IN ('$no_ref'))) AND a.is_status = '3' AND a.form_type = 'MDCR' AND a.is_status_admin_hr LIKE '1' AND a.is_status_divhead_hr LIKE '1' AND YEAR(b.tanggal_kuitansi) = '$tahun'
					ORDER BY a.id ASC";
			// dumper($sql3);

			$query = $this->db->query($sql3);
			$res3 = $query->result_array();
		} else {

			$sql 	= "SELECT * FROM hris_no_req_mdcr WHERE no_req_mdcr LIKE '$no_ref' AND is_status LIKE '3'";
			$query 	= $this->db->query($sql);
			$res 	= $query->result();
			// dumper($sql);

			if(empty($res)){
				$year_request	= '0000';
			}else{
				$year_request	=	strtotime($res[0]->created_at);
				$year_request	=	date("Y",$year_request);
			}

			$sql2 	= "SELECT id, no_req_mdcr FROM hris_no_req_mdcr WHERE id <= (SELECT a.id FROM hris_no_req_mdcr a LEFT JOIN form_request b ON a.no_req_mdcr LIKE b.no_req_mdcr WHERE b.no_req_mdcr LIKE '$no_ref' AND b.employee_id LIKE '$nik' LIMIT 1) AND is_status LIKE '3' AND year(created_at) LIKE '%$year_request%'";
			$query 	= $this->db->query($sql2);
			$res2 	= $query->result();

			$listNoReq = array();
			foreach ($res2 as $key) {
				$listNoReq[] = $key->no_req_mdcr;
			}

			$no_ref = join("','",$listNoReq);   

			$sql3 = "SELECT *
					FROM form_request
					WHERE ((employee_id LIKE '$nik') AND (no_req_mdcr IN ('$no_ref'))) AND is_status = '3' AND form_type = 'MDCR' AND is_status_admin_hr LIKE '1' AND is_status_divhead_hr LIKE '1'
					ORDER BY id ASC";
			// dumper($sql3);

			$query = $this->db->query($sql3);
			$res3 = $query->result_array();
		}
		

		// dumper($sql3);

		$data = array();
		foreach ($res3 as $key) {
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
			$row['no_req_mdcr'] =  $key['no_req_mdcr'];
			$row['complete_name'] =  $complete_name[0]->complete_name;
			
			$data[] = $row;
			//$data[] = (object)$row;
		}
		// dumper($data);
		if($data){
			return $data;
		}else{
			return false;
		}

	}


	public function get_data_pagu($employee_id){

		$today = $this->today;

		// $sql = "SELECT 	
		// 			TOP 2 employee_group as employee_group,
		// 			start_date as start_date,
		// 			reason_of_action as reason_of_action
		// 		FROM		
		// 			hris_employee
		// 		WHERE nik LIKE '$employee_id' ORDER BY id_employee DESC";
		$sql = "SELECT 	
					employee_group as employee_group,
					start_date as start_date,
					reason_of_action as reason_of_action
				FROM		
					hris_employee
				WHERE nik LIKE '$employee_id' ORDER BY id_employee DESC";
		$query = $this->db->query($sql);
		$res 		= $query->result();
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
		//dumper($pagu_jalan_tahun." - ".$pagu_inap_tahun." - ".$pagu_inap_kamar);

		$sql4 = "SELECT 	
					pagu_one_focus_tahun as pagu_one_focus_tahun,
					pagu_two_focus_tahun as pagu_two_focus_tahun,
					pagu_frame_dua_tahun as pagu_frame_dua_tahun
				FROM		
					hris_medical_pagu_kacamata
				WHERE grade = '$res_eg_new' AND start_date <= '$today' AND end_date > '$today'";
		$query4 = $this->db->query($sql4);
		$res4 	= $query4->result();
		//dumper($res_eg_new);
		$pagu_one_focus_tahun 	= $res4[0]->pagu_one_focus_tahun;
		$pagu_two_focus_tahun 	= $res4[0]->pagu_two_focus_tahun;
		$pagu_frame_dua_tahun 	= $res4[0]->pagu_frame_dua_tahun;

		$pagu_optic_tahun = ($pagu_one_focus_tahun + $pagu_two_focus_tahun + $pagu_frame_dua_tahun);


		$data = array(
			'pagu_jalan_tahun' => ( $pagu_jalan_tahun ),
			'pagu_inap_tahun' => ( $pagu_inap_tahun ),
			'pagu_optic_tahun' => ( $pagu_optic_tahun )
		);
		
		return $data;

	}


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


	public function get_data_fi_total($form_request_id, $fi_year){
		$sql = "SELECT 	
				SUM(CASE
						WHEN tor_grandparent = '1'
							THEN penggantian
						ELSE 0
					END)  as sum_penggantian_jalan,
				SUM(CASE
					WHEN tor_grandparent = '2'
						THEN penggantian
					ELSE 0
				END)  as sum_penggantian_inap,
				SUM(CASE
					WHEN tor_grandparent = '3'
						THEN penggantian
					ELSE 0
				END)  as sum_penggantian_kacamata
			FROM		
				hris_medical_reimbursment_item
			WHERE request_id IN('$form_request_id') AND year(tanggal_kuitansi) LIKE '%$fi_year%'";
		$query = $this->db->query($sql);
		$res 	= $query->result();
		// dumper($sql);
		if($res){
			return $res;
		}else{
			return false;
		}

	}

}