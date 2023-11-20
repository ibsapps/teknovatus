<?php
class Login_model extends CI_Model    
{
    
    public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('general');
		$this->date = date('Y-m-d H:i:s');
		$this->year = date('Y');
	}

  function getUsers($email="", $password="") {
		$email = encrypt($email);
		//dumper($email);
        $sql = "SELECT * FROM hris_employee a
				LEFT JOIN users b ON a.nik = b.employee_id
				WHERE a.email like '$email' and b.password like '$password' and b.is_active <> 2
				ORDER BY a.id_employee DESC";
		$query = $this->db->query($sql);
		$res = $query->result();
		//dumper($res);
		return $res;
  }

  function checkEmail($email="") {
		$email = encrypt($email);

    $sql = "SELECT * FROM hris_employee a
			LEFT JOIN users b ON a.nik = b.employee_id
			WHERE a.email like '$email' and b.is_active <> 2
			ORDER BY a.id_employee DESC";
		$query = $this->db->query($sql);
		$res = $query->result();

		return $res;
  }

  function updatePassword($nik="") {
  	$random_password = mt_rand(1,1000000);
  	$new_pass = encrypt(strval($random_password));
  	$sql = "UPDATE users SET password='$new_pass' WHERE is_active = 1 and employee_id = '$nik'";
		$query = $this->db->query($sql);
		// $res = $query->result();

		if($query){
			return true;
		}else{
			return false;
		}
		
		return $query;
  }
    
}