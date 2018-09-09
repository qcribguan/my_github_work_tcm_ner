<?php

if (isset($_POST["reg_rules_str"])) {
	#echo $_POST["reg_rules_str"];
	#echo $_POST["chk_box"];
	
	save_re_rules_from_file($_POST["reg_rules_str"]);
}

showSetting();

?>