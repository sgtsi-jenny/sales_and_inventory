<?php

  if (!empty($_GET)) {
    $data=$con->myQuery("SELECT product_code,product_name FROM products WHERE is_deleted=0 AND product_id=?",array($_GET['id']))->fetch(PDO::FETCH_ASSOC);
  }

?>
<?php
  Alert();
?>
<form class='form-horizontal' action='#' method="POST" enctype="multipart/form-data">
    <input type='hidden' name='id' value='<?php //echo !empty($data)?$data['id']:''; ?>'>

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
      <div class="col-sm-10 col-md-offset-2 text-center">
        <button type='submit' class='btn btn-warning'>Save </button>
        <a href='products.php' class='btn btn-default'>Back to Products</a>
      </div>
    </div>
</form>