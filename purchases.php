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
                                        <a href='frm_purchase.php' class='btn btn-brand'> New Purchase Order <span class='fa fa-plus'></span> </a>
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
                                        <th class='text-center'>Total Amount</th>
                                        <th class='text-center'>Payment Status</th>
                                        <th class='text-center'>Date Modified</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $po=$con->myQuery("SELECT
                                                            pm.po_master_id,
                                                            pm.po_number,
                                                            (SELECT NAME FROM suppliers WHERE supplier_id=pm.supplier_id) AS supplier,
                                                            pm.purchased_date,
                                                            (SELECT NAME FROM po_status WHERE po_status_id=pm.po_status_id AND is_deleted=0) AS po_status,
                                                            (SELECT SUM(total_cost) FROM po_details WHERE po_master_id=pm.po_master_id AND is_deleted=0) AS total_cost,
                                                            (SELECT NAME FROM payment_status WHERE payment_status_id=pm.payment_status_id) AS payment_status
                                                          FROM po_master  pm
                                                          WHERE is_deleted=0");
                                          
                                        while($row = $po->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <tr>
                                        <td class='text-center'> 
                                            <a href='purchase_order_details.php?id=<?php echo $row['po_master_id']; ?>'><img width="36" height="36" class="" src="uploads/so_id.png">PO<?php echo htmlspecialchars($row['po_master_id'])?></a>
                                        </td>
                                        <td class='text_center'><?php echo htmlspecialchars($row['supplier']); ?></td>
                                        <td class='text_center'><?php echo htmlspecialchars($row['purchased_date']); ?></td>
                                        <td class='text_center'><?php echo htmlspecialchars($row['po_status']); ?></td>
                                        <td class='text_center pull-right'><?php echo "PHP ".number_format(($row['total_cost']),2,'.',','); ?></td>
                                        <td class='text_center'><?php echo htmlspecialchars($row['payment_status']); ?></td>
                                        <td class='text_center'>#</td>
                                    </tr>
                                    <?php
                                        endwhile;
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
<?php
    Modal();
    makeFoot();
?>