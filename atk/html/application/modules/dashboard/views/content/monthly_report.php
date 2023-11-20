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
                            	 <div class="col-lg-12">
                                <div class="col-lg-3">
                                    <!-- <a href="<?php echo base_url() ?>dashboard/index" name="btnBack" class="btn btn-default">Kembali</a> -->
				   					<form method="get" action="">
        <label>Filter Berdasarkan</label><br>
        <select class="form-control" name="filter" id="filter">
            <option value="">Pilih</option>
            <option value="1">Per Tanggal</option>
            <option value="2">Per Bulan</option>
            <option value="3">Per Tahun</option>
        </select>
        <br /><br />
        <div id="form-tanggal">
            <label>Tanggal</label><br>
            <input class="form-control" type="date" name="tanggal" class="input-tanggal" />
            <br /><br />
        </div>
    </div>
         <div class="col-lg-3">
        <div id="form-bulan">
            <label>Bulan</label><br>
            <select class="form-control" name="bulan">
                <option value="">Pilih</option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
            <br /><br />
        </div>
    </div>
     <div class="col-lg-3">
        <div id="form-tahun">
            <label>Tahun</label><br>
            <select class="form-control" name="tahun">
                <option value="">Pilih</option>
                <?php
                foreach($option_tahun as $data){ // Ambil data tahun dari model yang dikirim dari controller
                    echo '<option value="'.$data->tahun.'">'.$data->tahun.'</option>';
                }
                ?>
            </select>
            <br /><br />
        </div>
    </div>
        <button class="btn btn-primary" type="submit">Tampilkan</button>
    </form>
    <a href="<?php echo base_url() ?>dashboard/excel_export/cetak" name="btnBack" class="btn btn-success">Cetak</a>
</div>
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
				<th>Stock Awal</th>
				<th>Stock Keluar </th>
				<th>Stock Akhir</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no=1;
			
			$sql = "SELECT 
                        br1.barang_id,br1.nama_barang, 
                        IFNULL((ts.totalstockin),0)+br1.qty AS tot_in, 
                        IFNULL(out2.tot_out,0) AS tot_out,
                        IFNULL((IFNULL((ts.totalstockin),0)+br1.qty-IFNULL(out2.tot_out,0)),0) AS stock_ready,
                        br1.satuan
                        FROM tbl_barang br1 
                        LEFT JOIN tbl_stock_in_item in1 ON br1.barang_id=in1.barang_id 
                        LEFT JOIN tbl_stock_in in2 ON in1.stockin_id=in2.stockin_id 
                        left join tbl_stock_out_item stoko on br1.barang_id = stoko.barang_id
                        left join totalstokin ts on br1.barang_id = ts.barang_id
						left join tbl_order ord on stoko.order_id = ord.order_id
						left join t_users tu on ord.order_by = tu.id
						left join t_user_roles tur on tu.id = tur.fk_uid
						left join t_roles tr on tur.fk_rid = tr.rid
						left join t_groups gr on gr.gid = tr.fk_gid
						left join e_department ed on ed.id=gr.fk_dept_id
                        LEFT JOIN (
                        SELECT barang_id, SUM(so1.stock_out_qty) AS tot_out FROM tbl_stock_out_item so1
                        LEFT JOIN tbl_order or1 ON or1.order_id=so1.order_id 
                        LEFT JOIN tbl_stock_out out1 ON or1.order_id=out1.order_id 
                        WHERE or1.status='3' 
                        GROUP BY so1.barang_id 
                        ) out2 ON out2.barang_id=br1.barang_id 
                                             
                        GROUP BY br1.nama_barang";
			$exc = $this->db->query($sql)->result();
			foreach($exc AS $rs){
			?>

				<tr>
				<td><?php echo$no++;?></td>
				<td><?php echo$rs->nama_barang;?></td>
				<td><?php echo$rs->satuan;?></td>
				<td><?php echo$rs->tot_in;?></td>
				<td><?php echo$rs->tot_out;?></td>
				<td><?php echo$rs->stock_ready;?></td>
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
 
   <script type="text/javascript" src="<?php echo base_url('assets/js/jquery/jquery.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>"></script>
<script>
    $(document).ready(function(){ // Ketika halaman selesai di load
        $('.input-tanggal').datepicker({
            dateFormat: 'yy-mm-dd' // Set format tanggalnya jadi yyyy-mm-dd
        });
        $('#form-tanggal, #form-bulan, #form-tahun').hide(); // Sebagai default kita sembunyikan form filter tanggal, bulan & tahunnya
        $('#filter').change(function(){ // Ketika user memilih filter
            if($(this).val() == '1'){ // Jika filter nya 1 (per tanggal)
                $('#form-bulan, #form-tahun').hide(); // Sembunyikan form bulan dan tahun
                $('#form-tanggal').show(); // Tampilkan form tanggal
            }else if($(this).val() == '2'){ // Jika filter nya 2 (per bulan)
                $('#form-tanggal').hide(); // Sembunyikan form tanggal
                $('#form-bulan, #form-tahun').show(); // Tampilkan form bulan dan tahun
            }else{ // Jika filternya 3 (per tahun)
                $('#form-tanggal, #form-bulan').hide(); // Sembunyikan form tanggal dan bulan
                $('#form-tahun').show(); // Tampilkan form tahun
            }
            $('#form-tanggal input, #form-bulan select, #form-tahun select').val(''); // Clear data pada textbox tanggal, combobox bulan & tahun
        })
    })
    </script>
	

