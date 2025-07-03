<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><?php echo $title ?></h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Daftar Booking Online</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li class="nav-item">
                                <div class="btn-group btn-group-sm d-flex gap-2">
                                    <button class="btn btn-outline-primary" onclick="showReportModal()" title="Generate Report">
                                        <i class="fa fa-file-text-o"></i> Rekap Laporan
                                    </button>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="collapse-link" title="Collapse Panel">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="close-link" title="Close Panel">
                                    <i class="fa fa-close"></i>
                                </a>
                            </li>
                        </ul>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <?php if ($this->session->flashdata('message')): ?>
                            <div class="alert-container">
                                <?php echo $this->session->flashdata('message'); ?>
                            </div>
                        <?php endif; ?>

                        <table id="databooking" width="100%" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode Booking</th>
                                    <th>Nama Customer</th>
                                    <th>Telepon</th>
                                    <th>Karyawan</th>
                                    <th>Studio</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($booking as $row): ?>
                                    <tr>
                                        <td><?php echo $row->kode_booking ?></td>
                                        <td><?php echo $row->nama_customer ?></td>
                                        <?php if ($this->session->userdata('username') == 'admin' || $this->session->userdata('username') == 'kasir'): ?>
                                            <td><?php echo $row->telp_customer ?></td>
                                        <?php endif; ?>
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
                                            <?php
                                            $badge_class = '';
                                            switch ($row->status) {
                                                case 'pending':
                                                    $badge_class = 'label-warning';
                                                    break;
                                                case 'completed':
                                                    $badge_class = 'label-success';
                                                    break;
                                                case 'cancelled':
                                                    $badge_class = 'label-danger';
                                                    break;
                                            }
                                            ?>
                                            <span class="label <?php echo $badge_class ?>"><?php echo ucfirst($row->status) ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $payment_status = isset($row->payment_status) ? $row->payment_status : 'unpaid';
                                            $payment_badge_class = '';
                                            switch ($payment_status) {
                                                case 'paid':
                                                    $payment_badge_class = 'label-success';
                                                    $payment_text = 'Lunas';
                                                    break;
                                                case 'partial':
                                                    $payment_badge_class = 'label-warning';
                                                    $payment_text = 'Sebagian';
                                                    break;
                                                case 'unpaid':
                                                default:
                                                    $payment_badge_class = 'label-danger';
                                                    $payment_text = 'Belum Bayar';
                                                    break;
                                            }
                                            ?>
                                            <span id="payment_status_<?php echo $row->kode_booking ?>" class="label <?php echo $payment_badge_class ?>"><?php echo $payment_text ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">
                                                    Status <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#" onclick="updateStatus('<?php echo $row->kode_booking ?>', 'pending')">Menunggu</a></li>
                                                    <li><a href="#" onclick="updateStatus('<?php echo $row->kode_booking ?>', 'completed')">Selesai</a></li>
                                                    <li><a href="#" onclick="updateStatus('<?php echo $row->kode_booking ?>', 'cancelled')">Batal</a></li>
                                                </ul>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
                                                    Bayar <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#" onclick="updatePaymentStatus('<?php echo $row->kode_booking ?>', 'unpaid')">Belum Bayar</a></li>
                                                    <li><a href="#" onclick="updatePaymentStatus('<?php echo $row->kode_booking ?>', 'partial')">Sebagian</a></li>
                                                    <li><a href="#" onclick="updatePaymentStatus('<?php echo $row->kode_booking ?>', 'paid')">Lunas</a></li>
                                                </ul>
                                            </div>
                                            <a href="<?php echo base_url('booking/print_ticket/' . $row->kode_booking) ?>" class="btn btn-xs btn-success" target="_blank" title="Print Tiket">
                                                <i class="fa fa-print"></i>
                                            </a>
                                            <a href="#" class="btn btn-xs btn-warning" onclick="viewBooking('<?php echo $row->kode_booking ?>')" title="Lihat Detail">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-xs btn-primary" onclick="editBooking('<?php echo $row->kode_booking ?>')" title="Edit Booking">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <?php if ($this->session->userdata('username') == 'admin'): ?>
                                                <a href="<?php echo base_url('booking/delete_booking/' . $row->kode_booking) ?>" class="btn btn-xs btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus booking ini?')" title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Booking -->
