<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  require '/var/www/html/application/vendor/phpmailer/phpmailer/src/Exception.php';
  require '/var/www/html/application/vendor/phpmailer/phpmailer/src/PHPMailer.php';
  require '/var/www/html/application/vendor/phpmailer/phpmailer/src/SMTP.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception ;
class Login extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('login_model', 'login_m', TRUE);
    $this->load->library('enc');
    $this->load->model('login/login_model');
    $this->email = $this->session->userdata('user_email');
    $this->date = date('Y-m-d H:i:s');
    $this->year = date('Y');
    $this->load->library('curl');
    $this->load->library('enc');
  }


  public function index()
  {
    //dumper(encrypt('12345'));
    if(($this->session->userdata('nik')!='') && ($this->session->userdata('nik')!=' ')){
      redirect('dashboard');
    }else{
      $this->load->view('login/index');
    }
  }

  public function forgot_password()
  {
    $kind = $this->input->get('kind');

    $data['kind'] = $kind;

    $response = $this->load->view('login/forgot_password', $data, TRUE);
    header('Content-type: application/json');
    echo json_encode($response);
  }

  public function reset_password()
  {
    $email = $this->input->post('email');
    $cek_email = $this->login_model->checkEmail($email);

    $is_status = "Live";

    if((!empty($cek_email[0]->nik))){

      $nik = $cek_email[0]->nik;
      $sql = "SELECT * FROM users WHERE employee_id='$nik'";
      $query = $this->db->query($sql);
      $res = $query->result();

      if (!empty($res[0]->id_user)) {
        $emp_id = $res[0]->employee_id;
        $update_password = $this->login_model->updatePassword($emp_id);
        
        $sql = "SELECT * FROM users WHERE employee_id='$emp_id'";
        $query = $this->db->query($sql);
        $res = $query->result();        

        $new_reset_password = decrypt($res[0]->password);
        $this->sendEmail('reset_password_email', $res[0]->full_name, $res[0]->user_email, $new_reset_password);

        $data['kind'] = 'reset_password';
        $data['content'] = $this->load->view('login/forgot_password', $data, TRUE);
        $response = array('status' => 1, 'message' => 'Success', 'data' => $data);
      } else {
        $response = array('status' => 0, 'message' => '<b>user has not been created!</b>');
      }
    } else{
      $response = array('status' => 0, 'message' => '<b>Email not registered!</b>');
    }
      header('Content-type: application/json');
      echo json_encode($response);

  }

  public function sendEmail($type, $fullname, $email_to, $new_password)
  {

    $data['email'] = $email_to;
    $data['fullname'] = $fullname;
    $data['new_password'] = $new_password;
    $html = $this->load->view('services/email/reset_password_email', $data, TRUE);
    $email_subject = 'Reset Password Email';

    // $url = 'http://dev-medclaim.ibsmulti.com';
    // $params = array(
    //  'app_name'      => 'IBST-HRIS',
    //  'ip_address'    => $_SERVER['SERVER_ADDR'],
    //  'email_to'      => $email_to,
    //  'email_subject' => $email_subject,
    //  'email_content' => $html,
    //  'is_status'   => 0,
    //  'created_at'  => $this->date,
    //  'created_by'  => $this->email
    // );

    //  $htmlContent = '<h3 align=center>EMAIL AM ONLINE </h3>';
    // $htmlContent .= '<hr>';
    // $htmlContent .= '<br>';
    // $htmlContent .= '<div style=border:1px solid black;';

    // $htmlContent .= '<p><b>NOTIFICATION</b></p>';
    // $htmlContent .= '<p>New notification, from AM Online</p>';
    // $htmlContent .= '<p>AM NUMBER : '.$am.'</p>';
    // $htmlContent .= '<p>PURPOSE   : '.$description.'</p>';
    // $htmlContent .= '<hr>';
    // $htmlContent .= '<p>STATUS :'.$status.'</p>';

    // $htmlContent .= '<p>please check the am online application, <a href=http://mis.ibsmulti.com/amonline/>HERE</a></p>';


    $mail = new PHPMailer();
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host       = 'mail.ibsmulti.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'no.reply@ibsmulti.com'; // ubah dengan alamat email Anda
    $mail->Password   = '2023@54321No.Reply'; // ubah dengan password email Anda
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('no.reply@ibsmulti.com', 'Medclaim - Notification System'); // ubah dengan alamat email Anda
    $mail->addAddress($email_to);

    // Isi Email
    $mail->isHTML(true);
    $mail->Subject = $email_subject;
    $mail->Body    = $html;

    $mail->send();
    // $this->email->print_debugger(array('headers'));

    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    // curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // $result = curl_exec($ch);
    // if (curl_errno($ch) !== 0) {
    //  print_r('Oops! cURL error when connecting to ' . $url . ': ' . curl_error($ch));
    // }

    // curl_close($ch);
  }


  public function proses_login()
  {
    $email = $this->input->post('email');
    $password = $this->input->post('password');
    $password = encrypt($password);
    $data = $this->login_model->getUsers($email, $password);

    // $is_status = "Maintenance";
    $is_status = "Live";

    if((!empty($data[0]->nik))){
      //$nik_superior = decrypt($data[0]->superior);
      //$email_superior = $this->db->get_where('hris_employee',array('nik'=>$nik_superior))->row_array()['email'];
      //dumper($email_superior);

			$sess_array = array(     
				'user_email' => decrypt($data[0]->email),   
				'user_role' => $data[0]->user_role,    
				'access_level' => $data[0]->access_level, 
				'verification_status' => $data[0]->verification_status, 
				'employee_id' => $data[0]->nik,
				'employee_name' => decrypt($data[0]->complete_name),
				'join_date' => $data[0]->join_date,
				'action' => $data[0]->action,
				'employee_subgroup' => $data[0]->employee_subgroup, 
				'employee_group' => $data[0]->employee_group, 
				'name' => decrypt($data[0]->complete_name), 
				'cost_center' => decrypt($data[0]->cost_center), 
				'nik' => $data[0]->nik,
				'division' => $data[0]->division,
				'gender' => $data[0]->gender,
				'access_employee' => $data[0]->user_role,
				'access_level' => $data[0]->access_level,
				'id_hr_emp' => $data[0]->id_employee,
				'verification_status' => $data[0]->verification_status
			);


			$sql = "SELECT * FROM users WHERE employee_id='20230011'";
			$query = $this->db->query($sql);
			$res = $query->result();

    	// dumper($res);

      $test = array(
        'ts' => decrypt($res[0]->password)  
      );

    	// $test = encrypt('78213');
      // dumper($test);




			$this->session->set_userdata($sess_array);
			$data_ses = $this->session->userdata();

			if($is_status == "Maintenance" && $email != "hr.support@ibsmulti.com" && $email != "emilia.sutanto@ibsmulti.com"){
					$this->session->sess_destroy();
					$this->load->view('login/maintenance_503');
			} else {
				redirect('dashboard');
			}

		} else {
			redirect('login');
		}
    
  }

    // public function index()
    // {
    //     if ($this->enc->check_session()) {
    //         if ($this->enc->access_user()) {
    //             redirect('list/ebast');
    //             exit();
    //         } else {
    //             $url = base_url();
    //             redirect($url);
    //             exit();
    //         }
    //     } 
    // }

    // ebast
  //   public function checkToken($email_account = NULL, $token = NULL)
  //   {  
  //       $db1 = $this->config->item("db1");
    // $db2 = $this->config->item("db2");
  //       $email = urldecode ($email_account);
  //       $token = urldecode ($token);

  //       $curr_date = date('Y-m-d');
  //       $this->db->select("count(*) as jumlah");
  //       $this->db->from($db2.".dbo.th_token_login thl");
  //       $this->db->where("thl.s_user",$email);
  //       $this->db->where("thl.s_token",$token);
  //       $this->db->where("CONVERT (DATE, s_created_on) = ",$curr_date);
  //       $this->db->where("thl.n_istatus",1);
  //       $query = $this->db->get();
  //       $ret = $query->row();
  //       $jumlah = $ret->jumlah;

  //       if ($jumlah > 0){

  //           $this->db->from($db2.'.dbo.tm_user tmu');
  //           $this->db->where('tmu.n_istatus',1);
  //           $this->db->where('tmu.s_username', $email);
  //           // Run the query
  //           $query = $this->db->get();

  //           if ($query->num_rows() == 1) {

  //               $row = $query->row();
  //               $this->db->select("*");
  //               $this->db->where("user_email", $email);

  //               $eapproval = $this->db->get($db1 . ".dbo.users");

  //               if ($eapproval->num_rows() == 1) {

  //                   foreach ($eapproval->result_array() as $el) {
  //                       $user_name     = $el['user_name'];
  //                       $user_email    = $el['user_email'];
  //                       $user_role     = $el['user_role'];
  //                       $access_level  = $el['access_level'];
  //                       $phone_number  = $el['phone_number'];
  //                       $verification_status  = $el['verification_status'];
  //                   }

  //                   $sess_array = array(
  //                                       'username' => $user_name,     
  //                                       'user_email' => $user_email,   
  //                                       'user_role' => $user_role,    
  //                                       'access_level' => $access_level, 
  //                                       'verification_status' => $verification_status, 
  //                                       'phone_number' => $phone_number );

  //                   $this->session->set_userdata($sess_array);

  //                   if ($verification_status == '0') {

  //                       redirect('register/v2/eb');
  //                       exit();

  //                   } else {
  //                       redirect('list/ebast');
  //                       exit();
  //                   }
  //                   exit();
                    
  //               } else {
  //                   // user not found
  //                   $url = base_url();
  //                   redirect($url);
  //                   exit();
  //               }

  //           } else {
  //               // user SSO not found
  //               $url = base_url();
  //               redirect($url);
  //               exit();
  //           }
        
  //       } else {
  //           // checktoken failed
  //           $url = base_url();
  //           redirect($url);
  //           exit();
  //       }
  //   }

    // eapp
  public function checkToken2($email_account = NULL, $token = NULL)
  {  
    $db1 = $this->config->item("db1");
    $db2 = $this->config->item("db2");
    $email = urldecode ($email_account);
    $token = urldecode ($token);

    $curr_date = date('Y-m-d');
    $this->db->select("count(*) as jumlah");
    $this->db->from($db2.".dbo.th_token_login thl");
    $this->db->where("thl.s_user",$email);
    $this->db->where("thl.s_token",$token);
    $this->db->where("CONVERT (DATE, s_created_on) = ",$curr_date);
    $this->db->where("thl.n_istatus",1);
    $query = $this->db->get();
    $ret = $query->row();
    $jumlah = $ret->jumlah;

    if ($jumlah > 0){

      $this->db->from($db2.'.dbo.tm_user tmu');
      $this->db->where('tmu.n_istatus',1);
      $this->db->where('tmu.s_username', $email);
      // Run the query
      $query = $this->db->get();

      if ($query->num_rows() == 1) {
        $row = $query->row();
        $this->db->select("*");
        $this->db->where("user_email", $email);

        $eapproval = $this->db->get($db1 . ".dbo.users");

        if ($eapproval->num_rows() == 1) {
          foreach ($eapproval->result_array() as $el) {
            $user_name     = $el['user_name'];
            $user_email    = $el['user_email'];
            $user_role     = $el['user_role'];
            $access_level  = $el['access_level'];
            $phone_number  = $el['phone_number'];
            $employee_id  = $el['employee_id'];
            $verification_status  = $el['verification_status'];
          }

          $this->db->where("id", $employee_id);
          $employee = $this->db->get($db1 . ".dbo.employee")->row_array();

          $sess_array = array(
            'username' => $user_name,     
            'user_email' => $user_email,   
            'user_role' => $user_role,    
            'access_level' => $access_level, 
            'verification_status' => $verification_status, 
            'employee_id' => $employee_id, 
            'name' => $employee['employee_name'], 
            'nik' => $employee['employee_nik'], 
            'position' => $employee['position'], 
            // 'position_level' => $employee['position_level'], 
            // 'division_root' => $employee['division_root'], 
            'office_location' => $employee['office_location'], 
            'division' => $employee['division'], 
            'join_date' => $employee['join_date'], 
            'employment_status' => $employee['employment_status'], 
            'access_employee' => $employee['access_employee'], 
            // 'second_division' => $employee['second_division'], 
            'phone_number' => $phone_number
          );

          $this->session->set_userdata($sess_array);

          if ($verification_status == '0') {
            redirect('register/v2/e');
            exit();
          } else {
            redirect('form');
            exit();
          }
          exit();
        } else {
          // user not found
          $url = base_url();
          redirect($url);
          exit();
        }
      } else {
        // user SSO not found
        $url = base_url();
        redirect($url);
        exit();
      }
    } else {
      // checktoken failed
      $url = base_url();
      redirect($url);
      exit();
    }
  }
  
  public function do_logout()
  {
    $this->session->sess_destroy();
    $url = base_url();
    redirect($url);
    exit();
  }

  function email_name($address)
  {
    $str    = substr($address, strpos($address, "@") + 1);
    $str    = str_replace('@'.$str, '', $address);
    $str    = str_replace('.', ' ', $str);

    if ($str == 'fadli fadli') {
      $str = 'Super Admin';
    }
    else {
      $str = ucwords($str);
    }

    return $str;
  }

  //public function sendotp($type, $id, $request_number, $method)
  // public function sendotpMDCR($type, $method)
  // {
  //   $request_id    = $_POST['id'];
  //   //dumper($id);

  //   $sql = "SELECT * FROM form_request WHERE id='$request_id'";
    // $query = $this->db->query($sql);
    // $res = $query->result();
  //   $request_number = $res[0]->request_number;
  //   $request_type = $res[0]->form_type;

  //   $sql = "select * from form_approval where request_id='$request_id' and approval_status='In Progress' and approval_priority = '1'";
  //  $query = $this->db->query($sql);
  //  $res = $query->result();
  //   $approval_email = $res[0]->approval_email;
  //   //dumper($res);


  //   //delete jika ada otp berjalan dengan data sama
  //   $sql = "DELETE service_auth WHERE trans_id = '$request_id' AND trans_code = '$request_number' AND user_email = '$approval_email'";
  //   $query = $this->db->query($sql);


  //  $transok = 0;
  //  $username = "ibstower_api";
  //  $password = "cZU4Hs7";
  //   $data_api = $this->get_data_api_employee($approval_email);
    // $phone = $data_api['phone_number'];
  //   $request_number = $request_number;
  //   $email = $approval_email;
  //   $id = $request_id;

  //  //$phone = $this->phone;
  //  $otp_code = mt_rand(100000, 999999);
  //  $message = "$otp_code is your authentication code for request number: [ $request_number ].";

    // switch ($method) {

    //  case 'sms':

    //    $curl = curl_init();
    //    curl_setopt_array($curl, array(
  //        CURLOPT_RETURNTRANSFER => 1,
  //        CURLOPT_URL => 'http://api.nusasms.com/api/v3/sendsms/plain',
  //        CURLOPT_POST => true,
  //        CURLOPT_POSTFIELDS => array(
  //          'username' => $username,
  //          'password' => $password,
  //          'SMSText' => $message,
  //          'GSM' => $phone,
  //          'otp' => 'Y',
  //          'output' => 'json'
  //        )
    //    ));

    //    $resp = curl_exec($curl);
    //    $res = (array) json_decode($resp, true);
    //    $status = $res['results'][0]['status'];

    //    if ($status == '0') {
    //      if ($type == 'send') {

    //        $send = array(
    //          'user_email'    => $email,
    //          'phone_number'    => $phone,
    //          'otp_code'      => $otp_code,
    //          'authentication'  => 0,
    //          'activity_name'   => 'OTP via SMS',
    //          'last_sent'     => date('Y-m-d'),
    //          'trans_id'      => $id,
    //          'trans_code'    => $request_number,
    //          'messages'      => $message
    //        );

    //        if ($this->db->insert('service_auth', $send)) {
    //          $logs_msg = 'Send OTP via SMS successfully';
    //          $this->logs('approved', 'MDCR', $request_id, $logs_msg, $logs_msg);
    //          $transok = 1;
    //        }
    //      } else {

    //        $this->db->where(array(
    //          'user_email' => $this->email,
    //          'phone_number' => $phone,
    //          'trans_code' => $request_number,
    //          'authentication' => 0,
    //          'trans_id' => $id
    //        ));

    //        $resend = array(
    //          'otp_code' => $otp_code,
    //          'messages' => $message,
    //          'activity_name' => 'Resend OTP via SMS',
    //          'last_sent' => $this->date
    //        );

    //        if ($this->db->update('service_auth', $resend)) {
    //          $logs_msg = 'Resend OTP via SMS successfully';
    //          $this->logs('approved', 'MDCR', $request_id, $logs_msg, $logs_msg);
    //          $transok = 1;
    //        }
    //      }
    //    }

    //    curl_close($curl);
    //    echo json_encode($transok);
    //    break;

    //  default:
    //    break;
    // }
  // }

 //  public function validateMDCR($resp)
  // {
  //  $output = array('status' => 0, 'message' => 'Authentication failed.');
 //    $request_id    = $_POST['request_id'];
 //    $otp       = $_POST['otp'];
 //    $sql = "SELECT * FROM form_request WHERE id='$request_id'";
  //  $query = $this->db->query($sql);
  //  $res = $query->result();
 //    $request_number = $res[0]->request_number;
 //    $request_type = $res[0]->form_type;
 //    $sql = "select * from form_approval where request_id='$request_id' and approval_status='In Progress' and approval_priority = '1'";
  //  $query = $this->db->query($sql);
  //  $res = $query->result();
 //    $approval_id = $res[0]->id;
 //    $approval_email = $res[0]->approval_email;
 //    //dumper($res);
 //    $data_api = $this->get_data_api_employee($approval_email);
  //  $phone = $data_api['phone_number'];
  //  $request_id = $request_id;
  //  $request_number = $request_number;

  //  #data response approval
  //  $approval = array('approval_status' => $resp, 'updated_at' => date('Y-m-d'), 'updated_by' => '$approval_email');

  //  //$getOTP = $this->m_services->get_otp('luffi.utomo@ibsmulti.com', $phone, $request_id, $request_number);
 //    $sql = "SELECT otp_code FROM service_auth WHERE user_email LIKE '$approval_email' AND phone_number LIKE '$phone' AND trans_id LIKE '$request_id' AND trans_code LIKE '$request_number'";
 //    $getOTP = $this->db->query($sql);
 //    $getOTP = $getOTP->result();
        

  //  switch ($resp) {
  //    case 'Approved':  
  //      if ($getOTP[0]->otp_code == $otp) {
  //        #update service auth
  //        $this->db->where(array('user_email' => '$approval_email', 'otp_code' => $otp, 'phone_number' => '08888933033', 'trans_id' => $request_id, 'trans_code' => $request_number));
  //        if ($this->db->update('service_auth', array('authentication' => 1))) {

  //          #check approver list
  //          $sql = "SELECT * FROM form_approval WHERE id >= '$approval_id' AND request_id = '$request_id' ORDER BY approval_priority ASC OFFSET 1 ROWS FETCH NEXT 1 ROWS ONLY";
  //          $checkleft = $this->db->query($sql);
  //          if ($checkleft->num_rows() > 0) {
  //            #update response approval
  //            $this->db->where('id', $approval_id);
  //            if ($this->db->update('form_approval', $approval)) {
  //              #set in progress for next approver
  //              $this->db->where('id', $checkleft->row_array()['id']);
  //              if ($this->db->update('form_approval', array('approval_status' => 'In Progress'))) {

  //                $this->logs('approved', 'MDCR', $request_id, 'Approved successfully', 'Approved successfully');
  //                $output = array('status' => 1, 'message' => 'Approved successfully.', 'id' => encode_url($request_id));
  //              } else {
  //                $this->logs('system', $request_id, 'Authentication success, but failed while updating the next approver.');
  //                $output = array('status' => 1, 'message' => 'Authentication success, but failed while updating the next approver.');
  //              }
  //            } else {
  //              $this->logs('system', $request_id, 'Authentication success, but failed while updating response approval.');
  //              $output = array('status' => 1, 'message' => 'Authentication success, but failed while updating response approval. ');
  //            }
  //          } else {  
  //            #update response approval
  //            $this->db->where('id', $approval_id);
  //            if ($this->db->update('form_approval', $approval)) {
  //              //$this->sendEmail('approved_eapp', $request_id, $requestor);
  //              $this->logs('approved', $request_id, 'Approved successfully.');
  //              $output = array('status' => 1, 'message' => 'Approved successfully.', 'id' => encode_url($request_id));
  //            } else {
  //              $this->logs('system', $request_id, 'Authentication success, but failed while updating response approval [Full Approved].');
  //              $output = array('status' => 1, 'message' => 'Authentication success, but failed updating response approval.');
  //            }
  //          }
  //        } else {
 //            $this->logs('system', 'MDCR', $request_id, 'Failed while updating service auth.', 'Failed while updating service auth.');
  //          $output = array('status' => 0, 'message' => 'Please try again.');
  //        }
  //      } else {
 //          $this->logs('system', 'MDCR', $request_id, 'Authentication Failed. Code didn\'t match.', 'Authentication Failed. Code didn\'t match.');
  //      }
  //      break;
  //    default:
  //      break;
  //  }

  //  echo json_encode($output);
  // }

  public function logs($type, $formType, $id, $activity = '', $description = '')
  {
    $log['request_id'] = $id;
    $log['form_type'] = $formType;
    $log['created_by'] = ($type == 'system') ? 'system' : $this->email;
    $log['created_at'] = $this->date;

    switch ($type) {
      case 'system':
        $log['activity'] = $activity;
        $log['description'] = $description;
        $this->db->insert('logs', $log);
        break;
      case 'approved':
        $log['activity'] = 'Approved';
        $log['description'] = 'Success';
        $this->db->insert('logs', $log);
        break;
      default:
        break;
    }
  }

  public function get_data_api_employee($email)
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.ibstower.com/employee_service/'.$email);
    curl_setopt($curl, CURLOPT_HTTPGET, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($curl);
    $response = json_decode($response,true);
    curl_close($curl);

    return $response;
  }

}
?>