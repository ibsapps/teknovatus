<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Master Barang</h1>
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
                                            $btnDesign = "btn-warning";
                                            break;
                                        case "delete":
                                            $btnDesign = "btn-danger";
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
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataBarang" data-src="<?Php echo $feedUri ?>">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" name="select_all" id="select_all" value="1" /></th>
                                        <th>Nama Barang</th>
                                        <th>Satuan</th>
                                        <th>Kategori</th>
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
