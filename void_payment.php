<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

		//Validate form inputs
		$inputs=$_POST;
				// var_dump($_GET['id']);
				// var_dump($_GET['p_id']);
				// die;
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				// $sm['sales_payment_id']=$_GET['p_id'];
				// $sm['date_modified']=$now->format('Ymd');
				$sales_payment_id=$_GET['p_id'];
				$date_voided=$now->format('Ymd');
				// var_dump($sales_payment_id);
				// var_dump($date_modified);
				// die;

				$is_voided=1;
				// var_dump($is_voided);
				// die;
				$con->myQuery("UPDATE sales_payments SET is_voided='$is_voided',date_voided='$date_voided' WHERE sales_payment_id='$sales_payment_id'");
				
							

				Alert("Payment for SO".$_GET['id']." was successfully voided.","success");
			
			redirect("sales_payments.php?id=".$_GET['id']);

	
?>