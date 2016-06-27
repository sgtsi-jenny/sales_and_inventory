<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <?php
              if(AllowUser(array(1,2,3))):
            ?>   
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="index.php"?"active":"";?>">
              <a href="index.php">              
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>
            <?php
              endif;
            ?>
            <?php
              if(AllowUser(array(1,4,5))):
            ?>   
            <li class='header'>INVENTORY</li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="products_inventory.php" || (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="products_inventory.php"?"active":"";?>">
              <a href="products_inventory.php">
                <i class="fa fa-cube"></i> <span>Stock Monitoring</span>
              </a>
            </li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="stock_adjustments.php" || (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="stock_adjustments.php"?"active":"";?>">
              <a href="stock_adjustments_main.php">
                <i class="fa fa-cubes"></i> <span>Stock Adjustments</span>
              </a>
            </li>
             <?php
              endif;
            ?>
             <?php
              if(AllowUser(array(1,2,3))):
            ?> 
            <li class='header'>ORDERS</li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="sales.php" || (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="sales_order_details.php" || (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="sales_payments.php" ?"active":"";?> ">
              <a href="sales.php">
                <i class="fa fa-cart-arrow-down"></i> <span>Sales</span>
              </a>
            </li>
             <?php
              endif;
            ?>
             <?php
              if(AllowUser(array(1,4,5))):
            ?> 
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="purchases.php" || (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="frm_purchase.php" || (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="purchase_order_details.php"?"active":"";?>">
             <a href="purchases.php">
                <i class="fa fa-cart-arrow-down"></i> <span>Purchases</span>
              </a>
            </li>
             <?php
              endif;
            ?>
             <?php
              if(AllowUser(array(1,2,3))):
            ?> 
            <li class='header'>ACCOUNTS</li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="customers.php" || (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="customers.php"?"active":"";?>">
              <a href="customers.php">
                <i class="fa fa-cart-arrow-down"></i> <span>Customers</span>
              </a>
            </li>
             <?php
              endif;
            ?>
            <?php
              if(AllowUser(array(1,4,5))):
            ?> 
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="suppliers.php" || (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="suppliers.php"?"active":"";?>">
              <a href="suppliers.php">
                <i class="fa fa-cart-arrow-down"></i> <span>Suppliers</span>
              </a>
            </li>
             <?php
              endif;
            ?>     
            <li class='header'>SETTINGS</li>
            <?php
              if(AllowUser(array(1,2,4,5))):
            ?>
            <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array("products.php","categories.php","measurements.php","users.php","settings_users.php","tax.php","backup_restore")))?"active":"";?>'>
              <a href=''><i class="fa fa-cubes"></i><span>Administrator</span><i class="fa fa-angle-left pull-right"></i></a>
            <?php
            endif;
            ?>
              <ul class='treeview-menu'>
              <?php
              if(AllowUser(array(1,4,5))):
              ?>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="products.php"?"active":"";?>">
                  <a href="products.php">
                    <i class="fa fa-cube"></i> <span>Products</span>
                  </a>
              <?php
              endif;
              ?>
              <?php
              if(AllowUser(array(1,4))):
              ?>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="categories.php"?"active":"";?>">
                  <a href="categories.php">
                    <i class="fa fa-folder-open-o"></i> <span>Categories</span>
                  </a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="measurements.php"?"active":"";?>">
                  <a href="measurements.php">
                    <i class="fa fa-columns"></i> <span>Measurements</span>
                  </a>
                </li>
              <?php
              endif;
              ?>
              <?php
              if(AllowUser(array(1))):
              ?>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="users.php"?"active":"";?>">
                  <a href="users.php">
                    <i class="fa fa-users"></i> <span>Users</span>
                  </a>
                </li>
              <?php
              endif;
              ?>
              <?php
              if(AllowUser(array(1,2))):
              ?>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="tax.php"?"active":"";?>">
                  <a href="tax.php">
                    <i class="fa fa-list-alt"></i> <span>Taxes</span>
                  </a>
                </li>
              <?php
              endif;
              ?>
                <!-- <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="settings_users.php"?"active":"";?>">
                  <a href="backup_restore.php">
                    <i class="fa fa-database"></i> <span>Backup and Restore </span>
                  </a>
                </li> -->
              </ul>
              </li>

              <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array("sold_items.php","sales_payment_report.php","customer_revenue.php")))?"active":"";?>'>
              <a href=''><i class="fa fa-file"></i><span>Reports</span><i class="fa fa-angle-left pull-right"></i></a>
              <ul class='treeview-menu'>
                          <!-- <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="stock_status.php"?"active":"";?>">
                            <a href="stock_status.php">
                              <i class="fa fa-area-chart "></i> <span>Stock Status</span>
                            </a>
                          </li> -->
                          <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="sold_items.php"?"active":"";?>">
                            <a href="sold_items.php">
                              <i class="fa fa-area-chart "></i> <span>Sold Items</span>
                            </a>
                          </li>
                          <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="sales_payment_report.php"?"active":"";?>">
                            <a href="sales_payment_report.php">
                              <i class="fa fa-area-chart "></i> <span>Sales Payments</span>
                            </a>
                          </li>
                          <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="customer_revenue.php"?"active":"";?>">
                            <a href="customer_revenue.php">
                              <i class="fa fa-area-chart "></i> <span>Customer Revenue</span>
                            </a>
                          </li>
                         <!--  <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="returned_products.php"?"active":"";?>">
                            <a href="returned_products.php">
                              <i class="fa fa-area-chart "></i> <span>Returned Products</span>
                            </a>
                          </li>   -->
                          <!-- <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="fast_slow_items.php"?"active":"";?>">
                            <a href="fast_slow_items.php">
                              <i class="fa fa-area-chart "></i> <span>Fast, Slow and Non-Moving Items</span>
                            </a>
                          </li>  -->           
                  </ul>
                </li>

              </ul>
            </li>
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>