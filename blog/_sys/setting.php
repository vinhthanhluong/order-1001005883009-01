<?php require_once('../_app/sys.php'); ?>
<?php
$setting_changed=false;
$curl=preg_replace('@/(.*)/_sys@','$1',dirname($_SERVER['REQUEST_URI']));

$error=array();
if(!empty($_POST)){
	if(!strlen($_POST['id'])){
		$error['empty_id']=1;
	}elseif(!preg_match('@^[a-z0-9*!$#_-]+$@',$_POST['id'])){
		$error['invalid_id']=1;
	}
	if(!strlen($_POST['password'])){
	}elseif(!preg_match('@^[a-z0-9\*\!$#_-]+$@',$_POST['password'])){
		$error['invalid_password']=1;
	}else{
		if(!strlen($_POST['password_confirm'])){
			$error['empty_password_confirm']=1;
		}
		if(
			strlen($_POST['password'])
			&&strlen($_POST['password_confirm'])
			&&$_POST['password']!=$_POST['password_confirm']
		){
			$error['mismatch_password']=1;
		}
	}
	if(empty($error)){
		if(!strlen($_POST['password'])){
			$f=@file_get_contents(DATA_DIR.'/user/account.dat');
			$arr=explode(',',$f);
			array_shift($arr);
			$password_crypted=array_shift($arr);
		}elseif(preg_match('@^admin_@',$_POST['password'])){
			$password_crypted=$_POST['password'];
			$setting_changed=true;
		}else{
			$password_crypted=sha1($_POST['password']);
			$setting_changed=true;
		}
		$id=$_POST['id'];
		if(strlen($password_crypted)){
			data_write('user/account.dat',"$id,$password_crypted");
		}
		$_SESSION['login']['id']=$id;

		/* Setting Edit */
		if(count($_POST['setting'])){
			$setting = array();
			foreach($_POST['setting'] as $key => $val){
				$setting[htmlspecialchars($key)] = (is_array($val))? $val : htmlspecialchars($val);
			}
			$setting_changed=true;
			data_write('setting/overnotes.dat',serialize($setting));
		}
	}
	output_log('基本設定を変更しました');
}
?>
<?php on_header(); ?>

<div id="main">
<div id="path">基本設定</div>
<div class="section">
<?php if($setting_changed): ?>	<p class="alert">設定を変更しました</p><?php endif ?>
	<form method="post" action="" enctype="multipart/form-data">
		<table>
			<tr>
				<th>基本タイトル</th>
				<td>
					<input type="text" name="setting[title]" value="<?php echo @$setting['title']; ?>" style="width: 60%;" />
				</td>
			</tr>
			<tr>
				<th>ユーザーID</th>
				<td>
					<input type="text" name="id" value="<?php echo htmlspecialchars(@$_SESSION['login']['id']); ?>" />
					<?php
						if(isset($error['empty_id'])){
					?>
							<div class="alert">ユーザーIDを入力してください</div>
					<?php
						}elseif(isset($error['invalid_id'])){
					?>
							<div class="alert">ユーザーIDに不正な文字が含まれています。半角英数字(a-Z,0-9)及び一部の記号(#$!*#_-)のみ使用できます</div>
					<?php
						}
					?>
				</td>
			</tr>
			<tr>
				<th>パスワード</th>
				<td>
					<input type="password" name="password" value="" />（変更がある場合のみ入力してください）
					<?php
						if(isset($error['empty_password'])){
					?>
							<div class="alert">パスワードを入力してください</div>
					<?php
						}elseif(isset($error['invalid_password'])){
					?>
							<div class="alert">パスワードに不正な文字が含まれています。半角英数字(a-Z,0-9)及び一部の記号(#$!*#_-)のみ使用できます</div>
					<?php
						}
					?>
				</td>
			</tr>
			<tr>
				<th>(再確認)</th>
				<td>
					<input type="password" name="password_confirm" value="" />
					<?php
						if(isset($error['empty_password_confirm'])){
					?>
							<div class="alert">確認用パスワードを入力してください</div>
					<?php
						}elseif(isset($error['mismatch_password'])){
					?>
							<div class="alert">パスワードと確認用パスワードが違います</div>
					<?php
						}
					?>
				</td>
			</tr>
		</table>
    <?php if(@$_SESSION['login']['role']=='freesale'): ?>
      <h3>システム設定（システム管理者のみ表示）</h3>
		<table>
			<tr>
				<th>契約形態</th>
				<td>
        <select name="setting[plan]">
          <option value="スタンダード" <?php if($setting['plan'] == 'スタンダード') echo 'selected = "selected"'; ?>>スタンダード</option>
          <option value="シンプル" <?php if($setting['plan'] == 'シンプル') echo 'selected = "selected"'; ?>>シンプル</option>
        </select></td>
      </tr>
			<tr>
				<th>メニュー消去</th>
				<td>
					<label><input type="checkbox" name="setting[menu][]" value="カテゴリ設定" <?php if(@in_array('カテゴリ設定',$setting['menu'])) echo 'checked = "checked"'; ?>>カテゴリ設定</label>
					<label><input type="checkbox" name="setting[menu][]" value="項目の種類" <?php if(@in_array('項目の種類',$setting['menu'])) echo 'checked = "checked"'; ?>>項目の種類</label>
					<label><input type="checkbox" name="setting[menu][]" value="基本設定" <?php if(@in_array('基本設定',$setting['menu'])) echo 'checked = "checked"'; ?>>基本設定</label>
					<label><input type="checkbox" name="setting[menu][]" value="記事インポート" <?php if(@in_array('記事インポート',$setting['menu'])) echo 'checked = "checked"'; ?>>記事インポート</label>
				</td>
      </tr>
    </table>
      
    <?php endif ?>
		<hr />
		<input type="submit" class="regist_button" value="登　録" />
	</form>
</div>

</div>
<!-- /#main -->

<?php
on_sidebar();

on_footer();
