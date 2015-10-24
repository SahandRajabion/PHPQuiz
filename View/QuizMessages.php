<?php

class QuizMessages {

    private $messageId;

    private $messages = array('Quizen har skapats',
     'Quizen har tagits bort', "Quizen har ändrats",'Fråga har tagits bort',
      "Fråga har ändrats", "Fråga har lagts till", "Svar har lagts till", 
      "Svar har ändrats", "Svar har tagits bort", "Quizens namn finns redan", 
      "Quizens namn måste minst ha 3 tecken", "Quizens namn får inte innehålla ogiltiga tecken",
      "Fråga måste ha minst 3 tecken", "Fråga får inte innehålla ogiltiga tecken",
      "Fråga finns redan", "Måste bocka i ett rätt svar",
      "Alla svars alternativ måste ha minst 3 tecken", "Inga svars alternativ får inte innehålla ogiltiga tecken");
                              

    public function __construct($messageId) {

        $this->messageId = $messageId;
    }

   //Gets message and displays depending on message id. 
    public function getMessage() {

        $message = $this->messages[$this->messageId];

		if($this->messageId > 8) {

            $alert = "<div class='alert alert-danger alert-error'>";
        }   
        else {
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