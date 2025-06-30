<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan_m extends CI_Model
{

    protected $table = 'penjualan';
    protected $primary = 'id_jual';

    public function getAllData()
    {
        return $this->db->get($this->table)->result_array();
    }

    public function getDetilJual()
    {
        $sql = "SELECT a.id_detil_jual, b.barcode, b.nama_barang, b.harga_jual, a.qty_jual, a.diskon, a.subtotal FROM detil_penjualan a, barang b WHERE b.id_barang = a.id_barang AND a.id_jual IS NULL";
        return $this->db->query($sql)->result_array();
    }

    public function getDetilService()
    {
        $sql = "SELECT a.id_detil_jual, b.kode, b.nama_servis, a.harga_item, a.subtotal, a.qty_jual, c.nama_karyawan
        FROM detil_penjualan a, servis b, karyawan c WHERE b.id_servis = a.id_servis AND a.id_karyawan = c.id_karyawan AND a.id_jual IS NULL";
        return $this->db->query($sql)->result_array();
    }

    public function addItem($id, $qty, $subtotal, $harga, $jenis, $pegawai)
    {
        $this->db->select("RIGHT (detil_penjualan.kode_detil_jual, 7) as kode_detil_jual", false);
        $this->db->order_by("kode_detil_jual", "DESC");
        $this->db->limit(1);
        $query = $this->db->get('detil_penjualan');

        if ($query->num_rows() <> 0) {
            $data = $query->row();
            $kode = intval($data->kode_detil_jual) + 1;
        } else {
            $kode = 1;
        }
        $kodedetil = str_pad($kode, 7, "0", STR_PAD_LEFT);
        $detiljual = "DJ-" . $kodedetil;
        if ($jenis == "Produk") {
            $data = array(
                'id_barang'           => $id,
                'id_servis'           => null,
                'id_karyawan'         => null,
                'kode_detil_jual'     => $detiljual,
                'diskon'              => 0,
                'harga_item'          => $harga,
                'qty_jual'            => $qty,
                'subtotal'            => $subtotal,

            );
            $this->db->insert('detil_penjualan', $data);
            $sqlstok = "select stok from barang where id_barang = '$id'";
            $stok = implode($this->db->query($sqlstok)->row_array());
            $hasil = $stok - $qty;
            $update = "update barang set stok = '$hasil' where id_barang = '$id'";
            $this->db->query($update);
        } else if ($jenis == "Servis") {
            $data = array(
                'id_barang'           => null,
                'id_servis'           => $id,
                'id_karyawan'         => $pegawai,
                'kode_detil_jual'     => $detiljual,
                'diskon'              => 0,
                'harga_item'          => $harga,
                'qty_jual'            => $qty,
                'subtotal'            => $subtotal,

            );
            $this->db->insert('detil_penjualan', $data);
        }

        $sql = "SELECT sum(subtotal) as subtotal FROM detil_penjualan WHERE id_jual IS NULL";
        $data = $this->db->query($sql)->row_array();
        echo json_encode($data);
    }

    public function editDetailPenjualan($id, $diskon, $qty, $hakhir)
    {
        $data = array(
            'diskon'     => $diskon,
            'qty_jual'   => $qty,
            'subtotal'   => $hakhir,
        );
        return $this->db->set($data)->where('id_detil_jual', $id)->update('detil_penjualan');
    }

    public function hapusDetail($id)
    {
        $getDetil = $this->db->get_where('detil_penjualan', ['id_detil_jual' => $id])->row_array();
        $qty = $getDetil['qty_jual'];
        $idBrg = $getDetil['id_barang'];

        if ($idBrg != NULL) {

            $getBrg = $this->db->get_where('barang', ['id_barang' => $idBrg])->row_array();
            $stokBrg = $getBrg['stok'];
            $stok = $qty + $stokBrg;
            $updateStok = $this->db->set(array('stok' => $stok))->where('id_barang', $idBrg)->update('barang');
        }
        $sql = "delete from detil_penjualan where id_detil_jual = '$id'";
        $this->db->query($sql);

        $subtotal = "SELECT sum(subtotal) as subtotal FROM detil_penjualan WHERE id_jual IS NULL";
        $data = $this->db->query($subtotal)->row_array();
        echo json_encode($data);
    }

    public function simpanPenjualan()
    {
        date_default_timezone_set('Asia/Jakarta');
        $kodeinvoice = "POS" . date('YmdHis');

        $this->db->select("RIGHT (penjualan.kode_jual, 7) as kode_jual", false);
        $this->db->order_by("kode_jual", "DESC");
        $this->db->limit(1);
        $query = $this->db->get('penjualan');

        if ($query->num_rows() <> 0) {
            $data = $query->row();
            $kode = intval($data->kode_jual) + 1;
        } else {
            $kode = 1;
        }
        $kodejual = str_pad($kode, 7, "0", STR_PAD_LEFT);
        $kodepenjualan = "KJ-" . $kodejual;
        $kembalian = $this->input->post('kembali');
        $bayar = $this->input->post('bayar');
        $metode = $this->input->post('metode');

        if ($kembalian < 0) {
            $kembalian = 0;
        }

        $data = array(
            'id_user'     => $this->input->post('kasir'),
            'id_cs'       => $this->input->post('cus'),
            'kode_jual'   => $kodepenjualan,
            'invoice'     => $kodeinvoice,
            'method'      => $metode,
            'bayar'       => $bayar,
            'kembali'     => $kembalian,
            'ppn'         => $this->input->post('nominal_ppn'),
            'tgl'         => date('Y-m-d H:i:s'),
            'is_active'   => 1,

        );
        $this->db->insert('penjualan', $data);

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
        $nominal = $bayar - $kembalian;
        $kas = array(
            'id_user'     => $this->input->post('kasir'),
            'kode_kas'    => $kodekas,
            'tanggal'     => date('Y-m-d H:i:s'),
            'jenis'       => 'Pemasukan',
            'keterangan'  => 'Penjualan',
            'nominal'     => $nominal,
        );

        $this->db->insert('kas', $kas);
        if ($this->input->post('nominal_ppn') != 0) {

            $this->db->select("RIGHT (pajak_ppn.kode_pajak, 7) as kode_pajak", false);
            $this->db->order_by("kode_pajak", "DESC");
            $this->db->limit(1);
            $query = $this->db->get('pajak_ppn');
            $pajak_ppn = $this->input->post('nominal_ppn');

            if ($query->num_rows() <> 0) {
                $data = $query->row();
                $kode = intval($data->kode_pajak) + 1;
            } else {
                $kode = 1;
            }
            $kodeppn = str_pad($kode, 7, "0", STR_PAD_LEFT);
            $kode_pajak = "PPN-" . $kodeppn;
            $ppn = array(
                'kode_pajak' => $kode_pajak,
                'jenis'      => 'PPN Keluaran',
                'nominal'    => $pajak_ppn,
                'tanggal'    => date('Y-m-d H:i:s'),
                'keterangan' => 'Penjualan',
                'id_user'    => $this->input->post('kasir'),
            );

            $this->db->insert('pajak_ppn', $ppn);
        }

        $idjual = "select max(id_jual) as id_jual from penjualan";
        $id = implode($this->model->General($idjual)->row_array());
        $sql = "update detil_penjualan set id_jual = '$id' where id_jual is null";
        $this->db->query($sql);
        $kembali = $this->input->post('kembali');

        if ($kembali < 0 || $metode == "Kredit") {
            $jml_piutang = abs($kembali);
            $piutang = array(
                'id_jual'        => $id,
                'tgl_piutang'    => date('Y-m-d H:i:s'),
                'jml_piutang'    => $jml_piutang,
                'bayar'          => 0,
                'sisa'           => $jml_piutang,
                'status'         => 'Belum Lunas',
                'jatuh_tempo'    => $this->input->post('tempo')
            );
            $this->db->insert('piutang', $piutang);
        }
    }

    public function detilItemJual($id)
    {
        $sql = "SELECT a.id_detil_jual, b.barcode, b.id_barang, b.nama_barang, b.harga_jual, a.qty_jual, a.diskon, 
		a.subtotal FROM detil_penjualan a, barang b WHERE b.id_barang = a.id_barang AND a.id_detil_jual = '$id'";
        $data = $this->model->General($sql)->row_array();
        echo json_encode($data);
    }
    public function detilServisJual($id)
    {
        $sql = "SELECT a.id_detil_jual, b.kode, b.id_servis, b.nama_servis, a.harga_item, a.qty_jual, 
		a.subtotal FROM detil_penjualan a, servis b WHERE b.id_servis = a.id_servis AND a.id_detil_jual = '$id'";
        $data = $this->model->General($sql)->row_array();
        echo json_encode($data);
    }

    public function editServisJual($id, $harga, $subtotal)
    {
        $data = array(
            'harga_item' => $harga,
            'subtotal'   => $subtotal,
        );
        return $this->db->set($data)->where('id_detil_jual', $id)->update('detil_penjualan');
    }
}
