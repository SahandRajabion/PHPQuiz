<?php 

require_once("Model/Quiz.php");
require_once("Model/Dao/QuizRepository.php");
require_once("Model/Result.php");
require_once("Model/QuestionModel.php");


class QuizModel{


	private $quizRepository;	


	public function __construct() {

		
		$this->questionModel = new QuestionModel();
		$this->quizRepository = new QuizRepository();
	}

	//Gets the number of questions for a quiz.
	public function numOfQuestions(Questions $questions) {
		
		$num = 0;
		
		foreach ($questions->toArray() as $question) {
			$question = $this->questionModel->getQuestion($question->getQuestionId());
			
			foreach ($question->toArray() as $answer) {
			
				if ($answer != null) {
					$num++;
				}	
			}
		}
		return $num;	
	}

	//Validates a played quiz from database to calculate score.
	public function validateQuiz($userAnswers, $quizId) {
		$score = 0;
		$quiz = $this->getQuiz($quizId);
		$questions = $quiz->getQuestions();		
		$nr = 1;

		foreach ($questions->toArray() as $questionObj) {
			$question = $this->questionModel->getQuestion($questionObj->getQuestionId());

			foreach ($question->toArray() as $answer) {
				if (isset($userAnswers[$nr]) == true) {
					if ($userAnswers[$nr] == $answer->getRightAnswer()) {
						$score++;
					}
				}
			}

			$nr++;
		}

		return $score;
	}

	//Saves taken quiz results.
	public function saveQuizResult(Result $result) {
		$this->quizRepository->saveQuizResult($result);
	}

	//Checks if quiz already exists in database(created quiz name).
	public function quizExists($quizName) {
		return $this->quizRepository->quizExists($quizName);
	}

	//Adds new created quiz to database.
	public function addQuiz(Quiz $quiz) {
		$this->quizRepository->addQuiz($quiz);
	}

	//Gets quiz results stored in database for specific user.
	public function getQuizResults($userId) {
		return $this->quizRepository->getQuizResults($userId);
	}

	//Gets all availible quizes.
	public function getAvalibleQuizes() {
		return $this->quizRepository->getAvalibleQuizes();
	}

	//Gets a list of all quizes from database.	
	public function getAllQuiz() {
		return $this->quizRepository->getQuizList();
	}

	//Deletes a quiz.
	public function removeQuiz(Quiz $quiz) {
		$this->quizRepository->removeQuiz($quiz);
	}

	//Saves changes on a altered quiz.
	public function saveEditQuiz(Quiz $quiz) {
		$this->quizRepository->saveEditQuiz($quiz);
	}

	//Gets specific quiz from database.
	public function getQuiz($quizId) {
		return $this->quizRepository->getQuiz($quizId);
	}

	
}