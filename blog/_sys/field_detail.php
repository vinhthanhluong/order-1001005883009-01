<?php require_once('../_app/sys.php'); ?>
<?php
$dir=DATA_DIR.'/field';

if($_GET['id']){
	$index_filename="$dir/{$_GET['id']}/index.dat";
	if(file_exists($index_filename)){
		$index=unserialize(@file_get_contents($index_filename));
	}else{
		$index=array();
	}
}
$done='';
if(!empty($_POST['mode'])){
	switch($_POST['mode']){
	case 'set_name':
		if(!strlen($_POST['name'])){
			$done='SET_NAME_ERROR';
		}else{
			$fieldset_index_filename=$dir.'/index.dat';
			if(file_exists($fieldset_index_filename)){
				$fieldset_index=unserialize(@file_get_contents($fieldset_index_filename));
			}else{
				$fieldset_index=array();
			}
			if(@$_GET['id']){
				$data_id=(int)$_GET['id'];
			}else{
				$data_id=count($fieldset_index)?((int)max($fieldset_index)+1):1;
				$fieldset_index[]=$data_id;
				data_write('field/index.dat',serialize($fieldset_index));
			}
			mkdir($dir.'/'.$data_id);
			chmod_if_not_suexec($dir.'/'.$data_id);
			data_write("field/{$data_id}/name.dat",$_POST['name']);
			header("Location: field_detail.php?id=".$data_id.'&nc=1');
			output_log('項目の種類を編集しました');
			exit;
		}
		break;
	case 'create':
		if(!$has_error){
		if(!strlen($_POST['name'])){
			header("Location: field.php?nn=1");
			exit;
		}
		$fieldset_index_filename=$dir.'/index.dat';
			if(file_exists($fieldset_index_filename)){
				$fieldset_index=unserialize(@file_get_contents($fieldset_index_filename));
			}else{
				$fieldset_index=array();
			}
			$data_id=count($fieldset_index)?((int)max($fieldset_index)+1):1;
			$fieldset_index[]=$data_id;
			data_write('field/index.dat',serialize($fieldset_index));
			mkdir($dir.'/'.$data_id);
			chmod_if_not_suexec($dir.'/'.$data_id);
			data_write("field/{$data_id}/name.dat",$_POST['name']);
			header("Location: field_detail.php?id=".$data_id.'&nn=1');
			output_log('項目の種類を追加しました');
			exit;
		}
	break;
	case 'edit':
		if(@$_GET['id']){
			if(@$_POST['delete']){
				$data_id=(int)$_POST['field_id'];
				unlink(DATA_DIR."/field/{$_GET['id']}/{$data_id}.dat");
				foreach($index as $rowid=>$id){
					if($id==$data_id){
						unset($index[$rowid]);
						break;
					}
				}
				data_write("field/{$_GET['id']}/index.dat",serialize($index));
				output_log('項目の種類を編集しました');
				$done='DELETE_FIELD';
			}elseif(@$_POST['edit']){
				$has_error=false;
				$index_cmp=$index;

				if(strlen($_POST['code'])){
					if(!preg_match('/^[a-zA-Z_][0-9a-zA-Z_]*$/',strtolower($_POST['code']))){
						$has_error=true;
						$done='INVALID_CODE';
						break;
					}
					foreach($index_cmp as $__i=>$_i){
						if(@$_POST['field_id']!=$_i){
							$_data=unserialize(@file_get_contents("$dir/{$_GET['id']}/{$_i}.dat"));
							if(strtolower($_data['code'])==strtolower($_POST['code'])){
								$has_error=true;
								$done='DUPLICATE_CODE';
								break;
							}
						}
					}
				}
				if(!$has_error){
					if(@$_POST['field_id']){
						$data_id=(int)$_POST['field_id'];
						$done='EDIT_FIELD';
					}else{
						$data_id=count($index)?((int)max($index)+1):1;
						$index[]=$data_id;
						data_write("field/{$_GET['id']}/index.dat",serialize($index));
						$done='ADD_FIELD';
					}
					$data=array(
						'name'=>$_POST['name']
						,'type'=>$_POST['type']
						,'code'=>$_POST['code']
						,'label'=>$_POST['label']
						,'value'=>$_POST['value']
					);
					data_write("field/{$_GET['id']}/{$data_id}.dat",serialize($data));
					output_log('項目の種類を編集しました');
				}
			}
		}
		break;
	case "sort":
		$sorted=array();
		foreach($_POST['field'] as $i=>$rep_index){
			$sorted[$i]=$index[$rep_index];
		}
		data_write("field/{$_GET['id']}/index.dat",serialize($sorted));
		header("Location: ./field_detail.php?id=".$_GET['id']);
		output_log("項目の種類を編集しました");
		exit;
		break;
	}
}
if($_GET['id']&&file_exists("$dir/{$_GET['id']}/name.dat")){
	$name=@file_get_contents("$dir/{$_GET['id']}/name.dat");
}else{
	$name='';
}
?>
<?php on_header(); ?>

