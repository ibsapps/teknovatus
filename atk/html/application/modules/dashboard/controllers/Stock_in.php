<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_in extends CI_Controller {

    private $data;
    private $feed;
    private $content;
    private $type;
    private $usrInfo;
    private $menuPanel;
    private $result;
 
    public function __construct() {
        parent::__construct();
        $this->setDefaultType()->load_ModSupport()->checkSessionActive()->checkModuleRole()->generatePanel();
            $this->load->model('M_monthly_report');
    }

    public function index() {
        if ($this->uri->segment(4) == "feed") {
            $this->load->model('m_stock_in', 'stock_in');
            $this->type = "json";
            $this->feed['data'] = $this->stock_in->feed_barang();
            $this->getContent()->displayView();
        } else {
            $this->content['feedUri'] = base_url() . "dashboard/stock_in/index/feed";
            $this->getDataPlugins()->getContent('stock_in')->displayView();
        }
    }

 public function form() {
        if ($this->uri->segment(4) == "feed") {
            $this->load->model('m_master', 'master');
            $this->type = "json";
            $raw_barang = $this->master->search_barang($this->input->get('q'));
            $hasil['results'] = $this->feed_format($raw_barang);
            echo json_encode($hasil);
        } else {
            $this->content['dataResult'] = array();
            $this->content['feedUri'] = base_url() . "dashboard/stock_in/form/feed";
            $this->filterInput()->executePost()->getDataPlugins()->getContent('stock_in_form')->displayView();
        }
    }

    private function feed_format($data) {
        $dataRes = array();
        foreach($data as $rs) {
            $dataRes[] = array('id'=>$rs['barang_id'], 'text'=>$rs['nama_barang']);
        }
        return $dataRes;
    }

    private function filterInput() {
        /*WARNING NO FILTER VALIDATION*/
        $time = time();
        $this->load->model('m_stock_in', 'stock_in');
        if ($this->input->post('processForm') == "1") {
            //Do The Filter
            if($this->input->post('no_po')=="") {
                $post_res['no_po'] = "PO_".$time;
                $post_res['date_po'] = date("Y-m-d");
            }
            if($this->input->post('delivery_no')=="") {
                $post_res['delivery_no'] = "D_".$time;
                $post_res['date_in'] = date("Y-m-d");
            }
            $post_res['id_barang'] = $this->input->post('id_barang');
            $post_res['qty_barang'] = $this->input->post('qty_barang');
            $post_res['rem_barang'] = $this->input->post('rem_barang');
            $post_res['request_by'] = $this->usrInfo->id;
            $this->result = array('status'=>1, 'desc'=>'Continue', 'param'=>$post_res);
        }
        return $this;
    }

    private function executePost() {
        if ($this->input->post('processForm') == "1") {
            $this->content['query_result'] = $this->stock_in->save_post($this->result);
        }
        return $this;
    }

    private function getDataPlugins() {
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
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/js/stock_in.js\"></script>";
        return $this;
    }

    private function getContent($tpl = "") {
        switch ($this->type) {
            case "json":
                $this->data['contentResult'] = json_encode($this->content);
                break;
            case "html":
                $this->content['result'] = $this->result;
                $this->content['menuPanel'] = $this->data['menuPanel'];
                $this->data['contentResult'] = $this->load->view('content/' . $tpl, $this->content, TRUE);
        }
        return $this;
    }

    private function generatePanel() {
        $this->menuPanel = $this->panel->menu_panel($this->usrInfo->fk_rid);
        $this->data['menuPanel'] = $this->menuPanel;
        return $this;
    }

    private function displayView() {
        switch ($this->type) {
            case "json":
                return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200) // Return status
                                ->set_output(json_encode($this->feed));
            case "html":
                $this->load->view('home', $this->data);
        }
    }

    private function load_ModSupport() {
        $this->erp = $this->common_erp;
        $this->load->model('m_panel', 'panel');
        $this->load->helper('date');
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
        return $this;
    }

}
