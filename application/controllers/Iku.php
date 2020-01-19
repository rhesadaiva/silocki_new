<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Iku extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Indikator_model');
        $this->load->model('Global_model');
    }

    // Halaman Index
    public function index()
    {
        $data['title'] = 'Browse Indikator Kinerja Utama';
        $data['user'] = $this->Global_model->getLoggedUser($this->session->userdata('nip'));
        $data['userList'] = $this->Global_model->getUserList();
        $data['refAspekTarget'] = $this->Indikator_model->getAspekTarget();
        $data['refPeriodePelaporan'] = $this->Indikator_model->getPeriodePelaporan();
        $data['refKonsolidasiPeriode'] = $this->Indikator_model->getKonsolidasiPeriode();
        $data['refKonversi120'] = $this->Indikator_model->getKonversi120();
        $data['refBulanLogbook'] = $this->Indikator_model->getBulan();
        $data['role'] = $this->session->userdata('role_id');

        // Ambil Jumlah Kontrak Kinerja dalam angka
        $data['getKontrak'] = $this->Indikator_model->getKontrakByNIP()->num_rows();

        // Masukkan array multidimensi ke variabel
        $getKontrak = $data['getKontrak'];

        // Jika Role = Admin, ambil semua data Kontrak Kinerja dan IKU
        if ($data['role'] == 1) {
            $data['kontrakKinerjaAdmin'] = $this->Indikator_model->getKontrak();
            $data['listIKU'] = $this->Indikator_model->getIKU();
        } else {
            if ($getKontrak > 1) {
                // Jika jumlah Kontrak Kinerja >= 1, tampilkan dalam bentuk result_array
                $data['kontrakKinerja'] = $this->Indikator_model->getKontrakByNIP()->result_array();
            } else {
                // Jika hanya 1, tampilkan ke dalam row_array()
                $data['kontrakKinerja'] = $this->Indikator_model->getKontrakByNIP()->row_array();
            }
            $data['listIKU'] = $this->Indikator_model->getIKUbyNIP();
        }
        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/main_sidebar');
        $this->load->view('iku/v_iku', $data);
        $this->load->view('templates/main_footer');
    }

    // Menambah IKU Baru
    public function createIKU()
    {
        $newIKU = $this->Indikator_model->newIKU();
        helper_log("add", "menambah IKU baru");
        echo json_encode($newIKU);
    }

    // Menghapus IKU
    public function deleteIKU($idIKU)
    {
        $deleteIKU = $this->Indikator_model->deleteIKU($idIKU);
        helper_log("delete", "menghapus IKU (id-iku = $idIKU)");
        echo json_encode($deleteIKU);
    }

    // Mengambil data IKU berdasarkan ID
    public function getIKUByID()
    {
        $id = $this->input->get('id');
        $getIKU = $this->Indikator_model->getIKUById($id);
        echo json_encode($getIKU);
    }

    // Update data IKU berdasarkan ID
    public function updateIKU()
    {
        $idIKU = $this->input->post('idIKU');
        $editIKU = $this->Indikator_model->editIKU();
        helper_log("edit", "mengubah IKU (id-iku = $idIKU)");
        echo json_encode($editIKU);
    }

    // Fungsi addendum IKU
    public function addendumIKU()
    {
        $idIKU = $this->input->post('idIKU');
        $editIKU = $this->Indikator_model->editIKU();
        helper_log("edit", "addendum IKU (id-iku = $idIKU)");
        echo json_encode($editIKU);
    }
}