<div id="main">
<div id="path"><a href="field.php">項目の種類</a> &gt; 項目の種類詳細</div>
<?php
switch($done){
case 'ADD_FIELD':
?>
	<div class="alert">項目を追加しました</div>
<?php
	break;
case 'EDIT_FIELD':
?>
	<div class="alert">項目を編集しました</div>
<?php
	break;
case 'DELETE_FIELD':
?>
	<div class="alert">項目を削除しました</div>
<?php
	break;
case 'INVALID_CODE':
?>
	<div class="alert">置換コードは半角英字又は[_」(アンダーバー)で始まる半角英数字及び_で入力してください</div>
<?php
	break;
case 'DUPLICATE_CODE':
?>
	<div class="alert">置換コードが重複していたため、登録をキャンセルしました。</div>
<?php
	break;
case 'SET_NAME_ERROR':
?>
	<div class="alert">設定名を入力してください。</div>
<?php
	break;
default:
	if(@$_GET['nn']): ?>
	<div class="alert">新しい項目の種類を作成しました</div>
<?php elseif(@$_GET['nc']): ?>
	<div class="alert">設定名を変更しました</div>
<?php endif;
	break;
}
?>
<div>
	<div class="setting_field">
		<form method="post" action="field_detail.php?id=<?php echo @$_GET['id']; ?>" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="set_name" />
			種類名：<input type="text" name="name" value="<?php echo htmlspecialchars($name);?>" />
			<input class="add_button" type="submit" value="種類名変更" />
		
		</form>
	</div>
<hr />
<?php
if(@$_GET['id']){
?>
	<div class="setting_field">置換コードには、半角英字又は[_」(アンダーバー)で始まる半角英数字及び_を使用できます</div>
	<div class="list_caption">
      <table class="field_list">
        <tr>
          <!-- <th class="field_change"></th> -->
          <th class="field_move"></th>
          <th class="field_name">項目名</th>
          <th class="field_type">タイプ</th>
          <th class="field_code">コード</th>
          <th class="field_label">ラベル</th>
          <th class="field_value">値</th>
          <th class="field_send"></th>
        </tr>
      </table>
	</div>
	<ul class="list" id="field_list">
<?php
	$type_list=array(
		'oneline'=>'一行テキスト'
		,'multiline'=>'複数行テキスト'
		,'radio'=>'ラジオ'
		,'checkbox'=>'チェックボックス'
		,'select'=>'セレクト'
		,'image'=>'画像'
	);
	foreach($index as $rowid=>$id){
		$data=unserialize(@file_get_contents("$dir/{$_GET['id']}/{$id}.dat"));
?>
		<li id="field-<?php echo $rowid; ?>">
			<form method="post" action="field_detail.php?id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data">
      <input type="hidden" name="mode" value="edit" />
      <input type="hidden" name="field_id" value="<?php echo $id; ?>" />
      <table class="field_list">
        <tr>
          <!-- <td class="field_change"><input type="checkbox" name="change" value="" /></td> -->
          <td class="field_move"><img src="./images/icon_move.png" class="move" /></td>
          <td class="field_name">
            <input type="text" name="name" value="<?php echo htmlspecialchars($data['name']); ?>" />
          </td>
          <td class="field_type">
            <select name="type">
            <?php foreach($type_list as $type=>$type_name): ?>
              <option value="<?php echo $type; ?>"<?php echo $type==$data['type']?' selected="selected"':''; ?>><?php echo $type_name; ?></option>
            <?php endforeach ?>
            </select>
          </td>
          <td class="field_code"><input type="text" name="code" value="<?php echo htmlspecialchars($data['code']); ?>" /></td>
          <td class="field_label"><input type="text" name="label" value="<?php echo htmlspecialchars($data['label']); ?>" /></td>
          <td class="field_value"><input type="text" name="value" value="<?php echo htmlspecialchars($data['value']); ?>" /></td>
          <td class="field_send">
            <input type="submit" class="edit_button" name="edit" value="変更" onclick="return confirm('設定内容を変更します。よろしいですか？');" />
            <input type="submit" class="delete_button" name="delete" value="削除" onclick="return confirm('[<?php echo htmlspecialchars($data['name']); ?>]を削除します。よろしいですか？');"/>
        </tr>
      </table>
			</form>
		</li>
<?php
	}
?>
	</ul>

	<form method="post" action="field_detail.php?id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="edit" />
		<input type="hidden" name="edit" value="1" />
		<div class="list_add" style="margin:0px 0px 0px 23px;">
      <table class="field_list">
        <tr>
          <td class="field_move"></td>
          <td class="field_name">
            <input type="text" name="name" value="" />
          </td>
          <td class="field_type">
            <select name="type">
            <?php foreach($type_list as $type=>$type_name): ?>
              <option value="<?php echo $type; ?>"><?php echo $type_name; ?></option>
            <?php endforeach ?>
            </select>
          </td>
          <td class="field_code"><input type="text" name="code" value="" /></td>
          <td class="field_label"><input type="text" name="label" value="" /></td>
          <td class="field_value"><input type="text" name="value" value="" /></td>
          <td class="field_send">
            <input class="add_button" type="submit" value="項目追加" />
          </td>
        </tr>
      </table>

		</div>
	</form>
<?php
}
?>
</div>
<form id="form" method="post" action="">
	<input type="hidden" name="mode" value="sort" />
</form>
<script type="text/javascript">
	$('#field_list').sortable({
		axis: 'y'
		//,helper: 'clone'
		,placeholder: "placeholder"
		,handle: $('.move')
		,start: function(event,ui){
			//$('.out_category').css('display','list-item').css('color','#777777');
		}
		,update: function(event,ui){
			var seri=$('#field_list').sortable("serialize");
			var pairs=seri.split("&");
			$.each(pairs,function(i, val){
				var pair=val.split("=");
				$('#form').append('<input type="hidden" name="'+pair[0]+'" value="'+pair[1]+'" />');
			});
			$('#form').submit();
			var seri=$('#field_list').sortable("disable");
		}
	});
</script>

</div>
<!-- /#main -->

<?php
on_sidebar();

on_footer();
