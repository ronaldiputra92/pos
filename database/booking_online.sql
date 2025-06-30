-- Tabel untuk booking online
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
  UNIQUE KEY `kode_booking` (`kode_booking`),
  KEY `idx_tanggal_jam` (`tanggal_booking`, `jam_booking`),
  KEY `idx_studio` (`studio_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Index untuk optimasi query
CREATE INDEX idx_booking_schedule ON booking_online (studio_id, tanggal_booking, jam_booking, status);

-- Contoh data dummy (opsional)
INSERT INTO `booking_online` (`kode_booking`, `nama_customer`, `telp_customer`, `email_customer`, `studio_id`, `tanggal_booking`, `jam_booking`, `durasi`, `catatan`, `status`) VALUES
('BK-000001', 'John Doe', '081234567890', 'john@example.com', 'Studio 1', '2025-01-15', '10:00:00', 60, 'Foto untuk pre-wedding', 'confirmed'),
('BK-000002', 'Jane Smith', '081234567891', 'jane@example.com', 'Studio 2', '2025-01-15', '14:00:00', 90, 'Foto keluarga', 'pending'),
('BK-000003', 'Bob Wilson', '081234567892', 'bob@example.com', 'Studio 3', '2025-01-16', '09:00:00', 120, 'Foto produk', 'confirmed');