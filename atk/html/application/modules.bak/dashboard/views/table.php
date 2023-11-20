  <br>
  <?php 
        if ($this->session->userdata('user_type') == '1') {
          }else{

          if ($status != '0') {
          }else{
        ?>
        <button class="btn btn-danger" onclick="add_pengajuan()"><i class="glyphicon glyphicon-plus"></i> Tambah Data</button>
      <?php }} ?>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Creater</th>
                    <th>Nama Pengajuan</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <!-- <th>Status</th> -->
                    <th>Catatan user</th>
                    <th>Foto</th>

                    <?php 
                      if ($status == '0') {
                       echo "<th>Dibuat Tgl.</th>";
                      }else if ($status == '1') {
                        echo "<th>Diajukan Tgl.</th>";
                      }else if ($status == '2') {
                        echo "<th>Ditolak Tgl.</th>";
                      }else if ($status == '3') {
                        echo "<th>Diajukan Kembali Tgl.</th>";
                      }else if ($status == '4') {
                        echo "<th>Disetujui Tgl.</th>";
                      }

                    ?>
                    <th style="width:130px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

      </div>
    </div>
</div>

        <script type="text/javascript">
            $('#data').addClass('active');
   $('#home').removeClass('active');

var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

      "createdRow": function( row, data, dataIndex ) {
           if ( data[8] == "1" ) {        
             $(row).addClass('kuning');
           }else if ( data[8] == "4") {
             $(row).addClass('hijau');
           }else if ( data[8] == "2") {
             $(row).addClass('red');
           }else if ( data[8] == "3") {
             $(row).addClass('biru');
           }

        },
        "processing": true, 
        "serverSide": true,
        "order": [], 
        "ajax": {
            "url": "<?php echo site_url('dashboard/data_master/ajax_list')?>",
            "type": "POST",            
            "data": {status:"<?php echo $status; ?>"},            

        },

        "columnDefs": [
        { 
            "targets": [ -1 ], 
            "orderable": false, 
        },

        ],

    });

   
});



function add_pengajuan()
{
      save_method = 'add';

    $('#simpannn')[0].reset();
    $('#preview').attr('src','' );

    $('#modal_simpan_data').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
}

function edit_master(id_master)
{
      save_method = 'update';
    
     $.ajax({
            url : "<?php echo site_url('dashboard/data_master/edit_data')?>/"+id_master,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="id"]').val(data.id_master);
                $('[name="nama_barang"]').val(data.nama_barang);
                $('[name="jumlah"]').val(data.jumlah);
                $('[name="harga"]').val(data.harga);
                $('[name="catatan"]').val(data.catatan);
                $('[name="image2"]').val(data.foto);
                $('#preview').attr('src','<?php echo base_url("assets/image") ?>/' + data.foto );

                $('#modal_simpan_data').modal('show'); 
                $('.modal-title').text('Edit Data'); 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error data');
            }
        });

}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
     $.ajax({
            url : "<?php echo site_url('dashboard/data_master/cek_notif')?>",
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
              if (data.nol == '0') {
                $("#nol").addClass('hidden');
              }else{
                $("#satu").removeClass('hidden');
                $("#nol").text(data.nol);
              }

              if (data.satu == '0') {
                $("#satu").addClass('hidden');
              }else{
                $("#satu").removeClass('hidden');
                $("#satu").text(data.satu);
              }

               if (data.dua == '0') {
                $("#dua").addClass('hidden');
              }else{
                $("#dua").removeClass('hidden');
                $("#dua").text(data.dua);
              }
               if (data.tiga == '0') {
                $("#tiga").addClass('hidden');
              }else{
                $("#tiga").removeClass('hidden');
                $("#tiga").text(data.tiga);
              }
               if (data.empat == '0') {
                $("#empat").addClass('hidden');
              }else{
                $("#empat").removeClass('hidden');
                $("#empat").text(data.empat);
              }

               
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error data');
            }
        });


}
reload_table();

