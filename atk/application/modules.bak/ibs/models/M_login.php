<?php

	class M_login extends CI_Model {

		function login($username, $password) {
			$this->db->where('username', $username);
	        $this->db->where('password', $password);
	        $query = $this->db->get('user');

	        return $query->row_array();
		}

		function select_user_username($username) {
			$this->db->select('*');
			$this->db->from('user');
			$this->db->where('username', $username);

			return $this->db->get();
		}

		
	}

?>