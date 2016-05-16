<?php
	require_once("support/config.php");

  // if(isLoggedIn()){
  //   redirect("test_index.php");
  //   die();
  // }

	makeHead("Login");
?>
    <div class="login-box">
      <div class="login-logo">
        <img src="" class='img-responsive'>
      </div><!-- /.login-logo -->
      <div class='row'>
        <div class='panel panel-primary'>
          <div class='panel-heading text-center'>
            <h4><b>Customer Relationship Management</b></h4>
          </div>
        <div class="login-box-body">
        <?php
          Alert();
        ?>
        <div align="center"> <img  class='img-responsive' align="center" src="uploads/Protect.png" width="128" height="128" alt="Closed Padlock" title="Locked out!" /><br />       
			<p class="login-box-msg">
			Your account is now deactivated and can only be activate by the site Administrator!
			</p>
            <div class="col-xs-12" align="center">
              <a href="frmlogin.php" align="center">Back to Login Page</a>
            </div>
            </br>			
      </div>
      </div>
      </div>
    </div><!-- /.login-box -->

<?php
  Modal();
	makeFoot();
?>




