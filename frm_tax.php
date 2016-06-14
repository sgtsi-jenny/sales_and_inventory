<?php
	require_once("support/config.php");
	if(!isLoggedIn())
    {
		toLogin();
		die();
	}

    if(!AllowUser(array(1)))
    {
        redirect("index.php");
    }
    if (!empty($_GET['id'])) 
    {
        $tax=$con->myQuery("SELECT tax_id,tax_name,percentage FROM tax WHERE tax_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
    }
    //var_dump($tax);
    //die();

	makeHead("Tax Settings");
?>
<?php
	require_once("template/header.php");
	require_once("template/sidebar.php");
?>
 	<div class="content-wrapper">
        <section class="content-header">
            <h1 align="center" style="color:#24b798;">Tax Maintenance</h1>
        </section>
        <section class="content">
          <div class="row">
            <div class='col-md-10 col-md-offset-1'>
				<?php
					Alert();
				?>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                	<div class='col-sm-12 col-md-8 col-md-offset-2'>
                        <form class='form-horizontal' method='POST' action='save_tax.php'>
                            <input type='hidden' name='tax_id' value='<?php echo !empty($tax)?$tax['tax_id']:""?>'>
                            <div class='form-group'>
                                <label class='col-sm-12 col-md-3 control-label'> Percentage* </label>
                                <div class='col-sm-12 col-md-9'>
                                    <input type="text" class="form-control" name="percentage" placeholder="Percentage" value="<?php echo !empty($tax)?$tax["percentage"]:"" ?>" required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <div class='col-sm-12 col-md-9 col-md-offset-3 '>
                                    <button type='submit' class='btn btn-brand'> <span class='fa fa-check'></span> Save</button>
                                    <a href='tax.php' class='btn btn-default'>Cancel</a>
                                </div>
                            </div>                        
                        </form>
                      </div>
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>

<script type="text/javascript">
  $(function () {
        $('#ResultTable').DataTable();
      });
</script>
<?php
	makeFoot();
?>