<?php

require_once("./Model/LoginModel.php");
require_once("./helper/CookieStorage.php");
require_once("HTMLView.php");


class LoginView {
    
    private $submitLocation = "submit";
    private $usernameLocation = "username";
    private $passwordLocation = "password";
    private $checkBoxLocation = "checkbox";
    private $registerLocation = "register";

    private $model;
    private $cookieExpireTime;
    private $register = false;
    private $username;
    private $cookiePassword;
    private $message;
    private $password;
    private $encryptedPassword;
    private $htmlView;
    private $cookie;
 
    public function __construct() {

        $this->model = new LoginModel();
        $this->cookie = new CookieStorage();
        $this->htmlView = new HTMLView();
        
    }

    //Sets/saves the new cookie with the expire time.
    public function setCookie(){
        if (isset($_POST[$this->checkBoxLocation])) {
            $this->cookie->save($this->usernameLocation, $this->username, $this->cookieExpireTime);
            $this->cookie->save($this->passwordLocation, $this->encryptedPassword, $this->cookieExpireTime);
        }

    }

    //Loads cookies if there is anything to load. Returns true or false.
    public function loadCookie() {
       
        if (isset($_COOKIE[$this->usernameLocation])) {
            $cookieUser = $this->cookie->load($this->usernameLocation);
            $this->cookiePassword = $this->cookie->load($this->passwordLocation);
            $this->username = $cookieUser;

            return true;
        }
        return false;
    }

    //Deletes cookies / unseting
    public function unsetCookies() {

        $this->cookie->save($this->usernameLocation, null, time()-1);
        $this->cookie->save($this->passwordLocation, null, time()-1);
    }

    //Sets expireTime.
    public function setCookieExpireTime($expireTime) {
        $this->cookieExpireTime = $expireTime;
    }

    //Sets the cookie password.
    public function getCookiePassword() {
        return $this->cookiePassword;
    }


    //Displays login page.
    public function showLoginpage() {
       
        $username = "";
       
        if (isset($_POST[$this->submitLocation]) || $this->register == true) {
            $username = $this->username;
        }


        $html = "
        </br>
        <a href='?$this->registerLocation' name='$this->registerLocation'>Registrera ny användare</a>
                   <h1>Movie Quiz</h1>
                    
                    <form action=?login class='form-horizontal' method=post enctype=multipart/form-data>
                       <fieldset>
					      <legend>Skriv in användarnamn och lösenord</legend>
					      $this->message
					      
                          <div class='form-group'>
					        <label class='col-sm-2 control-label' for='$this->usernameLocation'>Användarnamn: </label>
					        <div class='col-sm-10'>
					          <input id='$this->usernameLocation' class='form-control' value='$username' name='$this->usernameLocation' type='text' maxlength='30' size='20' />
					        </div>
					      </div>
					    
                          <div class='form-group'>
					         <label class='col-sm-2 control-label' for='$this->passwordLocation'>Lösenord: </label>
					         <div class='col-sm-10'>
					           <input id='$this->passwordLocation' class='form-control' name='$this->passwordLocation' type='password' maxlength='20' size='20'>
					         </div>
					      </div>
				          
                          <div class='form-group'>
				             <div class='col-sm-offset-2 col-sm-10'>
				               <div class='checkbox'>
				                  <label>
					              <input class='$this->checkBoxLocation' type='checkbox' name='$this->checkBoxLocation'/> Håll mig inloggad
					              </label>
					           </div>
					         </div>
					      </div>
					     
                         <div class='form-group'>
				           <div class='col-sm-offset-2 col-sm-10'>
					         <input class='btn btn-default' name='$this->submitLocation' type='submit' value='Logga in' />
					       </div>
					     </div>
					   </fieldset>
			       </form>";

        return $html;
    }


    public function getAuthentication() {
        $this->username = $_POST[$this->usernameLocation];
        $this->password = $_POST['password'];

    }
    
    public function setMessage($message) {
        $this->message = $message;

    }

    public function getEncryptedPassword() {
        return $this->encryptedPassword;
    }

    public function setEncryptedPassword($pwd) {
        $this->encryptedPassword = $pwd;

    }

    public function getUsername() {
        return $this->username;
    }

    
    public function getPassword() {
        return $this->password;
    }

    public function didUserPressLogin() {
        if (isset($_POST[$this->submitLocation])) {
            return true;
        }
        return false;
    }

    public function setDecryptedPassword($pwd) {
        $this->password = $pwd;
    }

    
    public function setRegister($username) {
        $this->register = true;
        $this->username = $username;
    }

    public function didUserPressGoToRegisterPage() {
        if (isset($_GET[$this->registerLocation])) {
            
            return true;
        }
        return false;
    }
    
    public function keepMeLoggedIn() {
        if (isset($_POST[$this->checkBoxLocation])){
            return true;
        }
        return false;
    }
    
  
}