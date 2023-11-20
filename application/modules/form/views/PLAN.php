<div class="nk-ibx-head">

    <?php if ($header['is_status'] == 3) {
        $disabled = 'disabled';
        $show = 'none';
    } else {
        $disabled = '';
        $show = '';
    } ?>

    <input type="hidden" id="id_request" name="id_request" value="<?=decode_url($this->uri->segment(4));?>">
	<input type="hidden" id="is_status" name="is_status" value="<?=$header['is_status'];?>">

    <div class="nk-ibx-head-actions">
        <ul class="nk-ibx-head-tools g-1">
            <li class="ml-n2">
            	<a href="<?= site_url('home/request'); ?>" class="btn btn-icon btn-tooltip" title="Back">
            		<em class="icon ni ni-arrow-left"></em>
            		Back
            	</a>
            </li>
            <li style="display: <?=$show?>">
            	<a id="submit_new_employee" class="btn btn-icon">
            		<em class="icon ni ni-send"></em>
            		Submit
            	</a>
            </li>
        </ul>
    </div>
</div>

<div class="nk-ibx-reply nk-reply" data-simplebar>

    <!-- Header Request -->
    <div class="nk-ibx-reply-head">
        <div>
            <h4 class="title"><span class="text-soft">CREATE: </span>NEW EMPLOYEE PERFORMANCE PLAN</h4>
            <ul class="nk-ibx-tags g-1">
                <li class="btn-group is-tags">
                    <strong>Evaluation Period: Januari <?=$eval_year = date('Y') - 1;?> - Desember <?=$eval_year = date('Y') - 1;?></strong>
                </li>
            </ul>
        </div>
        <ul class="d-flex g-1">
            <li class="d-none d-sm-block">
                <?= status_color($header['is_status']);?>
            </li>
        </ul>
    </div>

    <div class="nk-ibx-reply-group">

        <!-- Detail Request -->
        <div class="nk-ibx-reply-item nk-reply-item">

            <!-- Appraisal -->
            <div class="nk-reply-header nk-ibx-reply-header">
                <div class="nk-reply-desc">
                    <div class="nk-reply-info">
                        <div class="nk-reply-author lead-text">
                            <h5 class="text-soft">Personal Details</h5> 
                        </div>
                        <div class="nk-reply-msg-excerpt">Click to view</div>
                    </div>
                </div>
            </div>
            <div class="nk-reply-body nk-ibx-reply-body is-shown">
                <div class="nk-reply-entry entry">

                	<!-- Personal Details -->
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <strong class="text-secondary">I. PERSONAL DETAILS</strong>
                            </div>
                        </div>
                        <div class="card card-bordered card-preview">
                            <table class="table table-orders">
                                <tbody class="tb-odr-body">
                                    <tr class="tb-odr-item">
                                        <td class="tb-odr-info">
                                            <span class="tb-odr-id text-soft">Nama / NIK</span>
                                            <span class="tb-odr-id lead-primary fw-bold text-uppercase">
                                            	<mark><?= $header['employee_name']; ?> / <?= $header['employee_nik']; ?></mark>
                                            </span>
                                         </td>
                                     </tr>
                                     <tr class="tb-odr-item">
                                        <td class="tb-odr-info">
                                            <span class="tb-odr-id text-soft">Jabatan</span>
                                            <span class="tb-odr-id lead-primary fw-bold text-uppercase">
                                            	<mark><?= $header['position']; ?></mark>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="tb-odr-item">
                                        <td class="tb-odr-info">
                                            <span class="tb-odr-id text-soft">Jenis Karyawan</span>
                                            <span class="tb-odr-id lead-primary fw-bold text-uppercase">
                                            	<mark><?= $header['employment_status']; ?> </mark>
                                            </span>
                                        </td>
                                        <td class="tb-odr-info">
                                            <span class="tb-odr-id text-soft">Tanggal Bergabung</span>
                                            <span class="tb-odr-id lead-primary fw-bold text-uppercase">
                                            	<mark><?= $header['join_date']; ?> </mark>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="tb-odr-item">
                                        <td class="tb-odr-info">
                                            <span class="tb-odr-id text-soft">Divisi</span>
                                            <span class="tb-odr-id lead-primary fw-bold text-uppercase">
                                            	<mark><?= $header['division']; ?> </mark>
                                            </span>
                                        </td>
                                        <td class="tb-odr-info">
                                            <span class="tb-odr-id text-soft">Departemen</span>
                                            <span class="tb-odr-id lead-primary fw-bold text-uppercase">
                                                <input type="text" class="form-control" placeholder="Input Nama Departemen" name="kpi_departemen" id="kpi_departemen" value="<?php echo ($header['departement'] == '') ? '' : $header['departement']; ?>">
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="tb-odr-item">
                                        <td class="tb-odr-info">
                                            <span class="tb-odr-id text-soft">Div. Head / C-Level</span>
                                            <span class="tb-odr-id lead-primary fw-bold text-uppercase w-40">
                                                <!-- <input type="text" class="form-control" placeholder="Nama atasan langsung" name="atasan_langsung" id="atasan_langsung" value="<?php echo ($header['direct_manager'] == '') ? '' : $header['direct_manager']; ?>"> -->
                                                <select class="form-select required" data-search="on" data-msg="Required" id="atasan_langsung" name="atasan_langsung" required>
                                                    <?= $employee_list; ?>
                                                </select>
                                            </span>
                                        </td>
                                        <td class="tb-odr-info">
                                            <span class="tb-odr-id text-soft">Lokasi Kantor</span>
                                            <span class="tb-odr-id lead-primary fw-bold text-uppercase">
                                                <mark><?= $header['office_location']; ?></mark>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <br>
            <!-- Performance Plan -->
            <div class="nk-reply-header nk-ibx-reply-header">
                <div class="nk-reply-desc">
                    <div class="nk-reply-info">
                        <div class="nk-reply-author lead-text">
                            <h5 class="text-soft">Performance Plan</h5> 
                        </div>
                        <div class="nk-reply-msg-excerpt">Click to view</div>
                    </div>
                </div>
            </div>
            <div class="nk-reply-body nk-ibx-reply-body is-shown">
                <div class="nk-reply-entry entry">

                    <!-- Performance Objective/KPIs -->
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <strong class="text-secondary">I. PERFORMANCE OBJECTIVES / KPIs (**Maximum 2 rows on each perspective)</strong>
                                
                            </div>
                        </div>
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner p-0">
                                    <div class="invoice">
                                        <div class="invoice-bills">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th class="w-20">Performance Objective</th>
                                                            <th class="w-20">KPI Measurement</th>
                                                            <th class="w-15">Weight %</th>
                                                            <th class="text-left">Unit</th>
                                                            <th class="w-15 text-secondary text-left">Target <?= $nextYear = date('Y'); ?></th>
                                                            <th class="w-15 text-secondary text-left">Semester 1</th>
                                                            <th class="w-15 text-secondary text-left">Semester 2</th>
                                                            <th class="text-left">Total</th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="2" class="text-secondary">
                                                                Financial Perspective
                                                                <span>
                                                                    <a class="text-primary btn btn-icon btn-trigger" id="f" onclick="return modal_add_plan(this.id);" data-offset="-8,0"><em class="icon ni ni-plus-circle"></em> Add
                                                                    </a>
                                                                </span>
                                                            </th>
                                                            <th colspan="7">
                                                                <p class="text-danger text-uppercase" id="alert_financial" style="display: none;"><em class="icon ni ni-alert-circle-fill"></em> 
                                                                    Max 2 rows
                                                                </p> 
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="financial_perspective">
                                                        <?php $no = 1;
                                                        foreach ($additional as $key) { 
                                                        if ($key['plan_perspective'] == 'financial_perspective') { ?>
                                                            <tr id='plan_row-<?= $key['id']; ?>'>
                                                                <td>
                                                                    <a onclick='delete_plan_row(<?= $key["id"]; ?>)' class="btn btn-icon btn-trigger"><em class="icon ni ni-cross-circle-fill"></em></a>
                                                                    <br>
                                                                    <a onclick='modal_update_plan(<?=$key["id"]?>)' class="btn btn-icon btn-trigger"><em class="icon ni ni-edit"></em></a>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_objective-<?= $key['id']; ?>"><?= $key['objective']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_measurement-<?= $key['id']; ?>"><?= $key['measurement']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_time-<?= $key['id']; ?>"><?= $key['time']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_unit-<?= $key['id']; ?>"><?= $key['unit']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_target-<?= $key['id']; ?>"><?= $key['target']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_semester_1-<?= $key['id']; ?>"><?= $key['semester_1']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_semester_2-<?= $key['id']; ?>"><?= $key['semester_2']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <b id="kpi_plan_total-<?= $key['id']; ?>"><?= $key['total']; ?></b>
                                                                </td>
                                                            </tr>
                                                        <?php $no++; }  } ?>
                                                    </tbody>

                                                    <thead>
                                                        <tr>
                                                            <th colspan="2" class="text-secondary">
                                                                Customer Perspective
                                                                <span>
                                                                    <a class="text-primary btn btn-icon btn-trigger" id="c" onclick="return modal_add_plan(this.id);" data-offset="-8,0"><em class="icon ni ni-plus-circle"></em> Add
                                                                    </a>
                                                                </span>
                                                            </th>
                                                            <th colspan="7">
                                                                <p class="text-danger text-uppercase" id="alert_customer" style="display: none;"><em class="icon ni ni-alert-circle-fill"></em> 
                                                                    Max 2 rows
                                                                </p> 
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="cust_perspective">
                                                        <?php $no = 1;
                                                        foreach ($additional as $key) { 
                                                        if ($key['plan_perspective'] == 'cust_perspective') { ?>
                                                            <tr id='plan_row-<?= $key['id']; ?>'>
                                                                <td>
                                                                    <a onclick='delete_plan_row(<?= $key["id"]; ?>)' class="btn btn-icon btn-trigger"><em class="icon ni ni-cross-circle-fill"></em></a>
                                                                    <br>
                                                                    <a onclick='modal_update_plan(<?=$key["id"]?>)' class="btn btn-icon btn-trigger"><em class="icon ni ni-edit"></em></a>

                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_objective-<?= $key['id']; ?>"><?= $key['objective']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_measurement-<?= $key['id']; ?>"><?= $key['measurement']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_time-<?= $key['id']; ?>"><?= $key['time']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_unit-<?= $key['id']; ?>"><?= $key['unit']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_target-<?= $key['id']; ?>"><?= $key['target']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_semester_1-<?= $key['id']; ?>"><?= $key['semester_1']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_semester_2-<?= $key['id']; ?>"><?= $key['semester_2']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <b id="kpi_plan_total-<?= $key['id']; ?>"><?= $key['total']; ?></b>
                                                                </td>
                                                            </tr>
                                                        <?php $no++; }  } ?>
                                                    </tbody>

                                                    <thead>
                                                        <tr>
                                                            <th colspan="2" class="text-secondary">
                                                                Internal Process
                                                                <span>
                                                                    <a class="text-primary btn btn-icon btn-trigger" id="i" onclick="return modal_add_plan(this.id);" data-offset="-8,0"><em class="icon ni ni-plus-circle"></em> Add
                                                                    </a>
                                                                </span>
                                                            </th>
                                                            <th colspan="7">
                                                                <p class="text-danger text-uppercase" id="alert_internal" style="display: none;"><em class="icon ni ni-alert-circle-fill"></em> 
                                                                    Max 2 rows
                                                                </p> 
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="intern_perspective">
                                                        <?php $no = 1;
                                                        foreach ($additional as $key) { 
                                                        if ($key['plan_perspective'] == 'intern_perspective') { ?>
                                                            <tr id='plan_row-<?= $key['id']; ?>'>
                                                                <td>
                                                                    <a onclick='delete_plan_row(<?= $key["id"]; ?>)' class="btn btn-icon btn-trigger"><em class="icon ni ni-cross-circle-fill"></em></a>
                                                                    <br>
                                                                    <a onclick='modal_update_plan(<?=$key["id"]?>)' class="btn btn-icon btn-trigger"><em class="icon ni ni-edit"></em></a>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_objective-<?= $key['id']; ?>"><?= $key['objective']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_measurement-<?= $key['id']; ?>"><?= $key['measurement']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_time-<?= $key['id']; ?>"><?= $key['time']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_unit-<?= $key['id']; ?>"><?= $key['unit']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_target-<?= $key['id']; ?>"><?= $key['target']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_semester_1-<?= $key['id']; ?>"><?= $key['semester_1']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_semester_2-<?= $key['id']; ?>"><?= $key['semester_2']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <b id="kpi_plan_total-<?= $key['id']; ?>"><?= $key['total']; ?></b>
                                                                </td>
                                                            </tr>
                                                        <?php $no++; }  } ?>
                                                    </tbody>

                                                    <thead>
                                                        <tr>
                                                            <th colspan="2" class="text-secondary">
                                                                Learning & Growth
                                                                <span>
                                                                    <a class="text-primary btn btn-icon btn-trigger" id="l" onclick="return modal_add_plan(this.id);" data-offset="-8,0"><em class="icon ni ni-plus-circle"></em> Add
                                                                    </a>
                                                                </span>
                                                            </th>
                                                            <th colspan="7">
                                                                <p class="text-danger text-uppercase" id="alert_learning" style="display: none;"><em class="icon ni ni-alert-circle-fill"></em> 
                                                                    Max 2 rows
                                                                </p> 
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="learn_perspective">
                                                        <?php $no = 1;
                                                        foreach ($additional as $key) { 
                                                        if ($key['plan_perspective'] == 'learn_perspective') { ?>
                                                            <tr id='plan_row-<?= $key['id']; ?>'>
                                                                <td>
                                                                    <a onclick='delete_plan_row(<?= $key["id"]; ?>)' class="btn btn-icon btn-trigger"><em class="icon ni ni-cross-circle-fill"></em></a>
                                                                    <br>
                                                                    <a onclick='modal_update_plan(<?=$key["id"]?>)' class="btn btn-icon btn-trigger"><em class="icon ni ni-edit"></em></a>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_objective-<?= $key['id']; ?>"><?= $key['objective']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_measurement-<?= $key['id']; ?>"><?= $key['measurement']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_time-<?= $key['id']; ?>"><?= $key['time']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_unit-<?= $key['id']; ?>"><?= $key['unit']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_target-<?= $key['id']; ?>"><?= $key['target']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_semester_1-<?= $key['id']; ?>"><?= $key['semester_1']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <span id="kpi_plan_semester_2-<?= $key['id']; ?>"><?= $key['semester_2']; ?></span>
                                                                </td>
                                                                <td scope="row">
                                                                    <b id="kpi_plan_total-<?= $key['id']; ?>"><?= $key['total']; ?></b>
                                                                </td>
                                                            </tr>
                                                        <?php $no++; }  } ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="2">
                                                                <input type="hidden" id="countPlanFinancial" value="<?php echo ($header['count_plan_financial'] == 0) ? 0 : $header['count_plan_financial']; ?>">
                                                                <input type="hidden" id="countPlanCustomer" value="<?php echo ($header['count_plan_customer'] == 0) ? 0 : $header['count_plan_customer']; ?>">
                                                                <input type="hidden" id="countPlanInternal" value="<?php echo ($header['count_plan_internal'] == 0) ? 0 : $header['count_plan_internal']; ?>">
                                                                <input type="hidden" id="countPlanLearning" value="<?php echo ($header['count_plan_learning'] == 0) ? 0 : $header['count_plan_learning']; ?>">
                                                            </td>
                                                            <td class="text-secondary">
                                                                <b>Total Weight(%)</b>
                                                            </td>
                                                            <td colspan="6" class="text-left">
                                                                <input type="text" disabled size="5" value="<?php echo ($header['plan_total_weight'] == 0) ? 0 : $header['plan_total_weight']; ?>" name="plan_total_weight" id="plan_total_weight">
                                                                <br>
                                                                <p class="text-danger text-uppercase" id="alert_plan_weight" style="display: none;">
                                                                    <em class="icon ni ni-alert-circle-fill"></em> 
                                                                    Total max Weight must be 100
                                                                </p> 
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <!-- Approval Layer -->
            <div class="nk-reply-header nk-ibx-reply-header">
                <div class="nk-reply-desc">
                    <div class="nk-reply-info">
                        <div class="nk-reply-author lead-text">
                            <h5 class="text-soft">Approval Layer</h5> 
                        </div>
                        <div class="nk-reply-msg-excerpt">Click to view</div>
                    </div>
                </div>
            </div>

            <div class="nk-reply-body nk-ibx-reply-body is-shown">
                <div class="nk-reply-entry entry">
                    <div class="nk-block nk-block-lg">

                        <div class="col-md-6">
                            <div class="card card-bordered card-stretch" style="width:auto;">
                                <div class="card-inner-group">
                                    <div class="card-inner">
                                        <div class="card-title-group">
                                            <div class="card-title">
                                                <h6 class="title">Select Approver </h6> 
                                            </div>
                                            <div class="card-tools">
                                                <button type="button" name="add" id="add" class="btn btn-primary">
                                                    <span id="addLayerText" style="display: show;">Add</span>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" id="loadLayerSpinner" style="display: none;"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="dynamic_field">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
        </div>

        <!-- Request Notes -->
        <div class="nk-ibx-reply-item nk-reply-item">
            <div class="nk-reply-header nk-ibx-reply-header">
                <div class="nk-reply-desc">
                    <div class="nk-reply-info">
                        <div class="nk-reply-author lead-text">
                            <h5 class="text-soft">Notes</h5> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-reply-body nk-ibx-reply-body is-shown">
                <div class="nk-reply-entry entry">
                    <div class="nk-block">
                        <div class="nk-block-head nk-block-head-sm nk-block-between">
                            <a data-toggle="modal" data-target="#modalAddNotes" class="btn btn-md text-primary">+ Add Note</a>
                        </div>
                        <?php foreach ($notes as $key => $value) { ?>
                            <div class="bq-note">
                                <div class="bq-note-item">
                                    <div class="bq-note-text">
                                        <p><?= $value['notes']?></p>
                                    </div>
                                    <div class="bq-note-meta">
                                        <span class="bq-note-added">Added on <span class="date"><?= $value['created_at'] ?></span></span>
                                        <span class="bq-note-sep sep">|</span>
                                        <span class="bq-note-by text-dark">By <strong><?= $value['created_by'] ?></strong></span>
                                        <a id="<?= $value['id'] ?>" style="cursor: pointer;"  onclick="return delete_notes(this.id)" class="link link-sm link-danger">Delete Note</a>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalAddPlan">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"> <em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-md">
                <h5 class="title" id="title_modal_plan"></h5>
                <form action="#" class="pt-2 form-validate is-alter">
                    <div class="row gy-3 gx-gs">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="kpi-objective">Performance Objective</label>
                                <textarea class="form-control" required placeholder="Performance Objective" name="plan_objective" id="plan_objective"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">KPI Measurement</label>
                                <textarea class="form-control" required placeholder="KPI Measurement" name="plan_measurement" id="plan_measurement"></textarea>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Weight (%)</label>
                                <div class="form-control-wrap">
                                    <input type="number" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" maxlength="3" min="1" max="100" class="form-control" placeholder="Weight" name="plan_time" id="plan_time">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Unit</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" required placeholder="Unit" name="plan_unit" id="plan_unit">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Target next year</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" required placeholder="Target" name="plan_target" id="plan_target">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Semester I</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" required placeholder="Semester I" name="plan_semester_1" id="plan_semester_1">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Semester II</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" required placeholder="Semester II" name="plan_semester_2" id="plan_semester_2">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Total</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" placeholder="Total" name="plan_total" id="plan_total">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">

                                <input type="hidden" id="table_type">

                                <a href="#" class="btn btn-danger" data-dismiss="modal"> Cancel</a>

                                <button type="button" class="btn btn-primary add_plan" id="add_plan">Save Informations</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalUpdatePlan">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"> <em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-md">
                <h5 class="title" id="title_modal_plan_update"></h5>
                <form action="#" class="pt-2 form-validate is-alter">
                    <div class="row gy-3 gx-gs">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="kpi-objective">Performance Objective</label>
                                <textarea class="form-control" required placeholder="Performance Objective" name="update_plan_objective" id="update_plan_objective"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">KPI Measurement</label>
                                <textarea class="form-control" required placeholder="KPI Measurement" name="update_plan_measurement" id="update_plan_measurement"></textarea>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Weight (%)</label>
                                <div class="form-control-wrap">
                                    <input type="number" onkeypress="return isNumeric(event)" oninput="maxLengthCheck(this)" maxlength="3" min="1" max="100" class="form-control" placeholder="Weight" name="update_plan_time" id="update_plan_time">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Unit</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" required placeholder="Unit" name="update_plan_unit" id="update_plan_unit">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Target next year</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" required placeholder="Target" name="update_plan_target" id="update_plan_target">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Semester I</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" required placeholder="Semester I" name="update_plan_semester_1" id="update_plan_semester_1">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Semester II</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" required placeholder="Semester II" name="update_plan_semester_2" id="update_plan_semester_2">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Total</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" placeholder="Total" name="update_plan_total" id="update_plan_total">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">

                                <input type="hidden" id="table_type_update">
                                <input type="hidden" id="id_plan_update">

                                <a href="#" class="btn btn-danger" data-dismiss="modal"> Cancel</a>

                                <button type="button" class="btn btn-primary update_plan" id="update_plan">Update Informations</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalAddNotes">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card-inner">
                    <div class="card-head">
                        <h5 class="card-title">Notes</h5>
                    </div>
                    <div class="card-content">
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <textarea class="form-control form-control-sm" id="response-notes" name="response-notes" placeholder="Write your notes here..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sp-package-action">
                    <a href="#" class="btn btn-dim btn-danger" data-dismiss="modal" data-toggle="modal">Cancel</a>
                    <button type="button" onclick="return save_response_notes();" class="btn btn-md btn-primary"><span class="text-notes-response"> Save</span></button>
                </div>

            </div>
        </div>
    </div>
</div>
