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
                            <h5 class="card-title text-center">Lupa Password</h5>
                            
                            <form action="<?php echo $forget_uri; ?>" method="POST" class="form-signin">
                                <div class="form-label-group">
                                    <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Masukkan Email Anda" required autofocus>
                                    <label for="inputUsername">Email</label>
                                </div>

                                <button class="btn btn-lg btn-warning btn-block text-uppercase" type="submit">Reset Password</button>
                                <hr class="my-4">
                                <a href="<?php echo $login_uri; ?>"><i class="fa fa-arrow-left"></i> Back to Login</a>
                            </form>
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
