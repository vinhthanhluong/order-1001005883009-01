<?php require_once('../_app/sys.php'); ?>
<?php
$type_map=array(
	'oneline'=>'text'
	,'multiline'=>'textarea'
);
function check_duplicate_dir_contribute($id,$dir) {
	$index=unserialize(@file_get_contents(DATA_DIR.'/category/index.dat'));
	$category_dir=array();
	foreach($index as $cid){
		$c=unserialize(@file_get_contents(DATA_DIR."/category/$cid.dat"));
		$category_dir[$cid]=$c['id'];
	}
	
	$index=unserialize(@file_get_contents(DATA_DIR.'/contribute/index.dat'));
	foreach($index as $index_value){
		$cid=$index_value['id'];
		$c=unserialize(@file_get_contents(DATA_DIR."/contribute/$cid.dat"));
		$contribute_dir[$cid]=$c['url'];
	}
	unset($contribute_dir[$id]);
	return in_array($dir,$contribute_dir)||in_array($dir,$category_dir);
}

$rooturl=@file_get_contents(DATA_DIR.'/rooturl.dat');

$dir=DATA_DIR.'/contribute';
$curl=preg_replace('@/(.*)/_sys@','$1',dirname($_SERVER['REQUEST_URI']));

$category_index_filename=DATA_DIR.'/category/index.dat';
if(file_exists($category_index_filename)){
	$category_index=unserialize(@file_get_contents($category_index_filename));
}else{
	$category_index=array();
}

$fieldset_index_filename=DATA_DIR.'/field/index.dat';
if(file_exists($fieldset_index_filename)){
	$fieldset_index=unserialize(@file_get_contents($fieldset_index_filename));
}else{
	$fieldset_index=array();
}

$contribute_index_filename=$dir.'/index.dat';
if(file_exists($contribute_index_filename)){
	$contribute_index=unserialize(@file_get_contents($contribute_index_filename));
}else{
	$contribute_index=array();
}

if(@$_GET['id']){
	$data_id=(int)$_GET['id'];
}

$error=array();
if(@$_POST['mode']=='edit'){
	if(check_duplicate_dir_contribute($_GET['id'],$_POST['url'])){
		$error['DUPLICATE_DIR']=1;
	}elseif(!preg_match('@^[a-zA-Z0-9]+[a-zA-Z0-9_-]+$@',$_POST['url'])){
		$error['INVALID_URL']=1;
	}else{
		$bd=
			$_POST['public_begin_datetime_year']&&$_POST['public_begin_datetime_month']&&$_POST['public_begin_datetime_day']
				?(sprintf("%02d",$_POST['public_begin_datetime_year']).'/'.sprintf("%02d",$_POST['public_begin_datetime_month']).'/'.sprintf("%02d",$_POST['public_begin_datetime_day']))
				:date('Y/m/d')
		;
		$ed=
			$_POST['public_end_datetime_year']&&$_POST['public_end_datetime_month']&&$_POST['public_end_datetime_day']
				?sprintf("%02d",$_POST['public_end_datetime_year']).'/'.sprintf("%02d",$_POST['public_end_datetime_month']).'/'.sprintf("%02d",$_POST['public_end_datetime_day'])
				:''
		;
		$index_data=array(
			'public_begin_datetime'=>$bd
			,'public_end_datetime'=>$ed
			,'category'=>$_POST['category']
			,'field'=>$_POST['field']
		);
		if($data_id){
			$index_data['id']=$data_id;
			foreach($contribute_index as $index=>$val){
				if($val['id']==$data_id){
					$contribute_index[$index]=$index_data;
					break;
				}
			}
		}else{
			foreach($contribute_index as $ci){
				if($ci['id']>$data_id){
					$data_id=$ci['id'];
				}
			}
			$index_data['id']=($data_id+=1);
			array_unshift($contribute_index,$index_data);
		}
		foreach($_POST['data'][$index_data['field']] as $key => $val){
			if(is_array($val))
				$_POST['data'][$index_data['field']][$key] = implode(',',$val);
		}
		$data=array(
			'title'=>$_POST['title']
			,'url'=>$_POST['url']
			,'data'=>$_POST['data']
		);
		if(isset($_POST['delete_image']))foreach($_POST['delete_image'] as $field_index_id=>$field){
			foreach($field as $field_id=>$field_data){
				if($field_data==1){
					unlink(DATA_DIR.'/contribute/images/'.$data['data'][$field_index_id][$field_id]);
					unset($data['data'][$field_index_id][$field_id]);
				}
			}
		}
		if(@$_FILES['image']['name'])foreach($_FILES['image']['name'] as $field_index_id=>$field){
			foreach($field as $field_id=>$field_data){
				@mkdir($dir.'/images');
				chmod_if_not_suexec($dir.'/images');
				if($_FILES['image']['tmp_name'][$field_index_id][$field_id]){
					preg_match('@\.[^\.]*$@',$_FILES['image']['name'][$field_index_id][$field_id],$matches);
					$image_data=@file_get_contents($_FILES['image']['tmp_name'][$field_index_id][$field_id]);
					$file_name="{$data_id}_{$field_index_id}_{$field_id}".strtolower($matches[0]);
					$data['data'][$field_index_id][$field_id]=$file_name;
					data_write('contribute/images/'.$file_name,$image_data);
					unlink($_FILES['data']['tmp_name'][$field_index_id][$field_id]);
				}
			}
		}
		$contribute_old=unserialize(@file_get_contents("{$dir}/{$data_id}.dat"));
		data_write('contribute/index.dat',serialize($contribute_index));
		data_write("contribute/{$data_id}.dat",serialize($data));
		if(!$_GET['id']){
			make_front_contribute($data_id);
			output_log("記事を追加しました({$data_id}:".$_POST['title'].")");
		}else{
			output_log("記事を編集しました({$data_id}:".$_POST['title'].")");
			//古いの消す
			if($contribute_old['url']!=$_POST['url']){
				$url=@file_get_contents(DATA_DIR.'/rooturl.dat');
				$root_dir=ROOT_DIR.(strlen($url)?"/$url":'');
				$cont_dir=$root_dir.'/'.$contribute_old['url'];
				unlink($cont_dir.'/index.php');
				rmdir($cont_dir);
			}
			//新しいの作る
			make_front_contribute($data_id);
		}

		//インポート直前データを削除
		$dest=DATA_DIR.'/_contribute';
		`rm -r $dest`;

		header("Location: contribute.php?cat=".@$_GET['cat']);
		exit;
	}
}

