<?php

class Result {

	private $userId;
	private $quizId;
	private $resultId;
	private $numOfQuestions;
	private $score;
	


	public function __construct($score, $numOfQuestions, $userId, $quizId, $resultId = NULL) {
		
		$this->userId = $userId;
		$this->quizId = $quizId;
		$this->resultId = $resultId;
		$this->numOfQuestions = $numOfQuestions;
		$this->score = $score;
		
	}

	public function getUserId() {
		return $this->userId;
	}

	public function getQuizId() {
		return $this->quizId;
	}

	public function getResultId() {
		return $resultId;
	}

	public function getNumOfQuestions() {
		return $this->numOfQuestions;
	}

	public function getScore() {
		return $this->score;
	}
	
}