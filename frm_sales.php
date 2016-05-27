<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }
    if(!AllowUser(array(1))){
         redirect("index.php");
    }
    $tab="1";
    if(!empty($_GET['tab']) && !is_numeric($_GET['tab'])){
        redirect("frm_sales.php".(!empty($sales_order)?'?id='.$sales_order['id']:''));
        die;
    }
    else{
        if(!empty($_GET['tab'])){
            if($_GET['tab'] >0 && $_GET['tab']<=9){
                $tab=$_GET['tab'];
            }
            else{
                #invalid TAB
                redirect("frm_employee.php".(!empty($products)?'?id='.$products['id']:''));
            }
        }
    }
    
    if(!empty($_GET['id']))
    {
        $sales_order=$con->myQuery("SELECT 
                                                sm.sales_master_id,
                                                customers.customer_name AS customer,
                                                ss.name AS status_name,
                                                ps.name AS payment_name,
                                                (SELECT SUM(sd.total_cost) FROM sales_details sd WHERE sd.sales_master_id=sm.sales_master_id) AS total,
                                                (SELECT SUM(sp.amount) FROM sales_payments sp WHERE sp.sales_master_id=sm.sales_master_id) AS paymed_amount
                                                FROM sales_master sm
                                                INNER JOIN customers ON sm.customer_id=customers.customer_id
                                                INNER JOIN sales_status ss ON sm.sales_status_id=ss.sales_status_id
                                                INNER JOIN payment_status ps ON sm.payment_status_id=ps.payment_status_id")->fetchAll(PDO::FETCH_ASSOC);
        if(empty($sales_order)){
            Modal("Invalid Record Selected");
            redirect("sales.php");
            die;
        }
    }
    else{
        if($tab>"1"){
            Modal("Sales Order information must be saved first.");
            redirect("frm_sales.php");
        }
    }


        if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
        if(!empty($organization)){
            $old_org=$organization;
        }
        $organization=$_SESSION[WEBAPP]['frm_inputs'];
        if(!empty($old_org)){
            $organization['id']=$old_org['id'];
        }
        }

    $stat_id=1;
    $customer=$con->myQuery("SELECT customer_id,customer_name FROM customers")->fetchAll(PDO::FETCH_ASSOC);
    $customer_add=$con->myQuery("SELECT customer_add_id,label_address FROM customers cus INNER JOIN customer_address cus_add ON cus.customer_id=cus_add.customer_id")->fetchAll(PDO::FETCH_ASSOC);
    $prod=$con->myQuery("SELECT product_id,product_name,selling_price,current_quantity FROM products")->fetchAll(PDO::FETCH_ASSOC);
    $sales_stat=$con->myQuery("SELECT name FROM sales_status where sales_status_id=1")->fetchAll(PDO::FETCH_ASSOC);
    
    makeHead("Sales Order");
?>
<script type="application/javascript">
  function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
         return true;
      }
