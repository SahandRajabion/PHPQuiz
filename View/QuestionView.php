<?php

require_once("View/BaseView.php");
require_once("Model/Quiz.php");
require_once("helper/CookieStorage.php");
require_once("View/QuizView.php");


class QuestionView extends BaseView {

	private $editQuestionLocation = 'editQuestion';
	private $saveEditQuestionLocation = 'saveEditQuestion';
	private $questionName = 'questionName';
	private $removeQuestionLocation = 'removeQuestion';
	private $confirmRemoveQuestionLocation = 'confirmRemoveQuestion';

	public function __construct() {

		$this->cookieStorage = new CookieStorage();
		$this->quizView = new QuizView();	
	}


	//Displays add new question form in admin panel.
	public function showAddQuestionForm(Quiz $quiz) {

		$message = $this->renderCookieMessage($this->messageLocation);

		$userUnique = $quiz->getQuizId();
		$html = "

		</br>

		<a href='?" . $this->showQuizLocation . "&" . $this->id . "=" . $quiz->getQuizId() . "' name='returnToPage'>Tillbaka</a>
		</br> </br>
		<legend>Lägg till en fråga till " . $quiz->getName() . "</legend>
		$message 
		<form action='' method='post'>
		<input type='text' name='" . $this->questionName . "' maxlength='150'/>
		</br>
		</br>
		<input type='submit' class='btn btn-default' name='" . $this->addQuestionLocation . "' value='Lägg till' />
		</form>";

		return $html;
	}

	//Displays questions in admin panel.	
	public function showQuestion(Question $question) {

		
		$message = $this->renderCookieMessage($this->messageLocation);
		$i = 0;

		$html = "

		<form action='' method='post'>

		</br>

		<a href='?" . $this->showQuizLocation . "&" . $this->id . "=" . $question->getQuizId() . "' name='returnToPage'>Tillbaka</a> </br> 
		<h2>" . $question->getName() . "</h2>
		<input type='submit' class='btn btn-default' name='" . $this->editQuestionLocation . "' value='Redigera frågan'> <input type='submit' class='btn btn-default' name='" . $this->removeQuestionLocation . "' value='Radera frågan'>
		</br></br>

		<legend>Svar:</legend>
		$message
		<ul style='list-style-type: none;'>";

		if (count($question->toArray()) > 0) {

			foreach($question->toArray() as $answersObj) {

				foreach ($answersObj->getAnswers() as $answerName) {

					$html .= "<li> " . $this->alphabets[$i] . ") " . $answerName ."</li> ";

					$i++;
				}
			}

			$html .= "Rätt svar: <strong style='color: green;'>" . $answersObj->getRightAnswer() . "</strong>";
		}

		if (count($question->toArray()) > 0) {

			$html .= "</ul><input type='submit' class='btn btn-default' name='" . $this->removeAnswersLocation . "' value='Radera svar'></form>";			
		}

		else {

			$html .= "</ul>" . $this->getQuestionMenu($question->getQuestionId()) . "</form>";			
		}

		return $html;		
	}

	//Displays edit question form in admin panel.
	public function showEditQuestionForm(Question $question) {
		
		return $html = "

		</br>

		<a href='?" . $this->showQuestionLocation . "&" . $this->id . "=" . $question->getQuestionId() . "' name='returnToPage'>Tillbaka</a>
		</br>
		</br>
		<legend>Redigera " 	. $question->getName() . "</legend>
		<form action='' method='post'>
		<input type='text' name='" . $this->questionName . "' value='" . $question->getName() . "' maxlength='150'>
		</br>
		</br>
		<input type='submit' class='btn btn-default' name='" . $this->saveEditQuestionLocation . "' value='Spara ändringar'>
		</form>
		 ";
	}

	//Displays Confirmation of deleting a question. 
	public function showConfirmToRemoveQuestion(Question $question) {
		
		return $html = "

		</br>

		<a href='?" . $this->showQuestionLocation . "&" . $this->id . "=" . $question->getQuestionId() . "' name='returnToPage'>Tillbaka</a>

		</br>
		</br>

		<legend>Radera " . $question->getName() . "</legend>

		<form action='' method='post'>
		<input type='submit' class='btn btn-default' name='" . $this->confirmRemoveQuestionLocation . "' value='Ta bort?'>
		</form>";
	}	

	public function getQuestionName() {
		if (isset($_POST[$this->questionName])) {
			return $_POST[$this->questionName];
		}
	}

	public function getId() {
		if (isset($_GET[$this->id])) {
			return $_GET[$this->id];
		}
		return NULL;
	}			

	

	public function didUserPressToShowQuestion() {
		if (isset($_GET[$this->showQuestionLocation])) {
			return true;
		}
		return false;
	}

	public function didUserConfirmToRemoveQuestion() {
		if (isset($_POST[$this->confirmRemoveQuestionLocation])) {
			return true;
		}
		return false;
	}

	public function didUserPressToRemoveQuestion() {
		if (isset($_POST[$this->removeQuestionLocation])) {
			return true;
		}
		return false;
	}

	public function didUserPressToEditQuestion() {
		if (isset($_POST[$this->editQuestionLocation])) {
			return true;
		}
		return false;
	}

	public function didUserPressToAddQuestion() {
		if (isset($_GET[$this->addQuestionLocation])) {
			return true;
		}
		return false;
	}	

	public function didUserPressToSaveEditQuestion() {
		if (isset($_POST[$this->saveEditQuestionLocation])) {
			return true;
		}
		return false;
	}

	public function didUserSubmitAddQuestion() {
		if (isset($_POST[$this->addQuestionLocation])) {
			return true;
		}
		return false;
	}

	public function getQuestionMenu($id) {
		return $html = "<a href='?" . $this->addAnswersLocation . "&" . $this->id . "=$id' class='btn btn-default'>Lägg till svar</a>";
	}		
	
	public function renderCookieMessage($string) {
		$value = $this->cookieStorage->load($string);
		$this->quizView->unsetMessage($string);	
		return $value;
	}		
}