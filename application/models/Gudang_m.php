<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gudang_m extends CI_Model
{

    protected $table = 'gudang';
    protected $primary = 'id_gudang';

    public function getAllData($id = null)
    {
        if ($id == null) {
            $sql = "SELECT b.id_gudang, a.nama_barang, a.barcode, a.gambar, c.satuan, b.stok FROM barang a, gudang b, satuan c WHERE a.id_barang = b.id_barang AND c.id_satuan = a.id_satuan";
            return $this->db->query($sql)->result_array();
        } else {
            $sql = "SELECT b.id_gudang, a.nama_barang, a.barcode, a.gambar, c.satuan, b.stok as stok_gudang, a.stok as stok_toko FROM barang a, gudang b, satuan c WHERE a.id_barang = b.id_barang AND c.id_satuan = a.id_satuan AND b.id_gudang = '$id'";
            return $this->db->query($sql)->row_array();
        }
    }

    public function transferKeluar($id)
    {
        $ids = decrypt_url($id);
        $success = false;

        $getGudang = $this->db->get_where($this->table, [$this->primary => $ids])->row();
        $stokGudang = $this->input->post('stok_gudang');
        $stokToko = $this->input->post('stok_toko');
        $jumlahTransfer = $this->input->post('stok');

        if ($jumlahTransfer <= $stokGudang) {

            $hasilStokGudang = $stokGudang - $jumlahTransfer;
            $this->db->set(['stok' => $hasilStokGudang])->where($this->primary, $ids)->update($this->table);
            $hasilStokToko = $stokToko + $jumlahTransfer;
            $this->db->set(['stok' => $hasilStokToko])->where('id_barang', $getGudang->id_barang)->update('barang');
            $success = true;
        }
        return $success;
    }
    public function transferMasuk($id)
    {
        $ids = decrypt_url($id);
        $success = false;

        $getGudang = $this->db->get_where($this->table, [$this->primary => $ids])->row();
        $stokGudang = $this->input->post('stok_gudang');
        $stokToko = $this->input->post('stok_toko');
        $jumlahTransfer = $this->input->post('stok');

        if ($jumlahTransfer <= $stokToko) {

            $hasilStokGudang = $stokGudang + $jumlahTransfer;
            $this->db->set(['stok' => $hasilStokGudang])->where($this->primary, $ids)->update($this->table);
            $hasilStokToko = $stokToko - $jumlahTransfer;
            $this->db->set(['stok' => $hasilStokToko])->where('id_barang', $getGudang->id_barang)->update('barang');
            $success = true;
        }
        return $success;
    }
}
