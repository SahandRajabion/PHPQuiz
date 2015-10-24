<?php

require_once("Model/Dao/Repository.php");
require_once("Model/User.php");

class UserRepository extends Repository {

	private static $hash = 'hash';
	private static $username = 'username';
	private $db;

	public function __construct() {

		$this->dbTable = 'user';
		$this->db = $this->connection();
	}

	public function add(User $user) {

			$sql = "INSERT INTO $this->dbTable (". self::$username .", ". self::$hash .") VALUES (?,?)";
			$params = array($user->getUsername(), $user->getPassword());
			$query = $this->db->prepare($sql);
			$query->execute($params);
	}

	public function get($username) {

			$sql = "SELECT * FROM $this->dbTable WHERE (" . self::$username . ") = ?";
			$params = array($username);
			$query = $this->db->prepare($sql);
			$query->execute($params);

			$result = $query->fetch();

			return $result;
	}
	
	public function exists($username) {

			$sql = "SELECT * FROM $this->dbTable WHERE " . self::$username . " = ?";
			$params = array($username);
			$query = $this->db->prepare($sql);
			$query->execute($params);

			$results = $query->fetch();

			if ($results == false) {
				return false;
			}
			return true;
	}
}
