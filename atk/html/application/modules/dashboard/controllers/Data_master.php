<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Data_master extends CI_Controller {

    private $data;
    private $feed;
    private $content;
    private $type;
    private $usrInfo;
    private $menuPanel;
    private $result;

    public function __construct() {
        parent::__construct();
        $this->setDefaultType()->load_ModSupport()->checkSessionActive()->checkModuleRole()->generatePanel();
//		$this->load->model('M_master','master');
//		$this->load->library('func_table');
    }

    public function index() {
        redirect('dashboard');
    }

    public function input() {
        if ($this->input->post('uploadFiles')) {
            $node = $this->input->post('uploadFiles');
            $this->type = "json";
            $this->initUpload($node)->doUpload()->displayView();
        } else {
            $this->content['dataResult'] = array();
            $this->filterInput()->executePost()->getDataPlugins($this->input->post('node'))->getContent($this->input->post('node') . "_form")->displayView();
        }
    }

    public function update() {
        if ($this->input->post('uploadFiles') != "") {
            $node = $this->input->post('uploadFiles');
            $this->type = "json";
            $this->initUpload($node)->doUpload()->displayView();
        } else {
            $this->filterUpdate()->executePost()->getDataPlugins($this->input->post('node'))->getDataFromResult()->getContent($this->input->post('node') . "_form")->displayView();
        }
    }

    public function delete() {
        $this->filterDelete()->deletePost();
        redirect('dashboard/data_master/'.$this->input->post('node'));
    }

    public function barang() {
        if ($this->uri->segment(4) == "feed") {
            $this->load->model('m_master', 'master');
            $this->type = "json";
            $this->feed['data'] = $this->master->feed_barang();
            $this->getContent('barang')->displayView();
        } else {
            $this->content['feedUri'] = base_url() . "dashboard/data_master/barang/feed";
            $this->getDataPlugins('barang')->getContent('barang')->displayView();
        }
    }

    public function karyawan() {
        if ($this->uri->segment(4) == "feed") {
            $this->load->model('m_master', 'master');
            $this->type = "json";
            $this->feed['data'] = $this->master->feed_karyawan();
            $this->getContent('karyawan')->displayView();
        } else {
            $this->content['feedUri'] = base_url() . "dashboard/data_master/karyawan/feed";
            $this->getDataPlugins('karyawan')->getContent('karyawan')->displayView();
        }
    }

	public function penerimaan(){
	
	 if ($this->uri->segment(4) == "feed") {
            $this->load->model('m_master', 'master');
            $this->type = "json";
            $this->feed['data'] = $this->master->feed_karyawan();
            $this->getContent('penerimaan')->displayView();
        } else {
            $this->content['feedUri'] = base_url() . "dashboard/data_master/karyawan/feed";
            $this->getDataPlugins('penerimaan')->getContent('penerimaan')->displayView();
        }


	}
    private function filterInput() {
        $this->load->model('m_master', 'master');
        $this->content['list_satuan'] = $this->master->list_satuan();
        $this->content['list_kategori'] = $this->master->list_kategori();
        $this->content['list_depertement'] = $this->master->list_departement();
        switch ($this->input->post('node')) {
            case (preg_match('/barang|karyawan/i', $this->input->post('node')) ? true : false):
                $this->result = array('status' => 1, 'desc' => 'Continue', 'param' => '');
                break;
            default:
                $this->result = array('status' => 0, 'desc' => 'Data Tidak Valid', 'param' => '');
        }
        return $this;
    }

    private function filterUpdate() {
        $this->load->model('m_master', 'master');
        $this->content['list_satuan'] = $this->master->list_satuan();
        $this->content['list_kategori'] = $this->master->list_kategori();
        $this->content['list_depertement'] = $this->master->list_departement();
        $cbox = $this->input->post('cbox');
        switch ($this->input->post('node')) {
            case (preg_match('/barang|karyawan/i', $this->input->post('node')) ? true : false):
                foreach ($cbox as $val) {
                    if (preg_match('/^\d{1,6}$/i', $val) ? true : false) {
                        $this->result = array('status' => 1, 'desc' => 'Continue', 'param' => $cbox);
                    } else {
                        $this->result = array('status' => 0, 'desc' => 'Data Tidak Valid', 'param' => '');
                        break;
                    }
                }
                break;
            default:
                $this->result = array('status' => 0, 'desc' => 'Data Tidak Valid', 'param' => '');
        }
        return $this;
    }
    
    private function filterDelete() {
        $this->load->model('m_master', 'master');
        $cbox = $this->input->post('cbox');
        switch ($this->input->post('node')) {
            case (preg_match('/barang|karyawan/i', $this->input->post('node')) ? true : false):
                foreach ($cbox as $val) {
                    if (preg_match('/^\d{1,6}$/i', $val) ? true : false) {
                        $this->result = array('status' => 1, 'desc' => 'Continue', 'param' => $cbox);
                    } else {
                        $this->result = array('status' => 0, 'desc' => 'Data Tidak Valid', 'param' => '');
                        break;
                    }
                }
                break;
            default:
                $this->result = array('status' => 0, 'desc' => 'Data Tidak Valid', 'param' => '');
        }
        return $this;
    }
    
    private function executePost() {
        if($this->input->post('processForm')=="1") {
            $this->content['query_result'] = $this->master->save_post($this->input->post(), $this->input->post('node'));
        }
        return $this;
    }
    
    private function deletePost() {
        $this->content['query_result'] = $this->master->delete_post($this->input->post(), $this->input->post('node'));
        return $this;
    }

    private function doUpload() {
        if (!$this->upload->do_upload('file')) {
            $this->result = array('status' => 0, 'desc' => $this->upload->display_errors(), 'param' => $_FILES);
        } else {
            $this->result = array('status' => 1, 'desc' => 'success', 'param' => $this->upload->data());
        }
        $this->feed = $this->result;
        return $this;
    }

    private function initUpload($addOnDirectory) {
        $time = time();
        $extFile = "";
        switch ($_FILES['file']['type']) {
            case "image/jpeg":
                $extFile = "jpg";
                break;
            case "image/png":
                $extFile = "png";
                break;
        }
        $fileName = "file_" . $time . "." . $extFile;
        $configUpload['file_name'] = $fileName;
        $configUpload['upload_path'] = FCPATH . 'assets/images/upload/' . $addOnDirectory;
        $configUpload['allowed_types'] = 'jpg|png';
        $configUpload['max_size'] = 800;
        $configUpload['max_width'] = 1024;
        $configUpload['max_height'] = 768;
        $this->load->library('upload', $configUpload);
        return $this;
    }

    private function getDataPlugins($dataMasterUntuk) {
        $this->content['cssPlugins'] = "<!-- DataTables CSS -->";
        $this->content['cssPlugins'] .= "<link href=\"" . base_url() . "assets/vendor/datatables-plugins/dataTables.bootstrap.css\" rel=\"stylesheet\">";
        $this->content['cssPlugins'] .= "<!-- DataTables Responsive CSS -->";
        $this->content['cssPlugins'] .= "<link href=\"" . base_url() . "assets/vendor/datatables-responsive/dataTables.responsive.css\" rel=\"stylesheet\">";
        $this->content['javascriptPlugins'] = "<!-- DataTables JavaScript -->";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/js/jquery.dataTables.min.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables-plugins/dataTables.bootstrap.min.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables-responsive/dataTables.responsive.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/vendor/datatables/datatables.net-select/js/dataTables.select.js\"></script>";
        $this->content['javascriptPlugins'] .= "<script src=\"" . base_url() . "assets/js/data_master_" . $dataMasterUntuk . ".js\"></script>";
        return $this;
    }

    private function getDataFromResult() {
        $dataRow = array();
        $sql = array();
        $this->load->model('m_master', 'master');
        switch ($this->input->post('node')) {
            case "barang":
                foreach ($this->result['param'] as $par) {
                    $dataRow[] = $this->master->get_barang($par);
                }
                break;
            case "karyawan":
                foreach ($this->result['param'] as $par) {
                    $dataRow[] = $this->master->get_karyawan($par);
                }
                break;
        }
        $this->content['dataResult'] = $dataRow;
        return $this;
    }

    private function getContent($name) {
        switch ($this->type) {
            case "json":
                $this->data['contentResult'] = json_encode($this->content);
                break;
            case "html":
                $this->content['result'] = $this->result;
                $this->content['menuPanel'] = $this->data['menuPanel'];
                $this->data['contentResult'] = $this->load->view('content/' . $name, $this->content, TRUE);
        }
        return $this;
    }

    private function displayView() {
        switch ($this->type) {
            case "json":
                return $this->output
                                ->set_content_type('application/json')
                                ->set_status_header(200) // Return status
                                ->set_output(json_encode($this->feed));
            case "html":
                $this->load->view('home', $this->data);
        }
    }

    private function generatePanel() {
        $this->menuPanel = $this->panel->menu_panel($this->usrInfo->fk_rid);
        $this->data['menuPanel'] = $this->menuPanel;
        return $this;
    }

    private function load_ModSupport() {
        $this->erp = $this->common_erp;
        $this->load->model('m_panel', 'panel');
        $this->load->helper('date');
        return $this;
    }

    private function checkSessionActive() {
        if (!$this->erp->erpSessionCheck($this->session)) {
            redirect('ibs');
        } else {
            $this->usrInfo = $this->panel->query_user($this->session->userdata('username'));
            $this->data['usrInfo'] = $this->usrInfo;
        }
        return $this;
    }

    private function checkModuleRole() {
        if (isset($this->usrInfo)) {
            return $this;
        } else {
            redirect('dashboard');
        }
        return $this;
    }

    private function setDefaultType($default = 'html') {
        $this->type = $default;
        return $this;
    }

    /*
      public function ajax_list()
      {
      $status_post = $_POST['status'];
      $list = $this->master->get_datatables();
      $data = array();
      $no = $_POST['start'];
      foreach ($list as $master) {

      if ($master->status == 0) {
      $status = "Belum diajukan";
      }else if ($master->status == 1) {
      $status = "Sedang diajukan";
      }else if ($master->status == 2) {
      $status = "Ditolak";
      }
      else if ($master->status == 3) {
      $status = "Diajukan Kembali";
      }
      else if ($master->status == 4) {
      $status = "Data berhasil diajukan";
      }
      $no++;
      $row = array();
      $row[] = '<img src="'.base_url('assets/image/'.$master->photo).'" class="img-responsive image-circle" width="50" ><span style="font-weight:bold"> '.$master->nama.'</span>';
      $row[] = $master->nama_barang;
      $row[] = $master->jumlah;
      $row[] = number_format($master->harga);
      // $row[] = $status;
      $row[] = $master->catatan;
      $row[] = '<img src="'.base_url('assets/image/'.$master->foto).'" class="img-responsive image-rounded" width="90" >';
      if ($status_post == '0') {
      $row[] = $this->func_table->tgl_indo($master->tgl_dibuat);
      }else if ($status_post == '1') {
      $row[] = $this->func_table->tgl_indo($master->tgl_pengajuan);
      }else if ($status_post == '2') {
      $row[] = $this->func_table->tgl_indo($master->tgl_penolakan);
      }else if ($status_post == '3') {
      $row[] = $this->func_table->tgl_indo($master->tgl_pengajuan_kembali);
      }else if ($status_post == '4') {
      $row[] = $this->func_table->tgl_indo($master->tgl_disetujui);
      }

      if ($master->status == 0) {
      if ($this->session->userdata('user_type') == '1' ) {
      $row[] = 'Belum diajukan user';
      }else{
      $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_master('."'".$master->id_master."'".')"><i class="glyphicon glyphicon-trash"></i></a><a class="btn btn-sm btn-success" href="javascript:void()" title="Edit" onclick="edit_master('."'".$master->id_master."'".')"><i class="glyphicon glyphicon-pencil"></i></a> <a class="btn btn-sm btn-warning" href="javascript:void()" title="Ajukan" onclick="pengajuan('."'".$master->id_master."'".')">Ajukan ?</a>';
      }
      }else if ($master->status == 1) {
      if ($this->session->userdata('user_type') == '1' ) {
      $row[] = '<a class="btn btn-sm btn-success" href="javascript:void()" title="approve" onclick="approve('."'".$master->id_master."'".')">Approve ?</a> <a class="btn btn-sm btn-danger" href="javascript:void()" title="tolak" onclick="tolak('."'".$master->id_master."'".')">Tolak ?</a>';
      }else{
      $row[] = 'Sedang diajukan ke admin ..';
      }
      }else if ($master->status == 2) {
      if ($this->session->userdata('user_type') == '1' ) {
      $row[] = 'Pengajuan ditolak admin dengan alasan: <br>'.$master->catatan_admin.'';
      }else{
      $row[] = 'Pengajuan ditolak, dengan alasan: <br>'.$master->catatan_admin.' <br><a class="btn btn-sm btn-success" href="javascript:void()" title="Edit" onclick="edit_master('."'".$master->id_master."'".')"><i class="glyphicon glyphicon-pencil"></i></a><a class="btn btn-sm btn-info" href="javascript:void()" title="Ajukan kembali? " onclick="ajukan_kembali('."'".$master->id_master."'".')">Ajukan lagi?</a> ';
      }
      }else if ($master->status == 3) {
      if ($this->session->userdata('user_type') == '1' ) {
      $row[] = '<a class="btn btn-sm btn-success" href="javascript:void()" title="approve" onclick="approve('."'".$master->id_master."'".')">Approve ?</a> <a class="btn btn-sm btn-danger" href="javascript:void()" title="tolak" onclick="tolak('."'".$master->id_master."'".')">Tolak ?</a>';
      }else{
      $row[] = 'Data telah diajukan kembali';


      }
      }else if ($master->status == 4) {
      if ($this->session->userdata('user_type') == '1' ) {
      $row[] = 'Pengajuan telah disetujui admin';
      }else{
      $row[] = 'Pengajuan telah disetujui admin';
      }
      }else{
      $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_master('."'".$master->id_master."'".')"><i class="glyphicon glyphicon-trash"></i></a> <a class="btn btn-sm btn-warning" href="javascript:void()" title="Ajukan" onclick="pengajuan('."'".$master->id_master."'".')">Ajukan ?</a>';
      }
      $row[] = $master->status;



      $data[] = $row;
      }

      $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->master->count_all(),
      "recordsFiltered" => $this->master->count_filtered(),
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
      }


      public function tambah_pengajuan()
      {
      $id = $this->session->userdata('id');
      $user_type = $this->session->userdata('user_type');
      $config['upload_path'] = './assets/image';
      $config['allowed_types'] = 'jpg|jpeg|png|gif';
      $config['max_size']	= 10000000000;

      $this->upload->initialize($config);
      if ( ! $this->upload->do_upload())
      {

      }
      else
      {
      $hasil = $this->upload->file_name;
      }

      $data = array(
      'nama_barang' => $this->input->post('nama_barang'),
      'jumlah' => $this->input->post('jumlah'),
      'harga' => $this->input->post('harga'),
      'catatan' => $this->input->post('catatan'),
      'tgl_dibuat' => date('Y-m-d'),
      'foto' => $hasil,
      'id_user' => $id,
      'user_type' => $user_type,

      );
      $insert = $this->master->save($data);
      }


      public function hapus_pengajuan($id)
      {
      $this->master->delete_by_id($id);
      echo json_encode(array("status" => TRUE));
      }

      public function get_table($status)
      {
      $data['status'] = $status;
      $this->load->view('table',$data);
      }

      public function pengajuan($id_master)
      {
      $data['status'] = '1';
      $data['tgl_pengajuan'] = date('Y-m-d');
      $this->db->where('id_master',$id_master);
      $this->db->update('data_master',$data);

      }
      public function approve($id_master)
      {
      $data['status'] = '4';
      $data['tgl_disetujui'] = date('Y-m-d');
      $this->db->where('id_master',$id_master);
      $this->db->update('data_master',$data);

      }
      public function tolak($id_master)
      {
      $data['status'] = '2';
      $data['catatan_admin'] = $this->input->post('catatan_admin');
      $data['tgl_penolakan'] = date('Y-m-d');
      $this->db->where('id_master',$id_master);
      $this->db->update('data_master',$data);

      }
      public function ajukan_kembali($id_master)
      {
      $data['status'] = '3';
      $data['tgl_pengajuan_kembali'] = date('Y-m-d');
      $this->db->where('id_master',$id_master);
      $this->db->update('data_master',$data);

      }

      public function edit_data($id_master)
      {
      $data = $this->db->query("select * from data_master where id_master = '$id_master' ")->row();
      echo json_encode($data);
      }

      public function cek_notif()
      {
      $user_type = $this->session->userdata('user_type');
      $user_id =$this->session->userdata('id');
      if ($user_type == 1) {
      $where = "";
      }else{
      $where = "where id_user = '$user_id' ";
      }

      $data = $this->db->query("SELECT id_master, id_user,
      COUNT(CASE WHEN status = 0 OR status = 1 OR status = 2 OR status = 3 OR status = 4  THEN 1 END) AS nol,
      COUNT(CASE WHEN status = 1 THEN 1 END) AS satu,
      COUNT(CASE WHEN status = 2 THEN 1 END) AS dua,
      COUNT(CASE WHEN status = 3 THEN 1 END) AS tiga,
      COUNT(CASE WHEN status = 4 THEN 1 END) AS empat FROM data_master
      $where
      ")->row();
      echo json_encode($data);
      }

      public function update_pengajuan()
      {
      $id_master = $this->input->post('id');
      $config['upload_path'] = './assets/image';
      $config['allowed_types'] = 'jpg|jpeg|png|gif';
      $config['max_size']	= 10000000000;
      $this->upload->initialize($config);
      if ( ! $this->upload->do_upload())
      {
      $hasil = $this->input->post('image2');
      }
      else
      {
      $hasil = $this->upload->file_name;
      }

      $data = array(
      'nama_barang' => $this->input->post('nama_barang'),
      'jumlah' => $this->input->post('jumlah'),
      'harga' => $this->input->post('harga'),
      'catatan' => $this->input->post('catatan'),
      'foto' => $hasil
      );
      $this->db->where('id_master',$id_master);
      $this->db->update('data_master',$data);

      }

     */
}
