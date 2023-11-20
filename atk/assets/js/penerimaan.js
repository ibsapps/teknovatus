var feedUri = $('#submit2Process').attr('data-src');

var orderRequest = null;
var orderCollect = null;
var orderApprove = null;
var orderDeliver = null;
var orderReject = null;

$(document).ready(function () {
    loadDataJson(HoldOn);

    //BTN APPROVAL REJECT
    $('table').on('click', '.pullRightBtn', function () {
        var protocol = window.location.protocol;
        var hostname = window.location.hostname;
        var ctrl = $(this).attr('data-ctrl');
        var node = $(this).attr('data-node');
        var attrId = $(this).attr('id');
        var remk = '';
        var dataUri = protocol + "//" + hostname + '/dashboard/' + ctrl + '/' + node;
        var tableParents = $(this).parents('table').attr('id');
        if (node === "reject" || node === "reject_head") {
            var title = $('h4[class=modal-title]').html('Request Ditolak');
            var body = $('div[class=modal-body]').html('<div class="form-group"><label for="remark_info">Keterangan</label><input type="text" name="remark_info" value="" class="form-control" id="remark_info" placeholder="Berikan deskripsi penolakan" /></div>');
            $('#idModal').modal('show');
            $('div[class=modal-footer] button').bind('click', function () {
                if ($(this).attr('class') === "btn btn-primary") {
                    $('#idModal').modal('hide');
                    console.log($('div[class=modal-body] input[name=remark_info]').val());
                    remk = $('div[class=modal-body] input[name=remark_info]').val();
                    processOrder(dataUri, attrId, remk, node);
                }
            });
        } else {
            processOrder(dataUri, attrId, remk, node);
        }
    });

    //CHECK ALL
    $('.all_cbox').bind('click', function() {
        var tableList = $(this).parents('table');
        tableList.find('input[type=checkbox]').each(function(){
            $(this).prop( "checked", !$(this).prop("checked"));
        });
    });
});

function generateBtn(btnTableDt, x, textBtn, classBtn, nodez) {
    console.log(classBtn);
    new $.fn.dataTable.Buttons( btnTableDt, {
        buttons: [
            {
                text: textBtn,
                action: function( e, dt, node, conf ) {
                    if(!confirm('Semua data yang di check akan di proses, lanjutkan ?')) {
                        return false;
                    }
                    var protocol = window.location.protocol;
                    var hostname = window.location.hostname;
                    var ctrl = 'stock_out';
                    var checkIfNull = 0;
                    $('input[name^=cbox]').each(function(){
                        if($(this).prop('checked')===true) {
                            var remk = '';
                            var attrId = $(this).val();
                            var dataUri = protocol + "//" + hostname + '/dashboard/' + ctrl + '/' + nodez;
                            processOrderBatch(dataUri, attrId, remk);                                
                        }
                    });
                }
            }
        ]
    });
    btnTableDt.buttons( 0, null ).container().prependTo(
        btnTableDt.table().container()
    );
    /*
    btnTableDt.button().add( x, {text: textBtn}).nodes().addClass('btn', classBtn);
    btnTableDt.buttons().action( function( e, dt, button, config ) {
        if(!confirm('Semua data yang di check akan di proses, lanjutkan ?')) {
            return false;
        }
        var protocol = window.location.protocol;
        var hostname = window.location.hostname;
        var ctrl = 'stock_out';
        var checkIfNull = 0;
        $('input[name^=cbox]').each(function(){
            if($(this).prop('checked')===true) {
                var remk = '';
                var attrId = $(this).val();
                var dataUri = protocol + "//" + hostname + '/dashboard/' + ctrl + '/' + nodez;
                processOrderBatch(dataUri, attrId, remk);                                
            }
        });
        // this.column(6).search(y).draw();
    });*/
}

