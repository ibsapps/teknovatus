<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_export extends CI_Controller {

	    private $rDate;

    public function __construct() {
        parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$now=date('Y-m-d H:i:s');
		$this->load->model('m_reportexcel');
		$this->load->library('m_pdf');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('tgl_indo');          
 }


public function index()
    {
	
       $data = array( 'title' => 'Report Excell',
			'report' => $this->m_reportexcel->getexcel()->result_array(),
			'ga' => $ga,
			'bc' => $bc,
			);	
		$this->load->view('../modules/dashboard/views/content/v_reportexcel',$data);   
 }    

public function pdf()
	{
		$data = array(
        'nama' => 'LAPORAN DATA ATK',
        'report' => $this->m_reportexcel->getexcel()->result_array(),    
		);
    $html = $this->load->view('../modules/dashboard/views/content/v_reportpdf', $data, true);
    $this->mpdf = new mPDF();
	$this->mpdf->AddPage('L', '', '', '', '',23,18,15,25,10,10); // margin footer
    $this->mpdf->WriteHTML($html);
    $this->mpdf->Output("Laporan Data ATK", 'I');
    }


public function closing()
	{

$data = array(
		 'nama' => 'LAPORAN DATA ATK',
		'report'=>$this->m_reportexcel->getexcel()->result_array(),
);
 $html = $this->load->view('../modules/dashboard/views/content/v_reportpdfclosing', $data, true);
    $this->mpdf = new mPDF();
	$this->mpdf->AddPage('P', '', '', '', '',23,18,15,25,10,10); // margin footer
    $this->mpdf->WriteHTML($html);
    $this->mpdf->Output("Laporan Data ATK", 'I'); 
          
}
public function closingold()
    {

$data = array(
         'nama' => 'LAPORAN DATA ATK',
        'report'=>$this->m_reportexcel->getexcel()->result_array(),
);
$this->load->view('../modules/dashboard/views/content/v_trial',$data);  
          
}

public function laporan_bulanan_old() {
  	  $cnt = "laporan_bulanan";
        if ($this->uri->segment(4) == "feed") {
            $this->load->model('m_reportexcel', 'report_excel');
            $this->type = "json";
            $this->feed['data'] = $this->report_excel->getexcel();
            $this->getContent()->displayView();
        } else {
            $this->content['feedUri'] = base_url() . "dashboard/excel_export/laporan_bulanan";
            $this->getDataPlugins($cnt)->getContent($cnt)->displayView();
        }
    }
	
public function laporan_bulanan()
{
	$data = array(
		'report'=>$this->m_reportexcel->getexcel()->result_array(),
		
	);
	$this->load->view('../modules/dashboard/views/content/v_report',$data);
}

public function cetak(){
        if(isset($_GET['filter']) && ! empty($_GET['filter'])){ // Cek apakah user telah memilih filter dan klik tombol tampilkan
            $filter = $_GET['filter']; // Ambil data filder yang dipilih user
            if($filter == '1'){ // Jika filter nya 1 (per tanggal)
                $tgl = $_GET['tanggal'];
                
                $ket = 'Data Transaksi Tanggal '.date('d-m-y', strtotime($tgl));
                $url_cetak = 'excel_export/cetak?filter=1&tahun='.$tgl;
                $transaksi = $this->M_reportexcel->view_by_date($tgl); // Panggil fungsi view_by_date yang ada di M_reportexcel
            }else if($filter == '2'){ // Jika filter nya 2 (per bulan)
                $bulan = $_GET['bulan'];
                $tahun = $_GET['tahun'];
                $nama_bulan = array('', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                
                $ket = 'Data Transaksi Bulan '.$nama_bulan[$bulan].' '.$tahun;
                 $url_cetak = 'excel_export/cetak?filter=2&bulan='.$bulan.'&tahun='.$tahun;
                $transaksi = $this->M_reportexcel->view_by_month($bulan, $tahun); // Panggil fungsi view_by_month yang ada di M_reportexcel
            }else{ // Jika filter nya 3 (per tahun)
                $tahun = $_GET['tahun'];
                
                $ket = 'Data Transaksi Tahun '.$tahun;
                 $url_cetak = 'excel_export/cetak?filter=3&tahun='.$tahun;
                $transaksi = $this->M_reportexcel->view_by_year($tahun); // Panggil fungsi view_by_year yang ada di M_reportexcel
            }
        }else{ // Jika user tidak mengklik tombol tampilkan
            $ket = 'Semua Data Transaksi';
              $url_cetak = 'excel_export/cetak';
            $transaksi = $this->M_reportexcel->view_all(); // Panggil fungsi view_all yang ada di M_reportexcel
        }
        
        $data['ket'] = $ket;
        $data['transaksi'] = $transaksi;
    $html = $this->load->view('../modules/dashboard/views/content/v_report', $data, true);
    $this->mpdf = new mPDF();
	$this->mpdf->AddPage('P', '', '', '', '',23,18,15,25,10,10); // margin footer
    $this->mpdf->WriteHTML($html);
    $this->mpdf->Output("Laporan Data ATK", 'I');
  }

}




