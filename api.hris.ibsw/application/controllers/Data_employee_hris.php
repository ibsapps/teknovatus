<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . "libraries/Format.php";
require APPPATH . "libraries/RestController.php";


use chriskacerguis\RestServer\RestController;


class Data_employee_hris extends RestController
{

  public function __construct(){
    parent::__construct();
    $this->load->model('Mhris_ibsw', 'mhid');
  }

  public function get_data_get(){
    $nik = $this->get('NIK');
    if($nik === null){
      $employee = $this->mhid->get_employee();
    }else{
      $employee = $this->mhid->get_employee($nik);
    }
      
    if($employee){
      $this->response([
        'status' => TRUE,
        'data' => $employee
      ], RestController::HTTP_OK);
    }else{
      $this->response([
        'status' => FALSE,
        'message' => 'NIK Not Found'
      ], RestController::HTTP_NOT_FOUND);
    }
  }
}
