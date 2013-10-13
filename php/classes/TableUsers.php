<?php

include_once 'TableBase.php';

class TableUsers extends TableBase {
   
	public function create() {
		return $this->query("CREATE TABLE IF NOT EXISTS `webchat_users` (
			`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
			`name`   varchar(16)                       NOT NULL,
			`last_activity` DATE DEFAULT (datetime('now','localtime')),
			UNIQUE (`name`)
		);");
	}	

	public function insert($name) {
		return $this->query("INSERT INTO webchat_users (name)
			VALUES ('".$this->esc($name)."')");
	}
	
	public function update($name) {
		return $this->query("INSERT INTO webchat_users (name)
			VALUES ('".$this->esc($name)."')
			ON DUPLICATE KEY UPDATE last_activity = NOW()");
		// TODO !!!
		//insert or replace into Book (Name, TypeID, Level, Seen) values ( ... )
	}	

	public function selectWithLimit($limit) {
		return $this->query('SELECT * FROM webchat_users ORDER BY name ASC LIMIT '.$limit);
	}
	
	public function getUserCount() {
		return $this->query('SELECT COUNT(*) as cnt FROM webchat_users')->fetch_object()->cnt;
	}
	
	public function deleteByName($name) {
		return $this->query("DELETE FROM webchat_users WHERE name = '".$this->esc($name)."'");
	}
	
	public function deleteOlderThen($time) {
		return $this->query("DELETE FROM webchat_users WHERE last_activity < SUBTIME(NOW(),".$time.")");
	}
}

?>