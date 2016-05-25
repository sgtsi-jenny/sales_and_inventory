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
        $sales_order=$con->myQuery("SELECT * FROM sales WHERE salees_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
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
    $stat_id=1;
    $customer=$con->myQuery("SELECT customer_id,customer_name FROM customers")->fetchAll(PDO::FETCH_ASSOC);
    $prod=$con->myQuery("SELECT product_id,product_name FROM products")->fetchAll(PDO::FETCH_ASSOC);
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
                <ul class="nav nav-tabs">
                    <?php
                        $no_order_msg=' Sales Order information must be saved.';
                    ?>
                    <li <?php echo $tab=="1"?'class="active"':''?>><a href="frm_products.php<?php echo !empty($sales_order)?"?id={$sales_order['product_id']}":''; ?>" >Sales Order Details</a>
                    </li>
                    <li <?php echo empty($sales_order)?'class="disabled"':''; ?> <?php echo $tab=="2"?'class="active"':''?> ><a href="?tab=2<?php echo !empty($sales_order)?"&id={$sales_order['product_id']}":''; ?>" <?php echo empty($sales_order)?'onclick="alert(\''.$no_order_msg.'\');return false;"':''; ?>>Payments</a>
                    </li>
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >

                <form class='form-horizontal' method='POST' action='save_sales_order.php'>
                <div class="box box-primary">
                    <div class="box-body">
                    <div class="row">
                    <div class='col-sm-12'>
                                <input type='hidden' name='sales_master_id' value='<?php echo !empty($supplier)?$supplier['supplier_id']:""?>'>

                                <label class='col-md-2 text-left' > Customer</label>
                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-3'>
                                        <select class='form-control' name='customer_id' id='customer_id'  onchange='get_address()' data-placeholder="Select a Customer" <?php echo!(empty($organization))?"data-selected='".$organization['industry']."'":NULL ?> >
                                                    <?php
                                                        echo makeOptions($customer,'Select Customer')
                                                    ?>
                                        </select>
                                    </div>
                                </div>

                                <label class='col-md-2 text-left' >Ship To:</label>
                                <div class='form-group'>
                                  <div class='col-sm-12 col-md-3 '>
                                    <select class='form-control' name='ship_to' data-placeholder="Select an address" <?php echo!(empty($organization))?"data-selected='".$organization['industry']."'":NULL ?> >
                                                    <?php
                                                        echo makeOptions($customer,'Select an address',NULL,'',!(empty($organization))?$organization['industry']:NULL)
                                                    ?>
                                        </select>
                                  </div>
                                  </div>

                                  <label class='col-md-2 text-left' >Bill To:</label>
                                  <div class='form-group'>
                                  <div class='col-sm-12 col-md-3'>
                                    <select class='form-control' name='bill_to' data-placeholder="Select an address" <?php echo!(empty($organization))?"data-selected='".$organization['industry']."'":NULL ?> >
                                                    <?php
                                                        echo makeOptions($customer,'Select an address',NULL,'',!(empty($organization))?$organization['industry']:NULL)
                                                    ?>
                                        </select>
                                  </div>
                                  </div>
                                
                                <label class='col-md-2 text-left' > Order creation date:</label>
                                <div class='form-group'>
                                  <div class='col-sm-12 col-md-3'>
                                        <?php
                                          $php_timestamp = time();
                                          $php_timestamp_date = date("F d, Y l h:i A", $php_timestamp);
                                          echo $php_timestamp_date;
                                           ?>
                                  </div>
                                </div>

                                <label class='col-md-2 text-left'> Order last updated date:</label>
                                <div class='form-group'>
                                  <div class='col-sm-12 col-md-3'>
                                        <?php
                                          $php_timestamp = time();
                                          $php_timestamp_date = date("F d, Y l h:i A", $php_timestamp);
                                          echo $php_timestamp_date;
                                           ?>
                                  </div>
                                </div>
                                <label class='col-md-2 text-left'> Order Status:</label>
                                <div class='form-group'>
                                  <div class='col-sm-12 col-md-3'>
                                        QUOTE
                                        
                                  </div>
                                </div>
                                
                      </div>
                      </div>
                </div>
                </div>



                    <!-- <table id='' class='table table-bordered table-striped'>
                                    <thead>
                                        <tr>
                                                <th class='text-center' style='min-width:250px'>Item Name</th>
                                                <th class='text-center'>Quantity</th>
                                                <th class='text-center'>Price (Php)</th>
                                                <th class='text-center'>Discount</th>
                                                <th class='text-center'>Tax</th>
                                                <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <td>
                                        <select class='form-control' name='product_id' id='product_id' data-placeholder="Select a Customer" <?php echo!(empty($sale))?"data-selected='".$sale['product_id']."'":NULL ?> required>
                                                                    <?php
                                                                        echo makeOptions($prod,'Select Item')
                                                                    ?>
                                        </select>
                                    </td>
                                    <td class='text-right'>
                                        <input type='text' name='quantity' class='form-control' onkeypress='return isNumberKey(event)'>                                        
                                    </td>
                                    <td class='text-right'>
                                        <input type='text' name='price' class='form-control' onkeypress='return isNumberKey(event)'>
                                    </td>
                                    <td class='text-right'>
                                        <input type='text' name='discount' class='form-control' onkeypress='return isNumberKey(event)'>
                                        
                                    </td class='text-right'>
                                    <td>
                                        <input type='text' name='tax' class='form-control' onkeypress='return isNumberKey(event)'>
                                        
                                    </td>
                                    <td>
                                    <button type='button' onclick='' class='btn btn-brand' onclick='AddToTable()'><span class='fa fa-plus'></span></button>
                                    </td>                                        
                                    </tbody>
                    </table> -->
                                <!-- <br>    -->   
                                <table id='' class='table table-bordered table-striped'>
                                    <thead>
                                        <tr>
                                                <th></th>
                                                <th class='text-center' style='min-width:150px'>Product Name</th>
                                                <th class='text-center'>Quantity</th>
                                                <th class='text-center'>Available</th>
                                                <th class='text-center'>Price (Php)</th>
                                                <th class='text-center'>Discount</th>
                                                <th class='text-center'>Tax</th>
                                                <th class='text-center'>Total (Php)</th>
                                                <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id='dataTable'>
                                    <td><input type="checkbox" required="required" name="chk" checked="checked" /></td>
                                    <td>
                                        <select class='form-control' name='product_id[]' id='product_id' data-placeholder="Select a Customer" <?php echo!(empty($sale))?"data-selected='".$sale['product_id']."'":NULL ?> required>
                                                                    <?php
                                                                        echo makeOptions($prod,'Select Item')
                                                                    ?>
                                        </select>
                                    </td>
                                    <td class='text-right'>
                                        <input type='text' name='quantity[]' class='form-control' onkeypress='return isNumberKey(event)'>                                        
                                    </td>
                                    <td>
                                    </td>
                                    <td class='text-right'>
                                        <input type='text' name='price[]' class='form-control' onkeypress='return isNumberKey(event)'>
                                    </td>
                                    <td class='text-right'>
                                        <input type='text' name='discount[]' class='form-control' onkeypress='return isNumberKey(event)'>
                                        
                                    </td class='text-right'>
                                    <td>
                                        <input type='text' name='tax[]' class='form-control' onkeypress='return isNumberKey(event)'>
                                        
                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <button type='button' onclick='deleteRow(this)' class='btn btn-danger'>X</button>

                                    </td>

                                    </tbody>
                                </table>
                                <section  align="left">
                                    <input type="button" value="+ Add another item" onClick="addRow('dataTable')" />
                                    <input type="button" value="Remove Item" onClick="deleteRow('dataTable')"  /> 
                                </section>
                                </br>
                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9'>
                                        <textarea class='form-control' name='description' placeholder='Message to Customer' value=''><?php echo !empty($supplier)?$supplier['description']:"" ?></textarea>
                                    </div>
                                </div>
                                <br>


                                <div class='form-group'>
                                    <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                        <button type='submit' class='btn btn-brand'> <span class='fa fa-check'></span> Save</button>
                                        <a href='sales.php' class='btn btn-default'>Save as Draft</a>
                                        <a href='sales.php' class='btn btn-default'>Cancel</a>
                                    </div>                                    
                                </div>
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


  function get_address(){
        $("#address").val($("#customer_id option:selected").data("price"));
        $("#prod_name2").val($("#prod_id option:selected").html());
    }
  
  function AddToTable() {
        
        select_1_val=$("select[name='product_id']").val();
        select_1_text=$("select[name='product_id'] :selected").text()
        quantity=$("input[name='quantity']").val();

        //available=$("input[name='available']").val();
        price=$("input[name='price']").val();
        discount=$("input[name='discount']").val();
        tax=$("input[name='tax']").val();
        // total=(price*quantity);

        input="<input type='hidden' name='product_id[]' value='"+select_1_val+"'> <input type='hidden' name='quantity[]' value='"+quantity+"'>";
        var_dump(input);
        die;
        $("#table_container").append("<tr>"+input+"<td>"+select_1_text+"</td><td>"+quantity+"</td><td>"+price+"</td><td>"+discount+"</td><td>"+tax+"</td><td>"+(price*quantity)+"</td><td><button type='button' onclick='removeRow(this)' class='btn btn-danger'>X</button></td></tr>");

        $('#myModal').modal("hide");
        $("#modal_form")[0].reset()
    }
    function removeRow(del_button) {
        // body...
        if(confirm('Remove this item?')){
        $(del_button).parent().parent().remove();
            
        }
        return false;
    }

    function addRow(tableID) {
    var table = document.getElementById(tableID);
    var rowCount = table.rows.length;
    //if(rowCount < 5){                            // limit the user from creating fields more than your limits
        var row = table.insertRow(rowCount);
        var colCount = table.rows[0].cells.length;
        for(var i=0; i <colCount; i++) {
            var newcell = row.insertCell(i);
            newcell.innerHTML = table.rows[0].cells[i].innerHTML;
        }
    //}else{
    //      alert("Maximum Passenger per ticket is 5");
               
    // }
    }

    function deleteRow(tableID) {
       
        // if(confirm('Remove this item?')){
        // $(del_button).parent().parent().remove();
            
        // }
        // return false;
        var table = document.getElementById(tableID);
        var rowCount = table.rows.length;
        for(var i=0; i<rowCount; i++) {
            var row = table.rows[i];
            var chkbox = row.cells[0].childNodes[0];
            if(null != chkbox && true == chkbox.checked) {
                if(rowCount <= 1) {               // limit the user from removing all the fields
                    alert("Cannot Remove all.");
                    break;
                }
                table.deleteRow(i);
                rowCount--;
                i--;
            }
        }
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