<?php
	require_once("support/config.php");
	 if(!isLoggedIn())
	 {
	 	toLogin();
	 	die();
	 }

     if(!AllowUser(array(1)))
     {
         redirect("index.php");
     }

	if(!empty($_POST))
	{
		//Validate form inputs
		$inputs=$_POST;

		if(empty($inputs['product_id']))
		{
			Modal("Invalid Record Selected");
			redirect("products.php");
		}

		$errors="";
		$tab=2;
		
		if($errors!="")
		{
			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id']))
			{
				redirect("frm_products.php"."?id={$inputs['product_id']}&tab={$tab}");
			}else
			{
				redirect("frm_products.php"."?id={$inputs['product_id']}&tab={$tab}&ee_id={$inputs['id']}");
			}
			die;
		}
		else{
			
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);

				$con->myQuery("INSERT INTO supplier_products(
					product_id,
					supplier_id,
					unit_cost
					) VALUES(
					:product_id,
					:supplier,
					:unit_cost
					)",$inputs);

				//insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Added ({$skill_name}) training to ({$emp['last_name']}, {$emp['first_name']} {$emp['middle_name']}).");
			}
			else{
				//Update
				
				// $con->myQuery("UPDATE employees_education SET
				// 	employee_id=:employee_id,
				// 	training_id=:training_id,
				// 	WHERE id=:id
				// 	",$inputs);
			}
			
			Alert("Save succesful","success");
			redirect("frm_products.php"."?id={$inputs['product_id']}&tab={$tab}");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>