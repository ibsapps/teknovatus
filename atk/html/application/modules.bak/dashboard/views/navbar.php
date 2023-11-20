<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo base_url() ?>"><i class="fa fa-user"></i> <?Php echo $usrInfo->c_fullname ?></a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
        <!-- <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a> 
            <ul class="dropdown-menu dropdown-alerts">
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-comment fa-fw"></i> New Comment
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                            <span class="pull-right text-muted small">12 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-envelope fa-fw"></i> Message Sent
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-tasks fa-fw"></i> New Task
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <div>
                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                            <span class="pull-right text-muted small">4 minutes ago</span>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a class="text-center" href="#">
                        <strong>See All Alerts</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
            </ul>
        </li> -->
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <?Php
                $posAcc = 3;
                foreach ($menuPanel as $key => $menu) {
                    foreach ($menu as $key => $menuPos) {
                        if ($menuPos['pos'] == $posAcc) {
                            if ($menuPos['openUrl'] != "") {
                                $menuUri = $menuPos['openUrl'];
                            } else {
                                $menuUri = $menuPos['closeUrl'];
                            }
                            echo "<li><a href=\"" . $menuUri . "\">" . $menuPos['pName'];
                            echo "</a>";
                            echo "</li>";
                        }
                    }
                }
                ?>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li style="padding: 0px 5px 0px 5px">
                    <img src="<?php echo base_url() ?>assets/images/ibs.png" class="img-responsive" />
                </li>
                <?Php
                $posForm = 1;
                foreach ($menuPanel as $key => $menu) {
                    foreach ($menu as $key => $menuPos) {
                        if ($menuPos['pos'] == $posForm) {
                            if ($menuPos['openUrl'] != "") {
                                $menuUri = $menuPos['openUrl'];
                            } else {
                                $menuUri = $menuPos['closeUrl'];
                            }
                ?>
                <li class="sidebar-search">
                    <form action="<?php echo $menuUri ?>" method="POST" name="cari_barang" id="cari_barang">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="<?Php echo $menuPos['pName']; ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                    <!-- /input-group -->
                </li>
                <?Php }}} ?>
                <li>
                    <a href="<?php echo base_url() ?>dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <?Php
                $posData = 0;
                foreach ($menuPanel as $key => $menu) {
                    foreach ($menu as $key => $menuPos) {
                        if ($menuPos['pos'] == $posData) {
                            if ($menuPos['openUrl'] != "") {
                                $menuUri = $menuPos['openUrl'];
                            } else {
                                $menuUri = $menuPos['closeUrl'];
                            }
                            echo "<li><a href=\"" . $menuUri . "\">" . $menuPos['pName'];
                            if (count($menuPos['sub']) > 0) {
                                echo "<span class=\"fa arrow\"></span></a>";
                                echo "<ul class=\"nav nav-second-level\">";
                                foreach ($menuPos['sub'] as $key => $subMenu) {
                                    if ($subMenu['openUrl'] != "") {
                                        $menuUri = $subMenu['openUrl'];
                                    } else {
                                        $menuUri = $subMenu['closeUrl'];
                                    }
                                    echo "<li>";
                                    echo "    <a href=\"" . $menuUri . "\">" . $subMenu['pName'] . "</a>";
                                    echo "</li>";
                                }
                                echo "</ul>";
                            } else {
                                echo "</a>";
                            }
                            echo "</li>";
                        }
                    }
                }
                ?>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>