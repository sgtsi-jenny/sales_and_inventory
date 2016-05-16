<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

	$organization="";
  //if(!empty($_GET['id'])){
        //$organization=$con->myQuery("SELECT id,org_name,reg_name,trade_name,tin_num,tel_num,phone_num,email,address,industry,rating,org_type,annual_revenue,assigned_to,description,contact_id FROM organizations WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);

        //if(empty($organization)){
            //Alert("Invalid asset selected.");
            //Modal("Invalid Organization Selected");
            //redirect("organizations.php");
            //die();
        //}
        //var_dump($organization);
        //die;
    //}
    
if(!empty($_GET['id'])){
        $organization=$con->myQuery("SELECT id,user_type_id,first_name,middle_name,last_name,username,password,email,contact_no,security_question, security_answer from users WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($organization)){
            //Alert("Invalid consumables selected.");
            Modal("Invalid user selected");
            redirect("users.php");
            die();
        }
    }
    //$org_industries=$con->myQuery("SELECT id,name FROM org_industry")->fetchAll(PDO::FETCH_ASSOC);
    //$org_ratings=$con->myQuery("SELECT id,name FROM org_ratings")->fetchAll(PDO::FETCH_ASSOC);
    //$org_types=$con->myQuery("SELECT id,name FROM org_types")->fetchAll(PDO::FETCH_ASSOC);
    //$contact=$con->myQuery("SELECT id,CONCAT(fname,' ',lname) as name from contacts")->fetchAll(PDO::FETCH_ASSOC);
    //$user=$con->myQuery("SELECT id, CONCAT(last_name,' ',first_name,' ',middle_name) as name FROM users")->fetchAll(PDO::FETCH_ASSOC);
    if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
        if(!empty($organization)){
            $old_org=$organization;
        }
        $organization=$_SESSION[WEBAPP]['frm_inputs'];
        if(!empty($old_org)){
            $organization['id']=$old_org['id'];
        }
    }

    $department=$con->myQuery("SELECT id,name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $location=$con->myQuery("SELECT id,name FROM locations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    $user_type=$con->myQuery("SELECT id,name FROM user_types WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
	makeHead("User Form");


?>
<script type="application/javascript">

  function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }

</script>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <!-- <h1>
            Create New User
          </h1> -->
            <?php
                if(!empty($_GET['id'])){
            ?>
                <h1>Update User</h1>
            <?php
            }
                else{                    
            ?>
            <h1>Create New User</h1>                
            <?php
                }
            ?>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">

            <div class='col-md-10 col-md-offset-1'>
				<?php
					Alert();
				?>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                	<div class='col-sm-12 col-md-8 col-md-offset-2'>
                        <form class='form-horizontal' method='POST' action='create_users.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($organization)?$organization['id']:""?>'>
                          
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> User Type*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <!--<select class='form-control' required name='user_type_id' placeholder="Select User Type" <?php echo!(empty($organization))?"data-selected='".$organization['user_type_id']."'":NULL ?>>
                                            <?php
                                            echo makeOptions($user_type);
                                            ?>
                                        </select>-->
                                        <select class='form-control' name='user_type_id' data-placeholder="Select User Type" <?php echo!(empty($organization))?"data-selected='".$organization['user_type_id']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($user_type,'Select User Type',NULL,'',!(empty($organization))?$organization['user_type_id']:NULL)
                                                    ?>
                                                </select>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> First name* </label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type="text" class="form-control" name="first_name" placeholder="Enter First Name" value="<?php echo !empty($organization)?$organization["first_name"]:"" ?>" required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Middle name*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type="text" class="form-control" name="middle_name" placeholder="Enter Middle name" value="<?php echo !empty($organization)?$organization["middle_name"]:"" ?>" required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Last name*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type="text" class="form-control" name="last_name" placeholder="Enter Last name" value="<?php echo !empty($organization)?$organization["last_name"]:"" ?>" required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Username*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='username' placeholder='Enter Username' value='<?php echo !empty($organization)?$organization['username']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Password*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='password' class='form-control' name='password' placeholder='Enter Password' value='<?php echo !empty($organization)?htmlspecialchars(decryptIt($organization['password'])):''; ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Email Address*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='email' placeholder='Enter Email Address' value='<?php echo !empty($organization)?$organization['email']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Contact Number</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='contact_no' placeholder='Enter Contact Number' value='<?php echo !empty($organization)?$organization['contact_no']:"" ?>'  onkeypress="return isNumberKey(event)">
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Security Question</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='security_question' placeholder='Enter your security question' value='<?php echo !empty($organization)?$organization['security_question']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Answer</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='security_answer' placeholder='Enter you security answer' value='<?php echo !empty($organization)?$organization['security_answer']:"" ?>' required>
                                    </div>
                                </div>

                                
                                

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='users.php' class='btn btn-default'>Cancel</a>
                                        <button type='submit' class='btn btn-brand'> <span class='fa fa-check'></span> Save</button>
                                    </div>
                                    
                                </div>                        
                        </form>
                      </div>
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable();
      });
</script>
<?php
if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
    // $asset=$_SESSION[WEBAPP]['frm_inputs'];
    // var_dump($asset);
    unset($_SESSION[WEBAPP]['frm_inputs']);
}
?>
<?php
	makeFoot();
?>