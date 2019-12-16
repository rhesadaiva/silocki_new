<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Global_model extends CI_Model
{
    public function getPengumuman()
    {
        return $this->db->get('pengumuman')->result_array();
    }

    public function getLastActivity($name)
    {
        $query = $this->db->query("SELECT * FROM `tabel_log` WHERE `log_user` = '$name' ORDER BY `log_id` DESC LIMIT 4");
        return $query->result_array();
    }

    public function getAllActivity($name)
    {
        $query = $this->db->query("SELECT * FROM `tabel_log` WHERE `log_user` = '$name' ORDER BY `log_id` DESC");
        return $query->result_array();
    }
}
