<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

    private $data;
    private $feed;
    private $content;
    private $type;
    private $usrInfo;
    private $menuPanel;
    private $result;
    private $btnAction;

    public function __construct() {
        parent::__construct();
        $this->content['dataResult'] = array();
        $this->content['feedUri'] = base_url() . "dashboard/order/form/feed";
        $this->content['feedCheck'] = base_url() . "dashboard/order/form/stock_check";
        $this->setDefaultType()->load_ModSupport()->checkSessionActive()->checkModuleRole()->generatePanel();
    }

    public function index() {
        $this->load->model('m_order', 'order');
        switch ($this->uri->segment(4)) {
            case "feed":
                $this->type = "json";
                $this->feed['data'] = $this->order->feed_order(array($this->usrInfo->id));
                $this->addButtonMenu()->getContent()->displayView();
                break;
            default:
                $this->content['feedUri'] = base_url() . "dashboard/order/index/feed";
                $this->content['updateOrder'] = base_url() . "dashboard/order/update";
                $this->getDataPlugins()->getContent('order')->displayView();
        }
    }

    public function form() {
        $this->load->model('m_stock_in', 'stock');
        $this->content['processUri'] = base_url() . "dashboard/order/form/proceed";
        switch ($this->uri->segment(4)) {
            case "proceed":
                $this->filterInput()->executePost()->redirectOrder();
                break;
            case "feed":
                $this->type = "json";
                $raw_barang = $this->stock->feed_barang($this->input->get('q'));
                $hasil['results'] = $this->feed_format($raw_barang);
                echo json_encode($hasil);
                break;
            case "stock_check":
                $stock_info = $this->stock->check_stock($this->uri->segment(5), 'id');
                $res = array('status'=>$stock_info['status'], 'stock'=>$stock_info['jumlah']);
                echo json_encode($res);
                break;
            case "add_basket":
                $this->getInputPost()->getDataPlugins()->getContent('order_form')->displayView();
                break;
            default :
                $this->getDataPlugins()->getContent('order_form')->displayView();
        }
    }

    public function input() {
        redirect('dashboard/order/form');
    }

    public function update() {
        $this->content['processUri'] = base_url() . "dashboard/order/update/proceed";
        switch ($this->uri->segment(4)) {
            case "proceed":
                $this->filterInput()->executePost()->redirectOrder();
                break;
            default:
                $this->getUpdatePost()->getDataPlugins()->getContent('order_form')->displayView();
        }
    }


	public function accept(){

	 if ($this->uri->segment(4) == "feed") {
            $this->load->model('m_master', 'master');
            $this->type = "json";
            $this->feed['data'] = $this->master->feed_karyawan();
            $this->getContent('accept')->displayView();
        } else {
            $this->content['feedUri'] = base_url() . "dashboard/data_master/karyawan/feed";
            $this->getDataPlugins('accept')->getContent('accept')->displayView();
        }


	}


    public function delete() {
        $this->filterInput()->deletePost()->redirectOrder();
    }

    private function filterInput() {
		

        /* WARNING NO FILTER VALIDATION */
        $post_res['id_barang'] = $this->input->post('id_barang');
        $post_res['qty_barang'] = $this->input->post('qty_barang');
        $post_res['rem_barang'] = $this->input->post('rem_barang');
        $post_res['request_by'] = $this->usrInfo->id;
	

        if ($this->input->post('order_id') != "") {
            $post_res['order_id'] = $this->input->post('order_id');
        }
        $this->result = array('status' => 1, 'desc' => 'Continue', 'param' => $post_res);
        return $this;
    }

    private function executePost() {
        $this->load->model('m_order', 'order');
        $this->content['query_result'] = $this->order->save_post($this->result);
        return $this;
    }

    private function deletePost() {
        $this->load->model('m_order', 'order');
        $this->content['query_result'] = $this->order->delete_post($this->result);
        return $this;
    }

    private function redirectOrder() {
        // redirect('dashboard/order');
        redirect('dashboard');
    }

    private function getInputPost() {
        if (preg_match('/^\d{1,4}$/i', $this->input->post('barang_id')) ? true : false) {
            $res = $this->stock->search_barang($this->input->post('barang_id'), 'id');
            foreach ($res as $rs) {
                $inPost = array('barang_id' => $rs['barang_id'],
                    'nama_barang' => $rs['nama_barang'],
                    'jumlah' => $this->input->post('jumlah_barang'),
                    'remark' => $this->input->post('remark'));
                if($rs['stock_ready']>$this->input->post('jumlah_barang')) {
                    $this->content['dataResult'][] = $inPost;
                } else {
                    $this->content['query_result'] = false;
                }
            }
        } else {
            redirect('dashboard');
        }
        return $this;
    }

    private function getUpdatePost() {
        $this->load->model('m_order', 'order');
        $cols = array('tb1.order_id', 'tb3.id');
        $vals = array($this->input->post('order_id'), $this->usrInfo->id);
        $order = $this->order->feed_order($vals, $cols);
        foreach ($order as $or) {
            $inPost = array(
		'barang_id' => $or['barang_id'],
                'nama_barang' => $or['order_item'],
                'jumlah' => $or['stock_out_qty'],
                'remark' => $or['remark']);
            $this->content['dataResult'][] = $inPost;
        }
        $this->content['order_id'] = $this->input->post('order_id');
        return $this;
    }

    private function feed_format($data) {
        $dataRes = array();
        foreach ($data as $rs) {
            if($rs['stock_ready']>0) {
                $dataRes[] = array('id' => $rs['barang_id'], 'text' => $rs['nama_barang']);
            }
        }
        return $dataRes;
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
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/js/order.js\"></script>";
        return $this;
    }

    private function addButtonMenu() {
        $this->btnAction = "";
        $arrColor = array('insert' => 'btn-info', 'update' => 'btn-warning', 'delete' => 'btn-danger', 'reject' => 'btn-danger');
        foreach ($this->menuPanel as $menu) {
            foreach ($menu as $mn) {
                if ($mn['pos'] == 4 && $mn['ctrlName'] == $this->uri->segment(2)) {
                    $this->btnAction .= "<button type=\"button\" xid data-ctrl=\"" . $mn['ctrlName'] . "\" data-node=\"" . $mn['nodeName'] . "\" class=\"btn" . $mn['nodeName'] . " pullRightBtn btn btn-xs " . $arrColor[$mn['nodeName']] . " pull-right\">" . $mn['pName'] . "</button>";
                }
            }
        }
        foreach ($this->feed['data'] as $key => $feed) {
            if ($feed['status_id'] > 0 && $this->usrInfo->level > 2) {
                //Restricted Area for Staff
            } else {
                $this->feed['data'][$key]['order_group'] .= str_replace("xid", "id=\"" . $feed['order_id'] . "\"", $this->btnAction);
            }
        }
        return $this;
    }

    private function getContent($name = "") {
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

    public function confirm(){

	echo"tes";


	} 


}
