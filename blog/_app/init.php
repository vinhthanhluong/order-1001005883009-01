<?php
/**
 * Common settings.
 *
 * @package OverNotes
 * @copyright 2012 Freesale Inc.
 */

//バージョン情報
define( 'VERSION','1.06');

//何に使うの？
define( 'PACKAGE','OverNotes');
define( 'COPYRIGHT','Branding Technology Inc.');

/**
 * 環境設定
 * 必要があれば変更してください
 */
ini_set( 'display_errors', 0);
ini_set( 'session.gc_maxlifetime',28800);
//PHP5以下の場合の臨時サポート
define( 'LEGACY_MODE', 0 );

/**
 * 各ディレクトリのパス定数
 * 基本は変更はいりませんが、サーバによって変更が必要な場合があります
 */
define( 'ROOT_DIR',dirname(dirname(__FILE__)));
define( 'SYS_DIR',ROOT_DIR.'/_sys');
define( 'APP_DIR',ROOT_DIR.'/_app');
define( 'DATA_DIR',ROOT_DIR.'/_data');
define( 'ROOT_URI',preg_replace( '/(.*'.basename(ROOT_DIR).')+\/.*/', '$1', str_replace($_SERVER["DOCUMENT_ROOT"], '', dirname($_SERVER["SCRIPT_FILENAME"]))));
define( 'SYS_URI',ROOT_URI.'/_sys');
define( 'SELF',basename($_SERVER["PHP_SELF"],".php"));

/**
 * タイムゾーン
 */
date_default_timezone_set('Asia/Tokyo');

/**
 * デバイスリスト
 * 管理画面のスマートフォンの対応のため
 */
$GLOBALS['SMART_PHONE_LIST'] = array(
	'iPhone',         // Apple iPhone
	'iPod',           // Apple iPod touch
	'Android.*Mobile',        // 1.5+ Android
	'Windows Phone',  // Windows Phone
	'IEMobile',       // Windows Phone
	'dream',          // Pre 1.5 Android
	'CUPCAKE',        // 1.5+ Android
	'blackberry9500', // Storm
	'blackberry9530', // Storm
	'blackberry9520', // Storm v2
	'blackberry9550', // Storm v2
	'blackberry9800', // Torch
	'webOS',          // Palm Pre Experimental
	'incognito',      // Other iPhone browser
	'webmate'         // Other iPhone browser
);

/**
 * ファイルロックに関する設定
 */
define( 'FILELOCK_ENABLE',    1 );
define( 'FILELOCK_LIFETIME',  30 );  //待ち時間
define( 'FILELOCK_LIFECYCLE', 3 );  //試行回数