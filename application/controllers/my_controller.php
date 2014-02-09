<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class My_controller extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('auth_model');
    }
    
    public function login()
    {
        if ($this->auth_model->isBlockedUser()) {
            $this->load->view('block');
            return;
        }
             
        $data['incorrectData'] = '';
        if (isset($_SESSION['user'])) {
            redirect('/');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $accessData = $this->input->post(null, true);
            $result = $this->auth_model->login($accessData);
            if ($result == true) {
                redirect('/');
                exit;
            }
            
            /*if (isset($_SESSION['error'])) {
                $this->load->view('error');
                return;
            }*/
        }
        $this->load->view('login', $data);
    }
    
    public function logout()
    {
        $_SESSION = array();
        setcookie(session_name(), $_COOKIE[session_name()], time()-3600, '/');
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
