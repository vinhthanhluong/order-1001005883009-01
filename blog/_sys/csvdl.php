<?php
require_once('../_app/data.php');
$dir=DATA_DIR.'/contribute';
$contribute_index_filename=$dir.'/index.dat';
if(file_exists($contribute_index_filename)){
	$contribute_index=unserialize(@file_get_contents($contribute_index_filename));
}else{
	$contribute_index=array();
}
$field=get_field($_GET['field']);
ob_start();
?>_ID,公開日,終了日,記事名,URL,カテゴリ<?php
foreach($field as $i=>$f){
?>,<?php echo $f['name']; ?><?php
}
?>

<?php
foreach($contribute_index as $rowid=>$index){
	$contribute=unserialize(@file_get_contents("{$dir}/{$index['id']}.dat"));
	if($index['field']!=$_GET['field']){
		continue;
	}
	$public_begin_datetime=str_replace('"','""',$index['public_begin_datetime']);
	$public_end_datetime=str_replace('"','""',$index['public_end_datetime']);
	$title=str_replace('"','""',$contribute['title']);
	$url=str_replace('"','""',$contribute['url']);
	$category=str_replace('"','""',$index['category']);
?><?php echo $index['id']; ?>,"<?php echo $public_begin_datetime; ?>","<?php echo $public_end_datetime; ?>","<?php echo $title; ?>","<?php echo $url; ?>","<?php echo $category; ?>"<?php
	foreach($field as $i=>$f){
?>,"<?php echo str_replace('"','""',$contribute['data'][$_GET['field']][$i]);?>"<?php
	}
?>

<?php
}
$b=mb_convert_encoding(ob_get_contents(),'SJIS-win','UTF-8');
ob_end_clean();

header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=overnotes-".$_GET['field'].".csv");
echo $b;