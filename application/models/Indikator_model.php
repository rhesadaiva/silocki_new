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
        $this->db->select('kontrakkinerja.id_kontrak, kontrakkinerja.nomorkk, kontrakkinerja.nip, user.nama');
        $this->db->from('kontrakkinerja');
        $this->db->join('user', 'kontrakkinerja.nip = user.nip');

        $query = $this->db->get();
        return $query->result_array();
    }

    //Ambil data kontrak berdasarkan NIP login
    public function getKontrakByNIP()
    {
        $nip = $this->session->userdata('nip');

        $this->db->select('kontrakkinerja.*, user.nama, ref_validasiKK.*');
        $this->db->from('kontrakkinerja');
        $this->db->join('user', 'kontrakkinerja.nip = user.nip');
        $this->db->join('ref_validasiKK', 'kontrakkinerja.is_validated = ref_validasiKK.validasi_id');
        $this->db->where('kontrakkinerja.nip', $nip);

        $query = $this->db->get();
        return $query;
    }

    // Ambil data Kontrak berdasarkan NIP Login dan tahun berjalan (apabila Konfigurasi Tahun Berjalan diaktifkan)
    public function getKontrakByNIPYear($activeYear)
    {
        $nip = $this->session->userdata('nip');

        $this->db->select('kontrakkinerja.*, user.nama, ref_validasiKK.*');
        $this->db->from('kontrakkinerja');
        $this->db->join('user', 'kontrakkinerja.nip = user.nip');
        $this->db->join('ref_validasiKK', 'kontrakkinerja.is_validated = ref_validasiKK.validasi_id');
        $this->db->where('kontrakkinerja.nip', $nip);
        $this->db->where('kontrakkinerja.tahun_kontrak', $activeYear);

        $query = $this->db->get();
        return $query;
    }

    // Ambil data Kontrak khusus admin berdasarkan tahun berjalan (apabila Konfigurasi Tahun Berjalan diaktifkan)
    public function getKontrakByYear($activeYear)
    {
        $this->db->select('kontrakkinerja.id_kontrak, kontrakkinerja.nomorkk, kontrakkinerja.nip, kontrakkinerja.tahun_kontrak, user.nama');
        $this->db->from('kontrakkinerja');
        $this->db->join('user', 'kontrakkinerja.nip = user.nip');
        $this->db->where('kontrakkinerja.tahun_kontrak', $activeYear);

        $query = $this->db->get();
        return $query->result_array();
    }


    //Ambil semua IKU (admin)
    public function getIKU()
    {
        $this->db->select('kontrakkinerja.nomorkk, user.nama, indikatorkinerjautama.id_iku, indikatorkinerjautama.kodeiku,
                            indikatorkinerjautama.namaiku, indikatorkinerjautama.targetiku, indikatorkinerjautama.nilaitertinggi,
                            indikatorkinerjautama.iku_validated');

        $this->db->from('indikatorkinerjautama');
        $this->db->join('user', 'indikatorkinerjautama.nip = user.nip');
        $this->db->join('kontrakkinerja', 'indikatorkinerjautama.id_kontrak = kontrakkinerja.id_kontrak');
        $this->db->order_by('nama', 'asc');

        $query = $this->db->get();
        return $query->result_array();
    }

    // Ambil semua IKU khusus admin berdasarkan tahun berjalan (apabila Konfigurasi Tahun Berjalan diaktifkan)
    public function getIKUByYear($activeYear)
    {
        $this->db->select('kontrakkinerja.nomorkk, user.nama, indikatorkinerjautama.id_iku, indikatorkinerjautama.kodeiku,
                            indikatorkinerjautama.namaiku, indikatorkinerjautama.targetiku, indikatorkinerjautama.nilaitertinggi,
                            indikatorkinerjautama.tahun_iku,indikatorkinerjautama.iku_validated');

        $this->db->from('indikatorkinerjautama');
        $this->db->join('user', 'indikatorkinerjautama.nip = user.nip');
        $this->db->join('kontrakkinerja', 'indikatorkinerjautama.id_kontrak = kontrakkinerja.id_kontrak');
        $this->db->where('indikatorkinerjautama.tahun_iku', $activeYear);
        $this->db->order_by('nama', 'asc');

        $query = $this->db->get();
        return $query->result_array();
    }

    //Ambil data IKU berdasarkan NIP login
    public function getIKUByNIP()
    {
        $nip = $this->session->userdata('nip');

        $this->db->select('kontrakkinerja.id_kontrak, kontrakkinerja.nomorkk, indikatorkinerjautama.*');
        $this->db->from('kontrakkinerja');
        $this->db->join('indikatorkinerjautama', 'indikatorkinerjautama.id_kontrak = kontrakkinerja.id_kontrak');
        $this->db->where('indikatorkinerjautama.nip', $nip);

        $query = $this->db->get();
        return $query->result_array();
    }

    // Ambil data IKU berdasarkan NIP Login dan tahun berjalan (apabila Konfigurasi Tahun Berjalan diaktifkan)
    public function getIKUByNIPYear($activeYear)
    {
        $nip = $this->session->userdata('nip');

        $this->db->select('kontrakkinerja.id_kontrak, kontrakkinerja.nomorkk, indikatorkinerjautama.*');
        $this->db->from('kontrakkinerja');
        $this->db->join('indikatorkinerjautama', 'indikatorkinerjautama.id_kontrak = kontrakkinerja.id_kontrak');
        $this->db->where('indikatorkinerjautama.nip', $nip);
        $this->db->where('indikatorkinerjautama.tahun_iku', $activeYear);

        $query = $this->db->get();
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
        $nip = $this->session->userdata('nip');
        $login = $this->session->userdata('nama');

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
