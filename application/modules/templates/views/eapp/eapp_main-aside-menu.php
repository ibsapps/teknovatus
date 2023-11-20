<div class="nk-ibx-aside" data-content="inbox-aside" data-toggle-overlay="true" data-toggle-screen="lg">
    <?php
        if($this->session->userdata('access_employee') != '13'){
    ?>
    <div class="nk-ibx-head">
        <div class="mr-n1">
            <a href="#" class="link link-text" data-toggle="modal" data-target="#create-request"><em class="icon-circle icon ni ni-plus-c"></em> <span>Create New</span></a> 
        </div>
    </div>
    <?php
        }
    ?>

    <div class="nk-ibx-nav" data-simplebar>

        <ul class="nk-ibx-menu">

            <li>
            <!-- <li <?php echo ($this->uri->segment(2) == 'dashboard') ? 'class="active"' : ''; ?>> -->
                <a class="nk-ibx-menu-item" href="<?= site_url('dashboard/dashboard'); ?>">
                <em class="icon ni ni-dashboard"></em>
                    <span class="nk-ibx-menu-text">Dashboard</span>
                </a>
            </li>
            <?php if ($this->session->userdata('access_employee') == '12'){ ?>
            <li <?php echo ($this->uri->segment(2) == 'request') ? 'class="active"' : ''; ?>>
                <a class="nk-ibx-menu-item" href="<?= site_url('home/request'); ?>">
                    <em class="icon ni ni-edit"></em>
                    <span class="nk-ibx-menu-text">My Submission <span class="badge badge-pill badge-primary"><?=($count_mysubmission == 0) ? '' : $count_mysubmission;?></span></span>
                </a>
            </li>
            <li class="menu-item_2">
                <a href="#" class="nk-ibx-menu-item nk-menu-toggle"><em class="icon ni ni-file-docs"></em>
                    <span class="nk-ibx-menu-text">Approval List</span>
                </a>
                <ul class="sub-menu_2 nk-ibx-menu-sub">
                    <li class="nk-ibx-menu-item">
                    <a class="nk-ibx-menu-link" href="<?= site_url('inbox/approval'); ?>">
                    <!-- <em class="icon ni ni-file-docs"></em> -->
                    <span class="nk-ibx-menu-text">Approval All <span class="badge badge-pill badge-primary"><?=($count_approval == 0) ? '' : $count_approval;?></span></span>
                    </a>
                    </li>
                    <li class="nk-ibx-menu-item">
                    <a class="nk-ibx-menu-link" href="<?= site_url('inbox/approval_mdcr'); ?>">
                        <span class="nk-ibx-menu-text">Medical Approval<span class="badge badge-pill badge-primary"><?=($count_need_mdcr_cek == 0) ? '' : $count_need_mdcr_cek;?></span></span>
                    </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item_2">
            <a href="#" class="nk-ibx-menu-item nk-menu-toggle"><em class="icon ni ni-file-docs"></em>
                    <span class="nk-ibx-menu-text">Medical Report</span>
                </a>
                <ul class="sub-menu_2 nk-ibx-menu-sub">
                    <li class="nk-ibx-menu-item">
                    <a class="nk-ibx-menu-link" href="<?= site_url('report/medical_control_sheets'); ?>">
                        <span class="nk-ibx-menu-text">Medical Control Sheets<span class="badge badge-pill badge-primary"></span></span>
                    </a>
                    </li>
                    <li class="nk-ibx-menu-item">
                    <a class="nk-ibx-menu-link" href="<?= site_url('report/medical_monthly_report'); ?>">
                        <span class="nk-ibx-menu-text">Medical Monthly Report<span class="badge badge-pill badge-primary"></span></span>
                    </a>
                    </li>
                </ul>
            </li>
            <?php } elseif ($this->session->userdata('access_employee') == '13'){ ?>
                <li class="menu-item_2">
                    <a href="#" class="nk-ibx-menu-item nk-menu-toggle"><em class="icon ni ni-file-docs"></em>
                        <span class="nk-ibx-menu-text">Medical Report</span>
                    </a>
                    <ul class="sub-menu_2 nk-ibx-menu-sub">
                        <li class="nk-ibx-menu-item">
                        <a class="nk-ibx-menu-link" href="<?= site_url('report/medical_control_sheets'); ?>">
                            <span class="nk-ibx-menu-text">Medical Control Sheets<span class="badge badge-pill badge-primary"></span></span>
                        </a>
                        </li>
                    </ul>
                </li>
            <?php } elseif ($this->session->userdata('access_level') == '9'){ ?>
                <li <?php echo ($this->uri->segment(2) == 'request') ? 'class="active"' : ''; ?>>
                    <a class="nk-ibx-menu-item" href="<?= site_url('home/request'); ?>">
                        <em class="icon ni ni-edit"></em>
                        <span class="nk-ibx-menu-text">My Submission <span class="badge badge-pill badge-primary"><?=($count_mysubmission == 0) ? '' : $count_mysubmission;?></span></span>
                    </a>
                </li>
                <li class="menu-item_2">
                <a href="#" class="nk-ibx-menu-item nk-menu-toggle"><em class="icon ni ni-file-docs"></em>
                    <span class="nk-ibx-menu-text">Approval List</span>
                </a>
                <ul class="sub-menu_2 nk-ibx-menu-sub">
                    <li class="nk-ibx-menu-item">
                    <a class="nk-ibx-menu-link" href="<?= site_url('inbox/approval'); ?>">
                    <span class="nk-ibx-menu-text">Approval All <span class="badge badge-pill badge-primary"><?=($count_approval == 0) ? '' : $count_approval;?></span></span>
                    </a>
                    </li>
                    <li class="nk-ibx-menu-item">
                    <a class="nk-ibx-menu-link" href="<?= site_url('inbox/approval_mdcr_to_fi'); ?>">
                        <span class="nk-ibx-menu-text">Medical Approval<span class="badge badge-pill badge-primary"><?=($count_mdcr_after_grouping_need_approved == 0) ? '' : $count_mdcr_after_grouping_need_approved;?></span></span>
                    </a>
                    </li>
                </ul>
            </li>
            <?php } else { ?>
                <li <?php echo ($this->uri->segment(2) == 'request') ? 'class="active"' : ''; ?>>
                    <a class="nk-ibx-menu-item" href="<?= site_url('home/request'); ?>">
                        <em class="icon ni ni-edit"></em>
                        <span class="nk-ibx-menu-text">My Submission <span class="badge badge-pill badge-primary"><?=($count_mysubmission == 0) ? '' : $count_mysubmission;?></span></span>
                    </a>
                </li>
                <li <?php echo ($this->uri->segment(2) == 'approval') ? 'class="active"' : ''; ?>>
                <a class="nk-ibx-menu-item" href="<?= site_url('inbox/approval'); ?>">
                    <em class="icon ni ni-file-docs"></em>
                    <span class="nk-ibx-menu-text">Approval List</span>
                    <span class="badge badge-pill badge-primary"><?=($count_approval == 0) ? '' : $count_approval;?></span>
                </a>
            </li>
            <?php } ?>
        </ul>

        <?php if ($this->session->userdata('access_employee') == '2' || $this->session->userdata('access_employee') == '99') { ?>
        <div class="nk-ibx-nav-head">
            <h6 class="title">Management</h6>
        </div>
        <ul class="nk-ibx-label">

            <li <?php echo ($this->uri->segment(2) == 'pa_management') ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('inbox/pa_management'); ?>">
                    <span class="nk-ibx-label-dot dot dot-xl dot-label bg-danger"></span>
                    <span class="nk-ibx-label-text">Performance Appraisal</span>
                </a>
            </li>

            <li <?php echo ($this->uri->segment(2) == 'mgmt') ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('inbox/mgmt'); ?>">
                    <span class="nk-ibx-label-dot dot dot-xl dot-label bg-danger"></span>
                    <span class="nk-ibx-label-text">Summary</span>
                </a>
            </li>
        </ul>
        <?php } ?>

        <?php if ($this->session->userdata('access_employee') == '3' || $this->session->userdata('access_employee') == '99') { ?>
        <div class="nk-ibx-nav-head">
            <h6 class="title">Management</h6>
        </div>
        <ul class="nk-ibx-label">

            <li <?php echo ($this->uri->segment(2) == 'pa_management') ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('inbox/pa_management'); ?>">
                    <span class="nk-ibx-label-dot dot dot-xl dot-label bg-danger"></span>
                    <span class="nk-ibx-label-text">Performance Appraisal</span>
                </a>
            </li>

            <li <?php echo ($this->uri->segment(2) == 'c') ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('dashboard/c'); ?>">
                    <span class="nk-ibx-label-dot dot dot-xl dot-label bg-info"></span>
                    <span class="nk-ibx-label-text">Summary</span>
                </a>
            </li>
        </ul>
        <?php } ?>

        <?php if ($this->session->userdata('access_employee') == '4' || $this->session->userdata('access_employee') == '99') { ?>
        <div class="nk-ibx-nav-head">
            <h6 class="title">Management</h6>
        </div>
        <ul class="nk-ibx-label">
            <li <?php echo ($this->uri->segment(2) == 'pa_management') ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('dashboard/pa_management'); ?>">
                    <span class="nk-ibx-label-dot dot dot-xl dot-label bg-danger"></span>
                    <span class="nk-ibx-label-text">Performance Appraisal</span>
                </a>
            </li>
            <li <?php echo ($this->uri->segment(2) == 'mgmt') ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('dashboard/mgmt'); ?>">
                    <span class="nk-ibx-label-dot dot dot-xl dot-label bg-danger"></span>
                    <span class="nk-ibx-label-text">Division Summary</span>
                </a>
            </li>
            <li <?php echo ($this->uri->segment(2) == 'm') ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('dashboard/m'); ?>">
                    <span class="nk-ibx-label-dot dot dot-xl dot-label bg-info"></span>
                    <span class="nk-ibx-label-text">All Summary</span>
                </a>
            </li>
        </ul>
        <?php } ?>

        <?php if ($this->session->userdata('access_employee') == '11' || $this->session->userdata('access_employee') == '99') { ?>
        <div class="nk-ibx-nav-head">
            <h6 class="title">Human Resource</h6>
        </div>
        <ul class="nk-ibx-label">
            <li <?php echo ($this->uri->segment(2) == 'hr_division') ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('inbox/hr_division'); ?>">
                    <span class="nk-ibx-label-dot dot dot-xl dot-label bg-info"></span>
                    <span class="nk-ibx-label-text">Division Review</span>
                </a>
            </li>
            <li <?php echo ($this->uri->segment(2) == 'hr_confirmed') ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('inbox/hr_confirmed'); ?>">
                    <span class="nk-ibx-label-dot dot dot-xl dot-label bg-info"></span>
                    <span class="nk-ibx-label-text">Confirmed PA & Plan</span>
                </a>
            </li>
           <li <?php echo ($this->uri->segment(2) == 'm') ? 'class="active"' : ''; ?>>
                <a href="<?= site_url('dashboard/h'); ?>">
                    <span class="nk-ibx-label-dot dot dot-xl dot-label bg-info"></span>
                    <span class="nk-ibx-label-text">All Summary</span>
                </a>
            </li>
        </ul>
        <?php } ?>

        <?php if ($this->session->userdata('access_employee') == '12' || $this->session->userdata('access_employee') == '99') { ?>
        <div class="nk-ibx-nav-head">
            <h6 class="title">Configuration</h6>
        </div>
        <ul class="nk-ibx-label">
            <li <?php echo ($this->uri->segment(2) == 'm') ? 'class="active"' : ''; ?>>
              <a href="<?= site_url('master/employee'); ?>">
                <span class="nk-ibx-label-dot dot dot-xl dot-label bg-info"></span>
                <span class="nk-ibx-label-text">Master Employee</span>
              </a>
            </li>
<!--             <li <?#php echo ($this->uri->segment(2) == 'm') ? 'class="active"' : ''; ?>>
              <a href="<?#= site_url('master/regional_pm'); ?>">
                <span class="nk-ibx-label-dot dot dot-xl dot-label bg-info"></span>
                <span class="nk-ibx-label-text">Master RPM</span>
              </a>
            </li> -->
            <li <?php echo ($this->uri->segment(2) == 'm') ? 'class="active"' : ''; ?>>
              <a href="<?= site_url('master/users'); ?>">
                <span class="nk-ibx-label-dot dot dot-xl dot-label bg-info"></span>
                <span class="nk-ibx-label-text">Users</span>
              </a>
            </li>
            <li <?php echo ($this->uri->segment(2) == 'm') ? 'class="active"' : ''; ?>>
              <a href="<?= site_url('master/medical_plafon'); ?>">
                <span class="nk-ibx-label-dot dot dot-xl dot-label bg-info"></span>
                <span class="nk-ibx-label-text">Medical - Plafon</span>
              </a>
            </li>
            <li <?php echo ($this->uri->segment(2) == 'm') ? 'class="active"' : ''; ?>>
              <a href="<?= site_url('master/medical_type_of_reimbursment'); ?>">
                <span class="nk-ibx-label-dot dot dot-xl dot-label bg-info"></span>
                <span class="nk-ibx-label-text">Medical - Type of Reimbursment</span>
              </a>
            </li>
        </ul>
        <?php } ?>
       
    </div>
</div><!-- .nk-ibx-aside -->