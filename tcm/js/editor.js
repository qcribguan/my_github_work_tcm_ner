// JavaScript Document
    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var ue = UE.getEditor('editor');


    function isFocus(e){
        alert(UE.getEditor('editor').isFocus());
        UE.dom.domUtils.preventDefault(e)
    }
    function setblur(e){
        UE.getEditor('editor').blur();
        UE.dom.domUtils.preventDefault(e)
    }
    function insertHtml() {
        var value = prompt('插入html代码', '');
        UE.getEditor('editor').execCommand('insertHtml', value)
    }
    function createEditor() {
        enableBtn();
        UE.getEditor('editor');
    }
    function getAllHtml() {
        alert(UE.getEditor('editor').getAllHtml())
    }
    function getContent() {
        var arr = [];
        arr.push("使用editor.getContent()方法可以获得编辑器的内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getContent());
        alert(arr.join("\n"));
    }
    function getPlainTxt() {
        var arr = [];
        arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getPlainTxt());
        alert(arr.join('\n'))
    }
    function setContent(isAppendTo) {
        var arr = [];
        arr.push("使用editor.setContent('欢迎使用ueditor')方法可以设置编辑器的内容");
        UE.getEditor('editor').setContent('欢迎使用ueditor', isAppendTo);
        alert(arr.join("\n"));
    }
    function setDisabled() {
        UE.getEditor('editor').setDisabled('fullscreen');
        disableBtn("enable");
    }

    function setEnabled() {
        UE.getEditor('editor').setEnabled();
        enableBtn();
    }

    function getText() {
        //当你点击按钮时编辑区域已经失去了焦点，如果直接用getText将不会得到内容，所以要在选回来，然后取得内容
        var range = UE.getEditor('editor').selection.getRange();
        range.select();
        var txt = UE.getEditor('editor').selection.getText();
        alert(txt)
    }

    function getContentTxt() {
        var arr = [];
        arr.push("使用editor.getContentTxt()方法可以获得编辑器的纯文本内容");
        arr.push("编辑器的纯文本内容为：");
        arr.push(UE.getEditor('editor').getContentTxt());
        alert(arr.join("\n"));
    }
    function hasContent() {
        var arr = [];
        arr.push("使用editor.hasContents()方法判断编辑器里是否有内容");
        arr.push("判断结果为：");
        arr.push(UE.getEditor('editor').hasContents());
        alert(arr.join("\n"));
    }
    function setFocus() {
        UE.getEditor('editor').focus();
    }
    function deleteEditor() {
        disableBtn();
        UE.getEditor('editor').destroy();
    }
    function disableBtn(str) {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            if (btn.id == str) {
                UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
            } else {
                btn.setAttribute("disabled", "true");
            }
        }
    }
    function enableBtn() {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
        }
    }

    function getLocalData () {
        alert(UE.getEditor('editor').execCommand( "getlocaldata" ));
    }

    function clearLocalData () {
        UE.getEditor('editor').execCommand( "clearlocaldata" );
        alert("已清空草稿箱")
    }
	
	//////////////////////////////////////////
	//my js starts from here
	//////////////////////////////////////////
	function my_init(){
		if(typeof(editor_flag) != "undefined"){
			//alert(editor_flag);
			setDisabled();
		}
	}
	my_init();

	function btn_submit() {
		//UM.getEditor('editor').sync();		
		var myform=document.getElementById("book_content_form");
  		myform.submit();
	}

	function btn_submit2_bak() {
		//UM.getEditor('editor').sync();
		//document.getElementById('flag').value = "export";
		//document.book_content_form.action="1.htm"
		var myform=document.getElementById("book_content_form");
		myform.action="content/book_download.php"
  		myform.submit();
	}
	
	function btn_submit2(){
		alert(UM.getEditor("editor").getAllHtml());
		var oWD = new ActiveXObject("Word.Application");
		var oDC = oWD.Documents.Add("",0,1);
		var oRange = oDC.Range(0,1);
		var sel = document.body.createTextRange();
		sel.moveToElementText(UM.getEditor("editor").getAllHtml());
		sel.select();
		sel.execCommand("Copy");
		oRange.Paste();
		oWD.Application.Visible = true;
		//window.close();
		alert("test");
	}

	//Additional function: replace the string
	function replace_str(){
		var html = UE.getEditor('editor').getContent();
		//alert(html);
		var replacetext = document.getElementById("replacetxt").value;
		var casesensitive = document.getElementById("matchCase1");
 
        var regex = new RegExp(document.getElementById("findtxt1").value, 'g' + (casesensitive.checked ? '' : 'i'));
		//replace
		var ret = html.replace(regex, replacetext);
		UE.getEditor('editor').setContent(ret);
		
		//how many matched
		var result = html.match(regex);
		if (null==result || 0==result.length) {
			alert("没有匹配！");
			return false;
		}
		var strResult = "共替换 " + result.length + " 处匹配!\r\n";
		alert(strResult);
			
	}
