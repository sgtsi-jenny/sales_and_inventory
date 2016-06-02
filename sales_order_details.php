<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    // if(!AllowUser(array(1))){
    //      redirect("index.php");
    // }

    $tab="1";
    if(!empty($_GET['tab']) && !is_numeric($_GET['tab'])){
        redirect("sales_order_details.php".(!empty($employee)?'?id='.$employee['id']:''));
        die;
    }
    else{
        if(!empty($_GET['tab'])){
            if($_GET['tab'] >0 && $_GET['tab']<=9){
                $tab=$_GET['tab'];
            }
            else{
                #invalid TAB
                redirect("sales_order_details.php".(!empty($employee)?'?id='.$employee['id']:''));
            }
        }
    }
    // var_dump($_GET['id']);
    // die;


    if(empty($_GET['id'])){
        //Modal("No Account Selected");
        redirect("sales.php");
        die();
    }
    else{
        $sale=$con->myQuery("SELECT 
        sm.sales_master_id,
        customers.customer_id,
        customers.customer_name AS customer,
        customers.email,
        customers.fax,
        customers.mobile_number,
        customers.telephone_number,
        prod.product_name,
        ss.sales_status_id,
        ss.name AS status_name,
        DATE_FORMAT(sm.date_issue,'%m/%d/%Y') as date_issue,
        DATE_FORMAT(sm.date_modified,'%m/%d/%Y') as date_modified,
        sd.quantity,
        prod.current_quantity AS available,
        sd.unit_cost,
        sd.discount,
        sd.tax,
        (SELECT SUM(sd.quantity) FROM sales_details sd WHERE sd.sales_master_id=sm.sales_master_id) AS t_qty,
        (SELECT SUM(sd.total_cost) FROM sales_details sd WHERE sd.sales_master_id=sm.sales_master_id) AS total,
        (SELECT address FROM customer_address c_add WHERE sm.bill_to=c_add.customer_add_id) AS bill_to,
        (SELECT address FROM customer_address c_add WHERE sm.ship_to=c_add.customer_add_id) AS ship_to
        FROM sales_master sm
        INNER JOIN customers ON sm.customer_id=customers.customer_id
        INNER JOIN sales_status ss ON sm.sales_status_id=ss.sales_status_id
        INNER JOIN sales_details sd ON sm.sales_master_id=sd.sales_master_id
        INNER JOIN products prod ON prod.product_id=sd.product_id
        WHERE sm.sales_master_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($sale)){
            //Modal("Invalid sales Selected");
            redirect("sales.php");
            die;
        }
    }
    // var_dump($sale['customer_id']);
    // die;
    $customer=$con->myQuery("SELECT customer_id,customer_name FROM customers")->fetchAll(PDO::FETCH_ASSOC);
    $customer_add=$con->myQuery("SELECT customer_add_id,label_address FROM customers cus INNER JOIN customer_address cus_add ON cus.customer_id=cus_add.customer_id where cus.customer_id=?",array($sale['customer_id']))->fetchall(PDO::FETCH_ASSOC);
    // var_dump( $sale);
    // die;
    $invoice=$con->myQuery("SELECT invoice_master_id FROM invoice_master im INNER JOIN sales_master sm ON sm.sales_master_id=im.sales_master_id")->fetchAll(PDO::FETCH_ASSOC);
    $prod=$con->myQuery("SELECT product_id,product_name,selling_price,current_quantity FROM products")->fetchAll(PDO::FETCH_ASSOC);
    $sales_stat=$con->myQuery("SELECT name FROM sales_status where sales_status_id=1")->fetchAll(PDO::FETCH_ASSOC);
    
    
    makeHead("Sales");
?>
<script type="application/javascript">
  function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
         return true;
      }
