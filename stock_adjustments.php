<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    if(!AllowUser(array(1))){
         redirect("index.php");
    }
    $reason=$con->myQuery("SELECT adj_status_id, name FROM adjustment_status")->fetchAll(PDO::FETCH_ASSOC);
    $product=$con->myQuery("SELECT product_id, product_name FROM products")->fetchAll(PDO::FETCH_ASSOC);
    $data=$con->myQuery("SELECT stock_adjmaster_id FROM stock_adj_master WHERE is_deleted=0 AND stock_adjmaster_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);

    makeHead("Stock Adjustment");
?>
<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>

 <div class="content-wrapper">
     <section class="content-header">
          <h1 align="center" style="color:#24b798;">
          		Stock Adjustment
          </h1>
    </section>
     <section class="content">
     <!-- Main row -->
          <div class="row">
          	<div class='col-sm-12 col-md-12'>
          	<div class="box box-primary">
                <div class="box-body">
                
	            	<div class='form-group'>
		            	<div class = "row">
		            		<div class = 'col-md-6' >
			            		<div class = 'row'>
			            			<div class = 'col-md-10'>
				            			<?php
				            				if (!empty($_GET['id']))
				            					{
				            						?>
				            				<h4 class='control-label'> Stock adjusment no. </h4>
				            				<!-- may idadag dag ako here-->
				            			<?php
				            				}	
				            			?>
				            			

				            			<label class = 'control-label'>Date created: </label>
					            			<?php 
					            			echo date("m/d/Y");
					            			?>
				            			
				            			<br>
				            			<label class = 'control-label'>Issued by: 	</label>
				            					
				            		
				            			<?php
		                        			echo htmlspecialchars("{$_SESSION[WEBAPP]['user']['last_name']}, {$_SESSION[WEBAPP]['user']['first_name']} {$_SESSION[WEBAPP]['user']['middle_name']}")
		                      				?>

		                      				<br>
		                      				<br>
				            			<div class ='row'>
				            				<div class = 'col-md-2' >
				            					<h4 class='control-label'> Reason* </h4>
				            					
				            				</div>
				            				<div class = 'col-md-10'>
				            					<select class='form-control' name='adj_status_id' data-placeholder="Select Reason" 
						            				<?php echo!(empty($reason))?"data-selected='".$reason['adj_status_id']."'":NULL ?> required>
					                            	<?php
					                                	echo makeOptions($reason,'Select reason',NULL,'',!(empty($reason))?$reason['adj_status_id']:NULL)
					                            	?>
			                        			</select>
				            				</div>
				            				
				            			</div>
		            				</div>
			            		</div>
			            		

		            		</div>
		            		
		            		
	                        
		            	</div>
                        
		            			
                    </div>
                    <br>
                    <br>
	           <!-- <div class='panel-body'>
                                    <div class='col-md-12 text-right'>
                                        <div class='col-md-12 text-right'>
                                        <a href='#' class='btn btn-brand'> Create New <span class='fa fa-plus'></span> </a>
                                        </div>                                
                                    </div> 
                          </div>
                          -->
                <div class= "row">
            	<div class = "col-md-5">
					<div class="box box-primary">
		                <div class="box-body">
		                <form method="post">
		                	<div class='form-group'>
			                	<div class ="row">
			                		<div class = "col-md-4">
			                			<label class='control-label'> Select product:* </label>
			                		</div>
			                		<div class = "col-md-8">
			                			<select class='form-control' 
			                			 name='select_1' data-placeholder="Select product" 
				            				<?php echo!(empty($product))?"data-selected='".$product['product_id']."'":NULL ?> required>
			                            	<?php
			                                	echo makeOptions($product,'Select product',NULL,'',!(empty($product))?$product['product_id']:NULL)
			                            	?>
		                				</select>
			                		</div>
			                	</div>
                           </div>
                           <div class='form-group'>
                           		<div class ="row">
			                		<div class = "col-md-4">
			                			<label class='control-label'> Quantity received:* </label>
			                		</div>
			                		<div class = "col-md-8">
			                			<input type="text" class="form-control" id="product_code" placeholder="Quantity received" name='quantity_received' value='<?php echo !empty($data)?htmlspecialchars($data['stock_adjmaster_id']):''; ?>' required>
			                		</div>
			                	</div>
                           </div>
                           <div class='form-group'>
                           		<div class ="row">
			                		<div class = "col-md-4">
			                			<label class='control-label'> Stock on hand: </label>
			                		</div>
			                		<div class = "col-md-8">
			                			<input type="text" class="form-control " id="product_code" placeholder="Stock on hand" name='stock_onhand' value='<?php echo !empty($data)?htmlspecialchars($data['stock_adjmaster_id']):''; ?>' required readonly>
			                		</div>
			                	</div>
                           </div>
                           <div class='form-group'>
                           		<div class ="row">
			                		<div class = "col-md-4">
			                			<label class='control-label'> After: </label>
			                		</div>
			                		<div class = "col-md-8">
			                			<input type="text" class="form-control " id="product_code" placeholder="Stock on hand" name='after' value='<?php echo !empty($data)?htmlspecialchars($data['stock_adjmaster_id']):''; ?>' required readonly>
			                		</div>
			                	</div>
                           </div>

		                </form>
		                <section align = "right">
		                	<button type="button" class="btn btn-brand" onclick="AddToTable()">Add</button>
		                  	<button type="button" class="btn btn-default" onclick="AddToTable()">Cancel</button>
		                </section>
		                  
		                	
		                </div>
					</div>
            	</div>
            	<div class = "col-md-7">
					<div class="box box-primary">
		                <div class="box-body">
		                	 <table id='ResultTable' class='table table-bordered table-striped'>
		                	 		<thead>
	                                    <tr>
	                                      <th class='text-center'>Product ID</th>
	                                      <th class='text-center'>Product name</th>
	                                      <th class='text-center'>Quantity received</th>
	                                      <th class='text-center'>Stock </th>
	                                      <th class='text-center'>After </th>
	                                      <th class='text-center'>Action </th>
                                           
                                    </thead>
                                    <tbody id='table_container'>
					
									</tbody>
		                	 </table>
		                </div>
					</div>
            	</div>
            	
            </div>
                </div>
            </div>  

              
	            
            </div>
          </div>
     </section>
</div>
<script type="text/javascript">
	function AddToTable() {
		select_1_val=$("select[name='select_1']").val();
		select_1_text=$("select[name='select_1'] :selected").text()
		text_quantity=$("input[name='quantity_received']").val();
		text_stockOnhand = $("input[name='stock_onhand']").val();
		text_after = $("input[name='after']").val();


		/*
			See those group of letters up there^?
			They just get the M@#$%^&*# Values of the M%*!@#*!@# Form on the modal.
			More inputs?
			Just add them there.

			Also YOU should validate that shit.
			Make sure the data is not already int the table.
		*/


		input="<input type='hidden' name='select_1[]' value='"+select_1_val+"'> <input type='hidden' name='text_quantity[]' value='"+text_quantity+"'><input type='hidden' name='text_stockOnhand[]' value='"+text_stockOnhand+"'><input type='hidden' name='after[]' value='"+text_after+"'>" ;
		/*
			SEE THIS SHIT RIGHT HERE ^?
			Thats what gets sent for saving. 
			See the M*@#$%^&* square brackets? They allow the data to be passed as an array. That shit is important.
		*/

		$("#table_container").append("<tr><td>"+select_1_val+"</td><td>"+select_1_text+"</td><td>"+text_quantity+"</td><td>"+text_stockOnhand+"</td><td>"+text_after+"</td><td><button type='button' onclick='removeRow(this)' class='btn btn-danger fa fa-trash'></button></td></tr>");
		/*
			UPTOP ^?
			This is what adds the data to the table.

			that in the end? That deletes the whole row.
		*/

		
	}

	function removeRow(del_button) {
		// body...
		if(confirm('Remove this row?')){
		$(del_button).parent().parent().remove();
			
		}
		return false;
		/*
				^
			Get theat row out of here.;
		*/
	}
</script>
<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({

        });
      });

   
</script>

<?php
  
    makeFoot();
?>