<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        cek_admin();
        $this->load->model('Admin_model');
        $this->load->model('Global_model');
    }

    // HALAMAN INDEX
    public function index()
    {
        $data['title'] = 'Home';
        $data['user'] = $this->Admin_model->getLoggedUser($this->session->userdata('nip'));
        $data['jumlahUser'] = $this->Admin_model->countUser();
        $data['jumlahKontrakKinerja'] = $this->Admin_model->countKK();
        $data['jumlahIKU'] = $this->Admin_model->countIKU();
        $data['jumlahLogbook'] = $this->Admin_model->countLogbook();
        $data['recentActivity'] = $this->Global_model->getLastActivity($this->session->userdata('nama'));

        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/main_sidebar');
        $this->load->view('admin/index');
        $this->load->view('templates/main_footer');
    }

    // MANAJEMEN USER
    public function manajemenUser()
    {
        $data['title'] = 'Manajemen User';
        $data['user'] = $this->Admin_model->getLoggedUser($this->session->userdata('nip'));
        $data['user_data'] = $this->Admin_model->getUsersData();

        helper_log("access", "Mengakses menu Manajemen User");

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin');
        $this->load->view('templates/topbar');
        $this->load->view('admin/manajemen_user', $data);
        $this->load->view('templates/footer');
    }

    //Fungsi tambah pegawai
    public function tambahpegawai()
    {
        $data['title'] = 'Tambah Pegawai';
        $data['user'] = $this->Admin_model->getLoggedUser($this->session->userdata('nip'));
        $data['user_data'] = $this->Admin_model->getUsersData();
        $data['pangkat'] = $this->Admin_model->getPangkat();
        $data['role'] = $this->Admin_model->getRole();
        $data['seksi'] = $this->Admin_model->getSeksi();

        //Validasi Tambah User
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim|is_unique[user.nama]');
        $this->form_validation->set_rules('nip', 'Nomor Induk Pegawai', 'required|trim|is_unique[user.nip]|numeric');
        $this->form_validation->set_rules('telegram', 'ID Telegram', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar_admin');
            $this->load->view('templates/topbar');
            $this->load->view('admin/tambah_user', $data);
            $this->load->view('templates/footer');
        } else {

            $this->Admin_model->tambahUser();

            helper_log("add", "Menambah data pegawai baru");

            $this->session->set_flashdata('user', 'ditambahkan dengan password 123456');
            redirect('admin/manajemen_user');
        }
    }

    //Fitur Edit User
    public function editpegawai($id)
    {
        $data['title'] = 'Ubah Data Pegawai';
        $data['user'] = $this->Admin_model->getLoggedUser($this->session->userdata('nip'));
        $data['user_data'] = $this->Admin_model->getUsersData();
        $data['userdetail'] = $this->Admin_model->getUserByID($id);
        $data['pangkat'] = $this->Admin_model->getPangkat();
        $data['role'] = $this->Admin_model->getRole();
        $data['seksi'] = $this->Admin_model->getSeksi();

        //Validasi EDIT User
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim|is_unique[user.nama]');
        $this->form_validation->set_rules('nip', 'Nomor Induk Pegawai', 'required|trim|is_unique[user.nip]|numeric');
        $this->form_validation->set_rules('telegram', 'ID Telegram', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar_admin');
            $this->load->view('templates/topbar');
            $this->load->view('admin/edituser', $data);
            $this->load->view('templates/footer');
        } else {
            //Validasi
            $this->Admin_model->editUser($id);
            helper_log("edit", "Mengubah data pegawai (id-pegawai = $id)");
            $this->session->set_flashdata('user', 'berhasil diubah, silahkan melanjutkan kegiatan anda!');
            redirect('admin/manajemen_user');
        }
    }

    //Hapus User
    public function hapuspegawai($id)
    {
        $this->Admin_model->deleteUser($id);
        helper_log("delete", "Menghapus data pegawai (id-pegawai = $id)");
        $this->session->set_flashdata('user', 'berhasil dihapus. Silahkan melanjutkan kegiatan anda!');
        redirect('admin/manajemen_user');
    }

    //Halaman Pencarian Logbook belum disetujui
    public function logbookbelumdisetujui()
    {
        $data['title'] = 'Logbook Yang Belum Disetujui';
        $data['user'] = $this->Admin_model->getLoggedUser($this->session->userdata('nip'));
        $data['periode'] = $this->input->get('periodepelaporan');

        $data['belumlogbook'] = $this->Admin_model->pegawainotvalidatedlogbook();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin');
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/belumapprovelogbook', $data);
        $this->load->view('templates/footer');
    }

    //Halaman Filter Logbook belum disetujui
    public function filterlogbookbelumdisetujui()
    {
        $data['title'] = 'Logbook Yang Belum Disetujui';
        $data['user'] = $this->Admin_model->getLoggedUser($this->session->userdata('nip'));
        $data['periode'] = $this->input->get('periodepelaporan');

        $data['belumlogbook'] = $this->Admin_model->filternotvalidatedlogbook($data['periode']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin');
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/belumapprovelogbook', $data);
        $this->load->view('templates/footer');
        helper_log("access", "melakukan pencarian pada menu Logbook Yang Belum Disetujui");
    }

    //Halaman Daftar Pegawai yang sudah rekam logbook
    public function logbookselesai()
    {
        $data['title'] = 'Logbook Yang Sudah Divalidasi';
        $data['user'] = $this->Admin_model->getLoggedUser($this->session->userdata('nip'));
        $data['periode'] = $this->input->get('periodepelaporan');


        $data['logbookclear'] = $this->Admin_model->logbookclear();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin');
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/logbookselesai', $data);
        $this->load->view('templates/footer');
    }

    // //Halaman Pencarian Pegawai yang belum rekam logbook
    public function filterlogbookselesai()
    {
        $data['title'] = 'Logbook Yang Sudah Divalidasi';
        $data['user'] = $this->Admin_model->getLoggedUser($this->session->userdata('nip'));
        $data['periode'] = $this->input->get('periodepelaporan');

        $data['logbookclear'] = $this->Admin_model->filterlogbookclear($data['periode']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin');
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/logbookselesai', $data);
        $this->load->view('templates/footer');
        helper_log("access", "melakukan pencarian pada menu Logbook Yang Sudah Divalidasi");
    }

    // Mengakses menu Konsol
    public function console()
    {
        $data['user'] = $this->Admin_model->getLoggedUser($this->session->userdata('nip'));
        $data['title'] = "Admin Console";
        $data['log_data'] = $this->Admin_model->getLogData();
        $data['notifikasi'] = $this->Admin_model->getPengumuman();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin');
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/logpage', $data);
        $this->load->view('templates/footer');
    }

    // Menambah Pengumuman baru
    public function tambahpengumuman()
    {
        $this->Admin_model->insertPengumuman();
        helper_log("add", "menambah pengumuman");
        $this->session->set_flashdata('pengumuman', 'ditambahkan');
        redirect('admin/console');
    }

    // Menghapus pengumuman
    public function hapuspengumuman($id)
    {
        $this->Admin_model->deletePengumuman($id);
        helper_log("delete", "menghapus pengumuman");
        $this->session->set_flashdata('pengumuman', 'dihapus');
        redirect('admin/console');
    }

    public function getDetailLogbookDisetujui()
    {
        $nama = $this->input->get('nama');
        $periode = $this->input->get('periode');

        $get_logbook = $this->Admin_model->detaillogbookdatadisetujui($nama, $periode);
        echo json_encode($get_logbook);
        exit();
    }

    public function getDetailLogbookBelumDisetujui()
    {
        $namabelum = $this->input->get('namabelum');
        $periodebelum = $this->input->get('periodebelum');

        $get_logbookbelum = $this->Admin_model->detaillogbookdatabelumdisetujui($namabelum, $periodebelum);
        echo json_encode($get_logbookbelum);
        exit();
    }

    public function ketepatanwaktu()
    {
        $data['user'] = $this->Admin_model->getLoggedUser($this->session->userdata('nip'));
        $data['title'] = "Test Ketepatan Waktu";

        $data['logbookall'] = $this->Admin_model->getAllLogbook();

        foreach ($data['logbookall'] as $logbook) {
            $wakturekam = $logbook['wakturekam'];
            $tgl_approve = $logbook['tgl_approve'];

            $selisih = selisihwkatu($wakturekam, $tgl_approve);
            return $selisih;
        }

        var_dump($wakturekam);
        die;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_admin');
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/ketepatanwaktu', $data);
        $this->load->view('templates/footer');
    }
}
