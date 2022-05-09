<?php require_once('../_app/sys.php'); ?>
<?php
$index_filename= DATA_DIR.'/memo/index.dat';
if(file_exists($index_filename)){
	$memo_index=unserialize(@file_get_contents($index_filename));
}else{
	$memo_index=array();
}

if(strlen(@$_POST['log_comment'])){
	output_log($_POST['log_comment']);
	header("Location: ./");
	exit;
}elseif(strlen(@$_POST['memo'])){
	if(@$_POST['id']){
		$data_id=(int)$_POST['id'];
	}else{
		$data_id=(int)@max($memo_index)+1;
		$memo_index[]=$data_id;
		data_write('memo/index.dat',serialize($memo_index));
	}
	$filename="memo/{$data_id}.dat";
	data_write(
		$filename
		,serialize(array(
			'date'=>date('Y-m-d H:i:s')
			,'memo'=>$_POST['memo']
		))
	);
	header("Location: ./");
	exit;
}elseif(@$_POST['delete_memo']){
	foreach($memo_index as $rowid=>$id){
		if($id==$_POST['delete_memo']){
			//古いの削除
			$filename="memo/{$id}.dat";
			if(file_exists(DATA_DIR."/$filename")){
				unset($memo_index[$rowid]);
				data_write('memo/index.dat',serialize($memo_index));
				unlink(DATA_DIR."/memo/{$id}.dat");
				header("Location: ./");
			}
			break;
		}
	}
}
$log=explode("\n",trim(@file_get_contents(DATA_DIR.'/memo/log.dat')));
$log=array_reverse($log);
if(is_smart_phone()){
	header("Location: ./contribute.php");
}else{
?>
<?php on_header(); ?>

<div id="main">
<div id="path">ユーザーメモ</div>
<ul class="memo">
<?php
$memo_index=array_reverse($memo_index);
$count=0;
foreach($memo_index as $id){
	$m=unserialize(@file_get_contents(DATA_DIR."/memo/{$id}.dat"));
?>
	<li class="clearfix">
		<div class="date"><?php echo $m['date']; ?></div> <div class="memo_text"><?php echo nl2br($m['memo']); ?></div>
		<div class="delete_icon">
			<form method="post" action="" enctype="multipart/form-data">
				<input type="hidden" name="delete_memo" value="<?php echo $id; ?>" />
				<input class="delete_icon" type="submit" value="Ｘ" />
			</form>
		</div>
	</li>
<?php
	if(++$count>=10){
		break;
	}
}
?>
</ul>
<hr />
<form method="post" action="" enctype="multipart/form-data">
	<textarea name="memo"></textarea>
	<input class="add_button" type="submit" value="メモ追加" />
</form>
<br /><br />
<div id="path">変更履歴　<input type="button" name="履歴を閉じる" value="履歴を開く" /></div>
<div id="topic" style="display:none;">
	<ul class="topic">
	<?php
		for($i=0;$i<10&&isset($log[$i]);$i++){
	?>
		<li><?php echo htmlspecialchars($log[$i]); ?></li>
	<?php
		}
	?>
	</ul>
	<hr />
	<form method="post" action="" enctype="multipart/form-data">
		<input type="text" name="log_comment" value="" class="log_comment">
		<input class="add_button" type="submit" value="コメント追加" />
	</form>
</div>
<?php
}
?>

</div>
<!-- /#main -->

<?php
on_sidebar();

on_footer();
