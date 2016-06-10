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

		if (empty($inputs['is_main'])) 
		{
			//echo $inputs['is_main'];
			$inputs['is_main']=0;
		}

		//die();


		if(empty($inputs['product_id']))
		{
			Modal("Invalid Record Selected");
			redirect("products.php");
		}

		$errors="";
		$tab=2;
		
		$prod_id=$inputs['product_id'];

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
					unit_cost,
					is_main
					) VALUES(
					:product_id,
					:supplier,
					:unit_cost,
					:is_main
					)",$inputs);
				//insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Added ({$skill_name}) training to ({$emp['last_name']}, {$emp['first_name']} {$emp['middle_name']}).");
			}
			else{
				//Update
				unset($inputs['product_id']);
				//var_dump($inputs);
				//die();	

				$con->myQuery("UPDATE supplier_products SET
				 	unit_cost=:unit_cost, is_main=:is_main
				 	WHERE supplier_product_id=:id
				 	",$inputs);
			}
			
			Alert("Save succesful","success");
			redirect("frm_products.php"."?id={$prod_id}&tab={$tab}");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>