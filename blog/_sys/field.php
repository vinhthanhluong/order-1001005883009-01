<?php require_once('../_app/sys.php'); ?>
<?php
$dir=DATA_DIR.'/field';
if(!file_exists($dir)){
	mkdir($dir);
	chmod_if_not_suexec($dir);
}

$fieldset_index_filename=$dir.'/index.dat';
if(file_exists($fieldset_index_filename)){
	$fieldset_index=unserialize(@file_get_contents($fieldset_index_filename));
}else{
	$fieldset_index=array();
}
if(@$_POST['delete']){
	foreach($fieldset_index as $i=>$id){
		if($id==$_POST['id']){
			if(file_exists(DATA_DIR."/field/{$id}/index.dat")){
				$field_index=unserialize(@file_get_contents(DATA_DIR."/field/{$id}/index.dat"));
				foreach($field_index as $rowid=>$fid){
					unlink(DATA_DIR."/field/{$id}/{$fid}.dat");
				}
			}
			unlink(DATA_DIR."/field/{$id}/name.dat");
			@unlink(DATA_DIR."/field/{$id}/index.dat");
			rmdir(DATA_DIR."/field/{$id}");
			unset($fieldset_index[$i]);
			data_write('field/index.dat',serialize($fieldset_index));
			output_log('項目の種類を編集しました');
		}
	}
	
}
?>
<?php on_header(); ?>

<div id="main">
<div id="path">項目の種類</div>
	<ul class="setting_list">
<?php
foreach($fieldset_index as $rowid=>$id){
	$name=@file_get_contents("$dir/$id/name.dat");
?>
		<li class="clearfix">
			<span class="setting_list_name"><?php echo htmlspecialchars($name); ?></span>
			<span class="f_R">
				<form method="post" action="">
					<input type="hidden" name="id" value="<?php echo $id;?>">
					<input type="submit" class="delete_button" name="delete" value="削除" onclick="return confirm('[<?php echo htmlspecialchars($name); ?>]を削除します。よろしいですか？');"/>
				</form>
			</span>
			<span class="f_R">
				<form method="get" action="field_detail.php">
					<input type="hidden" name="id" value="<?php echo $id;?>">
					<input style="margin:0px 5px 0px 0px;" type="submit" class="edit_button" value="変更" />
				</form>
			</span>
		</li>
<?php
}
?>
	</ul>

<hr />
<div>
	<div class="setting_field">
		<?php if(@$_GET['nn']): ?>
		<div class="alert">項目の種類名を入力してください。</div>
		<?php endif ?>
		<form method="post" action="field_detail.php?id=0" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="create" />
			種類名：<input type="text" name="name" value="" />
			<input class="add_button" type="submit" value="新しい項目の種類を追加" />
		</form>
	</div>
</div>

</div>
<!-- /#main -->

<?php
on_sidebar();

on_footer();
