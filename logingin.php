<?php
	require_once 'support/config.php';
	if(!empty($_POST)){

		$user=$con->myQuery("SELECT users.user_id,users.user_type_id as user_type,users.first_name,users.middle_name,users.last_name,users.is_login,users.is_active FROM users WHERE username=? AND password=? AND users.is_deleted=0 LIMIT 1",array($_POST['username'],encryptIt($_POST['password'])))->fetch(PDO::FETCH_ASSOC);

		//$user=$con->myQuery("SELECT first_name,middle_name,last_name,id,location_id,user_type_id as user_type,location_id,is_login,is_active  FROM users WHERE username=? AND password=? AND is_deleted=0",array($_POST['username'],$_POST['password']))->fetch(PDO::FETCH_ASSOC);

		if(!empty($_SESSION[WEBAPP]['attempt_no']) && $_SESSION[WEBAPP]['attempt_no']>1){
			Alert("You've failed too many times.","danger");
			UNSET($_SESSION[WEBAPP]['attempt_no']);
			$con->myQuery("UPDATE users SET is_active=0 WHERE username=?",array($_POST['username']));
			//redirect("frmlogin.php");
			include("locked.php");
			die;
		}

		if(empty($user)){
			Alert("Invalid Username/Password","danger");
			redirect('frmlogin.php');
			if(!empty($_SESSION[WEBAPP]['attempt_no'])){
				// setcookie("attempt_no",$_SESSION[WEBAPP]['attempt_no']+1,time()+(3600));
				$_SESSION[WEBAPP]['attempt_no']+=1;
			}
			else{
				$_SESSION[WEBAPP]['attempt_no']=1;
			}
		}
		else{
			
			if($user['is_login']==0){
				if($user['is_active']==1){
					UNSET($_SESSION[WEBAPP]['attempt_no']);
					$_SESSION[WEBAPP]['user']=$user;
					//refresh_activity($_SESSION[WEBAPP]['user']['id']);
					$is_login=1;
					$con->myQuery("UPDATE users SET is_login='$is_login' where username=?",array($_POST['username']));
					refresh_activity($_SESSION[WEBAPP]['user']['id']);
					redirect("index.php");
					die;
				}
				else{
					Alert("This account is currently deactivated.","danger");
					redirect("frmlogin.php");
					die;
				}
				
			}
			else{
				Alert("This account is currently already logged in.","danger");
				redirect("frmlogin.php");
				die;
			}
		}
		die;
	}
	else{
		redirect('frmlogin.php');
		die();
	}
	redirect('frmlogin.php');
?>