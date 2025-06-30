<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang_m extends CI_Model
{

    protected $table = 'barang';
    protected $primary = 'id_barang';

    public function getAllData()
    {
        $sql = "SELECT a.id_barang, a.kode_barang, a.gambar, a.barcode ,a.nama_barang, b.satuan, c.kategori, a.harga_beli, 
        a.harga_jual, a.stok FROM barang a LEFT JOIN satuan b ON b.id_satuan = a.id_satuan LEFT JOIN kategori c ON c.id_kategori = a.id_kategori WHERE a.is_active = 1";
        return $this->db->query($sql)->result_array();
    }

    public function Save()
    {
        $this->db->select("RIGHT (barang.kode_barang, 5) as kode_barang", false);
        $this->db->order_by("kode_barang", "DESC");
        $this->db->limit(1);
        $query = $this->db->get('barang');

        if ($query->num_rows() <> 0) {
            $data = $query->row();
            $kode = intval($data->kode_barang) + 1;
        } else {
            $kode = 1;
        }
        $kodebrg = str_pad($kode, 5, "0", STR_PAD_LEFT);
        $kodebarang = "BRG-" . $kodebrg;

        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['max_size'] = '2048';
        $config['upload_path'] = './assets/img/produk/';

        $this->load->library('upload', $config);
        $data = array(
            'kode_barang'      => $kodebarang,
            'barcode'          => htmlspecialchars($this->input->post('barcode') == null ? '-' : $this->input->post('barcode'), true),
            'nama_barang'      => htmlspecialchars($this->input->post('namabarang'), true),
            'gambar'           => htmlspecialchars(!$this->upload->do_upload('gambar') ? 'default.jpg' : $this->upload->data('file_name'), true),
            'id_kategori'      => htmlspecialchars($this->input->post('kategori'), true),
            'id_satuan'        => htmlspecialchars($this->input->post('satuan'), true),
            'id_supplier'      => htmlspecialchars($this->input->post('supplier'), true),
            'harga_beli'       => htmlspecialchars($this->input->post('beli'), true),
            'harga_jual'       => htmlspecialchars($this->input->post('jual'), true),
            'harga_pelanggan'  => htmlspecialchars($this->input->post('pelanggan'), true),
            'harga_toko'       => htmlspecialchars($this->input->post('toko'), true),
            'harga_sales'      => htmlspecialchars($this->input->post('sales'), true),
            'stok'             => 0,
            'is_active'        => 1,
        );
        $this->db->insert($this->table, $data);
        $barang = $this->db->order_by('id_barang', 'DESC')->get('barang', 1)->row();

        $gudang = [
            'id_barang' => $barang->id_barang,
            'stok' => 0
        ];
        $this->db->insert('gudang', $gudang);
    }

    public function Edit()
    {
        $id = $this->input->post('iditem');
        $data = $this->db->get_where($this->table, [$this->primary => $id])->row();

        $config['allowed_types'] = 'jpeg|jpg|png';
        $config['max_size'] = '2048';
        $config['upload_path'] = './assets/img/produk/';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('gambar')) {
            $data = array(
                'barcode'          => htmlspecialchars($this->input->post('barcode') == null ? '-' : $this->input->post('barcode'), true),
                'nama_barang'      => htmlspecialchars($this->input->post('namabarang'), true),
                'id_kategori'      => htmlspecialchars($this->input->post('kategori'), true),
                'id_satuan'        => htmlspecialchars($this->input->post('satuan'), true),
                'id_supplier'      => htmlspecialchars($this->input->post('supplier'), true),
                'harga_beli'       => htmlspecialchars($this->input->post('beli'), true),
                'harga_jual'       => htmlspecialchars($this->input->post('jual'), true),
                'harga_pelanggan'  => htmlspecialchars($this->input->post('pelanggan'), true),
                'harga_toko'       => htmlspecialchars($this->input->post('toko'), true),
                'harga_sales'      => htmlspecialchars($this->input->post('sales'), true),
            );
        } else {
            if ($data->gambar !== 'default.jpg') {
                unlink('assets/img/produk/' . $data->gambar);
            }
            $data = array(
                'barcode'          => htmlspecialchars($this->input->post('barcode') == null ? '-' : $this->input->post('barcode'), true),
                'nama_barang'      => htmlspecialchars($this->input->post('namabarang'), true),
                'gambar'           => htmlspecialchars($this->upload->data('file_name'), true),
                'id_kategori'      => htmlspecialchars($this->input->post('kategori'), true),
                'id_satuan'        => htmlspecialchars($this->input->post('satuan'), true),
                'id_supplier'      => htmlspecialchars($this->input->post('supplier'), true),
                'harga_beli'       => htmlspecialchars($this->input->post('beli'), true),
                'harga_jual'       => htmlspecialchars($this->input->post('jual'), true),
                'harga_pelanggan'  => htmlspecialchars($this->input->post('pelanggan'), true),
                'harga_toko'       => htmlspecialchars($this->input->post('toko'), true),
                'harga_sales'      => htmlspecialchars($this->input->post('sales'), true),
            );
        }
        return $this->db->set($data)->where($this->primary, $id)->update($this->table);
    }

    public function Delete($id)
    {
        $data = $this->db->get_where($this->table, [$this->primary => $id])->row();
        if ($data->gambar !== 'default.jpg') {
            unlink('assets/img/produk/' . $data->gambar);
        }
        $this->db->set(array('is_active' => 0))->where($this->primary, $id)->update($this->table);
        $this->db->where('id_barang', $id)->delete('gudang');
    }

    public function Detail($id)
    {
        return $this->db->get_where($this->table, [$this->primary => $id])->row_array();
    }

    public function Search($key)
    {
        return $this->db->get_where($this->table, ['barcode' => $key])->row_array();
    }

    public function getStokHabis()
    {
        $sql = "SELECT a.id_barang, a.kode_barang, a.barcode ,a.nama_barang, b.satuan, c.kategori,
        a.stok FROM barang a LEFT JOIN satuan b ON b.id_satuan = a.id_satuan LEFT JOIN kategori c ON c.id_kategori = a.id_kategori WHERE a.is_active = 1 AND a.stok < 5 ORDER BY a.stok ASC";
        return $this->db->query($sql)->result_array();
    }
}
