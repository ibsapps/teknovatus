<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Closing Range (<?Php echo $rDate ?>)</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row" id="mainContent">
            <form action="#" method="POST" name="submit2Closing" id="submit2Closing" enctype="multipart/form-data">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="<?php echo base_url() ?>dashboard/index" name="btnBack" class="btn btn-default">Kembali</a>
                                </div>
                            </div>
                        </div>
                        <div id="notifyAlert">
                            <?Php echo "<div class=\"alert hidden alert-success alert-dismissible fade in text-center\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Data telah diproses</div>"; ?>
                        </div>
                        <div class="panel-body">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table width="100%" class="table table-striped table-bordered table-hover" id="orderRequest" data-src="<?Php echo $feedUri ?>">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Order Group</th>
                                            <th class="text-center">User</th>
                                            <th class="text-center">Nama Barang</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Action</th>
                                            <th class="text-center">Remark</th>
                                        </tr>
                                

				    </thead>
                                </table>
				
                            </div>    
                        </div>
                        <div class="panel panel-footer text-center">
                            <button type="submit" name="btnSave" class="btn btn-warning">Simpan</button>
                            <button type="reset" name="btnReset" id="btnReset" class="btn btn-info">Reset</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
