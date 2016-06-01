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

			if(empty($inputs['payment_id'])){
				//Insert
				// $inputs=$_POST;
				unset($inputs['payment_id']);
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$sales_master_id=$inputs['sales_master_id'];
				$pay_date = date_create($inputs['pay_date']);
				$inputs['pay_date']= date_format($pay_date, 'Ymd');	
				$inputs['user_id']=$_SESSION[WEBAPP]['user']['user_id'];
				// var_dump($inputs);
				// die;
				//$inputs['name']=$_POST['name'];

				$con->myQuery("INSERT INTO sales_payments(type,amount,pay_date,reference,sales_master_id,invoice_master_id,user_id) VALUES(:payment_type,:amount,:pay_date,:reference,:sales_master_id,:invoice_master_id,:user_id)",$inputs);

				$total_cost=$con->myQuery("SELECT (SELECT SUM(sd.total_cost) FROM sales_details sd WHERE sd.sales_master_id=sm.sales_master_id) AS total FROM sales_master sm
                                WHERE sm.sales_master_id=?",array($sales_master_id))->fetch(PDO::FETCH_ASSOC);

				$total_payment=$con->myQuery("SELECT 
                                SUM(sp.amount) AS total_payment
                                FROM sales_payments sp
                                INNER JOIN sales_master sm ON sp.sales_master_id=sm.sales_master_id
                                INNER JOIN invoice_master im ON sp.invoice_master_id=im.invoice_master_id
                                WHERE sp.sales_master_id=?",array($sales_master_id))->fetch(PDO::FETCH_ASSOC);
				$tc=$total_cost['total'];
				$tp=$total_payment['total_payment'];
				// var_dump($tc==$tp);
				// die;
				$sm['sales_master_id']=$inputs['sales_master_id'];
				$sm['date_modified']=$now->format('Ymd');
				if ($tc==$tp){
					$payment_status_id=2;
					$con->myQuery("UPDATE sales_master SET payment_status_id='$payment_status_id',date_modified=:date_modified WHERE sales_master_id=:sales_master_id",$sm);
				}
							

				Alert("Payment was successfully processed.","success");
			}
			else{				
				//Update
				// $con->myQuery("UPDATE users SET user_type_id=:user_type_id,first_name=:name,middle_name=:middle_name,last_name=:last_name,username=:username,password=:password,email=:email,contact_no=:contact_no WHERE id=:id",$inputs);
				// Alert("Update successful","success");
			}

			redirect("sales_payments.php?id=".$inputs['sales_master_id']);
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>