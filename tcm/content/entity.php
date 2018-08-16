<?php

/*
* It should be put betweeen "<style type="text/css">" and "</style>"
*/
function entityStyle()
{
	echo "css/entity.css";
}

/*
* make sure user cannot edit the content of the book in book_entity page.
*/
function setEditorDisabled()
{
	echo '<script type="text/javascript">
        UM.getEditor("myEditor").setDisabled("fullscreen");
        //disableBtn("enable");
		</script>';
}

function hiddenButton()
{
	echo '<script type="text/javascript">
		var btn = document.getElementById("save1");
       	btn.style.display = "none";
	</script>';
}


$flag = array("disease", "material", "prescription");
$num = ['disease' => 0,
		'material' => 0,
		'prescription' => 0,
		];
$l_res = array();
$r_res = array();
//left side entity is already in the right side entity list
$l_in_r_res = array();
$l_in_r_res_no_type = array();


function html_entity_block_one_left($color = "#CCCCCC", $value = "new_add", $flag = "left")
{
	$fun_onclick = "sendEntity2RightSide(this)";
	//return '<input type="button" id="btn_entity_'.$value.'" style="background:'.$color.';height:22px;border:0px #9999FF none;" onmouseover="" onclick="'.$fun_onclick.'" value="'.$value.'">&nbsp';
	return '<input type="button" id="btn_entity_'.$value.'" style="background:'.$color.';height:22px;border:2px '.$color.' solid;" onmouseover="" onclick="'.$fun_onclick.'" value="'.$value.'">&nbsp';
}

function html_entity_block_one_right($color = "#CCCCCC", $value = "new_add", $flag = "left")
{
	//$fun_onmouseover = "changeColor(this, 1)";
	//$fun_onmouseout = "changeColor(this, 0)";
	$fun_onmouseover = "changeColorMenu(this)";
	$fun_onmouseout = "";	
	return '<input type="button" id="btn_entity_new_'.$value.'" style="background:'.$color.';height:22px;border:0px #9999FF none;" onmouseover="'.$fun_onmouseover.'" onmouseout="'.$fun_onmouseout.'" value="'.$value.'">&nbsp';
}


function html_entity_block_left($arr, $type)
{
	global $num;
	global $l_in_r_res;
	global $l_in_r_res_no_type;
	global $flag;
	
	$flag_flip = array_flip($flag);

	$cnt = 0;
	foreach ($arr as $key => $val){
		$cnt += 1;
		//if (in_array($val, $l_in_r_res[$flag_flip[$type]])){
		if (in_array($val, $l_in_r_res_no_type)){
			echo html_entity_block_one_left(config("default"), $val);
		}
		else{
			echo html_entity_block_one_left(config($type), $val);
		}
	}
	$num[$type] = $cnt;
}

function html_entity_block_right($arr, $type)
{
	global $num;

	//$cnt = 0;
	foreach ($arr as $key => $val){
		//$cnt += 1;
		echo html_entity_block_one_right(config($type), $val);
	}
	//$num[$type] = $cnt;
}

function entityContentLeftSide($book)
{
	global $flag;
	global $l_res;
	
	echo '<div id="left_div_entity" class="entity-container" style="overflow:scroll; width:300px;height:600px; border: 1px solid #CCCCCC;">';
	foreach ($l_res as $key => $val){
		html_entity_block_left($val, $flag[$key]);
	}
	
	echo '</div>';
}

function entityContentRightSide($book)
{
	global $flag;
	global $r_res;
	
	echo '

	<div id="right_div_entity" class="entity-container" style="overflow:scroll; width:300px;height:600px; margin:30px auto;">';
	foreach ($r_res as $key => $val){
		html_entity_block_right($val, $flag[$key]);
	}
	
	echo '</div>';
}


function entityQueryDb($book)
{
	global $flag;
	global $l_res;
	global $r_res;	
	global $l_in_r_res;	
	global $l_in_r_res_no_type;
	
	//read automatically generated entities;
	$left_entity_all = array();
	foreach ($flag as $key => $val){
		$l_res[] = db_query_entity($book, $val);
		
		$left_entity_all = array_merge($l_res[$key], $left_entity_all);
	}
	
	//read corrected entities;
	$right_entity_all = array();
 	foreach ($flag as $key => $val){
		$r_res[] = db_query_entity_correct($book, $val); 
		
		$l_in_r_res[] = array_intersect($l_res[$key], $r_res[$key]);
		
		$right_entity_all = array_merge($r_res[$key], $right_entity_all);
	}
	
	$l_in_r_res_no_type = array_intersect($left_entity_all, $right_entity_all);
}


