<?php

class QuizList {

	private $quizList;

	public function __construct() {
		$this->quizList = array();
	}

	public function add(Quiz $quiz) {
		$this->quizList[] = $quiz;
	}

	public function toArray() {
		return $this->quizList;
	}

}