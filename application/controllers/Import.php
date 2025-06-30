<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Import extends CI_Controller
{
   public function __construct()
   {

      parent::__construct();
      cek_login();
      cek_user();
      $this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
   }
   public function karyawan()
   {
      if (isset($_FILES["importkar"]["name"])) {
         $path = $_FILES["importkar"]["tmp_name"];
         $object = IOFactory::load($path);

         foreach ($object->getWorksheetIterator() as $worksheet) {

            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            for ($row = 2; $row <= $highestRow; $row++) {
               $kode = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
               $nama = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
               $telp = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
               $email = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
               $status = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
               $tmptLahir = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
               $tglLahir = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
               $tglMasuk = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
               $alamat = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
               $data[] = array(
                  'kode_karyawan'   => $kode,
                  'nama_karyawan'   => $nama,
                  'telp_karyawan'   => $telp,
                  'email_karyawan'  => $email,
                  'status_karyawan' => $status,
                  'tmpt_lahir'      => $tmptLahir,
                  'tgl_lahir'       => $tglLahir,
                  'tgl_masuk'       => $tglMasuk,
                  'alamat'          => $alamat,
               );
            }
         }
         $this->db->insert_batch('karyawan', $data);
         $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button><b>Success!</b> Data Karyawan berhasil diimport.</div>');
         redirect('karyawan');
      }
   }
   public function customer()
   {
      if (isset($_FILES["importcus"]["name"])) {
         $path = $_FILES["importcus"]["tmp_name"];
         $object = IOFactory::load($path);

         foreach ($object->getWorksheetIterator() as $worksheet) {

            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            for ($row = 2; $row <= $highestRow; $row++) {
               $kode = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
               $nama = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
               $jk = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
               $telp = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
               $email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
               $alamat = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
               $jenis = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
               $data[] = array(
                  'kode_cs'         => $kode,
                  'nama_cs'         => $nama,
                  'jenis_kelamin'   => $jk,
                  'telp'            => $telp,
                  'email'           => $email,
                  'alamat'          => $alamat,
                  'jenis_cs'        => $jenis,
               );
            }
         }
         $this->db->insert_batch('customer', $data);
         $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button><b>Success!</b> Data Customer berhasil diimport.</div>');
         redirect('customer');
      }
   }
   public function supplier()
   {
      if (isset($_FILES["importsupp"]["name"])) {
         $path = $_FILES["importsupp"]["tmp_name"];
         $object = IOFactory::load($path);

         foreach ($object->getWorksheetIterator() as $worksheet) {

            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            for ($row = 2; $row <= $highestRow; $row++) {
               $kode = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
               $nama = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
               $alamat = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
               $telp = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
               $fax = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
               $email = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
               $bank = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
               $rekening = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
               $atasNama = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
               $data[] = array(
                  'kode_supplier'    => $kode,
                  'nama_supplier'    => $nama,
                  'alamat_supplier'  => $alamat,
                  'telp_supplier'    => $telp,
                  'fax_supplier'     => $fax,
                  'email_supplier'   => $email,
                  'bank'             => $bank,
                  'rekening'         => $rekening,
                  'atas_nama'        => $atasNama,
               );
            }
         }
         $this->db->insert_batch('supplier', $data);
         $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button><b>Success!</b> Data Supplier berhasil diimport.</div>');
         redirect('supplier');
      }
   }
   public function barang()
   {
      if (isset($_FILES["importbrg"]["name"])) {
         $path = $_FILES["importbrg"]["tmp_name"];
         $object = IOFactory::load($path);

         foreach ($object->getWorksheetIterator() as $worksheet) {

            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            for ($row = 2; $row <= $highestRow; $row++) {
               $kode = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
               $barcode = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
               $nama = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
               $satuan = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
               $beli = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
               $jual = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
               $stok = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
               $sql = "select id_satuan from satuan where satuan = '$satuan'";
               $dataSatuan = implode($this->db->query($sql)->row_array());

               $data = array(
                  'kode_barang'        => $kode,
                  'barcode'            => $barcode,
                  'nama_barang'        => $nama,
                  'gambar'             => 'default.jpg',
                  'id_satuan'          => $dataSatuan,
                  'harga_beli'         => $beli,
                  'harga_jual'         => $jual,
                  'harga_pelanggan'    => $jual,
                  'harga_toko'         => $jual,
                  'harga_sales'        => $jual,
                  'stok'               => $stok,
                  'is_active'          => 1
               );
               $this->db->insert('barang', $data);
               $barang = $this->db->order_by('id_barang', 'DESC')->get('barang', 1)->row();

               $gudang = [
                  'id_barang' => $barang->id_barang,
                  'stok' => $stok
               ];
               $this->db->insert('gudang', $gudang);
            }
         }
         $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button><b>Success!</b> Data Barang berhasil diimport.</div>');
         redirect('barang');
      }
   }
}
