<?php
	require_once("support/config.php");
	 if(!isLoggedIn()){
	 	toLogin();
	 	die();
	 }

     if(!AllowUser(array(1))){
         redirect("index.php");
     }

	if(!empty($_POST))
	{
		//Validate form inputs
		$inputs=$_POST;

		//echo $inputs['type'];
		//die();

		$errors="";
		if (empty($inputs['product_code']))
		{
			$errors.="Enter Product Code. <br/>";
		}
		if (empty($inputs['product_name']))
		{
			$errors.="Enter Product Name. <br/>";
		}
		if (empty($inputs['description']))
		{
			$errors.="Enter Description. <br/>";
		}
		if (empty($inputs['category']))
		{
			$errors.="Select Category. <br/>";
		}

		if($errors!="")
		{
			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['id']))
			{
				die();
				redirect("frm_products.php");
			}else
			{
				die();
				redirect("frm_products.php?id=".urlencode($inputs['id']));
			}
			die;
		}else
		{
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);

				$con->myQuery("INSERT INTO products(
								product_code,
								product_name,
								description,
								category_id,
								selling_price,
								wholesale_price,
								current_quantity,
								measurement_id,
								minimum_quantity,
								maximum_quantity,
								barcode,
								product_type
								) VALUES(
								:product_code,
								:product_name,
								:description,
								:category,
								:selling_price,
								:wholesale_price,
								:current_quantity,
								:measurement,
								:min_quantity,
								:max_quantity,
								:barcode,
								:type
								)",$inputs);

				$id=$con->lastInsertId();
				//insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Created New Employee ({$inputs['first_name']} {$inputs['last_name']}).");
			}else
			{
				//var_dump($inputs);
				unset($inputs['current_quantity']);
				var_dump($inputs);
				//die();
				#Update
				$con->myQuery("UPDATE products SET
								product_code=:product_code,
								product_name=:product_name,
								description=:description,
								category_id=:category,
								selling_price=:selling_price,
								wholesale_price=:wholesale_price,
								measurement_id=:measurement,
								minimum_quantity=:min_quantity,
								maximum_quantity=:max_quantity,
								barcode=:barcode,
								product_type=:type
								WHERE product_id=:id
								",$inputs);
				//insertAuditLog($_SESSION[WEBAPP]['user']['last_name'].", ".$_SESSION[WEBAPP]['user']['first_name']." ".$_SESSION[WEBAPP]['user']['middle_name']," Modified Employee Personal Information ({$inputs['first_name']} {$inputs['last_name']}).");
				$id=$inputs['id'];
			}
			Alert("Save succesful","success");
			redirect("frm_products.php?id=".urlencode($id));
		}
		die;
	}
	else
	{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>