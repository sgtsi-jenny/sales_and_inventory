<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    if(!AllowUser(array(1))){
         redirect("index.php");
    }
    if(!empty($_GET['d_start'])){
        $_start=date_create($_GET['d_start']);
    }
    else{
        $d_start="";
    }
    if(!empty($_GET['d_end'])){
        $d_end=date_create($_GET['d_end']);
    }
    else{
        $d_end="";
    }

    $date_filter="";
    if(!empty($d_start)){
        $date_filter.=" AND purchase_date >= '".date_format($d_start,'Y-m-d')."'";
    }

    if(!empty($d_end)){
        $date_filter.=" AND purchase_date <= '".date_format($d_end,'Y-m-d')."'";
    }

    $sold_items=$con->myQuery("SELECT 
    DISTINCT(p.product_name),
    SUM(quantity) AS quantity_sold, 
    p.selling_price,
    SUM(sd.discount) AS discount,
    SUM(sd.total_cost) AS total_cost
    FROM products p 
    INNER JOIN sales_details sd ON sd.product_id=p.product_id
    INNER JOIN sales_master sm ON sm.sales_master_id=sd.sales_master_id
    INNER JOIN customers ON sm.customer_id=customers.customer_id
    GROUP BY sd.product_id")->fetchAll(PDO::FETCH_ASSOC);
    
    makeHead("Sold Items Report");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
         <section class="content-header">
                                      <h1 align="center" style="color:#24b798;">
                                      Sold Items Report
                                      </h1>
        </section>
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="box box-primary">
                <div class="box-body">
                <!-- <form method='GET'>
                  <label class='col-md-2 text-right' >Start Date</label>
                      <div class='col-md-3'>
                        <input type='date' name='d_start' class='form-control' id='d_start' value='<?php echo !empty($_GET['date_start'])?htmlspecialchars($_GET['d_start']):''?>'>
                      </div>
                      <label class='col-md-2 text-right' >End Date</label>
                      <div class='col-md-3'>
                        <input type='date' name='d_end' class='form-control' id='d_end' value='<?php echo !empty($_GET['d_end'])?htmlspecialchars($_GET['d_end']):''?>'>
                      </div>
                      <div class='col-md-2'>
                        <button type='submit' class='btn btn-brand'> <span class='fa fa-check'></span>Filter</button>
                      </div>
                </form> -->
                                <?php
                                Alert();
                                ?>   
                                <br>             
                            <!-- <br/>  <br/>  <br/>  <br/>  -->               
                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Product Name</th>
                                                <th class='text-center'>Quantity Sold</th>
                                                <th class='text-center'>Selling Price</th>
                                                <th class='text-center'>Discount</th>              
                                                <th class='text-center'>Total Cost</th>
                                               <!--  <th class='text-center'>Action</th> -->
                                                
                            </tr>
                          </thead>
                          <tbody>
                                            <?php
                                                foreach ($sold_items as $row):
                                                  $action_buttons="";
                                            ?>

                                                <tr>
                                                    <?php
                                                      foreach ($row as $key => $value):
                                                    ?>
                                                    <?php
                                                      if($key=='selling_price'):
                                                    ?>
                                                    <?php
                                                      elseif($key=='quantity_sold'):
                                                    ?>
                                                       <td class='text-center'><?php echo htmlspecialchars($row['quantity_sold']) ?></td> 
                                                       <td class='text-right'><?php echo htmlspecialchars(number_format($row['selling_price'],2)) ?></td>
                                                    <?php
                                                      elseif($key=='total_cost'):
                                                    ?>
                                                       <td class='text-right'><?php echo htmlspecialchars(number_format($row['total_cost'],2)) ?></td>
                                                    <?php
                                                      elseif($key=='discount'):
                                                    ?>
                                                       <td class='text-center'><?php echo htmlspecialchars($row['discount']) ?>%</td>
                                                   
                                                    <?php
                                                      else:
                                                    ?>
                                                            <td>
                                                                <?php
                                                                    echo htmlspecialchars($value);
                                                                ?>
                                                            </td>
                                                    <?php
                                                      endif;
                                                      endforeach;
                                                    ?>
                                                </tr>
                                            <?php
                                                endforeach;
                                            ?>
                                        </tbody>
                        </table>


                  <!--</div>--><!-- /.tab-pane -->
                <!--</div>--><!-- /.tab-content -->
              <!--</div>--><!-- /.nav-tabs-custom -->
                </div>
              </div>
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
         $('#ResultTable').DataTable({
            "scrollX": true,
            dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"excel",
                        text:"<span class='fa fa-download'></span> Download as Excel File "
                    }
                    ],

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