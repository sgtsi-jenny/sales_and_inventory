<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    // if(!AllowUser(array(1))){
    //      redirect("index.php");
    // }
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

    $payment=$con->myQuery("SELECT sm.sales_master_id,DATE_FORMAT(sp.pay_date,'%m/%d/%Y') as payment_date,c.customer_name,sp.reference,sp.amount AS pay_amount,sm.total_minus_wtax,sm.total_amount FROM sales_payments sp
      INNER JOIN sales_master sm ON sp.sales_master_id=sm.sales_master_id
      INNER JOIN customers c ON sm.customer_id=c.customer_id
      WHERE sp.is_voided=0")->fetchAll(PDO::FETCH_ASSOC);
    
    makeHead("Sales Payment Report");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
         <section class="content-header">
                                      <h1 align="center" style="color:#24b798;">
                                      Sales Payment Reports
                                      </h1>
        </section>
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="box box-primary">
                <div class="box-body">
                
                                <?php
                                Alert();
                                ?>   
                                <br>             
                            <!-- <br/>  <br/>  <br/>  <br/>  -->               
                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>SO Number</th>
                                                <th class='text-center'>Payment Date</th>
                                                <th class='text-center'>Customer Name</th>
                                                <th class='text-center'>Reference</th>
                                                <th class='text-center'>Paid Amount</th>              
                                                <th class='text-center'>Amount to Pay</th>
                                                <th class='text-center'>Total</th>
                                                
                            </tr>
                          </thead>
                          <tbody>
                                            <?php
                                                foreach ($payment as $row):
                                                  $action_buttons="";
                                            ?>

                                                <tr>
                                                    <?php
                                                      foreach ($row as $key => $value):
                                                    ?>
                                                    <?php
                                                      if($key=='pay_amount'):
                                                    ?>
                                                    <td class='text-right'><?php echo htmlspecialchars(number_format($row['pay_amount'],2)) ?></td>
                                                    <?php
                                                      elseif($key=='total_minus_wtax'):
                                                      $total=$row['total_amount'];
                                                      $pay_amount=$row['pay_amount'];
                                                      $bal=$total-$pay_amount;
                                                    ?>
                                                       <td class='text-right'><?php echo htmlspecialchars(number_format($bal,2)) ?></td>
                                                    <?php
                                                      elseif($key=='total_amount'):
                                                    ?>
                                                       <td class='text-right'><?php echo htmlspecialchars(number_format($row['total_amount'],2)) ?></td>
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