<?php

include_once 'TableBase.php';

class TableUsers extends TableBase {

	public function create() {
		return $this->$db->query("CREATE TABLE IF NOT EXISTS `webchat_users` (
			`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
			`name`   varchar(16)                       NOT NULL,
			`last_activity` DATE DEFAULT (datetime('now','localtime')),
			UNIQUE (`name`)
		);");
	}	

	public function insert($name, $avatar) {
		return $this->$db->query("INSERT INTO webchat_users (name, avatar)
			VALUES ('".$this->esc($name)."', '".$this->esc($avatar)."')");
	}
	
	public function update() {
		return $this->$db->query("INSERT INTO webchat_users (name, avatar)
			VALUES ('".$this->esc($name)."', '".$this->esc($avatar)."')
			ON DUPLICATE KEY UPDATE last_activity = NOW()");
		// TODO !!!
		//insert or replace into Book (Name, TypeID, Level, Seen) values ( ... )
	}	

	public function selectWithLimit($limit) {
		return $this->$db->query('SELECT * FROM webchat_users ORDER BY name ASC LIMIT '.$limit);
	}
	
	public function getUserCount() {
		return $this->$db->query('SELECT COUNT(*) as cnt FROM webchat_users')->fetch_object()->cnt;
	}
	
	public function deleteByName($name) {
		return $this->$db->query("DELETE FROM webchat_users WHERE name = '".$this->esc($name)."'");
	}
	
	public function deleteOlderThen($time) {
		return $this->$db->query("DELETE FROM webchat_users WHERE last_activity < SUBTIME(NOW(),".$time.")");
	}
}

?>