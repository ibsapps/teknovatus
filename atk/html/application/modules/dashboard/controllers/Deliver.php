<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deliver extends CI_Controller {
	function __construct(){
    parent::__construct();
    $this->load->library('datatables');
    $this->load->model('m_dashboard');
     $this->load->model('m_scf');
          $now=date('Y-m-d H:i:s');
	$this->load->helper(array('form', 'url'));
	$this->load->helper('tgl_indo');
    $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
  }

 public function index()
    {
         
        $session    = $this->session->userdata('login');
               $data = array(
                         'subtitle' => 'DELIVER', 
              'title' => 'DELIVER', 
              // 'site' => $this->m_site->getsite()->result_array(), 
              'contentResult' => '../modules/dashboard/views/content/penerimaan.php', 
        );
        $this->load->view('../modules/dashboard/views/home', $data);
    }

}