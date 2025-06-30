// JavaScript untuk Edit Booking
var currentJamBooking = '';

function editBooking(bookingId) {
    // Load booking data
    $.ajax({
        url: base_url + 'booking/get_booking_data/' + bookingId,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data) {
                $('#edit_booking_id').val(data.kode_booking);
                $('#edit_nama_customer').val(data.nama_customer);
                $('#edit_telp_customer').val(data.telp_customer);
                $('#edit_email_customer').val(data.email_customer);
                $('#edit_studio_id').val(data.studio_id);
                $('#edit_tanggal_booking').val(data.tanggal_booking);
                $('#edit_durasi').val(data.durasi);
                $('#edit_catatan').val(data.catatan);
                
                // Store current jam booking
                currentJamBooking = data.jam_booking;
                
                // Load available time slots for edit
                loadTimeSlotsForEdit();
                
                $('#editBookingModal').modal('show');
            }
        },
        error: function() {
            alert('Terjadi kesalahan saat memuat data booking');
        }
    });
}

function loadTimeSlotsForEdit() {
    var studio = $('#edit_studio_id').val();
    var tanggal = $('#edit_tanggal_booking').val();
    var bookingId = $('#edit_booking_id').val();
    
    if (studio && tanggal) {
        $.ajax({
            url: base_url + 'booking/get_available_slots_edit',
            type: 'POST',
            data: {
                studio_id: studio,
                tanggal: tanggal,
                booking_id: bookingId
            },
            dataType: 'json',
            success: function(response) {
                var html = '<option value="">--Pilih Jam--</option>';
                
                // Add current jam booking to available slots if not already included
                if (currentJamBooking && response.indexOf(currentJamBooking) === -1) {
                    response.push(currentJamBooking);
                    response.sort();
                }
                
                if (response.length > 0) {
                    response.forEach(function(time) {
                        var selected = (time === currentJamBooking) ? 'selected' : '';
                        html += '<option value="' + time + '" ' + selected + '>' + time + '</option>';
                    });
                }
                $('#edit_jam_booking').html(html);
            },
            error: function() {
                $('#edit_jam_booking').html('<option value="">Error loading slots</option>');
            }
        });
    }
}

// Event listeners for edit form
$(document).ready(function() {
    $('#edit_studio_id, #edit_tanggal_booking').on('change', function() {
        loadTimeSlotsForEdit();
    });
});