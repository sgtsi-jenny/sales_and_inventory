<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn())
	{
		toLogin();
		die();
	}

	if(!AllowUser(array(1,2)))
	{
		redirect("index.php");
	}
	if(!empty($_POST))
	{
		//Validate form inputs
		$inputs=$_POST;
		$inputs=array_map("trim", $inputs);
		$errors="";
		$cname=$inputs['custName'];
		$results=$con->myQuery("select customer_name from customers where customer_name='$cname'")->fetch(PDO::FETCH_ASSOC);
		if($cname==$results['customer_name']){
			$errors.="Customer Name already exists. <br/>";
		}
		// var_dump($cname);
		// die;
		if (empty($inputs['custName'])){
			$errors.="Enter Customer Name. <br/>";
		}
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
     		$errors.="Enter a valid email address. <br/>";
		}

		if($errors!=""){
			$_SESSION[WEBAPP]['frm_inputs']=$inputs;
			// $_SESSION[WEBAPP]['frm_inputs']['asset_model_id']=$inputs['model_id'];
			// unset($_SESSION[WEBAPP]['frm_inputs']['model_id']);

			Alert("Please try again, you have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['customer_id'])){
				redirect("frm_customers.php");
			}
			else{
				redirect("frm_customers.php?id=".urlencode($inputs['customer_id']));
			}
			die;
		}
		else{
			//var_dump($inputs['id']);
			//die();
			//IF id exists update ELSE insert

			if (empty($inputs['is_top'])) 
			{
				$inputs['is_top']=0;
			}

			$date = date_create($inputs['dob']);
			$inputs['dob']= date_format($date, 'Ymd');		
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				
				//unset($inputs['dob']);
				// echo "INSERT INTO asset_maintenances(asset_id,asset_maintenance_type_id,title,start_date,completion_date,cost,notes) VALUES('{$inputs['asset_id']}',{$inputs['asset_maintenance_type_id']},{$inputs['title']},{$inputs['start_date']},{$inputs['completion_date']},{$inputs['cost']},{$inputs['notes']})";
				$con->myQuery("INSERT INTO customers(
					customer_name,
					tin,
					description,
					fax,
					telephone_number,
					mobile_number,
					birth_date,
					website,
					email,
					is_top_company
					) VALUES(
					:custName,
					:tin,
					:description,
					:fax,
					:telephoneNumber,
					:mobileNumber,
					:dob,
					:website,
					:email,
					:is_top
					)",$inputs);

					Alert("Save succesful","success");
				    redirect("customers.php");
			}else
			{
				//Update
				//var_dump($inputs);
				//die;
				//echo $inputs['is_top'];
				//die;
				$con->myQuery("UPDATE customers SET 
					customer_name=:custName,
					tin=:tin,
					description=:description,
					fax=:fax,
					telephone_number=:telephoneNumber,
					mobile_number=:mobileNumber,
					birth_date=:dob,
					website=:website,
					email=:email,
					is_top_company=:is_top
					WHERE customer_id=:id",$inputs);

					Alert("Update succesful","success");
					redirect("customers.php");
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