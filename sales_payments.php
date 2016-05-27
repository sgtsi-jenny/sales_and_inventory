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
    
    if(empty($_GET['id'])){
        //Modal("No Account Selected");
        redirect("sales.php");
        die();
    }
    else{
        $sale=$con->myQuery("SELECT 
        sm.sales_master_id,
        customers.customer_name AS customer,
        customers.email,
        customers.fax,
        customers.mobile_number,
        customers.telephone_number,
        prod.product_name,
        ss.sales_status_id,
        ss.name AS status_name,
        DATE_FORMAT(sm.date_issue,'%m/%d/%Y') as date_issue,
        sd.quantity,
        prod.current_quantity AS available,
        sd.unit_cost,
        sd.discount,
        sd.tax,
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

    $status=
    
    makeHead("Sales");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        
        <section class="content-header" align="right">
          <a href='sales.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Back</a>
        </section>
        <section class="content-header">
                                      <h1>
                                      <img src="uploads/summary_Oppurtunities.png" width="50" height="50" title="Organization" alt="Organization" />
                                      <?php echo htmlspecialchars($sale['customer']) ?>
                                      </h1>
        </section>
        <section class="content-header">
                <div class='row'>
                            <div class='col-xs-12'>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>Order Number: </strong>
                                <?php echo htmlspecialchars('#'.$sale['sales_master_id'])?>
                            </div>
                            <div class='col-xs-12'>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>Bill To: </strong>
                                <em><?php echo htmlspecialchars($sale['bill_to'])?></em>
                            </div>
                            <div class='col-xs-12'>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>Ship To: </strong>
                                <em><?php echo htmlspecialchars($sale['ship_to'])?></em>
                            </div>
                            <div class='col-xs-12'>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>Issue Date: </strong>
                                <em><?php echo htmlspecialchars($sale['date_issue'])?></em>
                            </div>
                            <div class='col-xs-12'>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>Email: </strong>
                                <em><?php echo htmlspecialchars($sale['email'])?></em>
                            </div>
                            <div class='col-xs-12'>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>Fax: </strong>
                                <em><?php echo htmlspecialchars($sale['fax'])?></em>
                            </div>
                            <div class='col-xs-12'>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>Mobile Number: </strong>
                                <em><?php echo htmlspecialchars($sale['mobile_number'])?></em>
                            </div>
                            <div class='col-xs-12'>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>Telephone Number: </strong>
                                <em><?php echo htmlspecialchars($sale['telephone_number'])?></em>
                            </div>
                            <div class='col-xs-12'>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>Assigned To: </strong>
                                <em>
                                    <?php
                                            echo htmlspecialchars("{$_SESSION[WEBAPP]['user']['last_name']}, {$_SESSION[WEBAPP]['user']['first_name']} {$_SESSION[WEBAPP]['user']['middle_name']}")
                                            ?>
                                </em>
                            </div>
                            <div class='col-xs-12'>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <strong>Order Status: </strong>
                                <?php echo htmlspecialchars($sale['status_name'])?>
                            </div>
                            <?php
                                if($sale['sales_status_id']==1){
                            ?>
                            <div class='col-xs-12'>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <a href='sales.php' class='btn btn-default'> Allocate</a>
                            </div>
                            <?php
                                }
                                elseif($sale['sales_status_id']==2){
                            ?>
                            <div class='col-xs-12'>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <a href='sales.php' class='btn btn-default'> Invoice</a>
                            </div>
                            <?php
                                }
                                elseif($sale['sales_status_id']==3){
                            ?>
                            <div class='col-xs-12'>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <a href='sales.php' class='btn btn-default'> Ship</a>
                            </div>
                            <?php
                                }
                            ?>

                            <!-- <div class='col-xs-12'>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                  <a href='sales.php' class='btn btn-default'> Invoice</a>
                            </div> -->

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
                    <li <?php echo $tab=="2"?'class="active"':''?>><a>Payments</a>
                    </li>
                    
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
                                                (SELECT SUM(sd.total_cost) FROM sales_details sd WHERE sd.sales_master_id=sm.sales_master_id) AS total
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
</script>

<?php
    Modal();
    makeFoot();
?>