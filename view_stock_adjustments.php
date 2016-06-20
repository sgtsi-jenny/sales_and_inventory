<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }


     if(empty($_GET['id']))
    {
        //Modal("No Account Selected");
        redirect("stock_adjustments_main.php");
       
    }else{
    	$sa = $con->myQuery("SELECT StM.stock_adjmaster_id as SAID, StD.quantity_received as q_Rec ,  aStat.`name` as reason,
						aStat.adj_status_id as status_ID, StM.notes as notes,
						p.product_id as prodID, p.product_name as prodName , 
						p.current_quantity as c_Quan,SP.unit_cost as u_cost 
						FROM stock_adj_master StM  
						INNER JOIN stock_adj_details StD  on StM.stock_adjmaster_id = StD.stock_adjmaster_id
						INNER JOIN adjustment_status aStat on aStat.adj_status_id = StM.adj_status_id
						INNER JOIN supplier_products SP on StD.product_id = SP.product_id
						INNER JOIN products p on p.product_id =StD.product_id
						WHERE StM.stock_adjmaster_id = ?
                        ",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);

    	if(empty($sa))
        {
            //Modal("Invalid sales Selected");
            redirect("stock_adjustments_main.php");
            die;
        }
    }

    $get_sa = $con->myQuery("SELECT
						stock_adj_master.stock_adjmaster_id,
						adjustment_status.name as reason,
						stock_adj_master.notes
						FROM
						stock_adj_master
						INNER JOIN adjustment_status ON stock_adj_master.adj_status_id = adjustment_status.adj_status_id
						WHERE
						stock_adj_master.stock_adjmaster_id =?
                        ",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);

    $reason=$con->myQuery("SELECT adj_status_id, name FROM adjustment_status")->fetchAll(PDO::FETCH_ASSOC);

      makeHead("stock adjusments");
?>


<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
<div class="content-wrapper">

		<section class="content-header" align="right">
		        <a href='stock_adjustments_main.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Back</a>
		        <button type="button" class="btn btn-brand" data-toggle="modal" data-target="#newStockReason">Revert stock adjustment <span class='fa fa-plus'></span> </button>
		      
		       <a href='#' class='btn btn-brand'> Preview/Print &nbsp;<span class='fa fa-print'></span> </a>
		    </section>
		    
		   <section class="content-header">

		        <h1>
		            <img src="uploads/stock_control.png" width="50" height="50" title="" alt="" /> 
		         	Stock adjustment id: SA#<?php echo $_GET['id'];?>
		        </h1>	
		    </section>
		    <section class="content-header">
		    <div class='col-md-12'>
		                    <div class="box box-primary">
		                        <div class="box-body">
		                            <div class='panel-body'>
		                        		<div class = "row">
											<div class = "col-md-12">
													<strong> Reason: </strong>
													<em><?php echo htmlspecialchars($get_sa['reason']) ?></em>
											</div>	
											<div class = "col-md-4">
													<strong> Note: </strong>
													<textarea style="resize:none;" name='notes' rows = '6' cols="5" class='form-control' readonly> <?php echo htmlspecialchars($get_sa['notes']) ?></textarea>
											</div>	
			                            </div>
			                            <br/>
			                            <br/>
			                            <div class = "row"> 
			                             	<div class="col-sm-12">
			                             		<table id='ResultTable' class='table table-bordered table-striped'>
	                          						<thead>
	                          							<th class='text-center'>Product id</th>
	                          							<th class='text-center'>Product name</th>
	                          							<th class='text-center'>Quantity</th>
	                          							<th class='text-center'>Unit cost</th>
	                          							<th class='text-center'>Stock on hand</th>
	                          							
	                          						</thead>
	                          						<tbody>
		                          						<?php 
		                          						foreach ($sa as $row):
		                          							
		                          						?>
		                          						<tr>
		                          							<td><?php echo htmlspecialchars($row['prodID'])?></td>
		                          							<td><?php echo htmlspecialchars($row['prodName'])?></td>
		                          							<td><?php echo htmlspecialchars($row['q_Rec'])?></td>
		                          							<td><?php echo htmlspecialchars(number_format($row['u_cost']))?></td>
		                          							<td><?php echo htmlspecialchars($row['c_Quan'])?></td>
		                          							
		                          						</tr>
		                          						<?php
		                          						endforeach;
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
	<div class="modal fade" id="newStockReason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Revert Stock Adjustment</h4>
                  </div>
                 
                      <div class="modal-body"> 
                        
                           <!--  -->
                            <!-- <?php
                                // var_dump($sale['t_qty']);
                            ?> -->
                            <form action='stock_adjustments.php?id=<?php echo $_GET['id'];?>'  method='POST' >
                             <div class="form-group">
                                <label>Select Reason</label>
                                 <select class='form-control' id='adj_status_id'  name='adj_status_id' data-placeholder="Select reason"
                                  required>
                                      <?php
                                          // echo makeOptions($customer,'Select Customer')
                                          echo makeOptions($reason,'Select reason',NULL,'',!(empty($reason))?$reason['adj_status_id']:NULL)
                                      ?>
                                  </select>
                                  <!-- <input type="hidden" name="reason" > -->
                            </div>
                            <!-- <?php 
                            	// var_dump($_);
                            	// die;
                            ?> -->
                            <div class="modal-footer">
                                <button class="btn btn-brand" type="submit"  >Next</button>
                                <button type="button" class="btn btn-default"  data-dismiss="modal"> Cancel </button>
                            </div>
                			</form>
                  </div>
                </div>
              </div>
            </div>

	
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