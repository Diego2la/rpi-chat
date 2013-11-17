<?php

include_once 'TableUsers.php';
include_once 'TableLines.php';

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

class Chat {
		
	private static $instance;

	private $tableUsers;
	private $tableLines;
	private $db;
	
	private function __construct($dbName){	
		// Create (connect to) SQLite database in file
		$db = new PDO('sqlite:'.$dbName);
		// Set errormode to exceptions
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$this->db = $db;
		$this->tableUsers = new TableUsers($db);
		$this->tableLines = new TableLines($db);

		$this->tableUsers->create();
		$this->tableLines->create();
	}
	
	public static function getInstance($dbName){

		if(self::$instance == null){
			self::$instance = new self($dbName);
		} 
		return self::$instance;
	}
	
	public function login($name){
		if(!$name || $name == 'Name'){
			throw new Exception('Input name');
		}
		
		if ($this->tableUsers->findUser($name)) {
			throw new Exception('Name is not availible');
		}
		
		$res = $this->tableUsers->insert($name);
		
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
		$this->tableUsers->deleteByName($_SESSION['user']['name']);
		$_SESSION = array();
		unset($_SESSION);

		return array('status' => 1);
	}
	
	public function submitChat($chatText){
		if(!$_SESSION['user']){
			throw new Exception('You are not in chat');
		}
		$chatText = cutWords($chatText, 20);
		if(!$chatText){
			throw new Exception('Input message');
		}		
			
		$this->tableLines->insert($_SESSION['user']['name'], $chatText);
		
		return array(
			'status'	=> 1,
			'insertID'	=> $this->db->lastInsertId()
		);
	}
	
	public function getUsers(){
		if($_SESSION['user']['name']){
			$this->tableUsers->update($_SESSION['user']['name']);
		}
		
		// ”дал€ем записи чата страше X мин и пользователей, неактивных в течении Y мин
		$this->tableLines->deleteOlderThen(2*60);
		$this->tableUsers->deleteOlderThen(2);
		
		$result = $this->tableUsers->selectWithLimit(18); 

		$users = array();
		while($user = $result->fetchObject()){
			$user->avatar = $this->getAvatar($user->name);
			$users[] = $user;
		}
		
		return array(
			'users' => $users,
			'total' => $this->tableUsers->getUserCount()
		);
	}
	
	public function getChats($lastID){
		$result = $this->tableLines->selectByIdGraterThen((int)$lastID);
	
		$chats = array();
		while($chat = $result->fetchObject()){
			
			// ¬озвращаем врем€ создани€ сообщени€ в формате GMT (UTC):
			$chat->time = array(
				'hours'		=> gmdate('H',strtotime($chat->ts)),
				'minutes'	=> gmdate('i',strtotime($chat->ts))
			);			
			
			$chat->avatar = $this->getAvatar($chat->author);
			
			$chats[] = $chat;
		}
	
		return array('chats' => $chats);
	}
	
	public function getAvatar($name){
		$IMG_COUNT = 338;
		$idx = abs(hexdec(substr(md5($name), 25, 5)) % $IMG_COUNT);
		return './../../img/ava/'.$idx.'.jpg';
	}

}

?>