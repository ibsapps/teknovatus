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
							<th style="width:3%;text-align:center;" rowspan="2">NO.</th>    
							<th style="width:10%;text-align:center;" rowspan="2">Nama Barang</th>    
							<th style="width:5%;text-align:center;" rowspan="2">Satuan</th> 
							<th style="width:5%;text-align:center;" rowspan="2">Stock Awal</th>
							<th style="width:5%;text-align:center;" rowspan="2">Stock Masuk</th>  
							<th style="width:5%;text-align:center;" rowspan="2">Stock Keluar</th> 
							<th style="width:5%;text-align:center;" rowspan="2">Stock Akhir</th>
							<th colspan="25">DIVISI</th>								       
						</tr>
						<tr>
   <td style="width:3%;text-align:center;">IT</td>
<td style="width:3%;text-align:center;">GA</td>
   <td style="width:3%;text-align:center;">HR</td>
<td style="width:3%;text-align:center;">Developer</td>
   <td style="width:3%;text-align:center;">Manajemen</td>
   <td style="width:3%;text-align:center;">Planning</td>
   <td style="width:3%;text-align:center;">Project</td>
   <td style="width:3%;text-align:center;">OM</td>
   <td style="width:3%;text-align:center;">TS</td>
   <td style="width:3%;text-align:center;">DC</td>
   <td style="width:3%;text-align:center;">Commercial</td>
   <td style="width:3%;text-align:center;">BR</td>
   <td style="width:3%;text-align:center;">Sitac</td>
   <td style="width:3%;text-align:center;">PMO</td>
   <td style="width:3%;text-align:center;">Wifi</td>
   <td style="width:3%;text-align:center;">FAD</td>
   <td style="width:3%;text-align:center;">Procurement</td>
   <td style="width:3%;text-align:center;">Legal</td>
   <td style="width:3%;text-align:center;">BC</td>
   <td style="width:3%;text-align:center;">IMT</td>
   <td style="width:3%;text-align:center;">NDL</td>
   <td style="width:3%;text-align:center;">Open Net</td>
   <td style="width:3%;text-align:center;">Coorporate</td>
   <td style="width:3%;text-align:center;">Head Bus.Dev</td>
<td style="width:3%;text-align:center;">NOC</td>

  </tr>


                        </thead>
                        <tbody>
                            <?php if(isset($monthly_report) != NULL or isset($monthly_report) != ''){?>
                        <?php $no = 1; foreach($monthly_report as $k){?>
                        <tr>
							<td style="text-align:center;"><?php echo $no;?></td>
							<td style="text-align:left;"><?php echo $k['nama_barang'];?></td>
							<td style="text-align:center;"><?php echo $k['satuan'];?></td>
							  <td><?php echo $k['tot_in'];?></td>
							  <td><?php echo $k['stock_in'];?></td>
 <td><?php echo $k['tot_out'];?></td>
 <td><?php echo $k['stock_ready'];?></td>
<td><?php echo $k['it'];?></td>
 <td><?php echo $k['ga']+ $k['g_a'];?> </td>
 <td><?php echo $k['hr'];?></td>
 <td><?php echo $k['developer'];?></td>
<td><?php echo $k['manajemen'];?></td>
 <td><?php echo $k['planning'];?></td>
<td><?php echo $k['project'];?></td>
 <td><?php echo $k['om'];?></td>
 <td><?php echo $k['ts'];?></td>
 <td><?php echo $k['dc'];?></td>
 <td><?php echo $k['commercial'];?></td>
 <td><?php echo $k['br'];?></td>
<td><?php echo $k['sitac'];?></td>
 <td><?php echo $k['pmo'];?></td>
 <td><?php echo $k['wifi'];?></td>
<td><?php echo $k['fad'];?></td>
 <td><?php echo $k['procurement'];?></td>
 <td><?php echo $k['legal'] + $k['legalcorp'];?></td>
 <td><?php echo $k['bc'];?></td>
 <td><?php echo $k['imt'];?></td>
 <td><?php echo $k['ndl'];?></td>
 <td><?php echo $k['opennet'] + $k['opennetplanning'];?></td>
 <td><?php echo $k['coorporate'];?></td>
 <td><?php echo $k['headbus'];?></td>
 <td><?php echo $k['noc'];?></td>

						</tr>
                                <?php  $no++; }?>
                            <?php }else{?>
                            <tr>
							<td style="text-align:center;" colspan="3">Tidak Ada Data</td>
							 
						</tr>
                            <?php } ?>
					</tbody>
				
				</table>
				
				
				<br>
			</div>					
		</div>
		<!-- END BODY -->



	</div>
</body>
</html>