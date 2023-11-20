<?php echo $this->load->view('header')?>
 

 <div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home2" onclick="data_master('0');" aria-controls="home2" role="tab" data-toggle="tab">Data Master  <span class='badge badge-warning' id="nol"></a></li>
    <li role="presentation"><a href="#profile" onclick="data_master('1');" aria-controls="profile" role="tab" data-toggle="tab">Pengajuan  <span class='badge badge-warning' id="satu"></a></li>
    <li role="presentation"><a href="#messages" onclick="data_master('2');" aria-controls="messages" role="tab" data-toggle="tab">Ditolak  <span class='badge badge-warning' id="dua"></a></li>
    <li role="presentation"><a href="#settings" onclick="data_master('3');" aria-controls="settings" role="tab" data-toggle="tab">Diajukan Kembali  <span class='badge badge-warning' id="tiga">1</a></li>
    <li role="presentation"><a href="#finish" onclick="data_master('4');" aria-controls="finish" role="tab" data-toggle="tab">Disetujui  <span class='badge badge-warning' id="empat"></a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
      <div id="kontent"></div>
  </div>

</div>
      
      <script type="text/javascript">
          $('#data').addClass('active');
          $('#home').removeClass('active');

        function data_master(status)
        {
            $.ajax({
                url : "<?php echo site_url('dashboard/data_master/get_table')?>/"+status,
                success: function(data)
                {
                    $('#kontent').html(data);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error data');
                }
            });
        }
        data_master('0');
      </script>