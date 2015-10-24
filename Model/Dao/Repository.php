<?php

require_once("Settings.php");

abstract class Repository {
	
	protected $dbCon;
    protected $dbTable;
	protected $numberOfQuestions = "NumberOfQuestions";
	protected $questionName = 'QuestionName';
	protected $answerId = "AnswerId";
	protected $rightAnswer = "RightAnswer";
	protected $questionId = "QuestionId"; 
	protected $result = "Result";
	protected $quizId = "QuizId";
	protected $answerA = "AnswerA";
	protected $answerB = "AnswerB";
	protected $answerC = "AnswerC";
	protected $quizName = "QuizName";
	protected $userId = "UserId";
	protected $resultId = "ResultId";

    protected function connection() {
    	if ($this->dbCon == null) {
            $this->dbCon = new PDO(Settings::$DB_CONNECTION, Settings::$DB_USERNAME, Settings::$DB_PASSWORD);
        
        $this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->dbCon;
    }
  }
}