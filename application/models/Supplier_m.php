<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_m extends CI_Model
{

   protected $table = 'supplier';
   protected $primary = 'id_supplier';

   public function getAllData()
   {
      return $this->db->get($this->table)->result_array();
   }

   public function Save()
   {
      $this->db->select("RIGHT (supplier.kode_supplier, 5) as kode_supplier", false);
      $this->db->order_by("kode_supplier", "DESC");
      $this->db->limit(1);
      $query = $this->db->get('supplier');

      if ($query->num_rows() <> 0) {
         $data = $query->row();
         $kode = intval($data->kode_supplier) + 1;
      } else {
         $kode = 1;
      }
      $kodesup = str_pad($kode, 5, "0", STR_PAD_LEFT);
      $kodesupplier = "SP-" . $kodesup;
      $data = array(
         'kode_supplier'   => $kodesupplier,
         'nama_supplier'   => htmlspecialchars($this->input->post('namasup'), true),
         'alamat_supplier' => htmlspecialchars($this->input->post('alamatsup'), true),
         'telp_supplier'   => htmlspecialchars($this->input->post('telpsup'), true),
         'fax_supplier'    => htmlspecialchars($this->input->post('faxsup'), true),
         'email_supplier'   => htmlspecialchars($this->input->post('emailsup'), true),
         'bank'            => htmlspecialchars($this->input->post('banksup'), true),
         'rekening'         => htmlspecialchars($this->input->post('reksup'), true),
         'atas_nama'         => htmlspecialchars($this->input->post('atasnamasup'), true),
      );
      return $this->db->insert($this->table, $data);
   }

   public function Edit()
   {
      $id = $this->input->post('idsupplier');
      $data = array(
         'nama_supplier'   => htmlspecialchars($this->input->post('editnamasup'), true),
         'alamat_supplier' => htmlspecialchars($this->input->post('editalamatsup'), true),
         'telp_supplier'    => htmlspecialchars($this->input->post('edittelpsup'), true),
         'fax_supplier'    => htmlspecialchars($this->input->post('editfaxsup'), true),
         'email_supplier'    => htmlspecialchars($this->input->post('editemailsup'), true),
         'bank'             => htmlspecialchars($this->input->post('editbanksup'), true),
         'rekening'          => htmlspecialchars($this->input->post('editreksup'), true),
         'atas_nama'       => htmlspecialchars($this->input->post('editatasnamasup'), true),
      );
      return $this->db->set($data)->where($this->primary, $id)->update($this->table);
   }

   public function Delete($id)
   {
      return $this->db->where($this->primary, $id)->delete($this->table);
   }

   public function Detail($id)
   {
      return $this->db->get_where($this->table, [$this->primary => $id])->row_array();
   }
   public function cekDelete($id)
   {
      $sql = "SELECT a.id_supplier, b.id_beli FROM supplier a, pembelian b WHERE a.id_supplier = b.id_supplier AND a.id_supplier = '$id' GROUP BY a.id_supplier";
      $result = $this->db->query($sql)->row_array();
      if ($result['id_supplier'] == NULL and $result['id_beli'] == NULL) {
         return array('num' => 0);
      } else {
         return array('num' => 1);
      }
   }
}
