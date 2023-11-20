<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    private $data;
    private $content;
    private $type;
    private $usrInfo;
    private $menuPanel;

    public function __construct() {
        parent::__construct();
        $this->setDefaultType()->load_ModSupport()->checkSessionActive()->checkModuleRole()->generatePanel();
    }

    public function index() {
        switch($this->uri->segment(3)) {
            case "kategori":
                $this->getKategoriList()->getContent('kategori_list')->displayView();
                break;
            case "search":
                $this->getSearchResult()->getContent('barang_detail')->displayView('blank');
                break;
            default:
                $this->getDataDashboard()->getContent('dashboard')->displayView();
        }
    }

    private function getSearchResult() {
        $this->type = "json";
        $searchBy = $this->input->post('search');
        $search = $this->input->post('q');
        if ($this->uri->segment(4) != "") {
            $this->type = "html";
            $this->content['orderUri'] = base_url() . "dashboard/order/form/add_basket";
            $searchBy = $this->uri->segment(4);
            $search = $this->uri->segment(5);
        }
        $searchResult = $this->stock_in->search_barang($search, $searchBy);
        $this->feed['data'] = $searchResult;
        $this->content['searchResult'] = $searchResult;
        return $this;
    }
    
    private function getKategoriList() {
        $idKat = $this->uri->segment(4);
        if(preg_match('/^\d{1,4}$/i', $idKat) ? true : false) {
            $this->content['kategori_name'] = ucwords(str_replace("-"," ",$this->uri->segment(5)));
            $this->content['kategori_list'] = $this->stock_in->search_barang($idKat, 'kategori');
        } else {
            redirect('dashboard');
        }
        $this->content['cssPlugins'] = "<!-- AddOn Custom CSS -->";
        $this->content['javascriptPlugins'] = "<!-- AddOn Custom JavaScript -->";
        return $this;
    }

    private function getDataDashboard() {
        $this->content['kategori_list'] = $this->stock_in->feed_kategori();
        $this->content['cssPlugins'] = "<!-- AddOn Custom CSS --><link rel=\"stylesheet\" href=\"https://use.fontawesome.com/releases/v5.7.1/css/all.css\" integrity=\"sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr\" crossorigin=\"anonymous\">";
        $this->content['javascriptPlugins'] = "<!-- AddOn Custom JavaScript -->";
        return $this;
    }

    private function getContent($name = "") {
        switch ($this->type) {
            case "json":
                $this->data['contentResult'] = json_encode($this->content);
                break;
            case "html":
                $this->data['contentResult'] = $this->load->view('content/' . $name, $this->content, TRUE);
        }
        return $this;
    }

    private function displayView($template="home") {
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
        $this->load->model('m_stock_in', 'stock_in');
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
