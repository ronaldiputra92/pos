<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Booking_m extends CI_Model
{
    protected $table = 'booking_online';
    protected $primary = 'id_booking';

    public function saveBooking()
    {
        // Generate kode booking
        $kode_booking = $this->generateBookingCode();

        // Get current user info for tracking
        $user = infoLogin();
        $created_by = isset($user['username']) ? $user['username'] : 'guest';

        $data = array(
            'kode_booking' => $kode_booking,
            'nama_customer' => htmlspecialchars($this->input->post('nama_customer'), true),
            'jenis_kelamin' => htmlspecialchars($this->input->post('jenis_kelamin'), true),
            'telp_customer' => htmlspecialchars($this->input->post('telp_customer'), true),
            'email_customer' => htmlspecialchars($this->input->post('email_customer'), true),
            'alamat_customer' => htmlspecialchars($this->input->post('alamat_customer'), true),
            'id_karyawan' => $this->input->post('id_karyawan'),
            'studio_id' => htmlspecialchars($this->input->post('studio_id'), true),
            'tanggal_booking' => $this->input->post('tanggal_booking'),
            'jam_booking' => $this->input->post('jam_booking'),
            'catatan' => htmlspecialchars($this->input->post('catatan'), true),
            'status' => 'pending',
            'created_by' => $created_by,
            'created_at' => date('Y-m-d H:i:s')
        );

        // Start transaction
        $this->db->trans_start();

        // Insert booking data
        $booking_inserted = $this->db->insert($this->table, $data);

        if ($booking_inserted) {
            // Auto-save customer data to master customer table
            $this->saveCustomerFromBooking();
        }

        // Complete transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return $kode_booking;
        }
    }

    public function updateBooking()
    {
        $booking_id = $this->input->post('edit_booking_id');

        $data = array(
            'nama_customer' => htmlspecialchars($this->input->post('edit_nama_customer'), true),
            'telp_customer' => htmlspecialchars($this->input->post('edit_telp_customer'), true),
            'email_customer' => htmlspecialchars($this->input->post('edit_email_customer'), true),
            'id_karyawan' => $this->input->post('edit_id_karyawan'),
            'studio_id' => htmlspecialchars($this->input->post('edit_studio_id'), true),
            'tanggal_booking' => $this->input->post('edit_tanggal_booking'),
            'jam_booking' => $this->input->post('edit_jam_booking'),
            'catatan' => htmlspecialchars($this->input->post('edit_catatan'), true),
            'updated_at' => date('Y-m-d H:i:s')
        );

        // Add new fields if they exist in the form
        if ($this->input->post('edit_jenis_kelamin')) {
            $data['jenis_kelamin'] = htmlspecialchars($this->input->post('edit_jenis_kelamin'), true);
        }
        if ($this->input->post('edit_alamat_customer')) {
            $data['alamat_customer'] = htmlspecialchars($this->input->post('edit_alamat_customer'), true);
        }

        $this->db->where('kode_booking', $booking_id);
        return $this->db->update($this->table, $data);
    }

    public function checkAvailability($tanggal, $jam, $studio_id)
    {
        $this->db->where('tanggal_booking', $tanggal);
        $this->db->where('jam_booking', $jam);
        $this->db->where('studio_id', $studio_id);
        $this->db->where_in('status', ['pending', 'confirmed']);
        $this->db->where_in('payment_status', ['paid', 'partial']);
        $query = $this->db->get($this->table);

        return $query->num_rows() > 0;
    }

    public function checkAvailabilityForEdit($tanggal, $jam, $studio_id, $booking_id)
    {
        $this->db->where('tanggal_booking', $tanggal);
        $this->db->where('jam_booking', $jam);
        $this->db->where('studio_id', $studio_id);
        $this->db->where('kode_booking !=', $booking_id);
        $this->db->where_in('status', ['pending', 'confirmed']);
        $this->db->where_in('payment_status', ['paid', 'partial']);
        $query = $this->db->get($this->table);

        return $query->num_rows() > 0;
    }

    public function getAvailableSlots($tanggal, $studio_id)
    {
        // Jam operasional studio (9:00 - 21:30)
        $time_slots = array();
        for ($hour = 9; $hour <= 21; $hour++) {
            $time_slots[] = sprintf('%02d:00', $hour);
            $time_slots[] = sprintf('%02d:30', $hour);
        }

        // Ambil slot yang sudah terboking dengan pembayaran lunas atau sebagian
        // Hanya slot dengan payment_status 'paid' atau 'partial' yang dianggap tidak tersedia
        $this->db->select('jam_booking, payment_status');
        $this->db->where('tanggal_booking', $tanggal);
        $this->db->where('studio_id', $studio_id);
        $this->db->where_in('status', ['pending', 'confirmed']);
        $this->db->where_in('payment_status', ['paid', 'partial']);
        $booked_slots = $this->db->get($this->table)->result_array();

        $booked_times = array_column($booked_slots, 'jam_booking');

        // Filter slot yang tersedia
        $available_slots = array_diff($time_slots, $booked_times);

        return array_values($available_slots);
    }

    public function getAvailableSlotsForEdit($tanggal, $studio_id, $booking_id)
    {
        // Jam operasional studio (9:00 - 21:30)
        $time_slots = array();
        for ($hour = 9; $hour <= 21; $hour++) {
            $time_slots[] = sprintf('%02d:00', $hour);
            $time_slots[] = sprintf('%02d:30', $hour);
        }

        // Ambil slot yang sudah terboking dengan pembayaran lunas atau sebagian (kecuali booking yang sedang diedit)
        $this->db->select('jam_booking');
        $this->db->where('tanggal_booking', $tanggal);
        $this->db->where('studio_id', $studio_id);
        $this->db->where('kode_booking !=', $booking_id);
        $this->db->where_in('status', ['pending', 'confirmed']);
        $this->db->where_in('payment_status', ['paid', 'partial']);
        $booked_slots = $this->db->get($this->table)->result_array();

        $booked_times = array_column($booked_slots, 'jam_booking');

        // Filter slot yang tersedia
        $available_slots = array_diff($time_slots, $booked_times);

        return array_values($available_slots);
    }

    public function getAllSlotsWithStatus($tanggal, $studio_id)
    {
        // Log untuk debugging
        log_message('debug', "getAllSlotsWithStatus called with tanggal: $tanggal, studio_id: $studio_id");
        log_message('debug', "Only considering bookings with payment_status: paid or partial");

        // Jam operasional studio (9:00 - 21:30)
        $all_slots = array();
        for ($hour = 9; $hour <= 21; $hour++) {
            $all_slots[] = sprintf('%02d:00', $hour);
            $all_slots[] = sprintf('%02d:30', $hour);
        }

        // Ambil slot yang sudah terboking dengan pembayaran lunas atau sebagian
        $this->db->select('jam_booking');
        $this->db->where('tanggal_booking', $tanggal);
        $this->db->where('studio_id', $studio_id);
        $this->db->where_in('status', ['pending', 'confirmed']);
        $this->db->where_in('payment_status', ['paid', 'partial']);
        $booked_slots = $this->db->get($this->table)->result_array();

        // Log query yang dijalankan
        log_message('debug', "Query executed: " . $this->db->last_query());
        log_message('debug', "Booked slots found: " . json_encode($booked_slots));

        $booked_times = array_column($booked_slots, 'jam_booking');
        
        // Normalize format jam - hapus detik jika ada (HH:MM:SS -> HH:MM)
        $booked_times_normalized = array();
        foreach ($booked_times as $time) {
            // Jika format HH:MM:SS, potong menjadi HH:MM
            if (strlen($time) > 5) {
                $booked_times_normalized[] = substr($time, 0, 5);
            } else {
                $booked_times_normalized[] = $time;
            }
        }
        
        log_message('debug', "Original booked times: " . json_encode($booked_times));
        log_message('debug', "Normalized booked times: " . json_encode($booked_times_normalized));

        // Buat array dengan status untuk setiap slot
        $slots_with_status = array();
        foreach ($all_slots as $slot) {
            $is_available = !in_array($slot, $booked_times_normalized);
            $slots_with_status[] = array(
                'time' => $slot,
                'available' => $is_available
            );
            
            // Log khusus untuk jam 09:00
            if ($slot === '09:00') {
                log_message('debug', "Slot 09:00 - Available: " . ($is_available ? 'true' : 'false') . ", In normalized booked_times: " . (in_array($slot, $booked_times_normalized) ? 'true' : 'false'));
            }
        }

        log_message('debug', "Final slots_with_status: " . json_encode($slots_with_status));
        return $slots_with_status;
    }

    public function getAllBooking()
    {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    public function getAllBookingWithEmployee()
    {
        $this->db->select('booking_online.*, karyawan.nama_karyawan');
        $this->db->from($this->table);
        $this->db->join('karyawan', 'karyawan.id_karyawan = booking_online.id_karyawan', 'left');
        $this->db->order_by('booking_online.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function getBookingById($booking_id)
    {
        $this->db->select('booking_online.*, karyawan.nama_karyawan');
        $this->db->from($this->table);
        $this->db->join('karyawan', 'karyawan.id_karyawan = booking_online.id_karyawan', 'left');
        $this->db->where('booking_online.kode_booking', $booking_id);
        return $this->db->get()->row();
    }

    public function updateStatus($booking_id, $status)
    {
        $data = array(
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('kode_booking', $booking_id);
        return $this->db->update($this->table, $data);
    }

    public function deleteBooking($booking_id)
    {
        $this->db->where('kode_booking', $booking_id);
        return $this->db->delete($this->table);
    }

    private function generateBookingCode()
    {
        try {
            $this->db->select("RIGHT (booking_online.kode_booking, 6) as kode_booking", false);
            $this->db->order_by("kode_booking", "DESC");
            $this->db->limit(1);
            $query = $this->db->get('booking_online');

            if ($query->num_rows() > 0) {
                $data = $query->row();
                $kode = intval($data->kode_booking) + 1;
            } else {
                $kode = 1;
            }

            $kode_booking = str_pad($kode, 6, "0", STR_PAD_LEFT);
            return "BK-" . $kode_booking;
        } catch (Exception $e) {
            log_message('error', 'Error generating booking code: ' . $e->getMessage());
            // Fallback: generate simple code
            return "BK-" . str_pad(rand(1, 999999), 6, "0", STR_PAD_LEFT);
        }
    }

    public function getBookingByDate($tanggal)
    {
        $this->db->where('tanggal_booking', $tanggal);
        $this->db->where_in('status', ['pending', 'confirmed']);
        $this->db->order_by('jam_booking', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function getTodayBooking()
    {
        $today = date('Y-m-d');
        return $this->getBookingByDate($today);
    }

    public function getReportData($start_date, $end_date, $status_filter = '', $payment_filter = '', $studio_filter = '')
    {
        $this->db->select('booking_online.*, karyawan.nama_karyawan');
        $this->db->from($this->table);
        $this->db->join('karyawan', 'karyawan.id_karyawan = booking_online.id_karyawan', 'left');

        // Filter by date range
        $this->db->where('booking_online.tanggal_booking >=', $start_date);
        $this->db->where('booking_online.tanggal_booking <=', $end_date);

        // Filter by status if specified
        if (!empty($status_filter)) {
            $this->db->where('booking_online.status', $status_filter);
        }

        // Filter by payment status if specified
        if (!empty($payment_filter)) {
            $this->db->where('booking_online.payment_status', $payment_filter);
        }

        // Filter by studio if specified
        if (!empty($studio_filter)) {
            $this->db->where('booking_online.studio_id', $studio_filter);
        }

        $this->db->order_by('booking_online.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function getBookingByCustomer($customer_identifier)
    {
        $this->db->select('booking_online.*, karyawan.nama_karyawan');
        $this->db->from($this->table);
        $this->db->join('karyawan', 'karyawan.id_karyawan = booking_online.id_karyawan', 'left');

        // Filter berdasarkan created_by (username) atau email/nama customer
        $this->db->group_start();
        $this->db->where('booking_online.created_by', $customer_identifier);
        $this->db->or_like('booking_online.email_customer', $customer_identifier);
        $this->db->or_like('booking_online.nama_customer', $customer_identifier);
        $this->db->group_end();

        $this->db->order_by('booking_online.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function searchCustomerBooking($search_term)
    {
        $this->db->select('booking_online.*, karyawan.nama_karyawan');
        $this->db->from($this->table);
        $this->db->join('karyawan', 'karyawan.id_karyawan = booking_online.id_karyawan', 'left');

        // Search berdasarkan kode booking, nama customer, email, atau telepon
        $this->db->group_start();
        $this->db->like('booking_online.kode_booking', $search_term);
        $this->db->or_like('booking_online.nama_customer', $search_term);
        $this->db->or_like('booking_online.email_customer', $search_term);
        $this->db->or_like('booking_online.telp_customer', $search_term);
        $this->db->group_end();

        $this->db->order_by('booking_online.created_at', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Save customer data from booking to master customer table
     */
    private function saveCustomerFromBooking()
    {
        // Ensure email column exists in customer table
        $this->ensureEmailColumnExists();

        $nama_customer = htmlspecialchars($this->input->post('nama_customer'), true);
        $jenis_kelamin = htmlspecialchars($this->input->post('jenis_kelamin'), true);
        $telp_customer = htmlspecialchars($this->input->post('telp_customer'), true);
        $email_customer = htmlspecialchars($this->input->post('email_customer'), true);
        $alamat_customer = htmlspecialchars($this->input->post('alamat_customer'), true);
        $studio_id = htmlspecialchars($this->input->post('studio_id'), true);
        $tanggal_booking = $this->input->post('tanggal_booking');
        $jam_booking = $this->input->post('jam_booking');

        // Check if customer already exists (by phone or email)
        $existing_customer = $this->checkExistingCustomer($telp_customer, $email_customer);

        if (!$existing_customer) {
            // Generate customer code
            $kode_customer = $this->generateCustomerCode();

            // Prepare customer data
            $customer_data = array(
                'kode_cs' => $kode_customer,
                'nama_cs' => $nama_customer,
                'jenis_kelamin' => $jenis_kelamin, // Dari form booking
                'telp' => $telp_customer,
                'alamat' => $alamat_customer, // Dari form booking
                'jenis_cs' => $studio_id, // Studio yang dipilih sebagai jenis customer
                'tanggal_booking' => $tanggal_booking . ' ' . $jam_booking . ':00' // Gabungkan tanggal dan jam
            );

            // Add email if column exists
            if ($this->columnExists('customer', 'email')) {
                $customer_data['email'] = $email_customer;
            }

            // Insert to customer table
            $this->db->insert('customer', $customer_data);
            
            log_message('info', 'New customer auto-created from booking: ' . $nama_customer . ' (' . $telp_customer . ')');
        } else {
            // Update existing customer's booking date, email, gender, and address if needed
            $this->updateCustomerBookingDate($existing_customer['id_cs'], $tanggal_booking, $jam_booking, $email_customer, $jenis_kelamin, $alamat_customer);
            
            log_message('info', 'Existing customer booking date updated: ' . $nama_customer . ' (' . $telp_customer . ')');
        }
    }

    /**
     * Check if customer already exists by phone, email, or name
     */
    private function checkExistingCustomer($telp, $email)
    {
        // Check by phone number first (most reliable)
        $this->db->where('telp', $telp);
        $query = $this->db->get('customer');

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

        // Check by email if email column exists
        if ($this->columnExists('customer', 'email')) {
            $this->db->where('email', $email);
            $query_email = $this->db->get('customer');

            if ($query_email->num_rows() > 0) {
                return $query_email->row_array();
            }
        }

        // If not found by phone or email, check by name (using email as name for comparison)
        // This is a fallback in case the same person uses different phone but same name
        $nama_from_email = explode('@', $email)[0]; // Get name part from email
        $this->db->like('nama_cs', $nama_from_email);
        $query2 = $this->db->get('customer');

        if ($query2->num_rows() > 0) {
            return $query2->row_array();
        }

        return false;
    }

    /**
     * Generate customer code
     */
    private function generateCustomerCode()
    {
        try {
            $this->db->select("RIGHT (customer.kode_cs, 6) as kode_cs", false);
            $this->db->order_by("kode_cs", "DESC");
            $this->db->limit(1);
            $query = $this->db->get('customer');

            if ($query->num_rows() > 0) {
                $data = $query->row();
                $kode = intval($data->kode_cs) + 1;
            } else {
                $kode = 1;
            }

            $kodecs = str_pad($kode, 6, "0", STR_PAD_LEFT);
            return "CS-" . $kodecs;
        } catch (Exception $e) {
            log_message('error', 'Error generating customer code: ' . $e->getMessage());
            // Fallback: generate simple code
            return "CS-" . str_pad(rand(1, 999999), 6, "0", STR_PAD_LEFT);
        }
    }

    /**
     * Update existing customer's booking date, email, gender, and address
     */
    private function updateCustomerBookingDate($customer_id, $tanggal_booking, $jam_booking, $email = null, $jenis_kelamin = null, $alamat = null)
    {
        $data = array(
            'tanggal_booking' => $tanggal_booking . ' ' . $jam_booking . ':00'
        );

        // Add email if column exists and email is provided
        if ($email && $this->columnExists('customer', 'email')) {
            $data['email'] = $email;
        }

        // Update gender if provided and not empty
        if ($jenis_kelamin) {
            $data['jenis_kelamin'] = $jenis_kelamin;
        }

        // Update address if provided and not empty
        if ($alamat) {
            $data['alamat'] = $alamat;
        }

        $this->db->where('id_cs', $customer_id);
        $this->db->update('customer', $data);
    }

    /**
     * Check if column exists in table
     */
    private function columnExists($table, $column)
    {
        try {
            $query = $this->db->query("SHOW COLUMNS FROM {$table} LIKE '{$column}'");
            return $query->num_rows() > 0;
        } catch (Exception $e) {
            log_message('error', 'Error checking column existence: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ensure email column exists in customer table
     */
    private function ensureEmailColumnExists()
    {
        try {
            if (!$this->columnExists('customer', 'email')) {
                $sql = "ALTER TABLE customer ADD COLUMN email VARCHAR(100) NULL AFTER telp";
                $this->db->query($sql);
                log_message('info', 'Email column added to customer table');
            }
        } catch (Exception $e) {
            log_message('error', 'Error adding email column to customer table: ' . $e->getMessage());
        }
    }
}
