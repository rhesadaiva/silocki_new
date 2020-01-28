<?php

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    // Validasi untuk ganti password
    public function validateChangePass()
    {
        $data['title'] = 'Ganti Password';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();

        // Set validasi
        $this->form_validation->set_rules('passwordlama', 'Password Lama', 'trim|required');
        $this->form_validation->set_rules('passwordbaru1', 'Password Baru', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('passwordbaru2', 'Konfirmasi Password Baru', 'trim|required|matches[passwordbaru1]|min_length[5]');

        // Jika hasil validasi = False, ambil response alert untuk dikonversi ke JSON
        if ($this->form_validation->run() == FALSE) {
            $response = [
                'error' => true,
                'passwordlama_error' => form_error(
                    'passwordlama',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <i class="fa fa-times-circle"></i> ',
                    '</div>'
                ),

                'passwordbaru1_error' => form_error(
                    'passwordbaru1',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <i class="fa fa-times-circle"></i> ',
                    '</div>'
                ),

                'passwordbaru2_error' => form_error(
                    'passwordbaru2',
                    '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <i class="fa fa-times-circle"></i> ',
                    '</div>'
                ),
            ];
            // Kembalikan response sebagai JSON
            echo json_encode($response);

            // Apabila validasi benar
        } else {
            // Ambil data inputan
            $passwordlama = md5($this->input->post('passwordlama'));
            $passwordbaru = $this->input->post('passwordbaru1');
            $currentpass = $data['user']['password'];

            //Jika input pada form Password Sekarang tidak match pada database, ambil respon alert untuk dikonversi ke JSON
            if ($passwordlama != $currentpass) {
                $response = [
                    'notmatch' => true,
                    'notmatchalert' => '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <i class="fa fa-times-circle"></i> Password lama tidak sesuai dengan password sekarang!
                                        </div>'
                ];
                // Kembalikan response sebagai JSON
                echo json_encode($response);

                // Jika semua kondisi terpenuhi, update passwordnya
            } else {
                // Update Passwordnya
                $this->_updatePassword($passwordbaru);
                $response = [
                    'success' => true,
                    'successalert' => '<div class="alert alert-success alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <i class="fa fa-check-circle"></i> Password berhasil diubah! Silahkan gunakan password baru anda pada saat login.
                                        </div>'
                ];
                // Kembalikan response sebagai JSON
                echo json_encode($response);
                helper_log("edit", "Mengubah password");
            }
        }
    }

    // Private function untuk update password ke database
    private function _updatePassword($passwordbaru)
    {
        // Convert to md5
        $newpassword = md5($passwordbaru);

        // Update to database
        $this->db->set('password', $newpassword);
        $this->db->where('nip', $this->session->userdata('nip'));
        $this->db->update('user');
    }

    // Melakukan update foto profil serta resize
    public function doUploadProfile()
    {
        $this->load->model('Global_model');
        $nip = $this->session->userdata('nip');
        $this->load->library('upload');

        // Config untuk library upload
        $config['upload_path'] = './assets/img/profile';
        $config['allowed_types'] = 'gif|jpg|png|jpeg'; //type yang dapat diakses bisa anda sesuaikan
        $config['file_name'] = $nip;

        $this->upload->initialize($config);

        // Jika data tidak kosong
        if (!empty($_FILES['filefoto']['name'])) {

            // Jika sudah upload
            if ($this->upload->do_upload('filefoto')) {

                // Ambil array data upload
                $pic = $this->upload->data();

                // Config untuk library Resize Image
                $config['image_library'] = 'gd2';
                $config['source_image'] = './assets/img/profile/' . $pic['file_name'];
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;
                $config['quality'] = '100%';
                $config['width'] = 120;
                $config['height'] = 120;
                $config['new_image'] = './assets/img/profile/' . $pic['file_name'];

                // Do the resize process
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                // Ambil nama filenya
                $profilePicture = $pic['file_name'];

                // Insert ke Database
                $updatePhoto =  $this->Global_model->saveUpload($profilePicture, $nip);
                echo json_decode($updatePhoto);
            } else {
                // Jika gagal
                $errorMessage = $this->upload->display_errors();
                echo $errorMessage;
            }
        }
    }
}
