<?php

include_once 'TableBase.php';

class TableUsers extends TableBase {

	public function __construct($db){
		parent::__construct($db);
	}

	public function create() {
		$statement = $this->db->prepare("CREATE TABLE IF NOT EXISTS `webchat_users` (
			`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
			`name`   varchar(16)                       NOT NULL,
			`last_activity` DATE DEFAULT (datetime('now','localtime')),
			UNIQUE (`name`)
		);");
		return $this->execute($statement);
	}	

	public function findUser($name) {
		$statement = $this->db->prepare("SELECT COUNT(*) FROM webchat_users WHERE name = :col1");
		$statement->bindValue(':col1', $this->esc($name), PDO::PARAM_STR);
		$row = $res->fetch(PDO::FETCH_NUM);
		return $row[0] == 1;
	}
	
	public function insert($name) {
		$statement = $this->db->prepare("INSERT INTO webchat_users (name) VALUES (:col1)");
		$statement->bindValue(':col1', $this->esc($name), PDO::PARAM_STR);
		return $this->execute($statement);
	}
	
	public function update($name) {
		$statement = $this->db->prepare(
			"INSERT OR REPLACE INTO webchat_users (name, last_activity) 
				VALUES (:col1, datetime('now'))");
		$statement->bindValue(':col1', $this->esc($name), PDO::PARAM_STR);
		return $this->execute($statement);
	}	

	public function selectWithLimit($limit) {
		$statement = $this->db->prepare("SELECT * FROM webchat_users ORDER BY name ASC LIMIT :col1");
		$statement->bindValue(':col1', $limit, PDO::PARAM_INT);
		return $this->execute($statement);
	}
	
	public function getUserCount() {
		$statement = $this->db->prepare("SELECT COUNT(*) as cnt FROM webchat_users");
		return $this->execute($statement)->fetchColumn();
	}
	
	public function deleteByName($name) {
		$statement = $this->db->prepare("DELETE FROM webchat_users WHERE name = :col1");
		$statement->bindValue(':col1', $this->esc($name), PDO::PARAM_STR);
		return $this->execute($statement);
	}
	
	public function deleteOlderThen($minutes) {
		$statement = $this->db->prepare(
			"DELETE FROM webchat_users WHERE (strftime('%s','now') - strftime('%s',last_activity)) > :col1");
		$statement->bindValue(':col1', $minutes, PDO::PARAM_INT);
		return $this->execute($statement);
	}
	
	public function selectAll() {
		$statement = $this->db->prepare("SELECT * FROM webchat_users");
		return $this->execute($statement);
		
		$str = "webchat_users[";
		foreach ($statement as $row) {
			$str = $str . "(";
			$str = $str . $row['id'] . ', ';
			$str = $str . $row['name'] . ', ';
			$str = $str . $row['last_activity'];
			$str = $str . "),";
		}		
		$str = $str . "]";
		return $str;
	}
	
}

?>