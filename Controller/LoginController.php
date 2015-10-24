<?php
require_once("View/LoginMessages.php");
require_once("Model/LoginModel.php");
require_once("Model/Hash.php");
require_once("Model/Dao/UserRepository.php");
require_once("Model/User.php");
require_once("View/LoginView.php");
require_once("View/LoggedInView.php");
require_once("View/HTMLView.php");
require_once("helper/UserAgent.php");
require_once("View/RegisterView.php");
require_once("Model/Validation/ValidatePassword.php");
require_once("Model/Validation/ValidateUsername.php");


class LoginController {
    private $model;
    private $username;
    private $password;
    private $userAgent;
    private $userAgent2;
    private $showLoggedInPage;
    private $showRegisterPage;
    private $loginView;
    private $validateUsername;
    private $validatePassword;
    private $hash;
    private $registerView;
    private $htmlView;
    private $loggedInView;

    public function __construct() {

        $this->validateUsername = new ValidateUsername();
        $this->validatePassword = new ValidatePassword();
        $this->loginView = new LoginView();
        $this->htmlView = new HTMLView();
        $this->registerView = new RegisterView();
        $this->userRepository = new UserRepository();
        $this->hash = new Hash();
        $this->loggedInView = new LoggedInView();
        $this->model = new LoginModel();
    }

    
    public function doControll() {
            $this->logOutUser();
            $this->logInUser();
            $this->goRegisterPage();
            $this->registerNewUser();
            $this->returnLoginPage();
            $this->logInCookie();
            $this->isLoggedIn();
            $this->renderPage();
    }




    //Checks so the session is not altered and if user has logged in session.
    public function isLoggedIn() {
        $userAgent = new UserAgent();
        $this->userAgent2 = $userAgent->getUserAgent();
        if($this->model->isLoggedIn() && $this->model->checkUserAgent($this->userAgent2)) {
            $this->showLoggedInPage = true;
        }
    }

    // Checks if user is authenticated already.
    public function isAuthenticated() {
        if ($this->model->isLoggedIn()) {
            return true;
        }
        return false;
    }



    //Logging in user if the statements below are passed.
    public function logInUser(){

        //If user not already is logged in.
        if (!$this->model->isLoggedIn()) {
           
            if ($this->loginView->didUserPressLogin()) {
                $this->loginView->getAuthentication();
               
                if($this->loginView->keepMeLoggedIn()) {
                    $msgId = 9;
                }
                
                else {
                    $msgId = 10;
                }

                $this->setUsername();
                $this->setPassword();

                if ($this->model->doLogIn($this->username, $this->password, $msgId )) {
                   
                    $this->setMessage();
                    $this->userRepository->get($this->username);
                    $userAgent = new UserAgent();
                    $this->userAgent = $userAgent->getUserAgent();
                    
                    $this->encryptPassword();

                    $this->getCookieExpireTime();
                    $this->model->setCookieExpireTime();
                    $this->model->writeCookieExpireTimeToFile();
                    $this->loginView->setCookie();
                    $this->model->setUserAgent($this->userAgent);

                    $this->showLoggedInPage = true;

                } 
                else {

                    $this->setMessage();
                    $this->showLoggedInPage = false;
                }
            }
             else {

                    $this->showLoggedInPage = false;
            }
        }
    }
    

    //Checks if user pressed logout.
    public function logOutUser() {

        if ($this->model->isLoggedIn()) {
            
            if ($this->loggedInView->didUserPressLogOut()) {
               
                if ($this->loginView->loadCookie()) {
                    $this->loginView->unsetCookies();                   
                }
                $this->model->doLogOut();
                $this->setMessage();
            }
        }
    }

    //Checks if user can login with stored cookies. 
    public function logInCookie() {
       
        if (!$this->loggedInView->didUserPressLogOut() && $this->loginView->loadCookie() && !$this->model->isLoggedIn() && !$this->loginView->didUserPressLogin()) {
           
            if (time() < $this->model->getCookieExpireTimeFromfile()) {
                $this->setUsername();
                $this->setDecryptedPassword();
               
                $msgId = 13;

                if ($this->model->doLogIn($this->username, $this->password, $msgId)) {
                    $userAgent = new UserAgent();
                    $this->userAgent = $userAgent->getUserAgent();
                    $this->model->setUserAgent($this->userAgent);

                    $this->setMessage();
                    $this->showLoggedInPage = true;
                }
                //If coockie data was wrong, unseting cookie
                else {
                    $msgId = 3;
                    $this->model->setMessage($msgId);
                    $this->setMessage();
                    $this->loginView->unsetCookies();
                }

            }
            //if cookie data is expired.
            else {
                $msgId = 3;
                $this->model->setMessage($msgId);
                $this->setMessage();
                $this->loginView->unsetCookies();

            }
        }

    }
     
