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
        (SELECT address FROM customer_address c_add WHERE sm.ship_to=c_add.customer_add_id) AS ship_to,
        sm.terms
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
    
    
    makeHead("Sales",0,true);
?>

<?php
    // require_once("template/header.php");
    // require_once("template/sidebar.php");
?>
<div class='col-md-10 no-print' align='right'>
    <a href='sales_order_details.php?id=<?php echo $_GET['id'];?>' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Back</a>
    <button onclick='window.print()' class='btn btn-brand no-print'>Print &nbsp;<span class='fa fa-print'></span> </a> </button>  
    
</div>
<div class='page'>
    <div class="row">
         <h1 align="center" style="color:#24b798;">Sales Order</h1>
    </div>

    <div class='row'>
        <!-- <h2>
          <img src="uploads/summary_Oppurtunities.png"  width="50" height="50" title="Organization" alt="Organization" />
          <?php //echo htmlspecialchars($sale['customer']) ?>
        </h2> -->
    </div>
    <table>
        <tr>
            <td>
                <h2>
                  <?php echo htmlspecialchars($sale['customer']) ?>
                </h2>
            </td>
        </tr>
        <tr>
            <td style="width:150px">Order Number</td>
            <td>
                <?php echo htmlspecialchars('#'.$sale['sales_master_id'])?>
            </td>
        </tr>
        <tr>
            <td>Order creation date:</td>
            <td>
                <?php echo htmlspecialchars($sale['date_issue'])?>
            </td>
        </tr>
        <tr>
            <td>Order last updated date:</td>
            <td>
                <?php echo htmlspecialchars($sale['date_modified'])?>
            </td>
        </tr>
        <!-- <tr>
            <td>Order Status:</td>
            <td>
                 <?php echo htmlspecialchars($sale['status_name'])?>
            </td>
        </tr> -->
    </table>

    <br/>
    <div class="row">
        <div class='col-md-12'>
            <?php
            // $total_qty=0;
            ?>
            <table id='' class='table table-bordered table-striped'>
            <thead>
                <tr>
                    <th class='text-left' style='min-width:200px'>Product Name</th>
                    <th class='text-left'>Quantity</th>
                    <th class='text-right'>Price (Php)</th>
                    <th class='text-center'>Discount</th>
                    <!-- <th class='text-center'>Tax</th> -->
                    <th class='text-right'>Total (Php)</th>
                </tr>
            </thead>
            <tbody>
                            <?php  
                                $total_qty=0;
                                $t_cost=0;
                                $subtotal=0;  
                                $tax=0;                                        
                                $order_details=$con->myQuery("SELECT 
                                prod.product_name,
                                sd.quantity,
                                sd.unit_cost,
                                sd.discount,
                                sd.total_cost
                                FROM sales_master sm
                                INNER JOIN customers ON sm.customer_id=customers.customer_id
                                INNER JOIN sales_status ss ON sm.sales_status_id=ss.sales_status_id
                                INNER JOIN sales_details sd ON sm.sales_master_id=sd.sales_master_id
                                INNER JOIN products prod ON prod.product_id=sd.product_id
                                WHERE sm.sales_master_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($order_details as $row):

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
                                           elseif($key=='quantity'):
                                           $total_qty+= $row['quantity'];
                                           $t_cost+=$row['total_cost'];

                                        ?> 
                                            <td class='text-left'>
                                                <?php echo htmlspecialchars($row['quantity'])?></a>
                                            </td>
                                        <?php
                                            elseif($key=='total'):
                                        ?>  
                                            <td class='text-right'>
                                                <?php echo htmlspecialchars(number_format($row['total'],2))?></a>
                                            </td>
                                        <?php
                                            elseif($key=='total_cost'):
                                        ?>  
                                            <td class='text-right'>
                                                <?php echo htmlspecialchars(number_format($row['total_cost'],2))?></a>
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
                                // var_dump($total_qty);
                                $tax=$t_cost*.12;
                                $subtotal=$t_cost-$tax;
                                echo $t_cost;
                                echo $subtotal;
                            ?>
                            

            </tbody>
            </table>

           
           <!--  <?php
            //var_dump($total_qty);
            ?> -->
            <table align='right'>
            <tr>
                <td style='min-width:100px'>Total Units</td>
                <td align="right">
                    <?php echo htmlspecialchars($total_qty)?>
                </td>
            </tr>  
            <tr>
                <td>Subtotal (Php)</td>
                <td align="right">
                    <?php echo htmlspecialchars(number_format($subtotal,2))?>
                </td>

            </tr>
            <tr>
                <td>Plus VAT (12%)</td>
                <td align="right">
                    <?php echo htmlspecialchars(number_format($tax,2))?>
                </td>

            </tr>
            <tr>
                <td>Total (Php)</td>
                <td align="right">
                    <?php echo htmlspecialchars(number_format($t_cost,2))?>
                </td>

            </tr>           
            </table>


        </div>

    </div>
</div>
<?php
    makeFoot();
?>