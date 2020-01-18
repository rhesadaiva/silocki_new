<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kontrak_model extends CI_Model
{

    //Ambil data user
    public function getUserList()
    {
        $query = $this->db->query('SELECT `nama`, `nip` FROM `user`');
        return $query->result_array();
    }

    //Ambil semua KK (khusus admin)
    public function getKontrak()
    {
        $query = $this->db->query('SELECT `kontrakkinerja`.*, `user`.nama, `ref_validasiKK`.* 
                                    FROM `kontrakkinerja` JOIN `user` USING(nip) JOIN `ref_validasiKK` 
                                    WHERE `kontrakkinerja`.`is_validated` = `ref_validasiKK`.`validasi_id` 
                                    ORDER BY `kontrakkinerja`.`is_validated` ASC');
        return $query->result_array();
    }

    //Ambil KK berdasarkan NIP login
    public function getKontrakByNIP()
    {
        $role = $this->session->userdata('nip');
        $query = $this->db->query("SELECT `kontrakkinerja`.*, `user`.nama, `ref_validasiKK`.* 
                                    FROM `kontrakkinerja` JOIN `user` USING(nip) JOIN `ref_validasiKK` 
                                    ON `kontrakkinerja`.`is_validated` = `ref_validasiKK`.`validasi_id` WHERE nip='$role' ");
        return $query->result_array();
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
        $queryAtasan = $this->db->query("SELECT `user`.nip, `user`.pejabat_id, `pejabat`.`nama_pejabat`, `pejabat`.`pejabat_id` FROM `user` 
                                        JOIN `pejabat` USING (pejabat_id) WHERE `user`.nip = '$role'")->row_array();
        $namaAtasan = $queryAtasan['nama_pejabat'];

        // Ambil ID Telegram Atasan dari nama
        $telegramAtasan = $this->db->query("SELECT `user`.nama, `user`.telegram FROM `user` where `user`.nama = '$namaAtasan'")->row_array();

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

        $this->db->insert('kontrakkinerja', $data);

        $nomorKontrak = $this->input->post('nomorKontrakKinerja');

        // Send Notif ke Telegram
        $this->_telegram(
            $telegramAtasan['telegram'],
            "Halo, *" . $telegramAtasan['nama'] . "*. \n\nBawahan anda: *" . $login . "* telah mengajukan Kontrak Kinerja dengan data sebagai berikut: \n\n*Nomor Kontrak Kinerja*: " . $nomorKontrak . "\n\nMohon diperiksa dan diberikan persetujuan apabila data sudah benar, terima kasih."
        );
        return true;
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
