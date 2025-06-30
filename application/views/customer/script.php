<script>
    function tambahcustomer() {
        $('#inputCustomerModal').modal('show');
    }

    function editCustomer(e) {
        $.ajax({
            url: base_url + "customer/detilcustomer/" + e,
            type: "post",
            success: function(data) {
                var obj = JSON.parse(data);
                $('#idcustomer').val(obj.id_cs);
                $('#editnamacs').val(obj.nama_cs);
                $('#editkelamin').val(obj.jenis_kelamin);
                $('#edittelpcs').val(obj.telp);
                $('#editjenis').val(obj.jenis_cs);
                $('#editalamat').val(obj.alamat);
                
                // Format tanggal booking untuk input datetime-local
                if (obj.tanggal_booking && obj.tanggal_booking !== null) {
                    var date = new Date(obj.tanggal_booking);
                    var year = date.getFullYear();
                    var month = String(date.getMonth() + 1).padStart(2, '0');
                    var day = String(date.getDate()).padStart(2, '0');
                    var hours = String(date.getHours()).padStart(2, '0');
                    var minutes = String(date.getMinutes()).padStart(2, '0');
                    var formattedDate = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
                    $('#edittanggalbooking').val(formattedDate);
                }
                
                $('#editCustomerModal').modal('show');
            }
        })
    }

    function hapusCustomer(e) {
        $.ajax({
            url: base_url + "customer/cek_delete/" + e,
            type: "post",
            success: function(data) {
                var obj = JSON.parse(data);
                if (obj.num == 1) {
                    Swal.fire({
                        title: "Cannot Delete This Data!",
                        text: "Please check your data relation!",
                        icon: "error",
                    });
                } else {
                    Swal.fire({
                        title: "Are you sure ?",
                        text: "Deleted data can not be restored!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: base_url + "customer/hapuscustomer/" + e,
                                type: "post",
                                success: function(data) {
                                    window.location = base_url + "customer"
                                }
                            })
                        }
                    })
                }
            }
        });
    }

    function importCus() {
        $('#importCustomerModal').modal('show');
    }
</script>