<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
			toLogin();
			die();
	}



if(!empty($_POST)){
//Validate form inputs
		$inputs=$_POST;
		$errors="";
		// var_dump($inputs);
		// die(); 	
		if (empty($inputs['']))
		{
			$errors.="No items in the list. <br/>";
		}
		if($errors!=""){

			Alert($errors,"danger");
				if(empty($inputs['select_id[]'])){
					redirect("stock_adjustments.php");
				}
				else{
					redirect("stock_adjustments.php?id=".urlencode($inputs['select_id[]']));
				}
				die;
		}
		else{
			$resu=$inputs['stock_adjmaster_id'];
			//IF id exists update ELSE insert
			// var_dump($resu);
			// die(); 	
			if(empty($inputs['stock_adjmaster_id'])){
				//Insert
				
				unset($inputs['stock_adjmaster_id']);
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$date_adjusted=date_format($now, 'Ymd');
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
		redirect('stock_adjustments_main.php');
		die();
	}
	redirect('index.php');
?>
