<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_order extends CI_Model {

    private $queryRes;
    private $queryStr;
    private $statusOrder;
    private $queryblk = array();

    public function __construct() {
        //Initialized FN
        $this->statusOrder = array('Request', 'Collect', 'Approve', 'Delivered', 'Reject');
        $this->load_ModSupport()->defineFileContent();
    }
    
    public function feed_order($arrVal, $arrCols = array('tb3.id')) {
        $sql = array();
        $sql['table'] = "tbl_order tb1";
        $sql['join'] = array("LEFT JOIN", "LEFT JOIN", "LEFT JOIN");
        $sql['jtable'] = array('tbl_stock_out_item tb2', 't_users tb3', 'tbl_barang tb4');
        $sql['jcols'] = array('tb1.order_id', 'tb1.order_by', 'tb2.barang_id');
        $sql['jvals'] = array('tb2.order_id', 'tb3.id', 'tb4.barang_id');
        $sql['field'] = array('tb2.order_id as cbox',
          'tb1.order_id',
          'tb1.status',
          'tb1.status AS status_id',
          'tb1.order_date',
          'tb1.order_by',
          'tb3.c_fullname',
          'tb3.c_email',
          // 'tb2.stock_out_qty',
          'tb2.stock_out_qty_gantung',
          'tb4.barang_id',
          'tb4.satuan',
          'tb2.remark',
          'CONCAT("<i class=\"fa fa-shopping-cart\"></i> ", "OrderID:", tb1.order_id, " Tanggal. ", tb1.order_date) AS order_group',
          'tb4.nama_barang AS order_item'
          
//        'GROUP_CONCAT(tb4.nama_barang SEPARATOR "|") AS order_item'
      );
//        $sql['group'] = "tb1.order_id";
        $sql['cols'] = $arrCols;
        $sql['vals'] = $arrVal;



        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        echo json_encode($this->queryStr);
        $this->queryRes = $this->db->query($this->queryStr);
      
        echo json_encode($query);
        
        return $this->drawCtrlAction($this->fetchBy());
    }
    
    
    public function save_post($post) {
        if(isset($post['param']['order_id'])) {
            $this->assign_param_update($post)->assign_param_activity($post);
        } else {
            $this->assign_param_input($post)->assign_param_activity($post);
        }
        $this->execute_query();
        return $this->queryRes;
    }
    
    public function delete_post($post) {
        $this->assign_param_delete($post)->execute_query();
    }
    
    private function assign_param_delete($post) {
        $updateType = "delete";
        $sqlTemp = array();
        $sqlIn = array();
        $sqlIn['table'] = "tbl_order";
        $sqlIn['cols'] = array('order_id');
        $sqlIn['vals'] = array($post['param']['order_id']);
        $sql = array();
        $sql['table'] = "tbl_stock_out_item";
        $sql['cols'] = array("order_id");
        $sql['vals'] = array($post['param']['order_id']);
        $sqlTemp[] = $this->fn->sqlQuery($sql, $updateType);
        $sqlTemp[] = $this->fn->sqlQuery($sqlIn, $updateType);
        $this->queryblk = $sqlTemp;
        return $this;
    }

    private function assign_param_update($post) {
        $updateType = "insert";
        $sqlTemp = array();
        $sqlIn = array();
        $sqlIn['table'] = "tbl_stock_out_item";
        $sqlIn['cols'] = array('order_id');
        $sqlIn['vals'] = array($post['param']['order_id']);
        $loop = count($post['param']['id_barang']);
        for ($x = 0; $x < $loop; $x++) {
            $sql = array();
            $sql['table'] = "tbl_stock_out_item";
            $sql['cols'] = array("order_id", "barang_id", "stock_out_qty", "remark");
            $sql['vals'] = array($post['param']['order_id'], $post['param']['id_barang'][$x], $post['param']['qty_barang'][$x], $post['param']['rem_barang'][$x]);
            $sqlTemp[$x] = $this->fn->sqlQuery($sql, $updateType);
        }
        $sqlTemp[] = $this->fn->sqlQuery($sqlIn, 'delete');
        $this->queryblk = array_reverse($sqlTemp);
        return $this;
    }
    
    private function assign_param_input($post) {

       $user = $this->session->userdata('userid');
       $dept = "";
       $sql = "SELECT t_groups.fk_dept_id FROM t_users,t_user_roles,t_roles,t_groups WHERE t_users.id = t_user_roles.fk_uid AND t_user_roles.fk_rid = t_roles.rid AND t_roles.fk_gid = t_groups.gid AND t_users.id='".$user."' ";
       $exc = $this->db->query($sql)->result();
       foreach($exc AS $rs){
         $dept = $rs->fk_dept_id;
     }

     $updateType = "insert";
     $sqlTemp = array();
     $sqlIn = array();
     $sqlIn['table'] = "tbl_order";
     $sqlIn['cols'] = array('order_id', 'order_by','dept_id','order_date', 'status');
     $sqlIn['ctype'] = array('fkey', '', '', '', '', '');
     $sqlIn['vals'] = array('@order_id', $post['param']['request_by'], $dept,date("Y-m-d H:i:s"), 0);
     $loop = count($post['param']['id_barang']);
     for ($x = 0; $x < $loop; $x++) {
        $sql = array();
        $sql['table'] = "tbl_stock_out_item";
        $sql['ctype'] = array("fkey", "", "", "", "");
        $sql['cols'] = array("order_id", "barang_id","stock_out_qty","stock_out_qty_gantung","hsoq", "remark");
        $sql['vals'] = array('@order_id', $post['param']['id_barang'][$x],$post['param']['qty_barang'][$x],$post['param']['qty_barang'][$x],$post['param']['qty_barang'][$x], $post['param']['rem_barang'][$x]);
        $sqlTemp[$x] = $this->fn->sqlQuery($sql, $updateType);
    }
    $sqlTemp[] = $this->fn->sqlQuery($sqlIn, $updateType);
    $this->queryblk = array_reverse($sqlTemp);
    return $this;
}

private function assign_param_activity($post) {
//        print_r($this->session->userdata('username'));exit;
    $sqlAct['table'] = "tbl_order";
    return $this;
}

private function execute_query() {
    $this->db->query("SET autocommit = 0;");
    $this->db->query("START TRANSACTION;");
    $this->db->query("SELECT IFNULL(@order_id:=MAX(order_id)+1, @order_id:=1) FROM tbl_order;");
    foreach ($this->queryblk as $qryStr) {
        $this->db->query($qryStr);
    }
    if ($this->db->query("COMMIT")) {
        $this->queryRes = true;
    } else {
        $this->db->query("ROLLBACK");
        $this->queryRes = false;
    }
    $this->db->query("SET autocommit = 1");
    return $this;
}

private function defineFileContent() {
    $startSegment = 1;
    foreach ($this->uri->segment_array() as $key => $sg) {
        if ($startSegment <= $key) {
            $this->fn->fileContent[] = $sg;
        }
    }
    return $this;
}

private function load_ModSupport() {
    $this->load->library('query_generator', NULL, 'fn');
    return $this;
}

private function fetchBy($fetchMode = "fetch_rowset") {
    switch ($fetchMode) {
        case "fetch_rowset":
        return $this->queryRes->result_array();
        case "fetch_row":
        return $this->queryRes->row();
        default :
        return $this->queryRes->result_array();
    }
}

private function drawCtrlAction($rs) {
    $rsArr = array();
    foreach ($rs as $key => $val) {
        $rsArr[$key] = $val;
        $overrideFirstCol = "<input type=\"checkbox\" class=\"cbox\" name=\"cbox[]\" value=\"" . $val['cbox'] . "\" />";
        $rsArr[$key]['cbox'] = $overrideFirstCol;
        $rsArr[$key]['status'] = $this->statusOrder[$val['status']];
    }
    return $rsArr;
}

}
