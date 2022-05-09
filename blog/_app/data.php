<?php
/**
 * システムに必要な関数群
 */
//なぜここなのかは不明だが、設定用ファイル読み込み
require_once('init.php');

/**
 * ==================================== システム処理系 ====================================
 */
function make_dir( $dir = '' ) {
	if(file_exists($dir)) return false;
	if(!@mkdir($dir)) if(!is_writable(dirname($dir))) die('パーミッションを変更してください');
	chmod_if_not_suexec($dir);
}

function data_backup(){
	$command = 'cd ' . DATA_DIR . '; zip -ru ' . ROOT_DIR . '/backup.zip .';
	exec($command);
}

function backup_temp() {
	$dir=preg_replace('@_app$@','',dirname(__FILE__));
	if(strlen($dir)){
		$src=$dir.'_data/contribute';
		$dest=$dir.'_data/_contribute';
		`rm -r $dest`;
		`cp -rp $src $dest`;
	}
}

function output_log( $txt ) {
	if($fp=fopen(DATA_DIR.'/memo/log.dat','a')){
		fputs($fp,date('Y-m-d H:i:s').' '.$txt."\n");
		fclose($fp);
	}
	chmod_if_not_suexec(DATA_DIR.'/memo/log.dat');
}

function is_suexec() {
	return fileowner(__FILE__) == fileowner( DATA_DIR );
}

function chmod_if_not_suexec( $fname ) {
	if(!is_suexec()){
		if(file_exists($fname)){
			if(!@chmod($fname,0777))
			{
				die('chmodに失敗しました。このサーバーでは当システムは動作しません');
			}
		}
	}
}

function data_write( $filename, $data ) {
	$file = DATA_DIR.'/'.$filename;
	if( filelock( $file ) ) {
		file_put_contents( $file, $data, 2 );
		chmod_if_not_suexec( $file );
		fileunlock( $file );
	}
}

function filelock( $target ) {
	if( ! FILELOCK_ENABLE )
		return true;
	
	if( ! file_exists( DATA_DIR . '/lock/' ) ){
		@mkdir( DATA_DIR . '/lock/' );
		chmod_if_not_suexec( DATA_DIR . '/lock/' );
	}
	
	$lockdir = DATA_DIR . '/lock/';
	//異常終了したデータを消す作業
	if( $files = scandir($lockdir) ){
		foreach( $files as $file ){
			if( $file != '.' && $file != '..' ){
				if( time() - FILELOCK_LIFETIME > filemtime( "{$lockdir}{$file}" ) )
					@rmdir( "{$lockdir}{$file}" );
			}
		}
	}
	
	$lockfile = $lockdir . md5( $target );
	for( $i = 0; $i < FILELOCK_LIFECYCLE; $i++ ) {
		if( ! file_exists( $lockfile ) ){
			@mkdir( $lockfile );
			if( file_exists( $lockfile ) ) {
				return true;
				break;
			}
		} else {
			sleep( FILELOCK_LIFETIME );
			fileunlock( $file );
		}
	}
	
	return false;
}

function fileunlock( $target ) {
	if( ! FILELOCK_ENABLE )
		return true;
	
	$lockdir = DATA_DIR . '/lock/';
	$lockfile = $lockdir . md5( $target );
	if( file_exists( $lockfile ) )
		rmdir( $lockfile );
	
	return ! file_exists( $lockfile );
}



/* この関数は一体？ */
function delete_front($url) {
	//カテゴリデータ消去
	$category_index=get_category_index();
	foreach($category_index as $id){
		$category_data=unserialize(@file_get_contents(DATA_DIR.'/category/'.$id.'.dat'));
		if(strlen($category_data['id'])){
			$dir=ROOT_DIR.'/'.$category_data['id'];
		}else{
			$dir=ROOT_DIR.'/cate-'.$id;
		}
		unlink($dir.'/index.php');
		rmdir($dir);
	}

	//投稿削除
	$contribute_index_filename=DATA_DIR.'/contribute/index.dat';
	if(file_exists($contribute_index_filename)){
		$contribute_index=unserialize(@file_get_contents($contribute_index_filename));
	}else{
		$contribute_index=array();
	}
	foreach($contribute_index as $data){
		$contribute=unserialize(@file_get_contents(DATA_DIR.'/contribute/'.$data['id'].'.dat'));
		unlink(ROOT_DIR.'/'.$contribute['url'].'/index.php');
		rmdir(ROOT_DIR.'/'.$contribute['url']);
	}

	//TOP削除
	if(file_exists(ROOT_DIR."/index.php")) unlink($root_dir."/index.php");

	//ディレクトリ削除
	if(strlen($url)){
		rmdir($root_dir);
	}
}

