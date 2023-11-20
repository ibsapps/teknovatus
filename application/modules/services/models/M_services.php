<?php
class M_services extends CI_Model
{

    public function check_otp($email, $id, $phone, $requestNumber)
    {
        return $this->db->get_where('service_auth', array(
            'user_email' => $email, 
            'phone_number' => $phone, 
            'trans_id' => $id, 
            'trans_code' => $requestNumber, 
            'authentication' => 0
        ));
    }

    public function get_otp($user_email, $phones, $requestId, $requestNumber)
    {
        $check = $this->db
            ->where('user_email', $user_email)
            ->where('phone_number', $phones)
            ->where('trans_id', $requestId)
            ->where('trans_code', $requestNumber)
            ->where('authentication', 0)
            ->get('service_auth');

        if ($check->num_rows() > 0) {
            return $check->row_array()['otp_code'];
        } else {
            return 0;
        }
    }

}
