<?php
	require_once("support/config.php");

  if(isLoggedIn()){
    redirect("index.php");
    die();
  }

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
        <p class="login-box-msg">Enter your Username below</p>
        <form action="frmforgotanswer.php" method="post">
          <div class="form-group has-feedback">
            <!--<span class="glyphicon glyphicon-envelope form-control-feedback"></span>-->
            <input type='hidden' name='sec_key' value='pass'>
            <input type="text" class="form-control" placeholder="Username" name='username' required>
          </div>
          <!--<div class="form-group has-feedback">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            <input type="password" class="form-control" placeholder="Password" name='password'>
          </div>-->
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