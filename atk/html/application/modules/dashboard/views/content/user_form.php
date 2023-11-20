<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">User Form</h1>
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
                                    <a href="<?php echo base_url() ?>dashboard/user/index" name="btnBack" class="btn btn-default">Kembali</a>
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
                            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="id_karyawan" id="drop_down_karyawan" data-src="<?Php echo $feedUri ?>">Karyawan</label>
                                    <select name="id_karyawan" id="id_karyawan" class="form-control js-data-barang-ajax"></select>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="id_roles">Roles</label>
                                    <select name="id_roles" id="id_roles" class="form-control">
                                        <?Php
                                        foreach($role_list as $rl) {
                                            if($rl['username']=="") {echo "<option value=\"".$rl['rid']."\">".$rl['role_name']."</option>";}
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="remark"><span style="visibility: hidden">Tambah</span></label>
                                    <button type="button" name="add_barang" data-src="<?Php echo $getUri ?>" class="btn btn-primary btn-sm" id="add_barang"><i class="fa fa-plus-circle"></i> Tambah</button>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
                <!-- Second Form -->
                <div class="col-lg-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Data Karyawan
                        </div>
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataListKaryawan">
                                <thead>
                                    <tr>
                                        <th class="text-center">NIK</th>
                                        <th class="text-center">Nama Karyawan</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Departement</th>
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