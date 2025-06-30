<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Karyawan_m extends CI_Model
{

    protected $table = 'karyawan';
    protected $primary = 'id_karyawan';

    public function getAllData()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function Save()
    {
        $this->db->select("RIGHT (karyawan.kode_karyawan, 5) as kode_karyawan", false);
        $this->db->order_by("kode_karyawan", "DESC");
        $this->db->limit(1);
        $query = $this->db->get('karyawan');

        if ($query->num_rows() <> 0) {
            $data = $query->row();
            $kode = intval($data->kode_karyawan) + 1;
        } else {
            $kode = 1;
        }
        $kodekry = str_pad($kode, 5, "0", STR_PAD_LEFT);
        $kodekaryawan = "K-" . $kodekry;
        $data = array(
            'kode_karyawan'    => $kodekaryawan,
            'nama_karyawan'    => htmlspecialchars($this->input->post('namakaryawan'), true),
            'jenis_kelamin'   => htmlspecialchars($this->input->post('kelamin'), true),
            'telp_karyawan'    => htmlspecialchars($this->input->post('telp'), true),
            'email_karyawan'   => htmlspecialchars($this->input->post('email'), true),
            'status_karyawan'  => htmlspecialchars($this->input->post('status'), true),
            'tmpt_lahir'       => htmlspecialchars($this->input->post('tmptlahir'), true),
            'tgl_lahir'        => htmlspecialchars($this->input->post('tgllahir'), true),
            'tgl_masuk'        => htmlspecialchars($this->input->post('tglmasuk'), true),
            'alamat'           => htmlspecialchars($this->input->post('alamat'), true),

        );
        return $this->db->insert($this->table, $data);
    }

    public function Edit()
    {
        $id = $this->input->post('idkaryawan');
        $data = array(
            'nama_karyawan'    => htmlspecialchars($this->input->post('editnama'), true),
            'jenis_kelamin'    => htmlspecialchars($this->input->post('editkelamin'), true),
            'telp_karyawan'    => htmlspecialchars($this->input->post('edittelp'), true),
            'email_karyawan'   => htmlspecialchars($this->input->post('editemail'), true),
            'status_karyawan'  => htmlspecialchars($this->input->post('editstatus'), true),
            'tmpt_lahir'       => htmlspecialchars($this->input->post('edittmptlahir'), true),
            'tgl_lahir'        => htmlspecialchars($this->input->post('edittgllahir'), true),
            'tgl_masuk'        => htmlspecialchars($this->input->post('edittglmasuk'), true),
            'alamat'           => htmlspecialchars($this->input->post('editalamat'), true),

        );
        return $this->db->set($data)->where($this->primary, $id)->update($this->table);
    }

    public function Delete($id)
    {
        return $this->db->where($this->primary, $id)->delete($this->table);
    }

    public function Detail($id)
    {
        return $this->db->get_where($this->table, [$this->primary => $id])->row_array();
    }

    public function getPrestasiToday()
    {
        $date = date('Y-m-d');
        $sql = "SELECT a.id_karyawan, c.nama_karyawan, SUM(a.subtotal) AS total FROM detil_penjualan a, penjualan b, karyawan c WHERE a.id_karyawan = c.id_karyawan AND a.id_jual = b.id_jual AND SUBSTRING(b.tgl, 1, 10) = '$date' GROUP BY a.id_karyawan ORDER BY total DESC";
        return $this->db->query($sql);
    }

    public function banyakServisToday()
    {
        $date = date('Y-m-d');
        $sql = "SELECT a.id_karyawan, COUNT(a.id_karyawan) AS banyaknya FROM detil_penjualan a, penjualan b
        WHERE a.id_jual = b.id_jual AND SUBSTRING(b.tgl, 1, 10) =  '$date' AND a.id_karyawan IS NOT NULL GROUP BY a.id_karyawan";
        return $this->db->query($sql)->result_array();
    }
}
