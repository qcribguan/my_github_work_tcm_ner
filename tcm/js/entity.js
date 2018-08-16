// JavaScript Document


//TODO: generate by php
var dic_array = ["disease", "material", "prescription", "delete", "add"];
var dic_json = {"disease":"#99CCFF", "material":"#99FF99", "prescription":"#FFCC66", "delete":"#CCCCCC", "add":""};

//show the type change menu when you put mouse on the entity button.
//then, you can assign the new type for the entity
function changeColorMenu(btn){
	//var btn = document.getElementsById("input5");
	//var item = document.getElementsById("tips1");
	//tips1.innerHTML="你的鼠标指针所在的文字是："+btn.value;
	var content = document.getElementById("tips1");
	//alert(content.getAttribute("name"));
	var menu = content.getElementsByTagName("input");
	//alert(menu.length)
	for(var i=0;i<menu.length;i++){
		//menu[i].id = btn.id+"_"+menu[i].id;
		menu[i].id = btn.id+"#"+dic_array[i];
		//alert(menu[i].id+menu[i].value);
	}
	//alert(content.getAttribute("name"));
	//alert(tips1.style.display);
	content.style.display="block";
	var par = btn.offsetParent;
	content.style.left=par.offsetLeft+btn.offsetLeft+btn.offsetWidth+"px";
	content.style.top=par.offsetTop-par.scrollTop+btn.offsetTop+"px";
	
	//Second method to change the type: use up and down arrow on keyboard
	changeEntityTypeByKb(btn);
	
}

function changeEntityType(btn){
	var arr = btn.id.split('#');
	var entityId = arr[0];
	var entityType = arr[1];
	var entity = document.getElementById(entityId);
	entity.style.background = dic_json[entityType];	
	//alert(dic_json[entityType] + "==" + entity.style.background);
	
	var content = document.getElementById("tips1");
	content.style.display="none";
}

function changeEntityTypeByKb(btn){
	document.onkeydown=function(event){
		var e = event || window.event || arguments.callee.caller.arguments[0];

		var type = colorRGB2Hex(btn.style.background).toUpperCase();
		//alert(type);
		var flag = false;
		if(e && e.keyCode==38 || e && e.keyCode==37){//上,左
			for (var p in dic_json){
				if (type == dic_json[p]){
					var ind = dic_array.lastIndexOf(p);
					if (ind > 0){
						var c = dic_json[dic_array[ind-1]];
						if(c!=""){
							btn.style.background = c;
						}
					}
					break;
				}
			}
		}

		if(e && e.keyCode==40 || e && e.keyCode==39){//下,右
			for (var p in dic_json){
				if (type == dic_json[p]){
					flag = true;
				}
				else if (flag == true && dic_json[p] != ""){
					btn.style.background = dic_json[p];
					
					flag = false;
					break;
				}
			}
		}
	}; 
	
	//var arr = btn.id.split('#');
	//var entityId = arr[0];
	//var entityType = arr[1];
	//var entity = document.getElementById(entityId);
	//entity.style.background = dic_json[entityType];	
}

//function noShow(btn){
//	btn.style.display="none";
//}

