<script>
    function tambahServis() {
        $('#tambahServisModal').modal('show');
    }

    function editServis(e) {
        $.ajax({
            url: base_url + "servis/detail/" + e,
            type: "post",
            success: function(data) {
                var obj = JSON.parse(data);
                $('#id_servis').val(obj.id_servis);
                $('#kode_servis').val(obj.kode);
                $('#nama_servis').val(obj.nama_servis);
                $('#harga_servis').val(obj.harga);
                $('#keterangan_servis').val(obj.keterangan);
                $('#status_servis').val(obj.status);
                $('#editServisModal').modal('show');
            }
        })
    }

    function hapusServis(e) {
        $.ajax({
            url: base_url + "servis/cek_delete/" + e,
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
                                url: base_url + "servis/hapus/" + e,
                                type: "post",
                                success: function(data) {
                                    window.location = base_url + "servis"
                                }
                            })
                        }
                    })
                }
            }
        });
    }
</script>