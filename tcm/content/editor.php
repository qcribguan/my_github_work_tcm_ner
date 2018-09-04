<?php

/*
* It should be put in <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />, but outside "<style>"
*/
function editorScript()
{
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script type="text/javascript" charset="utf-8" src="umeditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="umeditor/ueditor.all.min.js"></script>
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
* use some rules to clean the original json book or html json book
* Most Important!!!
*/
function cleanBookContent($text)
{
	/*
	1、	在部分古籍文本中会出现大量图片的文件名；
	2、	出现KT、HT等字母；
	3、	较多图书中会出现英文字符或“□”、“○”等符号，
	*/
	$kws = array("KT", "HT", "□", "○", "■", "●");
	foreach ($kws as $key => $value){
		$text = str_replace($value, "", $text);
	}
	return $text;
}


/*
* show json book content in editor, and provide save button.
* @flag: true: there is an corrected version, load it from DB; 
* 		false: there is no DB version, load the original json version;
*/
function editorContent($book, $flag, $ver)
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
	<script id="editor" name="editor" type="text/plain" style="width:630px;height:160px;">';

	if ($book_name != ''){	
		if ($flag == true){
			load_correct_version($book_name);
		}
		else{
			#read from the json file and show it through the editor
			#read from file to php variable
			$json_string = file_get_contents($book_path);
			if($ver){
				#use some rules to clean the original json book
				$json_string = cleanBookContent($json_string);
			}
			
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
		//UM.getEditor("editor").sync();
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
echo '<div id="btns">
    <div>
        <button onclick="getAllHtml()">获得整个html的内容</button>
        <button onclick="getContent()">获得内容</button>
        <button onclick="setContent()">写入内容</button>
        <button onclick="setContent(true)">追加内容</button>
        <button onclick="getContentTxt()">获得纯文本</button>
        <button onclick="getPlainTxt()">获得带格式的纯文本</button>
        <button onclick="hasContent()">判断是否有内容</button>
        <button onclick="setFocus()">使编辑器获得焦点</button>
        <button onmousedown="isFocus(event)">编辑器是否获得焦点</button>
        <button onmousedown="setblur(event)" >编辑器失去焦点</button>

    </div>
    <div>
        <button onclick="getText()">获得当前选中的文本</button>
        <button onclick="insertHtml()">插入给定的内容</button>
        <button id="enable" onclick="setEnabled()">可以编辑</button>
        <button onclick="setDisabled()">不可编辑</button>
        <button onclick=" UE.getEditor(\'editor\').setHide()">隐藏编辑器</button>
        <button onclick=" UE.getEditor(\'editor\').setShow()">显示编辑器</button>
        <button onclick=" UE.getEditor(\'editor\').setHeight(300)">设置高度为300默认关闭了自动长高</button>
    </div>

    <div>
        <button onclick="getLocalData()" >获取草稿箱内容</button>
        <button onclick="clearLocalData()" >清空草稿箱</button>
    </div>

</div>
<div>
    <button onclick="createEditor()">
    创建编辑器</button>
    <button onclick="deleteEditor()">
    删除编辑器</button>
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
			#echo $res[0]['book_content'];
			#filter some keywords!
			echo cleanBookContent($res[0]['book_content']);
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
}

/*
* not worked
*/
function downloadBook2Word($filename, $content)
{
	header('pragma:public');
    header('Content-type:application/vnd.ms-word;charset=utf-8;name="'.$filename.'".doc');
    header('Content-Disposition:attachment;filename='.$filename.'.doc');
  	save2WordLocal($content);
}


/*
* a replace function to edit the text using RegularExpresion
*/
function replaceHtml()
{
	echo '
	<div class="panel" id="replace">
			<div>辅助编辑功能：</div>
			<div>字符串批量替换功能（正则表达式）</div>
            <table>
                <tr>
                    <td width="240">原字符串（正则表达式）: </td>
                    <td><input id="findtxt1" type="text" class="int"/></td>
                </tr>
                <tr>
                    <td>替换新字符串: </td>
                    <td><input id="replacetxt" type="text" class="int" /></td>
                </tr>
                <tr>
                    <td>区分大小写</var></td>
                    <td>
                        <input id="matchCase1" type="checkbox" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input id="repalceAllBtn" type="button" class="btn" value="全部替换" onclick="replace_str()"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span id="replace-msg" style="color:red"></span>
                    </td>
                </tr>
				<tr>
					<td colspan="2">
						<span id="example" style="color:red">例如：正则表达式"\\\\pa\d+.bmp\\\\r"可匹配所有类似"\pa1.bmp\r"的字符串。</span>
					</td>
				</tr>
            </table>
        </div>';
}
function showReplaceFunc()
{
	replaceHtml();	
}
