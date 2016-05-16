<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
if(!AllowUser(array(1))){
        redirect("index.php");
    }
	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;
		$inputs=array_map('trim', $inputs);
		$inputs['contact_no']=str_replace("_", "", $inputs['contact_no']);
		$pwd=$inputs['password'];
		
		$errors="";


		if (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $pwd)){
		    //echo "Your password is strong.";
		    //die;
		} else {
		    	$errors.="Password should have at least:<br/>";
				$errors.="1 Integer<br>";
				$errors.="1 Character<br>";
				$errors.="1 Uppercase character<br>";
				$errors.="1 Special Character<br>";
		}
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
     		$errors.="Enter a valid email address. <br/>";
		}		

		if($errors!=""){

			$_SESSION[WEBAPP]['frm_inputs']=$inputs;

			Alert($errors,"danger");
			if(empty($inputs['id'])){
				redirect("frm_users.php");
			}
			else{
				redirect("frm_users.php?id=".urlencode($inputs['id']));
			}
			//die;
			
		}
		// var_dump($inputs);
		// 		die;
		else{
			//IF id exists update ELSE insert
			$inputs['password']=encryptIt($inputs['password']);
			if(empty($inputs['id'])){
				//Insert
				$inputs=$_POST;
				unset($inputs['id']);
				$inputs['password']=encryptIt($inputs['password']);
				$uname=$inputs['username'];
				var_dump($inputs);
				die;
				$results=$con->myQuery("if EXISTS(select username from users where username='$uname') THEN
				select false as result;
				else
				INSERT INTO users(user_type_id,first_name,middle_name,last_name,username, password,email,contact_no, security_question, security_answer) VALUES(:user_type_id,:first_name,:middle_name,:last_name,:username,:password, :email,:contact_no,:security_question,:security_answer);
				select true as result;
				end IF;",$inputs)->fetch(PDO::FETCH_ASSOC);
				$resultString=$results['result'];

				if($resultString==1){
				Alert("Save succesful","success");
				//die($resultString);
				}
				else
				{
					$_SESSION[WEBAPP]['frm_inputs']=$inputs;
					Alert("Username already exists.","danger");
					if(empty($inputs['id'])){
						redirect("frm_users.php");
					}
					else{
						redirect("frm_users.php?id=".urlencode($inputs['id']));
					}
					die;
				}
			}
			else{				
				//Update
				// $inputs['password']=encryptIt($inputs['password']);
				// var_dump($inputs['password']);
				// var_dump(decryptIt($inputs['password']));
				// 	die;
				$pwd=$inputs['password'];		
				$errors="";


				if (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $pwd)){
				    //echo "Your password is strong.";
				    //die;
				} else {
				    	$errors.="Password should have at least:<br/>";
						$errors.="1 Integer<br>";
						$errors.="1 Character<br>";
						$errors.="1 Uppercase character<br>";
						$errors.="1 Special Character<br>";
				}
				if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		     		$errors.="Enter a valid email address. <br/>";
				}	

				if($errors!=""){
					$_SESSION[WEBAPP]['frm_inputs']=$inputs;
					Alert($errors,"danger");
					if(empty($inputs['id'])){
						redirect("frm_users.php");
					}
					else{
						redirect("frm_users.php?id=".urlencode($inputs['id']));
					}
				}
				//die;
				
				$con->myQuery("UPDATE users SET user_type_id=:user_type_id, first_name=:first_name, middle_name=:middle_name,last_name=:last_name,username=:username,password=:password,email=:email,contact_no=:contact_no,security_question=:security_question, security_answer=:security_answer WHERE id=:id",$inputs);
				//die();
				Alert("Update successful","success");

			}

			redirect("users.php");
		}
		die;
	}
	else{
		redirect('index.php');
		die();
	}
	redirect('index.php');
?>