<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	// if(!AllowUser(array(1,2,5))){
	// 	redirect("index.php");
	// }

				// var_dump($inputs);
				// die;

	
	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;

		$errors="";
		
		// echo count($inputs['product_id']);
		// die;		
		if (empty($inputs['customer_id'])){
			$errors.="Select customer name. <br/>";
		}
		if ($inputs['product_id']=="Select Product"){
			$errors.="Please fill out your order and try again. <br/> ";
		}

		if($errors!=""){

			Alert($errors,"danger");
				if(empty($inputs['sales_master_id'])){
					redirect("frm_sales.php");
				}
				else{
					redirect("frm_sales.php?id=".urlencode($inputs['sales_master_id']));
				}
				die;
		}
		else{
			$sales_id=$inputs['sales_master_id'];
			//IF id exists update ELSE insert
			if(empty($inputs['sales_master_id'])){
				//Insert
				
				unset($inputs['sales_master_id']);
				//$userid=$_SESSION[WEBAPP]['user']['id'];
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$date_issue=date_format($now, 'Ymd');	
				$customer_id=$inputs['customer_id'];
				$description=$inputs['description'];
				$user_id=$_SESSION[WEBAPP]['user']['user_id'];

				// var_dump($inputs);
				// die;

				$total_cost = 0;
				foreach($inputs['total_price'] as $key=>$value)
				{
				   $total_cost+= $value;
				}

				
				unset($inputs['customer_id']);
				unset($inputs['description']);
				unset($inputs['current_quantity']);
				unset($inputs['total_price']);
				unset($inputs['prod_name']);

				//echo $arr_count=count($inputs);
				//echo count($inputs['product_id']);
				//die();
				$field_count=count($inputs);
				$arr_count=count($inputs['product_id']);
				
				// var_dump($inputs);
				// die;

				$con->myQuery("INSERT INTO sales_master (date_issue,total_amount,customer_id,user_id,sales_status_id,payment_status_id,description) VALUES ('$date_issue','$total_cost','$customer_id','$user_id','1','1','$description')", $inputs);

				$file_id=$con->lastInsertId();
				//var_dump($file_id);
				//die;
				//arr_count=count($inputs);
				// var_dump($inputs);
				// die;

				for ($i=0; $i < $arr_count; $i++) { 
					// var_dump($inputs['product_id'][$i]);
					// var_dump($inputs['prod_name'][$i]);
					// echo '<br>';
					$params=array(
						'product_id' => $inputs['product_id'][$i],
						'qty' => $inputs['quantity'][$i], 
						'selling_price' => $inputs['selling_price'][$i],
						'discount' => $inputs['discount'][$i],
						'tax' => $inputs['tax'][$i],
						'file_id' => $file_id,
						'total_cost' => $total_cost
						);
					// var_dump($params);
					// die;
					$con->myQuery("INSERT INTO sales_details (product_id,sales_master_id,quantity,unit_cost,total_cost,discount,tax) VALUES (:product_id,:file_id,:qty,:selling_price,:total_cost,:discount,:tax)", $params);		
				}			
				//die;
				Alert("Save succesful","success");
				redirect("sales_order_details.php?id=".$file_id);
				

			}
			else{
				
				$con->myQuery("UPDATE suppliers SET name=:name,description=:description, contact_number=:contact_number,address=:address, email=:email WHERE supplier_id=:supplier_id",$inputs);
				
				Alert("Update successful","success");
				redirect("sales_order_details.php?id=".$inputs['sales_master_id']);
				}
			
			// redirect("sales.php");
				
		}
		die();
		
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>