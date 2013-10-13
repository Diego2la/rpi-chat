<?php

class TableUsers extends TableBase {

	public function create() {
		return db->exec("CREATE TABLE IF NOT EXISTS `webchat_users` (
			`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
			`name`   varchar(16)                       NOT NULL,
			`last_activity` DATE DEFAULT (datetime('now','localtime')),
			UNIQUE (`name`)
		);");
	}	

	public function insert($name, $avatar) {
		db->exec("INSERT INTO webchat_users (name, avatar)
			VALUES ('".esc($name)."', '".esc($avatar)."')");
	}
	
	public function update() {
		return db->exec("INSERT INTO webchat_users (name, avatar)
			VALUES ('".esc($name)."', '".esc($avatar)."')
			ON DUPLICATE KEY UPDATE last_activity = NOW()");
		// TODO !!!
		//insert or replace into Book (Name, TypeID, Level, Seen) values ( ... )
	}	

	public function selectWithLimit($limit) {
		return db->exec('SELECT * FROM webchat_users ORDER BY name ASC LIMIT '.$limit);
	}
	
	public function getUserCount() {
		return db->exec('SELECT COUNT(*) as cnt FROM webchat_users')->fetch_object()->cnt;
	}
	
	public function deleteByName($name) {
		return db->exec("DELETE FROM webchat_users WHERE name = '".esc($name)."'");
	}
	
	public function deleteOlderThen($time) {
		return db->exec("DELETE FROM webchat_users WHERE last_activity < SUBTIME(NOW(),".$time.")");
	}
}

?>