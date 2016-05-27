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
		if (empty($inputs['abvr'])){
			$errors.="Enter Abbreviation. <br/>";
		}
		if (empty($inputs['measname'])){
			$errors.="Enter Name. <br/>";
		}

		
// var_dump($inputs);
// die;

		if($errors!=""){
			$_SESSION[WEBAPP]['frm_inputs']=$inputs;
			// $_SESSION[WEBAPP]['frm_inputs']['asset_model_id']=$inputs['model_id'];
			// unset($_SESSION[WEBAPP]['frm_inputs']['model_id']);

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['measurement_id'])){
				redirect("frm_measurement.php");
			}
			else{
				redirect("frm_measurement.php?id=".urlencode($inputs['measurement_id']));
			}
			die;
		}
		else{
			//var_dump($inputs['id']);
			//die();
			//IF id exists update ELSE insert
			if(empty($inputs['id'])){
				//Insert
				unset($inputs['id']);
				// echo "INSERT INTO asset_maintenances(asset_id,asset_maintenance_type_id,title,start_date,completion_date,cost,notes) VALUES('{$inputs['asset_id']}',{$inputs['asset_maintenance_type_id']},{$inputs['title']},{$inputs['start_date']},{$inputs['completion_date']},{$inputs['cost']},{$inputs['notes']})";
				$con->myQuery("INSERT INTO measurements(abv,name) VALUES(:abvr,:measname)",$inputs);
			}
			else{
				//Update
				$con->myQuery("UPDATE measurements SET abv=:abvr,name=:measname WHERE measurement_id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("measurements.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>