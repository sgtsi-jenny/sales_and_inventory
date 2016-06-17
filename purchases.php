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

    $supplier=$con->myQuery("SELECT supplier_id, CONCAT(description,' (',name,')') as name FROM suppliers")->fetchAll(PDO::FETCH_ASSOC);
    
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
                                        <button type="button" class="btn btn-brand" data-toggle="modal" data-target="#myModal"> New Purchase Order <span class='fa fa-plus'></span></button>

                                        
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
                                                            (SELECT NAME FROM suppliers WHERE supplier_id=pm.supplier_id) AS supplier,
                                                            pm.purchased_date,
                                                            (SELECT NAME FROM po_status WHERE po_status_id=pm.po_status_id AND is_deleted=0) AS po_status,
                                                            (SELECT SUM(total_cost) FROM po_details WHERE po_master_id=pm.po_master_id AND is_deleted=0) AS total_cost,
                                                            (SELECT NAME FROM payment_status WHERE payment_status_id=pm.payment_status_id) AS payment_status,
                                                            DATE_FORMAT(date_modified,'%m/%d/%Y') as 'date_modified',
                                                            DATE_FORMAT(purchased_date,'%m/%d/%Y') as 'purchased_date'
                                                          FROM po_master  pm
                                                          WHERE is_deleted=0 and is_void =0
                                                          ORDER BY pm.po_master_id desc");
                                          
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
                                        <td class='text_center'><?php echo htmlspecialchars($row['date_modified']); ?></td>
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
    <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Select Supplier</h4>
                  </div>
                 
                      <div class="modal-body"> 
                        
                            <form method='GET' action="frm_purchase.php">
                               
                                <div class='form-group'>
                                    <div class ="row">
                                        <label class='col-md-3 control-label'> Select Supplier: * </label>
                                        <div class = "col-md-9">
                                            <select class='form-control ' id='supplier' name='supplier' data-placeholder="Select supplier" required>
                                                <?php
                                                    echo makeOptions($supplier,'Select Supplier')
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <div class ="row">
                                        <div class = "col-md-3">
                                            <label class='control-label'> Ship To: </label>
                                        </div>
                                        <div class = "col-md-9">
                                            <input type="text" class="form-control " id="ship_to" placeholder="Ship To" name='ship_to' value='' required >
                                        </div>
                                    </div>
                                </div>
                                <div class ="modal-footer ">
                                    <button type="submit" class="btn btn-brand" >Next</button>
                                    <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
                                </div> 
                              </form>
                        </div>

                      
                            
                        
                  </div>
                </div>
              </div>
            </div>
            <!-- End Modal -->
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