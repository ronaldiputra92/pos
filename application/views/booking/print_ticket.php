<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .ticket {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border: 2px dashed #333;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .ticket-header {
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .ticket-header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }

        .ticket-header p {
            margin: 5px 0;
            color: #666;
        }

        .booking-code {
            text-align: center;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .booking-code h2 {
            margin: 0;
            color: #007bff;
            font-size: 20px;
        }

        .ticket-details {
            margin: 15px 0;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            padding: 5px 0;
            border-bottom: 1px dotted #ddd;
        }

        .detail-label {
            font-weight: bold;
            color: #333;
        }

        .detail-value {
            color: #666;
        }

        .employee-name {
            color: #007bff;
            font-weight: bold;
        }

        .qr-code {
            text-align: center;
            margin: 20px 0;
        }

        .ticket-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        @media print {
            body {
                background: white;
            }

            .no-print {
                display: none;
            }

            .ticket {
                border: 2px solid #333;
                box-shadow: none;
                max-width: none;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fa fa-print"></i> Print Tiket
        </button>
        <button onclick="window.close()" class="btn btn-default">
            <i class="fa fa-times"></i> Tutup
        </button>
    </div>

    <div class="ticket">
        <div class="ticket-header">
            <h1>Tiket Booking</h1>
            <p>Dena Studio Photo</p>
        </div>

        <div class="booking-code">
            <h2><?php echo $booking->kode_booking ?></h2>
            <p>Kode Booking</p>
        </div>

        <div class="ticket-details">
            <div class="detail-row">
                <span class="detail-label">Nama Customer:</span>
                <span class="detail-value"><?php echo $booking->nama_customer ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Karyawan:</span>
                <span class="detail-value employee-name"><?php echo $booking->nama_karyawan ? $booking->nama_karyawan : '-' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Telepon:</span>
                <span class="detail-value"><?php echo $booking->telp_customer ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value"><?php echo $booking->email_customer ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Studio:</span>
                <span class="detail-value">
                    <?php
                    switch ($booking->studio_id) {
                        case 'Studio 1':
                            echo 'Studio 1 (Self Photo)';
                            break;
                        case 'Studio 2':
                            echo 'Studio 2 (Self Photo)';
                            break;
                        case 'Studio 3':
                            echo 'Studio 3 (Wide Photobox)';
                            break;
                        case 'Studio 4':
                            echo 'Studio 4 (Photo Elevator)';
                            break;
                        default:
                            echo $booking->studio_id;
                    }
                    ?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tanggal:</span>
                <span class="detail-value"><?php echo date('d F Y', strtotime($booking->tanggal_booking)) ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Jam:</span>
                <span class="detail-value"><?php echo $booking->jam_booking ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Durasi:</span>
                <span class="detail-value"><?php echo $booking->durasi ?> menit</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    <span class="status-badge status-<?php echo $booking->status ?>">
                        <?php echo ucfirst($booking->status) ?>
                    </span>
                </span>
            </div>
            <?php if ($booking->catatan): ?>
                <div class="detail-row">
                    <span class="detail-label">Catatan:</span>
                    <span class="detail-value"><?php echo $booking->catatan ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="qr-code">
            <!-- QR Code bisa ditambahkan di sini -->
            <div style="border: 1px solid #ddd; padding: 20px; background: #f9f9f9;">
                <strong>QR Code</strong><br>
                <small><?php echo $booking->kode_booking ?></small>
            </div>
        </div>

        <div class="ticket-footer">
            <p><strong>PENTING:</strong></p>
            <p>• Harap datang 15 menit sebelum jadwal</p>
            <p>• Tunjukkan tiket ini saat check-in</p>
            <p>• Hubungi kami jika ada perubahan jadwal</p>
            <p>• Tiket ini berlaku untuk 1 sesi foto</p>
            <?php if ($booking->nama_karyawan): ?>
                <p>• Karyawan yang bertugas: <strong><?php echo $booking->nama_karyawan ?></strong></p>
            <?php endif; ?>
            <hr>
            <p>Dicetak pada: <?php echo date('d F Y H:i') ?></p>
            <p>Terima kasih telah memilih layanan kami!</p>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>

</html>