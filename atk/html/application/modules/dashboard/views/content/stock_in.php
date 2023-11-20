<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Stock In</h1>

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
                                            $btnDesign = "btn-danger";
                                            break;
                                        case "update":
                                            $btnDesign = "btn-primary";
                                            break;
                                        case "delete":
                                            $btnDesign = "btn-warning";
                                            break;
                                    }
                                    echo " <a href=\"" . $menuUri . "\" class=\"btn " . $btnDesign . "\">" . $menuPos['pName'] . "</a> ";
                                }
                            }
                        }
                        ?>
<!-- <a class="btn btn-success" href="http://atk.ibsmulti.com/dashboard/excel_export" role="button" method="POST" enctype="multipart/form-data">Export Excell
</a>
<a class="btn btn-danger" href="http://atk.ibsmulti.com/dashboard/excel_export/pdf" target="_blank" role="button" method="POST" enctype="multipart/form-data">Export PDF
</a>
<a class="btn btn-warning" href="http://atk.ibsmulti.com/dashboard/excel_export/closing" target="_blank" role="button" method="POST" enctype="multipart/form-data">Closing
</a> -->
               
                    </div>
                    <div id="notifyAlert"></div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form action="#" method="POST" name="batchExec" id="batchExec">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataBarang" data-src="<?Php echo $feedUri ?>">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Barang</th>
                                        <th class="text-center">Stock In</th>
                                        <th class="text-center">Stock Out</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-center">Satuan</th>
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
