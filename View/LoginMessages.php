<?php

class LoginMessages {

    
    private $messages = array('Användarnamnet saknas', 'Lösenordet saknas', "Felaktigt användarnamn och / eller lösenord",
                              "Felaktig information i lagrad cookie", 'Användarnamnet innehåller ogiltiga tecken',
                              'Användarnamnet är tyvärr upptaget, välj ett annat', 'Lösenorden matchar inte, försök igen', 'Lösenorden har få tecken. Minst 6 tecken.',
                              'Användarnamnet har för få tecken. Minst 3 tecken', "Inloggningen lyckades och vi kommer komma ihåg dig nästa gång", "Inloggningen lyckades", "Du är nu utloggad",
                              'Registreringen av ny användare lyckades', "Inloggningen lyckades via cookies"
    );
    private $messageId;

    public function __construct($messageId) {
        $this->messageId = $messageId;
    }

    //Gets message and displays depending on message id. 
    public function getMessage() {
        $message = $this->messages[$this->messageId];

        if($this->messageId < 9) {

            $alert = "<div class='alert alert-danger alert-error'>";
        }   
        else{

            $alert = "<div class='alert alert-success'>";
        }
        if(!empty($message)) {
          $ret = "
          $alert
          <a href='#' class='close' data-dismiss='alert'>&times;</a>        
					<p>$message</p>
					</div>";
        }
        else {

            $ret = "<p>$message</p>";
        }
        return $ret;
    }
}