<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }

    $checked="";
    if(!empty($_GET['id'])){
        $data=$con->myQuery("SELECT customer_id,customer_name,tin,description,fax,telephone_number,mobile_number,DATE_FORMAT(customers.birth_date,'%m/%d/%Y') as 'birth_date',website,email,is_top_company FROM customers WHERE customer_id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($data)){
            Modal("Invalid Record Selected");
            redirect("customer_maintenance.php");
            die;
        }
        if ($data['is_top_company'] == 1) 
        {
            $checked="checked";
        }
        $disable2="";
    }
	makeHead("Customer");
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
                    
<form class='form-horizontal' method='POST' action='save_customer.php' >
<input type='hidden' name='id' value='<?php echo !empty($data)?$data['customer_id']:""?>'>
<div class='form-group'>
    <label class='col-sm-12 col-md-3 control-label'> Customer Name*</label>
    <div class=' col-md-7'>
        <input name="custName" type="text" class='form-control' placeholder="Enter Customer Name" value='<?php echo !empty($data)?htmlspecialchars($data['customer_name']):''; ?>' required>
    </div>
</div>
<div class='form-group'>
    <label class='col-sm-12 col-md-3 control-label'> TIN*</label>
    <div class='col-sm-12 col-md-7'>
       <input name="tin" type="text" class='form-control' placeholder="Enter TIN" value='<?php echo !empty($data)?htmlspecialchars($data['tin']):''; ?>'  onkeypress='return isNumberKey(event)' required>
    </div>
</div>
<div class='form-group'>
    <label class='col-sm-12 col-md-3 control-label'> Description*</label>
    <div class='col-sm-12 col-md-7'>
       <input name="description" type="text" class='form-control' placeholder="Enter Description" value='<?php echo !empty($data)?htmlspecialchars($data['description']):''; ?>' required>
    </div>
</div>
<div class='form-group'>
    <label class='col-sm-12 col-md-3 control-label'> Fax*</label>
    <div class='col-sm-12 col-md-7'>
       <input name="fax" type="text" class='form-control' placeholder="Enter Fax" value='<?php echo !empty($data)?htmlspecialchars($data['fax']):''; ?>' required>
    </div>
</div>
 <div class='form-group'>
    <label class='col-sm-12 col-md-3 control-label'> Telephone Number*</label>
    <div class='col-sm-12 col-md-7'>
       <input name="telephoneNumber" type="text" class='form-control' placeholder="Enter Telephone Number" value='<?php echo !empty($data)?htmlspecialchars($data['telephone_number']):''; ?>' required>
    </div>
</div>
<div class='form-group'>
    <label class='col-sm-12 col-md-3 control-label'> Mobile Number*</label>
    <div class='col-sm-12 col-md-7'>
       <input name="mobileNumber" type="text" class='form-control' placeholder="Enter Mobile Number" value='<?php echo !empty($data)?htmlspecialchars($data['mobile_number']):''; ?>' required>
    </div>
</div>
<div class='form-group'>
    <label class='col-sm-12 col-md-3 control-label'> Birth Date*</label>
    <div class='col-sm-12 col-md-7'>
       <?php
            $dob="";
            if(!empty($data))
            {
                // var_dump($data['birth_date']);
                $dob=$data['birth_date'];
                if($dob=="00/00/0000")
                {
                    $dob="";
                }else
                {
                    $dob=inputmask_format_date($dob);
                }
            }                                         
        ?>
        <input type='text' class='form-control date_picker' name='dob'  value='<?php echo !empty($data)?htmlspecialchars($data['birth_date']):''; ?>' required>
    </div>
</div>
<!-- <div class='form-group'>
    <label class='col-sm-12 col-md-3 control-label'> Date*</label>
    <div class='col-sm-12 col-md-7'>
       <?php
            $dob="";
            if(!empty($data))
            {
                $dob=$data['birth_date'];
                if($dob=="00/00/0000")
                {
                    $dob="";
                }else
                {
                    $dob=inputmask_format_date($dob);
                }
            }                                         
        ?>
        <input type='date' class='form-control ' name='dob'  value='<?php echo !empty($data)?$data['birth_date']:''; ?>' required>
    </div>
</div> -->
<div class='form-group'>
    <label class='col-sm-12 col-md-3 control-label'> Website*</label>
    <div class='col-sm-12 col-md-7'>
       <input name="website" type="text" class='form-control' placeholder="Enter Website" value='<?php echo !empty($data)?htmlspecialchars($data['website']):''; ?>' required>
    </div>
</div>
<div class='form-group'>
    <label class='col-sm-12 col-md-3 control-label'> Email Address*</label>
    <div class='col-sm-12 col-md-7'>
       <input name="email" type="text" class='form-control' placeholder="Enter Email Address" value='<?php echo !empty($data)?htmlspecialchars($data['email']):''; ?>' required>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="is_top" value="1" <?php echo $checked; ?>> Is Belong to Top 1,000 Companies?
            </label>
        </div>
    </div>
</div>

<br>
<div class='form-group'>
    <div class='col-sm-12 col-md-7 col-md-offset-3'>
         <button type='submit' class='btn btn-success btn-brand'> <span class='fa fa-check'></span> Save</button>
        <a href='customers.php' class='btn btn-default'>Cancel</a>
    </div>
</div>
                
</form>
                            
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