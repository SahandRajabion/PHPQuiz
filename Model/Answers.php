<?php

class Answers {
	
	private $answers = array();
	private $questionId;
	private $rightAnswer;
	private $answerId;
	
	public function __construct($answerA, $answerB, $answerC , $rightAnswer, $questionId, $answerId = NULL) {

		
	    $this->rightAnswer = $rightAnswer;	
	    $this->questionId = $questionId;

		$this->answers[] = $answerA;
		$this->answers[] = $answerB;		
	    $this->answers[] = $answerC;

		
		//Validate before assigning value.
	 	if (empty($answerId) == false) {
	 		
	 		$this->answerId = $answerId;
	 	}
	}
	
	public function getQuestionId() {
		return $this->questionId;
	}

	public function getRightAnswer() {
		return $this->rightAnswer;
	}

	public function getAnswerId() {
		return $this->answerId;
	}

	public function getAnswer($i) {
		return $this->answers[$i];
	}

	public function getAnswers() {
		return $this->answers;
	}
}