<?php

require_once("Model/Question.php");
require_once("Model/Dao/QuestionRepository.php");


class QuestionModel {

	private $questRepository;

	public function __construct() {

		$this->questRepository = new QuestionRepository();
	}

	public function getQuestion($questionId) {
		return $this->questRepository->getQuestion($questionId);
	}

	public function saveEditQuestion(Question $question) {
		$this->questRepository->saveEditQuestion($question);
	}	

	public function addQuestion(Question $question) {
		$this->questRepository->addQuestion($question);
	}

	public function removeQuestion(Question $question) {
		$this->questRepository->removeQuestion($question);
	}

	
}