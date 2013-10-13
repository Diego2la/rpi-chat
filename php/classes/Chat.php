<?php

/* Класс Chat содержит публичные статические методы, которые используются в ajax.php */

class Chat{
	
	private const IMG_COUNT = 338;
	
	private tableUsers;
	private tableLines;
	
	public function __construct($db){
		tableUsers = new TableUsers($db);
		tableLines = new TableLines($db);
	}
	
	public function login($name){
		if(!$name){
			throw new Exception('Введите имя');
		}
		
		tableUsers->insert($name);

		if(DB::affectedRows() != 1){
			throw new Exception('Данное имя используется.');
		}
		
		$_SESSION['user'] = array(
			'name'		=> $name
		);
		
		return array(
			'status'	=> 1,
			'name'		=> $name,
			'avatar'	=> Chat::getAvatar($name)
		);
	}
	
	public function checkLogged(){
		$response = array('logged' => false);
		$name = $_SESSION['user']['name'];	
			
		if($name){
			$response['logged'] = true;
			$response['loggedAs'] = array(
				'name'		=> $name,
				'avatar'	=> Chat::getAvatar($name)
			);
		}
		
		return $response;
	}
	
	public function logout(){
		TableUsers::deleteByName($_SESSION['user']['name']);
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
			
		tableLines->insert($_SESSION['user']['name'], $chatText);
		
		return array(
			'status'	=> 1,
			'insertID'	=> tableLines->lastInsertID()
		);
	}
	
	public function getUsers(){
		if($_SESSION['user']['name']){
			tableUsers->update($_SESSION['user']['name']);
		}
		
		// Удаляем записи чата страше 2 часов и пользователей, неактивных в течении 2 часов
		TableLines::deleteOlderThen('2:0:0');
		TableUsers::deleteOlderThen('2:0:0');
		
		$result = TableUsers::selectWithLimit(18); 
		
		$users = array();
		while($user = $result->fetch()){
			$user->avatar = Chat::avatarFromIndex($user->avatar);
			$users[] = $user;
		}
	
		return array(
			'users' => $users,
			'total' => TableUsers::getUserCount()
		);
	}
	
	public function getChats($lastID){
		
		$result = TableLines::selectByIdGraterThen((int)$lastID);
	
		$chats = array();
		while($chat = $result->fetch()){
			
			// Возвращаем время создания сообщения в формате GMT (UTC):
			$chat->time = array(
				'hours'		=> gmdate('H',strtotime($chat->ts)),
				'minutes'	=> gmdate('i',strtotime($chat->ts))
			);			
			$chat->avatar = Chat::avatarFromIndex($chat->avatar);
			
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