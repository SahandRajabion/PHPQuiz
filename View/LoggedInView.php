<?php

require_once("View/BaseView.php");
require_once("./Model/LoginModel.php");
require_once("./View/LoginView.php");



class LoggedInView extends BaseView {
    

    private $adminNavigation;
    private $model;
    private $msg;
    private $username;
    private $menuNav;
    

    public function __construct() {

        $this->model = new LoginModel();
    }

    //Set feedback message.
    public function setMessage($message) {
        $this->msg = $message;
    }

    //Displays logged in page.
    public function showLoggedInPage() {
        
        $this->username = $this->model->getUsername();

        //If Admin, then show extra functionalities in menu.
        if ($this->model->isAdmin()) {
            $this->menuNav .= "<li><a name='" . $this->showAllQuizLocation . "' href='?" . $this->showAllQuizLocation . "'>Admin funktioner</a></li>";
        }

        $html = "
            </br></br>
            <h4>$this->username är inloggad</h4>    
            
            <nav class='navbar navbar-default' role='navigation'>
           
            <div class='navbar-header'>
              <button type='button' class='navbar-toggle' data-toggle='collapse' 
                 data-target='#example-navbar-collapse'>
                 <span class='sr-only'>Toggle navigation</span>
                 <span class='icon-bar'></span>
                 <span class='icon-bar'></span>
                 <span class='icon-bar'></span>
              </button>
              <a class='navbar-brand'>MovieQuiz</a>
           </div>
          
           <div class='collapse navbar-collapse' id='example-navbar-collapse'>
              <ul class='nav navbar-nav'>
                 <li><a name='Play' href='?". $this->showAllQuizToPlayLocation . "'>Välj ett quiz att spela</a></li>
                 <li><a name='" . $this->showResultsLocation . "' href='?" . $this->showResultsLocation . "'>Visa mina tidigare resultat</a></li>

                 $this->menuNav
                
                 <li><a name='logOut' href='?". $this->logOutLocation . "'>Logga ut</a></li>
              </ul>
           </div>
        </nav>

        $this->msg";

        return $html;
    }
 
    public function didUserPressLogOut() {

        if (isset($_GET[$this->logOutLocation])) {

            return true;
        }

        return false;

    }
}