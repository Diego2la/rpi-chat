<?php

include_once 'TableBase.php';

class TableUsers extends TableBase {
   
	// test field
	private $sqlTest = "
		CREATE TABLE IF NOT EXISTS `webchat_users` (
			`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
			`name`   varchar(16)                       NOT NULL,
			`last_activity` DATE DEFAULT (datetime('now','localtime')),
			UNIQUE (`name`)
		);
		INSERT INTO webchat_users (name) VALUES ('Veronika');
		INSERT INTO webchat_users (name) VALUES ('Veronika2');
		INSERT INTO webchat_users (name) VALUES ('Veronika3');
		
		SELECT COUNT(*) FROM webchat_users WHERE name = 'Veronika';
		
		INSERT OR REPLACE INTO webchat_users (name, last_activity) VALUES ('Veronika3', datetime('now'));
		INSERT OR REPLACE INTO webchat_users (name, last_activity) VALUES ('Veronika4', datetime('now'));
		
		INSERT OR IGNORE INTO webchat_users (name) VALUES ('Veronika4');
		UPDATE webchat_users SET last_activity = datetime('now') WHERE name = 'Veronika4'		

		DELETE FROM webchat_users WHERE name = 'Veronika3';
		SELECT * FROM webchat_users ORDER BY name ASC LIMIT 10;
		SELECT COUNT(*) as cnt FROM webchat_users;
		
		DELETE FROM webchat_users WHERE (strftime('%M','now') - strftime('%M',last_activity)) > 1;
		SELECT * FROM webchat_users;
		";

	public function __construct($db){
		parent::__construct($db);
	}

	public function create() {
		return $this->query("CREATE TABLE IF NOT EXISTS `webchat_users` (
			`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
			`name`   varchar(16)                       NOT NULL,
			`last_activity` DATE DEFAULT (datetime('now','localtime')),
			UNIQUE (`name`)
		);");
	}	

	public function findUser($name) {
		$res = $this->query("SELECT COUNT(*) FROM webchat_users WHERE name = '".$this->esc($name)."'");
		$row = $res->fetch(PDO::FETCH_NUM);
		return $row[0] == 1;
	}
	
	public function insert($name) {
		return $this->query("INSERT INTO webchat_users (name)
			VALUES ('".$this->esc($name)."')");
	}
	
	public function update($name) {
		return $this->query("
			INSERT OR REPLACE INTO webchat_users (name, last_activity) VALUES ('".$this->esc($name)."', datetime('now'))
			");
	}	

	public function selectWithLimit($limit) {
		return $this->query('SELECT * FROM webchat_users ORDER BY name ASC LIMIT '.$limit);
	}
	
	public function getUserCount() {
		return $this->query('SELECT COUNT(*) as cnt FROM webchat_users')->fetchColumn();
	}
	
	public function deleteByName($name) {
		return $this->query("DELETE FROM webchat_users WHERE name = '".$this->esc($name)."'");
	}
	
	public function deleteOlderThen($minutes) {
		return $this->query("DELETE FROM webchat_users WHERE (strftime('%M','now') - strftime('%M',last_activity)) > ".$minutes);
	}
	
	public function selectAll() {
		$res = $this->query('SELECT * FROM webchat_users');
		$str = "webchat_users[";
		foreach ($res as $row) {
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