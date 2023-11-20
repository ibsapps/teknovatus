<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . "libraries/Format.php";
require APPPATH . "libraries/RestController.php";


use chriskacerguis\RestServer\RestController;


class Data_user_hris extends RestController
{

    public function __construct(){
        parent::__construct();
        $this->load->model('Mhris_ibsw', 'mhid');
    }

    public function users_get(){
      $users = $this->mhid->get_users();
      
      if($users){
        $this->response([
          'status' => TRUE,
          'data' => $users
        ], RestController::HTTP_OK);
      }else{
        $this->response([
          'status' => FALSE,
          'message' => 'User Not Found'
        ], RestController::HTTP_NOT_FOUND);
      }
    }

    public function user_login_get(){
      $email = $this->get('EMAIL');
      $nik = $this->get('NIK');

      if($email === null || $nik === null){
        $employee = $this->mhid->get_user_login();
      }else{
        $employee = $this->mhid->get_user_login($email, $nik);
      }
        
      if($employee){
        $this->response([
          'status' => TRUE,
          'data' => $employee
        ], RestController::HTTP_OK);
      }else{
        $this->response([
          'status' => FALSE,
          // 'data' => $employee
          'message' => 'NIK  Not Found'
        ], RestController::HTTP_OK);
        // ], RestController::HTTP_NOT_FOUND);
      }
    }
}
