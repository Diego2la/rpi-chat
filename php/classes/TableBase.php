<?php

class TableBase {

	protected $db;
	
	public function __construct($db){
		$this->db = $db;
	}

	protected function esc($str){
		return sqlite_escape_string(htmlspecialchars($str));
	}
	
	protected function query($q) {
		try {
			return $db->query($q);
		} catch (PDOException $e) {
			throw new Exception('DataBase error : '.$e);
		}
	}
}

?>