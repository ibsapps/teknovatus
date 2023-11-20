<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    private $result;

    private $erp;

    public function __construct()
    {
        parent::__construct();
        $this->load_ModSupport();
    }

    public function index()
    {
        redirect('ibs');
    }

    public function login()
    {
        $this->assignObjectPost()
            ->filterLoginPost()
            ->filterUser()
            ->filterPassword()
            ->assignSession()
            ->displayView();
    }

    public function forget()
    {
        $this->assignForgetPost()
            ->filterForgetPost()
            ->filterUser()
            ->resetUserPass()
            ->sendEmail()
            ->displayView();
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url('ibs'));
    }

    private function assignObjectPost()
    {
        $this->postData = array(
            'usrId' => $this->input->post('username'),
            'pasId' => $this->input->post('password')
        );
        return $this;
    }

    private function assignForgetPost()
    {
        $this->postData = array(
            'usrId' => $this->input->post('email')
        );
        return $this;
    }

    private function filterLoginPost()
    {
        $filter = array(
            "rules" => array(
                "usrId" => "/^[a-zA-Z0-9\.\-\_@]{4,64}$/i", // Username
                "pasId" => "/^[a-zA-Z0-9\.\-\_]{4,16}$/i" // Password
            ),
            "err" => array(
                "usrId" => "Masukkan username dengan benar", // Username
                "pasId" => "Masukkan password dengan benar" // Password
            )
        );
        $this->erp->erpFilterCheck($this->postData, $filter);
        $this->result = $this->erp->erpGetResult();
        return $this;
    }

    private function filterForgetPost()
    {
        $filter = array(
            "rules" => array(
                "usrId" => "/^[a-zA-Z0-9\.\-\_@]{4,64}$/i"
            ),
            "err" => array(
                "usrId" => "Masukkan alamat email dengan benar"
            )
        );
        $this->erp->erpFilterCheck($this->postData, $filter);
        $this->result = $this->erp->erpGetResult();
        return $this;
    }

    private function filterUser()
    {
        /* RULES */
        /*
         * 1. User with passed filterLogin
         * 2. User with published corporate
         * 3. User with roles defined by each function or pages
         */
        if ($this->result['status'] == 1) {
            $varLogin = array(
                $this->result['param']['usrId'],
                '1',
                '1'
            );
            $varColum = array(
                'tb2.c_username',
                'tb4.status',
                'tb2.c_status'
            );
            $res = $this->corp->query_user($varLogin, $varColum, 'fetch_row');
            $arrUser = $this->corp->arrUser($res);
            if (! $arrUser) {
                $this->result = array(
                    'status' => 0,
                    'desc' => 'Username/Password salah',
                    'param' => $this->result['param']
                );
            } else {
                $data = array(
                    'post' => $this->result['param'],
                    'user' => $arrUser
                );
                $this->result = array(
                    'status' => 1,
                    'desc' => 'Selamat datang, ' . $arrUser['fullname'],
                    'param' => $data
                );
            }
        }
        return $this;
    }

    private function filterPassword()
    {
        if ($this->result['status'] == 1) {
            $usrPassword = $this->result['param']['post']['pasId'];
            $hash = $this->result['param']['user']['enctpass'];
            if (password_verify($usrPassword, $hash)) {
                $rawSession = array(
                    'userid' => $this->result['param']['user']['userid'],
                    'username' => $this->result['param']['user']['username'],
                    'fullname' => $this->result['param']['user']['fullname']
                );
                $this->result = array(
                    'status' => 1,
                    'desc' => 'Selamat datang, ' . $this->result['param']['user']['fullname'],
                    'param' => $rawSession
                );
            } else {
                $this->result = array(
                    'status' => 0,
                    'desc' => 'Username/Password salah',
                    'param' => ''
                );
            }
        }
        return $this;
    }

    private function assignSession()
    {
        if ($this->result['status'] == 1) {
            $this->session->set_userdata('logged_in', TRUE);
            $this->session->set_userdata('userid', $this->result['param']['userid']);
            $this->session->set_userdata('username', $this->result['param']['username']);
            $this->session->set_userdata('fullname', $this->result['param']['fullname']);
        }
        return $this;
    }

    private function resetUserPass()
    {
        $this->result['desc'] = 'Silahkan Periksa Email Anda';
        if ($this->result['status'] == 1) {
            $rand = rand();
            $this->result['param']['new'] = $rand;
            $this->corp->update_post($this->result);
        } else {
            $rs = $this->corp->feed_karyawan($this->result['param']['usrId']);
            if(is_object($rs)) {
                $this->result['param']['nik'] = $rs->nik;
                $this->corp->save_post($this->result);
                $this->result['status'] = 1;
            } else {
                $this->result['status'] = 0;
                $this->result['desc'] = "Email tidak terdaftar, mohon hubungi administrator sistem";
            }
        }
        return $this;
    }
    
    private function sendEmail() {
        if ($this->result['status'] == 1) {
            
            $from_email = "atk.apps@ibsmulti.com";
            $to_email = $this->result['param']['user']['email'];
            $this->load->library('email');
            
            $config = array();
            $config['protocol']     = 'smtp';
            $config['smtp_crypto']  = 'tls';
            $config['smtp_host']    = 'mail.ibsmulti.com';
            $config['smtp_user']    = 'atk.apps@ibsmulti.com';
            $config['smtp_pass']    = '12345@2019Atk.Apps';
            $config['smtp_port']    = '587';
            $config['charset']      = "utf-8";
            $config['mailtype']     = "html";
            $config['newline']      = "\r\n";
            
            $this->email->initialize($config);
            
            $this->email->from($from_email, 'ATK Notification System');
            $this->email->to($to_email);
            $this->email->subject('Reset password untuk '.$this->result['param']['user']['fullname']);
            $message = $this->load->view('mail_template',$this->result['param'],TRUE);
            $this->email->message($message);
            $this->email->send();
            $this->email->print_debugger(array('headers'));
        }
        return $this;
    }

    private function displayView()
    {
        if ($this->result['status'] == 1) {
            $data = array(
                'redirect_uri' => site_url('dashboard'),
                'result' => $this->result
            );
        } else {
            $data = array(
                'redirect_uri' => site_url('ibs'),
                'result' => $this->result
            );
        }
        $this->load->view('notification', $data);
    }

    private function load_ModSupport()
    {
        $this->load->model('m_corporate', 'corp');
        $this->erp = $this->common_erp;
    }
}
