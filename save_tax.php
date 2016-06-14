<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn())
	{
		toLogin();
		die();
	}
	if(!AllowUser(array(1,2)))
	{
		redirect("index.php");
	}

	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;
		$con->myQuery("UPDATE tax SET percentage=:percentage WHERE tax_id=:tax_id",$inputs);			
		Alert("Update successful","success");
		redirect("tax.php");
		die();	
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>