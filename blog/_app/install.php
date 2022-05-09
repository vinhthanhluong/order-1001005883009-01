<?php
/**
 * Make folders and files when first login. 
 *
 * @package OverNotes
 * @since 1.04
 */

make_dir(DATA_DIR);
make_dir(DATA_DIR.'/user');
make_dir(DATA_DIR.'/session');
make_dir(DATA_DIR.'/memo');
make_dir(DATA_DIR.'/category');
make_dir(DATA_DIR.'/field');
make_dir(DATA_DIR.'/contribute');
make_dir(DATA_DIR.'/contribute/images');
make_dir(DATA_DIR.'/setting');
make_dir(DATA_DIR.'/tpl');
make_dir(DATA_DIR.'/lock');
data_write('.htaccess','deny from all');
data_write('contribute/images/.htaccess','allow from all');