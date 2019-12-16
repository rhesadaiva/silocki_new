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
        $this->load->model('Logbook_model');
    }

    public function browseiku()
    {
        $data['title'] = 'Browse Indikator Kinerja Utama';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['role'] = $this->session->userdata('role_id');
        if ($data['role'] == 1) {
            $data['indikator'] = $this->Indikator_model->getIKU();
        } else {
            $data['indikator'] = $this->Indikator_model->getIKUbyNIP();
        }

        $this->load->view('templates/header', $data);
        cek_sidebar();
        $this->load->view('templates/topbar', $data);
        $this->load->view('iku/browseiku', $data);
        $this->load->view('templates/footer');
    }

    public function rekamiku()
    {
        $data['title'] = 'Tambah IKU Baru';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['nip'] = $this->Indikator_model->user();
        $data['role'] = $this->session->userdata('role_id');

        if ($data['role'] == 1) {
            $data['kontrak_kinerja'] = $this->Indikator_model->getKontrak();
        } else {
            $data['kontrak_kinerja'] = $this->Indikator_model->getKontrakByNIP();
        }

        $this->form_validation->set_rules('kodeiku', 'Kode IKU', 'required');
        $this->form_validation->set_rules('namaiku', 'Nomor Kontrak Kinerja', 'required');
        $this->form_validation->set_rules('formulaiku', 'Formula IKU', 'required');
        $this->form_validation->set_rules('targetiku', 'Target IKU', 'required');
        $this->form_validation->set_rules('nilaitertinggi', 'Nilai Tertinggi', 'required');
        $this->form_validation->set_rules('aspektarget', 'Aspek Target', 'required');
        $this->form_validation->set_rules('penanggungjawab', 'Penanggung Jawab', 'required');
        $this->form_validation->set_rules('penyediadata', 'Penyedia Data', 'required');
        $this->form_validation->set_rules('sumberdata', 'Sumber Data', 'required');
        $this->form_validation->set_rules('satuanpengukuran', 'Satuan Pengukuran', 'required');
        $this->form_validation->set_rules('konsolidasiperiode', 'Konsolidasi Periode', 'required');
        $this->form_validation->set_rules('periodepelaporan', 'Periode Pelaporan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            cek_sidebar();
            $this->load->view('templates/topbar', $data);
            $this->load->view('iku/tambahiku', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Indikator_model->rekamikubaru();
            $this->session->set_flashdata('flash', 'Ditambahkan');
            helper_log("add", "menambah IKU baru");
            redirect('iku/browseiku');
        }
    }

    public function hapusiku($idiku)
    {
        $hapusiku = $this->Indikator_model->hapusiku($idiku);
        helper_log("delete", "menghapus IKU (id-iku = $idiku)");
        // $this->session->set_flashdata('flash', 'Dihapus');
        // redirect('iku/browseiku');
        echo json_encode($hapusiku);
    }

    public function editiku($idiku)
    {
        $data['title'] = 'Edit IKU';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['nip'] = $this->Indikator_model->user();
        $data['role'] = $this->session->userdata('role_id');
        $data['iku'] = $this->Indikator_model->getIKUById($idiku);
        $data['satuanpengukuran'] = ['Persentase', 'Indeks', 'Satuan', 'Waktu'];
        $data['konsolidasiperiode'] = ['Sum', 'Average', 'Take Last Known'];
        $data['aspektarget'] = ['Kuantitas', 'Kualitas', 'Waktu', 'Biaya'];
        $data['periodepelaporan'] = ['Bulanan', 'Triwulanan', 'Semesteran', 'Tahunan'];
        $data['konversi'] = ['Ya', 'Tidak'];

        $this->form_validation->set_rules('kodeiku', 'Kode IKU', 'required');
        $this->form_validation->set_rules('namaiku', 'Nomor Kontrak Kinerja', 'required');
        $this->form_validation->set_rules('formulaiku', 'Formula IKU', 'required');
        $this->form_validation->set_rules('targetiku', 'Target IKU', 'required');
        $this->form_validation->set_rules('nilaitertinggi', 'Nilai Tertinggi', 'required');
        $this->form_validation->set_rules('satuanpengukuran', 'Satuan Pengukuran', 'required');
        $this->form_validation->set_rules('konsolidasiperiode', 'Konsolidasi Periode', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            cek_sidebar();
            $this->load->view('templates/topbar', $data);
            $this->load->view('iku/editiku', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Indikator_model->ubahdataIKU($idiku);
            helper_log("edit", "mengubah IKU (id-iku = $idiku)");
            $this->session->set_flashdata('flash', 'Diubah');
            redirect('iku/browseiku');
        }
    }

    public function adendum($idiku)
    {
        $data['title'] = 'Adendum IKU';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['nip'] = $this->Indikator_model->user();
        $data['role'] = $this->session->userdata('role_id');
        $data['iku'] = $this->Indikator_model->getIKUById($idiku);
        $data['satuanpengukuran'] = ['Persentase', 'Indeks', 'Satuan', 'Waktu'];
        $data['konsolidasiperiode'] = ['Sum', 'Average', 'Take Last Known'];
        $data['aspektarget'] = ['Kuantitas', 'Kualitas', 'Waktu', 'Biaya'];
        $data['periodepelaporan'] = ['Bulanan', 'Triwulanan', 'Semesteran', 'Tahunan'];
        $data['konversi'] = ['Ya', 'Tidak'];

        $this->form_validation->set_rules('kodeiku', 'Kode IKU', 'required');
        $this->form_validation->set_rules('namaiku', 'Nomor Kontrak Kinerja', 'required');
        $this->form_validation->set_rules('formulaiku', 'Formula IKU', 'required');
        $this->form_validation->set_rules('targetiku', 'Target IKU', 'required');
        $this->form_validation->set_rules('nilaitertinggi', 'Nilai Tertinggi', 'required');
        $this->form_validation->set_rules('satuanpengukuran', 'Satuan Pengukuran', 'required');
        $this->form_validation->set_rules('konsolidasiperiode', 'Konsolidasi Periode', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            cek_sidebar();
            $this->load->view('templates/topbar', $data);
            $this->load->view('iku/editiku', $data);
            $this->load->view('templates/footer');
        } else {
            $this->_aksiadendum($idiku);
        }
    }

    private function _aksiadendum($idiku)
    {
        $this->Indikator_model->adendumIKU($idiku);
        helper_log("edit", "melakukan adendum IKU (id-iku = $idiku)");
        $this->session->set_flashdata('flash', 'Diadendum');
        redirect('iku/browseiku');
    }
}
