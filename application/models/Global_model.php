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
        $query = $this->db->query("SELECT * FROM `tabel_log` WHERE `log_user` = '$name' ORDER BY `log_id` DESC LIMIT 4");
        return $query->result_array();
    }

    // Ambil semua aktivitas
    public function getAllActivity($name)
    {
        $query = $this->db->query("SELECT * FROM `tabel_log` WHERE `log_user` = '$name' ORDER BY `log_id` DESC");
        return $query->result_array();
    }

    // Ambil data user yang sedang login
    public function getLoggedUser($loggedNIP)
    {
        $query = $this->db->query("SELECT `user`.id, `user`.nama, `user`.nip, `user`.pangkat, `user`.`role_id`, `user`.`seksi`, 
                                    `user`.`pejabat_id`,`user`.`telegram`, `user`.`img`,
                                    `user_role`.* , `pejabat`.`pejabat_id`, `pejabat`.`nama_pejabat`
                                    FROM `user` JOIN `user_role` ON `user`.`role_id` = `user_role`.`id_role` JOIN `pejabat` 
                                    ON `user`.`pejabat_id` = `pejabat`.`pejabat_id`
                                    WHERE `user`.`nip` = '$loggedNIP'");

        return $query->row_array();
    }

    // Ambil data User dan NIP
    public function getUserList()
    {
        $query = $this->db->query('SELECT `nama`, `nip` FROM `user`');
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
}
