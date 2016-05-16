<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
    if(!empty($_GET['id'])){
        $account=$con->myQuery("SELECT name, id FROM user_types WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($account)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Account Selected");
            redirect("settings.php");
            die();
        }
    }

    $account_stat=$con->myQuery("SELECT id,name FROM account_statuses")->fetchAll(PDO::FETCH_ASSOC);
    $user=$con->myQuery("SELECT id, CONCAT(last_name,' ',first_name,' ',middle_name) as name FROM users")->fetchAll(PDO::FETCH_ASSOC);
                    						
	makeHead("User Types");
?>
<div id='wrapper'>
<?php
	 require_once 'template/navbar.php';
?>
</div>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">User Types</h1>
                </div>

                <!-- /.col-lg-12 -->;
            </div>
            <!-- /.row -->
            <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>    
                    <div class='row'>
                    	<div class='col-sm-12 col-md-8 col-md-offset-2'>
                    		<form class='form-horizontal' method='POST' enctype="multipart/form-data" action='save_usertypes.php'>
                                <input type='hidden' name='id' value='<?php echo !empty($account)?$account['id']:""?>'>
                    			
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'>User Type</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='name' placeholder='Type of User' value='<?php echo !empty($account)?$account['name']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <a href='settings.php' class='btn btn-default'>Cancel</a>
                                        <button type='submit' class='btn btn-success'> <span class='fa fa-check'></span> Save</button>
                                    </div>
                                    
                                </div>                    		
                    		</form>
                    	</div>
                    </div>

                </div>
            </div>
            <!-- /.row -->
</div>
<?php
Modal();
?>
<?php
	makeFoot();
?>