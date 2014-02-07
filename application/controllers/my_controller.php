<?php
session_start();
class My_controller extends CI_Controller
{
    public function __construct() {
        parent::__construct();
    }
    
    public function login()
    {
        $data['incorrectData'] = '';
        if (isset($_SESSION['user'])) {
            redirect('/');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $accessData = $this->input->post(null, true);
            $this->load->model('auth_model');
            $result = $this->auth_model->login($accessData);
            if ($result == true) {
                redirect('/');
                exit;
            }
        }
        $this->load->view('login', $data);
    }
    
    public function logout()
    {
        $_SESSION = array();
        unset($_COOKIE[session_name()]);
        session_destroy();
        redirect('/login');
        exit;
    }
    
    public function isAuth()
    {
        if (!isset($_SESSION['user'])) {
            redirect('/login');
            exit;
        } 
    }
}

