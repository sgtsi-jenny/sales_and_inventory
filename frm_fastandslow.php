<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }

    $dataFS=$con->myQuery("SELECT
                        CM.product_id,
                        CM.product_name as product_name,
                        CM.category,
                        CM.currentmonth,
                        if(previousmonth.previousmonth > 0 ,previousmonth.previousmonth,'0') as 'previousmonth',
                        if(last2months.last2months > 0, last2months.last2months,'0') as 'last2months',
                        FORMAT((sum(CM.currentmonth + if(previousmonth.previousmonth > 0 ,previousmonth.previousmonth,'0') + if(last2months.last2months > 0, last2months.last2months,'0')) / 3),2) as avesales,
                        if((sum(CM.currentmonth + if(previousmonth.previousmonth > 0 ,previousmonth.previousmonth,'0') + if(last2months.last2months > 0, last2months.last2months,'0')) / 3) > CM.currentmonth,'Fast Moving','Slow Moving') as 'status'
                        FROM (SELECT
                            products.product_id,
                            products.product_name,
                            categories.`name` as category,
                            shipments.date_delivered,
                            sum(sales_details.quantity) as currentmonth,
                            sales_status.`name` as sales_status
                            FROM
                            sales_details
                            INNER JOIN sales_master ON sales_master.sales_master_id = sales_details.sales_master_id
                            INNER JOIN sales_status ON sales_master.sales_status_id = sales_status.sales_status_id
                            INNER JOIN shipments ON sales_master.shipment_id = shipments.shipment_id
                            INNER JOIN products ON sales_details.product_id = products.product_id
                            INNER JOIN categories ON products.category_id = categories.category_id
                            WHERE
                            sales_master.sales_status_id = '4' AND
                            DATE_FORMAT(shipments.date_delivered - INTERVAL 1 MONTH, '%m%Y') = DATE_FORMAT( CURRENT_DATE - INTERVAL 1 MONTH, '%m%Y')
                            GROUP BY
                            sales_details.product_id
                            ORDER BY
                            products.product_name ASC ) as CM
                            LEFT OUTER JOIN (SELECT products.product_id,sum(sales_details.quantity) as 'previousmonth' from sales_details 
                                INNER JOIN sales_master ON sales_master.sales_master_id = sales_details.sales_master_id
                                INNER JOIN shipments ON sales_master.shipment_id = shipments.shipment_id 
                                INNER JOIN products ON sales_details.product_id = products.product_id
                                WHERE 
                                DATE_FORMAT(shipments.date_delivered, '%m%Y' ) = DATE_FORMAT( CURRENT_DATE - INTERVAL 1 MONTH, '%m%Y') and 
                                products.product_name = product_name) as previousmonth ON previousmonth.product_id=cm.product_id

                            LEFT OUTER JOIN (SELECT products.product_id,sum(sales_details.quantity) as 'last2months' from sales_details 
                                INNER JOIN sales_master ON sales_master.sales_master_id = sales_details.sales_master_id
                                INNER JOIN shipments ON sales_master.shipment_id = shipments.shipment_id 
                                INNER JOIN products ON sales_details.product_id = products.product_id
                                WHERE 
                                DATE_FORMAT(shipments.date_delivered, '%m%Y' ) = DATE_FORMAT( CURRENT_DATE - INTERVAL 2 MONTH, '%m%Y') and 
                                products.product_name = product_name) as last2months ON last2months.product_id=cm.product_id
                            GROUP BY CM.product_id, CM.product_name");

	makeHead("Fast and Slow Moving");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Fast and Slow Moving</h1>
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
                                        <th>Last 2 Months</th>
                                        <th>Previous Month</th>
                                        <th>Current Month</th>
                                        <th>Average Sales</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $fsdate = date("Ymd");
                                        $con->myQuery("DELETE FROM fastslow WHERE fs_date = ?",array($fsdate));
                                        while ($dataFSs=$dataFS->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                   <tr>
                                        <td><?php echo htmlspecialchars($dataFSs['product_name'])?></td>
                                        <td><?php echo htmlspecialchars($dataFSs['last2months'])?></td>
                                        <td><?php echo htmlspecialchars($dataFSs['previousmonth'])?></td>
                                        <td><?php echo htmlspecialchars($dataFSs['currentmonth'])?></td>
                                        <td><?php echo htmlspecialchars($dataFSs['avesales'])?></td>
                                        <td><?php echo htmlspecialchars($dataFSs['status'])?></td>
                                    </tr>
                                    <?php
                                        $lastdayofmonth=date('Y-m-t');
                                        $todate = date("Y-m-d");

                                            if ($lastdayofmonth == $todate)
                                            {
                                                $prodID = $dataFSs['product_id'];
                                                $fmonth = $dataFSs['last2months'];
                                                $smonth = $dataFSs['previousmonth'];
                                                $cmonth = $dataFSs['currentmonth'];
                                                $avesales = $dataFSs['avesales'];
                                                $fsStatus = $dataFSs['status'];

                                                                                             
                                                $con->myQuery("INSERT INTO fastslow(product_id,first_month,second_month,current_month,average_sales,fs_date,fs_status) 
                                                                    VALUES(?,?,?,?,?,?,?)",array($prodID,$fmonth,$smonth,$cmonth,$avesales,$fsdate,$fsStatus));

                                            }
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
                 // dom: 'Bfrtip',
                 //      buttons: [
                 //          {
                 //              extend:"excel",
                 //              text:"<span class='fa fa-download'></span> Download as Excel File "
                 //          }
                 //          ]
          });
        });
  </script>
<?php
    Modal();
	makeFoot();
?>