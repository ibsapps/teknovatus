<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><a href="<?Php echo base_url() ?>"><i class="fa fa-arrow-circle-left"></i> Dashboard</a> / <?Php echo $kategori_name ?></h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row" id="mainContent">
            <!-- Main Content -->
            <?Php
		
            foreach ($kategori_list as $kat) {
                if ($kat['file']=="No_Image_Available.jpg") {
                    $ImgUri = base_url() . "assets/images/";
                } else {
                    $ImgUri = base_url() . "assets/images/upload/barang/";
                }

                echo "<div class=\"col-lg-3 col-md-6\">";
                echo "<div class=\"panel panel-warning\">";
                echo "<div class=\"panel-heading\" data-toggle=\"tooltip\" title=\"" . $kat['nama_barang'] . "\" style=\"height:35px; overflow:hidden\">" . $kat['nama_barang'] . "</div>";
                echo "<div class=\"panel-body\">";
                echo "<div class=\"row\">";
                echo "<img src=\"" . $ImgUri . $kat['file'] . "\" class=\"img-responsive\" />";

		if ($kat['stock_ready'] > $kat['batas']) {
                echo "<a href=\"" . base_url() . "/dashboard/index/search/id/" . $kat['barang_id'] . "\" class=\"modal_show\" data-stock=\"".$kat['stock_ready']."\" data-target=\"#idModal\">";
                echo "<div class=\"panel-footer\">";
                echo "<span class=\"pull-left\">Order Detail</span>";
		echo "<span class=\"pull-right\"><i class=\"fa fa-arrow-circle-right\"></i></span>";
                echo "<div class=\"clearfix\"></div>";
                echo "</div>";
                echo "</a>";
		}else{
		echo "<a href=\"#\" class=\"#\" data-stock=\"#\" data-target=\"#\">";
                echo "<div class=\"panel-footer\">";
		echo "<span class=\"pull-left\" \"disabled\">Order Detail</span>";
		echo "<span class=\"pull-right\"><i class=\"fa fa-arrow-circle-right\"></i></span>";
                echo "<div class=\"clearfix\"></div>";
                echo "</div>";
                echo "</a>";
		}
              

                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</div>
<!-- /#page-wrapper -->

<div class="modal fade" id="xx" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">&nbsp;</h4>
                    </div>
                    <div class="modal-body">
                        <p>...</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
