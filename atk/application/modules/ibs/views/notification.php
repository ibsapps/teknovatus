<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php echo $this->load->view('favicon')?>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/bootstrap/css/bootstrap.min.css" />
        <link href="<?php echo base_url() ?>assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/custom.style.css" />
        <style type="text/css">
            body {
                background-image: url(<?php echo base_url(); ?>assets/images/chicago-silhouette-png-7.png);
                background-position: center center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: cover;
                /*background-color:#464646;*/
            }
        </style>
        <title>Infrastruktur Bisnis Sejahtera</title>
    </head>
    <body>

        <div class="container">
            <div class="row">
                <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                    <img src="<?php echo base_url(); ?>assets/images/ibs.png" class="img-fluid" />
                    <div class="card card-signin my-5">
                        <div class="card-body">
                            <h5 class="card-title text-center">Authentication</h5>
                            <?Php if ($result['status'] == 1) { ?>
                                <form action="<?Php echo $redirect_uri ?>" method="POST" class="form-signin">
                                    <div class="alert alert-success" role="alert"><?Php echo $result['desc'] ?></div>
                                    <button class="btn btn-lg btn-success btn-block text-uppercase" autofocus="true" type="submit">Lanjutkan >></button>
                                </form>
                            <?Php } else { ?>
                                <form action="<?Php echo $redirect_uri ?>" method="POST" class="form-signin">
                                    <div class="alert alert-danger" role="alert"><?Php echo $result['desc'] ?></div>
                                    <button class="btn btn-lg btn-danger btn-block text-uppercase" autofocus="true" type="submit"><< Kembali</button>
                                </form>
                            <?Php } ?>
                            <hr class="my-4">
                            <a href="#forget"><i class="fa fa-question-circle"></i> Lupa Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="<?php echo base_url() ?>assets/jquery/jquery.min.js"></script>
        <script src="<?php echo base_url() ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>
</html>