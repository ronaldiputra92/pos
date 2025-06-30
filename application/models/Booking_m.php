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

        $data = array(
            'kode_booking' => $kode_booking,
            'nama_customer' => htmlspecialchars($this->input->post('nama_customer'), true),
            'telp_customer' => htmlspecialchars($this->input->post('telp_customer'), true),
            'email_customer' => htmlspecialchars($this->input->post('email_customer'), true),
            'id_karyawan' => $this->input->post('id_karyawan'),
            'studio_id' => htmlspecialchars($this->input->post('studio_id'), true),
            'tanggal_booking' => $this->input->post('tanggal_booking'),
            'jam_booking' => $this->input->post('jam_booking'),
            'durasi' => $this->input->post('durasi') ? $this->input->post('durasi') : 60, // default 60 menit
            'catatan' => htmlspecialchars($this->input->post('catatan'), true),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->db->insert($this->table, $data)) {
            return $kode_booking;
        } else {
            return false;
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
            'durasi' => $this->input->post('edit_durasi') ? $this->input->post('edit_durasi') : 60,
            'catatan' => htmlspecialchars($this->input->post('edit_catatan'), true),
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('kode_booking', $booking_id);
        return $this->db->update($this->table, $data);
    }

    public function checkAvailability($tanggal, $jam, $studio_id)
    {
        $this->db->where('tanggal_booking', $tanggal);
        $this->db->where('jam_booking', $jam);
        $this->db->where('studio_id', $studio_id);
        $this->db->where_in('status', ['pending', 'confirmed']);
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
        $query = $this->db->get($this->table);

        return $query->num_rows() > 0;
    }

    public function getAvailableSlots($tanggal, $studio_id)
    {
        // Jam operasional studio (9:00 - 21:00)
        $time_slots = array();
        for ($hour = 9; $hour <= 20; $hour++) {
            $time_slots[] = sprintf('%02d:00', $hour);
            $time_slots[] = sprintf('%02d:30', $hour);
        }

        // Ambil slot yang sudah terboking
        $this->db->select('jam_booking');
        $this->db->where('tanggal_booking', $tanggal);
        $this->db->where('studio_id', $studio_id);
        $this->db->where_in('status', ['pending', 'confirmed']);
        $booked_slots = $this->db->get($this->table)->result_array();

        $booked_times = array_column($booked_slots, 'jam_booking');

        // Filter slot yang tersedia
        $available_slots = array_diff($time_slots, $booked_times);

        return array_values($available_slots);
    }

    public function getAvailableSlotsForEdit($tanggal, $studio_id, $booking_id)
    {
        // Jam operasional studio (9:00 - 21:00)
        $time_slots = array();
        for ($hour = 9; $hour <= 20; $hour++) {
            $time_slots[] = sprintf('%02d:00', $hour);
            $time_slots[] = sprintf('%02d:30', $hour);
        }

        // Ambil slot yang sudah terboking (kecuali booking yang sedang diedit)
        $this->db->select('jam_booking');
        $this->db->where('tanggal_booking', $tanggal);
        $this->db->where('studio_id', $studio_id);
        $this->db->where('kode_booking !=', $booking_id);
        $this->db->where_in('status', ['pending', 'confirmed']);
        $booked_slots = $this->db->get($this->table)->result_array();

        $booked_times = array_column($booked_slots, 'jam_booking');

        // Filter slot yang tersedia
        $available_slots = array_diff($time_slots, $booked_times);

        return array_values($available_slots);
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
}
