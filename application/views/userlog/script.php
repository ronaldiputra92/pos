<script>
	function deluserlog() {
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
                    url: base_url + "userlog/delete/",
                    type: "post",
                    success: function(data) {
                        window.location = base_url + "userlog/"
                    }
                })
            }
        })
    }
</script>