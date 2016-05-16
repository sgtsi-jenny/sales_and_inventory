<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
                <a class="navbar-brand" href="index.php">SGTSI <?php echo WEBAPP;?></a>

    </div>
</nav>  
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-left navbar-collapse">
                <?php
                    if($_SESSION[WEBAPP]['user']['user_type']==1 || $_SESSION[WEBAPP]['user']['user_type']==2):
                ?>
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard "></i> Dashboard</a>
                        </li>
                        
                <?php
                    endif;
                ?>
                        <li>
                            <a href="my_calendar.php"><i class="fa fa-calendar-o"></i> Calendar</a>
                        </li>
                        <li>
                            <a href="organizations.php"><i class="fa fa-users "></i> Organizations</a>
                        </li>
                        <li>
                            <a href="opportunities.php"><i class="fa fa-lightbulb-o "></i> Oppurtunities</a>
                        </li>
                        <li>
                            <a href="contacts.php"><i class="fa fa-phone "></i> Contacts</a>
                        </li>
                        <li class='dropdown'>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-file "></i> Reports  <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li>
                                    <a href="reports_status.php"><i class ="fa fa-file-text"> </i> Opportunity Statuses</a>
                                </li>
                                <li>
                                    <a href="reports_closed.php"> <i class ="fa fa-file-text"> </i> Closed Opportunities</a>
                                </li>
                                <li>
                                    <a href="reports_activities_currentmonth.php"><i class ="fa fa-file-text"> </i> Event Reports</a>
                                </li>
                                <li>
                                    <a href="reports_contacts.php"><i class ="fa fa-file-text"> </i> Contacts Reports</a>
                                </li>
                                <?php
                                    if($_SESSION[WEBAPP]['user']['user_type']==1):
                                ?>
                                <li>
                                    <a href="report_asset_activity.php"><i class="fa fa-book"></i> Audit Logs </a>
                                </li>
                                <?php
                                    endif;
                                ?>

                            </ul>
                        </li>
                <li class='dropdown'>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-list-alt "></i> All  <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                
                                <li>
                                    <a href="opportunities.php"><i class ="fa fa-lightbulb-o"> </i> Opportunities</a>
                                </li>
                                <li>
                                    <a href="organizations.php"> <i class ="fa fa-users"> </i> Organizations</a>
                                </li>
                                <li>
                                    <a href="contacts.php"> <i class ="fa fa-phone"> </i> Contacts</a>
                                </li>
                                <li>
                                    <a href="products.php"> <i class ="fa fa-stack-exchange"> </i> Products</a>
                                </li>
                                <li>
                                    <a href="events.php"> <i class ="fa fa-list-alt"> </i> Event / To Do</a>
                                </li>
                                <?php
                                    if($_SESSION[WEBAPP]['user']['user_type']==1):
                                ?>
                                    <li>
                                        <a href="documents.php"> <i class ="fa fa-folder-open-o"> </i> Documents</a>
                                    </li>
                                    <li>
                                        <a href="quotes.php"> <i class ="fa fa-file-text"> </i> Quotes</a>
                                    </li>
                                    <li>
                                        <a href="user.php"> <i class ="fa fa-user"> </i> Users</a>
                                    </li>
                                <?php
                                    endif;
                                ?>
                            </ul>
                </li>
               
               
                <!-- /.dropdown -->
            </ul>
            <ul class="nav navbar-top-links navbar-right navbar-collapse">
                <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-plus fa-fw"></i> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="frm_opportunities.php"><i class="fa fa-lightbulb-o"></i> Opportunity</a>
                            </li>
                            <li><a href="frm_organizations.php"><i class="fa fa-group"></i> Organization</a>
                            </li>
                            <li><a href="frm_products.php"><i class="fa fa-stack-exchange"></i> Product</a>
                            </li>
                            <li><a href="frm_event.php"><i class="fa fa-list-alt"></i> Event/To Do</a>
                            </li>
                            <li><a href="frm_contacts.php"><i class="fa fa-phone"></i> Contact</a>
                            </li>
                            <?php
                                if(AllowUser(array(1))):
                            ?>                            
                            <li><a href="frm_documents.php"><i class="fa fa-tasks"></i> Document</a>
                            </li>
                            <li><a href="frm_quotes.php"><i class="fa fa-file-text"></i> Quote</a>
                            </li>
                            <li><a href="frm_users.php"><i class="fa fa-user"></i> User</a>
                            </li>
                            <?php
                                endif;
                            ?>
                        </ul>
                        <!-- /.dropdown-create new -->
                    </li>   
                <?php
                    if(AllowUser(array(1))):
                ?>  
                <li class="dropdown">
                    <a href=""><i class="fa fa-gear "></i></a>
                </li>
               <?php
                    endif;
                ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>Welcome, <?php echo $_SESSION[WEBAPP]['user']['first_name']?>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
            </ul>

</nav>
 