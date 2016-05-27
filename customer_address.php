<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    // if(!AllowUser(array(1))){
    //      redirect("s");
    // }
    $tab="2";
  
    if(empty($_GET['id'])){
        //Modal("No Account Selected");
        redirect("customer_address.php");
        die();
    }
    else{
        $data=$con->myQuery("SELECT customer_id,customer_name,tin,description,fax,telephone_number,mobile_number,DATE_FORMAT(customers.birth_date,'%m/%d/%Y') as 'birth_date',website,email FROM customers WHERE customer_id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($data)){
            Modal("Invalid Record Selected");
            redirect("customers.php");
            die;
        }
    }
    $dataOn=$con->myQuery("SELECT customer_add_id,label_address,address FROM customer_address WHERE is_deleted='0' AND customer_id=?",array($_GET['id']))->fetchAll(PDO::FETCH_ASSOC);

    makeHead("Customer");

?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <section class="content-header">
              <h1>
              <i class="fa fa-user" style="font-size:48px;text-shadow:2px 2px 4px #000000;"></i>
              &nbsp;&nbsp;Customers
              </h1>
        </section>
         <section class="content-header">

        <br/>
          <a href='customers.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> List of All Customers</a>
          <!--<a href='org_opp.php?id=<?php echo $opp['org_id'] ?>' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Back to My Opportunity</a>-->
          </section>
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <!-- <?php
                        $no_employee_msg=' Personal Information must be saved.';
                    ?> -->
                   
                    <li> <a href="customer_details.php?id=<?php echo $_GET['id'] ?>">Customer Details</a>
                    </li>
                    <li <?php echo $tab=="2"?'class="active"':''?>> <a href="">Address</a>
                    </li>
                    
                    
                </ul>
                <form class='form-horizontal' action='save_customer_address.php' method="POST" >
                <div class="tab-content">
                  <div class="active tab-pane" >
                     <div class='panel-body'>
                          <div class='col-md-12'>
                            <input type='hidden' name='customer_id' value='<?php echo $data['customer_id']?>'>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Label Address</label>
                                <div class='col-sm-12 col-md-6'>
                                   <input name="lblAddress" type="text" class='form-control' placeholder="Enter Label Address" >
                                </div>
                            </div>
                            
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Address</label>
                                <div class='col-sm-12 col-md-6'>
                                   <input name="address" type="text" class='form-control' placeholder="Enter Address" >
                                </div>
                            </div>
                           
                            <div class="form-group">
                            <div class="col-sm-7 col-md-offset-5 text-center">
                              <button type='submit' class='btn btn-success brand-bg'>Add </button>
                                
                              </div>
                          </div>                    
                          </div> 
                      </div>
                      
                  </div>
                </div>
                </form>
                 <?php
                  Alert();
                 ?>
                <!--<div id='collapseForm' class='collapse'>
                  <form class='form-horizontal' action='save_opp_contact.php' method="POST" >
                    <input type='hidden' name='opp_con' value='<?php echo !empty($opp)?$opp['id']:""?>'>
                    <input type='hidden' name='opp_id' value='<?php echo $opp['id']?>'>
                                    <div class='form-group'>
                                        <label for="" class="col-md-4 control-label">Contact Name * </label>
                                        <div class="col-sm-5">
                                            <!--<select class='form-control' id='c_id' onchange='get_con()' name='c_id' data-placeholder="Select a contact" <?php echo!(empty($data))?"data-selected='".$data['name']."'":NULL ?>>
                                            <?php
                                                foreach ($contacts as $key => $row):
                                                    ?>
                                                    <option data-phone='' placeholder="Select contact" value='<?php echo $row['id']?>'><?php echo $row['name']?></option>
                                                <?php
                                                    endforeach;
                                                ?>
                                                <input type='hidden' id='name' name='name' value=''>
                                            -->
                                            <!--
                                            <select name='c_id' class='form-control select2' data-placeholder="Select Contact Name" <?php echo !(empty($record))?"data-selected='".$record['c_id']."'":NULL ?> style='width:100%' required>
                                                <?php
                                                  echo makeOptions($contacts);
                                                ?>
                                            </select>
                                        </div>  
                                      </div>
                          <div class="form-group">
                            <div class="col-sm-10 col-md-offset-2 text-center">
                              <button type='submit' class='btn btn-success brand-bg'>Add </button>
                            <a href='opp_contact_persons.php?id=<?php echo $opp['id'] ?>' class='btn btn-default'>Cancel</a>
                              </div>
                          </div>    
                  </form>
                </div>   -->                         
                  <h2>List of Customer Address</h2>
                   <br>
                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Label Address</th>
                                                <th class='text-center'>Address</th>
                                                <th class='text-center' style='min-width:40px'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                                            <?php
                                              foreach($dataOn as $row):
                                            ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['label_address']) ?></td>
                                                    <td><?php echo htmlspecialchars($row['address']) ?></td>
                                                    
                                                    <td align="center">
                                                        <!--<a href='' class='btn btn-sm btn-success'><span class='fa fa-pencil'></span></a>
                                                        -->
                                                        <a class='btn btn-sm btn-flat btn-danger' href='delete.php?id=<?php echo $row['customer_add_id']?>&x=<?php echo $data['customer_id']; ?>&t=ca' onclick='return confirm("Are you sure you want to delete this address?")'><span class='fa fa-trash'></span></a>
                                                    </td>
                                                </tr>
                                            <?php
                                                endforeach;
                                            ?>
                                        </tbody>
                        </table>


                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
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
  if(!empty($eve)):
?>
<script type="text/javascript">
  $(function(){
    $('#collapseForm').collapse({
      toggle: true
    })    
  });
</script>
<?php
  endif;
?>
<?php 
  if(!empty($to_do)):
?>
<script type="text/javascript">
  $(function(){
    $('#collapseForm2').collapse({
      toggle: true
    })    
  });
</script>
<?php
  endif;
?>
<script type="text/javascript">
  $(function(){
   $('#collapseForm').on('show.bs.collapse', function () {
    $('#collapseForm2').collapse('hide')
    });

   $('#collapseForm2').on('show.bs.collapse', function () {
      $('#collapseForm').collapse('hide')
    });

  });
</script>

<?php
    Modal();
    makeFoot();
?>