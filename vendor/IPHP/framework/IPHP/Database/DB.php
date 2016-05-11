<?php
namespace IPHP\Database;

class DB {
	private $pdo;
	private $lastError = [];
	private $rowsAffected = 0;

	public function __construct ($type, $host, $dbname, $dbuser, $dbpassword) {
		$dsn = $type . ':dbname='. $dbname .';host='. $host;
		$this->pdo = new \PDO($dsn, $dbuser, $dbpassword);
	}

	public function executeQuery (string $query, array $params = [], $returnStatement = true) {
		$statement = $this->pdo->prepare($query);
		$this->rowsAffected = 0;
		$result = $statement->execute($params);
		if (!$result) {
			$this->lastError[] = $statement->errorInfo();
		}

		$this->rowsAffected = $statement->rowCount();

		if ($returnStatement){
			return $statement;
		} else {
			return $result;
		}
	}

	public function fetch (string $query, array $params = []) {
		$statement = $this->executeQuery($query, $params);
		return $statement->fetch(\PDO::FETCH_OBJ);
	}

	public function fetchAll (string $query, array $params = []) {
		$statement = $this->executeQuery($query, $params);
		return $statement->fetchAll(\PDO::FETCH_CLASS, "stdClass");
	}

	public function insertId () {
		return $this->pdo->lastInsertId();
	}

	public function getErrors () {
		if ($this->lastError) {
			$lastError = end($this->lastError);
			return $lastError;
		}
		return null;
	}

	public function getAllErrors () {
		return $this->lastError; 
	}

	public function delete (string $query, array $params = []) {
		$statement = $this->executeQuery($query, $params);

		return $this->rowsAffected > 0;
	}

	public function getRowsAffected () {
		return $this->rowsAffected;
	}
}