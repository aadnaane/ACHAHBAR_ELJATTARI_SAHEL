<?php
// This is Dashboard at admin side!!!!!!!!! 
$obj_attend = new Attendence_Manage();
$all_notice = "";
$args['post_type'] = 'notice';
$args['posts_per_page'] = -1;
$args['post_status'] = 'public';
$q = new WP_Query();
$all_notice = $q->query($args);
$notive_array = array();
if (!empty($all_notice)) {
	foreach ($all_notice as $notice) {
		$notice_start_date = get_post_meta($notice->ID, 'start_date', true);
		$notice_end_date = get_post_meta($notice->ID, 'end_date', true);
		$i = 1;

		$notive_array[] = array(
			'title' => $notice->post_title,
			'description' => 'test 123',
			'start' => mysql2date('Y-m-d', $notice_start_date),
			'end' => date('Y-m-d', strtotime($notice_end_date . ' +' . $i . ' days')),
			'color' => '#22BAA0'
		);
	}
}
$holiday_list = mj_smgt_get_all_data('holiday');
if (!empty($holiday_list)) {
	foreach ($holiday_list as $holiday) {
		$notice_start_date = $holiday->date;
		$notice_end_date = $holiday->end_date;
		$i = 1;

		$notive_array[] = array(
			'title' => $holiday->holiday_title,
			'description' => 'test 123',
			'start' => mysql2date('Y-m-d', $notice_start_date),
			'end' => date('Y-m-d', strtotime($notice_end_date . ' +' . $i . ' days')),
			'color' => '#5BC0DE'
		);
	}
}
?>
<script>
   var calendar_laungage ="<?php echo mj_smgt_calander_laungage();?>";
	$ = jQuery.noConflict();
	document.addEventListener('DOMContentLoaded', function() {
		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {

			initialView: 'dayGridMonth',
			locale: calendar_laungage,
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
			},

			events: <?php echo json_encode($notive_array); ?>,

		});
		calendar.render();
	});
</script>




<!--task-event POP up code -->
<div class="popup-bg">
	<div class="overlay-content content_width">
		<div class="modal-content d-modal-style">
			<div class="task_event_list">
			</div>
		</div>
	</div>
