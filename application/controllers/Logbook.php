<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Logbook extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Indikator_model');
        $this->load->model('Logbook_model');
    }

    // Ambil semua data Logbook berdasarkan ID IKU
    public function getLogbook()
    {
        $idIKU = $this->input->get('id-iku');
        $Logbook = $this->Logbook_model->getLogbook($idIKU);
        echo json_encode($Logbook);
    }

    // Membuat Logbook baru
    public function createLogbook()
    {
        $newLogbook = $this->Logbook_model->newLogbook();
        helper_log("add", "menambah Logbook baru");
        echo json_encode($newLogbook);
    }

    // Hapus Logbook
    public function deleteLogbook($idLogbook)
    {
        $hapusLogbook = $this->Logbook_model->deleteLogbook($idLogbook);
        helper_log("delete", "menghapus Logbook (id-logbook = $idLogbook)");
        echo json_encode($hapusLogbook);
    }

    // Ambil data Logbook berdasarkan ID Logbook
    public function getLogbookByID()
    {
        $idLogbook = $this->input->get('id');
        $getLogbook = $this->Logbook_model->getLogbookByID($idLogbook);
        echo json_encode($getLogbook);
    }

    // Update data Logbook
    public function updateLogbook()
    {
        $idLogbook = $this->input->post("idLogbook");
        $editLogbook = $this->Logbook_model->editLogbook();
        helper_log("edit", "mengubah Logbook (id-logbook = $idLogbook)");
        echo json_encode($editLogbook);
    }


    public function kirimLogbookKeAtasan()
    {
        $idLogbook = $this->input->post('idLogbook');
        $kirimlogbook = $this->Logbook_model->kirimLogbook();
        helper_log("send", "mengirim Logbook ke atasan (id-logbook = $idLogbook)");
        echo json_encode($kirimlogbook);
    }

    public function cetakLogbook($idlogbook)
    {
        $idlogbook = $this->uri->segment(3);

        $data['cetaklogbook'] = $this->Logbook_model->printLogbook($idlogbook);

        $this->load->library('pdf');

        $foliopaper = array(0, 0, 612.00, 936.00);
        $this->pdf->setPaper($foliopaper, 'landscape');
        $this->pdf->filename = "logbook" . $idlogbook;
        $this->pdf->load_view('templates/cetak-logbook', $data);
    }
}
