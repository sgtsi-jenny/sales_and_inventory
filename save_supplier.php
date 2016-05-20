<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    
	if(!AllowUser(array(1,2,5))){
		redirect("index.php");
	}

	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;

		$errors="";
		
		// if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  //    		$errors.="Enter a valid email address. <br/>";
		// }
		

		if($errors!=""){

			Alert($errors,"danger");
			if(empty($inputs['supplier_id'])){
				redirect("frm_supplier.php");
			}
			else{
				redirect("frm_supplier.php?supplier_id=".urlencode($inputs['supplier_id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['supplier_id'])){
				//Insert
				$inputs=$_POST;
				unset($inputs['supplier_id']);
				//$userid=$_SESSION[WEBAPP]['user']['id'];
				// var_dump($inputs);
				// die;
				$con->myQuery("INSERT INTO suppliers(name,description, contact_number,address, email) VALUES (:name,:description, :contact_number,:address, :email)", $inputs);					
				
				Alert("Save succesful","success");
				

			}
			else{
				
				$con->myQuery("UPDATE suppliers SET name=:name,description=:description, contact_number=:contact_number,address=:address, email=:email WHERE supplier_id=:supplier_id",$inputs);
				
				Alert("Update successful","success");
				}
			
			redirect("suppliers.php");
		}
		die();
		
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>