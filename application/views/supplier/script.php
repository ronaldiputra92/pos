<script>
    function tambahsupplier() {
        $('#inputSupplierModal').modal('show');
    }

    function editSupplier(e) {
        $.ajax({
            url: base_url + "supplier/detilsupplier/" + e,
            type: "post",
            success: function(data) {
                var obj = JSON.parse(data);
                $('#idsupplier').val(obj.id_supplier);
                $('#editnamasup').val(obj.nama_supplier);
                $('#editemailsup').val(obj.email_supplier);
                $('#edittelpsup').val(obj.telp_supplier);
                $('#editfaxsup').val(obj.fax_supplier);
                $('#editbanksup').val(obj.bank);
                $('#editreksup').val(obj.rekening);
                $('#editatasnamasup').val(obj.atas_nama);
                $('#editalamatsup').val(obj.alamat_supplier);
            }
        })
        $('#editSupplierModal').modal('show');
    }

    function hapusSupplier(e) {
        $.ajax({
            url: base_url + "supplier/cek_delete/" + e,
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
                                url: base_url + "supplier/hapussupplier/" + e,
                                type: "post",
                                success: function(data) {
                                    window.location = base_url + "supplier";
                                }
                            })
                        }
                    })
                }
            }
        });
    }

    function importSupp() {
        $('#importSupplier').modal('show');
    }
</script>