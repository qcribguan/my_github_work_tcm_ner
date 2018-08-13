<?php

/**
 * Json operation.
 * Read book from json file
 */
 
function getBookList()
{
	$book_excpet_list = array("content.json");
	$result = array();
	
    $path = getBookPath();
	
	$handle = opendir($path);
	/*其中$filename = readdir($handler)
	每次循环时将读取的文件名赋值给$filename，$filename !== false。
	一定要用!==，因为如果某个文件名如果叫'0’，或某些被系统认为是代表false，用!=就会停止循环
	*/
	echo '<div><table>
	<tr align=center>
		<td style="width: 5%;">序号</td>
		<td style="width: 35%;">古籍书名</td>
		<td style="width: 17%;">原始版本链接</td>
		<td style="width: 17%;">校正版本链接</td>
		<td style="width: 15%;">最后校正日期</td>
		<td style="width: 11%;">实体抽取链接</td>
	</tr>';
	if ($handle){
		$index = 0;
		while( ($filename = readdir($handle)) !== false ){
 			//ignore . and .. direcotries under Linux
 			if($filename != "." && $filename != ".."){
				//if (is_file($filename)){
				if (!in_array($filename, $book_excpet_list)){
					$book_name = cleanBookName($filename);
					$update_t = query_correct_version($book_name);
					$index += 1;
					
					echo '<tr><td>'.$index.'</td>';
		   			echo '<td><a href="/'.(null !== config('site_home') && config('site_home') != ''? config('site_home').'/' : '').config('book_proof').'?book='.$filename.'&flag='.(null !== $update_t ? true : false).'">'.$book_name.'</a></br></td>';
					echo '<td align=center><a href="/'.(null !== config('site_home') && config('site_home') != ''? config('site_home').'/' : '').config('book_proof').'?book='.$filename.'">原始版本</a></br></td>';
					echo '<td align=center>'.(null !== $update_t ? '<a href="/'.(null !== config('site_home') && config('site_home') != ''? config('site_home').'/' : '').config('book_proof').'?book='.$filename.'&flag='.(null !== $update_t ? true : false).'">校正版本</a></br></td>' : '').'</td>';
					echo '<td align=center>'.(null !== $update_t ? $update_t : '').'</td>';
					echo '<td align=center><a href="/'.(null !== config('site_home') && config('site_home') != ''? config('site_home').'/' : '').config('book_entity').'?book='.$filename.'&flag='.(null !== $update_t ? true : false).'">实体抽取</a></br></td>';
					echo '</tr>';
				}
  			}
		}
		closedir($handle);
	}
	echo '</table></div>';
}
 




