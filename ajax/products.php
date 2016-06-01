<?php
require_once("../support/config.php"); 

if(!AllowUser(array(1,2))){
        redirect("index.php");
    }

$product=$con->myQuery("SELECT
                        products.product_id,
                        products.product_name as 'name',
                        supplier_products.unit_cost as unit_cost
                        FROM
                        supplier_products
                        INNER JOIN suppliers ON suppliers.supplier_id = supplier_products.supplier_id
                        INNER JOIN products ON products.product_id = supplier_products.product_id
                        WHERE suppliers.supplier_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);


?>
<input value='<?php echo $row['unit_cost']?>'>
<option value=''>Select Product</option>
<?php
foreach ($product as $row):
?>
<option value='<?php echo $row['product_id']?>'><?php echo $row['name']?></option>
<?php
endforeach;
?>


