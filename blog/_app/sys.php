<?php
/**
 * Common classes for _sys/.
 *
 * @package OverNotes
 * @since 1.00
 */

require_once('data.php');

if(!file_exists(DATA_DIR) || count(scandir(DATA_DIR)) <= 2) require_once('install.php');
session_save_path(DATA_DIR.'/session');
session_start();

$setting=unserialize(@file_get_contents(DATA_DIR.'/setting/overnotes.dat'));

if(strlen(file_get_contents("php://input"))){
	if(
	ini_get('mbstring.encoding_translation')
	&&(ini_get('mbstring.internal_encoding')!='UTF-8')
	&&(ini_get('mbstring.http_input')=='auto')
	){
		$enc=ini_get('mbstring.internal_encoding');
		if($enc=='SJIS'){
			$enc=='SJIS-win';
		}elseif($enc=='EUC-JP'){
			$enc=='EUCJP-win';
		}
		$_POST=_mbConvertEncodingEx($_POST,'UTF-8',ini_get('mbstring.internal_encoding'));
	}
}
if(get_magic_quotes_gpc()){
	$_POST=_stripslashesEx($_POST);
}

require_once('auth.php');

function _mbConvertEncodingEx($target, $toEncoding, $fromEncoding = null){
	if (is_array($target)) {
		foreach ($target as $key => $val) {
			if (is_null($fromEncoding)) {
				$fromEncoding = mb_detect_encoding($val);
			}
			$target[$key] = _mbConvertEncodingEx($val,$toEncoding, $fromEncoding);
		}
	} else {
		if(is_null($fromEncoding)) {
			$fromEncoding = mb_detect_encoding($target);
		}
		$target = mb_convert_encoding($target, $toEncoding, $fromEncoding);
	}
	return $target;
}

function _stripslashesEx($target)
{
	if (is_array($target)) {
		foreach ($target as $key => $val) {
			$target[$key] = _stripslashesEx($val);
		}
	} else {
		$target = stripslashes($target);
	}
	return $target;
}


function is_smart_phone() {
	$smart_phone_list =$GLOBALS['SMART_PHONE_LIST'];
	$pattern='/'.implode('|',$smart_phone_list).'/i';

	return preg_match( $pattern, @$_SERVER['HTTP_USER_AGENT'] );
}

function on_header(){
	global $setting;
	require_once(SYS_DIR.'/header.php');
}
function on_sidebar(){
	global $setting;
	require_once(SYS_DIR.'/sidebar.php');
}
function on_footer(){
	global $setting;
	require_once(SYS_DIR.'/footer.php');
}


