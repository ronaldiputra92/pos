<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prestasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Karyawan_m');
    }
    public function index()
    {
        $data = array(
            'title'    => 'Index Prestasi',
            'user'     => infoLogin(),
            'toko'     => $this->db->get('profil_perusahaan')->row(),
            'content'  => 'prestasi/index',
            'prestasi' => $this->Karyawan_m->getPrestasiToday(),
            'banyaknya' => $this->Karyawan_m->banyakServisToday(),
        );
        $this->load->view('templates/main', $data);
    }
}
