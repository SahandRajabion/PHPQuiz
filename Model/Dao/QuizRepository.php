<?php
require_once("Model/Dao/Repository.php");
require_once("Model/Result.php");
require_once("Model/Quiz.php");
require_once("Model/QuizList.php");


class QuizRepository extends Repository{
	private $quizList;
	private $db;
	private $questionTable = "question";
	private $resultTable = "results";
	private $answerTable = "answer";
	private $results = array();

	public function __construct() {
		$this->dbTable = 'quiz';
		$this->quizList = new QuizList();
		$this->db = $this->connection();
	}

	//Gets all availible quizess containing questions.
	public function getAvalibleQuizes() {
		$sql = "SELECT * FROM $this->dbTable";
		$query = $this->db->prepare($sql);
		$query->execute();

		foreach ($query->fetchAll() as $dbQuizObj) {
			$quizId = $dbQuizObj[$this->quizId];
			$quizName = $dbQuizObj[$this->quizName];
			$quiz = new Quiz($quizName, $quizId);

			$sql = "SELECT * FROM " . $this->questionTable . " WHERE " . $this->quizId . " = ?";
			$query = $this->db->prepare($sql);
			$query->execute (array($dbQuizObj[$this->quizId]));

			$question = $query->fetch();
			$question = new Question($question[$this->questionName], $question[$this->quizId], $question[$this->questionId]);

			$sql = "SELECT * FROM " . $this->answerTable . " WHERE " . $this->questionId . " = ?";
			$query = $this->db->prepare($sql);
			$query->execute (array($question->getQuestionId()));
			$answers = $query->fetch();

			if ($answers != null) {
				$this->quizList->add($quiz);	
			}
		}

		return $this->quizList;
	}	

	//Gets stored quiz results for all played games, user specific.
	public function getQuizResults($userId) {

		$sql = "SELECT * FROM " . $this->resultTable . " WHERE " . $this->userId . " = ?";
		$params = array($userId);
		$query = $this->db->prepare($sql);
		$query->execute($params);

		foreach ($query->fetchAll() as $obj) {

			$userId = $obj[$this->userId];
			$resultId = $obj[$this->resultId];
			$quizId = $obj[$this->quizId];
			
			$numOfQuestions = $obj[$this->numberOfQuestions];
			$score = $obj[$this->result];
			
			$result = new Result($score, $numOfQuestions, $userId, $quizId, $resultId);
			$this->results[] = $result;
		}

		return $this->results;
	}

	//Checks if the quiz exists by name.
	public function quizExists($quizName) {

			$sql = "SELECT * FROM $this->dbTable WHERE " . $this->quizName . " = ?";
			$params = array($quizName);
			$query = $this->db->prepare($sql);
			$query->execute($params);

			$results = $query->fetch();

			if ($results == false) {

				return false;
			}
			return true;
	}

	//Adds new quiz to database.
	public function addQuiz(Quiz $quiz) {

		$sql = "INSERT INTO $this->dbTable (" . $this->quizName . ") VALUES (?)";
		$params = array($quiz->getName());
		$query = $this->db->prepare($sql);
		$query->execute($params);
	}

    //Checks if the quiz id is valid.
	public function isValidQuizId($id) {

			$sql = "SELECT * FROM $this->dbTable WHERE " . $this->quizId . " = ?";
			$params = array($id);
			$query = $this->db->prepare($sql);
			$query->execute($params);

			$obj = $query->fetch();

			if ($obj == null) {

				return false;
			}
			return true;
	}

	//Delets quiz from database.
	public function removeQuiz(Quiz $quiz) {

		$sql = "DELETE FROM $this->dbTable WHERE " . $this->quizId . " = ?";
		$params = array($quiz->getQuizId());
		$query = $this->db->prepare($sql);
		$query->execute($params);
	}

	//Saves quiz results.
	public function saveQuizResult(Result $result) {	

		$sql = "INSERT INTO $this->resultTable (" . $this->result . ", " . $this->numberOfQuestions . ", " 
												  . $this->userId . ", " . $this->quizId . ") VALUES (?,?,?,?)";

		$params = array($result->getScore(), $result->getNumofQuestions(),
		$result->getUserId(), $result->getQuizId());

		$query = $this->db->prepare($sql);
		$query->execute($params);
	}

	//Saves changes to quiz.
	public function saveEditQuiz(Quiz $quiz) {

			$sql = "UPDATE $this->dbTable SET " . $this->quizName . " = ? WHERE " . $this->quizId . " = ?";
			$params = array($quiz->getName(), $quiz->getQuizId());
			$query = $this->db->prepare($sql);
			$query->execute($params);	
	}

	//Checks if the chosen quiz is valid by its id and got any questions/answers.
	public function isValidGameId($id) {

			$sql = "SELECT * FROM $this->dbTable WHERE " . $this->quizId . " = ?";
			$params = array($id);
			$query = $this->db->prepare($sql);
			$query->execute($params);

			$obj = $query->fetch();
			
			$quizId = $obj[$this->quizId];
			$quizName = $obj[$this->quizName];

			$quiz = new Quiz($quizName, $quizId);

			$sql = "SELECT * FROM " . $this->questionTable . " WHERE " . $this->quizId . " = ?";

			$query = $this->db->prepare($sql);
			$query->execute (array($obj[$this->quizId]));
			$question = $query->fetch();

			$question = new Question($question[$this->questionName], $question[$this->quizId], $question[$this->questionId]);

			$sql = "SELECT * FROM " . $this->answerTable . " WHERE " . $this->questionId . " = ?";

			$query = $this->db->prepare($sql);
			$query->execute (array($question->getQuestionId()));
			$answers = $query->fetch();

			if ($answers != null) {

				return true;
			}
			return false;
	}

	//Gets specific quiz.
	public function getQuiz($quizId) {

		$sql = "SELECT * FROM $this->dbTable WHERE " . $this->quizId . " = ?";
		$params = array($quizId);
		$query = $this->db->prepare($sql);
		$query->execute($params);
		$result = $query->fetch();

		if ($result) {

			$quiz = new Quiz($result[$this->quizName], $result[$this->quizId]);

			$sql = "SELECT * FROM " . $this->questionTable . " WHERE " . $this->quizId . " = ?";
			$query = $this->db->prepare($sql);
			$query->execute (array($result[$this->quizId]));
			$questions = $query->fetchAll();

			foreach($questions as $question) {

				$question = new Question($question[$this->questionName], 
										 $question[$this->quizId], $question[$this->questionId]);
				$quiz->add($question);
			}

			return $quiz;			
		}

		return null;
	}

    //Gets a list of stored quizess.
	public function getQuizList() {

		$sql = "SELECT * FROM $this->dbTable";
		$query = $this->db->prepare($sql);
		$query->execute();

		foreach ($query->fetchAll() as $dbQuizObj) {

			$quizName = $dbQuizObj[$this->quizName];
			$quizId = $dbQuizObj[$this->quizId];
			
			$quiz = new Quiz($quizName, $quizId);
			$this->quizList->add($quiz);
		}

		return $this->quizList;
	}
}