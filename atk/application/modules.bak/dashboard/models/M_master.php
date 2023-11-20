<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_master extends CI_Model {

    private $queryRes;
    private $queryStr;
    private $queryblk = array();

    public function __construct() {
        //Initialized FN
        $this->load_ModSupport()->defineFileContent();
    }

    public function list_satuan() {
        $sql = array();
        $sql['table'] = "tbl_barang tb1";
        $sql['join'] = array('LEFT JOIN');
        $sql['jtable'] = array('tbl_kategori tb2');
        $sql['jcols'] = array('tb1.id_kategori');
        $sql['jvals'] = array('tb2.id');
        $sql['field'] = array('DISTINCT(tb1.satuan)');
        $sql['order'] = "tb1.satuan ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy();
        return $rs;
    }

    public function list_kategori() {
        $sql = array();
        $sql['table'] = "tbl_kategori tb1";
        $sql['field'] = array('id, kategori');
        $sql['order'] = "tb1.kategori ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "query");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy();
        return $rs;
    }
    
    public function list_departement() {
        $sql = array();
        $sql['table'] = "e_department tb1";
        $sql['field'] = array('tb1.id, tb1.dept');
        $sql['order'] = "tb1.dept ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "query");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy();
        return $rs;
    }

    public function feed_barang() {
        $sql = array();
        $sql['table'] = "tbl_barang tb1";
        $sql['join'] = array('LEFT JOIN');
        $sql['jtable'] = array('tbl_kategori tb2');
        $sql['jcols'] = array('tb1.id_kategori');
        $sql['jvals'] = array('tb2.id');
        $sql['field'] = array('tb1.barang_id as cbox', 'tb1.nama_barang', 'tb1.satuan', 'tb2.kategori');
        $sql['order'] = "tb1.nama_barang ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy();
        return $this->drawCtrlAction($rs);
    }

    public function feed_karyawan() {
        $sql = array();
        $sql['table'] = "e_karyawan tb1";
        $sql['join'] = array('LEFT JOIN');
        $sql['jtable'] = array('e_department tb2');
        $sql['jcols'] = array('tb1.id_department');
        $sql['jvals'] = array('tb2.id');
        $sql['field'] = array('tb1.id as cbox', 'tb1.nik', 'tb1.nama_lengkap', 'tb2.dept', 'tb1.email', 'tb1.level');
        $sql['order'] = "tb1.nama_lengkap ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy();
        return $this->drawCtrlAction($rs);
    }
    
    public function get_barang($id="") {
        $sql = array();
        $sql['table'] = "tbl_barang tb1";
        $sql['join'] = array('LEFT JOIN');
        $sql['jtable'] = array('tbl_kategori tb2');
        $sql['jcols'] = array('tb1.id_kategori');
        $sql['jvals'] = array('tb2.id');
        $sql['field'] = array('tb1.barang_id', 'tb1.nama_barang', 'tb1.satuan', 'tb1.id_kategori', 'tb2.kategori', 'tb1.file');
        $sql['order'] = "tb1.nama_barang ASC";
        if($id!="") {
            $sql['cols'] = array('tb1.barang_id');
            $sql['vals'] = array($id);
        }
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy();
        return $rs;
    }
    
    public function search_barang($q="") {
        $sql = array();
        $sql['table'] = "tbl_barang tb1";
        $sql['join'] = array('LEFT JOIN');
        $sql['jtable'] = array('tbl_kategori tb2');
        $sql['jcols'] = array('tb1.id_kategori');
        $sql['jvals'] = array('tb2.id');
        $sql['field'] = array('tb1.barang_id', 'tb1.nama_barang', 'tb1.satuan', 'tb1.id_kategori', 'tb2.kategori', 'tb1.file');
        $sql['order'] = "tb1.nama_barang ASC";
        if($q!="") {
            $sql['cond'] = " WHERE tb1.nama_barang LIKE '%".$q."%'";
        }
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy();
        return $rs;
    }
    
     public function get_karyawan($id="") {
        $sql = array();
        $sql['table'] = "e_karyawan tb1";
        $sql['join'] = array('LEFT JOIN');
        $sql['jtable'] = array('e_department tb2');
        $sql['jcols'] = array('tb1.id_department');
        $sql['jvals'] = array('tb2.id');
        $sql['field'] = array('tb1.id', 'tb1.nik', 'tb1.nama_lengkap', 'tb2.id as id_departement', 'tb2.dept', 'tb1.email', 'tb1.level', 'tb1.gsm');
        $sql['order'] = "tb1.nama_lengkap ASC";
        if($id!="") {
            $sql['cols'] = array('tb1.id');
            $sql['vals'] = array($id);
        }
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy();
        return $rs;
    }
    
    public function save_post($post, $node) {
        switch($node) {
            case "barang":
                $this->assign_param_barang($post)->execute_query();
                break;
            case "karyawan":
                $this->assign_param_karyawan($post)->execute_query();
                break;
        }
        return $this->queryRes;
    }

    public function delete_post($post, $node) {
        switch($node) {
            case "barang":
                $this->assign_delete_barang($post)->execute_query();
                break;
            case "karyawan":
                $this->assign_delete_karyawan($post)->execute_query();
                break;
        }
        return $this->queryRes;
    }

    private function drawCtrlAction($rs) {
        $rsArr = array();
        foreach ($rs as $key => $val) {
//            $overrideLastCol  = "Edit | Delete";
            $rsArr[$key] = $val;
            $overrideFirstCol = "<input type=\"checkbox\" class=\"cbox\" name=\"cbox[]\" value=\"" . $val['cbox'] . "\" />";
            $rsArr[$key]['cbox'] = $overrideFirstCol;
        }
        return $rsArr;
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
    
    private function assign_param_barang($post) {
        $updateType = "insert";
        $loop = count($post['nama_barang']);
        for($x=0;$x<$loop;$x++) {
            $sql = array();
            $sql['table'] = "tbl_barang";
            $sql['cols'] = array("nama_barang", "id_kategori", "satuan", "file");
            if($post['opt_satuan'][$x]=="addNew") {
                $satuanDlm = $post['satuan'][$x];
            } else {
                $satuanDlm = $post['opt_satuan'][$x];
            }
            $sql['vals'] = array($post['nama_barang'][$x], $post['id_kategori'][$x], $satuanDlm, $post['uploaded_file'][$x]);
            if(isset($post['cbox'][$x]) && $post['cbox'][$x]!="") {
                $sql['field'] = array("nama_barang", "id_kategori", "satuan", "file");
                $sql['fvals'] = array($post['nama_barang'][$x], $post['id_kategori'][$x], $satuanDlm, $post['uploaded_file'][$x]);
                $sql['cols'] = array('barang_id');
                $sql['vals'] = array($post['cbox'][$x]);
                $updateType = "update";
            }
            $this->queryblk[$x] = $this->fn->sqlQuery($sql, $updateType);
        }
        return $this;
    }
    
    private function assign_param_karyawan($post) {
         $updateType = "insert";
        $loop = count($post['nik']);
        for($x=0;$x<$loop;$x++) {
            $sql = array();
            $sql['table'] = "e_karyawan";
            $sql['cols'] = array("nik", "nama_lengkap", "id_department", "gsm", "email");
            $sql['vals'] = array($post['nik'][$x], $post['nama_lengkap'][$x], $post['id_departement'][$x], $post['gsm'][$x], $post['email'][$x]);
            if(isset($post['cbox'][$x]) && $post['cbox'][$x]!="") {
                $sql['field'] = array("nik", "nama_lengkap", "id_department", "gsm", "email");
                $sql['fvals'] = array($post['nik'][$x], $post['nama_lengkap'][$x], $post['id_departement'][$x], $post['gsm'][$x], $post['email'][$x]);
                $sql['cols'] = array('id');
                $sql['vals'] = array($post['cbox'][$x]);
                $updateType = "update";
            }
            $this->queryblk[$x] = $this->fn->sqlQuery($sql, $updateType);
        }
        return $this;       
    }

    private function assign_delete_barang($post) {
        $updateType = "delete";
        $loop = count($post['cbox']);
        for($x=0;$x<$loop;$x++) {
            $sql = array();
            $sql['table'] = "tbl_barang";
            $sql['cols'] = array("barang_id");
            $sql['vals'] = array($post['cbox'][$x]);
            $this->queryblk[$x] = $this->fn->sqlQuery($sql, $updateType);
        }
        return $this;
    }
    
    private function assign_delete_karyawan($post) {
        $updateType = "delete";
        $loop = count($post['cbox']);
        for($x=0;$x<$loop;$x++) {
            $sql = array();
            $sql['table'] = "e_karyawan";
            $sql['cols'] = array("id");
            $sql['vals'] = array($post['cbox'][$x]);
            $this->queryblk[$x] = $this->fn->sqlQuery($sql, $updateType);
        }
        return $this;
    }
    
    private function execute_query() {
        $this->db->query("SET autocommit = 0;");
        $this->db->query("START TRANSACTION");
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

}