function btn_save_entity(){
	var html=document.getElementById("right_div_entity").innerHTML;
	//alert("right:"+html);
	
	reg = /id="btn_entity_new_([\u4e00-\u9fa5]+)" style="background:([#,\(,\),\s,\,,0-9,a-z,A-Z]+);/gi
	var result;
	var entity_str = "";
	if((result = html.match(reg)) != null){
		for (var i=0;i<result.length;i++){
			var entity = result[i].match(/new_(\S*)" style/)[1];
			var type;
			if (result[i].indexOf("rgb") != -1){
				type = result[i].match(/background:([\(,\),\s,\,,0-9,a-z,A-Z]+);/)[1];
				type = colorRGB2Hex(type).toUpperCase();
			}
			else{
				type = result[i].match(/background:(\S*);/)[1].toUpperCase();
			}

			for (var p in dic_json){
				if (type == dic_json[p] && p != "delete"){
				//if (type == dic_json[p]){
						entity_str += p + ":" + entity + ";"; 
						break;
				}
			}
		}
	}	

	document.getElementById('entity_correct').value = entity_str;
	var myform=document.getElementById("right_entity_form");
  	myform.submit();
}

/*
*make sure all of the value of "background" is HEX, not RGB
*/
function btn_recommand_entity(){
	var html=document.getElementById("left_div_entity").innerHTML;
	//alert("left:"+html);
		
	reg = /id="btn_entity_([\u4e00-\u9fa5]+)" style="background:(#[0-9,a-z,A-Z]+);/gi
	var result;
	var entity_str = "";
	if((result = html.match(reg)) != null){
		for (var i=0;i<result.length;i++){
			var entity = result[i].match(/entity_(\S*)" style/)[1];
			var type = result[i].match(/background:(\S*);/)[1].toUpperCase();

			//alert(entity+"---"+type);
			for (var p in dic_json){
				if (type == dic_json[p] && p != "delete"){
				//if (type == dic_json[p]){
						entity_str += p + ":" + entity + ";"; 
						break;
				}
			}
		}
	}	

	//alert(entity_str);
	document.getElementById('entity_original').value = entity_str;
	var myform=document.getElementById("left_entity_form");
  	myform.submit();
	
}


//used to load the AI recommanded entities
function load_ai_entity_recommand2(entity_list, default_color){
	//show the new
	if (entity_list == ""){
		return;
	}
	
	var right_div = document.getElementById("right_div_entity");
	var left_div = document.getElementById("left_div_entity");
	
	var right_side_html = "";
	var left_side_html = left_div.innerHTML;
	var arr = entity_list.split("|");
	for (var i=0;i<arr.length;i++){
		entity = arr[i].split("-")[0];
		color = arr[i].split("-")[1];
		
		//add new entity to right side
		right_side_html += add_new_entity(entity, color);		
		
		//change the color of the left
		old_str = 'id="btn_entity_' + entity + '" style="background:' + color;
		new_str = 'id="btn_entity_' + entity + '" style="background:' + default_color;
		left_side_html.replace(new RegExp(old_str, 'g'), new_str);		
	}	

	right_div.innerHTML += right_side_html;
	left_div.innerHTML = left_side_html;
}

		
function colorRGB2Hex(color) {
    var rgb = color.split(',');
    var r = parseInt(rgb[0].split('(')[1]);
    var g = parseInt(rgb[1]);
    var b = parseInt(rgb[2].split(')')[0]);
 
    var hex = "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
    return hex;
}

/*===================================*/
//Good functionality, but not used now!
var scrollFunc = function (e, btn) {
	e = e || window.event;
	//var btn = document.getElementById("input4");
	if (e.wheelDelta) {  //判断浏览器IE，谷歌滑轮事件
		if (e.wheelDelta > 0) { //当滑轮向上滚动时
			btn.style.background="#FFCC55";
		}
		if (e.wheelDelta < 0) { //当滑轮向下滚动时
			btn.style.background="#BBCC55";
		}
	} else if (e.detail) {  //Firefox滑轮事件
		if (e.detail> 0) { //当滑轮向上滚动时
			btn.style.background="#FFCC55";
		}
		if (e.detail< 0) { //当滑轮向下滚动时
			btn.style.background="#BBCC55";
		}
	}
};
function changeColor(btn, flag) {
	if (flag == 0){
		//forefox
		if (document.addEventListener) {
			//bug: not works for firefox
			document.removeEventListener('DOMMouseScroll', function(event){scrollFunc(event,btn)}, false);
		}
		//ie, chrome
		window.onmousewheel = document.onmousewheel = null;
		return;
	}
    //给页面绑定滑轮滚动事件(firefox)
    if (document.addEventListener) {
        //document.addEventListener('DOMMouseScroll', scrollFunc, false);
		document.addEventListener('DOMMouseScroll', function(event){scrollFunc(event,btn)}, false);		
    }
	//滚动滑轮触发scrollFunc方法(ie, chrome)
    window.onmousewheel = document.onmousewheel = function(event){scrollFunc(event,btn)};
}

