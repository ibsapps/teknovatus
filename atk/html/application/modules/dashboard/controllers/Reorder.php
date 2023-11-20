<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reorder extends CI_Controller {

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

public function proses(){
$qty =  $this->input->post('qty');

$idbarang = $this->input->post('idbarang');
$n = count($idbarang);
$dt = date("Y-m-d");
$trx = "IBSW-TRX-".$dt;
for($i=0;$i<$n;$i++){


	$sql ="INSERT INTO reorder(barangid,qty)VALUES('".$idbarang[$i]."','".$qty[$i]."')";
	$this->db->query($sql);

}
	


}

}