function delete_master(id)
{


  swal({
      title: "",
      text: "Hapus Data ?",
      type: "info",
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true
    }, function () {

      $.ajax({
            url : "<?php echo site_url('dashboard/data_master/hapus_pengajuan')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });
      setTimeout(function () {
        swal("","Data telah dihapus!","success");
      }, 2000);

    });


    
}


  function simpan_data_master(){
    var userfile=$('#userfile').val();
    var file=$('#file').val();
      var url;
      if(save_method == 'add') 
      {
          url = "<?php echo site_url('dashboard/data_master/tambah_pengajuan')?>";
           if (file == "") {
             alert('Masukan Photo !');
            }else{

                $('#simpannn').ajaxForm({
                     url:url,
                     type: 'post',
                     data:{"userfile":userfile},
                     
                     success: function(resp) {
                        $('#modal_simpan_data').modal('hide');
                        reload_table();
                       $('#preview')[0].reset();

                        alert('berhasil');
                     },
                    });  

            }
      }
      else
      {
        url = "<?php echo site_url('dashboard/data_master/update_pengajuan')?>";

            $('#simpannn').ajaxForm({
                 url:url,
                 type: 'post',
                 data:{"userfile":userfile},
                 
                 success: function(resp) {
                    $('#modal_simpan_data').modal('hide');
                    reload_table();
                   $('#preview')[0].reset();

                    alert('berhasil');
                 },
                });  
           
      }
    
   
};


function pengajuan(id_master)
{

  swal({
      title: "",
      text: "Ajukan data ini ke admin ?",
      type: "info",
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true
    }, function () {

      $.ajax({
            url : "<?php echo site_url('dashboard/data_master/pengajuan')?>/"+id_master,
            success: function(data)
            {
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error  data');
            }
        });
      setTimeout(function () {
        swal("","Data telah diajukan!","success");
      }, 2000);

    });

}


function approve(id_master)
{

   swal({
      title: "",
      text: "Approve data ini ?",
      type: "info",
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true
    }, function () {

      $.ajax({
            url : "<?php echo site_url('dashboard/data_master/approve')?>/"+id_master,
            success: function(data)
            {
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error  data');
            }
        });
      setTimeout(function () {
        swal("","Data telah di approve!","success");
      }, 2000);

    });

}

function tolak(id_master)
{
  

  swal({
  title: "Tolak pengajuan ini ?",
  text: "Beri catatan kenapa di tolak",
  type: "input",
  showCancelButton: true,
  closeOnConfirm: false,
  inputPlaceholder: "Write something"
}, function (inputValue) {
  if (inputValue === false) return false;
  if (inputValue === "") {
    swal.showInputError("You need to write something!");
    return false
  }
  

   $.ajax({
            url : "<?php echo site_url('dashboard/data_master/tolak')?>/"+id_master,
            data : {catatan_admin : inputValue},
            type: 'post',
            success: function(data)
            {
                
                swal("", "Data berhasil ditolak", "success");
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error  data');
            }
        });


});

    
}


function ajukan_kembali(id_master)
{

   swal({
      title: "",
      text: "Ajukan kembali data ini ?",
      type: "info",
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true
    }, function () {

      $.ajax({
            url : "<?php echo site_url('dashboard/data_master/ajukan_kembali')?>/"+id_master,
            success: function(data)
            {
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error  data');
            }
        });
      setTimeout(function () {
        swal({
          title: "",
          text: "Data telah di diajukan kembali."
        });
      }, 2000);

    });


}


</script>
<script type="text/javascript" src="<?php echo base_url('assets/photo.js'); ?>"></script>

 <div class="modal fade" id="modal_simpan_data" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title text-center msg"></h3>
      </div>
      <div class="modal-body form">
        <form role="form" name="simpannn" id="simpannn" action="javascript:simpan_data_master();" method="post" enctype="multipart/form-data" class="form-horizontal">
          <input type="hidden" value="" name="id"/> 
          <input type="hidden" value="" name="image2"/> 
          <div class="form-body">

                    <div class="form-group">
                        <label class="col-md-3 control-label">Foto pengajuan</label>
                        <div class="col-sm-5">
                             <div class="thumbnail" style="padding:10px; ">
                             <input type="file" id="file" accept="image/*" onChange="tampilkanPreview(this, 'preview')" name="userfile" size="50" multiple="multiple">
                                <img src="" id="preview" alt="" accept="jpg/png" width="200px" class="photo_placed" name="user_image" style="height:150px;"><br>
                                <div class="brows" onClick="file()">Pilih Photo</div>
                              </div>
                        </div>
                      </div>
                     <div class="form-group">
                            <label class="control-label col-md-3">Nama Pengajuan</label>
                            <div class="col-md-9">
                                <input name="nama_barang" placeholder="Nama Pengajuan" class="form-control" type="text" required="">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jumlah</label>
                            <div class="col-md-2">
                               <input name="jumlah" class="form-control" placeholder="Jumlah"  required="" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                                <span class="help-block"></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="control-label col-md-3">Harga</label>
                            <div class="col-md-5">
                                <input name="harga" class="form-control" placeholder="Harga"  required="" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                                <span class="help-block"></span>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="control-label col-md-3">Catatan</label>
                            <div class="col-md-9">
                              <textarea name="catatan" class="form-control" placeholder="Catatan"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                      

          </div>

           <div class="modal-footer">
            <button type="submit" id="btnSave" onclick="javascript:simpan_data_master();" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
        </form>
          </div>
         
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</body>
</html>

<style type="text/css">
  input[type="file"]{
    display:none;
  }
  .brows{
    width: 100%;
    background-color: red;
    background: rgba(0,0,0,0.2);
    cursor: pointer;
    padding: 5px;
  }
</style>
