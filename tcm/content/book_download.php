<?php
require 'editor.php';

$book = isset($_POST['btitle']) ? $_POST['btitle'] : null;
//$flag = isset($_GET['flag']) ? $_GET['flag'] : null;
//echo $flag;
//echo $book;
//foreach ($_GET as $key => $val)
//{
//	echo $key;
//	echo $val;
//}

//If user submit the book after editing.
if (isset($_POST["myEditor"])) {
	//echo "test111111";
	//echo cleanBookName($book);
	//echo $_POST["myEditor"];
	downloadBook2Word(cleanBookName($book), $_POST["myEditor"]);
}