</div>
<!-- End task-event POP-UP Code -->
<div class="page-inner">
	<div class="page-title page_title_dashboard">
		<h3 class="dashboard_display_inline_css"><img src="<?php echo get_option('smgt_school_logo') ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option('smgt_school_name'); ?>
		</h3>
		<h3 class="dashboard_with_lms_div"><img src="<?php echo SMS_PLUGIN_URL . "/assets/images/Thumbnail-img.png" ?>" class="wplms_image head_logo" width="40" height="40" />
			<div class="dashboard_display_inline_css">
				<div><label class="dashboard_lable1"><a href=" https://codecanyon.net/item/wplms-learning-management-system-for-wordpress/15485895" target="_blank"><?php echo esc_html_e(esc_attr__('COMBINE WITH', 'school-mgt')); ?></a></label></div>
				<div><label class="dashboard_lable2"><?php echo esc_html_e(esc_attr__('WP Learning Mngmt. System', 'school-mgt')); ?></label></div>
			</div>
		</h3>
	</div>
	<div id="main-wrapper">
		<div class="row">
			<div class="responsivesort col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a class="anchor_css" href="<?php print admin_url() . 'admin.php?page=smgt_student'; ?>">
					<div class="panel info-box panel-white">
						<div class="panel-body student">
							<span class="info-box-icon bg-aqua">
								<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL . "/assets/images/student.png" ?>"></i>
							</span>
							<div class="info-box-stats">
								<?php
								$user_query = new WP_User_Query(array('role' => 'student'));
								$student_count = (int) $user_query->get_total();
								?>
								<span class="info-box-title all_box"><?php echo esc_html(esc_attr__('Students', 'school-mgt')); ?></span>
								<p class="counter"><?php echo $student_count; ?></p>
							</div>

						</div>
					</div>
				</a>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a class="anchor_css" href="<?php print admin_url() . 'admin.php?page=smgt_teacher'; ?>">
					<div class="panel info-box panel-white">
						<div class="panel-body teacher">
							<span class="info-box-icon bg-aqua">
								<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL . "/assets/images/teacher.png" ?>"></i>
							</span>
							<div class="info-box-stats">
								<?php
								$user_query = new WP_User_Query(array('role' => 'teacher'));
								$teacher_count = (int) $user_query->get_total();
								?>
								<span class="info-box-title all_box"><?php echo esc_html(esc_attr__('Teachers', 'school-mgt')); ?></span>
								<p class="counter"><?php echo $teacher_count; ?></p>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a class="anchor_css" href="<?php print admin_url() . 'admin.php?page=smgt_parent'; ?>">
					<div class="panel info-box panel-white">
						<div class="panel-body parent">
							<span class="info-box-icon bg-aqua">
								<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL . "/assets/images/parents.png" ?>"></i>
							</span>
							<div class="info-box-stats">
								<?php
								$user_query = new WP_User_Query(array('role' => 'parent'));
								$parent_count = (int) $user_query->get_total();
								?>
								<span class="info-box-title all_box"><?php echo esc_html(esc_attr__('Parents', 'school-mgt')); ?></span>
								<p class="counter"><?php echo $parent_count; ?></p>
							</div>
						</div>
					</div>
				</a>
			</div>

			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a class="anchor_css" href="<?php print admin_url() . 'admin.php?page=smgt_supportstaff'; ?>">
					<div class="panel info-box panel-white">
						<div class="panel-body staff1">
							<span class="info-box-icon bg-aqua">
								<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL . "/assets/images/support_staff.png" ?>"></i>
							</span>
							<div class="info-box-stats">
								<?php
								$user_query = new WP_User_Query(array('role' => 'supportstaff'));
								$support_count = (int) $user_query->get_total();
								?>
								<span class="info-box-title all_box"><?php echo esc_html(esc_attr__('Support Staffs', 'school-mgt')); ?></span>
								<p class="counter"><?php echo $support_count; ?></p>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a class="anchor_css" href="<?php print admin_url() . 'admin.php?page=smgt_notice'; ?>">
					<div class="panel info-box panel-white">
						<div class="panel-body notices">
							<span class="info-box-icon bg-aqua">
								<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL . "/assets/images/notice_new.png" ?>"></i>
							</span>
							<div class="info-box-stats">
								<?php
								global $wpdb;
								$table_post = $wpdb->prefix . 'posts';
								$total_notice = $wpdb->get_row("SELECT COUNT(*) as  total_notice FROM $table_post where post_type='notice' ");
								?>
								<span class="info-box-title all_box"><?php echo esc_html(esc_attr__('Notice', 'school-mgt')); ?></span>
								<p class="counter"><?php echo $total_notice->total_notice;; ?></p>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a class="anchor_css" href="<?php print admin_url() . 'admin.php?page=smgt_attendence'; ?>">
					<div class="panel info-box panel-white">
						<div class="panel-body attendence">
							<span class="info-box-icon bg-aqua">
								<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL . "/assets/images/attendance.png" ?>"></i>
							</span>
							<div class="info-box-stats">
								<span class="info-box-title all_box"><?php echo esc_html(esc_attr__('Today attendance', 'school-mgt')); ?></span>
								<p class="counter"><?php echo $obj_attend->mj_smgt_today_presents(); ?></p>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a class="anchor_css" href="<?php print admin_url() . 'admin.php?page=smgt_message'; ?>">
					<div class="panel info-box panel-white">
						<div class="panel-body message">
							<span class="info-box-icon bg-aqua">
								<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL . "/assets/images/email_new.png" ?>"></i>
							</span>
							<div class="info-box-stats">
								<span class="info-box-title all_box"><?php echo esc_html(esc_attr__('Messages', 'school-mgt')); ?></span>
								<p class="counter"><?php echo count(mj_smgt_count_inbox_item(get_current_user_id())); ?></p>
							</div>
						</div>
					</div>
				</a>
			</div>


			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<a class="anchor_css" href="<?php print admin_url() . 'admin.php?page=smgt_gnrl_settings'; ?>">
					<div class="panel info-box panel-white">
						<div class="panel-body settings">
							<span class="info-box-icon bg-aqua">
								<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL . "/assets/images/settings_new.png" ?>"></i>
							</span>
							<div class="info-box-stats">
								<span class="info-box-title all_box"><?php echo esc_html(esc_attr__('Settings', 'school-mgt')); ?></span>
								<p class="counter"><?php echo " "; ?></p>
							</div>
						</div>
					</div>
				</a>
			</div>

		</div>

		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12 attandance_padding">
				<div class="p-0 col-md-12">
					<div class="panel panel-body panel-white payment_report ">
						<div class="panel-heading-report">
							<h3 class="panel-title-report"><i class="fa fa-file-text" aria-hidden="true"></i><?php esc_attr_e('Last Week Attendance Report', 'school-mgt'); ?>
							</h3>
						</div>
						<?php
						global $wpdb;
						$table_attendance = $wpdb->prefix . 'attendence';
						$table_class = $wpdb->prefix . 'smgt_class';

						$report_1 = $wpdb->get_results("SELECT  at.class_id,
								SUM(case when `status` ='Present' then 1 else 0 end) as Present,
								SUM(case when `status` ='Absent' then 1 else 0 end) as Absent
								from $table_attendance as at,$table_class as cl where at.attendence_date >  DATE_SUB(NOW(), INTERVAL 1 WEEK) AND at.class_id = cl.class_id AND at.role_name = 'student' GROUP BY at.class_id");
						$chart_array = array();
						$chart_array[] = array(esc_attr__('Class', 'school-mgt'), esc_attr__('Present', 'school-mgt'), esc_attr__('Absent', 'school-mgt'));
						if (!empty($report_1))
							foreach ($report_1 as $result) {
								$class_id = mj_smgt_get_class_name($result->class_id);
								$chart_array[] = array("$class_id", (int)$result->Present, (int)$result->Absent);
							}

						$options = array(
							'title' => esc_attr__('Last Week Attendance Report', 'school-mgt'),
							'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
							'legend' => array(
								'position' => 'right',
								'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
							),

							'hAxis' => array(
								'title' =>  esc_attr__('Class', 'school-mgt'),
								'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
								'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
								'maxAlternation' => 2


							),
							'vAxis' => array(
								'title' =>  esc_attr__('No of Student', 'school-mgt'),
								'minValue' => 0,
								'maxValue' => 4,
								'format' => '#',
								'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
								'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
							),
							'colors' => array('#22BAA0', '#f25656')

						);
						require_once SMS_PLUGIN_DIR . '/lib/chart/GoogleCharts.class.php';
						$GoogleCharts = new GoogleCharts;
						if (!empty($report_1)) {
							$chart = $GoogleCharts->load('column', 'chart_div')->get($chart_array, $options);
						}
						if (isset($report_1) && count($report_1) > 0) {

						?>
							<div id="chart_div"></div>

							<!-- Javascript -->
							<script type="text/javascript" src="https://www.google.com/jsapi"></script>
							<script type="text/javascript">
								<?php echo $chart; ?>
							</script>
						<?php
						}
						if (isset($report_1) && empty($report_1)) { ?>
							<div class="clear col-md-12 error_msg"><?php esc_attr_e("No data available.", 'school-mgt'); ?></div>
						<?php } ?>

					</div>
				</div>

				<div class="p-0 col-md-12">
					<div class="panel panel-body panel-white month_attandance">
						<div class="panel-heading-report">
							<h3 class="panel-title-report"><i class="fa fa-file-text" aria-hidden="true"></i><?php esc_attr_e('Last Month Attendance Report', 'school-mgt'); ?>
							</h3>
						</div>
						<?php
						global $wpdb;

						$table_attendance = $wpdb->prefix . 'attendence';
						$table_class = $wpdb->prefix . 'smgt_class';
						$report_2 = $wpdb->get_results("SELECT  at.class_id,
							SUM(case when `status` ='Present' then 1 else 0 end) as Present,
							SUM(case when `status` ='Absent' then 1 else 0 end) as Absent
							from $table_attendance as at,$table_class as cl where at.attendence_date >  DATE_SUB(NOW(), INTERVAL 1 MONTH) AND at.class_id = cl.class_id AND at.role_name = 'student' GROUP BY at.class_id");
						$chart_array = array();
						$chart_array[] = array(esc_attr__('Class', 'school-mgt'), esc_attr__('Present', 'school-mgt'), esc_attr__('Absent', 'school-mgt'));
						if (!empty($report_2))
							foreach ($report_2 as $result) {

								$class_id = mj_smgt_get_class_name($result->class_id);
								$chart_array[] = array("$class_id", (int)$result->Present, (int)$result->Absent);
							}
						$options = array(
							'title' => esc_attr__('Last Month Attendance Report', 'school-mgt'),
							'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
							'legend' => array(
								'position' => 'right',
								'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
							),

							'hAxis' => array(
								'title' =>  esc_attr__('Class', 'school-mgt'),
								'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
								'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
								'maxAlternation' => 2


							),
							'vAxis' => array(
								'title' =>  esc_attr__('No of Student', 'school-mgt'),
								'minValue' => 0,
								'maxValue' => 4,
								'format' => '#',
								'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
								'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
							),
							'colors' => array('#22BAA0', '#f25656')
						);
						require_once SMS_PLUGIN_DIR . '/lib/chart/GoogleCharts.class.php';
						$GoogleCharts = new GoogleCharts;
						if (!empty($report_2)) {
							$chart = $GoogleCharts->load('column', 'chart_div_last_month')->get($chart_array, $options);
						}
						if (isset($report_2) && count($report_2) > 0) {

						?>
							<div id="chart_div_last_month"></div>

							<!-- Javascript -->
							<script type="text/javascript" src="https://www.google.com/jsapi"></script>
							<script type="text/javascript">
								<?php echo $chart; ?>
							</script>
						<?php
						}
						if (isset($report_2) && empty($report_2)) { ?>
							<div class="clear col-md-12 error_msg"><?php esc_attr_e("No data available.", 'school-mgt'); ?></div>
						<?php } ?>

					</div>
				</div>
				<div class="p-0 col-md-12">
					<div class="panel panel-body panel-white result_report student_report">
						<div class="panel-heading-report">
							<h3 class="panel-title-report"><i class="fa fa-graduation-cap" aria-hidden="true"></i>
								<?php esc_attr_e('Student Fail Report', 'school-mgt'); ?>
							</h3>
						</div>
						<?php
						$chart_array = array();
						$chart_array[] = array(esc_attr__('Class', 'school-mgt'), esc_attr__('No. of Student Fail', 'school-mgt'));

						global $wpdb;
						$table_marks = $wpdb->prefix . 'marks';
						$table_users = $wpdb->prefix . 'users';

						$report_3 = $wpdb->get_results("SELECT * , count( student_id ) as count
									FROM $table_marks as m, $table_users as u
									WHERE m.marks <40
									AND m.student_id = u.id
									GROUP BY subject_id");

						if (!empty($report_3))
							foreach ($report_3 as $result) {

								$subject = mj_smgt_get_single_subject_name($result->subject_id);
								$chart_array[] = array("$subject", (int)$result->count);
							}
						$options = array(
							'title' => esc_attr__('Exam Failed Report', 'school-mgt'),
							'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
							'legend' => array(
								'position' => 'right',
								'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
							),

							'hAxis' => array(
								'title' =>  esc_attr__('Subject', 'school-mgt'),
								'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
								'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
								'maxAlternation' => 2
							),
							'vAxis' => array(
								'title' =>  esc_attr__('No of Student', 'school-mgt'),
								'minValue' => 0,
								'maxValue' => 4,
								'format' => '#',
								'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
								'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
							),
							'colors' => array('#22BAA0')
						);

						require_once SMS_PLUGIN_DIR . '/lib/chart/GoogleCharts.class.php';
						$GoogleCharts = new GoogleCharts;
						if (!empty($report_3)) {
							$chart = $GoogleCharts->load('column', 'chart_div_fail_report')->get($chart_array, $options);
						}
						if (isset($report_3) && count($report_3) > 0) {

						?>
							<div id="chart_div_fail_report"></div>

							<!-- Javascript -->
							<script type="text/javascript" src="https://www.google.com/jsapi"></script>
							<script type="text/javascript">
								<?php echo $chart; ?>
							</script>
						<?php
						}
						if (isset($report_3) && empty($report_3)) { ?>
							<div class="clear col-md-12 error_msg"><?php esc_attr_e("No data available.", 'school-mgt'); ?></div>
						<?php } ?>

					</div>
				</div>

				<div class="p-0 col-md-12">
					<div class="panel panel-body panel-white result_report">
						<div class="panel-heading-report">
							<h3 class="panel-title-report"><i class="fa fa-shopping-cart" aria-hidden="true"></i><?php esc_attr_e('Fees Payment', 'school-mgt'); ?>
							</h3>
						</div>
						<?php
						$month = array(
							'1' => esc_attr__('January', 'school-mgt'), '2' => esc_attr__('February', 'school-mgt'), '3' => esc_attr__('March', 'school-mgt'), '4' => esc_attr__('April', 'school-mgt'), '5' => esc_attr__('May', 'school-mgt'), '6' => esc_attr__('June', 'school-mgt'), '7' => esc_attr__('July', 'school-mgt'), '8' => esc_attr__('August', 'school-mgt'),
							'9' => esc_attr__('September', 'school-mgt'), '10' => esc_attr__('October', 'school-mgt'), '11' => esc_attr__('November', 'school-mgt'), '12' => esc_attr__('December', 'school-mgt'),
						);
						$year = isset($_POST['year']) ? $_POST['year'] : date('Y');

						global $wpdb;
						$table_smgt_fees_payment = $wpdb->prefix . "smgt_fee_payment_history";
						$income = "SELECT EXTRACT(MONTH FROM paid_by_date) as date,sum(amount) as count FROM " . $table_smgt_fees_payment . " WHERE YEAR(paid_by_date) =" . $year . " group by month(paid_by_date) ORDER BY paid_by_date ASC";
						$income_result = $wpdb->get_results($income);
						$month_array = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
						$data_array = array();
						foreach ($month_array as $m) {
							$data_array[$m] = 0;
							foreach ($income_result as $a) {
								if ($a->date == $m) {
									$data_array[$m] = $data_array[$m] + $a->count;
								}
							}

							if ($data_array[$m] == 0) {
								unset($data_array[$m]);
							}
						}

						$chart_array = array();
						$currency = mj_smgt_get_currency_symbol();
						$chart_array[] = array(esc_attr__('Month', 'school-mgt'), esc_attr__('Payment', 'school-mgt'));
						$currency = mj_smgt_get_currency_symbol();
						foreach ($data_array as $key => $value) {
							foreach ($month as $key1 => $value1) {
								if ($key1 == $key) {
									$chart_array[] = array(esc_attr__($value1, 'school-mgt'), $value);
								}
							}
						}
						$options = array(
							'title' => esc_attr__('Payment by month', 'school-mgt'),
							'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
							'legend' => array(
								'position' => 'right',
								'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
							),

							'hAxis' => array(
								'title' => esc_attr__('Month', 'school-mgt'),
								'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
								'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
								'maxAlternation' => 2

							),
							'vAxis' => array(
								'title' => esc_attr__('Payment', 'school-mgt'),
								'minValue' => 0,
								'maxValue' => 5,
								'format' =>  html_entity_decode($currency),
								'titleTextStyle' => array('color' => '#4e5e6a', 'fontSize' => 16, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
								'textStyle' => array('color' => '#4e5e6a', 'fontSize' => 13, 'bold' => false, 'italic' => false, 'fontName' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
							),
							'colors' => array('#22BAA0')
						);
						require_once SMS_PLUGIN_DIR . '/lib/chart/GoogleCharts.class.php';
						$GoogleCharts = new GoogleCharts;
						
						if (!empty($income_result)) {
							$chart = $GoogleCharts->load('column', 'chart_div_payment_report')->get($chart_array, $options);
						}
						if (isset($income_result) && count($income_result) > 0) {
						?>
							<div id="chart_div_payment_report"></div>

							<!-- Javascript -->
							<script type="text/javascript" src="https://www.google.com/jsapi"></script>
							<script type="text/javascript">
								<?php echo $chart; ?>
							</script>
						<?php
						}
						if (isset($income_result) && empty($income_result)) { ?>
							<div class="clear col-md-12 error_msg"><?php esc_attr_e("No data available.", 'school-mgt'); ?></div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 data_right">


				<div class="panel panel-white exam list_en1">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-book" aria-hidden="true"></i> <?php esc_attr_e('Exam List', 'school-mgt'); ?></h3>
						<ul class="nav navbar-right panel_toolbox float-end">
							<li class="margin_dasboard"><a href="<?php echo admin_url() . 'admin.php?page=smgt_exam'; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
							</li>
						</ul>
					</div>
					<div class="panel-body">
						<div class="events">
							<?php
							global $wpdb;
							$smgt_exam = $wpdb->prefix . 'exam';

							$result = $wpdb->get_results("SELECT * FROM $smgt_exam ORDER BY exam_id DESC limit 3");

							if (!empty($result)) {
								foreach ($result as $retrieved_data) {
							?>
									<div class="calendar-event view-complaint">
										<p class="cursor_effect Bold show_task_event" id="<?php echo $retrieved_data->exam_id; ?>" model="Exam Details"> <?php esc_attr_e('Exam Title : ', 'school-mgt'); ?>
											<?php echo 	$retrieved_data->exam_name;  ?>
										</p>
										<p class="remainder_date"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_start_date); ?> | <?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_end_date); ?></p>
										<p class="remainder_title_pr_new">
											<?php
											$strlength = strlen($retrieved_data->exam_comment);
											if ($strlength > 90) {
												echo substr($retrieved_data->exam_comment, 0, 90) . '...';
											} else {
												echo $retrieved_data->exam_comment;
											}
											?>
										</p>
									</div>
								<?php
								}
							} else {
								?>
								<div class="eror_msg">
									<?php
									esc_attr_e("No Upcoming Exam", 'school-mgt');

									?>
								</div>
							<?php
							}
							?>
						</div>
					</div>
				</div>

				<div class="panel panel-white notification list_en2">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-bell" aria-hidden="true"></i> <?php esc_attr_e('Notification', 'school-mgt'); ?></h3>
						<ul class="nav navbar-right panel_toolbox float-end">
							<li class="margin_dasboard"><a href="<?php echo admin_url() . 'admin.php?page=smgt_notification'; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
							</li>
						</ul>
					</div>
					<div class="panel-body">
						<div class="events">
							<?php
							global $wpdb;
							$smgt_notification = $wpdb->prefix . 'smgt_notification';

							$result = $wpdb->get_results("SELECT * FROM $smgt_notification ORDER BY notification_id DESC limit 3");

							if (!empty($result)) {
								foreach ($result as $retrieved_data) {
							?>
									<div class="calendar-event view-complaint">
										<p class="remainder_title_pr Bold show_task_event" id="<?php echo $retrieved_data->notification_id; ?>" model="Notification Details"> <?php esc_attr_e(' Notification Title :', 'school-mgt'); ?>
											<?php echo 	$retrieved_data->title;  ?>
										</p>
										<p class="">
											<?php esc_attr_e('Notification Message :', 'school-mgt'); ?>
											<?php
											$strlength = strlen($retrieved_data->message);
											if ($strlength > 90) {
												echo substr($retrieved_data->message, 0, 90) . '...';
											} else {
												echo $retrieved_data->message;
											}
											?></p>
									</div>
								<?php
								}
							} else {
								?>
								<div class="eror_msg">
									<?php
									esc_attr_e("No Upcoming Notification", 'school-mgt');

									?>
								</div>
							<?php
							}
							?>
						</div>
					</div>
				</div>
				<div class="panel panel-white nt list_en2">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php esc_attr_e('Notice board', 'school-mgt'); ?></h3>
						<ul class="nav navbar-right panel_toolbox float-end">
							<li class="margin_dasboard"><a href="<?php echo admin_url() . 'admin.php?page=smgt_notice'; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
							</li>
						</ul>
					</div>
					<div class="panel-body">
						<div class="events">
							<?php
							$args['post_type'] = 'notice';
							$args['posts_per_page'] = 3;
							$args['post_status'] = 'public';
							$q = new WP_Query();
							$retrieve_class = $q->query($args);

							$format = get_option('date_format');
							if (!empty($retrieve_class)) {
								foreach ($retrieve_class as $retrieved_data) {
							?>
									<div class="calendar-event">
										<p class="remainder_title Bold show_task_event" id="<?php echo $retrieved_data->ID; ?>" model="Noticeboard Details">
											<?php echo 	$retrieved_data->post_title;  ?>
										</p>
										<p class="remainder_date"><?php echo mj_smgt_getdate_in_input_box(get_post_meta($retrieved_data->ID, 'start_date', true)); ?> | <?php echo mj_smgt_getdate_in_input_box(get_post_meta($retrieved_data->ID, 'end_date', true)); ?></p>
										<p class="remainder_title_pr_new"><?php
																			$strlength = strlen($retrieved_data->post_content);
																			if ($strlength > 90) {
																				echo substr($retrieved_data->post_content, 0, 90) . '...';
																			} else {
																				echo $retrieved_data->post_content;
																			}
																			?>
										</p>
									</div>
								<?php
								}
							} else {
								?>
								<div class="eror_msg">
									<?php
									esc_attr_e("No Upcoming Notice", 'school-mgt');
									?>
								</div>
							<?php
							}
							?>
						</div>
					</div>
				</div>
				<div class="panel panel-white event list_en">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-calendar" aria-hidden="true"></i> <?php esc_attr_e('Holiday List', 'school-mgt'); ?></h3>
						<ul class="nav navbar-right panel_toolbox float-end">
							<li class="margin_dasboard"><a href="<?php echo admin_url() . 'admin.php?page=smgt_holiday'; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
							</li>
						</ul>
					</div>
					<div class="panel-body">
						<div class="events">
							<?php
							global $wpdb;
							$smgt_holiday = $wpdb->prefix . 'holiday';

							$result_holidays = $wpdb->get_results("SELECT * FROM $smgt_holiday ORDER BY holiday_id DESC limit 3");

							if (!empty($result_holidays)) {
								foreach ($result_holidays as $retrieved_data) {
							?>
									<div class="calendar-event">
										<p class="cursor_effect Bold show_task_event" id="<?php echo $retrieved_data->holiday_id; ?>" model="holiday Details">
											<?php echo 	$retrieved_data->holiday_title;  ?>
										</p>
										<p class="remainder_date"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->date); ?> | <?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date); ?></p>
										<p class="remainder_title_pr_new"><?php
																			$strlength = strlen($retrieved_data->description);
																			if ($strlength > 90) {
																				echo substr($retrieved_data->description, 0, 90) . '...';
																			} else {
																				echo $retrieved_data->description;
																			}
																			?></p>
									</div>
								<?php
								}
							} else {
								?>
								<div class="eror_msg">
									<?php
									esc_attr_e("No Upcoming Holiday", 'school-mgt');
									?>
								</div>
							<?php
							}
							?>
						</div>
					</div>
				</div>
				<div class="panel panel-white class list_en1">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-building" aria-hidden="true"> </i><?php esc_attr_e(' Class', 'school-mgt'); ?></h3>
						<ul class="nav navbar-right panel_toolbox float-end">
							<li class="margin_dasboard"><a href="<?php echo admin_url() . 'admin.php?page=smgt_class'; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
							</li>
						</ul>
					</div>
					<div class="panel-body">
						<table class="table table-borderless">
							<?php
							global $wpdb;
							$smgt_class = $wpdb->prefix . 'smgt_class';

							$result = $wpdb->get_results("SELECT * FROM $smgt_class ORDER BY class_id DESC limit 3");

							if (!empty($result)) { ?>
								<thead class="responsive_font">
									<tr>
										<th scope="col" class="d-th-style" <?php esc_attr_e('Class Name', 'school-mgt'); ?></th>
										<th scope="col" class="d-th-style" <?php esc_attr_e('Numeric Class Name', 'school-mgt'); ?></th>
										<th scope="col" class="d-th-style" <?php esc_attr_e('Capacity', 'school-mgt'); ?></th>
									</tr>
								</thead>

								<tbody>
									<?php
									foreach ($result as $retrieved_data) {
									?>
										<tr>
											<td class="unit"><?php echo 	$retrieved_data->class_name;  ?></td>
											<td class="unit"><?php echo 	$retrieved_data->class_num_name;  ?> </td>
											<td class="unit"><span class="btn btn-success btn-xs"><?php echo 	$retrieved_data->class_capacity;  ?> </span></td>
										</tr>
									<?php
									}	?>
								</tbody>
							<?php
							} else {
							?>
								<div class="eror_msg">
									<?php
									esc_attr_e("No Upcoming Class", 'school-mgt');
									?>
								</div>
							<?php
							}
							?>

						</table>
					</div>

				</div>
				<div class="panel panel-white report_height cln">
					<div class="panel-body">
						<div id="calendar"></div><br>
						<mark class="d-cal-style">&nbsp;&nbsp;&nbsp;</mark><span> &nbsp;<?php esc_attr_e('Notice', 'school-mgt') ?><span><br><br>
								<mark class="d-cal-style2">&nbsp;&nbsp;&nbsp;</mark><span> &nbsp;<?php esc_attr_e('Holiday', 'school-mgt') ?><span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>