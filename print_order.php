<?php
require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }
    //  if(!AllowUser(array(1))){
    //      redirect("index.php");
    // }
$report=array();
						if(!empty($_GET)){
						$stmt=$con->prepare("SELECT ID,m.month_name,year,payroll,rent,internet,electricity,water,grocery,others from opex as o left join months as m on o.month=m.id");
						$stmt->execute($_GET);
						$report=$stmt->fetchAll(PDO::FETCH_ASSOC);

						$order_details=$con->myQuery("SELECT 
                        prod.product_name,
                        sd.quantity,
                        sd.unit_cost,
                        sd.discount,
                        sd.total_cost
                        FROM sales_master sm
                        INNER JOIN customers ON sm.customer_id=customers.customer_id
                        INNER JOIN sales_status ss ON sm.sales_status_id=ss.sales_status_id
                        INNER JOIN sales_details sd ON sm.sales_master_id=sd.sales_master_id
                        INNER JOIN products prod ON prod.product_id=sd.product_id
                        WHERE sm.sales_master_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);
						}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SALES ORDER</title>
<!-- <?php
//require_once("./support/head_includes.php");
?> -->
</head>
<body>
<div class="container-fluid">
	<!-- <div class="col-sm-12">
		//<?php require_once("./support/menu.php"); ?>
	</div> -->
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2 style='display:inline'>
				Monthly Report
				</h2>
				
				<form method="GET" action='pdf_report_download.php' class='form-inline pull-right' target="_blank">
						<a href='frm_monthly_report.php' class='btn btn-success pull-right'>+ Create Report</a>
						&nbsp;
						<input type='hidden' name='report' value='<?= $_GET['report']?>'>
						<button type='submit' class='btn btn-success '>Download PDF</button>
						&nbsp;
						
				</form>
				
				
				
			</div>
			<div class="panel-body" style="overflow:auto">
				<table class="table table-condensed" id='dt_table'>
				<thead>
					<tr>
						<th class='text-center'>ID</th>
						<th class='text-center'>Month</th>
						<th class='text-center'>Year</th>
						<th class='text-center'>Monthly Income</th>
						<th class='text-center'>Monthly Expense</th>
						<th class='text-center'>Net</th>
						<th class='text-center'>Action</th>
						
					</tr>
				</thead>
				<tbody>
					<?php 
					$stmt=$con->prepare("SELECT o.id,m.month_name,year,income,(payroll+office_rent+office_water_bill+office_meralco_bill+office_net_bill+wh_rent+wh_water_bill+wh_meralco_bill+wh_net_bill+grocery+others) AS expense,net from opex as o left join months as m on o.month=m.id");
					//$stmt->execute();
					$stmt->execute(array("date_change"=>$_GET['date_change']."%"));	
					//$stmt->execute(array("search"=>$_GET['search']."%"));	
					$reports =$stmt->fetchAll(PDO::FETCH_ASSOC);
						foreach($reports as $report):
							echo "<tr>";
							foreach($report as $key=>$value):
								echo "<td class='text-center'>";
								if($key=="o.id"){
									echo str_pad($value, STR_PAD_LEFT);
								}
								else if($key=="net" || $key=="income" || $key=="expense"){
									echo number_format($value,2);
								}
								else{
									echo htmlspecialchars($value);
								}
								echo "</td>";
							endforeach;
							?>
							<td class='text-center'>							
							<a href='frm_monthly_report.php?id=<?= $report['id']?>' class='btn btn-xs btn-success text-center' style='width:80%'><span class='glyphicon glyphicon-pencil'></span> Edit</a>&nbsp;
							
							</td>
							<?php
							echo "</tr>";
						endforeach;							
					?>					
				</tbody>
				</table>
			</div>
			
		</div>
	</div>
	
</div>

<?php
require_once("./support/body_includes.php");
?>
<script>

$(document).ready(function() {
    $('#dt_table').DataTable({
        "scrollY": true,
        "order": [],
        "bFilter": false
    });
   
} );

</script>
</body>

</html>