<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Indikator_model extends CI_Model
{

    //Ambil data User
    public function user()
    {
        $query = $this->db->get('user');
        return $query->result_array();
    }

    //Ambil IKU berdasarkan id
    public function getIKUById($idiku)
    {
        return $this->db->get_where('indikatorkinerjautama', ['id_iku' => $idiku])->row_array();
    }

    //Ambil data kontrak khusus admin
    public function getKontrak()
    {
        $query = $this->db->query('SELECT `kontrakkinerja`.*, `user`.nama from `kontrakkinerja`join `user` using(nip)');
        return $query->result_array();
    }

    //Ambil data kontrak berdasarkan NIP login
    public function getKontrakByNIP()
    {
        $role = $this->session->userdata('nip');
        $query = $this->db->query("SELECT * from `kontrakkinerja` where nip= $role ");
        return $query->row_array();
    }

    //Ambil data IKU khusus ADMIN
    public function getIKU()
    {
        $query = $this->db->query("SELECT `kontrakkinerja`.id_kontrak, `kontrakkinerja`.nomorkk,`indikatorkinerjautama`.*,`user`.nama, `user`.nip from `indikatorkinerjautama`join `user` using(nip) join `kontrakkinerja` using (id_kontrak)");
        return $query->result_array();
    }

    //Ambil data IKU berdasarkan NIP login
    public function getIKUByNIP()
    {
        $role = $this->session->userdata('nip');
        return $this->db->query("SELECT `kontrakkinerja`.id_kontrak, `kontrakkinerja`.nomorkk, `indikatorkinerjautama`.*
                                FROM `kontrakkinerja` JOIN `indikatorkinerjautama` using (id_kontrak) where `indikatorkinerjautama`.nip = $role")->result_array();
    }

    //Tambah IKU Baru
    public function rekamikubaru()
    {
        $role = $this->session->userdata('nip');
        $login = $this->session->userdata('nama');
        // Ambil Nama Atasan
        $queryAtasan = $this->db->query("SELECT `user`.nip, `user`.atasan FROM `user` where `user`.nip = '$role' ")->row_array();
        $namaAtasan = $queryAtasan['atasan'];
        // Ambil ID Telegram Atasan dari nama
        $telegramAtasan = $this->db->query("SELECT `user`.nama, `user`.telegram FROM `user` where `user`.nama = '$namaAtasan'")->row_array();


        //Ambil data dari form
        $data = [
            'nip' => $this->input->post('nomorpegawai', true),
            'id_iku' => uniqid(),
            'id_kontrak' => $this->input->post('nomorkk', true),
            'kodeiku' => $this->input->post('kodeiku', true),
            'namaiku' => $this->input->post('namaiku', true),
            'formulaiku' => $this->input->post('formulaiku', true),
            'targetiku' => $this->input->post('targetiku', true),
            'nilaitertinggi' => $this->input->post('nilaitertinggi', true),
            'aspektarget' => $this->input->post('aspektarget', true),
            'penanggungjawab' => $this->input->post('penanggungjawab', true),
            'penyediadata' => $this->input->post('penyediadata', true),
            'sumberdata' => $this->input->post('sumberdata', true),
            'satuanpengukuran' => $this->input->post('satuanpengukuran', true),
            'konsolidasiperiodeiku' => $this->input->post('konsolidasiperiode', true),
            'periodepelaporan' => $this->input->post('periodepelaporan', true),
            'konversi120' => $this->input->post('konversi', true),
            'tahun_iku' => date("Y"),
        ];

        $this->db->insert('indikatorkinerjautama', $data);

        // Send notif ke Telegram atasan
        $this->_telegram(
            $telegramAtasan['telegram'],
            "Halo, *" . $telegramAtasan['nama'] . "*. \n\nBawahan anda: *" . $login . "* telah mengajukan IKU dengan data sebagai berikut: \n\n*Kode IKU*: " . $this->input->post('kodeiku') . "\n*Nama IKU*: " . $this->input->post('namaiku') . "\n\nMohon diperiksa dan diberikan persetujuan apabila data sudah benar, terima kasih."
        );
    }

    //Hapus IKU
    public function hapusiku($idiku)
    {
        $this->db->where('id_iku', $idiku);
        $this->db->delete('indikatorkinerjautama');
    }

    //Edit IKU
    public function ubahdataIKU()
    {
        $data = [
            'kodeiku' => $this->input->post('kodeiku', true),
            'namaiku' => $this->input->post('namaiku', true),
            'formulaiku' => $this->input->post('formulaiku', true),
            'targetiku' => $this->input->post('targetiku', true),
            'nilaitertinggi' => $this->input->post('nilaitertinggi', true),
            'satuanpengukuran' => $this->input->post('satuanpengukuran', true),
            'konsolidasiperiodeiku' => $this->input->post('konsolidasiperiode', true),
            'konversi120' => $this->input->post('konversi', true),
        ];

        $this->db->where('id_iku', $this->input->post('id_iku'));
        $this->db->update('indikatorkinerjautama', $data);
    }

    public function adendumIKU()
    {
        $data = [
            'kodeiku' => $this->input->post('kodeiku', true),
            'namaiku' => $this->input->post('namaiku', true),
            'formulaiku' => $this->input->post('formulaiku', true),
            'targetiku' => $this->input->post('targetiku', true),
            'nilaitertinggi' => $this->input->post('nilaitertinggi', true),
            'satuanpengukuran' => $this->input->post('satuanpengukuran', true),
            'konsolidasiperiodeiku' => $this->input->post('konsolidasiperiode', true),
            'konversi120' => $this->input->post('konversi', true),
        ];

        $this->db->where('id_iku', $this->input->post('id_iku'));
        $this->db->update('indikatorkinerjautama', $data);
    }

    // Enabler Telegram
    private function _telegram($telegram, $message)
    {
        $url = "https://api.telegram.org/bot905076968:AAG8sNGqlABcYAw6PuUL6eSuFn1-pmSGUpU/sendMessage?parse_mode=markdown&chat_id=" . $telegram;
        $url = $url . "&text=" . urlencode($message);

        $ch = curl_init();
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $optArray);
        $result = curl_exec($ch);
        curl_close($ch);
    }
}
