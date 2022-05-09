<?php
$cat = (file_exists(ROOT_DIR.'/_template/category_'.$category_data['id'].'.tpl'))? $category_data['id'] : 'default';
compile_template('category_'.$cat);
include(DATA_DIR.'/tpl/category_'.$cat.'.php');
