<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{

    //Ambil data user
    public function getAllUsers()
    {
        $query =  $this->db->query("SELECT `user`.id, `user`.nama, `user`.nip, `user`.pangkat, `user`.`role_id`, `user`.`seksi`, `user`.`atasan`,`user`.`telegram`, `user_role`.* 
                                    FROM `user` JOIN `user_role` ON `user`.`role_id` = `user_role`.`id_role` ORDER BY `user`.`role_id` ASC");

        return $query->result_array();
    }

    // Ambil status data login
    public function getLoggedUser($loggedNIP)
    {
        $query = $this->db->query(" SELECT `user`.id, `user`.nama, `user`.nip, `user`.pangkat, `user`.`role_id`, `user`.`seksi`, `user`.`atasan`,`user`.`telegram`, `user_role`.* 
                                    FROM `user` JOIN `user_role` ON `user`.`role_id` = `user_role`.`id_role`
                                    WHERE `user`.`nip` = '$loggedNIP'");

        return $query->row_array();
    }

    // Ambil data pejabat
    public function getAllPejabat()
    {
        $query = $this->db->query("SELECT `user`.nama, `user`.`role_id`, `user`.`seksi`, `user_role`.* 
                                    FROM `user` JOIN `user_role` ON `user`.`role_id` = `user_role`.`id_role`
                                    WHERE `user`.`role_id` >= 2 AND `user`.`role_id` <=4 ORDER BY `user`.`role_id` ASC");

        return $query->result_array();
    }

    //Ammbil data pangkat
    public function getPangkat()
    {
        return $this->db->get('pangkat')->result_array();
    }

    //Ambil data role
    public function getRole()
    {
        return $this->db->get('user_role')->result_array();
    }

    //Ambil data seksi
    public function getSeksi()
    {
        return $this->db->get('seksi/subseksi')->result_array();
    }

    //Ambil data user by ID
    public function getUserByID($id)
    {
        return $this->db->get_where('user', ['id' => $id])->row_array();
    }

    //Hitung jumlah User
    public function countUser()
    {
        $query = $this->db->get('user');
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    //Hitung jumlah KK
    public function countKK()
    {
        $query = $this->db->get('kontrakkinerja');
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    //Hitung jumlah IKU
    public function countIKU()
    {
        $query = $this->db->get('indikatorkinerjautama');
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    //Hitung jumlah logbook
    public function countLogbook()
    {
        $query = $this->db->get('logbook');
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            return 0;
        }
    }

    //Rekam User
    public function tambahUser()
    {
        $data = [
            'nama' => $this->input->post('namaPegawai', true),
            'nip' => $this->input->post('nipPegawai', true),
            'pangkat' => $this->input->post('pangkatPegawai'),
            'password' => md5(123456),
            'role_id' => $this->input->post('levelPegawai'),
            'seksi' => $this->input->post('organisasiPegawai'),
            'atasan' => $this->input->post('atasanPegawai'),
            'telegram' => $this->input->post('telegramPegawai', true),

        ];
        return $this->db->insert('user', $data);
    }

    //Hapus User
    public function deleteUser($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user');
    }

    //Edit User
    public function editUser($id)
    {

        $id = $this->uri->segment(3);

        $data = [
            'nama' => $this->input->post('nama', true),
            'nip' => $this->input->post('nip', true),
            'pangkat' => $this->input->post('pangkat'),
            'role_id' => $this->input->post('role'),
            'seksi' => $this->input->post('seksisub'),
            'atasan' => $this->input->post('atasan'),
            'telegram' => $this->input->post('telegram', true),

        ];
        $this->db->where('id', $id);
        $this->db->update('user', $data);
    }

    //query pegawai yang belum divalidasi logbooknya
    public function pegawainotvalidatedlogbook()
    {
        $query = $this->db->query("SELECT `logbook`.periode, `logbook`.is_sent, `logbook`.is_approved, `user`.`nama`,
                                    count(nippegawai) as total
                                    from logbook 
                                    join user on `logbook`.`nippegawai`=`user`.`nip`
                                    where `is_sent` = 1 and `is_approved` = 0 
                                    GROUP BY `nippegawai`, `periode` ORDER BY `periode`");

        return $query->result_array();
    }

    //query filter data pegawai yang belum divalidasi logbooknya
    public function filternotvalidatedlogbook($periode)
    {
        $query = $this->db->query("SELECT `logbook`.*, `user`.`nama`,
                                    count(nippegawai) as total
                                    from logbook 
                                    join user on `logbook`.`nippegawai`=`user`.`nip`
                                    where `is_sent` = 1 and `is_approved` = 0 and `periode` = '$periode'
                                    GROUP BY `nippegawai`, `periode` ORDER BY `periode`");

        return $query->result_array();
        return $query->num_rows();
    }

    //query ambil data pegawai yang sudah punya logbook
    public function logbookclear()
    {
        $query = $this->db->query("SELECT `logbook`.*, `user`.`nama`,
                                    count(nippegawai) as total
                                    from logbook 
                                    join user on `logbook`.`nippegawai`=`user`.`nip`
                                    where `is_sent` = 1 and `is_approved` = 1 
                                    group by `nippegawai`, `periode` Order BY `periode` ");

        return $query->result_array();
    }

    //query filter data pegawai yang sudah punya logbook bulan ini
    public function filterlogbookclear($periode)
    {
        $query = $this->db->query("SELECT `logbook`.*, `user`.`nama`,
                                    count(nippegawai) as total
                                    from logbook 
                                    join user on `logbook`.`nippegawai`=`user`.`nip`
                                    where `is_sent` = 1 and `is_approved` = 1 and `periode` =  '$periode'
                                    group by `nippegawai`, `periode` Order BY `periode` ");

        return $query->result_array();
    }

    public function getLogData()
    {
        return $this->db->get('tabel_log')->result_array();
    }

    public function insertPengumuman()
    {
        $alert =
            [
                'datapengumuman' => $this->input->post('contentisi', true),
                'tglrekam' => date("Y-m-d H:i:s")
            ];

        $this->db->insert('pengumuman', $alert);
    }

    public function deletePengumuman($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('pengumuman');
    }

    public function detaillogbookdatadisetujui($nama, $periode)
    {
        $query = $this->db->query("SELECT `user`.nama, `user`.nip, `indikatorkinerjautama`.id_iku, `indikatorkinerjautama`.kodeiku, `indikatorkinerjautama`.namaiku, `indikatorkinerjautama`.nip, `logbook`.*
        FROM `user` JOIN `indikatorkinerjautama` USING (nip)
        JOIN `logbook` using (id_iku) WHERE `logbook`.is_approved = 1 
        AND `user`.nama = '$nama' AND `logbook`.periode = '$periode'");

        return $query->result_array();
    }

    public function detaillogbookdatabelumdisetujui($nama, $periode)
    {
        $query = $this->db->query("SELECT `user`.nama, `user`.nip, `indikatorkinerjautama`.id_iku, `indikatorkinerjautama`.kodeiku, `indikatorkinerjautama`.namaiku, `indikatorkinerjautama`.nip, `logbook`.*
        FROM `user` JOIN `indikatorkinerjautama` USING (nip)
        JOIN `logbook` using (id_iku) WHERE `logbook`.is_approved = 0
        AND `user`.nama = '$nama' AND `logbook`.periode = '$periode'");

        return $query->result_array();
    }

    public function getAllLogbook()
    {
        return $this->db->get('logbook')->result_array();
    }
}
