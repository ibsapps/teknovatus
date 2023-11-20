<style>
.dt-buttons {
    float:left;
}
</style>
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Stock Report</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<div class="row" id="mainContent">
			<form action="#" method="POST" name="submit2Process"
				id="submit2Process" enctype="multipart/form-data">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-3">
									Table Report
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
						<!-- /.panel-heading -->
						<div class="panel-body">
							<form action="#" method="POST" name="batchExec" id="batchExec">
								<table width="100%"
									class="table table-striped table-bordered table-hover"
									id="dataBarang" data-src="<?Php echo $feedUri ?>">
									<thead>
										<tr>
											<th class="text-center">Nama Barang</th>
											<th class="text-center">Stock In</th>
											<th class="text-center">Stock Out</th>
											<th class="text-center">Stock</th>
											<th class="text-center">Satuan</th>
										</tr>
									</thead>
								</table>
							</form>
							<!-- /.table-responsive -->
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel -->
				</div>
			</form>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
