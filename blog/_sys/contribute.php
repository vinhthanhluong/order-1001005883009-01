<?php require_once('../_app/sys.php'); ?>
<?php
$dir=DATA_DIR.'/contribute';
if(!file_exists($dir)){
	mkdir($dir);
	chmod_if_not_suexec($dir);
	mkdir($dir.'/images');
	chmod_if_not_suexec($dir.'/images');
	data_write('contribute/images/.htaccess','allow from all');
}

$contribute_index_filename=$dir.'/index.dat';
if(file_exists($contribute_index_filename)){
	$contribute_index=unserialize(@file_get_contents($contribute_index_filename));
}else{
	$contribute_index=array();
}

$category_data = array();
$category_id = (!empty($_GET['cat']))? htmlspecialchars($_GET['cat']): '';
if($category_id) {
	$contribute_index = array_filter($contribute_index, "in_category");
	$category_data = unserialize( @file_get_contents(DATA_DIR.'/category/'.$category_id.'.dat') );
}

$fieldset_index_filename = DATA_DIR.'/field/index.dat';
if(file_exists($fieldset_index_filename)){
	$fieldset_index=unserialize(@file_get_contents($fieldset_index_filename));
} else {
	$fieldset_index=array();
}
$fieldset_name=array();
foreach($fieldset_index as $i=>$id){
	$fieldset_name[$id]=@file_get_contents(DATA_DIR.'/field/'.$id.'/name.dat');
}

switch(@$_POST['mode']){
case 'sort':
	if( empty( $category_id ) ){
		$sorted=array();
		$a=$_POST['cont'];
		foreach($_POST['cont'] as $i=>$rep_index){
			$sorted[$i]=$contribute_index[$rep_index];
		}
		data_write('contribute/index.dat',serialize(array_reverse($sorted)));
		output_log('記事の表示順序を変更しました');
	} else {
		output_log('記事の表示に失敗しました');
	}
	header("Location: ./contribute.php".($_SERVER['QUERY_STRING']?('?'.$_SERVER['QUERY_STRING']):''));
	exit;
	break;
case 'delete':
	if( empty( $category_id ) ){
		$id=$_POST['id'];
		foreach($contribute_index as $i=>$row){
			if($row['id']==$id){
				$rooturl=@file_get_contents(DATA_DIR.'/rooturl.dat');
				$contribute=unserialize(@file_get_contents(DATA_DIR."/contribute/{$id}.dat"));
				$root_dir=ROOT_DIR.(strlen($rooturl)?"/$rooturl":'');
				$field=get_field($row['field']);
				foreach($field as $findex=>$f){
					if(
						$f['type']=='image'
						&&strlen($filename=$contribute['data'][$row['field']][$findex])
						&&file_exists($path=(DATA_DIR."/contribute/images/$filename"))
					){
						unlink($path);
					}
				}
				unlink(DATA_DIR."/contribute/{$id}.dat");
				unlink($root_dir.'/'.$contribute['url'].'/index.php');
				rmdir($root_dir.'/'.$contribute['url']);
				unset($contribute_index[$i]);
				data_write('contribute/index.dat',serialize($contribute_index));
				header("Location: ./contribute.php".($_SERVER['QUERY_STRING']?('?'.$_SERVER['QUERY_STRING']):''));
				output_log('記事を削除しました('.$id.':'.$contribute['title'].')');
				exit;
			}
		}
	} else {
		header("Location: ./contribute.php".($_SERVER['QUERY_STRING']?('?'.$_SERVER['QUERY_STRING']):''));
		output_log('記事の削除に失敗しました('.$id.':'.$contribute['title'].')');
		exit;
	}
	break;
case 'category_text':
	
	$category_data['text'] = $_POST['category_text'];
	data_write('/category/'.$category_id.'.dat',serialize($category_data));
	break;
}

$rooturl=@file_get_contents(DATA_DIR.'/rooturl.dat');
$curl=preg_replace('@/(.*)/_sys@','$1',dirname($_SERVER['REQUEST_URI']));

function in_category($val){
  global $category_id;
  if($val['category']+0 == $category_id) return $val;
}

?>
<?php on_header(); ?>

<div id="main">
<div id="path"><?php if( $category_id ) : ?>
	「<?php echo htmlspecialchars($category_data['name']); ?>」カテゴリー記事一覧
<?php else : ?>
	投稿記事一覧
<?php endif ?>
</div>
<div>
	<div class="add_link clearfix">
	<?php if( empty($category_id) ) : ?>
		<a href="contribute_detail.php?id=0&amp;cat=<?php echo $category_id ?>">新しい記事を投稿</a>
	<?php else : ?>
		<a href="contribute.php">全カテゴリの記事を表示</a>
	<?php endif ?>
	</div>
	
	<hr />
	<div class="list_caption" style="padding:0 0 5px 0px;">
	<?php if(!is_smart_phone() && empty($category_id) ){ ?>
		<span class="move_holder"></span>
	<?php } ?>
	<?php if(!is_smart_phone()){ ?>
		<span class="list_caption contribute_id">ID</span>
	<?php } ?>
		<span class="list_caption contribute_title">記事名</span>
		<span class="list_caption contribute_public"></span>
	<?php if(!is_smart_phone() && empty($category_id) ){ ?>
		<span class="list_caption contribute_category">カテゴリ</span>
	<?php } ?>
	<?php if(!is_smart_phone()){ ?>
		<span class="list_caption contribute_field">項目の種類</span>
	<?php } ?>
	</div>
	<ul class="list" id="contribute_list">
