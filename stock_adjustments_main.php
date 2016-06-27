<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    if(!AllowUser(array(1))){
         redirect("index.php");
    }
    $data=$con->myQuery("SELECT 
                            stkMaster.stock_adjMaster_id as stock_id,
                            adj.name as reason,
                            stkMaster.total_cost as total_Cost
                            FROM stock_adj_master stkMaster
                            INNER JOIN adjustment_status adj
                            ON stkMaster.adj_status_id =  adj.adj_status_id
                            WHERE stkMaster.is_deleted = '0'
                        ");
    $reason=$con->myQuery("SELECT adj_status_id, name FROM adjustment_status")->fetchAll(PDO::FETCH_ASSOC);
    makeHead("Stock Adjustments");

?>
<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
<div class="content-wrapper">
     <section class="content-header">
        <h1 align="center" style="color:#24b798;">
            Stock Adjustments Transactions
        </h1>
    </section>
    <section class="content">
            <div class="row">
                <div class='col-sm-12 col-md-12'>
                    <div class="box box-primary">
                        <div class="box-body">
                        <?php
                            alert();
                        ?>
                            <div class="row">

                                <div class="col-sm-12">
                                    <div class='col-ms-12 text-right'>
                                      <div class='col-md-12 text-right'>
                                          <button type="button" class="btn btn-brand" data-toggle="modal" data-target="#newStockReason">Create new <span class='fa fa-plus'></span> </button>
                                        </div>  
                                    </div>
                                    <br/>
                                    <br/>
                                    <table id='ResultTable' class='table table-bordered table-striped'>
                                         <thead>
                                            <tr>
                                              <th class='text-center'>Stock ID</th>
                                              <th class='text-center'>Reason</th>
                                         
                                              <th class='text-right'>Total cost</th>
                                           
                                          </thead>
                                              <tbody>
                                                <?php
                                                  while($row = $data->fetch(PDO::FETCH_ASSOC)):
                                                ?>
                                                  <tr>
                                                   <td class='text-center'> 
                                                                <a href='view_stock_adjustments.php?id=<?= $row['stock_id']?>'><img width="36" height="36" class="" src="uploads/so_id.png">SA<?php echo htmlspecialchars($row['stock_id'])?></a>
                                                    </td>
                                                    
                                                    <td class='text-center'><?php echo htmlspecialchars($row['reason'])?></td>
                                                 
                                                    <td class='text-right'><?php 
                                                    $num = number_format($row['total_Cost'],2);
                                                 
                                                    echo htmlspecialchars($num); 
                                                    ?></td>
                                                  </tr>
                                                <?php
                                                  endwhile;
                                                ?>
                                               </tbody>
                                    </table>
                                </div> 
                            </div>     
                        </div>
                    </div>
                </div>    
            </div>
    </section>
</div>
<div class="modal fade" id="newStockReason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add New Stock Adjustment</h4>
                  </div>
                 
                      <div class="modal-body"> 
                        <form action='stock_adjustments.php' method='GET' >
                           <!--  -->
                            <!-- <?php
                                // var_dump($sale['t_qty']);
                            ?> -->
                            
                             <div class="form-group">
                                <label>Select Reason</label>
                                 <select class='form-control' id='adj_status_id'  name='adj_status_id' data-placeholder="Select reason"
                                  required>

                                      <?php
                                          // echo makeOptions($customer,'Select Customer')
                                          echo makeOptions($reason,'Select reason',NULL,'',!(empty($reason))?$reason['adj_status_id']:NULL)
                                      ?>

                                  </select>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-brand" type="submit"  >Next</button>
                                <button type="button" class="btn btn-default"  data-dismiss="modal"> Cancel </button>
                            </div>
                        </form>
                  </div>
                </div>
              </div>
            </div>
<script type="text/javascript">
    function validateReason() {
      selectedReason = $("select[name='adj_status_id'] ").val();
      if (selectedReason == '' ) {
        alert('Please select a reason');
        return  false;
    }
  }
</script>

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