asd<div id="page-wrapper">
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
				<th>User</th>
				<th>Date Order</th>
				<th>Item </th>
				<th>Order</th>
				<th>Order Delivered By GA</th>	
				<th>Confirm</th>	
			</tr>
		</thead>
		<tbody>
			<?php
			$no=1;
			$Dept="";
			//get departemen
			$get = "SELECT t_groups.fk_dept_id FROM t_users,t_user_roles,t_roles,t_groups WHERE t_users.id = t_user_roles.fk_uid AND t_user_roles.fk_rid = t_roles.rid
				 AND t_roles.fk_gid = t_groups.gid AND t_users.id='".$user."' ";
			$xGet = $this->db->query($get)->result();
			foreach($xGet AS $t){
					$Dept = $t->fk_dept_id;
			}

			$sql = "SELECT tbl_barang.nama_barang,tsoi.stock_out_qty,tsoi.hsoq,tbl_order.order_date,tbl_order.order_by, t_groups.fk_dept_id,t_users.c_username,tbl_order.status_accept 
				FROM tbl_stock_out_item tsoi,tbl_barang,tbl_order, t_users,t_user_roles,t_groups,t_roles WHERE tsoi.barang_id = tbl_barang.barang_id AND tbl_order.order_id = tsoi.order_id AND
				t_users.id = t_user_roles.fk_uid AND t_user_roles.fk_rid = t_roles.rid AND t_roles.fk_gid = t_groups.gid AND tbl_order.order_by = t_users.id AND t_groups.fk_dept_id='".$Dept."'";
			$exc = $this->db->query($sql)->result();
			foreach($exc AS $rs){
			?>

				<tr>
				<td><?php echo$no++;?></td>
				<td><?php echo$rs->c_username;?></td>
				<td><?php echo$rs->order_date;?></td>
				<td><?php echo$rs->nama_barang;?></td>
				<td><?php echo$rs->hsoq;?></td>
				<td><?php echo$rs->stock_out_qty;?></td>
				<td><?php echo$rs->status_accept;?></td>
			</tr>
			<?php
			}
			?>
			<div align="right">
				 <a href="<?php echo base_url();?>dashboard/confirm/accept/<?php echo$Dept;?>">
					
			<button type="button" class="btn btn-md btn-info">Accept Confirmation</button> </div>
			</a>
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
