<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    // if(!AllowUser(array(1))){
    //      redirect("index.php");
    // }
    

    
    $data="";
    if(!empty($_GET['id'])){
        $data=$con->myQuery("SELECT first_name,middle_name,last_name,username,email,contact_no,security_question, security_answer, id FROM users WHERE id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($data)){
            //Alert("Invalid asset selected.");
            Modal("Invalid Account Selected");
            redirect("settings_users.php");
            die();
        }
    }

    //$products=$con->myQuery("SELECT id,product_name,unit_price FROM products WHERE is_deleted=0",array($opp['id']))->fetchAll(PDO::FETCH_ASSOC);
    //$user=$con->myQuery("SELECT id,CONCAT(last_name,' ',first_name,' ',middle_name) as name FROM users")->fetchAll(PDO::FETCH_ASSOC);
    

    makeHead("Profile");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
         <section class="content-header">
                                      <h1>
                                      User's Profile
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
                                        <!--<button class='btn btn-brand' data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">Add New <span class='fa fa-plus'></span> </button>-->
                                        </div>                                
                                    </div> 
                          </div>
                                <?php
                                Alert();
                                ?>
                   <table id='ResultTable' class='table table-bordered table-striped' style='min-width:50px'>
                           <thead align="center">
                                <th class='text-center'>USER'S INFORMATION</th>
                          </thead>
                          <tbody>
                                            <?php
                                                $userid=$_SESSION[WEBAPP]['user']['id'];
                                                $users=$con->myQuery("SELECT first_name,middle_name,last_name,username,email,contact_no,security_question, security_answer, id, security_question, security_answer, id FROM users WHERE id='$userid'")->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($users as $row):
                                            ?>

                            <tr>
                                <th>First name:</th>
                                <td style='min-width:200px'><?php echo htmlspecialchars($row['first_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Middle name:</th>
                                <td style='min-width:200px'><?php echo htmlspecialchars($row['middle_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Last name:</th>
                                <td style='min-width:200px'><?php echo htmlspecialchars($row['last_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Username:</th>
                                <td style='min-width:200px'><?php echo htmlspecialchars($row['username']) ?></td>
                            </tr>
                            <tr>
                                <th>Contact Number:</th>
                                <td style='min-width:200px'><?php echo htmlspecialchars($row['contact_no']) ?></td>
                            </tr>
                                            <?php
                                                endforeach;
                                            ?>
                            </tbody>
                    </table>
                    <hr>
                    <div id='collapseForm' class='collapse'>
                              <form class='form-horizontal' action='save_profile.php' onsubmit="return validatePost(this)" method="POST" >
                                 <input type='hidden' name='id' value='<?php echo !empty($data)?$data['id']:""?>'>
                                 <!--<input type='hidden' name='opp_id' value='<?php echo $opp['id']?>'>-->
                                      
                                      <div class="form-group">
                                        <label for="" class="col-md-4 control-label">Security Question</label>
                                        <div class="col-sm-6">
                                            <input type='text' name='security_question' id='security_question' class='form-control' placeholder='Create your own Security Question' value='<?php echo !empty($data)?$data['security_question']:"" ?>'>
                                        </div>            
                                      </div>
                                      <div class="form-group">
                                        <label for="" class="col-md-4 control-label">Security Answer</label>
                                        <div class="col-sm-6">
                                            <input type='text' name='security_answer' id='security_answer' class='form-control' placeholder='Answer for your Security Question' value='<?php echo !empty($data)?$data['security_answer']:"" ?>'>
                                        </div>            
                                      </div>

                                  <div class="form-group">
                                    <div class="col-sm-10 col-md-offset-2 text-center">
                                      <button type='submit' class='btn btn-brand'>Save </button>
                                      <a href='user_profile.php' class='btn btn-default'>Cancel</a>
                                    </div>
                                  </div>
                              </form>
                            </div>
                  <br/>

                    <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                                                <th class='text-center'>Security Question</th>
                                                <th class='text-center'>Security Answer</th>
                                                <th class='text-center'>Edit</th>
                            </tr>
                          </thead>
                          <tbody>
                                            <?php
                                                $userid=$_SESSION[WEBAPP]['user']['id'];
                                                $opp2=$con->myQuery("SELECT security_question, security_answer, id FROM users WHERE id='$userid'")->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($opp2 as $row):
                                            ?>
                                                <tr>
                                                   <td><?php echo htmlspecialchars($row['security_question'])?></td>
                                                   <td><?php echo htmlspecialchars($row['security_answer'])?></td>
                                                <td align="center">
                                                    <a class='btn btn-sm btn-warning' href='user_profile.php?id=<?php echo $row['id'];?>'><span class='fa fa-pencil'></span></a>
                                                    <!--<a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $row['id'];?>&t=ra' onclick='return confirm("This rating will be deleted.")'><span class='fa fa-trash'></span></a>-->
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
  // $(function () {
  //       $('#ResultTable').DataTable({
  //              // dom: 'Bfrtip',
  //              //      buttons: [
  //              //          {
  //              //              extend:"excel",
  //              //              text:"<span class='fa fa-download'></span> Download as Excel File "
  //              //          }
  //              //          ]
  //       });
  //     });
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