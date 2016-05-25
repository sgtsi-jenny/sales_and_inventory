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
                          <tr>  
                              <td width="50px">
                                <p>Total Stock: </p> 
                              </td>
                              <td>
                                <p><?php echo $data['total_stock']; ?></p> 
                              </td>
                          </tr>
                          <tr>  
                              <td width="50px">
                                <p>Stock on-hand: </p> 
                              </td>
                              <td>
                                <p>#</p> 
                              </td>
                          </tr>
                          <tr>  
                              <td width="50px">
                                <p>Allocated Stocks: </p> 
                              </td>
                              <td>
                                <p>#</p> 
                              </td>
                          </tr>
                      <table>
                      <!--
                        <div class='col-ms-12 text-right'>
                          <a href='frm_products.php' class='btn btn-success'> Create New <span class='fa fa-plus'></span> </a>
                        </div>
                        </br>
                        <?php
                          Alert();
                        ?>
                        <br/>
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Product Code</th>
                              <th class='text-center'>Product Name</th>
                              <th class='text-center'>Description</th>
                              <th class='text-center'>Category</th>
                              <th class='text-center'>Selling Price</th>
                              <th class='text-center'>Wholesale Price</th>
                              <th class='text-center'>Current Quantity</th>
                              <th class='text-center'>Barcode</th>
                              <th class='text-center'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              //while($row = $data->fetch(PDO::FETCH_ASSOC)):
                            ?>
                              <tr>
                                <td><?php //echo htmlspecialchars($row['product_code'])?></td>
                                <td><?php //echo htmlspecialchars($row['product_name'])?></td>
                                <td><?php //echo htmlspecialchars($row['description'])?></td>
                                <td><?php //echo htmlspecialchars($row['category_name'])?></td>
                                <td><?php //echo htmlspecialchars($row['selling_price'])?></td>
                                <td><?php //echo htmlspecialchars($row['wholesale_price'])?></td>
                                <td><?php //echo htmlspecialchars($row['quantity'])?></td>
                                <td><?php //echo htmlspecialchars($row['barcode'])?></td>
                                <td class='text-center'>
                                  <a href='frm_products.php?id=<?php //echo $row['product_id']; ?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
                                  <a href='delete.php?id=<?php //echo $row['product_id']; ?>&t=prod' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
                                </td>
                              </tr>
                            <?php
                              //endwhile;
                            ?>
                          </tbody>
                        </table>
                      -->
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