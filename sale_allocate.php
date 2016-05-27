<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	// if(!AllowUser(array(1,2,5))){
	// 	redirect("index.php");
	// }
		$inputs['sales_master_id']=$_GET['id'];
		$sales_status_id=2;
				// var_dump($sales_status_id);
				// die;
				
				$con->myQuery("UPDATE sales_master SET sales_status_id='$sales_status_id' WHERE sales_master_id=:sales_master_id",$inputs);
				
				Alert("Your inventory has been allocated","success");
				redirect("sales_order_details.php?id=".$inputs['sales_master_id']);
				
	
		
?>