<?php
    $supplier_product=$con->myQuery("SELECT
                                      sp.supplier_product_id,
                                      sp.product_id,
                                      CONCAT(p.product_name,'(',p.product_code,')') AS product,
                                      sp.supplier_id,
                                      s.name AS supplier_name,
                                      s.contact_number,
                                      s.address,
                                      s.email,
                                      sp.unit_cost,
                                      sp.is_main
                                    FROM supplier_products sp
                                    INNER JOIN products p
                                      ON p.product_id=sp.product_id
                                    INNER JOIN suppliers s
                                      ON s.supplier_id=sp.supplier_id
                                    WHERE sp.product_id=? AND sp.is_deleted=0
                                    ",array($products['product_id']))->fetchAll(PDO::FETCH_ASSOC);
    #EDIT
    $disable="";
    if (!empty($_GET['sp_id'])) 
    {
      $record=$con->myQuery("SELECT supplier_product_id,product_id,supplier_id,unit_cost,is_main FROM supplier_products WHERE supplier_product_id=? AND product_id=?",array($_GET['sp_id'],$products['product_id']))->fetch(PDO::FETCH_ASSOC);
      $disable="disabled";
      $supplier=$con->myQuery("SELECT supplier_id,CONCAT(description,' (',name,')') as name FROM suppliers WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
      //var_dump($record['supplier_id']);
    }else
    {
      #COMBO BOX
      $supplier=$con->myQuery("SELECT supplier_id,CONCAT(description,' (',name,')') as name FROM suppliers WHERE is_deleted=0 AND supplier_id NOT IN (SELECT supplier_id FROM supplier_products WHERE product_id=? AND is_deleted=0)",array($products['product_id']))->fetchAll(PDO::FETCH_ASSOC);
    }

    $if_main=$con->myQuery("SELECT * FROM supplier_products WHERE product_id=? AND is_deleted=0 AND is_main=1",array($products['product_id']))->fetch(PDO::FETCH_ASSOC);
    $disable2="";
    if (!empty($if_main) && empty($_GET['sp_id'])) 
    {
      $disable2="disabled";
    }
    if (!empty($_GET['sp_id'])) 
    {
      $disable2="";
      if ($record['is_main']==1) 
      {
        $disable2="checked";
      }elseif ($record['is_main']==0 && !empty($if_main)) {
        $disable2="disabled";
      }
    }
    //var_dump($if_main);
    //echo "<br>".$disable2;
    //die();

    $tab=2; 
?>
<?php
  Alert();
?>
<div class='text-right'>
  <button class='btn btn-success' data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">Hide/Unhide Form </button>
</div>
<div id='collapseForm' class='collapse'>
  <form class='form-horizontal' action='save_products_suppliers.php' method="POST" name="frm_prod_sup" onsubmit="return validate(this)">
    <input type='hidden' name='product_id' value='<?php echo !empty($products)?$products['product_id']:''; ?>'>
    <input type='hidden' name='id' value='<?php echo !empty($record)?$record['supplier_product_id']:''; ?>'>
      <div class="form-group">
        <label for="supplier" class="col-md-3 control-label">Supplier *</label>
        <div class="col-md-7">
          <div class="row">
            <div class="col-sm-11">
            	<select name='supplier' class='form-control select2' data-placeholder="Select Supplier" <?php echo !(empty($record))?"data-selected='".$record['supplier_id']."'":NULL ?> style='width:100%' required <?php echo $disable; ?>>
            		<?php
            			echo makeOptions($supplier);
            		?>
            	</select>
            </div>
            <div class='col-ms-1'>
              <a href='frm_supplier.php' class='btn btn-flat btn-sm btn-success'><span class='fa fa-plus'></span></a>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="unit_cost" class="col-md-3 control-label">Unit Cost * </label>
        <div class="col-md-7">
          <input type="text" class="form-control" id="unit_cost"  name='unit_cost' placeholder="0000.00" value='<?php echo !empty($record)?htmlspecialchars($record['unit_cost']):''; ?>' required>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-offset-3 col-sm-10">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="is_main" value="1" <?php echo $disable2; ?>> Is Main Supplier?
            </label>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-10 col-md-offset-2 text-center">
          <button type='submit' class='btn btn-brand'>Save </button>
          <a href='frm_products.php?id=<?php echo $products['product_id']; ?>&tab=<?php echo $tab; ?>' class='btn btn-default'>Cancel</a>
        </div>
      </div>
  </form>
</div>
<br/>
<table id='ResultTable' class='table table-bordered table-striped'>
  <thead>
    <tr>
      <th class='text-center'>Name</th>
      <th class='text-center'>Contact Number</th>
      <th class='text-center'>Address</th>
      <th class='text-center'>Email</th>
      <th class='text-center'>Unit Cost</th>
      <th class='text-center'>Is Main</th>
      <th class='text-center'>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($supplier_product as $row):
    ?>
      <tr>
        <td class='text-center'><?php echo htmlspecialchars($row['supplier_name']) ?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['contact_number']) ?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['address']) ?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['email']) ?></td>
        <td class='text-center'><?php echo htmlspecialchars($row['unit_cost']) ?></td>
        <td class='text-center'>
          <?php 
            if ($row['is_main']==1) {
              echo "YES"; 
            }else
            {
              echo "NO";
            }
          ?>
        </td>

        <td class='text-center'>
          <a href='frm_products.php?id=<?php echo $products['product_id']?>&tab=<?php echo $tab?>&sp_id=<?php echo $row['supplier_product_id']?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
          <a href='delete.php?pid=<?php echo $products['product_id']; ?>&id=<?php echo $row['supplier_product_id']; ?>&t=prod_sup' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
        </td>
      </tr>
    <?php
      endforeach;
    ?>
  </tbody>
</table>
<script type="text/javascript">
  $(function(){
    $('#collapseForm').collapse({
      toggle: true
    })    
  });
</script>
<script type='text/javascript'>
    function validate(frm) 
    {
      var unit_cost = document.forms["frm_prod_sup"]["unit_cost"].value;

      if (checkNumber(unit_cost)==false)
      {
        alert("Invalid Character: Unit Cost Should Be Numeric.");
        return false;
      }
      return true;
    }

    function checkNumber(amt)
    {
      var amount = /[0-9]/;
      var valid = amount.test(amt); 
      return valid;
    }

</script>
