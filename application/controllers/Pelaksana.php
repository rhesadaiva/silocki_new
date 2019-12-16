<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelaksana extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        cek_pelaksana();
        $this->load->model('Pelaksana_model');
    }


    public function index()
    {
        $data['title'] = 'Home';
        $data['user'] = $this->db->get_where('user', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['kkdisetujui'] = $this->Pelaksana_model->countKKApproved();
        $data['ikudisetujui'] = $this->Pelaksana_model->countIKUApproved();
        $data['logbookdikirim'] = $this->Pelaksana_model->countLogbookSent();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar_user');
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }
}
