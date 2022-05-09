<?php

	$setting=unserialize(@file_get_contents(DATA_DIR.'/setting/overnotes.dat'));
	ini_set('mbstring.http_input', 'pass');
	parse_str($_SERVER['QUERY_STRING'],$_GET);
	$keyword=isset($_GET['k'])?trim($_GET['k']):'';
	$category=isset($_GET['c'])?trim($_GET['c']):'';
	$page=isset($_GET['p'])?trim($_GET['p']):'';
	$base_title = !empty($setting['title'])? $setting['title'] : 'OverNotes';

?><?php
$cat = (file_exists(ROOT_DIR.'/_template/category_'.$category_data['id'].'.tpl'))? $category_data['id'] : 'default';
compile_template('category_'.$cat);
include(DATA_DIR.'/tpl/category_'.$cat.'.php');
