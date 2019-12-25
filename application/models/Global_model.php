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

    public function getLoggedUser($loggedNIP)
    {
        $query = $this->db->query("SELECT `user`.id, `user`.nama, `user`.nip, `user`.pangkat, `user`.`role_id`, `user`.`seksi`, `user`.`pejabat_id`,`user`.`telegram`,
                                    `user_role`.* , `pejabat`.`pejabat_id`, `pejabat`.`nama_pejabat`
                                    FROM `user` JOIN `user_role` ON `user`.`role_id` = `user_role`.`id_role` JOIN `pejabat` 
                                    ON `user`.`pejabat_id` = `pejabat`.`pejabat_id`
                                    WHERE `user`.`nip` = '$loggedNIP'");

        return $query->row_array();
    }
}
