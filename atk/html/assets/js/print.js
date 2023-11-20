var feedUri = $('#orderRequest').attr('data-src');
$(document).ready(function () {
     loadDataJson(HoldOn);     
});

//LIST ORDER
function loadDataJson(HoldOn) {
    $.ajax({
        type: 'POST',
        url: feedUri,
        data: {'inquiry': 'closing'},
        beforeSend: function () {
            HoldOn.open();
        },
        success: function (x) {
            orderClosing = inquiryData(x.data, 'orderRequest');
            generateBtn(orderClosing, 'Arsipkan Data', 'print');
            HoldOn.close();
        },
        error: function (data) {
            console.log('Error Feed response');
        }
    });
}

function generateBtn(btnTableDt, textBtn, nodez) {
    new $.fn.dataTable.Buttons( btnTableDt, {
        buttons: [
            {
                text: textBtn,
                action: function( e, dt, node, conf ) {
                    if(!confirm('Perhatian semua data akan di arsipkan, lanjutkan ?')) {
                        return false;
                    }
                    var protocol = window.location.protocol;
                    var hostname = window.location.hostname;
                    var ctrl = 'stock_out';
                    var checkIfNull = 0;
                    var dataUri = protocol + "//" + hostname + '/dashboard/' + ctrl + '/' + nodez + '/arsipkan';
                    window.open(dataUri, '_self');
                }
            }
        ]
    });
    btnTableDt.buttons( 0, null ).container().prependTo(
        btnTableDt.table().container()
    );
}

//LIST ORDER
function inquiryData(feedData, idTable) {
    var groupColumn = 0;
    if ($.fn.dataTable.isDataTable('#' + idTable)) {
        $('#' + idTable).DataTable().clear().draw().rows.add(feedData).columns.adjust().draw();
    } else {
        idTable = $('#' + idTable).DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'print','pdf'
            ],
            "columnDefs": [
                {"visible": false, "targets": [0]},
//                {"className": "dt-body-center", "targets": 3},
                {"width": "12%", "targets": [4]},
                {"width": "20%", "targets": [1]},
                {"width": "40%", "targets": [2]},
                {"width": "10%", "targets": [3]}
            ],
            "paging": true,
            "autoWidth": false,
            data: feedData,
            order: [[groupColumn, 'desc']],
            "drawCallback": function () {
                var api = this.api();
                var rows = api.rows({page: 'current'}).nodes();
                var last = null;

                api.column(groupColumn, {page: 'current'}).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5"><strong>' + group + '</strong></td></tr>');
                        last = group;
                    }
                });
            },
            "columns": [
                {"data": "order_group"},
                {"data": "c_fullname"},
                {"data": "nama_barang"},
                {"data": "stock_out_qty"},
                {"data": "satuan"},
                {"data": "remark"}
            ]
        }).draw();
    }
    return idTable;
}