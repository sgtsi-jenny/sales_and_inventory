<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    // if(!AllowUser(array(1))){
    //      redirect("s");
    // }
    $tab="2";
  
    if(empty($_GET['id'])){
        //Modal("No Account Selected");
        redirect("po_payments.php");
        die();
    }
    // else{
    //     $data=$con->myQuery("SELECT customer_id,customer_name,tin,description,fax,telephone_number,mobile_number,DATE_FORMAT(customers.birth_date,'%m/%d/%Y') as 'birth_date',website,email FROM customers WHERE customer_id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
    //     if(empty($data)){
    //         Modal("Invalid Record Selected");
    //         redirect("purchases.php");
    //         die;
    //     }
    //}
    $po_payments=$con->myQuery("SELECT
                                po_payment_id,
                                Format(amount,2) as 'amount',
                                DATE_FORMAT(date_paid,'%m/%d/%Y') as 'date_paid',     
                                remarks
                                FROM po_payments WHERE is_void='0' and po_master_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);

    $remaining_bal=$con->myQuery("SELECT
                                  pod.po_master_id,
                                  pod.total_cost,
                                  if (remaining_bal.amount > '0',sum(remaining_bal.amount),'0') as 'amount',
                                  (pod.total_cost - if (remaining_bal.amount > '0',sum(remaining_bal.amount),'0')) as 'remaining_balance'
                                FROM
                                (SELECT po_details.po_master_id,
                                        Sum(po_details.total_cost) AS total_cost
                                        FROM
                                        po_details
                                        WHERE
                                        po_details.po_master_id AND
                                        po_details.is_void = 0
                                        GROUP BY po_details.po_master_id) as pod
                                LEFT OUTER JOIN(SELECT po_payments.po_master_id,
                                                      po_payments.amount
                                                      FROM
                                                      po_payments
                                                      WHERE 
                                                      is_void = 0
                                                      ) as remaining_bal on remaining_bal.po_master_id = pod.po_master_id
                                WHERE pod.po_master_id = ?
                                GROUP BY pod.po_master_id
                                ORDER BY pod.po_master_id",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);

                          

    makeHead("Payments");

?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <section class="content-header">
              <h1>
              <i class="fa fa-user" style="font-size:48px;text-shadow:2px 2px 4px #000000;"></i>
              &nbsp;&nbsp;Purchases
              </h1>
        </section>
         <section class="content-header">

        <br/>
          <a href='purchases.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> List of All Purchases</a>
          <!--<a href='org_opp.php?id=<?php echo $opp['org_id'] ?>' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Back to My Opportunity</a>-->
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
                   
                    <li> <a href="purchase_order_details.php?id=<?php echo $_GET['id'] ?>">Purchase Details</a>
                    </li>
                    <li <?php echo $tab=="2"?'class="active"':''?>> <a href="">Payments</a>
                    </li>
                    
                    
                </ul>
                <form class='form-horizontal' action='save_po_payments.php' method="POST" >
                <?php
                  $po_payments_status=$con->myQuery("SELECT
                                      po_master.po_master_id,
                                      payment_status.payment_status_id
                                      FROM
                                      po_master
                                      INNER JOIN payment_status ON po_master.payment_status_id = payment_status.payment_status_id WHERE po_master_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
                  $pstatus = $po_payments_status['payment_status_id'];
                  if ($pstatus == "1"){
                ?>
                
                <div class="tab-content">
                  <div class="active tab-pane" >
                     <div class='panel-body'>
                          <div class='col-md-12'>
                            <input type='hidden' name='id' value='<?php echo $_GET['id']?>'>

                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Remaining Balance </label>
                                <div class='col-sm-12 col-md-6'>
                                   <input name="remain_bal" type="text" class='form-control' value='<?php echo $remaining_bal['remaining_balance']; ?>' readonly>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Amount *</label>
                                <div class='col-sm-12 col-md-6'>
                                   <input name="amount" type="text" class='form-control' placeholder="Enter Amount" >
                                </div>
                            </div>
                            
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Date Paid*</label>
                                <div class='col-sm-12 col-md-6'>
                                   <?php
                                        $dp="";
                                         if(!empty($account)){
                                            $dp=$account['dp'];
                                            if($dp=="00000000"){
                                                $dp="";
                                            }
                                             else
                                            {
                                                $dp=inputmask_format_date($dp);
                                                //echo $dob;
                                            }
                                        }                                         
                                    ?>

                                        <input type='text' class='form-control date_picker' name='dp'   required>

                                </div>
                            </div>
                           <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Remarks *</label>
                                <div class='col-sm-12 col-md-6'>
                                   <input name="remarks" type="text" class='form-control' placeholder="Enter Remarks" >
                                </div>
                            </div>
                            <div class="form-group">
                            <div class="col-sm-7 col-md-offset-5 text-center">
                              <button type='submit' class='btn btn-success brand-bg'>Add </button>
                                
                              </div>
                          </div>                    
                          </div> 
                      </div>
                      
                  </div>
                </div>
                <?php
                  }
                  elseif ($pstatus == "2") {
                ?>
                  <div class="tab-content">
                  <div class="active tab-pane" >
                     <div class='panel-body'>
                          <div class='col-md-12'>
                            <input type='hidden' name='id' value='<?php echo $_GET['id']?>'>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Amount *</label>
                                <div class='col-sm-12 col-md-6'>
                                   <input name="amount" type="text" class='form-control' placeholder="Enter Amount" readonly >
                                </div>
                            </div>
                            
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Date Paid*</label>
                                <div class='col-sm-12 col-md-6'>
                                   <?php
                                        $dp="";
                                         if(!empty($account)){
                                            $dp=$account['dp'];
                                            if($dp=="00000000"){
                                                $dp="";
                                            }
                                             else
                                            {
                                                $dp=inputmask_format_date($dp);
                                                //echo $dob;
                                            }
                                        }                                         
                                    ?>

                                        <input type='text' class='form-control date_picker' name='dp'   required readonly>

                                </div>
                            </div>
                           <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Remarks *</label>
                                <div class='col-sm-12 col-md-6'>
                                   <input name="remarks" type="text" class='form-control' placeholder="Enter Remarks" readonly >
                                </div>
                            </div>
                            <div class="form-group">
                            <div class="col-sm-7 col-md-offset-5 text-center">
                              <button type='submit' class='btn btn-success brand-bg' disabled>Add </button>
                                
                              </div>
                          </div>                    
                          </div> 
                      </div>
                      
                  </div>
                </div>
                <?php
                  }
                ?>
                </form>
                 <?php
                  Alert();
                 ?>
                           
                  <h2>List of Payments</h2>
                   <br>
                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Payment ID</th>
                                                <th class='text-center'>Amount</th>
                                                <th class='text-center'>Date Paid</th>
                                                <th class='text-center'>Remarks</th>
                                                <th class='text-center' style='min-width:40px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                                            <?php
                                              foreach($po_payments as $row):
                                            ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['po_payment_id']) ?></td>
                                                    <td><?php echo htmlspecialchars($row['amount']) ?></td>
                                                    <td><?php echo htmlspecialchars($row['date_paid']) ?></td>
                                                    <td><?php echo htmlspecialchars($row['remarks']) ?></td>
                                                    
                                                    <td align="center">
                                                        <a href='po_void_payment.php?id=<?php echo $_GET['id']; ?>&pay_id=<?php echo $row['po_payment_id'] ?>' class='btn btn-default' onclick='return confirm("Are you do you want to void this PO Payment?")'> Void </a>

                                                       
                                                    </td>
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
<?php 
  if(!empty($eve)):
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
  if(!empty($to_do)):
?>
<script type="text/javascript">
  $(function(){
    $('#collapseForm2').collapse({
      toggle: true
    })    
  });
</script>
<?php
  endif;
?>
<script type="text/javascript">
  $(function(){
   $('#collapseForm').on('show.bs.collapse', function () {
    $('#collapseForm2').collapse('hide')
    });

   $('#collapseForm2').on('show.bs.collapse', function () {
      $('#collapseForm').collapse('hide')
    });

  });
</script>

<?php
    Modal();
    makeFoot();
?>