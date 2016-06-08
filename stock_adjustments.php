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
    $product=$con->myQuery("SELECT products.product_id as productID, product_name,current_quantity, unit_cost
    	FROM products 
    	INNER JOIN supplier_products ON products.product_id = supplier_products.product_id")->fetchAll(PDO::FETCH_ASSOC);
   // $data=$con->myQuery("SELECT stock_adjmaster_id FROM stock_adj_master WHERE is_deleted=0 AND stock_adjmaster_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);

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
                
                <form method="post" action="save_stock_adjustment.php">

                
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
				            				<input type='hidden' name='stock_adjmaster_id' value='<?php echo !empty($supplier)?$supplier['supplier_id']:""?>'>
				            				<div class = 'col-md-2' >
				            					<h4 class='control-label'> Reason* </h4>
				            					
				            				</div>
				            				<div class = 'col-md-8'>
				            					<!-- <select class='form-control' id='adj_status_id' name='adj_status_id' onchange='getReason()'> data-placeholder="Select Reason" 
						            				<?php echo!(empty($reason))?"data-selected='".$reason['adj_status_id']."'":NULL ?> required>
					                            	<?php
					                                	echo makeOptions($reason,'Select reason',NULL,'',!(empty($reason))?$reason['adj_status_id']:NULL)
					                            	?>
			                        			</select> -->
			                        			<select class='form-control' id='adj_status_id' onchange='compute()' name='adj_status_id' data-placeholder="Select reason" 
			                        			<?php 
			                        			echo!(empty($data))?"data-selected='".$data['adj_status_id']."'":NULL 
			                        			?>
			                        			style='width:100%'  >

                                                <option value=''>Select Reason</option>
                                                <?php
                                                    foreach ($reason as $key => $row):
                                                ?>
                                                    <option data-price='<?php echo $row['selling_price'] ?>' placeholder="Select reason" value='<?php echo $row['adj_status_id']?>' <?php echo (!empty($data)?'selected':'') ?> ><?php echo $row['name']?></option>                                                    
                                                <?php
                                                    endforeach;
                                                ?>
                                                <!-- <input type='hidden' id='reason_name2' name='reason_name' value=''> -->
                                            	</select>
				            				</div>
				            				
				            			</div>
				            			<div class ='row'>
				            				<div class = 'col-md-2' >
				            					<h4 class='control-label'> Notes* </h4>
				            					
				            				</div>
				            				<div class = 'col-md-8'>
				            					<textarea name='notes' rows = '4' cols="5" class='form-control'></textarea>
				            				</div>
				            				
				            			</div>
				            			<div class = "" >

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
            	<div class = "col-md-4">
					<div class="box box-primary">
		                <div class="box-body">
		                
		                	<div class='form-group'>
			                	<div class ="row">
			                		<div class = "col-md-4">
			                			<label class='control-label'> Select product:* </label>
			                		</div>
			                		<div class = "col-md-8">
			                			<select class='form-control' id='select_1' onchange='getStockOnHand()' name='select_1' data-placeholder="Select a product" <?php echo!(empty($data))?"data-selected='".$data['productID']."'":NULL ?>style='width:100%'  >
                                                <option>Select Product</option>
                                                <?php
                                                    foreach ($product as $key => $row):
                                                ?>
                                                    <option data-qty='<?php echo $row['current_quantity'] ?>' data-cost= '<?php echo $row['unit_cost'] ?>' placeholder="Select product" value='<?php echo $row['productID']?>' <?php echo (!empty($data) && $row['productID']==$data['productID']?'selected':'') ?> ><?php echo $row['product_name']?></option>                                                    
                                                <?php
                                                    endforeach;
                                                ?>
                                                <input type='hidden' id='prod_name2' name='prod_name' value=''>
                                            </select>
			                		</div>
			                	</div>
                           </div>
                           <div class='form-group'>
                           		<div class ="row">
			                		<div class = "col-md-4">
			                			<label class='control-label'> Quantity:* </label>
			                		</div>
			                		<div class = "col-md-8">
			                			<input type="text" class="form-control" id="quantity_received" placeholder="Quantity received" onblur='compute()' name='quantity_received' value='<?php echo !empty($data)?htmlspecialchars($data['stock_adjmaster_id']):''; ?>' >
			                		</div>
			                	</div>
                           </div>
                            <div class='form-group'>
                           		<div class ="row">
			                		<div class = "col-md-4">
			                			<label class='control-label'> Unit Cost: </label>
			                		</div>
			                		<div class = "col-md-8">
			                			<input type="text" class="form-control " id="unit_cost" placeholder="Unit cost" name='unit_cost' value='<?php 
			                			echo !empty($data)?number_format($data['stock_adjmaster_id']):'';
			                			/*	if(!empty($data))
			                				{
			                					 $x = $row['unit_cost'];
                                                    $pos = strlen($x) - 2;
                                                    $dec = substr($x, 0,$pos).'.'.substr($x, strlen($x) -2);
                                                    echo htmlspecialchars(number_format($dec , 2));
			                				}
			                				else
			                				{echo "";}
			                				*/	
			                					?>'  

			                			readonly>
			                		</div>
			                	</div>
                           </div>
                           <div class='form-group'>
                           		<div class ="row">
			                		<div class = "col-md-4">
			                			<label class='control-label'> Stock on hand: </label>
			                		</div>
			                		<div class = "col-md-8">
			                			<input type="text" class="form-control " id="current_quantity" placeholder="Stock on hand" name='current_quantity' value='<?php echo !empty($data)?htmlspecialchars($data['current_quantity']):''; ?>'  readonly>
			                		</div>
			                	</div>
                           </div>
                           <div class='form-group'>
                           		<div class ="row">
			                		<div class = "col-md-4">
			                			<label class='control-label'> After: </label>
			                		</div>
			                		<div class = "col-md-8">
			                			<input type="text" class="form-control " id="stock_after" placeholder="Stock after" name='stock_after'  readonly>
			                		</div>
			                	</div>
                           </div>

		                
		                <section align = "right">
		                <!--	<button type="button" class="btn btn-brand" id="addRow" >Add</button>	-->
		                	<button type="button" class="btn btn-brand" onclick="AddToTable()" >Add</button>
		                  	<button type="button" class="btn btn-default" onclick="AddToTable()">Cancel</button>
		                </section>
		                  
		                	
		                </div>
					</div>
            	</div>
            	<div class = "col-md-8">
					<div class="box box-primary">
		                <div class="box-body">
		                	 <table id = "tblMe" class='table table-bordered table-striped'>
		                	 		<thead>
	                                    <tr>
	                                      <th class='text-center'>Product ID</th>
	                                      <th class='text-center'>Product name</th>
	                                      <th class='text-center'>Quantity received</th>
	                                      <th class='text-center'>Unit cost</th>
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
            		<div class = "row">
            				<div class = "col-md-12">
            					<section align = "right">
		                            <button type='submit' class='btn btn-brand'>Save </button>
		                            <button type="reset" class="btn btn-default" >Cancel</button> 
		                        </section>
            				</div>

            		</div>
            </form>
                </div>
            </div>  

              
	            
            </div>
          </div>
     </section>
</div>
<script type="text/javascript">
	$(document).ready(function() {
    var t = $('#ResultTable').DataTable();
    	
    	//some validations like empty textboxes...



    	/*
		select_1_val=$("select[name='select_1']").val();
		select_1_text=$("select[name='select_1'] :selected").text()
		text_quantity=$("input[name='quantity_received']").val();
		text_stockOnhand = $("input[name='stock_onhand']").val();
		text_after = $("input[name='after']").val();
		*/
		$('#addRow').on( 'click', function () {
        t.row.add( [
            select_1_val=$("select[name='select_1']").val() ,
			select_1_text=$("select[name='select_1'] :selected").text() , 
			text_quantity=parseInt($("input[name='quantity_received']").val()),
			unit_cost=parseInt($("input[name='unit_cost']").val()),
			text_stockOnhand = parseInt($("input[name='current_quantity']").val()),
			text_after = parseInt($("input[name='stock_after']").val()),
			buttons = "<button type='button' onclick='' class='btn btn-primary fa fa-edit'></button><button type='button' onclick='removeRow(this)' class='btn btn-danger fa fa-trash'></button>" 
        ] ).draw( false );
 
        
    } );
		//$('#addRow').click();
} );
	function AddToTable() {
        select_1_val=$("select[name='select_1']	").val();
        select_1_text=$("select[name='select_1'] :selected").text()
        quantity=$("input[name='quantity_received']").val();
        unit_cost=$("input[name='unit_cost']").val(), 
        stock_onhand = $("input[name='current_quantity']").val();
        after = $("input[name='stock_after']").val();
        prod_name = $("input[name='prod_name']").val();
        input="<input type='hidden' name='select_id[]' value='"+select_1_val+"'> <input type='hidden' name='quantity_received[]' value='"+quantity+"'><input type='hidden' name='current_quantity[]' value='"+unit_cost+"'><input type='hidden' name='current_quantity[]' value='"+stock_onhand+"'><input type='hidden' name='stock_after[]' value='"+after+"'><input type='hidden' name='prod_name[]' value='"+prod_name+"'>";
   
        $("#table_container").append("<tr><td>"+input+select_1_val+"</td><td>"+prod_name+"</td><td>"+quantity+"</td> <td>"+unit_cost+"</td> <td>"+stock_onhand+"</td><td>"+after+"</td><td> <button type='button'  class='btn btn-brand fa fa-pencil' onclick='edit(this)'></button><button type='button' onclick='removeRow(this)' class='btn btn-danger fa fa-trash'></button></td></tr>");

        $("#select_1").val('');
        $("#quantity_received").val('');
        $("#current_quantity").val('');
        $("#stock_after").val('');
        $("#unit_cost").val('');
    }
		
		/*
			See those group of letters up there^?
			They just get the M@#$%^&*# Values of the M%*!@#*!@# Form on the modal.
			More inputs?
			Just add them there.

			Also YOU should validate that shit.
			Make sure the data is not already int the table.
		*/


		/*input="<input type='hidden' name='select_1[]' value='"+select_1_val+"'> <input type='hidden' name='text_quantity[]' value='"+text_quantity+"'><input type='hidden' name='text_stockOnhand[]' value='"+text_stockOnhand+"'><input type='hidden' name='after[]' value='"+text_after+"'>" ;

		/*
			SEE THIS SHIT RIGHT HERE ^?
			Thats what gets sent for saving. 
			See the M*@#$%^&* square brackets? They allow the data to be passed as an array. That shit is important.
		*/

		/*$("#table_container").append("<tr><td>"+select_1_val+"</td><td>"+select_1_text+"</td><td>"+text_quantity+"</td><td>"+text_stockOnhand+"</td><td>"+text_after+"</td><td><button type='button' onclick='' class='btn btn-primary fa fa-edit'></button><button type='button' onclick='removeRow(this)' class='btn btn-danger fa fa-trash'></button> </td>");
		/*
			UPTOP ^?
			This is what adds the data to the table.

			that in the end? That deletes the whole row.
		*/

		
	
</script>
<script type="text/javascript">
	function removeRow(del_button) {
			// body...
			 if(confirm('Remove this product?')){
        $(del_button).parent().parent().remove();
            
        }
        return false;
			
			/*
					^
				Get theat row out of here.;
			*/
		}	
	 function getStockOnHand(){
        nums = parseInt($("#select_1 option:selected").data("cost")).toString();
        $("#current_quantity").val($("#select_1 option:selected").data("qty"));        
        $("#prod_name2").val($("#select_1 option:selected").html());
        $("#unit_cost").val(addCommas(nums));   
        $("#stock_after").val("");
        

        compute();

    }

    function getReason(){
    	return $( "#adj_status_id" ).val();
    }
   

    function addCommas(s) {
		    //number before the decimal point
	    num = s.substring(0,s.length-2);
	    //number after the decimal point
	    dec = s.substring(s.length-2,s.length);

	    var amount = new String(num);
	    amount = amount.split("").reverse();

	    var output = "";
	    for ( var i = 0; i <= amount.length-1; i++ ){
	        output = amount[i] + output;
	        if ((i+1) % 3 == 0 && (amount.length-1) !== i) output = ',' + output;
	    }
	output = output + "." + dec;
	    return output;
	}

    function compute(){
        $("#stock_after").val("");

    	reason=getReason();
    	//alert(reason);
    	received=$("#quantity_received").val();
    	stock_onhand=$("#current_quantity").val();
    	// if(isNaN(received) || isNaN(stock_onhand)){
    	// 	return false;
    	// }
    	// else{
    	// 	alert('asd');
    	// }
    	//alert(getReason());
    	if(reason==''){
    		return false;
    	}

    	value=0;
    	switch(reason){
    		case '5':
    			value=stock_onhand-received;
    		break;
    		case '6':
    			value=stock_onhand-received;
    			break;
    		case '4':
    			value= stock_onhand;
    			break;
    		default:
    			value=parseInt(stock_onhand)+parseInt(received);
    		break;
    	}
    	if(isNaN(value)){
    		return false;
    	}
    	else{
    		$("#stock_after").val(value);
    	}
    }
</script>

<?php
  
    makeFoot();
?>