function make_front($new_dir) {
	$dir=ROOT_DIR;
	if(strlen($new_dir)){
		$dirs=explode('/',$new_dir);
	}else{
		$dirs=array();
	}
	foreach($dirs as $d){
		$dir.='/'.$d;
		if(!file_exists($dir)){
			@mkdir($dir);
			chmod_if_not_suexec($dir);
		}
	}
	$index_filename=$dir.'/index.php';
	$skel=@file_get_contents(dirname(__FILE__).'/skel/index.php');
	$c=str_replace('[*dir*]',str_repeat('../',count($dirs)),$skel);
	file_put_contents($index_filename,$c,2);
	chmod_if_not_suexec($index_filename);

	$category_index=get_category_index();
	foreach($category_index as $id){
		make_front_category($id);
	}
	
	$contribute_index_filename=DATA_DIR.'/contribute/index.dat';
	if(file_exists($contribute_index_filename)){
		$contribute_index=unserialize(@file_get_contents($contribute_index_filename));
	}else{
		$contribute_index=array();
	}
	foreach($contribute_index as $data){
		make_front_contribute($data['id']);
	}
}

function make_front_contribute( $contribute_id ) {
	$contribute_data = unserialize(@file_get_contents(DATA_DIR.'/contribute/'.$contribute_id.'.dat'));
	$dir=ROOT_DIR.'/'.$contribute_data['url'];
	if(!file_exists($dir)){
		mkdir($dir);
		chmod_if_not_suexec($dir);
	}
	$skel=@file_get_contents(dirname(__FILE__).'/skel/contribute.php');
	$c=str_replace('[*dir*]',str_repeat('../',1),$skel);
	$c=str_replace('[*contribute_id*]',$contribute_id,$c);
	$index_filename=$dir.'/index.php';
	file_put_contents($index_filename,$c,2);
	chmod_if_not_suexec($index_filename);
}

function make_front_category( $category_id ) {
	$category_data=unserialize(@file_get_contents(DATA_DIR.'/category/'.$category_id.'.dat'));
	if(strlen($category_data['id'])){
		$dir=ROOT_DIR.'/'.$category_data['id'];
	}else{
		$dir=ROOT_DIR.'/cate-'.$category_id;
	}
	if(!file_exists($dir)){
		mkdir($dir);
		chmod_if_not_suexec($dir);
	}
	$skel=@file_get_contents(dirname(__FILE__).'/skel/category.php');
	$c=str_replace('[*dir*]',str_repeat('../',1),$skel);
	$c=str_replace('[*category_id*]',$category_id,$c);
	$index_filename=$dir.'/index.php';
	file_put_contents($index_filename,$c,2);
	chmod_if_not_suexec($index_filename);
}

function text2array($val)
{
	$v=explode(',',str_replace('\,','__escaped_comma__',$val));
	return str_replace('__escaped_comma__',',',$v);
}

function make_value( $name, $val, $type, $id, $field_id, $field_index ) {
	switch($type){
	case 'image':
		if(strlen($val)){
			return '<img src="'.ROOT_URI.'/_data/contribute/images/'.$val.'" alt="'.$name.'" />';
		}else{
			return '';
		}
	default:
		return $val;
	}
}






/**
 * ================================ テンプレート関連 ====================================
 */

/**
 * テンプレートのコンパイル処理、ON***のなどのタグをPHPのタグに変換している
 */
