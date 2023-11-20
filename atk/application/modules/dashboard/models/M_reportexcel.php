<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_reportexcel extends CI_Model {

    
	function getexcel($where = ''){
		return $this->db->query("SELECT 
                        br1.barang_id,br1.nama_barang, 
                        IFNULL((in5.totalstockin),0)+br1.qty AS tot_in, 
                        IFNULL(out2.tot_out,0) AS tot_out,
                        IFNULL((IFNULL((in5.totalstockin),0)+br1.qty-IFNULL(out2.tot_out,0)),0) AS stock_ready,
                        br1.satuan,
						SUM(IF( ed.id = 1, stoko.stock_out_qty, 0)) AS it,
						SUM(IF( ed.id = 3, stoko.stock_out_qty, 0)) AS g_a,
						SUM(IF( ed.id = 4, stoko.stock_out_qty, 0)) AS developer,                   
                        SUM(IF( ed.id = 5, stoko.stock_out_qty, 0)) AS manajemen,
                        SUM(IF( ed.id = 6, stoko.stock_out_qty, 0)) AS planning,
                       SUM(IF( ed.id = 7, stoko.stock_out_qty, 0)) AS project,
                        SUM(IF( ed.id = 8, stoko.stock_out_qty, 0)) AS om,
                        SUM(IF( ed.id = 9, stoko.stock_out_qty, 0)) AS ts,
                        SUM(IF( ed.id = 10, stoko.stock_out_qty, 0)) AS dc,
                        SUM(IF( ed.id = 11, stoko.stock_out_qty, 0)) AS commercial,
                        SUM(IF( ed.id = 13, stoko.stock_out_qty, 0)) AS br,
                         SUM(IF( ed.id = 14, stoko.stock_out_qty, 0)) AS sitac,
                         SUM(IF( ed.id = 15, stoko.stock_out_qty, 0)) AS pmo,
                        SUM(IF( ed.id = 16, stoko.stock_out_qty, 0)) AS wifi,
                         SUM(IF( ed.id = 18, stoko.stock_out_qty, 0)) AS ga,
						SUM(IF( ed.id = 19, stoko.stock_out_qty, 0)) AS fad,
                        SUM(IF( ed.id = 20, stoko.stock_out_qty, 0)) AS procurement,
                         SUM(IF( ed.id = 21, stoko.stock_out_qty, 0)) AS legal,
                         SUM(IF( ed.id = 22, stoko.stock_out_qty, 0)) AS bc,
                        SUM(IF( ed.id = 23, stoko.stock_out_qty, 0)) AS imt,
                        SUM(IF( ed.id = 24, stoko.stock_out_qty, 0)) AS hr,
                         SUM(IF( ed.id = 25, stoko.stock_out_qty, 0)) AS ndl,
                         SUM(IF( ed.id = 27, stoko.stock_out_qty, 0)) AS legalcorp,
                         SUM(IF( ed.id = 28, stoko.stock_out_qty, 0)) AS opennet,
                         SUM(IF( ed.id = 29, stoko.stock_out_qty, 0)) AS opennetplanning,
                        SUM(IF( ed.id = 30, stoko.stock_out_qty, 0)) AS coorporate,
						SUM(IF( ed.id = 32, stoko.stock_out_qty, 0)) AS headbus,
                         SUM(IF( ed.id = 33, stoko.stock_out_qty, 0)) AS noc
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
						 left join (
                            SELECT a.stockin_id,b.barang_id,b.received_qty,a.date_in,a.request_by,SUM(b.received_qty)
								as totalstockin from tbl_stock_in a
								LEFT JOIN tbl_stock_in_item b on a.stockin_id=b.stockin_id 
                         		
                         		GROUP BY b.barang_id)in5 
                                on in5.stockin_id = in2.stockin_id
                        LEFT JOIN (
                        SELECT barang_id, SUM(so1.stock_out_qty) AS tot_out FROM tbl_stock_out_item so1
                        LEFT JOIN tbl_order or1 ON or1.order_id=so1.order_id 
                        LEFT JOIN tbl_stock_out out1 ON or1.order_id=out1.order_id 
                        WHERE or1.status='3' 
                        GROUP BY so1.barang_id 
                        ) out2 ON out2.barang_id=br1.barang_id                        
                        GROUP BY br1.nama_barang $where; ");


		
	}
	function getsum($where = ''){
			return $this->db->query("SELECT barang_id, SUM(order_in_qty) AS totalstockin FROM tbl_stock_in_item GROUP BY barang_id $where;");	
			}




	public function getsql($where = ''){
			return $this->db->query("SELECT barang_id, nama_barang, satuan FROM tbl_barang  $where;");	
		
	}


	public function InsertData($table_name,$data){
		return $this->db->insert($table_name, $data);
	}
	
	public function UpdateData($table, $data, $where){
		return $this->db->update($table, $data, $where);
	}
	
	public function DeleteData($table,$where){
		return $this->db->delete($table,$where);
	}


}

/* End of file M_datarekap.php */
/* Location: .//C/Users/Arundaya/AppData/Local/Temp/fz3temp-2/M_datarekap.php */