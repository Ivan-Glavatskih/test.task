<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
