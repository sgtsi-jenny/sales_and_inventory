<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    // if(!AllowUser(array(1))){
    //      redirect("index.php");
    // }
    
    makeHead("Suppliers");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
         <section class="content-header">
                                      <h1 class='page-header text-center text-green' align="center" style="color:#24b798;">
                                      List of Supplier
                                      </h1>
        </section>
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-lg-12'>
                    <div class='row'>
                        <div class='col-sm-12'>
                                <a href='frm_supplier.php' class='btn btn-brand pull-right'> <span class='fa fa-plus'></span> Create New</a>
                        </div>
                    </div>
                    <br/>  
              <div class="box box-primary">
                <div class="box-body">
                          <!-- <div class='panel-body'>
                                    <div class='col-md-12 text-right'>
                                        <div class='col-md-12 text-right'>
                                        <a href='frm_supplier.php' class='btn btn-brand'> Create New <span class='fa fa-plus'></span> </a>
                                        </div>                                
                                    </div> 
                          </div> -->
                                <?php
                                Alert();
                                ?>                
                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Supplier name</th>
                                                <th class='text-center'>Contact Number</th>
                                                <th class='text-center'>Address</th>
                                                <th class='text-center'>Email</th>              
                                                <th class='text-center'>Description</th>
                                                <th class='text-center'>Action</th>
                                                
                            </tr>
                          </thead>
                          <tbody>
                                            <?php
                                                $supplier=$con->myQuery("SELECT name, contact_number,address,email,description,supplier_id
FROM suppliers 
WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($supplier as $row):
                                                  $action_buttons="";
                                            ?>

                                                <tr>
                                                    <?php
                                                      foreach ($row as $key => $value):
                                                    ?>
                                                   

                                                    <?php
                                                        if($key=='supplier_id'):
                                                    ?> 
                                                      <td class="text-center">
                                                          
                                                          <a class='btn btn-sm btn-brand' href='frm_supplier.php?id=<?php echo $row['supplier_id'];?>'><span class='fa fa-pencil'></span></a>
                                                          <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $row['supplier_id'];?>&t=sup' onclick='return confirm("This supplier will be deleted.")'><span class='fa fa-trash'></span></a>
                                                      </td>
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


                  <!--</div>--><!-- /.tab-pane -->
                <!--</div>--><!-- /.tab-content -->
              <!--</div>--><!-- /.nav-tabs-custom -->
                </div>
              </div>
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
<script type="text/javascript">
    function validatePost(post_form){
        console.log();
        var str_error="";
        $.each($(post_form).serializeArray(),function(index,field){
            console.log(field);
            if(field.value==""){
            
                switch(field.name){
                    case "name":
                        str_error+="Incorrect entry in the field. \n";
                        break;
                    case "prod_price":
                        str_error+="Please provide product price. \n";
                        break;
                }
                
            }

        });
        if(str_error!=""){
            alert("You have the following errors: \n" + str_error );
            return false;
        }
        else{
            return true
        }
    }

    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY": true,
                "scrollX": true
        });
    });

    function get_price(){
        
        $("#prod_based_price").val($("#prod_id option:selected").data("price"));
        
        $("#prod_name2").val($("#prod_id option:selected").html());
    }
    
</script>
<?php 
  if(!empty($data)):
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
    Modal();
    makeFoot();
?>