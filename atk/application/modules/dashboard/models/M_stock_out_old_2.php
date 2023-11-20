<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_stock_out extends CI_Model {

    private $queryRes;
    private $queryStr;
    private $queryblk = array();

    public function __construct() {
        //Initialized FN
        $this->statusOrder = array('Request', 'Collect', 'Approved', 'Delivered', 'Reject');
        $this->load_ModSupport()->defineFileContent();
    }

    public function feed_closing($gid, $rid, $rd, $order_status=2, $item_status=0) {
        $sql = array();
        $sql['table'] = "tbl_order tb1";
        $sql['join'] = array("LEFT JOIN", "LEFT JOIN", "LEFT JOIN", "LEFT JOIN", "LEFT JOIN", "LEFT JOIN", "LEFT JOIN");
        $sql['jtable'] = array('tbl_stock_out_item tb2', 't_users tb3', 'tbl_barang tb4', 't_user_roles tb5', 't_roles tb6', 't_groups tb7', 'e_department tb8');
        $sql['jcols'] = array('tb1.order_id', 'tb1.order_by', 'tb2.barang_id', 'tb3.id', 'tb5.fk_rid', 'tb6.fk_gid', 'tb7.fk_dept_id');
        $sql['jvals'] = array('tb2.order_id', 'tb3.id', 'tb4.barang_id', 'tb5.fk_uid', 'tb6.rid', 'tb7.gid', 'tb8.id');
        $sql['field'] = array('tb2.order_id as cbox',
            'tb2.stotem_id',
            'tb1.order_id',
            'tb1.status',
            'tb1.status AS status_id',
            'tb1.order_date',
            'tb1.order_by',
            'tb3.c_fullname',
            'tb2.stock_out_qty',
            // '(tb2.stock_out_qty_gantung) AS stock_out_qty',
            'tb4.barang_id',
            'tb4.satuan',
            'tb4.nama_barang',
            'IFNULL(tb1.remark, tb2.remark) AS remark',
//            'CONCAT("<i class=\"fa fa-shopping-cart\"></i> ", tb3.c_fullname, " (OrderID:", tb1.order_id, ")", " Tanggal. ", tb1.order_date) AS order_group',
            'tb8.dept AS order_group'
        );
        // $sql['cond'] = " WHERE (tb1.order_date > '".$rd['firstDate']."' AND tb1.order_Date < '".$rd['endDate']."') AND tb5.fk_rid IN (".$rid.",".implode(",",$this->fetch_rid_child($gid)).") AND tb1.status=".$order_status." AND tb2.status=".$item_status;
        $sql['cond'] = " WHERE tb5.fk_rid IN (".$rid.",".implode(",",$this->fetch_rid_child($gid)).") AND tb1.status=".$order_status." AND tb2.status=".$item_status;
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        // echo $this->queryStr;
        $this->queryRes = $this->db->query($this->queryStr);
        return $this->drawCtrlAction($this->fetchBy());
    }

    public function feed_print($gid, $rid, $rd){
        return $this->feed_closing($gid, $rid, $rd, 3, 1);
    }
    
    public function feed_order($uInfo) {
        $gid = $uInfo->gid;
        $rid = $uInfo->fk_rid;
        $lvl = 3;
        $arr_rid = array();
        while($lvl>1) {
            $gidz =  $this->fetch_gid($gid);
            $lvl = $gidz->level;
            $gid = $gidz->parent_id;
            if($lvl>1) {$arr_rid[] = $gidz->rid;}
        }
        $sql = array();
        $sql['table'] = "tbl_order tb1";
        $sql['join'] = array("LEFT JOIN", "LEFT JOIN", "LEFT JOIN", "LEFT JOIN");
        $sql['jtable'] = array('tbl_stock_out_item tb2', 't_users tb3', 'tbl_barang tb4', 't_user_roles tb5');
        $sql['jcols'] = array('tb1.order_id', 'tb1.order_by', 'tb2.barang_id', 'tb3.id');
        $sql['jvals'] = array('tb2.order_id', 'tb3.id', 'tb4.barang_id', 'tb5.fk_uid');
        $sql['field'] = array('tb2.order_id as cbox',
            'tb1.order_id',
            'tb1.status',
            'tb1.status AS status_id',
            'tb1.order_date',
            'tb1.order_by',
            'tb3.c_fullname',
            'tb2.stock_out_qty',
            // 'stock_out_qty',
            'tb4.barang_id',
            'tb4.satuan',
            'IFNULL(tb1.remark, tb2.remark) AS remark',
            'CONCAT(tb3.c_fullname, " (OrderID:", tb1.order_id, ")", " Tanggal. ", tb1.order_date) AS order_group',
            'tb4.nama_barang AS order_item'
        );
        $arr_rid_child = $this->fetch_rid_child($uInfo->gid);
        // print_r($arr_rid_child);
        $all_rid = array_merge($arr_rid, $arr_rid_child);
        $all_rid_list = implode(",",$all_rid);
        $firstDate = new DateTime();
        $firstDate->modify('-90 days');
        $rd = $firstDate->format('Y-m-d')." 12:00:00";
        // $sql['cond'] = " WHERE tb1.order_date > '".$rd."' AND tb5.fk_rid IN (".$all_rid_list.")";
        $sql['cond'] = " WHERE tb5.fk_rid IN (".$all_rid_list.")";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        // echo $this->queryStr;
        $this->queryRes = $this->db->query($this->queryStr);
        return $this->drawCtrlAction($this->fetchBy());
    }

    private function fetch_gid($gid) {
            $sql['table'] = "t_groups tb1";
            $sql['field'] = array('tb1.gid', 'tb1.parent_id', 'tb1.level', 'tb2.rid');
            $sql['join'] = array("LEFT JOIN");
            $sql['jtable'] = array('t_roles tb2');
            $sql['jcols'] = array('tb1.gid');
            $sql['jvals'] = array('tb2.fk_gid');
            $sql['cond'] = "WHERE tb1.gid ='".$gid."'";
            $this->queryStr = $this->fn->sqlQuery($sql, "join");
            $this->queryRes = $this->db->query($this->queryStr);
            $rs = $this->fetchBy('fetch_row');
            return $rs;
    }

    private function fetch_rid_child($gid) {
        $ridCollect = array();
        $gidCollect = array();
        $parentGid  = array($gid);
        $x=1;
        while ($x > 0) {
            $sql['table'] = "t_groups tb1";
            $sql['field'] = array('tb1.gid', 'tb2.rid');
            $sql['join'] = array("LEFT JOIN");
            $sql['jtable'] = array('t_roles tb2');
            $sql['jcols'] = array('tb1.gid');
            $sql['jvals'] = array('tb2.fk_gid');
            $sql['cond'] = "WHERE tb1.parent_id IN (".implode(",",$parentGid).")";
            $this->queryStr = $this->fn->sqlQuery($sql, "join");
            // echo $this->queryStr;
            $this->queryRes = $this->db->query($this->queryStr);
            $rs = $this->fetchBy('fetch_rowset');
            $x=0;
            $parentGid = array();
            foreach($rs as $key=>$rsa) {
                if($rsa['rid']!="") {
                    $ridCollect[] = $rsa['rid'];
                    $gidCollect[] = $rsa['gid'];
                    $parentGid[]  = $rsa['gid'];
                }
                $x++;
            }
        }
        return $ridCollect;
    }

    public function collect_post($res) {
        $res['param']['status_id'] = 1;
        return $this->assign_param($res)->execute_query()->response_post();
    }

    public function approve_post($res) {
        $res['param']['status_id'] = 2;
        return $this->assign_param($res)->execute_query()->response_post();
    }

    public function deliver_post($res) {
        $res['param']['status_id'] = 3;
        return $this->assign_param($res)->execute_query()->response_post();
    }

    public function reject_post($res) {
        $res['param']['status_id'] = 4;
        return $this->assign_param($res)->execute_query()->response_post();
    }
    
    public function closing_post($res) {
        $res['param']['status_id'] = 3;
        return $this->assign_param_closing($res)->execute_query()->response_post();
    }

    public function achieve_post() {
        return $this->assign_param_achieve()->execute_query()->response_post();
    }

	private function assign_param_rej($x) {
        $updateType = "update";
        $sqlTemp = array();
        $sqlIn = array();
        $sqlIn['table'] = "tbl_order";
        $sqlIn['field'] = array('status');
        $sqlIn['fvals'] = array($x['param']['status_id']);
        if(isset($x['param']['remark'])) {
            array_push($sqlIn['field'], 'remark');
            array_push($sqlIn['fvals'], $x['param']['remark']);        
        }
        $sqlIn['cols'] = array('order_id');
        $sqlIn['vals'] = array($x['param']['order_id']);
        $sqlTemp[] = $this->fn->sqlQuery($sqlIn, $updateType);
	        if($x['param']['status_id']==3 || $x['param']['status_id']==4) {
            $statusItem = $x['param']['status_id']-2;
            $sqlItem = array();
            $sqlItem['table'] = "tbl_stock_out_item";
            $sqlItem['field'] = array('status');
            $sqlItem['fvals'] = array($statusItem);
	    $sqlItem['cols'] = array('order_id');
            $sqlItem['vals'] = array($x['param']['order_id']);  		  
         
            $sqlTemp[] = $this->fn->sqlQuery($sqlItem,$updateType);
        }
        $this->queryblk = $sqlTemp;
        return $this;
    }



	private function assign_param($x) {
        $updateType = "update";
        $sqlTemp = array();
        $sqlIn = array();
        $sqlIn['table'] = "tbl_order";
	
 	$sqlIn['field'] = array('status');
        $sqlIn['fvals'] = array($x['param']['status_id']);
	
        if(isset($x['param']['remark'])) {
            array_push($sqlIn['field'], 'remark');
            array_push($sqlIn['fvals'], $x['param']['remark']);        
        }
        $sqlIn['cols'] = array('order_id');
        $sqlIn['vals'] = array($x['param']['order_id']);
        $sqlTemp[] = $this->fn->sqlQuery($sqlIn, $updateType);
        if($x['param']['status_id']==3 || $x['param']['status_id']==4) {
            $statusItem = $x['param']['status_id']-2;
            $sqlItem = array();
            $sqlItem['table'] = "tbl_stock_out_item";
            $sqlItem['field'] = array('status');
            $sqlItem['fvals'] = array($statusItem);
	    $sqlItem['cols'] = array('order_id');
            $sqlItem['vals'] = array($x['param']['order_id']);  		  
         
            $sqlTemp[] = $this->fn->sqlQuery($sqlItem,$updateType);
        }
        $this->queryblk = $sqlTemp;
        return $this;
    }


    private function assign_param_achieve() {
        $sql = array();
        $sql['table'] = "tbl_stock_out_item";
        $sql['field'] = array('status');
        $sql['fvals'] = array('3');
        $sql['cols'] = array('status');
        $sql['vals'] = array('1');
        $sqlTemp[] = $this->fn->sqlQuery($sql, 'update');
        $this->queryblk = $sqlTemp;
        return $this;
    }
    
    private function assign_param_closing($res) {
        $sql = array();
        $sql['table'] = "tbl_stock_out_item";
        $sqr['table'] = $sql['table'];
        $sqs['table'] = "tbl_order";
        for($x = 0; $x<count($res['param']['cond']['vals']); $x++) {
            //UPDATE STOCK ITEM
            $sql['field'] = $res['param']['field']['cols'][$x];
            $sql['fvals'] = $res['param']['field']['vals'][$x];
            $sql['cols'] = $res['param']['cond']['cols'][$x];
            $sql['vals'] = $res['param']['cond']['vals'][$x];
            $sqlTemp[] = $this->fn->sqlQuery($sql, 'update');
            //QUERY ORDER ID
            $sqr['field'] = array('@orderId:=order_id');
            $sqr['cols'] = $sql['cols'];
            $sqr['vals'] = $sql['vals'];
            $sqlTemp[] = $this->fn->sqlQuery($sqr, 'query');
            //UPDATE ORDER ID
            $sqs['field'] = array('status');
            $sqs['fvals'] = array($res['param']['status_id']);
            $sqs['ctype'] = array("fkey");
            $sqs['cols'] = array('order_id');
            $sqs['vals'] = array('@orderId');
            $sqlTemp[] = $this->fn->sqlQuery($sqs, 'update');
        }
        $this->queryblk = $sqlTemp;
        // echo json_encode($this->queryblk);
        return $this;
    }

    private function response_post() {
        if ($this->queryRes) {
            return array('status' => 1, 'desc' => 'Update data berhasil', 'param' => null);
        } else {
            return array('status' => 1, 'desc' => 'Update data gagal', 'param' => null);
        }
    }

    private function execute_query() {
        $this->db->query("SET autocommit = 0;");
        $this->db->query("START TRANSACTION;");
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
        $x = 0;
        foreach ($rs as $key => $val) {
            $x++;
            $rsArr[$key] = $val;
            $overrideFirstCol = "<input type=\"checkbox\" class=\"cbox\" style=\"margin:0px 0px 0px 10px;\" name=\"cbox[]\" value=\"" . $val['cbox'] . "\" />";
            $rsArr[$key]['no'] = "";
            $rsArr[$key]['cbox'] = $overrideFirstCol;
            $rsArr[$key]['order_group'] = $rsArr[$key]['cbox']." ".$rsArr[$key]['order_group'];
            $rsArr[$key]['status'] = $this->statusOrder[$val['status']];
        }
        return $rsArr;
    }

}
