<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }

    //echo $_GET['id'];
    //die();

    if(!empty($_GET)){
        $data=$con->myQuery("SELECT category_id,name FROM categories WHERE category_id=? LIMIT 1",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
        if(empty($data)){
            Modal("Invalid Record Selected");
            redirect("categories.php");
            die;
        }
    }
	makeHead("Categories");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Categories Form</h1>
    </div>
    <section class='content'>
      <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                    <div class='col-sm-12 col-md-8 col-md-offset-2'>
                        <form class='form-horizontal' method='POST' action='save_categories.php' >
                            <input type='hidden' name='id' value='<?php echo !empty($data)?$data['category_id']:""?>'>
                      <!--      <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Category ID*</label>
                                <div class=' col-md-9'>
                                    <input name="categoryid" type="text" class='form-control' placeholder="Auto Generated ID" readonly value='<?php// echo !empty($data)?htmlspecialchars($data['category_id']):''; ?>' required>
                                </div>
                            </div>
                           --> <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Category Name*</label>
                                <div class='col-sm-12 col-md-9'>
                                   <input name="catname" type="text" class='form-control' placeholder="Category Name"  value='<?php echo !empty($data)?htmlspecialchars($data['name']):''; ?>' required>
                                </div>
                            </div>
                            
                            <div class='form-group'>
                                <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                     <button type='submit' class='btn btn-brand'> <span class='fa fa-check'></span> Save</button>
                                    <a href='categories.php' class='btn btn-default'>Cancel</a>
                                   
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