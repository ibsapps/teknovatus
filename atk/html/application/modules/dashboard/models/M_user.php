<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {

    private $queryRes;
    private $queryStr;
    private $statusUser;
    private $queryblk = array();

    public function __construct() {
        //Initialized FN
        $this->statusUser = array('Not Active', 'Active', 'Suspended', 'Block');
        $this->load_ModSupport()->defineFileContent();
    }

    public function feed_user($lvl) {
        $sql = array();
        $sql['table'] = "t_users tb1";
        $sql['join'] = array("LEFT JOIN", "LEFT JOIN", "LEFT JOIN");
        $sql['jtable'] = array('t_user_roles tb2', 't_roles tb3', 't_groups tb4');
        $sql['jcols'] = array('tb1.id', 'tb2.fk_rid', 'tb3.fk_gid');
        $sql['jvals'] = array('tb2.fk_uid', 'tb3.rid', 'tb4.gid');
        $sql['field'] = array('tb1.id',
            'tb1.id AS cbox',
            'tb1.c_username AS username',
            'tb1.c_fullname AS nama_lengkap',
            'tb1.c_email AS email',
            'tb1.c_mobileno AS mobile_number',
            'tb1.c_status AS status',
            'tb3.role_name',
            'tb3.rid'
        );
        $sql['cond'] = " WHERE tb4.level>".$lvl;
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        return $this->drawCtrlAction($this->fetchBy());
    }

    public function batch_query($impParam) {
        $sql = array();
        $sql['table'] = "t_users tb1";
        $sql['join'] = array("LEFT JOIN", "LEFT JOIN");
        $sql['jtable'] = array('t_user_roles tb2', 't_roles tb3');
        $sql['jcols'] = array('tb1.id', 'tb2.fk_rid');
        $sql['jvals'] = array('tb2.fk_uid', 'tb3.rid');
        $sql['field'] = array('tb1.id',
            'tb1.id AS cbox',
            'tb1.c_username AS username',
            'tb1.c_fullname AS nama_lengkap',
            'tb1.c_email AS email',
            'tb1.c_mobileno AS mobile_number',
            'tb1.c_status AS status',
            'tb3.role_name',
            'tb3.rid'
        );
        $sql['cond'] = " WHERE tb1.id IN (".$impParam.")";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        return $this->fetchBy();
    }

    public function feed_karyawan($q = "") {
        $sql = array();
        if ($q != "" && strlen($q) > 2) {
            $sql['cond'] = " WHERE tb1.nama_lengkap LIKE '%" . $q . "%' OR tb1.nik LIKE '%" . $q . "%' OR tb1.email LIKE '%" . $q . "%' ";
        }
        $sql['table'] = "e_karyawan tb1";
        $sql['join'] = array('LEFT JOIN', 'LEFT JOIN');
        $sql['jtable'] = array('e_department tb2', 't_users tb3');
        $sql['jcols'] = array('tb1.id_department', 'tb1.email');
        $sql['jvals'] = array('tb2.id', 'tb3.c_email');
        $sql['field'] = array('tb1.id', 'tb1.nik', 'tb1.nama_lengkap', 'tb2.dept', 'tb1.email', 'tb1.level', 'IFNULL(tb3.c_username, "") AS username');
        $sql['order'] = "tb1.nama_lengkap ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy();
        return $rs;
    }

    public function get_karyawan($cols = array(), $vals = array()) {
        $sql = array();
        $sql['table'] = "e_karyawan tb1";
        $sql['join'] = array('LEFT JOIN', 'LEFT JOIN');
        $sql['jtable'] = array('e_department tb2', 't_users tb3');
        $sql['jcols'] = array('tb1.id_department', 'tb1.email');
        $sql['jvals'] = array('tb2.id', 'tb3.c_email');
        $sql['field'] = array('tb1.id', 'tb1.nik', 'tb1.nama_lengkap', 'tb2.dept', 'tb1.email', 'tb1.level', 'tb1.gsm', 'IFNULL(tb3.c_username, "") AS username');
        $sql['order'] = "tb1.nama_lengkap ASC";
        if (is_array($cols) && count($cols) > 0) {
            $sql['cols'] = $cols;
            $sql['vals'] = $vals;
        }
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy('fetch_row');
        return $rs;
    }

    public function role_list($lvl) {
        $sql = array();
        $sql['table'] = "t_roles tb1";
        $sql['join'] = array('LEFT JOIN');
        $sql['jtable'] = array('t_groups tb2');
        $sql['jcols'] = array('tb1.fk_gid');
        $sql['jvals'] = array('tb2.gid');
        $sql['cond'] = " WHERE tb2.level >= " . $lvl;
        $sql['field'] = array('tb1.rid', 'tb1.fk_gid', 'tb1.role_name');
        $sql['order'] = "tb1.fk_gid ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        return $this->fetchBy();
    }
    
    public function group_list($gid) {
        $sql = array();
        $sql['table'] = "t_groups tb1";
        $sql['join'] = array('LEFT JOIN', 'LEFT JOIN', 'LEFT JOIN');
        $sql['jtable'] = array('t_groups tb2', 't_groups tb3', 't_groups tb4');
        $sql['jcols'] = array('tb2.gid', 'tb3.gid', 'tb4.gid', 'tb1.gid');
        $sql['jvals'] = array('tb1.parent_id', 'tb2.parent_id', 'tb3.parent_id');
        $sql['cond'] = "WHERE tb1.parent_id>0";
        $sql['field'] = array('tb1.gid', 'tb1.group_name AS lv1', 'IFNULL(tb2.group_name,"") AS lv2', 'IFNULL(tb3.group_name,"") AS lv3', 'IFNULL(tb4.group_name, "") AS lv4');
        $sql['order'] = "tb1.parent_id ASC, tb1.level ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        return $this->fetchBy();
    }
    
    public function group_role_list($gid) {
        $sql = array();
        $sql['table'] = "t_groups tb1";
        $sql['join'] = array('LEFT JOIN', 'LEFT JOIN', 'LEFT JOIN', 'LEFT JOIN');
        $sql['jtable'] = array('t_groups tb2', 't_groups tb3', 't_groups tb4', 't_roles tb5');
        $sql['jcols'] = array('tb2.gid', 'tb3.gid', 'tb4.gid', 'tb1.gid');
        $sql['jvals'] = array('tb1.parent_id', 'tb2.parent_id', 'tb3.parent_id', 'tb5.fk_gid');
        //$sql['cols'] = array('tb1.parent_id');
        //$sql['vals'] = array($gid);
        $sql['cond'] = "WHERE tb1.parent_id>0";
        $sql['field'] = array('tb1.gid', 'tb1.group_name AS lv1', 'IFNULL(tb2.group_name,"") AS lv2', 'IFNULL(tb3.group_name,"") AS lv3', 'IFNULL(tb4.group_name, "") AS lv4', 'tb5.rid', 'tb5.role_name');
        $sql['order'] = "tb1.parent_id ASC, tb1.level ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        return $this->fetchBy();
    }
    
    public function panel_by_role($rid) {
        $arrPanel = array();
        foreach($this->panel_list($rid, 0) as $rs) {
            $rs['cbox'] = "<button type='button' name='remove_panel' data-id='".$rs['p2rid']."' class='btn btn-danger'><i class='fa fa-arrow-left'></i></button>";
            $arrPanel[] = $rs;
            foreach($this->panel_list($rid, $rs['pid']) as $sub) {
                $sub['pname'] = "[sub] ".$sub['pname'];
                $sub['cbox'] = "<button type='button' name='remove_panel' data-id='".$sub['p2rid']."' class='btn btn-danger'><i class='fa fa-arrow-left'></i></button>";
                $arrPanel[] = $sub;
            }
        }
        return $arrPanel;
    }
    
    public function panel_re_role($rid) {
        $arrPanel = array();
        foreach($this->panel_list($rid, 0, true) as $rs) {
            $rs['cbox'] = "<button type='button' name='add_panel' data-id='".$rs['pid']."' class='btn btn-success'><i class='fa fa-arrow-right'></i></button>";
            $arrPanel[] = $rs;
            foreach($this->panel_list($rid, $rs['pid'], true) as $sub) {
                $sub['pname'] = "[sub] ".$sub['pname'];
                $sub['cbox'] = "<button type='button' name='add_panel' data-id='".$sub['pid']."' class='btn btn-success'><i class='fa fa-arrow-right'></i></button>";
                $arrPanel[] = $sub;
            }
        }
        return $arrPanel;
    }
    
    public function panel_add_role($post) {
        $sql = array();
        $sql['table']   = "t_roles";
        $sql['cols']    = array('fk_gid', 'role_name', 'created', 'status');
        $sql['vals']    = array($post['fkgid'], $post['rolename'], date("Y-m-d H:i:s"), '1');
        $this->queryStr = $this->fn->sqlQuery($sql, "insert");
        return $this->db->query($this->queryStr);
    }
    
    public function add_r2panel($rid, $r2pid) {
        $sql = array();
        $sql['table']   = "t_panel2role";
        $sql['cols']    = array('fk_rid', 'fk_pid');
        $sql['vals']    = array($rid, $r2pid);
        $this->queryStr = $this->fn->sqlQuery($sql, "insert");
        return $this->db->query($this->queryStr);
    }
    
    public function rem_r2panel($id) {
        $sql = array();
        $sql['table']   = "t_panel2role";
        $sql['cols']    = array('p2rid');
        $sql['vals']    = array($id);
        $this->queryStr = $this->fn->sqlQuery($sql, "delete");
        return $this->db->query($this->queryStr);
    }

    public function save_post($post) {
        if ($post['desc']=="user_info") {
            $this->assign_param_update($post)->assign_param_activity($post)->execute_query();
        } else {
            $this->assign_param_input($post)->assign_param_activity($post)->execute_query();
        }
        return $this->queryRes;
    }

    public function batch_delete($post) {
        $this->assign_param_delete($post)->execute_query();
        return $this->queryRes;
    }

    public function batch_status($post, $m) {
        $this->assign_param_status($post, $m)->execute_query();
        return $this->queryRes;
    }
    
    public function panel_list($rid, $parentId, $reverse=false) {
        $sql = array();
        $sql['table'] = "t_panel tb1";
        $sql['join'] = array('LEFT JOIN', 'LEFT JOIN');
        $sql['jtable'] = array('t_panel2pos tb2', '(SELECT * FROM t_panel2role WHERE fk_rid=\''.$rid.'\') tb3');
        $sql['jcols'] = array('tb1.pos', 'tb3.fk_pid');
        $sql['jvals'] = array('tb2.pos_id', 'tb1.pid');
        $sql['cols'] = array('tb1.publish', 'tb1.parentid');
        $sql['vals'] = array(1, $parentId);
        if($reverse) {$sql['cond'] = 'AND tb3.p2rid IS NULL';} else {$sql['cond'] = 'AND tb3.p2rid IS NOT NULL';}
        $sql['field'] = array('tb1.pid', 'tb3.fk_rid', 'tb3.p2rid', 'tb1.pname', 'tb2.c_desc AS location', 'tb1.pos');
        $sql['order'] = "tb2.pos_id ASC, tb1.sort ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        return $this->fetchBy();
    }

    private function assign_param_update($post) {
        $updateType = "update";
        $sqlTemp = array();
        $loop = count($post['param']['id']);
        for ($x = 0; $x < $loop; $x++) {
            $sql = array();
            $sql['table'] = "t_users";
            $sql['field'] = array("c_mobileno", "c_fullname", "c_status");
            $sql['fvals'] = array($post['param']['c_mobileno'][$x], $post['param']['c_fullname'][$x], $post['param']['c_status'][$x]);
            $sql['cols'] = array("id");
            $sql['vals'] = array($post['param']['id'][$x]);
            if($post['param']['r_password'][$x]!="" && $post['param']['r_password'][$x]==$post['param']['c_password'][$x]) {
                array_push($sql['field'], 'c_password');
                $enctPass = password_hash($post['param']['r_password'][$x], PASSWORD_BCRYPT);
                array_push($sql['fvals'], $enctPass);
            }
            $sqlTemp['usr'][] = $this->fn->sqlQuery($sql, $updateType);
            $sqlDel = array();
            $sqlDel['table'] = "t_user_roles";
            $sqlDel['field'] = array('fk_rid');
            $sqlDel['fvals'] = array($post['param']['rid'][$x]);
            $sqlDel['cols'] = array('fk_uid');
            $sqlDel['vals'] = array($post['param']['id'][$x]);
            $sqlTemp['roles'][] = $this->fn->sqlQuery($sqlDel, $updateType);
        }
        $this->queryblk = $sqlTemp;
        return $this;
    }

    private function assign_param_input($post) {
        $updateType = "insert";
        $sqlTemp = array();
        $sqlIn = array();
        foreach ($post['param']['nik'] as $par) {
            $rs = $this->get_karyawan(array('nik'), array($par));
            $usrName = explode("@", $rs->email);
            $usrPass = password_hash($par, PASSWORD_BCRYPT);
            $sqlIn['table'] = "t_users";
            $sqlIn['cols'] = array('id', 'c_username', 'c_password', 'c_fullname', 'c_email', 'c_mobileno', 'c_retry', 'c_status', 'c_active_date');
            $sqlIn['ctype'] = array('fkey', '', '', '', '', '', '', '', '');
            $sqlIn['vals'] = array('@usr_id', $usrName[0], $usrPass, $rs->nama_lengkap, $rs->email, $rs->gsm, 0, 1, date("Y-m-d H:i:s"));
            $sqlTemp['usr'][] = $this->fn->sqlQuery($sqlIn, $updateType);
        }
        foreach ($post['param']['roles'] as $rol) {
            $sqlIn['table'] = "t_user_roles";
            $sqlIn['cols'] = array('fk_uid', 'fk_rid');
            $sqlIn['ctype'] = array('fkey', 'int');
            $sqlIn['vals'] = array('@usr_id', $rol);
            $sqlTemp['roles'][] = $this->fn->sqlQuery($sqlIn, $updateType);
        }
        $this->queryblk = $sqlTemp;
        return $this;
    }
    
    private function assign_param_delete($post) {
        $updateType = "delete";
        $loop = count($post['cbox']);
        for($x=0;$x<$loop;$x++) {
            $sql = array();
            $sql['table'] = "t_users";
            $sql['cols'] = array("id");
            $sql['vals'] = array($post['cbox'][$x]);
            $this->queryblk['usr'][$x] = $this->fn->sqlQuery($sql, $updateType);
            $sqli = array();
            $sqli['table'] = "t_user_roles";
            $sqli['cols'] = array("fk_uid");
            $sqli['vals'] = array($post['cbox'][$x]);
            $this->queryblk['roles'][$x] = $this->fn->sqlQuery($sqli, $updateType);
        }
        
        return $this;
    }

    private function assign_param_status($post, $m) {
        $updateType = "update";
        $loop = count($post['cbox']);
        for($x=0;$x<$loop;$x++) {
            $sql = array();
            $sql['table'] = "t_users";
            $sql['field'] = array("c_status");
            $sql['fvals'] = array($m);
            $sql['cols'] = array("id");
            $sql['vals'] = array($post['cbox'][$x]);
            $this->queryblk['usr'][$x] = $this->fn->sqlQuery($sql, $updateType);
        }
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
        $this->db->query('SET SQL_SAFE_UPDATES = 0;');
        for ($x = 0; $x < count($this->queryblk['usr']); $x++) {
            $this->db->query("SELECT IFNULL(@usr_id:=MAX(id)+1, @usr_id:=1) FROM t_users;");
            $this->db->query($this->queryblk['usr'][$x]);
            if(isset($this->queryblk['roles'][$x])) {
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
