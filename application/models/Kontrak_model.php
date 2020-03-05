<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kontrak_model extends CI_Model
{

    //Ambil data user
    public function getUserList()
    {
        $this->db->select('nama,nip');
        $this->db->from('user');

        $query = $this->db->get();
        return $query->result_array();
    }

    //Ambil semua KK (khusus admin)
    public function getKontrak()
    {
        $this->db->select('kontrakkinerja.*, user.nama, ref_validasiKK.*');
        $this->db->from('kontrakkinerja');
        $this->db->join('user', 'user.nip = kontrakkinerja.nip');
        $this->db->join('ref_validasiKK', 'kontrakkinerja.is_validated = ref_validasiKK.validasi_id');
        $this->db->order_by('kontrakkinerja.is_validated', "ASC");

        $query = $this->db->get();
        return $query;
    }

    // Ambil kontrak berdasarkan tahun (apabila Konfigurasi Tahun berjalan  diaktifkan)
    public function getKontrakByYear($activeYear)
    {
        $this->db->select('kontrakkinerja.*, user.nama, ref_validasiKK.*');
        $this->db->from('kontrakkinerja');
        $this->db->join('user', 'user.nip = kontrakkinerja.nip');
        $this->db->join('ref_validasiKK', 'kontrakkinerja.is_validated = ref_validasiKK.validasi_id');
        $this->db->where('kontrakkinerja.tahun_kontrak', $activeYear);
        $this->db->order_by('kontrakkinerja.is_validated', "ASC");

        $query = $this->db->get();
        return $query;
    }

    //Ambil KK berdasarkan NIP login
    public function getKontrakByNIP()
    {
        $nip = $this->session->userdata('nip');

        $this->db->select('kontrakkinerja.*, user.nama, ref_validasiKK.*');
        $this->db->from('kontrakkinerja');
        $this->db->join('user', 'user.nip = kontrakkinerja.nip');
        $this->db->join('ref_validasiKK', 'kontrakkinerja.is_validated = ref_validasiKK.validasi_id');
        $this->db->where('kontrakkinerja.nip', $nip);

        $query = $this->db->get();
        return $query;
    }

    // Ambil KK berdasarkan NIP Login dan tahun berjalan (jika setting tahun berjalan diaktifkan)
    public function getKontrakByNIPYear($activeYear)
    {
        $nip = $this->session->userdata('nip');

        $this->db->select('kontrakkinerja.*, user.nama, ref_validasiKK.*');
        $this->db->from('kontrakkinerja');
        $this->db->join('user', 'user.nip = kontrakkinerja.nip');
        $this->db->join('ref_validasiKK', 'kontrakkinerja.is_validated = ref_validasiKK.validasi_id');
        $this->db->where('kontrakkinerja.nip', $nip);
        $this->db->where('kontrakkinerja.tahun_kontrak', $activeYear);

        $query = $this->db->get();
        return $query;
    }


    //ambil KK berdasarkan ID
    public function getKontrakbyID($id)
    {
        return $this->db->get_where('kontrakkinerja', ['id_kontrak' => $id])->row_array();
    }

    //Tambah KK Baru
    public function addNewKontrakKinerja()
    {
        $role = $this->session->userdata('nip');
        $login = $this->session->userdata('nama');

        // Ambil Nama Atasan
        $this->db->select('user.nip, user.pejabat_id, pejabat.nama_pejabat, pejabat.pejabat_id');
        $this->db->from('user');
        $this->db->join('pejabat', 'user.pejabat_id = pejabat.pejabat_id');
        $this->db->where('user.nip', $role);

        $query = $this->db->get();
        $queryAtasan = $query->row_array();

        $namaAtasan = $queryAtasan['nama_pejabat'];

        // Ambil ID Telegram Atasan dari nama
        $telegramAtasan = $this->db->get_where('user', ['nama' => $namaAtasan])->row_array();

        $data = [
            'id_kontrak' => uniqid(),
            'nip' => $this->input->post('setPegawai', true),
            'kontrakkinerjake' => $this->input->post('seriKontrakKinerja', true),
            'nomorkk' => $this->input->post('nomorKontrakKinerja', true),
            'tanggalmulai' => $this->input->post('tanggalAwalKontrak', true),
            'tanggalselesai' => $this->input->post('tanggalAkhirKontrak', true),
            'is_validated' => 1,
            'tahun_kontrak' => date("Y")
        ];

        // Insert kontrak kinerja
        $this->db->insert('kontrakkinerja', $data);

        // Ambil nomor KK untuk diinput ke telegram
        $nomorKontrak = $data['nomorkk'];

        // Send Notif ke Telegram
        $this->_telegram(
            $telegramAtasan['telegram'],
            "Halo, *" . $telegramAtasan['nama'] . "*. \n\nBawahan anda: *" . $login . "* telah mengajukan Kontrak Kinerja dengan data sebagai berikut: \n\n*Nomor Kontrak Kinerja*: " . $nomorKontrak . "\n\nMohon diperiksa dan diberikan persetujuan apabila data sudah benar, terima kasih."
        );
    }

    //Hapus KK 
    public function deleteKontrak($id)
    {
        $this->db->where('id_kontrak', $id);
        $this->db->delete('kontrakkinerja');
    }

    //Edit KK
    public function editKontrak()
    {
        $idKontrak =  $this->input->post('idKontrak');
        $data = [
            'kontrakkinerjake' => $this->input->post('SeriKontrakKinerja'),
            'nomorkk' => $this->input->post('NomorKontrakKinerja'),
            'tanggalmulai' => $this->input->post('TanggalAwalKontrak'),
            'tanggalselesai' => $this->input->post('TanggalAkhirKontrak'),
            'is_validated' => 1,
        ];

        $this->db->where('id_kontrak', $idKontrak);
        $this->db->update('kontrakkinerja', $data);
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
