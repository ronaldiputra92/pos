<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Servis_m extends CI_Model
{

    protected $table = 'servis';
    protected $primary = 'id_servis';

    public function all_data()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function Save()
    {
        $data = array(
            'kode'          => 'SV' . date('His'),
            'nama_servis'   => htmlspecialchars($this->input->post('servis'), true),
            'harga'         => htmlspecialchars($this->input->post('harga'), true),
            'keterangan'    => htmlspecialchars($this->input->post('keterangan'), true),
            'status'        => htmlspecialchars($this->input->post('status'), true),
        );
        return $this->db->insert($this->table, $data);
    }
    public function Edit()
    {
        $id = $this->input->post('id_servis');
        $data = array(
            'nama_servis'   => htmlspecialchars($this->input->post('servis'), true),
            'harga'         => htmlspecialchars($this->input->post('harga'), true),
            'keterangan'    => htmlspecialchars($this->input->post('keterangan'), true),
            'status'        => htmlspecialchars($this->input->post('status'), true),
        );
        return $this->db->set($data)->where($this->primary, $id)->update($this->table);
    }

    public function Detail($id)
    {
        return $this->db->get_where($this->table, [$this->primary => $id])->row_array();
    }

    public function Delete($id)
    {
        return $this->db->where($this->primary, $id)->delete($this->table);
    }

    public function cekDelete($id)
    {
        $sql = "SELECT b.id_detil_jual, a.id_servis FROM servis a, detil_penjualan b WHERE a.id_servis = b.id_servis AND a.id_servis = '$id' GROUP BY a.id_servis";
        $result = $this->db->query($sql)->row_array();
        if ($result['id_detil_jual'] == NULL and $result['id_servis'] == NULL) {
            return array('num' => 0);
        } else {
            return array('num' => 1);
        }
    }
}
