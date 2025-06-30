<script>
    function tambahkaryawan() {
        $('#inputKaryawanModal').modal('show');
    }

    function editKaryawan(e) {
        $.ajax({
            url: base_url + "karyawan/detilkaryawan/" + e,
            type: "post",
            success: function(data) {
                var obj = JSON.parse(data);
                $('#idkaryawan').val(obj.id_karyawan);
                $('#editnama').val(obj.nama_karyawan);
                $('#editkelamin').val(obj.jenis_kelamin);
                $('#editemail').val(obj.email_karyawan);
                $('#edittelp').val(obj.telp_karyawan);
                $('#edittmptlahir').val(obj.tmpt_lahir);
                $('#edittgllahir').val(obj.tgl_lahir);
                $('#edittglmasuk').val(obj.tgl_masuk);
                $('#editstatus').val(obj.status_karyawan);
                $('#editalamat').val(obj.alamat);
            }
        })
        $('#editKaryawanModal').modal('show');
    }

    function hapusKaryawan(e) {
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
                    url: base_url + "karyawan/hapuskaryawan/" + e,
                    type: "post",
                    success: function(data) {
                        window.location = base_url + "karyawan";
                    }
                })
            }
        })
    }

    function importKar() {
        $('#importKaryawanModal').modal('show');
    }
</script>