<?php
echo "<p>当前中医古籍列表：</p>";

$currentpage = 1;
if(isset($_GET['p'])){
    $currentpage = $_GET['p'];
	if (intval($currentpage)<=0){
		$currentpage = 1;
	}
}

//echo $currentpage."<br />";
getBookList(intval($currentpage));


?>
