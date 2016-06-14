<?php
	require_once 'support/config.php';
	
	if(!isLoggedIn()){
		toLogin();
		die();
	}
	if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
    //echo $_GET['id'];
    //echo $_GET['t'];
    //die();	
    if(empty($_GET['id']) || empty($_GET['t'])){
		redirect('index.php');
		die;
	}	
	else
	{

		$table="";
		$id="";
		switch ($_GET['t'])
		{
			case 'cad':
				$table="customer_address";
				$page="customer_address.php";
				$id="customer_add_id";
				break;
			case 'cat':
				$table="categories";
				$page="categories.php";
				$id="category_id";
				break;
			case 'meas':
				$table="measurements";
				$page="measurements.php";
				$id="measurement_id";
				break;
			case 'cust':
				$table="customers";
				$page="customers.php";
				$id="customer_id";
				break;
			case 'prod':
				$table="products";
				$page="products.php";
				$id="product_id";
				break;
			case 'prod_sup':
				$table="supplier_products";
				$page="frm_products.php?id={$_GET['id']}&tab=2";
				$id="supplier_product_id";
				break;
			case 'sup':
				$table="suppliers";
				$page="suppliers.php";
				$id="supplier_id";
				break;
			case 'user':
				$table="users";
				$page="users.php";
				$id="user_id";
				break;
			default:
				redirect("index.php");
				break;
		}
		//var_dump($_GET);
		//echo "<br>".$table."<br>".$id;
		//die();

		$con->myQuery("UPDATE {$table} SET is_deleted=1 WHERE {$id}=?",array($_GET['id']));
		Alert("Delete Successful.","success");
		redirect($page);

		die();

	}
?>