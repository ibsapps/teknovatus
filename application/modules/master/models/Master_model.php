<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {

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

	public function getEmployee()
	{
		//GROUP BY nik ORDER BY id_employee DESC
		// $sql = "SELECT * FROM hris_employee GROUP BY nik, complete_name";
		//$query  = $this->db->query($sql);
		// $this->db->group_by('nik'); 
		// $this->db->order_by('id_employee', 'desc');
		$result = $this->db->get('v_hris_employee_updated')->result();
		// dumper($result);
		return $result;
	}

	public function getFamilyEmployee()
	{
		$result = $this->db->get('hris_family_employee')->result();
		return $result;
	}

	public function getCoupleEmployee()
	{
		$result = $this->db->get('hris_couple_employee')->result();
		return $result;
	}



	public function getPaguRawatJalan()
	{
		$result = $this->db->get('hris_medical_pagu_rawat_jalan')->result();
		return $result;
	}

	public function getEditPaguRawatJalan($id)
	{

		// $sql = "SELECT a.employee_id, b.complete_name, c.grandparent, d.parent, e.child, a.diagnosa, a.keterangan, a.total_nominal_kuitansi
		// 		FROM hris_medical_reimbursment_item a
		// 		LEFT JOIN hris_employee b ON a.employee_id = b.nik
		// 		LEFT JOIN hris_medical_type_of_reimbursment_grandparent c ON a.tor_grandparent =  c.id
		// 		LEFT JOIN hris_medical_type_of_reimbursment_parent d ON a.tor_parent = d.id
		// 		LEFT JOIN hris_medical_type_of_reimbursment_child e ON a.tor_child = e.id
		// 		ORDER BY a.employee_id ASC"

		$sql = "SELECT * FROM hris_medical_pagu_rawat_jalan WHERE id='$id'";
		$query = $this->db->query($sql);
		// dumper($query);
		$res = $query->result();
		return $res;
	}

	public function getEditPaguRawatInap($id)
	{
		$sql = "SELECT * FROM hris_medical_pagu_rawat_inap WHERE id='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function getEditPaguMaternity($id)
	{
		$sql = "SELECT * FROM hris_medical_pagu_maternity WHERE id='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function getEditPaguKacamata($id)
	{
		$sql = "SELECT * FROM hris_medical_pagu_kacamata WHERE id='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function getPaguRawatInap()
	{
		$result = $this->db->get('hris_medical_pagu_rawat_inap')->result();
		return $result;
	}

	public function getPaguMaternity()
	{
		$result = $this->db->get('hris_medical_pagu_maternity')->result();
		return $result;
	}

	public function getPaguKacamata()
	{
		$result = $this->db->get('hris_medical_pagu_kacamata')->result();
		return $result;
	}
	
	public function setPaguRawatJalan($start_date, $end_date, $grade, $pagu_tahun){		
		$formData = array(
					'start_date' => date('Y-m-d',strtotime($start_date)),
					'end_date' => date('Y-m-d',strtotime($start_date)),
					'grade' => $grade,
					'pagu_tahun' => $pagu_tahun
				);
		$query = $this->db->insert("hris_medical_pagu_rawat_jalan", $formData);
		// $query = $this->db->insert_id();
		if($query){
			return true;
		}else{
			return false;
		}

	}

	public function updatePaguRawatJalan($id, $start_date, $end_date, $grade, $pagu_tahun){

		$start_date = date('Y-m-d',strtotime($start_date));
		$end_date = date('Y-m-d',strtotime($end_date));

		$sql = "UPDATE hris_medical_pagu_rawat_jalan SET start_date='$start_date', end_date='$end_date', grade='$grade', pagu_tahun='$pagu_tahun' WHERE id='$id'";
		$query = $this->db->query($sql);
		
		if($query){
			return true;
		}else{
			return false;
		}

	}

	public function getDeletePaguRawatJalan($id)
	{
		$id = encrypt($id);
		$sql = "SELECT * FROM hris_medical_reimbursment WHERE id_eg_prj='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		if($res){
			$res;
			return false;
		}else{
			$id = decrypt($id);
			$sql = "DELETE FROM hris_medical_pagu_rawat_jalan where id = '".$id."'";
			$query = $this->db->query($sql);
			return true;
		}
	}

	public function setPaguRawatInap($start_date, $end_date, $grade, $pagu_kamar, $pagu_tahun){

		$formData = array(
					'start_date' => date('Y-m-d',strtotime($start_date)),
					'end_date' => date('Y-m-d',strtotime($end_date)),
					'grade' => $grade,
					'pagu_kamar_hari' => $pagu_kamar,
					'pagu_tahun' => $pagu_tahun
				);
		
		$query = $this->db->insert("hris_medical_pagu_rawat_inap", $formData);
		// $query = $this->db->insert_id();
		if($query){
			return true;
		}else{
			return false;
		}

	}

	public function setPaguMaternity($start_date, $end_date, $melahirkan, $grade, $pagu_tahun){

		$formData = array(
					'start_date' => date('Y-m-d',strtotime($start_date)),
					'end_date' => date('Y-m-d',strtotime($end_date)),
					'melahirkan' => $melahirkan,
					'grade' => $grade,
					'pagu_tahun' => $pagu_tahun
				);
		
		$query = $this->db->insert("hris_medical_pagu_maternity", $formData);
		// $query = $this->db->insert_id();
		if($query){
			return true;
		}else{
			return false;
		}

	}

	public function updatePaguRawatInap($id, $start_date, $end_date, $grade, $pagu_kamar, $pagu_tahun){

		$start_date = date('Y-m-d',strtotime($start_date));
		$end_date = date('Y-m-d',strtotime($end_date));

		$sql = "UPDATE hris_medical_pagu_rawat_inap SET start_date='$start_date', end_date='$end_date', grade='$grade', pagu_kamar_hari='$pagu_kamar', pagu_tahun='$pagu_tahun' WHERE id='$id'";
		$query = $this->db->query($sql);
		

		if($query){
			return true;
		}else{
			return false;
		}

	}

	public function updatePaguMaternity($id, $start_date, $end_date, $melahirkan, $grade, $pagu_tahun){

		$start_date = date('Y-m-d',strtotime($start_date));
		$end_date = date('Y-m-d',strtotime($end_date));

		$sql = "UPDATE hris_medical_pagu_maternity SET start_date='$start_date', end_date='$end_date', melahirkan='$melahirkan', grade='$grade', pagu_tahun='$pagu_tahun' WHERE id='$id'";
		$query = $this->db->query($sql);
		
		if($query){
			return true;
		}else{
			return false;
		}

	}

	public function getDeletePaguRawatInap($id)
	{
		$id = encrypt($id);
		$sql = "SELECT * FROM hris_medical_reimbursment WHERE id_eg_pri='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		if($res){
			$res;
			return false;
		}else{
			$id = decrypt($id);
			$sql = "DELETE FROM hris_medical_pagu_rawat_inap where id = '".$id."'";
			$query = $this->db->query($sql);
			return true;
		}
	}

	public function getDeletePaguMaternity($id)
	{
		$sql = "DELETE FROM hris_medical_pagu_maternity where id = '".$id."'";
		$query = $this->db->query($sql);
		return true;
	}

	public function setPaguKacamata($start_date, $end_date, $grade, $pagu_one_focus, $pagu_two_focus, $pagu_frame){

		$formData = array(
					'start_date' => date('Y-m-d',strtotime($start_date)),
					'end_date' => date('Y-m-d',strtotime($end_date)),
					'grade' => $grade,
					'pagu_one_focus_tahun' => $pagu_one_focus,
					'pagu_two_focus_tahun' => $pagu_two_focus,
					'pagu_frame_dua_tahun' => $pagu_frame
				);
		
		$query = $this->db->insert("hris_medical_pagu_kacamata", $formData);
		// $query = $this->db->insert_id();
		if($query){
			return true;
		}else{
			return false;
		}

	}

	public function updatePaguKacamata($id, $start_date, $end_date, $grade, $pagu_one_focus, $pagu_two_focus, $pagu_frame){
		
		$start_date = date('Y-m-d',strtotime($start_date));
		$end_date = date('Y-m-d',strtotime($end_date));
		
		$sql = "UPDATE hris_medical_pagu_kacamata SET start_date='$start_date', end_date='$end_date', grade='$grade', pagu_one_focus_tahun='$pagu_one_focus', pagu_two_focus_tahun='$pagu_two_focus', pagu_frame_dua_tahun='$pagu_frame' WHERE id='$id'";
		$query = $this->db->query($sql);
		
		if($query){
			return true;
		}else{
			return false;
		}

	}

	public function getDeletePaguKacamata($id)
	{
		$id = encrypt($id);
		$sql = "SELECT * FROM hris_medical_reimbursment WHERE id_eg_pk='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		if($res){
			$res;
			return false;
		}else{
			$id = decrypt($id);
			$sql = "DELETE FROM hris_medical_pagu_kacamata where id = '".$id."'";
			$query = $this->db->query($sql);
			return true;
		}
	}

	public function setGrandparent($grandparent, $description=""){

		$formData = array(
					'grandparent' => $grandparent,
					'description' => $description
				);
		
		$query = $this->db->insert("hris_medical_type_of_reimbursment_grandparent", $formData);
		// $query = $this->db->insert_id();
		if($query){
			return true;
		}else{
			return false;
		}

	}

	public function setParent($parent_grandparent, $parent, $description_parent=""){

		$formData = array(
					'grandparent' => $parent_grandparent,
					'parent' => $parent,
					'description' => $description_parent
				);
		
		$query = $this->db->insert("hris_medical_type_of_reimbursment_parent", $formData);
		// $query = $this->db->insert_id();
		if($query){
			return true;
		}else{
			return false;
		}

	}

	public function setChild($start_date, $end_date, $child_grandparent, $child_parent, $child, $claim_percentage_child, $description_child=""){

		$formData = array(
					'start_date' => date('Y-m-d',strtotime($start_date)),
					'end_date' => date('Y-m-d',strtotime($end_date)),
					'grandparent' => $child_grandparent,
					'parent' => $child_parent,
					'child' => $child,
					'claim_percentage' => $claim_percentage_child,
					'description' => $description_child
				);
		
		$query = $this->db->insert("hris_medical_type_of_reimbursment_child", $formData);
		// $query = $this->db->insert_id();
		if($query){
			return true;
		}else{
			return false;
		}

	}

	public function getGrandparent()
	{
		$result = $this->db->get('hris_medical_type_of_reimbursment_grandparent')->result();
		return $result;
	}

	public function getParent()
	{
		$sql = "SELECT 	
					a.id as id,
					b.grandparent as grandparent,
					a.parent as parent,
					a.description as description
				FROM		
					hris_medical_type_of_reimbursment_parent a
				LEFT JOIN hris_medical_type_of_reimbursment_grandparent b ON a.grandparent = b.id";

		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function getChild()
	{
		$sql = "SELECT 	
					a.id as id,
					a.grandparent as id_grandparent,
					a.start_date as start_date,
					a.end_date as end_date,
					b.grandparent as grandparent,
					c.parent as parent,
					a.child as child,
					a.claim_percentage as claim_percentage,
					a.claim_value as claim_value,
					a.description as description
				FROM		
					hris_medical_type_of_reimbursment_child a
				LEFT JOIN hris_medical_type_of_reimbursment_grandparent b ON a.grandparent = b.id
				LEFT JOIN hris_medical_type_of_reimbursment_parent c ON a.parent = c.id
				ORDER BY a.grandparent";

		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;


	}

	public function getUsers()
	{
		$sql = "SELECT * FROM users ORDER BY id_user ASC";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function getRPM()
	{
		$sql = "SELECT * FROM hris_rpm ORDER BY id ASC";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function get_Grandparent(){
		$sql = "SELECT * FROM hris_medical_type_of_reimbursment_grandparent";
		$query  = $this->db->query($sql);
		$output = '<option value="">Jenis Penggantian</option>';
		foreach($query->result() as $row)
		{
			$output .= '<option value="'.$row->id.'">'.$row->grandparent.'</option>';
		}
		return $output;
	}

	public function edit_get_Grandparent($id){
		$sql = "SELECT * FROM hris_medical_type_of_reimbursment_grandparent";
		$query  = $this->db->query($sql);

		$output = '';
		foreach($query->result() as $row)
		{
			if ($row->id == $id){
				$output .= '<option value="'.$row->id.'" selected>' .$row->grandparent. '</option>';
			}else{
				$output .= '<option value="'.$row->id.'">'.$row->grandparent.'</option>';
			}
			
		}
		return $output;
	}

	public function get_Parent($id_grandparent, $emp_group = null){
		$user_nik = $this->session->userdata('nik');
		if ($emp_group == 'GOL A' || $emp_group == 'GOL B' || $emp_group == 'GOL C' || $emp_group == 'GOL D' || $emp_group == 'GOL E' || $user_nik = '00000000') {
			$sql = "SELECT * FROM hris_medical_type_of_reimbursment_parent WHERE grandparent='$id_grandparent' and is_active = 1";
		} else {
			$sql = "SELECT * FROM hris_medical_type_of_reimbursment_parent WHERE grandparent='$id_grandparent' and is_active = 1 and parent != 'Medical Check Up'";
		}
		$query  = $this->db->query($sql);
		$output = '<option value="">Sub Penggantian</option>';
		foreach($query->result() as $row)
		{
		$output .= '<option value="'.$row->id.'">'.$row->parent.'</option>';
		}

		// if ($emp_group != 'GOL A' || $emp_group != 'GOL B' || $emp_group != 'GOL C' || $emp_group != 'GOL D' || $emp_group != 'GOL E') {
		// 	$array = array_diff($output,['Medical Check Up']);
		// }
		// dumper($output);
		return $output;
	}

	public function edit_get_Parent($id){
		$sql = "SELECT * FROM hris_medical_type_of_reimbursment_parent";
		$query  = $this->db->query($sql);

		$output = '';
		foreach($query->result() as $row)
		{
			if ($row->id == $id){
				$output .= '<option value="'.$row->id.'" selected>' .$row->parent. '</option>';
			}else{
				$output .= '<option value="'.$row->id.'">'.$row->parent.'</option>';
			}
			
		}
		return $output;
	}

	public function get_Child($id_parent){
		$sql = "SELECT * FROM hris_medical_type_of_reimbursment_child WHERE parent='$id_parent'";
		$query  = $this->db->query($sql);
		$output = '<option value="">Penggantian</option>';
		foreach($query->result() as $row)
		{
		$output .= '<option value="'.$row->id.'">'.$row->child.'</option>';
		}
		return $output;
	}

	public function getEditGrandparent($id)
	{
		$sql = "SELECT * FROM hris_medical_type_of_reimbursment_grandparent WHERE id='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function getDeleteGrandparent($id)
	{
		$sql = "SELECT * FROM hris_medical_type_of_reimbursment_parent WHERE grandparent='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		if($res){
			$res;
			return false;
		}else{
			$sql = "DELETE FROM hris_medical_type_of_reimbursment_grandparent where id = '".$id."'";
			$query = $this->db->query($sql);
			return true;
		}
	}

	public function ubah_grandparent($id, $grandparent, $description)
	{
		$sql = "SELECT * FROM hris_medical_type_of_reimbursment_parent WHERE grandparent='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		// if($res){
		// 	$res;
		// 	return false;
		// }else{
			$sql = "UPDATE hris_medical_type_of_reimbursment_grandparent SET grandparent='$grandparent', description='$description' WHERE id='$id'";
			$query = $this->db->query($sql);
			return true;
		// }
	}

	public function getEditParent($id)
	{
		$sql = "SELECT * FROM hris_medical_type_of_reimbursment_parent WHERE id='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function getDeleteParent($id)
	{
		$sql = "SELECT * FROM hris_medical_type_of_reimbursment_child WHERE parent='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		if($res){
			$res;
			return false;
		}else{
			$sql = "DELETE FROM hris_medical_type_of_reimbursment_parent where id = '".$id."'";
			$query = $this->db->query($sql);
			return true;
		}
	}

	public function ubah_parent($id, $grandparent, $parent, $description)
	{
		$sql = "SELECT * FROM hris_medical_type_of_reimbursment_child WHERE parent='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		// dumper($sql);
		// if($res){
		// 	$res;
		// 	return false;
		// }else{
			$sql = "UPDATE hris_medical_type_of_reimbursment_parent SET grandparent='$grandparent', parent='$parent', description='$description' WHERE id='$id'";
			$query = $this->db->query($sql);
			return true;
		// }
	}

	public function getEditChild($id)
	{
		$sql = "SELECT * FROM hris_medical_type_of_reimbursment_child WHERE id='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res;
	}

	public function ubah_child($id, $start_date, $end_date, $child_grandparent, $child_parent, $child, $claim_percentage_child, $claim_value_child, $description_child)
	{
		$sql = "SELECT * FROM hris_medical_reimbursment_item WHERE tor_child='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		// dumper($sql);
		// if($res){
		// 	$res;
		// 	return false;
		// }else{
			$start_date = date('Y-m-d',strtotime($start_date));
			$end_date = date('Y-m-d',strtotime($end_date));
			
			$sql = "UPDATE hris_medical_type_of_reimbursment_child SET start_date ='$start_date', end_date ='$end_date', grandparent='$child_grandparent', parent='$child_parent', child='$child', claim_percentage='$claim_percentage_child', claim_value='$claim_value_child', description='$description_child' WHERE id='$id'";
			$query = $this->db->query($sql);
			return true;
		// }
	}

	public function getDeleteChild($id)
	{
		$sql = "SELECT * FROM hris_medical_reimbursment_item WHERE tor_child='$id'";
		$query = $this->db->query($sql);
		$res = $query->result();
		if($res){
			$res;
			return false;
		}else{
			$sql = "DELETE FROM hris_medical_type_of_reimbursment_child where id = '".$id."'";
			$query = $this->db->query($sql);
			return true;
		}
	}
	
	public function delete_couple($id)
	{
		$sql = "DELETE FROM hris_couple_employee where id = '".$id."'";
		$query = $this->db->query($sql);
		if($query){
			return true;
		}else{
			return false;
		}
	}

	public function getEmployeeToUsers(){

		// $sql = "with cteRowNumber as (
		// 	select nik, complete_name,
		// 		   row_number() over(partition by nik order by id_employee desc) as RowNum
		// 		from hris_employee
		// )
		// select nik, complete_name
		// 	from cteRowNumber
		// 	where RowNum = 1
		// 	";
		
		$sql = "SELECT nik, complete_name FROM hris_employee GROUP BY nik, complete_name";
		$query  = $this->db->query($sql);
		//dumper($query);
		$output = '<option value="">Select Employee</option>';
		foreach($query->result() as $row)
		{
			$complete_name = str_replace("||","'", decrypt($row->complete_name));
			$output .= '<option value="'.$row->complete_name.'">'.$complete_name.'</option>';
		}
		return $output;
	}

	public function getMaleEmployee(){
		$gender = encrypt('Male');
		$status1	= encrypt('Single');
		$status2	= encrypt('Div.');
		//$sql = "SELECT nik, complete_name FROM hris_employee WHERE gender = '$gender' AND ( marital_status = '$status1' OR marital_status = '$status2') GROUP BY nik, complete_name";

		////Start Perubahan Tanggal 16/10/2023 Rekon Luffi Dengan Tiopan//////
		$sql = "SELECT nik, complete_name FROM v_hris_employee_updated WHERE gender = '$gender' GROUP BY nik, complete_name";
		////End - Mengganti Table dari hris_employee to v_hris_employee_updated /////////////////

		$query  = $this->db->query($sql);
		$output = '<option value="">Select Employee</option>';
		foreach($query->result() as $row)
		{
			$complete_name = str_replace("||","'", decrypt($row->complete_name));
			$output .= '<option value="'.$row->complete_name.'">'.$complete_name.'</option>';
		}
		return $output;
	}

	public function getFemaleEmployee(){
		$gender = encrypt('Female');
		$status1	= encrypt('Single');
		$status2	= encrypt('Div.');
		//$sql = "SELECT nik, complete_name FROM hris_employee WHERE gender = '$gender' AND ( marital_status = '$status1' OR marital_status = '$status2') GROUP BY nik, complete_name";

		////Start Perubahan Tanggal 16/10/2023 Rekon Luffi Dengan Tiopan//////
		$sql = "SELECT nik, complete_name FROM v_hris_employee_updated WHERE gender = '$gender' GROUP BY nik, complete_name";
		////End - Mengganti Table dari hris_employee to v_hris_employee_updated /////////////////

		$query  = $this->db->query($sql);
		$output = '<option value="">Select Employee</option>';
		foreach($query->result() as $row)
		{
			$complete_name = str_replace("||","'", decrypt($row->complete_name));
			$output .= '<option value="'.$row->complete_name.'">'.$complete_name.'</option>';
		}
		return $output;
	}

	public function get_DataEmployeeToUsers($full_name_tambah_users)
	{
		$sql = "SELECT nik, email, phone_number FROM hris_employee WHERE complete_name ='$full_name_tambah_users' ORDER BY id_employee DESC LIMIT 1";
		$query = $this->db->query($sql);
		$res = $query->result_array();
		$nik = $res[0]['nik'];
		$email = decrypt($res[0]['email']);
		$phone_number = decrypt($res[0]['phone_number']);
		$res=array('nik'=>$nik,'email'=>$email,'phone_number'=>$phone_number);
		//dumper($res);
		return $res;
	}

	public function setUsers($nik, $complete_name, $email="", $phone_number="", $password, $role, $access, $verification){
		$complete_name 	= decrypt($complete_name);
		$password 		= encrypt($password);
		$formData = array(
					'employee_id' => $nik,
					'full_name' => $complete_name,
					'user_email' => $email,
					'phone_number' => $phone_number,
					'password' => $password,
					'user_role' => $role,
					'access_level' => $access,
					'verification_status' => $verification
				);
		
		$query = $this->db->insert("users", $formData);
		// $query = $this->db->insert_id();
		if($query){
			return true;
		}else{
			return false;
		}

	}


	public function get_DataEmployeeMale($full_name_male_add_couple)
	{
		$sql 	= "SELECT nik FROM hris_employee WHERE complete_name ='$full_name_male_add_couple'";
		$query 	= $this->db->query($sql);
		$res 	= $query->result_array();
		$nik 	= $res[0]['nik'];
		$res	= array('nik'=>$nik);
		//dumper($res);
		return $res;
	}
	
	public function get_DataEmployeeFemale($full_name_female_add_couple)
	{
		$sql 	= "SELECT nik FROM hris_employee WHERE complete_name ='$full_name_female_add_couple'";
		$query 	= $this->db->query($sql);
		$res 	= $query->result_array();
		$nik 	= $res[0]['nik'];
		$res	= array('nik'=>$nik);
		//dumper($res);
		return $res;
	}

	public function add_couple_employee($employee_id_male, $employee_id_female, $complete_name_male, $complete_name_female){

		$formData = array(
					'male_nik' => $employee_id_male,
					'female_nik' => $employee_id_female,
					'male_employee' => $complete_name_male,
					'female_employee' => $complete_name_female
				);
		
		$query = $this->db->insert("hris_couple_employee", $formData);
		// $query = $this->db->insert_id();
		if($query){
			return true;
		}else{
			return false;
		}

	}

	public function save_act_child($id_family, $st_act){

		$sql = "UPDATE hris_family_employee SET status_act='$st_act' WHERE id_family='$id_family'";
		$query = $this->db->query($sql);
		
		if($query){
			return true;
		}else{
			return false;
		}

	}

	
	public function tambah_efektifitas_kuitansi($hari, $start_date_tambah_efektif_kuitansi){


		$sqlt 	= "SELECT id, start_date FROM hris_efektifitas_kuitansi ORDER BY id DESC LIMIT 3";
		$queryt = $this->db->query($sqlt);
		$rest 	= $queryt->result_array();
		$start_datet 	= (!empty(($rest[0]['start_date']))) ? $rest[0]['start_date'] : '';
		$idt 	= (!empty(($rest[1]['id']))) ? $rest[1]['id'] : '';
			// dumper($idt);

		$tgl1 = strtotime($start_datet); 
		$tgl2 = strtotime($start_date_tambah_efektif_kuitansi);
		$jarak = $tgl2 - $tgl1;
		$jarak_hari = $jarak / 60 / 60 / 24;
		
		if($jarak_hari > 0){			
			$sql 	= "SELECT id FROM hris_efektifitas_kuitansi WHERE end_date ='9999-12-31' and active = '1' ORDER BY id DESC LIMIT 1";
			$query 	= $this->db->query($sql);
			$res 	= $query->result_array();
			if (!empty($res)){
				$id 	= $res[0]['id'];
				
				$update_end_date = date('Y-m-d', strtotime('-1 days', strtotime($start_date_tambah_efektif_kuitansi)));
				$sql = "UPDATE hris_efektifitas_kuitansi SET end_date = '$update_end_date' WHERE id='$id'";
				$query = $this->db->query($sql);

				$sqla = "UPDATE hris_efektifitas_kuitansi SET active = '0' WHERE id='$id'";
				$querya = $this->db->query($sqla);
			}

			$formData = array(
						'efektif_kuitansi' => $hari,
						'start_date' => date('Y-m-d',strtotime($start_date_tambah_efektif_kuitansi)),
						'end_date' => '9999-12-31',
						'active' => '1'	
					);
			
			$query = $this->db->insert("hris_efektifitas_kuitansi", $formData);
			// $query = $this->db->insert_id();
			
			if($query){
				$query = 'jarak_plus';
			}else{
				$query = false;
			}

		}else{
			$query = 'jarak_minus';
		}

		//dumper($query);
		if($query == 'jarak_minus'){
			return $query;
		}else if($query == 'jarak_plus'){
			return true;
		}else{
			return false;
		}

	}

	public function getEKuitansi()
	{
		$result = $this->db->get('hris_efektifitas_kuitansi')->result();
		return $result;
	}

	public function getDeleteEkuitansi($id)
	{
		$sqlt 	= "SELECT id FROM hris_efektifitas_kuitansi ORDER BY id DESC limit 3";
		$queryt = $this->db->query($sqlt);
		$rest 	= $queryt->result_array();
		$idt0 	= (!empty(($rest[0]['id']))) ? $rest[0]['id'] : '';
		$idt1 	= (!empty(($rest[1]['id']))) ? $rest[1]['id'] : '';
		$idt2 	= (!empty(($rest[2]['id']))) ? $rest[2]['id'] : '';

		if($id == $idt0){
			$sqla = "UPDATE hris_efektifitas_kuitansi SET active = '1' WHERE id='$idt2'";
			$querya = $this->db->query($sqla);	

			$sql = "UPDATE hris_efektifitas_kuitansi SET end_date = '9999-12-31' WHERE id='$idt1'";
			$query = $this->db->query($sql);

			$sqldel = "DELETE FROM hris_efektifitas_kuitansi where id = '".$id."'";
			$querydel = $this->db->query($sqldel);
		}else if($id == $idt1){
			$sqla = "UPDATE hris_efektifitas_kuitansi SET active = '1' WHERE id='$idt2'";
			$querya = $this->db->query($sqla);	

			$sql = "UPDATE hris_efektifitas_kuitansi SET end_date = '9999-12-31' WHERE id='$idt2'";
			$query = $this->db->query($sql);
			
			$sqldel = "DELETE FROM hris_efektifitas_kuitansi where id = '".$id."'";
			$querydel = $this->db->query($sqldel);
		}

		if($querydel){
			return true;
		}else{
			return false;
		}
	}

}