<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pejabat_model extends CI_Model
{
    //Ambil semua Kontrak Khusus Admin
    public function getAllKontrak()
    {
        $query = $this->db->query("SELECT `kontrakkinerja`.*, `user`.nama, `user`.nip, `user`.atasan, `user`.telegram from kontrakkinerja join user using (nip)");

        return $query->result_array();
    }

    //Ambil kontrak bawahan
    public function getKontrakBawahan()
    {
        $role = $this->session->userdata('nama');

        $query = $this->db->query("SELECT `kontrakkinerja`.*, `user`.nama, `user`.nip, `user`.atasan, `user`.telegram from kontrakkinerja join user using (nip) where atasan = '$role' ");

        return $query->result_array();
    }

    //Fungsi approve kontrak
    public function approvekontrak($id)
    {
        $role = $this->session->userdata('nama');

        $query = $this->db->query("SELECT `kontrakkinerja`.*, `user`.nama, `user`.nip, `user`.atasan, `user`.telegram from `kontrakkinerja` join `user` using (nip) where `kontrakkinerja`.id_kontrak = '$id' ");

        $telegram = $query->row_array();

        $data =
            [
                'is_validated' => 2,
                'nama_validated' => $role,
                'tgl_validated' => date("Y-m-d H:i:s"),
            ];

        $this->db->where('id_kontrak', $id);
        $this->db->update('kontrakkinerja', $data);

        // Kirim notifikasi ke Telegram bahwa Kontrak sudah diapprove
        $this->_telegram(
            $telegram['telegram'],
            "Halo, *" . $telegram['nama'] . " *\n\nKontrak Kinerja anda dengan nomor *" . $telegram['nomorkk'] . "* \nTELAH DISETUJUI oleh atasan anda.\nSilahkan melanjutkan dengan mengisi IKU dan Logbook anda, terima kasih."
        );
    }

    //Fungsi batal approve kontrak
    public function batalapprovekontrak($id)
    {
        $query = $this->db->query("SELECT `kontrakkinerja`.*, `user`.nama, `user`.nip, `user`.atasan, `user`.telegram from `kontrakkinerja` join `user` using (nip) where `kontrakkinerja`.id_kontrak = '$id' ");

        $telegram = $query->row_array();

        $data =
            [
                'is_validated' => 1
            ];

        $this->db->where('id_kontrak', $id);
        $this->db->update('kontrakkinerja', $data);

        $this->_telegram(
            $telegram['telegram'],
            "Halo, *" . $telegram['nama'] . " *\n\nKontrak Kinerja anda dengan nomor: *" . $telegram['nomorkk'] . "* \nTIDAK DISETUJUI oleh atasan anda.\nSilahkan melakukan perbaikan pada Kontrak Kinerja anda, terima kasih."
        );
    }

    //Ambil detail Kontrak
    public function getdetailkontrak($id)
    {
        return $this->db->get_where('kontrakkinerja', ['id_kontrak' => $id])->row_array();
    }

    //Ambil IKU dari Kontrak
    public function getIKUFromKontrak($id)
    {
        $id = $this->uri->segment(3);
        $query = $this->db->query("SELECT `user`.nip, `user`.`telegram`, `user`.atasan, 
                                    `kontrakkinerja`.*, `indikatorkinerjautama`.* FROM `user` JOIN `kontrakkinerja` 
                                    using (nip) join `indikatorkinerjautama` using (id_kontrak)
                                    where `indikatorkinerjautama`.id_kontrak = '$id' ");
        return $query->result_array();
    }

    //Aprrove IKU
    public function approveiku($idiku)
    {
        $role = $this->session->userdata('nama');

        $query = $this->db->query(" SELECT `indikatorkinerjautama`.id_iku, `indikatorkinerjautama`.nip, 
                                    `indikatorkinerjautama`.kodeiku, `indikatorkinerjautama`.namaiku, `user`.nama, `user`.nip, `user`.telegram FROM `indikatorkinerjautama` JOIN `user` USING (nip) WHERE `indikatorkinerjautama`.id_iku = '$idiku'");

        $telegram = $query->row_array();

        $data =
            [
                'iku_validated' => 1,
                'nama_validated' => $role
            ];

        $this->db->where('id_iku', $idiku);
        $this->db->update('indikatorkinerjautama', $data);

        // Send Notif ke Telegram
        $this->_telegram(
            $telegram['telegram'],
            "Halo, *" . $telegram['nama'] . "*. \nIKU anda dengan data sebagai berikut: \n\n*Kode IKU*: " . $telegram['kodeiku'] . "\n*Nama IKU*: " . $telegram['namaiku'] . "\n\nTELAH DISETUJUI oleh atasan anda. \nSilahkan melakukan pengisian Logbook anda, terima kasih."
        );
    }

    //Batal approve IKU
    public function batalapproveiku($idiku)
    {
        $query = $this->db->query("SELECT `indikatorkinerjautama`.id_iku, `indikatorkinerjautama`.nip,                                         `indikatorkinerjautama`.kodeiku, `indikatorkinerjautama`.namaiku,                                             `user`.nama, `user`.nip, `user`.telegram FROM `indikatorkinerjautama`                                         JOIN `user` USING (nip) WHERE `indikatorkinerjautama`.id_iku =                                              '$idiku'");

        $telegram = $query->row_array();

        $data =
            [
                'iku_validated' => 0
            ];

        $this->db->where('id_iku', $idiku);
        $this->db->update('indikatorkinerjautama', $data);

        // Send Notif ke Telegram
        $this->_telegram(
            $telegram['telegram'],
            "Halo, *" . $telegram['nama'] . "*. \nIKU anda dengan data sebagai berikut: \n\n*Kode IKU*: " . $telegram['kodeiku'] . "\n*Nama IKU*: " . $telegram['namaiku'] . "\n\nTIDAK DISETUJUI oleh atasan anda. \nSilahkan melakukan perbaikan pada IKU anda, terima kasih."
        );
    }

    //Approve Logbook
    public function approvelogbook($idlogbook)
    {
        $role = $this->session->userdata('nama');

        // Ambil Data Telegram 
        $query1 = $this->db->query("SELECT `logbook`.id_logbook, `logbook`.periode, 
                                    `logbook`.nippegawai, `user`.nip, `user`.nama, 
                                    `user`.telegram FROM `logbook` JOIN `user`
                                    ON `logbook`.nippegawai = `user`.nip 
                                    WHERE `logbook`.id_logbook = '$idlogbook'");

        $telegram = $query1->row_array();

        // Ambil data IKU
        $query2 = $this->db->query("SELECT `logbook`.id_iku, `logbook`.id_logbook, `logbook`.periode, `indikatorkinerjautama`.id_iku, `indikatorkinerjautama`.kodeiku, `indikatorkinerjautama`.namaiku FROM `logbook` JOIN `indikatorkinerjautama` USING (id_iku) WHERE `logbook`.id_logbook = '$idlogbook'");

        $dataIKU = $query2->row_array();

        $data =
            [
                'is_sent' => 1,
                'is_approved' => 1,
                'tgl_approve' => date("Y-m-d H:i:s"),
                'nama_validated' => $role,
            ];

        $this->db->where('id_logbook', $idlogbook);
        $this->db->update('logbook', $data);

        // Send Notif ke Telegram
        $this->_telegram(
            $telegram['telegram'],
            "Halo, *" . $telegram['nama'] . "*. \nLogbook anda dengan data sebagai berikut: \n\n*Kode IKU*: " . $dataIKU['kodeiku'] . "\n*Nama IKU*: " . $dataIKU['namaiku'] . "\n*Periode Pelaporan Logbook*: " . $telegram['periode'] . "\n\nTELAH DISETUJUI oleh atasan anda.\nTerima kasih telah mengajukan Logbook."
        );
    }
    //Batal approve logbook
    public function batalapprovelogbook($idlogbook)
    {
        // Ambil Data Telegram 
        $query1 = $this->db->query("SELECT `logbook`.id_logbook, `logbook`.periode, `logbook`.nippegawai, `user`.nip, `user`.nama, `user`.telegram FROM `logbook` JOIN `user` ON `logbook`.nippegawai = `user`.nip WHERE `logbook`.id_logbook = '$idlogbook'");

        $telegram = $query1->row_array();

        // Ambil data IKU
        $query2 = $this->db->query("SELECT `logbook`.id_iku, `logbook`.id_logbook, `logbook`.periode, `indikatorkinerjautama`.id_iku, `indikatorkinerjautama`.kodeiku, `indikatorkinerjautama`.namaiku FROM `logbook` JOIN `indikatorkinerjautama` USING (id_iku) WHERE `logbook`.id_logbook = '$idlogbook'");

        $dataIKU = $query2->row_array();

        $data =
            [
                'tgl_approve' => NULL,
                'is_approved' => 0,
                'is_sent' => 0,
                'tgl_batalapprove' => date("Y-m-d H:i:s")
            ];

        $this->db->where('id_logbook', $idlogbook);
        $this->db->update('logbook', $data);

        // Send Notif ke Telegram
        $this->_telegram(
            $telegram['telegram'],
            "Halo, *" . $telegram['nama'] . "*. \nLogbook anda dengan data sebagai berikut: \n\n*Kode IKU*: " . $dataIKU['kodeiku'] . "\n*Nama IKU*: " . $dataIKU['namaiku'] . "\n*Periode Pelaporan Logbook*: " . $telegram['periode'] . "\n\nTIDAK DISETUJUI oleh atasan anda. \nSilahkan melakukan perbaikan pada Logbook anda, terima kasih."
        );
    }

    //Fungsi Hitung Bawahan
    public function countBawahan()
    {
        $role = $this->session->userdata('nama');

        $query = $this->db->query("SELECT `user`.nip FROM user where atasan = '$role' ");
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    //Fungsi hitung KK Bawahan yang belum diapprove
    public function countKKBawahanNotApproved()
    {
        $role = $this->session->userdata('nama');

        $query = $this->db->query("SELECT `kontrakkinerja`.*, `user`.nama, `user`.`atasan` from kontrakkinerja  join user using (nip) where atasan = '$role' and is_validated != 2");
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    //Fungsi hitung IKU Bawahan yang belum diapprove
    public function countIKUBawahanNotApproved()
    {
        $role = $this->session->userdata('nama');

        $query = $this->db->query("SELECT `indikatorkinerjautama`.id_iku, `user`.nama, `user`.atasan from indikatorkinerjautama join user using (nip) where atasan = '$role' and iku_validated != 1");
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    //Fungsi hitung Logbook Bawahan yang belum diapprove
    public function countLogbookBawahanNotApproved()
    {
        $role = $this->session->userdata('nama');

        $query = $this->db->query("SELECT `user`.`nama`, `user`.`nip`, `user`.`atasan`, `kontrakkinerja`.`id_kontrak`, `kontrakkinerja`.`nip`, `indikatorkinerjautama`.id_iku, `indikatorkinerjautama`.`nip`, `indikatorkinerjautama`.id_kontrak, `logbook`.* 
                                    from `kontrakkinerja` right join `user` using (`nip`) 
                                    join `indikatorkinerjautama` using (`id_kontrak`)
                                    join `logbook` using (`id_iku`) where `is_sent` = 1 and `is_approved` != 1 and `atasan` = '$role'");
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    public function getPengumuman()
    {
        return $this->db->get('pengumuman')->result_array();
    }

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