function entityContent($book, $flag)
{
	global $flag;
	global $num;
	
	#get the data
	entityQueryDb($book);
	
	
	echo '<div><table style="width:630px;"><tr>';
	echo '<td>自动化抽取的实体：</td>';
	echo '<td>校对后的实体：</td>';
	echo '</tr><tr><td>';
	
	echo '<form id="left_entity_form" name="left_entity_form" action="" method="post">';
	//echo '<td>'.entityContentLeftSide($book).'</td>';
	entityContentLeftSide($book);
	echo '<input id="entity_original" type="hidden" name="entity_original" value="" />';
	echo '</form>';
	
	echo '</td><td>';
	echo '<form id="right_entity_form" name="right_entity_form" action="" method="post">';
	entityContentRightSide($book);
	echo '<input id="entity_correct" type="hidden" name="entity_correct" value="" />';
	//echo '<input id="btitle" type="hidden" name="btitle" value="'.$book.'" />';
	//echo '<input id="flag" type="hidden" name="flag" value="save" />';
	echo '</form>';
	//echo '<td>'.entityContentRightSide($book).'</td>';
	echo '</td></tr>';
	echo '<tr><td></td>';
	echo '<td><div align="right">
			<button id="save_entity" class="btn" onClick="btn_recommand_entity()">实体推荐</button>&nbsp;
			<button id="save_entity" class="btn" onClick="btn_save_entity()">保存实体</button>&nbsp';
	echo '</div></td></tr>';
	echo '<tr>';
	echo '<td>说明：<input type="button" id="btn_entity" style="background:'.config($flag[0]).'; width:40px;height:18px;border:1px #9999FF none;" value=""> 疾病（'.(isset($num[$flag[0]]) ? $num[$flag[0]] : 0).'）、<input type="button" id="btn_entity" style="background:'.config($flag[1]).'; width:40px;height:18px;border:1px #9999FF none;" value=""> 中草药（'.(isset($num[$flag[1]]) ? $num[$flag[1]] : 0).'）、<input type="button" id="btn_entity" style="background:'.config($flag[2]).'; width:40px;height:18px;border:1px #9999FF none;" value=""> 方剂（'.(isset($num[$flag[2]]) ? $num[$flag[2]] : 0).'）<br>请点击正确实体，移至右侧。</td>';
	echo '<td>说明：鼠标箭头指向实体，在右侧菜单改变实体类型或者删除，或用左右键改变类型或删除。校正完毕请点击保存实体。</td>';
	echo '</tr>
	  	  <tr><td colspan="2">
		  <div id="ai_entity_num" style="display:none;color:#F00">
		  注：系统为您自动识别方剂实体：<label id="prescription_ai_num">0</label>个；自动识别中草药实体：<label id="material_ai_num">0</label>个；自动识别疾病实体：<label id="disease_ai_num">0</label>个。
		  </div>
		  &nbsp;</td>
		  </tr>
	</table></div>';
	
	echo '
	<script type="text/javascript">
		function btn_submit3() {
		//UM.getEditor("myEditor").sync();
		//document.getElementById("flag").value = "export";
		//document.book_content_form.action="1.htm"
		var myform=document.getElementById("book_content_form");
		myform.action="'.config("content_path").'/'.config("book_download").'"
  		myform.submit();
		}

		function add_new_entity(value, color){
			var btn = document.getElementById("btn_entity_new_" + value);
			if(btn!=null){
				return "";
			}else{
				return "<input type=\'button\' id=\'btn_entity_new_" + value + "\' style=\'background:" + colorRGB2Hex(color) + ";height:22px;border:0px #9999FF none;\' onmouseover=\'changeColorMenu(this)\' onmouseout=\'\' value=\'" + value + "\'>&nbsp";
			}
		}

		function sendEntity2RightSide(btu){
			//alert(btu.value);
			var color = btu.style.background;
			//alert(color);
			btu.style.background = "'.config("default").'";
			var right_div = document.getElementById("right_div_entity");
			right_div.innerHTML += add_new_entity(btu.value, color);
		}
		function leftEntityOver(){
			//alert("点击实现实体移动到右边！")
		}
	</script>';
}


