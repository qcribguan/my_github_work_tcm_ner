<?php

$re_rules_str = "";
function read_re_rules_from_file(){
	global $re_rules_str;
	$re_rules_str = read_re_rule_file(config('re_file'));
	return;
}


function save_re_rules_from_file($rules_str){
	save_re_rules_file(config('re_file'), $rules_str);
	return;
}


function replaceHtml2()
{
	global $re_rules_str;
	
	echo '
	<div class="panel2" id="replace2">
			<div><strong>古籍辅助校对功能设置：</strong></div>
			<div>设置全局替换规则：</div>
			<form id="reg_replace_rule" action="" method="post">
            <table>
                <tr>
                    <td width="240" valign="top">输入要替换的字符（支持正则表达式）: </td>
                    <td><textarea rows="5" cols="50" id="reg_rules_str" name="reg_rules_str">'.$re_rules_str.'</textarea></td>
                </tr>
                <tr>
                    <td>区分大小写</var></td>
                    <td>
                        <input name="chk_box" id="matchCase2" type="checkbox" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input id="reSaveRefreshBtn" type="submit" class="btn" value="保存并刷新页面"/>
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
						<span id="example" style="color:red">
						<p>注意：1. 请勿删除输入框中内容；2.请添加新替换的字符或字符串，并请用“;”隔开；3.正则表达式请写在“//”之内；4.默认去掉替换字符，如需指定替换后的字符，请使用=>分割；</p>
						<p>例如：KT; HT; □; ○; ■; \r; /p05-c16a1.bmp/=>" "</p>
						</span>
					</td>
				</tr>
            </table>
			</form>
        </div>';
}

function showSetting()
{
	read_re_rules_from_file();
	replaceHtml2();
}





?>