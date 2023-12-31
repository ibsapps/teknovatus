<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mpdf {
    
    function pdf() 
    {
        $CI = &get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }

    function load($param = NULL)
    {
        include_once APPPATH . '/third_party/mpdf/src/mpdf.php';
        if ($params == NULL) {
            $param = '"en-GB-x","A4","","",10,10,10,10,6,3';
        }
        return new mPDF($param);
    }
}
