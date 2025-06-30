<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    cek_login();
    cek_user();
    $this->load->model('Barang_m');
  }
  public function index()
  {
    $date = date('Y-m-d');
    $sql = "SELECT a.username, a.nama_lengkap, a.tipe, b.login FROM user a, user_log b WHERE a.id_user  = b.id_user ORDER BY id_log DESC LIMIT 5";

    $kategori = "SELECT b.kategori, COUNT(a.id_kategori) AS jml_kategori FROM barang a, kategori b WHERE a.id_kategori = b.id_kategori GROUP BY a.id_kategori";

    $sqlmasuk = "SELECT SUM(nominal) AS nominal FROM kas WHERE jenis = 'Pemasukan' AND SUBSTRING(tanggal, 1, 10) = '$date'";

    $sqlkeluar = "SELECT SUM(nominal) AS nominal FROM kas WHERE jenis = 'Pengeluaran' AND SUBSTRING(tanggal, 1, 10) = '$date'";

    $pemasukan = implode($this->model->General($sqlmasuk)->row_array());
    $pengeluaran = implode($this->model->General($sqlkeluar)->row_array());

    $pendapatan = "SELECT SUBSTRING(a.tgl, 1, 10) AS tgl, SUM(b.subtotal) AS total FROM penjualan a, detil_penjualan b WHERE a.is_active = 1 AND b.id_jual = a.id_jual AND SUBSTRING(tgl, 6, 2) = DATE_FORMAT(CURDATE(), '%m') GROUP BY SUBSTRING(a.tgl, 1, 10)";

    $sql_jual_today = "SELECT COUNT(id_jual) AS total FROM penjualan WHERE is_active = 1 AND SUBSTRING(tgl ,1, 10) = '$date'";

    if ($pengeluaran == "") {
      $pengeluaran = 0;
    }
    if ($pemasukan == "") {
      $pemasukan = 0;
    }
    $kas = "SELECT jenis, SUM(nominal) AS jumlah FROM kas GROUP BY jenis";


    $data = array(
      'title'      => 'Dashboard',
      'user'       => infoLogin(),
      'toko'       => $this->db->get('profil_perusahaan')->row(),
      'userlog'    => $this->model->General($sql)->result_array(),
      'supplier'   => $this->db->get('supplier')->num_rows(),
      'customer'   => $this->db->get('customer')->num_rows(),
      'barang'     => $this->db->get('barang')->num_rows(),
      'jual'       => $this->db->query($sql_jual_today)->row_array(),
      'kategori'   => $this->model->General($kategori)->result(),
      'pemasukan'  => $pemasukan,
      'pengeluaran' => $pengeluaran,
      'kas'        => $this->model->General($kas)->result(),
      'pendapatan' =>  $this->model->General($pendapatan)->result(),
      'content'    => 'dashboard/index',
      'stok'       => $this->Barang_m->getStokHabis()
    );
    $this->load->view('templates/main', $data);
  }
}
