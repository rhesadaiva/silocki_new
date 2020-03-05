<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Global_model extends CI_Model
{
    // Ambil data Pengumuman
    public function getPengumuman()
    {
        return $this->db->get('pengumuman')->result_array();
    }

    // Ambil 4 log aktivitas terakhir
    public function getLastActivity($name)
    {
        $this->db->select('*');
        $this->db->from('tabel_log');
        $this->db->where('log_user', $name);
        $this->db->order_by('log_id', 'DESC');
        $this->db->limit(4);

        $query = $this->db->get();
        return $query->result_array();
    }

    // Ambil semua aktivitas
    public function getAllActivity($name)
    {
        $this->db->select('*');
        $this->db->from('tabel_log');
        $this->db->where('log_user', $name);
        $this->db->order_by('log_id', 'DESC');

        $query = $this->db->get();
        return $query->result_array();
    }

    // Ambil data user yang sedang login
    public function getLoggedUser($loggedNIP)
    {
        $this->db->select('user.id, user.nama, user.nip, user.pangkat, user.role_id,
                          user.seksi, user.pejabat_id, user.telegram, user.img,
                          user_role.level,
                          pejabat.nama_pejabat,
                          seksi_subseksi.seksi_subseksi');

        $this->db->from('user');
        $this->db->join('user_role', 'user.role_id = user_role.id_role');
        $this->db->join('pejabat', 'user.pejabat_id = pejabat.pejabat_id');
        $this->db->join('seksi_subseksi', 'user.seksi = seksi_subseksi.id_seksi_subseksi');
        $this->db->where('user.nip', $loggedNIP);
        $query = $this->db->get();

        return $query->row_array();
    }

    // Ambil data User dan NIP
    public function getUserList()
    {
        $this->db->select('nama,nip');
        $this->db->from('user');

        $query = $this->db->get();
        return $query->result_array();
    }

    // Ambil data bulan referensi
    public function getBulanRef()
    {
        return $this->db->get('ref_Bulan')->result_array();
    }

    // Update upload foto profil ke database
    public function saveUpload($picture, $nip)
    {
        $this->db->set('img', $picture, true);
        $this->db->where('nip', $nip);
        $query = $this->db->update('user');

        return $query;
    }

    // Ambil data konfigurasi
    public function getConfigData()
    {
        return $this->db->get('config_data')->result_array();
    }

    public function getActiveConfig()
    {
        return $this->db->get_where('config_data', ['config_is_active' => 1])->result_array();
    }

    public function getConfigByID($idConfig)
    {
        return $this->db->get_where('config_data', ['config_id' => $idConfig])->row_array();
    }

    public function editConfig()
    {
        $idConfig = $this->input->post('configId');
        $data = [
            'config_value' => $this->input->post('configValue'),
            'config_is_active' => $this->input->post('configActive')
        ];

        $this->db->where('config_id', $idConfig);
        $this->db->update('config_data', $data);
    }
}
