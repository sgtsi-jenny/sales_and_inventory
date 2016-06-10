<?php

  if (!empty($_GET))
  {
    $data=$con->myQuery("SELECT product_id,product_code,product_name,description,category_id,selling_price,wholesale_price,current_quantity,measurement_id,minimum_quantity,maximum_quantity,barcode FROM products WHERE is_deleted=0 AND product_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
  }

  #COMBO BOX
  $category=$con->myQuery("SELECT category_id,name FROM categories")->fetchAll(PDO::FETCH_ASSOC);
  $measurement=$con->myQuery("SELECT measurement_id,name FROM measurements")->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
  Alert();
?>
<form class='form-horizontal' name="frm_prod" action='save_product_details.php' method="POST" onsubmit="return validate(this)" enctype="multipart/form-data">
    <input type='hidden' name='id' value='<?php echo !empty($data)?$data['product_id']:''; ?>'>

    <div class="form-group">
      <label for="product_code" class="col-md-3 control-label">Product Code *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="product_code" placeholder="Product Code" name='product_code' value='<?php echo !empty($data)?htmlspecialchars($data['product_code']):''; ?>' required>
      </div>
    </div>
    
    <div class="form-group">
      <label for="product_name" class="col-md-3 control-label">Product Name *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="product_name" placeholder="Product Name" name='product_name' value='<?php echo !empty($data)?htmlspecialchars($data['product_name']):''; ?>'  required>
      </div>
    </div>

    <div class="form-group">
      <label for="description" class="col-md-3 control-label">Product Description *</label>
      <div class="col-md-7">
        <textarea class='form-control' name='description' id='description'  required><?php echo !empty($data)?htmlspecialchars($data['description']):''; ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <label for="category" class="col-md-3 control-label">Cetegory *</label>
      <div class="col-md-7">
        <div class="row">
          <div class="col-sm-11">
            <select name='category' class='form-control select2' data-placeholder="Select Category " <?php echo !(empty($data))?"data-selected='".$data['category_id']."'":NULL ?> style='width:100%' required>
              <?php
                echo makeOptions($category);
              ?>
            </select>
          </div>
          <div class='col-ms-1'>
            <a href='categories.php' class='btn btn-flat btn-sm btn-success'><span class='fa fa-plus'></span></a>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="selling_price" class="col-md-3 control-label">Selling Price * </label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="selling_price"  name='selling_price' placeholder="0000.00" value='<?php echo !empty($data)?htmlspecialchars($data['selling_price']):''; ?>' required>
      </div>
    </div>

    <div class="form-group">
      <label for="wholesale_price" class="col-md-3 control-label">Wholesale Price * </label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="wholesale_price"  name='wholesale_price' placeholder="0000.00" value='<?php echo !empty($data)?htmlspecialchars($data['wholesale_price']):''; ?>' required>
      </div>
    </div>

    <div class="form-group">
      <label for="current_quantity" class="col-md-3 control-label">Current Quantity *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="current_quantity"  name='current_quantity' placeholder="0" value='<?php echo !empty($data)?htmlspecialchars($data['current_quantity']):''; ?>' required>
      </div>
    </div>

    <div class="form-group">
      <label for="measurement" class="col-md-3 control-label">Measurement *</label>
      <div class="col-md-7">
        <div class="row">
          <div class="col-sm-11">
            <select name='measurement' class='form-control select2' data-placeholder="Select Measurement " <?php echo !(empty($data))?"data-selected='".$data['measurement_id']."'":NULL ?> style='width:100%' required>
              <?php
                echo makeOptions($measurement);
              ?>
            </select>
          </div>
          <div class='col-ms-1'>
            <a href='measurements.php' class='btn btn-flat btn-sm btn-success'><span class='fa fa-plus'></span></a>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="min_quantity" class="col-md-3 control-label">Minimum Quantity *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="min_quantity"  name='min_quantity' placeholder="0" value='<?php echo !empty($data)?htmlspecialchars($data['minimum_quantity']):''; ?>' required>
      </div>
    </div>

    <div class="form-group">
      <label for="max_quantity" class="col-md-3 control-label">Maximum Quantity *</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="max_quantity"  name='max_quantity' placeholder="0" value='<?php echo !empty($data)?htmlspecialchars($data['maximum_quantity']):''; ?>' required>
      </div>
    </div>

    <div class="form-group">
      <label for="barcode" class="col-md-3 control-label">Barcode</label>
      <div class="col-md-7">
        <input type="text" class="form-control" id="barcode" placeholder="Barcode" name='barcode' value='<?php echo !empty($data)?htmlspecialchars($data['barcode']):''; ?>'>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-10 col-md-offset-2 text-center">
        <button type='submit' class='btn btn-brand'>Save </button>
        <a href='products.php' class='btn btn-default'>Back to Products</a>
      </div>
    </div>
</form>

<script type='text/javascript'>
    function validate(frm) 
    {
      var selling_price = document.forms["frm_prod"]["selling_price"].value;
      var wholesale_price = document.forms["frm_prod"]["wholesale_price"].value;
      var current_qty = document.forms["frm_prod"]["current_quantity"].value;
      var min_qty = document.forms["frm_prod"]["min_quantity"].value;
      var max_qty = document.forms["frm_prod"]["max_quantity"].value;

      if (checkNumber(selling_price)==false)
      {
        alert("Invalid Character: Wholesale Price Should Be Numeric.");
        return false;
      }
      if (checkNumber(wholesale_price)==false)
      {
        alert("Invalid Character: Selling Price Should Be Numeric.");
        return false;
      }
      if (checkNumber(current_qty)==false)
      {
        alert("Invalid Character: Current Quantity Should Be Numeric.");
        return false;
      }
      if (checkNumber(min_qty)==false)
      {
        alert("Invalid Character: Minimum Quantity Should Be Numeric.");
        return false;
      }
      if (checkNumber(max_qty)==false)
      {
        alert("Invalid Character: Maximum Quantity Should Be Numeric.");
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