</script>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        
        <section class="content-header" align="right">
         <h1 align="center" style="color:#24b798;">Sales Order Details</h1>
          <a href='sales.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Back to Sales List</a>
          <a href='frm_sales.php' class='btn btn-brand'> New Sales Order &nbsp;<span class='fa fa-plus'></span> </a>
          <?php
            if($sale['sales_status_id']==2 || $sale['sales_status_id']==3){
          ?>
          <a href='sales_void.php?id=<?=$_GET['id']?>' class='btn btn-default' onclick='return confirm("Click confirm to void this order. This will also rollback any fulfillments and revert any stock movements.")'>Void</a>
          <?php
            }
            elseif($sale['sales_status_id']==1){
          ?>
          <a href='frm_sales.php?id=<?php echo $_GET['id'];?>' class='btn btn-default'>Edit Order</a>
          <?php
            }
            ?>
        </section>
        <section class="content-header">
                                      <h1>
                                      <img src="uploads/summary_Oppurtunities.png" width="50" height="50" title="Organization" alt="Organization" />
                                      <?php echo htmlspecialchars($sale['customer']) ?>
                                      </h1>
        </section>
        <section class="content-header">
                    <div class="row">
                                <?php
                                    alert();
                                ?>
                    <div class='col-sm-12'>
                                <input type='hidden' name='sales_master_id' value='<?php echo !empty($supplier)?$supplier['supplier_id']:""?>'>
                                <label class='col-md-2 text-left' > Order Number</label>
                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-3'>
                                        <?php echo htmlspecialchars('#'.$sale['sales_master_id'])?>
                                    </div>
                                </div>
                                <br>
                                          
                                <label class='col-md-2 text-left' > Order creation date:</label>
                                <div class='form-group'>
                                  <div class='col-sm-12 col-md-3'>
                                        <?php echo htmlspecialchars($sale['date_issue'])?>
                                        <!-- <?php
                                          $php_timestamp = $sale['date_issue'];
                                          echo $php_timestamp;
                                           ?> -->
                                  </div>
                                </div>
                                <br>

                                <label class='col-md-2 text-left'> Order last updated date:</label>
                                <div class='form-group'>
                                  <div class='col-sm-12 col-md-3'>
                                        <?php echo htmlspecialchars($sale['date_modified'])?>
                                  </div>
                                </div>
                                <br>

                                <label class='col-md-2 text-left'> Order Status:</label>
                                <div class='form-group'>
                                  <div class='col-sm-12 col-md-3'>
                                        <?php echo htmlspecialchars($sale['status_name'])?>
                                  </div>
                                </div>
                                </br>
                                <?php
                                if($sale['sales_status_id']==1){
                                ?>
                                <div class='col-xs-12'>
                                    <p>Allocate your stock to this order. <br>
                                    Once allocated, the sales order will be locked and no additions will be possible.</p>                                        
                                      <a href='sale_allocate.php?id=<?=$_GET['id']?>' class='btn btn-brand'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Allocate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                </div>
                                <?php
                                    }
                                    elseif($sale['sales_status_id']==2){
                                ?>
                                <div class='col-xs-12'>
                                    <p>Generate an Invoice for this order. </p>
                                      <!--<a href='sales.php' class='btn btn-brand'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Invoice&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>-->
                                      <button type="button" class="btn btn-brand" data-toggle="modal" data-target="#myModal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Invoice&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>

                                </div>
                                <?php
                                    }
                                    elseif($sale['sales_status_id']==3){
                                ?>
                                <div class='col-xs-12'>
                                    <p>Generate a shipment for this order. </p>
                                      <button type="button" class="btn btn-brand" data-toggle="modal" data-target="#shipModal">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ship &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                </div>
                                <?php
                                }   
                                    else{
                                ?>
                                <div class='col-xs-12'>
                                    <p>Your order is complete. No other additional steps are required.</p>
                                </div>
                                <?php
                                    }
                                ?>
                                
                    </div>
                    </div>
        </section>
                            
        <section class="content">
          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php
                        $no_msg=' Sales order must be invoiced.';
                    ?>
                    <li <?php echo $tab=="1"?'class="active"':''?>><a href="" >Sales Order Details</a>
                    </li>

                   <!--  <li>
                        <?php
                        // var_dump($sale['sales_status_id']<>3);
                    ?>
                    </li> -->
                    <li <?php echo ($sale['sales_status_id']<>3)?'class="disabled"':''; ?> <?php echo $tab=="2"?'class="active"':''?> ><a href="sales_payments.php?id=<?php echo $_GET['id'] ?>" <?php echo ($sale['sales_status_id']<>3)?'onclick="alert(\''.$no_msg.'\');return false;"':''; ?>>Payments</a>
                    </li>
                   <!--  <li> <a href="sales_payments.php?id=<?php echo $_GET['id'] ?>">Payments</a>
                    </li> -->
                    
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >

                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center' style='min-width:200px'>Product Name</th>
                                                <th class='text-center'>Quantity</th>
                                                <th class='text-center'>Available</th>
                                                <th class='text-center'>Price (Php)</th>
                                                <th class='text-center'>Discount</th>
                                                <th class='text-center'>Tax</th>
                                                <th class='text-center'>Total (Php)</th>
                                                
                            </tr>
                          </thead>
                          <tbody>
                                            <?php                                              
                                                $opportunities=$con->myQuery("SELECT 
                                                prod.product_name,
                                                sd.quantity,
                                                prod.current_quantity AS available,
                                                sd.unit_cost,
                                                sd.discount,
                                                sd.tax,
                                                sd.total_cost
                                                FROM sales_master sm
                                                INNER JOIN customers ON sm.customer_id=customers.customer_id
                                                INNER JOIN sales_status ss ON sm.sales_status_id=ss.sales_status_id
                                                INNER JOIN sales_details sd ON sm.sales_master_id=sd.sales_master_id
                                                INNER JOIN products prod ON prod.product_id=sd.product_id
                                                WHERE sm.sales_master_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($opportunities as $row):
                                            ?>
                                            <tr>
                                                        <?php
                                                            foreach ($row as $key => $value):  
                                                        ?>
                                                        <?php
                                                            if($key=='unit_cost'):
                                                        ?> 
                                                            <td class='text-right'>
                                                                <?php echo htmlspecialchars(number_format($row['unit_cost'],2))?></a>
                                                            </td>
                                                        <?php
                                                            elseif($key=='total'):
                                                        ?>  
                                                            <td class='text-right'>
                                                                <?php echo htmlspecialchars(number_format($row['total'],2))?></a>
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

                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

  <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Invoice</h4>
                  </div>
                 
                      <div class="modal-body"> 
                        <form action='save_invoice.php' method='POST'>
                            <input type='hidden' name='invoice_master_id' value=''>
                            <input type='hidden' name='sales_master_id' value='<?php echo htmlspecialchars($_GET['id']) ?>'>
                            <input type='hidden' name='customer_id' value='<?php echo !empty($sale)?$sale['customer_id']:""?>'>
                            <input type='hidden' name='date_issue' value='<?php echo !empty($sale)?$sale['date_issue']:""?>'>
                            <input type='hidden' name='quantity' value='<?php echo !empty($sale)?$sale['t_qty']:""?>'>
                            <!-- <?php
                                // var_dump($sale['t_qty']);
                            ?> -->
                            <div class="form-group">
                                <label>Due Payement</label>
                                <input type="date" class="form-control" id="due_payment"  name='due_payment' value='<?php echo !empty($sale)?htmlspecialchars($sale['due_payment']):''; ?>' required>
                            </div>

                            <div class="form-group">
                                <label>Bill To</label>
                                    <select class='form-control' name='bill_to' id='bill_to'  onchange='get_address()' data-placeholder="Select a Customer" <?php echo!(empty($customer_add))?"data-selected='".$customer_add['label_address']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($customer_add,'Select Billing address')
                                                    ?>
                                    </select>
                            </div>

                            <div class="form-group">
                                <label>Ship To</label>
                                    <select class='form-control' name='ship_to' id='ship_to'  onchange='get_address()' data-placeholder="Select a Customer" <?php echo!(empty($customer_add))?"data-selected='".$customer_add['label_address']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($customer_add,'Select Shipping address')
                                                    ?>
                                        </select>
                            </div>

                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class='form-control' name='description' placeholder='Enter comments to your customer' value='<?php echo !empty($organization)?$organization['description']:"" ?>'></textarea>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-brand" type="submit" ">Save</button>
                                <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Modal -->



<!-- Ship Modal -->
            <div class="modal fade" id="shipModal" tabindex="-1" role="dialog" aria-labelledby="shipModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="shipModalLabel">Add Shipment</h4>
                  </div>
                 
                      <div class="modal-body">
                        <form action='save_shipment.php' method='POST'>
                            <?php                                              
                                // $shipment=$con->myQuery("SELECT 
                                // spt.name,
                                // sum(sp.amount) as amount,
                                // sp.amount as amount1,
                                // DATE_FORMAT(pay_date,'%m/%d/%Y') as pay_date,
                                // sp.amount AS balance,
                                // sp.reference,
                                // (SELECT SUM(sd.total_cost) FROM sales_details sd WHERE sd.sales_master_id=sm.sales_master_id) AS total
                                // FROM sales_payments sp
                                // INNER JOIN sales_master sm ON sp.sales_master_id=sm.sales_master_id
                                // INNER JOIN invoice_master im ON sp.invoice_master_id=im.invoice_master_id
                                // INNER JOIN sales_payment_type spt ON spt.payment_type_id=sp.type
                                // WHERE sp.is_voided=0 AND sp.sales_master_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
                                // foreach ($payments as $row):

                                $total_cost=$con->myQuery("SELECT 
                                (SELECT SUM(sd.total_cost) FROM sales_details sd WHERE sd.sales_master_id=sm.sales_master_id) AS total FROM sales_master sm
                                WHERE sm.sales_master_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);

                                $invoice_id=$con->myQuery("SELECT im.invoice_master_id FROM invoice_master im INNER JOIN sales_master sm ON im.sales_master_id=sm.sales_master_id WHERE sm.sales_master_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
                                if(!empty($_GET['p_id'])){
                                    $sales_payment=$con->myQuery("SELECT type,amount,DATE_FORMAT(pay_date,'%m/%d/%Y') as pay_date,reference FROM sales_payments WHERE is_voided=0 AND sales_payment_id=?",array($_GET['p_id']))->fetch(PDO::FETCH_ASSOC);
                                    // var_dump($sales_payment);
                                }

                                // foreach ($invoice_id as $rows):
                            ?>
                            <input type='hidden' name='shipment_id' value=''>
                            <input type='hidden' name='sales_master_id' value='<?php echo htmlspecialchars($_GET['id']) ?>'>
                            <?php
                                $c_id=$sale['customer_id'];
                                // echo $c_id;
                            ?>
                            <input type='hidden' name='customer_id' value='<?php echo $c_id ?>'>

                            <div class="form-group">
                                <label>Shipping Service</label>
                                <input type="text" class="form-control" id="ship_service" placeholder="DHL, FedEX, UPS..." name='ship_service' value='<?php echo !empty($shipment)?htmlspecialchars($shipment['ship_service']):''; ?>'   required>
                            </div>

                            <div class="form-group">
                                <label>Shipping Method</label>
                                <input type="text" class="form-control" id="ship_method" placeholder="By Air, Pickup..." name='ship_method' value='<?php echo !empty($shipment)?htmlspecialchars($shipment['ship_method']):''; ?>'   required>
                            </div>

                            <div class="form-group">
                                <label>Shipment Date</label>
                                <?php
                                        $date_shipped="";
                                         if(!empty($shipment)){
                                            $date_shipped=$shipment['date_shipped'];
                                            if($date_shipped=="00000000"){
                                                $date_shipped="";
                                            }
                                             else
                                            {
                                                $date_shipped=inputmask_format_date($date_shipped);
                                                //echo $dob;
                                            }
                                        }
                                        
                                         
                                                                               
                                    ?>
                                <input type='text' class='form-control date_picker' name='date_shipped'  value='<?php echo !empty($shipment)?htmlspecialchars($shipment['date_shipped']):''; ?>' required>
                            </div>

                            <div class="form-group">
                                <label>Delivery Date</label>
                                <?php
                                        $date_delivered="";
                                         if(!empty($shipment)){
                                            $date_delivered=$shipment['date_delivered'];
                                            if($date_delivered=="00000000"){
                                                $date_delivered="";
                                            }
                                             else
                                            {
                                                $date_delivered=inputmask_format_date($date_delivered);
                                                //echo $dob;
                                            }
                                        }
                                        
                                         
                                                                               
                                    ?>
                                <input type='text' class='form-control date_picker' name='date_delivered'  value='<?php echo !empty($shipment)?htmlspecialchars($shipment['date_delivered']):''; ?>' required>
                            </div>

                            <div class="form-group">
                                <label>Tracking code</label>
                                <input type="text" class="form-control" id="tracking_code" placeholder="Tracking code provided by the shipment service" name='tracking_code' value='<?php echo !empty($shipment)?htmlspecialchars($shipment['tracking_code']):''; ?>'   required>
                            </div>

                            <div class="form-group">
                                <label>Bill To</label>
                                    <select class='form-control' name='bill_to' id='bill_to'  onchange='get_address()' data-placeholder="Select a Customer" <?php echo!(empty($customer_add))?"data-selected='".$customer_add['label_address']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($customer_add,'Select Billing address')
                                                    ?>
                                    </select>
                            </div>

                            <div class="form-group">
                                <label>Ship To</label>
                                    <select class='form-control' name='ship_to' id='ship_to'  onchange='get_address()' data-placeholder="" <?php echo!(empty($customer_add))?"data-selected='".$customer_add['label_address']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($customer_add,'Select Shipping address')
                                                    ?>
                                        </select>
                            </div>

                            <div class="form-group">
                                <label>Ship From</label>
                                <input type="text" class="form-control" id="ship_from" placeholder="" name='ship_from' value='<?php echo !empty($shipment)?htmlspecialchars($shipment['ship_from']):''; ?>'>
                            </div>

                            <div class="form-group">
                                <label>Comment</label>
                                <textarea class='form-control' name='comments' placeholder='Enter comments to your customer' value='<?php echo !empty($organization)?$organization['description']:"" ?>'></textarea>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-brand" type="submit" ">Save</button>
                                <a href='sales_order_details.php?id=<?php echo $_GET['id'] ?>' type="button" class="btn btn-default"  >Cancel</a>
                            </div>
                            <?php
                                // endforeach;
                                // endforeach;
                            ?>
                        </form>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Ship Modal -->

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
               dom: 'Bfrtip'
               ,
                    buttons: [
                        // {
                        //     extend:"excel",
                        //     text:"<span class='fa fa-download'></span> Download as Excel File "
                        // }
                        ]
        });
      });


  function validatePost(post_form){
        console.log();
        var str_error="";
        $.each($(post_form).serializeArray(),function(index,field){
            console.log(field);
            if(field.value==""){
            
                switch(field.name){
                    case "due_payment":
                        str_error+="Please provide due payment date. \n";
                        break;
                }
                
            }

        });
        if(str_error!=""){
            alert(str_error );
            return false;
        }
        else{
            return true
        }
    }
</script>

<?php
    Modal();
    makeFoot();
?>