<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ibs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load_ModSupport()->checkSessionActive();
    }

    public function index() {
        $data = array('login_uri' => site_url('ibs/auth/login'), 'forget_uri' => site_url('ibs/forget'));
        $this->load->view('login', $data);
    }
    
    public function forget() {
        $data = array('forget_uri' => site_url('ibs/auth/forget'), 'login_uri'=> site_url('ibs'));
        $this->load->view('forget', $data);
    }

    private function load_ModSupport() {
        $this->erp = $this->common_erp;
        return $this;
    }
    
    private function checkSessionActive() {
        if(!$this->erp->erpSessionCheck($this->session)) {
            return $this;
        } else {
            redirect('dashboard');
        }
        return $this;
    }
}
