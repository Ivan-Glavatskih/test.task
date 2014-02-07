<?php

class Auth_model extends CI_Model
{
    /**
     *
     * @var string Path to file with user access data
     */
    private $userDataFile = 'files/users.txt';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function login($inputData)
    {
        if (isset($inputData['send']) && $inputData['send'] == 'signin') {
            if($this->isCorrectLogin($inputData)) {
                $_SESSION['user'] = true;
                return true;
            }
        }
    }
    
    /**
     * Trims input data
     * 
     * @param array $inputData
     * @return boolean|array
     */
    private function parseInputData($inputData)
    {
        $login      = trim($inputData['login']);
        $password   = trim($inputData['pass']);
        
        if ($login == '' || $password == '') {
            $_SESSION['error'] = 'Неверные данные';
            return false;
        }
        return array('login' => strtolower($login), 'pass' => $password);
    }
    
    private function countAttempts()
    {
        if (!isset($_SESSION['count'])) {
            $_SESSION['count'] = 0;
        } else {
            $_SESSION['count'] += 1;
        }
        return $_SESSION['count'];
    }
    
    private function isBlockSystem()
    {
        $count = $this->countAttempts();
        if ($count == 3) {
            $_SESSION['block'] = 'Попробуйте еще раз через № секунд';
            return true;
        }
        return false;
    }
    
    /**
     * Mathces input data against data account
     * @param array $inputData
     */
    private function matchAccessData($inputData)
    {
        $rfile = fopen($this->userDataFile, 'r');
        while(!feof($rfile)) {
            $string = fgets($rfile);
            $string = trim($string);
            list($login, $pass) = explode(":", $string);
            $login = strtolower($login);
            if ($login == $inputData['login'] && $pass == $inputData['pass']) {
                $_SESSION['userName'] = ucfirst($login);
                return true;
            } 
        }
        $_SESSION['error'] = 'Неверные данные';
        return false;
    }
    
    /**
     * 
     * @param array $inputData
     * @return boolean
     */
    private function isCorrectLogin($inputData)
    {
        $inputData = $this->parseInputData($inputData);
        if ($inputData === false) {
            return false;
        } else {
            return $this->matchAccessData($inputData);
        }
        
    }
    
    
}

