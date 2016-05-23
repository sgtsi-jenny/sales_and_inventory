<?php
    require_once("support/config.php");
     if(!isLoggedIn()){
        toLogin();
        die();
     }

    if(!AllowUser(array(1))){
         redirect("index.php");
    }
    $reason=$con->myQuery("SELECT adj_status_id, name FROM adjustment_status")->fetchAll(PDO::FETCH_ASSOC);
    makeHead("Stock Adjustment");
?>
<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>

 <div class="content-wrapper">
     <section class="content-header">
          <h1 align="center" style="color:#24b798;">
          		Stock Adjustment
          </h1>
    </section>
     <section class="content">
     <!-- Main row -->
          <div class="row">
          	<div class='col-sm-12 col-md-12'>
          	<div class="box box-primary">
                <div class="box-body">
                
	            	<div class='form-group'>
		            	<div class = "row">
		            		<div class = 'col-md-8' >
		            			<div class ='row'>
		            				<div class = 'col-md-8' >
		            					<h4 class='control-label'> Reason* </h4>
		            					<select class='form-control' name='adj_status_id' data-placeholder="Select User Type" 
				            				<?php echo!(empty($reason))?"data-selected='".$reason['adj_status_id']."'":NULL ?> required>
			                            	<?php
			                                	echo makeOptions($reason,'Select reason',NULL,'',!(empty($reason))?$reason['adj_status_id']:NULL)
			                            	?>
	                        			</select>
		            				</div>
		            				
		            			</div>

		            		</div>
		            		
		            		<div class = 'col-md-4'>
		            			<h4 class='control-label'> Stock adjusment no. </h4>

		            			<label class = 'control-label'>Date created: </label>
			            			<?php 
			            			echo date("m/d/Y");
			            			?>
		            			
		            		<br>
		            			<label class = 'control-label'>Issued by: 	</label>
		            					
		            		
		            			<?php
                        			echo htmlspecialchars("{$_SESSION[WEBAPP]['user']['last_name']}, {$_SESSION[WEBAPP]['user']['first_name']} {$_SESSION[WEBAPP]['user']['middle_name']}")
                      				?>
		            		</div>
	                        
		            	</div>
                        
		            			
                    </div>
	            <div class='panel-body'>
                                    <div class='col-md-12 text-right'>
                                        <div class='col-md-12 text-right'>
                                        <a href='#' class='btn btn-brand'> Create New <span class='fa fa-plus'></span> </a>
                                        </div>                                
                                    </div> 
                          </div>
                </div>
            </div>    
	            
            </div>
          </div>
     </section>
</div>