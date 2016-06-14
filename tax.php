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
    $data=$con->myQuery("SELECT tax_id, tax_name, percentage FROM tax");
    makeHead("Taxes");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
         <section class="content-header">
            <h1 align="center" style="color:#24b798;">
                List of Taxes
            </h1>
        </section>
        <section class="content">

          <!-- Main row -->
          <div class="row">
            <div class='col-md-12'>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-6 col-md-offset-3">
                        </br>
                        <?php
                          Alert();
                        ?>
                        <br/>
                        <table class='table table-bordered table-striped' style='width:100%'>
                          <thead>
                            <tr>
                              <th class='text-center'>Tax Name</th>
                              <th class='text-center'>Percentage</th>
                              <th class='text-center'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                                <?php
                                    while($row = $data->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['tax_name'])?></td>
                                        <td class='text-center'><?php echo htmlspecialchars($row['percentage']).' %'?></td>
                                        <td class='text-center'>
                                          <a href='frm_tax.php?id=<?php echo $row['tax_id']; ?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
                                        </td>
                                    </tr>
                                <?php
                                    endwhile;
                                ?>
                          </tbody>
                        </table>
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div><!-- /.box-body -->
              </div>
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
  </div>
<?php
    Modal();
    makeFoot();
?>