<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }

    if(!empty($_GET['id'])){
        $data=$con->myQuery("SELECT customer_id,customer_name,tin,description,fax,telephone_number,mobile_number,DATE_FORMAT(customers.birth_date,'%m/%d/%Y') as 'birth_date',website,email FROM customers WHERE customer_id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($data)){
            Modal("Invalid Record Selected");
            redirect("customer_maintenance.php");
            die;
        }
    }
	makeHead("Customer");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Customer Form</h1>
    </div>
    <section class='content'>
      <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                    <div class='col-sm-12 col-md-8 col-md-offset-2'>
                        <form class='form-horizontal' method='POST' action='save_customer.php' >
                            <input type='hidden' name='id' value='<?php echo !empty($data)?$data['customer_id']:""?>'>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Customer Name*</label>
                                <div class=' col-md-9'>
                                    <input name="custName" type="text" class='form-control' placeholder="Enter Customer Name" value='<?php echo !empty($data)?htmlspecialchars($data['customer_name']):''; ?>' required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> TIN*</label>
                                <div class='col-sm-12 col-md-9'>
                                   <input name="tin" type="text" class='form-control' placeholder="Enter TIN" value='<?php echo !empty($data)?htmlspecialchars($data['tin']):''; ?>' required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Description*</label>
                                <div class='col-sm-12 col-md-9'>
                                   <input name="description" type="text" class='form-control' placeholder="Enter Description" value='<?php echo !empty($data)?htmlspecialchars($data['description']):''; ?>' required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Fax*</label>
                                <div class='col-sm-12 col-md-9'>
                                   <input name="fax" type="text" class='form-control' placeholder="Enter Fax" value='<?php echo !empty($data)?htmlspecialchars($data['fax']):''; ?>' required>
                                </div>
                            </div>
                             <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Telephone Number*</label>
                                <div class='col-sm-12 col-md-9'>
                                   <input name="telephoneNumber" type="text" class='form-control' placeholder="Enter Telephone Number" value='<?php echo !empty($data)?htmlspecialchars($data['telephone_number']):''; ?>' required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Mobile Number*</label>
                                <div class='col-sm-12 col-md-9'>
                                   <input name="mobileNumber" type="text" class='form-control' placeholder="Enter Mobile Number" value='<?php echo !empty($data)?htmlspecialchars($data['mobile_number']):''; ?>' required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Birth Date*</label>
                                <div class='col-sm-12 col-md-9'>
                                   <?php
                                        $dob="";
                                         if(!empty($account)){
                                            $dob=$account['dob'];
                                            if($dob=="00000000"){
                                                $dob="";
                                            }
                                             else
                                            {
                                                $dob=inputmask_format_date($dob);
                                                //echo $dob;
                                            }
                                        }
                                        
                                         
                                                                               
                                    ?>

                                        <input type='text' class='form-control date_picker' name='dob'  value='<?php echo !empty($data)?htmlspecialchars($data['birth_date']):''; ?>' required>

                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Website*</label>
                                <div class='col-sm-12 col-md-9'>
                                   <input name="website" type="text" class='form-control' placeholder="Enter Website" value='<?php echo !empty($data)?htmlspecialchars($data['website']):''; ?>' required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Email Address*</label>
                                <div class='col-sm-12 col-md-9'>
                                   <input name="email" type="text" class='form-control' placeholder="Enter Email Address" value='<?php echo !empty($data)?htmlspecialchars($data['email']):''; ?>' required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                     <button type='submit' class='btn btn-flat btn-success'> <span class='fa fa-check'></span> Save</button>
                                    <a href='customers.php' class='btn btn-default'>Cancel</a>
                                   
                                </div>
                                
                            </div>
                            
                        </form>
                    </div>
                </div>
                </div>
            </div>
    </section>
</div>
 <?php
        if(!empty($maintenance)):
    ?>
        $(document).ready(function(){
            
            // console.log($("#purchase_date").inputmask("hasMasked"));

        });
    <?php
        endif;
        if(!empty($_SESSION[WEBAPP]['frm_inputs'])){
            unset($_SESSION[WEBAPP]['frm_inputs']);
        }
    ?>
<?php
Modal();
?>
<?php
	makeFoot();
?>