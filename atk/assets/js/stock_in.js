$(document).ready(function () {
    var feedUri = $('#dataBarang').attr('data-src');
    var tableFeed = $('#dataBarang').DataTable({
	dom: 'Bfrtip',
             responsive: true,
        ajax: feedUri,
        order: [[1, 'desc']],
        "columns": [
            {"data": "nama_barang", "width": "50%"},
            {"data": "tot_in"},
            {"data": "tot_out"},
            {"data": "stock_ready"},
            {"data": "satuan"}
        ]
    });

    var tbi = $('#dataBarangIn').DataTable({order: [[0, 'asc']], responsive: true, searching: false, paging: false, info: false, "columns": [{"width": "50%", "orderable": false}, {"width": "10%", "orderable": false}, {"width": "30%", "orderable": false}, {"width": "10%", "orderable": false}]});
    $('#add_barang').on('click', function () {
        
        var dataOrderBarang = $('#nama_barang').select2('data');
        var jmlOrderBarang = $('#jml_barang').val();
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
    });

    $('#submit2Process').on('submit', function (e) {
        if (!tbi.data().count()) {
            alert('Data barang harus ada minimal 1');
            e.preventDefault();
        } else {
            console.log(tbi.data().count());
//            e.preventDefault();
        }
    });

//    $('#dataBarangIn tbody').on('click', 'tr', function () {
//        if ($(this).hasClass('selected')) {
//            $(this).removeClass('selected');
//        } else {
//            tbi.$('tr.selected').removeClass('selected');
//            $(this).addClass('selected');
//        }
//    });

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
