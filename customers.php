<?php
	require_once 'support/config.php';
	if(!isLoggedIn()){
		toLogin();
		die();
	}
    if(!AllowUser(array(1,2))){
        redirect("index.php");
    }
    
	makeHead("Customer");
?>
<?php
	 require_once("template/header.php");
	require_once("template/sidebar.php");
?>
<div class='content-wrapper'>
    <div class='content-header'>
        <h1 align="center" style="color:#24b798;">Customer Maintenance</h1>
    </div>
    <section class='content'>
        <div class="row">
            <div class='col-md-12'>
                <div class="box box-primary">
                    <div class="box-body">
                        <div class='panel-body'>
                            <div class='col-md-12 text-right'>
                                <div class='col-md-12 text-right'>
                                    <a href='frm_customer.php' class='btn btn-success btn-success pull-right'><span class='fa fa-plus'></span> Create New</a>
                                </div>
                            </div>
                        </div>
                        <?php
                            Alert();
                        ?>
                        <br/>    
                                            <form method="get">
                                                <table class='table table-bordered table-condensed table-hover ' id='dataTables'>
                                                    <thead>
                                                        <tr>
                                                            <th class='text-center'>Customer Name</th>
                                                            <th class='text-center'>TIN</th>
                                                            <th class='text-center'>Description</th>
                                                            <th class='text-center'>FAX</th>
                                                            <th class='text-center'>Telephone Number</th>
                                                            <th class='text-center'>Mobile Number</th>
                                                            <th class='text-center'>Birth Date</th>
                                                            <th>Action</th> 
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $customer=$con->myQuery("SELECT
                                                            customers.customer_id,
                                                            customers.customer_name,
                                                            customers.tin,
                                                            customers.description,
                                                            customers.fax,
                                                            customers.telephone_number,
                                                            customers.mobile_number,
                                                            DATE_FORMAT(customers.birth_date,'%m/%d/%Y') as 'birth_date'
                                                            FROM
                                                            customers
                                                            WHERE
                                                            customers.is_deleted = '0'")->fetchAll(PDO::FETCH_ASSOC);
                                                            foreach ($customer as $customers):
                                                        ?>
                                                            <tr>
                                                                    <?php
                                                                    foreach ($customers as $key => $value):                                                            
                                                                    ?>                                                          
                                                                    <?php
                                                                        if($key=='customer_name'):
                                                                    ?> 
                                                                        <td>
                                                                            <a href='customer_details.php?id=<?= $customers['customer_id']?>'><?php echo htmlspecialchars($value)?></a>
                                                                        </td>                                                        
                                                                    <?php
                                                                    ?>

                                                                
                                                                <td><?php echo htmlspecialchars($customers['tin'])?></td>
                                                                <td><?php echo htmlspecialchars($customers['description'])?></td>
                                                                <td><?php echo htmlspecialchars($customers['fax'])?></td>
                                                                <td><?php echo htmlspecialchars($customers['telephone_number'])?></td>
                                                                <td><?php echo htmlspecialchars($customers['mobile_number'])?></td>
                                                                <td><?php echo htmlspecialchars($customers['birth_date'])?></td>
                                                                
                                                                <td>
                                                                    <a class='btn btn-sm btn-success btn-success' href='frm_customer.php?id=<?php echo $customers['customer_id'];?>'><span class='fa fa-pencil'></span></a>
                                                                    <a class='btn btn-sm btn-success btn-danger' href='delete.php?id=<?php echo $customers['customer_id']?>&t=cust' onclick='return confirm("Are you sure you want to delete this customer?")'><span class='fa fa-trash'></span></a>
                                                                </td>
                                                                <?php
                                                                    endif;
                                                                    endforeach;
                                                                ?>
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