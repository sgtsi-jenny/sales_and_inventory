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
        redirect("customers.php".(!empty($customers)?'?id='.$customers['id']:''));
        die;
    }
    else{
        if(!empty($_GET['tab'])){
            if($_GET['tab'] >0 && $_GET['tab']<=9){
                $tab=$_GET['tab'];
            }
            else{
                #invalid TAB
                redirect("customers.php".(!empty($customers)?'?id='.$customers['id']:''));
            }
        }
    }
    
    if(!empty($_GET['id']))
    {
        $customers=$con->myQuery("SELECT * FROM customers WHERE customer_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($customers)){
            Modal("Invalid Record Selected");
            redirect("customers.php");
            die;
        }
    }
    else{
        if($tab>"1"){
            Modal("Customer information must be saved first.");
            redirect("frm_customers.php");
        }

    }
    

    makeHead("Customer Form");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Product Form
          </h1>
          <br/>
          <a href='customers.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Customer list</a>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php
                        $no_employee_msg=' Customer information must be saved.';
                    ?>
                    <li <?php echo $tab=="1"?'class="active"':''?>><a href="frm_customers.php<?php echo !empty($customers)?"?id={$customers['customer_id']}":''; ?>" >Customer Details</a>
                    </li>
                    <li <?php echo empty($customers)?'class="disabled"':''; ?> <?php echo $tab=="2"?'class="active"':''?> ><a href="?tab=2<?php echo !empty($customers)?"&id={$customers['customer_id']}":''; ?>" <?php echo empty($customers)?'onclick="alert(\''.$no_employee_msg.'\');return false;"':''; ?>>Address</a>
                    </li>
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >
                    <?php
                        switch ($tab) {
                            case '1':
                                #Details
                                $form='frm_customer.php';
                                break;
                            case '2':
                                #suppliers
                                $form='customer_address.php';
                                break;
                            default:
                                $form='frm_customer.php';
                                break;
                        }
                        require_once($form);
                    ?>
                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
/*  $(function () {
        $('#ResultTable').DataTable({
               dom: 'Bfrtip',
                    buttons: [
                        {
                            extend:"excel",
                            text:"<span class='fa fa-download'></span> Download as Excel File "
                        }
                        ]
        });
      });
*/
</script>

<?php
    Modal();
    makeFoot();
?>