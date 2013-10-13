<?php

/* Базовый класс, который используется классами ChatLine и ChatUser */

class ChatBase{

	// Данный конструктор используется всеми класса чата:

	public function __construct(array $options){
		
		foreach($options as $k=>$v){
			if(isset($this->$k)){
				$this->$k = $v;
			}
		}
	}
}

?>