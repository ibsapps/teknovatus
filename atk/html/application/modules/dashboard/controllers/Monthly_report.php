<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Monthly_report extends CI_Controller {
  
  public function __construct(){
    parent::__construct();
    
    $this->load->model('M_monthly_report');
    $this->load->library('m_pdf');
  }
  
  public function index(){
        if(isset($_GET['filter']) && ! empty($_GET['filter'])){ // Cek apakah user telah memilih filter dan klik tombol tampilkan
            $filter = $_GET['filter']; // Ambil data filder yang dipilih user
            if($filter == '1'){ // Jika filter nya 1 (per tanggal)
                $tgl = $_GET['tanggal'];
                
                $ket = 'Data ATK Tanggal '.date('d-m-y', strtotime($tgl));
                $url_cetak = 'monthly_report/cetak?filter=1&tahun='.$tgl;
                $monthly_report = $this->M_monthly_report->view_by_date($tgl)->result_array(); // Panggil fungsi view_by_date yang ada di TransaksiModel
            }else if($filter == '2'){ // Jika filter nya 2 (per bulan)
                $bulan = $_GET['bulan'];
                $tahun = $_GET['tahun'];
                $nama_bulan = array('', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                
                $ket = 'Data ATK Bulan '.$nama_bulan[$bulan].' '.$tahun;
                $url_cetak = 'monthly_report/cetak?filter=2&bulan='.$bulan.'&tahun='.$tahun;
                $url_closing = 'monthly_report/closing?filter=2&bulan='.$bulan.'&tahun='.$tahun;
                $url_pdf = 'monthly_report/pdf?filter=2&bulan='.$bulan.'&tahun='.$tahun;
                $url_excel = 'monthly_report/excel?filter=2&bulan='.$bulan.'&tahun='.$tahun;
                $monthly_report = $this->M_monthly_report->view_by_month($bulan, $tahun)->result_array(); // Panggil fungsi view_by_month yang ada di TransaksiModel
            }else{ // Jika filter nya 3 (per tahun)
                $tahun = $_GET['tahun'];
                
                $ket = 'Data ATK Tahun '.$tahun;
                $url_cetak = 'monthly_report/cetak?filter=3&tahun='.$tahun;
                $monthly_report = $this->M_monthly_report->view_by_year($tahun)->result_array(); // Panggil fungsi view_by_year yang ada di TransaksiModel
            }
        }else{ // Jika user tidak mengklik tombol tampilkan
            $ket = 'Semua Data ATK';
            $url_cetak = 'monthly_report/cetak';
            $monthly_report = $this->M_monthly_report->view_all($bulan, $tahun)->result_array(); // Panggil fungsi view_all yang ada di TransaksiModel
        }
    $data = array(
        'ket' => $ket, 
        'url_cetak' => base_url('dashboard/'.$url_cetak), 
        'url_closing' => base_url('dashboard/'.$url_closing), 
        'url_pdf' => base_url('dashboard/'.$url_pdf), 
        'url_excel' => base_url('dashboard/'.$url_excel), 
        'monthly_report' => $monthly_report,
        'option_tahun' => $this->M_monthly_report->option_tahun(),
    );
   $this->load->view('../modules/dashboard/views/content/v_monthly_report',$data); 
  }
  
  public function cetak(){
        if(isset($_GET['filter']) && ! empty($_GET['filter'])){ // Cek apakah user telah memilih filter dan klik tombol tampilkan
            $filter = $_GET['filter']; // Ambil data filder yang dipilih user
            if($filter == '1'){ // Jika filter nya 1 (per tanggal)
                $tgl = $_GET['tanggal'];
                
                $ket = 'Data Transaksi Tanggal '.date('d-m-y', strtotime($tgl));
                $monthly_report = $this->M_monthly_report->view_by_date($tgl)->result_array(); // Panggil fungsi view_by_date yang ada di TransaksiModel
            }else if($filter == '2'){ // Jika filter nya 2 (per bulan)
                $bulan = $_GET['bulan'];
                $tahun = $_GET['tahun'];
                $nama_bulan = array('', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                
                $ket = 'Data Transaksi Bulan '.$nama_bulan[$bulan].' '.$tahun;
                $monthly_report = $this->M_monthly_report->view_by_month($bulan, $tahun)->result_array(); // Panggil fungsi view_by_month yang ada di TransaksiModel
            }else{ // Jika filter nya 3 (per tahun)
                $tahun = $_GET['tahun'];
                
                $ket = 'Data Transaksi Tahun '.$tahun;
                $monthly_report = $this->M_monthly_report->view_by_year($tahun)->result_array(); // Panggil fungsi view_by_year yang ada di TransaksiModel
            }
        }else{ // Jika user tidak mengklik tombol tampilkan
            $ket = 'Semua Data Transaksi';
            $monthly_report = $this->M_monthly_report->view_all($bulan, $tahun)->result_array(); // Panggil fungsi view_all yang ada di TransaksiModel
        }
        
        $data['ket'] = $ket;
        $data['monthly_report'] = $monthly_report;
        
    ob_start();
      $html = $this->load->view('../modules/dashboard/views/content/v_monthly_report_pdf', $data, true);
    $this->mpdf = new mPDF();
	$this->mpdf->AddPage('P', '', '', '', '',23,18,15,25,10,10); // margin footer
    $this->mpdf->WriteHTML($html);
    $this->mpdf->Output("Monthly Report ATK.pdf", 'I');
  }


 public function closing(){
        if(isset($_GET['filter']) && ! empty($_GET['filter'])){ // Cek apakah user telah memilih filter dan klik tombol tampilkan
            $filter = $_GET['filter']; // Ambil data filder yang dipilih user
            if($filter == '1'){ // Jika filter nya 1 (per tanggal)
                $tgl = $_GET['tanggal'];
                
                $ket = 'Data Transaksi Tanggal '.date('d-m-y', strtotime($tgl));
                $monthly_report = $this->M_monthly_report->view_by_date($tgl)->result_array(); // Panggil fungsi view_by_date yang ada di TransaksiModel
            }else if($filter == '2'){ // Jika filter nya 2 (per bulan)
                $bulan = $_GET['bulan'];
                $tahun = $_GET['tahun'];
                $nama_bulan = array('', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                
                $ket = 'Data Transaksi Bulan '.$nama_bulan[$bulan].' '.$tahun;
                $monthly_report = $this->M_monthly_report->view_by_month($bulan, $tahun)->result_array(); // Panggil fungsi view_by_month yang ada di TransaksiModel
            }else{ // Jika filter nya 3 (per tahun)
                $tahun = $_GET['tahun'];
                
                $ket = 'Data Transaksi Tahun '.$tahun;
                $monthly_report = $this->M_monthly_report->view_by_year($tahun)->result_array(); // Panggil fungsi view_by_year yang ada di TransaksiModel
            }
        }else{ // Jika user tidak mengklik tombol tampilkan
            $ket = 'Semua Data Transaksi';
            $monthly_report = $this->M_monthly_report->view_all($bulan, $tahun)->result_array(); // Panggil fungsi view_all yang ada di TransaksiModel
        }
        
        $data['ket'] = $ket;
        $data['monthly_report'] = $monthly_report;
    ob_start();
      $html = $this->load->view('../modules/dashboard/views/content/v_reportpdfclosing', $data, true);
    $this->mpdf = new mPDF();
    $this->mpdf->AddPage('P', '', '', '', '',23,18,15,25,10,10); // margin footer
    $this->mpdf->WriteHTML($html);
    $this->mpdf->Output("Closing.pdf", 'I');
  }

   public function pdf(){
        if(isset($_GET['filter']) && ! empty($_GET['filter'])){ // Cek apakah user telah memilih filter dan klik tombol tampilkan
            $filter = $_GET['filter']; // Ambil data filder yang dipilih user
            if($filter == '1'){ // Jika filter nya 1 (per tanggal)
                $tgl = $_GET['tanggal'];
                
                $ket = 'Data Transaksi Tanggal '.date('d-m-y', strtotime($tgl));
                $monthly_report = $this->M_monthly_report->view_by_date($tgl)->result_array(); // Panggil fungsi view_by_date yang ada di TransaksiModel
            }else if($filter == '2'){ // Jika filter nya 2 (per bulan)
                $bulan = $_GET['bulan'];
                $tahun = $_GET['tahun'];
                $nama_bulan = array('', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                
                $ket = 'Data Transaksi Bulan '.$nama_bulan[$bulan].' '.$tahun;
                $monthly_report = $this->M_monthly_report->view_by_month($bulan, $tahun)->result_array(); // Panggil fungsi view_by_month yang ada di TransaksiModel
            }else{ // Jika filter nya 3 (per tahun)
                $tahun = $_GET['tahun'];
                
                $ket = 'Data Transaksi Tahun '.$tahun;
                $monthly_report = $this->M_monthly_report->view_by_year($tahun)->result_array(); // Panggil fungsi view_by_year yang ada di TransaksiModel
            }
        }else{ // Jika user tidak mengklik tombol tampilkan
            $ket = 'Semua Data Transaksi';
            $monthly_report = $this->M_monthly_report->view_all($bulan, $tahun)->result_array(); // Panggil fungsi view_all yang ada di TransaksiModel
        }
        
        $data['ket'] = $ket;
        $data['monthly_report'] = $monthly_report;
        
    ob_start();
      $html = $this->load->view('../modules/dashboard/views/content/v_reportpdf', $data, true);
    $this->mpdf = new mPDF();
    $this->mpdf->AddPage('L', '', '', '', '',23,18,15,25,10,10); // margin footer
    $this->mpdf->WriteHTML($html);
    $this->mpdf->Output("Monthly Report ATK All Dept.pdf", 'I');
  }

  public function excel(){
        if(isset($_GET['filter']) && ! empty($_GET['filter'])){ // Cek apakah user telah memilih filter dan klik tombol tampilkan
            $filter = $_GET['filter']; // Ambil data filder yang dipilih user
            if($filter == '1'){ // Jika filter nya 1 (per tanggal)
                $tgl = $_GET['tanggal'];
                
                $ket = 'Data Transaksi Tanggal '.date('d-m-y', strtotime($tgl));
                $monthly_report = $this->M_monthly_report->view_by_date($tgl)->result_array(); // Panggil fungsi view_by_date yang ada di TransaksiModel
            }else if($filter == '2'){ // Jika filter nya 2 (per bulan)
                $bulan = $_GET['bulan'];
                $tahun = $_GET['tahun'];
                $nama_bulan = array('', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                
                $ket = 'Data Transaksi Bulan '.$nama_bulan[$bulan].' '.$tahun;
                $monthly_report = $this->M_monthly_report->view_by_month($bulan, $tahun)->result_array(); // Panggil fungsi view_by_month yang ada di TransaksiModel
            }else{ // Jika filter nya 3 (per tahun)
                $tahun = $_GET['tahun'];
                
                $ket = 'Data Transaksi Tahun '.$tahun;
                $monthly_report = $this->M_monthly_report->view_by_year($tahun)->result_array(); // Panggil fungsi view_by_year yang ada di TransaksiModel
            }
        }else{ // Jika user tidak mengklik tombol tampilkan
            $ket = 'Semua Data Transaksi';
            $monthly_report = $this->M_monthly_report->view_all($bulan, $tahun)->result_array(); // Panggil fungsi view_all yang ada di TransaksiModel
        }
        
        $data['title'] = $ket;
        $data['monthly_report'] = $monthly_report;
        
    $this->load->view('../modules/dashboard/views/content/v_reportexcel',$data);
  }
function printcsv(){
	echo "halo";
}

   
}