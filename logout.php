<?php
	require_once 'support/config.php';
	// $is_login=0;
	// $userid=$_SESSION[WEBAPP]['user']['id'];
	$con->myQuery("UPDATE users SET is_login=0 where user_id=?",array($_SESSION[WEBAPP]['user']['user_id']));
	session_destroy();
	redirect('frmlogin.php');
?>