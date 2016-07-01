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
        $data=$con->myQuery("SELECT measurement_id,abv,name FROM measurements WHERE measurement_id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($data)){
            Modal("Invalid Record Selected");
            redirect("measurement_maintenance.php");
            die;
        }
    }
	makeHead("Measurement");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Measurement Form</h1>
    </div>
    <section class='content'>
      <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                    <div class='col-sm-12 col-md-8 col-md-offset-2'>
                        <form class='form-horizontal' method='POST' action='save_measurement.php' >
                            <input type='hidden' name='id' value='<?php echo !empty($data)?$data['measurement_id']:""?>'>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Abbreviation*</label>
                                <div class=' col-md-9'>
                                    <input name="abvr" type="text" class='form-control' placeholder="Enter Abbreviation" value='<?php echo !empty($data)?htmlspecialchars($data['abv']):''; ?>' required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Name*</label>
                                <div class='col-sm-12 col-md-9'>
                                   <input name="measname" type="text" class='form-control' placeholder="Enter Name" value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
                                </div>
                            </div>
                            
                            <div class='form-group'>
                                <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                     <button type='submit' class='btn btn-brand'> <span class='fa fa-check'></span> Save</button>
                                    <a href='measurements.php' class='btn btn-default'>Cancel</a>
                                   
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