<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
	makeHead("Assets");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Categories Maintenance</h1>
    </div>
    <section class='content'>
                <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                        <div class='col-sm-12'>
                                <a href='frm_categories.php' class='btn btn-flat btn-success pull-right'> <span class='fa fa-plus'></span> Create New</a>
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <form method="get">
                                <table id='ResultTable' class='table table-bordered table-striped'>
                                    <thead>
                                        <tr>
                                           
                                            <th class='text-center'>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $categories=$con->myQuery("SELECT categories.category_id,categories.name FROM categories WHERE categories.is_deleted = '0'")->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($categories as $category):
                                        ?>
                                            <tr>
                                                
                                               
                                                <td><?php echo htmlspecialchars($category['name'])?></td>
                                                <td>
                                                    <a class='btn btn-sm btn-flat btn-success' href='frm_categories.php?id=<?php echo $category['category_id'];?>'><span class='fa fa-pencil'></span></a>
                                                    <a class='btn btn-sm btn-flat btn-danger' href='delete.php?id=<?php echo $category['category_id']?>&t=cat' onclick='return confirm("Are you sure you want to delete this categories?")'><span class='fa fa-trash'></span></a>
                                                </td>
                                            </tr>
                                        <?php
                                            endforeach;
                                        ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
<script type="text/javascript">
    $(function () {
          $('#ResultTable').DataTable({
                 // dom: 'Bfrtip',
                 //      buttons: [
                 //          {
                 //              extend:"excel",
                 //              text:"<span class='fa fa-download'></span> Download as Excel File "
                 //          }
                 //          ]
          });
        });
  </script>
<?php
    Modal();
	makeFoot();
?>