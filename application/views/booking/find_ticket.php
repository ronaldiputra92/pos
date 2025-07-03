<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Tiket Booking</title>
    <link href="<?php echo base_url('assets/') ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/') ?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/') ?>build/css/custom.min.css" rel="stylesheet">
    <style>
        .find-ticket-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .header-find {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header-find">
        <div class="container">
            <h1><i class="fa fa-search"></i> Cari Tiket Booking Anda</h1>
            <p>Masukkan kode booking Anda untuk melihat detail tiket.</p>
        </div>
    </div>

    <div class="container">
        <div class="find-ticket-container">
            <h2 class="text-center">Temukan Tiket Anda</h2>
            <p class="text-center text-muted">Lupa menyimpan tiket Anda? Tidak masalah. Cukup masukkan kode booking di bawah ini.</p>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo base_url('booking/show_ticket'); ?>" method="post" class="form-horizontal">
                <div class="form-group">
                    <label for="kode_booking" class="control-label">Kode Booking</label>
                    <input type="text" class="form-control" id="kode_booking" name="kode_booking" placeholder="Contoh: BK-12345678" required>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-search"></i> Cari Tiket</button>
                    <a href="<?php echo base_url('booking') ?>" class="btn btn-default btn-lg">Kembali</a>
                </div>
            </form>
        </div>
    </div>

    <script src="<?php echo base_url('assets/') ?>vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/') ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>