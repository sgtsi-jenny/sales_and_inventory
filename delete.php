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
				$page="frm_products.php?id={$_GET['phpid']}&tab=2";
				$id="supplier_product_id";
				break;
			case 'org':
				$table="organizations";
				$page="customers.php";
				break;
			case 'opp':
				$table="opportunities";
				$page="opportunities.php";
				break;
			case 'org_opp':
				$table="opportunities";
				$page="org_opp.php?id={$_GET['id']}";
				break;
			case 'prod':
				$table="products";
				$page="products.php";
				break;
			case 'oprod':
				$table="opp_products";
				$page="opp_products.php?id={$_GET['id']}";
				break;
			case 'ocon':
				$table="opp_contacts";
				$page="opp_contact_persons.php?id={$_GET['opp_id']}";
				break;
			case 'odocs':
				$table="documents";				
				$page="opp_documents.php?id={$_GET['opp_id']}";
				break;
			case 'oquotes':
				$table="quotes";
				$page="opp_quotes.php?id={$_GET['opp_id']}";
				break;
			case 'eve':
				$table="events";
				$page="calendar_list.php";
				break;
			case 'user':
				$table="users";
				$page="users.php";
				break;
			case 'ut':
				$table="user_types";
				$page="settings_users.php";
				break;
			case 'os':
				$table="opp_statuses";
				$page="settings_oppstat.php";
				break;
			case 'lo':
				$table="locations";
				$page="settings_locations.php";
				break;
			case 'ra':
				$table="org_ratings";
				$page="settings_ratings.php";
				break;
			case 'dpmt':
				$table="departments";
				$page="settings_departments.php";
				break;
			case 'orgt':
				$table="org_types";
				$page="settings_orgtypes.php";
				break;
			case 'oppt':
				$table="opp_types";
				$page="settings_opptypes.php";
				break;
			case 'fu':
				$table="files";
				$page="assets.php";
				if(!empty($_GET['a'])){
					#asset_id
					$page="view_asset.php?id={$_GET['a']}";
				}
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