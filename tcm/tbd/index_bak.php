<?php
if(isset($_POST['action']) && $_POST['text']){
    $pattern = array(
    '/ /',//半角下空格
    '/　/',//全角下空格
    '/\r\n/',//window 下换行符
    '/\n/',//Linux && Unix 下换行符
    );
    $replace = array('&nbsp;','&nbsp;','<br />','<br />');
    echo preg_replace($pattern, $replace, $_POST['text']);
}
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>无标题文档</title>
	</head>
<body>
<form action="" method="post">
	<textarea name="text"></textarea>
	<input type="submit" />
</form>
<!-- <?php phpinfo(); ?> -->
</body>
</html>

