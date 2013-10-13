<?php

class DB {
	
	private static $webchat_lines = "CREATE TABLE IF NOT EXISTS `webchat_lines` (
		`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
		`author` varchar(16)                       NOT NULL,
		`avatar` int                               NOT NULL,
		`text`   varchar(255)                      NOT NULL,
		'ts'     DATE DEFAULT (datetime('now','localtime'))
		);";
	private static $webchat_users = "CREATE TABLE IF NOT EXISTS `webchat_users` (
		`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
		`name`   varchar(16)                       NOT NULL,
		`avatar` int                               NOT NULL,
		`last_activity` DATE DEFAULT (datetime('now','localtime')),
		UNIQUE (`name`)
		);";
	
	private static $instance;
	private $db;
	
	private function __construct(){
		$this->db = new SQLite3('mysqlitedb.db');
	}
	
	public static function init(){
		if(self::$instance instanceof self){
			return false;
		}
		self::$instance = new self();
		self::query($webchat_lines);
		self::query($webchat_users);
	}
	
	public static function getdbObject(){
		return self::$instance->db;
	}
	
	public static function query($q){
		return self::$instance->db->query($q);
	}
	
	public static function esc($str){
		return self::$instance->db->real_escape_string(htmlspecialchars($str));
	}
	
}

?>