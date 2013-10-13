<?php

include_once 'TableBase.php'; 

class TableLines extends TableBase {

	private $sqlTest = "
		CREATE TABLE IF NOT EXISTS `webchat_lines` (
			`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
			`author` varchar(16)                       NOT NULL,
			`text`   varchar(255)                      NOT NULL,
			'ts'     DATE DEFAULT (datetime('now','localtime'))
		);
		INSERT INTO webchat_lines (author, text) VALUES ('dima','hello');
		INSERT INTO webchat_lines (author, text) VALUES ('tanya','go fack yourself');
		INSERT INTO webchat_lines (author, text) VALUES ('nastya','lol');
		SELECT * FROM webchat_lines WHERE ts < datetime('now', '-1 minute');
		";
//		SELECT (strftime('%s','now') - strftime('%s',ts)) AS real FROM webchat_lines;
//		DELETE FROM webchat_lines WHERE ts < (julianday(Date('now')) + julianday(DateCreated));

	public function create() {
		return $this->query("CREATE TABLE IF NOT EXISTS `webchat_lines` (
			`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
			`author` varchar(16)                       NOT NULL,
			`text`   varchar(255)                      NOT NULL,
			'ts'     DATE DEFAULT (datetime('now','localtime'))
		);");
	}
	
	public function insert($author, $text) {
		return $this->query("INSERT INTO webchat_lines (author, text)
			VALUES ('".$this->esc($author)."','".$this->esc($text)."')");
	}
	
	public function selectByIdGraterThen($lastID) {
		return $this->query('SELECT * FROM webchat_lines WHERE id > '.$lastID.' ORDER BY id ASC');
	}
	
	public function deleteOlderThen($time) {
		return $this->query("DELETE FROM webchat_lines WHERE ts < SUBTIME(NOW(),".$time.")");
	}
	
}

?>