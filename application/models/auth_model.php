<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    /**
     * Path to file with user access data
     * 
     * @var string
     */
    private $userDataFile = 'files/users.txt';
    
    /**
     * The name of file that consists initital blocked time
     * 
     * @var string
     */
    private $blockedFileName;


    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->blockedFileName = $this->setFileName();
    }
    
    /**
     * This is main method. Invokes checker method if isset button send
     * 
     * @param type $inputData
     * @return boolean
     */
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
            $this->blockUser();
            return false;
        }
        return array('login' => strtolower($login), 'pass' => $password);
    }
    
    /**
     * Counts unsuccessful attempts to signin
     * 
     * @return int
     */
    private function countAttempts()
    {
        if (!isset($_SESSION['count'])) {
            $_SESSION['count'] = 1;
        } else {
            $_SESSION['count'] += 1;
        }
        return $_SESSION['count'];
    }
    
    /**
     * Blocks system for 5 minutes
     * 
     * @return boolean
     */
    private function blockUser()
    {
        $count = $this->countAttempts();
        if ($count >= 3) {
            $rfile = fopen($this->blockedFileName, 'w');
            fwrite($rfile, time());
            unset($_SESSION['count']);
            fclose($rfile);
            return true;
        }
        return false;
    }
    
    /**
     * Generates file name of blocked user
     * 
     * @return string
     */
    private function setFileName()
    {
        $remoteIP = $_SERVER['REMOTE_ADDR'];
        if (!is_dir('files/blocked')) {
            mkdir('files/blocked', 0777);
        }
        $fileName = 'files/blocked/' . md5($remoteIP) . '.txt';
        return $fileName;
    }

    /**
     * Determines whether user bocked or not
     * 
     * @return boolean
     */
    public function isBlockedUser()
    {
        if ($rfile = @fopen($this->blockedFileName, 'r')) {
            $initialTime    = (int)fgets($rfile);
            fclose($rfile);
            $currentTime    = time();
            $expireTime      = 20; //seconds
            $leftTime       = $expireTime - ($currentTime - $initialTime);
            if ($leftTime > 0) {
                $_SESSION['block'] = "Попробуйте еще раз через {$leftTime} секунд";
                return true;
            } else {
                unset($_SESSION['block']);
                unlink('./' . $this->blockedFileName);
            }
        }
        return false;
    }

    /**
     * Mathces input data against data account
     *
     * * @param array $inputData
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
        $this->blockUser();
        return false;
    }
    
    /**
     * Determines whether input data equivalent to account data
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
