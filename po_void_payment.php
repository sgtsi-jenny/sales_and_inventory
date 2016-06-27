<?php
	require_once 'support/config.php';
	
	$po_master_id = $_GET['id'];
	$po_payment_id = $_GET['pay_id'];
					
	$con->myQuery("UPDATE po_payments SET is_void='1' WHERE po_master_id='$po_master_id' and po_payment_id='$po_payment_id'");
	$con->myQuery("UPDATE po_master SET payment_status_id='1' WHERE po_master_id='$po_master_id'");

	
	Alert("Payment was succesfully voided.","success");
	redirect("po_payments.php?id={$_GET['id']}");
		
?>