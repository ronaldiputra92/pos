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
        .booking-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .studio-card {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            cursor: pointer;
            transition: all 0.3s;
        }

        .studio-card:hover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }

        .studio-card.selected {
            border-color: #007bff;
            background-color: #e3f2fd;
        }

        .time-slot {
            display: inline-block;
            margin: 5px;
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .time-slot:hover {
            background-color: #007bff;
            color: white;
        }

        .time-slot.selected {
            background-color: #007bff;
            color: white;
        }

        .time-slot.unavailable {
            background-color: #dc3545;
            color: white;
            cursor: not-allowed;
        }

        .header-booking {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="header-booking">
        <div class="container text-center">
            <h1><i class="fa fa-camera"></i> Booking Online Studio Photo</h1>
            <p>Reservasi studio photo Anda dengan mudah dan cepat</p>
        </div>
    </div>

    <div class="container">
        <div class="booking-container">
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <form id="bookingForm" method="post" action="<?php echo base_url('booking/process_booking') ?>">
                <!-- Data Customer -->
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4><i class="fa fa-user"></i> Data Customer</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- pilih karyawan -->
                                <div class="form-group">
                                    <label>Pilih Karyawan *</label>
                                    <select name="id_karyawan" class="form-control" required>
                                        <option value="">-- Pilih Karyawan --</option>
                                        <?php foreach ($karyawan as $k): ?>
                                            <option value="<?php echo $k['id_karyawan'] ?>"><?php echo $k['nama_karyawan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Customer *</label>
                                    <input type="text" name="nama_customer" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. Telepon *</label>
                                    <input type="text" name="telp_customer" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email_customer" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Pilih Studio -->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4><i class="fa fa-building"></i> Pilih Studio</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <?php foreach ($studios as $key => $studio): ?>
                                <div class="col-md-6">
                                    <div class="studio-card" data-studio="<?php echo $key ?>">
                                        <input type="radio" name="studio_id" value="<?php echo $key ?>" style="display: none;">
                                        <h5><strong><?php echo $studio ?></strong></h5>
                                        <p class="text-muted">
                                            <?php
                                            switch ($key) {
                                                case 'Studio 1':
                                                case 'Studio 2':
                                                    echo 'Cocok untuk foto selfie dan portrait';
                                                    break;
                                                case 'Studio 3':
                                                    echo 'Ruang luas untuk foto grup dan keluarga';
                                                    break;
                                                case 'Studio 4':
                                                    echo 'Studio dengan konsep elevator yang unik';
                                                    break;
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Pilih Tanggal dan Jam -->
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h4><i class="fa fa-calendar"></i> Pilih Tanggal & Jam</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Booking *</label>
                                    <input type="date" name="tanggal_booking" id="tanggal_booking" class="form-control" min="<?php echo date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Durasi (menit)</label>
                                    <select name="durasi" class="form-control">
                                        <option value="20">20 menit</option>
                                        <option value="30">30 menit</option>
                                        <option value="40">40 menit</option>
                                        <option value="50">50 menit</option>
                                        <option value="60">60 menit</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Pilih Jam *</label>
                            <div id="time-slots">
                                <p class="text-muted">Silakan pilih studio dan tanggal terlebih dahulu</p>
                            </div>
                            <input type="hidden" name="jam_booking" id="jam_booking" required>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-comment"></i> Catatan Tambahan</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan khusus untuk booking Anda (opsional)"></textarea>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa fa-check"></i> Konfirmasi Booking
                    </button>
                    <a href="<?php echo base_url() ?>" class="btn btn-default btn-lg">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="<?php echo base_url('assets/') ?>vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/') ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
        var base_url = "<?php echo base_url() ?>";

        $(document).ready(function() {
            // Studio selection
            $('.studio-card').click(function() {
                $('.studio-card').removeClass('selected');
                $(this).addClass('selected');
                $(this).find('input[type="radio"]').prop('checked', true);
                loadTimeSlots();
            });

            // Date change
            $('#tanggal_booking').change(function() {
                loadTimeSlots();
            });

            // Time slot selection
            $(document).on('click', '.time-slot:not(.unavailable)', function() {
                $('.time-slot').removeClass('selected');
                $(this).addClass('selected');
                $('#jam_booking').val($(this).data('time'));
            });

            function loadTimeSlots() {
                var studio = $('input[name="studio_id"]:checked').val();
                var tanggal = $('#tanggal_booking').val();

                if (studio && tanggal) {
                    $.ajax({
                        url: base_url + 'booking/get_available_slots',
                        type: 'POST',
                        data: {
                            studio_id: studio,
                            tanggal: tanggal
                        },
                        dataType: 'json',
                        success: function(response) {
                            var html = '';
                            if (response.length > 0) {
                                response.forEach(function(time) {
                                    html += '<span class="time-slot" data-time="' + time + '">' + time + '</span>';
                                });
                            } else {
                                html = '<p class="text-danger">Tidak ada slot waktu yang tersedia untuk tanggal ini</p>';
                            }
                            $('#time-slots').html(html);
                        },
                        error: function() {
                            $('#time-slots').html('<p class="text-danger">Terjadi kesalahan saat memuat slot waktu</p>');
                        }
                    });
                }
            }

            // Form validation
            $('#bookingForm').submit(function(e) {
                var isValid = true;
                var errorMessage = '';

                // Check employee selection
                if (!$('select[name="id_karyawan"]').val()) {
                    errorMessage += '- Silakan pilih karyawan\n';
                    isValid = false;
                }

                // Check customer name
                if (!$('input[name="nama_customer"]').val().trim()) {
                    errorMessage += '- Nama customer harus diisi\n';
                    isValid = false;
                }

                // Check phone
                if (!$('input[name="telp_customer"]').val().trim()) {
                    errorMessage += '- No. telepon harus diisi\n';
                    isValid = false;
                }

                // Check email
                if (!$('input[name="email_customer"]').val().trim()) {
                    errorMessage += '- Email harus diisi\n';
                    isValid = false;
                }

                // Check studio selection
                if (!$('input[name="studio_id"]:checked').val()) {
                    errorMessage += '- Silakan pilih studio\n';
                    isValid = false;
                }

                // Check date
                if (!$('#tanggal_booking').val()) {
                    errorMessage += '- Tanggal booking harus dipilih\n';
                    isValid = false;
                }

                // Check time
                if (!$('#jam_booking').val()) {
                    errorMessage += '- Silakan pilih jam booking\n';
                    isValid = false;
                }

                if (!isValid) {
                    alert('Mohon lengkapi data berikut:\n' + errorMessage);
                    e.preventDefault();
                    return false;
                }

                // Show loading
                $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
            });
        });
    </script>

    <script>
        // alert akan menghilang setelah 3 detik
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    </script>
</body>

</html>