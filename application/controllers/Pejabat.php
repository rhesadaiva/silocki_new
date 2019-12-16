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
    }

    public function index()
    {
        $data['title'] = 'Home';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['jumlahbawahan'] = $this->Pejabat_model->countBawahan();
        $data['kkbelumdiapprove'] = $this->Pejabat_model->countKKBawahanNotApproved();
        $data['ikubelumdiapprove'] = $this->Pejabat_model->countIKUBawahanNotApproved();
        $data['logbookbelumdiapprove'] = $this->Pejabat_model->countLogbookBawahanNotApproved();

        $this->load->view('templates/header', $data);
        cek_sidebar();
        $this->load->view('templates/topbar', $data);
        $this->load->view('pejabat/index');
        $this->load->view('templates/footer');
    }

    //Ambil Data KK Bawahan
    public function kontrakkinerjabawahan()
    {
        $data['title'] = 'Browse Kontrak Kinerja Bawahan';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['role'] = $this->session->userdata('role_id');
        if ($data['role'] == 1) {
            $data['kontrak_kinerja'] = $this->Pejabat_model->getAllKontrak();
        } else {
            $data['kontrak_kinerja'] = $this->Pejabat_model->getKontrakBawahan();
        }

        $this->load->view('templates/header', $data);
        cek_sidebar();
        $this->load->view('templates/topbar', $data);
        $this->load->view('pejabat/kontrakkinerjabawahan', $data);
        $this->load->view('templates/footer');
    }

    //Approve KK
    public function approvekontrak($id)
    {
        $approvekontrak = $this->Pejabat_model->approvekontrak($id);
        helper_log("approval", "memberikan persetujuan Kontrak Kinerja (id-kontrak-kinerja = $id)");
        echo json_encode($approvekontrak);
    }

    //Batal Approve KK
    public function batalapprovekontrak($id)
    {
        $batalapprovekontrak = $this->Pejabat_model->batalapprovekontrak($id);
        helper_log("unapprove", "membatalkan persetujuan Kontrak Kinerja (id-kontrak-kinerja = $id)");
        echo json_encode($batalapprovekontrak);
    }

    //Get Data KK
    public function detailkontrak($id)
    {
        $data['detailkontrak'] = $this->Pejabat_model->getdetailkontrak($id);
        $data['title'] = 'Detail Kontrak Kinerja';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['listiku'] = $this->Pejabat_model->getIKUFromKontrak($id);

        $this->load->view('templates/header', $data);
        cek_sidebar();
        $this->load->view('templates/topbar', $data);
        $this->load->view('pejabat/detailkontrak', $data);
        $this->load->view('templates/footer');
    }

    //Approve IKU
    public function approveiku($idiku)
    {
        $approveiku = $this->Pejabat_model->approveiku($idiku);
        helper_log("approval", "memberikan persetujuan IKU (id-iku = $idiku)");
        echo json_encode($approveiku);
    }

    //Batal Approve IKU
    public function batalapproveiku($idiku)
    {
        $batalapproveiku = $this->Pejabat_model->batalapproveiku($idiku);
        helper_log("unapprove", "membatalkan persetujuan IKU (id-iku = $idiku)");
        echo json_encode($batalapproveiku);
    }

    //Get Logbook Bawahan
    public function logbookbawahan($idiku)
    {
        $idiku = $this->uri->segment(3);
        $data['title'] = 'Detail Indikator Kinerja Utama';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['role'] = $this->session->userdata('role_id');
        $data['indikator'] = $this->Indikator_model->getIKUById($idiku);
        $data['logbookdetail'] = $this->Logbook_model->getsentlogbook($idiku);


        $this->load->view('templates/header', $data);
        cek_sidebar();
        $this->load->view('templates/topbar', $data);
        $this->load->view('pejabat/logbookbawahan', $data);
        $this->load->view('templates/footer');
    }

    //Approve Logbook
    public function approvelogbook($idlogbook)
    {
        $approvelogbook =  $this->Pejabat_model->approvelogbook($idlogbook);
        helper_log("approval", "memberikan persetujuan Logbook bawahan (id-logbook = $idlogbook)");
        echo json_encode($approvelogbook);
    }

    //Batal approve logbook
    public function batalapprovelogbook($idlogbook)
    {
        $batalapprovelogbook = $this->Pejabat_model->batalapprovelogbook($idlogbook);
        helper_log("unapproval", "membatalkan persetujuan Logbook bawahan (id-logbook = $idlogbook)");
        echo json_encode($batalapprovelogbook);
    }
}
