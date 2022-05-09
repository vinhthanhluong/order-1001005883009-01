<?php
require_once('_app/data.php');
compile_template('search');
$root_url=get_sys_root_url().@file_get_contents(get_data_dir().'/rooturl.dat').'/';
include(get_data_dir().'/tpl/search.php');
