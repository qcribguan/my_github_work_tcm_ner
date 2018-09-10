<?php

/**
 * Json operation.
 * Read book from json file
 */
 
function getBookList($currentpage)
{
	$book_excpet_list = array("content.json");
	$book_name_arr = array();

    $path = getBookPath();
	$handle = opendir($path);
	/*其中$filename = readdir($handler)
	每次循环时将读取的文件名赋值给$filename，$filename !== false。
	一定要用!==，因为如果某个文件名如果叫'0’，或某些被系统认为是代表false，用!=就会停止循环
	*/
	echo '<div><table>
	<tr align=center>
		<td style="width: 5%;">序号</td>
		<td>古籍书名</td>
		<td style="width: 30%;">原始版本链接</td>
		<td style="width: 17%;">校正版本链接</td>
		<td style="width: 15%;">最后校正日期</td>
		<td style="width: 11%;">实体抽取链接</td>
	</tr>';
	if ($handle){
		while( ($filename = readdir($handle)) !== false ){
 			//ignore . and .. direcotries under Linux
 			if($filename != "." && $filename != ".."){
				//if (is_file($filename)){
				if (!in_array($filename, $book_excpet_list)){
					$book_name = cleanBookName($filename);
					$book_name_arr[] = $book_name;
				}
  			}
		}
		closedir($handle);
		
		$bh = new bihua();
		$book_name_arr_ordered = $bh->sortBihuaFirstTwoCh($book_name_arr);
		
		//divide into different pages
		$count = count($book_name_arr_ordered);
		$pagesize = config('page_items_default');
		$pages = ceil($count/$pagesize);//共多少页
		//echo "pages: ".$pages;

		$prepage = $currentpage - 1;
		if($prepage<=0){
			$prepage=1;
		}

		$nextpage = $currentpage + 1;
		if($nextpage >= $pages){
			$nextpage = $pages;
		}		

		//echo $currentpage." ".$pagesize." ";
		$start =($currentpage - 1) * $pagesize + 1;//起始位置
		$end = $start + $pagesize - 1;
		//echo "|".$start."-".$end."|";

		$index = 0;
		$root_path = (null !== config('site_home') && config('site_home') != ''? config('site_home').'/' : '').config('book_proof');
		$root_path_entity = (null !== config('site_home') && config('site_home') != ''? config('site_home').'/' : '').config('book_entity');
		foreach ($book_name_arr_ordered as $key => $book_name){
			$index += 1;			
			if ($index < $start || $index > $end){
				continue;
			}
			//echo $index;
			
			
			$update_t = query_correct_version($book_name);
			//$update_t = "000";
			$filename = $book_name.".json";
			
			$book_path = getBookPath().'\\'.$filename;
			if (is_os_linux()){
				$book_path = str_replace('\\', '/', $book_path);
			}
			$fzise = filesize_h($book_path);
			
			echo '<tr><td>'.$index.'</td>';
			echo '<td><a href="/'.$root_path.'?book='.$filename.'&flag='.(null !== $update_t ? true : false).'&ver=true">'.$book_name.'</a></br></td>';
			//echo '<td align=center><a href="/'.$root_path.'?book='.$filename.'">原始版本</a></br></td>';
			echo '<td align=center><a href="/'.$root_path.'?book='.$filename.'">原始版本</a>('.$fzise.')</br></td>';

			echo '<td align=center>'.(null !== $update_t ? '<a href="/'.$root_path.'?book='.$filename.'&flag='.(null !== $update_t ? true : false).'">校正版本</a></br></td>' : '').'</td>';
			echo '<td align=center>'.(null !== $update_t ? $update_t : '').'</td>';
			echo '<td align=center><a href="/'.$root_path_entity.'?book='.$filename.'&flag='.(null !== $update_t ? true : false).'">实体抽取</a></br></td>';
			echo '</tr>';
					
		}
		
		
	}
	echo '</table></div><br />';
	echo '<form><a href='.config('book_list').'?p='.$prepage.'>上一页</a>&nbsp;&nbsp;<a href='.config('book_list').'?p='.$nextpage.'>下一页</a>&nbsp;&nbsp;';
	echo '跳转到第<input type="text" name="p" style="width:30px" />页<input type="submit" value="提交">&nbsp;&nbsp;';
	echo '（古籍总数量：'.$count.'）</form>';
}
 
?>



