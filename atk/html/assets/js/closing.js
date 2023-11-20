var feedUri = $('#orderRequest').attr('data-src');
$(document).ready(function () {
     loadDataJson(HoldOn);
     $('#submit2Closing').submit(function(e){
        $('input[type=hidden]').each(function(){
            var stoid = $(this).attr("value");
            var check = false;
            var cbstoid = false;
            $('input[class=list_'+stoid+']').each(function(){
                if($(this).is(':checked')===true) {
                    check = true;
                }
            });
            if(check===false) {
                alert('Mohon dipilih action setiap order');
                e.preventDefault();
                return false;
            }
        });
     });
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
            HoldOn.close();
        },
        error: function (data) {
            console.log('Error Feed response');
        }
    });
}

//LIST ORDER
function inquiryData(feedData, idTable) {
    var groupColumn = 0;
    if ($.fn.dataTable.isDataTable('#' + idTable)) {
        $('#' + idTable).DataTable().clear().draw().rows.add(feedData).columns.adjust().draw();
    } else {
        idTable = $('#' + idTable).DataTable({
            "columnDefs": [
                {"visible": false, "targets": [0]},
//                {"className": "dt-body-center", "targets": 3},
                {"width": "12%", "targets": [4]},
                {"width": "20%", "targets": [1]},
                {"width": "40%", "targets": [2]},
                {"width": "10%", "targets": [3]}
            ],
            "paging": false,
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
                {"data": "stock_out_qty_gantung"},
                {"data": "satuan"},
                {"data": "remark"}
            ]
        }).draw();
    }
    return idTable;
}