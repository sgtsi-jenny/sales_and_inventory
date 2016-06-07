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
    
    $po_num=$con->myQuery("SELECT po_master_id FROM po_master ORDER BY po_master_id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $supplier=$con->myQuery("SELECT supplier_id, CONCAT(description,' (',name,')') as name FROM suppliers")->fetchAll(PDO::FETCH_ASSOC);
    
	$prod=$con->myQuery("SELECT
						products.product_id,
						products.product_name AS `name`,
						supplier_products.unit_cost
						FROM
						supplier_products
						INNER JOIN suppliers ON suppliers.supplier_id = supplier_products.supplier_id
						INNER JOIN products ON products.product_id = supplier_products.product_id
						WHERE suppliers.supplier_id")->fetchAll(PDO::FETCH_ASSOC);
    makeHead("Purchase Order");
?>
<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>

<div class="content-wrapper">
	<section class="content-header">
		<h1 align="center" style="color:#24b798;">
			Purchase Order
		</h1>
	</section>
	<section class="content">
		<!-- Main row -->
		<div class="row">
			<div class='col-sm-12 col-md-12'>
			<form method='POST' action='save_purchase_order.php'>
				<div class="box box-primary">
					<div class="box-body">
						<div class='form-group'>
							<div class = "row">
								<div class = 'col-md-6' >
									<div class = 'row'>
										<div class = 'col-md-10'>
											<input type='hidden' name='po_master_id' value=''>
			                                <?php
			                                    alert();
			                                ?>
											<br>
											<h4 class='control-label'> PO Number:  <?php echo 'PO'.$po_num['po_master_id'];?></h4>
											<label class = 'control-label'>Date created: </label><?php echo date("m/d/Y");?>
											<br>
											<label class = 'control-label'>Issued by: 	</label>
											<?php
												echo htmlspecialchars("{$_SESSION[WEBAPP]['user']['last_name']}, {$_SESSION[WEBAPP]['user']['first_name']} {$_SESSION[WEBAPP]['user']['middle_name']}")
											?>
											<br><br>

							<!-- SUPPLIER FORM -->
											<form method="GET">
												<div class='form-group'>
													<div class ="row">
														<label class='col-md-3 control-label'> Select Supplier: * </label>
														<div class = "col-md-8">
															<select class='form-control select2' id='supplier' name='supplier' data-placeholder="Select product" onchange="get_suppID();" required>
																<?php
																	
																	echo makeOptions($supplier,'Select Supplier')
																?>
															</select>
															
															<!--<input type="submit" value="Add" class="btn btn-default pull-right" />-->
															
														</div>
													</div>
													<div class='form-group'>
													<div class ="row">
														<div class = "col-md-3">
															<label class='control-label'> Ship To: </label>
														</div>
														<div class = "col-md-8">
															<input type="text" class="form-control " id="ship_to" placeholder="Ship To" name='ship_to' value='' required >
														</div>
													</div>
											</div>
												</div>

											</form>
									<!--		<div class="form-group">
												<label for="description" class="col-md-3 control-label">Delivery Address: *</label>
												<div class="col-md-8">
													<textarea class='form-control' name='description' id='description'  required><?php //echo !empty($data)?htmlspecialchars($data['description']):''; ?></textarea>
												</div>
										    </div>
									-->
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class= "row">
							<div class = "col-md-5">
								<div class="box box-primary">
									<div class="box-body">
										<form method="post">
											<div class='form-group'>
												<div class ="row">
													<div class = "col-md-4">
														<label class='control-label'> Select Product: * </label>
													</div>
													<div class = "col-md-8">
														<select class='form-control select2' name='product_id' id='product_id' data-placeholder="Select a product" <?php echo!(empty($data))?"data-selected='".$data['product_id']."'":NULL?>
															onchange="get_prodIDCost()" required>
															<?php
																//echo makeOptions($product,'Select Product')
															?>
															<option>Select Product</option>
					                                        <?php
					                                            foreach ($prod as $key => $row):
					                                        ?>
					                                            <option data-cost='<?php echo $row['unit_cost'] ?>'  placeholder="Select product" value='<?php echo $row['product_id']?>' <?php echo (!empty($data) && $row['product_id']==$data['product_id']?'selected':'') ?> ><?php echo $row['name']?></option>                                                   
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
														<label class='control-label'> Unit Cost: </label>
													</div>
													<div class = "col-md-8">
														<input type="text" class="form-control " id="unit_cost" placeholder="Unit Cost" name='unit_cost' value='<?php echo !empty($data)?htmlspecialchars($row['unit_cost']):''; ?>' required readonly>
													</div>
												</div>
											</div>
											<div class='form-group'>
				                           		<div class ="row">
							                		<div class = "col-md-4">
							                			<label class='control-label'> Quantity to Purchase: * </label>
							                		</div>
							                		<div class = "col-md-8">
							                			<input type="text" class="form-control" id="qty" placeholder="Quantity to Purchase" name='qty' value='<?php //echo !empty($data)?htmlspecialchars($data['stock_adjmaster_id']):''; ?>' required>
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
										<table class='table table-bordered table-striped'>
											<thead>
												<tr>
												
												<th class='text-center'>Product name</th>
												<th class='text-center'>Quantity</th>
												<th class='text-center'>Unit Cost</th>
												<th class='text-center'>Total Cost</th>
												<th class='text-center'>Action </th>
												</tr>
											</thead>
											<tbody id='table_container'>

											</tbody>
										</table>
										<br>
										<section align = "right">
											<button type='submit' class='btn btn-brand'> <span class='fa fa-check'></span> Save</button>
											
										</section>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>  
				</form>
			</div>
		</div>
	</section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript">

	function get_suppID()
	{
	    var supplierid = document.getElementById("supplier").value;
	    $("#product").load("ajax/products.php?id="+supplierid)
	    
	}
	function get_prodIDCost()
	{
	   //var prodid = document.getElementById("product").value;
	    $("#unit_cost").val($("#product_id option:selected").data("cost"));  
	    $("#prod_name2").val($("#product_id option:selected").html());
	}
    
	function AddToTable() 
	{
        select_1_val=$("select[name='product_id']").val();
        select_1_text=$("select[name='product_id'] :selected").text()
        unit_cost=$("input[name='unit_cost']").val();
        quantity = $("input[name='qty']").val();
        total_cost = unit_cost * quantity;
        prod_name = $("input[name='prod_name']").val();
        input="<input type='hidden' name='product_id[]' value='"+select_1_val+"'> <input type='hidden' name='quantity[]' value='"+quantity+"'><input type='hidden' name='unit_cost[]' value='"+unit_cost+"'><input type='hidden' name='total_cost[]' value='"+total_cost+"'><input type='hidden' name='prod_name[]' value='"+prod_name+"'>" ;
   
        $("#table_container").append("<tr><td>"+input+select_1_text+"</td><td>"+quantity+"</td><td>"+unit_cost+"</td><td>"+total_cost+"</td><td><button type='button' onclick='removeRow(this)' class='btn btn-danger fa fa-trash'></button></td></tr>");

        $("#product_id").val('');
        $("#qty").val('');
        $("#unit_cost").val('');
	}

	function removeRow(del_button) 
	{
		// body...
		if(confirm('Remove this row?'))
		{
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