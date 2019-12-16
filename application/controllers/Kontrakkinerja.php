<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kontrakkinerja extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Kontrak_model');
    }

    public function browsekontrak()
    {

        $data['title'] = 'Browse Kontrak Kinerja';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['role'] = $this->session->userdata('role_id');
        if ($data['role'] == 1) {
            $data['kontrak_kinerja'] = $this->Kontrak_model->getKontrak();
        } else {
            $data['kontrak_kinerja'] = $this->Kontrak_model->getKontrakByNIP();
        }

        $this->load->view('templates/header', $data);
        cek_sidebar();
        $this->load->view('templates/topbar', $data);
        $this->load->view('kontrakkinerja/browsekontrak', $data);
        $this->load->view('templates/footer');
    }

    public function tambahkontrak()
    {
        $data['title'] = 'Rekam Kontrak Kinerja Baru';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['nip'] = $this->Kontrak_model->user();

        $this->form_validation->set_rules('kontrakkinerjake', 'Seri Kontrak Kinerja', 'required');
        $this->form_validation->set_rules('nomorkontrakkinerja', 'Nomor Kontrak Kinerja', 'required');
        $this->form_validation->set_rules('tanggalmulai', 'Tanggal Mulai', 'required');
        $this->form_validation->set_rules('tanggalselesai', 'Tanggal Selesai', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            cek_sidebar();
            $this->load->view('templates/topbar', $data);
            $this->load->view('kontrakkinerja/tambahkontrak', $data);
            $this->load->view('templates/footer');
        } else {

            $this->Kontrak_model->tambahkontrakbaru();
            $this->session->set_flashdata('flash', 'Ditambahkan');
            helper_log("add", "menambah Kontrak Kinerja baru");
            redirect('kontrakkinerja/browsekontrak');
        }
    }
    public function hapuskontrak($id)
    {
        $hapuskontrak = $this->Kontrak_model->hapuskontrak($id);
        // $this->session->set_flashdata('flash', 'Dihapus');
        helper_log("delete", "menghapus Kontrak Kinerja (id-kontrak = $id)");
        echo json_encode($hapuskontrak);
    }

    public function editkontrak($id)
    {
        $data['title'] = 'Ubah Data Kontrak Kinerja';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['nip'] = $this->Kontrak_model->user();
        $data['kontrak'] = $this->Kontrak_model->getKontrakbyID($id);
        $data['serikontrak'] = ['Pertama', 'Kedua', 'Ketiga', 'Keempat'];

        $this->form_validation->set_rules('kontrakkinerjake', 'Seri Kontrak Kinerja', 'required');
        $this->form_validation->set_rules('nomorkontrakkinerja', 'Nomor Kontrak Kinerja', 'required');
        $this->form_validation->set_rules('tanggalmulai', 'Tanggal Mulai', 'required');
        $this->form_validation->set_rules('tanggalselesai', 'Tanggal Selesai', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            cek_sidebar();
            $this->load->view('templates/topbar', $data);
            $this->load->view('kontrakkinerja/editkontrak', $data);
            $this->load->view('templates/footer');
        } else {

            $this->Kontrak_model->editkontrak($id);
            $this->session->set_flashdata('flash', 'Diubah');
            helper_log("edit", "mengubah Kontrak Kinerja (id-kontrak = $id)");
            redirect('kontrakkinerja/browsekontrak');
        }
    }
}
