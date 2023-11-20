<!-- Page Content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Dashboard</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		<style>
        #mainContent {
        	display: table; /* Make the container element behave like a table */
            width: 100%;
        }
        
        .cols-me {
            display: table-cell;
        }
        </style>
		<div class="row" id="mainContent">
			<!-- Main Content -->
            <?Php foreach ($kategori_list as $kat) { ?>
                <div class="cols-me col-lg-3 col-md-6">
				<div class="panel panel-info">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fas fa-<?Php echo $kat['icon']; ?> fa-4x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<!--<div class="col-xs-12">-->
								<div class="huge"><?Php echo $kat['jml_item'] ?></div>
								<div data-toggle="tooltip" title="<?php echo $kat['kategori'] ?>"><?Php echo substr($kat['kategori'],0,18); if(strlen($kat['kategori'])>18){echo "...";}  ?></div>
							</div>
						</div>
					</div>
					<a href="<?Php echo base_url() ?>dashboard/index/kategori/<?Php echo $kat['id'] ?>/<?Php echo str_replace(" ","-",$kat['kategori']) ?>">
						<div class="panel-footer">
							<span class="pull-left">View Details</span> <span
								class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
            <?Php } ?>
        </div>
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->