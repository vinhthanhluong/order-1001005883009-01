<?php require_once('../_app/sys.php'); ?>
<?php
function check_duplicate_dir_category($id,$dir)
{
	$category_dir=array();
	if(file_exists(DATA_DIR.'/category/index.dat')){
		$index=unserialize(@file_get_contents(DATA_DIR.'/category/index.dat'));
		$category_dir=array();
		foreach($index as $cid){
			$c=unserialize(@file_get_contents(DATA_DIR."/category/$cid.dat"));
			$category_dir[$cid]=$c['id'];
		}
	}
	
	$contribute_dir=array();
	if(file_exists(DATA_DIR.'/contribute/index.dat')){
		$index=unserialize(@file_get_contents(DATA_DIR.'/contribute/index.dat'));
		foreach($index as $index_value){
			$cid=$index_value['id'];
			$c=unserialize(@file_get_contents(DATA_DIR."/contribute/$cid.dat"));
			$contribute_dir[$cid]=$c['url'];
		}
	}
	unset($category_dir[$id]);
	return in_array($dir,$contribute_dir)||in_array($dir,$category_dir);
}

$dir=DATA_DIR.'/category';
if(!file_exists($dir)){
	mkdir($dir);
	chmod_if_not_suexec($dir);
}

$index_filename=$dir.'/index.dat';
if(file_exists($index_filename)){
	$index=unserialize(@file_get_contents($index_filename));
}else{
	$index=array();
}

$done='';
$error=array();
if(!empty($_POST['mode'])){
	switch($_POST['mode']){
	case 'edit':
		if(check_duplicate_dir_category(@$_POST['id'],@$_POST['category_id'])){
			$error['DUPLICATE_DIR']=1;
		}elseif(!strlen($_POST['category_name'])||!strlen($_POST['category_id'])){
			$error['EMPTY']=1;
		}elseif(!preg_match('@^[a-zA-Z0-9]+[a-zA-Z0-9_-]+$@',$_POST['category_id'])){
			$error['INVALID_DIR']=1;
		}else{
			$category=array(
				'id'=>$_POST['category_id']
				,'name'=>$_POST['category_name']
			);

			if(@$_POST['id']){
				$data_id=(int)$_POST['id'];
			}else{
				$data_id=(int)@max($index)+1;
				$index[]=$data_id;
				data_write('category/index.dat',serialize($index));
			}
			$filename="category/{$data_id}.dat";
			if(file_exists(DATA_DIR."/$filename")){
				$c=unserialize(@file_get_contents(DATA_DIR."/$filename"));
				$category['text'] = @$c['text'];
				if($c['id']!=$_POST['category_id']){
					//古いの削除
					$url=@file_get_contents(DATA_DIR.'/rooturl.dat');
					$root_dir=SYS_DIR.'/'.$url;
					if(strlen($c['id'])){
						$category_dir=$root_dir.'/'.$c['id'];
					}else{
						$category_dir=$root_dir.'/cate_'.$data_id;
					}
					unlink($category_dir.'/index.php');
					rmdir($category_dir);
				}
			}
			data_write($filename,serialize($category));
			make_front_category($data_id);
			if(@$_POST['id']){
				output_log("カテゴリを編集しました({$data_id}:".$_POST['category_name'].")");
				$done='EDIT';
			}else{
				output_log("カテゴリを追加しました({$data_id}:".$_POST['category_name'].")");
				$done='ADD';
			}
		}
		break;
	case 'delete':
		foreach($index as $rowid=>$id){
			if($id==$_POST['id']){
				//古いの削除
				$filename="category/{$id}.dat";
				if(file_exists(DATA_DIR."/$filename")){
					$c=unserialize(@file_get_contents(DATA_DIR."/$filename"));
					$url=@file_get_contents(DATA_DIR.'/rooturl.dat');
					$category_dir= (!empty($url))? ROOT_DIR.'/'.$url.'/'.$c['id']: ROOT_DIR.'/'.$c['id'];
					unlink($category_dir.'/index.php');
					rmdir($category_dir);

					unset($index[$rowid]);
					data_write('category/index.dat',serialize($index));
					unlink($dir.'/'.$id.'.dat');
					output_log("カテゴリを削除しました({$id}:".$c['name'].".)");
					$done='DELETE';
				}
				break;
			}
		}
		break;
	case 'sort':
		$sorted=array();
		$a=$_POST['cat'];
		foreach($_POST['cat'] as $i=>$rep_index){
			$sorted[$i]=$index[$rep_index];
		}
		data_write('category/index.dat',serialize($sorted));
		header("Location: ./category.php");
		output_log("カテゴリの表示順序を変更しました");
		exit;
		break;
	}
}
?>
<?php on_header(); ?>

