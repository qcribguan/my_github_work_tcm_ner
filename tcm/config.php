<?php

/**
 * Used to store website configuration information.
 *
 * @var string
 */
function config($key = '')
{
    $config = [
        'name' => '中医古籍管理',
        'nav_menu' => [
            '' => '首页',
			'book_list' => '古籍列表',
            'book_proof' => '古籍校对',
            'book_entity' => '古籍实体抽取',
			'book_entity_panel' => '实体统计',
			'proof_setting' => '设置',
            'about_us' => '关于我们',
        ],
		'site_home' => 'tcm',
        'template_path' => 'template',
        'content_path' => 'content',
        'pretty_uri' => true,
        'version' => 'v1.0',
		'foot' => '中国科学院软件研究所',
		// the directory that hold all of the json book
		'book_holder' => 'D:\my_work\mysrc\TCMBigData\src\main\resources\static\book-holder',
		'book_proof' => 'book_proof',
		'book_entity' => 'book_entity',
		'book_entity_panel' => 'book_entity_panel',
		'book_download' =>'book_download',
		
		//DB connection
		'db_host' => 'localhost',
		'db_name' => 'tcmdata',
		'db_user' => 'root',
		'db_passwd' => '123456',
		//DB tables
		'dbtab_book_proof' => 'book_proof',
		'dbtab_auto_book_entity_expert' => 'auto_book_expert_relation',
		'dbtab_auto_book_entity_disease' => 'auto_book_disease_relation',
		'dbtab_auto_book_entity_material' => 'auto_book_material_relation',
		'dbtab_auto_book_entity_prescription' => 'auto_book_prescription_relation',	
		'dbtab_book_entity_expert' => 'set_book_expert_relation',
		'dbtab_book_entity_disease' => 'set_book_disease_relation',
		'dbtab_book_entity_material' => 'set_book_material_relation',
		'dbtab_book_entity_prescription' => 'set_book_prescription_relation',
		'dbfield_name' => 'related_entity',
		//DB tables: base type tables;
		'dbtab_expert' => 'expert',
		'dbtab_disease' => 'disease',
		'dbtab_material' => 'material',
		'dbtab_prescription' => 'prescription',	
		//DB tables: base type tables;(name field)
		'dbtab_expert_namefield' => '',
		'dbtab_disease_namefield' => 'name',
		'dbtab_material_namefield' => 'name_cn',
		'dbtab_prescription_namefield' => 'name',	
				
		
		//Entity config
		'disease' => '#99CCFF',
		'material' => '#99FF99',
		'prescription' => '#FFCC66',
		'expert' => '',
		'default' => '#CCCCCC',	// default = delete

		//Entity config Charactors (change the type menu)
		'disease_m' => '疾病',
		'material_m' => '中草药',
		'prescription_m' => '方剂',
		'expert_m' => '',
		'default_m' => '删除',
		
		
		//AI Assistant Rules for pickup entity;
		'prescription_suffix_keywords' => array('汤', '丸', '散', '丹', '膏', '饮', '圆', '酒', '片', '合剂', '颗粒', '糖浆', '胶囊', '口服液', '方'),
		'material_suffix_keywords' => array('草', '花', '根', '枝', '叶', '莲', '子', '皮', '香'),
		
		
		//regular expression saved file
		're_file' => 'data/regular_expression_db.txt'
    ];

    return isset($config[$key]) ? $config[$key] : null;
}

define('DATABASE_NAME', config('db_name'));
define('DATABASE_USER', config('db_user'));
define('DATABASE_PASS', config('db_passwd'));
define('DATABASE_HOST', config('db_host'));

ini_set('memory_limit', '2048M');
