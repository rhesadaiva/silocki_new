<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelaksana_model extends CI_Model
{
    public function countKKApproved()
    {
        $role = $this->session->userdata('nip');

        $query = $this->db->query("SELECT `kontrakkinerja`.*, `user`.nama, `user`.nip, `user`.`atasan` from kontrakkinerja join user using (nip) where nip = '$role' and is_validated = 2");
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    public function countIKUApproved()
    {
        $role = $this->session->userdata('nip');

        $query = $this->db->query("SELECT `indikatorkinerjautama`.*, `user`.nama, `user`.nip, `user`.atasan from indikatorkinerjautama join user using (nip) where user.nip = '$role' and iku_validated = 1");
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    public function countLogbookSent()
    {
        $role = $this->session->userdata('nip');

        $query = $this->db->query("SELECT `user`.`nama`, `user`.`nip`, `user`.`atasan`, `kontrakkinerja`.`id_kontrak`, `kontrakkinerja`.`nip`, `indikatorkinerjautama`.id_iku, `indikatorkinerjautama`.`nip`,`indikatorkinerjautama`.id_kontrak, `logbook`.* 
                                    from `kontrakkinerja` right join `user` using (`nip`) 
                                    join `indikatorkinerjautama` using (`id_kontrak`)
                                    join `logbook` using (`id_iku`) where `is_sent` = 1 and `user`.nip = $role ");
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
