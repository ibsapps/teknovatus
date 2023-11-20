$(document).ready(function () {
//    console.log($('#dataBarang').attr('data-src'));
    var feedUri = $('#dataKaryawan').attr('data-src');
    var tableFeed = $('#dataKaryawan').DataTable({
        responsive: true,
        ajax: feedUri,
        order: [[1, 'desc']],
        "columns": [
            {"data": "cbox", "orderable": false, className: "text-center"},
            {"data": "nik"},
            {"data": "nama_lengkap"},
            {"data": "dept"},
            {"data": "email"},
	    {"data": "try"}
        ]
    });
});