<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    // if(!AllowUser(array(1))){
    //      redirect("index.php");
    // }

    $tab="1";
     if(empty($_GET['id'])){
        //Modal("No Account Selected");
        redirect("customers.php");
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
    

    makeHead("Customer");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
       <!--  <section class="content-header">
          <h1>
            Employee Form
          </h1>
          <br/>
          <a href='employees.php' class='btn btn-default'><span class='glyphicon glyphicon-arrow-left'></span> Employee list</a>
        </section> -->

        <!-- Main content -->
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
                    <li <?php echo $tab=="1"?'class="active"':''?>><a href="" >Customer Details</a>
                    </li>
                    <li> <a href="customer_address.php?id=<?php echo $_GET['id'] ?>">Address</a>
               
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" >
                  <br/>
                    <table class='table table-bordered table-condensed'>
                            <tr>
                                <th>Customer Name:</th>
                                <td><?php echo htmlspecialchars($data['customer_name']) ?></td>
                            </tr>
                            <tr>
                                <th>TIN:</th>
                                <td><?php echo htmlspecialchars($data['tin']) ?></td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td><?php echo htmlspecialchars($data['description']) ?></td>
                            </tr>
                            <tr>
                                <th>Fax:</th>
                                <td><?php echo htmlspecialchars($data['fax']) ?></td>
                            </tr>
                             <tr>
                                <th>Telephone Number:</th>
                                <td><?php echo htmlspecialchars($data['telephone_number']) ?></td>
                            </tr>
                             <tr>
                                <th>Mobile Number:</th>
                                <td><?php echo htmlspecialchars($data['mobile_number']) ?></td>
                            </tr>
                            <tr>
                                <th>Birth Date:</th>
                                <td><?php echo htmlspecialchars($data['birth_date']) ?></td>
                            </tr>
                            <tr>
                                <th>Website:</th>
                                <td><?php echo htmlspecialchars($data['website']) ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo htmlspecialchars($data['email']) ?></td>
                            </tr>
                           
                        </table>

                    <!-- <?php
                        switch ($tab) {
                            case '1':
                                #PERSONAL INFORMATION
                                $form='customer_details.php';
                                break;
                            case '2':
                                #EDUCATION
                                $form='education.php';
                                break;
                            default:
                                $form='personal_information.php';
                                break;
                        }
                        //require_once("admin/employee/".$form);
                    ?> -->
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
               dom: 'Bfrtip',
                    buttons: [
                        {
                            extend:"excel",
                            text:"<span class='fa fa-download'></span> Download as Excel File "
                        }
                        ]
        });
      });
</script>

<?php
    Modal();
    makeFoot();
?>