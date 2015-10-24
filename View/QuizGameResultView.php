<?php

require_once("View/BaseView.php");
require_once("Model/QuizModel.php");

class QuizGameResultView extends BaseView {

	private $quizModel;

	public function __construct() {

		$this->quizModel = new QuizModel();
	}

	//Displays all stored scores from database for user.
	public function showMyResults($userId) {
		$results = $this->quizModel->getQuizResults($userId);

		$html = "

		</br>
		<a href='?' name='returnToPage'>Tillbaka</a> </br> </br>
		<legend>Mina resultat</legend>

		<ul style='list-style-type: none;'>";

		if ($results == NULL) {
			$html .= "Du har inga resultat";
		}

		else {

			foreach($results as $result) {
				$quiz = $this->quizModel->getQuiz($result->getQuizId());
				$html .= "<li><h4>" . $quiz->getName() . "</h4>" . $result->getScore() . " rätt svar av totalt ". $result->getNumOfQuestions() ."</li>";
			}
		}

		$html .= "</ul>";

		return $html;	
	}

	public function didUserPressGoToShowResults() {
		
		if (isset($_GET[$this->showResultsLocation])) {
			return true;
		}
		return false;
	}
	
}