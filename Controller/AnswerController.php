<?php

require_once("View/QuizMessages.php");
require_once("View/BaseView.php");
require_once("View/AnswerView.php");
require_once("Model/Dao/AnswerRepository.php");
require_once("Model/AnswerModel.php");
require_once("View/HTMLView.php");
require_once("Model/Validation/ValidateInput.php");
require_once("Model/Answers.php");
require_once("Model/Dao/QuestionRepository.php");



class AnswerController {

	
	private $validateInput;
	private $answerRepository;
	private $questionRepository;
	private $cookieValA = "cookieValA";
	private $cookieValB = "cookieValB";
	private $cookieValC = "cookieValC";
	private $answerView;
	private $quizMessage;
	private $quizView;

	public function __construct() {
		
		$this->quizView = new QuizView;
		$this->validateInput = new ValidateInput();
		$this->answerRepository = new AnswerRepository();
		$this->htmlView = new HTMLView();
		$this->answerModel = new AnswerModel();
		$this->answerView = new AnswerView();
		$this->questionRepository = new QuestionRepository();
		
	}

	//Input validation
	public function validate($string) {
		if ($this->validateInput->validateLength($string) == false) {
				$this->quizMessage = new QuizMessages(16);
				$message = $this->quizMessage->getMessage();
				$this->quizView->saveMessage($message);
				$this->answerView->redirectToAddAnswers($this->answerView->getId());		
				return false;
		}

		if ($this->validateInput->validateCharacters($string) == false) {
				$this->quizMessage = new QuizMessages(17);
				$message = $this->quizMessage->getMessage();
				$this->quizView->saveMessage($message);
				$this->answerView->redirectToAddAnswers($this->answerView->getId());	
				return false;
		}
		return true;
	}

	//Checks if question has valid answers or not.
	public function hasNoAnswers() {
		$question = $this->questionRepository->getQuestion($this->answerView->getId());
		if (count($question->toArray()) > 0) {
			return false;
		}
		return true;
	}

    //The answers are saved to cookie to show msg after a header location.
	public function rememberAnswers($answerA, $answerB, $answerC) {
		$this->quizView->saveValueMessage($this->cookieValA, $answerA);
		$this->quizView->saveValueMessage($this->cookieValB, $answerB);
		$this->quizView->saveValueMessage($this->cookieValC, $answerC);
	}	



	//Displays the confirmation page for delete.
	public function confirmRemoveAnswers() {
		$answers = $this->answerRepository->getAnswers($this->answerView->getId());
		$this->htmlView->echoHTML($this->answerView->displayConfirmRemoveAnswers($answers));
	}


    //Adding answer to question
	public function addAnswers() {
		if ($this->answerView->hasSubmitAddAnswers() == false) {
			$question = $this->questionRepository->getQuestion($this->answerView->getId());
			$this->htmlView->echoHTML($this->answerView->showAddAnswersForm($question));			
		}
		
		else {

			$answerA = $this->answerView->getAnswerA();
			$answerB = $this->answerView->getAnswerB();
			$answerC = $this->answerView->getAnswerC();

			if ($this->validate($answerA) && $this->validate($answerB) && $this->validate($answerC)) {
				$checkedStatus = $this->answerView->getRightAnswerCheckBox();

				if (empty($checkedStatus)) {

					$this->quizMessage = new QuizMessages(15);
					$message = $this->quizMessage->getMessage();
					$this->quizView->saveMessage($message);
					$this->rememberAnswers($answerA, $answerB, $answerC);
					$this->answerView->redirectToAddAnswers($this->answerView->getId());	
				} 
				else {

					$answers = new Answers($answerA, $answerB, $answerC, $this->answerView->getRightAnswerCheckBox(), $this->answerView->getId());
					$this->answerModel->addAnswers($answers);
					$this->quizMessage = new QuizMessages(6);
					$message = $this->quizMessage->getMessage();
					$this->quizView->saveMessage($message);
					$this->quizView->redirectToShowQuestion($this->answerView->getId());	
				}
			}
			else {		

				$this->rememberAnswers(strip_tags($answerA), strip_tags($answerB), strip_tags($answerC));
			}			
		}
	}

    

    //Delete answers.
	public function removeAnswers() {
		$answers = $this->answerRepository->getAnswers($this->answerView->getId());
		$this->answerModel->removeAnswers($answers);
		$this->quizMessage = new QuizMessages(8);
		$message = $this->quizMessage->getMessage();
		$this->quizView->saveMessage($message);
		$this->quizView->redirectToShowQuestion($this->answerView->getId());		
	}		


}