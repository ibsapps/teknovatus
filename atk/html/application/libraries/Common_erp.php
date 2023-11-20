<?php

/**
 * class dbquery
 *
 * @package    	Web Development Frameworks
 * @author     	Gilang <gilang@kresnadi.web.id>
 * @License    	Free GPL
 * @link 	http://www.kresnadi.web.id
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_erp {

    private $erpResult;
    private $sesUserData;

    public function erpFilterCheck($postData, $filter) {
        foreach ($postData as $key => $var) {
            $filter['res'][$key] = preg_match($filter['rules'][$key], $var);
            $filter['param'][$key] = $var;
        }
        $errCheck = "";
        foreach ($filter['res'] as $rkey => $rval) {
            if ($rval == 0) {
                $errCheck = $filter['err'][$rkey];
                break;
            }
        }
        if ($errCheck != "") {
            $this->erpResult = array('status' => 0, 'desc' => $errCheck, 'param' => $filter['param']);
        } else {
            $this->erpResult = array('status' => 1, 'desc' => 'Parameter filter passed', 'param' => $filter['param']);
        }
    }

    public function erpGetResult() {
        return $this->erpResult;
    }

    public function erpSessionCheck($ses) {
        if ($ses->has_userdata('logged_in')) {
            $this->sesUserData = $ses->userdata();
            return $this->sesUserData;
        } else {
            return false;
        }
    }

}
