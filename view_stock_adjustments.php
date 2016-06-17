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
						p.current_quantity,SP.unit_cost as u_cost 
						FROM stock_adj_master StM  
						INNER JOIN stock_adj_details StD  on StM.stock_adjmaster_id = StD.stock_adjmaster_id
						INNER JOIN adjustment_status aStat on aStat.adj_status_id = StM.adj_status_id
						INNER JOIN supplier_products SP on StD.product_id = SP.product_id
						INNER JOIN products p on p.product_id =StD.product_id
						WHERE StM.stock_adjmaster_id = ?
                        ",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);

    	if(empty($sa))
        {
            //Modal("Invalid sales Selected");
            redirect("stock_adjustments_main.php");
            die;
        }
    }

      makeHead("stock adjusments");
?>


<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
<div class="content-wrapper">
<form action='stock_adjustments.php' method='GET'>
		<section class="content-header" align="right">
		        <a href='stock_adjustments_main.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Back</a>
		        <a href='stock_adjustments.php' class='btn btn-brand'>Revert stock adjustment &nbsp;<span class='fa  fa-undo'></span> </a>
		       <a href='#' class='btn btn-brand'> Preview/Print &nbsp;<span class='fa fa-print'></span> </a>
		    </section>
		   <section class="content-header">

		        <h1>
		            <img src="uploads/stock_control.png" width="50" height="50" title="" alt="" /> 
		         	Stock adjustment id: #<?php echo $_GET['id'] ?>
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
													<em><?php echo htmlspecialchars($sa['reason']) ?></em>
											</div>	
											<div class = "col-md-4">
													<strong> Note: </strong>
													<textarea  name='notes' rows = '4' cols="5" class='form-control' readonly> <?php echo htmlspecialchars($sa['notes']) ?></textarea>
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
	                          							<th class='text-center'>After</th>
	                          						</thead>
	                          						<tbody>

	                          						</tbody>
                          					</table>
											</div>
			                            	
			                            </div>
		                         	</div>
		                    	</div>
		    				</div>
    	
    		</div>
    </section>
	
</form>
	
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