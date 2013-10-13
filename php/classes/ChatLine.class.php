<?php

/* Строка чата */

class ChatLine extends ChatBase{
	
	protected $text = '', $author = '', $avatar = '';
	
	public function save(){
		DB::query("
			INSERT INTO webchat_lines (author, avatar, text)
			VALUES (
				'".DB::esc($this->author)."',
				'".DB::esc($this->avatar)."',
				'".DB::esc($this->text)."'
		)");
		
		// Возвращаем объект MySQLi класса DB
		
		return DB::getMySQLiObject();
	}
}

?>