/*
* Show recommanded entities on the corrected side;
*	$en_arr = array(
*		"disease" => array(),
*    	"material" => array(),
*    	"prescription" => array(),
*	);
*/
function recommand_entity($type, $entity_arr)
{
	$new_ai_entity = "";
	foreach ($entity_arr as $key => $val){
		if (trim($val) != ""){
			$new_ai_entity .= $val."-".config($type)."|";
		}
	}
	
	echo '<script type="text/javascript">
		var content = document.getElementById("ai_entity_num");
		content.style.display="block";
		
		load_ai_entity_recommand("'.$type.'", "'.$new_ai_entity.'", "'.config("default").'");
		</script>';
}

/*
* It should be put betweeen "<script type="text/javascript">" and "</script>"
*/
function entityJs()
{
	echo "js/entity.js";
}
function utilsJs()
{
	echo "js/utils.js";
}


/*
*  generate a js varible
*  var dic_json = { "disease":"#99CCFF", "material":"#99FF99", "prescription":"#FFCC66", "delete":"#CCCCCC", "add":""};	
*/
function loadJsDefinition()
{
	return;
}


/*
* show the type change menu when you put mouse on the entity button. Then, you can assign the new type for the entity
* TODO: change the hard coding;
*/
function loadTypeChangeMenu()
{
	$order = array("disease", "material", "prescription", "default");
	
	echo '<div id="tips1" style="display:none;position:absolute;border:2px solid #bbb;background-color:#bbb;padding:0px">';
	//<input id="disease" type="button" style="background:#99CCFF; width:60px;height:20px;border:0px #CCCCCC none;" onClick="changeEntityType(this)" onmouseout="" value="疾病"><br>
	//<input id="material" type="button" style="background:#99FF99; width:60px;height:20px;border:0px #CCCCCC none;" onClick="changeEntityType(this)" onmouseout="" value="中草药"><br>
	//<input id="prescription" type="button" style="background:#FFCC66; width:60px;height:20px;border:0px #CCCCCC none;" onClick="changeEntityType(this)" onmouseout="" value="处方"><br>
	//<input id="delete" type="button" style="background:#CCCCCC; width:60px;height:20px;border:0px #CCCCCC none;" onClick="changeEntityType(this)" onmouseout="" value="删除"><br>
	foreach ($order as $key => $val){
		echo '<input id="'.$val.'" type="button" style="background:'.config($val).'; width:60px;height:20px;border:0px #CCCCCC none;" onClick="changeEntityType(this)" onmouseout="" value="'.config($val.'_m').'"><br>';
	}
	
	echo '<!--<hr align=center height:1px color=#987cb9 size=1>
	<input id="add" type="button" style="background:; width:60px;height:20px;border:0px #9999FF none;" onClick="changeType(this)" onmouseout="" value="添加"><br>-->
	</div>';
}