if(@$data_id){
	$contribute=unserialize(@file_get_contents("{$dir}/{$data_id}.dat"));
	foreach($contribute_index as $index=>$val){
		if($val['id']==$data_id){
			$contribute['public_begin_datetime']=$val['public_begin_datetime'];
			$contribute['public_end_datetime']=$val['public_end_datetime'];
			$contribute['category']=$val['category'];
			$contribute['field']=$val['field'];
			break;
		}
	}
}

if(strlen(@$contribute['url'])){
	$url=$contribute['url'];
}else{
	$max_id=0;
	foreach($contribute_index as $ci){
		if($ci['id']>$max_id){
			$max_id=$ci['id'];
		}
	}
	$url='post-'.($max_id+1);
}
?>

<?php on_header(); ?>

<div id="main">
<div id="path"><a href="contribute.php?cat=<?php echo @$_GET['cat']; ?>">記事投稿</a> &gt; 記事投稿詳細</div>
<div>
<?php if(!count($category_index)||!count($fieldset_index)) : ?>
	<div class="alert">項目の種類が設定されていません。</div>
<?php else :
		if(@$error['INVALID_URL']) : ?>
	<div class="alert">URLに不正な文字が含まれています。半角英数字(a-Z,0-9)及び一部の記号(_-)のみ使用できます</div>
<?php   elseif(@$error['DUPLICATE_DIR']) : ?>
	<div class="alert">URLが重複しています。(<?php echo $_POST['url'];?>)</div>
<?php   endif ?>
	<form method="post" action="contribute_detail.php?id=<?php echo @$_GET['id']; ?>&cat=<?php echo @$_GET['cat']; ?>" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="edit" />
		<input type="hidden" name="id" value="<?php echo @$_GET['id']; ?>" />
		
		<p class="form_contribute_id">
			<label>
				<b>記事ID：</b>
				<?php echo htmlspecialchars( $_GET['id'] ) ?>
			</label>　
			<label>
				<b>URL：</b>
				http://<?php if(is_smart_phone()){ ?>～/<?php }else{ ?><?php echo $_SERVER['SERVER_NAME']; ?><?php } ?>/<?php echo $curl; ?>/<?php echo $rooturl?"$rooturl/":'';?><input type="text" name="url" value="<?php echo htmlspecialchars($url); ?>" />/
			</label>
		</p>
		<hr />
		
		<div class="clearfix">
			<div class="contribute_detail_left">
				<div class="contribute_detail_header">
					<p>
						<label>
							記事タイトル
							<input type="text" name="title" value="<?php echo htmlspecialchars(@$contribute['title']); ?>" class="title" />
						</label>
					</p>
					<p>
						項目の種類
						<select id="field_select" name="field" onchange="$('.fieldset').hide();$('#fieldset_'+$(this).val()).show();">
