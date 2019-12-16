<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kontrak_model extends CI_Model
{

    //Ambil data user
    public function user()
    {
        $query = $this->db->get('user');
        return $query->result_array();
    }

    //Ambil semua KK (khusus admin)
    public function getKontrak()
    {
        $query = $this->db->query('SELECT `kontrakkinerja`.*, `user`.nama from `kontrakkinerja`join `user` using(nip)');
        return $query->result_array();
    }

    //Ambil KK berdasarkan NIP login
    public function getKontrakByNIP()
    {
        $role = $this->session->userdata('nip');
        $query = $this->db->query("SELECT * from `kontrakkinerja` where nip='$role' ");
        return $query->result_array();
    }

    //ambil KK berdasarkan ID
    public function getKontrakbyID($id)
    {
        return $this->db->get_where('kontrakkinerja', ['id_kontrak' => $id])->row_array();
    }

    //Tambah KK Baru
    public function tambahkontrakbaru()
    {
        $role = $this->session->userdata('nip');
        $login = $this->session->userdata('nama');

        // Ambil Nama Atasan
        $queryAtasan = $this->db->query("SELECT `user`.nip, `user`.atasan FROM `user` where `user`.nip = '$role' ")->row_array();
        $namaAtasan = $queryAtasan['atasan'];

        // Ambil ID Telegram Atasan dari nama
        $telegramAtasan = $this->db->query("SELECT `user`.nama, `user`.telegram FROM `user` where `user`.nama = '$namaAtasan'")->row_array();

        $data = [
            'id_kontrak' => uniqid(),
            'nip' => $this->input->post('nipkk', true),
            'kontrakkinerjake' => $this->input->post('kontrakkinerjake', true),
            'nomorkk' => $this->input->post('nomorkontrakkinerja', true),
            'tanggalmulai' => $this->input->post('tanggalmulai', true),
            'tanggalselesai' => $this->input->post('tanggalselesai', true),
            'is_validated' => 1,
            'tahun_kontrak' => date("Y")
        ];

        $this->db->insert('kontrakkinerja', $data);

        $nomorKontrak = $this->input->post('nomorkontrakkinerja');
        // Send Notif ke Telegram

        $this->_telegram(
            $telegramAtasan['telegram'],
            "Halo, *" . $telegramAtasan['nama'] . "*. \n\nBawahan anda: *" . $login . "* telah mengajukan Kontrak Kinerja dengan data sebagai berikut: \n\n*Nomor Kontrak Kinerja*: " . $nomorKontrak . "\n\nMohon diperiksa dan diberikan persetujuan apabila data sudah benar, terima kasih."
        );
    }

    //Hapus KK 
    public function hapuskontrak($id)
    {
        $this->db->where('id_kontrak', $id);
        $this->db->delete('kontrakkinerja');
    }

    //Edit KK
    public function editkontrak()
    {
        $data = [
            'kontrakkinerjake' => $this->input->post('kontrakkinerjake'),
            'nomorkk' => $this->input->post('nomorkontrakkinerja'),
            'tanggalmulai' => $this->input->post('tanggalmulai'),
            'tanggalselesai' => $this->input->post('tanggalselesai'),
            'is_validated' => 1,
        ];

        $this->db->where('id_kontrak', $this->input->post('id_kontrak'));
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
