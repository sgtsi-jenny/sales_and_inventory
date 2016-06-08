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
		$inputs=array_map("trim", $inputs);
		$errors="";
		if (empty($inputs['custName'])){
			$errors.="Enter Customer Name. <br/>";
		}
		if (empty($inputs['tin'])){
			$errors.="Enter TIN. <br/>";
		}
		if (empty($inputs['description'])){
			$errors.="Enter Description. <br/>";
		}
		if (empty($inputs['fax'])){
			$errors.="Enter Fax. <br/>";
		}
		if (empty($inputs['telephoneNumber'])){
			$errors.="Enter Telephone Number. <br/>";
		}
		if (empty($inputs['mobileNumber'])){
			$errors.="Enter Mobile Number. <br/>";
		}
		if (empty($inputs['dob'])){
			$errors.="Enter Birth Date. <br/>";
		}
		if (empty($inputs['website'])){
			$errors.="Enter Website. <br/>";
		}
		if (empty($inputs['email'])){
			$errors.="Enter Email Address. <br/>";
		}

		
// var_dump($inputs);
// die;

		if($errors!=""){
			$_SESSION[WEBAPP]['frm_inputs']=$inputs;
			// $_SESSION[WEBAPP]['frm_inputs']['asset_model_id']=$inputs['model_id'];
			// unset($_SESSION[WEBAPP]['frm_inputs']['model_id']);

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['customer_id'])){
				redirect("frm_customer.php");
			}
			else{
				redirect("frm_customer.php?id=".urlencode($inputs['customer_id']));
			}
			die;
		}
		else{
			//var_dump($inputs['id']);
			//die();
			//IF id exists update ELSE insert
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
					email) 
					VALUES(
					:custName,
					:tin,
					:description,
					:fax,
					:telephoneNumber,
					:mobileNumber,
					:dob,
					:website,
					:email)",$inputs);

					Alert("Save succesful","success");
				    redirect("customers.php");
			}
			else{
				//Update
				//var_dump($inputs);
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
					email=:email
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