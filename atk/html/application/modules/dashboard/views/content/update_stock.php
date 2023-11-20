<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row" id="mainContent">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="<?php echo base_url() ?>dashboard/index" name="btnBack" class="btn btn-default">Kembali</a>
				   
				                                </div>
                            </div>
                        </div>
                              <div class="panel-body">
				<?php 
				$user = $this->session->userdata('userid');
						
				?>			
                          
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <table class="table table-bordered" id="table" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Barang</th>
				<th>Satuan</th>
				<th>Qty</th>
				<th>Qty Try </th>
				<th>Buffer Stock</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no=1;
			$sql = "SELECT *
				FROM tbl_barang ;";
			$exc = $this->db->query($sql)->result();
			foreach($exc AS $rs){
			?>

				<tr>
				<td><?php echo$no++;?></td>
				<td><?php echo$rs->nama_barang;?></td>
				<td><?php echo$rs->satuan;?></td>
				<td><?php echo$rs->qty;?></td>
				<td><?php echo$rs->qty_try;?></td>
				<td><?php echo$rs->buffer_stock;?></td>
			</tr>
			<?php
			}
			?>
			</tbody>
			</table>
                            </div>    
                        </div>
                        <div class="panel panel-footer text-center">
                          <!--  <button type="submit" name="btnSave" class="btn btn-warning">Simpan</button>
                            <button type="reset" name="btnReset" id="btnReset" class="btn btn-info">Reset</button>-->
                        </div>
                    </div>
                </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
