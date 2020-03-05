<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Logbook_model extends CI_Model
{

    //Ambil data logbook
    public function getLogbook($idIKU)
    {
        $query = $this->db->get_where('logbook', ['id_iku' => $idIKU]);
        if ($query->num_rows() > 0) {
            // Jika ada Logbook
            return $query->result_array();
        } else {
            // Jika tidak ada logbook, return null
            return null;
        }
    }

    //Rekam Logbook Baru
    public function newLogbook()
    {
        // Set Data yang diinput
        $data = [
            'id_iku' => $this->input->post('idIKU', true),
            'periode' => $this->input->post('periodeLogbook', true),
            'id_logbook' => uniqid(),
            'is_sent' => 0,
            'wakturekam' => date("Y-m-d H:i:s"),
            'nippegawai' => $this->session->userdata('nip'),
            'tahun_logbook' => date("Y"),
            'perhitungan' => $this->input->post('perhitunganLogbook', true),
            'realisasibulan' => $this->input->post('realisasiBulanPelaporanLogbook', true),
            'realisasiterakhir' => $this->input->post('realisasiTerakhirLogbook', true),
            'ket' => $this->input->post('keteranganLogbook', true),
        ];
        // Insert data Logbook
        $this->db->insert('logbook', $data);
    }

    /* DEPRECATED FUNCTION

    // Ambil data logbook yang sudah dikirim
    public function getsentlogbook($idiku)
    {
        $idiku = $this->uri->segment(3);
        $query = $this->db->query("SELECT indikatorkinerjautama.*, logbook.* FROM indikatorkinerjautama JOIN logbook using(id_iku) where logbook.id_iku='$idiku' and is_sent = 1");
        return $query->result_array();
    }

    */

    // Hapus Logbook
    public function deleteLogbook($idLogbook)
    {
        $this->db->where('id_logbook', $idLogbook);
        $this->db->delete('logbook');
    }

    // Ambil Logbook berdasarkan ID
    public function getLogbookByID($idLogbook)
    {
        return $this->db->get_where('logbook', ['id_logbook' => $idLogbook])->row_array();
    }

    // Edit Logbook
    public function editLogbook()
    {
        $idLogbook = $this->input->post('idLogbook');
        $data = [
            'periode' => $this->input->post('periodeLogbook'),
            'perhitungan' => $this->input->post('perhitunganLogbook'),
            'realisasibulan' => $this->input->post('realisasiBulanPelaporanLogbook', true),
            'realisasiterakhir' => $this->input->post('realisasiTerakhirLogbook', true),
            'ket' => $this->input->post('keteranganLogbook', true),
            'wakturekam' => date("Y-m-d H:i:s"),
        ];

        $this->db->where('id_logbook', $idLogbook);
        $this->db->update('logbook', $data);
    }

    // Kirim data Logbook ke atasan
    public function kirimLogbook()
    {
        // ID Logbook
        $idLogbook = $this->input->post('idLogbook');

        // Ambil data Login
        $nip = $this->session->userdata('nip');
        $login = $this->session->userdata('nama');

        // Ambil Nama Atasan
        // Ambil Nama Atasan
        $this->db->select('user.nip, user.pejabat_id, pejabat.nama_pejabat, pejabat.pejabat_id');
        $this->db->from('user');
        $this->db->join('pejabat', 'user.pejabat_id = pejabat.pejabat_id');
        $this->db->where('user.nip', $nip);

        $query = $this->db->get();
        $queryAtasan = $query->row_array();

        $namaAtasan = $queryAtasan['nama_pejabat'];

        // Ambil ID Telegram Atasan dari nama
        $telegramAtasan = $this->db->get_where('user', ['nama' => $namaAtasan])->row_array();

        // Update status logbook menjadi terkirim
        $data = [
            'is_sent' => 1
        ];
        $this->db->where('id_logbook', $idLogbook);
        $this->db->update('logbook', $data);

        // Query data IKU untuk dikirim ke Telegram
        $this->db->select('logbook.id_iku, logbook_id_logbook, logbook.periode, indikatorkinerjautama.id_iku, 
                            indikatorkinerjautama.kode_iku, indikatorkinerjautama.namaiku');
        $this->db->from('logbook');
        $this->db->join('indikatorkinerjautama', 'logbook.id_iku = indikatorkinerjautama.id_iku');
        $this->db->where('logbook.id_logbook', $idLogbook);

        $query = $this->db->get();
        $dataIKU = $query->row_array();

        // Send Notif ke Telegram
        $this->_telegram(
            $telegramAtasan['telegram'],
            "Halo, *" . $telegramAtasan['nama'] . "*. \n\nBawahan anda: *" . $login . "* telah mengirim Logbook dengan data sebagai berikut: \n\n*Kode IKU*: " . $dataIKU['kodeiku'] . "\n*Nama IKU*: " . $dataIKU['namaiku'] . "\n*Periode Pelaporan Logbook*: " . $dataIKU['periode'] . "\n\nMohon diperiksa dan diberikan persetujuan apabila data sudah benar, terima kasih."
        );
    }

    public function printLogbook($idlogbook)
    {
        $this->db->select('user.nama, user.nip, user.seksi, user.role_id,
                   user_role.id_role, user_role.level,
                   indikatorkinerjautama.id_iku, indikatorkinerjautama.nip,
                   indikatorkinerjautama.namaiku, indikatorkinerjautama.formulaiku,
                   indikatorkinerjautama.targetiku,
                   logbook.periode, logbook.perhitungan, logbook.realisasibulan,
                   logbook.realisasiterakhir, logbook.ket, logbook.tgl_approve, logbook.tahun_logbook');

        $this->db->from('user');
        $this->db->join('user_role', 'user.role_id = user_role.id_role');
        $this->db->join('indikatorkinerjautama', 'user.nip = indikatorkinerjautama.nip');
        $this->db->join('logbook', 'logbook.id_iku = indikatorkinerjautama.id_iku');
        $this->db->where('logbook.id_logbook', $idlogbook);

        $query = $this->db->get();
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
