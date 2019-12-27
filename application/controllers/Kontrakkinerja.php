<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kontrakkinerja extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Kontrak_model');
        $this->load->model('Global_model');
    }

    public function index()
    {
        $data['title'] = 'Browse Kontrak Kinerja';
        $data['user'] = $this->Global_model->getLoggedUser($this->session->userdata('nip'));
        $data['role'] = $this->session->userdata('role_id');
        if ($data['role'] == 1) {
            $data['kontrakKinerja'] = $this->Kontrak_model->getKontrak();
        } else {
            $data['kontrakKinerja'] = $this->Kontrak_model->getKontrakByNIP();
        }
        $data['userList'] = $this->Global_model->getUserList();
        $data['seriKontrak'] = ['Pertama', 'Kedua', 'Ketiga', 'Keempat'];

        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/main_sidebar');
        $this->load->view('kontrak-kinerja/v_kontrakKinerja', $data);
        $this->load->view('templates/main_footer');
    }

    // Menambah Kontrak Kinerja
    public function addKontrakKinerja()
    {
        $addKontrak = $this->Kontrak_model->addNewKontrakKinerja();
        echo json_encode($addKontrak);
        helper_log("add", "menambah Kontrak Kinerja baru");
    }

    // Menghapus Kontrak Kinerja
    public function deleteKontrak($id)
    {
        $deleteKontrak = $this->Kontrak_model->deleteKontrak($id);
        helper_log("delete", "menghapus Kontrak Kinerja (id-kontrak = $id)");
        echo json_encode($deleteKontrak);
    }

    // Ambil data Kontrak Kinerja berdasarkan ID
    public function getKontrakByID()
    {
        $id = $this->input->get('id');
        $getKontrak = $this->Kontrak_model->getKontrakbyID($id);
        echo json_encode($getKontrak);
    }

    // Update Kontrak Kinerja
    public function updateKontrak()
    {
        $idKontrak =  $this->input->post('id_kontrak');
        $updateKontrak = $this->Kontrak_model->editKontrak();
        helper_log("edit", "mengubah Kontrak Kinerja (id-kontrak = $idKontrak)");
        echo json_encode($updateKontrak);
    }
}
