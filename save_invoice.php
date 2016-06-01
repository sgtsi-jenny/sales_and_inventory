<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}


	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;
		// var_dump($inputs);
		// 		die;

		
		$errors="";
		
		if($errors!=""){

			// Alert("You have the following errors: <br/>".$errors,"danger");
			// if(!empty($inputs['id'])){
			// 	redirect("frm_posts.php?a_id={$inputs['account_id']}&id={$inputs['id']}");
			// }
			// else{
			// 	redirect("posts.php?id={$inputs['account_id']}");
			// }
			// die;
		}
		else{

			if(empty($inputs['invoice_master_id'])){
				//Insert
				// $inputs=$_POST;
				unset($inputs['invoice_master_id']);

				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$due_payment = date_create($inputs['due_payment']);
				$inputs['due_payment']= date_format($due_payment, 'Ymd');
				$date_issue = date_create($inputs['date_issue']);
				$inputs['date_issue']= date_format($date_issue, 'Ymd');	
				// var_dump($inputs);
				// die;
				//$inputs['name']=$_POST['name'];
				$con->myQuery("INSERT INTO invoice_master(sales_master_id,bill_to,ship_to,customer_id,payment_due,date_issued,total_units,description) VALUES(:sales_master_id,:bill_to,:ship_to,:customer_id,:due_payment,:date_issue,:quantity,:description)",$inputs);
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$sm['sales_master_id']=$inputs['sales_master_id'];
				$sm['date_modified']=$now->format('Ymd');
				$sales_status_id=3;
				$con->myQuery("UPDATE sales_master SET sales_status_id='$sales_status_id',date_modified=:date_modified WHERE sales_master_id=:sales_master_id",$sm);

				Alert("Invoice was successfully created.","success");
			}
			else{				
				//Update
				// $con->myQuery("UPDATE users SET user_type_id=:user_type_id,first_name=:name,middle_name=:middle_name,last_name=:last_name,username=:username,password=:password,email=:email,contact_no=:contact_no WHERE id=:id",$inputs);
				// Alert("Update successful","success");
			}

			redirect("sales_order_details.php?id=".$inputs['sales_master_id']);
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>