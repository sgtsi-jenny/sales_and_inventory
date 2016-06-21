<?php
    require_once("support/config.php");
    if(!isLoggedIn()){
        toLogin();
        die();
    }
    $data=$con->myQuery("SELECT
                          p.product_id,
                          p.product_code,
                          p.product_name,
                          p.description,
                          p.category_id,
                          c.name AS category_name,
                          p.selling_price,
                          p.wholesale_price,
                          p.current_quantity AS quantity,
                          IFNULL((SELECT SUM(quantity) FROM sales_details sd INNER JOIN sales_master sm ON sm.sales_master_id=sd.sales_master_id
                            WHERE sd.product_id=p.product_id AND sm.sales_status_id=2),'0') AS allocated,
                          p.minimum_quantity,
                          p.maximum_quantity,
                          p.barcode
                        FROM products p
                        INNER JOIN categories c
                          ON c.category_id=p.category_id
                        INNER JOIN measurements m
                          ON m.measurement_id=p.measurement_id
                        WHERE p.is_deleted=0 AND CAST(p.current_quantity AS UNSIGNED INTEGER) < CAST(p.minimum_quantity AS UNSIGNED INTEGER)
                        ");
    makeHead();
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Dashboard
          </h1>
        </section>
       
        <!-- Main content -->
        <section class="content">
        <div class='row'>
            <div class='col-md-12'>
                <div class='panel panel-primary'>
                    <div class='panel-heading text-left'>
                        <div class="row">
                            <h4 class="col-xs-10">Products on Critical Level</h4>
                            <!-- <span class="fa fa-tasks fa-3x col-md-2 text-right" style="padding-top: 15px;"></span> -->
                        </div>
                    </div>
                <div class="panel-body">
                <div class="row">
                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Product Code</th>
                              <th class='text-center'>Product Name</th>
                              <th class='text-center'>Description</th>
                              <th class='text-center'>Category</th>
                              <th class='text-center'>Total Quantity</th>
                              <th class='text-center'>Allocated Stocks</th>
                              <th class='text-center'>Stock on hand</th>
                              <!-- <th class='text-center' style='width:11%'>Stock Condition</th> -->
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              while($row = $data->fetch(PDO::FETCH_ASSOC)):
                            ?>
                              <tr>
                                <td><a href="product_inventory_details.php?id=<?php echo $row['product_id']; ?>"><i class="fa fa-cube"></i> <?php echo htmlspecialchars($row['product_code'])?></a></td>
                                <td><?php echo htmlspecialchars($row['product_name'])?></td>
                                <td><?php echo htmlspecialchars($row['description'])?></td>
                                <td><?php echo htmlspecialchars($row['category_name'])?></td>
                                <?php
                                  $alloc=$con->myQuery("SELECT SUM(sd.quantity) AS order_qty FROM sales_details sd INNER JOIN sales_master sm ON sm.sales_master_id=sd.sales_master_id WHERE sm.sales_status_id=2 AND sd.product_id=? GROUP BY sd.product_id",array($row['product_id']))->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <td><?php echo intval($row['quantity']) + $alloc['order_qty']; ?></td>
                                <td><?php echo !empty($alloc['order_qty'])?$alloc['order_qty']:'0'; ?></td>
                                <td> <?php echo intval($row['quantity']); ?> </td>
                               
                              </tr>
                            <?php
                              endwhile;
                            ?>
                          </tbody>
                        </table>
                    </div>
                    </div>
                </div>

                <div class='panel panel-primary'>
                <div class='panel-heading text-left'>
                    <div class="row">
                        <h4 class="col-xs-10">Fast and Slow Moving Items</h4>
                    </div>

                </div>
                <div class="panel-body">
                <div class="row">
                    <?php 
                     $dataFS=$con->myQuery("SELECT
                        CM.product_id,
                        CM.product_name as product_name,
                        CM.category,
                        CM.currentmonth,
                        if(previousmonth.previousmonth > 0 ,previousmonth.previousmonth,'0') as 'previousmonth',
                        if(last2months.last2months > 0, last2months.last2months,'0') as 'last2months',
                        FORMAT((sum(CM.currentmonth + if(previousmonth.previousmonth > 0 ,previousmonth.previousmonth,'0') + if(last2months.last2months > 0, last2months.last2months,'0')) / 3),2) as avesales,
                        if((sum(CM.currentmonth + if(previousmonth.previousmonth > 0 ,previousmonth.previousmonth,'0') + if(last2months.last2months > 0, last2months.last2months,'0')) / 3) >= CM.currentmonth,'Fast Moving','Slow Moving') as 'status'
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
                            DATE_FORMAT(shipments.date_delivered, '%m%Y') = DATE_FORMAT(CURRENT_DATE, '%m%Y')
                            GROUP BY
                            sales_details.product_id
                            ORDER BY
                            products.product_name ASC ) as CM
                            LEFT OUTER JOIN (SELECT products.product_id,sales_details.quantity as 'previousmonth' from sales_details 
                                INNER JOIN sales_master ON sales_master.sales_master_id = sales_details.sales_master_id
                                INNER JOIN shipments ON sales_master.shipment_id = shipments.shipment_id 
                                INNER JOIN products ON sales_details.product_id = products.product_id
                                WHERE 
                                DATE_FORMAT(shipments.date_delivered, '%m%Y' ) = DATE_FORMAT( CURRENT_DATE - INTERVAL 1 MONTH, '%m%Y') and 
                                products.product_name = product_name) as previousmonth ON previousmonth.product_id=cm.product_id

                            LEFT OUTER JOIN (SELECT products.product_id,sales_details.quantity as 'last2months' from sales_details 
                                INNER JOIN sales_master ON sales_master.sales_master_id = sales_details.sales_master_id
                                INNER JOIN shipments ON sales_master.shipment_id = shipments.shipment_id 
                                INNER JOIN products ON sales_details.product_id = products.product_id
                                WHERE 
                                DATE_FORMAT(shipments.date_delivered, '%m%Y' ) = DATE_FORMAT( CURRENT_DATE - INTERVAL 2 MONTH, '%m%Y') and 
                                products.product_name = product_name) as last2months ON last2months.product_id=cm.product_id
                            GROUP BY CM.product_id, CM.product_name
                            ORDER BY CM.currentmonth DESC");
                    ?>

                    <table id='ResultTable2' class='table table-bordered table-striped'>
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
                   
                    </div>
                    </div>
                </div>
            </div>
        </div>                                
           
        </section><!-- /.content -->
  </div>
 <script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
            "scrollX": true
            // ,
            // dom: 'Bfrtip',
            //     buttons: [
            //         {
            //             extend:"excel",
            //             text:"<span class='fa fa-download'></span> Download as Excel File "
            //         }
            //         ],

        });
      });
  $(function () {
        $('#ResultTable2').DataTable({
            "scrollX": true
            // ,
            // dom: 'Bfrtip',
            //     buttons: [
            //         {
            //             extend:"excel",
            //             text:"<span class='fa fa-download'></span> Download as Excel File "
            //         }
            //         ],

        });
      });
</script> 
<?php
    makeFoot();
?>