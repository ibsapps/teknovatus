<html lang="en">

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <main role="main" class="container">

      	<div style="page-break-inside: avoid">
	        <br>
	        <div class="row">
	          <table>
	              <tr>
	                  <th><img src="<?= base_url('assets/images/logo_ibs.png'); ?>" style="max-height:90px;"></th>
	              </tr>
	          </table>
	        </div>
	        <br>
	        
	        <div class="col-12">
		        <h5 class="text-center"><b>PERFORMANCE APPRAISAL</b></h5>
		        <h6 class="text-center"><b>Evaluation Period: 1 Jan 2020 - 31 Dec 2020</b></h6>
		        <p class="text-center"><?= $header['request_number'] ?></p>
		    </div>

	      	<!-- Personal Detail -->
	        <table class="table">
		     	<tr>
					<td style="border-bottom: 1px solid #000000" colspan=5 align="left" valign=bottom bgcolor="#FFFFFF">
						<b><font size=4>I. PERSONAL DETAILS</font></b>
					</td>
				</tr>
				<tr>
					<td></td>
					<td colspan=1 align="left" valign=bottom bgcolor="#FFFFFF"><b><font size=3>Name/Employee ID</font></b></td>
					<td align="left" valign=middle bgcolor="#FFFFFF"><font size=3><?=$employee['employee_name'] .' / '. $employee['employee_nik']?></font></td>
					<td colspan=1 align="left" valign=middle bgcolor="#FFFFFF"><b><font size=3>Evaluation Period</font></b></td>
					<td colspan=4 align="left" valign=middle bgcolor="#FFFFFF"><font size=3>Januari 2020 - Desember 2020</font></td>
					</tr>
				<tr>
				<tr>
					<td></td>
					<td colspan=1 align="left" valign=middle bgcolor="#FFFFFF"><b><font size=3>Position</font></b></td>
					<td align="left" valign=middle bgcolor="#FFFFFF"><font size=3><?=$employee['position']?></font></td>
					<td colspan=1 align="left" valign=middle bgcolor="#FFFFFF"><b><font size=3>Join Date</font></b></td>
					<td colspan=4 align="left" valign=middle bgcolor="#FFFFFF" sdval="42870" sdnum="1033;1033;D-MMM-YY"><font size=3><?=$employee['join_date']?></font></td>
					</tr>
				<tr>
					<td></td>
					<td colspan=1 align="left" valign=middle bgcolor="#FFFFFF"><b><font size=3>Departement</font></b></td>
					<td align="left" valign=middle bgcolor="#FFFFFF"><font size=3><?=$header['departement']?></font></td>
					<td colspan=1 align="left" valign=middle bgcolor="#FFFFFF"><b><font size=3>Employment Type</font></b></td>
					<td colspan=4 align="left" valign=middle bgcolor="#FFFFFF"><font size=3><?=$employee['employment_status']?></font></td>
					</tr>
				<tr>
					<td></td>
					<td colspan=1 align="left" valign=middle bgcolor="#FFFFFF"><b><font size=3>Division</font></b></td>
					<td align="left" valign=middle bgcolor="#FFFFFF"><font size=3><?=$employee['division']?></font></td>
					<td colspan=1 align="left" valign=middle bgcolor="#FFFFFF"><b><font size=3>Office Location</font></b></td>
					<td colspan=4 align="left" valign=middle bgcolor="#FFFFFF"><font size=3><?=$employee['office_location']?></font></td>
					</tr>
				<tr>
					<td></td>
					<td colspan=1 align="left" valign=middle bgcolor="#FFFFFF"><b><font size=3>Div. Head / C-Level</font></b></td>
					<td colspan=1 align="left" valign=middle bgcolor="#FFFFFF"><font size=3><?=$header['direct_manager']?></font></td>
					<td align="left" valign=middle bgcolor="#FFFFFF"><font size=3></font></td>
					<td align="left" valign=middle bgcolor="#FFFFFF"><font size=3></font></td>
				</tr>
			</table>
	        <br>

	        <!-- KPI -->
	    	<table class="table table-striped">
	    		<thead>
	        	<tr>
					<td style="border-bottom: 1px solid #000000" colspan=9 align="left" valign=bottom bgcolor="#FFFFFF">
						<b><font size=4>II. KPI ACHIEVEMENT</font></b>
					</td>
				</tr>
				<tr>
					<th>No</th>	
					<th>OBJECTIVE</th>
					<th>MEASUREMENT</th>
					<th style="font-size: 13px;">TARGET/YEAR</th>
					<th style="font-size: 13px;">ACHIEVEMENT</th>
					<th style="font-size: 13px;">TARGET VS ACHIEVEMENT</th>
					<th style="font-size: 13px;">SCORE</th>
					<th style="font-size: 13px;">WEIGHT</th>
					<th style="font-size: 13px;">TOTAL</th>
				</tr>
				</thead>
				<tbody style="font-size: 13px;">

				<?php $no=1; foreach ($detail_kpi as $key => $value) { ?>
				<tr>
					<td><font color="#000000"><?=$no;?></td>
					<td><font color="#000000"><?=$value['objective']?></font></td>
					<td><font color="#000000"><?=$value['measurement']?></font></td>
					<td><font color="#000000"><?=$value['target_per_year']?></font></td>
					<td><font color="#000000"><?=$value['achievement']?></font></td>
					<td><font color="#000000"><?=$value['target_vs_achievement']?></font></td>
					<td><font color="#000000"><?=$value['score']?></font></td>
					<td><font color="#000000"><?=$value['time']?>%</font></td>
					<td><font color="#000000"><?=$value['total']?></font></td>
				</tr>
				<?php $no++;} ?> 
				</tbody>
				<tfoot>
				<tr>
					<td class="text-secondary" colspan="7" align="right"><b>Total Weight</b></td>
					<td><b><?=$header['sub_total_weight']?>%</b></td>
				</tr>
				<tr>
					<td class="text-secondary" colspan="8" align="right"><b>Total KPI Score</b></td>
					<td><b><?=$header['sub_total_kpi']?></b></td>
				</tr>
				</tfoot>
			</table>
        </div>

		<div style="page-break-inside: avoid">
	        <div class="row">
	          <table>
	              <tr>
	                  <th><img src="<?= base_url('assets/images/logo_ibs.png'); ?>" style="max-height:90px;"></th>
	              </tr>
	          </table>
	        </div>
	        <br>
	        
	        <div class="col-12">
		        <h5 class="text-center"><b>PERFORMANCE APPRAISAL</b></h5>
		        <h6 class="text-center"><b>Evaluation Period: 1 Jan 2020 - 31 Dec 2020</b></h6>
		        <p class="text-center"><?= $header['request_number'] ?></p>

		    </div>
		    <!-- Qualitative -->
			<table class="table table-striped">
	            <thead>
	            	<tr>
						<td colspan="8">
							<b><font size=4>III. QUALITATIVE ASSESMENT</font></b>
						</td>
	            	</tr>
	                <tr>
	                    <th>COMPETENCIES</th>
	                    <th>WEIGHT</th>
	                    <th>SCORE (1-10)</th>
	                    <th>Score vs Weight</th>
	                    <th>Weak (1-5)</th>
	                    <th>Moderate (6-7)</th>
	                    <th>Strong (8-9)</th>
	                    <th>Exceptional (10)</th>
	                </tr>
	            </thead>
	            <tbody style="font-size: 13px;">
	                <tr id="tr_1">
	                    <td>Work Efficiency</td>
	                    <td>
	                        15%
	                    </td>
	                    <td>
	                        <?= $header['work_efficiency']; ?>
	                    </td>
	                    <td>
	                        <?= $header['work_efficiency_result']; ?>
	                    </td>
	                    <td><small>Unable to complete assigned work.</small></small></td>
	                    <td><small>Able to complete most assigned work</small></td>
	                    <td><small>Able to complete all assigned work</small></td>
	                    <td><small>Consistently delivers additional work.</small></td>
	                </tr>
	                <tr id="tr_2">
	                    <td>Work Quality</td>
	                    <td>15%</td>
	                    <td><?= $header['work_quality']; ?></td>
	                    <td><?= $header['work_quality_result']; ?></td>
	                    <td><small>Work quality is below expectations</small></td>
	                    <td><small>Work quality meets expectations</small></td>
	                    <td><small>Work quality meets and sometimes exceeds expectations</small></td>
	                    <td><small>Work quality consistently exceeds expectations</small></td>
	                </tr>
	                <tr id="tr_3">
	                    <td>Communication</td>
	                    <td>
	                        10%
	                    </td>
	                    <td>
	                        <?= $header['communication']; ?>
	                    </td>
	                    <td>
	                        <?= $header['communication_result']; ?>
	                    </td>
	                    <td><small>Weak delivery and content</small></td>
	                    <td><small>Moderate delivery and content</small></td>
	                    <td><small>Good delivery and adequate content</small></td>
	                    <td><small>Excellent delivery and very adequate content</small></td>
	                </tr>
	               	<tr id="tr_4">
	                    <td>Planning and Organizing</td>
	                    <td>
	                        10%
	                    </td>
	                    <td>
	                        <?= $header['planing']; ?>
	                    </td>
	                    <td>
	                        <?= $header['planing_result']; ?>
	                    </td>
	                    <td><small>Lacks ability to plan. Lacks ability to set priorities.</small></td>
	                    <td><small>Shows some ability to plan. Shows some ability to set priorities. Shows some ability to organize work tasks.</small></td>
	                    <td><small>Able to plan. Able to set priorities. Able to organize work tasks.</small></td>
	                    <td><small>Shows high skiils in planning and priority setting. Very organized in accomplishing work tasks.</small></td>
	                </tr>
	                <tr id="tr_5">
	                    <td>Problem Solving</td>
	                    <td>
	                        10%
	                    </td>
	                    <td>
	                        <?= $header['problem_solving']; ?>
	                    </td>
	                    <td>
	                        <?= $header['problem_solving_result']; ?>
	                    </td>
	                    <td><small>Unable to identify the real problems and the causes of the problems.</small></td>
	                    <td><small>Able to identify the real problems or the causes of the problems</small></td>
	                    <td><small>Able to identify the real problems or the causes of the problems. Able to solve problems with good results.</small></td>
	                    <td><small>Able to identify the real problems or the causes of the problems. Able to solve problems systematically and analytically with significant results and has a back-up plan.</small></td>
	                </tr>
	                <tr id="tr_6">
	                    <td>Team Work</td>
	                    <td>
	                        10%
	                    </td>
	                    <td>
	                        <?= $header['team_work']; ?>
	                    </td>
	                    <td>
	                        <?= $header['team_work_result']; ?>
	                    </td>
	                    <td><small>Does not consider the effect of personal work on others.</small></td>
	                    <td><small>Considers the effect of personal work on others</small></td>
	                    <td><small>Considers the effect of personal work on others. Willing to stretch him/herself to achieve team goals.</small></td>
	                    <td><small>Considers the effect of personal work on others. Willing to stretch him/herself to achieve team goals.Able to mediate differences in team.</small> </td>
	                </tr>
	                <tr id="tr_7">
	                    <td>Potential</td>
	                    <td>
	                        10%
	                    </td>
	                    <td>
	                        <?= $header['potential']; ?>
	                    </td>
	                    <td>
	                        <?= $header['potential_result']; ?>
	                    </td>
	                    <td><small>Lacks capacity to learn. Lacks capacity to take new responsibilities.</small></td>
	                    <td><small>Shows capacity to learn. Shows capacity to take new responsibilities.</small></td>
	                    <td><small>Shows strong capacity to learn. Shows strong capacity to take new responsibilities.</small></td>
	                    <td><small>Shows very strong capacity to learn. Shows very strong capacity to take new and challenging responsibilities.Excel in constrained situations.</small></td>
	                </tr>
	                <tr id="tr_8">
	                    <td>Initiative</td>
	                    <td>
	                        10%
	                    </td>
	                    <td>
	                        <?= $header['initiative']; ?>
	                    </td>
	                    <td>
	                        <?= $header['initiative_result']; ?>
	                    </td>
	                    <td><small>Waits for instructions to do the job. Prefers to stay in the comfort zone/status quo rather than initiate the change.</small></td>
	                    <td><small>Does the job as instructed. Initiates change occasionally.</small></td>
	                    <td><small>Does the job more than instructed. Frequently initiates changes that create a positive impact in the current situation.</small></td>
	                    <td><small>Actively initiate ideas that add value to the current situation and with innovation.</small></td>
	                </tr>
	                <tr id="tr_9">
	                    <td>Leadership</td>
	                    <td>
	                        10%
	                    </td>
	                    <td>
	                        <?= $header['leadership']; ?>
	                    </td>
	                    <td>
	                        <?= $header['leadership_result']; ?>
	                    </td>
	                    <td><small>Unable to Lead people</small></td>
	                    <td><small>Able to manage people to achieve work goals.</small></td>
	                    <td><small>Able to manage people to achieve work goals. Sets clear directions for others.</small></td>
	                    <td><small>Able to lead a team to achieve excellent team results. Visionary</small></td>
	                </tr> 
	            </tbody>
	            <tfoot>
	                <tr>
	                    <td colspan="3" class="text-secondary">
	                        <b>Total Qualitative Assesment Score</b>
	                    </td>
	                    <td class="lead-text">
	                        <b><?= $header['sub_total_qualitative']; ?></b>
	                    </td>
	                </tr>
	            </tfoot>
	        </table>
	        
	        <br>

	        <table class="table table-striped">
	        	<thead>
	        		<tr>
						<td colspan="4" align="left">
							<b><font size=4>IV. TOTAL</font></b>
						</td>
					</tr>
	        	</thead>
	        	<tbody style="font-size: 13px">
		        	<tr>
		        		<td>Total KPI Score</td>
		        		<td>85%</td>
		        		<td><?=$header['sub_total_kpi']?></td>
		        		<td><?=$header['grand_total_kpi']?></td>
		        	</tr>
		        	<tr>
		        		<td>Total Qualitative Asessment Score</td>
		        		<td>15%</td>
		        		<td><?=$header['sub_total_qualitative']?></td>
		        		<td><?=$header['grand_total_qualitative']?></td>
		        	</tr>
		        	<tr>
		        		<td>Pre Final Score</td>
		        		<td></td>
		        		<td></td>
		        		<td><?=$header['pre_final_score']?></td>
		        	</tr>
		        	<tr>
		        		<td><b>Final Score</b></td>
		        		<td></td>
		        		<td></td>
		        		<td><b><?=$header['final_score']?></b></td>
		        	</tr>
		        	<tr>
		        		<td><b>Grade</b></td>
		        		<td></td>
		        		<td></td>
		        		<td>
		        			<b>
		        			<?php 
		        				$score = $header['final_score'];
			        			if ($score <= 5.5) {
			        				$grade = 'E';
			        			} elseif ($score >= 5.6 && $score < 6.9) {
			        				$grade = 'D';
			        			} elseif ($score >= 6.9 && $score < 8.1) {
			        				$grade = 'C';
			        			}elseif ($score >= 8.1 && $score < 9.1) {
			        				$grade = 'B';
			        			}elseif ($score >= 9.1 || $score == 10) {
			        				$grade = 'A';
			        			}
		        				echo $grade;
		        			?>
		        			</b>
		        		</td>
		        	</tr>
	        	</tbody>
	        </table>
        </div>

        <div style="page-break-after: always">
        	<br>
	        <div class="row">
	          <table>
	              <tr>
	                  <th><img src="<?= base_url('assets/images/logo_ibs.png'); ?>" style="max-height:90px;"></th>
	              </tr>
	          </table>
	        </div>
	        <br>
	        
	        <div class="col-12">
		        <h5 class="text-center"><b>PERFORMANCE APPRAISAL</b></h5>
		        <h6 class="text-center"><b>Evaluation Period: 1 Jan 2020 - 31 Dec 2020</b></h6>
		        <p class="text-center"><?= $header['request_number'] ?></p>
		         
		    </div>

		    <table class="table table-striped">
	    		<thead>
	        	<tr>
					<td style="border-bottom: 1px solid #000000" colspan=9 align="left" valign=bottom bgcolor="#FFFFFF">
						<b><font size=4>V. DEVELOPMENT PLAN & RECOMENDATION</font></b>
					</td>
				</tr>
				<tr>
					<td>Please use this section to identify development that sustains, improves and builds performance, and enables the employee to contribute to organizational effectiveness.  This section should be used to identify which area that needs to be improved and detail development activities, and should be completed by the supervisor in collaboration with the employee. </td>
				</tr>
				</thead>
				<tbody style="font-size: 13px;">
					<tr>
						<td>AREA FOR IMPROVEMENT (Competency, Technical Skill, Soft Skill, Leadership, etc)</td>
					</tr>
					<tr>
						<td><textarea rows="5" class="form-control"><?=$header['area_improvement']?></textarea></td>
					</tr>
					<tr>
						<td>DEVELOPMENT PLAN (Training, On The Job Training)</td>
					</tr>
					<tr>
						<td><textarea rows="5" class="form-control"><?=$header['development_plan']?></textarea></td>
					</tr>
				</tbody>
			</table>
			<br><br>

			<table class="table table-striped">
	    		<thead>
		        	<tr>
						<td style="border-bottom: 1px solid #000000" colspan=9 align="left" valign=bottom bgcolor="#FFFFFF">
							<b><font size=4>VI. COMMENTS & ACKNOWLEDGEMENT</font></b>
						</td>
					</tr>
					<tr>
						<td>The employee and the superior may add any relevant comments before signing the performance evaluation. By signing the evaluation the employee indicates that he/she has participated in a performance appraisal meeting; the signature does not indicate agreement or disagreement. If there is disagreement with the superior’s evaluation of an employee’s performance, the employee may explain that disagreement in the comments section.</td>
					</tr>
				</thead>
				<tbody style="font-size: 13px;">
					<tr>
						<td>Employee</td>
					</tr>
					
					<tr>
						<td><textarea rows="3" class="form-control"><?=$header['comment_employee']?></textarea></td>
					</tr>
					<tr>
						<td>Direct Superior/ Manager/Division Head (1)</td>
					</tr>
					<tr>
						<td><textarea rows="3" class="form-control"><?=$header['comment_head_1']?></textarea></td>
					</tr>
					<tr>
						<td>Direct Superior/ Manager/Division Head (2)</td>
					</tr>
					<tr>
						<td><textarea rows="1" class="form-control"><?=$header['comment_head_2']?></textarea></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div style="page-break-after: always">
        	<br>

			<div class="row">
	          <table>
	              <tr>
	                  <th><img src="<?= base_url('assets/images/logo_ibs.png'); ?>" style="max-height:90px;"></th>
	              </tr>
	          </table>
	        </div>
	        <br>
	        
	        <div class="col-12">
		        <h5 class="text-center"><b>PERFORMANCE PLAN</b></h5>
		        <h6 class="text-center"><b>Evaluation Period: 1 Jan 2020 - 31 Dec 2020</b></h6>
		        <p class="text-center"><?= $header['request_number'] ?></p>
		    </div>

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
                        <th colspan="2" class="text-secondary text-left">
                            Financial Perspective
                        </th>
                        <th colspan="7"></th>
                    </tr>
                </thead>
                <tbody id="financial_perspective" style="font-size: 13px;">
                    <?php $no = 1;
                    foreach ($additional as $key) { 
                    if ($key['plan_perspective'] == 'financial_perspective') { ?>
                        <tr id='plan_row-<?= $key['id']; ?>'>
                            <td>
                                <?=$no;?>
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
                        <th colspan="2" class="text-secondary text-left">
                            Customer Perspective
                        </th>
                        <th colspan="7"></th>
                    </tr>
                </thead>
                <tbody id="cust_perspective" style="font-size: 13px;">
                    <?php $no = 1;
                    foreach ($additional as $key) { 
                    if ($key['plan_perspective'] == 'cust_perspective') { ?>
                        <tr id='plan_row-<?= $key['id']; ?>'>
                            <td>
                                <?=$no;?>
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
                        <th colspan="2" class="text-secondary text-left">
                            Internal Process
                        </th>
                        <th colspan="7"></th>
                    </tr>
                </thead>
                <tbody id="intern_perspective" style="font-size: 13px;">
                    <?php $no = 1;
                    foreach ($additional as $key) { 
                    if ($key['plan_perspective'] == 'intern_perspective') { ?>
                        <tr id='plan_row-<?= $key['id']; ?>'>
                            <td>
                                <?=$no;?>
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
                        <th colspan="2" class="text-secondary text-left">
                            Learning & Growth
                        </th>
                        <th colspan="7"></th>
                    </tr>
                </thead>
                <tbody id="learn_perspective" style="font-size: 13px;">
                    <?php $no = 1;
                    foreach ($additional as $key) { 
                    if ($key['plan_perspective'] == 'learn_perspective') { ?>
                        <tr id='plan_row-<?= $key['id']; ?>'>
                            <td>
                                <?=$no;?>
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
                        <td colspan="2"></td>
                        <td class="text-secondary">
                            <b>Total Weight(%)</b>
                        </td>
                        <td colspan="6" class="text-left">
                            <input type="text" disabled size="5" value="<?php echo ($header['plan_total_weight'] == 0) ? 0 : $header['plan_total_weight']; ?>" name="plan_total_weight" id="plan_total_weight">
                        </td>
                    </tr>
                </tfoot>
           </table>
		</div>

		<div style="page-break-inside: avoid">
        	<br>

			<div class="row">
	          <table>
	              <tr>
	                  <th><img src="<?= base_url('assets/images/logo_ibs.png'); ?>" style="max-height:90px;"></th>
	              </tr>
	          </table>
	        </div>
	        <br>
	        
	        <div class="col-12">
		        <h5 class="text-center"><b>APPROVAL SUMMARY</h5>
		        <h6 class="text-center"><b>Evaluation Period: 1 Jan 2020 - 31 Dec 2020</b></h6>
		        <p class="text-center"><?= $header['request_number'] ?></p>
		    </div>
		    <br><br>
		    <table class="table table-borderless">
		    	<tr>
		    		<td><b>Prepared by employee</b></td>
		    		<td><b>Reviewed & approved by (Line Manager)</b></td>
		    		<td><b>Approved by (Indirect Superior)</b></td>
		    	</tr>
		    	<tr>
		    		<td style="height: 120px;font-size: 13px;"><br><br><center><i>Approval is generated by system. <br>No signature are required.</center></i></td>
		    		<td style="height: 120px;font-size: 13px;"><br><br><center><i>Approval is generated by system. <br>No signature are required.</center></i></td>
		    		<td style="height: 120px;font-size: 13px;"><br><br><center><i>Approval is generated by system. <br>No signature are required.</center></i></td>
		    	</tr>
		    	<tr>
		    		<td><b>Name:</b> <?=$header['created_by']?> <br><b>Date:</b> <?= str_replace('.000', '', $header['created_at'])?> </td>

			    	<?php foreach ($approval as $key => $value) { ?>
			    	
				    	<?php if ($count_approval == 2) { ?>
			    		
					    	<?php if ($value['approval_priority'] == 1): ?>
				    		<td><b>Name:</b> <?=$value['approval_email']?> <br> <b>Date:</b> <?= str_replace('.000', '', $value['updated_at'])?> </td>
					    	<?php endif ?>

					    	<?php if ($value['approval_priority'] == 2): ?>
				    		<td><b>Name:</b> <?=$value['approval_email']?> <br><b>Date:</b> <?= str_replace('.000', '', $value['updated_at'])?> </td>
					    	<?php endif ?>
						
				    	<?php } else { ?>

				    		<?php if ($value['approval_priority'] == 1): ?>
				    		<td><b>Name:</b> <?=$value['approval_email']?> <br><b>Date:</b> <?= str_replace('.000', '', $value['updated_at'])?> </td>
					    	<?php endif ?>

					    	<?php if ($value['approval_priority'] == 1): ?>
				    		<td><b>Name:</b> <?=$value['approval_email']?> <br><b>Date:</b> <?= str_replace('.000', '', $value['updated_at'])?> </td>
					    	<?php endif ?>

						<?php } ?> 

					<?php } ?> 
		    	</tr>
		    	
		    </table>
		</div>

    </main>
</body>

</html>