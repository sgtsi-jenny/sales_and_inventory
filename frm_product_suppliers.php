<?php
	require_once("support/config.php");
	if(!isLoggedIn())
  {
		toLogin();
		die();
	}

  if(!AllowUser(array(1)))
  {
      redirect("index.php");
  }

	$data="";
	if(!empty($_GET['id']))
  {
  		$data=$con->myQuery("SELECT id,name FROM leaves WHERE is_deleted=0 AND id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
  		if(empty($data))
      {
  			Modal("Invalid Record Selected");
  			redirect("leave_type.php");
  			die;
  		}
	}

  $supplier=$con->myQuery("SELECT id,name FROM suppliers WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
  $product=$con->myQuery("SELECT id,CONCAT(product_name,' (',product_code,')') FROM products WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);

	makeHead("Product-Suppliers");
?>

<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
      <section class="content-header">
          <h1>
              Product-Suppliers
          </h1>
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
                	           <div class='col-md-12'>
		              	             <form class='form-horizontal' action='save_product_suppliers.php' method="POST">
		              		              <input type='hidden' name='id' value='<?php echo !empty($data)?$data['id']:''; ?>'>

                                    <div class='form-group'>
                                        <label for="supplier" class="col-sm-3 control-label"> Supplier *</label>
                                        <div class='col-sm-7'>
                                            <select class='form-control' name='supplier_id' data-placeholder="Select Supplier" <?php //echo!(empty($data))?"data-selected='".$data['supplier_id']."'":NULL ?> >
                                                <?php
                                                    echo makeOptions($supplier,"Select Supplier");
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class='form-group'>
                                        <label for="product" class="col-sm-3 control-label"> Product *</label>
                                        <div class='col-sm-7'>
                                            <select class='form-control' name='product_id' data-placeholder="Select Product" <?php //echo!(empty($data))?"data-selected='".$data['supplier_id']."'":NULL ?> >
                                                <?php
                                                    echo makeOptions($product,"Select Product");
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                      <label for="unit_price" class="col-md-3 control-label"> Unit Price * </label>
                                      <div class="col-md-7">
                                        <input type="text" class="form-control" id="unit_price"  name='unit_price' placeholder="0000.00" value='<?php echo !empty($employee)?htmlspecialchars($employee['basic_salary']):''; ?>' required>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="checkbox">
                                        <label>
                                          <input type="checkbox"> Check me out
                                        </label>
                                      </div>
                                    </div>
          		                      <div class="form-group">
            		                      <div class="col-sm-9 col-md-offset-2 text-center">
                                          <button type='submit' class='btn btn-warning'>Save </button>
              		                      	<a href='product_suppliers.php' class='btn btn-default'>Cancel</a>
            		                      </div>
          		                      </div>
		                          </form>	
                	       </div>
                      </div><!-- /.row -->
                  </div><!-- /.box-body -->
              </div><!-- /.box -->
          </div><!-- /.row -->
      </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable();
      });
</script>

<?php
	makeFoot();
?>