</script>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <?php
                if(!empty($_GET['id'])){
            ?>
                <h1 align="center" style="color:#24b798;">Update Sales Order</h1>
            <?php
            }
                else{                    
            ?>
            <h1 align="center" style="color:#24b798;">Create New Sales Order</h1>                
            <?php
                }
            ?>
          <br/>
          <a href='sales.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Back to Sales List</a>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="nav-tabs-custom">
                <!-- <ul class="nav nav-tabs">
                    <?php
                        $no_order_msg=' Sales Order information must be saved.';
                    ?>
                    <li <?php echo $tab=="1"?'class="active"':''?>><a href="frm_products.php<?php echo !empty($sales_order)?"?id={$sales_order['product_id']}":''; ?>" >Sales Order Details</a>
                    </li>
                    <li <?php echo empty($sales_order)?'class="disabled"':''; ?> <?php echo $tab=="2"?'class="active"':''?> ><a href="?tab=2<?php echo !empty($sales_order)?"&id={$sales_order['product_id']}":''; ?>" <?php echo empty($sales_order)?'onclick="alert(\''.$no_order_msg.'\');return false;"':''; ?>>Payments</a>
                    </li>
                </ul> -->
                <div class="tab-content">
                  <div class="active tab-pane" >

                <form class='form-horizontal' method='POST' action='save_sales_order.php'>
                <div class="box box-primary">
                    <div class="box-body">
                    <div class="row">
                    <div class='col-sm-12'>
                                <input type='hidden' name='sales_master_id' value='<?php echo !empty($supplier)?$supplier['supplier_id']:""?>'>
                                <?php
                                    alert();
                                ?>
                                <label class='col-md-2 text-left' > Customer</label>
                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-3'>
                                        <select class='form-control' name='customer_id' id='customer_id'  onchange='get_address()' data-placeholder="Select a Customer" <?php echo!(empty($sales_order))?"data-selected='".$sales_order['customer']."'":NULL ?> required>
                                                    <?php
                                                        echo makeOptions($customer,'Select Customer')
                                                    ?>
                                        </select>
                                    </div>
                                </div>
                                          
                                <label class='col-md-2 text-left' > Order creation date:</label>
                                <div class='form-group'>
                                  <div class='col-sm-12 col-md-3'>
                                        <?php
                                          // $php_timestamp = date("m/d/Y");
                                          $php_timestamp_date = date("m/d/Y");
                                          echo $php_timestamp_date;
                                           ?>
                                  </div>
                                </div>

                                <!-- <label class='col-md-2 text-left'> Order last updated date:</label>
                                <div class='form-group'>
                                  <div class='col-sm-12 col-md-3'>
                                        <?php
                                          $php_timestamp = time();
                                          $php_timestamp_date = date("F d, Y l h:i A", $php_timestamp);
                                          echo $php_timestamp_date;
                                           ?>
                                  </div>
                                </div>
                                 -->
                                <label class='col-md-2 text-left'> Order Status:</label>
                                <div class='form-group'>
                                  <div class='col-sm-12 col-md-3'>
                                        Quote
                                  </div>
                                </div>
                                
                    </div>
                    </div>
                </div>
                </div>  
                <div class= "row">
                <div class = "col-md-5">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class='form-group'>
                                <div class ="row">
                                    <div class = "col-md-4">
                                        <label class='control-label'> Select product:* </label>
                                    </div>
                                    <div class = "col-md-8  ">
                                        <select class='form-control' id='product_id' onchange='get_price2()' name='product_id' data-placeholder="Select a product" <?php echo!(empty($data))?"data-selected='".$data['product_id']."'":NULL ?>style='width:100%'  >
                                                <option>Select Product</option>
                                                <?php
                                                    foreach ($prod as $key => $row):
                                                ?>
                                                    <option data-price='<?php echo $row['selling_price'] ?>' data-qty='<?php echo $row['current_quantity'] ?>' placeholder="Select product" value='<?php echo $row['product_id']?>' <?php echo (!empty($data) && $row['product_id']==$data['product_id']?'selected':'') ?> ><?php echo $row['product_name']?></option>                                                    
                                                <?php
                                                    endforeach;
                                                ?>
                                                <input type='hidden' id='prod_name2' name='prod_name' value=''>
                                            </select>
                                    </div>
                                </div>
                           </div>
                           <div class='form-group'>
                                <div class ="row">
                                    <div class = "col-md-4">
                                        <label class='control-label'> Quantity:* </label>
                                    </div>
                                    <div class = "col-md-8">
                                        <input type="text" class="form-control" id="quantity" placeholder="0" name='quantity' value='<?php echo !empty($data)?htmlspecialchars($data['stock_adjmaster_id']):''; ?>' >
                                    </div>
                                </div>
                           </div>
                           <div class='form-group' class="text-right">
                                <div class ="row">
                                    <div class = "col-md-4">
                                        <label class='control-label'> Available: </label>
                                    </div>
                                    <div class = "col-md-8">
                                        <input type="text" class="form-control " id="current_quantity" placeholder="--" name='current_quantity' value='<?php echo !empty($data)?htmlspecialchars($data['stock_adjmaster_id']):''; ?>'  readonly>
                                    </div>
                                </div>
                           </div>
                           <div class='form-group'>
                                <div class ="row">
                                    <div class = "col-md-4">
                                        <label class='control-label'> Price: </label>
                                    </div>
                                    <div class = "col-md-8">
                                        <input class="form-control" name='selling_price' id="selling_price"   type="text" value='<?php echo !empty($data)?$data['selling_price']:"" ?>' onkeypress='return isNumberKey(event)' >
                                    </div>
                                </div>
                           </div>
                           <div class='form-group'>
                                <div class ="row">
                                    <div class = "col-md-4">
                                        <label class='control-label'> Discount:* </label>
                                    </div>
                                    <div class = "col-md-8">
                                        <input type="text" class="form-control" id="discount" placeholder="0" name='discount' value='<?php echo !empty($data)?htmlspecialchars($data['stock_adjmaster_id']):''; ?>' >
                                    </div>
                                </div>
                           </div>
                           <div class='form-group'>
                                <div class ="row">
                                    <div class = "col-md-4">
                                        <label class='control-label'> Tax:* </label>
                                    </div>
                                    <div class = "col-md-8">
                                        <input type="text" class="form-control" id="tax" placeholder="12%" name='tax' value='<?php echo !empty($data)?htmlspecialchars($data['stock_adjmaster_id']):''; ?>' >
                                    </div>
                                </div>
                           </div>

                        <section align = "right">
                            <button type="button" class="btn btn-brand" onclick="AddToTable()">Add</button>
                            <!-- <button type="reset" class="btn btn-default" onclick="">Reset</button> -->
                        </section>
                          
                            
                        </div>
                    </div>
                </div>

                <!-- </form>
                <form class='form-horizontal' method='POST' action='save_sales_order.php'> -->

                <div class = "col-md-7">
                    <div class="box box-primary">
                        <div class="box-body">
                             <table id='' class='table table-bordered table-striped'>
                                    <thead>
                                        <tr>
                                                <th class='text-center'>Product name</th>
                                                <th class='text-center'>Quantity</th>
                                                <th class='text-center'>Available</th>
                                                <th class='text-center'>Price (Php)</th>
                                                <th class='text-center'>Discount</th>
                                                <th class='text-center'>Tax</th>
                                                <th class='text-center'>Total (Php)</th>
                                          <th class='text-center'>Action </th>
                                           
                                    </thead>
                                    <tbody id='table_container'>                    
                                    </tbody>
                             </table>
                        </div>
                    </div>
                </div>                
            </div>

                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-5'>
                                        <textarea class='form-control' name='description' placeholder='Message to Customer' value=''><?php echo !empty($supplier)?$supplier['description']:"" ?></textarea>
                                    </div>
                                </div>

                                <section align='right'>
                                    <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <button type='submit' class='btn btn-brand'> <span class='fa fa-check'></span> Save</button>
                                        <a href='sales.php' class='btn btn-default'>Save as Draft</a>
                                        <a href='sales.php' class='btn btn-default'>Cancel</a>
                                    </div>                                    
                                </div>
                                </section>
                                
                                </br>
                 </form>


                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable();
      });
 function get_price2(){
        
        $("#selling_price").val($("#product_id option:selected").data("price"));
        $("#current_quantity").val($("#product_id option:selected").data("qty"));        
        $("#prod_name2").val($("#product_id option:selected").html());
    }
  
  function AddToTable() {
        select_1_val=$("select[name='product_id']").val();
        select_1_text=$("select[name='product_id'] :selected").text()
        quantity=$("input[name='quantity']").val();
        current_quantity = $("input[name='current_quantity']").val();
        selling_price = $("input[name='selling_price']").val();
        discount = $("input[name='discount']").val();
        tax = $("input[name='tax']").val();
        prod_name = $("input[name='prod_name']").val();
        input="<input type='hidden' name='product_id[]' value='"+select_1_val+"'> <input type='hidden' name='quantity[]' value='"+quantity+"'><input type='hidden' name='current_quantity[]' value='"+current_quantity+"'><input type='hidden' name='selling_price[]' value='"+selling_price+"'><input type='hidden' name='discount[]' value='"+discount+"'><input type='hidden' name='prod_name[]' value='"+prod_name+"'><input type='hidden' name='tax[]' value='"+tax+"'><input type='hidden' name='total_price[]' value='"+(quantity*selling_price)+"'>" ;
   
        $("#table_container").append("<tr><td>"+input+select_1_text+"</td><td>"+quantity+"</td><td>"+current_quantity+"</td><td>"+selling_price+"</td><td>"+discount+"</td><td>"+tax+"</td><td>"+(quantity*selling_price)+"</td><td><button type='button' onclick='removeRow(this)' class='btn btn-danger fa fa-trash'></button></td></tr>");

        $("#product_id").val('');
        $("#quantity").val('');
        $("#selling_price").val('');
        $("#current_quantity").val('');
        $("#discount").val('');
        $("#tax").val('');
        
    }
    function removeRow(del_button) {
        // body...
        if(confirm('Remove this item?')){
        $(del_button).parent().parent().remove();
            
        }
        return false;
    }
</script>
<?php
if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
    // $asset=$_SESSION[WEBAPP]['frm_inputs'];
    // var_dump($asset);
    unset($_SESSION[WEBAPP]['frm_inputs']);
}
?>
<?php
    Modal();
    makeFoot();
?>