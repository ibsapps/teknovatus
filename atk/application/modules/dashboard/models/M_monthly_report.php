<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_monthly_report extends CI_Model {
  public function view_by_date($date){
        $this->db->where('DATE(order_date)', $date); // Tambahkan where tanggal nya
        
    return $this->db->get('tbl_order')->result();// Tampilkan data transaksi sesuai tanggal yang diinput oleh user pada filter
  }
    
  public function view_all($month, $year){
      return $this->db->query("SELECT 
                         br1.barang_id,br1.nama_barang, 
                        br1.qty AS tot_in, 
                        IFNULL((in5.totalstockin),0) AS stock_in, 
                       IFNULL(out2.tot_out,0) AS tot_out,
                        IFNULL((IFNULL((in5.totalstockin),0)+br1.qty-IFNULL(out2.tot_out,0)),0) AS stock_ready,
                        br1.satuan,
                        SUM(IF( ed.id = 1, out2.tot_out, 0)) AS it,
						SUM(IF( ed.id = 3, out2.tot_out, 0)) AS g_a,
						SUM(IF( ed.id = 4, out2.tot_out, 0)) AS developer,                   
                        SUM(IF( ed.id = 5, out2.tot_out, 0)) AS manajemen,
                        SUM(IF( ed.id = 6, out2.tot_out, 0)) AS planning,
                       SUM(IF( ed.id = 7, out2.tot_out, 0)) AS project,
                        SUM(IF( ed.id = 8, out2.tot_out, 0)) AS om,
                        SUM(IF( ed.id = 9, out2.tot_out, 0)) AS ts,
                        SUM(IF( ed.id = 10, out2.tot_out, 0)) AS dc,
                        SUM(IF( ed.id = 11, out2.tot_out, 0)) AS commercial,
                        SUM(IF( ed.id = 13, out2.tot_out, 0)) AS br,
                         SUM(IF( ed.id = 14, out2.tot_out, 0)) AS sitac,
                         SUM(IF( ed.id = 15, out2.tot_out, 0)) AS pmo,
                        SUM(IF( ed.id = 16, out2.tot_out, 0)) AS wifi,
                         SUM(IF( ed.id = 18, out2.tot_out, 0)) AS ga,
						SUM(IF( ed.id = 19, out2.tot_out, 0)) AS fad,
                        SUM(IF( ed.id = 20, out2.tot_out, 0)) AS procurement,
                         SUM(IF( ed.id = 21, out2.tot_out, 0)) AS legal,
                         SUM(IF( ed.id = 22, out2.tot_out, 0)) AS bc,
                        SUM(IF( ed.id = 23, out2.tot_out, 0)) AS imt,
                        SUM(IF( ed.id = 24, out2.tot_out, 0)) AS hr,
                         SUM(IF( ed.id = 25, out2.tot_out, 0)) AS ndl,
                         SUM(IF( ed.id = 27, out2.tot_out, 0)) AS legalcorp,
                         SUM(IF( ed.id = 28, out2.tot_out, 0)) AS opennet,
                         SUM(IF( ed.id = 29, out2.tot_out, 0)) AS opennetplanning,
                        SUM(IF( ed.id = 30, out2.tot_out, 0)) AS coorporate,
						SUM(IF( ed.id = 32, out2.tot_out, 0)) AS headbus,
                         SUM(IF( ed.id = 33, out2.tot_out, 0)) AS noc
                        FROM tbl_barang br1 
                        LEFT JOIN tbl_stock_in_item in1 ON br1.barang_id=in1.barang_id 
                        LEFT JOIN tbl_stock_in in2 ON in1.stockin_id=in2.stockin_id 
                        left join tbl_stock_out_item stoko on br1.barang_id = stoko.barang_id
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
                         		WHERE MONTH(a.date_in)=' ".$month." ' AND YEAR(a.date_in)=' ".$year." '
                         		GROUP BY b.barang_id)in5 
                                on in5.barang_id = br1.barang_id
                                LEFT JOIN (
                        SELECT barang_id,stock_out_qty, SUM(so1.stock_out_qty) AS tot_out FROM tbl_stock_out_item so1
                        LEFT JOIN tbl_order or1 ON or1.order_id=so1.order_id 
                        LEFT JOIN tbl_stock_out out1 ON or1.order_id=out1.order_id 
                        WHERE or1.status='3' AND MONTH(or1.deliver_date)=' ".$month." '
                        AND YEAR(or1.deliver_date)=' ".$year." ' 
                        GROUP BY so1.barang_id 
                        ) out2 ON out2.barang_id=br1.barang_id 
                        GROUP BY br1.nama_barang;");
  }

 
  public function view_by_month($month, $year){
      return $this->db->query("SELECT 
                        br1.barang_id,br1.nama_barang, 
                        br1.qty AS tot_in, 
                        IFNULL((in5.totalstockin),0) AS stock_in, 
                       IFNULL(out2.tot_out,0) AS tot_out,
                        IFNULL((IFNULL((in5.totalstockin),0)+br1.qty-IFNULL(out2.tot_out,0)),0) AS stock_ready,
                        br1.satuan,
                        SUM(CASE when ed.id = 1 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) it,
                        SUM(CASE when ed.id = 3 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) g_a,
                        SUM(CASE when ed.id = 4 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) developer,
                        SUM(CASE when ed.id = 5 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) manajemen,
                        SUM(CASE when ed.id = 6 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) planning,
                        SUM(CASE when ed.id = 7 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) project,
                        SUM(CASE when ed.id = 8 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) om,
                        SUM(CASE when ed.id = 9 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) ts,
                        SUM(CASE when ed.id = 10 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) dc,
                        SUM(CASE when ed.id = 11 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) commercial,
                        SUM(CASE when ed.id = 13 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) br,
                        SUM(CASE when ed.id = 14 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) sitac,
                        SUM(CASE when ed.id = 15 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) pmo,
                        SUM(CASE when ed.id = 16 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) wifi,
                        SUM(CASE when ed.id = 18 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) ga,
                          SUM(CASE when ed.id = 19 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) fad,
                          SUM(CASE when ed.id = 20 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) procurement,
                          SUM(CASE when ed.id = 21 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) legal,
                          SUM(CASE when ed.id = 22 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) bc,
                          SUM(CASE when ed.id = 23 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) imt,
                          SUM(CASE when ed.id = 24 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) hr,
                          SUM(CASE when ed.id = 25 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) ndl,
                          SUM(CASE when ed.id = 27 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) legalcorp,
                          SUM(CASE when ed.id = 28 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) opennet,
                          SUM(CASE when ed.id = 29 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) opennetplanning,
                          SUM(CASE when ed.id = 30 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) coorporate,
                          SUM(CASE when ed.id = 32 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) headbus,
                          SUM(CASE when ed.id = 33 AND MONTH(ord.order_date)=' ".$month." ' then (stoko.stock_out_qty) ELSE 0 END) noc
                         
                        FROM tbl_barang br1 
                        LEFT JOIN tbl_stock_in_item in1 ON br1.barang_id=in1.barang_id 
                        LEFT JOIN tbl_stock_in in2 ON in1.stockin_id=in2.stockin_id 
                        left join tbl_stock_out_item stoko on br1.barang_id = stoko.barang_id
                        left join tbl_order ord on stoko.order_id = ord.order_id
                        left join t_users tu on ord.order_by = tu.id
                        left join t_user_roles tur on tu.id = tur.fk_uid
                        left join t_roles tr on tur.fk_rid = tr.rid
                        left join t_groups gr on gr.gid = tr.fk_gid
                        left join e_department ed on ed.id=gr.fk_dept_id
                         left join (
                            SELECT a.stockin_id,b.barang_id,b.received_qty,a.date_in,a.request_by,SUM(b.received_qty)
								as totalstockin from tbl_stock_in_item b
								LEFT JOIN tbl_stock_in a on a.stockin_id=b.stockin_id 
                         		WHERE MONTH(a.date_in)=' ".$month." ' AND YEAR(a.date_in)=' ".$year." '
                         		GROUP BY b.barang_id)in5 
                                on in5.barang_id = br1.barang_id
                                LEFT JOIN (
                        SELECT barang_id, SUM(so1.stock_out_qty) AS tot_out FROM tbl_stock_out_item so1
                        LEFT JOIN tbl_order or1 ON or1.order_id=so1.order_id 
                        LEFT JOIN tbl_stock_out out1 ON or1.order_id=out1.order_id 
                        WHERE or1.status='3' AND MONTH(or1.deliver_date)=' ".$month." '
                        AND YEAR(or1.deliver_date)=' ".$year." '
                        GROUP BY so1.barang_id 
                        ) out2 ON out2.barang_id=br1.barang_id 
                        GROUP BY br1.nama_barang;");
  }
  public function view_by_year($year){
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
                         		WHERE YEAR(a.date_in)=' ".$year." '
                         		GROUP BY b.barang_id)in5 
                                on in5.stockin_id = in2.stockin_id
                                LEFT JOIN (
                        SELECT barang_id, SUM(so1.stock_out_qty) AS tot_out FROM tbl_stock_out_item so1
                        LEFT JOIN tbl_order or1 ON or1.order_id=so1.order_id 
                        LEFT JOIN tbl_stock_out out1 ON or1.order_id=out1.order_id 
                        WHERE or1.status='3' 
                        AND YEAR(or1.order_date)=' ".$year." '
                        GROUP BY so1.barang_id 
                        ) out2 ON out2.barang_id=br1.barang_id 
                        GROUP BY br1.nama_barang;");
  }


     
    public function option_tahun(){
        $this->db->select('YEAR(order_date) AS tahun'); // Ambil Tahun dari field tgl
        $this->db->from('tbl_order'); // select ke tabel transaksi
        $this->db->order_by('YEAR(order_date)'); // Urutkan berdasarkan tahun secara Ascending (ASC)
        $this->db->group_by('YEAR(order_date)'); // Group berdasarkan tahun pada field tgl
        
        return $this->db->get()->result(); // Ambil data pada tabel transaksi sesuai kondisi diatas
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