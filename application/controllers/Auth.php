<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function index()
    {
        $this->form_validation->set_rules('nip', 'NIP', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/authv2_header');
            $this->load->view('login/index_v2');
            $this->load->view('templates/authv2_footer');
        } else {
            //validation success
            $this->_login();
        }
    }

    //Private function login
    private function _login()
    {
        $username = $this->input->post('nip');
        $password = md5($this->input->post('password'));

        $user = $this->db->get_where('user', ['nip' => $username])->row_array();

        if ($user) {
            //cek password
            if ($password == $user['password']) {

                $data = [
                    'nama' => $user['nama'],
                    'nip' => $user['nip'],
                    'role_id' => $user['role_id'],
                    'pegawai_id' => $user['id']
                ];
                $this->session->set_userdata($data);
                helper_log("login", "Masuk ke aplikasi SiLocki");

                if ($user['role_id'] == 1) {

                    redirect('index-admin');
                } elseif ($user['role_id'] == 2) {

                    redirect('kepalakantor');
                } elseif ($user['role_id'] == 3) {

                    redirect('pejabat');
                } elseif ($user['role_id'] == 4) {

                    redirect('pejabat');
                } else {
                    redirect('pelaksana');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"><b class="alert-message">NIP atau Password tidak sesuai!</b></div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"><b class="alert-message">NIP atau Password tidak sesuai!</b></div>');
            redirect('auth');
        }
    }

    //Logout
    public function logout()
    {
        $this->session->sess_destroy();
        helper_log("logout", "Keluar dari aplikasi SiLocki");
        redirect('auth');
    }

    //Halaman Blocked
    public function blocked()
    {
        $this->load->view('login/blocked');
    }

    public function lupapassword()
    {
        $this->form_validation->set_rules('nipforgot', 'NIP', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/authv2_header');
            $this->load->view('login/index_v2');
            $this->load->view('templates/authv2_footer_auto_modal');
        } else {
            $this->_forgotpass();
        }
    }

    private function _forgotpass()
    {
        // Ambil data NIP dari inputan form
        $nipforgot = $this->input->post('nipforgot', true);

        // Cek NIP apakah tersedia di database
        $user = $this->db->get_where('user', ['nip' => $nipforgot])->row_array();

        // Jika ada NIP
        if ($nipforgot == $user['nip']) {

            $data = [
                'nama' => $user['nama'],
                'nip'  => $nipforgot
            ];

            // Update session
            $this->session->set_userdata($data);

            // Set token
            $token = mt_rand(100000, 999999);

            // Set data Token
            $user_token = [
                'nama' => $user['nama'],
                'nip' => $nipforgot,
                'token' => $token,
                'is_used' => 0,
                'is_sent' => 0
            ];

            // Insert Token, dan redirect ke halaman ganti password
            $this->db->insert('user_token', $user_token);
            redirect('auth/resetpassword');

            // Jika NIP tidak ditemukan
        } else {
            $this->session->set_flashdata('forgot', '<div class="alert alert-danger-sm" role="alert"><b class="alert-message">NIP atau Password tidak sesuai!</b></div>');
            redirect('auth/lupapassword');
        };
    }

    public function resetpassword()
    {
        $data['nama'] = $this->session->userdata('nama');
        $data['nip'] = $this->session->userdata('nip');

        $nipforgot = $this->session->userdata('nip');

        // Ambil data Token yang terkirim tapi belum digunakan
        $getTokenData = $this->db->get_where('user_token', ['nip' => $nipforgot, 'is_sent' => 1, 'is_used' => 0])->row_array();
        $tokendata = $getTokenData['token'];

        $this->form_validation->set_rules('resetpass', 'Form', 'trim|required');
        $this->form_validation->set_rules('konfirmresetpass', 'Form', 'trim|required');
        $this->form_validation->set_rules('token_number', 'trim|required');

        // Jika validasi gagal, kembali ke halaman resetpassword
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/authv2_header');
            $this->load->view('login/resetpassword', $data);
            $this->load->view('templates/authv2_footer');

            // Jika validasi sukses, ambil inputan token dan password
        } else {
            $tokeninput = $this->input->post('tokennumber');
            $resetpass = $this->input->post('resetpass');

            // Jika token yang diinput != token yang ada di database, kembali ke halaman reset password
            if ($tokeninput != $tokendata) {
                $this->session->set_flashdata('reset', '<div class="alert alert-danger-sm" role="alert"><b class="alert-message">TOKEN TIDAK SESUAI!</b></div>');
                redirect('auth/resetpassword');

                // Jika Password OK
            } else {

                // Tetapkan password baru
                $newpassreset = md5($resetpass);

                // Insert New Password
                $this->db->set('password', $newpassreset);
                $this->db->where('nip', $nipforgot);
                $this->db->update('user');

                //Ganti Status Token menjadi sudah dipakai
                $this->db->set('is_used', 1);
                $this->db->where('token', $tokeninput);
                $this->db->where('is_sent', 1);
                $this->db->update('user_token');

                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert"><b class="alert-message">Password berhasil direset!</b></div>');

                redirect('auth');

                $this->logout();
            }
        }
    }

    public function requestToken()
    {
        $nipforgot = $this->session->userdata('nip');
        $namaforgot = $this->session->userdata('nama');

        // Ambil data token
        $query = $this->db->query("SELECT `user_token`.*, `user`.`telegram` 
                                    FROM `user_token` JOIN `user`  USING(nip) 
                                    WHERE `user_token`.`nip` = '$nipforgot' AND `is_used` = 0 AND `is_sent` = 0 ");
        $data['tokendata'] = $query->row_array();

        // Tetapkan data token
        $tokenid = $data['tokendata']['token'];

        // Ambil data untuk dikirim ke Telegram
        $telegramuser['nama'] = $this->db->get_where('user', ['nip' => $nipforgot])->row_array();

        // Kirim Token Ke Telegram
        $this->_telegram(
            $telegramuser['nama']['telegram'],
            "Halo, *" . $namaforgot . "*. \n\nSistem mendeteksi adanya permintaan reset password aplikasi *SILOCKI* yang dilakukan oleh akun anda. \n\nBerikut adalah nomor token untuk mereset akun anda = *" . $tokenid . "* \n\nSilahkan abaikan pesan ini apabila anda tidak merasa melakukan permintaan reset. Terima Kasih."
        );

        // Update status pesan menjadi sudah terkirim
        $this->db->set('is_sent', 1);
        $this->db->where('token', $tokenid);
        $this->db->where('is_used', 0);
        $this->db->update('user_token');

        redirect('auth/resetpassword');
    }

    private function _telegram($telegram, $message)
    {
        $url = "https://api.telegram.org/bot905076968:AAG8sNGqlABcYAw6PuUL6eSuFn1-pmSGUpU/sendMessage?parse_mode=markdown&chat_id=" . $telegram;
        $url = $url . "&text=" . urlencode($message);

        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);
        curl_close($ch);
    }
}
