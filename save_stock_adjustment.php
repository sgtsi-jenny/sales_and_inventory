<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
			toLogin();
			die();
	}



if(!empty($_POST)){
//Validate form inputs
		$inputs=$_POST;

		var_dump($inputs);
		die;
		
		$errors="";


}
else{
		redirect('stock_adjustments_main.php');
		die();
	}
	redirect('index.php');
?>
