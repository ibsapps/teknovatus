<?Php foreach ($searchResult as $rs) { ?>
    <!--<div class="modal-content">-->
        <form action="<?Php echo $orderUri; ?>" method="POST" name="submit_order" id="submit_order">
            <input type="hidden" name="barang_id" value="<?Php echo $rs['barang_id']; ?>" />
            <input type="hidden" name="nama_barang" value="<?Php echo $rs['nama_barang']; ?>" />
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?Php echo $rs['nama_barang'] ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-8 col-md-8 col-lg-8 text-center">
                        <?Php
                        if ($rs['file'] == "No_Image_Available.jpg") {
                            $ImgUri = base_url() . "assets/images/" . $rs['file'];
                        } else {
                            $ImgUri = base_url() . "assets/images/upload/barang/" . $rs['file'];
                        }
                        ?>
                        <img src="<?Php echo $ImgUri; ?>" class="img-responsive" />
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 text-center">
                        <div class="form-group">
                            <div class="input-group">
                                <!--<div class="input-group-addon">Jumlah </div>-->
                                <input type="text" name="jumlah_barang" value="1" class="form-control" id="jumlah_barang" placeholder="Masukkan Jumlah Barang" />                                                
                                <div class="input-group-addon"><?Php echo $rs['satuan'] ?></div>
                            </div>                            
                        </div>
                        <div class="form-group">
                            <label for="remark">Note:</label>
                            <textarea name="remark" class="form-control" rows="3"></textarea>
                        </div>
                        <!-- 
                        <div class="form-group">
                            <button id="subplus" type="button" class="btn btn-warning substract"><i class="fa fa-minus"></i></button>
                            <button id="submin" type="button" class="btn btn-primary substract"><i class="fa fa-plus"></i></button>
                        </div>
                         -->
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="footer_info">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary order_btn">Order Now</button>
            </div>
        </form>
    <!--</div>-->
<?Php } ?>