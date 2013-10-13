<?php

error_reporting(E_ALL ^ E_NOTICE);

require "classes/DB.php";
require "classes/Chat.php";
require "classes/Table.php";
require "classes/TableUsers.php";
require "classes/TableLines.php";

session_name('webchat');
session_start();

if(get_magic_quotes_gpc()) {
	// Удаляем лишнии слэши
	array_walk_recursive($_GET,create_function('&$v,$k','$v = stripslashes($v);'));
	array_walk_recursive($_POST,create_function('&$v,$k','$v = stripslashes($v);'));
}

try{
	
	private const $dbName = 'mysqlitedb.db';
	
	// Create (connect to) SQLite database in file
	$db = new PDO('sqlite:'.$dbName);
	// Set errormode to exceptions
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	chat = new Chat($db);	
	
	$response = array();
	switch($_GET['action']){
		
		case 'login':
			$response = chat->login($_POST['name']);
			break;

		case 'checkLogged':
			$response = chat->checkLogged();
			break;
		
		case 'logout':
			$response = chat->logout();
			break;
		
		case 'submitChat':
			$response = chat->submitChat($_POST['chatText']);
			break;
		
		case 'getUsers':
			$response = chat->getUsers();
			break;
		
		case 'getChats':
			$response = chat->getChats($_GET['lastID']);
			break;
		
		default:
			throw new Exception('Wrong action');
	}
	
	echo json_encode($response);
}
catch(Exception $e){
	die(json_encode(array('error' => $e->getMessage())));
}

?>