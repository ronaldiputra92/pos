# Troubleshooting: Booking Slots Tidak Menampilkan Tanda Silang

## Masalah
Slot waktu yang sudah terboking tidak menampilkan tanda silang merah, padahal data booking sudah ada di database.

## Kemungkinan Penyebab & Solusi

### 1. **Format Jam Tidak Cocok**
**Masalah**: Database menyimpan jam dalam format `HH:MM:SS` (contoh: `09:00:00`) sedangkan sistem membandingkan dengan format `HH:MM` (contoh: `09:00`).

**Solusi**: Sudah diperbaiki dengan normalisasi format jam di method `getAllSlotsWithStatus()`.

### 2. **Status Booking Tidak Sesuai**
**Masalah**: Query hanya mencari booking dengan status `pending` dan `confirmed`, tapi booking mungkin memiliki status lain.

**Cara Cek**:
```sql
SELECT DISTINCT status FROM booking_online;
```

**Solusi**: Pastikan status booking sesuai dengan yang dicari dalam query.

### 3. **Nama Studio Tidak Cocok**
**Masalah**: Nama studio di database berbeda dengan yang dikirim dari frontend.

**Cara Cek**:
```sql
SELECT DISTINCT studio_id FROM booking_online;
```

**Solusi**: Pastikan nama studio konsisten antara frontend dan database.

### 4. **Format Tanggal Tidak Cocok**
**Masalah**: Format tanggal yang dikirim tidak sesuai dengan format di database.

**Cara Cek**:
```sql
SELECT DISTINCT tanggal_booking FROM booking_online ORDER BY tanggal_booking;
```

**Solusi**: Pastikan format tanggal konsisten (YYYY-MM-DD).

## Tools Debugging

### 1. **Debug URL**
Akses URL berikut untuk melihat data booking:
```
http://localhost/kasir/booking/debug_booking/2025-07-04/Studio%203
```

### 2. **Test AJAX URL**
Test AJAX call langsung:
```
http://localhost/kasir/booking/test_ajax_slots
```

### 3. **Test Page**
Buka file test HTML:
```
http://localhost/kasir/test_booking_slots.html
```

## Langkah Troubleshooting

### Step 1: Verifikasi Data Booking
```sql
SELECT * FROM booking_online 
WHERE tanggal_booking = '2025-07-04' 
AND studio_id = 'Studio 3';
```

### Step 2: Cek Format Jam
```sql
SELECT jam_booking, LENGTH(jam_booking) as jam_length 
FROM booking_online 
WHERE tanggal_booking = '2025-07-04' 
AND studio_id = 'Studio 3';
```

### Step 3: Test Method getAllSlotsWithStatus
Akses debug URL dan periksa:
- Query yang dijalankan
- Data booking yang ditemukan
- Array booked_times
- Array normalized booked_times
- Result akhir getAllSlotsWithStatus

### Step 4: Test AJAX Response
Buka test page dan periksa:
- Response JSON dari AJAX call
- Apakah slot jam 09:00 memiliki `available: false`
- Console browser untuk error JavaScript

### Step 5: Verifikasi Frontend
Periksa di browser:
- Apakah AJAX call berhasil
- Apakah response JSON benar
- Apakah CSS class `unavailable` diterapkan
- Apakah tanda silang (✗) muncul

## Checklist Debugging

- [ ] Data booking ada di database untuk tanggal dan studio yang benar
- [ ] Format jam di database (cek apakah HH:MM atau HH:MM:SS)
- [ ] Status booking adalah 'pending' atau 'confirmed'
- [ ] Nama studio di database sama dengan yang dikirim frontend
- [ ] Format tanggal konsisten (YYYY-MM-DD)
- [ ] Method getAllSlotsWithStatus mengembalikan data yang benar
- [ ] AJAX call berhasil dan mengembalikan response yang benar
- [ ] JavaScript menerapkan class CSS yang benar
- [ ] CSS untuk tanda silang sudah benar

## Log Files

Periksa log files di:
- `application/logs/log-YYYY-MM-DD.php`

Cari log dengan keyword:
- `getAllSlotsWithStatus`
- `Slot 09:00`
- `Booked times`

## Contoh Response Yang Benar

Jika jam 09:00 sudah terboking, response JSON harus seperti ini:
```json
[
    {
        "time": "09:00",
        "available": false
    },
    {
        "time": "09:30", 
        "available": true
    }
]
```

## Quick Fix

Jika masalah masih terjadi, coba langkah berikut:

1. **Clear Browser Cache**: Ctrl+F5 atau Ctrl+Shift+R
2. **Check Console**: F12 → Console tab untuk error JavaScript
3. **Check Network**: F12 → Network tab untuk melihat AJAX request/response
4. **Restart Server**: Restart Apache/Nginx jika menggunakan opcache

## Contact

Jika masalah masih berlanjut, sertakan informasi berikut:
- Screenshot dari debug URL
- Response JSON dari AJAX call
- Error message dari console browser
- Data booking dari database