<?php foreach($fieldset_index as $rowid=>$id) :
	$fieldset_name=@file_get_contents(DATA_DIR.'/field/'.$id.'/name.dat') ?>
						<option value="<?php echo $id; ?>"<?php echo @$contribute['field']==$id?' selected="selected"':''; ?>><?php echo htmlspecialchars($fieldset_name); ?></option>
<?php endforeach ?>
						</select>
					</p>
				</div>
				
				<?php foreach($fieldset_index as $rowid => $id) :
							$field_index = unserialize(@file_get_contents(DATA_DIR."/field/{$id}/index.dat")); ?>
				<div class="fieldset" id="fieldset_<?php echo $id; ?>" >
					<table>
						<?php if($field_index) : foreach($field_index as $field_id) : ?>
						<tr>
						<?php $filename = DATA_DIR."/field/{$id}/{$field_id}.dat";
									$field_data=unserialize(@file_get_contents($filename)); ?>
							<th><?php echo htmlspecialchars($field_data['name']);?>
							<p class="field_label">(<?php echo htmlspecialchars(isset($type_map[$field_data['type']])?$type_map[$field_data['type']]:$field_data['type']); ?>)</p></th>
							<td>
								<?php if(!empty($field_data['label'])) : ?>
								<p class="contribute_label"><?php echo $field_data['label'] ?></p>
								<?php endif;
								switch($field_data['type']){
									case 'multiline' : 
										$val = (strlen(htmlspecialchars(@$contribute['data'][$id][$field_id])))
														? htmlspecialchars(@$contribute['data'][$id][$field_id])
														: $field_data['value']; ?>
								<textarea class="ckeditor" name="data[<?php echo $id; ?>][<?php echo $field_id; ?>]"><?php echo $val ?></textarea>
								<?php
									break;
									
									case 'oneline':
										$val = (strlen(htmlspecialchars(@$contribute['data'][$id][$field_id])))
														? htmlspecialchars(@$contribute['data'][$id][$field_id])
														: $field_data['value']; ?>
								<input type="text" name="data[<?php echo $id; ?>][<?php echo $field_id; ?>]" value="<?php echo $val; ?>" />
								<?php
									break;
									
									case 'image':
										if(@$contribute['data'][$id][$field_id]) : ?>
								<div class="image_action">
									<img src="../_data/contribute/images/<?php echo $contribute['data'][$id][$field_id];?>" style="max-width:140px;max-height:140px;"<?php if(is_smart_phone()){ ?> width="140px"<?php } ?> /><br />
									<input type="hidden" name="data[<?php echo $id; ?>][<?php echo $field_id; ?>]" value="<?php echo @$contribute['data'][$id][$field_id];?>" />
									<input type="checkbox" name="delete_image[<?php echo $id; ?>][<?php echo $field_id; ?>]" value="1" />&nbsp;登録済み画像を削除<br />
								</div>
								<?php endif ?>
								<input type="file" name="image[<?php echo $id; ?>][<?php echo $field_id; ?>]" /><br />
								<?php if(ini_get ('upload_max_filesize')) : ?>
									アップロードサイズ上限：<?php echo ini_get ('upload_max_filesize') ?>
								<?php endif ?>
								<?php
									break;
									
									case 'radio': ?>
								<ul class="contribute_list">
									<?php if(strlen(trim($field_data['value']))): foreach(explode(',',$field_data['value']) as $key => $val ): $val = trim($val) ?>
									<?php $chk = (htmlspecialchars(@$contribute['data'][$id][$field_id]) == $val
															|| ( (!strlen(htmlspecialchars(@$contribute['data'][$id][$field_id]))
															|| !@in_array($val,$field_data['value']) ) && $key == 0 ) )
																			? 'checked="checked"'
																			: ''; ?>
									<li>
										<label><input type="<?php echo $field_data['type'] ?>" name="data[<?php echo $id; ?>][<?php echo $field_id; ?>]" value="<?php echo $val ?>" <?php echo $chk ?> />
										<?php echo $val ?></label>
									</li>
									<?php endforeach; endif; ?>
								</ul>
								<?php
									break;
									
									case 'checkbox' : ?>
								<ul class="contribute_list">
									<?php
									if(strlen(trim($field_data['value']))):
										$contribute['data'][$id][$field_id] = @explode(',',$contribute['data'][$id][$field_id]); 
										foreach(explode(',',$field_data['value']) as $key => $val ): $val = trim($val) ?>
									<?php $chk = (@in_array($val,$contribute['data'][$id][$field_id]))
																		? 'checked="checked"'
																		: ''; ?>
									<li>
										<label><input type="<?php echo $field_data['type'] ?>" name="data[<?php echo $id; ?>][<?php echo $field_id; ?>][]" value="<?php echo $val ?>" <?php echo $chk ?> />
										<?php echo $val ?></label>
									</li>
									<?php endforeach; endif; ?>
								</ul>
								<?php
									break;
									
									case 'select': ?>
								<select name="data[<?php echo $id; ?>][<?php echo $field_id; ?>]" class="contribute_select">
									<option value="">----</option>
									<?php if(strlen(trim($field_data['value']))): foreach(explode(',',$field_data['value']) as $key => $val ): $val = trim($val) ?>
									<?php $chk = (htmlspecialchars(@$contribute['data'][$id][$field_id]) == $val )
																? 'selected="selected"'
																: ''; ?>
									<option value="<?php echo $val ?>" <?php echo $chk ?>><?php echo $val ?></option>
									<?php endforeach; endif; ?>
								</select>
								<?php
									break;
								} ?>
							</td>
						</tr>
						<?php endforeach; endif ?>
					</table>
					
					<?php if( count($field_index) > 10 ) : ?>
					<p class="center">
						<input class="regist_button" type="button" value="登録" onclick="submit();" />
					</p>
					<?php endif ?>
				</div>
				<?php endforeach ?>
				
			</div>
			
			<div class="contribute_detail_right">
				<p>
					<label>
						カテゴリ<br />
						<select name="category">
