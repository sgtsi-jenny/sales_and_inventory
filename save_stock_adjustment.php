<?php
	require_once 'support/config.php';
	if(!isLoggedIn())
	{
		toLogin();
		die();
	}

	if(!empty($_POST)){
	//Validate form inputs
		$inputs=$_POST;
		$errors="";
		 	
		// if (empty($inputs['']))
		// {
		// 	$errors.="No items in the list. <br/>";
		// }
		if($errors!="")
		{
			Alert($errors,"danger");
			if(empty($inputs['select_id[]']))
			{
				redirect("stock_adjustments.php");
			}else
			{
				redirect("stock_adjustments.php?id=".urlencode($inputs['select_id[]']));
			}
			die;
		}else
		{
			$resu=$inputs['stock_adjmaster_id'];
			//IF id exists update ELSE insert
				
			if(empty($inputs['stock_adjmaster_id']))
			{
				//Insert				
				unset($inputs['stock_adjmaster_id']);
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$date_adjusted=date_format($now, 'Ymd');
				//var_dump($inputs);
				//die;
				$total_cost =0;
				foreach($inputs['unit_cost'] as $key=>$value)
				{
				   
				
					// NOTE: You don't really have to use floatval() here, it's just to prove that it's a legitimate float value.
					$number = floatval(str_replace(',', '', $value));

					// At this point, $number is a "natural" float.
					
					$total_cost+= $number;
				}
			
				// $total_cost = 0;
				// foreach($inputs['total_price'] as $key=>$value)
				// {
				//    $total_cost+= $value;
				// }
				$adj_status_id = $inputs['adj_status_id'];
				$current_quantity = $inputs['current_quantity'];				
				$prod_name = $inputs['prod_name'];
				$notes = $inputs['notes'];
				$quantity_received = $inputs['quantity_received'];
				$unit_cost = $inputs['unit_cost'];
		


				// unset($inputs['customer_id']);
				unset($inputs['current_quantity']);
				unset($inputs['prod_name']);
				unset($inputs['notes']);
				unset($inputs['quantity_received']);
				unset($inputs['unit_cost']);
				unset($inputs['adj_status_id']);


				// var_dump($inputs);
				//  die;

				//echo $arr_count=count($inputs);
				//echo count($inputs['select_id']);
				//die();
				$field_count=count($inputs);
				$arr_count=count($inputs['select_id']);
				
				

				$con->myQuery("INSERT INTO stock_adj_master (adj_status_id, date_adjusted,total_cost,is_reverted,reverted_from) VALUES ('$adj_status_id','$date_adjusted','$total_cost', '0', '0')");

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
						'stock_adjmaster_id' => $file_id,
						'product_id' => $inputs['select_id'][$i],
						'quantity_received' => $quantity_received[$i]						
						);
			
					// die;
				
					$con->myQuery("INSERT INTO stock_adj_details (stock_adjmaster_id,product_id,quantity_received) 
						VALUES (:stock_adjmaster_id, :product_id , :quantity_received) ", $params);

						//update products
						//if damaged bagsak sya sa bad orders.

				}	

				if ($adj_status_id = '1' || $adj_status_id = '2' ){
					for ($i=0; $i < $arr_count; $i++) { 
						$params=array(
					
						'product_id' => $inputs['select_id'][$i],
						'quantity_received' => $quantity_received[$i]						
						

						);
						$con->myQuery("UPDATE products SET current_quantity = current_quantity + :quantity_received  WHERE product_id = :product_id ", $params)
						//select //
					}

				}elseif ($adj_status_id = '4' || $adj_status_id = '5' ){
					for ($i=0; $i < $arr_count; $i++) { 
						$params=array(
					
						'product_id' => $inputs['select_id'][$i],
						'quantity_received' => $quantity_received[$i]						
						

						);
						$con->myQuery("UPDATE products SET current_quantity = current_quantity - :quantity_received  WHERE product_id = :product_id ", $params)
						//select //
					}
				}elseif  ($adj_status_id = '3' ){
					for ($i=0; $i < $arr_count; $i++) { 
						$params=array(
					
						'product_id' => $inputs['select_id'][$i],
						'quantity_received' => $quantity_received[$i]
						);
						$con->myQuery("INSERT INTO badorders (product_id,product_id,quantity) 
						VALUES (:product_id , :quantity_received) ", $params); )
						
						//select //
					}
				}
				// insert sa badorders and update products depende sa reason...
				//if reason
				//for loop

			
				Alert("Save successful","success");
				redirect("stock_adjustments.php?id=".$file_id);
			}
			else{
				
				
				//dito update
				// $con->myQuery("UPDATE suppliers SET name=:name,description=:description, contact_number=:contact_number,address=:address, email=:email WHERE supplier_id=:supplier_id",$inputs);
				
				// Alert("Update successful","success");
				// redirect("stock_adjustments.php?id=".$inputs['stock_adjmaster_id']);
				// }
			
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
