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
                            stkMaster.total_quantity_received as quantity_received,
                            stkMaster.total_cost as total_Cost
                            FROM stock_adj_master stkMaster
                            INNER JOIN adjustment_status adj
                            ON stkMaster.adj_status_id =  adj.adj_status_id
                            WHERE stkMaster.is_deleted = '0'
                        ");
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
                                      <a href='stock_adjustments.php' class='btn btn-success btn-flat'> Create New <span class='fa fa-plus'></span> </a>
                                    </div>
                                    <br/>
                                    <table id='ResultTable' class='table table-bordered table-striped'>
                                         <thead>
                                            <tr>
                                              <th class='text-center'>Stock ID</th>
                                              <th class='text-center'>Reason</th>
                                              <th class='text-center'>Quantity received</th>
                                              <th class='text-center'>Total cost</th>
                                           
                                          </thead>
                                              <tbody>
                                                <?php
                                                  while($row = $data->fetch(PDO::FETCH_ASSOC)):
                                                ?>
                                                  <tr>
                                                   <td class='text-center'> 
                                                                <a href='stock_adjustment.php?id=<?= $row['stock_id']?>'><img width="36" height="36" class="" src="uploads/so_id.png">SO<?php echo htmlspecialchars($row['stock_id'])?></a>
                                                    </td>
                                                    
                                                    <td><?php echo htmlspecialchars($row['reason'])?></td>
                                                    <td><?php echo htmlspecialchars($row['quantity_received'])?></td>
                                                    <td><?php 
                                                    $num = $row['total_Cost'];
                                                 
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

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({

        });
      });

   
</script>

<?php
    Modal();
    makeFoot();
?>