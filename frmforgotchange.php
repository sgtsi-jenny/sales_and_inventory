<?php
	require_once("support/config.php");

  if(isLoggedIn()){
    redirect("index.php");
    die();
  }

  
  
  $data="";
    if(!empty($_POST['username']) && !empty($_POST['sec_key'])){
      //var_dump($_POST['security_answer']);
      //var_dump($_POST['security_answer1']);
      //var_dump($_POST['username']);

      //var_dump($_POST['sec_key']);
      //die();
        if(($_POST['security_answer1']==$_POST['security_answer'])){
            //$data=$con->myQuery("SELECT username FROM users WHERE username=?",array($_POST['username']))->fetch(PDO::FETCH_ASSOC);
            //die();
        }
          else{
           
          Alert("Incorrect Answer", "success");
          redirect("frmforgotanswer.php?username=".urlencode($_POST['username'])."&sec_key=".urlencode($_POST['sec_key']));
          die;
          }

    }
    elseif(!empty($_GET['username']) && !empty($_GET['sec_key'])){
      //var_dump($_GET['username']);
    }
  
    // var_dump($_POST['username']);
    // var_dump($_GET['username']);
	makeHead("Login");
?>
    <div class="login-box">
      <!-- <div class="login-logo">
        <img src="" class='img-responsive'>
      </div> -->
      <!-- /.login-logo -->
      <div class='row'>
        <div class='panel panel-primary'>
          <div class='panel-heading text-center'>
            <h4><b>Change password</b></h4>
          </div>
        <div class="login-box-body">
        <?php
          Alert();
        ?>
        <p class="login-box-msg">Type in your desired New Password</p>
        <form action="save_changepassword.php" method="post">
           <!--<input type='hidden' name='security_answer' value='<?php echo !empty($data)?$data['security_answer']:""?>'>-->
           <input type='hidden' name='username' value='<?php echo !empty($_POST['username'])?$_POST['username']:$_GET['username']?>'>
           <input type='hidden' name='sec_key' value='pass'>
          <div class="form-group has-feedback">
            <!--<span class="glyphicon glyphicon-envelope form-control-feedback"></span>-->
            <input type="password" class="form-control" placeholder="your New Password" name='password1' required>
          </div>
          <div class="form-group has-feedback">
            <!--<span class="glyphicon glyphicon-lock form-control-feedback"></span>-->
            <input type="password" class="form-control" placeholder="Confirm your New Password" name='password2' required>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Next</button>
            </div><!-- /.col -->
            <div class="col-xs-12" align="center">
              <a href="frmlogin.php" align="center">Back to Login Page</a>
            </div>
            <!--<div class="col-xs-12" align="center">
              <a align="center">Forgot Password?</a>-->
            </div>
          </div>
        </form>

      </div>
      </div>
      </div>
    </div><!-- /.login-box -->


<?php
  Modal();
	makeFoot();
?>