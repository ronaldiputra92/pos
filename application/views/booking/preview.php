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
        .preview-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .preview-icon {
            font-size: 60px;
            color: #007bff;
            margin-bottom: 20px;
        }

        .booking-preview {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }

        .preview-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .preview-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .preview-label {
            font-weight: bold;
            color: #495057;
        }

        .preview-value {
            color: #212529;
        }

        .header-preview {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .status-confirmed {
            background-color: #28a745;
            color: white;
        }

        .status-completed {
            background-color: #6c757d;
            color: white;
        }

        .status-cancelled {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
    <div class="header-preview">
        <div class="container text-center">
            <h1><i class="fa fa-calendar-check-o"></i> Preview Booking</h1>
            <p>Informasi ringkas booking studio photo</p>
        </div>
    </div>

    <div class="container">
        <div class="preview-container">
            <div class="preview-icon">
                <i class="fa fa-calendar-check-o"></i>
            </div>

            <h2>Informasi Booking</h2>
            <p class="lead">Berikut adalah ringkasan booking Anda:</p>

            <div class="booking-preview">
                <div class="preview-row">
                    <span class="preview-label">Kode Booking:</span>
                    <span class="preview-value"><strong><?php echo $booking->kode_booking ?></strong></span>
                </div>
                <div class="preview-row">
                    <span class="preview-label">Nama Customer:</span>
                    <span class="preview-value"><?php echo $booking->nama_customer ?></span>
                </div>
                <div class="preview-row">
                    <span class="preview-label">Studio:</span>
                    <span class="preview-value">
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
                <div class="preview-row">
                    <span class="preview-label">Tanggal:</span>
                    <span class="preview-value"><?php echo date('d-m-Y', strtotime($booking->tanggal_booking)) ?></span>
                </div>
                <div class="preview-row">
                    <span class="preview-label">Jam:</span>
                    <span class="preview-value"><?php echo $booking->jam_booking ?></span>
                </div>
                <div class="preview-row">
                    <span class="preview-label">Status:</span>
                    <span class="preview-value">
                        <span class="status-badge status-<?php echo $booking->status ?>">
                            <?php
                            switch ($booking->status) {
                                case 'pending':
                                    echo 'Pending';
                                    break;
                                case 'confirmed':
                                    echo 'Dikonfirmasi';
                                    break;
                                case 'completed':
                                    echo 'Selesai';
                                    break;
                                case 'cancelled':
                                    echo 'Dibatalkan';
                                    break;
                                default:
                                    echo ucfirst($booking->status);
                            }
                            ?>
                        </span>
                    </span>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i>
                <strong>Catatan:</strong><br>
                Ini adalah halaman preview yang hanya menampilkan informasi dasar booking.
            </div>

            <div class="text-center">
                <a href="<?php echo base_url('booking') ?>" class="btn btn-primary btn-md">
                    <i class="fa fa-plus"></i> Booking Baru
                </a>
                <!-- <a href="<?php echo base_url('booking/admin') ?>" class="btn btn-default btn-md">
                    <i class="fa fa-list"></i> Lihat Semua Booking
                </a> -->
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/') ?>vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/') ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>