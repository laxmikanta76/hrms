<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Single extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();

        // $this->permission->module('accounting')->redirect();
        $this->permission->module()->redirect();
    }
 
    public function index()
    {
        // $this->permission->module('accounting', 'read')->redirect(); 
        $this->permission->module()->read()->redirect(); 

        $data['title'] = "Single :: Read";
        $data['module'] = "accounting";  
        $data['page']   = "home";   
        echo Modules::run('template/layout', $data); 
    }
 
 
}
