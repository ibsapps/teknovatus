<div class="nk-ibx-head">
    <div class="nk-ibx-head-actions">
        <ul class="nk-ibx-head-tools g-1">
            <li>
                <a href="<?= site_url('home/request'); ?>" class="btn btn-icon btn-trigger"><em class="icon ni ni-undo"></em></a>
            </li>
        </ul>
    </div>
    <div>
        <ul class="nk-ibx-head-tools g-1">
            <li>
                <a href="#" class="btn btn-trigger btn-icon search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a>
            </li>
            <li class="mr-n1 d-lg-none">
                <a href="<?= site_url('home/request'); ?>" class="btn btn-trigger btn-icon toggle" data-target="inbox-aside"><em class="icon ni ni-menu-alt-r"></em></a>
            </li>
        </ul>
    </div>
    <div class="search-wrap" data-search="search">
        <div class="search-content">
            <a onclick="return clearSearch()" class="search-back btn btn-icon toggle-search" data-target="search">
                <em class="icon ni ni-arrow-left"></em>
            </a>
            
            <input type="text" onkeyup="search_approval()" id="search_approval" class="form-control border-transparent form-focus-none" placeholder="Search request">
            
            <button class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
        </div>
    </div>
</div>

<div class="nk-ibx-list" data-simplebar>
    
    <?php foreach ($header as $key => $value) { ?>
    
    <?php if ($value["is_status"] == '0' || $value["is_status"] == '4' || $value["is_status"] == '1' || $value["is_status"] == '2' || $value["is_status"] == '7') { 
        
        $url = base_url('form/detail/'.$value["form_type"].'/'.encode_url($value["id"]));
    } else {
        $url = base_url('form/detail_full_approve/'.$value["form_type"].'/'.encode_url($value["id"]));
    } ?>

    <div class="nk-ibx-item is-unread" style="display: show;">
        <div class="nk-ibx-item-elem nk-ibx-item-check">
            <div class="custom-control custom-control-sm custom-checkbox">
                <?php if ($value['is_status'] == 0): ?>
                    <a onclick="return delete_draft(this.id);" id="<?=$value['id']?>" class="btn btn-sm btn-icon btn-trigger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><em class="icon ni ni-trash"></em></a>
                <?php endif ?>
            </div>
        </div>
        <div class="nk-ibx-item-elem nk-ibx-item-star" onclick="location.href='<?=$url;?>'">
            <div class="asterisk"></div>
        </div>
        <div class="nk-ibx-item-elem nk-ibx-item-user" onclick="location.href='<?=$url;?>'">
            <div class="user-card">
                <div class="user-name">
                    <?= str_replace(".000","",$value['created_at']);?>
                </div>
            </div>
        </div>
        <div class="nk-ibx-item-elem nk-ibx-item-user" onclick="location.href='<?=$url;?>'">
            <div class="user-card">
                <div class="user-name">
                    <?php
                    if($value['is_status_admin_hr'] == 1){
                        echo "<div class='bg-teal-dim text-primary is-dim'>Checked By HR Support</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="nk-ibx-item-elem nk-ibx-item-fluid" onclick="location.href='<?=$url;?>'">
            <div class="nk-ibx-context-group">
                <div class="nk-ibx-context-badges"><?= status_text($value['is_status']);?></div>
                <div></div>
                    <div class="lead-text"><?= $value['request_number'];?></div>
                <div class="nk-ibx-context">
                    <!-- <span class="nk-ibx-context-text">
                        <span class="heading">Performance Appraisal & Plan </span>
                        - Evaluation period: <?= $value['evaluation_period_start'];?>  - <?= $value['evaluation_period_end'];?> 
                    </span> -->
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

</div>


