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
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <?php echo $this->session->flashdata('message'); ?>

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
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($booking as $row): ?>
                                    <tr>
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
                                        <td><?php echo $row->durasi ?> menit</td>
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
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown">
                                                    Status <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#" onclick="updateStatus('<?php echo $row->kode_booking ?>', 'pending')">Pending</a></li>
                                                    <li><a href="#" onclick="updateStatus('<?php echo $row->kode_booking ?>', 'completed')">Completed</a></li>
                                                    <li><a href="#" onclick="updateStatus('<?php echo $row->kode_booking ?>', 'cancelled')">Cancelled</a></li>
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
                                            <a href="#" class="btn btn-xs btn-danger" onclick="deleteBooking('<?php echo $row->kode_booking ?>')" title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </a>
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Customer *</label>
                                <input type="text" name="edit_nama_customer" id="edit_nama_customer" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. Telepon *</label>
                                <input type="text" name="edit_telp_customer" id="edit_telp_customer" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="edit_email_customer" id="edit_email_customer" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Karyawan *</label>
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
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Durasi (menit)</label>
                                <select name="edit_durasi" id="edit_durasi" class="form-control">
                                    <option value="20">20 menit</option>
                                    <option value="30">30 menit</option>
                                    <option value="40">40 menit</option>
                                    <option value="50">50 menit</option>
                                    <option value="60">60 menit</option>
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
                            <tr>
                                <td><strong>No. Telepon:</strong></td>
                                <td id="view_telp_customer">-</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td id="view_email_customer">-</td>
                            </tr>
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
                                <td><strong>Durasi:</strong></td>
                                <td id="view_durasi">-</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td id="view_status">-</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Dibuat:</strong></td>
                                <td id="view_created_at">-</td>
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

<script>
    var base_url = "<?php echo base_url(); ?>";

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

    function deleteBooking(bookingId) {
        if (confirm('Apakah Anda yakin ingin menghapus booking ini?')) {
            window.location.href = base_url + 'booking/delete_booking/' + bookingId;
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
                $('#edit_telp_customer').val(data.telp_customer);
                $('#edit_email_customer').val(data.email_customer);
                $('#edit_id_karyawan').val(data.id_karyawan);
                $('#edit_studio_id').val(data.studio_id);
                $('#edit_tanggal_booking').val(data.tanggal_booking);
                $('#edit_durasi').val(data.durasi);
                $('#edit_catatan').val(data.catatan);

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
                    $('#view_telp_customer').text(booking.telp_customer || '-');
                    $('#view_email_customer').text(booking.email_customer || '-');
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
                        var formattedDate = date.getDate().toString().padStart(2, '0') + '-' +
                            (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                            date.getFullYear();
                        $('#view_tanggal_booking').text(formattedDate);
                    } else {
                        $('#view_tanggal_booking').text('-');
                    }

                    $('#view_jam_booking').text(booking.jam_booking || '-');
                    $('#view_durasi').text((booking.durasi || '-') + (booking.durasi ? ' menit' : ''));

                    var statusBadge = '';
                    switch (booking.status) {
                        case 'pending':
                            statusBadge = '<span class="label label-warning">Pending</span>';
                            break;
                        case 'confirmed':
                            statusBadge = '<span class="label label-info">Confirmed</span>';
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
                        var formattedCreatedDate = createdDate.getDate().toString().padStart(2, '0') + '-' +
                            (createdDate.getMonth() + 1).toString().padStart(2, '0') + '-' +
                            createdDate.getFullYear() + ' ' +
                            createdDate.getHours().toString().padStart(2, '0') + ':' +
                            createdDate.getMinutes().toString().padStart(2, '0');
                        $('#view_created_at').text(formattedCreatedDate);
                    } else {
                        $('#view_created_at').text('-');
                    }

                    $('#view_catatan').text(booking.catatan || 'Tidak ada catatan');
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
</script>

<script>
    // Alert akan menghilang setelah 3 detik
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    });
</script>