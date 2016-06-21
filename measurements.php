<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
	makeHead("Measurement");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 class='page-header text-center text-green'>Measurement Maintenance</h1>
    </div>
    <section class='content'>
                <div class="row">
                <div class='col-lg-12'>
                    <?php
                        Alert();
                    ?>
                    <div class='row'>
                        <div class='col-sm-12'>
                                <a href='frm_measurement.php' class='btn btn-brand pull-right'> <span class='fa fa-plus'></span> Create New</a>
                        </div>
                    </div>
                    <br/>    

                    <div class='panel panel-default'>
                        
                        <div class='panel-body ' >
                            <form method="get">
                                <table class='table table-bordered table-condensed table-hover ' id='dataTables'>
                                    <thead>
                                        <tr>
                                            <th class='text-center'>Abbreviation</th>
                                            <th class='text-center'>Name</th>
                                            <th>Action</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $measurement=$con->myQuery("SELECT measurements.measurement_id,measurements.abv, measurements.name FROM measurements WHERE measurements.is_deleted = '0'")->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($measurement as $measurements):
                                        ?>
                                            <tr>
                                                
                                                <td><?php echo htmlspecialchars($measurements['abv'])?></td>
                                                <td><?php echo htmlspecialchars($measurements['name'])?></td>
                                                <td>
                                                    <a class='btn btn-sm btn-brand' href='frm_measurement.php?id=<?php echo $measurements['measurement_id'];?>'><span class='fa fa-pencil'></span></a>
                                                    <a class='btn btn-sm btn-danger' href='delete.php?id=<?php echo $measurements['measurement_id']?>&t=meas' onclick='return confirm("Are you sure you want to delete this measurement?")'><span class='fa fa-trash'></span></a>
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
<script>
    $(document).ready(function() {
        $('#dataTables').DataTable({
                 "scrollY":"400px",
                 //"scrollX": true,
        });
    });
    </script>
<?php
    Modal();
	makeFoot();
?>