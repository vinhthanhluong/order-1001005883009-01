<?php require_once('../_app/sys.php'); ?>
<?php on_header(); ?>
<div id="main">

<?php
function output_top($error=false)
{
	$fieldset_index_filename=DATA_DIR.'/field/index.dat';
	if(file_exists($fieldset_index_filename)){
		$fieldset_index=unserialize(@file_get_contents($fieldset_index_filename));
	}else{
		$fieldset_index=array();
	}
?>

<?php
	if($error){
?>
		<p class="alert">ファイルが選択されていないか、ファイルがの中身が空です。</p>
<?php
	}
?>

<div class="setting_field">
	<select name="fieldset" onchange="$('.fieldset').hide();$('#fieldset_'+$(this).val()).show();">
		<option value="">項目の種類を選択してください</option>
<?php
	foreach($fieldset_index as $rowid=>$id){
		if(file_exists(DATA_DIR.'/field/'.$id.'/name.dat')){
			$fieldset_name=@file_get_contents(DATA_DIR.'/field/'.$id.'/name.dat');
		}else{
			$fieldset_name='';
		}
?>
		<option value="<?php echo $id; ?>"<?php echo @$_POST['field']==$id?' selected="selected"':''; ?>><?php echo htmlspecialchars($fieldset_name); ?></option>
<?php
	}
?>
	</select>
</div>
<?php
	foreach($fieldset_index as $rowid=>$id){
		if(file_exists(DATA_DIR."/field/{$id}/index.dat")){
			$field_index=unserialize(@file_get_contents(DATA_DIR."/field/{$id}/index.dat"));
		}else{
			$field_index=array();
		}
?>
	<div class="fieldset" id="fieldset_<?php echo $id; ?>" style="display:<?php echo @$_POST['field']==$id?'block':'none'; ?>;">
		<fieldset>
			<legend>CSVフォーマット</legend>
			ID,公開日,終了日,記事名,URL,カテゴリ,項目の種類<?php
		foreach($field_index as $field_id){
			$filename=DATA_DIR."/field/{$id}/{$field_id}.dat";
			$field_data=unserialize(@file_get_contents($filename));
?>,<?php echo htmlspecialchars($field_data['name']);?><?php
	}
?>
		</fieldset>
		<div class="alert section">
			※上記フォーマットに合わせてCSVデータを用意してください<br />
			※IDが空の場合、新規登録となります<br />
			※URLが空の場合、「post-連番」で自動生成されます<br />
			※画像データは、FTPで「_upload」ディレクトリに画像データを予めアップロードしておき、CSVには「/_upload/アップロードしたファイル名」と入力して登録してください<br />
		</div>
		<p class="data_dl"><a href="csvdl.php?field=<?php echo $id;?>" target="_blank" class="dl_link">>>記事データダウンロード</a></p>
		<fieldset>
			<legend>CSVアップロード</legend>
			<form method="post" action="import.php" enctype="multipart/form-data">
				<input type="hidden" name="mode" value="confirm" />
				<input type="hidden" name="field" value="<?php echo $id; ?>" />
				CSVファイル：<input type="file" name="csv" /><br />
				<input class="regist_button" type="submit" value="次へ" style="margin:10px 0px 0px 0px;" />
			</form>
		</fieldset>
<?php
	if(file_exists(DATA_DIR.'/_contribute')){
		$date=date("Y/n/d H:i:s.", filectime(DATA_DIR.'/_contribute'));
?>
		<fieldset>
			<legend>データ復旧</legend>
			<?php echo $date; ?>にインポートされる直前のデータがあります。

			<form method="post" action="import.php">
				<input type="hidden" name="mode" value="repair" />
				<input class="regist_button" type="submit" value="直前データに戻す" style="margin:10px 0px 0px 0px;" onclick="return confirm('直前データに戻します。よろしいですか？');" />
			</form>

		</fieldset>
<?php
	}
?>
	</div>
<?php
	}
}