function loadDataJson(HoldOn) {
    $.ajax({
        type: 'POST',
        url: feedUri,
        data: {'inquiry': 'order'},
        beforeSend: function () {
            HoldOn.open();
        },
        success: function (x) {
            var sumData = x.data;
            orderRequest = inquiryData(sumData, 'orderRequest', '0');
            orderCollect = inquiryData(sumData, 'orderCollect', '1');
            orderApprove = inquiryData(sumData, 'orderApprove', '2');
            orderDeliver = inquiryResult(sumData, 'orderDeliver', '3');
            orderReject = inquiryResult(sumData, 'orderReject', '4');
            HoldOn.close();
        },
        complete: function(y) {
            //Location di view template
            generateBtn(orderRequest, 0, 'Collect All', 'btn-primary', 'collect');
            generateBtn(orderCollect, 0, 'Approve All', 'btn-success', 'approve');
            generateBtn(orderApprove, 0, 'Deliver All', 'btn-warning', 'deliver');
            generateBtn(orderCollect, 1, 'Reject All', 'btn-danger', 'reject_head');
            generateBtn(orderApprove, 1, 'Reject All', 'btn-danger', 'reject');
            HoldOn.close();
        },
        error: function (data) {
            console.log('Error Feed response');
        }
    });
}

function processOrder(dataUri,attrId, remk, node) {
    $.ajax({
        type: 'POST',
        url: dataUri,
        data: {'order_id': attrId, 'remark': remk},
        beforeSend: function () {
            if (node !== "reject") {
                if (confirm('Data akan diproses, lanjutkan?')) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        success: function (x) {
            loadDataJson(HoldOn);
        },
        error: function (data) {
            console.log('Error Feed response:' + data);
        }
    });
}

function processOrderBatch(dataUri,attrId, remk) {
    $.ajax({
        type: 'POST',
        url: dataUri,
        data: {'order_id': attrId, 'remark': remk},
        success: function (x) {
            loadDataJson(HoldOn);
            if(x.status===0) {
                alert('Update data gagal, '+x.desc);
            }
        },
        error: function (data) {
            console.log('Error Feed response:' + data);
        }
    });
}

//LIST ORDER
function inquiryData(feedData, idTable, filter) {
    var groupColumn = 1;
    if ($.fn.dataTable.isDataTable('#' + idTable)) {
        $('#' + idTable).DataTable().clear().draw().rows.add(feedData).columns.adjust().draw();
    } else {
        idTable = $('#' + idTable).DataTable({
            "columnDefs": [
                {"orderable": false, "targets": [0]},
                {"visible": false, "targets": [1, 6]},
                {"className": "dt-body-center", "targets": 4},
                {"width": "12%", "targets": [4, 5]},
                {"width": "40%", "targets": [2]},
                {"width": "10%", "targets": [3]}
            ],
            dom: 'Bfrtip',
            buttons: [],
            //     {
            //         text: 'Approve All',
            //         // action: function ( e, dt, node, config ) {
                                                                    
            //         // }
            //     },
            //     {text: 'Collect All'},
            //     {text: 'Reject All'}
            // ],
            "autoWidth": false,
            data: feedData,
            order: [[groupColumn, 'desc']],
            "drawCallback": function () {
                var api = this.api();
                var rows = api.rows({page: 'current'}).nodes();
                var last = null;

                api.column(groupColumn, {page: 'current'}).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="6">' + group + '</td></tr>');
                        last = group;
                    }
                });
            },
            "columns": [
                {"data": "no"},
                {"data": "order_group"},
                {"data": "order_item"},
                {"data": "stock_out_qty"},
                {"data": "satuan"},
                {"data": "status"},
                {"data": "status_id"},
                {"data": "remark"}
            ]
        }).column(6).search(filter).draw();
    }
    return idTable;
}

function inquiryResult(feedData, idTable, filter) {
        var groupColumn = 0;
        if ($.fn.dataTable.isDataTable('#' + idTable)) {
            $('#' + idTable).DataTable().clear().draw().rows.add(feedData).columns.adjust().draw();
        } else {
            idTable = $('#' + idTable).DataTable({
                "columnDefs": [
                    {"visible": false, "targets": [0, 5]},
                    {"className": "dt-body-center", "targets": 3},
                    {"width": "12%", "targets": [3, 4]},
                    {"width": "40%", "targets": [1]},
                    {"width": "10%", "targets": [2]}
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
                            $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                            last = group;
                        }
                    });
                },
                "columns": [
                    {"data": "order_group"},
                    {"data": "order_item"},
                    {"data": "stock_out_qty"},
                    {"data": "satuan"},
                    {"data": "status"},
                    {"data": "status_id"},
                    {"data": "remark"}
                ]
            }).column(5).search(filter).draw();
        }
        return idTable;
    }
