<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    private $data;
    private $content;
    private $type;
    private $usrInfo;
    private $menuPanel;
    private $result;

    public function __construct() {
        parent::__construct();
        $this->setDefaultType()->load_ModSupport()->checkSessionActive()->checkModuleRole()->generatePanel();
    }

    public function index() {
        if ($this->uri->segment(4) == "feed") {
            $this->type = "json";
            $this->feed['data'] = $this->user->feed_user($this->usrInfo->level);
            $this->getContent('user')->displayView();
        } else {
            $this->content['feedUri'] = base_url() . "dashboard/user/index/feed";
            $this->getDataPlugins('user')->getContent('user')->displayView();
        }
    }

    public function form() {
        switch ($this->uri->segment(4)) {
            case "batch":
                echo "Assalamualaikum Gee";
                exit;
                break;
            case "feed":
                $hasil = array();
                $this->type = "json";
                $raw_karyawan = $this->user->feed_karyawan($this->input->get('q'));
                $hasil['results'] = $this->feed_format($raw_karyawan);
                echo json_encode($hasil);
                break;
            case "get":
                $this->type = "json";
                $cols = array($this->uri->segment(5));
                $vals = array($this->input->get('nik'));
                if($cols[0]!='' && $vals[0]!='') {
                    $raw_karyawan = $this->user->get_karyawan($cols, $vals);
                    echo json_encode($raw_karyawan);
                }
                // $raw_karyawan = $this->user->feed_karyawan();
                // $x=0;
                // $postUser = array();
                // foreach($raw_karyawan as $rw) {
                //     $usrNem = explode('@', $rw['email']);
                //     if($rw['username']=="") {
                //         $postUser['param']['nik'][$x]=$rw['nik'];
                //         $postUser['param']['roles'][$x]=0;
                //         $x++;
                //     }
                // }
                // $postUser['desc']="user_add";
                // $this->user->save_post($postUser);
                break;
            default:
                $this->content['feedUri'] = base_url() . "dashboard/user/form/feed";
                $this->content['getUri'] = base_url() . "dashboard/user/form/get/nik";
                $this->filterInput()->executePost()->getDataPlugins('user')->getContent('user_form')->displayView();
        }
    }

    public function input() {
        $this->form();
    }
    
    public function roles() {
        $rid = $this->usrInfo->fk_rid;
        if($this->uri->segment(5)!="") {$rid = $this->uri->segment(5);}
        if($this->uri->segment(6)!="") {$r2pid = $this->uri->segment(6);}
        switch ($this->uri->segment(4)) {
            case "add_roles":
                $this->type = "json";
                $this->feed['data'] = $this->user->panel_add_role($this->input->post());
                $this->getContent()->displayView();
                break;
            case "feed_panel_add":
                $this->type = "json";
                $this->feed['data'] = $this->user->add_r2panel($rid, $r2pid);
                $this->getContent()->displayView();
                break;
            case "feed_r2panel_rem":
                $this->type = "json";
                $this->feed['data'] = $this->user->rem_r2panel($rid);
                $this->getContent()->displayView();
                break;
            case "feed_panel":
                $this->type = "json";
                $this->feed['data'] = $this->user->panel_re_role($rid);
                $this->getContent()->displayView();
                break;
            case "feed_r2panel":
                $this->type = "json";
                $this->feed['data'] = $this->user->panel_by_role($rid);
                $this->getContent()->displayView();
                break;
            default:
                $rid=$this->usrInfo->fk_rid;
                $this->content['getUri'] = base_url() . "dashboard/user/roles/add_roles";
                $this->content['feedUriPanel'] = base_url() . "dashboard/user/roles/feed_panel";
                $this->content['feedUriR2Panel'] = base_url() . "dashboard/user/roles/feed_r2panel";
                $this->content['group_role_list'] = $this->roleList();
                $this->content['group_list'] = $this->groupList();
                $this->getDataPlugins('user')->getContent('user_roles')->displayView();
        }
    }

    public function update() {
        $this->filterInput()->executePost()->getDataPlugins('user')->getContent('user_info')->displayView();
    }
    
    public function profile() {
        $this->content['role_list'] = $this->user->role_list($this->usrInfo->level);
        $this->content['dataResult'] = $this->user->batch_query($this->usrInfo->id);
        $this->update();
    }

    public function delete() {
        $this->filterCbox()->deletePost();
        redirect('dashboard/user/' . $this->input->post('node'));
    }
    
    public function set_active() {
        $this->filterCbox()->setStatus(1);
        redirect('dashboard/user/' . $this->input->post('node'));
    }
    
    public function set_unactive() {
        $this->filterCbox()->setStatus(0);
        redirect('dashboard/user/' . $this->input->post('node'));
    }
    
    private function groupList() {
        $arrList = array();
        foreach($this->user->group_list($this->usrInfo->gid) as $arr) {
            for($x=1; $x<=4;$x++) {
                if($arr['lv'.$x]=="Webmaster" || $arr['lv'.$x]=="") {$arr['lv'.$x]="";} else {if($x>1) {$arr['lv'.$x]=$arr['lv'.$x]."->";} else {$arr['lv'.$x]=$arr['lv'.$x];}}
            }
            $arrList[] = array('gid'=>$arr['gid'], 'group_name'=>$arr['lv4'].$arr['lv3'].$arr['lv2'].$arr['lv1']);
        }
        return $arrList;
    }
    
    private function roleList() {
        $arrList = array();
        foreach($this->user->group_role_list($this->usrInfo->level) as $arr) {
            for($x=1; $x<=4;$x++) {
                if($arr['lv'.$x]=="Webmaster" || $arr['lv'.$x]=="") {$arr['lv'.$x]="";} else {if($x>1) {$arr['lv'.$x]=$arr['lv'.$x]."->";} else {$arr['lv'.$x]=$arr['lv'.$x];}}
            }
            $arrList[] = array('rid'=>$arr['rid'], 'role_name'=>$arr['lv4'].$arr['lv3'].$arr['lv2'].$arr['lv1']." (".$arr['role_name'].")");
        }
        return $arrList;
    }

    private function executePost() {
        if ($this->input->post('processForm') == "1" && $this->result['status'] == "1") {
            $this->content['query_result'] = $this->user->save_post($this->result);
            if ($this->result['desc'] == "user_info") {
                redirect('dashboard/user/index', 'refresh');
            }
        }
        return $this;
    }

    private function feed_format($data) {
        $dataRes = array();
        foreach ($data as $rs) {
            if ($rs['username'] == "") {
                $dataRes[] = array('id' => $rs['nik'], 'text' => $rs['nama_lengkap'] . " (NIK:" . $rs['nik'] . ")");
            }
        }
        return $dataRes;
    }

    private function filterInput() {
        $post_res = array();
        if ($this->input->post('processForm') == "1") {
            if ($this->input->post('node') == "user_info") {
                $post_res['id'] = $this->input->post('cbox');
                $post_res['c_mobileno'] = $this->input->post('mobile_number');
                $post_res['rid'] = $this->input->post('user_roles');
                $post_res['c_fullname'] = $this->input->post('full_name');
                $post_res['r_password'] = $this->input->post('passwd');
                $post_res['c_password'] = $this->input->post('c_passwd');
                $post_res['c_status'] = $this->input->post('status');
            } else {
                $post_res['nik'] = $this->input->post('nik');
                $post_res['roles'] = $this->input->post('roles');
                $post_res['request_by'] = $this->usrInfo->id;
            }
            $this->result = array('status' => 1, 'desc' => $this->input->post('node'), 'param' => $post_res);
        } else if ($this->input->post('node') == "index" && $this->input->post('cbox') !== null) {
            $this->content['role_list'] = $this->user->role_list($this->usrInfo->level);
            $this->content['dataResult'] = $this->user->batch_query(implode(",", $this->input->post('cbox')));
        }
        
        return $this;
    }

    private function filterCbox() {
        $cbox = $this->input->post('cbox');
        foreach ($cbox as $val) {
            if (preg_match('/^\d{1,6}$/i', $val) ? true : false) {
                $this->result = array('status' => 1, 'desc' => 'Continue', 'param' => $cbox);
            } else {
                $this->result = array('status' => 0, 'desc' => 'Data Tidak Valid', 'param' => '');
                break;
            }
        }
        return $this;
    }

    private function deletePost() {
        $this->content['query_result'] = $this->user->batch_delete($this->input->post());
        return $this;
    }
    
    private function setStatus($m) {
        $this->content['query_result'] = $this->user->batch_status($this->input->post(), $m);
        return $this;
    }

    private function getDataPlugins($dataMasterUntuk) {
        $this->content['cssPlugins'] = "<!-- DataTables CSS -->";
        $this->content['cssPlugins'] .= "<link href=\"" . base_url() . "assets/vendor/datatables-plugins/dataTables.bootstrap.css\" rel=\"stylesheet\">";
        $this->content['cssPlugins'] .= "<!-- DataTables Responsive CSS -->";
        $this->content['cssPlugins'] .= "<link href=\"" . base_url() . "assets/vendor/datatables-responsive/dataTables.responsive.css\" rel=\"stylesheet\">";
        $this->content['cssPlugins'] .= "<link href=\"" . base_url() . "assets/vendor/select2-4.0.5/css/select2.min.css\" rel=\"stylesheet\">";
        $this->content['javascriptPlugins'] = "<!-- DataTables JavaScript -->";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/js/jquery.dataTables.min.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables-plugins/dataTables.bootstrap.min.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables-responsive/dataTables.responsive.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-select/js/dataTables.select.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/select2-4.0.5/js/select2.min.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/js/multiselect.min.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/js/" . $dataMasterUntuk . ".js\"></script>";
        return $this;
    }

    private function getContent($name = "") {
        switch ($this->type) {
            case "json":
                $this->data['contentResult'] = json_encode($this->content);
                break;
            case "html":
                $this->content['result'] = $this->result;
                $this->content['menuPanel'] = $this->data['menuPanel'];
                $this->content['role_list'] = $this->user->role_list($this->usrInfo->level);
                $this->data['contentResult'] = $this->load->view('content/' . $name, $this->content, TRUE);
        }
        return $this;
    }

    private function displayView($template = "home") {
        switch ($this->type) {
            case "json":
                return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200) // Return status
                                ->set_output(json_encode($this->feed));
            case "html":
                $this->load->view($template, $this->data);
        }
        return $this;
    }

    private function generatePanel() {
        $this->menuPanel = $this->panel->menu_panel($this->usrInfo->fk_rid);
        $this->data['menuPanel'] = $this->menuPanel;
        return $this;
    }

    private function load_ModSupport() {
        $this->erp = $this->common_erp;
        $this->load->model('m_panel', 'panel');
        $this->load->model('m_user', 'user');
        return $this;
    }

    private function checkSessionActive() {
        if (!$this->erp->erpSessionCheck($this->session)) {
            redirect('ibs');
        } else {
            $this->usrInfo = $this->panel->query_user($this->session->userdata('username'));
            $this->data['usrInfo'] = $this->usrInfo;
        }
        return $this;
    }

    private function checkModuleRole() {
        if (isset($this->usrInfo)) {
            return $this;
        } else {
            redirect('dashboard');
        }
        return $this;
    }

    private function setDefaultType($default = 'html') {
        $this->type = $default;
        $this->content['dataResult'] = array();        
        return $this;
    }

}