<div id="main">
<div id="path">カテゴリ設定</div>
<div>
<?php
if(isset($error['DUPLICATE_DIR'])){
?>
	<div class="alert">フォルダ名が重複しています。(<?php echo $_POST['category_id'];?>)</div>
<?php
}elseif(isset($error['EMPTY'])){
?>
	<div class="alert">カテゴリ名及びフォルダ名を入力してください</div>
<?php
}elseif(isset($error['INVALID_DIR'])){
?>
	<div class="alert">フォルダ名は半角英数字又は[_」(アンダーバー)「-」(ハイフン)で入力してください</div>
<?php
}else{
	switch($done){
	case 'ADD':
?>
	<div class="alert">カテゴリを追加しました</div>
<?php
		break;
	case 'EDIT':
?>
	<div class="alert">カテゴリを変更しました</div>
<?php
		break;
	case 'DELETE':
?>
	<div class="alert">カテゴリを削除しました</div>
<?php
		break;
	}
}
?>
	<div class="setting_field">フォルダ名には、半角英数字及び「_」(アンダーバー)「-」(ハイフン)を使用できます</div>
	<div class="list_caption">
		<span class="move_holder"></span>
		<span class="category_id_ttl">ID</span>
		<span class="category_name_ttl">カテゴリ名</span>
		<span class="category_url_ttl">フォルダ名</span>
	</div>
	<ul class="list"  id="category_list">
<?php
foreach($index as $rowid=>$id){
	$category_data=unserialize(@file_get_contents($dir.'/'.$id.'.dat'));
?>
		<li id="cat-<?php echo $rowid; ?>"><img src="./images/icon_move.png" class="move" />
			<form method="post" action="" enctype="multipart/form-data">
				<input type="hidden" name="mode" value="edit" />
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<span class="category_id"><?php echo $id; ?></span>
				<span class="category_name"><input type="text" name="category_name" value="<?php echo htmlspecialchars($category_data['name']); ?>" /></span>
				<span class="category_url"><input type="text" name="category_id" value="<?php echo htmlspecialchars($category_data['id']); ?>" /></span>
				<span><input class="edit_button" type="submit" value="変更確定" onclick="return confirm('設定内容を変更します。よろしいですか？');" /></span>
			</form>
			<form method="post" action="">
				<input type="hidden" name="mode" value="delete" />
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<span><input class="delete_button" type="submit" value="削除" onclick="return confirm('「<?php echo htmlspecialchars($category_data['name']); ?>」を削除します。よろしいですか？');" /></span>
			</form>
		</li>
<?php
}
?>
	</ul>
	<form method="post" action="" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="edit" />
		<div class="list_add">
			<span class="move_holder"></span>
			<span class="category_id"></span>
			<span class="category_name"><input type="text" name="category_name" /></span>
			<span class="category_url"><input type="text" name="category_id" value="cate_<?php echo count($index)?((int)max($index)+1):1;?>"/></span>
			<span>
				<input class="add_button" type="submit" value="追加" />
			</span>
		</div>
	</form>
</div>

<form id="form" method="post" action="">
	<input type="hidden" name="mode" value="sort" />
</form>
<script type="text/javascript">
	$('#category_list').sortable({
		axis: 'y'
		//,helper: 'clone'
		,placeholder: "placeholder"
		,handle: $('.move')
		,start: function(event,ui){
			//$('.out_category').css('display','list-item').css('color','#777777');
		}
		,update: function(event,ui){
			var seri=$('#category_list').sortable("serialize");
			var pairs=seri.split("&");
			$.each(pairs,function(i, val){
				var pair=val.split("=");
				$('#form').append('<input type="hidden" name="'+pair[0]+'" value="'+pair[1]+'" />');
			});
			$('#form').submit();
			var seri=$('#category_list').sortable("disable");
		}
	});
</script>

</div>
<!-- /#main -->

<?php
on_sidebar();

on_footer();
