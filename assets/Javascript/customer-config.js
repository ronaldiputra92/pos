// Konfigurasi DataTable untuk Customer tanpa kolom email
var tableCustomer = $('#datacustomer').DataTable({
	"bProcessing": false,
	"bJQueryUI": true,
	"sPaginationType": "full_numbers",
	"sAjaxSource": base_url + 'customer/LoadData',
	"sAjaxDataProp": "aaData",
	"fnRender": function (oObj) {
		uss = oObj.aData["Username"];
	},
	"aoColumns": [{
			"mDataProp": "kode_cs",
			bSearchable: true
		},
		{
			"mDataProp": "nama_cs",
			bSearchable: true
		},
		{
			"mDataProp": "jenis_kelamin",
			bSearchable: true
		},
		{
			"mDataProp": "telp",
			bSearchable: true
		},
		{
			"mDataProp": "alamat",
			bSearchable: true
		},
		{
			"mDataProp": "jenis_cs",
			bSearchable: true
		},
		{
			"mDataProp": function (data, type, val) {
				// Format tanggal booking
				var tanggalBooking = '';
				if (data.tanggal_booking && data.tanggal_booking !== null) {
					var date = new Date(data.tanggal_booking);
					var day = String(date.getDate()).padStart(2, '0');
					var month = String(date.getMonth() + 1).padStart(2, '0');
					var year = date.getFullYear();
					var hours = String(date.getHours()).padStart(2, '0');
					var minutes = String(date.getMinutes()).padStart(2, '0');
					tanggalBooking = day + '-' + month + '-' + year + ' ' + hours + ':' + minutes;
				}
				return tanggalBooking;
			},
			bSearchable: true
		},
		{
			"mDataProp": function (data, type, val) {
				pKode = data.id_cs;
				var btn = '<a href="#" class="btn btn-primary btn-xs" title="Edit Data" onclick="editCustomer(\'' + pKode + '\')"><i class="fa fa-edit"></i></a> \n\ <a href="#" class="btn btn-danger btn-xs" title="Hapus Data" onclick="hapusCustomer(\'' + pKode + '\')"><i class="fa fa-trash "></i></a>';

				return (btn).toString();
			},
			bSortable: false,
			bSearchable: false
		}
	],
	"bDestroy": true,
});