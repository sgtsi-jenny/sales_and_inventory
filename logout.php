<?php
	require_once 'support/config.php';
	// $is_login=0;
	// $userid=$_SESSION[WEBAPP]['user']['id'];
	$con->myQuery("UPDATE users SET is_login=0 where id=?",array($_SESSION[WEBAPP]['user']['id']));
	session_destroy();
	redirect('frmlogin.php');
?>