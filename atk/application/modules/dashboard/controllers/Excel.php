<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Excel extends CI_Controller {
  
  public function __construct(){
    parent::__construct();
    
    $this->load->model('M_monthly_report');
   
  }

function index(){
	echo"print";
}

function print(){
	echo"print";
}

}