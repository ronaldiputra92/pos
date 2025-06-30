<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gudang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Gudang_m');
    }
    public function index()
    {
        $data = array(
            'title'    => 'Gudang',
            'user'     => infoLogin(),
            'content'  => 'gudang/index',
            'toko'     => $this->db->get('profil_perusahaan')->row(),
            'item'       => $this->Gudang_m->getAllData()
        );
        $this->load->view('templates/main', $data);
    }

    public function masuk($id)
    {
        $ids = decrypt_url($id);
        $data = array(
            'title'    => 'Transfer Masuk Stok',
            'user'     => infoLogin(),
            'content'  => 'gudang/form',
            'toko'     => $this->db->get('profil_perusahaan')->row(),
            'action'       => 'gudang/transfer_masuk/' . $id,
            'item'       => $this->Gudang_m->getAllData($ids),
            'type' => 'in'
        );
        $this->load->view('templates/main', $data);
    }
    public function keluar($id)
    {
        $ids = decrypt_url($id);
        $data = array(
            'title'    => 'Transfer Keluar Stok',
            'user'     => infoLogin(),
            'content'  => 'gudang/form',
            'toko'     => $this->db->get('profil_perusahaan')->row(),
            'action'       => 'gudang/transfer_keluar/' . $id,
            'item'       => $this->Gudang_m->getAllData($ids),
            'type' => 'out'
        );
        $this->load->view('templates/main', $data);
    }

    public function transfer_keluar($id)
    {
        $transfer = $this->Gudang_m->transferKeluar($id);
        if ($transfer == true) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button><b>Success!</b> Berhasil melakukan transfer.</div>');
            redirect('gudang');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button><b>Opps!</b> Gagal melakukan transfer.</div>');
            redirect('gudang/keluar/' . $id);
        }
    }
    public function transfer_masuk($id)
    {
        $transfer = $this->Gudang_m->transferMasuk($id);
        if ($transfer == true) {
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button><b>Success!</b> Berhasil melakukan transfer.</div>');
            redirect('gudang');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button><b>Opps!</b> Gagal melakukan transfer.</div>');
            redirect('gudang/masuk/' . $id);
        }
    }
}
