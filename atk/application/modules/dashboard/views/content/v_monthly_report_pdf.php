<!doctype html>
<?php ini_set("memory_limit","528M");?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $nama;?></title>
	<meta name="keywords" content="<?php echo $nama; ?>" />
	<meta name="description" content="<?php echo $nama; ?>">
	<meta name="author" content="<?php echo $nama ; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style type="text/css">
	hr {color: black;}
	h1 {text-align:center; font-size:18px;}
	h2 {font-size:13px;}
	.tengah {text-align:center;	}
	.kiri {padding-left:5px;}
	.teks{
		font-size : 12px;font-family: arial;  text-align: justify;
	}
	table.bredak-before
	table.nilai {border-collapse: collapse; width:100%;}
	table.nilai td, tr{font-size: 13; padding: 2px 2px 2px 2px; border-bottom: 1px solid #ddd;}
	table.nilai tr:nth-child(even){background-color: #f2f2f2}

	table.nilai1 {font-family: arial;border-collapse: collapse; width:100%;}
	table.nilai1 td {font-size: 14px}
	table.nilai2 { font-family: arial;
		border-collapse: collapse;
		width: 100%;}
		table.nilai2 td, th {
			border: 1px solid black;
			text-align: left;
		}
		table.nilai2 tr, th {
			border: 1px solid black;
			text-align: center; font-size:12px;
		}
		table.nilai2 tr:{
			background-color: black;

		}
		table.nilai2 td, tr{font-size: 12px; padding: 2px 2px 2px 2px; border-bottom: 1px solid black;}

		.borderheader {
			border-bottom: 1px solid #ddd;
			border-top: 1px solid #ddd;
		}


		table.nilai4 {font-family: arial;border-collapse: collapse; width:100%;text-indent: 50px;}
		table.nilai4 td {font-size: 12px;text-indent: 50px;}
		table.nilai5 {font-family: arial;border-collapse: collapse; width:100%; text-align: right; text-indent: 50px;}
		table.nilai5 td {font-size: 12px;text-indent: 50px;}
		


	</style>
</head>
<body>
<div>
	<div style="width:100%;padding-top:20px;" >
		
			<div style="width:100%;">
				<table class="nilai1" >
					<tr><td style="text-align:center;"><b><font size="4"><?php echo $ket; ?> 
					</font></b></td></tr>
				</table>
                <hr/>
				<table class="bredak-before special nilai2" >
					<thead>
						<tr>
							<th style="width:3%;text-align:center;" >NO.</th>    
							<th style="width:10%;text-align:center;" >Nama Barang</th>    
							<th style="width:5%;text-align:center;" >Satuan</th> 
							<th style="width:5%;text-align:center;" >Stock Awal</th> 
							<th style="width:5%;text-align:center;" >Stock Masuk</th>
							<th style="width:5%;text-align:center;" >Stock Keluar</th> 
							<th style="width:5%;text-align:center;" >Stock Akhir</th>
						</tr>


                        </thead>
                        <tbody>
                            
				<?php 
			$no=1;
				if( ! empty($monthly_report)){
			foreach($monthly_report AS $rs){
			?>
<br>
				<tr>
				<td width="2" style="text-align:center;"><?php echo $no++;?></td>
				<td width="15" style="text-align:left;"><?php echo$rs['nama_barang'];?></td>
				<td width="5" style="text-align:center;"><?php echo$rs['satuan'];?></td>
				<td width="5" style="text-align:center;"><?php echo$rs['tot_in'];?></td>
				<td width="5" style="text-align:center;"><?php echo$rs['stock_in'];?></td>
				<td width="5" style="text-align:center;"><?php echo$rs['tot_out'];?></td>
				<td width="5" style="text-align:center;"><?php echo$rs['stock_ready'];?></td>
			</tr>
			<?php
			}}
			?>
					</tbody>
				
				</table>
				
				
				<br>
			</div>					
		</div>
		<!-- END BODY -->



	</div>
</body>
</html>