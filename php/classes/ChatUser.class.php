<?php

class ChatUser extends ChatBase{
	
	protected $name = '', $avatar = '';
	
	public function save(){
		
		DB::query("
			INSERT INTO webchat_users (name, avatar)
			VALUES (
				'".DB::esc($this->name)."',
				'".DB::esc($this->avatar)."'
		)");
		
		return DB::getMySQLiObject();
	}
	
	public function update(){
		DB::query("
			INSERT INTO webchat_users (name, avatar)
			VALUES (
				'".DB::esc($this->name)."',
				'".DB::esc($this->avatar)."'
			) ON DUPLICATE KEY UPDATE last_activity = NOW()");
	}
}

?>