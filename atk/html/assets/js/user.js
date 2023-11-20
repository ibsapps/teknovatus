$(document).ready(function () {
	$('.multiselect').multiselect();

    //LIST ORDER
    var feedUri = $('#dataUser').attr('data-src');
    var groupColumn = 3;
    var tableFeed = $('#dataUser').DataTable({
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
                            '<tr class="group"><td colspan="6"><strong>' + group + '</strong></td></tr>'
                            );

                    last = group;
                }
            });
        },
        "columns": [
            {"data": "cbox"},
            {"data": "username", "width": "17%"},
            {"data": "nama_lengkap", "width": "20%"},
            {"data": "role_name"},
            {"data": "mobile_number", "width": "15%"},
            {"data": "status", "width": "11%"},
            {"data": "email", "width": "30%"}
        ]
    });

    $('#dataUser tbody').on('click', '.btn-xs', function () {
        var checkElement = $('#idKaryawan').length;
        if (checkElement > 0) {
            $('#idKaryawan').attr('value', $(this).attr('id'));
        } else {
            $(this).parents('#batchExec').append('<input type="hidden" name="karyawan_id" id="idKaryawan" value="' + $(this).attr('id') + '">');
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
    var tbi = $('#dataListKaryawan').DataTable({order: [[0, 'asc']], responsive: true, searching: false, paging: false, info: false, "columns": [
            {"width": "10%", "orderable": false}
            , {"width": "25%", "orderable": false}
            , {"width": "35%", "orderable": false}
            , {"width": "20%", "orderable": false}
            , {"width": "10%", "orderable": false}
        ]}
    );
    
    $('#add_barang').on('click', function () {
        var dataIdKaryawan = $('#id_karyawan').select2('data');
        var dataIdRoles = $('#id_roles').select2('data');
        var feedUri = $(this).attr('data-src');
        try {
            $.ajax({
                type: 'GET',
                url: feedUri,
                dataType: 'json',
                data: {'nik': dataIdKaryawan[0].id},
                beforeSend: function () {
//            HoldOn.open();
                },
                success: function (res) {
                    console.log(res);
                    if (res.username !== "") {
                        alert('User telah terdaftar');
                    } else {
                        var col0 = "<input type=\"hidden\" name=\"nik[]\" value=\"" + dataIdKaryawan[0].id + "\" />" + dataIdKaryawan[0].id;
                        var col1 = "<input type=\"hidden\" name=\"roles[]\" value=\"" + dataIdRoles[0].id + "\" />" + res.nama_lengkap;
                        var col2 = res.email;
                        var col3 = res.dept;
                        var col4 = "<button data-node=\"delete\" data-ctrl=\"user\" class=\"removeRow btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Hapus</button>";
                        tbi.row.add([col0, col1, col2, col3, col4]).draw();
                    }
                },
                error: function (data) {
                    console.log('Error Feed response' + data);
                }
            });


        } catch (e) {
            alert('Pilih karyawan yang akan dientry');
        }
    });

    $('#submit2Process').on('submit', function (e) {
        if (!tbi.data().count()) {
            alert('Data karyawan harus ada minimal 1');
            e.preventDefault();
        } else {
            console.log(tbi.data().count());
        }
    });

    $('#dataListKaryawan tbody').on('click', 'button', function () {
        if (confirm('Data akan dihapus, lanjutkan?')) {
            tbi.row($(this).parents('tr')).remove().draw();
        }
    });

    var optKaryawan = $('.js-data-barang-ajax').select2({
        ajax: {
            url: $('#drop_down_karyawan').attr('data-src'),
            dataType: 'json',
            delay: 250
        },
        placeholder: "Pilih Karyawan"
    });

    var optRoles = $('#id_roles').select2({placeholder: "Pilih Roles"});
    
    //LIST PANEL ROLES
    init_table();
    $('#id_roles').on('change', function() {
    	change_row();
    });
    
    //ADD NEW ROLES
    $('#add_new_roles_realtime').on('click', function() {
    	feedUriRes = $(this).attr('data-src');
    	if(confirm('Anda akan menambahkan roles baru, lanjutkan?')) {
    	    $.ajax({
    	        type: 'POST',
    	        url: feedUriRes,
    	        data: {'rolename': $('#new_role').val(), 'fkgid':$('#id_groups').val()},
    	        beforeSend: function () {
    	            //console.log($('#new_role').val());
    	        	if($('#new_role').val()==="") {
    	        		alert('Kolom roles tidak boleh kosong');
    	        	}
    	        },
    	        success: function (x) {
    	        	if(x.data==true) {
    	        		location.reload();
    	        	}
    	        	console.log(x.data);
    	        },
    	        error: function (data) {
    	            console.log('Error Feed response');
    	        }
    	    });
    	}
    });
    
    $('#dataPanel tbody').on('click', 'button', function () {    	
    	var uri = $(this).parents('table').attr('data-src')+"_add/"+$('#id_roles').val()+"/"+$(this).attr('data-id');
    	$.ajax({
	        type: 'POST',
	        url: uri,
	        data: {'param': 'add_remove'},
	        beforeSend: function () {
	        	//Console System
	        },
	        success: function (x) {
	        	if(x.data==true) {
	        		change_row();
	        	}
	        	console.log(x.data);
	        },
	        error: function (data) {
	            console.log('Error Feed response');
	        }
	    });
    });
    
    $('#dataR2Panel tbody').on('click', 'button', function () {
    	var uri = $(this).parents('table').attr('data-src')+"_rem/"+$(this).attr('data-id'); 
    	$.ajax({
	        type: 'POST',
	        url: uri,
	        data: {'param': 'add_remove'},
	        beforeSend: function () {
	        	//Console System
	        },
	        success: function (x) {
	        	if(x.data==true) {
	        		change_row();
	        	}
	        	console.log(x.data);
	        },
	        error: function (data) {
	            console.log('Error Feed response');
	        }
	    });    	
    });
});

function change_row() {
	$('#dataPanel').DataTable().destroy();
	$('#dataR2Panel').DataTable().destroy();
	init_table();
}

function init_table(x="") {
	if(x==="") {rid = $('#id_roles').val();} else {rid = x;}
	var leftCol = [
    	{"data": "pname", "orderable": false},
    	{"data": "cbox", "orderable": false, "width":"10%", "className": "text-center", "width":"10%"},
    	{"data": "location", "orderable": false},
    	{"data": "pos", "orderable": false}
    ];
    var rightCol = [
    	{"data": "cbox", "orderable": false, "width":"10%", "className": "text-center", "width":"10%"},
    	{"data": "pname", "orderable": false},
    	{"data": "location", "orderable": false},
    	{"data": "pos", "orderable": false}
    ];
    generate_panel('dataPanel', leftCol, rid);
    generate_panel('dataR2Panel', rightCol, rid);
}

function generate_panel(tag, setColumn, rid) {
    var feedUri = $('#'+tag).attr('data-src')+"/"+rid;
    groupColumn = 2;
    if ( $.fn.dataTable.isDataTable( '#tag' ) ) {
    	$('#'+tag).DataTable().reload();
    } else {
    	tableFeed = $('#'+tag).DataTable({
            "columnDefs": [
                {"visible": false, "targets": [groupColumn,groupColumn+1]}
            ],
            ajax: feedUri,
            searching: false, paging: false,
            order: [[groupColumn+1, 'asc']],
            "drawCallback": function (settings) {
                api = this.api();
                rows = api.rows({page: 'current'}).nodes();
                last = null;

                api.column(groupColumn, {page: 'current'}).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                                '<tr class="group"><td colspan="4"><strong>' + group + '</strong></td></tr>'
                                );

                        last = group;
                    }
                });
            },
            "columns": setColumn
        }).draw();
    }
}