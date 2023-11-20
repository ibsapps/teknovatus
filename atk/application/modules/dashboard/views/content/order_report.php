<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Order Report</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row" id="mainContent">
            <form action="<?Php echo $feedUri ?>" method="POST" name="submit2Process" id="submit2Process" enctype="multipart/form-data">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="hidden" name="processForm" value="1" />
                                    <a href="<?php echo base_url() ?>dashboard/report/order" name="btnBack" class="btn btn-default">Kembali</a>
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
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="nama_barang" id="src_barang" data-src="<?Php echo $feedBarang ?>">Barang</label>
                                    <select name="nama_barang" id="nama_barang" class="form-control js-data-barang-ajax"></select>
                                    <p class="help-block"><i class="fa fa-exclamation-circle"></i> <small>Kosongkan untuk pencarian semua barang</small></p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="id_karyawan" id="src_karyawan" data-src="<?Php echo $feedKaryawan ?>">Karyawan</label>
                                    <select name="id_karyawan" id="id_karyawan" class="form-control js-data-karyawan-ajax"></select>
                                    <p class="help-block"><i class="fa fa-exclamation-circle"></i> <small>Kosongkan untuk pencarian semua karyawan</small></p>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="dari_tgl" id="dari_tgl_label">Dari</label>
                                    <input type="date" name="dari_tgl" id="dari_tgl" value="<?php echo date('m / d / Y') ?>" />
                                    <p class="help-block"><i class="fa fa-warning"></i> <small>Harus diisi</small></p>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="smp_tgl" id="smp_tgl_label">Dari</label>
                                    <input type="date" name="smp_tgl" id="smp_tgl" value="<?php echo date('m / d / Y') ?>" />
                                    <p class="help-block"><i class="fa fa-warning"></i> <small>Harus diisi</small></p>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
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
                            Data Laporan
                        </div>
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataReportIn">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Barang</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Nama Karyawan</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </form> 
        </div>
    </div>
</div>
</div>