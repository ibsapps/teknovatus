<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_out extends CI_Controller {

    private $data;
    private $feed;
    private $content;
    private $type;
    private $usrInfo;
    private $menuPanel;
    private $result;
    private $rDate;

    public function __construct() {
        parent::__construct();
        $this->setDefaultType()->load_ModSupport()->checkSessionActive()->checkModuleRole()->generatePanel();
    }

    public function index() {
        $cnt = "stock_out";
        switch ($this->uri->segment(4)) {
            case "closing":
                $this->type = "json";
                $this->getDate();
                $this->feed['data'] = $this->stockout->feed_closing($this->usrInfo->gid, $this->usrInfo->fk_rid, $this->rDate);
                $this->addFormClosing()->getContent()->displayView();
                break;

            case "print":
                $this->type = "json";
                $this->getDate();
                $this->feed['data'] = $this->stockout->feed_print($this->usrInfo->gid, $this->usrInfo->fk_rid, $this->rDate);
                $this->getContent()->displayView();
                break;

 	   case "feed":
                $this->type = "json";
                //$this->feed['data'] = $this->stockout->feed_order($this->usrInfo->gid, $this->usrInfo->fk_rid);
                $this->feed['data'] = $this->stockout->feed_order($this->usrInfo);
                $this->addButtonMenu()->getContent()->displayView();
                break;
	   case "penerimaan":
                $this->type = "json";
                $this->getDate();
                $this->feed['data'] = $this->stockout->feed_permintaan($this->usrInfo->gid, $this->usrInfo->fk_rid, $this->rDate);
                $this->getContent()->displayView();
                break;



            default:
                $this->content['feedUri'] = base_url() . "dashboard/stock_out/index/feed";
                $this->getDataPlugins($cnt)->getContent($cnt)->displayView();
        }
    }

  public function delivered() {

	$data = array(
	'deliver' => $this->stockout->deliver_post()->result_array(),
	);
	$this->load->view('../modules/dashboard/views/content/penerimaan', $data);

        }



 public function penerimaan() {
       $cnt = "penerimaan";
        $this->content['feedUri'] = base_url() . "dashboard/stock_out/penerimaan";
        $this->getDate()->getDataPlugins($cnt)->getContent($cnt)->displayView();



 }


  public function buffer_stock() {
  	  $cnt = "buffer_stock";
        if ($this->uri->segment(4) == "feed") {
            $this->load->model('m_stock_in', 'stock_in');
            $this->type = "json";
            $this->feed['data'] = $this->stock_in->feed_barang();
            $this->getContent()->displayView();
        } else {
            $this->content['feedUri'] = base_url() . "dashboard/stock_out/buffer_stock";
            $this->getDataPlugins($cnt)->getContent($cnt)->displayView();
        }
    }

    public function update_stock() {
  	  $cnt = "update_stock";
        if ($this->uri->segment(4) == "feed") {
            $this->load->model('m_stock_in', 'stock_in');
            $this->type = "json";
            $this->feed['data'] = $this->stock_in->feed_barang();
            $this->getContent()->displayView();
        } else {
            $this->content['feedUri'] = base_url() . "dashboard/stock_out/update_stock";
            $this->getDataPlugins($cnt)->getContent($cnt)->displayView();
        }
    }


    public function closing() {
        if (null!==$this->input->post('stotem_id')) {
            $this->filterPostClosing()->closingPost();
            if($this->content['query_result']['status']==1) {
                redirect('dashboard/stock_out/print');
            } else {
                redirect('dashboard/stock_out/closing');
            }
        } else {
            $cnt = "closing";
            $this->content['feedUri'] = base_url() . "dashboard/stock_out/index/closing";
            $this->getDate()->getDataPlugins($cnt)->getContent($cnt)->displayView();
        }
    }

    public function print() {
        $cnt = "print";
        if($this->uri->segment(4)=='arsipkan') {
            $this->achievePost();
            redirect('dashboard/stock_out/print');
        }
        $this->content['feedUri'] = base_url() . "dashboard/stock_out/index/print";
        $this->getDate()->getDataPlugins($cnt)->getContent($cnt)->displayView();
    }

	    public function collect() {
        $this->filterInput()->collectPost()->setJsonResponse()->displayView();
    }

    public function approve() {
        $this->filterInput()->approvePost()->setJsonResponse()->displayView();
    }

    public function deliver() {
        $this->filterInput()->deliverPost()->setJsonResponse()->sendEmail()->displayView();
    }

    public function reject() {
        $this->filterInput()->rejectPost();
        // ->setJsonResponse()->sendEmail()->displayView();
        // $this->filterInput();
    
    }
    
    public function reject_head() {
        $this->reject();
    }
    
    private function getDate() {
        $endDate = new DateTime();
        $endDate->modify('friday');
        $firstDate = clone $endDate;
        $firstDate->modify('-8 days');
        $this->rDate['firstDate'] = $firstDate->format('Y-m-d')." 12:00:00";
        $this->rDate['endDate'] = $endDate->format('Y-m-d')." 11:59:59";
        $this->content['rDate'] = $endDate->format('l\, d-m-Y')." 11:59:59";
        return $this;
    }

    private function collectPost() {
        $this->content['query_result'] = $this->stockout->collect_post($this->result);
        return $this;
    }

    private function approvePost() {
        $this->content['query_result'] = $this->stockout->approve_post($this->result);
        return $this;
    }

    private function deliverPost() {
        $this->content['query_result'] = $this->stockout->deliver_post($this->result);
        return $this;
    }

    private function achievePost() {
        $this->content['query_result'] = $this->stockout->achieve_post();
        return $this;
    }

    private function rejectPost() {


        $test =  $this->result;
        $order_id_final = $test['param']['order_id']; 
        $query = $this->db->query("UPDATE tbl_stock_out_item SET stock_out_qty = 0 WHERE order_id =".$order_id_final. ";");
        $this->content['query_result'] = $this->stockout->reject_post($this->result);
        return $this;
    }
    
    private function closingPost() {
        $this->content['query_result'] = $this->stockout->closing_post($this->result);
        return $this;
    }

    private function setJsonResponse() {
        $this->type = "json";
        $this->feed['data'] = $this->content['query_result'];
        return $this;
    }

    private function filterPostClosing() {
        $thisPost = $this->input->post();
        // print_r($thisPost);
        $field    = array();
        $cond     = array();
	for($x=0;$x<count($thisPost['stotem_id']);$x++) {
	$stotenId = $thisPost['stotem_id'][$x];
	if($stotenId == 2){
            $field['cols'][]    = array('stock_out_qty', 'remark', 'status');
            $field['vals'][]    = array($x, $thisPost['rem'][$x], $thisPost['act_todo_'.$stotenId][0]);
            $cond['cols'][]   = array('stotem_id');
            $cond['vals'][]   = array($thisPost['stotem_id'][$x]);
		$sql = "UPDATE tbl_order SET status='4' WHERE order_id ='".$stotenId."'";
		$exc = $this->db->query($sql);

	}else{
	$field['cols'][]    = array('stock_out_qty', 'remark', 'status');
            $field['vals'][]    = array($thisPost['qty'][$x], $thisPost['rem'][$x], $thisPost['act_todo_'.$stotenId][0]);
            $cond['cols'][]   = array('stotem_id');
            $cond['vals'][]   = array($thisPost['stotem_id'][$x]);
	}
           
        }
	
        $this->result = array('status'=>3, 'desc'=>'Closing', 'param'=>array('field'=>$field, 'cond'=>$cond, 'exc'=>$exc));
        // print_r($this->result);exit;
        return $this;
    }
    
    private function filterInput() {
        $post_res = array();
        /* WARNING NO FILTER VALIDATION */
        if ($this->input->post('order_id') != "") {
            $post_res['order_id'] = $this->input->post('order_id');
        }
        if ($this->input->post('remark') != "") {
            $post_res['remark'] = $this->input->post('remark');
        }
        $this->result = array('status' => 1, 'desc' => 'Continue', 'param' => $post_res);
        return $this;
        // $test= json_encode($this->result);
        // echo $test;
    }

    private function addFormClosing() {
//         $arrColor = array('collect' => 'btn-info', 'approve' => 'btn-success', 'deliver' => 'btn-warning', 'reject' => 'btn-danger');
        foreach ($this->feed['data'] as $key => $feed) {
            $this->feed['data'][$key]['stock_out_qty_gantung'] = $this->addFormClosing_qty($feed['stock_out_qty_gantung']);
            $this->feed['data'][$key]['satuan'] = $this->addFormClosing_id($feed['stotem_id'], $feed['satuan']);
            $this->feed['data'][$key]['remark'] = $this->addFormClosing_rem($feed['remark']);
	    
        }
        return $this;
    }

    private function addFormClosing_id($stotem_id, $satuan) {
        $stotem_form = "<input type=\"hidden\" name=\"stotem_id[]\" value=\"" . $stotem_id . "\" /> 
		<div class=\"btn-group btn-group-toggle\" data-toggle=\"buttons\">
		<label class=\"btn btn-xs btn-primary\">
			<input type=\"radio\" name=\"act_todo_".$stotem_id."[]\" value=\"1\" class=\"list_".$stotem_id."\" autocomplete=\"off\"> 
		Deliver</label>
		<label class=\"btn btn-xs btn-warning\">
		<input type=\"radio\" value=\"2\" name=\"act_todo_".$stotem_id."[]\" class=\"list_".$stotem_id."\" autocomplete=\"off\"> 
		Reject</label>
		</div>";
        return $stotem_form;
    }

    private function addFormClosing_qty($qty_form_value) {
        $qty_form = "<input type=\"text\" name=\"qty[]\" value=\"" . $qty_form_value . "\" style=\"width:40px\" />";
        return $qty_form;
    }

    private function addFormClosing_rem($rem_form_value) {
        $rem_form = "<input type=\"text\" name=\"rem[]\" value=\"" . $rem_form_value . "\" style=\"width:100%\" />";
        return $rem_form;
    }

    private function addButtonMenu() {
        $arrColor = array('collect' => 'btn-info', 'approve' => 'btn-success', 'deliver' => 'btn-warning', 'reject' => 'btn-danger', 'reject_head' => 'btn-danger');
        foreach ($this->feed['data'] as $key => $feed) {
            $this->btnAction = "";
            foreach ($this->menuPanel as $menu) {
                foreach ($menu as $mn) {
                    if ($mn['pos'] == 4 && $mn['ctrlName'] == $this->uri->segment(2)) {
                        if (($mn['nodeName'] == "collect" && $feed['status_id'] == '0') || ($mn['nodeName'] == "approve" && $feed['status_id'] == '1') || ($mn['nodeName'] == "reject_head" && $feed['status_id'] == '1') || ($mn['nodeName'] == "deliver" && $feed['status_id'] == '2') || ($mn['nodeName'] == "reject" && $feed['status_id'] == '2')) {
                            $this->btnAction .= "<button type=\"button\" xid data-ctrl=\"" . $mn['ctrlName'] . "\" data-node=\"" . $mn['nodeName'] . "\" class=\"btn" . $mn['nodeName'] . " pullRightBtn btn btn-xs " . $arrColor[$mn['nodeName']] . " pull-right\">" . $mn['pName'] . "</button>";
                        }
                    }
                }
            }
            $this->feed['data'][$key]['order_group'] .= str_replace("xid", "id=\"" . $feed['order_id'] . "\"", $this->btnAction);
        }
        return $this;
    }

    private function getDataPlugins($cnt) {
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
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-select/js/dataTables.select.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-buttons/js/dataTables.buttons.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-buttons/js/buttons.print.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-buttons/js/buttons.html5.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/select2-4.0.5/js/select2.min.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/js/HoldOn.min.js\"></script>";
	 $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/js/" . $cnt . ".js\"></script>";
             
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

    private function generatePanel() {
        $this->menuPanel = $this->panel->menu_panel($this->usrInfo->fk_rid);
        $this->data['menuPanel'] = $this->menuPanel;
        return $this;
    }

    private function load_ModSupport() {
        $this->erp = $this->common_erp;
        $this->load->model('m_panel', 'panel');
        $this->load->model('m_stock_out', 'stockout');
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
    
    private function sendEmail() {
        $this->load->model('m_order', 'order');
        $vals = array($this->result['param']['order_id']);
        $cols = array('tb1.order_id');
        $this->result['param']['order_data'] = $this->order->feed_order($vals, $cols);
        $this->result['param']['title'] = "Notifikasi Pesanan ATK (orderID: ".$this->result['param']['order_id'].")";
        $this->result['param']['user']['name'] = $this->result['param']['order_data'][0]['c_fullname'];
        $this->result['param']['user']['email'] = $this->result['param']['order_data'][0]['c_email'];
        //$this->result['param']['user']['email'] = "gilang@kresnadi.web.id";
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
            $this->email->subject($this->result['param']['title']);
            $message = $this->load->view('mail_template',$this->result['param'],TRUE);
            $this->email->message($message);
            $this->email->send();
            $this->email->print_debugger(array('headers'));
        }
        return $this;
    }

	function reorder(){
			echo"hai";
		}	

}