/*
* provide a book name, query all of the entities generated automatically;
* entity_expert:
* entity_disease:
* entity_material:
* entity_prescription:
*
* $tital: book name
* $entity_type: disease, material, prescription, expert
*/
function db_query_entity($title, $entity_type)
{
	$table = config('dbtab_auto_book_entity_'.$entity_type);
	$col_array = array("base_entity", "related_entity", "path", "paragraph");
		
	try{
		$DB = new DBPDO();
		//check if this book is already in the database;
		$sql_str = sql_query_str2($table, $col_array[0], $title, $col_array[1]);
		//echo $sql_str;
		$res = $DB->fetchAll($sql_str);
		if (sizeof($res) == 0) {
			return array();
		}
		else {
			$res_array = array();
			foreach ($res as $key => $val){
				$res_array[] = $val['related_entity'];
			}
			$res_array_uniq = array_unique($res_array);
						
			//foreach ($res_array_uniq as $key => $val){
			//	echo $key.'--'.$val;
			//}
			
			return $res_array_uniq;
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
}

function db_query_entity_correct($title, $entity_type)
{
	$table = config('dbtab_book_entity_'.$entity_type);
	$col_array = array("base_entity", "related_entity", "path", "paragraph");
		
	try{
		$DB = new DBPDO();
		//check if this book is already in the database;
		$sql_str = sql_query_str2($table, $col_array[0], $title, $col_array[1]);
		//echo $sql_str;
		$res = $DB->fetchAll($sql_str);
		if (sizeof($res) == 0) {
			return array();
		}
		else {
			$res_array = array();
			foreach ($res as $key => $val){
				$res_array[] = $val['related_entity'];
			}
			$res_array_uniq = array_unique($res_array);
						
			//foreach ($res_array_uniq as $key => $val){
			//	echo $key.'--'.$val;
			//}
			
			return $res_array_uniq;
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
}

/*
* after correct the entities, save them into DB; (1. delete all of entities from this book, 2. save current entities)
* entity_expert:
* entity_disease:
* entity_material:
* entity_prescription:
*
* $tital: book name
* $entity_type: disease, material, prescription, expert
*/
function db_save_entity_correct($title, $entity_type, $entity_set, $only_delete_flag = 0)
{
	$table = config('dbtab_book_entity_'.$entity_type);
	$col_array = array("create_at", "update_at", "base_entity", "related_entity", "paragraph", "path", "position");
	
	//1. delete all of the eneities from this table
	try{
		$DB = new DBPDO();	
		$sql_str = sql_delete_str($table, $col_array[2], $title);
		$ret = $DB->execute($sql_str);
		/*echo '<script type="text/javascript">alert("'.$table.'删除成功！")</script>';*/
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
	
	if ($only_delete_flag){
		return;
	}
	
	//2. insert current entities;
	//insert ignore into set_book_expert_relation(create_at, update_at, base_entity, related_entity, paragraph, path, position) values ("0000-00-00 00:00:00", "0000-00-00 00:00:00", "书3","实体1","0","0","0"),("0000-00-00 00:00:00", "0000-00-00 00:00:00", "书3","实体2","0","0","0");
	$t = get_current_time();
	$records_list_str = "";
	foreach($entity_set as $key => $val){
		$records_list_str .= '("'.$t.'", "'.$t.'", "'.$title.'", "'.$val.'", "0", "0", "0"),';
	}
	$records_list_str = rtrim($records_list_str, ','); 
	//echo "test:||".$records_list_str;
	
	try{
		$DB = new DBPDO();		
		$sql_str = sql_insert_str_with_ignore($table, $col_array, $records_list_str);		
		//echo "test:||".$sql_str;

		//return;		
		$ret = $DB->execute($sql_str);
		//echo $DB->lastInsertId();
		/*echo '<script type="text/javascript">alert("保存成功！")</script>';*/
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
}


/*
* Query all the disease, material and prescription from our DB
*/
function db_query_base_entity($entity_type)
{
	$table = config('dbtab_'.$entity_type);
	$field = config('dbtab_'.$entity_type.'_namefield');
		
	try{
		$DB = new DBPDO();
		//check if this book is already in the database;
		$sql_str = 'SELECT '.$field.' FROM '.$table;
		//echo $sql_str;
		$res = $DB->fetchAll($sql_str);
		if (sizeof($res) == 0) {
			return array();
		}
		else {
			$res_array = array();
			foreach ($res as $key => $val){
				$res_array[] = $val[$field];
			}
			$res_array_uniq = array_unique($res_array);
						
			//foreach ($res_array_uniq as $key => $val){
			//	echo $key.'--'.$val;
			//}
			
			return $res_array_uniq;
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
}

/*
* Query all the disease, material and prescription from our DB
* [not used]
*/
function db_query_proof_entity($entity_type)
{
	$table = config('dbtab_book_entity_'.$entity_type);
	$field = config('dbfield_name');
		
	try{
		$DB = new DBPDO();
		//check if this book is already in the database;
		$sql_str = 'SELECT '.$field.' FROM '.$table;
		//echo $sql_str;
		$res = $DB->fetchAll($sql_str);
		if (sizeof($res) == 0) {
			return array();
		}
		else {
			$res_array = array();
			foreach ($res as $key => $val){
				$res_array[] = $val[$field];
			}
			$res_array_uniq = array_unique($res_array);
						
			//foreach ($res_array_uniq as $key => $val){
			//	echo $key.'--'.$val;
			//}
			
			return $res_array_uniq;
		}
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
}

