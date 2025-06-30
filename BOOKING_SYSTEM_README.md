# Sistem Booking Online Studio Photo

## Fitur Utama

### 1. Booking Online untuk Customer
- **URL**: `http://localhost/kasir/booking`
- Halaman booking yang user-friendly untuk customer
- Pilihan studio dengan deskripsi lengkap
- Sistem pencegahan bentrok jadwal
- Real-time availability checking
- Form booking dengan validasi lengkap

### 2. Manajemen Booking untuk Admin
- **URL**: `http://localhost/kasir/booking/admin`
- Dashboard admin untuk mengelola semua booking
- Filter berdasarkan status, tanggal, dan studio
- Update status booking (pending, confirmed, completed, cancelled)
- Print tiket booking
- Hapus booking

### 3. Print Tiket Booking
- Tiket booking yang dapat di-print
- Berisi informasi lengkap booking
- QR Code untuk verifikasi (dapat dikembangkan)
- Format yang professional

## Struktur Database

### Tabel: `booking_online`
```sql
CREATE TABLE `booking_online` (
  `id_booking` int(11) NOT NULL AUTO_INCREMENT,
  `kode_booking` varchar(20) NOT NULL,
  `nama_customer` varchar(100) NOT NULL,
  `telp_customer` varchar(20) NOT NULL,
  `email_customer` varchar(100) NOT NULL,
  `studio_id` varchar(20) NOT NULL,
  `tanggal_booking` date NOT NULL,
  `jam_booking` time NOT NULL,
  `durasi` int(11) DEFAULT 60,
  `catatan` text,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_booking`),
  UNIQUE KEY `kode_booking` (`kode_booking`)
);
```

## Instalasi

### 1. Import Database
```bash
# Import file SQL ke database
mysql -u username -p database_name < database/booking_online.sql
```

### 2. Konfigurasi Routes (Opsional)
Tambahkan routes khusus di `application/config/routes.php` jika diperlukan:
```php
$route['booking'] = 'booking/index';
$route['booking/admin'] = 'booking/admin';
```

### 3. Menu Sidebar
Menu booking sudah ditambahkan ke sidebar admin dengan akses:
- **Halaman Booking**: Link ke halaman booking customer
- **Manajemen Booking**: Dashboard admin untuk mengelola booking

## Cara Penggunaan

### Untuk Customer:
1. Akses halaman booking: `http://localhost/kasir/booking`
2. Isi data customer (nama, telepon, email)
3. Pilih studio yang diinginkan
4. Pilih tanggal dan jam yang tersedia
5. Tambahkan catatan jika diperlukan
6. Submit booking
7. Dapatkan kode booking dan print tiket

### Untuk Admin:
1. Login ke sistem admin
2. Akses menu "Booking Online" > "Manajemen Booking"
3. Lihat daftar semua booking
4. Filter booking berdasarkan status/tanggal/studio
5. Update status booking sesuai kebutuhan
6. Print tiket untuk customer
7. Hapus booking jika diperlukan

## Fitur Pencegahan Bentrok Jadwal

### 1. Real-time Availability Check
- Sistem mengecek ketersediaan slot waktu secara real-time
- AJAX call untuk memuat slot yang tersedia
- Slot yang sudah terboking tidak dapat dipilih

### 2. Validasi Server-side
- Double check di server sebelum menyimpan booking
- Mencegah booking ganda pada waktu dan studio yang sama
- Error handling yang informatif

### 3. Status Management
- **Pending**: Booking baru yang belum dikonfirmasi
- **Confirmed**: Booking yang sudah dikonfirmasi admin
- **Completed**: Sesi foto sudah selesai
- **Cancelled**: Booking dibatalkan

## Jam Operasional Studio
- **Jam Buka**: 09:00
- **Jam Tutup**: 21:00
- **Slot Waktu**: Setiap 30 menit (09:00, 09:30, 10:00, dst.)
- **Durasi Default**: 60 menit (dapat disesuaikan)

## Studio yang Tersedia
1. **Studio 1 (Self Photo)**: Cocok untuk foto selfie dan portrait
2. **Studio 2 (Self Photo)**: Cocok untuk foto selfie dan portrait
3. **Studio 3 (Wide Photobox)**: Ruang luas untuk foto grup dan keluarga
4. **Studio 4 (Photo Elevator)**: Studio dengan konsep elevator yang unik

## Pengembangan Selanjutnya

### 1. Notifikasi
- Email confirmation untuk customer
- SMS reminder sebelum jadwal
- WhatsApp integration

### 2. Payment Gateway
- Online payment integration
- Deposit system
- Refund management

### 3. QR Code Integration
- Generate QR code untuk tiket
- QR scanner untuk check-in
- Mobile app integration

### 4. Calendar Integration
- Google Calendar sync
- Outlook integration
- iCal export

### 5. Customer Portal
- Customer dashboard
- Booking history
- Reschedule booking

## Troubleshooting

### 1. Slot Waktu Tidak Muncul
- Pastikan JavaScript berjalan dengan baik
- Cek koneksi AJAX ke server
- Periksa data di database

### 2. Booking Gagal Disimpan
- Cek validasi form
- Pastikan tidak ada konflik jadwal
- Periksa log error di server

### 3. Print Tiket Tidak Berfungsi
- Pastikan browser mendukung print
- Cek CSS print media queries
- Periksa popup blocker

## File yang Ditambahkan/Dimodifikasi

### Controller:
- `application/controllers/Booking.php`

### Model:
- `application/models/Booking_m.php`

### Views:
- `application/views/booking/online_booking.php`
- `application/views/booking/admin_booking.php`
- `application/views/booking/print_ticket.php`

### Database:
- `database/booking_online.sql`

### Modified Files:
- `application/views/templates/pos_sidebar.php` (menambah menu)
- `application/views/templates/pos_footer.php` (menambah script)

## Support
Untuk pertanyaan atau bantuan, silakan hubungi developer atau buat issue di repository ini.