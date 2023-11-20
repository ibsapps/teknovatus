<?php

class M_corporate extends CI_Model
{

    private $queryRes;

    private $statusUser;

    private $queryblk = array();

    private $queryStr;

    public function __construct()
    {
        // LOADING FN Library
        $this->load_ModSupport()->defineFileContent();
    }

    public function query_user($vals, $cols = array('tb2.c_username'), $fetch = 'fetch_rowset')
    {
        $sql = array();
        $sql['table'] = "t_user_roles tb1";
        $sql['join'] = array(
            "LEFT JOIN",
            "LEFT JOIN",
            "LEFT JOIN",
            "LEFT JOIN"
        );
        $sql['jtable'] = array(
            't_users tb2',
            't_roles tb3',
            't_groups tb5',
            't_corporate tb4'
        );
        $sql['jcols'] = array(
            'tb1.fk_uid',
            'tb1.fk_rid',
            'tb3.fk_gid',
            'tb5.fk_cid'
        );
        $sql['jvals'] = array(
            'tb2.id',
            'tb3.rid',
            'tb5.gid',
            'tb4.cid'
        );
        $sql['field'] = array(
            'tb2.id',
            'tb2.c_username',
            'tb2.c_password',
            'tb2.c_fullname',
            'tb2.c_email',
            'tb2.c_mobileno',
            'tb2.c_retry',
            'tb1.fk_rid',
            'tb3.role_name',
            'tb4.cid',
            'tb4.corp_name',
            'tb5.level',
            'tb5.group_name'
        );
        $sql['cols'] = $cols;
        $sql['vals'] = $vals;
        $sql['cond'] = ' AND tb2.c_retry<3 OR tb2.c_email=\'' . $vals[0] . '\'';
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        // echo $this->queryStr;
        $this->queryRes = $this->db->query($this->queryStr);
        if ($this->queryRes) {
            return $this->fetchBy($fetch);
        } else {
            return false;
        }
    }

    public function arrUser($res)
    {
        if (is_array($res)) {
            $rs = (object) $res;
        } else if (is_object($res)) {
            $rs = $res;
        } else {
            return false;
        }
        $arrUser = array(
            'userid' => $rs->id,
            'username' => $rs->c_username,
            'enctpass' => $rs->c_password,
            'fullname' => $rs->c_fullname,
            'retry' => $rs->c_retry,
            'email' => $rs->c_email,
            'phone' => $rs->c_mobileno,
            'rolesid' => $rs->fk_rid,
            'rolesname' => $rs->role_name,
            'cid' => $rs->cid,
            'corp_name' => $rs->corp_name,
            'level' => $rs->level,
            'groupname' => $rs->group_name
        );
        return $arrUser;
    }

    public function show_query()
    {
        return $this->queryStr;
    }

    public function save_post($post)
    {
        $this->assign_param_input($post)->execute_query();
        return $this->queryRes;
    }
    
    public function update_post($post)
    {
        $this->assign_param_update($post)->execute_query();
        return $this->queryRes;
    }

    private function assign_param_input($post)
    {
        $updateType = "insert";
        $sqlTemp = array();
        $sqlIn = array();
        $par = $post['param']['nik'];
        $rs = $this->get_karyawan(array('nik'), array($par));
        $usrName = explode("@", $rs->email);
        $usrPass = password_hash($par, PASSWORD_BCRYPT);
        $sqlIn['table'] = "t_users";
        $sqlIn['cols'] = array(
            'id',
            'c_username',
            'c_password',
            'c_fullname',
            'c_email',
            'c_mobileno',
            'c_retry',
            'c_status',
            'c_active_date'
        );
        $sqlIn['ctype'] = array('fkey','','','','','','','','');
        $sqlIn['vals'] = array(
            '@usr_id',
            $usrName[0],
            $usrPass,
            $rs->nama_lengkap,
            $rs->email,
            $rs->gsm,
            0,
            1,
            date("Y-m-d H:i:s")
        );
        $sqlTemp['usr'][] = $this->fn->sqlQuery($sqlIn, $updateType);
        
        $rol = 25;
        $sqlIn['table'] = "t_user_roles";
        $sqlIn['cols'] = array(
            'fk_uid',
            'fk_rid'
        );
        $sqlIn['ctype'] = array(
            'fkey',
            'int'
        );
        $sqlIn['vals'] = array(
            '@usr_id',
            $rol
        );
        $sqlTemp['roles'][] = $this->fn->sqlQuery($sqlIn, $updateType);
        $this->queryblk = $sqlTemp;
        return $this;
    }
    
    private function assign_param_update($post)
    {
        $updateType = "update";
        $sqlTemp = array();
        $sqlIn = array();
        $usrPass = password_hash($post['param']['new'], PASSWORD_BCRYPT);
        $sqlIn['table'] = "t_users";
        $sqlIn['field'] = array('c_password');
        $sqlIn['fvals'] = array($usrPass);
        $sqlIn['cols'] = array('id');
        $sqlIn['vals'] = array($post['param']['user']['userid']);
        $sqlTemp['usr'][] = $this->fn->sqlQuery($sqlIn, $updateType);
        
        $this->queryblk = $sqlTemp;
        return $this;
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

    private function execute_query()
    {
        $this->db->query("SET autocommit = 0;");
        $this->db->query("START TRANSACTION;");
        $this->db->query('SET SQL_SAFE_UPDATES = 0;');
        for ($x = 0; $x < count($this->queryblk['usr']); $x ++) {
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

    public function feed_karyawan($q = "")
    {
        $sql = array();
        if ($q != "" && strlen($q) > 2) {
            $sql['cond'] = " WHERE tb1.nama_lengkap LIKE '%" . $q . "%' OR tb1.nik LIKE '%" . $q . "%' OR tb1.email LIKE '%" . $q . "%' ";
        }
        $sql['table'] = "e_karyawan tb1";
        $sql['join'] = array(
            'LEFT JOIN',
            'LEFT JOIN'
        );
        $sql['jtable'] = array(
            'e_department tb2',
            't_users tb3'
        );
        $sql['jcols'] = array(
            'tb1.id_department',
            'tb1.email'
        );
        $sql['jvals'] = array(
            'tb2.id',
            'tb3.c_email'
        );
        $sql['field'] = array(
            'tb1.id',
            'tb1.nik',
            'tb1.nama_lengkap',
            'tb2.dept',
            'tb1.email',
            'tb1.level',
            'IFNULL(tb3.c_username, "") AS username'
        );
        $sql['order'] = "tb1.nama_lengkap ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy('fetch_row');
        return $rs;
    }

    private function fetchBy($fetchMode)
    {
        switch ($fetchMode) {
            case "fetch_rowset":
                return $this->queryRes->result_array();
            case "fetch_row":
                return $this->queryRes->row();
            default:
                return $this->queryRes->result_array();
        }
    }

    private function defineFileContent()
    {
        $startSegment = 1;
        foreach ($this->uri->segment_array() as $key => $sg) {
            if ($startSegment <= $key) {
                $this->fn->fileContent[] = $sg;
            }
        }
        return $this;
    }

    private function load_ModSupport()
    {
        $this->load->library('query_generator', NULL, 'fn');
        return $this;
    }
}
