<?php
echo "古籍实体抽取";

//require 'book_proof.php';
$book = isset($_GET['book']) ? $_GET['book'] : null;
$flag = isset($_GET['flag']) ? $_GET['flag'] : null;
//echo $book;
//echo $flag;


//Do the submit task first;
//foreach ($_POST as $key => $val){
//	echo $key.'='.$val.'<br>';
//}
if (isset($_POST["entity_correct"])) {
	$en_arr = array(
		"disease" => array(),
    	"material" => array(),
    	"prescription" => array(),
	);

	$arr = explode(";", trim($_POST["entity_correct"]));
	foreach ($arr as $key => $val){
		$tmp_arr = explode(":", $val);
		if (count($tmp_arr)<2){
			continue;
		}
		
		$type = explode(":", $val)[0];
		$entity = explode(":", $val)[1];
		//echo gettype($en_arr);
		//echo gettype($en_arr["disease"]);
		array_push($en_arr[$type], $entity); 
	}
	
	//echo "<br />";
	foreach ($en_arr as $key => $val){
		if (count($val)==0){
			//only delete old entities
			db_save_entity_correct(cleanBookName($book), $key, $val, 1);
			continue;
		}

		//delete old and save the new
		db_save_entity_correct(cleanBookName($book), $key, $val);
	}
	echo '<script type="text/javascript">alert("保存成功！")</script>';
}


//Then, load the page;
//show json book content in editor, and save button;
editorContent($book, $flag);
setEditorDisabled();
hiddenButton();

//show entiry extraction;
entityContent(cleanBookName($book), $flag);

//load some element
loadTypeChangeMenu();



//load the AI rules last
if (isset($_POST["entity_original"]) && trim($_POST["entity_original"]) !="") {
	//echo "entity_original <br>";
	//echo $_POST["entity_original"]."<br>";

	$en_total = array();
	$en_arr = array(
		"disease" => array(),
    	"material" => array(),
    	"prescription" => array(),
	);

	$arr = explode(";", $_POST["entity_original"]);
	//echo count($arr);	

	foreach ($arr as $key => $val){
		$tmp_arr = explode(":", $val);
		if (count($tmp_arr)<2){
			continue;
		}
		
		$type = explode(":", $val)[0];
		$entity = explode(":", $val)[1];

		//array_push($en_arr[$type], $entity);
		array_push($en_total, $entity);
	}
	//echo "<br />";
	//echo "total_uncorrected_predicted_entity: ".count($en_total)."<br>";

	//Rule1: check from DB
	foreach($en_arr as $key => $val){
		//echo "total_uncorrected_predicted_entity: ".count($en_total)."<br>";
		
		$db_total = db_query_base_entity($key);
		//echo $key." DB total: ".count($db_total);
		
		$en_arr[$key] = array_intersect($en_total, $db_total); 
		//echo " AI_get_new_".$key."_entity: ".count($en_arr[$key])."<br>";
		
		$en_total = array_diff($en_total, $en_arr[$key]);
	}


	//Rule2: check using keywords (suffix)
	//echo "total_uncorrected_predicted_entity: ".count($en_total)."<br>";
	
	//check the prescription first, then material;
	$en_arr2 = array("prescription", "material", "disease");
	foreach ($en_arr2 as $num => $type){
		//echo $key.":".count($val)."|";
		//print_arr($val);
		if (config($type.'_suffix_keywords') != null){
			foreach ($en_total as $k=>$v){
				//echo $v.' ';
				foreach (config($type.'_suffix_keywords') as $ind=>$kw){
					if (strrchr($v, $kw) == $kw){
						//echo $v."|";
						array_push($en_arr[$type], $v);
					}					
				}
			}
		}
		//echo "<br />";
	}
	
	//show the corrected entities on the webpage;
	foreach($en_arr as $key => $val){
		recommand_entity($key, $val);
		//print_arr($val);
	}
	/*echo '<script type="text/javascript">alert("请查看右侧新推荐实体！")</script>';*/
}


?>