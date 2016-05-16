<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="index.php"?"active":"";?>">
              <a href="index.php">
              
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
              </a>
            </li>
            <li class='header'>INVENTORY</li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="my_cal.php" || (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="products.php"?"active":"";?>">
              <a href="products.php">
                <i class="fa fa-cube"></i> <span>Products</span>
              </a>
            </li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="my_cal.php" || (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="variants.php"?"active":"";?>">
              <a href="variants.php">
                <i class="fa fa-cubes"></i> <span>Variants</span>
              </a>
            </li>
            <li class='header'>ORDERS</li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="opportunities.php"?"active":"";?>">
              <a href=" ">
                <i class="fa fa-cart-arrow-down"></i> <span>Sales</span>
              </a>
            </li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="products.php"?"active":"";?>">
              <a href=" ">
                <i class="fa fa-cart-arrow-down"></i> <span>Purchases</span>
              </a>
            </li>
            <li class='header'>ACCOUNTS</li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="products.php"?"active":"";?>">
              <a href=" ">
                <i class="fa fa-cart-arrow-down"></i> <span>Customers</span>
              </a>
            </li>
            <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="products.php"?"active":"";?>">
              <a href=" ">
                <i class="fa fa-cart-arrow-down"></i> <span>Suppliers</span>
              </a>
            </li>
           
            <?php
              if(AllowUser(array(1,5))):
            ?>    
            <li class='header'>SETTINGS</li>
            <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array("accounts.php","users.php","settings_users.php","backup_restore")))?"active":"";?>'>
              <a href=''><i class="fa fa-cubes"></i><span>Administrator</span><i class="fa fa-angle-left pull-right"></i></a>
              <ul class='treeview-menu'>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="accounts.php"?"active":"";?>">
                  <a href="accounts.php">
                    <i class="fa fa-gear"></i> <span>Accounts</span>
                  </a>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="users.php"?"active":"";?>">
                  <a href="users.php">
                    <i class="fa fa-users"></i> <span>Users</span>
                  </a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="settings_users.php"?"active":"";?>">
                  <a href="settings_users.php">
                    <i class="fa fa-users"></i> <span>User Levels</span>
                  </a>
                </li>
                <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="settings_users.php"?"active":"";?>">
                  <a href="backup_restore.php">
                    <i class="fa fa-database"></i> <span>Backup and Restore </span>
                  </a>
                </li>
              </ul>
              </li>

              <li class='treeview <?php echo (in_array(substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1), array("reports_products_forecast.php", "reports_revenue_forecast.php","reports_activities_currentmonth.php","reports_orgopportunities.php","reports_oppcontacts.php")))?"active":"";?>'>
              <a href=''><i class="fa fa-file"></i><span>Reports</span><i class="fa fa-angle-left pull-right"></i></a>
              <ul class='treeview-menu'>
                          <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reports_products_forecast.php"?"active":"";?>">
                            <a href="reports_products_forecast.php">
                              <i class="fa fa-clock-o"></i> <span>Cost of Goods Sold</span>
                            </a>
                          </li>
                          <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reports_revenue_forecast.php"?"active":"";?>">
                            <a href="reports_revenue_forecast.php">
                              <i class="fa fa-clock-o"></i> <span>Purchases Report</span>
                            </a>
                          </li>
                          <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reports_activities_currentmonth.php"?"active":"";?>">
                            <a href="reports_activities_currentmonth.php">
                              <i class="fa fa-clock-o"></i> <span>Stock/Inventory Reports</span>
                            </a>
                          </li>
                          <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reports_orgopportunities.php"?"active":"";?>">
                            <a href="reports_orgopportunities.php">
                              <i class="fa fa-clock-o"></i> <span>Sales Report</span>
                            </a>
                          </li>
                          <li class="<?php echo (substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], "/")+1))=="reports_oppcontacts.php"?"active":"";?>">
                            <a href="reports_oppcontacts.php">
                              <i class="fa fa-clock-o"></i> <span>Payments Report</span>
                            </a>
                          </li>            
                  </ul>
                </li>
                <?php
              endif;
            ?>


              </ul>
            </li>
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>