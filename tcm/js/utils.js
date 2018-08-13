// JavaScript Document

function add_new_entity2(value, color){
	var btn = document.getElementById("btn_entity_new_" + value);
	if(btn!=null){
		return "";
	}else{
		return "<input type='button' id='btn_entity_new_" + value + "' style='background:" + color + ";height:22px;border:0px #9999FF none;' onmouseover='changeColorMenu(this)' onmouseout='' value='" + value + "'>&nbsp";
	}
}
		
//used to load the AI recommanded entities
function load_ai_entity_recommand(type, entity_list, default_color){
	//show the new
	if (entity_list == ""){
		return;
	}
	
	var right_div = document.getElementById("right_div_entity");
	var left_div = document.getElementById("left_div_entity");
	
	var right_side_html = "";
	var left_side_html = left_div.innerHTML;
	var arr = entity_list.split("|");
	var cnt = 0;
	for (var i=0;i<arr.length;i++){
		entity = arr[i].split("-")[0];
		color = arr[i].split("-")[1];
		if (entity == ""){
			continue;
		}
		
		cnt += 1;
		//add new entity to right side
		right_side_html += add_new_entity2(entity, color);		
		
		//change the color of the left	
		old_str = 'id="btn_entity_' + entity + '" style="background:(#[0-9,a-z,A-Z]+);';
		new_str = 'id="btn_entity_' + entity + '" style="background:' + default_color + ';';
		left_side_html = left_side_html.replace(new RegExp(old_str, 'g'), new_str);		
	}	

	right_div.innerHTML += right_side_html;
	left_div.innerHTML = left_side_html;
	
	var content = document.getElementById("ai_entity_num");
	content.style.display="block";
	document.getElementById(type + "_ai_num").innerHTML = cnt;
}
