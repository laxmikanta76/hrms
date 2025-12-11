<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MX_Controller {
    
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

        $data['title'] = "Test :: Read";
        $data['module'] = "accounting";  
        $data['page']   = "home";   
        echo Modules::run('template/layout', $data); 
    }

    public function create()
    {
        $this->permission->module()->create()->redirect(); 
        $data['title'] = "Test :: Create";
        $data['module'] = "accounting";  
        $data['page']   = "home";   
        echo Modules::run('template/layout', $data); 
    }
 
    public function update()
    {
        $this->permission->module()->update()->redirect();
        $data['title'] = "Test :: Update";
        $data['module'] = "accounting";  
        $data['page']   = "home";   
        echo Modules::run('template/layout', $data); 
    }
 
    public function delete()
    {
        $this->permission->module()->delete()->redirect(); 
        $data['title'] = "Test :: Delete";
        $data['module'] = "accounting";  
        $data['page']   = "home";   
        echo Modules::run('template/layout', $data); 
    }
 
}
