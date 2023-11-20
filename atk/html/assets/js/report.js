var feedUri = $('#submit2Process').attr('action');
$(document).ready(function () {

    var optBarang = $('.js-data-barang-ajax').select2({
        ajax: {
            url: $('#src_barang').attr('data-src'),
            dataType: 'json',
            delay: 250
        },
        placeholder: "Pilih Barang"
    });
    var optKaryawan = $('.js-data-karyawan-ajax').select2({
        ajax: {
            url: $('#src_karyawan').attr('data-src'),
            dataType: 'json',
            delay: 250
        },
        placeholder: "Pilih Karyawan"
    });
    $('#iquiry_item').on('click', function () {
        loadDataJson(HoldOn);
    });
});
function loadDataJson(HoldOn) {
    var barang = $('#nama_barang').val();
    var karyawan = $('#id_karyawan').val();
    var dataX = null;
    $.ajax({
        type: 'POST',
        url: feedUri,
        data: {'barang': barang, 'karyawan': karyawan},
        beforeSend: function () {
            HoldOn.open();
        },
        success: function (x) {
            orderRequest = inquiryData(x, 'dataReportIn', 3);
            HoldOn.close();
        },
        error: function (data) {
            console.log('Error Feed response');
        }
    });
    return dataX;
}

function inquiryData(feedData, idTable, group) {
    var groupColumn = group;
    if ($.fn.dataTable.isDataTable('#' + idTable)) {
        $('#' + idTable).DataTable().clear().draw().rows.add(feedData).columns.adjust().draw();
    } else {
        idTable = $('#' + idTable).DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'print'
            ],
            "columnDefs": [
                {"visible": false, "targets": [3]},
                {"className": "dt-body-center", "targets": 3},
//                {"width": "12%", "targets": [3, 4]},
//                {"width": "40%", "targets": [1]},
//                {"width": "10%", "targets": [2]}
            ],
            "autoWidth": false,
            data: feedData,
            order: [[groupColumn, 'desc']],
            "drawCallback": function () {
                var api = this.api();
                var rows = api.rows({page: 'current'}).nodes();
                var last = null;
                api.column(groupColumn, {page: 'current'}).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5"> Tanggal ' + group + '</td></tr>');
                        last = group;
                    }
                });
            },
            "columns": [
                {"data": "nama_barang"},
                {"data": "stock_out_qty"},
                {"data": "c_fullname"},
                {"data": "order_date"},
                {"data": "status"}
            ]
        }).column(5).draw();
    }
    return idTable;
}