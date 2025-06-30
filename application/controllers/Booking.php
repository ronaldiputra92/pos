<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Booking extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Booking_m');
        $this->load->model('Karyawan_m');
        $this->load->library('form_validation');

        // Check if booking table exists
        $this->checkBookingTable();
    }

    // Halaman booking online untuk customer
    public function index()
    {
        $data = array(
            'title' => 'Booking Online - Studio Photo',
            'studios' => $this->getStudioList(),
            'karyawan' => $this->Karyawan_m->getAllData()
        );
        $this->load->view('booking/online_booking', $data);
    }

    // Admin panel untuk melihat booking
    public function admin()
    {
        // cek_login();
        $data = array(
            'title' => 'Manajemen Booking',
            'user' => infoLogin(),
            'toko' => $this->db->get('profil_perusahaan')->row(),
            'booking' => $this->Booking_m->getAllBookingWithEmployee(),
            'karyawan' => $this->Karyawan_m->getAllData(),
            'content' => 'booking/admin_booking',

        );
        $this->load->view('templates/main', $data);
    }

    // Proses booking online
    public function process_booking()
    {
        // Set validation rules
        $this->form_validation->set_rules('nama_customer', 'Nama Customer', 'required');
        $this->form_validation->set_rules('telp_customer', 'No. Telepon', 'required');
        $this->form_validation->set_rules('email_customer', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('id_karyawan', 'Karyawan', 'required');
        $this->form_validation->set_rules('studio_id', 'Studio', 'required');
        $this->form_validation->set_rules('tanggal_booking', 'Tanggal Booking', 'required');
        $this->form_validation->set_rules('jam_booking', 'Jam Booking', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('booking');
        } else {
            try {
                $tanggal = $this->input->post('tanggal_booking');
                $jam = $this->input->post('jam_booking');
                $studio_id = $this->input->post('studio_id');

                // Cek apakah jadwal sudah terisi
                if ($this->Booking_m->checkAvailability($tanggal, $jam, $studio_id)) {
                    $this->session->set_flashdata('error', 'Maaf, jadwal yang Anda pilih sudah terisi. Silakan pilih jadwal lain.');
                    redirect('booking');
                } else {
                    $booking_id = $this->Booking_m->saveBooking();
                    if ($booking_id) {
                        $this->session->set_flashdata('success', 'Booking berhasil! Kode booking Anda: ' . $booking_id);
                        redirect('booking/success/' . $booking_id);
                    } else {
                        // Log database error
                        log_message('error', 'Database error in saveBooking: ' . $this->db->error()['message']);
                        $this->session->set_flashdata('error', 'Terjadi kesalahan saat menyimpan booking. Silakan coba lagi.');
                        redirect('booking');
                    }
                }
            } catch (Exception $e) {
                log_message('error', 'Exception in process_booking: ' . $e->getMessage());
                $this->session->set_flashdata('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
                redirect('booking');
            }
        }
    }

    // Cek ketersediaan jadwal via AJAX
    public function check_availability()
    {
        $tanggal = $this->input->post('tanggal');
        $jam = $this->input->post('jam');
        $studio_id = $this->input->post('studio_id');

        $available = !$this->Booking_m->checkAvailability($tanggal, $jam, $studio_id);

        echo json_encode(['available' => $available]);
    }

    // Halaman sukses booking
    public function success($booking_id)
    {
        $booking = $this->Booking_m->getBookingById($booking_id);
        if (!$booking) {
            $this->session->set_flashdata('error', 'Booking tidak ditemukan.');
            redirect('booking');
        }

        $data = array(
            'title' => 'Booking Berhasil',
            'booking' => $booking,
            'toko' => $this->db->get('profil_perusahaan')->row()
        );
        $this->load->view('booking/success', $data);
    }

    // Print tiket booking
    public function print_ticket($booking_id)
    {
        $booking = $this->Booking_m->getBookingById($booking_id);
        if (!$booking) {
            show_404();
        }

        $data = array(
            'title' => 'Tiket Booking',
            'booking' => $booking,
            'toko' => $this->db->get('profil_perusahaan')->row()
        );
        $this->load->view('booking/print_ticket', $data);
    }

    // Get booking data for edit
    public function get_booking_data($booking_id)
    {
        // cek_login();
        $booking = $this->Booking_m->getBookingById($booking_id);
        echo json_encode($booking);
    }

    // Get booking details for view modal
    public function get_booking_details()
    {
        // cek_login();
        $booking_id = $this->input->post('booking_id');

        if (!$booking_id) {
            echo json_encode(['success' => false, 'message' => 'Booking ID tidak ditemukan']);
            return;
        }

        $booking = $this->Booking_m->getBookingById($booking_id);

        if ($booking) {
            echo json_encode(['success' => true, 'data' => $booking]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data booking tidak ditemukan']);
        }
    }

    // Update booking data
    public function update_booking()
    {
        // cek_login();
        $this->form_validation->set_rules('edit_nama_customer', 'Nama Customer', 'required');
        $this->form_validation->set_rules('edit_telp_customer', 'No. Telepon', 'required');
        $this->form_validation->set_rules('edit_email_customer', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('edit_id_karyawan', 'Karyawan', 'required');
        $this->form_validation->set_rules('edit_studio_id', 'Studio', 'required');
        $this->form_validation->set_rules('edit_tanggal_booking', 'Tanggal Booking', 'required');
        $this->form_validation->set_rules('edit_jam_booking', 'Jam Booking', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . validation_errors() . '</div>');
        } else {
            $booking_id = $this->input->post('edit_booking_id');
            $tanggal = $this->input->post('edit_tanggal_booking');
            $jam = $this->input->post('edit_jam_booking');
            $studio_id = $this->input->post('edit_studio_id');

            // Cek apakah jadwal sudah terisi (kecuali booking yang sedang diedit)
            if ($this->Booking_m->checkAvailabilityForEdit($tanggal, $jam, $studio_id, $booking_id)) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Maaf, jadwal yang Anda pilih sudah terisi. Silakan pilih jadwal lain.</div>');
            } else {
                $result = $this->Booking_m->updateBooking();
                if ($result) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success">Booking berhasil diupdate.</div>');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger">Terjadi kesalahan saat mengupdate booking.</div>');
                }
            }
        }
        redirect('booking/admin');
    }

    // Update status booking (admin)
    public function update_status()
    {
        // cek_login();
        $booking_id = $this->input->post('booking_id');
        $status = $this->input->post('status');

        $this->Booking_m->updateStatus($booking_id, $status);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Status booking berhasil diupdate.</div>');
        redirect('booking/admin');
    }

    // Hapus booking (admin)
    public function delete_booking($booking_id)
    {
        // cek_login();
        $this->Booking_m->deleteBooking($booking_id);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Booking berhasil dihapus.</div>');
        redirect('booking/admin');
    }

    // Get available time slots for edit
    public function get_available_slots_edit()
    {
        $tanggal = $this->input->post('tanggal');
        $studio_id = $this->input->post('studio_id');
        $booking_id = $this->input->post('booking_id');

        $slots = $this->Booking_m->getAvailableSlotsForEdit($tanggal, $studio_id, $booking_id);
        echo json_encode($slots);
    }

    // Get available time slots
    public function get_available_slots()
    {
        $tanggal = $this->input->post('tanggal');
        $studio_id = $this->input->post('studio_id');

        $slots = $this->Booking_m->getAvailableSlots($tanggal, $studio_id);
        echo json_encode($slots);
    }

    // Load data untuk DataTables (sama seperti halaman karyawan)
    public function LoadData()
    {
        $data = $this->Booking_m->getAllBookingWithEmployee();
        $result = array();
        
        foreach ($data as $row) {
            $result[] = array(
                'kode_booking' => $row->kode_booking,
                'nama_customer' => $row->nama_customer,
                'telp_customer' => $row->telp_customer,
                'email_customer' => $row->email_customer,
                'nama_karyawan' => $row->nama_karyawan ? $row->nama_karyawan : '-',
                'studio_id' => $row->studio_id,
                'tanggal_booking' => $row->tanggal_booking,
                'jam_booking' => $row->jam_booking,
                'durasi' => $row->durasi,
                'status' => $row->status,
                'catatan' => $row->catatan,
                'created_at' => $row->created_at
            );
        }
        
        $output = array(
            "aaData" => $result
        );
        
        echo json_encode($output);
    }

    // Debug method to test database connection
    public function test_db()
    {
        try {
            // Test database connection
            $this->db->query("SELECT 1");
            echo "Database connection: OK<br>";

            // Test if booking table exists
            $query = $this->db->query("SHOW TABLES LIKE 'booking_online'");
            if ($query->num_rows() > 0) {
                echo "Booking table: EXISTS<br>";

                // Test table structure
                $structure = $this->db->query("DESCRIBE booking_online");
                echo "Table structure:<br>";
                foreach ($structure->result() as $column) {
                    echo "- " . $column->Field . " (" . $column->Type . ")<br>";
                }
            } else {
                echo "Booking table: NOT EXISTS<br>";
                echo "Creating table...<br>";
                $this->createBookingTable();
            }

            // Test karyawan table
            $query = $this->db->query("SHOW TABLES LIKE 'karyawan'");
            if ($query->num_rows() > 0) {
                echo "Karyawan table: EXISTS<br>";
                $count = $this->db->count_all('karyawan');
                echo "Karyawan count: " . $count . "<br>";
            } else {
                echo "Karyawan table: NOT EXISTS<br>";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    private function checkBookingTable()
    {
        try {
            // Check if table exists
            $query = $this->db->query("SHOW TABLES LIKE 'booking_online'");
            if ($query->num_rows() == 0) {
                // Create table if it doesn't exist
                $this->createBookingTable();
            }
        } catch (Exception $e) {
            log_message('error', 'Error checking booking table: ' . $e->getMessage());
        }
    }

    private function createBookingTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `booking_online` (
            `id_booking` int(11) NOT NULL AUTO_INCREMENT,
            `kode_booking` varchar(20) NOT NULL,
            `nama_customer` varchar(100) NOT NULL,
            `telp_customer` varchar(20) NOT NULL,
            `email_customer` varchar(100) NOT NULL,
            `id_karyawan` int(11) DEFAULT NULL,
            `studio_id` varchar(50) NOT NULL,
            `tanggal_booking` date NOT NULL,
            `jam_booking` time NOT NULL,
            `durasi` int(11) DEFAULT 60,
            `catatan` text,
            `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id_booking`),
            UNIQUE KEY `kode_booking` (`kode_booking`),
            KEY `idx_tanggal_jam_studio` (`tanggal_booking`,`jam_booking`,`studio_id`),
            KEY `idx_status` (`status`),
            KEY `fk_booking_karyawan` (`id_karyawan`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        if ($this->db->query($sql)) {
            log_message('info', 'Booking table created successfully');
        } else {
            log_message('error', 'Failed to create booking table: ' . $this->db->error()['message']);
        }
    }

    private function getStudioList()
    {
        return array(
            'Studio 1' => 'Studio 1 (Self Photo)',
            'Studio 2' => 'Studio 2 (Self Photo)',
            'Studio 3' => 'Studio 3 (Wide Photobox)',
            'Studio 4' => 'Studio 4 (Photo Elevator)'
        );
    }
}
