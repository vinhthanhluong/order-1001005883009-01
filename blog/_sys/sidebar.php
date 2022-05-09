<div id="navi">
<?php
$category_index_filename=DATA_DIR.'/category/index.dat';
if(file_exists($category_index_filename)) $category_index=unserialize(@file_get_contents($category_index_filename));
if(!empty($category_index)):
?>
	<ul class="navi_menu1">
		<li class="navi_contribute">
			<a href="<?php echo SYS_URI ?>/contribute.php">投稿記事一覧</a>
		</li>
		<li class="devider"></li>
		<?php
		foreach($category_index as $rowid=>$id):
		$category_data=unserialize(@file_get_contents(DATA_DIR.'/category/'.$id.'.dat'));
		?>
		<li id="<?php echo $category_data['id'] ?>" class="navi_contribute_category <?php echo (( SELF=='contribute'  || SELF == 'contribute_detail' ) && $id == $_GET['cat'])? 'active': '' ?>">
			<a href="<?php echo SYS_URI ?>/contribute.php?cat=<?php echo $id ?>"><?php echo $category_data['name'] ?></a>
		</li>
		<?php endforeach ?>
	</ul>
	<?php endif ?>
<?php if(!is_smart_phone()): $menu = array('category'=>'カテゴリ設定','field'=>'項目の種類','setting'=>'基本設定','import'=>'記事インポート',); ?>
	<ul class="navi_menu2">
		<li class="navi_home <?php echo (SELF == 'index')? 'active': '' ?>"><a href="<?php echo SYS_URI ?>/">ホーム</a></li>
		<?php foreach ($menu as $key => $val): ?>
		<?php if(!(@in_array($val,$setting['menu']) && @$_SESSION['login']['role']!='freesale')): ?><li class="navi_<?php echo $key ?> <?php echo (SELF == $key || SELF == $key.'_detail')? 'active': '' ?>"><a href="<?php echo SYS_URI.'/'.$key ?>.php"><?php echo $val ?></a></li><?php endif ?>
		<?php endforeach ?>
	</ul>
<?php endif ?>
</div>