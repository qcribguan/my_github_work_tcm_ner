<?php

/*
* Get current time
*/
function get_current_time()
{
	date_default_timezone_set("Asia/Shanghai");
    return date('Y-m-d H:i:s', time());
}

/*
* print an array
*/
function print_arr($arr)
{
	foreach($arr as $key=>$val){
		echo $key."=>".$val;
	}
	echo "<br>";
}

/*
* Call Javascript alert()
*/
function js_alert($str)
{
	echo '<script type="text/javascript">alert("'.$str.'")</script>';
}

/*
* DB related
*/
function sql_query_str($table, $col_name, $value)
{
	return "SELECT * FROM ".$table." WHERE ".$col_name." = '".$value."';";
}

function sql_query_str2($table, $col_name, $value, $res_col)
{
	$col_str = "";
	if (is_array($res_col)){
		foreach ($res_col as $col) {
			$col_str .= $col.",";
		}
		$col_str = rtrim($col_str, ',');
	}
	else{
		$col_str = $res_col;
	}
	
	return "SELECT ".$res_col." FROM ".$table." WHERE ".$col_name." = '".$value."';";
}

/*
* @$colum_str: [book_name, book_content, update_at, create_at]
* @$value_str: ['test1','teststring','2018-08-05 17:44:28','2018-08-05 17:44:28']
*/
function sql_insert_str($table, $colum_array, $value_array)
{
	$col_str = "";
	foreach ($colum_array as $value) {
		$col_str .= $value.",";
	}
	$col_str = rtrim($col_str, ','); 
	
	$val_str = "";
	foreach ($value_array as $value) {
		$val_str .= "'".$value."',";
	}
	$val_str = rtrim($val_str, ','); 
	
	return "INSERT INTO ".$table."(".$col_str.") VALUES (".$val_str.");";
}

/*
* insert ignore into set_book_expert_relation(A,B,C) values ("A_val", "B_val", "C_val"),("A_val2", "B_val2", "C_val2");
*
*/
function sql_insert_str_with_ignore($table, $colum_array, $records_list_str)
{
	$col_str = "";
	foreach ($colum_array as $value) {
		$col_str .= $value.",";
	}
	$col_str = rtrim($col_str, ','); 
	
	return "INSERT IGNORE INTO ".$table."(".$col_str.") VALUES ".$records_list_str.";";
}

/*
* delete data from table according to one colum;
*
*/
function sql_delete_str($table, $col, $col_value)
{
	return "DELETE FROM ".$table." WHERE ".$col."='".$col_value."';";
}

///TODO
function sql_delete_str2($table, $col_arr, $col_value_arr)
{
	$cond = "";
	//foreach ($colum_array as $value) {
	//	$col_str .= $value.",";
	//}
	//$col_str = rtrim($col_str, ','); 
	
	return "DELETE FROM ".$table." WHERE ".$cond.";";
}


/*
* @$colum_array: 
* @$value_array: 
* @$cond: book_name='test'
*/
function sql_update_str($table, $colum_array, $value_array, $cond)
{
	$update_str = "";
	foreach($colum_array as $key=>$value){
		$update_str .= $value."='".$value_array[$key]."',";	
	}
	$update_str = rtrim($update_str, ','); 

	return "UPDATE ".$table." SET ".$update_str." WHERE ".$cond.";";
}


/*
* check of current OS is Windows or Linux
*/
function is_os_linux()
{
	$os_name=PHP_OS;
	if(strpos($os_name,"Linux")!==false){
    	return true;
	}else if(strpos($os_name,"WIN")!==false){
		return false;
	}
}
function check_os()
{
	$os_name=PHP_OS;
	if(strpos($os_name,"Linux")!==false){
    	$os_str="Linux操作系统";
	}else if(strpos($os_name,"WIN")!==false){
    	$os_str="Windows操作系统";
	}else{
		$os_str="error";
	}
	return $os_str;
}



/**
 * utf-8 转unicode
 * @param string $name
 * @return string
 */
function myutf8_unicode($name){
    $name = iconv('UTF-8', 'UCS-2BE', $name);
    $len  = strlen($name);
    $str  = '';
    for ($i = 0; $i < $len - 1; $i = $i + 2){
        $c  = $name[$i];
        $c2 = $name[$i + 1];
        if (ord($c) > 0){
            $str .= '\u'.base_convert(ord($c), 10, 16).str_pad(base_convert(ord($c2), 10, 16), 2, 0, STR_PAD_LEFT);
        } else {
            $str .= '\u'.str_pad(base_convert(ord($c2), 10, 16), 4, 0, STR_PAD_LEFT);
        }
    }
    return $str;
}

/**
 * unicode 转 utf-8
 *
 * @param string $name
 * @return string
 */
function myunicode_decode($name)
{
    $name = strtolower($name);
    // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $name, $matches);
    if (! empty($matches)) {
        $name = '';
        for ($j = 0; $j < count($matches[0]); $j ++) {
            $str = $matches[0][$j];
            if (strpos($str, '\\u') === 0) {
                $code = base_convert(substr($str, 2, 2), 16, 10);
                $code2 = base_convert(substr($str, 4), 16, 10);
                $c = chr($code) . chr($code2);
                $c = iconv('UCS-2BE', 'UTF-8', $c);
                $name .= $c;
            } else {
                $name .= $str;
            }
        }
    }
    return $name;
}


function tosize($bytes,$prec=2){
    $rank=0;
    $size=$bytes;
    $unit="B";
    while($size>1024){
        $size=$size/1024;
        $rank++;
    }
    $size=round($size,$prec);
    switch ($rank){
        case "1":
            $unit="KB";
            break;
        case "2":
            $unit="MB";
            break;
        case "3":
            $unit="GB";
            break;
        case "4":
            $unit="TB";
            break;
        default :

    }
    return $size." ".$unit;
 }
 
function filesize_h($filename){
	return tosize(filesize($filename));
}

function read_re_rule_file($file_path){
	if(file_exists($file_path)){
		$fp = fopen($file_path,"r");
		$str = fread($fp,filesize($file_path));//指定读取大小，这里把整个文件内容读取出来
		return str_replace("\r\n","<br />",$str);
	}else{
		echo "Warning: regular rules file is not found!";
		return "";
	}
}

function save_re_rules_file($file_path, $content){
	$fh = fopen($file_path, "w");
	$ret= fwrite($fh, $content);
	if($ret > 0){
		//echo "Save rules successfully!";
		echo '<span style="color:red">替换规则保存成功，请刷新文本编辑页面重新加载文本！</span>';
	}
	fclose($fh);
}

function read_file($file_path){
	if(file_exists($file_path)){
		$fp = fopen($file_path,"r");
		$str = fread($fp,filesize($file_path));//指定读取大小，这里把整个文件内容读取出来
		#return str_replace("\r\n"," ",$str);
		return trim($str);
	}else{
		echo "Warning: regular rules file is not found!";
		return "";
	}
}

?>