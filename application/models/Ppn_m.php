<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ppn_m extends CI_Model
{

    protected $table = "pajak_ppn";
    protected $primary = "id_pajak";

    public function getAllData()
    {
        $sql = "SELECT a.id_pajak, a.kode_pajak, a.jenis, a.nominal, a.tanggal, a.keterangan, b.nama_lengkap
        FROM pajak_ppn a, user b WHERE a.id_user = b.id_user";
        return $this->db->query($sql)->result_array();
    }

    public function getNominal()
    {
        $sql_keluar = "SELECT SUM(nominal) AS nominal FROM pajak_ppn WHERE jenis = 'PPN Keluaran'";
        $sql_setor = "SELECT SUM(nominal) AS nominal FROM pajak_ppn WHERE jenis = 'PPN Disetorkan'";
        $keluar = implode($this->model->General($sql_keluar)->row_array());
        $setor = implode($this->model->General($sql_setor)->row_array());
        if ($keluar == "") {
            $keluar = 0;
        }
        if ($setor == "") {
            $setor = 0;
        }
        $total = $keluar - $setor;
        return $total;
    }

    public function Save()
    {
        $user = infoLogin();
        $this->db->select("RIGHT (pajak_ppn.kode_pajak, 7) as kode_pajak", false);
        $this->db->order_by("kode_pajak", "DESC");
        $this->db->limit(1);
        $query = $this->db->get('pajak_ppn');

        if ($query->num_rows() <> 0) {
            $data = $query->row();
            $kode = intval($data->kode_pajak) + 1;
        } else {
            $kode = 1;
        }
        $kode_pajak = str_pad($kode, 7, "0", STR_PAD_LEFT);
        $kode_ppn = "PPN-" . $kode_pajak;
        $data = array(
            'kode_pajak' => $kode_ppn,
            'jenis'      => 'PPN Disetorkan',
            'nominal'    => htmlspecialchars($this->input->post('nominal_ppn'), true),
            'tanggal'    => date('Y-m-d H:i:s'),
            'keterangan' => htmlspecialchars($this->input->post('keterangan_ppn'), true),
            'id_user'    => $user['id_user'],
        );
        $this->db->insert($this->table, $data);

        $this->db->select("RIGHT (kas.kode_kas, 7) as kode_kas", false);
        $this->db->order_by("kode_kas", "DESC");
        $this->db->limit(1);
        $query = $this->db->get('kas');

        if ($query->num_rows() <> 0) {
            $data = $query->row();
            $kode = intval($data->kode_kas) + 1;
        } else {
            $kode = 1;
        }
        $kodeks = str_pad($kode, 7, "0", STR_PAD_LEFT);
        $kodekas = "KS-" . $kodeks;
        $kas = array(
            'id_user'     => $user['id_user'],
            'kode_kas'    => $kodekas,
            'tanggal'     => date('Y-m-d H:i:s'),
            'jenis'       => 'Pengeluaran',
            'keterangan'  => 'PPN Disetorkan',
            'nominal'     => htmlspecialchars($this->input->post('nominal_ppn'), true),
        );
        $this->db->insert('kas', $kas);
    }

    public function Detail($id)
    {
        return $this->db->get_where($this->table, [$this->primary => $id])->row_array();
    }

    // public function Edit()
    // {
    //     $id = $this->input->post('id_ppn');
    //     $data = array(
    //         'jenis'      => htmlspecialchars($this->input->post('jenis_ppn'), true),
    //         'nominal'    => htmlspecialchars($this->input->post('nominal_ppn'), true),
    //         'tanggal'    => date('Y-m-d H:i:s'),
    //         'keterangan' => htmlspecialchars($this->input->post('keterangan_ppn'), true),
    //     );
    //     return $this->db->set($data)->where($this->primary, $id)->update($this->table);
    // }

    public function Delete($id)
    {

        $detail = $this->db->get_where($this->table, ['id_pajak' => $id])->row();
        $user = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $this->db->select("RIGHT (kas.kode_kas, 7) as kode_kas", false);
        $this->db->order_by("kode_kas", "DESC");
        $this->db->limit(1);
        $query = $this->db->get('kas');

        if ($query->num_rows() <> 0) {
            $data = $query->row();
            $kode = intval($data->kode_kas) + 1;
        } else {
            $kode = 1;
        }
        $kodeks = str_pad($kode, 7, "0", STR_PAD_LEFT);
        $kodekas = "KS-" . $kodeks;
        $kas = array(
            'id_user'     => $user['id_user'],
            'kode_kas'    => $kodekas,
            'tanggal'     => date('Y-m-d H:i:s'),
            'jenis'       => 'Pemasukan',
            'keterangan'  => 'PPN Disetorkan Dengan Kode ' . $detail->kode_pajak . ' Telah Dihapus',
            'nominal'     => $detail->nominal,
        );
        $this->db->insert('kas', $kas);
        $this->db->where($this->primary, $id)->delete($this->table);
    }
}
