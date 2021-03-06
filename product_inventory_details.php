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
                          p.description AS product_description,
                          p.category_id,
                          c.name AS category_name,
                          p.selling_price,
                          p.wholesale_price,
                          p.current_quantity AS total_stock,
                          p.measurement_id,
                          m.name AS measurement_name,
                          p.minimum_quantity,
                          p.maximum_quantity,
                          p.barcode
                        FROM products p
                        INNER JOIN categories c
                          ON c.category_id=p.category_id
                        INNER JOIN measurements m
                          ON m.measurement_id=p.measurement_id
                        WHERE p.product_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
    makeHead("Products");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <br>  
         <section class="content-header">
            <a href='products_inventory.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Stock Monitoring</a>
            <br>
            <h2 align="left" style="color:#24b798;">
              <?php
                echo $data['product_name'];
              ?>
              <h4 align="left">
                <?php
                  echo "(".$data['product_code'].")";
                ?>
              </h4>
            </h2>
        </section>
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-12">
                      <table>
                          <tr>  
                              <td width="300px">
                                <p>Product Description: </p> 
                              </td>
                              <td>
                                <p><?php echo $data['product_description']; ?></p> 
                              </td>
                          </tr>
                          <tr>  
                              <td width="50px">
                                <p>Category: </p> 
                              </td>
                              <td>
                                <p><?php echo $data['category_name']; ?></p> 
                              </td>
                          </tr>
                          <tr>  
                              <td width="50px">
                                <p>Selling Price (Retail): </p> 
                              </td>
                              <td>
                                <p><?php echo "PHP ".number_format($data['selling_price'],2,".",","); ?></p> 
                              </td>
                          </tr>
                          <tr>  
                              <td width="50px">
                                <p>Wholesale  Price: </p> 
                              </td>
                              <td>
                                <p><?php echo "PHP ".number_format($data['wholesale_price'],2,".",","); ?></p> 
                              </td>
                          </tr>
                          <tr>  
                              <td width="50px">
                                <p>Unit: </p> 
                              </td>
                              <td>
                                <p><?php echo $data['measurement_name']; ?></p> 
                              </td>
                          </tr>
                          <?php
                            $alloc=$con->myQuery("SELECT SUM(sd.quantity) AS order_qty FROM sales_details sd INNER JOIN sales_master sm ON sm.sales_master_id=sd.sales_master_id WHERE sm.sales_status_id=2 AND sd.product_id=? GROUP BY sd.product_id",array($data['product_id']))->fetch(PDO::FETCH_ASSOC);
                          ?>
                          <tr>  
                              <td width="50px">
                                <p>Allocated Stocks: </p> 
                              </td>
                              <td>
                                <p><?php echo $alloc['order_qty']; ?></p> 
                              </td>
                          </tr>
                          <tr>  
                              <td width="50px">
                                <p>Stock on-hand: </p> 
                              </td>
                              <td>
                                <p><?php echo $data['total_stock']; ?></p> 
                              </td>
                          </tr>
                          <tr>  
                              <td width="50px">
                                <p>Total Stock: </p> 
                              </td>
                              <td>
                                <p><?php echo $data['total_stock'] + $alloc['order_qty']; ?></p> 
                              </td>
                          </tr>
                          <tr>  
                              <td width="50px">
                                <p>Minimum Stock: </p> 
                              </td>
                              <td>
                                <p><?php echo $data['minimum_quantity']; ?></p> 
                              </td>
                          </tr>
                          <tr>  
                              <td width="50px">
                                <p>Maximum Stocks: </p> 
                              </td>
                              <td>
                                <p><?php echo $data['maximum_quantity']; ?></p> 
                              </td>
                          </tr>
                          <tr>  
                              <td width="50px">
                                <p>Barcode: </p> 
                              </td>
                              <td>
                                <p><?php echo $data['barcode']; ?></p> 
                              </td>
                          </tr>
                          <tr>  
                              <td width="50px">
                                <p>Stock Condition: </p> 
                              </td>
                              <td>
                                <p>
                                  <?php
                                    if ($data['total_stock'] > $data['maximum_quantity']) 
                                    {
                                      echo "<button class='btn btn-warning' style='width:100%'>Oversupply</button>";
                                    }elseif (($data['total_stock'] <= $data['maximum_quantity']) && ($data['total_stock'] >= $data['minimum_quantity'])) 
                                    {
                                      echo "<button class='btn btn-brand' style='width:100%'>Normal</button>";
                                    }elseif ($data['total_stock'] < $data['minimum_quantity']) 
                                    {
                                      echo "<button class='btn btn-danger' style='width:100%'>Critical</button>";
                                    }
                                  ?>
                                </p> 
                              </td>
                          </tr>
                      <table>
                    </div>
                  </div>
                </div><!-- /.box-body -->
              </div>
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<?php
    Modal();
    makeFoot();
?>  