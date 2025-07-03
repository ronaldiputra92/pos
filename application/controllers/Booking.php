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
        $user = infoLogin();

        // Redirect customer ke halaman customer
        if ($user['username'] == 'customer') {
            redirect('booking/customer');
        }

        $data = array(
            'title' => 'Manajemen Booking',
            'user' => $user,
            'toko' => $this->db->get('profil_perusahaan')->row(),
            'booking' => $this->Booking_m->getAllBookingWithEmployee(),
            'karyawan' => $this->Karyawan_m->getAllData(),
            'content' => 'booking/admin_booking',

        );
        $this->load->view('templates/main', $data);
    }

    // Customer panel untuk melihat booking mereka sendiri
    public function customer()
    {
        // cek_login();
        $user = infoLogin();

        // Hanya customer yang bisa akses halaman ini
        if ($user['username'] != 'customer') {
            redirect('booking/admin');
        }

        $data = array(
            'title' => 'Booking Saya',
            'user' => $user,
            'toko' => $this->db->get('profil_perusahaan')->row(),
            'booking' => $this->Booking_m->getBookingByCustomer($user['username']),
            'content' => 'booking/customer_booking',
        );
        $this->load->view('templates/main', $data);
    }

    // Search booking untuk customer
    public function search_customer_booking()
    {
        $search_term = $this->input->post('search_term');

        if (empty($search_term)) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning">Harap masukkan kode booking, nama, atau email untuk pencarian.</div>');
            redirect('booking/customer');
        }

        $data = array(
            'title' => 'Hasil Pencarian Booking',
            'user' => infoLogin(),
            'toko' => $this->db->get('profil_perusahaan')->row(),
            'booking' => $this->Booking_m->searchCustomerBooking($search_term),
            'search_term' => $search_term,
            'content' => 'booking/customer_booking',
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

    // Halaman preview booking (tanpa detail kontak)
    public function preview($booking_id)
    {
        $booking = $this->Booking_m->getBookingById($booking_id);
        if (!$booking) {
            $this->session->set_flashdata('error', 'Booking tidak ditemukan.');
            redirect('booking');
        }

        $data = array(
            'title' => 'Preview Booking',
            'booking' => $booking
        );
        $this->load->view('booking/preview', $data);
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

    // Update payment status booking (admin/kasir)
    public function update_payment_status()
    {
        // Pastikan request via AJAX/POST
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $booking_id = $this->input->post('booking_id');
        $payment_status = $this->input->post('payment_status');

        if (!$booking_id || !$payment_status) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        // Validasi payment status
        $valid_statuses = ['unpaid', 'partial', 'paid'];
        if (!in_array($payment_status, $valid_statuses)) {
            echo json_encode(['success' => false, 'message' => 'Status pembayaran tidak valid']);
            return;
        }

        try {
            // Update ke database
            $this->db->where('kode_booking', $booking_id);
            $update = $this->db->update('booking_online', ['payment_status' => $payment_status]);

            if ($update) {
                echo json_encode(['success' => true, 'message' => 'Status pembayaran berhasil diupdate']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal update database']);
            }
        } catch (Exception $e) {
            log_message('error', 'Error updating payment status: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem']);
        }
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

        $slots = $this->Booking_m->getAllSlotsWithStatus($tanggal, $studio_id);
        echo json_encode($slots);
    }

    // Debug method untuk memeriksa data booking
    public function debug_booking($tanggal = null, $studio_id = null)
    {
        if (!$tanggal) $tanggal = '2025-07-04';
        if (!$studio_id) $studio_id = 'Studio 3';

        echo "<h3>Debug Booking Data</h3>";
        echo "<p><strong>Tanggal:</strong> $tanggal</p>";
        echo "<p><strong>Studio:</strong> $studio_id</p>";

        // Cek data booking yang ada
        $this->db->select('*');
        $this->db->where('tanggal_booking', $tanggal);
        $this->db->where('studio_id', $studio_id);
        $bookings = $this->db->get('booking_online')->result();

        echo "<h4>Data Booking di Database:</h4>";
        if (count($bookings) > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>Kode Booking</th><th>Nama Customer</th><th>Tanggal</th><th>Jam</th><th>Studio</th><th>Status</th><th>Payment Status</th><th>Akan Disilang?</th></tr>";
            foreach ($bookings as $booking) {
                $payment_status = isset($booking->payment_status) ? $booking->payment_status : 'unpaid';
                $will_be_crossed = in_array($payment_status, ['paid', 'partial']) ? 'YA' : 'TIDAK';
                $row_color = in_array($payment_status, ['paid', 'partial']) ? 'background-color: #ffebee;' : 'background-color: #e8f5e8;';
                
                echo "<tr style='$row_color'>";
                echo "<td>{$booking->kode_booking}</td>";
                echo "<td>{$booking->nama_customer}</td>";
                echo "<td>{$booking->tanggal_booking}</td>";
                echo "<td>{$booking->jam_booking}</td>";
                echo "<td>{$booking->studio_id}</td>";
                echo "<td>{$booking->status}</td>";
                echo "<td><strong>{$payment_status}</strong></td>";
                echo "<td><strong>{$will_be_crossed}</strong></td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<p><small>🟢 Hijau = Slot masih tersedia (belum dibayar) | 🔴 Merah = Slot akan disilang (sudah dibayar/sebagian)</small></p>";
        } else {
            echo "<p style='color: red;'>Tidak ada data booking untuk tanggal dan studio tersebut.</p>";
        }

        // Test method getAllSlotsWithStatus
        echo "<h4>Result getAllSlotsWithStatus:</h4>";
        $slots = $this->Booking_m->getAllSlotsWithStatus($tanggal, $studio_id);
        echo "<pre>";
        print_r($slots);
        echo "</pre>";

        // Test query manual
        echo "<h4>Query Manual Test (Hanya Paid/Partial):</h4>";
        $this->db->select('jam_booking, payment_status');
        $this->db->where('tanggal_booking', $tanggal);
        $this->db->where('studio_id', $studio_id);
        $this->db->where_in('status', ['pending', 'confirmed']);
        $this->db->where_in('payment_status', ['paid', 'partial']);
        $booked_slots = $this->db->get('booking_online')->result_array();
        
        echo "<p><strong>Booked slots query result:</strong></p>";
        echo "<pre>";
        print_r($booked_slots);
        echo "</pre>";

        $booked_times = array_column($booked_slots, 'jam_booking');
        echo "<p><strong>Booked times array:</strong></p>";
        echo "<pre>";
        print_r($booked_times);
        echo "</pre>";

        // Normalize format jam
        $booked_times_normalized = array();
        foreach ($booked_times as $time) {
            if (strlen($time) > 5) {
                $booked_times_normalized[] = substr($time, 0, 5);
            } else {
                $booked_times_normalized[] = $time;
            }
        }

        echo "<p><strong>Normalized booked times array:</strong></p>";
        echo "<pre>";
        print_r($booked_times_normalized);
        echo "</pre>";

        // Cek format jam yang tersimpan
        echo "<h4>Format Jam Analysis:</h4>";
        if (count($booked_times) > 0) {
            foreach ($booked_times as $time) {
                echo "<p>Jam tersimpan: '$time' (length: " . strlen($time) . ")</p>";
                echo "<p>Comparison dengan '09:00': " . ($time === '09:00' ? 'MATCH' : 'NO MATCH') . "</p>";
                echo "<p>Comparison dengan '09:00:00': " . ($time === '09:00:00' ? 'MATCH' : 'NO MATCH') . "</p>";
                echo "<hr>";
            }
        }

        // Test in_array dengan berbagai format
        echo "<h4>in_array Test (Original):</h4>";
        $test_times = ['09:00', '09:00:00', '9:00', '9:00:00'];
        foreach ($test_times as $test_time) {
            $result = in_array($test_time, $booked_times);
            echo "<p>in_array('$test_time', booked_times): " . ($result ? 'TRUE' : 'FALSE') . "</p>";
        }

        echo "<h4>in_array Test (Normalized):</h4>";
        foreach ($test_times as $test_time) {
            $result = in_array($test_time, $booked_times_normalized);
            echo "<p>in_array('$test_time', normalized_booked_times): " . ($result ? 'TRUE' : 'FALSE') . "</p>";
        }
    }

    // Test AJAX call untuk get_available_slots
    public function test_ajax_slots()
    {
        echo "<h3>Test AJAX get_available_slots</h3>";
        
        // Simulate POST data
        $_POST['tanggal'] = '2025-07-04';
        $_POST['studio_id'] = 'Studio 3';
        
        echo "<p>Simulating AJAX call with:</p>";
        echo "<p>tanggal: " . $_POST['tanggal'] . "</p>";
        echo "<p>studio_id: " . $_POST['studio_id'] . "</p>";
        
        echo "<h4>Response:</h4>";
        $this->get_available_slots();
    }

    // Test method untuk update payment status
    public function test_payment_status($booking_id = null, $status = null)
    {
        if (!$booking_id || !$status) {
            echo "<h3>Test Payment Status Update</h3>";
            echo "<p>Usage: /booking/test_payment_status/[booking_id]/[status]</p>";
            echo "<p>Status options: unpaid, partial, paid</p>";
            echo "<p>Example: /booking/test_payment_status/BK-000001/paid</p>";
            return;
        }

        $valid_statuses = ['unpaid', 'partial', 'paid'];
        if (!in_array($status, $valid_statuses)) {
            echo "<p style='color: red;'>Invalid status. Use: unpaid, partial, or paid</p>";
            return;
        }

        // Update payment status
        $this->db->where('kode_booking', $booking_id);
        $update = $this->db->update('booking_online', ['payment_status' => $status]);

        if ($update) {
            echo "<h3>Payment Status Updated</h3>";
            echo "<p>Booking ID: <strong>$booking_id</strong></p>";
            echo "<p>New Payment Status: <strong>$status</strong></p>";
            echo "<p style='color: green;'>✅ Update successful!</p>";
            
            // Show current booking data
            $booking = $this->db->get_where('booking_online', ['kode_booking' => $booking_id])->row();
            if ($booking) {
                echo "<h4>Current Booking Data:</h4>";
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr><th>Field</th><th>Value</th></tr>";
                echo "<tr><td>Kode Booking</td><td>{$booking->kode_booking}</td></tr>";
                echo "<tr><td>Nama Customer</td><td>{$booking->nama_customer}</td></tr>";
                echo "<tr><td>Tanggal</td><td>{$booking->tanggal_booking}</td></tr>";
                echo "<tr><td>Jam</td><td>{$booking->jam_booking}</td></tr>";
                echo "<tr><td>Studio</td><td>{$booking->studio_id}</td></tr>";
                echo "<tr><td>Status</td><td>{$booking->status}</td></tr>";
                echo "<tr><td><strong>Payment Status</strong></td><td><strong>{$booking->payment_status}</strong></td></tr>";
                echo "</table>";
            }
        } else {
            echo "<p style='color: red;'>❌ Update failed!</p>";
        }
    }

    // Debug method untuk print ticket
    public function debug_print_ticket($booking_id)
    {
        $booking = $this->Booking_m->getBookingById($booking_id);
        
        echo "<h3>Debug Print Ticket Data</h3>";
        echo "<p><strong>Booking ID:</strong> $booking_id</p>";
        
        if (!$booking) {
            echo "<p style='color: red;'>❌ Booking tidak ditemukan!</p>";
            return;
        }
        
        echo "<h4>Data Booking:</h4>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Field</th><th>Value</th><th>Type</th></tr>";
        
        foreach ($booking as $key => $value) {
            $type = gettype($value);
            $display_value = $value === null ? 'NULL' : $value;
            echo "<tr>";
            echo "<td><strong>$key</strong></td>";
            echo "<td>$display_value</td>";
            echo "<td><em>$type</em></td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h4>Payment Status Analysis:</h4>";
        $payment_status = isset($booking->payment_status) ? $booking->payment_status : 'unpaid';
        echo "<p><strong>payment_status field exists:</strong> " . (isset($booking->payment_status) ? 'YES' : 'NO') . "</p>";
        echo "<p><strong>payment_status value:</strong> '$payment_status'</p>";
        echo "<p><strong>Detected status:</strong> ";
        
        if ($payment_status == 'paid') {
            echo "<span style='color: green; font-weight: bold;'>✓ LUNAS</span>";
        } elseif ($payment_status == 'partial') {
            echo "<span style='color: orange; font-weight: bold;'>◐ SEBAGIAN</span>";
        } else {
            echo "<span style='color: red; font-weight: bold;'>✗ BELUM DIBAYAR</span>";
        }
        echo "</p>";
        
        echo "<h4>Quick Actions:</h4>";
        echo "<p>";
        echo "<a href='" . base_url('booking/test_payment_status/' . $booking_id . '/unpaid') . "' style='margin-right: 10px;'>Set to Unpaid</a> | ";
        echo "<a href='" . base_url('booking/test_payment_status/' . $booking_id . '/partial') . "' style='margin-right: 10px;'>Set to Partial</a> | ";
        echo "<a href='" . base_url('booking/test_payment_status/' . $booking_id . '/paid') . "' style='margin-right: 10px;'>Set to Paid</a>";
        echo "</p>";
        
        echo "<p><a href='" . base_url('booking/print_ticket/' . $booking_id) . "' target='_blank' style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>🖨️ View Print Ticket</a></p>";
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
            } else {
                // Table exists, check if payment_status column exists
                $this->addPaymentStatusColumn();
                // Remove duration column if exists
                $this->removeDurationColumn();
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
            `catatan` text,
            `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
            `payment_status` enum('unpaid','partial','paid') DEFAULT 'unpaid',
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

            // Tambahkan kolom payment_status jika tabel sudah ada sebelumnya
            $this->addPaymentStatusColumn();
            // Hapus kolom durasi jika ada
            $this->removeDurationColumn();
        } else {
            log_message('error', 'Failed to create booking table: ' . $this->db->error()['message']);
        }
    }

    private function addPaymentStatusColumn()
    {
        try {
            // Cek apakah kolom payment_status sudah ada
            $query = $this->db->query("SHOW COLUMNS FROM booking_online LIKE 'payment_status'");
            if ($query->num_rows() == 0) {
                // Tambahkan kolom payment_status jika belum ada
                $sql = "ALTER TABLE booking_online ADD COLUMN payment_status ENUM('unpaid','partial','paid') DEFAULT 'unpaid' AFTER status";
                $this->db->query($sql);
                log_message('info', 'Payment status column added successfully');
            }

            // Cek apakah kolom created_by sudah ada
            $query = $this->db->query("SHOW COLUMNS FROM booking_online LIKE 'created_by'");
            if ($query->num_rows() == 0) {
                // Tambahkan kolom created_by untuk tracking user yang membuat booking
                $sql = "ALTER TABLE booking_online ADD COLUMN created_by VARCHAR(50) NULL AFTER payment_status";
                $this->db->query($sql);
                log_message('info', 'Created by column added successfully');
            }
        } catch (Exception $e) {
            log_message('error', 'Error adding columns: ' . $e->getMessage());
        }
    }

    private function removeDurationColumn()
    {
        try {
            // Cek apakah kolom durasi masih ada
            $query = $this->db->query("SHOW COLUMNS FROM booking_online LIKE 'durasi'");
            if ($query->num_rows() > 0) {
                // Hapus kolom durasi jika masih ada
                $sql = "ALTER TABLE booking_online DROP COLUMN durasi";
                $this->db->query($sql);
                log_message('info', 'Duration column removed successfully');
            }
        } catch (Exception $e) {
            log_message('error', 'Error removing duration column: ' . $e->getMessage());
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

    // Generate report booking
    public function generate_report()
    {
        // cek_login();

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $status_filter = $this->input->get('status_filter');
        $payment_filter = $this->input->get('payment_filter');
        $studio_filter = $this->input->get('studio_filter');
        $format = $this->input->get('format');

        // Validasi input
        if (!$start_date || !$end_date) {
            show_error('Parameter tanggal tidak lengkap');
            return;
        }

        // Get filtered data
        $data = $this->Booking_m->getReportData($start_date, $end_date, $status_filter, $payment_filter, $studio_filter);

        $report_data = array(
            'title' => 'Report Booking',
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status_filter' => $status_filter,
            'payment_filter' => $payment_filter,
            'studio_filter' => $studio_filter,
            'booking_data' => $data,
            'total_records' => count($data),
            'toko' => $this->db->get('profil_perusahaan')->row()
        );

        switch ($format) {
            case 'excel':
                $this->generateExcelReport($report_data);
                break;
            case 'pdf':
                $this->generatePdfReport($report_data);
                break;
            case 'html':
            default:
                $this->load->view('booking/report_html', $report_data);
                break;
        }
    }

    private function generateExcelReport($data)
    {
        // Load PhpSpreadsheet library (jika ada) atau gunakan simple CSV
        $filename = 'booking_report_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header CSV
        fputcsv($output, array(
            'Kode Booking',
            'Nama Customer',
            'Telepon',
            'Email',
            'Karyawan',
            'Studio',
            'Tanggal Booking',
            'Jam Booking',
            'Status',
            'Status Pembayaran',
            'Catatan',
            'Tanggal Dibuat'
        ));

        // Data rows
        foreach ($data['booking_data'] as $row) {
            $studio_name = $this->getStudioDisplayName($row->studio_id);
            $payment_status = $this->getPaymentStatusText($row->payment_status ?? 'unpaid');

            fputcsv($output, array(
                $row->kode_booking,
                $row->nama_customer,
                $row->telp_customer,
                $row->email_customer,
                $row->nama_karyawan ?? '-',
                $studio_name,
                date('d-m-Y', strtotime($row->tanggal_booking)),
                $row->jam_booking,
                ucfirst($row->status),
                $payment_status,
                $row->catatan ?? '-',
                date('d-m-Y H:i', strtotime($row->created_at))
            ));
        }

        fclose($output);
    }

    private function generatePdfReport($data)
    {
        // Load TCPDF library (jika ada) atau gunakan HTML to PDF sederhana
        $html = $this->load->view('booking/report_pdf', $data, true);

        // Simple HTML to PDF using browser print
        $filename = 'booking_report_' . date('Y-m-d_H-i-s') . '.html';

        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        echo $html;
    }

    private function getStudioDisplayName($studio_id)
    {
        switch ($studio_id) {
            case 'Studio 1':
                return 'Studio 1 (Self Photo)';
            case 'Studio 2':
                return 'Studio 2 (Self Photo)';
            case 'Studio 3':
                return 'Studio 3 (Wide Photobox)';
            case 'Studio 4':
                return 'Studio 4 (Photo Elevator)';
            default:
                return $studio_id;
        }
    }

    private function getPaymentStatusText($status)
    {
        switch ($status) {
            case 'paid':
                return 'Lunas';
            case 'partial':
                return 'Sebagian';
            case 'unpaid':
            default:
                return 'Belum Bayar';
        }
    }
}
