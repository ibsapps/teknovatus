<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Stock In Report</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row" id="mainContent">
            <form action="#" method="POST" name="submit2Process" id="submit2Process" enctype="multipart/form-data">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="hidden" name="processForm" value="1" />
                                    <a href="<?php echo base_url() ?>dashboard/stock_in/index" name="btnBack" class="btn btn-default">Kembali</a>
                                </div>                                
                            </div>
                        </div>
                        <div id="notifyAlert">
                            <?Php
                            if (isset($query_result)) {
                                if ($query_result) {
                                    echo "<div class=\"alert alert-success alert-dismissible fade in text-center\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Data telah diproses</div>";
                                } else {
                                    echo "<div class=\"alert alert-danger alert-dismissible fade in text-center\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Data gagal diproses</div>";
                                }
                            }
                            ?>
                        </div>
                        <div class="panel-body">
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="no_po">No. PO</label>
                                    <input type="text" name="no_po" value="" class="form-control" id="no_po" placeholder="Masukkan Nomor PO" />
                                    <p class="help-block"><i class="fa fa-exclamation-circle"></i> <small>Kosongkan jika tidak perlu</small></p>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="no_po">Tanggal PO</label>
                                    <input type="text" name="date_po" value="" class="form-control" id="date_po" placeholder="2019-01-01" />
                                    <p class="help-block"><i class="fa fa-exclamation-circle"></i> <small>XXXX-XX-XX</small></p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="no_po">No. Delivery</label>
                                    <input type="text" name="delivery_no" value="" class="form-control" id="delivery_no" placeholder="Masukkan Nomor Delivery" />
                                    <p class="help-block"><i class="fa fa-exclamation-circle"></i> <small>Kosongkan jika tidak perlu</small></p>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="date_in">Tanggal Delivery</label>
                                    <input type="text" name="date_in" value="" class="form-control" id="date_in" placeholder="2019-01-01" />
                                    <p class="help-block"><i class="fa fa-exclamation-circle"></i> <small>XXXX-XX-XX</small></p>
                                </div>
                            </div>
                            
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="iquiry_item"><span style="visibility: hidden">Cari</span></label>
                                    <button type="button" name="iquiry_item" class="btn btn-primary" id="iquiry_item"><i class="fa fa-search"></i> Tampilkan</button>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
                <!-- Second Form -->
                <div class="col-lg-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Data Barang
                        </div>
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataBarangIn">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Barang</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Catatan</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="panel panel-footer text-center">
                            <button type="submit" name="btnSave" value="<?Php echo count($dataResult); ?>" class="btn btn-warning">Simpan</button>
                            <button type="reset" name="btnReset" id="btnReset" class="btn btn-info">Reset</button>
                        </div>
                    </div>
                </div>
            </form> 
        </div>
    </div>
</div>
</div>