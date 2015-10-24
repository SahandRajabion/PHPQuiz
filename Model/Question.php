<?php

class Question {

	private $name;
	private $answers;
	private $questionId;
	private $quizId;
	
	
	public function __construct($questionName, $quizId, $questionId = NULL) {

		$this->name = $questionName;
		$this->quizId = $quizId;
		$this->answers = array();
		
		//Validate before assigning value.
		if (empty($questionId) == false) {

			$this->questionId = $questionId;
		}
	}

	public function getQuestionId() {
		return $this->questionId;
	}

	public function add(Answers $answer) {
			$this->answers[] = $answer;
	}

	public function toArray() {
		return $this->answers;
	}

	public function getQuizId() {
		return $this->quizId;
	}

	public function getName() {
		return $this->name;
	}
}