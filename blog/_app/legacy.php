<?php
/**
 * 古いサーバ用の関数
 * そもそもPHP5.0以上なので必要ないと思うのだが、もともとのシステムに入っていたため残しておく
 * ただデフォルトは読まないでおく
 */

if(!function_exists('file_put_contents')) {
	function file_put_contents($filename, $data) {
		$f = @fopen($filename, 'w');
		if (!$f) {
			return false;
		}else{
			$bytes = fwrite($f, $data);
			fclose($f);
			return $bytes;
		}
	}
}