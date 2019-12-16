<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logbook_model extends CI_Model
{

    public function newlogbook()
    {
        //Rekam Logbook Baru
        $data = [
            'periode' => $this->input->post('periodepelaporan', true),

            //UNTUK DATA DIBAWAH INI TIDAK PERLU FORM VALIDATION
            'id_iku' => $this->input->post('id_iku', true),
            'id_logbook' => uniqid(),
            'is_sent' => 0,
            'wakturekam' => date("Y-m-d H:i:s"),
            'nippegawai' => $this->session->userdata('nip'),
            'tahun_logbook' => date("Y"),
            //END OF TIDAK PERLU FORM VALIDATION

            'perhitungan' => $this->input->post('perhitungan', true),
            'realisasibulan' => $this->input->post('realisasipadabulan', true),
            'realisasiterakhir' => $this->input->post('realisasisdbulan', true),

            'ket' => $this->input->post('keterangan', true),
        ];

        //Insert data
        $this->db->insert('logbook', $data);
    }

    //Ambil data logbook
    public function getlogbook($idiku)
    {
        $idiku = $this->uri->segment(3);
        $query = $this->db->query("SELECT indikatorkinerjautama.*, logbook.* FROM indikatorkinerjautama JOIN logbook using(id_iku) where logbook.id_iku='$idiku'");
        return $query->result_array();
    }

    // Ambil data logbook yang sudah dikirim
    public function getsentlogbook($idiku)
    {
        $idiku = $this->uri->segment(3);
        $query = $this->db->query("SELECT indikatorkinerjautama.*, logbook.* FROM indikatorkinerjautama JOIN logbook using(id_iku) where logbook.id_iku='$idiku' and is_sent = 1");
        return $query->result_array();
    }

    // Hapus Logbook
    public function deletelogbook($idlogbook)
    {
        $this->db->where('id_logbook', $idlogbook);
        $this->db->delete('logbook');
    }

    //Kirim data Logbook ke atasan
    public function kirimlogbook($idlogbook)
    {
        $role = $this->session->userdata('nip');
        $login = $this->session->userdata('nama');
        // Ambil Nama Atasan
        $queryAtasan = $this->db->query("SELECT `user`.nip, `user`.atasan FROM `user` where `user`.nip = '$role'")->row_array();
        $namaAtasan = $queryAtasan['atasan'];
        // Ambil ID Telegram Atasan dari nama
        $telegramAtasan = $this->db->query("SELECT `user`.nama, `user`.telegram FROM `user` where `user`.nama = '$namaAtasan'")->row_array();

        $data = [
            'is_sent' => 1
        ];

        $this->db->where('id_logbook', $idlogbook);
        $this->db->update('logbook', $data);

        // $this->db->insert('logbook', $data);

        // $id_iku = $this->input->post('id_iku', true);

        // Query data IKU untuk dikirim ke Telegram
        $dataIKU = $this->db->query("SELECT `logbook`.id_iku, `logbook`.id_logbook, `logbook`.periode, `indikatorkinerjautama`.id_iku, `indikatorkinerjautama`.kodeiku, `indikatorkinerjautama`.namaiku FROM `logbook` JOIN `indikatorkinerjautama` USING (id_iku) WHERE `logbook`.id_logbook = '$idlogbook'")->row_array();

        // Send Notif ke Telegram
        $this->_telegram(
            $telegramAtasan['telegram'],
            "Halo, *" . $telegramAtasan['nama'] . "*. \n\nBawahan anda: *" . $login . "* telah mengirim Logbook dengan data sebagai berikut: \n\n*Kode IKU*: " . $dataIKU['kodeiku'] . "\n*Nama IKU*: " . $dataIKU['namaiku'] . "\n*Periode Pelaporan Logbook*: " . $dataIKU['periode'] . "\n\nMohon diperiksa dan diberikan persetujuan apabila data sudah benar, terima kasih."
        );
    }

    public function printLogbook($idlogbook)
    {
        $idlogbook = $this->uri->segment(3);

        $query = $this->db->query("SELECT `user`.nama, `user`.`nip`, `user`.`seksi`, `user`.role_id, `user_role`.id, `user_role`.level, `indikatorkinerjautama`.id_iku, `indikatorkinerjautama`.`nip`, `indikatorkinerjautama`.namaiku, `indikatorkinerjautama`.formulaiku, `indikatorkinerjautama`.targetiku, `logbook`.periode, `logbook`.perhitungan, `logbook`.realisasibulan, `logbook`.realisasiterakhir, `logbook`.ket, `logbook`.tgl_approve, `logbook`.tahun_logbook
        from user join user_role on `user`.role_id = `user_role`.id
        join `indikatorkinerjautama` using (`nip`)
        join `logbook` using (id_iku) where `logbook`.id_logbook = '$idlogbook'");

        return $query->row_array();
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
