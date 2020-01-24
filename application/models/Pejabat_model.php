<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pejabat_model extends CI_Model
{
    //Ambil semua Kontrak Khusus Admin
    public function getAllKontrak()
    {
        $query = $this->db->query('SELECT `kontrakkinerja`.*, `user`.nama, `ref_validasiKK`.* 
                                    FROM `kontrakkinerja` JOIN `user` USING(nip) JOIN `ref_validasiKK` 
                                    WHERE `kontrakkinerja`.`is_validated` = `ref_validasiKK`.`validasi_id` 
                                    ORDER BY `kontrakkinerja`.`is_validated` ASC');
        return $query->result_array();
    }

    //Ambil kontrak bawahan
    public function getKontrakBawahan()
    {
        $loggedIn = $this->session->userdata('pegawai_id');

        $query = $this->db->query("SELECT`kontrakkinerja`.`id_kontrak`,`kontrakkinerja`.`nomorkk`,`user`.`nama`,
                                    `kontrakkinerja`.`tanggalmulai`,`kontrakkinerja`.`tanggalselesai`,`kontrakkinerja`.`is_validated`,
                                    `ref_validasiKK`.`validasi_ket`FROM `kontrakkinerja` JOIN `user` USING (nip) 
                                    JOIN `ref_validasiKK` ON `kontrakkinerja`.`is_validated`=`ref_validasiKK`.`validasi_id` 
                                    WHERE `pejabat_id` = '$loggedIn'");
        return $query->result_array();
    }

    //Ambil detail Kontrak
    public function getDetailKontrak($idKontrak)
    {
        $query = $this->db->query("SELECT `id_kontrak`, `nomorkk`,`kontrakkinerjake`, `tanggalmulai`, `tanggalselesai` 
                                    FROM `kontrakkinerja` WHERE `id_kontrak` = '$idKontrak'");
        return $query->row_array();
    }

    //Ambil IKU dari Kontrak
    public function getListIKUFromKontrak($idKontrak)
    {
        $query = $this->db->get_where('indikatorkinerjautama', ['id_kontrak' => $idKontrak]);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        };
    }

    // Ambil Logbook yang telah dikirim
    public function getLogbookIsSent($idIKU)
    {
        $query = $this->db->get_where('logbook', ['id_iku' => $idIKU, 'is_sent' => 1]);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        };
    }

    //Fungsi approve kontrak
    public function approveKontrak($idKontrak)
    {
        $loggedIn = $this->session->userdata('pegawai_id');

        $query = $this->db->query("SELECT `kontrakkinerja`.`id_kontrak`, `kontrakkinerja`.`nomorkk`,
                                    `user`.nama, `user`.pejabat_id, `user`.telegram FROM `kontrakkinerja` 
                                    JOIN `user` USING (nip) WHERE `kontrakkinerja`.id_kontrak = '$idKontrak'");

        $telegram = $query->row_array();

        $data =
            [
                'is_validated' => 2,
                'pejabat_id_validated' => $loggedIn,
                'tgl_validated' => date("Y-m-d H:i:s"),
            ];

        $this->db->where('id_kontrak', $idKontrak);
        $this->db->update('kontrakkinerja', $data);

        // Kirim notifikasi ke Telegram bahwa Kontrak sudah diapprove
        $this->_telegram(
            $telegram['telegram'],
            "Halo, *" . $telegram['nama'] . " *\n\nKontrak Kinerja anda dengan nomor *" . $telegram['nomorkk'] . "* \nTELAH DISETUJUI oleh atasan anda.\nSilahkan melanjutkan dengan mengisi IKU dan Logbook anda, terima kasih."
        );
    }

    //Fungsi batal approve kontrak
    public function rejectKontrak($idKontrak)
    {
        $query = $this->db->query("SELECT `kontrakkinerja`.`id_kontrak`, `kontrakkinerja`.`nomorkk`,
                                    `user`.nama, `user`.pejabat_id, `user`.telegram FROM `kontrakkinerja` 
                                    JOIN `user` USING (nip) WHERE `kontrakkinerja`.id_kontrak = '$idKontrak'");

        $telegram = $query->row_array();

        $data =
            ['is_validated' => 1];

        $this->db->where('id_kontrak', $idKontrak);
        $this->db->update('kontrakkinerja', $data);

        $this->_telegram(
            $telegram['telegram'],
            "Halo, *" . $telegram['nama'] . " *\n\nKontrak Kinerja anda dengan nomor: *" . $telegram['nomorkk'] . "* \nTIDAK DISETUJUI oleh atasan anda.\nSilahkan melakukan perbaikan pada Kontrak Kinerja anda, terima kasih."
        );
    }

    //Aprrove IKU
    public function approvalIKU($idIKU)
    {
        $role = $this->session->userdata('pegawai_id');

        $query = $this->db->query("SELECT `indikatorkinerjautama`.id_iku, 
                                    `indikatorkinerjautama`.kodeiku, `indikatorkinerjautama`.namaiku, 
                                    `user`.nama, `user`.telegram 
                                    FROM `indikatorkinerjautama` JOIN `user` USING (nip) 
                                    WHERE `indikatorkinerjautama`.id_iku = '$idIKU'");

        $telegram = $query->row_array();

        $data =
            [
                'iku_validated' => 1,
                'pejabat_validated' => $role
            ];

        // Update status validated
        $this->db->where('id_iku', $idIKU);
        $this->db->update('indikatorkinerjautama', $data);

        // Send Notif ke Telegram
        $this->_telegram(
            $telegram['telegram'],
            "Halo, *" . $telegram['nama'] . "*. \nIKU anda dengan data sebagai berikut: \n\n*Kode IKU*: " . $telegram['kodeiku'] . "\n*Nama IKU*: " . $telegram['namaiku'] . "\n\nTELAH DISETUJUI oleh atasan anda. \nSilahkan melakukan pengisian Logbook anda, terima kasih."
        );
    }

    //Batal approve IKU
    public function rejectIKU($idIKU)
    {
        $query = $this->db->query("SELECT `indikatorkinerjautama`.id_iku, 
                                    `indikatorkinerjautama`.kodeiku, `indikatorkinerjautama`.namaiku, 
                                    `user`.nama, `user`.telegram 
                                    FROM `indikatorkinerjautama` JOIN `user` USING (nip) 
                                    WHERE `indikatorkinerjautama`.id_iku = '$idIKU'");

        $telegram = $query->row_array();

        $data =
            [
                'iku_validated' => 0,
                'pejabat_validated' => null
            ];

        $this->db->where('id_iku', $idIKU);
        $this->db->update('indikatorkinerjautama', $data);

        // Send Notif ke Telegram
        $this->_telegram(
            $telegram['telegram'],
            "Halo, *" . $telegram['nama'] . "*. \nIKU anda dengan data sebagai berikut: \n\n*Kode IKU*: " . $telegram['kodeiku'] . "\n*Nama IKU*: " . $telegram['namaiku'] . "\n\nTIDAK DISETUJUI oleh atasan anda. \nSilahkan melakukan perbaikan pada IKU anda, terima kasih."
        );
    }

    //Approve Logbook
    public function approvalLogbook($idLogbook)
    {
        $role = $this->session->userdata('pejabat_id');

        // Ambil Data Telegram 
        $query = $this->db->query("SELECT `logbook`.`id_logbook`, `logbook`.`periode`,
                                    `logbook`.`nippegawai`, `user`.`nama`,
                                    `indikatorkinerjautama`.`kodeiku`,`indikatorkinerjautama`.`namaiku`,
                                    `user`.`telegram` FROM `logbook` JOIN `user`
                                    ON `logbook`.`nippegawai` = `user`.`nip`
                                    JOIN `indikatorkinerjautama`
                                    ON `logbook`.`id_iku` = `indikatorkinerjautama`.`id_iku`
                                    WHERE `logbook`.`id_logbook` = '$idLogbook'");

        $telegram = $query->row_array();

        $data =
            [
                'is_sent' => 1,
                'is_approved' => 1,
                'tgl_approve' => date("Y-m-d H:i:s"),
                'pejabat_validated' => $role,
            ];

        $this->db->where('id_logbook', $idLogbook);
        $this->db->update('logbook', $data);

        // Send Notif ke Telegram
        $this->_telegram(
            $telegram['telegram'],
            "Halo, *" . $telegram['nama'] . "*. \nLogbook anda dengan data sebagai berikut: \n\n*Kode IKU*: " . $telegram['kodeiku'] . "\n*Nama IKU*: " . $telegram['namaiku'] . "\n*Periode Pelaporan Logbook*: " . $telegram['periode'] . "\n\nTELAH DISETUJUI oleh atasan anda.\nTerima kasih telah mengajukan Logbook."
        );
    }
    //Batal approve logbook
    public function rejectLogbook($idLogbook)
    {
        // Ambil Data Telegram 
        $query = $this->db->query("SELECT `logbook`.`id_logbook`, `logbook`.`periode`,
                                    `logbook`.`nippegawai`, `user`.`nama`,
                                    `indikatorkinerjautama`.`kodeiku`,`indikatorkinerjautama`.`namaiku`,
                                    `user`.`telegram` FROM `logbook` JOIN `user`
                                    ON `logbook`.`nippegawai` = `user`.`nip`
                                    JOIN `indikatorkinerjautama`
                                    ON `logbook`.`id_iku` = `indikatorkinerjautama`.`id_iku`
                                    WHERE `logbook`.`id_logbook` = '$idLogbook'");

        $telegram = $query->row_array();

        $data =
            [
                'tgl_approve' => NULL,
                'is_approved' => 0,
                'is_sent' => 0,
                'tgl_batalapprove' => date("Y-m-d H:i:s")
            ];

        $this->db->where('id_logbook', $idLogbook);
        $this->db->update('logbook', $data);

        // Send Notif ke Telegram
        $this->_telegram(
            $telegram['telegram'],
            "Halo, *" . $telegram['nama'] . "*. \nLogbook anda dengan data sebagai berikut: \n\n*Kode IKU*: " . $telegram['kodeiku'] . "\n*Nama IKU*: " . $telegram['namaiku'] . "\n*Periode Pelaporan Logbook*: " . $telegram['periode'] . "\n\nTIDAK DISETUJUI oleh atasan anda. \nSilahkan melakukan perbaikan pada Logbook anda, terima kasih."
        );
    }

    //Fungsi Hitung Bawahan
    public function countBawahan()
    {
        $role = $this->session->userdata('pegawai_id');

        $query = $this->db->query("SELECT `user`.nip FROM user where pejabat_id = '$role' ");
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    //Fungsi hitung KK Bawahan yang belum diapprove
    public function countKKBawahanNotApproved()
    {
        $role = $this->session->userdata('pegawai_id');

        $query = $this->db->query("SELECT `kontrakkinerja`.*, `user`.nama, `user`.`pejabat_id` from kontrakkinerja  join user using (nip) where pejabat_id = '$role' and is_validated != 2");
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    //Fungsi hitung IKU Bawahan yang belum diapprove
    public function countIKUBawahanNotApproved()
    {
        $role = $this->session->userdata('pegawai_id');

        $query = $this->db->query("SELECT `indikatorkinerjautama`.id_iku, `user`.nama, `user`.pejabat_id from indikatorkinerjautama join user using (nip) where pejabat_id = '$role' and iku_validated != 1");
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    //Fungsi hitung Logbook Bawahan yang belum diapprove
    public function countLogbookBawahanNotApproved()
    {
        $role = $this->session->userdata('pegawai_id');

        $query = $this->db->query("SELECT `user`.`nama`, `user`.`nip`, `user`.`pejabat_id`, `kontrakkinerja`.`id_kontrak`, `kontrakkinerja`.`nip`, `indikatorkinerjautama`.id_iku,
                                    `indikatorkinerjautama`.`nip`, `indikatorkinerjautama`.id_kontrak, `logbook`.* 
                                    FROM `kontrakkinerja` RIGHT JOIN `user` USING (`nip`) 
                                    JOIN `indikatorkinerjautama` USING (`id_kontrak`)
                                    JOIN `logbook` USING (`id_iku`) WHERE `is_sent` = 1 AND `is_approved` != 1 AND `pejabat_id` = '$role'");
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
