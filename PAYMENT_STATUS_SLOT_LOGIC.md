# Logika Slot Booking Berdasarkan Status Pembayaran

## Overview

Sistem booking telah diperbarui dengan logika baru untuk menampilkan tanda silang merah pada slot waktu berdasarkan status pembayaran booking.

## Logika Baru

### 🟢 Slot Tersedia (Tidak Disilang)
Slot waktu akan tetap **tersedia** dan **tidak disilang** jika:
- **Payment Status = 'unpaid'** (Belum dibayar)
- Meskipun sudah ada booking, slot masih bisa dipilih karena pembayaran belum dilakukan

### ❌ Slot Tidak Tersedia (Disilang Merah)
Slot waktu akan **disilang merah** dan **tidak bisa dipilih** jika:
- **Payment Status = 'paid'** (Lunas)
- **Payment Status = 'partial'** (Sebagian)

## Implementasi Teknis

### Database Query
```sql
SELECT jam_booking, payment_status 
FROM booking_online 
WHERE tanggal_booking = ? 
  AND studio_id = ? 
  AND status IN ('pending', 'confirmed')
  AND payment_status IN ('paid', 'partial')
```

### Method yang Diperbarui

#### 1. `getAllSlotsWithStatus()`
```php
// Hanya slot dengan payment_status 'paid' atau 'partial' yang dianggap tidak tersedia
$this->db->where_in('payment_status', ['paid', 'partial']);
```

#### 2. `checkAvailability()`
```php
// Cek ketersediaan berdasarkan payment status
$this->db->where_in('payment_status', ['paid', 'partial']);
```

#### 3. `checkAvailabilityForEdit()`
```php
// Untuk edit booking, cek payment status
$this->db->where_in('payment_status', ['paid', 'partial']);
```

## Skenario Penggunaan

### Skenario 1: Booking Baru (Unpaid)
```
Customer A booking Studio 3 jam 09:00
- Status: pending
- Payment Status: unpaid
- Hasil: Slot 09:00 masih tersedia untuk customer lain
```

### Skenario 2: Booking Dibayar Sebagian
```
Customer A bayar DP untuk booking Studio 3 jam 09:00
- Status: confirmed  
- Payment Status: partial
- Hasil: Slot 09:00 disilang merah, tidak tersedia
```

### Skenario 3: Booking Lunas
```
Customer A bayar lunas untuk booking Studio 3 jam 09:00
- Status: confirmed
- Payment Status: paid
- Hasil: Slot 09:00 disilang merah, tidak tersedia
```

## Payment Status Values

| Status | Deskripsi | Slot Behavior |
|--------|-----------|---------------|
| `unpaid` | Belum dibayar | ✅ Tersedia |
| `partial` | Dibayar sebagian | ❌ Disilang |
| `paid` | Lunas | ❌ Disilang |

## Testing & Debugging

### 1. Debug Booking Data
```
http://localhost/kasir/booking/debug_booking/2025-07-04/Studio%203
```

### 2. Test Payment Status Update
```
http://localhost/kasir/booking/test_payment_status/BK-000001/paid
http://localhost/kasir/booking/test_payment_status/BK-000001/partial
http://localhost/kasir/booking/test_payment_status/BK-000001/unpaid
```

### 3. Test Slot Response
```
http://localhost/kasir/test_booking_slots.html
```

## Visual Indicators

### Frontend Display
- **Hijau**: Slot tersedia (unpaid bookings)
- **Merah dengan ✗**: Slot tidak tersedia (paid/partial bookings)

### Debug Table Colors
- **🟢 Hijau**: Booking unpaid (slot masih tersedia)
- **🔴 Merah**: Booking paid/partial (slot akan disilang)

## Business Logic

### Keuntungan Sistem Ini:
1. **Fleksibilitas**: Customer bisa ganti jadwal jika belum bayar
2. **Keamanan**: Slot terkunci setelah ada pembayaran
3. **Cash Flow**: Mendorong customer untuk segera bayar
4. **Manajemen**: Admin bisa track booking vs payment

### Use Cases:
1. **Booking Sementara**: Customer book slot tapi belum bayar
2. **Konfirmasi DP**: Setelah bayar DP, slot terkunci
3. **Pelunasan**: Setelah lunas, slot tetap terkunci
4. **Pembatalan**: Booking unpaid bisa dibatalkan tanpa masalah

## Migration Notes

### Existing Data
- Booking lama tanpa payment_status akan dianggap 'unpaid'
- Kolom payment_status otomatis ditambahkan dengan default 'unpaid'

### Backward Compatibility
- Sistem tetap kompatibel dengan booking yang sudah ada
- Default payment_status = 'unpaid' untuk data lama

## Configuration

### Default Payment Status
```php
// Saat create booking baru
'payment_status' => 'unpaid'
```

### Status Transitions
```
unpaid → partial → paid
unpaid → paid (langsung lunas)
```

## API Response Example

### Slot dengan Mixed Payment Status
```json
[
    {
        "time": "09:00",
        "available": false  // Ada booking paid/partial
    },
    {
        "time": "09:30",
        "available": true   // Tidak ada booking atau booking unpaid
    },
    {
        "time": "10:00", 
        "available": true   // Ada booking tapi unpaid
    }
]
```

## Monitoring

### Query untuk Monitoring
```sql
-- Lihat distribusi payment status
SELECT payment_status, COUNT(*) as total 
FROM booking_online 
GROUP BY payment_status;

-- Lihat slot yang masih bisa direbut (unpaid)
SELECT tanggal_booking, jam_booking, studio_id, nama_customer, payment_status
FROM booking_online 
WHERE payment_status = 'unpaid' 
AND tanggal_booking >= CURDATE()
ORDER BY tanggal_booking, jam_booking;
```

---

**Note**: Logika ini memberikan fleksibilitas lebih kepada customer sambil tetap melindungi slot yang sudah dibayar.