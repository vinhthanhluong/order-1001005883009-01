<?php
require_once('../_app/data.php');
compile_template('custom');
$root_url=ROOT_URL.@file_get_contents(DATA_DIR.'/rooturl.dat').'/';
include(DATA_DIR.'/tpl/custom.php');
