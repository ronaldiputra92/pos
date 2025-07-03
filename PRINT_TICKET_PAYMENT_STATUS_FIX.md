# Fix: Print Ticket Payment Status Issue

## Masalah
Print ticket menampilkan status pembayaran "Belum dibayar" meskipun booking sudah lunas.

## Penyebab
Kode di `print_ticket.php` menggunakan field `$booking->status` untuk menentukan status pembayaran, padahal seharusnya menggunakan `$booking->payment_status`.

## Perbedaan Field

### `status` (Status Booking)
- `pending` = Menunggu konfirmasi
- `confirmed` = Dikonfirmasi
- `completed` = Selesai
- `cancelled` = Dibatalkan

### `payment_status` (Status Pembayaran)
- `unpaid` = Belum dibayar
- `partial` = Dibayar sebagian
- `paid` = Lunas

## Solusi yang Diterapkan

### 1. **Perbaikan Kode Print Ticket**

#### Before (Salah):
```php
<?php if ($booking->status == 'lunas'): ?>
    <span class="badge">Lunas</span>
<?php elseif ($booking->status == 'sebagian'): ?>
    <span class="badge">Sebagian</span>
<?php else: ?>
    <span class="badge">Belum dibayar</span>
<?php endif; ?>
```

#### After (Benar):
```php
<?php 
$payment_status = isset($booking->payment_status) ? $booking->payment_status : 'unpaid';
if ($payment_status == 'paid'): ?>
    <span class="badge">✓ Lunas</span>
<?php elseif ($payment_status == 'partial'): ?>
    <span class="badge">◐ Sebagian</span>
<?php else: ?>
    <span class="badge">✗ Belum Dibayar</span>
<?php endif; ?>
```

### 2. **Peningkatan Tampilan**

#### Pemisahan Status:
- **Status Booking**: Menampilkan status konfirmasi booking
- **Status Pembayaran**: Menampilkan status pembayaran terpisah

#### Visual Indicators:
- ✓ Lunas (Hijau)
- ◐ Sebagian (Kuning)
- ✗ Belum Dibayar (Merah)

#### Informasi Footer:
- Pesan khusus berdasarkan status pembayaran
- Warning untuk pembayaran yang belum lunas
- Konfirmasi untuk pembayaran yang sudah lunas

### 3. **Debug Tools**

#### Debug Print Ticket:
```
http://localhost/kasir/booking/debug_print_ticket/BK-000001
```

#### Test Payment Status:
```
http://localhost/kasir/booking/test_payment_status/BK-000001/paid
```

## Testing

### 1. **Verifikasi Data Booking**
```sql
SELECT kode_booking, nama_customer, status, payment_status 
FROM booking_online 
WHERE kode_booking = 'BK-000001';
```

### 2. **Test Print Ticket**
1. Akses debug URL untuk melihat data booking
2. Update payment status menggunakan test URL
3. Buka print ticket dan verifikasi status yang ditampilkan

### 3. **Skenario Testing**

#### Skenario 1: Booking Unpaid
- Set payment_status = 'unpaid'
- Print ticket harus menampilkan "✗ Belum Dibayar"
- Footer menampilkan warning pembayaran

#### Skenario 2: Booking Partial
- Set payment_status = 'partial'
- Print ticket harus menampilkan "◐ Sebagian"
- Footer menampilkan info sisa pembayaran

#### Skenario 3: Booking Paid
- Set payment_status = 'paid'
- Print ticket harus menampilkan "✓ Lunas"
- Footer menampilkan konfirmasi pembayaran

## File yang Dimodifikasi

### 1. `application/views/booking/print_ticket.php`
- Perbaikan logika status pembayaran
- Penambahan status booking terpisah
- Peningkatan visual indicators
- Informasi footer berdasarkan payment status

### 2. `application/controllers/Booking.php`
- Method `debug_print_ticket()` untuk debugging
- Method `test_payment_status()` untuk testing

## Backward Compatibility

### Handling Missing payment_status:
```php
$payment_status = isset($booking->payment_status) ? $booking->payment_status : 'unpaid';
```

### Default Behavior:
- Jika field `payment_status` tidak ada, default ke 'unpaid'
- Sistem tetap berfungsi dengan data booking lama

## Best Practices

### 1. **Konsistensi Field**
- Selalu gunakan `payment_status` untuk status pembayaran
- Gunakan `status` untuk status booking/konfirmasi

### 2. **Error Handling**
- Selalu cek keberadaan field sebelum menggunakan
- Berikan default value yang masuk akal

### 3. **Visual Clarity**
- Gunakan icon dan warna untuk membedakan status
- Berikan informasi yang jelas di footer

## Monitoring

### Query untuk Monitoring:
```sql
-- Cek konsistensi status
SELECT 
    kode_booking,
    status as booking_status,
    payment_status,
    CASE 
        WHEN payment_status = 'paid' THEN 'OK'
        WHEN payment_status = 'partial' THEN 'PARTIAL'
        WHEN payment_status = 'unpaid' THEN 'UNPAID'
        ELSE 'CHECK'
    END as payment_check
FROM booking_online 
ORDER BY created_at DESC;
```

---

**Result**: Print ticket sekarang menampilkan status pembayaran yang akurat berdasarkan field `payment_status` yang benar.