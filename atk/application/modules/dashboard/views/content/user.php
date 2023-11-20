<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">User List</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row" id="mainContent">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?Php
                        $posAcc = 2;
                        foreach ($menuPanel as $key => $menu) {
                            foreach ($menu as $key => $menuPos) {
                                if ($menuPos['pos'] == $posAcc && $menuPos['ctrlName'] == $this->uri->segment(2)) {
                                    if ($menuPos['openUrl'] != "") {
                                        $menuUri = $menuPos['openUrl'];
                                    } else {
                                        $menuUri = $menuPos['closeUrl'];
                                    }
                                    $btnDesign = "btn-info";
                                    switch ($menuPos['nodeName']) {
                                        case "input":
                                            $btnDesign = "btn-info";
                                            break;
                                        case "update":
                                            $btnDesign = "btn-primary";
                                            break;
                                        case "delete":
                                            $btnDesign = "btn-warning";
                                            break;
                                    }
                                    echo " <a href=\"" . $menuUri . "\" class=\"batch btn " . $btnDesign . "\">" . $menuPos['pName'] . "</a> ";
                                }
                            }
                        }
                        ?>
                    </div>
                    <div id="notifyAlert"></div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form action="#" method="POST" name="batchExec" id="batchExec">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataUser" data-src="<?Php echo $feedUri ?>">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" name="select_all" id="select_all" value="1" /></th>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Rules</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                            </table>
                        </form>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
