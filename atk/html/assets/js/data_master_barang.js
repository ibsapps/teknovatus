$(document).ready(function () {
//    console.log($('#dataBarang').attr('data-src'));
    var feedUri = $('#dataBarang').attr('data-src');
    var tableFeed = $('#dataBarang').DataTable({
        responsive: true,
        ajax: feedUri,
        order: [[1, 'desc']],
        "columns": [
            {"data": "cbox", "orderable": false, className: "text-center"},
            {"data": "nama_barang", "width": "60%"},
            {"data": "satuan"},
            {"data": "kategori"}
        ]
    });

    $("input[type=file]").on("change", function () {
        $('#notifyAlert').html('');
        var formData = new FormData();
        var idImg = $(this).attr('id');
        var noImg = $('#preview_' + idImg).attr('src');
        var dataFile = $(this)[0].files[0];
        var imagefile = dataFile.type;
        var match = ["image/jpeg", "image/png", "image/jpg"];
        if (!((imagefile === match[0]) || (imagefile === match[1]) || (imagefile === match[2]))) {
            alert('Image type must jpg or png');
            $('#preview_' + idImg).attr('src', noImg);
        } else {
            showFiles(dataFile, idImg);
        }
        formData.append('uploadFiles', 'barang');
        formData.append('file', dataFile);

        var host = window.location.href;
//        console.log(formData);
        $.ajax({
            type: 'POST',
            url: host,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
//                $('#imgPreview_' + idImg).append('<div class="loading">Loading&#8230;</div>');
            },
            success: function (data) {
//                $('.loading').remove();
                if (data.status === 1) {
                    $('#uploaded_' + idImg).attr('value', data.param.file_name);
                } else {
                    $('#preview_' + idImg).attr('src', noImg);
                    console.log(data);
                    $('#notifyAlert').html('').html("<div class=\"alert alert-danger alert-dismissible fade in text-center\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>" + data.desc + "</div>");
                }
            },
            error: function (data) {
                $('#preview_' + idImg).attr('src', noImg);
//                $('.loading').remove();
                console.log("error");
                console.log(data);
            }
        });
    });

    $('#btnReset').on('click', function () {
        location.reload();
    });
    
    $("select[itemref=satuan]").on("change", function () {
        if($(this).val()==="addNew") {$('.hidden_'+$(this).attr('id')).fadeIn('slow');} else {$('.hidden_'+$(this).attr('id')).fadeOut('slow');}
    });
});

function showFiles(dataFile, idImg) {
    var reader = new FileReader();
    reader.onload = function (e) {
        $('#preview_' + idImg).attr('src', e.target.result);
    };
    reader.readAsDataURL(dataFile);
}