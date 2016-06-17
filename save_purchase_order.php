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
		if (empty($inputs['supplier'])){
			$errors.="Select Supplier. <br/>";
		}
		if ($inputs['product_id']=="Select Product"){
			$errors.="Please fill out your order and try again. <br/> ";
		}
		if (empty($inputs['ship_to'])){
			$errors.="Enter Ship To. <br/>";
		}

		if($errors!=""){

			Alert($errors,"danger");
				if(empty($inputs['po_master_id'])){
					redirect("frm_sales.php");
				}
				else{
					redirect("frm_purchase.php?id=".urlencode($inputs['po_master_id']));
				}
				die;
		}
		else{
			$sales_id=$inputs['po_master_id'];
			//IF id exists update ELSE insert
			if(empty($inputs['po_master_id'])){
				//Insert
				
				unset($inputs['po_master_id']);
				//$userid=$_SESSION[WEBAPP]['user']['id'];
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$date_created=date_format($now, 'Ymd');	
				$suppier_id=$inputs['supplier_id'];
				$ship_to=$inputs['ship_to'];
				//$user_id=$_SESSION[WEBAPP]['user']['user_id'];

				// var_dump($inputs);
				// die;

				$total_cost = 0;
				foreach($inputs['total_cost'] as $key=>$value)
				{
				   $total_cost+= $value;
				}

				
				unset($inputs['customer_id']);
				unset($inputs['description']);
				unset($inputs['current_quantity']);
				unset($inputs['total_price']);
				unset($inputs['prod_name']);

				$date_modified = date('Ymd');
				//echo $arr_count=count($inputs);
				//echo count($inputs['product_id']);
				//die();
				//$field_count=count($inputs);
								
				 // var_dump($inputs);
				 // die;

				$con->myQuery("INSERT INTO po_master (purchased_date,supplier_id,po_status_id,payment_status_id,total_amount,ship_to,date_modified) VALUES ('$date_created','$suppier_id','1','1','$total_cost','$ship_to','$date_modified')", $inputs);

				$file_id=$con->lastInsertId();
				//var_dump($file_id);
				//die;
				//arr_count=count($inputs);
				// var_dump($inputs);
				// die;
				$arr_count=count($inputs['product_id']);
				// var_dump($arr_count);
				// die;
				for ($i=0; $i < $arr_count; $i++) { 
					// var_dump($inputs['product_id'][$i]);
					// var_dump($inputs['prod_name'][$i]);
					// echo '<br>';
					$params=array(
						'product_id' => $inputs['product_id'][$i],
						'qty' => $inputs['quantity'][$i], 
						'unit_cost' => $inputs['unit_cost'][$i],
						'file_id' => $file_id,
						'total_cost' =>$inputs['total_cost'][$i],
						);
					// var_dump($params);
					//  die;
					$con->myQuery("INSERT INTO po_details (po_master_id,product_id,qty_ordered,unit_cost,total_cost) VALUES (:file_id,:product_id,:qty,:unit_cost,:total_cost)", $params);		
				}			
				//die;
				Alert("Save succesful","success");
				redirect("purchases.php?id=".$file_id);
				

			}
			else{
				
				$con->myQuery("UPDATE suppliers SET name=:name,description=:description, contact_number=:contact_number,address=:address, email=:email WHERE supplier_id=:supplier_id",$inputs);
				
				Alert("Update successful","success");
				redirect("purchases.php?id=".$inputs['sales_master_id']);
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