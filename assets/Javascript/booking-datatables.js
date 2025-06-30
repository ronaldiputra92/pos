// DataTables configuration untuk halaman booking
$(document).ready(function() {
    // Tunggu sampai semua library dimuat
    setTimeout(function() {
        if (typeof $.fn.DataTable !== 'undefined' && $('#databooking').length > 0) {
            // Hancurkan DataTable yang mungkin sudah ada
            if ($.fn.DataTable.isDataTable('#databooking')) {
                $('#databooking').DataTable().destroy();
            }
            
            // Inisialisasi DataTable dengan konfigurasi bahasa Indonesia
            $('#databooking').DataTable({
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                    "emptyTable": "Tidak ada data booking yang tersedia",
                    "zeroRecords": "Tidak ada data yang cocok dengan pencarian"
                },
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                "order": [[5, "desc"]], // Urutkan berdasarkan tanggal
                "columnDefs": [
                    {
                        "targets": [9], // Kolom aksi tidak bisa diurutkan
                        "orderable": false,
                        "searchable": false
                    }
                ],
                "responsive": true,
                "destroy": true
            });
            
            console.log('DataTable booking berhasil diinisialisasi');
        }
    }, 500);
});