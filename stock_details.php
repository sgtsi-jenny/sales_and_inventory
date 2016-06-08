<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }

    $dataSD=$con->myQuery("SELECT
                            p.product_name,
                            p.current_quantity AS 'on_hand',
                            IFNULL((SELECT SUM(quantity) FROM sales_details sd INNER JOIN sales_master sm ON sm.sales_master_id=sd.sales_master_id
                                WHERE sd.product_id=p.product_id AND sm.sales_status_id =2),'0') AS allocated,
                            (p.current_quantity - IFNULL((SELECT SUM(quantity) FROM sales_details sd INNER JOIN sales_master sm ON sm.sales_master_id=sd.sales_master_id
                                WHERE sd.product_id=p.product_id AND sm.sales_status_id=2),'0')) as 'in_stock'
                            FROM products p
                            WHERE p.is_deleted=0");

	makeHead("Stock Details");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Stock Details</h1>
    </div>
    <section class='content'>
                <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <form method="get">

                            <table id='ResultTable' class='table table-bordered table-striped'>
                                <thead>
                                    <tr>
                                       
                                        <th>Product Name</th>
                                        <th>On Hand</th>
                                        <th>Allocated</th>
                                        <th>In Stock</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        
                                        while ($dataSDs=$dataSD->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                   <tr>
                                        <td><?php echo htmlspecialchars($dataSDs['product_name'])?></td>
                                        <td><?php echo htmlspecialchars($dataSDs['on_hand'])?></td>
                                        <td><?php echo htmlspecialchars($dataSDs['allocated'])?></td>
                                        <td><?php echo htmlspecialchars($dataSDs['in_stock'])?></td>
                                        
                                    </tr>
                                    <?php
                                        
                                        endwhile;
                                    ?>
                                </tbody>
                            </table>

                            <?php
                            
                            ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
            "scrollX": true,
            dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"excel",
                        text:"<span class='fa fa-download'></span> Download as Excel File "
                    }
                    ],

        });
      });
</script>
<?php
    Modal();
	makeFoot();
?>