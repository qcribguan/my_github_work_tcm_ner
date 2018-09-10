<?php

require 'utils.php';
require 'config.php';
require 'functions.php';
require 'content/editor.php';
require 'content/entity.php';
require 'content/setting.php';
require 'content/entity_panel.php';
require 'content/tools.php';
require 'content/json_operator.php';
include_once('php_db_op/class.DBPDO.php');
include_once('php_export_word/class.PHPWord.php');
include_once('chinesebihua/chinese.php');

run();
