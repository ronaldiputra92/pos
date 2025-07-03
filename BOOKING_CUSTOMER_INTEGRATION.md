# Integrasi Booking Online dengan Master Data Customer

## Fitur Baru

Sistem booking online sekarang secara otomatis menyimpan data customer ke dalam master data customer ketika booking berhasil dibuat.

## Cara Kerja

### 1. Proses Otomatis
Ketika customer melakukan booking online, sistem akan:
- Menyimpan data booking ke tabel `booking_online`
- **Secara otomatis** menyimpan data customer ke tabel `customer`
- Menggunakan database transaction untuk memastikan konsistensi data

### 2. Pengecekan Duplikasi
Sistem akan memeriksa apakah customer sudah ada berdasarkan:
1. **Nomor telepon** (prioritas utama)
2. **Email** (jika kolom email tersedia)
3. **Nama** (sebagai fallback)

### 3. Mapping Data

#### Data dari Booking Online → Master Customer:
- `nama_customer` → `nama_cs`
- `jenis_kelamin` → `jenis_kelamin` (field baru di form booking)
- `telp_customer` → `telp`
- `email_customer` → `email` (kolom baru)
- `alamat_customer` → `alamat` (field baru di form booking)
- `studio_id` → `jenis_cs`
- `tanggal_booking + jam_booking` → `tanggal_booking`
- Auto-generate → `kode_cs` (format: CS-XXXXXX)

#### Field Baru di Form Booking Online:
- **Jenis Kelamin**: Dropdown (Laki-laki/Perempuan) - Required
- **Alamat**: Textarea - Required

### 4. Update Customer Existing
Jika customer sudah ada (berdasarkan telepon/email), sistem akan:
- **Tidak** membuat customer baru
- **Update** tanggal booking terakhir
- **Update** email jika kolom email tersedia
- **Update** jenis kelamin dari form booking
- **Update** alamat dari form booking

## Perubahan Database

### Tabel Customer
Sistem akan otomatis menambahkan kolom `email` ke tabel customer jika belum ada:
```sql
ALTER TABLE customer ADD COLUMN email VARCHAR(100) NULL AFTER telp;
```

### Tabel Booking Online
Sistem akan otomatis menambahkan kolom baru ke tabel booking_online jika belum ada:
```sql
ALTER TABLE booking_online ADD COLUMN jenis_kelamin ENUM('Laki-laki','Perempuan') NULL AFTER nama_customer;
ALTER TABLE booking_online ADD COLUMN alamat_customer TEXT NULL AFTER email_customer;
```

## Keuntungan

1. **Otomatisasi**: Tidak perlu input manual data customer
2. **Konsistensi**: Data customer selalu tersinkron dengan booking
3. **Efisiensi**: Mengurangi duplikasi data entry
4. **Tracking**: Dapat melacak customer yang booking online

## Contoh Skenario

### Skenario 1: Customer Baru
```
Customer booking online:
- Nama: John Doe
- Jenis Kelamin: Laki-laki
- Telepon: 08123456789
- Email: john@email.com
- Alamat: Jl. Contoh No. 123, Jakarta
- Studio: Studio 1

Hasil:
- Data booking tersimpan di tabel booking_online
- Customer baru dibuat di tabel customer dengan kode CS-000001
- Semua data customer lengkap tersimpan
```

### Skenario 2: Customer Existing
```
Customer yang sudah ada booking lagi:
- Telepon: 08123456789 (sudah ada di database)
- Jenis Kelamin: Perempuan (update dari form)
- Alamat: Jl. Baru No. 456, Bandung (update dari form)

Hasil:
- Data booking baru tersimpan di tabel booking_online
- Data customer existing di-update:
  * Tanggal booking terakhir
  * Jenis kelamin (jika berubah)
  * Alamat (jika berubah)
  * Email (jika berubah)
```

## Log System

Sistem akan mencatat aktivitas di log file:
- `New customer auto-created from booking: [nama] ([telepon])`
- `Existing customer booking date updated: [nama] ([telepon])`

## Error Handling

- Menggunakan database transaction untuk rollback jika ada error
- Log error ke system log jika terjadi masalah
- Fallback untuk generate kode customer jika terjadi error

## Maintenance

### Monitoring
- Periksa log file untuk memastikan integrasi berjalan dengan baik
- Monitor tabel customer untuk memastikan data tersimpan dengan benar

### Troubleshooting
Jika ada masalah:
1. Periksa log file di `application/logs/`
2. Pastikan tabel customer memiliki kolom email
3. Periksa permission database untuk ALTER TABLE

## Konfigurasi

Tidak ada konfigurasi khusus yang diperlukan. Fitur ini akan aktif otomatis ketika:
- Model `Booking_m` di-load
- Method `saveBooking()` dipanggil
- Database transaction berhasil

---

**Catatan**: Fitur ini menggunakan database transaction untuk memastikan data konsisten. Jika terjadi error saat menyimpan customer, booking juga akan di-rollback.