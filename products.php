<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    // if(!AllowUser(array(1))){
    //      redirect("index.php");
    // }
    $data=$con->myQuery("SELECT
                          p.product_id,
                          p.product_code,
                          p.product_name,
                          p.description,
                          p.category_id,
                          c.name AS category_name,
                          p.selling_price,
                          p.wholesale_price,
                          CONCAT(p.current_quantity,' ',m.abv) AS quantity,
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
                        <div class='col-ms-12 text-right'>
                          <a href='frm_products.php' class='btn btn-brand'> Create New <span class='fa fa-plus'></span> </a>
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
                              while($row = $data->fetch(PDO::FETCH_ASSOC)):
                            ?>
                              <tr>
                                <td><?php echo htmlspecialchars($row['product_code'])?></td>
                                <td><?php echo htmlspecialchars($row['product_name'])?></td>
                                <td><?php echo htmlspecialchars($row['description'])?></td>
                                <td><?php echo htmlspecialchars($row['category_name'])?></td>
                                <td><?php echo htmlspecialchars($row['selling_price'])?></td>
                                <td><?php echo htmlspecialchars($row['wholesale_price'])?></td>
                                <td><?php echo htmlspecialchars($row['quantity'])?></td>
                                <td><?php echo htmlspecialchars($row['barcode'])?></td>
                                <td class='text-center'>
                                  <a href='frm_products.php?id=<?php echo $row['product_id']; ?>' class='btn btn-brand btn-sm'><span class='fa fa-pencil'></span></a>
                                  <a href='delete.php?id=<?php echo $row['product_id']; ?>&t=prod' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
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
               // dom: 'Bfrtip',
               //      buttons: [
               //          {
               //              extend:"excel",
               //              text:"<span class='fa fa-download'></span> Download as Excel File "
               //          }
               //          ]
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