function compile_template( $target ) {
	$dir=DATA_DIR.'/tpl';
	chmod_if_not_suexec($dir);
	$compiled_filename=$dir."/{$target}.php";

	$template_dir=preg_replace('@_app$@','_template',dirname(__FILE__));
	$template_path="{$template_dir}/{$target}.tpl";
	$tpl=@file_get_contents($template_path);
	

	$mod_target=@filemtime($compiled_filename);
	$mod_source=@filemtime($template_path);
	if($mod_target>$mod_source){
		//return true;
	}

	//""で囲まれている部分を抽出、エスケープ
	preg_match_all('@"(.*?)"@',$tpl,$matches);
	$quotes=array();
	foreach($matches[1] as $index=>$val){
		$quotes[$index]=$val;
		$tpl=str_replace('"'.$val.'"','"[*q'.$index.'*]"',$tpl);
	}
	//ヘッダ
	ob_start();
?>

	$setting=unserialize(@file_get_contents(DATA_DIR.'/setting/overnotes.dat'));
	ini_set('mbstring.http_input', 'pass');
	parse_str($_SERVER['QUERY_STRING'],$_GET);
	$keyword=isset($_GET['k'])?trim($_GET['k']):'';
	$category=isset($_GET['c'])?trim($_GET['c']):'';
	$page=isset($_GET['p'])?trim($_GET['p']):'';
	$base_title = !empty($setting['title'])? $setting['title'] : 'OverNotes';
<?php
	$head=ob_get_contents();
	ob_end_clean();

	//ONCategory
	ob_start();
?>
	$category_index=get_category_index();
	foreach($category_index as $rowid=>$id){
		$category_data=unserialize(@file_get_contents(DATA_DIR.'/category/'.$id.'.dat'));
		$category_url=$category_data['id'];
		$category_name=$category_data['name'];
		$category_text=@$category_data['text'];
		$category_id=$id;
		${'category'.$id.'_url'}=$category_data['id'];
		${'category'.$id.'_name'}=$category_data['name'];
		${'category'.$id.'_text'}=@$category_data['text'];
		$selected=(@$_GET['c']==$id?' selected="selected"':'');
<?php
	$code=ob_get_contents();
	ob_end_clean();
	$tpl=preg_replace('@\<ONCategory\>@',"<?php\n".$code."\n?>",$tpl);
	$tpl=preg_replace('@\<\/ONCategory\>@',"<?php\n\t}\n?>",$tpl);

	//ONContributeSearch
	preg_match_all('@\<ONContributeSearch(.*?)\>@',$tpl,$matches);
	foreach($matches[1] as $index=>$params){
		if(preg_match('@^\s@',$params)||!strlen($params)){
			$param_output=attr2param($matches[1][$index]);
			$category=strlen(@$param_output['category'])?$param_output['category']:"''";
			$keyword=strlen(@$param_output['keyword'])?$param_output['keyword']:"''";
			$field=strlen(@$param_output['field'])?$param_output['field']:"''";
			if(strlen(@$param_output['order'])){
				$order=$param_output['order'];
			}else{
				$order="''";
			}
			if(strlen(@$param_output['sort'])){
				$sort=$param_output['sort'];
			}else{
				$sort="''";
			}
      
			if(strlen(@$param_output['category_order'])){
        $category_order=$param_output['category_order'];
			}else{
				$category_order="''";
			}
			$_page=strlen(@$param_output['page'])?$param_output['page']:1;
			$tpl=preg_replace(
				'/'.preg_quote($matches[0][$index]).'/'
				,
"<?php
	\$contribute_index=contribute_search(
		".$category."
		,".$field."
		,".$keyword."
		,".$order."
		,".$sort."
		,".$category_order."
	);
	\$max_record_count=count(\$contribute_index);
".(@$param_output['limit']?"
	\$current_page=(".$_page.")?(".$_page."):1;
	\$contribute_index=array_slice(\$contribute_index,(\$current_page-1)*".$param_output['limit'].",".$param_output['limit'].");
	\$record_count=count(\$contribute_index)
":'')."
?>"
				,$tpl
			);
		}
	}
	$tpl=preg_replace('@\<\/ONContributeSearch\>@',"",$tpl);
	
	//ONContribute
ob_start();
?>
	$title=$contribute['title'];
	$category_id=$contribute['category'];
	$category_data=unserialize(@file_get_contents(DATA_DIR.'/category/'.$category_id.'.dat'));
	$category_name=$category_data['name'];
	$category_text=@$category_data['text'];
	$category_url=$category_data['id'];
	$field_id=$contribute['field'];
	$id=$contribute['id'];
	$field=get_field($field_id);
	$date=$contribute['public_begin_datetime'];
	$url=$contribute['url'].'/';

	foreach($field as $field_index=>$field_data){
		${$field_data['code'].'_Name'}=$field_data['name'];
		${$field_data['code'].'_Value'}=make_value(
		$field_data['name']
				,@$contribute['data'][$field_id][$field_index]
			,$field_data['type']
			,$id
			,$field_id
			,$field_index
		);
		if($field_data['type']=='image'){
			${$field_data['code'].'_Src'}=ROOT_URI.'/_data/contribute/images/'.@$contribute['data'][$field_id][$field_index];
		}
	}
<?php
	$code=ob_get_contents();
	ob_end_clean();
	preg_match_all('@\<ONContribute(.*?)\>@',$tpl,$matches);
	foreach($matches[1] as $index=>$params){
		if(preg_match('@^\s@',$params)||!strlen($params)){
			$param_output=attr2param($matches[1][$index]);
			$id=strlen($param_output['id'])?$param_output['id']:"''";
			$tpl=preg_replace(
				'/'.preg_quote($matches[0][$index]).'/'
				,
"<?php
	\$contribute=get_contribute(".$id.");
	$code
?>"
				,$tpl
			);
		}
	}
	$tpl=preg_replace('@\<\/ONContribute\>@',"",$tpl);

	//ONContributeFetch
	ob_start();
?>
	$local_index=0;
	foreach($contribute_index as $rowid=>$index){
		$contribute=unserialize(@file_get_contents(DATA_DIR.'/contribute/'.$index['id'].'.dat'));
		$title=$contribute['title'];
		$url=$contribute['url'].'/';
		$category_id=$index['category'];
		$category_data=unserialize(@file_get_contents(DATA_DIR.'/category/'.$category_id.'.dat'));
		$category_name=$category_data['name'];
		$category_text=@$category_data['text'];
		$field_id=$index['field'];
		$date=$index['public_begin_datetime'];
		$id=$index['id'];
		$field=get_field($field_id);

		foreach($field as $field_index=>$field_data){
			${$field_data['code'].'_Name'}=$field_data['name'];
			${$field_data['code'].'_Value'}=make_value(
		$field_data['name']
				,@$contribute['data'][$field_id][$field_index]
				,$field_data['type']
				,$id
				,$field_id
				,$field_index
			);
	
			if($field_data['type']=='image'){
				${$field_data['code'].'_Src'}=ROOT_URI.'/_data/contribute/images/'.@$contribute['data'][$field_id][$field_index];
			}
		}
		$local_index++;
<?php
	$code=ob_get_contents();
	ob_end_clean();
	$tpl=preg_replace('@\<ONContributeFetch\>@',"<?php\n".$code."\n?>",$tpl);
	$tpl=preg_replace(
		'@\<\/ONContributeFetch\>@'
		,"<?php
		foreach(\$field as \$field_index=>\$field_data){
			unset(\${\$field_data['code'].'_Name'});
			unset(\${\$field_data['code'].'_Value'});
			unset(\${\$field_data['code'].'_Src'});
		}
	}
?>"
		,$tpl
	);

	//ONContributeField
	ob_start();
?>
	foreach($field as $field_index=>$field_data){
		$ONFieldName=$field_data['name'];
		if(@strlen(@$contribute['data'][$field_id][$field_index]) || @is_array(@$contribute['data'][$field_id][$field_index])){
			$ONFieldValue=make_value(
				$field_data['name']
				,@$contribute['data'][$field_id][$field_index]
				,$field_data['type']
				,$id
				,$field_id
				,$field_index
			);
			if($field_data['type']=='image'){
				$ONFieldSrc=ROOT_URI.'/_data/contribute/images/'.@$contribute['data'][$field_id][$field_index];
			}else{
				$ONFieldSrc='';
			}
		}else{
			$ONFieldValue='';
			$ONFieldSrc='';
		}
<?php
	$code=ob_get_contents();
	ob_end_clean();
	$tpl=preg_replace('@\<ONContributeField\>@',"<?php\n".$code."\n?>",$tpl);
	$tpl=preg_replace('@\<\/ONContributeField\>@',"<?php\n\t}\n?>",$tpl);

	//ONPagenation
	preg_match_all('@\<ONPagenation(.*?)\>@',$tpl,$matches);
	foreach($matches[1] as $index=>$params){
		if(preg_match('@^\s@',$params)||!strlen($params)){
			$param_output=attr2param($matches[1][$index]);
			$record_count=$param_output['record_count'];
			$limit=$param_output['limit'];
			$tpl=preg_replace(
				'/'.preg_quote($matches[0][$index]).'/'
				,
"<?php
	\$page_count=(int)ceil({$record_count}/(float){$limit});
?>"
				,$tpl
			);
		}
	}
	$tpl=preg_replace('@\<\/ONPagenation\>@',"",$tpl);

	//ONPagenationFetch
	$tpl=preg_replace(
		'@<ONPagenationFetch>@'
		,
"<?php
	\$page_old=@\$page;
	for(\$page=1;\$page<=\$page_count;\$page++){
?>"
		,$tpl
	);
	$tpl=preg_replace('@\<\/ONPagenationFetch\>@',"<?php\n\t}\n\$page=\$page_old;\n?>",$tpl);

	//ONif(ONElse,ONElseIf)
	preg_match_all('@\<ONIf(.*?)\>@',$tpl,$matches);
	foreach($matches[1] as $index=>$params){
		if(preg_match('@^\s@',$params)||!strlen($params)){
			$param_output=attr2param($matches[1][$index]);
			$condition=$param_output['condition'];
			$tpl=preg_replace(
				'/'.preg_quote($matches[0][$index]).'/'
				,
"<?php
	if(".$condition."){
?>"
				,$tpl
			);
		}
	}
	preg_match_all('@\<ONElseIf(.*?)\>@',$tpl,$matches);
	foreach($matches[1] as $index=>$params){
		if(preg_match('@^\s@',$params)||!strlen($params)){
			$param_output=attr2param($matches[1][$index]);
			$condition=$param_output['condition'];
			$tpl=preg_replace(
				'/'.preg_quote($matches[0][$index]).'/'
				,
"<?php
	}elseif(".$condition."){
?>"
				,$tpl
			);
		}
	}
	$tpl=preg_replace('@\<ONElse\>@',"<?php\n\t}else{\n?>",$tpl);
	$tpl=preg_replace('@\<\/ONIf\>@',"<?php\n\t}\n?>",$tpl);

	//エスケープした""を復帰
	foreach($quotes as $index=>$val){
		$tpl=str_replace('[*q'.$index.'*]',$val,$tpl);
	}

	//変数ehco
	$tpl=preg_replace('@\{\=(.*?)\=\}@',"<?php echo $1; ?>",$tpl);

	//xml宣言の出力
	if(preg_match("/<\?xml.*?\?>/",$tpl,$matches)){
		$tpl=str_replace($matches[0],'<?php echo "'.str_replace('"','\"',$matches[0]).'\n" ?>',$tpl);
	}

	//データ書き込み
	file_put_contents($compiled_filename,"<?php\n".$head."\n?>".$tpl,2);
	chmod_if_not_suexec($compiled_filename);
}


function attr2param( $attr ) {
	$param_output=array();
	$param=preg_split('/\s/',$attr);
	foreach($param as $p){
		if(strlen($p)){
			preg_match('@(.*?)\=\"(.*?)\"@',$p,$pair);
			$param_output[$pair[1]]=$pair[2];
		}
	}
	return $param_output;
}


function get_category_index() {
	$dir=DATA_DIR.'/category';

	$index_filename=$dir.'/index.dat';
	if(file_exists($index_filename)){
		$index=unserialize(@file_get_contents($index_filename));
	}else{
		$index=array();
	}
	return $index;
}

function get_contribute( $id ) {
	$index=contribute_search();
	foreach($index as $index=>$data){
		if($data['id']==$id){
			$c=unserialize(@file_get_contents(DATA_DIR.'/contribute/'.$data['id'].'.dat'));
			foreach($c as $k=>$v){
				$data[$k]=$v;
			}
			return $data;
		}
	}
	return array();
}

function contribute_search( $category='', $field='', $keyword='', $order='', $sort='', $category_order='' ) {
	$dir=DATA_DIR.'/contribute';
	
	$contribute_index_filename=$dir.'/index.dat';
	if(file_exists($contribute_index_filename)){
		$contribute_index=unserialize(@file_get_contents($contribute_index_filename));
	}else{
		$contribute_index=array();
	}
	foreach($contribute_index as $index=>$data){
		if( ($category && $data['category']!=$category) || ($field && $data['field']!=$field)){
			unset($contribute_index[$index]);
		}else{
			if($data['public_begin_datetime'] && empty($_GET['pre'])){
				list($year,$month,$day)=explode('/',$data['public_begin_datetime']);
				if(date('Ymd')<sprintf('%04d%02d%02d',$year,$month,$day)){
					unset($contribute_index[$index]);
				}
			}
			if($data['public_end_datetime'] && empty($_GET['pre'])){
				list($year,$month,$day)=explode('/',$data['public_end_datetime']);
				if(date('Ymd')>sprintf('%04d%02d%02d',$year,$month,$day)){
					unset($contribute_index[$index]);
				}
			}
		}
	}
	if(strlen($keyword)){
		$keywords=preg_split('@\s+@',$keyword);
		foreach($contribute_index as $index=>$data){
			$contribute=unserialize(@file_get_contents(DATA_DIR.'/contribute/'.$data['id'].'.dat'));
			$field=$data['field'];
			foreach($keywords as $k){
				if(strlen($k)){
					$found_tmp[$k]=false;
					if(strpos($contribute['title'],$k)!==false){
						$found_tmp[$k]=true;
						break;
					}
					if(isset($contribute['data'][$field]))foreach($contribute['data'][$field] as $val){
						if(strpos($val,$k)!==false){
							$found_tmp[$k]=true;
							break;
						}
					}
				}
			}
			$found=true;
			foreach($found_tmp as $b){
				$found=($found&&$b);
			}
			if(!$found){
				unset($contribute_index[$index]);
			}
		}
	}
	switch($sort){
		case '':
			switch($order){
				case 'bottom': $contribute_index = array_reverse($contribute_index); break;
			}
		break;
		
		case 'id':
			foreach( $contribute_index as $key => $val){
				$post[$key] = $val[$sort];
			}
			switch($order){
				case 'bottom': array_multisort($post,SORT_DESC,$contribute_index ); break;
				case 'top'   :
				case ''      : array_multisort($post,SORT_ASC,$contribute_index ); break;
			}
		break;
		
		case 'category': case 'field':
			foreach( $contribute_index as $key => $val){
				$post[$key] = $val[$sort];
			}
			switch($order){
				case 'bottom': array_multisort($post,SORT_DESC,$contribute_index); break;
				case 'top'   :
				case ''      : array_multisort($post,SORT_ASC,$contribute_index); break;
			}
		break;
		
		case 'date':
			foreach( $contribute_index as $key => $val){
				$post[$key] = $val['public_begin_datetime'];
			}
			switch($order){
				case 'bottom': array_multisort($post,SORT_DESC,$contribute_index); break;
				case 'top'   :
				case ''      : array_multisort($post,SORT_ASC,$contribute_index); break;
			}
		break;
		default:
			foreach( $contribute_index as $key => $val){
				$idsort[$key] = $val['id'];
			}
			array_multisort($idsort,SORT_ASC,$contribute_index);
			foreach( $contribute_index as $key => $val){
				$_key = $key + 1;
				$contribute=unserialize(@file_get_contents(DATA_DIR.'/contribute/'.$val['id'].'.dat'));
				$field=get_field($val['field']);
				$sort_field = 0;

				foreach($field as $sort_field => $val2) if(array_search($sort,$val2)) break;
				if(!empty($contribute['data'][$val['field']][$sort_field])){
					$contribute_index[$key][$sort] = $contribute['data'][$val['field']][$sort_field];
					$post[] = $contribute['data'][$val['field']][$sort_field];
				}else{
					$_noval[$key] = $contribute_index[$key];
					$post[] = '';

				}
			}
			switch($order){
				case 'bottom': array_multisort($post,SORT_DESC,$contribute_index); break;
				case 'top'   :
				case ''      : array_multisort($post,SORT_ASC,$contribute_index); break;
			}
			if(is_array($_noval)) $contribute_index = array_merge($contribute_index,$_noval);
		break;
	}

	return $contribute_index;
}

function get_field( $field_id ) {
	static $s_field=array();
	if(!isset($s_field[$field_id])){
		$index_filename=DATA_DIR."/field/{$field_id}/index.dat";
		if(file_exists($index_filename)){
			$index=unserialize(@file_get_contents($index_filename));
		}else{
			$index=array();
		}
		$a=array();
		foreach($index as $rowid=>$id){
			if(file_exists(DATA_DIR."/field/{$field_id}/{$id}.dat")){
				$a[$id]=unserialize(@file_get_contents(DATA_DIR."/field/{$field_id}/{$id}.dat"));
			}
		}
		$s_field[$field_id]=$a;
	}
	return $s_field[$field_id];
}





/**
 * ==================================== ディレクトリ関数 ====================================
 * v1.04で消えましたが、後方互換性のため一旦復旧させます（1.05）
 */
function get_sys_dir() {
	return SYS_DIR;
}

function get_sys_root_url() {
	return SYS_URI;
}

function get_root_url() {
	return ROOT_URI;
}

function get_data_dir() {
	return DATA_DIR;
}



/**
 * 古いPHPの場合
 */

if( LEGACY_MODE ){
	include 'legacy.php';
}