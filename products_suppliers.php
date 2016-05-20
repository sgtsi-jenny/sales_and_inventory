<?php
   $supplier=$con->myQuery("SELECT * FROM suppliers WHERE is_deleted=0")->fetchAll(PDO::FETCH_ASSOC);
  //if(!empty($_GET))
  //{
    $supplier_product=$con->myQuery("SELECT supplier_id FROM supplier_products WHERE supplier_product_id=?",array($products['product_id']))->fetchAll(PDO::FETCH_ASSOC);
  //}
  $tab=2;
?>
<?php
/*  $has_error=FALSE;
  if(!empty($_SESSION[WEBAPP]['Alert']) && $_SESSION[WEBAPP]['Alert']['Type']=="danger"){
    $has_error=TRUE;
  }
 */
  Alert();
?>
<div class='text-right'>
<button class='btn btn-warning' data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">Toggle Form </button>
</div>
<div id='collapseForm' class='collapse'>
  <form class='form-horizontal' action='#' method="POST" >
    <input type='hidden' name='id' value='<?php //echo !empty($employee_education)?$employee_education['id']:''; ?>'>
      <div class="form-group">
        <label for="supplier" class="col-md-3 control-label">Supplier *</label>
        <div class="col-md-7">
        	<select name='supplier' class='form-control select2' data-placeholder="Select Supplier" <?php //echo !(empty($employee_education))?"data-selected='".$employee_education['educ_level_id']."'":NULL ?> style='width:100%' required>
        		<?php
        			echo makeOptions($supplier);
        		?>
        	</select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-10 col-md-offset-2 text-center">
          <button type='submit' class='btn btn-warning'>Save </button>
          <a href='frm_products.php?id=<?php echo $products['id']?>&tab=<?php echo $tab?>' class='btn btn-default'>Cancel</a>
        </div>
      </div>
  </form>
</div>
<br/>
<table id='ResultTable' class='table table-bordered table-striped'>
  <thead>
    <tr>
      <th class='text-center'>Name</th>
      <th class='text-center'>Description</th>
      <th class='text-center'>Contact Number</th>
      <th class='text-center'>Address</th>
      <th class='text-center'>Email</th>
      <th class='text-center'>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($supplier_product as $row):
    ?>
      <tr>
        <td class='text-center'><?php echo htmlspecialchars($row['supplier_id']) ?></td>
        <td class='text-center'>#</td>
        <td class='text-center'>#</td>
        <td class='text-center'>#</td>
        <td class='text-center'>#</td>
        <td class='text-center'>
          <a href='frm_employee.php?id=<?php //echo $employee['id']?>&tab=<?php //echo $tab?>&ee_id=<?php //echo $row['id']?>' class='btn btn-success btn-sm'><span class='fa fa-pencil'></span></a>
          <a href='#' onclick="return confirm('This record will be deleted.')" class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></a>
        </td>
      </tr>
    <?php
      endforeach;
    ?>
  </tbody>
</table>
<?php 
 // if($has_error===TRUE || !empty($employee_education)):
?>
<script type="text/javascript">
  $(function(){
    $('#collapseForm').collapse({
      toggle: true
    })    
  });
</script>

<?php
  //endif;
?>