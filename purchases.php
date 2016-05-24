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

    makeHead("Purchase");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <section class="content-header">
              <h1 align="center" style="color:#24b798;">
              Purchase Orders
              </h1>
        </section>
        <section class="content">
          <!-- Main row -->
            <div class="row">
                <div class='col-md-12'>
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class='panel-body'>
                                <div class='col-md-12 text-right'>
                                    <div class='col-md-12 text-right'>
                                        <a href='#' class='btn btn-brand'> New Purchase Order<span class='fa fa-plus'></span> </a>
                                    </div>                                
                                </div> 
                            </div>
                            <?php
                                Alert();
                            ?>
                            <br/>                 
                            <table id='ResultTable' class='table table-bordered table-striped'>
                                <thead>
                                    <tr>
                                        <th class='text-center'>PO Number</th>
                                        <th class='text-center'>Supplier</th>
                                        <th class='text-center'>Date Purchased</th>
                                        <th class='text-center'>PO Status</th>
                                        <th class='text-center'>Total</th>
                                        <th class='text-center'>Payment Status</th>
                                        <th class='text-center'>Date Modified</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $po=$con->myQuery("SELECT 
                                            sm.sales_master_id,
                                            customers.customer_name AS customer,
                                            ss.name AS status_name,
                                            ps.name AS payment_name,
                                            (SELECT SUM(sd.total_cost) FROM sales_details sd WHERE sd.sales_master_id=sm.sales_master_id) AS total,
                                            (SELECT SUM(sp.amount) FROM sales_payments sp WHERE sp.sales_master_id=sm.sales_master_id) AS paymed_amount
                                            FROM sales_master sm
                                            INNER JOIN customers ON sm.customer_id=customers.customer_id
                                            INNER JOIN sales_status ss ON sm.sales_status_id=ss.sales_status_id
                                            INNER JOIN payment_status ps ON sm.payment_status_id=ps.payment_status_id")->fetchAll(PDO::FETCH_ASSOC);
                                          
                                          foreach ($sales as $row):
                                            $action_buttons="";
                                    ?>
                                    <tr>
                                        <?php
                                            foreach ($row as $key => $value):
                                        ?>
                                        <?php
                                            if($key=='sales_master_id'):
                                        ?>
                                        <td class='text-center'> 
                                            <a href='sales_order_details.php?id=<?= $row['sales_master_id']?>'><img width="36" height="36" class="" src="uploads/so_id.png">SO<?php echo htmlspecialchars($value)?></a>
                                        </td>
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
                        </div>
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