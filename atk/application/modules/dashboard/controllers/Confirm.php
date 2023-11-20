<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Confirm extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$now=date('Y-m-d H:i:s');
		$this->load->helper(array('form', 'url'));
		//$this->load->helper('tgl_indo');
           }

public function index()
    {
	echo"hai";
	}

public function accept($param){

	$sql = "UPDATE tbl_order SET status_accept='Y' WHERE dept_id='".$param."' AND status_accept='N' ";
	$exc = $this->db->query($sql);
		if($exc){
			redirect('dashboard/order/accept');
		}else{
			echo"gagal update";
			}
}

}




