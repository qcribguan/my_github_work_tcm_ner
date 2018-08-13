<?php

/*
* TODO: Query all the corrected disease, material and prescription entities from our DB
*/
function db_query_all_correct_entity($entity_type)
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

