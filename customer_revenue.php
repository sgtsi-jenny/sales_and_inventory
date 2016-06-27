<?php
  require_once("support/config.php");
  if(!isLoggedIn())
  {
    toLogin();
    die();
  }

  // if(!AllowUser(array(1)))
  // {
  //   redirect("index.php");
  // }

  $customer=$con->myQuery("SELECT customer_id,customer_name FROM customers WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);


    $query="SELECT 
            DISTINCT(p.product_name),
            SUM(quantity) AS quantity_sold, 
            p.selling_price,
            SUM(sd.discount) AS discount,
            SUM(sd.total_cost) AS total_cost
            FROM products p 
            INNER JOIN sales_details sd ON sd.product_id=p.product_id
            INNER JOIN sales_master sm ON sm.sales_master_id=sd.sales_master_id
            INNER JOIN customers ON sm.customer_id=customers.customer_id WHERE sm.date_issue BETWEEN :date_from AND :date_to 
            ";
    #only admin, salesadmin, and inventoryadmin
    if(AllowUser(array(1,2)))
    {
      if(!empty($_GET['customer_id']) && $_GET['customer_id']!='NULL' )
      {
        $query.=" AND sm.customer_id=:customer_id";
        $inputs['customer_id']=$_GET['customer_id'];
      }
    }
    else{
        $query.=" AND sm.customer_id=:customer_id";
    }
    //var_dump($data=$con->myQuery($query,$inputs));
    //die();
    if(!empty($_GET['date_from']) && !empty($_GET['date_to']))
    {
      $date = date_create($_GET['date_from']);
      $inputs['date_from']= date_format($date, 'Ymd');

      $date1 = date_create($_GET['date_to']);
      $inputs['date_to']= date_format($date1, 'Ymd');
      
      $data=$con->myQuery($query,$inputs)->fetchAll(PDO::FETCH_ASSOC);  
      // var_dump($inputs['date_from']);
      // die;
    }




  makeHead("Customer Revenue Report");
?>

<?php
  require_once("template/header.php");
  require_once("template/sidebar.php");
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1 align="center" style="color:#24b798;">
      Customer Revenue Reports
    </h1>
  </section>

  <section class="content">
    <!-- Main row -->
    <div class="row">
      <div class='col-md-12'>
        <div class="box box-primary">
          <div class="box-body">
            <div class="row">
              <div class='col-md-12'>
                <form class='form-horizontal' action='' method="GET">
                  <br>
                  <?php
                    if(AllowUser(array(1))):
                  ?>
                      <div class="form-group">
                        <label for="customer_id" class="col-sm-3 control-label">Customer Name *</label>
                        <div class="col-sm-7">
                          <select class='form-control select2' name='customer_id' data-placeholder="All Employees" <?php echo !(empty($_GET))?"data-selected='".$_GET['customer_id']."'":NULL ?> style='width:100%'>
                            <?php
                              echo makeOptions($customer,"All Customer");
                            ?>
                          </select>
                        </div>
                      </div>
                  <?php
                    endif;
                  ?>
                      <div class='form-group'>
                        <label for="date_start" class="col-sm-3 control-label">Start Date *</label>
                          <div class="col-sm-7">
                            <input type="date" class="form-control" id="date_from"  name='date_from' value='<?php echo !empty($_GET)?htmlspecialchars($_GET['date_from']):''; ?>' required>
                          </div>
                      </div>
                      <div class='form-group'>
                        <label for="date_end" class="col-sm-3 control-label">End Date *</label>
                          <div class="col-sm-7">
                            <input type="date" class="form-control" id="date_to"  name='date_to' value='<?php echo !empty($_GET)?htmlspecialchars($_GET['date_to']):''; ?>' required>
                          </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-7 col-md-offset-3 text-center">
                          <button type='submit' class='btn btn-success'>Filter </button>
                          <a href='customer_revenue.php' class='btn btn-default'>Cancel</a>
                        </div>
                      </div>
                </form> 
              </div>
            </div><!-- /.row -->
          </div><!-- /.box-body -->
        </div><!-- /.box -->



        <?php
          if(!empty($_GET)):
        ?>
            <div class="box-body">            
              <br/>                 
              <table id='ResultTable' class='table table-bordered table-striped'>
                <thead>
                <tr>
                <th class='text-center'>Product name</th>
                <th class='text-center'>Quantity Sold</th>
                <th class='text-center'>Selling Price</th>
                <th class='text-center'>Discount</th>              
                <th class='text-center'>Total Cost</th>
                <!--  <th class='text-center'>Action</th> -->

                </tr>
                </thead>
                <tbody>
                <?php
                  foreach ($data as $row):
                  $action_buttons="";
                ?>

                <tr>
                <?php
                  foreach ($row as $key => $value):
                ?>
                <?php
                  if($key=='selling_price'):
                ?>
                <?php
                  elseif($key=='quantity_sold'):
                ?>
                <td class='text-center'><?php echo htmlspecialchars($row['quantity_sold']) ?></td> 
                <td class='text-right'><?php echo htmlspecialchars(number_format($row['selling_price'],2)) ?></td>
                <?php
                  elseif($key=='total_cost'):
                ?>
                <td class='text-right'><?php echo htmlspecialchars(number_format($row['total_cost'],2)) ?></td>
                <?php
                  elseif($key=='discount'):
                ?>
                <td class='text-center'><?php echo htmlspecialchars($row['discount']); ?>%</td>

                <?php
                  else:
                ?>
                <td>
                <?php
                  echo htmlspecialchars($value);
                ?>
                </td>
                <?php
                  endif;
                  endforeach;
                ?>
                </tr>
                <?php
                    endforeach;
                ?>
                </tbody>
              </table>
            </div>
        <?php
          endif;
        ?>


      </div>
    </div><!-- /.row -->
  </section><!-- /.content -->
</div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable({
          "scrollX": true,
          searching:false,
          lengthChange:false
          <?php if(!empty($data)):?>
           ,dom: 'Bfrtip',
                buttons: [
                    {
                        extend:"excel",
                        text:"<span class='fa fa-download'></span> Download as Excel File ",
                        extension:".xls"
                    }
                    ]
          <?php endif; ?>
        });
      });
</script>
<script type="text/javascript">
    function validatePost(post_form)
    {
        console.log();
        var str_error="";
        $.each($(post_form).serializeArray(),function(index,field){
            console.log(field);
            if(field.value=="")
            {
            
                switch(field.name)
                {
                    case "name":
                        str_error+="Incorrect entry in the field. \n";
                        break;
                    case "prod_price":
                        str_error+="Please provide product price. \n";
                        break;
                }
                
            }

        });
        if(str_error!="")
        {
            alert("You have the following errors: \n" + str_error );
            return false;
        }else
        {
            return true;
        }
    }
    
</script>
<?php
    Modal();
    makeFoot();
?>