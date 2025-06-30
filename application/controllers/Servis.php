<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Servis extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Servis_m');
    }
    public function index()
    {
        $data = array(
            'title'    => 'Data Servis',
            'user'     => infoLogin(),
            'toko'     => $this->db->get('profil_perusahaan')->row(),
            'content'  => 'servis/index',
            'servis'   => $this->Servis_m->all_data()

        );
        $this->load->view('templates/main', $data);
    }

    public function LoadData()
    {
        $data = array(
            "aaData"    => $this->db->get_where('servis', ['status' => 'Aktif'])->result_array()
        );
        echo json_encode($data);
    }
    public function create()
    {
        $this->Servis_m->Save();
        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button><b>Success!</b> Data Servis berhasil disimpan.</div>');
        redirect('servis');
    }

    public function detail($id = '')
    {
        $data = $this->Servis_m->Detail($id);
        echo json_encode($data);
    }

    public function update()
    {
        $this->Servis_m->Edit();
        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button><b>Success!</b> Data Servis berhasil diubah.</div>');
        redirect('servis');
    }

    public function hapus($id = '')
    {
        $this->Servis_m->Delete($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span> </button><b>Success!</b> Data Servis berhasil dihapus.</div>');
    }

    public function cek_delete($id = '')
    {
        $data = $this->Servis_m->cekDelete($id);
        echo json_encode($data);
    }
}
