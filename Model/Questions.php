	<?php

class Questions {

	private $quizQuestions;

	public function __construct() {
		
		$this->quizQuestions = array();
	}
	
	public function add(Question $question) {
			$this->quizQuestions[] = $question;
	}

	public function toArray() {
		return $this->quizQuestions;
	}

}