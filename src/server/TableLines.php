<?php

include_once 'TableBase.php'; 

class TableLines extends TableBase {

	public function __construct($db){
		parent::__construct($db);
	}
	
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
	
	public function deleteOlderThen($minutes) {
		return $this->query("DELETE FROM webchat_lines WHERE (strftime('%M','now') - strftime('%M',ts)) > ".$minutes);
	}
	
}

?>