<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Pejabat extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        cek_atasan();
        $this->load->model('Pejabat_model');
        $this->load->model('Indikator_model');
        $this->load->model('Logbook_model');
        $this->load->model('Global_model');
    }

    public function index()
    {
        $data['title'] = 'Home';
        $data['user'] = $this->Global_model->getLoggedUser($this->session->userdata('nip'));
        $data['recentActivity'] = $this->Global_model->getLastActivity($this->session->userdata('nama'));
        $data['jumlahbawahan'] = $this->Pejabat_model->countBawahan();
        $data['kkbelumdiapprove'] = $this->Pejabat_model->countKKBawahanNotApproved();
        $data['ikubelumdiapprove'] = $this->Pejabat_model->countIKUBawahanNotApproved();
        $data['logbookbelumdiapprove'] = $this->Pejabat_model->countLogbookBawahanNotApproved();

        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/main_sidebar');
        $this->load->view('pejabat/index');
        $this->load->view('templates/main_footer');
    }

    //Ambil Data KK Bawahan
    public function approvalAtasan()
    {
        $data['title'] = 'Pengelolaan Kinerja Bawahan';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['role'] = $this->session->userdata('role_id');
        if ($data['role'] == 1) {
            $data['kontrakKinerjaBawahan'] = $this->Pejabat_model->getAllKontrak();
        } else {
            $data['kontrakKinerjaBawahan'] = $this->Pejabat_model->getKontrakBawahan();
        }
        $this->load->view('templates/main_header', $data);
        $this->load->view('templates/main_sidebar');
        $this->load->view('pejabat/v_approval', $data);
        $this->load->view('templates/main_footer');
    }

    // Approve KK
    public function approveKontrakKinerja()
    {
        $idKontrak = $this->input->post('idKontrak');
        $approveKontrak = $this->Pejabat_model->approveKontrak($idKontrak);
        helper_log("approval", "memberikan persetujuan Kontrak Kinerja (id-kontrak-kinerja = $idKontrak)");
        echo json_encode($approveKontrak);
    }

    // Batal Approve KK
    public function rejectKontrakKinerja()
    {
        $idKontrak = $this->input->post('idKontrak');
        $rejectKontrak = $this->Pejabat_model->rejectKontrak($idKontrak);
        helper_log("reject", "membatalkan persetujuan Kontrak Kinerja (id-kontrak-kinerja = $idKontrak)");
        echo json_encode($rejectKontrak);
    }

    // Get Data Kontrak Kinerja
    public function getDetailKontrak()
    {
        $idKontrak = $this->input->get('id');
        $detailKontrak = $this->Pejabat_model->getDetailKontrak($idKontrak);
        echo json_encode($detailKontrak);
    }

    // Get Data IKU berdasarkan Kontrak Kinerja
    public function getIKUFromKontrakKinerja()
    {
        $idKontrak = $this->input->get('kk');
        $listIKU = $this->Pejabat_model->getListIKUFromKontrak($idKontrak);
        echo json_encode($listIKU);
    }

    //Approve IKU
    public function approveIKU()
    {
        $idIKU = $this->input->post('iku');
        $approveiku = $this->Pejabat_model->approvalIKU($idIKU);
        helper_log("approval", "memberikan persetujuan IKU (id-iku = $idIKU)");
        echo json_encode($approveiku);
    }

    //Batal Approve IKU
    public function rejectIKU()
    {
        $idIKU = $this->input->post('iku');
        $rejectIKU = $this->Pejabat_model->rejectIKU($idIKU);
        helper_log("reject", "membatalkan persetujuan IKU (id-iku = $idIKU)");
        echo json_encode($rejectIKU);
    }

    // Ambil data Logbbok yang sudah dikirim bawahan
    public function getSentLogbook()
    {
        $idIKU = $this->input->get('id-iku');
        $getLogbook = $this->Pejabat_model->getLogbookIsSent($idIKU);
        echo json_encode($getLogbook);
    }

    //Approve Logbook
    public function approveLogbook()
    {
        $idLogbook = $this->input->post('idlb');
        $approvalLogbook =  $this->Pejabat_model->approvalLogbook($idLogbook);
        helper_log("approval", "memberikan persetujuan Logbook bawahan (id-logbook = $idLogbook)");
        echo json_encode($approvalLogbook);
    }

    //Batal approve logbook
    public function rejectLogbook()
    {
        $idLogbook = $this->input->post('idlb');
        $rejectLogbook = $this->Pejabat_model->rejectLogbook($idLogbook);
        helper_log("reject", "membatalkan persetujuan Logbook bawahan (id-logbook = $idLogbook)");
        echo json_encode($rejectLogbook);
    }
}
