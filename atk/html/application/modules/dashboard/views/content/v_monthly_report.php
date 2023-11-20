<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <?php echo $this->load->view('favicon') ?>
        <title>Infrastruktur Bisnis Sejahtera</title>

        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css" />

        <!-- MetisMenu CSS -->
        <link href="<?php echo base_url() ?>assets/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

        

        <!-- Custom CSS -->
        <link href="<?php echo base_url() ?>assets/css/sb-admin-2.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="<?php echo base_url() ?>assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>

        <div id="wrapper">

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
            <!-- <option value="1">Per Tanggal</option> -->
            <option value="2">Per Bulan</option>
           <!-- <option value="3">Per Tahun</option> -->
        </select>
        <br /><br />
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
	<br>
        <button class="btn btn-primary" type="submit">Tampilkan</button>
    </form>
    <a href="<?php echo $url_cetak; ?>" target="_blank" name="btnBack" class="btn btn-danger">PDF</a>
    <a class="btn btn-success" href="<?php echo $url_excel ?>" target="_blank" role="button" method="POST" enctype="multipart/form-data">Excell All Dept
</a>
<br><br>
<a class="btn btn-danger" href="<?php echo $url_pdf; ?>" target="_blank" role="button" method="POST" enctype="multipart/form-data">PDF All Dept
</a>
<a class="btn btn-warning" href="<?php echo $url_closing; ?>" target="_blank" role="button" method="POST" enctype="multipart/form-data">Closing
</a>

<a class="btn btn-default" href="<?php echo base_url(); ?>" role="button"  enctype="multipart/form-data">Kembali
</a>
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
				<th>Stock Masuk</th>
				<th>Stock Keluar </th>
				<th>Stock Akhir</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if( ! empty($monthly_report)){
			$no=1;
			
			foreach($monthly_report AS $rs){
			?>

				<tr>
				<td><?php echo$no++;?></td>
				<td><?php echo$rs['nama_barang'];?></td>
				<td><?php echo$rs['satuan'];?></td>
				<td><?php echo$rs['tot_in'];?></td>
				<td><?php echo$rs['stock_in'];?></td>
				<td><?php echo$rs['tot_out'];?></td>
				<td><?php echo$rs['stock_ready'];?></td>
			</tr>
			<?php
			}}
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

        
        <!-- jQuery -->

        <!-- Bootstrap Core JavaScript -->
        <!-- Custom Theme JavaScript -->
        <script type="text/javascript" src="<?php echo base_url('assets/js/jquery/jquery.min.js') ?>"></script>
        <!-- <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>"></script> -->
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
	
	
    </body>

</html>