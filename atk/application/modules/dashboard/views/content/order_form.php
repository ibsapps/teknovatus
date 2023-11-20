<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Order Form</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row" id="mainContent">
            <form action="<?Php echo $processUri ?>" method="POST" name="submit2Process" id="submit2Process" enctype="multipart/form-data">
                <?Php if (isset($order_id)) { ?>
                <input type="hidden" name="order_id" value="<?Php echo $order_id; ?>" />
                <?Php } ?>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="<?php echo base_url() ?>dashboard/order/index" name="btnBack" class="btn btn-default">Kembali</a>
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
                                    <label for="nama_barang" id="drop_down_nama_barang" data-src="<?Php echo $feedUri ?>">Nama Barang</label>
                                    <select name="nama_barang" id="nama_barang" class="form-control js-data-barang-ajax"></select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="jml_barang">Jumlah (Qty)</label>
                                    <input type="text" name="jml_barang" value="" class="form-control" id="jml_barang" placeholder="Jumlah Barang" />
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="remark">Catatan</label>
                                    <input type="text" name="remark" value="" class="form-control" id="remark" placeholder="Tuliskan catatan jika diperlukan" />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="remark"><span style="visibility: hidden">Tambah</span></label>
                                    <button type="button" name="add_barang" class="btn btn-primary" data-src="<?Php echo $feedCheck ?>" id="add_barang"><i class="fa fa-plus-circle"></i> Tambah</button>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
                <!-- Second Form -->
                <div class="col-lg-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            List Order Barang
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
                                <tbody>
                                    <?php foreach($dataResult as $rs) { ?>
                                    <tr>
                                        <td><?Php echo "<input type=\"hidden\" name=\"id_barang[]\" value=\"" . $rs['barang_id'] . "\" />".$rs['nama_barang']; ?></td>
                                        <td><?Php echo "<input type=\"hidden\" name=\"qty_barang[]\" value=\"" . $rs['jumlah'] . "\" />".$rs['jumlah']; ?></td>
                                        <td><?Php echo "<input type=\"hidden\" name=\"rem_barang[]\" value=\"" . $rs['remark'] . "\" />".$rs['remark']; ?></td>
                                        <td><button class="removeRow btn btn-xs btn-danger"><i class="fa fa-trash"></i> Hapus</button></td>
                                    </tr>
                                    <?Php } ?>
                                </tbody>
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