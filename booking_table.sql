-- SQL untuk membuat tabel booking_online
-- Jalankan script ini di phpMyAdmin atau MySQL client

CREATE TABLE IF NOT EXISTS `booking_online` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tambahkan foreign key constraint jika tabel karyawan sudah ada
-- ALTER TABLE `booking_online` 
-- ADD CONSTRAINT `fk_booking_karyawan` 
-- FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`) 
-- ON DELETE SET NULL ON UPDATE CASCADE;