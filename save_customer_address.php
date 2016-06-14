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
		$errors="";

		if($errors!=""){
			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['customer_id'])){
				redirect("customers.php");
			}
			else{
				redirect("customers.php");
			}
			die;
		}
		else{
				
			//IF id exists update ELSE insert
			// var_dump($_GET['ca']);
			// die;
			if(empty($_GET['ca'])){
				//Insert
				$cid = $inputs['customer_id'];
				// unset($inputs['customer_id']);
				// //unset($inputs['prod_id']);
				// var_dump($errors);
				// var_dump($inputs['opp_con']);
				// var_dump($inputs);
				// die;
				$con->myQuery("INSERT INTO customer_address(customer_id,label_address,address) VALUES(:customer_id,:lblAddress,:address)",$inputs);
								
				Alert("Save successful","success");
				redirect("customer_address.php"."?id={$cid}");

				
			}
			else{
				//Update
				
				$cid = $inputs['customer_id'];
				unset($inputs['customer_id']);
				$inputs['customer_add_id']=$_GET['ca'];
				// var_dump($inputs);
				// die;
				$con->myQuery("UPDATE customer_address SET
				 	address=:address,label_address=:lblAddress
				 	WHERE customer_add_id=:customer_add_id
				 	",$inputs);
				Alert("Update successful","success");
				redirect("customer_address.php"."?id={$cid}");
			}

			
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>