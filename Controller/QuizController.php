<?php

require_once("View/QuizGameView.php");
require_once("View/HTMLView.php");
require_once("Model/QuizModel.php");
require_once("View/QuizView.php");
require_once("Model/Quiz.php");
require_once("Model/Validation/ValidateInput.php");
require_once("Model/Dao/QuizRepository.php");
require_once("View/QuizMessages.php");
require_once("View/QuizGameResultView.php");
require_once("Model/Result.php");

class QuizController {

	private $quizGameView;
	private $htmlView;
	private $quizModel;
	private $quizView;
	private $quizMessage;
	private $cookieStorage;
	private $quizGameResultView;

	public function __construct() {
		
		$this->quizGameView = new QuizGameView();
		$this->htmlView = new HTMLView();
		$this->quizModel = new QuizModel();
		$this->quizView = new QuizView();
		$this->quizRepository = new QuizRepository();
		$this->validateInput = new ValidateInput();
		$this->quizGameResultView = new QuizGameResultView();
	}

    //Displays user results for played quiz.
	public function showMyResults($userId) {
		 $this->htmlView->echoHTML($this->quizGameResultView->showMyResults($userId)); 
	}

    //Displays alla availible quizes.
	public function showAllQuizToPlay() {
			$this->htmlView->echoHTML($this->quizGameView->showAllQuizToPlay());
	}

    //Saves altered quiz data.
	public function saveEditQuiz() {
		$currentQuiz = $this->quizRepository->getQuiz($this->quizView->getId());
		$quizName = $this->quizView->getQuizName();
		if ($currentQuiz != null) {
			if ($currentQuiz->getName() != $quizName) {
				if ($this->validation($quizName)) {
					if ($this->quizRepository->quizExists($quizName) == false) {
						$editQuiz = new Quiz($quizName, $this->quizView->getId());
						$this->quizModel->saveEditQuiz($editQuiz);
						$this->quizMessage = new QuizMessages(2);
						$message = $this->quizMessage->getMessage();
						$this->quizView->saveMessage($message);
					}
					else {
						$this->quizMessage = new QuizMessages(9);
						$message = $this->quizMessage->getMessage();
						$this->quizView->saveMessage($message);					
					}
				}
			}
		}

		$this->quizView->redirectToShowAllQuiz();	
	}

	//Delete quiz.
	public function removeQuiz() {
		$quiz = $this->quizRepository->getQuiz($this->quizView->getId());
		$this->quizModel->removeQuiz($quiz);
		$this->quizMessage = new QuizMessages(1);
		$message = $this->quizMessage->getMessage();
		$this->quizView->saveMessage($message);
		$this->quizView->redirectToShowAllQuiz();	
	}

 	//Confirmation page for delete quiz.
	public function confirmRemoveQuiz() {
		$quiz = $this->quizRepository->getQuiz($this->quizView->getId());
		$this->htmlView->echoHTML($this->quizView->showConfirmToRemoveQuiz($quiz));
	}


	//Validates input.
	public function validation($quizName) {
		if ($this->validateInput->validateLength($quizName) == false) {
				$this->quizMessage = new QuizMessages(10);
				$message = $this->quizMessage->getMessage();
				$this->quizView->saveMessage($message);
				$this->quizView->redirectToShowCreateQuizForm();
				return false;
		}

		if ($this->validateInput->validateCharacters($quizName) == false) {
				$this->quizMessage = new QuizMessages(11);
				$message = $this->quizMessage->getMessage();
				$this->quizView->saveMessage($message);
				return false;
		}		

		return true;
	}


   
	//User play chosen quiz.
	public function playQuiz($userId) {
		if ($this->quizGameView->hasUserSubmitQuiz()) {
			$chosenQuiz = $this->quizGameView->getChosenQuiz();
			$userAnswers = $this->quizGameView->getUserAnswers();
			$score = $this->quizModel->validateQuiz($userAnswers, $chosenQuiz);

			$quiz = $this->quizModel->getQuiz($chosenQuiz);
			$questions = $quiz->getQuestions();
			
			$quizResult = new Result($score, $this->quizModel->numOfQuestions($questions), $userId, $chosenQuiz);
			$this->quizModel->saveQuizResult($quizResult);
			$this->htmlView->echoHTML($this->quizGameView->showResult($score, $chosenQuiz));
		}
		else {
			$this->htmlView->echoHTML($this->quizGameView->showPlayQuiz($this->quizGameView->getChosenQuiz()));
		}
	}



    //Create new quiz for admins.
	public function createQuiz() {
		if ($this->quizView->didUserPressToSubmitCreateQuiz()) {
			$quizName = $this->quizView->getQuizName();

			if ($this->quizModel->quizExists($quizName) == false) {
				if ($this->validation($quizName)) {
					$quiz = new Quiz($quizName);
					$this->quizModel->addQuiz($quiz);

					$this->quizMessage = new QuizMessages(0);
					$message = $this->quizMessage->getMessage();
					$this->quizView->saveMessage($message);
					$this->quizView->redirectToShowAllQuiz();
				}
				else {
					$this->quizView->redirectToShowCreateQuizForm();
				}
			}
			else {
				$this->quizMessage = new QuizMessages(9);
				$message = $this->quizMessage->getMessage();
				$this->quizView->saveMessage($message);
				$this->quizView->redirectToShowCreateQuizForm();
			}		
		}
		else {
			$this->htmlView->echoHTML($this->quizView->showCreateQuizForm());
		}
 	}

	public function showAllQuiz() {
		$this->htmlView->echoHTML($this->quizView->showAll($this->quizRepository->getQuizList()));
	}

    //Edit quiz.
	public function editQuiz(Quiz $quiz) {
		$this->htmlView->echoHTML($this->quizView->showEditQuizForm($quiz));
	}

    
	public function showQuiz(Quiz $quiz) {
 		 $this->htmlView->echoHTML($this->quizView->showQuiz($quiz));
 	}
 	
	
}