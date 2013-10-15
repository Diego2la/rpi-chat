<?php

class TableBase {

	protected $db;
	
	public function __construct($db){
		$this->db = $db;
	}

	protected function esc($str){
		return $str;
		//return sqlite_escape_string(htmlspecialchars($str)); - old version of func
	}
	
	protected function query($q) {
		try {
			return $this->db->query($q);
		} catch (PDOException $e) {
			throw new Exception('DataBase error : '.$e);
		}
	}
}

?>