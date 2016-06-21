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
    $data=$con->myQuery("SELECT
                          p.product_id,
                          p.product_code,
                          p.product_name,
                          p.description,
                          p.category_id,
                          c.name AS category_name,
                          p.selling_price,
                          p.wholesale_price,
                          p.current_quantity AS quantity,
                          IFNULL((SELECT SUM(quantity) FROM sales_details sd INNER JOIN sales_master sm ON sm.sales_master_id=sd.sales_master_id
                            WHERE sd.product_id=p.product_id AND sm.sales_status_id=2),'0') AS allocated,
                          p.minimum_quantity,
                          p.maximum_quantity,
                          p.barcode
                        FROM products p
                        INNER JOIN categories c
                          ON c.category_id=p.category_id
                        INNER JOIN measurements m
                          ON m.measurement_id=p.measurement_id
                        WHERE p.is_deleted=0
                        ");
    makeHead("Products");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
         <section class="content-header">
            <h1 align="center" style="color:#24b798;">
            List of Products
            </h1>
        </section>
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-12">
                       <!-- <div class='col-ms-12 text-right'>
                          <a href='frm_products.php' class='btn btn-success'> Create New <span class='fa fa-plus'></span> </a>
                        </div>
                      -->
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Product Code</th>
                              <th class='text-center'>Product Name</th>
                              <th class='text-center'>Description</th>
                              <th class='text-center'>Category</th>
                              <th class='text-center'>Total Quantity</th>
                              <th class='text-center'>Allocated Stocks</th>
                              <th class='text-center'>Stock on hand</th>
                              <th class='text-center' style='width:11%'>Stock Condition</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              while($row = $data->fetch(PDO::FETCH_ASSOC)):
                            ?>
                              <tr>
                                <td><a href="product_inventory_details.php?id=<?php echo $row['product_id']; ?>"><i class="fa fa-cube"></i> <?php echo htmlspecialchars($row['product_code'])?></a></td>
                                <td><?php echo htmlspecialchars($row['product_name'])?></td>
                                <td><?php echo htmlspecialchars($row['description'])?></td>
                                <td><?php echo htmlspecialchars($row['category_name'])?></td>
                                <?php
                                  $alloc=$con->myQuery("SELECT SUM(sd.quantity) AS order_qty FROM sales_details sd INNER JOIN sales_master sm ON sm.sales_master_id=sd.sales_master_id WHERE sm.sales_status_id=2 AND sd.product_id=? GROUP BY sd.product_id",array($row['product_id']))->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <td><?php echo intval($row['quantity']) + $alloc['order_qty']; ?></td>
                                <td><?php echo !empty($alloc['order_qty'])?$alloc['order_qty']:'0'; ?></td>
                                <td> <?php echo intval($row['quantity']); ?> </td>
                                <td>
                                  <?php
                                    if ($row['quantity'] > $row['maximum_quantity']) 
                                    {
                                      echo "<button class='btn btn-sm btn-warning' style='width:100%'>Oversupply</button>";
                                    }elseif (($row['quantity'] <= $row['maximum_quantity']) && ($row['quantity'] >= $row['minimum_quantity'])) 
                                    {
                                      echo "<button class='btn btn-sm btn-brand' style='width:100%'>Normal</button>";
                                    }elseif ($row['quantity'] < $row['minimum_quantity']) 
                                    {
                                      echo "<button class='btn btn-sm btn-danger' style='width:100%'>Critical</button>";
                                    }
                                  ?>
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
              </div>
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
          "scrollX": true,
          searching: true,
          lengthChange: false,
                dom: 'Bfrtip',
                     buttons: [
                         {
                             extend:"excel",
                             text:"<span class='fa fa-download'></span> Download as Excel File "
                         }
                         ]
        });
      });
</script>  
<script type="text/javascript">
    function validatePost(post_form){
        console.log();
        var str_error="";
        $.each($(post_form).serializeArray(),function(index,field){
            console.log(field);
            if(field.value==""){
            
                switch(field.name){
                    case "name":
                        str_error+="Incorrect entry in the field. \n";
                        break;
                    case "prod_price":
                        str_error+="Please provide product price. \n";
                        break;
                }
                
            }

        });
        if(str_error!=""){
            alert("You have the following errors: \n" + str_error );
            return false;
        }
        else{
            return true
        }
    }

    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY": true,
                "scrollX": true
        });
    });

    function get_price(){
        
        $("#prod_based_price").val($("#prod_id option:selected").data("price"));
        
        $("#prod_name2").val($("#prod_id option:selected").html());
    }
    
</script>
<?php 
  if(!empty($data)):
?>
<script type="text/javascript">
  $(function(){
    $('#collapseForm').collapse({
      toggle: true
    })    
  });
</script>

<?php
  endif;
?>
<?php
    Modal();
    makeFoot();
?>