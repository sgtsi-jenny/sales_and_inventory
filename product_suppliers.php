<?php
    require_once("support/config.php");
    if(!isLoggedIn()){
     toLogin();
     die();
    }

    if(!AllowUser(array(1))){
        redirect("index.php");
    }

  $data=$con->myQuery("SELECT
                        sp.id AS id,
                        sp.supplier_id AS supplier_id,
                        s.name AS supplier_name,
                        s.description AS supplier_description,
                        s.contact_number AS supplier_contact_number,
                        s.address AS supplier_address,
                        s.email AS supplier_email,
                        sp.product_id AS product_id,
                        p.product_code AS product_code,
                        p.category_id AS category_id,
                        c.name AS category_name,
                        c.category_code AS category_code,
                        p.product_name AS product_name,
                        p.selling_price AS selling_price,
                        p.wholesale_price AS wholesale_price,
                        p.barcode AS barcode,
                        p.current_quantity AS current_quantity,
                        sp.unit_cost AS unit_cost,
                        sp.is_main AS is_main
                        
                      FROM supplier_products sp
                      INNER JOIN suppliers s
                        ON s.id=sp.supplier_id
                      INNER JOIN products p
                        ON p.id=sp.product_id
                      INNER JOIN categories c
                        ON c.id=p.category_id");
    makeHead("Suppliers");
?>

<?php
    require_once("template/header.php");
    require_once("template/sidebar.php");
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Suppliers
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Main row -->
          <div class="row">

            <div class='col-md-12'>
              <?php 
                Alert();
              ?>
              <div class="box box-primary">
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-12">
                        <div class='col-ms-12 text-right'>
                          <a href='frm_product_suppliers.php' class='btn btn-warning'> Create New <span class='fa fa-plus'></span> </a>
                        </div>
                        <br/>
                        <table id='ResultTable' class='table table-bordered table-striped'>
                          <thead>
                            <tr>
                              <th class='text-center'>Supplier Name</th>
                              <th class='text-center'>Product Code</th>
                              <th class='text-center'>Product Name</th>
                              <th class='text-center'>Category</th>
                              <th class='text-center'>Unit Cost</th>
                              <th class='text-center'>Contact Number</th>
                              <th class='text-center'>Address</th>
                              <th class='text-center'>Email</th>
                              <th class='text-center'>Is Main Supplier?</th>
                              <th class='text-center'>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              while($row = $data->fetch(PDO::FETCH_ASSOC)):
                            ?>
                              <tr>
                                <td><?php echo htmlspecialchars($row['supplier_name'])?></td>
                                <td><?php echo htmlspecialchars($row['product_code'])?></td>
                                <td><?php echo htmlspecialchars($row['product_name'])?></td>
                                <td><?php echo htmlspecialchars($row['category_code'])?></td>
                                <td><?php echo htmlspecialchars($row['unit_cost'])?></td>
                                <td><?php echo htmlspecialchars($row['supplier_contact_number'])?></td>
                                <td><?php echo htmlspecialchars($row['supplier_address'])?></td>
                                <td><?php echo htmlspecialchars($row['supplier_email'])?></td>
                                <td>
                                  <?php
                                    if ($row['is_main']=='1') {
                                      echo "YES";
                                    }else
                                    {
                                      echo "NO";
                                    }
                                  ?>
                                </td>
                                <td class='text-center'>
                                  <a href='frm_product_suppliers.php?id=<?php echo $row['id']?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
                                  <a href='delete.php?t=ltyp&id=<?php echo $row['id']?>' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
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
  Modal();
    makeFoot();
?>