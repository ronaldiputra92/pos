<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dpenjualan extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    cek_login();
  }
  public function index()
  {
    $data = array(
      'title'    => 'Daftar Penjualan',
      'user'     => infoLogin(),
      'toko'     => $this->db->get('profil_perusahaan')->row(),
      'content'  => 'daftarpenjualan/index',
    );
    $this->load->view('templates/main', $data);
  }
  public function LoadData()
  {
    $sql = "SELECT b.id_jual, b.kode_jual, b.invoice, d.nama_lengkap, c.nama_cs, SUM(a.diskon) diskon, SUM(a.subtotal) as total, b.tgl, SUM(a.qty_jual) AS qty, b.method FROM detil_penjualan a, penjualan b, customer c, user d WHERE b.id_jual = a.id_jual AND c.id_cs = b.id_cs AND d.id_user = b.id_user AND b.is_active= 1 GROUP BY a.id_jual";

    $json = array(
      "aaData"  => $this->model->General($sql)->result_array()
    );
    echo json_encode($json);
  }
  public function detilPenjualan($id = '')
  {
    $sql = "SELECT a.kode_detil_jual, c.barcode, c.nama_barang, a.harga_item, a.qty_jual, a.diskon, a.subtotal FROM detil_penjualan a, penjualan b, barang c WHERE b.id_jual = a.id_jual AND c.id_barang = a.id_barang AND  a.id_jual = '$id'";
    $data = $this->model->General($sql)->result_array();
    echo json_encode($data);
  }
  public function detilPenjualanServis($id = '')
  {
    $sql = "SELECT a.kode_detil_jual, c.kode, c.nama_servis, a.harga_item, a.subtotal, d.nama_karyawan FROM detil_penjualan a, penjualan b, servis c, karyawan d WHERE b.id_jual = a.id_jual AND c.id_servis = a.id_servis AND  a.id_jual = '$id' AND a.id_karyawan = d.id_karyawan";
    $data = $this->model->General($sql)->result_array();
    echo json_encode($data);
  }
}
