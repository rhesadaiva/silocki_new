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
        $data['user'] = $this->Global_model->getLoggedUser($this->session->userdata('nip'));
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
        $data['user'] = $this->Global_model->getLoggedUser($this->session->userdata('nip'));
        $data['user_data'] = $this->Admin_model->getAllUsers();
        $data['pangkat'] = $this->Admin_model->getPangkat();
        $data['role'] = $this->Admin_model->getRole();
        $data['seksi'] = $this->Admin_model->getSeksi();
        $data['pejabat'] = $this->Admin_model->getAllPejabat();
        $data['tlinks'] = "https://api.telegram.org/bot905076968:AAG8sNGqlABcYAw6PuUL6eSuFn1-pmSGUpU/getUpdates";

        helper_log("access", "Mengakses menu Manajemen User");

        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/main_sidebar');
        $this->load->view('admin/v_manajemenUser');
        $this->load->view('templates/main_footer');
    }

    //FUNGSI TAMBAH PEGAWAI
    public function addPegawai()
    {
        $newPegawai = $this->Admin_model->tambahUser();
        helper_log("add", "Menambah data pegawai baru");
        echo json_encode($newPegawai);
    }

    //AMBIL USER BY ID
    public function getUserByID()
    {
        $idPegawai = $this->input->get('u');
        $getPegawaiByID = $this->Admin_model->getUserByID($idPegawai);
        echo json_encode($getPegawaiByID);
    }

    //UPDATE USER
    public function updatePegawai()
    {
        $u = $this->input->post('u');
        $editPegawai = $this->Admin_model->updateUser($u);
        echo json_encode($editPegawai);
        helper_log("edit", "Mengubah data pegawai (id-pegawai = $u)");
    }

    //HAPUS USER
    public function deletePegawai($idPegawai)
    {
        $deletePegawai = $this->Admin_model->deleteUser($idPegawai);
        echo json_encode($deletePegawai);
        helper_log("delete", "Menghapus data pegawai (id-pegawai = $idPegawai)");
    }

    //HALAMAN LOGBOOK BELUM DISETUJUI
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
