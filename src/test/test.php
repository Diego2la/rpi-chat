#!/usr/bin/php5
<h1>Проверка CGI-версии PHP</h1>
<?

	function avatarFromIndex($idx/*TODO : size ???*/){
		return './../img/ava/'.$idx.'.jpg';
	}

	//echo '<img src="'.avatarFromIndex(12).'">';

	$in = "yefbwuefbwuebfuwebfuwebfuwebf wefubwuefbuwbefwuebfwuebfuwfbwuefbwuebfubweufbwef webfwebfuwbf wef w ef wef we fw ef wefwefwefwefwefwefwefwefwefwefwefwefwef";
	echo $in;
	$text = explode(" ", $in);
	foreach ($text as &$word) {
		if (strlen($word) > 20) {
			echo "<br>";
			$word = substr($word, 0, 20) . "...";
			echo $word;
		}
	}
	$text = implode(" ", $text);
	//echo $text;		
	
?>
