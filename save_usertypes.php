<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    
	if(!AllowUser(array(1,5))){
		redirect("index.php");
	}

	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;

		$errors="";
		
		

		if($errors!=""){

			Alert("Please fill in the following fields: <br/>".$errors,"danger");
			if(empty($inputs['id'])){
				redirect("settings_users.php");
			}
			else{
				redirect("settings_users.php?id=".urlencode($inputs['id']));
			}
			die;
		}
		else{
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				$inputs=$_POST;
				
				//$inputs['name']=$_POST['name'];
				unset($inputs['id']);
				//$userid=$_SESSION[WEBAPP]['user']['id'];
				
				$con->myQuery("INSERT INTO user_types(name) VALUES (:name)", $inputs);	
				var_dump($inputs['name']);
				//
				
				Alert("Save succesful","success");
				

			}
			else{
				//Update
				//date_default_timezone_set('Asia/Manila');
				//$now = new DateTime();
				//$inputs['date_modified']=$now->format('Y-m-d H:i:s a');
				$inputs=$_POST;
				//$userid=$_SESSION[WEBAPP]['user']['id'];
				
				$con->myQuery("UPDATE user_types SET name=:name WHERE id=:id",$inputs);
				
				Alert("Update successful","success");
				}
			
			redirect("settings_users.php");
		}
		die();
		
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>