function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
	$d = preg_quote($d);
	$e = preg_quote($e);
	$_line = "";
	while ((@$eof != true)and(!feof($handle))) {
		$_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
		$itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
		if ($itemcnt % 2 == 0) $eof = true;
	}
	$_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
	$_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
	preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
	$_csv_data = $_csv_matches[1];
	for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
		$_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
		$_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
	}
	return empty($_line) ? false : $_csv_data;
}
function output_confirm(){
	//setlocale(LC_ALL,'ja_JP.Shift_JIS');
	$csv=array();
	$fp=fopen($_FILES['csv']['tmp_name'],"r");
	while($data = fgetcsv_reg($fp)){
		$csv[]=$data;
	}
	fclose($fp);
	array_shift($csv);
	mb_convert_variables('UTF-8','SJIS-win',$csv);
	$_SESSION['import_csv'][$_POST['field']]=$csv;
	unlink($_FILES['csv']['tmp_name']);
	//setlocale(LC_ALL,'ja_JP.UTF-8');
?>
	<div>
		下記内容で登録します。<br />
		<form method="post" action="import.php">
			<input type="hidden" name="field" value="<?php echo $_POST['field']; ?>" />
			<input type="hidden" name="mode" value="regist" />
			<input type="submit" class="regist_button" value="登録" />
		</form>
		<form method="get" action="import.php">
			<input type="submit" class="cancel" value="キャンセル" />
		</form>
	</div>
<?php
	$field=get_field($_POST['field']);

	foreach($_SESSION['import_csv'][$_POST['field']] as $data){
?>
	<div>
		<div>
			ID:<?php echo htmlspecialchars($data[0]); ?>(<?php echo $data[0]?'<span class="renew">更新</span>':'<span class="new">新規</span>'; ?>)
		</div>
		<table class="import_table">
			<tr><th>公開日</th><td><?php echo htmlspecialchars($data[1]); ?>～<?php echo htmlspecialchars($data[2]); ?></td></tr>
			<tr><th>記事名</th><td><?php echo htmlspecialchars($data[3]); ?></td></tr>
			<tr><th>URL</th><td><?php echo htmlspecialchars($data[4]); ?></td></tr>
			<tr><th>カテゴリ</th><td><?php echo htmlspecialchars($data[5]); ?></td></tr>
<?php
		$index=0;
		foreach($field as $f){
			$index++;
?>
			<tr><th><?php echo htmlspecialchars($f['name']); ?></th><td><?php echo htmlspecialchars($data[$index+5]); ?></td></tr>
<?php
		}
?>
		</table>
	</div>
	<br />
<?php
	}
?>
	<div>
		<form method="post" action="import.php">
			<input type="hidden" name="field" value="<?php echo $_POST['field']; ?>" />
			<input type="hidden" name="mode" value="regist" />
			<input type="submit" class="regist_button" value="登録" />
		</form>
		<form method="get" action="import.php">
			<input type="submit" class="cancel" value="キャンセル" />
		</form>
	</div>
<?php
}

function output_regist(){
	if($sess=$_SESSION['import_csv'][$_POST['field']]){
		backup_temp();

		$field=get_field($_POST['field']);
		$contribute_index=unserialize(@file_get_contents(DATA_DIR.'/contribute/index.dat'));
		$contribute_index_map=array();
		$new_id=0;
		foreach($contribute_index as $rowid=>$data){
			$contribute_index_map[$data['id']]=$rowid;
			if($data['id']>=$new_id){
				$new_id=$data['id']+1;
			}
		}
		foreach($sess as $s){
			$index_data=array(
				'public_begin_datetime'=>$s[1]
				,'public_end_datetime'=>$s[2]
				,'category'=>$s[5]
				,'field'=>$_POST['field']
			);
			if($id=$s[0]){
				//更新
				$index_data['id']=$id;
				$contribute_data=unserialize(@file_get_contents(DATA_DIR.'/contribute/'.$id.'.dat'));
				$contribute_index[$contribute_index_map[$id]]=$index_data;
				$is_new=false;
			}else{
				//新規
				$id=$index_data['id']=$new_id;
				$new_id++;
				$contribute_index[]=$index_data;
				$is_new=true;
			}
			$contribute_data['title']=$s[3];
			$contribute_data['url']=strlen($s[4])?$s[4]:'post-'.$id;
			$data=array();
			$findex=0;
			foreach($field as $fid=>$f){
				$findex++;
				$val=$s[5+$findex];
				if($f['type']=='image'){
					if($contribute_data['data'][$_POST['field']][$fid]!=$val){
						if(!strlen($val)){
							//画像削除
							unlink(DATA_DIR.'/contribute/images/'.$contribute_data['data'][$_POST['field']][$fid]);
						}elseif(preg_match('@/_upload/(.*)@',$val,$matches)){
							//画像アップロード
							preg_match('@\..*$@',$matches[1],$ex);
							$org_filename=$val;
							$val="{$id}_{$_POST['field']}_{$fid}{$ex[0]}";
							data_write('/contribute/images/'.$val,@file_get_contents(ROOT_DIR.$org_filename));
						}
					}
				}
				$contribute_data['data'][$_POST['field']][$fid]=$val;
			}
			data_write('/contribute/'.$id.'.dat',serialize($contribute_data));
			if($is_new){
				make_front_contribute($id);
			}
		}
		data_write('/contribute/index.dat',serialize($contribute_index));
		header('Location: import.php?reg=1');
		exit;
	}
?>
	登録しました。
<?php
}

?>
<div id="path">記事インポート</div>
<div>
<?php
switch(@$_POST['mode']){
case 'confirm':
	if($_FILES['csv']['size']){
		output_confirm();
	}else{
		output_top(true);
	}
	break;
case 'regist':
	output_regist();
	break;
case 'repair':
	$data_dir=get_data_dir();
	if(file_exists($DATA_DIR.'/_contribute')){
		$org=$DATA_DIR.'/contribute';
		$new=$DATA_DIR.'/_contribute';
		//echo "rm -r $org","<br />";
		//echo "rename($new,$org)";
		`rm -r $org`;
		rename($new,$org);
		//@mkdir($dir.'_data/_contribute');
		//@chmod($dir.'_data/_contribute',0777);
		//`cp -rp $src $dest`;
	}
?>
	直前データに戻しました。
<?php
	break;
default:
	if(@$_GET['reg']){
?>
	登録しました。
<?php
	}else{
		output_top();
	}
}
?>
</div>

</div>
<!-- /#main -->

<?php
on_sidebar();

on_footer();
