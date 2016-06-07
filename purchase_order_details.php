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
    //  var_dump($_GET['p_id']);
    // die();

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
        redirect("purchases.php");
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
            <img src="uploads/summary_organizations.png" width="50" height="50" title="" alt="" /> 
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
        </div>
        <br>
        <div class='row'>
            <!--<div class='col-xs-12'>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>Bill To: </strong> 
                <em><?php echo htmlspecialchars($po['bill_to'])?></em>--> <!-- REQUESTOR'S ADDRESS -->
            <!--</div>-->
            <div class='col-xs-12'>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>Purchased Date: </strong>
                <em><?php echo htmlspecialchars($po['purchased_date'])?></em> <!--PURCHASED DATE-->
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
                    <li <?php echo $tab=="1"?'class="active"':''?>><a href="" >Purchase Details</a>
                    </li>
                    <li> <a href="po_payments.php?id=<?php echo $_GET['id'] ?>">Payments</a>
               
                </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" >
                            <table id='ResultTable' class='table table-bordered table-striped'>
                                <thead>
                                    <tr>
                                        <th class='text-center'>id</th>
                                        <th class='text-center' style='min-width:200px'>Product Name</th>
                                        <th class='text-center'>Order Quantity</th>
                                        <th class='text-center'>Received Quantity</th>
                                        <th class='text-center'>Unit Cost</th>
                                        <th class='text-center'>Total Cost</th>                 
                                        <th>Action</th> 
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php                                              
                                        $po_details=$con->myQuery("SELECT
                                        po.product_id,
                                        po.Name,
                                        po.QuantityOrdered,
                                        qtyReceived.qty_received as 'QuantityReceived',
                                        po.UnitCost,
                                        po.TotalCost
                                        FROM
                                        (SELECT
                                            po_master.po_master_id,
                                            products.product_id,
                                            products.product_name AS `Name`,
                                            po_details.qty_ordered AS `QuantityOrdered`,
                                            po_details.unit_cost AS `UnitCost`,
                                            po_details.total_cost AS `TotalCost`
                                            FROM
                                            po_master
                                            INNER JOIN po_details ON po_master.po_master_id = po_details.po_master_id
                                            INNER JOIN suppliers ON po_master.supplier_id = suppliers.supplier_id
                                            INNER JOIN po_status ON po_master.po_status_id = po_status.po_status_id
                                            INNER JOIN products ON po_details.product_id = products.product_id
                                            WHERE po_master.po_master_id = ?
                                            ORDER BY
                                            products.product_id ASC) as po
                                            LEFT OUTER JOIN (SELECT 
                                                            po_master.po_master_id,
                                                            po_details.product_id,
                                                            sum(po_received.qty_received) as 'qty_received'
                                                            FROM
                                                            po_master
                                                            INNER JOIN po_received ON po_master.po_master_id = po_received.po_master_id
                                                            INNER JOIN po_details ON po_details.product_id = po_received.product_id AND po_master.po_master_id = po_details.po_master_id
                                                            WHERE
                                                            po_master.po_master_id=?
                                                            GROUP BY po_details.product_id) as qtyReceived 
                                            ON qtyReceived.product_id=po.product_id and qtyReceived.po_master_id=po.po_master_id
                                            ORDER BY po.product_id",array($_GET['id'],$_GET['id']))->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($po_details as $row):
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
                                            if ($row['QuantityOrdered'] > $row['QuantityReceived'])
                                            {
                                        ?>
                                         <td>

                                            <a class="btn btn-brand" href="purchase_order_details.php?id=<?php echo $_GET['id'];?>&p_id=<?php echo $row['product_id'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Receive&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>

                                            
                                        </td>   
                                    
                                        <?php
                                           }
                                           else
                                           {
                                        ?>
                                            <td></td>
                                        <?php
                                            }   
                                            endforeach;
                                        ?>
                                    </tr>
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
                    <h4 class="modal-title" id="myModalLabel">Received</h4>
                  </div>
                 
                      <div class="modal-body"> 
                        <form action='save_po_received.php' method='POST'>
                          
                            <input type='hidden' name='id' value='<?php echo !empty($po)?$po['po_master_id']:""?>'>    
                            
                            <input type='hidden' name='p_id' value='<?php echo $_GET['p_id']; ?>'>   
                          

                            <div class='form-group'>
                                <label> Quantity Received*</label>
                                <div>
                                   <input name="qtyReceived" type="text" class='form-control' placeholder="Enter Quantity Received"  required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label> Reference Number*</label>
                                <div>
                                   <input name="RefNo" type="text" class='form-control' placeholder="Enter Reference Number"  required>
                                </div>
                            </div>

                            <div class='form-group'>
                                <label> Remarks</label>
                                <div>
                                   <input name="remarks" type="text" class='form-control' placeholder="Enter Remarks"  >
                                </div>
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

<script type="text/javascript">
$(function () {
$('#ResultTable').DataTable();
});

<?php
    if(!empty($_GET['p_id'])):
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