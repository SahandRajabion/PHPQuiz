<?php

require_once("View/BaseView.php");
require_once("View/QuizView.php");
require_once("helper/CookieStorage.php");


class AnswerView extends BaseView {
	
	private static $answerA = "answerA";
	private static $answerB = "answerB";
	private static $answerC = "answerC";

	private $confirmRemoveAnswers = 'confirmRemoveAnswers';
	private static $rightAnswer = "rightAnswerCheckBox";


	public function __construct() {
		
		$this->cookieStorage = new CookieStorage();
		$this->quizView = new QuizView();
	}

	//Get the right answer from checkbox for added question.
	public function getRightAnswerCheckBox() {

		if (isset($_POST[self::$rightAnswer])) {
			return $_POST[self::$rightAnswer];
		}
	}

	//Confirmation form to remove answer.
	public function displayConfirmRemoveAnswers(Answers $answers) {

		return $html = "</br>
		<a href='?" . $this->showQuestionLocation . "&" . $this->id . "=" . $answers->getQuestionId() . "' name='returnToPage'>Tillbaka</a>
		</br>
		</br>
		<legend>Radera svarsalternativ</legend>

		<form action='' method='post'>
		<input type='submit' class='btn btn-default' name='" . $this->confirmRemoveAnswers . "' value='Ta bort'>
		</form>";
	}			

	
	//Admin form to add new answers to created question.
	public function showAddAnswersForm(Question $question) {		
		
		
		$answerA = $this->renderCookieMessage($this->messageALocation);
		$answerB = $this->renderCookieMessage($this->messageBLocation);
		$answerC = $this->renderCookieMessage($this->messageCLocation);
		$message = $this->renderCookieMessage($this->messageLocation);

		$html = "
		</br>
		<a href='?" . $this->showQuestionLocation . "&" . $this->id . "=" . $question->getQuestionId() . "' name='returnToPage'>Tillbaka</a>
		</br> </br>
		<legend>Lägg till svar till " . $question->getName() . "</legend> 
		$message
		<form action='' method='post'>
		<strong>" . $this->alphabets[0] . "</strong>)
		</br>
		<input type='radio' name='" . self::$rightAnswer . "' value='" . $this->alphabets[0] . "'>		
		<input type='text' name='" . self::$answerA . "' value='$answerA' maxlength='28'/>
		</br>
		</br>

		<strong>" . $this->alphabets[1] . "</strong>)
		</br>
		<input type='radio' name='" . self::$rightAnswer . "' value='" . $this->alphabets[1] . "'>
		<input type='text' name='" .  self::$answerB . "' value='$answerB' maxlength='28'/>
		</br>
		</br>		

		<strong>" . $this->alphabets[2] . "</strong>)
		</br>
		<input type='radio' name='" . self::$rightAnswer . "' value='" . $this->alphabets[2] . "'>
		<input type='text' name='" . self::$answerC . "' value='$answerC' maxlength='28'/>
		</br>
		</br>	

		<input type='submit' class='btn btn-default' name='" . $this->addAnswersLocation . "' value='Lägg till' />
		</form>";

		return $html;
	}

	//Redirects user to right answer to add new questions to.
	public function redirectToAddAnswers($questionId) {

		header("Location: ?" . $this->addAnswersLocation . "&" . $this->id . "=" . $questionId);		
	}			

	
	public function renderCookieMessage($string) {

		$value = $this->cookieStorage->load($string);
		$this->quizView->unsetMessage($string);	
		return $value;
	}

	public function didUserPressToRemoveAnswers() {

		if (isset($_POST[$this->removeAnswersLocation])) {
			return true;
		}
		return false;
	}

	public function didUserConfirmToRemoveAnswers() {

		if (isset($_POST[$this->confirmRemoveAnswers])) {
			return true;
		}
		return false;
	}
		
	public function hasSubmitAddAnswers() {

		if (isset($_POST[$this->addAnswersLocation])) {
			return true;
		}
		return false;
	}

	public function didUserPressToAddAnswers() {

		if (isset($_GET[$this->addAnswersLocation])) {
			return true;
		}
		return false;
	}

	//Get Answer A from admin form.
	public function getAnswerA() {

		if (isset($_POST[self::$answerA])) {
			return $_POST[self::$answerA];
		}		
	}

	//Get Answer B from admin form.
	public function getAnswerB() {

		if (isset($_POST[self::$answerB])) {
			return $_POST[self::$answerB];
		}		
	}

	//Get Answer C from admin form.
	public function getAnswerC() {

		if (isset($_POST[self::$answerC])) {
			return $_POST[self::$answerC];
		}		
	}

	public function getId() {

		if (isset($_GET[$this->id])) {
			return $_GET[$this->id];
		}
	}
		
}