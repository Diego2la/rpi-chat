<?php

class TableLines extends TableBase {

	public function create() {
		return db->exec("CREATE TABLE IF NOT EXISTS `webchat_lines` (
			`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
			`author` varchar(16)                       NOT NULL,
			`text`   varchar(255)                      NOT NULL,
			'ts'     DATE DEFAULT (datetime('now','localtime'))
		);");
	}
	
	public function insert($author, $avatar, $text) {
		return db->exec("INSERT INTO webchat_lines (author, avatar, text)
			VALUES ('".esc($author)."','".esc($avatar)."','".esc($text)."')");
	}
	
	public function functionselectByIdGraterThen($lastID) {
		return db->exec('SELECT * FROM webchat_lines WHERE id > '.$lastID.' ORDER BY id ASC');
	}
	
	public function deleteOlderThen($time) {
		return db->exec("DELETE FROM webchat_lines WHERE ts < SUBTIME(NOW(),".$time.")");
	}
	
}

?>