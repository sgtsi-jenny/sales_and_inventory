<?php
	require_once("support/config.php");
	if(!isLoggedIn()){
		toLogin();
		die();
	}

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

	$supplier="";
    
if(!empty($_GET['id'])){
        $supplier=$con->myQuery("SELECT supplier_id,name,description, contact_number,address, email from suppliers WHERE supplier_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($supplier)){
            //Alert("Invalid consumables selected.");
            Modal("Invalid supplier selected");
            redirect("suppliers.php");
            die();
        }
    }
    if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
        if(!empty($supplier)){
            $old_sup=$supplier;
        }
        $supplier=$_SESSION[WEBAPP]['frm_inputs'];
        if(!empty($old_sup)){
            $supplier['supplier_id']=$old_sup['supplier_id'];
        }
    }

    // $department=$con->myQuery("SELECT id,name FROM departments WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    // $location=$con->myQuery("SELECT id,name FROM locations WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
    // $user_type=$con->myQuery("SELECT id,name FROM user_types WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
	makeHead("User Form");


?>
<script type="application/javascript">

  function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if ( charCode > 31 && (charCode < 47 || charCode > 57))
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
                <h1 align="center" style="color:#24b798;">Update Supplier</h1>
            <?php
            }
                else{                    
            ?>
            <h1 align="center" style="color:#24b798;">Create New Supplier</h1>                
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
                        <form class='form-horizontal' method='POST' action='save_supplier.php'>
                                <input type='hidden' name='supplier_id' value='<?php echo !empty($supplier)?$supplier['supplier_id']:""?>'>
                          
                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Name* </label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type="text" class="form-control" name="name" placeholder="Enter Supplier Name" value="<?php echo !empty($supplier)?$supplier["name"]:"" ?>" required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Email Address*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='email' placeholder='Enter Email Address' value='<?php echo !empty($supplier)?$supplier['email']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Contact Number</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='contact_number' placeholder='Enter Contact Number' value='<?php echo !empty($supplier)?$supplier['contact_number']:"" ?>'  onkeypress="return isNumberKey(event)">
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Address*</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <input type='text' class='form-control' name='address' placeholder='Enter Business Address' value='<?php echo !empty($supplier)?$supplier['address']:"" ?>' required>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Description</label>
                                    <div class='col-sm-12 col-md-9'>
                                        <textarea class='form-control' name='description' value=''><?php echo !empty($supplier)?$supplier['description']:"" ?></textarea>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <button type='submit' class='btn btn-brand'> <span class='fa fa-check'></span> Save</button>
                                        <a href='suppliers.php' class='btn btn-default'>Cancel</a>
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