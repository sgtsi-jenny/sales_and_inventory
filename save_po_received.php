<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}

	if(!AllowUser(array(1,2))){
		redirect("index.php");
	}
	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;
		// var_dump($inputs['id']);
		// var_dump($inputs['p_id']);
		// die;
		$inputs=array_map("trim", $inputs);
		$errors="";
		if (empty($inputs['qtyReceived'])){
			$errors.="Enter Quantity Received. <br/>";
		}
		if (empty($inputs['RefNo'])){
			$errors.="Enter Reference Number. <br/>";
		}
		
		
				//die();
				
 //var_dump($pos);
 //die;

		if($errors!=""){
			$_SESSION[WEBAPP]['frm_inputs']=$inputs;
			// $_SESSION[WEBAPP]['frm_inputs']['asset_model_id']=$inputs['model_id'];
			// unset($_SESSION[WEBAPP]['frm_inputs']['model_id']);

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['po_master_id'])){
				redirect("purchase_order_details.php");
			}
			else{
				redirect("purchase_order_details.php?id=".urlencode($inputs['po_master_id']));
			}
			die;
		}
		else{
			//var_dump($inputs['id']);
			//die();
			//IF id exists update ELSE insert
			if(!empty($inputs['id'])){
				//Insert
				//unset($inputs['id']);
				// echo "INSERT INTO asset_maintenances(asset_id,asset_maintenance_type_id,title,start_date,completion_date,cost,notes) VALUES('{$inputs['asset_id']}',{$inputs['asset_maintenance_type_id']},{$inputs['title']},{$inputs['start_date']},{$inputs['completion_date']},{$inputs['cost']},{$inputs['notes']})";
				$date = date_create($inputs['dob']);
				$inputs['dateReceived']= date_format($date, 'Ymd');	
				$date_paid = $inputs['dateReceived'];
				$po_master_id = $inputs['id'];	
				$product_id = $inputs['p_id'];
				$qtyReceived = $inputs['qtyReceived'];
				$RefNo = $inputs['RefNo'];
				$remarks = $inputs['remarks'];
				//var_dump($inputs);
				//die();
				
				$con->myQuery("INSERT INTO po_received(po_master_id,date_received,qty_received,product_id,reference_number,remarks) VALUES('$po_master_id','$date_paid','$qtyReceived','$product_id','$RefNo','$remarks')",$inputs);
								

				$getCurrentQty=$con->myQuery("SELECT product_id, current_quantity FROM products WHERE product_id='$product_id'")->fetch(PDO::FETCH_ASSOC);

				$currentqty = $getCurrentQty['current_quantity'];
				$newqty = $currentqty + $qtyReceived;
			
			
				$con->myQuery("UPDATE products SET current_quantity='$newqty' WHERE product_id='$product_id'");

				$pos=$con->myQuery("SELECT
	                        por.po_master_id,
	                        por.qty_received,
	                        poo.qty_ordered
	                        FROM
	                        (SELECT
	                            po_master.po_master_id,
	                            po_details.product_id,
	                            sum(po_received.qty_received)as 'qty_received'
	                            FROM
	                            po_master
	                            INNER JOIN po_details ON po_master.po_master_id = po_details.po_master_id
	                            INNER JOIN po_received ON po_details.po_master_id = po_received.po_master_id AND po_details.product_id = po_received.product_id
	                            GROUP BY
	                            po_master.po_master_id) as por 
	                        LEFT OUTER JOIN (SELECT
	                                po_master.po_master_id,
	                                Sum(po_details.qty_ordered) as 'qty_ordered'
	                                FROM
	                                po_master
	                                INNER JOIN po_details ON po_master.po_master_id = po_details.po_master_id
	                                GROUP BY po_master.po_master_id) as poo on poo.po_master_id = por.po_master_id
	                                WHERE por.po_master_id = ?",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);

	    
				$qty_received = $pos['qty_received'];
				$qty_ordered = $pos['qty_ordered'];
				$date_modified = date('Ymd');

				if ($qty_received == "0"){
					$con->myQuery("UPDATE po_master SET po_status_id='1', date_modified ='$date_modified'  WHERE po_master_id='$po_master_id'");
				}
				elseif ($qty_received < $qty_ordered) {
					$con->myQuery("UPDATE po_master SET po_status_id='2', date_modified ='$date_modified' WHERE po_master_id='$po_master_id'");
				}	
				elseif ($qty_received == $qty_ordered) {
					$con->myQuery("UPDATE po_master SET po_status_id='3', date_modified ='$date_modified' WHERE po_master_id='$po_master_id'");
				}



			}
			else{
				//Update
			
				//$con->myQuery("UPDATE measurements SET abv=:abvr,name=:measname WHERE measurement_id=:id",$inputs);
			}

			Alert("Save successful","success");
			redirect("purchase_order_details.php?id={$inputs['id']}");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>