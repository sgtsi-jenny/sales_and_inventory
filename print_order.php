<?php
require_once("support/config.php");
require_once('tcpdf/tcpdf.php');
header('Content-Type: application/pdf');
header("Content-Disposition: attachment; filename=\"audit_logs.pdf\"");


// create new PDF document
$pdf = new TCPDF('PORTRAIT', PDF_UNIT, "portrait", true, 'UTF-8', false);



// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();

$pdf->Write(0, 'SALES ORDER', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('helvetica', '', 8);

// -----------------------------------------------------------------------------

$tbl=<<<EOD
				<br/>
				<table border="1" cellpadding="2" >
				<thead>
					<tr>
						<th class='text-center'>Product Name</th>
						<th class='text-center'>Order Quantity</th>
						<th class='text-center'>Price</th>
						<th class='text-center'>Discount</th>
						<th class='text-center'>Total</th>
					</tr>
				</thead>

EOD;
						$stmt=$con->prepare("SELECT 
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
                                                WHERE sm.sales_master_id=?",array($_GET['id']));
						//$stmt->execute();
						// if(!empty($_GET)){
						// 	$stmt->execute(array("date_change"=>$_GET['date_change']."%"));
						// }
						// else{
						// 	$stmt->execute(array("date_change"=>'%%'));
						// }
						
						$logs=$stmt->fetchAll(PDO::FETCH_ASSOC);
						foreach($logs as $log):
							$tbl.= "<tr>";
							foreach($log as $key=>$value):
								$tbl.='<td align="center">';
								if($key=="unit_cost" || $key=="total_cost"){
									$tbl.= number_format($value,2);
								}
								
								else{
									$tbl.= htmlspecialchars($value);
								}
								$tbl.= "</td>";
							endforeach;
							$tbl.="</tr>";
						endforeach;
		
				$tbl.="</table>";

$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->Output();
// -----------------------------------------------------------------------------
die;
checkLogin();
if($_SESSION['app']['user']['type']==1){
//header("Content-Disposition: attachment; filename=\"revenue_report.xls\"");
//header("Content-Type: application/vnd.ms-excel");
?>

<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
</head>
<body>
<style>
	*{
	
    font-size: 1em !important;
        word-wrap: break-word;
    }
</style>
<br/>
<table class="table table-condensed" id='dt_table' style='width:100%'>
				<thead>
					<tr>
						<th class='text-center'>Product Name</th>
						<th class='text-center'>Order Quantity</th>
						<th class='text-center'>Price</th>
						<th class='text-center'>Discount</th>
						<th class='text-center'>Total</th>						
					</tr>
				</thead>
				<tbody>
					<?php 
					$stmt=$con->prepare("SELECT 
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
                                                WHERE sm.sales_master_id=?",array($_GET['id']));
					$stmt->execute();
					//$stmt->execute(array("date_change"=>$_GET['date_change']."%"));	
					//$stmt->execute(array("search"=>$_GET['search']."%"));	
					$logs=$stmt->fetchAll(PDO::FETCH_ASSOC);
						foreach($logs as $item):
							echo "<tr>";
							foreach($item as $key=>$value):
								echo "<td class='text-center'>";
								if($key=="o.id"){
									?>
										
									<?php
								}
								
								else if($key=="unit_cost" || $key=="income"){
									$tbl.= number_format($value,2);
								}
								
								else{
									echo htmlspecialchars($value);
								}
								echo "</td>";
							endforeach;
							?>
							<?php
							echo "</tr>";
						endforeach;
					?>
				</tbody>
				</table>
				</body>
				</html>
<?php
}
?>