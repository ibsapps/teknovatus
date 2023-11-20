<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_report extends CI_Model {

    private $queryRes;
    private $queryStr;
    private $statusUser;
    private $statusOrder;
    private $queryblk = array();

    public function __construct() {
        //Initialized FN
        $this->statusUser = array('Not Active', 'Active', 'Suspended', 'Block');
        $this->load_ModSupport()->defineFileContent();
    }

    public function feed_user($q = "") {
        $sql = array();
        if ($q != "" && strlen($q) > 2) {
            $sql['cond'] = " WHERE tb1.nama_lengkap LIKE '%" . $q . "%' OR tb1.nik LIKE '%" . $q . "%' OR tb1.email LIKE '%" . $q . "%' ";
        }
        $sql['table'] = "e_karyawan tb1";
        $sql['join'] = array('LEFT JOIN', 'RIGHT JOIN');
        $sql['jtable'] = array('e_department tb2', 't_users tb3');
        $sql['jcols'] = array('tb1.id_department', 'tb1.email');
        $sql['jvals'] = array('tb2.id', 'tb3.c_email');
        $sql['field'] = array('tb1.id', 'tb3.id AS user_id', 'tb1.nik', 'tb1.nama_lengkap', 'tb2.dept', 'tb1.email', 'tb1.level', 'IFNULL(tb3.c_username, "") AS username');
        $sql['order'] = "tb1.nama_lengkap ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        return $this->fetchBy();
    }

    public function feed_barang($q = "") {
        $sql = array();
        $sql = array();
        if ($q != "" && strlen($q) > 2) {
            $sql['cond'] = " WHERE tb1.nama_barang LIKE '%" . $q . "%' OR tb2.kategori LIKE '%" . $q . "%' ";
        }
        $sql['table'] = "tbl_barang tb1";
        $sql['join'] = array('LEFT JOIN');
        $sql['jtable'] = array('tbl_kategori tb2');
        $sql['jcols'] = array('tb1.id_kategori');
        $sql['jvals'] = array('tb2.id');
        $sql['field'] = array('tb1.barang_id as cbox', 'tb1.nama_barang', 'tb1.satuan', 'tb2.kategori');
        $sql['order'] = "tb1.nama_barang ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        return $this->fetchBy();
    }

    public function feed_order($inputPost) {
        $sql = array();
        $sql_add= array();
        $sql['cols'] = array('or1.status');
        $sql['vals'] = array('3');
        foreach ($inputPost as $key => $val) {
            if ($val != "") {
                switch ($key) {
                    case "barang":
                        $sql['cols'][] = 'br1.barang_id';
                        $sql['vals'][] = $val;
                        break;
                    case "karyawan":
                        $sql['cols'][] = 'usr1.id';
                        $sql['vals'][] = $val;
                        break;
                    case "dr_tgl":
                        $dr_tgl = explode("/", $val);
                        $date = $val." 00:00:00";
                        $sql_add['dr_tgl'] = 'or1.order_date > \''.$date.'\'';
                        $sql['cond'] = "AND ".$sql_add['dr_tgl'];
                        break;
                    case "smp_tgl":
                        $smp_tgl = explode("/", $val);
                        $date = $val." 21:59:59";
                        $sql_add['smp_tgl'] = 'or1.order_date <= \''.$date.'\'';
                        $sql['cond'] = "AND (".$sql_add['dr_tgl']." AND ".$sql_add['smp_tgl'].")";
                        break;
                }
            }
        }

        $sql['table'] = "tbl_barang br1";
        $sql['join'] = array('LEFT JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'LEFT JOIN');
        $sql['jtable'] = array('tbl_kategori kat1', 'tbl_stock_out_item so1', 'tbl_order or1', 't_users usr1');
        $sql['jcols'] = array('br1.id_kategori', 'br1.barang_id', 'so1.order_id', 'or1.order_by');
        $sql['jvals'] = array('kat1.id', 'so1.barang_id', 'or1.order_id', 'usr1.id');
        $sql['field'] = array('br1.barang_id', 'br1.nama_barang', 'kat1.kategori', 'so1.stock_out_qty', 'or1.order_date', 'or1.`status`', 'usr1.c_username', 'usr1.c_fullname');
        
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        return $this->fetchBy();
    }

    private function execute_query() {
        $this->db->query("SET autocommit = 0;");
        $this->db->query("START TRANSACTION;");
        $this->db->query('SET SQL_SAFE_UPDATES = 0;');
        for ($x = 0; $x < count($this->queryblk['usr']); $x++) {
            $this->db->query("SELECT IFNULL(@usr_id:=MAX(id)+1, @usr_id:=1) FROM t_users;");
            $this->db->query($this->queryblk['usr'][$x]);
            if (isset($this->queryblk['roles'][$x])) {
                $this->db->query($this->queryblk['roles'][$x]);
            }
        }
        if ($this->db->query("COMMIT")) {
            $this->queryRes = true;
        } else {
            $this->db->query("ROLLBACK");
            $this->queryRes = false;
        }
        $this->db->query("SET autocommit = 1");
        $this->db->query('SET SQL_SAFE_UPDATES = 1;');
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
            $rsArr[$key]['status'] = $this->statusUser[$val['status']];
        }
        return $rsArr;
    }

}
