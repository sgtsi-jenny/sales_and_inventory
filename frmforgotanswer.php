<?php
	require_once("support/config.php");

  if(isLoggedIn()){
    redirect("index.php");
    die();
  }

  

  $data="";
    if(!empty($_POST['username']) && !empty($_POST['sec_key'])){
      
      //die();
        //if(($_POST['security_answer1']=$_POST['security_answer']) && (!empty($_POST['security_answer1']))){
           //redirect("frmforgotchange.php?");
          //}

          $data=$con->myQuery("SELECT security_question, security_answer, id FROM users WHERE username=?",array($_POST['username']))->fetch(PDO::FETCH_ASSOC);
        //die();
          if(empty($data)){
            //Alert("Invalid asset selected.");
            Alert("Username not found");
            redirect("frmforgot.php");
            die();
          
        }
    }
    elseif(!empty($_GET['username'])){
        //if(($_POST['security_answer1']=$_POST['security_answer']) && (!empty($_POST['security_answer1']))){
           //redirect("frmforgotchange.php?");
          //}

          $data=$con->myQuery("SELECT security_question, security_answer, id FROM users WHERE username=?",array($_GET['username']))->fetch(PDO::FETCH_ASSOC);
        //die();
          if(empty($data)){
            //Alert("Invalid asset selected.");
            Alert("Username not found", "danger");
            redirect("frmforgot.php");
            die();
          
        }
    }
    else{
      redirect("frmlogin.php");
    }

    //$test=($_POST['username'])?$_POST['username']:$_GET['username'];
    //var_dump($test);
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
            <h4><b>Forgot password</b></h4>
          </div>
        <div class="login-box-body">
        <?php
          Alert();
        ?>
        <p class="login-box-msg">Answer the Security Question below</p>
        <form action="frmforgotchange.php" method="post">
           <input type='hidden' name='security_answer' value='<?php echo !empty($data)?$data['security_answer']:""?>'>
           <input type='hidden' name='username' value='<?php echo !empty($_POST['username'])?$_POST['username']:$_GET['username']?>'>
           <input type='hidden' name='sec_key' value='pass'>
          <div class="form-group has-feedback">
            <!--<span class="glyphicon glyphicon-envelope form-control-feedback"></span>-->
            <input type="text" class="form-control" placeholder="Username" name='security_question' value="<?php echo !empty($data)?$data['security_question']:"" ?>" readonly>
          </div>
          <div class="form-group has-feedback">
            <!--<span class="glyphicon glyphicon-lock form-control-feedback"></span>-->
            <input type="password" class="form-control" placeholder="Your answer here" name='security_answer1' required>
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