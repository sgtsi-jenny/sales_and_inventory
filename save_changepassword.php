<?php
	require_once 'support/config.php';
	
	if(isLoggedIn()){
    redirect("index.php");
    die();
  	}
    
	//if(!AllowUser(array(1,2))){
		//redirect("index.php");
	//}
	//$_POST['password1']=encryptIt($_POST['password1']);
  	var_dump($_POST['username']);
  	var_dump($_POST['password1']);
  	var_dump($_POST['password2']);
    	
	if(!empty($_POST)){
		//Validate form inputs
		$inputs=$_POST;

		$errors="";
		if(!($_POST['password1']==$_POST['password2'])){
			$errors.="Passwords doesn't match. <br/>";

		}
		$filter='/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}$/';
		if (!preg_match($filter, $_POST['password1'])){
				$errors.="Password should have at least:<br/>";
				$errors.="1 Integer<br>";
				$errors.="1 Character<br>";
				$errors.="1 Uppercase character<br>";
				$errors.="1 Special Character<br>";
		}

		if($errors!="")
		{
			
			Alert("Reminder: <br/>".$errors,"danger");
			if(empty($inputs['username'])){
				//var_dump($inputs['username']);
				//die();
				redirect("frmforgotchange.php?username=".urlencode($_POST['username'])."&sec_key=".urlencode($_POST['sec_key']));	
				die();
			}
			else{
				//die();
				redirect("frmforgotchange.php?username=".urlencode($_POST['username'])."&sec_key=".urlencode($_POST['sec_key']));
				var_dump($inputs['username']);
			var_dump($inputs['sec_key']);
			die();	
			}
			die;
		}
		else{
			
			$inputs=$_POST;
				//$userid=$_SESSION[WEBAPP]['user']['id'];
				$inputs['password1']=encryptIt($inputs['password1']);
				$password=$inputs['password1'];
				$username=$inputs['username'];
				//print_r(preg_split($filter,$_POST['password1']));
				//var_dump($password);
				//die();
				$con->myQuery("UPDATE users SET password='$password' WHERE username='$username'");
				
				Alert("Change password successful","success");
			redirect("frmlogin.php");
		}
		die();
		
	}
	else{
		redirect('frmlogin.php');
		die();
	}
	redirect('frmlogin.php');
?>