<?php
defined("BASEPATH") OR exit("No direct script access allowed");
/**
 * User_access.php
 */
class User_access extends CI_Model
{
  public function cek_user($data) {
		$query = $this->db->get_where('user', $data);
		return $query;
	}
}
