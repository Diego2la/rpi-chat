<?php

/* Класс Chat содержит публичные статические методы, которые используются в ajax.php */

class Chat{
	
	private const IMG_COUNT = 338;
	
	public static function login($name){
		if(!$name){
			throw new Exception('Введите имя');
		}
		
		// Определение индекса avatar:
		$avatar = abs(hexdec(substr(md5($str), 25, 5)) % IMG_COUNT);
		
		$user = new ChatUser(array(
			'name'		=> $name,
			'avatar'	=> $avatar
		));
		
		// Метод save возвращает объект MySQLi
		if($user->save()->affected_rows != 1){
			throw new Exception('Данное имя используется.');
		}
		
		$_SESSION['user'] = array(
			'name'		=> $name,
			'avatar'	=> $avatar
		);
		
		return array(
			'status'	=> 1,
			'name'		=> $name,
			'avatar'	=> Chat::avatarFromIndex($avatar)
		);
	}
	
	public static function checkLogged(){
		$response = array('logged' => false);
			
		if($_SESSION['user']['name']){
			$response['logged'] = true;
			$response['loggedAs'] = array(
				'name'		=> $_SESSION['user']['name'],
				'avatar'	=> Chat::avatarFromIndex($_SESSION['user']['avatar'])
			);
		}
		
		return $response;
	}
	
	public static function logout(){
		DB::query("DELETE FROM webchat_users WHERE name = '".DB::esc($_SESSION['user']['name'])."'");
		
		$_SESSION = array();
		unset($_SESSION);

		return array('status' => 1);
	}
	
	public static function submitChat($chatText){
		if(!$_SESSION['user']){
			throw new Exception('Вы вышли из чата');
		}
		
		if(!$chatText){
			throw new Exception('Вы не ввели сообщение.');
		}
	
		$chat = new ChatLine(array(
			'author'	=> $_SESSION['user']['name'],
			'avatar'	=> $_SESSION['user']['avatar'],
			'text'		=> $chatText
		));
	
		// Метод save возвращает объект MySQLi
		$insertID = $chat->save()->insert_id;
	
		return array(
			'status'	=> 1,
			'insertID'	=> $insertID
		);
	}
	
	public static function getUsers(){
		if($_SESSION['user']['name']){
			$user = new ChatUser(array('name' => $_SESSION['user']['name']));
			$user->update();
		}
		
		// Удаляем записи чата страше 2 часов и пользователей, неактивных в течении 2 часов
		
		DB::query("DELETE FROM webchat_lines WHERE ts < SUBTIME(NOW(),'2:0:0')");
		DB::query("DELETE FROM webchat_users WHERE last_activity < SUBTIME(NOW(),'2:0:')");
		
		$result = DB::query('SELECT * FROM webchat_users ORDER BY name ASC LIMIT 18');
		
		$users = array();
		while($user = $result->fetch_object()){
			$user->avatar = Chat::avatarFromIndex($user->avatar);
			$users[] = $user;
		}
	
		return array(
			'users' => $users,
			'total' => DB::query('SELECT COUNT(*) as cnt FROM webchat_users')->fetch_object()->cnt
		);
	}
	
	public static function getChats($lastID){
		$lastID = (int)$lastID;
	
		$result = DB::query('SELECT * FROM webchat_lines WHERE id > '.$lastID.' ORDER BY id ASC');
	
		$chats = array();
		while($chat = $result->fetch_object()){
			
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
	
	public static function avatarFromIndex($idx/*TODO : size ???*/){
		return '/../../img/ava/'.$idx.'jpg';
	}
}


?>