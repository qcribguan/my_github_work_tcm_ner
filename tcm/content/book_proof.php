<?php
echo "古籍文本校对";

$book = isset($_GET['book']) ? $_GET['book'] : null;
$flag = isset($_GET['flag']) ? $_GET['flag'] : null;
//echo $flag;
//echo $book;
//foreach ($_GET as $key => $val)
//{
//	echo $key;
//	echo $val;
//}


/*
*if there is an corrected version, load it from DB; Or, load the original json version;
*/
/*if ($flag == true) {
	#load from DB
	echo "Load from DB";
}
else {
	#load from json file
	echo "Load from json file";
}*/

//$button_flag = isset($_POST["flag"]) ? $_POST["flag"] : null;
//echo $button_flag;
//if ($button_flag == "export") {
//	//require 'content/book_download.php';
//	downloadBook2Word(cleanBookName($book), $_POST["myEditor"]);
//}


//If user submit the book after editing.
if (isset($_POST["editor"])) {
	//echo $_POST["editor"];
	//echo cleanBookName($book);
	//echo "get new content!";
	
	//save or update it in the database;
	saveBook2Db(cleanBookName($book), $_POST["editor"]);
}



//Then, load the page;
//show json book content in editor, and save button;
editorContent($book, $flag);

showReplaceFunc();
//editorContentTest();



?>