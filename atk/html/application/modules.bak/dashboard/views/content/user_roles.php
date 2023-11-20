<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">User Roles</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<div class="row" id="mainContent">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-3">
								<a href="<?php echo base_url() ?>dashboard/user/index"
									name="btnBack" class="btn btn-default">Kembali</a>
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
								<label for="id_groups">Groups</label> <select name="id_groups"
									id="id_groups" class="form-control">
                                        <?Php
                                        foreach ($group_list as $gl) {
                                            echo "<option value=\"" . $gl['gid'] . "\">" . $gl['group_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
								<p class="help-block">
									<i class="fa fa-exclamation-circle"></i> <small>Pilih salah
										satu group</small>
								</p>
							</div>
						</div>
						<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group">
								<label for="new_role">Roles</label> <input type="text"
									name="new_role" value="" class="form-control" id="new_role"
									placeholder="Nama Roles Baru" />
								<p class="help-block">
									<i class="fa fa-exclamation-circle"></i> <small>Isi roles baru</small>
								</p>
							</div>
						</div>
						<div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group">
								<label for="remark"><span style="visibility: hidden">Tambah</span></label>
								<button type="button" name="add_new_roles_realtime"
									data-src="<?Php echo $getUri ?>" class="btn btn-primary btn-sm"
									id="add_new_roles_realtime">
									<i class="fa fa-plus-circle"></i> Tambah
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Second Form -->
			<form action="#" method="POST" name="submit2Process"
				id="submit2Process" enctype="multipart/form-data">
				<input type="hidden" name="processForm" value="1" />
				<div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading">
							<div class="form-group">
								<label for="id_roles">Pilih Roles</label> <select
									name="id_roles" id="id_roles" class="form-control">
                                        <?Php
                                        foreach ($group_role_list as $rl) {
                                            if ($rl['username'] == "") {
                                                echo "<option value=\"" . $rl['rid'] . "\">" . $rl['role_name'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
							</div>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-xs-12 col-md-6">
    								<table width="100%" class="table table-striped table-bordered table-hover" id="dataPanel" data-src="<?Php echo $feedUriPanel ?>">
                                        <thead>
                                            <tr>
                                                <th><i class="fa fa-list-alt"></i> All Panel</th>
                                                <th>Action</th>
                                                <th>location</th>
                                                <th>location ID</th>
                                            </tr>
                                        </thead>
                                	</table>
								</div>

								<div class="col-xs-12 col-md-6">
									<table width="100%" class="table table-striped table-bordered table-hover" id="dataR2Panel" data-src="<?Php echo $feedUriR2Panel ?>">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th class="text-right">Roled Panel <i class="fa fa-list-alt"></i></th>
                                                <th>location</th>
                                                <th>location ID</th>
                                            </tr>
                                        </thead>
                                	</table>
								</div>
							</div>
						</div>
						<!--  
						<div class="panel panel-footer text-center">
							<button type="submit" name="btnSave"
								value="<?Php echo count($dataResult); ?>"
								class="btn btn-warning">Simpan</button>
							<button type="reset" name="btnReset" id="btnReset"
								class="btn btn-info">Reset</button>
						</div>
						-->
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>