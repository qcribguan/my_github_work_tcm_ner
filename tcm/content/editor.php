<?php

/*
* It should be put in <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />, but outside "<style>"
*/
function editorScript()
{
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="umeditor/third-party/jquery.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="umeditor/umeditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="umeditor/umeditor.min.js"></script>
    <script type="text/javascript" src="umeditor/lang/zh-cn/zh-cn.js"></script>
	';
}
 
/*
* It should be put betweeen "<style type="text/css">" and "</style>"
*/
function editorStyle()
{
	echo "css/editor.css";
}


/*
* Show the book using the editor main frame
*/
$html_space = "&nbsp;";
//$kws = array("section", "paragraph");
$kws2 = array("summary", "annotation", "translation", "content");

function readJson($data)
{
	global $html_space;
	//global $kws;
	global $kws2;
	$flag = 0;
	
	foreach ($data as $key => $value){
		//echo $key;
		if (is_array($value)) {
			//TODO: using keyword to represent the "summary", "annotation", "translation".
			if (in_array($key, $kws2) && $key != "0"){
				//echo '=='.$key.'==';
				echo "<br />";
			}
        	readJson($data[$key]);
        } else {
			$str= str_replace(array("\r\n", "\r", "\n"), "", $value);
			if ($key == "book_title"){
				echo '<p style="text-align:center;"><span style="font-size:20px"><strong>'.$str.'</strong></span></p>';
			}
			//elseif (in_array($value, $kws)) {
			elseif ($value == "section") {
				$flag = 1;
				continue;
			} 
			elseif ($value == "paragraph") {
				$flag = 2;
				continue;
			} 
			else{
				if ($flag == 1){
					//echo '<p><strong>'.$str.'</strong></p>';
					echo '<br /><p><strong>'.$str.'</strong></p>';
				}
				else{
					echo '<p>'.str_repeat($html_space, 7).$str.'</p>';
				}
			}
		}
	}
}

/*
* utils: get clean book name
*/
function cleanBookName($name)
{
	$pattern='/(.json)/';
	return preg_replace($pattern,'',$name);
}


/*
* show json book content in editor, and provide save button.
* @flag: true: there is an corrected version, load it from DB; 
* 		false: there is no DB version, load the original json version;
*/
function editorContent($book, $flag)
{
	//echo check_os();
	//echo "=========test=======";
	$book_path = getBookPath().'\\'.$book;
	if (is_os_linux()){
		$book_path = str_replace('\\', '/', $book_path);
	}
	//echo $book_path;
	
	//echo $book_path;	
	$book_name = cleanBookName($book);
	
 	echo '<h1>古籍名称：'.$book_name.'</h1>';
	if ($book_name != ''){
		if ($flag == true){
			$t = query_correct_version($book_name);
			echo '<p>提示：此为校正版本，最后校正时间为'.$t.'。</p>';
		}
		else {
			echo '<p>提示：此为原始文本。</p>';
		}
	}
	echo '<form id="book_content_form" name="book_content_form" action="" method="post">
	<!--style给定宽度可以影响编辑器的最终宽度-->
	<script type="text/plain" id="myEditor" name="myEditor" style="width:630px;height:160px;">';

	if ($book_name != ''){	
		if ($flag == true){
			load_correct_version($book_name);
		}
		else{
			#read from the json file and show it through the editor
			#read from file to php variable
			$json_string = file_get_contents($book_path);	
			#convert the str into json 
			$data = json_decode($json_string, true); 
			
			readJson($data);
		}
	}
	else{
		echo "没有提供古籍名称！";
	}
	echo '</script></br>
	<div align="right">
	<!-- <input type="button" name="submit" onClick="btn_sumbit()" value="保存"> -->
	<!-- <input type="submit" name="submit" value="保存"> -->
	<input id="btitle" type="hidden" name="btitle" value="'.$book_name.'" />
	<input id="flag" type="hidden" name="flag" value="save" />
	<button id="save1" class="btn" onClick="btn_submit()">保存</button>&nbsp;
	<!-- <button id="export" class="btn" onClick="btn_submit2()">导出为Word文档</button>&nbsp; -->
	</div>
	</form>';
	
	echo '<script type="text/javascript">
		function btn_submit3() {
		//UM.getEditor("myEditor").sync();
		//document.getElementById("flag").value = "export";
		//document.book_content_form.action="1.htm"
		var myform=document.getElementById("book_content_form");
		myform.action="'.config("content_path").'/'.config("book_download").'"
  		myform.submit();
		}
		

	</script>';
}

/*
* It should be put betweeen "<script type="text/javascript">" and "</script>"
*/
function editorJs()
{
	echo "js/editor.js";
}

/*
* Create a submit button and submit it to DB
*/
function submitButton()
{
	echo '<div class="clear"></div>
<div id="btns">
	<table>
		<tr>
			<td align="right">
				<button class="btn" unselected="on" onClick="getAllHtml()">保存</button>&nbsp;
			</td>
		</tr>
	</table>
</div>
';
}


