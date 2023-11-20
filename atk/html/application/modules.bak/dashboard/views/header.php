<!DOCTYPE html>
<html>
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Test | IBS </title>
        <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/style.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/sweetalert/sweetalert.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">

    </head> 
    <body>


        <script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
        <script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/jqueryform/jquery.form.js'); ?>"></script>
        <script src="<?php echo base_url('assets/sweetalert/sweetalert.min.js'); ?>"></script>


        <div class="container-fuid">
            <div class="col-md-12">


                <h6 class="text-left" style="margin:15px 0 0 10px">
                    <img src="<?php echo base_url('assets/image/ibs.jpg') ?>" width="300">
                </h6>

                <div class="col-md-2" style="background-color: ">
                    <nav class="navbar navbar-default sidebar" role="navigation">
                        <div class="container-fluid">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>      
                            </div>
                            <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
                                <ul class="nav navbar-nav">

                                    <li class="pull-right hidden-xs showopacity" style="height: 200px">

                                        <div class="thumbnail">
                                            <a href="#">
                                                <img src="<?php echo base_url('assets/image/user1.png') ?>"style="width:50%">
                                                <div class="caption">
                                                    <p style="font-weight: bold">Gilang Teguh Kresnadi<br><small style="font-weight: 100;">gilang.kresnadi@ibsmulti.com</small></p>
                                                </div>
                                            </a>
                                        </div>

                                    </li>

                                    <li id="home"><a href="<?php echo site_url('dashboard/home') ?>">Home<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-home"></span></a></li>

                                    <li id="data"><a href="<?php echo site_url('dashboard/data') ?>" onclick="data();">Master Data<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-th-list"></span></a></li>

                                    <li ><a href="#" onClick="logout();">Log out<span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-off"></span></a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <script type="text/javascript">

                    function logout() {

                        if (
                                swal({
                                    title: "Logout ? ",
                                    text: "Klik ( OK ) untuk keluar !",
                                    type: "info",
                                    showCancelButton: true,
                                    closeOnConfirm: false,
                                    showLoaderOnConfirm: true,
                                },
                                        function () {
                                            $.ajax({
                                                url: "<?php echo site_url('dashboard/logout'); ?>",
                                                beforeSend: function (a) {
                                                    var percentVal = '<img src="<?php echo base_url('ws_assets/icon/loading.gif'); ?>" style="width:40px;position:fixed; margin-top: -12px; margin-left: -35px;"> <b>Processing</b>';
                                                    $('.logout').html(percentVal);
                                                },
                                                success: function (data) {
                                                    location.reload();
                                                },
                                                error: function (e) {
                                                    alert('error');
                                                }
                                            })
                                            setTimeout(function () {
                                                swal("Berhasil Keluar");
                                            }, 2000);
                                        }))
                        {
                        }
                        ;

                    }
                </script>

                <div class="col-md-10" style="background-color: ">