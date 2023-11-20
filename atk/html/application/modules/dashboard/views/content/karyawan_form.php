<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Form Karyawan</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row" id="mainContent">
            <form action="#" method="POST" name="submit2Process" enctype="multipart/form-data">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="hidden" name="processForm" value="1" />
                                    <input type="hidden" name="node" value="karyawan" />
                                    <a href="<?php echo base_url() ?>dashboard/data_master/karyawan" name="btnBack" class="btn btn-default">Kembali</a>
                                </div>
                                <div class="col-md-9 text-right">
                                    <button type="submit" name="btnSave" value="<?Php echo count($dataResult); ?>" class="btn btn-warning">Simpan</button>
                                    <button type="reset" name="btnReset" id="btnReset" class="btn btn-info">Reset</button>
                                </div>
                            </div>
                            <?Php
                            $maxTab = 1;
                            if (count($dataResult) > 0 && count($dataResult) <= 10) {
                                $maxTab = count($dataResult);
                            } elseif (count($dataResult) > 10) {
                                $maxTab = 10;
                            }
                            ?>
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
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <?Php
                                $addClass = " class=\"active\"";
                                for ($x = 0; $x < $maxTab; $x++) {
                                    $y = $x + 1;
                                    echo "<li" . $addClass . "><a href=\"#" . $x . "\" data-toggle=\"tab\">Form " . $y . "</a>";
                                    $addClass = "";
                                }
                                ?>
                            </ul>
                            <div class="tab-content">
                                <?Php
                                $addClassTab = " active";
                                $dr = array('id' => '',
                                    'nik' => '',
                                    'nama_lengkap' => '',
                                    'id_department' => '',
                                    'id_jabatan' => '',
                                    'gsm' => '',
                                    'email' => '');
                                for ($x = 0; $x < $maxTab; $x++) {
                                    if (count($dataResult) > 0) {
                                        $dr = $dataResult[$x][0];
                                    }
                                    if (isset($dr['id'])) {
                                        echo "<input type=\"hidden\" name=\"cbox[]\" value=\"" . $dr['id'] . "\" id=\"id" . $x . "\" />";
                                    }
                                    ?>
                                    <div class="tab-pane fade in<?Php echo $addClassTab ?>" id="<?Php echo $x; ?>">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="nik">NIK</label>
                                                <input type="text" name="nik[]" value="<?Php echo $dr['nik']; ?>" class="form-control" id="nama_barang<?Php echo $x; ?>" placeholder="Masukkan Nomor NIK" />                                                
                                            </div>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="nama_lengkap">Nama Karyawan</label>
                                                    <input type="text" name="nama_lengkap[]" value="<?Php echo $dr['nama_lengkap']; ?>" class="form-control" id="nama_lengkap<?Php echo $x; ?>" placeholder="Masukkan Nama Lengkap" />                                                
                                                </div>
                                                <div class="form-group">
                                                    <label for="id_departement">Departement</label>
                                                    <select name="id_departement[]" id="id_kategori<?Php echo $x; ?>" class="form-control">
                                                        <option value="">Pilih Departement</option>
                                                        <?Php
                                                        foreach ($list_depertement as $ld) {
                                                            if ($ld['id'] == $dr['id_departement']) {
                                                                $selected = " selected";
                                                            } else {
                                                                $selected = "";
                                                            }
                                                            ?>
                                                            <option value="<?Php echo $ld['id'] ?>"<?Php echo $selected ?>><?Php echo $ld['dept'] ?></option>
                                                        <?Php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="gsm">Nomor Mobile</label>
                                                <input type="text" name="gsm[]" value="<?Php echo $dr['gsm']; ?>" class="form-control" id="nama_barang<?Php echo $x; ?>" placeholder="Masukkan Nomor Handphone" />                                                
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="email">Alamat Email</label>
                                                <input type="text" name="email[]" value="<?Php echo $dr['email']; ?>" class="form-control" id="nama_barang<?Php echo $x; ?>" placeholder="Masukkan Alamat Email" />                                                
                                            </div>
                                        </div>
                                    </div>
                                    <?Php $addClassTab = "";
                                } ?>
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
</div>