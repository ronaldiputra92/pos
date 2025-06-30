<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grafik extends CI_Controller
{
  public function __construct()
  {

    parent::__construct();
    cek_login();
    cek_user();
  }
  public function index()
  {
    $kategori = "SELECT b.kategori, COUNT(a.id_kategori) AS jml_kategori
    FROM barang a, kategori b WHERE a.id_kategori = b.id_kategori GROUP BY a.id_kategori";

    $kas = "SELECT jenis, SUM(nominal) AS jumlah FROM kas GROUP BY jenis";
    $pendapatan = "SELECT SUBSTRING(a.tgl, 1, 10) AS tgl, SUM(b.subtotal) AS total FROM penjualan a, detil_penjualan b WHERE a.is_active = 1 AND b.id_jual = a.id_jual AND SUBSTRING(tgl, 6, 2) = DATE_FORMAT(CURDATE(), '%m') GROUP BY SUBSTRING(a.tgl, 1, 10)";

    $sqllaris = "SELECT b.nama_barang, COUNT(a.id_barang) AS total FROM detil_penjualan a,barang b, penjualan c
    WHERE  b.id_barang = a.id_barang AND c.id_jual = a.id_jual AND SUBSTRING(tgl, 6, 2) = DATE_FORMAT(CURDATE(), '%m') GROUP BY a.id_barang ORDER BY total DESC LIMIT 10";

    // $sql_pelanggan = "SELECT b.nama_cs, a.kode_jual, SUM(c.subtotal) AS rata_rata FROM penjualan a, customer b, detil_penjualan c WHERE a.id_cs = b.id_cs AND a.id_jual = c.id_jual GROUP BY a.id_cs";

    $data = array(
      'title'     => 'Grafik',
      'user'     => infoLogin(),
      'toko'     => $this->db->get('profil_perusahaan')->row(),
      'kategori' => $this->model->General($kategori)->result(),
      'kas'      => $this->model->General($kas)->result(),
      'pendapatan' =>  $this->model->General($pendapatan)->result(),
      'terlaris' =>  $this->model->General($sqllaris)->result(),
      'content'  => 'grafik/index',
      // 'pelanggan' => $this->model->General($sql_pelanggan)->result(),

    );
    $this->load->view('templates/main', $data);
  }
}
