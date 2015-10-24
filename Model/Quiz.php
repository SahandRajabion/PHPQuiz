<?php

require_once("Model/Questions.php");

class Quiz {

	private $questions;
	private $id;
	private $name;

	public function __construct($quizName, $quizId = NULL) {
		
		$this->name = $quizName;
		$this->questions = new Questions();
		
		//Validate before assigning value.
		if (empty($quizId) == false || !empty($quizId)) {
			$this->id = $quizId;
		}
	}

	public function getName() {
		return $this->name;
	}

	public function getQuizId() {
		return $this->id;
	}

	public function add(Question $question) {
		$this->questions->add($question);
	}

	public function getQuestions() {
		return $this->questions;
	}
}