<?php
foreach($contribute_index as $rowid=>$index){
	$contribute=unserialize(@file_get_contents("{$dir}/{$index['id']}.dat"));
	
	$public=true;
	if($index['public_begin_datetime']){
		list($year,$month,$day)=explode('/',$index['public_begin_datetime']);
		$a=sprintf('%04d%02d%02d',$year,$month,$day);
		$b=date('Ymd');
		if($b<$a){
			$public=false;
		}
	}
	if($index['public_end_datetime']){
		list($year,$month,$day)=explode('/',$index['public_end_datetime']);
		$a=sprintf('%04d%02d%02d',$year,$month,$day);
		$b=date('Ymd');
		if($b>$a){
			$public=false;
		}
	}
  $rooturl .= (@$rooturl)? '/': '';
  $posturl = '/'.$curl.'/'.$rooturl.htmlspecialchars($contribute['url']);
  
?>
		<li id="cont-<?php echo $rowid; ?>" class="<?php echo ($index['category']==$_GET['cat']||!$_GET['cat'])?'in_category':'out_category';?>">
		<?php if(!is_smart_phone() && empty($category_id) ) : ?>
			<img src="./images/icon_move.png" class="move" />
		<?php endif ?>
		<?php if(!is_smart_phone()){ ?>
			<span class="list_caption contribute_id"><?php echo htmlspecialchars($index['id']); ?></span>
		<?php } ?>
		<?php if(!is_smart_phone()){ ?>
			<span class="contribute_public"><?php echo $public?'<span class="public"><img src="./images/icon_publication.png" alt="公開" width="37" height="17" /></span>':'<span class="private"><img src="./images/icon_private.png" alt="非公開" width="37" height="17" /></span>'; ?></span>
			<span class="contribute_title"><a href="<?php echo $posturl; echo (!$public)? '?pre='. date('YmdHis'): '' ?>" target="_blank"><?php echo htmlspecialchars($contribute['title']); ?></a></span>
		<?php }else{ ?>
			<span class="contribute_title"><?php echo $public?'<img src="./images/icon_publication.png" alt="公開" width="37" height="17" />':'<img src="./images/icon_private.png" alt="非公開" width="37" height="17" />'; ?><a href="<?php echo $posturl; echo (!$public)? '?pre='. date('YmdHis'): '' ?>" target="_blank"><?php echo htmlspecialchars($contribute['title']); ?></a></span>
		<?php } ?>
		<?php if(!is_smart_phone() && empty($category_id) ){ ?>
			<span class="contribute_category"><?php
			/*echo $index['category']; ?>:<?php echo */
			if( ! isset( $category_data[ $index['category'] ] ) )
				$category_data[ $index['category'] ] = unserialize( @file_get_contents(DATA_DIR.'/category/'.$index['category'].'.dat') );
			
			echo htmlspecialchars( $category_data[ $index['category'] ]['name'] ); ?></span>
		<?php } ?>
		<?php if(!is_smart_phone()){ ?>
			<span class="contribute_field"><?php echo @$index['field']; ?>:<?php echo htmlspecialchars(@$fieldset_name[$index['field']]); ?></span>
		<?php } ?>
			<span class="contribute_operate">
				<form method="get" action="contribute_detail.php">
					<input type="hidden" name="id" value="<?php echo htmlspecialchars($index['id']); ?>" />
					<input type="hidden" name="cat" value="<?php echo $category_id ?>" />
					<input class="edit_button" type="submit" value="変更" />
				</form>
				<?php if( empty($category_id) ) : ?>
				<form method="post" action="">
					<input type="hidden" name="id" value="<?php echo htmlspecialchars($index['id']); ?>" />
					<input type="hidden" name="mode" value="delete" />
					<input class="delete_button" type="submit" value="削除" onclick="return confirm('[<?php echo htmlspecialchars($contribute['title']); ?>]を削除します。よろしいですか？');" />
				</form>
				<?php endif ?>
			</span>
		</li>
<?php
}
?>
	</ul>
	<?php if( empty($category_id) ) : ?>
	<div class="add_link"><a href="contribute_detail.php?id=0">新しい記事を投稿</a></div>
	<?php endif ?>
</div>

<?php if(!empty($category_id)): ?>
<div id="category_text">
	<h3>カテゴリテキスト（カテゴリごとの説明文など）</h3>
	<form action="" method="POST">
		<input type="hidden" name="mode" value="category_text" />
		<textarea name="category_text" class="ckeditor"><?php echo @$category_data['text']; ?></textarea>
		<input class="edit_button" type="submit" value="カテゴリテキストを変更する" />
	</form>
</div>
<?php endif ?>

<form id="form" method="post" action="">
	<input type="hidden" name="mode" value="sort" />
</form>
<script type="text/javascript">
	$('#contribute_list').sortable({
		axis: 'y'
		//,helper: 'clone'
		,placeholder: "placeholder"
		,handle: $('.move')
		,start: function(event,ui){
			//$('.out_category').css('display','list-item').css('color','#777777');
		}
		,update: function(event,ui){
			var seri=$('#contribute_list').sortable("serialize");
			var pairs=seri.split("&");
			$.each(pairs,function(i, val){
				var pair=val.split("=");
				$('#form').prepend('<input type="hidden" name="'+pair[0]+'" value="'+pair[1]+'" />');
			});
			$('#form').submit();
			var seri=$('#contribute_list').sortable("disable");
		}
	});
</script>

</div>
<!-- /#main -->

<?php
on_sidebar();

on_footer();
