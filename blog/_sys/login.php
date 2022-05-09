<?php require_once('../_app/sys.php'); ?>
<?php
if(isset($_POST['log'])){
	if($_POST['log']=='in'){
		if(!auth(@$_POST['id'],@$_POST['password'])){
			$auth_error=true;
		}
	}elseif($_POST['log']=='out'){
		unset($_SESSION['login']);
		header('Location: ./');
		exit;
	}
}
?>
<?php on_header(); ?>


<div id="login">
	<form method="post" action="">
		<input type="hidden" name="log" value="in" />
		<table>
			<tr>
				<th>ID</th><td><input type="text" name="id" style="width:160px"/></td>
			</tr>
			<tr>
				<th>PASSWORD</th><td><input type="password" name="password" style="width:160px" /></td>
			</tr>
		</table>
<?php if(!empty($_POST['id']) || !empty($_POST['password'])): ?>
		<p class="alert">認証に失敗しました</p>
<?php endif ?>
		<span id="submit" style="margin-left:170px;"><input type="submit" value="ログイン" /></span>
	</form>
</div>

