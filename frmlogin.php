<?php
  require_once("support/config.php");

  if(isLoggedIn()){
    redirect("index.php");
    die();
    session_unset();
  }

  makeHead("Login");
?>
    <div class="login-box">
      <div class='row'>
        <div class='panel panel-primary'>
          <div class='panel-heading text-center'>
            <h4><b>Customer Relationship Management</b></h4>
          </div>
        <div class="login-box-body">
        <?php
          Alert();
        ?>
        <p class="login-box-msg">Login to your Account</p>
        <form action="logingin.php" method="post">
          <div class="form-group has-feedback">
            <span class="glyphicon glyphicon-user form-control-feedback" style='left:0px'></span>
            <input type="text" class="form-control" placeholder="Username" name='username' autofocus="" style="padding-left: 42.5px;padding-right: 0px">
          </div>
          <div class="form-group has-feedback">
            <span class="glyphicon glyphicon-lock form-control-feedback" style='left:0px'></span>
            <input type="password" class="form-control" placeholder="Password" name='password' autofocus="" style="padding-left: 42.5px;padding-right: 0px">
          </div>
          <div class="row">
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
            </div><!-- /.col -->
            <div class="col-xs-12" align="center">
              <a align="center" href="frmforgot.php">Forgot Password?</a>
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