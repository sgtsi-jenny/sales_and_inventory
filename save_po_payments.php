<?php
	require_once("support/config.php");
	// if(!isLoggedIn()){
	// 	toLogin();
	// 	die();
	// }

    // if(!AllowUser(array(1))){
 //        redirect("index.php");
 //    }

                                                       

		if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;

		$inputs=array_map("trim", $inputs);
		$errors="";
		if (empty($inputs['amount'])){
			$errors.="Enter Amount. <br/>";
		}
		if (empty($inputs['dp'])){
			$errors.="Enter Date Paid. <br/>";
		}
		if (empty($inputs['remarks'])){
			$errors.="Enter Remarks. <br/>";
		}

       // $required_fieds=array(
		//	"c_id"=>"Select Contact Name. <br/>"
		//	);
		//$errors="";

		// foreach ($required_fieds as $key => $value) {
		// 	if(empty($inputs[$key])){
		// 		$errors.=$value;
		// 	}else{
		// 		#CUSTOM VALIDATION
		// 	}
		// }
		
		//die;
		

		if($errors!=""){
			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("purchases.php");
			}
			else{
				redirect("purchases.php");
			}
			die;
		}
		else{
				
			//IF id exists update ELSE insert
			//if(empty($inputs['opp_con'])){
				//Insert
				//unset($inputs['opp_con']);
				// //unset($inputs['prod_id']);
				// var_dump($errors);
				// var_dump($inputs['opp_con']);
				
				$po_master_id = $inputs['id'];	
				$amount = $inputs['amount'];
				$remarks = $inputs['remarks'];
				$date = date_create($inputs['dp']);
				$inputs['dp']= date_format($date, 'Ymd');	
				$date_paid = $inputs['dp'];
				// var_dump($inputs['dp']);
				// die;

				$con->myQuery("INSERT INTO po_payments(po_master_id,amount,date_paid,remarks) VALUES('$po_master_id','$amount','$date_paid','$remarks')",$inputs);

				$getAmount=$con->myQuery("SELECT
									po_master.po_master_id,
									sum(po_payments.amount) as 'pay_amount',
									po_master.total_amount as 'po_amount'
									FROM
									po_master
									INNER JOIN po_payments ON po_master.po_master_id = po_payments.po_master_id
									WHERE po_master.po_master_id = ?
									GROUP BY po_master.po_master_id",array($inputs['id']))->fetch(PDO::FETCH_ASSOC);

				$pay_amount = $getAmount['pay_amount'];
				$po_amount = $getAmount['po_amount'];
				$date_modified = date('Ymd');

				if ($pay_amount < $po_amount){
					$con->myQuery("UPDATE po_master SET payment_status_id='1', date_modified ='$date_modified' WHERE po_master_id='$po_master_id'");
				}
				elseif ($pay_amount == $po_amount) {
					$con->myQuery("UPDATE po_master SET payment_status_id='2', date_modified ='$date_modified' WHERE po_master_id='$po_master_id'");
				}

								
				Alert("Save successful","success");
				redirect("po_payments.php"."?id={$inputs['id']}");

				
			//}
			//else{
				//Update
				
				// $con->myQuery("UPDATE opp_products SET prod_name=:prod_name,prod_price=:prod_price,commission_rate=:commission_rate WHERE id=:prod_id",$inputs);
				// Alert("Update successful","success");
			//}

			
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>