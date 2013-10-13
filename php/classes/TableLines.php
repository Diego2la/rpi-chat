<?php

include_once 'TableBase.php'; 

class TableLines extends TableBase {

	public function create() {
		return $this->$db->query("CREATE TABLE IF NOT EXISTS `webchat_lines` (
			`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
			`author` varchar(16)                       NOT NULL,
			`text`   varchar(255)                      NOT NULL,
			'ts'     DATE DEFAULT (datetime('now','localtime'))
		);");
	}
	
	public function insert($author, $avatar, $text) {
		return $this->$db->query("INSERT INTO webchat_lines (author, avatar, text)
			VALUES ('".$this->esc($author)."','".$this->esc($avatar)."','".$this->esc($text)."')");
	}
	
	public function selectByIdGraterThen($lastID) {
		return $this->$db->query('SELECT * FROM webchat_lines WHERE id > '.$lastID.' ORDER BY id ASC');
	}
	
	public function deleteOlderThen($time) {
		return $this->$db->query("DELETE FROM webchat_lines WHERE ts < SUBTIME(NOW(),".$time.")");
	}
	
}

?>