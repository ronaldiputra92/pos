<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    cek_login();
    //cek_user();
    $this->load->model('Penjualan_m');
  }
  public function index()
  {
    $data = array(
      'title'    => 'Penjualan',
      'user'     => infoLogin(),
      'customer' => $this->db->get('customer')->result_array(),
      'toko'     => $this->db->get('profil_perusahaan')->row(),
      'content'  => 'penjualan/index',
      'pegawai'  => $this->db->get('karyawan')->result_array()
    );
    $this->load->view('templates/main', $data);
  }

  public function LoadData()
  {
    $json = array(
      "aaData"  => $this->Penjualan_m->getDetilJual()
    );
    echo json_encode($json);
  }

  public function LoadDataService()
  {
    $json = array(
      "aaData"  => $this->Penjualan_m->getDetilService()
    );
    echo json_encode($json);
  }

  public function tambahbarang($id, $qty, $subtotal, $harga, $jenis, $pegawai)
  {

    $this->Penjualan_m->addItem($id, $qty, $subtotal, $harga, $jenis, $pegawai);
  }

  public function detilitemjual($id = '')
  {
    $this->Penjualan_m->detilItemJual($id);
  }

  public function detilservisjual($id = '')
  {
    $this->Penjualan_m->detilServisJual($id);
  }

  public function editdetiljual($id, $diskon, $qty, $hakhir)
  {
    $this->Penjualan_m->editDetailPenjualan($id, $diskon, $qty, $hakhir);
  }

  public function editservisjual($id, $harga, $subtotal)
  {
    $this->Penjualan_m->editServisJual($id, $harga, $subtotal);
  }

  public function hapusdetil($id = '')
  {
    $this->Penjualan_m->hapusDetail($id);
  }

  public function simpanpenjualan()
  {
    $this->Penjualan_m->simpanPenjualan();
    redirect('report/struk_penjualan');
  }

  public function kodeinvoice()
  {
    date_default_timezone_set('Asia/Jakarta');
    $kodeinvoice = "POS" . date('YmdHis');
    echo json_encode($kodeinvoice);
  }

  public function hargatotal()
  {
    $sql = "SELECT SUM(subtotal) AS subtotal, SUM(diskon) as diskon FROM detil_penjualan WHERE id_jual IS NULL";
    $data = $this->model->General($sql)->row_array();
    echo json_encode($data);
  }

  public function detailJual($id = '')
  {
    $data = array(
      'title'    => 'Edit Penjualan',
      'user'     => infoLogin(),
      'customer' => $this->db->get('customer')->result_array(),
      'toko'     => $this->db->get('profil_perusahaan')->row(),
      'content'  => 'penjualan/edit'
    );
    $this->load->view('templates/main', $data);
  }

  // public function dataEdit($id = '')
  // {
  //   $sql = "SELECT b.id_jual, a.id_detil_jual, d.barcode, d.nama_barang, d.harga_jual, a.qty_jual, a.diskon, 
  //   a.subtotal, c.nama_cs FROM detil_penjualan a, penjualan b, customer c, barang d
  //   WHERE b.id_jual = a.id_jual AND c.id_cs = b.id_cs AND d.id_barang = a.id_barang AND b.is_active = 1 AND b.id_jual = '$id'";

  //   $data = $this->model->General($sql)->result_array();
  //   $json = array(
  //     "aaData"  => $data
  //   );
  //   echo json_encode($json);
  // }
}
