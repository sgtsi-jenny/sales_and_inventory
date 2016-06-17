<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }
    if(!AllowUser(array(1))){
         redirect("index.php");
    }
    $reason = $con->myQuery
    ("SELECT adj_status_id, name FROM adjustment_status WHERE adj_status_id =?" , array($_GET['adj_status_id']) )
    ->fetch(PDO::FETCH_ASSOC);;
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
                <?php
                    alert();
                ?>
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
				            				<h4 class='control-label'> Stock adjusment no. SA
				            				<?php
				            					echo $_GET['id']		
				            				 ?> 
				            				 </h4>
				            				
				            			<?php
				            				}	
				            			?>
				            			<label class = 'control-label'>Date created: </label>
					            			<?php 
					            			echo date("m/d/Y");
					            			?>
				            			<br>
				            			<!-- <label class = 'control-label'>Issued by: 	</label>
				            					
				            		
				            			<?php
		                        			// echo htmlspecialchars("{$_SESSION[WEBAPP]['user']['last_name']}, {$_SESSION[WEBAPP]['user']['first_name']} {$_SESSION[WEBAPP]['user']['middle_name']}")
		                      				?>
		                      				<br>
		                      				<br> -->
				            			<div class ='row'>
				            				<input type='hidden' name='stock_adjmaster_id' value='<?php echo !empty($supplier)?$supplier['supplier_id']:""?>'>
				            				<div class = 'col-md-2' >
				            					<h4 class='control-label'> Reason </h4>
				            					
				            				</div>
				            				<div class = "col-md-8">
					                			<input type="text" class="form-control" id="input_Reason" name='input_Reason' 
					                			value='<?php echo $reason['name'];?>' readonly>
					                			<input type="hidden" class="form-control" id="input_Reason_id" name='input_Reason_id' 
					                			value='<?php echo $reason['adj_status_id'];?>'>
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
                                                <!-- <input type='hidden' id='prod_name2' name='prod_name' value=''> -->
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
		                  	<button type="button" class="btn btn-default" onclick="">Cancel</button>
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
var current_row = "";
function edit(edit_button){
        // $("#product_id").val('');
        // $("#product_id").children(0).attr("selected");
        // $("#quantity").val('');
        // $("#selling_price").val('');
        // $("#current_quantity").val('');
        // $("#discount").val('');

        $("#select_1").val('');
        $("#select_1").children(0).attr("selected");
        $("#quantity_received").val('');
        $("#current_quantity").val('');
        $("#stock_after").val('');
        $("#unit_cost").val('');
        // $("#tax").val('');

        row=$(edit_button).parent().parent();
        inputs=$(row).children(1).children();

       current_row=$(edit_button).parent().parent();

        $("#select_1").val($(inputs[0]).val()).change();
        $("#select_1").attr("disabled",true);
        $("#quantity_received").val($(  inputs[1]).val());
        $("#current_quantity").val($(inputs[2]).val());
        //onchange po itu...
        // $("#tax").val($(inputs[6]).val());
    }
	function validate_add_to_table() {
		var return_value=true;
    $("input[name='select_id[]']").each(function (d,i) {

        if($("#select_1").val()==$(i).val()){
            return_value=false;
        }
    });
        return return_value;
	}
	function validate_Form() {
		var return_value = true;
		var str_error= "";
		 if($("#select_1").val()=='' || $("#select_1").val()==0){
        str_error+="Please select a product.\n";
        return_value=false;
      }
		// if ($("#quantity").val == ''){
		// 	alert('Please input the quantity.');
		// 	return_value = false;
		// }
		if (str_error !== ""){
			alert ("You have the following error: \n" + str_error);
		}
		return return_value;
	}
	function AddToTable() {
		$("#select_1").attr("disabled",false);
		if (validate_Form() == false){
			return false;
		}
		console.log(current_row);
		// console.log(validate_add_to_table());

		if (validate_add_to_table() === false && current_row === ""){
			alert("This product was already added.");
			return false;
		}
		if(current_row !==""){
			$(current_row).remove();
		}
        select_1_val=$("select[name='select_1']	").val();
        select_1_text=$("select[name='select_1'] :selected").val();
        quantity=$("input[name='quantity_received']").val();
        unit_cost=$("input[name='unit_cost']").val(), 
        stock_onhand = $("input[name='current_quantity']").val();
        after = $("input[name='stock_after']").val();
        prod_name = $("select[name='select_1'] :selected").text()
        input="<input type='hidden' name='select_id[]' value='"+select_1_val+"'> <input type='hidden' name='quantity_received[]' value='"+quantity+"'><input type='hidden' name='unit_cost[]' value='"+unit_cost+"'><input type='hidden' name='current_quantity[]' value='"+stock_onhand+"'><input type='hidden' name='stock_after[]' value='"+after+"'><input type='hidden' name='prod_name[]' value='"+prod_name+"'>";
   		
        $("#table_container").append("<tr><td>"+input+select_1_text+"</td><td>"+prod_name+"</td><td>"+quantity+"</td> <td>"+unit_cost+"</td> <td>"+stock_onhand+"</td><td>"+after+"</td><td> <button type='button'  class='btn btn-brand fa fa-pencil' onclick='edit(this)'></button><button type='button' onclick='removeRow(this)' class='btn btn-danger fa fa-trash'></button></td></tr>");
        
        $("#select_1").val('');
        $("#quantity_received").val('');
        $("#current_quantity").val('');
        $("#stock_after").val('');
        $("#unit_cost").val('');
        
    }
		
	
		
	
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
    		case '3':
    			value=stock_onhand-received;
    			break;
    		case '3':
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