<?php

class My404 extends CI_Controller

{
   public function __construct()
   {
       parent::__construct();
   }

   public function index()
    {
        $code = generating_code();
        var_dump($code);
    }

}
?>