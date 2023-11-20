<?php

/**
 * index script
 *
 * @package    Web Development
 * @author     Gilang Teguh Kresnadi
 * @copyright  2017 Feast Web Studio
 * @link http://www.fwebstudio.com
 */
class M_panel extends CI_Model {

    private $queryRes;
    private $queryStr;
    private $byPass;

    public function __construct() {
        //Initialized FN
        $this->byPass['modul'] = array('dashboard');
        $this->byPass['ctrl'] = array('dashboard');
        $this->byPass['node'] = array('welcome');
        $this->load_ModSupport()->defineFileContent();
    }
    
    public function query_user($valSource, $cols = array('tb2.c_username')) {
        //FORCED VALUE INTO ARRAY
        if(!is_array($valSource)) {$vals = array($valSource);} else {$vals = $valSource;}
        $sql = array();
        $sql['table'] = "t_user_roles tb1";
        $sql['join'] = array("LEFT JOIN", "LEFT JOIN", "LEFT JOIN", "LEFT JOIN", "LEFT JOIN", "LEFT JOIN");
        $sql['jtable'] = array('t_users tb2', 't_roles tb3', 't_groups tb5', 't_corporate tb4', 't_panel2role tb6', 't_panel tb7');
        $sql['jcols'] = array('tb1.fk_uid', 'tb1.fk_rid', 'tb3.fk_gid', 'tb5.fk_cid', 'tb6.fk_rid', 'tb7.pid');
        $sql['jvals'] = array('tb2.id', 'tb3.rid', 'tb5.gid', 'tb4.cid', 'tb3.rid', 'tb6.fk_pid');
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
            'tb5.gid',
            'tb5.level',
            'tb5.group_name'
        );
        $sql['cols'] = $cols;
        $sql['vals'] = $vals;
//        $sql['cond'] = "OR (" . $cols[0] . "='" . $vals[0] . "' AND tb5.level=0)";
        $sql = $this->panel2Role($sql);
                
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
//        echo $this->queryStr;
        $this->queryRes = $this->db->query($this->queryStr);
        return $this->fetchBy('fetch_row');
    }

    public function query_all_json() {
        $sql = array();
        $sql['table'] = "t_panel tb1";
        $sql['field'] = array('tb1.pid', 'tb1.parentid', 'tb1.mod_name', 'tb1.ctrl_name', 'tb1.node_name', 'tb1.open_url', 'tb1.pname', 'tb1.publish', 'tb1.sort', 'tb1.pos');
        $sql['order'] = "tb1.pos ASC, tb1.parentid ASC, tb1.sort ASC";
        $this->queryStr = $this->fn->sqlQuery($sql, "query");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy();
        $arrList = array();
        if (!empty($rs)) {
            foreach ($rs as $rows) {
                $parentId = $rows['pid'];
                $hirarki = $rows['pname'];
                $arrow = " -> ";
                while ($parentId > 0) {
                    $sqli['table'] = "t_panel tb1";
                    $sqli['field'] = array('tb1.pid', 'tb1.pname', 'tb1.parentid', 'tb1.publish');
                    $sqli['cols'] = array('tb1.pid');
                    $sqli['vals'] = array($parentId);
                    $this->queryStr = $this->fn->sqlQuery($sqli, "query");
                    $this->queryRes = $this->db->query($this->queryStr);
                    $rss = $this->fetchBy('fetch_row');
                    if ($hirarki != $rss->pname) {
                        $hirarki = $rss->pname . $arrow . $hirarki;
                    }
                    $parentId = $rss->parentid;
                }
                $arrList[] = array('pid' => $rows['pid'], 'parentName' => $hirarki, 'mod_name' => $rows['mod_name'], 'ctrl_name' => $rows['ctrl_name'], 'node_name' => $rows['node_name'], 'open_url' => $rows['open_url'], 'pname' => $rows['pname'], 'sort' => $rows['sort'], 'pos' => $rows['pos'], 'publish' => $rows['publish']);
            }
        }
        return $arrList;
    }

    function menu_panel($rid) {
        $sql = array();
        $sql['table'] = "t_panel tb1";
        $sql['join'] = array('LEFT JOIN');
        $sql['jtable'] = array('t_panel2role tb2');
        $sql['jcols'] = array('tb1.pid');
        $sql['jvals'] = array('tb2.fk_pid');
        $sql['field'] = array('tb1.pid', 'tb1.mod_name', 'tb1.ctrl_name', 'tb1.node_name', 'tb1.open_url', 'tb1.pname', 'tb1.sort', 'tb1.pos');
        $sql['cols'] = array('tb2.fk_rid', 'tb1.publish', 'tb1.parentid');
        $sql['vals'] = array($rid, 1, 0);
        $sql['order'] = "pos ASC, sort ASC";
        $sql['group'] = "tb1.pid";
//        $strQuery = $this->fn->sqlQuery($sql, "join");
//        $rs = $this->fn->DB($strQuery, 'fetch_rowset');
        $this->queryStr = $this->fn->sqlQuery($sql, "join");
        $this->queryRes = $this->db->query($this->queryStr);
        $rs = $this->fetchBy();
        $arr = array();
        $x = 0;
        $thisPos = 0;
        foreach ($rs as $row) {
            if ($row['pos'] > $thisPos) {
                $x = 0;
                $thisPos = $row['pos'];
            }
            $modPath = "";
            $ctrlPath = "";
            $nodePath = "";
            if ($row['mod_name'] != "") {$modPath = $row['mod_name'];}
            if ($row['ctrl_name'] != "") {$ctrlPath = "/" . $row['ctrl_name'];}
            if ($row['node_name'] != "") {$nodePath = "/" . $row['node_name'];}
            $arr[$row['pos']][$x] = array('pid' => $row['pid'],
                'ctrlName' => $row['ctrl_name'],
                'pos' => $row['pos'],
                'nodeName' => $row['node_name'],
                'openUrl' => $row['open_url'],
                'closeUrl' => base_url() . $modPath . $ctrlPath . $nodePath,
                'pName' => $row['pname'],
                'sort' => $row['sort']);
            $sql['cols'] = array('tb2.fk_rid', 'tb1.publish', 'tb1.parentid');
            $sql['vals'] = array($rid, 1, $row['pid']);
//            $strQuery1 = $this->fn->sqlQuery($sql, "join");
//            $rs1 = $this->fn->DB($strQuery1, 'fetch_rowset');
            //            echo $strQuery1;
            $this->queryStr = $this->fn->sqlQuery($sql, "join");
            $this->queryRes = $this->db->query($this->queryStr);
            $rs1 = $this->fetchBy();
            $array1 = array();
            foreach ($rs1 as $row1) {
                if ($row1['mod_name'] != "") {$modPath1 = $row1['mod_name'];} else {$modPath1 = "";}
                if ($row1['ctrl_name'] != "") {$ctrlPath1 = "/" . $row1['ctrl_name'];} else {$ctrlPath1 = "";}
                if ($row1['node_name'] != "") {$nodePath1 = "/" . $row1['node_name'];} else {$nodePath1 = "";}
                $array1[] = array('pid' => $row1['pid'],
                    'ctrlName' => $row1['ctrl_name'],
                    'pos' => $row1['pos'],
                    'nodeName' => $row1['node_name'],
                    'openUrl' => $row1['open_url'],
                    'closeUrl' => base_url() . $modPath1 . $ctrlPath1 . $nodePath1,
                    'pName' => $row1['pname'],
                    'sort' => $row1['sort']);
            }
            $arr[$row['pos']][$x]['sub'] = $array1;
            $x++;
        }
        return $arr;
    }
    
    private function panel2Role($sql) {
        //FILTER Modul
        if (isset($this->fn->fileContent[0]) && !in_array($this->fn->fileContent[0], $this->byPass['modul'])) {
            array_push($sql['cols'], 'tb7.mod_name');
            array_push($sql['vals'], $this->fn->fileContent[0]);
        }
        //FILTER Controller
        if (isset($this->fn->fileContent[1]) && !in_array($this->fn->fileContent[1], $this->byPass['ctrl'])) {
            array_push($sql['cols'], 'tb7.mod_name');
            array_push($sql['vals'], $this->fn->fileContent[0]);
            array_push($sql['cols'], 'tb7.ctrl_name');
            array_push($sql['vals'], $this->fn->fileContent[1]);
        }
        //FILTER Node
        if (isset($this->fn->fileContent[2]) && !in_array($this->fn->fileContent[2], $this->byPass['node'])) {
            array_push($sql['cols'], 'tb7.mod_name');
            array_push($sql['vals'], $this->fn->fileContent[0]);
            array_push($sql['cols'], 'tb7.ctrl_name');
            array_push($sql['vals'], $this->fn->fileContent[1]);
            array_push($sql['cols'], 'tb7.node_name');
            array_push($sql['vals'], $this->fn->fileContent[2]);
        }
        return $sql;
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

}
