<?php

include_once 'TableUsers.php';
include_once 'TableLines.php';

class Chat {
	
	const IMG_COUNT = 338;
	
	private $tableUsers;
	private $tableLines;

	private $db;
	
	public function __construct($db){
		$this->$db = $db;
		$this->$tableUsers = new TableUsers($db);
		$this->$tableLines = new TableLines($db);
	}
	
	public function login($name){
		if(!$name){
			throw new Exception('Введите имя');
		}
		
		$this->$tableUsers->insert($name);

		if($this->$db->rowCount() != 1){
			throw new Exception('Данное имя используется.');
		}
		
		$_SESSION['user'] = array(
			'name'		=> $name
		);
		
		return array(
			'status'	=> 1,
			'name'		=> $name,
			'avatar'	=> $this->getAvatar($name)
		);
	}
	
	public function checkLogged(){
		$response = array('logged' => false);
		$name = $_SESSION['user']['name'];	
			
		if($name){
			$response['logged'] = true;
			$response['loggedAs'] = array(
				'name'		=> $name,
				'avatar'	=> $this->getAvatar($name)
			);
		}
		
		return $response;
	}
	
	public function logout(){
		$this->$tableUsers->deleteByName($_SESSION['user']['name']);
		$_SESSION = array();
		unset($_SESSION);

		return array('status' => 1);
	}
	
	public function submitChat($chatText){
		if(!$_SESSION['user']){
			throw new Exception('Вы вышли из чата');
		}
		if(!$chatText){
			throw new Exception('Вы не ввели сообщение.');
		}
			
		$this->$tableLines->insert($_SESSION['user']['name'], $chatText);
		
		return array(
			'status'	=> 1,
			'insertID'	=> $this->$db->lastInsertId()
		);
	}
	
	public function getUsers(){
		if($_SESSION['user']['name']){
			$this->$tableUsers->update($_SESSION['user']['name']);
		}
		
		// Удаляем записи чата страше 2 часов и пользователей, неактивных в течении 2 часов
		$this->$tableLines->deleteOlderThen('2:0:0');
		$this->$tableUsers->deleteOlderThen('2:0:0');
		
		$result = $this->$tableUsers->selectWithLimit(18); 
		
		$users = array();
		while($user = $result->fetch(PDO::FETCH_ASSOC)){
			$user->avatar = $this->avatarFromIndex($user->avatar);
			$users[] = $user;
		}
	
		return array(
			'users' => $users,
			'total' => $this->$tableUsers->getUserCount()
		);
	}
	
	public function getChats($lastID){
		$result = $this->$tableLines->selectByIdGraterThen((int)$lastID);
	
		$chats = array();
		while($chat = $result->fetch(PDO::FETCH_ASSOC)){
			
			// Возвращаем время создания сообщения в формате GMT (UTC):
			$chat->time = array(
				'hours'		=> gmdate('H',strtotime($chat->ts)),
				'minutes'	=> gmdate('i',strtotime($chat->ts))
			);			
			$chat->avatar = $this->avatarFromIndex($chat->avatar);
			
			$chats[] = $chat;
		}
	
		return array('chats' => $chats);
	}
	
	public function getAvatar($name){
		$idx = abs(hexdec(substr(md5($name), 25, 5)) % IMG_COUNT);
		return './../../img/ava/'.$idx.'.jpg';
	}

}

?>