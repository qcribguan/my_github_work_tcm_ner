<?php

/**
 * Show all the entity in our system
 * 
 */
 
function getAllBookList()
{
	$book_excpet_list = array("content.json");
	$book_name_arr = array();
	
    $path = getBookPath();	
	$handle = opendir($path);	
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
	}
	
	return $book_name_arr;
}


//this function can be used to optimize the db query time in entityPanelContent()
function tmp_bak()
{
	$base_disease_arr = db_query_base_entity($types[0]);
	$base_material_arr = db_query_base_entity($types[1]);
	$base_prescription_arr = db_query_base_entity($types[2]);
	
	$base_disease = count($base_disease_arr);
	$base_material = count($base_material_arr);
	$base_prescription = count($base_prescription_arr);
	$base_total = $base_disease + $base_material + $base_prescription;


	$auto_disease = count(db_query_all_auto_entity($types[0]));
	$auto_material = count(db_query_all_auto_entity($types[1]));
	$auto_prescription = count(db_query_all_auto_entity($types[2]));
	$auto_total = $auto_disease + $auto_material + $auto_prescription;


	$proof_disease_arr = db_query_proof_entity($types[0]);
	$proof_material_arr = db_query_proof_entity($types[1]);
	$proof_prescription_arr = db_query_proof_entity($types[2]);
	
	$proof_disease = count($proof_disease_arr);
	$proof_material = count($proof_material_arr);
	$proof_prescription = count($proof_prescription_arr);
	$proof_total = $proof_disease + $proof_material + $proof_prescription;	
	
	
	$total_disease = count(array_merge($base_disease_arr, $proof_disease_arr));
	$total_material = count(array_merge($base_material_arr, $proof_material_arr));
	$total_prescription = count(array_merge($base_prescription_arr, $proof_prescription_arr));
	$total_total = $total_disease + $total_material + $total_prescription;	
}
 
function entityPanelContent()
{
	$types = array("disease", "material", "prescription");

	$base_disease = count(db_query_base_entity($types[0]));
	$base_material = count(db_query_base_entity($types[1]));
	$base_prescription = count(db_query_base_entity($types[2]));
	$base_total = $base_disease + $base_material + $base_prescription;


	$auto_disease = count(db_query_all_auto_entity($types[0]));
	$auto_material = count(db_query_all_auto_entity($types[1]));
	$auto_prescription = count(db_query_all_auto_entity($types[2]));
	$auto_total = $auto_disease + $auto_material + $auto_prescription;

	$proof_disease = count(db_query_proof_entity($types[0]));
	$proof_material = count(db_query_proof_entity($types[1]));
	$proof_prescription = count(db_query_proof_entity($types[2]));
	$proof_total = $proof_disease + $proof_material + $proof_prescription;	
	
	$total_disease = count(array_merge(db_query_base_entity($types[0]), db_query_proof_entity($types[0])));
	$total_material = count(array_merge(db_query_base_entity($types[1]), db_query_proof_entity($types[1])));
	$total_prescription = count(array_merge(db_query_base_entity($types[2]), db_query_proof_entity($types[2])));
	$total_total = $total_disease + $total_material + $total_prescription;	
	
	

	echo '<div>系统总实体数量</div>';
	echo '<div><table border="1px" bordercolor="#CCCCCC" cellspacing="0px">
  <tr align=center>
    <td><span style="width: 40%;">种类</span></td>
    <td align="center" style="width: 15%;">疾病</td>
    <td align="center" style="width: 15%;">方剂</td>
    <td align="center" style="width: 15%;">中草药</td>
    <td align="center" style="width: 15%;">总计</td>
  </tr>
  <tr align=center>
    <td>系统原始词库实体</td>
    <td>'.$base_disease.'</td>
    <td>'.$base_material.'</td>
    <td>'.$base_prescription.'</td>
    <td>'.$base_total.'</td>
  </tr>
  <tr align=center>
    <td>系统自动抽取实体</td>
    <td>'.$auto_disease.'</td>
    <td>'.$auto_material.'</td>
    <td>'.$auto_prescription.'</td>
    <td>'.$auto_total.'</td>
  </tr>
  <tr align=center>
    <td>人工校正实体（含系统智能推荐和人工校正）</td>
    <td>'.$proof_disease.'</td>
    <td>'.$proof_material.'</td>
    <td>'.$proof_prescription.'</td>
    <td>'.$proof_total.'</td>
  </tr>
  <tr align=center>
    <td>系统现有实体总计</td>
    <td>'.$total_disease.'</td>
    <td>'.$total_material.'</td>
    <td>'.$total_prescription.'</td>
    <td>'.$total_total.'</td>
  </tr>';
  
	echo '</table></div>';
	echo '<br />';



	/*其中$filename = readdir($handler)
	每次循环时将读取的文件名赋值给$filename，$filename !== false。
	一定要用!==，因为如果某个文件名如果叫'0’，或某些被系统认为是代表false，用!=就会停止循环
	*/
	echo '<div>单本古籍实体数量</div>';
	echo '<div><table border="1px" bordercolor="#CCCCCC" cellspacing="0px">
  <tr align=center>
    <td rowspan="2" style="width: 5%;">序号</td>
    <td rowspan="2" style="width: 27%;">书名</td>
    <td colspan="4">系统自动抽取实体</td>
    <td colspan="4">人工校正实体（含系统智能推荐和人工校正）</td>
  </tr>
  <tr align=center>
    <td style="width: 8%;">疾病</td>
    <td style="width: 8%;">中草药</td>
    <td style="width: 8%;">方剂</td>
    <td style="width: 10%;">总计</td>
    <td style="width: 8%;">疾病</td>
    <td style="width: 8%;">中草药</td>
    <td style="width: 8%;">方剂</td>
    <td style="width: 10%;">总计</td>
  </tr>';

	$book_name_arr = getAllBookList();
	
	$bh = new bihua();
	$book_name_arr_ordered = $bh->sortBihuaFirstTwoCh($book_name_arr);
	$index = 0;
	foreach ($book_name_arr_ordered as $key => $book_name){		
		$update_t = query_correct_version($book_name);
		$filename = $book_name.".json";
		$index += 1;
		
		$auto_disease = count(db_query_entity($book_name, $types[0]));
		$auto_material = count(db_query_entity($book_name, $types[1]));
		$auto_prescription = count(db_query_entity($book_name, $types[2]));
		$auto_total = $auto_disease + $auto_material + $auto_prescription;

		$proof_disease = count(db_query_entity_correct($book_name, $types[0]));
		$proof_material = count(db_query_entity_correct($book_name, $types[1]));
		$proof_prescription = count(db_query_entity_correct($book_name, $types[2]));
		$proof_total = $proof_disease + $proof_material + $proof_prescription;		
		
		
		echo '<tr><td>'.$index.'</td>';
		echo '<td><a href="/'.(null !== config('site_home') && config('site_home') != ''? config('site_home').'/' : '').config('book_entity').'?book='.$filename.'&flag='.(null !== $update_t ? true : false).'">'.$book_name.'</a></br></td>';
		echo '<td>'.$auto_disease.'</td>';
		echo '<td>'.$auto_material.'</td>';
		echo '<td>'.$auto_prescription.'</td>';
		echo '<td>'.$auto_total.'</td>';
		echo '<td>'.$proof_disease.'</td>';
		echo '<td>'.$proof_material.'</td>';
		echo '<td>'.$proof_prescription.'</td>';
		echo '<td>'.$proof_total.'</td>';			
		echo '</tr>';
				
	}		
		
	echo '</table></div>';
}

