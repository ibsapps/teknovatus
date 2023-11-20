<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Stock Out List</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row" id="mainContent">
            <form action="#" method="POST" name="submit2Process" id="submit2Process" enctype="multipart/form-data" data-src="<?Php echo $feedUri ?>">
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
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#request" data-toggle="tab">Request</a></li>
                                <li><a href="#collect" data-toggle="tab">Collect</a></li>
                                <li><a href="#approve" data-toggle="tab">Approve</a></li>
                                <li><a href="#deliver" data-toggle="tab">Deliver</a></li>
                                <li><a href="#reject" data-toggle="tab">Reject</a></li>
                            </ul>
                            <div class="tab-content">                                
                                <div class="tab-pane fade in active" id="request">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table width="100%" class="table table-striped table-bordered table-hover" id="orderRequest">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"><input type="checkbox" name="request_cbox" class="all_cbox" /></th>
                                                    <th class="text-center">Order Group</th>
                                                    <th class="text-center">Order Item</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-center">Satuan</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Hidden Status</th>
                                                    <th class="text-center">Remark</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade in" id="collect">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table width="100%" class="table table-striped table-bordered table-hover" id="orderCollect">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"><input type="checkbox" name="collect_cbox" class="all_cbox" /></th>
                                                    <th class="text-center">Order Group</th>
                                                    <th class="text-center">Order Item</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-center">Satuan</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Hidden Status</th>
                                                    <th class="text-center">Remark</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade in" id="approve">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table width="100%" class="table table-striped table-bordered table-hover" id="orderApprove">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"><input type="checkbox" name="approve_cbox" class="all_cbox" /></th>
                                                    <th class="text-center">Order Group</th>
                                                    <th class="text-center">Order Item</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-center">Satuan</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Hidden Status</th>
                                                    <th class="text-center">Remark</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade in" id="deliver">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table width="100%" class="table table-striped table-bordered table-hover" id="orderDeliver">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Order Group</th>
                                                    <th class="text-center">Order Item</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-center">Satuan</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Hidden Status</th>
                                                    <th class="text-center">Remark</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade in" id="reject">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table width="100%" class="table table-striped table-bordered table-hover" id="orderReject">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Order Group</th>
                                                    <th class="text-center">Order Item</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-center">Satuan</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Hidden Status</th>
                                                    <th class="text-center">Remark</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
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