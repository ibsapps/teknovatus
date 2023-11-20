$(document).ready(function () {
    $('#cari_barang').submit(function (e) {
        e.preventDefault();
        $('#mainContent').html('');
        $('h1[class=page-header]').html('Hasil Pencarian');
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: {'q': $(this).find('input').val(), 'search': 'nama'},
            dataType: "json",
            success: function (r) {
                var x;
                for (x = 0; x < r.data.length; x++) {
                    var dat = r.data[x];
                    var panel = create_panel(dat);
                    $('#mainContent').append(panel);
                }
            }
        });
    });

    $('#idModal').on('show.bs.modal', function (e) {
        var modal = $(this);
        var stock  = $(e.relatedTarget).attr('data-stock');
    });

    $('.modal_show').click(function(e){
	e.preventDefault();
	var res = $.get($(this).attr('href'), function(res) {
		$('#xx .modal-content').html(res);
		$('#xx').modal('show');
	});
	
    });
});

function create_panel(data) {
//    var dom
    var protocol = window.location.protocol;
    var hostname = window.location.hostname;
    var url = protocol + "//" + hostname + "/assets/images/upload/barang/";
    if (data.file === "No_Image_Available.jpg") {
        url = protocol + "//" + hostname + "/assets/images/";
    }
    var panel = " " +
            "<div class=\"col-lg-3 col-md-6\">" +
            "<div class=\"panel panel-warning\">" +
            "<div class=\"panel-heading\" data-toggle=\"tooltip\" title=\"" + data.nama_barang + "\" style=\"height:35px; overflow:hidden\">" + data.nama_barang + "</div>" +
            "<div class=\"panel-body\">" +
            "<div class=\"row\">" +
            "<img src=\"" + url + data.file + "\" class=\"img-responsive\" />" +
            "</div>" +
            "</div>" +
            "<a href=\"" + protocol + "//" + hostname + "/dashboard/index/search/id/" + data.barang_id + "\" class=\"modal_show\" data-toggle=\"modal\" data-stock=\""+data.stock_ready+"\" data-target=\"#idModal\">" +
            "<div class=\"panel-footer\">" +
            "    <span class=\"pull-left\">Order Detail</span>" +
            "    <span class=\"pull-right\"><i class=\"fa fa-arrow-circle-right\"></i></span>" +
            "    <div class=\"clearfix\"></div>" +
            "</div>" +
            "</a>" +
            "</div>" +
            "</div>";
    return panel;
}