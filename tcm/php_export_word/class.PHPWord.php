<?php
class word
{ 
	function start()
	{
		ob_start();
		echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
		xmlns:w="urn:schemas-microsoft-com:office:word"
		xmlns="http://www.w3.org/TR/REC-html40">';
	}

	function end()
	{
		echo "</html>";
	}
	
	function save($path)
	{
  		$this->end();
		$data = ob_get_contents();
		ob_end_clean();
  
		$this->wirtefile ($path,$data);
	}
  
	function wirtefile ($fn,$data)
	{
		$fp=fopen($fn,"wb");
		fwrite($fp,$data);
		fclose($fp);
	}
}

function save2WordLocal($html)
{
 	$word = new word(); 
	$word->start(); 
	echo $html; 
	$word->end();
	//ob_flush();//每次执行前刷新缓存 
	//flush(); 
}

/*
* Save into word file in the server host.
*/
function save2WordServer($wordname, $html)
{
	$filename = $wordname.".doc"; 
	$word = new word(); 
	$word->start(); 
	echo $html; 
	$word->save($filename); 
	ob_flush();//每次执行前刷新缓存 
	flush(); 
}

function test()
{
$html = ' 
<table width=600 cellpadding="6" cellspacing="1" bgcolor="#336699"> 
<tr bgcolor="White"> 
 <td>PHP10086</td> 
 <td><a href="http://www.php10086.com" target="_blank" >http://www.php10086.com</a></td> 
</tr> 
<tr bgcolor="red"> 
 <td>PHP10086</td> 
 <td><a href="http://www.php10086.com" target="_blank" >http://www.php10086.com</a></td> 
</tr> 
<tr bgcolor="White"> 
 <td colspan=2 > 
 PHP10086<br> 
 最靠谱的PHP技术博客分享网站 
 <img src="http://www.php10086.com/wp-content/themes/WPortal-Blue/images/logo.gif"> 
 </td> 
</tr> 
</table> 
'; 
  
	//批量生成 
	for($i=1;$i<=3;$i++){ 
		$wordname = 'PHP淮北的个人网站--PHP10086.com'.$i.".doc"; 
		save2WordServer($wordname, $html);
	}
}

function test2()
{
$html = ' 
<table width=600 cellpadding="6" cellspacing="1" bgcolor="#336699"> 
<tr bgcolor="White"> 
 <td>PHP10086</td> 
 <td><a href="http://www.php10086.com" target="_blank" >http://www.php10086.com</a></td> 
</tr> 
<tr bgcolor="red"> 
 <td>PHP10086</td> 
 <td><a href="http://www.php10086.com" target="_blank" >http://www.php10086.com</a></td> 
</tr> 
<tr bgcolor="White"> 
 <td colspan=2 > 
 PHP10086<br> 
 最靠谱的PHP技术博客分享网站 
 <img src="http://www.php10086.com/wp-content/themes/WPortal-Blue/images/logo.gif"> 
 </td> 
</tr> 
</table> 
'; 
	$wordname = 'PHP淮北的个人网站--PHP10086.com1.doc';

	header('pragma:public');
    header('Content-type:application/vnd.ms-word;charset=utf-8;name="'.$wordname.'".doc');
    header('Content-Disposition:attachment;filename='.$wordname.'.doc');
  	save2WordLocal($html);
}

//test();
//test2();
