<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }


    if(empty($_GET['id'])){
        //Modal("No Account Selected");
            redirect("stock_adjustments_main.php");
        die();
    }
    else{
       $sa = $con->myQuery("SELECT StM.stock_adjmaster_id as SAID, StD.quantity_received as q_Rec ,  aStat.`name` as reason,
                        aStat.adj_status_id as status_ID, StM.notes as notes,
                        p.product_id as prodID, p.product_name as prodName , 
                        p.current_quantity as c_Quan,SP.unit_cost as u_cost 
                        FROM stock_adj_master StM  
                        INNER JOIN stock_adj_details StD  on StM.stock_adjmaster_id = StD.stock_adjmaster_id
                        INNER JOIN adjustment_status aStat on aStat.adj_status_id = StM.adj_status_id
                        INNER JOIN supplier_products SP on StD.product_id = SP.product_id
                        INNER JOIN products p on p.product_id =StD.product_id
                        WHERE StM.stock_adjmaster_id = ?
                        ",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);

        if(empty($sa))
        {
            //Modal("Invalid sales Selected");

            redirect("stock_adjustments_main.php");
            die;
        }
        
    }
    
    $get_sa = $con->myQuery("SELECT
                        stock_adj_master.stock_adjmaster_id,
                        adjustment_status.name as reason,
                        stock_adj_master.notes
                        FROM
                        stock_adj_master
                        INNER JOIN adjustment_status ON stock_adj_master.adj_status_id = adjustment_status.adj_status_id
                        WHERE
                        stock_adj_master.stock_adjmaster_id =?
                        ",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);

    $reason=$con->myQuery("SELECT adj_status_id, name FROM adjustment_status")->fetchAll(PDO::FETCH_ASSOC);
    
    
    makeHead("Stock Adjustments",0,true);
?>

<div class='col-md-10 no-print' align='right'>
    <a href='view_stock_adjustments.php?id=<?php echo $_GET['id'];?>' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Back</a>
    <button onclick='window.print()' class='btn btn-brand no-print'>Print &nbsp;<span class='fa fa-print'></span> </a> </button>  
    
</div>
<div class='page'>
    <div class="row">
         <h1 align="center" style="color:#24b798;">Stock Adjustments</h1>
    </div>
    <br/>
    <div class='row'>
        <!-- <h2>
          <img src="uploads/summary_Oppurtunities.png"  width="50" height="50" title="Organization" alt="Organization" />
          <?php //echo htmlspecialchars($sale['customer']) ?>
        </h2> -->
    </div>
    <table>
        <tr>
            <td style="width:150px">Stock Adjustment ID</td>
            <td>
                <?php echo htmlspecialchars('SA#'.$get_sa['stock_adjmaster_id'])?>
            </td>
        </tr>
        <tr>
            <td>Notes:</td>
            <td>
                <?php echo htmlspecialchars($get_sa['notes'])?>
            </td>
        </tr>
    </table>

    <br/>
    <div class="row">
        <div class='col-md-12'>
            <table id='' class='table table-bordered table-striped'>
            <thead>
                <tr>
                    <th class='text-left' style='min-width:200px'>Product Name</th>
                    <th class='text-left'>Quantity</th>
                    <th class='text-right'>Unit Cost (Php)</th>
                    <th class='text-center'>Stock on hand</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($sa as $row):
                    
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['prodName'])?></td>
                    <td><?php echo htmlspecialchars($row['q_Rec'])?></td>
                    <td align="right"><?php echo htmlspecialchars(number_format($row['u_cost'],2))?></td>
                    <td  class='text-center'><?php echo htmlspecialchars($row['c_Quan'])?></td>
                    
                </tr>
                <?php
                endforeach;
                ?>
            </tbody>
            </table>

           
           <!--  <?php
            //var_dump($total_qty);
            ?> -->
            <!-- <table align='right'>
                <tr>
                    <td style='min-width:100px'>Total Units</td>
                    <td align="right">
                        <?php echo htmlspecialchars($total_qty)?>
                    </td>
                </tr>  
                <tr>
                    <td>Subtotal (Php)</td>
                    <td align="right">
                        <?php echo htmlspecialchars(number_format($subtotal,2))?>
                    </td>

                </tr>
                <tr>
                    <td>Plus VAT (12%)</td>
                    <td align="right">
                        <?php echo htmlspecialchars(number_format($tax,2))?>
                    </td>

                </tr>
                <tr>
                    <td>Total (Php)</td>
                    <td align="right">
                        <?php echo htmlspecialchars(number_format($t_cost,2))?>
                    </td>

                </tr>           
            </table> -->


        </div>

    </div>
</div>
<?php
    makeFoot();
?>