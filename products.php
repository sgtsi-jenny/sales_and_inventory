<?php
    require_once("support/config.php");
    if(!isLoggedIn()){
     toLogin();
     die();
    }

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

    $data=$con->myQuery("SELECT * FROM products WHERE is_deleted=0");
    makeHead("Products");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Products
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">

            <div class='col-md-12'>
              <?php 
                Alert();
              ?>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-12">
                        <div class='col-ms-12 text-right'>
                          <a href='frm_products.php' class='btn btn-warning'> Create New <span class='fa fa-plus'></span> </a>
                        </div>
                        <br/>
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Product Code</th>
                              <th class='text-center'>Product Name</th>
                              <th class='text-center'>Description</th>
                              <th class='text-center'>Category</th>
                              <th class='text-center'>Current Quantity</th>
                              <th class='text-center'>Unit</th>
                              <th class='text-center'>Selling Price</th>
                              <th class='text-center'>Wholesale Price</th>
                              <th class='text-center'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              while($row = $data->fetch(PDO::FETCH_ASSOC)):
                            ?>
                              <tr>
                                <td><?php echo htmlspecialchars($row['product_code'])?></td>
                                <td><?php echo htmlspecialchars($row['product_name'])?></td>
                                <td><?php echo htmlspecialchars($row['description'])?></td>
                                <td><?php echo htmlspecialchars($row['category_id'])?></td>
                                <td><?php echo htmlspecialchars($row['current_quantity'])?></td>
                                <td><?php echo htmlspecialchars($row['measure_id'])?></td>
                                <td><?php echo htmlspecialchars($row['selling_price'])?></td>
                                <td><?php echo htmlspecialchars($row['wholesale_price'])?></td>
                                <td class='text-center'>
                                  <a href='frm_products.php?id=<?php echo $row['product_id']; ?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
                                  <a href='#' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
                                </td>
                              </tr>
                            <?php
                              endwhile;
                            ?>
                          </tbody>
                        </table>
                    </div><!-- /.col -->
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
  Modal();
    makeFoot();
?>