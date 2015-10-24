 <?php

require_once("View/BaseView.php");
require_once("Model/QuizModel.php");

class QuizGameView extends BaseView {

	private $userAnswers = array();
	private $quizModel;
	private $questionModel;
	private $submitQuizLocation = 'submitQuiz';
	private $answersLocation = 'answers';

	public function __construct() {
		$this->quizModel = new QuizModel();
		$this->questionModel = new QuestionModel();
	}

	//Displays all availible stored quizes from database.
	public function showAllQuizToPlay() {
		
		$html = "</br>
		<a href='?'>Tillbaka</a>
		</br> </br>
		<legend>Välj ett quiz:</legend>
		<ul style='list-style-type: none;'>";

		//Gets all quizes from database.
		$quizList = $this->quizModel->getAvalibleQuizes();

		//Loops through the array of quizes and displays one by one in a list.
		foreach ($quizList->ToArray() as $quiz) {
			$html .= "<li><a href='?$this->playQuizLocation=" . $quiz->getQuizId() . "'>" . $quiz->getName() . "</a></li>";
		}

		return $html .= "</ul>";
	}

	//Displays chosen quiz from list.
	public function showPlayQuiz($quizId) {

		//Gets specific quiz chosen by user.
		$quiz = $this->quizModel->getQuiz($quizId);
		$questions = $quiz->getQuestions();

		$questionNr = 1;

		$html = "
		</br>
		<a href='?$this->showAllQuizToPlayLocation' >Tillbaka</a>
		<h2>" . $quiz->getName() . "</h2>
		<form action ='' method='post'>";

			//Loops through the array to get all questions for chosen quiz.
			foreach ($questions->ToArray() as $question) {
				$question = $this->questionModel->getQuestion($question->getQuestionId());

				//Loops through the questions to get the availible answers for each question in the array.
				foreach ($question->toArray() as $answer) {
					$nr = 0;

					if ($answer != null) {
						$html .= "<h4>$questionNr.&nbsp;" . $question->getName() . "</h4>";
					}

					//Loops through and gets the question alternatives and displays them. 
					foreach ($answer->getAnswers() as $answerName) {
						$label = 'question-' . $questionNr . '-answers-'. $this->alphabets[$nr];

						$html .=
						"<div>
						<input type='radio' name='answers[$questionNr]' id='$label' value='" . $this->alphabets[$nr] . "'> 
						<small><label for='$label'>" . $this->alphabets[$nr] . ") " . $answerName . "</label></small>
						</div>";
						
						$nr++;
					}
				}

				$questionNr++;
			}

		return $html .= "
		</br>
		<input type='submit' class='btn btn-default' name='$this->submitQuizLocation' value='Skicka quiz' />
		</form>
		</br>
		";
	}

	//Displays the user scores after played quiz game.
	public function showResult ($score = 0, $quizId) {

		$userAnswers = $this->getUserAnswers();
		$quiz = $this->quizModel->getQuiz($quizId);
		$questions = $quiz->getQuestions();		

		$questionNr = 1;

		$html = "
		</br>
		<a href='?$this->showAllQuizToPlayLocation' >Tillbaka</a>
		</br>
		<a href='?$this->playQuizLocation=$quizId' >Spela igen</a>
		<h1>" . $quiz->getName() . "</h1>";

		if ($userAnswers > 0) {

			foreach ($questions->ToArray() as $questionObj) {
				
				if (isset($userAnswers[$questionNr])) {	
					$html .= "<h3>$questionNr. " . $questionObj->getName() . "</h3>";
					$question = $this->questionModel->getQuestion($questionObj->getQuestionId());

					foreach ($question->toArray() as $answer) {
						$num = $this->getNumber($userAnswers[$questionNr]);
						
						if ($userAnswers[$questionNr] != $answer->getRightAnswer()) {

						 	$label = 'question-' . $questionNr . '-answers-'. $answer->getRightAnswer();
						 	$html .= "<div>
						 	<input type='radio' name='answers[$questionNr]' id='$label' value='" . $answer->getAnswer($num) . "' disabled>
						 	<small><label style='color :red;' for='$label'>" . $userAnswers[$questionNr] . ") " . $answer->getAnswer($num) .  "</label></small>
					     	</div>";
	        			} 

	        			else {

	        			 	$label = 'question-' . $questionNr . '-answers-'.  $answer->getRightAnswer();
	            		 	$html .= "<div>
					 		<input type='radio' name='answers[$questionNr]' id='$label' value='" . $answer->getAnswer($num) . "' disabled>
					 		<small><label style='color: green;' for='$label'>" . $userAnswers[$questionNr] . ") " . $answer->getAnswer($num) .  "</label></small>
					 		</div>";
	        			}
					}
				}

				$questionNr++;
			}
		}

		$numOfQuestions = $this->quizModel->numOfQuestions($questions);

		return $html .= "
		</br>
		Ditt resultat är $score av totalt $numOfQuestions</br></br>";
	}

	public function hasUserSubmitQuiz () {

		if (isset($_POST[$this->submitQuizLocation])) {
			return true;
		}
		return false;
	}

	/*Gets a specific number based on the questions char 
	alternative (a,b,c) as in the alphabets array.*/	
	public function getNumber($string) {
		
		if ($string == $this->alphabets[0]) {

			return 0;
		}	

		if ($string == $this->alphabets[1]) {

		    return 1;
		}	

		if ($string == $this->alphabets[2]) {
		    return 2;
		}		
	}

	public function hasChosenQuiz() {
		
		if (isset($_GET[$this->playQuizLocation])) {
			return true;
		}		
		return false;
	}


	public function getChosenQuiz() {
		
		if (isset($_GET[$this->playQuizLocation])) {
			return $_GET[$this->playQuizLocation];
		}
	}

	public function didUserPressShowAllQuizToPlay() {
		
		if (isset($_GET[$this->showAllQuizToPlayLocation])) {
			return true;
		}
		return false;
	}

	public function getUserAnswers() {
		
		if(isset($_POST[$this->answersLocation])) {
			return $_POST[$this->answersLocation];
		}
	}
		
}
