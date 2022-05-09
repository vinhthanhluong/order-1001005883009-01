<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Over Notes</title>

<meta http-equiv="Content-Language" content="ja" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<?php if(is_smart_phone()): ?>
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
<?php endif ?>
<script type="text/javascript" src="<?php echo SYS_URI ?>/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="<?php echo SYS_URI ?>/js/jquery-ui-1.9.0.custom.min.js"></script>
<script type="text/javascript" src="<?php echo SYS_URI ?>/js/ckeditor/ckeditor.js"></script>

<link rel="stylesheet" href="<?php echo SYS_URI ?>/css/<?php echo is_smart_phone()?'sp.css':'sys.css'; ?>" type="text/css" />

<script type="text/javascript" charset="utf-8">
(function($){
$(function(){
	$('#path input').click(function(){
		var v = $(this).val();
		$(this).val($(this).attr('name')).attr('name',v);
		$('#topic').slideToggle();
	});
	if($('#field_select').children().length > 1) $('.fieldset').hide();
	$('#fieldset_'+$('select[name="field"]').val()).show();
});
})(jQuery);

</script>

</head>

<body id="<?php echo SELF ?>">
<div id="container">
	<div class="wrapper">
		<div id="head" class="clearfix">
			<h1 id="logo"><a href="./"><img src="./images/sys_logo.png" alt="Over Notes" width="154" height="24" /></a></h1>
			<div id="logout">
				<form method="post" action="<?php echo SYS_URI; ?>/login.php">
					<input type="hidden" name="log" value="out" />
					<?php if(!empty($_SESSION['login']['id'])): ?><input type="submit" value="ログアウト" /><?php endif ?>
				</form>
			</div>
		</div>
		<div id="contents" class="clearfix">
