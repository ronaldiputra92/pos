<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href="<?php echo base_url('assets/') ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/') ?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/') ?>build/css/custom.min.css" rel="stylesheet">
    <style>
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }

        .booking-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            font-weight: bold;
            color: #495057;
        }

        .detail-value {
            color: #212529;
        }

        .header-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="header-success">
        <div class="container text-center">
            <h1><i class="fa fa-check-circle"></i> Booking Berhasil!</h1>
            <p>Terima kasih telah melakukan reservasi studio photo</p>
        </div>
    </div>

    <div class="container">
        <div class="success-container">
            <div class="success-icon">
                <i class="fa fa-check-circle"></i>
            </div>

            <h2>Booking Berhasil Dibuat!</h2>
            <p class="lead">Booking Anda telah berhasil disimpan. Berikut adalah detail booking Anda:</p>

            <div class="booking-details">
                <div class="detail-row">
                    <span class="detail-label">Kode Booking:</span>
                    <span class="detail-value"><strong><?php echo $booking->kode_booking ?></strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nama Customer:</span>
                    <span class="detail-value"><?php echo $booking->nama_customer ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Karyawan:</span>
                    <span class="detail-value"><?php echo $booking->nama_karyawan ? $booking->nama_karyawan : '-' ?></span>
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
                    <span class="detail-value"><?php echo date('d-m-Y', strtotime($booking->tanggal_booking)) ?></span>
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
                        <span class="label label-warning">Pending</span>
                    </span>
                </div>
                <?php if ($booking->catatan): ?>
                    <div class="detail-row">
                        <span class="detail-label">Catatan:</span>
                        <span class="detail-value"><?php echo $booking->catatan ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i>
                <strong>Informasi Penting:</strong><br>
                - Harap datang 15 menit sebelum jadwal booking Anda<br>
                - Simpan kode booking ini untuk referensi<br>
                - Status booking akan dikonfirmasi oleh admin
            </div>

            <div class="text-center">
                <a href="<?php echo base_url('booking/print_ticket/' . $booking->kode_booking) ?>" class="btn btn-success btn-lg" target="_blank">
                    <i class="fa fa-print"></i> Print Tiket
                </a>
                <a href="<?php echo base_url('booking') ?>" class="btn btn-primary btn-lg">
                    <i class="fa fa-plus"></i> Booking Lagi
                </a>
                <a href="<?php echo base_url('booking/admin') ?>" class="btn btn-default btn-lg">
                    <i class="fa fa-home"></i> Beranda
                </a>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/') ?>vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/') ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>