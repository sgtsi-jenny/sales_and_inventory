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
		/**if (empty($inputs['categoryid'])){
			$errors.="Enter Category ID. <br/>";
		}**/
		$cname=$inputs['custName'];
		$results=$con->myQuery("select name from categories where name='$cname'")->fetch(PDO::FETCH_ASSOC);
		if($cname==$results['catname']){
			$errors.="Category Name already exists. <br/>";
		}
		if (empty($inputs['catname'])){
			$errors.="Enter Category Name. <br/>";
		}

		if($errors!=""){
			$_SESSION[WEBAPP]['frm_inputs']=$inputs;
			// $_SESSION[WEBAPP]['frm_inputs']['asset_model_id']=$inputs['model_id'];
			// unset($_SESSION[WEBAPP]['frm_inputs']['model_id']);

			Alert("You have the following errors: <br/>".$errors,"danger");
			if(empty($inputs['category_id'])){
				redirect("frm_categories.php");
			}
			else{
				redirect("frm_categories.php?id=".urlencode($inputs['category_id']));
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
				//var_dump($inputs);
				//die();
				$con->myQuery("INSERT INTO categories(name) VALUES(:catname)",$inputs);
				
				//echo $con;
			}
			else{
				//Update
				$con->myQuery("UPDATE categories SET name=:catname WHERE category_id=:id",$inputs);
			}

			Alert("Save succesful","success");
			redirect("categories.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>