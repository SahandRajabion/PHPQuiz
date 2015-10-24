<?php

require_once("Model/Dao/Repository.php");
require_once("Model/Answers.php");


class AnswerRepository extends Repository {
	
	private $db;

	public function __construct() {

		$this->dbTable = 'answer';
		$this->db = $this->connection();
	}


	public function getAnswers($questionId) {
		
		$sql = "SELECT * FROM $this->dbTable WHERE " . $this->questionId . " = ?";
		$params = array($questionId);
		$query = $this->db->prepare($sql);
		$query->execute($params);
		$answerObj = $query->fetch();

		if ($answerObj != null) {

			$answers = new Answers($answerObj[$this->answerA], $answerObj[$this->answerB], $answerObj[$this->answerC], $answerObj[$this->rightAnswer], $answerObj[$this->questionId], $answerObj[$this->answerId]);

			return $answers;
		}

		return null;
	}

	//Adds the created answer to database.
	public function addAnswers(Answers $answers) {

		$sql = "INSERT INTO $this->dbTable (" . $this->answerA . ", " . $this->answerB . ", " . $this->answerC . ", " . $this->rightAnswer . ", " . $this->questionId . ") VALUES (?,?,?,?,?)";
		$params = array($answers->getAnswer(0), $answers->getAnswer(1), $answers->getAnswer(2), $answers->getRightAnswer(), $answers->getQuestionId());
		$query = $this->db->prepare($sql);
		$query->execute($params);
	}

	//Deletes answer from database.
	public function removeAnswers(Answers $answers) {
		
		$sql = "DELETE FROM $this->dbTable WHERE " . $this->answerId . " = ?";
		$params = array($answers->getAnswerId());
		$query = $this->db->prepare($sql);
		$query->execute($params);
	}

}