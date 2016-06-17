<?php
	require_once 'support/config.php';
	
	$po_master_id = $_GET['id'];
					
	$con->myQuery("UPDATE po_master SET is_void='1' WHERE po_master_id='$po_master_id'");

	$con->myQuery("UPDATE po_details SET is_void='1' WHERE po_master_id='$po_master_id'");

	Alert("PO".$po_master_id." was succesfully voided.","success");
	redirect("purchases.php");
		
?>