<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link href="<?php echo base_url('assets/') ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/') ?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .report-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .report-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .report-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .payment-unpaid {
            background-color: #f8d7da;
            color: #721c24;
        }

        .payment-partial {
            background-color: #fff3cd;
            color: #856404;
        }

        .payment-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .report-summary {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fa fa-print"></i> Print Report
        </button>
        <button onclick="window.close()" class="btn btn-default">
            <i class="fa fa-times"></i> Tutup
        </button>
        <a href="<?php echo base_url('booking/generate_report?' . $_SERVER['QUERY_STRING'] . '&format=excel') ?>" class="btn btn-success">
            <i class="fa fa-file-excel-o"></i> Download Excel
        </a>
    </div>

    <div class="report-header">
        <h1>LAPORAN DATA BOOKING</h1>
        <?php if (isset($toko) && $toko): ?>
            <h3><?php echo $toko->nama_perusahaan ?></h3>
            <p><?php echo $toko->alamat_perusahaan ?></p>
        <?php else: ?>
            <h3>Dema Studio Photo</h3>
        <?php endif; ?>
        <p>Periode: <?php echo date('d-m-Y', strtotime($start_date)) ?> s/d <?php echo date('d-m-Y', strtotime($end_date)) ?></p>
    </div>

    <div class="report-info">
        <div class="row">
            <div class="col-md-6">
                <strong>Filter yang Diterapkan:</strong><br>
                - Tanggal: <?php echo date('d-m-Y', strtotime($start_date)) ?> s/d <?php echo date('d-m-Y', strtotime($end_date)) ?><br>
                - Status Booking: <?php echo $status_filter ? ucfirst($status_filter) : 'Semua' ?><br>
                - Status Pembayaran: <?php echo $payment_filter ? ucfirst($payment_filter) : 'Semua' ?><br>
                - Studio: <?php echo $studio_filter ? $studio_filter : 'Semua' ?>
            </div>
            <div class="col-md-6">
                <strong>Ringkasan:</strong><br>
                - Total Records: <?php echo $total_records ?><br>
                - Tanggal Generate: <?php echo date('d-m-Y H:i:s') ?>
            </div>
        </div>
    </div>

    <?php if (empty($booking_data)): ?>
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle"></i> Tidak ada data booking yang ditemukan untuk periode dan filter yang dipilih.
        </div>
    <?php else: ?>
        <table class="report-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Booking</th>
                    <th>Nama Customer</th>
                    <th>Telepon</th>
                    <th>Karyawan</th>
                    <th>Studio</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($booking_data as $row): ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $row->kode_booking ?></td>
                        <td><?php echo $row->nama_customer ?></td>
                        <td><?php echo $row->telp_customer ?></td>
                        <td><?php echo $row->nama_karyawan ? $row->nama_karyawan : '-' ?></td>
                        <td>
                            <?php
                            switch ($row->studio_id) {
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
                                    echo $row->studio_id;
                            }
                            ?>
                        </td>
                        <td><?php echo date('d-m-Y', strtotime($row->tanggal_booking)) ?></td>
                        <td><?php echo $row->jam_booking ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $row->status ?>">
                                <?php echo ucfirst($row->status) ?>
                            </span>
                        </td>
                        <td>
                            <?php
                            $payment_status = isset($row->payment_status) ? $row->payment_status : 'unpaid';
                            $payment_text = '';
                            switch ($payment_status) {
                                case 'paid':
                                    $payment_text = 'Lunas';
                                    break;
                                case 'partial':
                                    $payment_text = 'Sebagian';
                                    break;
                                case 'unpaid':
                                default:
                                    $payment_text = 'Belum Bayar';
                                    break;
                            }
                            ?>
                            <span class="status-badge payment-<?php echo $payment_status ?>">
                                <?php echo $payment_text ?>
                            </span>
                        </td>
                        <td><?php echo $row->catatan ? $row->catatan : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="report-summary">
            <h4>Ringkasan Data</h4>
            <div class="row">
                <div class="col-md-6">
                    <strong>Status Booking:</strong><br>
                    <?php
                    $status_count = array();
                    foreach ($booking_data as $row) {
                        $status = $row->status;
                        $status_count[$status] = isset($status_count[$status]) ? $status_count[$status] + 1 : 1;
                    }
                    foreach ($status_count as $status => $count) {
                        echo "- " . ucfirst($status) . ": " . $count . " booking<br>";
                    }
                    ?>
                </div>
                <div class="col-md-6">
                    <strong>Status Pembayaran:</strong><br>
                    <?php
                    $payment_count = array();
                    foreach ($booking_data as $row) {
                        $payment = isset($row->payment_status) ? $row->payment_status : 'unpaid';
                        $payment_count[$payment] = isset($payment_count[$payment]) ? $payment_count[$payment] + 1 : 1;
                    }
                    foreach ($payment_count as $payment => $count) {
                        $payment_text = '';
                        switch ($payment) {
                            case 'paid':
                                $payment_text = 'Lunas';
                                break;
                            case 'partial':
                                $payment_text = 'Sebagian';
                                break;
                            case 'unpaid':
                            default:
                                $payment_text = 'Belum Bayar';
                                break;
                        }
                        echo "- " . $payment_text . ": " . $count . " booking<br>";
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
        <p>Laporan ini digenerate pada: <?php echo date('d-m-Y H:i:s') ?></p>
        <p>© <?php echo date('Y') ?> - Sistem Manajemen Booking</p>
    </div>

    <script src="<?php echo base_url('assets/') ?>vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/') ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>