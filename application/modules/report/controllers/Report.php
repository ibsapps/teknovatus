<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('curl');
		$this->load->library('enc');
		$this->enc->check_session();

		$this->email = $this->session->userdata('user_email');
		$this->emp_id = $this->session->userdata('employee_id');
		
		$this->load->helper('general');
		$this->load->model('form/form_model');
		$this->load->model('inbox/inbox_model');
		$this->load->model('home/home_model');
		$this->load->model('report/report_model');
		$this->load->model('m_global');
		$this->date = date('Y-m-d H:i:s');
		$this->year = date('Y');

		if(empty($this->session->userdata('nik'))){
            $this->session->set_flashdata('failure', 'Login failed');
            redirect('login');
        }
	}

	public function medical_control_sheets(){
		$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$url_components = parse_url($url); 
		
		if(!empty($url_components['query'])){
			parse_str($url_components['query'], $params);
			$param = $url_components['query'];
			$nik 	= $params['nik'];
			$no_ref = $params['no_req_group'];
			if (isset($params['tahun'])) {
				$tahun = $params['tahun'];
			} else {
				$tahun = '';
			}
			

			//$listApproved = $this->report_model->getMedicalFullApproved_fix($nik, $no_ref);
			$listApproved = $this->report_model->get_data_fi_awal($nik, $no_ref, $tahun);
			// dumper($listApproved);
			if(empty($listApproved)){
				// dumper('nono');

			}else{

				// if((!empty($nik)) && (!empty($no_ref))){
				if (!empty($nik)) {
					$listApp = array();
					foreach ($listApproved as $key => $value) {
						$listApp[$key]['nik']=$value['employee_id'];
						$listApp[$key]['no_req_mdcr']=$value['no_req_mdcr'];
					}
					foreach($listApp as $k=>$v) {
	
					if( ($kt=array_search($v,$listApp))!==false and $k!=$kt )
						{ unset($listApp[$kt]);}
					
					}
					$data['listApp'] = $listApp;

				}else{
					// dumper('bambara');

					redirect('report/medical_control_sheets');
				}			
				// }else if((!empty($nik)) && (empty($no_ref))){
	
				// 	$listApp = array();
				// 	foreach ($listApproved as $key => $value) {
				// 		$listApp[$key]['nik']=$value['employee_id'];
				// 		$listApp[$key]['no_req_mdcr']=$value['no_req_mdcr'];
				// 	}
				// 	foreach($listApp as $k=>$v) {
	
				// 		if( ($kt=array_search($v,$listApp))!==false and $k!=$kt )
				// 			{ unset($listApp[$kt]);}
						
				// 		}
				// 	$data['listApp'] = $listApp;
					
				// }else if((empty($nik)) && (!empty($no_ref))){
				// 	$listApp = array();
				// 	foreach ($listApproved as $key => $value) {
				// 		$listApp[$key]['nik']=$value['employee_id'];
				// 		$listApp[$key]['no_req_mdcr']=$value['no_req_mdcr'];
				// 	}
				// 	foreach($listApp as $k=>$v) {
	
				// 		if( ($kt=array_search($v,$listApp))!==false and $k!=$kt )
				// 			{ unset($listApp[$kt]);}
						
				// 		}
				// 	$data['listApp'] = $listApp;
				// }
	
				$employeeArray = array();
				foreach ($listApproved as $key) {
					$employeeArray[] = $key['employee_id'];
				}
				$employeeArray = array_unique($employeeArray);
				$data['employee'] = $employeeArray;

			}
			
		}else{
			$param = '';
			$nik	= '';
			$no_ref = '';
			$data['employee'] = '00000000';
		}


		$sql 	= "SELECT * FROM hris_no_req_mdcr WHERE no_req_mdcr LIKE '$no_ref' AND is_status LIKE '3'";
		$query 	= $this->db->query($sql);
		$res 	= $query->result();
		if(empty($res) && ($tahun == '' || $tahun == null)){
			$year_request	= '0000';
			$query_result = 'empty';
		}else if ($tahun != '' || $tahun != null){
			$query_result = 'not empty';
			$year_request	=	$tahun;
		} else{
			$query_result = 'not empty';
			$year_request	=	strtotime($res[0]->created_at);
			$year_request	=	date("Y",$year_request);
		}

		// dumper($res);

		$data['fi_year'] = $year_request;
		$data['query'] = $param;
		$data['content_query'] = $query_result;
		$data['header'] = $this->home_model->getMyRequest();
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());

		$data['content'] = 'report/medical_control_sheets';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function medical_monthly_report(){
		$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$url_components = parse_url($url); 
		// dumper($url_components['query']);
		if(!empty($url_components['query'])){
			parse_str($url_components['query'], $params);
			$param = $url_components['query'];
			$bulan 	= $params['bulan'];
			// dumper('test');			
			//$listApproved = $this->report_model->getMedicalFullApproved_fix($nik, $no_ref);
			// $listApproved = $this->report_model->get_data_fi_awal($nik, $no_ref, $tahun);
			// dumper($listApproved);
			$start_date = $bulan.'-01';
			$end_date = date("Y-m-t", strtotime($bulan));

			// redirect('report/medical_monthly_report');
			
		}else{
			$start_date = date("Y-m-d");
			$end_date = date("Y-m-t", strtotime($start_date));
			$param = '';
			$bulan	= '';
		}



		// dumper($start_date);

		$sql 	= "SELECT a.employee_id, b.grandparent, c.parent, d.child, a.diagnosa, a.keterangan, a.penggantian
							FROM hris_medical_reimbursment_item a
							LEFT JOIN hris_medical_type_of_reimbursment_grandparent b ON a.tor_grandparent = b.id
							LEFT JOIN hris_medical_type_of_reimbursment_parent c ON a.tor_parent = c.id
							LEFT JOIN hris_medical_type_of_reimbursment_child d ON a.tor_child =  d.id
							LEFT JOIN form_request e ON a.request_id = e.id
							WHERE (a.tanggal_kuitansi BETWEEN '$start_date' AND '$end_date') AND e.no_req_mdcr IS NOT null
							ORDER BY a.create_date ASC;";
		$query 	= $this->db->query($sql);
		$res 	= $query->result();
		// dumper($res);
		if(empty($res)){
			$query_result = 'empty';
		}else {
			$query_result = 'not empty';
		}

		$data['query'] = $param;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['content_query'] = $query_result;
		$data['header'] = $this->home_model->getMyRequest();
		$data['report_monthly'] = $res;
		$data['count_mysubmission'] = count($this->home_model->getMySubmissionList());
		$data['count_approval'] = count($this->inbox_model->getApprovalList());
		$data['count_need_mdcr_cek'] = count($this->inbox_model->getApprovalListMDCRCek());
		$data['count_need_mdcr_after_cek'] = count($this->inbox_model->getApprovalListMDCRAfterCek());
		$data['count_mdcr_after_grouping_need_approved'] = count($this->inbox_model->getReqMDCRAfterGroupingNeedApproved());
		$data['count_review'] = count($this->inbox_model->getReviewList());
		$data['count_pa_mgmt'] = count($this->inbox_model->getPAList());

		// dumper($data['start_date']);

		$data['content'] = 'report/medical_monthly_report';
		$this->templates->show('index', 'templates/eapp/eapp_main', $data);
	}

	public function read($table){
		switch ($table) {
			case 'medical_control_sheets':
				$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$url_components = parse_url($url); 
				//dumper($url_components);
				if(!empty($url_components['query'])){
					parse_str($url_components['query'], $params);
					$nik 	= $params['nik'];
					$no_ref = $params['no_req_group'];
				}else{
					$nik	= '';
					$no_ref = '';
				}
				
				//dumper($nik);
				$listFA = $this->report_model->getMedicalFullApproved($nik, $no_ref);
				//dumper($listFA);
		        if (!empty($listFA)) {
		            foreach ($listFA as $key) {

		                $row   = array();
						$row[] = $key->employee_id;
		                $row[] = decrypt($key->complete_name);
		                $row[] = $key->request_number;
		                $row[] = $key->no_req_mdcr;
		                $row[] = '';
		                $data[] = $row;
		            }
		            $outputFA = array('data' => $data);
		        } else {
		            $outputFA = array('data' => new ArrayObject());
		        }
		        echo json_encode($outputFA);
				break;
			
			case 'family_employee':
				$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$url_components = parse_url($url); 
				//dumper($url_components);
				if(!empty($url_components['query'])){
					parse_str($url_components['query'], $params);
					$nik 	= $params['nik'];
					$no_ref = $params['no_req_group'];
				}else{
					$nik	= '';
					$no_ref = '';
				}
				
				//dumper($nik);
				$listF = $this->report_model->getFamilyEmployee($nik, $no_ref);
		        if (!empty($listF)) {
		            foreach ($listF as $key) {

						$member_birthdate = decrypt($key->member_birthdate);
						//dumper(encrypt('01062020'));
						$member_birthdate = DateTime::createFromFormat('Ymd', $member_birthdate);
						$member_birthdate = $member_birthdate->format('d.m.Y');

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
			
			case 'medical_monthly_report':
				$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$url_components = parse_url($url); 
				//dumper($url_components);
				if(!empty($url_components['query'])){
					parse_str($url_components['query'], $params);
					$nik 	= $params['nik'];
					$no_ref = $params['no_req_group'];
				}else{
					$nik	= '';
					$no_ref = '';
				}
				
				//dumper($nik);
				$listFA = $this->report_model->getMedicalFullApproved($nik, $no_ref);
				//dumper($listFA);
		        if (!empty($listFA)) {
		            foreach ($listFA as $key) {

		                $row   = array();
						$row[] = $key->employee_id;
		                $row[] = decrypt($key->complete_name);
		                $row[] = $key->request_number;
		                $row[] = $key->no_req_mdcr;
		                $row[] = '';
		                $data[] = $row;
		            }
		            $outputFA = array('data' => $data);
		        } else {
		            $outputFA = array('data' => new ArrayObject());
		        }
		        echo json_encode($outputFA);
				break;
			
			default:
				# code...
				break;
		}
	}

	public function get_data_fi(){
		$data = $this->report_model->get_data_fi();
		dumper($data);
	}

}
