<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Form Barang</h1>
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
                                    <input type="hidden" name="node" value="barang" />
                                    <a href="<?php echo base_url() ?>dashboard/data_master/barang" name="btnBack" class="btn btn-default">Kembali</a>
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
                            if(isset($query_result)) {
                                if($query_result) {
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
                                $dr = array('nama_barang'=>'', 'satuan'=>'', 'id_kategori'=>'', 'barang_id'=>'', 'file'=>'');
                                for ($x = 0; $x < $maxTab; $x++) {
                                    if(count($dataResult)>0) {$dr = $dataResult[$x][0];}
                                    if(isset($dr['barang_id'])) {
                                        echo "<input type=\"hidden\" name=\"cbox[]\" value=\"".$dr['barang_id']."\" id=\"barang_id". $x ."\" />";
                                    }
                                    ?>
                                    <div class="tab-pane fade in<?Php echo $addClassTab ?>" id="<?Php echo $x; ?>">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="nama_barang">Nama Barang</label>
                                                <input type="text" name="nama_barang[]" value="<?Php echo $dr['nama_barang']; ?>" class="form-control" id="nama_barang<?Php echo $x; ?>" placeholder="Masukkan Nama Barang" />                                                
                                            </div>
                                            <div class="form-group">
                                                <label for="id_kategori">Kategori</label>
                                                <select name="id_kategori[]" id="id_kategori<?Php echo $x; ?>" class="form-control">
                                                    <option value="">Pilih Kategori</option>
                                                    <?Php
                                                    foreach($list_kategori as $lk) {
                                                    if($lk['id']==$dr['id_kategori']) {$selected = " selected";} else {$selected="";}
                                                    ?>
                                                    <option value="<?Php echo $lk['id'] ?>"<?Php echo $selected ?>><?Php echo $lk['kategori'] ?></option>
                                                    <?Php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="opt_satuan">Satuan</label>
                                                <select itemref="satuan" name="opt_satuan[]" id="opt_satuan<?Php echo $x; ?>" class="form-control">
                                                    <option value="">Pilih Satuan</option>
                                                    <option value="addNew">[+] Tambah Baru</option>
                                                    <?Php
                                                    foreach($list_satuan as $ls) {
                                                        if($ls['satuan']==$dr['satuan']) {$selected = " selected";} else {$selected="";}
                                                        echo "<option value=\"".$ls['satuan']."\"".$selected.">".$ls['satuan']."</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <div style="display:none;" class="hidden_opt_satuan<?Php echo $x; ?>">
                                                    <input type="text" name="satuan[]" class="form-control" id="satuan<?Php echo $x; ?>" placeholder="Masukkan Satuan Barang" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="file_gambar">Gambar</label>
                                                <input type="file" name="file_gambar[]" id="file_gambar<?Php echo $x; ?>" placeholder="Masukkan Gambar Barang">
                                                <p class="help-block"><i class="fa fa-exclamation-circle"></i> <small>Max width:1024, height:768, size:800kb.</small></p>
                                                <input type="hidden" name="uploaded_file[]" id="uploaded_file_gambar<?Php echo $x; ?>" value="<?Php echo $dr['file'] ?>" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="imgPreview_file_gambar<?Php echo $x; ?>">
                                            <div class="form-group">
                                                <?Php
                                                if(is_file(FCPATH.'assets/images/upload/barang/'.$dr['file'])) {
                                                    $fileSrc = base_url()."assets/images/upload/barang/".$dr['file'];
                                                } else {
                                                    $fileSrc = base_url()."assets/images/No_Image_Available.jpg";
                                                }
                                                ?>
                                                <img id="preview_file_gambar<?Php echo $x; ?>" src="<?php echo $fileSrc; ?>" class="img-responsive" alt="No Images Available" />
                                            </div>
                                        </div>
                                    </div>
                                <?Php 
                                $addClassTab = "";
                                } 
                                ?>
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
