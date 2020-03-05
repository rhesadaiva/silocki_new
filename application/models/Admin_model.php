<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{

    //Ambil data user
    public function getAllUsers()
    {
        $this->db->select('user.id, user.nama, user.nip, user.pangkat,user.role_id, user.seksi, user.pejabat_id, user.telegram, 
                           user_role.*, 
                           pejabat.pejabat_id, pejabat.nama_pejabat,
                           seksi_subseksi.*');
        $this->db->from('user');
        $this->db->join('user_role', 'user.role_id = user_role.id_role');
        $this->db->join('pejabat', 'user.pejabat_id = pejabat.pejabat_id');
        $this->db->join('seksi_subseksi', 'user.seksi = seksi_subseksi.id_seksi_subseksi');
        $this->db->order_by('user.role_id', 'ASC');

        $query = $this->db->get();

        return $query->result_array();
    }

    // Ambil data pejabat
    public function getAllPejabat()
    {
        return $this->db->get('pejabat')->result_array();
    }

    //Ambil data pangkat
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
        return $this->db->get('seksi_subseksi')->result_array();
    }

    //Ambil data user by ID
    public function getUserByID($id)
    {
        $this->db->select('user.id, user.nama, user.nip, user.pangkat, user.role_id, user.seksi, user.pejabat_id, user.telegram,
                            user_role.*, pejabat.pejabat_id, pejabat.nama_pejabat, 
                            seksi_subseksi.*');
        $this->db->from('user');
        $this->db->join('user_role', 'user.role_id = user_role.id_role');
        $this->db->join('pejabat', 'user.pejabat_id = pejabat.pejabat_id');
        $this->db->join('seksi_subseksi', 'user.seksi = seksi_subseksi.id_seksi_subseksi');
        $this->db->where('user.id', $id);

        $query = $this->db->get();
        return $query->row_array();
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
            'pejabat_id' => $this->input->post('atasanPegawai'),
            'telegram' => $this->input->post('telegramPegawai', true),
            'img' => 'default.jpg',

        ];
        return $this->db->insert('user', $data);
    }

    //Hapus User
    public function deleteUser($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('user');
    }

    //Update User
    public function updateUser($u)
    {
        $u = $this->input->post('u');
        $data =
            [
                'nama' => $this->input->post('namaPegawai'),
                'nip' => $this->input->post('nipPegawai'),
                'pangkat' => $this->input->post('pangkatPegawai'),
                'role_id' => $this->input->post('roleIDPegawai'),
                'seksi' => $this->input->post('seksiPegawai'),
                'pejabat_id' => $this->input->post('atasanPegawai'),
                'telegram' => $this->input->post('telegramPegawai'),
            ];
        $this->db->where('id', $u);
        $this->db->update('user', $data);
    }

    //query pegawai yang belum divalidasi logbooknya
    public function notValidatedLogbook()
    {
        $query = $this->db->query("SELECT `logbook`.periode, `logbook`.is_sent, `logbook`.is_approved, `user`.`nama`,
                                    count(nippegawai) AS total
                                    FROM logbook 
                                    JOIN user ON `logbook`.`nippegawai`=`user`.`nip`
                                    WHERE `is_sent` = 1 AND `is_approved` = 0 
                                    GROUP BY `nippegawai`, `periode` ORDER BY `nama`");
        return $query->result_array();
    }

    //query filter data pegawai yang belum divalidasi logbooknya
    public function filterNotValidatedLogbook($periode)
    {
        $query = $this->db->query("SELECT `logbook`.*, `user`.`nama`,
                                    count(nippegawai) AS total
                                    FROM logbook 
                                    JOIN user ON `logbook`.`nippegawai`=`user`.`nip`
                                    WHERE `is_sent` = 1 AND `is_approved` = 0 AND `periode` = '$periode'
                                    GROUP BY `nippegawai`, `periode` ORDER BY `nama`");

        return $query->result_array();
        return $query->num_rows();
    }

    //query ambil data pegawai yang sudah punya logbook
    public function validatedLogbook()
    {
        $query = $this->db->query("SELECT `logbook`.*, `user`.`nama`,
                                    count(nippegawai) AS total
                                    FROM logbook 
                                    JOIN user ON `logbook`.`nippegawai`=`user`.`nip`
                                    WHERE `is_sent` = 1 AND `is_approved` = 1 
                                    GROUP BY `nippegawai`, `periode` ORDER BY `nama` ");

        return $query->result_array();
    }

    //query filter data pegawai yang sudah punya logbook bulan ini
    public function filterlogbookclear($periode)
    {
        $query = $this->db->query("SELECT `logbook`.*, `user`.`nama`,
                                    count(nippegawai) AS total
                                    FROM logbook 
                                    JOIN user ON `logbook`.`nippegawai`=`user`.`nip`
                                    WHERE `is_sent` = 1 AND `is_approved` = 1 AND `periode` =  '$periode'
                                    GROUP BY `nippegawai`, `periode` ORDER BY `periode` ");

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

    // Query detail Logbook yang telah disetujui
    public function detailLogbookApproved($nama, $periode)
    {
        $query = $this->db->query("SELECT `user`.nama, `user`.nip, `indikatorkinerjautama`.id_iku, `indikatorkinerjautama`.kodeiku, 
                                    `indikatorkinerjautama`.namaiku, `indikatorkinerjautama`.nip, `logbook`.*
                                    FROM `user` JOIN `indikatorkinerjautama` USING (nip)
                                    JOIN `logbook` using (id_iku) WHERE `logbook`.is_approved = 1 
                                    AND `user`.nama = '$nama' AND `logbook`.periode = '$periode'");

        return $query->result_array();
    }

    // Query detail Logbook yang belum disetujui
    public function detailLogbookUnapproved($nama, $periode)
    {
        $query = $this->db->query("SELECT `user`.nama, `user`.nip, `indikatorkinerjautama`.id_iku, 
                                    `indikatorkinerjautama`.kodeiku, `indikatorkinerjautama`.namaiku, 
                                    `indikatorkinerjautama`.nip, `logbook`.*
                                    FROM `user` JOIN `indikatorkinerjautama` USING (nip)
                                    JOIN `logbook` using (id_iku) WHERE `logbook`.is_approved = 0
                                    AND `user`.nama = '$nama' AND `logbook`.periode = '$periode'");

        return $query->result_array();
    }

    // Ambil semua data Logbook
    public function getAllLogbook()
    {
        return $this->db->get('logbook')->result_array();
    }
}
