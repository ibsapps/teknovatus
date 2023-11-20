<style>
.scroll{
  width: 100%;
  background: silver;
  padding: 5px;
  overflow: scroll;
  height: 100%;
  
  /*script tambahan khusus untuk IE */
  scrollbar-face-color: #CE7E00; 
  scrollbar-shadow-color: #FFFFFF; 
  scrollbar-highlight-color: #6F4709; 
  scrollbar-3dlight-color: #11111; 
  scrollbar-darkshadow-color: #6F4709; 
  scrollbar-track-color: #FFE8C1; 
  scrollbar-arrow-color: #6F4709;
}
</style>
<div class="nk-ibx-head">
    <div class="nk-ibx-head-actions">
        <ul class="nk-ibx-head-tools g-1">
            <li>
                <a href="<?= site_url('inbox/approval_mdcr_to_fi'); ?>" class="btn btn-icon btn-trigger"><em class="icon ni ni-undo"></em></a>
            </li>
        </ul>
    </div>
    <div>
        <ul class="nk-ibx-head-tools g-1">
            <li>
                <a href="#" class="btn btn-trigger btn-icon search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a>
            </li>
            <li class="mr-n1 d-lg-none">
                <a href="<?= site_url('inbox/approval_mdcr_to_fi'); ?>" class="btn btn-trigger btn-icon toggle" data-target="inbox-aside"><em class="icon ni ni-menu-alt-r"></em></a>
            </li>
        </ul>
    </div>
    <div class="search-wrap" data-search="search">
        <div class="search-content">
            <a onclick="return clearSearchMdcr()" class="search-back btn btn-icon toggle-search" data-target="search">
                <em class="icon ni ni-arrow-left"></em>
            </a>
            
            <input type="text" onkeyup="search_approval_mdcr_to_fi()" id="search_approval_mdcr_fi" class="form-control border-transparent form-focus-none" placeholder="Search request">
            
            <button class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
        </div>
    </div>
</div>

<div class="nk-ibx-list" data-simplebar>


<div class="tab-content">
<div>
    <ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card">
        <li class="nav-item">
            <a class="nav-link active" href="#inbox_mdcr_process_fi" role="tab" data-toggle="tab"><em
                    class="icon ni ni-archived-fill"></em><span>Process FI</span></a>
        </li>
    </ul>
</div>
<div class="tab-content">
    <div class="tab-pane active" id="inbox_mdcr_process_fi">
        <div class="card-inner">
                <table class="table table-striped" id="list_mdcr_proccess_fi">
					<thead>
                        <tr>
                            <th style='width: 40%'>Request Number Grouping</th>
							<th style='width: 40%'>Status</th>
							<th style='width: 20%'>Action</th>
                        </tr>
                    </thead>
				
					<tbody>
					<?php
						foreach($header_mdcr_after_grouping as $key => $value){
                        $no_req = $value['no_req_mdcr'];
                        $no_req = str_replace("HRIS_MDCR","",$no_req);
						echo"
                        <tr class='SearchMDCR'>
                          <td>".$value['no_req_mdcr']."</td>
                          <td>".status_color($value['is_status'])."</td>";
                    ?>
                          <td><a class="btn btn-outline-warning" data-toggle="modal" id="<?php echo $value['no_req_mdcr'];?>" onClick="viewResumeNoReqToFI('<?php echo $value['no_req_mdcr'];?>')" >
                          <em class="icon ni ni-list"></em>
                          </a>
                          <a data-toggle="modal" data-offset="-4,0" id="<?php echo $value['no_req_mdcr']?>" onClick="print_out_req_mdcr_all_per_day('<?php echo $value['no_req_mdcr']?>')" class="btn btn-outline-primary" title='Recap Medical Reimbursement'><em class="icon ni ni-printer"></em></a>
                          <?php if($value['is_status'] !='3'){
                            ?>
                          <a data-toggle="modal" data-offset="-4,0" id="<?php echo $value['no_req_mdcr']?>" onClick="action_approve_devhead_hr('<?php echo $value['no_req_mdcr']?>')" class="btn btn-outline-danger"><em class="icon ni ni-sign-dash"></em></a></td>
                          <?php
                          }
                          ?>
                        </tr>
					<?php	}
					?>
					
					</tbody>
					 
				</table>
        </div>
    </div>
    
</div>

</div>
</div>


<!-- ////////////////////////////////////////Modal Ubah///////////////////////////////////// -->

<!-- <div class="modal fade" role="dialog" id="modalResumeNoReqToFI">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"> <em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-md">
                <h5 class="title">Resume Medical Clime To FI</h5>
                <form action="#" class="pt-2 form-validate is-alter">
                    <div class="row gy-3 gx-gs">
                    
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->

<div id="mod_resume_no_req_to_fi"></div>