function editorContentTest()
{
echo '<div class="clear"></div>
<div id="btns">
    <table>
        <tr>
            <td>
                <button class="btn" unselected="on" onClick="getAllHtml()">获得整个html的内容</button>&nbsp;
                <button class="btn" onClick="getContent()">获得内容</button>&nbsp;
                <button class="btn" onClick="setContent()">写入内容</button>&nbsp;
                <button class="btn" onClick="setContent(true)">追加内容</button>&nbsp;
                <button class="btn" onClick="getContentTxt()">获得纯文本</button>&nbsp;
                <button class="btn" onClick="getPlainTxt()">获得带格式的纯文本</button>&nbsp;
                <button class="btn" onClick="hasContent()">判断是否有内容</button>
            </td>
        </tr>
        <tr>
            <td>
                <button class="btn" onClick="setFocus()">编辑器获得焦点</button>&nbsp;
                <button class="btn" onMouseDown="isFocus();return false;">编辑器是否获得焦点</button>&nbsp;
                <button class="btn" onClick="doBlur()">编辑器取消焦点</button>&nbsp;
                <button class="btn" onClick="insertHtml()">插入给定的内容</button>&nbsp;
                <button class="btn" onClick="getContentTxt()">获得纯文本</button>&nbsp;
                <button class="btn" id="enable" onClick="setEnabled()">可以编辑</button>&nbsp;
                <button class="btn" onClick="setDisabled()">不可编辑</button>
            </td>
        </tr>
        <tr>
            <td>
                <button class="btn" onClick="UM.getEditor(\'myEditor\').setHide()">隐藏编辑器</button>&nbsp;
                <button class="btn" onClick="UM.getEditor(\'myEditor\').setShow()">显示编辑器</button>&nbsp;
                <button class="btn" onClick="UM.getEditor(\'myEditor\').setHeight(300)">设置编辑器的高度为300</button>&nbsp;
                <button class="btn" onClick="UM.getEditor(\'myEditor\').setWidth(1200)">设置编辑器的宽度为1200</button>
            </td>
        </tr>

    </table>
</div>
<table>
    <tr>
        <td>
            <button class="btn" onClick="createEditor()"/>创建编辑器</button>
            <button class="btn" onClick="deleteEditor()"/>删除编辑器</button>
        </td>
    </tr>
</table>

<div>
    <h3 id="focush2"></h3>
</div>
	';
}


/*
* save the edited content into database;
*/
function saveBook2Db($title, $content)
{
	$table = config('dbtab_book_proof');
	$col_array = array("book_name", "book_content", "update_at", "create_at");
	
	//$content = "teststring-------------";
	//$title = "test2";
	//echo $table;
	
	try{
		$DB = new DBPDO();
		//check if this book is already in the database;
		$sql_str = sql_query_str($table, $col_array[0], $title);
		$res = $DB->fetchAll($sql_str);
		if (sizeof($res) == 0) {
			#insert a new record;
			$t = get_current_time();
			$val_array = array($title, $content, $t, $t);
			$sql_str = sql_insert_str($table, $col_array, $val_array);
			//echo $sql_str;
			
			$ret = $DB->execute($sql_str);
			echo $DB->lastInsertId();
			echo '<script type="text/javascript">alert("保存成功！")</script>';

		}
		else {
			#update the book;
			$t = get_current_time();
			$col_arr = array("book_content", "update_at");
			$val_arr = array($content, $t);
			$cond = "book_name='".$title."'";
		
			$sql_str = sql_update_str($table, $col_arr, $val_arr, $cond);
			//echo $sql_str;
			$ret = $DB->execute($sql_str);
			echo '<script type="text/javascript">alert("保存成功！")</script>';
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
}

/*
* provide a book name, check if there is a editor version in the database;
*/
function query_correct_version($title)
{
	$table = config('dbtab_book_proof');
	$col_array = array("book_name", "book_content", "update_at", "create_at");
		
	try{
		$DB = new DBPDO();
		//check if this book is already in the database;
		$sql_str = sql_query_str($table, $col_array[0], $title);
		$res = $DB->fetchAll($sql_str);
		if (sizeof($res) == 0) {
			return null;
		}
		else {
			return $res[0]['update_at'];
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
}

/*
* provide a book name, return the content from the database;
*/
function load_correct_version($title)
{
	$table = config('dbtab_book_proof');
	$col_array = array("book_name", "book_content", "update_at", "create_at");
		
	try{
		$DB = new DBPDO();
		//check if this book is already in the database;
		$sql_str = sql_query_str($table, $col_array[0], $title);
		$res = $DB->fetchAll($sql_str);
		if (sizeof($res) == 0) {
			echo "Fatal error!";
			#return null;
			echo "Al-Oh, page lost";
		}
		else {
			echo $res[0]['book_content'];
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
}

/*
*
*/
function downloadBook2Word($filename, $content)
{
	header('pragma:public');
    header('Content-type:application/vnd.ms-word;charset=utf-8;name="'.$filename.'".doc');
    header('Content-Disposition:attachment;filename='.$filename.'.doc');
  	save2WordLocal($content);
}
