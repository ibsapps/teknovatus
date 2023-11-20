<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">User Info</h1>
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
                                    <input type="hidden" name="node" value="user_info" />
                                    <a href="<?php echo base_url() ?>dashboard/user/index" name="btnBack" class="btn btn-default">Kembali</a>
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
                                    echo "<li" . $addClass . "><a href=\"#" . $x . "\" data-toggle=\"tab\"> " . $dataResult[$x]['username'] . "</a>";
                                    $addClass = "";
                                }
                                ?>
                            </ul>
                            <div class="tab-content">
                                <?Php
                                $addClassTab = " active";
                                $dr = array('cbox' => '', 'username' => '', 'nama_lengkap' => '', 'email' => '', 'mobile_number' => '', 'status' => '', 'role_name' => '', 'rid' => '');
                                for ($x = 0; $x < $maxTab; $x++) {
                                    if (count($dataResult) > 0) {
                                        $dr = $dataResult[$x];
                                    }
                                    if (isset($dr['cbox'])) {
                                        echo "<input type=\"hidden\" name=\"cbox[]\" value=\"" . $dr['cbox'] . "\" id=\"user_id" . $x . "\" />";
                                    }
                                    ?>
                                    <div class="tab-pane fade in<?Php echo $addClassTab ?>" id="<?Php echo $x; ?>">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" name="username[]" disabled="disabled" value="<?Php echo $dr['username']; ?>" class="form-control" id="username<?Php echo $x; ?>" placeholder="Masukkan Username" />                                                
                                            </div>
                                            <div class="form-group">
                                                <label for="email_addr">Alamat Email</label>
                                                <input type="email" name="email_addr[]" disabled="disabled" value="<?Php echo $dr['email']; ?>" class="form-control" id="email_addr<?Php echo $x; ?>" placeholder="Masukkan Alamat Email" />                                                
                                            </div>
                                            <div class="form-group">
                                                <label for="mobile_number">Mobile Number</label>
                                                <input type="tel" name="mobile_number[]" value="<?Php echo $dr['mobile_number']; ?>" class="form-control" id="mobile_number<?Php echo $x; ?>" placeholder="Masukkan Nomor HP" />                                                
                                            </div>
                                            <div class="form-group">
                                                <label for="user_roles">Roles</label>
                                                <select name="user_roles[]" id="user_roles<?Php echo $x; ?>" class="form-control">
                                                    <option value="">Pilih Roles</option>
                                                    <?Php
                                                    foreach ($role_list as $rl) {
                                                        $selected = "";
                                                        if($rl['rid']==$dr['rid']) {$selected=" selected";}
                                                        echo "<option value=\"" . $rl['rid'] . "\"".$selected.">" . $rl['role_name'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label for="full_name">Nama Lengkap</label>
                                                <input type="text" name="full_name[]" value="<?Php echo $dr['nama_lengkap']; ?>" class="form-control" id="full_name<?Php echo $x; ?>" placeholder="Masukkan Nama Lengkap" />                                                
                                            </div>
                                            <div class="form-group">
                                                <label for="passwd">Password</label>
                                                <input type="password" name="passwd[]" value="" class="form-control" id="passwd<?Php echo $x; ?>" placeholder="Masukkan Password Jika Ingin Dirubah" />                                                
                                            </div>
                                            <div class="form-group">
                                                <label for="c_passwd">Confirm Password</label>
                                                <input type="password" name="c_passwd[]" value="" class="form-control" id="c_passwd<?Php echo $x; ?>" placeholder="Ketik ulang password diatas" />                                                
                                            </div>
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select name="status[]" id="status<?Php echo $x; ?>" class="form-control">
                                                    <option value="0"<?Php if ($dr['status'] == 0) {
                                                    echo " selected";
                                                } ?>>Tidak Aktif</option>
                                                    <option value="1"<?Php if ($dr['status'] == 1) {
                                                    echo " selected";
                                                } ?>>Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
    <?Php $addClassTab = "";
} ?>
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
