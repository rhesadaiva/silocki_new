<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Indikator_model extends CI_Model
{

    //Ambil IKU berdasarkan id
    public function getIKUById($idIKU)
    {

        return $this->db->get_where('indikatorkinerjautama', ['id_iku' => $idIKU])->row_array();
    }

    //Ambil data kontrak khusus admin
    public function getKontrak()
    {
        $query = $this->db->query('SELECT `kontrakkinerja`.`id_kontrak`,`kontrakkinerja`.`nomorkk`,
                                    `kontrakkinerja`.`nip`,`user`.`nama` 
                                    FROM `kontrakkinerja` JOIN `user` USING(nip)');

        return $query->result_array();
    }

    //Ambil data kontrak berdasarkan NIP login
    public function getKontrakByNIP()
    {
        $role = $this->session->userdata('nip');
        $query = $this->db->query("SELECT `kontrakkinerja`.*, `user`.nama, `ref_validasiKK`.* 
                                    FROM `kontrakkinerja` JOIN `user` USING(nip) JOIN `ref_validasiKK` 
                                    ON `kontrakkinerja`.`is_validated` = `ref_validasiKK`.`validasi_id` WHERE nip='$role' ");

        return $query;
    }

    //Ambil semua IKU (admin)
    public function getIKU()
    {
        $query = $this->db->query("SELECT `kontrakkinerja`.`nomorkk`,`user`.`nama`,
                                    `indikatorkinerjautama`.`id_iku`,`indikatorkinerjautama`.`kodeiku`,
                                    `indikatorkinerjautama`.`namaiku`,`indikatorkinerjautama`.`targetiku`,
                                    `indikatorkinerjautama`.`nilaitertinggi`,`indikatorkinerjautama`.`iku_validated`
                                    FROM `indikatorkinerjautama`JOIN `user` USING(nip) 
                                    JOIN `kontrakkinerja` USING(id_kontrak) ORDER BY `nama` ASC");
        return $query->result_array();
    }

    //Ambil data IKU berdasarkan NIP login
    public function getIKUByNIP()
    {
        $role = $this->session->userdata('nip');
        $query =  $this->db->query("SELECT `kontrakkinerja`.id_kontrak, `kontrakkinerja`.nomorkk, `indikatorkinerjautama`.*
                                    FROM `kontrakkinerja` JOIN `indikatorkinerjautama` USING (id_kontrak) 
                                    WHERE `indikatorkinerjautama`.nip = '$role'");
        return $query->result_array();
    }

    //Ambil Referensi Aspek Target
    public function getAspekTarget()
    {
        return $this->db->get('ref_AspekTarget')->result_array();
    }

    //Ambil Referensi Periode Pelaporan
    public function getPeriodePelaporan()
    {
        return $this->db->get('ref_PeriodePelaporan')->result_array();
    }

    //Ambil Referensi Konsolidasi Periode
    public function getKonsolidasiPeriode()
    {
        return $this->db->get('ref_KonsolidasiPeriode')->result_array();
    }

    //Ambil Referensi Konversi 120
    public function getKonversi120()
    {
        return $this->db->get('ref_Konversi120')->result_array();
    }

    //Ambil Referensi Bulan
    public function getBulan()
    {
        return $this->db->get('ref_Bulan')->result_array();
    }

    //Tambah IKU Baru
    public function newIKU()
    {
        $role = $this->session->userdata('nip');
        $login = $this->session->userdata('nama');
        // Ambil Nama Atasan
        $queryAtasan = $this->db->query("SELECT `user`.nip, `user`.pejabat_id, `pejabat`.`nama_pejabat`, `pejabat`.`pejabat_id` FROM `user` 
                                        JOIN `pejabat` USING (pejabat_id) WHERE `user`.nip = '$role'")->row_array();
        $namaAtasan = $queryAtasan['nama_pejabat'];
        // Ambil ID Telegram Atasan dari nama
        $telegramAtasan = $this->db->query("SELECT `user`.nama, `user`.telegram FROM `user` where `user`.nama = '$namaAtasan'")->row_array();

        //Ambil data dari form
        $data = [
            'nip' => $this->input->post('setIKUPegawai', true),
            'id_iku' => uniqid(),
            'id_kontrak' => $this->input->post('setKontrakPegawai', true),
            'kodeiku' => $this->input->post('kodeIKU', true),
            'namaiku' => $this->input->post('namaIKU', true),
            'formulaiku' => $this->input->post('formulaIKU', true),
            'targetiku' => $this->input->post('targetIKU', true),
            'nilaitertinggi' => $this->input->post('nilaiTertinggiIKU', true),
            'aspektarget' => $this->input->post('aspekTargetIKU', true),
            'penanggungjawab' => $this->input->post('penanggungJawabIKU', true),
            'penyediadata' => $this->input->post('penyediaDataIKU', true),
            'sumberdata' => $this->input->post('sumberDataIKU', true),
            'satuanpengukuran' => $this->input->post('satuanPengukuranIKU', true),
            'konsolidasiperiodeiku' => $this->input->post('konsolidasiPeriodeIKU', true),
            'periodepelaporan' => $this->input->post('periodePelaporanIKU', true),
            'konversi120' => $this->input->post('konversi120IKU', true),
            'tahun_iku' => date("Y"),
        ];

        $this->db->insert('indikatorkinerjautama', $data);

        // Send notif ke Telegram atasan
        $this->_telegram(
            $telegramAtasan['telegram'],
            "Halo, *" . $telegramAtasan['nama'] . "*. \n\nBawahan anda: *" . $login . "* telah mengajukan IKU dengan data sebagai berikut: \n\n*Kode IKU*: " . $this->input->post('kodeIKU') . "\n*Nama IKU*: " . $this->input->post('namaIKU') . "\n\nMohon diperiksa dan diberikan persetujuan apabila data sudah benar, terima kasih."
        );
    }

    //Hapus IKU
    public function deleteIKU($idiku)
    {
        $this->db->where('id_iku', $idiku);
        $this->db->delete('indikatorkinerjautama');
    }

    //Edit IKU
    public function editIKU()
    {
        $idIKU = $this->input->post('idIKU');
        $data = [
            'kodeiku' => $this->input->post('kodeIKU', true),
            'namaiku' => $this->input->post('namaIKU', true),
            'formulaiku' => $this->input->post('formulaIKU', true),
            'targetiku' => $this->input->post('targetIKU', true),
            'nilaitertinggi' => $this->input->post('nilaiTertinggiIKU', true),
            'aspektarget' => $this->input->post('aspekTargetIKU', true),
            'penanggungjawab' => $this->input->post('penanggungJawabIKU', true),
            'penyediadata' => $this->input->post('penyediaDataIKU', true),
            'sumberdata' => $this->input->post('sumberDataIKU', true),
            'satuanpengukuran' => $this->input->post('satuanPengukuranIKU', true),
            'konsolidasiperiodeiku' => $this->input->post('konsolidasiPeriodeIKU', true),
            'periodepelaporan' => $this->input->post('periodePelaporanIKU', true),
            'konversi120' => $this->input->post('konversi120IKU', true),
        ];

        $this->db->where('id_iku', $idIKU);
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
