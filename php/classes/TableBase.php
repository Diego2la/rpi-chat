<?php

class TableBase {

	protected $db;
	
	public function __construct($db){
		$this->$db = $db;
	}

	protected function esc($str){
		return sqlite_escape_string(htmlspecialchars($str));
	}

	public function affectedRows() {
		// TODO
	}
	
	public function lastInsertID() {
		// TODO
	}
	
}

?>