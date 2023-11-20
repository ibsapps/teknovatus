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
				<th>UOM</th>
				<th>Result</th>
			</tr>
		</thead>
		<tbody>
			<?php
			error_reporting(0);
			$no=1;
			$sql = "SELECT *
				FROM tbl_barang ;";
			$exc = $this->db->query($sql)->result();
			foreach($exc AS $rs){
			$buffer = $rs->buffer_stock;
			$qty = $rs->qty;
			$uom = $rs->uom;
			$result = $qty/$uom;
			if($qty <= $buffer){
			?>

				<tr>
				<td><?php echo$no++;?></td>
				<td><?php echo$rs->nama_barang;?></td>
				<td><?php echo$rs->satuan;?></td>
				<td><?php echo$rs->qty;?></td>
				<td><?php echo$rs->uom;?></td>
				
				<td><?php echo$result;?></td>
				
			</tr>




			<?php
			}}
			?>
			</tbody>
			</table>
			<hr>
			
			<h3 align="center">ORDER BARANG</h3>
			<div class="col-md-6">
			<form method="POST" action="<?php echo base_url();?>dashboard/reorder/proses">
					
			<?php
			$sql1 = "SELECT*FROM tbl_barang";
			$exc1 = $this->db->query($sql1)->result();
			foreach($exc1 AS $rs1){
			$id = $rs1->barang_id;
			$buffer1 = $rs1->buffer_stock;
			$qty1 = $rs1->qty;

			if($qty1 <= $buffer1){
			?>
			<input type="hidden" name="idbarang[]" value="<?php echo $id;?>">
			<label>Nama Barang : <?php echo $rs1->nama_barang;?></label><input type="text" class="form-control" name="qty[]"><br>
				
			<?php
			}}
			?>
			<button type="submit" class="btn btn-md btn-warning">ORDER</button>
			</form>
			</div>
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
