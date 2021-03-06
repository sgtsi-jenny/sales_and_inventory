<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    if(!AllowUser(array(1,5))){
         redirect("index.php");
    }
    

    
    $data="";
    if(!empty($_GET['id'])){
        $data=$con->myQuery("SELECT name, user_type_id FROM user_types WHERE user_type_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($data)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Account Selected");
            redirect("settings_users.php");
            die();
        }
    }

    //$products=$con->myQuery("SELECT id,product_name,unit_price FROM products WHERE is_deleted=0",array($opp['id']))->fetchAll(PDO::FETCH_ASSOC);
    //$user=$con->myQuery("SELECT id,CONCAT(last_name,' ',first_name,' ',middle_name) as name FROM users")->fetchAll(PDO::FETCH_ASSOC);
    

    makeHead("User Levels");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
         <section class="content-header">
                                      <h1 align="center" style="color:#24b798;">
                                      User Levels
                                      </h1>
        </section>
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="box box-primary">
                <div class="box-body">
                          <div class='panel-body'>
                                    <div class='col-md-12 text-right'>
                                        <div class='col-md-12 text-right'>
                                        <button class='btn btn-brand' data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">Add New <span class='fa fa-plus'></span> </button>
                                        </div>                                
                                    </div> 
                          </div>
                                <?php
                                Alert();
                                ?>
                <div id='collapseForm' class='collapse'>
                              <form class='form-horizontal' action='save_usertypes.php' onsubmit="return validatePost(this)" method="POST" >
                                 <input type='hidden' name='user_type_id' value='<?php echo !empty($data)?$data['user_type_id']:""?>'>
                                 <!--<input type='hidden' name='opp_id' value='<?php echo $opp['id']?>'>-->
                                      
                                      <div class="form-group">
                                        <label for="" class="col-md-4 control-label">User Level</label>
                                        <div class="col-sm-6">
                                            <input type='text' name='name' id='name' class='form-control' placeholder='Provide type of User' value='<?php echo !empty($data)?$data['name']:"" ?>'>
                                        </div>            
                                      </div>
                                    <!-- <div class='form-group'>
                                      <label class="col-md-4 control-label"> Users*</label>
                                      <div class="col-sm-6">
                                              <select id="disabledSelect" class='form-control' name='assigned_to' data-placeholder="Select a user" <?php echo!(empty($opportunity))?"data-selected='".$opportunity['assigned_to']."'":"data-selected='".$_SESSION[WEBAPP]['user']['id']."'" ?> required>
                                                  <?php
                                                      echo makeOptions($user);
                                                  ?>
                                              </select>
                                      </div>
                                    </div> -->

                                  <div class="form-group">
                                    <div class="col-sm-10 col-md-offset-2 text-center">
                                      <button type='submit' class='btn btn-brand'>Save </button>
                                      <a href='settings_users.php' class='btn btn-default'>Cancel</a>
                                    </div>
                                  </div>
                              </form>
                            </div>
                            <br/>

                 
                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Name</th>
                                                <th class='text-center'>Action</th>
                                                
                            </tr>
                          </thead>
                          <tbody>
                                            <?php
                                                $opp2=$con->myQuery("SELECT user_type_id, name FROM user_types WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($opp2 as $row):
                                            ?>
                                                <tr>
                                                   <td><?php echo htmlspecialchars($row['name'])?></td>
                                                <td>
                                                    <a class='btn btn-sm btn-brand' href='settings_users.php?id=<?php echo $row['user_type_id'];?>'><span class='fa fa-pencil'></span></a>
                                                    <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $row['user_type_id'];?>&t=ut' onclick='return confirm("This user type will be deleted.")'><span class='fa fa-trash'></span></a>
                                                </td>
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