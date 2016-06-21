<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    // if(!AllowUser(array(1))){
    //      redirect("index.php");
    // }

    $tab="2";
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
    $customer=$con->myQuery("SELECT customer_id,customer_name,is_top_company FROM customers where customer_id=?",array($sale['customer_id']))->fetch(PDO::FETCH_ASSOC);
    // $customer=$con->myQuery("SELECT customer_id,customer_name FROM customers")->fetchAll(PDO::FETCH_ASSOC);
    $customer_add=$con->myQuery("SELECT customer_add_id,label_address FROM customers cus INNER JOIN customer_address cus_add ON cus.customer_id=cus_add.customer_id where cus.customer_id=?",array($sale['customer_id']))->fetchall(PDO::FETCH_ASSOC);
    // var_dump( $sale);
    // die;
    $invoice=$con->myQuery("SELECT invoice_master_id FROM invoice_master im INNER JOIN sales_master sm ON sm.sales_master_id=im.sales_master_id")->fetchAll(PDO::FETCH_ASSOC);
    $prod=$con->myQuery("SELECT product_id,product_name,selling_price,current_quantity FROM products")->fetchAll(PDO::FETCH_ASSOC);
    $sales_stat=$con->myQuery("SELECT name FROM sales_status where sales_status_id=1")->fetchAll(PDO::FETCH_ASSOC);
    $payment_type=$con->myQuery("SELECT payment_type_id,name FROM sales_payment_type")->fetchAll(PDO::FETCH_ASSOC);
    $row=$con->myQuery("SELECT 
            sum(sp.amount) as amount,
            (SELECT SUM(sd.total_cost) FROM sales_details sd WHERE sd.sales_master_id=sm.sales_master_id) AS total
            FROM sales_payments sp
            INNER JOIN sales_master sm ON sp.sales_master_id=sm.sales_master_id
            INNER JOIN invoice_master im ON sp.invoice_master_id=im.invoice_master_id
            INNER JOIN sales_payment_type spt ON spt.payment_type_id=sp.type
            WHERE sp.is_voided=0 AND sp.sales_master_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);

    $total_cost=$con->myQuery("SELECT 
            (SELECT SUM(sd.total_cost) FROM sales_details sd WHERE sd.sales_master_id=sm.sales_master_id) AS total FROM sales_master sm
            WHERE sm.sales_master_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
    
    // var_dump ($invoice_id);
    // die;
    
    makeHead("Sales Payment");
?>
<script type="application/javascript">
  function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 46 || charCode > 57))
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
            if($sale['sales_status_id']==2 || $sale['sales_status_id']==3 && AllowUser(array(1))){
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
                                <br>

                                 <label class='col-md-2 text-left'> Remaining Balance:</label>
                                <div class='form-group'>
                                  <div class='col-sm-12 col-md-3'>
                                        <?php
                                        
                                        ?>
                                                        <?php
                                                            // $amount=0;
                                                            // var_dump($customer['is_top_company']);
                                                        $total=$total_cost['total'];
                                                        $tax=$total*.12;
                                                        $subtotal=$total-$tax;
                                                        $wtax=$subtotal*.05;
                                                        
                                                        if ($customer['is_top_company']==1){
                                                            $total_wtax=$total-$wtax;
                                                        }
                                                        else{
                                                            $total_wtax=$total;
                                                        }
                                                            
                                                            if(empty($row)){
                                                                $amount=0;
                                                                $balance=$total_wtax-$amount;
                                                            }
                                                            else{
                                                                $amount=$row['amount'];
                                                                $balance=$total_wtax-$amount;
                                                            }

                                                            if ($balance<=0){
                                                                echo "00.00";
                                                            }
                                                            else{
                                                                echo (number_format($balance,2));
                                                            }
                                                            
                                                        ?>  
                                  </div>
                                </div>
                                
                    </div>
                    </div>
        </section>
                            

        <section class="content">
          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <!-- <?php
                        $no_employee_msg=' Personal Information must be saved.';
                    ?> -->

                    <li> <a href="sales_order_details.php?id=<?php echo $_GET['id'] ?>">Sales Order Details</a>
                    </li>
                    <li <?php echo $tab=="2"?'class="active"':''?>><a href="" >Payments</a>
                    </li>
                    
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >
                            <div class='panel-body'>
                                    <div class='col-md-12 text-right'>
                                        <div class='col-md-12 text-right'>
                                        <!-- <button class='btn btn-brand' data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm"> -->
                                        <?php
                                        if (!($balance<=0)){
                                            // var_dump($balance);
                                        ?>
                                            <button type="button" class="btn btn-brand" data-toggle="modal" data-target="#myModal">Add Payment <span class='fa fa-plus'></span> </button>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                                                  
                                    </div> 
                            </div>

                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-left' style='min-width:200px'>Payment Type</th>
                                                <th class='text-center'>Amount (Php)</th>
                                                <th class='text-center'>Payment Date</th>
                                                <!-- <th class='text-center'>Balance</th> -->
                                                <th class='text-center'>Reference</th>
                                                <?php
                                                  if(AllowUser(array(1))):
                                                ?> 
                                                <th class='text-center'>Action</th>
                                                <?php
                                                endif;
                                                ?>
                                                
                            </tr>
                          </thead>
                          <tbody>
                                            <?php
                                                $t_amount=0;                                           
                                                $payments=$con->myQuery("SELECT 
                                                spt.name,
                                                sp.amount,
                                                DATE_FORMAT(sp.pay_date,'%m/%d/%Y') as pay_date,
                                                sp.reference,
                                                (SELECT SUM(sd.total_cost) FROM sales_details sd WHERE sd.sales_master_id=sm.sales_master_id) AS total,
                                                sp.sales_payment_id
                                                FROM sales_payments sp
                                                INNER JOIN sales_master sm ON sp.sales_master_id=sm.sales_master_id
                                                INNER JOIN invoice_master im ON sp.invoice_master_id=im.invoice_master_id
                                                INNER JOIN sales_payment_type spt ON spt.payment_type_id=sp.type
                                                WHERE sp.is_voided=0 AND sm.sales_master_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($payments as $row):
                                            ?>
                                            <tr>
                                                        <?php
                                                            foreach ($row as $key => $value):  
                                                        ?>
                                                        <?php
                                                            if($key=='amount'):
                                                        ?> 
                                                            <td class='text-right'>
                                                                <?php echo htmlspecialchars(number_format($row['amount'],2))?></a>
                                                            </td>
                                                        <?php
                                                            elseif($key=='pay_date'):
                                                        ?> 
                                                            <td class='text-right'>
                                                                <?php echo htmlspecialchars($row['pay_date'])?></a>
                                                            </td>
                                                        <?php
                                                            elseif($key=='total'):
                                                        ?> 
                                                         <?php
                                                            elseif($key=='sales_payment_id'):
                                                        ?>
                                                        <?php
                                                          if(AllowUser(array(1))):
                                                        ?>
                                                            <td class="text-center">
                                                            <!-- 
                                                                <a class='btn btn-sm btn-brand' href='sales_payments.php?id=<?php echo $_GET['id'];?>&p_id=<?php echo $row['sales_payment_id'] ?>'><span class='fa fa-pencil'></span></a>
                                                             -->
                                                                <a class='btn btn-sm btn-danger' href='void_payment.php?id=<?php echo $_GET['id'];?>&p_id=<?php echo $row['sales_payment_id'] ?>' onclick='return             vvb ;; confirm("This payment will be voided.")'>&nbsp;&nbsp;&nbsp;Void&nbsp;&nbsp;&nbsp;</a>
                                                            </td> 
                                                        <?php
                                                        endif;
                                                        ?>
                                                            
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
                          <?php

                          // echo $amount;
                          ?>
                        </table>

                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

  <!-- Invoice Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Payment</h4>
                  </div>
                 
                      <div class="modal-body">
                        <!-- <?php 
                           // if (!empty($_GET['p_id'])){
                        ?>-->
                                <!-- <form action='save_payment.php?p_id=<?php echo $_GET['p_id'] ?>' method='POST'> -->
                        <!-- <?php 
                            // }
                            // else{
                        ?>-->
                                
                        <!-- <?php 
                            // }                            
                        ?>-->
                        <form action='save_payment.php' method='POST'>
                            <?php                                              
                                $row=$con->myQuery("SELECT 
                                spt.name,
                                sum(sp.amount) as amount,
                                sp.amount as amount1,
                                DATE_FORMAT(pay_date,'%m/%d/%Y') as pay_date,
                                sp.amount AS balance,
                                sp.reference,
                                (SELECT SUM(sd.total_cost) FROM sales_details sd WHERE sd.sales_master_id=sm.sales_master_id) AS total
                                FROM sales_payments sp
                                INNER JOIN sales_master sm ON sp.sales_master_id=sm.sales_master_id
                                INNER JOIN invoice_master im ON sp.invoice_master_id=im.invoice_master_id
                                INNER JOIN sales_payment_type spt ON spt.payment_type_id=sp.type
                                WHERE sp.is_voided=0 AND sp.sales_master_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
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


                            <section align='right'>
                                <label>Remaining Balance: </label>
                                    <?php
                                        // $amount=0;
                                        $total=$total_cost['total'];
                                        $tax=$total*.12;
                                        $subtotal=$total-$tax;
                                        $wtax=$subtotal*.05;
                                        

                                        if ($customer['is_top_company']==1){
                                            $total_wtax=$total-$wtax;
                                            // var_dump($total_wtax);
                                        }
                                        else{
                                            $total_wtax=$total;
                                            // var_dump($total_wtax);
                                        }

                                        if(empty($payments)){
                                            $amount=0;
                                            $balance=$total_wtax-$amount;
                                        }
                                        else{
                                            $amount=$row['amount'];
                                            $balance=$total_wtax-$amount;
                                        }
                                        echo (number_format($balance,2));
                                    ?>
                                <!-- <input type="text" id="total_cost" name='total_cost' value='' readonly> -->
                            </section>
                            <input type='hidden' name='payment_id' value=''>
                            <input type='hidden' name='sales_master_id' value='<?php echo htmlspecialchars($_GET['id']) ?>'>
                            <?php
                                $i_id=$invoice_id['invoice_master_id'];
                                // var_dump($row);
                                // echo ($i_id);
                            ?>
                            <input type='hidden' name='invoice_master_id' value='<?php echo $i_id ?>'>
                            <div class="form-group">
                                <label>Payment Date</label>
                                <?php
                                        $pdate="";
                                         if(!empty($sales_payment)){
                                            $pdate=$sales_payment['pay_date'];
                                            if($pdate=="00000000"){
                                                $pdate="";
                                            }
                                             else
                                            {
                                                $pdate=inputmask_format_date($pdate);
                                                //echo $dob;
                                            }
                                        }
                                        
                                         
                                                                               
                                    ?>
                                <input type='text' class='form-control date_picker' name='pay_date'  value='<?php echo !empty($sales_payment)?htmlspecialchars($sales_payment['pay_date']):''; ?>' required>
                            </div>

                            <div class="form-group">
                                <label>Payment Type</label>
                                <select class='form-control' name='payment_type' data-placeholder="Select Payment Type" <?php echo!(empty($payment_type))?"data-selected='".$payment_type['payment_type_id']."'":NULL ?> required>
                                        <?php
                                            echo makeOptions($payment_type,'Select Payment Type',NULL,'',!(empty($sales_payment))?$sales_payment['type']:NULL)
                                        ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" class="form-control" id="amount" placeholder="0" name='amount' value='<?php echo !empty($sales_payment)?htmlspecialchars($sales_payment['amount']):''; ?>'  onkeypress='return isNumberKey(event)' required>
                            </div>

                            <div class="form-group">
                                <label>Reference</label>
                                <textarea class='form-control' name='reference' placeholder='Payment Reference' value='<?php echo !empty($sales_payment)?$sales_payment['reference']:"" ?>'><?php echo !empty($sales_payment)?$sales_payment['reference']:"" ?></textarea>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-brand" type="submit" ">Save</button>
                                <a href='sales_payments.php?id=<?php echo $_GET['id'] ?>' type="button" class="btn btn-default"  >Cancel</a>
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
            <!-- End Invoice Modal -->

    

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
                    case "pay_date":
                        str_error+="Please provide payment date. \n";
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


<?php
    if(!empty($sales_payment)):
?>
    $(document).ready(function(){
        $("#myModal").modal("show");
    });
<?php
    endif;
?>
</script>

<?php
    Modal();
    makeFoot();
?>