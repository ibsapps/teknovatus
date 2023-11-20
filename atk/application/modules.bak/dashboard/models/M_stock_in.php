<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_stock_in extends CI_Model {

    private $queryRes;
    private $queryStr;
    private $queryblk = array();

    public function __construct() {
        //Initialized FN
        $this->load_ModSupport()->defineFileContent();
    }
    
    public function feed_kategori() {
        $sql_rujit = "SELECT 
                        kat1.id, 
                        kat1.kategori, 
                        kat1.icon, 
                        IFNULL(COUNT(DISTINCT br1.barang_id), 0) AS jml_item, 
                        IFNULL(SUM(in1.received_qty), 0)+br1.qty AS tot_in, 
                        IFNULL(out2.tot_out,0) AS tot_out,
                        IFNULL((IFNULL(SUM(in1.received_qty),0)+br1.qty-IFNULL(out2.tot_out,0)),0) AS stock_ready 
                        FROM tbl_barang br1 
                        LEFT JOIN tbl_kategori kat1 ON br1.id_kategori=kat1.id 
                        LEFT JOIN tbl_stock_in_item in1 ON br1.barang_id=in1.barang_id 
                        LEFT JOIN tbl_stock_in in2 ON in1.stockin_id=in2.stockin_id 
                        LEFT JOIN (
                        SELECT barang_id, SUM(so1.stock_out_qty) AS tot_out FROM tbl_stock_out_item so1
                        LEFT JOIN tbl_order or1 ON or1.order_id=so1.order_id 
                        LEFT JOIN tbl_stock_out out1 ON or1.order_id=out1.order_id 
                        GROUP BY so1.barang_id 
                        ) out2 ON out2.barang_id=br1.barang_id
                        GROUP BY kat1.id";
        $this->queryRes = $this->db->query($sql_rujit);
        return $this->fetchBy();
    }

    public function feed_barang($q="", $searchBy="nama") {
        $addSearch = "";
        switch($searchBy) {
            case "nama":
                $addCols = ",br1.file, br1.barang_id ";
                $addSearch = " WHERE br1.nama_barang LIKE '%".$q."%'";
                break;
            case "kategori":
                $addCols = ",br1.file, br1.barang_id ";
                $addSearch = " WHERE br1.id_kategori = '".$q."'";
                break;
            case "id":
                $addCols = ",br1.file, br1.barang_id ";
                $addSearch = " WHERE br1.barang_id = '".$q."'";
                break;
        }
        
        $sql_rujit = "SELECT 
                        br1.nama_barang, 
                        IFNULL(SUM(in1.received_qty), 0)+br1.qty AS tot_in, 
                        IFNULL(out2.tot_out,0) AS tot_out,
                        IFNULL((IFNULL(SUM(in1.received_qty),0)+br1.qty-IFNULL(out2.tot_out,0)),0) AS stock_ready, 
                        br1.satuan".$addCols."
                        FROM tbl_barang br1 
                        LEFT JOIN tbl_stock_in_item in1 ON br1.barang_id=in1.barang_id 
                        LEFT JOIN tbl_stock_in in2 ON in1.stockin_id=in2.stockin_id 
                        LEFT JOIN (
                        SELECT barang_id, SUM(so1.stock_out_qty) AS tot_out FROM tbl_stock_out_item so1
                        LEFT JOIN tbl_order or1 ON or1.order_id=so1.order_id 
                        LEFT JOIN tbl_stock_out out1 ON or1.order_id=out1.order_id 
                        WHERE or1.status='3' 
                        GROUP BY so1.barang_id 
                        ) out2 ON out2.barang_id=br1.barang_id".$addSearch."
                        GROUP BY br1.barang_id";
        $this->queryRes = $this->db->query($sql_rujit);
        $rs = $this->fetchBy();
        return $rs;
    }

    public function stock_barang($q="", $searchBy="nama") {
        $addSearch = "";
        switch($searchBy) {
            case "nama":
                $addCols = ",br1.file, br1.barang_id ";
                $addSearch = " WHERE br1.nama_barang LIKE '%".$q."%'";
                break;
            case "kategori":
                $addCols = ",br1.file, br1.barang_id ";
                $addSearch = " WHERE br1.id_kategori = '".$q."'";
                break;
            case "id":
                $addCols = ",br1.file, br1.barang_id ";
                $addSearch = " WHERE br1.barang_id = '".$q."'";
                break;
        }
        
        $sql_rujit = "SELECT 
                        br1.nama_barang, 
                        IFNULL(SUM(in1.received_qty), 0)+br1.qty AS tot_in, 
                        IFNULL(out2.tot_out,0) AS tot_out,
                        IFNULL((IFNULL(SUM(in1.received_qty),0)+br1.qty-IFNULL(out2.tot_out,0)),0) AS stock_ready, 
                        br1.satuan".$addCols."
                        FROM tbl_barang br1 
                        LEFT JOIN tbl_stock_in_item in1 ON br1.barang_id=in1.barang_id 
                        LEFT JOIN tbl_stock_in in2 ON in1.stockin_id=in2.stockin_id 
                        LEFT JOIN (
                        SELECT barang_id, SUM(so1.stock_out_qty) AS tot_out FROM tbl_stock_out_item so1
                        LEFT JOIN tbl_order or1 ON or1.order_id=so1.order_id 
                        LEFT JOIN tbl_stock_out out1 ON or1.order_id=out1.order_id 
                        WHERE or1.status<3 
                        GROUP BY so1.barang_id 
                        ) out2 ON out2.barang_id=br1.barang_id".$addSearch."
                        GROUP BY br1.barang_id";
        $this->queryRes = $this->db->query($sql_rujit);
        $rs = $this->fetchBy();
        return $rs;
    }
    
    public function search_barang($q="", $searchBy="") {
        $res = $this->feed_barang($q, $searchBy);
        foreach($res as $key=>$rs) {
           if(!file_exists(FCPATH."assets/images/upload/barang/".$rs['file'])) {
               $res[$key]['file'] = "No_Image_Available.jpg"; 
           }
        }
        return $res;
    }
    
    public function check_stock($id, $searchBy) {
        $res = $this->stock_barang($id, $searchBy);
        $stock = array();
        foreach($res as $rs) {
            if($rs['stock_ready']>0) {
                $stock['status'] = true;
                $stock['jumlah'] = $rs['stock_ready'];
            } else {
                $stock['status'] = false;
                $stock['jumlah'] = 0;
            }
        }
        return $stock;
    }

    public function save_post($post) {
        $this->assign_param_barang($post)->execute_query();
        return $this->queryRes;
    }
    
    private function assign_param_barang($post) {
        $updateType = "insert";
        $sqlTemp = array();
        $sqlIn = array();
        $sqlIn['table'] = "tbl_stock_in";
        $sqlIn['cols'] = array('stockin_id', 'no_po', 'date_po', 'delivery_no', 'date_in', 'request_by');
        $sqlIn['ctype'] = array('fkey', '', '', '', '', '');
        $sqlIn['vals'] = array('@stockin_id', $post['param']['no_po'], $post['param']['date_po'], $post['param']['delivery_no'], $post['param']['date_in'], $post['param']['request_by']);
        $loop = count($post['param']['id_barang']);
        for ($x = 0; $x < $loop; $x++) {
            $sql = array();
            $sql['table'] = "tbl_stock_in_item";
            $sql['ctype'] = array("fkey", "", "", "", "");
            $sql['cols'] = array("stockin_id", "barang_id", "order_in_qty", "received_qty", "remark");
            $sql['vals'] = array('@stockin_id', $post['param']['id_barang'][$x], $post['param']['qty_barang'][$x], $post['param']['qty_barang'][$x], $post['param']['rem_barang'][$x]);
            $sqlTemp[$x] = $this->fn->sqlQuery($sql, $updateType);
        }
        $sqlTemp[] = $this->fn->sqlQuery($sqlIn, $updateType);
        $this->queryblk = array_reverse($sqlTemp);
        return $this;
    }
    
    private function execute_query() {
        $this->db->query("SET autocommit = 0;");
        $this->db->query("START TRANSACTION;");
//        $this->db->query("SELECT @stockin_id:=MAX(stockin_id)+1 FROM tbl_stock_in;");
        $this->db->query("SELECT IFNULL(@stockin_id:=MAX(stockin_id)+1, @stockin_id:=1) FROM tbl_stock_in;");
        foreach($this->queryblk as $qryStr) {
            $this->db->query($qryStr);
        }
        if($this->db->query("COMMIT")) {
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

}
