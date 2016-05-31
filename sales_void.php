<?php
	require_once 'support/config.php';
	
	// if(!isLoggedIn()){
	// 	toLogin();
	// 	die();
	// }


	// if(!empty($_POST)){
		//Validate form inputs
		// $inputs=$_POST;
		
				$sm['sales_master_id']=$_GET['id'];
				// var_dump($sm['sales_master_id']);
				// die;

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
                    $total=$available+$qty;
                    $product_id=htmlspecialchars($row['product_id']);
                    // var_dump($product_id);
                    // var_dump($total);
                    $con->myQuery("UPDATE products SET current_quantity='$total' WHERE product_id=$product_id",$inputs);
               
                endforeach;
                // die;
				$is_void=1;
				$con->myQuery("UPDATE sales_master SET is_void='$is_void' WHERE sales_master_id=:sales_master_id",$sm);

				Alert("SO".$sm['sales_master_id']." was successfully voided.","success");
			
				redirect("sales.php");
		
	// }
	// else{
	// 	redirect('index.php');
	// 	die();
	// }
	// redirect('index.php');
?>