<?php

include_once 'classes/Chat.php';

error_reporting(E_ALL ^ E_NOTICE);

session_name('webchat');
session_start();

if(get_magic_quotes_gpc()) {
	// Delete extra slashes
	array_walk_recursive($_GET,create_function('&$v,$k','$v = stripslashes($v);'));
	array_walk_recursive($_POST,create_function('&$v,$k','$v = stripslashes($v);'));
}

try{
	
	$chat = Chat::getInstance('sqlitedb.db');

	$response = array();
	switch($_GET['action']){
		
		case 'login':
			$response = $chat->login($_POST['name']);
			break;

		case 'checkLogged':
			$response = $chat->checkLogged();
			break;
		
		case 'logout':
			$response = $chat->logout();
			break;
		
		case 'submitChat':
			$response = $chat->submitChat($_POST['chatText']);
			break;
		
		case 'getUsers':
			$response = $chat->getUsers();
			break;
		
		case 'getChats':
			$response = $chat->getChats($_GET['lastID']);
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