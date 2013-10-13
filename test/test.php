#!/usr/bin/php5
<h1>Проверка CGI-версии PHP</h1>
<?

	function avatarFromIndex($idx/*TODO : size ???*/){
		return './../img/ava/'.$idx.'.jpg';
	}

	echo '<img src="'.avatarFromIndex(12).'">';

	// Create (connect to) SQLite database in file
	$file_db = new PDO('sqlite:messaging.sqlite3');
	// Set errormode to exceptions
	$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
?>
