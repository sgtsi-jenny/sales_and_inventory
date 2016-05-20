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

    $customer=$con->myQuery("SELECT customer_id,customer_name FROM customers")->fetchAll(PDO::FETCH_ASSOC);
	makeHead("Sales Order");


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
                <h1 align="center" style="color:#24b798;">Update Sales Order</h1>
            <?php
            }
                else{                    
            ?>
            <h1 align="center" style="color:#24b798;">Create New Sales Order</h1>                
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
                                    <label class='col-sm-12 col-md-3 control-label'> Customer</label>
                                    <div class='col-sm-12 col-md-4'>
                                        <select class='form-control' name='customer_id' id='customer_id'  onchange='get_address()' data-placeholder="Select a Customer" <?php echo!(empty($organization))?"data-selected='".$organization['industry']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($customer,'Select Customer')
                                                    ?>
                                        </select>
                                    </div>
                                </div>


                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Ship To:</label>
                                    <div class='col-sm-12 col-md-4'>
                                        <select class='form-control' name='industry' data-placeholder="Select an address" <?php echo!(empty($organization))?"data-selected='".$organization['industry']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($customer,'Select an address',NULL,'',!(empty($organization))?$organization['industry']:NULL)
                                                    ?>
                                        </select>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Bill To:</label>
                                    <div class='col-sm-12 col-md-4'>
                                        <select class='form-control' name='industry' data-placeholder="Select an address" <?php echo!(empty($organization))?"data-selected='".$organization['industry']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($customer,'Select an address',NULL,'',!(empty($organization))?$organization['industry']:NULL)
                                                    ?>
                                        </select>
                                    </div>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Order creation date:</label>
                                        <?php
                                          $php_timestamp = time();
                                          $php_timestamp_date = date("F d, Y l h:i A", $php_timestamp);
                                          echo $php_timestamp_date;
                                           ?>
                                </div>

                                <div class='form-group'>
                                    <label class='col-sm-12 col-md-3 control-label'> Order last updated date:</label>
                                        <?php
                                          $php_timestamp = time();
                                          $php_timestamp_date = date("F d, Y l h:i A", $php_timestamp);
                                          echo $php_timestamp_date;
                                           ?>
                                </div>
                                <br><br>
                                 <?php
                                    $sales_details=$con->myQuery("SELECT prod.product_name FROM sales_details sd INNER JOIN products prod ON sd.product_id=prod.product_id")->fetchAll(PDO::FETCH_ASSOC);
                                    if(!empty($sales_details)):

                                ?>
                                <table class='table table-bordered table-condensed '>
                                    <thead>
                                        <tr>    
                                            <td>Product Name</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                foreach ($sales_details as $sd):
                                        ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($sd['product_name']);?></td>
                                                    
                                                    <td class='text-center'> 
                                                    <a class='btn btn-flat btn-sm btn-danger' href='delete.php?id=<?php echo $sd['id']?>&t=fu&a=<?php echo $asset['id']?>' onclick='return confirm("This sale order will be deleted.")'><span class='fa fa-trash'></span></a>
                                                    
                                                    </td>
                                                </tr>
                                        <?php
                                                endforeach;
                                        ?>
                                        
                                    </tbody>
                                </table>
                                    <?php
                                    endif;
                                    ?>





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


  function get_address(){
        
        $("#address").val($("#customer_id option:selected").data("price"));
        
        $("#prod_name2").val($("#prod_id option:selected").html());
    }
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