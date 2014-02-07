<?php
require_once '/my_controller.php';

class User extends My_controller
{
    public function __construct() {
        parent::__construct();
        parent::isAuth();
    }
    
    public function index()
    {
        $this->load->view('user');
    }
}