    public function goRegisterPage() {
        if ($this->loginView->didUserPressGoToRegisterPage()) {
            $this->showRegisterPage = true;
        }
    }

    public function returnLoginPage() {
        if ($this->registerView->didUserPressReturnToLoginPage()) {
            $this->showRegisterPage = false;
        }
    }

    public function renderPage() {
        if ($this->showLoggedInPage) {
            $this->htmlView->echoHTML($this->loggedInView->showLoggedInPage());    
        }

        else {
            if ($this->showRegisterPage) {
                $this->htmlView->echoHTML($this->registerView->showRegisterPage());
            }
            else {
                $this->htmlView->echoHTML($this->loginView->showLoginpage());
            }
        }
    }


    //User registration.
    public function registerNewUser() {
       
        if ($this->registerView->didUserPressSubmit()) {
           
            $validationErrors = 0;
            $username = $this->registerView->getUsername();            
            $password = $this->registerView->getPassword();
            $confirmPassword = $this->registerView->getConfirmPassword();

            if($this->validateUsername->validateUsernameLength($username) == false) {
                $msgId = 8;
                $validationErrors++;
                $this->model->setMessage($msgId);
                $this->setMessage();
            }
            
            else {
                
                if($this->validateUsername->validateCharacters($username) == false) {
                    $msgId = 4;
                    $validationErrors++;
                    $this->model->setMessage($msgId);
                    $this->setMessage();                    
                }
            }

            if($this->validatePassword->validatePasswordLength($password, $confirmPassword) == false) {
                $msgId = 7;
                $validationErrors++;
                $this->model->setMessage($msgId);
                $this->setMessage();
            }
            else {
                
                if($this->validatePassword->validateIfSamePassword($password, $confirmPassword) == false) {
                    $msgId = 6;
                    $validationErrors++;
                    $this->model->setMessage($msgId);
                    $this->setMessage();
                }
            }

            if($validationErrors == 0) {
               
               $hash = $this->hash->crypt($password);
               $newUser = new User($username, $hash);

               if ($this->userRepository->exists($username) == false) {
               
                $this->userRepository->add($newUser);
                $msgId = 12;
                $this->model->setMessage($msgId);
                $this->setMessage();
                $this->showRegisterPage = false;
                $this->loginView->setRegister($username);                
               }
               
               else {
                $msgId = 5;
                $this->model->setMessage($msgId);
                $this->setMessage();
               }     
            }

        }
    }

    //Decides if user role is admin or not.
    public function isAdmin() {
        if ($this->model->isAdmin()) {
            return true;
        }
        return false;
    }

    public function setMessage() {
        $message = new LoginMessages($this->model->getMessage());

        if (!$this->model->isLoggedIn()) {
            if ($this->showRegisterPage) {
                $this->registerView->setMessage($message->getMessage());
            }            
            $this->loginView->setMessage($message->getMessage());
        }
        else{ 
            $this->loggedInView->setMessage($message->getMessage());
        }
    }

    public function setUsername() {
        $this->username = $this->loginView->getUsername();
    }


    public function encryptPassword() {
        $this->loginView->setEncryptedPassword($this->model->encryptedPassword($this->loginView->getPassword()));
    }

    public function setPassword() {
        $this->password = $this->loginView->getPassword();
    }

    public function getId() {
        return $this->model->getId();
    }

    public function getUserAgent2() {
        return $this->userAgent2;
    }

    public function getCookieExpireTime() {
        $this->loginView->setCookieExpireTime($this->model->getCookieExpireTime());
    }

    public function setDecryptedPassword() {
        $this->password = $this->model->decryptPassword($this->loginView->getCookiePassword());
    }

    public function getUserAgent() {
        return $this->userAgent;
    }

    
}