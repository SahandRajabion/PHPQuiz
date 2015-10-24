<?php

require_once('./Controller/LoginController.php');

class LoginModel{

    private $role;
    private $roleNr = 1;
    private $id;
    private $username;
    private $hash;
    private $userRepository;
    private $messageId;
    private $cookieExpireTime;
    
   

    public function __construct() {

        $this->userRepository = new userRepository();
    }

    //Checks active session.
    public function isLoggedIn(){
        if (isset($_SESSION['loggedIn'])){
            return true;
        }
        return false;
    }

    //Logging out user.
    public function doLogOut(){
        if (isset($_SESSION['loggedIn'])) {
            $this->messageId = 11;
            session_unset("loggedIn");
        }
    }

    public function getMessage(){
        return $this->messageId;
    }

    
    public function setMessage($msgId){
        $this->messageId = $msgId;
    }

    public function getId() {
        return $_SESSION['id'];
    }

    /*Handles the login and returns true if user input is correct, 
    else returns validation message*/
    public function doLogIn($usr, $pass, $msgId){
      
       if (empty($usr) == false) {

           $this->get($usr);
       }

       //If input is empty (both username&password) or just username.
       if (empty($usr) && empty($pass) || empty($usr)) {

           $this->messageId = 0;

        }

        //If password field is empty.
        else if (empty($pass)) {

            $this->messageId = 1;

        }

        //If input missmatch stored data.
        else if($usr !== $this->username || crypt($pass, $this->hash) !== $this->hash){

            $this->messageId = 2;
        }

        //If input match stored data, then login user.
        if ($usr === $this->username && crypt($pass, $this->hash) === $this->hash) {     

            if (isset($_SESSION['loggedIn']) == false) {

                $_SESSION['loggedIn'] = $usr;
            }

            if ($this->role == $this->roleNr) {

                $this->setAdmin();
            }

            $this->setId();

            $this->messageId = $msgId;

            return true;

        }

        return false;

    }

     //Gets the userdata from database for compariation.
     public function get($username) {
        $data = $this->userRepository->get($username);

        $this->id = $data[0];
        $this->username = $data[1];
        $this->hash = $data[2];
        $this->role = $data[3];
    }
  
    public function getUsername(){
        return $_SESSION['loggedIn'];
    }

    /*Returns true if user agent is already logged in, 
    else false (if session is hacked/manipulated).*/
    public function checkUserAgent($ua){

        if(isset($_SESSION['userAgent'])){

            if($ua === $_SESSION['userAgent']){

            return true;
            }
        }
        return false;

    }

    /*If user logged in then store the 
    useragent in a session*/
    public function setUserAgent($userAgent){

        if(isset($_SESSION['userAgent']) == false){

            $_SESSION['userAgent'] = $userAgent;
        }
    }

      public function isAdmin() {
        if (isset($_SESSION['admin'])) {
            return true;
        }
        return false;
    }

    public function setId() {
        if (isset($_SESSION['id']) == false) {
            $_SESSION['id'] = $this->id;
        }       
    }
    
    public function setAdmin() {
        if (isset($_SESSION['admin']) == false) {
            $_SESSION['admin'] = $this->roleNr;
        }        
    }
    public function getUserAgent(){
        return $_SESSION['userAgent'];
    }

     //Writes expire time for coockie to textfile.
    public function writeCookieExpireTimeToFile() {
        file_put_contents("expire.txt", $this->cookieExpireTime);
    }

    //Gets expire time for coockie from textfile.
    public function getCookieExpireTimeFromFile() {
        return file_get_contents("expire.txt");
    }

     //Sets the time interval for expire.
    public function setCookieExpireTime(){
        $this->cookieExpireTime = time()+3600*24;
    }
    //Gets the time interval for expire.
   public function getCookieExpireTime(){
        return $this->cookieExpireTime;
    }

    public function encryptedPassword($pwd){
        return base64_encode($pwd);
    }

    public function decryptPassword($pwd) {
        return base64_decode($pwd);
    }
}