<?php foreach($category_index as $rowid=>$id) :
			$category_data=unserialize(@file_get_contents(DATA_DIR.'/category/'.$id.'.dat')) ?>
				<option value="<?php echo $id; ?>"<?php echo (@$contribute['category']==$id || ( !isset($contribute) && $id==@$_GET['cat']))? ' selected="selected"':''; ?>><?php echo htmlspecialchars($category_data['name']); ?></option>
<?php endforeach ?>
						</select>
					</label>
				</p>
				
				<p>
					公開日<br />
					<?php
						@list($year,$month,$day)=explode('/',@$contribute['public_begin_datetime']);
					?>
					<input type="text" style="width:40px;" name="public_begin_datetime_year" value="<?php echo htmlspecialchars($year); ?>" />年
					<input type="text" style="width:20px;" size="2" name="public_begin_datetime_month" value="<?php echo htmlspecialchars($month); ?>" />月
					<input type="text" style="width:20px;" size="2" name="public_begin_datetime_day" value="<?php echo htmlspecialchars($day); ?>" />日
					<br />
					<span class="note">yyyy/mm/dd (未入力の場合は、即時公開となります)</span>
				</p>
				<p>
					終了日<br />
					<?php
						@list($year,$month,$day)=explode('/',@$contribute['public_end_datetime']);
					?>
					<nobr><input type="text" style="width:40px;" name="public_end_datetime_year" value="<?php echo htmlspecialchars($year); ?>" />年
					<input type="text" style="width:20px;" size="2" name="public_end_datetime_month" value="<?php echo htmlspecialchars($month); ?>" />月
					<input type="text" style="width:20px;" size="2" name="public_end_datetime_day" value="<?php echo htmlspecialchars($day); ?>" />日</nobr>
					<br />
					<span class="note">yyyy/mm/dd (未入力の場合は、無期限で公開となります)</span>
				</p>
				<hr />
				<p class="center">
					<input class="regist_button" type="button" value="登録" onclick="submit();" />
				</p>
			</div>
		</div>
	</form>
	<?php endif ?>
</div>

</div>
<!-- /#main -->

<?php
on_sidebar();

on_footer();
