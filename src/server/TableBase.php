<?php

// cutting words longer than $len chars
function cutWords($text, $len) {
	$text = explode(" ", $text);
	foreach ($text as &$word) {
		if (strlen($word) > $len) {
			$word = substr($word, 0, $len) . "...";
		}
	}
	return implode(" ", $text);
}

class TableBase {

	protected $db;
	
	public function __construct($db){
		$this->db = $db;
	}

	protected function esc($str){
		$str = trim($str);
		$str = cutWords($str, 20);
		//$str = mysql_real_escape_string($str);
		//$str = htmlspecialchars($str);
		//$str = mysqli_real_escape_string($this->db, $str);
		return $str;
	}
	
	protected function execute($statement) {
		try {
			$statement->execute();
			return $statement;
		} catch (PDOException $e) {
			throw new Exception('DataBase error : '.$e);
		}
	}
}

?>