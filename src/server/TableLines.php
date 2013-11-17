<?php

include_once 'TableBase.php'; 

class TableLines extends TableBase {

	public function __construct($db){
		parent::__construct($db);
	}
	
	public function create() {
		$statement = $this->db->prepare("CREATE TABLE IF NOT EXISTS `webchat_lines` (
			`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
			`author` varchar(16)                       NOT NULL,
			`text`   varchar(255)                      NOT NULL,
			'ts'     DATE DEFAULT (datetime('now','localtime'))
		);");
		return $this->execute($statement);
	}
	
	public function insert($author, $text) {
		$statement = $this->db->prepare("INSERT INTO webchat_lines (author, text) VALUES (:col1, :col2)");
		$statement->bindValue(':col1', $this->esc($author), PDO::PARAM_STR);
		$statement->bindValue(':col2', $this->esc($text), PDO::PARAM_STR);
		return $this->execute($statement);
	}
	
	public function selectByIdGraterThen($lastID) {
		$statement = $this->db->prepare("SELECT * FROM webchat_lines WHERE id > :col1 ORDER BY id ASC");
		$statement->bindValue(':col1', $lastID, PDO::PARAM_INT);
		return $this->execute($statement);
	}
	
	public function deleteOlderThen($minutes) {
		$statement = $this->db->prepare(
			"DELETE FROM webchat_lines WHERE (strftime('%s','now') - strftime('%s',ts)) > :col1");
		$statement->bindValue(':col1', $minutes, PDO::PARAM_INT);
		return $this->execute($statement);
	}
	
}

?>