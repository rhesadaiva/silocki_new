<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelaksana_model extends CI_Model
{
    public function countKKApproved()
    {
        $role = $this->session->userdata('nip');

        $query = $this->db->query("SELECT `kontrakkinerja`.id_kontrak, `user`.nama, `user`.nip, `user`.`pejabat_id` 
                                    FROM kontrakkinerja JOIN user USING (nip) 
                                    WHERE nip = '$role' AND is_validated = 2");
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    public function countIKUApproved()
    {
        $role = $this->session->userdata('nip');

        $query = $this->db->query("SELECT `indikatorkinerjautama`.id_kontrak, `user`.nama, `user`.nip, `user`.`pejabat_id` 
                                    FROM indikatorkinerjautama JOIN user USING (nip) 
                                    WHERE `user`.`nip` = '$role' AND `iku_validated` = 1");
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    public function countLogbookSent()
    {
        $role = $this->session->userdata('nip');

        $query = $this->db->query("SELECT `user`.`nama`, `user`.`nip`, `user`.`pejabat_id`, `kontrakkinerja`.`id_kontrak`, `kontrakkinerja`.`nip`, 
                                    `indikatorkinerjautama`.id_iku, `indikatorkinerjautama`.`nip`,`indikatorkinerjautama`.id_kontrak, 
                                    `logbook`.`id_logbook`
                                    FROM `kontrakkinerja` RIGHT JOIN `user` USING (`nip`) 
                                    JOIN `indikatorkinerjautama` USING (`id_kontrak`)
                                    JOIN `logbook` USING (`id_iku`) WHERE `is_sent` = 1 AND `user`.nip = '$role' ");
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
}
