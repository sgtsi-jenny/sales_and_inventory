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
		//$inputs['qty']=$_GET['qty'];

		$sales_status_id=2;
				// var_dump($inputs['qty']);
				// var_dump($inputs);

            $sales=$con->myQuery("SELECT 
                sd.quantity,
                sd.product_id,
                prod.current_quantity AS available
                FROM sales_master sm
                INNER JOIN sales_details sd ON sm.sales_master_id=sd.sales_master_id
                INNER JOIN products prod ON prod.product_id=sd.product_id
                WHERE sm.sales_master_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);
                foreach ($sales as $row):
            
                    $qty=htmlspecialchars($row['quantity']);
                    $available=htmlspecialchars($row['available']);
                    $total=$available-$qty;
                    $product_id=htmlspecialchars($row['product_id']);
                    // var_dump($product_id);
                    // var_dump($total);
                    $con->myQuery("UPDATE products SET current_quantity='$total' WHERE product_id=$product_id",$inputs);
               
                endforeach;
				// die;
				date_default_timezone_set('Asia/Manila');
				$now = new DateTime();
				$inputs['sales_master_id']=$inputs['sales_master_id'];
				$con->myQuery("UPDATE sales_master SET sales_status_id='$sales_status_id',date_modified=:date_modified WHERE sales_master_id=:sales_master_id",$inputs);
				
				Alert("Your inventory has been allocated","success");
				redirect("sales_order_details.php?id=".$inputs['sales_master_id']);
				
	
		
?>