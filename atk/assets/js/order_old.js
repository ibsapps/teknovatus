$(document).ready(function () {

    //LIST ORDER
    var feedUri = $('#dataBarang').attr('data-src');
    var groupColumn = 0;
    var tableFeed = $('#dataBarang').DataTable({
        "columnDefs": [
            {"visible": false, "targets": groupColumn},
            {"className": "dt-body-center", "targets": 3}
        ],
//        responsive: false,
        ajax: feedUri,
        order: [[groupColumn, 'desc']],
        "drawCallback": function (settings) {
            var api = this.api();
            var rows = api.rows({page: 'current'}).nodes();
            var last = null;

            api.column(groupColumn, {page: 'current'}).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                            '<tr class="group"><td colspan="5">' + group + '</td></tr>'
                            );

                    last = group;
                }
            });
        },
        "columns": [
            {"data": "order_group"},
            {"data": "order_item", "width": "40%"},
            {"data": "stock_out_qty_gantung", "width": "10%"},
            {"data": "satuan", "width": "12%"},
            {"data": "status", "width": "12%"}, 
            {"data": "remark"}
        ]
    });

    $('#dataBarang tbody').on('click', '.btn-xs', function () {
        var checkElement = $('#idOrder').length;
        if (checkElement > 0) {
            $('#idOrder').attr('value', $(this).attr('id'));
        } else {
            $(this).parents('#batchExec').append('<input type="hidden" name="order_id" id="idOrder" value="' + $(this).attr('id') + '">');
        }
        var protocol = window.location.protocol;
        var hostname = window.location.hostname;
        var ctrl = $(this).attr('data-ctrl');
        var node = $(this).attr('data-node');
        $(this).parents('#batchExec').attr('action', protocol + "//" + hostname + '/dashboard/' + ctrl + '/' + node);
        if (node === "delete") {
            if (confirm('Perhatian, data akan terhapus. Lanjutkan?')) {
                $(this).parents('#batchExec').submit();
            }
        } else {
            $(this).parents('#batchExec').submit();
        }
    });

    //LIST FORM ORDER
    var tbi = $('#dataBarangIn').DataTable({order: [[0, 'asc']], responsive: true, searching: false, paging: false, info: false, "columns": [{"width": "50%", "orderable": false}
            , {"width": "10%", "orderable": false}
            , {"width": "30%", "orderable": false}
            , {"width": "10%", "orderable": false}
        ]}
    );
    $('#add_barang').on('click', function () {
        var dataOrderBarang = $('#nama_barang').select2('data');
        var jmlOrderBarang = $('#jml_barang').val();
        var checkUri = $(this).attr('data-src') + "/" + dataOrderBarang[0].id;
        $.ajax({
            type: 'POST',
            url: checkUri,
            data: {'inquiry': 'order'},
            dataType: 'json',
            success: function (x) {
                if (x.status === true && x.stock>jmlOrderBarang) {
                    try {
                        var col0 = "<input type=\"hidden\" name=\"id_barang[]\" value=\"" + dataOrderBarang[0].id + "\" />" + dataOrderBarang[0].text;
                        var col1 = "<input type=\"hidden\" name=\"qty_barang[]\" value=\"" + jmlOrderBarang + "\" />" + jmlOrderBarang;
                        var col2 = "<input type=\"hidden\" name=\"rem_barang[]\" value=\"" + $('#remark').val() + "\" />" + $('#remark').val();
                        var col3 = "<button class=\"removeRow btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Hapus</button>";
                        tbi.row.add([
                            col0,
                            col1,
                            col2,
                            col3
                        ]).draw();
                    } catch (err) {
                        alert('Pilih data barang yang akan dientry');
                    }
                } else {
                    alert('Out of stock (stock '+x.stock+', permintaan '+jmlOrderBarang+')');
                }
            },
            error: function (data) {
                console.log('Error Feed response');
            }
        });
    });

    $('#submit2Process').on('submit', function (e) {
        if (!tbi.data().count()) {
            alert('Data barang harus ada minimal 1');
            e.preventDefault();
        } else {
            console.log(tbi.data().count());
        }
    });

    $('#dataBarangIn tbody').on('click', 'button', function () {
        if (confirm('Data akan dihapus, lanjutkan?')) {
            tbi.row($(this).parents('tr')).remove().draw();
        }
    });

    var optBarang = $('.js-data-barang-ajax').select2({
        ajax: {
            url: $('#drop_down_nama_barang').attr('data-src'),
            dataType: 'json',
            delay: 250
        },
        placeholder: "Pilih Barang"
    });
});
