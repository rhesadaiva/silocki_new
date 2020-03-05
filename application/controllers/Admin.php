<?php

use phpDocumentor\Reflection\Types\This;

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
        $this->load->view('admin/v_manajemenUser', $data);
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

    // LOGBOOK BELUM DISETUJUI
    public function unapprovedLogbook()
    {
        $data['title'] = 'Logbook Yang Belum Disetujui';
        $data['user'] = $this->Global_model->getLoggedUser($this->session->userdata('nip'));
        $data['refBulan'] = $this->Global_model->getBulanRef();

        // Query data yang belum divalidasi
        $data['notValidated'] = $this->Admin_model->notValidatedLogbook();

        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/main_sidebar');
        $this->load->view('admin/v_unapproved', $data);
        $this->load->view('templates/main_footer');
    }

    // FILTER LOGBOOK BELUM DISETUJUI
    public function filteredUnapproved()
    {
        $data['title'] = 'Logbook Yang Belum Disetujui';
        $data['user'] = $this->Global_model->getLoggedUser($this->session->userdata('nip'));
        $data['refBulan'] = $this->Global_model->getBulanRef();

        // Ambil filter bulan
        $periode = $this->input->get('m');

        // Query data yang belum divalidasi
        $data['notValidated'] = $this->Admin_model->filterNotValidatedLogbook($periode);

        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/main_sidebar');
        $this->load->view('admin/v_unapproved', $data);
        $this->load->view('templates/main_footer');
        helper_log("access", "melakukan pencarian pada menu Logbook Yang Belum Disetujui");
    }

    // LOGBOOK TELAH DISETUJUI
    public function approvedLogbook()
    {
        $data['title'] = 'Logbook Yang Sudah Divalidasi';
        $data['user'] = $this->Global_model->getLoggedUser($this->session->userdata('nip'));
        $data['refBulan'] = $this->Global_model->getBulanRef();

        // Query data yang telah divalidasi
        $data['validatedLogbook'] = $this->Admin_model->validatedLogbook();

        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/main_sidebar');
        $this->load->view('admin/v_approved', $data);
        $this->load->view('templates/main_footer');
    }

    // FILTER LOGBOOK YANG TELAH DISETUJUI
    public function filteredApproved()
    {
        $data['title'] = 'Logbook Yang Sudah Divalidasi';
        $data['user'] = $this->Global_model->getLoggedUser($this->session->userdata('nip'));
        $data['refBulan'] = $this->Global_model->getBulanRef();

        // Ambil filter bulan
        $periode = $this->input->get('m');

        // Query data yang telah divalidasi
        $data['validatedLogbook'] = $this->Admin_model->filterlogbookclear($periode);

        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/main_sidebar');
        $this->load->view('admin/v_approved', $data);
        $this->load->view('templates/main_footer');
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

    // GET DETAIL LOGBOOK YANG TELAH DISETUJUI
    public function getDetailLogbookApproved()
    {
        $nama = $this->input->get('nama');
        $periode = $this->input->get('periode');

        $get_logbook = $this->Admin_model->detailLogbookApproved($nama, $periode);
        echo json_encode($get_logbook);
        exit();
    }

    // GET DETAIL LOGBOOK YANG BELUM DISETUJUI
    public function getDetailLogbookUnapproved()
    {
        $nama = $this->input->get('nama');
        $periode = $this->input->get('periode');

        $getLogbookUnapproved = $this->Admin_model->detailLogbookUnapproved($nama, $periode);
        echo json_encode($getLogbookUnapproved);
    }

    // MENU LOG AKTIFITAS
    public function logActivity()
    {
        $data['title'] = 'Log Aktivitas';
        $data['user'] = $this->Global_model->getLoggedUser($this->session->userdata('nip'));

        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/main_sidebar');
        $this->load->view('admin/v_logActivity', $data);
        $this->load->view('templates/main_footer');
    }

    // GET LOG ACTIVITY
    public function getLogActivity()
    {
        $this->load->library("datatables");
        $this->datatables->select("*");
        $this->datatables->from("tabel_log");
        return print_r($this->datatables->generate());
    }

    // MENU KONFIGURASI
    public function configMenu()
    {
        $data['title'] = 'Konfigurasi Aplikasi';
        $data['user'] = $this->Global_model->getLoggedUser($this->session->userdata('nip'));
        $data['config'] = $this->Global_model->getConfigData();

        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/main_sidebar');
        $this->load->view('admin/v_config', $data);
        $this->load->view('templates/main_footer');
    }

    // AMBIL DETAIL KONFIGURASI
    public function getConfigDetail()
    {
        $idConfig = $this->input->get('id');
        $configData = $this->Global_model->getConfigByID($idConfig);
        echo json_encode($configData);
    }

    public function updateConfigDetail()
    {
        $updateConfig = $this->Global_model->editConfig();
        echo json_encode($updateConfig);
    }
}
