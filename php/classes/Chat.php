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
			throw new Exception('������� ���');
		}
		
		$this->$tableUsers->insert($name);

		if($this->$db->rowCount() != 1){
			throw new Exception('������ ��� ������������.');
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
			throw new Exception('�� ����� �� ����');
		}
		if(!$chatText){
			throw new Exception('�� �� ����� ���������.');
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
		
		// ������� ������ ���� ������ 2 ����� � �������������, ���������� � ������� 2 �����
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
			
			// ���������� ����� �������� ��������� � ������� GMT (UTC):
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