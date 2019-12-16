<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	public function __construct()
	{
		parent::__construct(); {
			is_logged_in();
			$this->load->model('Global_model');
		}
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function ambilPengumuman()
	{
		$pengumuman = $this->Global_model->getPengumuman();
		echo json_encode($pengumuman);
	}

	public function getLastActivity()
	{
		$this->Global_model->getLastActivity($this->session->userdata('nama'));
	}

	public function getAllActivity()
	{
		$allActivity = $this->Global_model->getAllActivity($this->session->userdata('nama'));
		echo json_encode($allActivity);
	}
}
