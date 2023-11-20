<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    private $data;
    private $content;
    private $type;
    private $usrInfo;
    private $menuPanel;
    private $feed;
    private $statusOrder;

    public function __construct() {
        parent::__construct();
        $this->statusOrder = array('Request', 'Collect', 'Approved', 'Delivered', 'Reject');
        $this->setDefaultType()->load_ModSupport()->checkSessionActive()->checkModuleRole()->generatePanel();
    }

    public function index() {
        echo "Assalamualaikum tiopan";
    }

    public function stock_in() {
        switch ($this->uri->segment(4)) {
            case "feed_barang":
                $raw = $this->report->feed_barang($this->input->get('q'));
                $hasil['results'] = $this->feed_barang_format($raw);
                echo json_encode($hasil);
                break;
            case "feed_karyawan":
                $raw = $this->report->feed_user($this->input->get('q'));
                $hasil['results'] = $this->feed_user_format($raw);
                echo json_encode($hasil);
                break;
            default:
                $this->content['feedBarang'] = base_url() . "dashboard/report/stock_in/feed_barang";
                $this->content['feedKaryawan'] = base_url() . "dashboard/report/stock_in/feed_karyawan";
                $this->getDataContent()->getContentTemplate('stock_in_report')->displayView();
        }
    }
    
    public function stock_barang() {
        if ($this->uri->segment(4) == "feed") {
            $this->load->model('m_stock_in', 'stock_in');
            $this->type = "json";
            $this->feed['data'] = $this->stock_in->feed_barang();
            $this->getContent()->displayView();
        } else {
            $this->content['feedUri'] = base_url() . "dashboard/stock_in/index/feed";
            $this->getDataPlugins()->getContentTemplate('stock_report')->displayView();
        }
    }

    public function order() {
        switch ($this->uri->segment(4)) {
            case "feed_uri":
                $this->feed = $this->report->feed_order($this->input->post());
                $this->type = "json";
                $this->feed_report_format()->getContentTemplate()->displayView();
                break;
            case "feed_barang":
                $raw = $this->report->feed_barang($this->input->get('q'));
                $hasil['results'] = $this->feed_barang_format($raw);
                echo json_encode($hasil);
                break;
            case "feed_karyawan":
                $raw = $this->report->feed_user($this->input->get('q'));
                $hasil['results'] = $this->feed_user_format($raw);
                echo json_encode($hasil);
                break;
            default:
                $this->content['feedBarang'] = base_url() . "dashboard/report/order/feed_barang";
                $this->content['feedKaryawan'] = base_url() . "dashboard/report/order/feed_karyawan";
                $this->content['feedUri'] = base_url() . "dashboard/report/order/feed_uri";
                $this->getDataContent()->getContentTemplate('order_report')->displayView();
        }
    }

    private function feed_report_format() {
        $x=0;
        foreach ($this->feed as $key=>$rs) {
             $this->feed[$x]['status'] =  $this->statusOrder[$rs['status']];
            $x++;
        }
        return $this;
    }

    private function feed_barang_format($data) {
        $dataRes = array();
        foreach ($data as $rs) {
            $dataRes[] = array('id' => $rs['cbox'], 'text' => $rs['nama_barang']);
        }
        return $dataRes;
    }

    private function feed_user_format($data) {
//        $dataRes = array();
        foreach ($data as $rs) {
            $dataRes[] = array('id' => $rs['user_id'], 'text' => $rs['nama_lengkap'] . " (NIK:" . $rs['nik'] . ")");
        }
        return $dataRes;
    }

    private function getDataContent() {
        $this->content['dataResult'] = array();
        $this->content['cssPlugins'] = "<!-- DataTables CSS -->";
        $this->content['cssPlugins'] .= "<link href=\"" . base_url() . "assets/vendor/datatables-plugins/dataTables.bootstrap.css\" rel=\"stylesheet\">";
        $this->content['cssPlugins'] .= "<!-- DataTables Responsive CSS -->";
        $this->content['cssPlugins'] .= "<link href=\"" . base_url() . "assets/vendor/datatables-responsive/dataTables.responsive.css\" rel=\"stylesheet\">";
        $this->content['cssPlugins'] .= "<link href=\"" . base_url() . "assets/vendor/select2-4.0.5/css/select2.min.css\" rel=\"stylesheet\">";
        $this->content['cssPlugins'] .= "<link href=\"" . base_url() . "assets/css/HoldOn.min.css\" rel=\"stylesheet\">";
        $this->content['javascriptPlugins'] = "<!-- DataTables JavaScript -->";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/js/jquery.dataTables.min.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables-plugins/dataTables.bootstrap.min.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables-responsive/dataTables.responsive.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-buttons/js/dataTables.buttons.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-buttons/js/buttons.print.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-buttons/js/buttons.html5.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-select/js/dataTables.select.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/select2-4.0.5/js/select2.min.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/js/HoldOn.min\"></script>";
     //   $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/js/report.js\"></script>";
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
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-buttons/js/dataTables.buttons.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-buttons/js/buttons.print.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-buttons/js/buttons.html5.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-select/js/dataTables.select.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/select2-4.0.5/js/select2.min.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/js/stock_in_report.js\"></script>";
        return $this;
    }

    private function getContentTemplate($name = "") {
        switch ($this->type) {
            case "json":
                $this->data['contentResult'] = json_encode($this->content);
                break;
            case "html":
                $this->content['menuPanel'] = $this->data['menuPanel'];
                $this->data['contentResult'] = $this->load->view('content/' . $name, $this->content, TRUE);
        }
        return $this;
    }

    private function displayView($template = "home") {
        switch ($this->type) {
            case "json":
                return $this->output->set_content_type('application/json')
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
        $this->load->model('m_report', 'report');
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

	private function prints(){
		  $this->load->view('modules/dashboars/views/blank');
	}
}
