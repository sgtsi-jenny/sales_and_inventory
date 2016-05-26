<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    // if(!AllowUser(array(1))){
    //      redirect("index.php");
    // }

    //echo $_GET['id'];
    //die();

    $tab="1";
    if(!empty($_GET['tab']) && !is_numeric($_GET['tab']))
    {
        redirect("purchase_order_details.php".(!empty($employee)?'?id='.$employee['id']:''));
        die;
    }else
    {
        if(!empty($_GET['tab']))
        {
            if($_GET['tab'] >0 && $_GET['tab']<=9)
            {
                $tab=$_GET['tab'];
            }else
            {
                #invalid TAB
                redirect("purchase_order_details.php".(!empty($employee)?'?id='.$employee['id']:''));
            }
        }
    }
    
    if(empty($_GET['id']))
    {
        //Modal("No Account Selected");
        redirect("sales.php");
        die();
    }
    else{
        $po=$con->myQuery("SELECT
                            pm.po_master_id,
                            pm.supplier_id,
                            CONCAT(s.description,' (',s.name,')') AS supplier_name,
                            s.address AS supplier_address,
                            s.email AS supplier_email,
                            s.contact_number AS supplier_contact,
                            pm.bill_to,
                            pm.purchased_date,
                            ps.name AS po_status
                        FROM po_master pm
                        INNER JOIN suppliers s
                            ON s.supplier_id=pm.supplier_id
                        INNER JOIN po_status ps
                            ON ps.po_status_id=pm.po_status_id
                        WHERE pm.po_master_id=?
                        ",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        
        if(empty($po))
        {
            //Modal("Invalid sales Selected");
            redirect("purchases.php");
            die;
        }
    }
    makeHead("Purchase");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
<div class="content-wrapper">
    <section class="content-header" align="right">
        <a href='purchases.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Back</a>
    </section>
    <section class="content-header">
        <h1>
            <img src="uploads/summary_Oppurtunities.png" width="50" height="50" title="" alt="" /> 
            <?php echo htmlspecialchars($po['supplier_name']) ?> <!-- SUPPLIER NAME -->
        </h1>
    </section>
    <section class="content-header">
        <div class='row'>
            <div class='col-xs-12'>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>Address: </strong>
                <em><?php echo htmlspecialchars($po['supplier_address'])?></em> <!-- SUPPLIER'S ADDRESS -->
            </div>
            <div class='col-xs-12'>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>Email: </strong>
                <em><?php echo htmlspecialchars($po['supplier_email'])?></em> <!-- SUPPLIER'S EMAIL -->
            </div>
            <div class='col-xs-12'>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>Contact Number: </strong>
                <em><?php echo htmlspecialchars($po['supplier_contact'])?></em> <!-- SUPPLIER'S CONTACT NUMBER -->
            </div>
            <br>
            <div class='col-xs-12'>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>Bill To: </strong> 
                <em><?php //echo htmlspecialchars($sale['bill_to'])?></em> <!-- REQUESTOR'S ADDRESS -->
            </div>
            <div class='col-xs-12'>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>Purchased Date: </strong>
                <em><?php //echo htmlspecialchars($sale['date_issue'])?></em> <!--PURCHASED DATE-->
            </div>


        </div>
    </section>
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <div class='col-md-12'>
                <div class="nav-tabs-custom">
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