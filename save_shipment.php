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

		}
		else{

			// var_dump($_GET['p_id']);
			// var_dump($inputs['payment_id']);

			if(empty($inputs['shipment_id'])){
				// var_dump("ADD PAYMENT");
				// die;
				//Insert
				// $inputs=$_POST;
				unset($inputs['shipment_id']);
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				// $sales_master_id=$inputs['sales_master_id'];
					$date_shipped = date_create($inputs['date_shipped']);
				$inputs['date_shipped']= date_format($date_shipped, 'Ymd');	
					$date_delivered = date_create($inputs['date_delivered']);
				$inputs['date_delivered']= date_format($date_delivered, 'Ymd');	
				// $inputs['user_id']=$_SESSION[WEBAPP]['user']['user_id'];
				// var_dump($inputs);
				// die;

				$con->myQuery("INSERT INTO shipments(customer_id,bill_to,ship_to,ship_from,ship_service,ship_method,date_delivered,date_shipped,sales_master_id,tracking_code,comments) VALUES(:customer_id,:bill_to,:ship_to,:ship_from,:ship_service,:ship_method,:date_delivered,:date_shipped,:sales_master_id,:tracking_code,:comments)",$inputs);
				$file_id=$con->lastInsertId();
				$sm['sales_master_id']=$inputs['sales_master_id'];
				$sm['date_modified']=$now->format('Ymd');
				$sales_status_id=4;
				$con->myQuery("UPDATE sales_master SET sales_status_id='$sales_status_id',date_modified=:date_modified,shipment_id='$file_id' WHERE sales_master_id=:sales_master_id",$sm);
							

				Alert("Order was successfully shipped.","success");
			}
			else{	
				var_dump("UPDATE PAYMENT");
				die;			
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