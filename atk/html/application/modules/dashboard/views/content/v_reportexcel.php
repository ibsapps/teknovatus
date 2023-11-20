<?php 

header("Content-type: application/octet-stream");

header("Content-Disposition: attachment; filename=$title.xls");

header("Pragma: no-cache");

header("Expires: 0");

?>

<table border="1" width="100%">

<thead>

<tr>

 <th rowspan="2">No</th>
 <th rowspan="2">Nama Barang</th>
 <th rowspan="2">Satuan</th>
 <th rowspan="2">Stock Awal</th>
 <th rowspan="2">Stock Masuk</th>
 <th rowspan="2">Stock Keluar</th>
 <th rowspan="2">Stock Akhir</th>
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
<?php $i=1; foreach($monthly_report as $k) { ?>
<tr>

<td style="text-align:center;"><?php echo $i;?></td>
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
<?php $i++; } ?>



</tbody>

</table>