<div class="modal fade" id="editBookingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">Edit Booking</h4>
            </div>
            <form method="post" action="<?php echo base_url('booking/update_booking') ?>">
                <div class="modal-body">
                    <input type="hidden" id="edit_booking_id" name="edit_booking_id">
                    <?php $canViewSensitiveData = ($this->session->userdata('username') == 'admin' || $this->session->userdata('username') == 'kasir'); ?>

                    <div class="row">
                        <div class="col-md-<?php echo $canViewSensitiveData ? '6' : '12'; ?>">
                            <div class="form-group">
                                <label>Nama Customer *</label>
                                <input type="text" name="edit_nama_customer" id="edit_nama_customer" class="form-control" required>
                            </div>
                        </div>
                        <?php if ($canViewSensitiveData): ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. Telepon *</label>
                                    <input type="text" name="edit_telp_customer" id="edit_telp_customer" class="form-control" required>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <?php if ($canViewSensitiveData): ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" name="edit_email_customer" id="edit_email_customer" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                            <?php else: ?>
                                <div class="col-md-12">
                                <?php endif; ?>
                                <div class="form-group">
                                    <label>Nama Karyawan *</label>
                                    <select name="edit_id_karyawan" id="edit_id_karyawan" class="form-control" required>
                                        <option value="">--Pilih Karyawan--</option>
                                        <?php foreach ($karyawan as $k): ?>
                                            <option value="<?php echo $k['id_karyawan'] ?>"><?php echo $k['nama_karyawan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Studio *</label>
                                        <select name="edit_studio_id" id="edit_studio_id" class="form-control" required>
                                            <option value="">--Pilih Studio--</option>
                                            <option value="Studio 1">Studio 1 (Self Photo)</option>
                                            <option value="Studio 2">Studio 2 (Self Photo)</option>
                                            <option value="Studio 3">Studio 3 (Wide Photobox)</option>
                                            <option value="Studio 4">Studio 4 (Photo Elevator)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal Booking *</label>
                                        <input type="date" name="edit_tanggal_booking" id="edit_tanggal_booking" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jam Booking *</label>
                                        <select name="edit_jam_booking" id="edit_jam_booking" class="form-control" required>
                                            <option value="">--Pilih Jam--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status Pembayaran</label>
                                        <select name="edit_payment_status" id="edit_payment_status" class="form-control">
                                            <option value="unpaid">Belum Bayar</option>
                                            <option value="partial">Sebagian</option>
                                            <option value="paid">Lunas</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <label>Catatan</label>
                                <textarea name="edit_catatan" id="edit_catatan" class="form-control" rows="3"></textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Booking</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal View Booking Details -->
<div class="modal fade" id="viewBookingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">Detail Booking</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Kode Booking:</strong></td>
                                <td id="view_kode_booking">-</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Customer:</strong></td>
                                <td id="view_nama_customer">-</td>
                            </tr>
                            <?php if ($this->session->userdata('username') == 'admin' || $this->session->userdata('username') == 'kasir'): ?>
                                <tr>
                                    <td><strong>No. Telepon:</strong></td>
                                    <td id="view_telp_customer">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td id="view_email_customer">-</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td><strong>Karyawan:</strong></td>
                                <td id="view_karyawan">-</td>
                            </tr>
                            <tr>
                                <td><strong>Studio:</strong></td>
                                <td id="view_studio">-</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Tanggal Booking:</strong></td>
                                <td id="view_tanggal_booking">-</td>
                            </tr>
                            <tr>
                                <td><strong>Jam Booking:</strong></td>
                                <td id="view_jam_booking">-</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td id="view_status">-</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Dibuat:</strong></td>
                                <td id="view_created_at">-</td>
                            </tr>
                            <tr>
                                <td><strong>Status Pembayaran:</strong></td>
                                <td id="view_payment_status">-</td>
                            </tr>

                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><strong>Catatan:</strong></label>
                            <div id="view_catatan" class="well well-sm" style="min-height: 60px; background-color: #f9f9f9;">-</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="printTicket()">
                    <i class="fa fa-print"></i> Print Tiket
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Report -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">Generate Report Booking</h4>
            </div>
            <div class="modal-body">
                <form id="reportForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" id="start_date" class="form-control" value="<?php echo date('Y-m-01') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Akhir</label>
                                <input type="date" id="end_date" class="form-control" value="<?php echo date('Y-m-d') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Booking</label>
                                <select id="status_filter" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Pembayaran</label>
                                <select id="payment_filter" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="unpaid">Belum Bayar</option>
                                    <option value="partial">Sebagian</option>
                                    <option value="paid">Lunas</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Studio</label>
                                <select id="studio_filter" class="form-control">
                                    <option value="">Semua Studio</option>
                                    <option value="Studio 1">Studio 1 (Self Photo)</option>
                                    <option value="Studio 2">Studio 2 (Self Photo)</option>
                                    <option value="Studio 3">Studio 3 (Wide Photobox)</option>
                                    <option value="Studio 4">Studio 4 (Photo Elevator)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Format Report</label>
                                <select id="report_format" class="form-control">
                                    <option value="html">HTML (Preview)</option>
                                    <option value="excel">Excel (.xlsx)</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="generateReport()">
                    <i class="fa fa-file-text-o"></i> Generate Report
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var base_url = "<?php echo base_url(); ?>";
    var canViewSensitiveData = <?php echo ($this->session->userdata('username') == 'admin' || $this->session->userdata('username') == 'kasir') ? 'true' : 'false'; ?>;

    function updateStatus(bookingId, status) {
        if (confirm('Apakah Anda yakin ingin mengubah status booking ini?')) {
            $.ajax({
                url: base_url + 'booking/update_status',
                type: 'POST',
                data: {
                    booking_id: bookingId,
                    status: status
                },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengupdate status');
                }
            });
        }
    }

    function updatePaymentStatus(bookingId, paymentStatus) {
        if (confirm('Apakah Anda yakin ingin mengubah status pembayaran ini?')) {
            $.ajax({
                url: base_url + 'booking/update_payment_status',
                type: 'POST',
                dataType: 'json',
                data: {
                    booking_id: bookingId,
                    payment_status: paymentStatus
                },
                success: function(response) {
                    if (response.success) {
                        // Update badge on table without reload
                        var badgeClass = '';
                        var badgeText = '';
                        switch (paymentStatus) {
                            case 'paid':
                                badgeClass = 'label-success';
                                badgeText = 'Lunas';
                                break;
                            case 'partial':
                                badgeClass = 'label-warning';
                                badgeText = 'Sebagian';
                                break;
                            case 'unpaid':
                            default:
                                badgeClass = 'label-danger';
                                badgeText = 'Belum Bayar';
                                break;
                        }
                        var badge = $('#payment_status_' + bookingId);
                        badge.removeClass('label-success label-warning label-danger').addClass(badgeClass).text(badgeText);

                        // Show success message
                        alert('Status pembayaran berhasil diupdate!');
                    } else {
                        alert('Gagal mengupdate status pembayaran: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', xhr.responseText);
                    alert('Terjadi kesalahan saat mengupdate status pembayaran');
                }
            });
        }
    }

    function editBooking(bookingId) {
        $.ajax({
            url: base_url + 'booking/get_booking_data/' + bookingId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#edit_booking_id').val(data.kode_booking);
                $('#edit_nama_customer').val(data.nama_customer);
                if (canViewSensitiveData) {
                    $('#edit_telp_customer').val(data.telp_customer);
                    $('#edit_email_customer').val(data.email_customer);
                }
                $('#edit_id_karyawan').val(data.id_karyawan);
                $('#edit_studio_id').val(data.studio_id);
                $('#edit_tanggal_booking').val(data.tanggal_booking);
                $('#edit_catatan').val(data.catatan);
                $('#edit_payment_status').val(data.payment_status || 'unpaid');
                $('#edit_payment_amount').val(data.payment_amount || '');

                loadEditTimeSlots(data.tanggal_booking, data.studio_id, data.jam_booking, bookingId);
                $('#editBookingModal').modal('show');
            },
            error: function() {
                alert('Terjadi kesalahan saat memuat data booking');
            }
        });
    }

    function loadEditTimeSlots(tanggal, studio, currentTime, bookingId) {
        $.ajax({
            url: base_url + 'booking/get_available_slots_edit',
            type: 'POST',
            data: {
                tanggal: tanggal,
                studio_id: studio,
                booking_id: bookingId
            },
            dataType: 'json',
            success: function(slots) {
                var html = '<option value="">--Pilih Jam--</option>';
                slots.forEach(function(time) {
                    var selected = (time == currentTime) ? 'selected' : '';
                    html += '<option value="' + time + '" ' + selected + '>' + time + '</option>';
                });
                if (slots.indexOf(currentTime) === -1) {
                    html += '<option value="' + currentTime + '" selected>' + currentTime + '</option>';
                }
                $('#edit_jam_booking').html(html);
            },
            error: function() {
                alert('Terjadi kesalahan saat memuat slot waktu');
            }
        });
    }

    function viewBooking(bookingId) {
        $('#viewBookingModal').modal('show');
        $.ajax({
            url: base_url + 'booking/get_booking_details',
            type: 'POST',
            data: {
                booking_id: bookingId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var booking = response.data;
                    $('#view_kode_booking').text(booking.kode_booking || '-');
                    $('#view_nama_customer').text(booking.nama_customer || '-');

                    // Show/hide sensitive data based on role
                    if (canViewSensitiveData) {
                        $('#view_telp_customer').closest('tr').show();
                        $('#view_email_customer').closest('tr').show();
                        $('#view_telp_customer').text(booking.telp_customer || '-');
                        $('#view_email_customer').text(booking.email_customer || '-');
                    } else {
                        $('#view_telp_customer').closest('tr').hide();
                        $('#view_email_customer').closest('tr').hide();
                    }

                    $('#view_karyawan').text(booking.nama_karyawan || '-');
                    var studioName = booking.studio_id || '-';
                    switch (booking.studio_id) {
                        case 'Studio 1':
                            studioName = 'Studio 1 (Self Photo)';
                            break;
                        case 'Studio 2':
                            studioName = 'Studio 2 (Self Photo)';
                            break;
                        case 'Studio 3':
                            studioName = 'Studio 3 (Wide Photobox)';
                            break;
                        case 'Studio 4':
                            studioName = 'Studio 4 (Photo Elevator)';
                            break;
                    }
                    $('#view_studio').text(studioName);

                    if (booking.tanggal_booking) {
                        var date = new Date(booking.tanggal_booking);
                        var formattedDate = ('0' + date.getDate()).slice(-2) + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + date.getFullYear();
                        $('#view_tanggal_booking').text(formattedDate);
                    } else {
                        $('#view_tanggal_booking').text('-');
                    }

                    $('#view_jam_booking').text(booking.jam_booking || '-');

                    var statusBadge = '';
                    switch (booking.status) {
                        case 'pending':
                            statusBadge = '<span class="label label-warning">Pending</span>';
                            break;
                        case 'completed':
                            statusBadge = '<span class="label label-success">Completed</span>';
                            break;
                        case 'cancelled':
                            statusBadge = '<span class="label label-danger">Cancelled</span>';
                            break;
                        default:
                            statusBadge = booking.status || '-';
                    }
                    $('#view_status').html(statusBadge);

                    if (booking.created_at) {
                        var createdDate = new Date(booking.created_at);
                        var formattedCreatedDate = ('0' + createdDate.getDate()).slice(-2) + '-' + ('0' + (createdDate.getMonth() + 1)).slice(-2) + '-' + createdDate.getFullYear() + ' ' + ('0' + createdDate.getHours()).slice(-2) + ':' + ('0' + createdDate.getMinutes()).slice(-2);
                        $('#view_created_at').text(formattedCreatedDate);
                    } else {
                        $('#view_created_at').text('-');
                    }

                    $('#view_catatan').text(booking.catatan || 'Tidak ada catatan');

                    // Payment status
                    var paymentStatus = booking.payment_status || 'unpaid';
                    var paymentBadge = '';
                    switch (paymentStatus) {
                        case 'paid':
                            paymentBadge = '<span class="label label-success">Lunas</span>';
                            break;
                        case 'partial':
                            paymentBadge = '<span class="label label-warning">Sebagian</span>';
                            break;
                        case 'unpaid':
                        default:
                            paymentBadge = '<span class="label label-danger">Belum Bayar</span>';
                            break;
                    }
                    $('#view_payment_status').html(paymentBadge);

                    // Payment amount
                    var paymentAmount = booking.payment_amount || 0;
                    $('#view_payment_amount').text(paymentAmount > 0 ? 'Rp ' + number_format(paymentAmount, 0, ',', '.') : '-');

                    $('#viewBookingModal').data('booking-id', bookingId);
                } else {
                    alert('Gagal memuat detail booking: ' + (response.message || 'Unknown error'));
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat memuat detail booking');
            }
        });
    }

    function printTicket() {
        var bookingId = $('#viewBookingModal').data('booking-id');
        if (bookingId) {
            window.open(base_url + 'booking/print_ticket/' + bookingId, '_blank');
        }
    }

    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    function showReportModal() {
        $('#reportModal').modal('show');
    }

    function generateReport() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        var statusFilter = $('#status_filter').val();
        var paymentFilter = $('#payment_filter').val();
        var studioFilter = $('#studio_filter').val();
        var reportFormat = $('#report_format').val();

        if (!startDate || !endDate) {
            alert('Harap pilih tanggal mulai dan tanggal akhir');
            return;
        }

        if (new Date(startDate) > new Date(endDate)) {
            alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
            return;
        }

        var params = {
            start_date: startDate,
            end_date: endDate,
            status_filter: statusFilter,
            payment_filter: paymentFilter,
            studio_filter: studioFilter,
            format: reportFormat
        };

        var queryString = $.param(params);
        var url = base_url + 'booking/generate_report?' + queryString;

        if (reportFormat === 'html') {
            // Open in new window for HTML preview
            window.open(url, '_blank');
        } else {
            // Download file for Excel/PDF
            window.location.href = url;
        }

        $('#reportModal').modal('hide');
    }

    $(document).ready(function() {
        // Hide sensitive data rows initially if not authorized
        if (!canViewSensitiveData) {
            $('#view_telp_customer').closest('tr').hide();
            $('#view_email_customer').closest('tr').hide();
        }
    });
</script>