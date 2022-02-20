<?php 
// This is Setting page of school management plugin
require_once SMS_PLUGIN_DIR. '/includes/class-attendence-manage.php';
require_once SMS_PLUGIN_DIR. '/smgt-function.php';
require_once SMS_PLUGIN_DIR. '/includes/class-marks-manage.php';
require_once SMS_PLUGIN_DIR. '/school-management-class.php';
require_once SMS_PLUGIN_DIR. '/includes/class-routine.php';
require_once SMS_PLUGIN_DIR. '/includes/class-dashboard.php';
require_once SMS_PLUGIN_DIR. '/includes/class-payment.php';
require_once SMS_PLUGIN_DIR. '/includes/class-fees.php';
require_once SMS_PLUGIN_DIR. '/includes/class-homework.php';
require_once SMS_PLUGIN_DIR. '/includes/class-feespayment.php';
require_once SMS_PLUGIN_DIR. '/lib/paypal/paypal_class.php'; 
require_once SMS_PLUGIN_DIR. '/includes/class-library.php';
require_once SMS_PLUGIN_DIR. '/includes/class-teacher.php';
require_once SMS_PLUGIN_DIR. '/includes/class-exam.php';
require_once SMS_PLUGIN_DIR. '/includes/class-admissioin.php';
require_once SMS_PLUGIN_DIR. '/includes/class-hostel.php';
require_once SMS_PLUGIN_DIR. '/includes/class-subject.php';
require_once SMS_PLUGIN_DIR. '/includes/custome_field.php';
require_once SMS_PLUGIN_DIR. '/includes/class_virtual_classroom.php';

function mj_smgt_role_exists( $role ) { 

	if( ! empty( $role ) ) {
		return $GLOBALS['wp_roles']->is_role( $role );
	}
	return false;
}
function mj_smgt_add_role_caps() {
	// gets the author role
	if( mj_smgt_role_exists( 'teacher' ) ) {
		// The 'editor' role exists!
		$role = get_role( 'teacher' );
		$role->add_cap('read');
		$role->add_cap('level_0');
	}
	if( mj_smgt_role_exists( 'student' ) ) {
		// The 'editor' role exists!
		$role = get_role( 'student' );
		$role->add_cap('read');
		$role->add_cap('level_0');
	}
	if( mj_smgt_role_exists( 'parent' ) ) {
		// The 'editor' role exists!
		$role = get_role( 'parent' );
		$role->add_cap('read');
		$role->add_cap('level_0');
	}
	if( !mj_smgt_role_exists( 'supportstaff' ) ) {
		// The 'editor' role exists!
		add_role('supportstaff', esc_attr__( 'Support Staff' ,'school-mgt'),array( 'read' => true, 'level_0' => true ));
	}
	if( !mj_smgt_role_exists( 'student_temp' ) ) {
		// The 'editor' role exists!
		add_role('student_temp', esc_attr__( 'student_temp' ,'school-mgt'),array( 'read' => true, 'level_0' => true ));
	}
	
}
add_action( 'admin_init', 'mj_smgt_add_role_caps');

add_action( 'admin_bar_menu', 'mj_smgt_school_dashboard_link', 999 );

function mj_smgt_school_dashboard_link( $wp_admin_bar ) {
	$args = array(
			'id'    => 'school-dashboard',
			'title' => esc_attr__('School Dashboard','school-mgt'),
			'href'  => home_url().'?dashboard=user',
			'meta'  => array( 'class' => 'smgt-school-dashboard' )
	);
	$wp_admin_bar->add_node( $args );
}

add_action( 'admin_head', 'mj_smgt_admin_css' );

function mj_smgt_admin_css(){  ?>
     <link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/settings/setting_admin.css'; ?>">
<?php
}

add_action('init', 'mj_smgt_amgt_session_manager'); 
function mj_smgt_amgt_session_manager()
{
	if (!session_id()) 
	{
		session_start();		
		if(!isset($_SESSION['cmgt_verify']))
		{			
			$_SESSION['cmgt_verify'] = '';
		}		
	}
	
	session_write_close();
	
}


function mj_smgt_logout(){
 if(isset($_SESSION['cmgt_verify']))
 { unset($_SESSION['cmgt_verify']);}
   
}
add_action('wp_logout','mj_smgt_logout');
add_action('init', 'mj_smgt_setup'); 
function mj_smgt_setup()
{
	$is_cmgt_pluginpage = mj_smgt_is_cmgtpage();
	$is_verify = false;
	if(!isset($_SESSION['cmgt_verify']))
		$_SESSION['cmgt_verify'] = '';
	$server_name = $_SERVER['SERVER_NAME'];
	$is_localserver = mj_smgt_chekserver($server_name);
	
	if($is_localserver)
	{		
		return true;
	}
	if($is_cmgt_pluginpage)
	{	
		if($_SESSION['cmgt_verify'] == ''){
		
			if( get_option('licence_key') && get_option('cmgt_setup_email'))
			{				
				$domain_name = $_SERVER['SERVER_NAME'];
				$licence_key = get_option('licence_key');
				$email = get_option('cmgt_setup_email');
				$result = mj_smgt_check_productkey($domain_name,$licence_key,$email);
				$is_server_running = mj_smgt_check_ourserver();
				if($is_server_running)
					$_SESSION['cmgt_verify'] =$result;
				else
					$_SESSION['cmgt_verify'] = '0';
					$is_verify = mj_smgt_check_verify_or_not($result);
			
			}
		}
	}
	$is_verify = mj_smgt_check_verify_or_not($_SESSION['cmgt_verify']);
	if($is_cmgt_pluginpage)
		if(!$is_verify)
		{
			$_SESSION['cmgt_verify'] = '';
			if($_REQUEST['page'] != 'smgt_setup')
			wp_redirect(admin_url().'admin.php?page=smgt_setup');			
		}	
}

if ( is_admin() )
{
	require_once SMS_PLUGIN_DIR. '/admin/admin.php';
	function mj_smgt_school_install()
	{
		add_role('teacher', esc_attr__( 'Teacher' ,'school-mgt'),array( 'read' => true, 'level_0' => true ));
		add_role('student', esc_attr__( 'Student' ,'school-mgt'),array( 'read' => true, 'level_0' => true ));
		add_role('parent', esc_attr__( 'Parent' ,'school-mgt'),array( 'read' => true, 'level_0' => true ));
		add_role('supportstaff', esc_attr__( 'Support Staff' ,'school-mgt'),array( 'read' => true, 'level_0' => true ));
		
		mj_smgt_install_tables();
		mj_smgt_register_post();
			
	}
	register_activation_hook(SMS_PLUGIN_BASENAME, 'mj_smgt_school_install' );
	function mj_smgt_option(){
		$role_access_right_student = array();
		$role_access_right_student['student'] = [
									"teacher"=>["menu_icone"=>plugins_url('school-management/assets/images/icons/teacher.png'),
									           "app_icone"=>plugins_url('school-management/assets/images/icons/app_icon/teacher.png'),
											   "menu_title"=>'Teacher',
											   "page_link"=>'teacher',
										 	   "own_data" =>isset($_REQUEST['teacher_own_data'])?$_REQUEST['teacher_own_data']:1,
											   "add" =>isset($_REQUEST['teacher_add'])?$_REQUEST['teacher_add']:0,
												"edit"=>isset($_REQUEST['teacher_edit'])?$_REQUEST['teacher_edit']:0,
												"view"=>isset($_REQUEST['teacher_view'])?$_REQUEST['teacher_view']:1,
												"delete"=>isset($_REQUEST['teacher_delete'])?$_REQUEST['teacher_delete']:0
												],
														
								   "student"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/student-icon.png'),
								   'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/student.png'),
											  "menu_title"=>'Student',
											  "page_link"=>'student',
											 "own_data" => isset($_REQUEST['student_own_data'])?$_REQUEST['student_own_data']:1,
											 "add" => isset($_REQUEST['student_add'])?$_REQUEST['student_add']:0,
											 "edit"=>isset($_REQUEST['student_edit'])?$_REQUEST['student_edit']:0,
											 "view"=>isset($_REQUEST['student_view'])?$_REQUEST['student_view']:1,
											 "delete"=>isset($_REQUEST['student_delete'])?$_REQUEST['student_delete']:0
								  ],
											  
									"parent"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/parents.png'),
									'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/parents.png'),
											"menu_title"=>'Parent',
											"page_link"=>'parent',
											 "own_data" => isset($_REQUEST['parent_own_data'])?$_REQUEST['parent_own_data']:1,
											 "add" => isset($_REQUEST['parent_add'])?$_REQUEST['parent_add']:0,
											"edit"=>isset($_REQUEST['parent_edit'])?$_REQUEST['parent_edit']:0,
											"view"=>isset($_REQUEST['parent_view'])?$_REQUEST['parent_view']:1,
											"delete"=>isset($_REQUEST['parent_delete'])?$_REQUEST['parent_delete']:0
								  ],
											  
									  "subject"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/subject.png'),
									  'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/subject.png'),
												"menu_title"=>'Subject',
												"page_link"=>'subject',
												"own_data" => isset($_REQUEST['subject_own_data'])?$_REQUEST['subject_own_data']:1,
												 "add" => isset($_REQUEST['subject_add'])?$_REQUEST['subject_add']:0,
												 "edit"=>isset($_REQUEST['subject_edit'])?$_REQUEST['subject_edit']:0,
												"view"=>isset($_REQUEST['subject_view'])?$_REQUEST['subject_view']:1,
												"delete"=>isset($_REQUEST['subject_delete'])?$_REQUEST['subject_delete']:0
									  ],
									  
									  "schedule"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/class-route.png'),
									   'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/class-route.png'),
												 "menu_title"=>'Class Routine',
												 "page_link"=>'schedule',
												 "own_data" => isset($_REQUEST['schedule_own_data'])?$_REQUEST['schedule_own_data']:1,
												 "add" => isset($_REQUEST['schedule_add'])?$_REQUEST['schedule_add']:0,
												"edit"=>isset($_REQUEST['schedule_edit'])?$_REQUEST['schedule_edit']:0,
												"view"=>isset($_REQUEST['schedule_view'])?$_REQUEST['schedule_view']:1,
												"delete"=>isset($_REQUEST['schedule_delete'])?$_REQUEST['schedule_delete']:0
									  ],
									  "virtual_classroom"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/virtual_classroom.png'),	
									  'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/virtual_class.png'),
												 "menu_title"=>'virtual_classroom',
												 "page_link"=>'virtual_classroom',
												 "own_data" => isset($_REQUEST['virtual_classroom_own_data'])?$_REQUEST['virtual_classroom_own_data']:1,
												 "add" => isset($_REQUEST['virtual_classroom_add'])?$_REQUEST['virtual_classroom_add']:0,
												"edit"=>isset($_REQUEST['virtual_classroom_edit'])?$_REQUEST['virtual_classroom_edit']:0,
												"view"=>isset($_REQUEST['virtual_classroom_view'])?$_REQUEST['virtual_classroom_view']:1,
												"delete"=>isset($_REQUEST['virtual_classroom_delete'])?$_REQUEST['virtual_classroom_delete']:0
									  ],

									  "attendance"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/attandance.png'),
									   'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/attandance.png'),
												   "menu_title"=>'Attendance',
												   "page_link"=>'attendance',
												 "own_data" => isset($_REQUEST['attendance_own_data'])?$_REQUEST['attendance_own_data']:0,
												 "add" => isset($_REQUEST['attendance_add'])?$_REQUEST['attendance_add']:0,
												"edit"=>isset($_REQUEST['attendance_edit'])?$_REQUEST['attendance_edit']:0,
												"view"=>isset($_REQUEST['attendance_view'])?$_REQUEST['attendance_view']:0,
												"delete"=>isset($_REQUEST['attendance_delete'])?$_REQUEST['attendance_delete']:0
									  ],
									  
										"exam"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/exam.png'),
										'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/exam.png'),
												 "menu_title"=>'Exam',
												 "page_link"=>'exam',
												 "own_data" => isset($_REQUEST['exam_own_data'])?$_REQUEST['exam_own_data']:1,
												 "add" => isset($_REQUEST['exam_add'])?$_REQUEST['exam_add']:0,
												"edit"=>isset($_REQUEST['exam_edit'])?$_REQUEST['exam_edit']:0,
												"view"=>isset($_REQUEST['exam_view'])?$_REQUEST['exam_view']:1,
												"delete"=>isset($_REQUEST['exam_delete'])?$_REQUEST['exam_delete']:0
									  ],
									  
									  
										"hostel"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/hostel.png'),
										'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/hostel.png'),
												 "menu_title"=>'Hostel',
												 "page_link"=>'hostel',
												 "own_data" => isset($_REQUEST['hostel_own_data'])?$_REQUEST['hostel_own_data']:0,
												 "add" => isset($_REQUEST['hostel_add'])?$_REQUEST['hostel_add']:0,
												"edit"=>isset($_REQUEST['hostel_edit'])?$_REQUEST['hostel_edit']:0,
												"view"=>isset($_REQUEST['hostel_view'])?$_REQUEST['hostel_view']:1,
												"delete"=>isset($_REQUEST['hostel_delete'])?$_REQUEST['hostel_delete']:0
									  ],
										"homework"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/homework.png'),
										'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/homework.png'),
												 "menu_title"=>'Home Work',
												 "page_link"=>'homework',
												 "own_data" => isset($_REQUEST['homework_own_data'])?$_REQUEST['homework_own_data']:1,
												 "add" => isset($_REQUEST['homework_add'])?$_REQUEST['homework_add']:0,
												"edit"=>isset($_REQUEST['homework_edit'])?$_REQUEST['homework_edit']:0,
												"view"=>isset($_REQUEST['homework_view'])?$_REQUEST['homework_view']:1,
												"delete"=>isset($_REQUEST['homework_delete'])?$_REQUEST['homework_delete']:0
									  ],
										"manage_marks"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/mark-manage.png'),
										'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/mark-manage.png'),
												  "menu_title"=>'Mark Manage',
												  "page_link"=>'manage_marks',
												 "own_data" => isset($_REQUEST['manage_marks_own_data'])?$_REQUEST['manage_marks_own_data']:0,
												 "add" => isset($_REQUEST['manage_marks_add'])?$_REQUEST['manage_marks_add']:0,
												"edit"=>isset($_REQUEST['manage_marks_edit'])?$_REQUEST['manage_marks_edit']:0,
												"view"=>isset($_REQUEST['manage_marks_view'])?$_REQUEST['manage_marks_view']:0,
												"delete"=>isset($_REQUEST['manage_marks_delete'])?$_REQUEST['manage_marks_delete']:0
									  ],
									  
									  "feepayment"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/fee.png'),
									  'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/fee_payment.png'),
												 "menu_title"=>'Fee Payment',
												 "page_link"=>'feepayment',
												 "own_data" => isset($_REQUEST['feepayment_own_data'])?$_REQUEST['feepayment_own_data']:1,
												 "add" => isset($_REQUEST['feepayment_add'])?$_REQUEST['feepayment_add']:0,
												"edit"=>isset($_REQUEST['feepayment_edit'])?$_REQUEST['feepayment_edit']:0,
												"view"=>isset($_REQUEST['feepayment_view'])?$_REQUEST['feepayment_view']:1,
												"delete"=>isset($_REQUEST['feepayment_delete'])?$_REQUEST['feepayment_delete']:0
									  ],
									  
									  "payment"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/payment.png'),
									  'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/payment.png'),
												 "menu_title"=>'Payment',
												 "page_link"=>'payment',
												 "own_data" => isset($_REQUEST['payment_own_data'])?$_REQUEST['payment_own_data']:1,
												 "add" => isset($_REQUEST['payment_add'])?$_REQUEST['payment_add']:0,
												"edit"=>isset($_REQUEST['payment_edit'])?$_REQUEST['payment_edit']:0,
												"view"=>isset($_REQUEST['payment_view'])?$_REQUEST['payment_view']:1,
												"delete"=>isset($_REQUEST['payment_delete'])?$_REQUEST['payment_delete']:0
									  ],
									  "transport"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/transport.png'),
									  'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/transport.png'),
											   "menu_title"=>'Transport',
											   "page_link"=>'transport',
												 "own_data" => isset($_REQUEST['transport_own_data'])?$_REQUEST['transport_own_data']:0,
												 "add" => isset($_REQUEST['transport_add'])?$_REQUEST['transport_add']:0,
												"edit"=>isset($_REQUEST['transport_edit'])?$_REQUEST['transport_edit']:0,
												"view"=>isset($_REQUEST['transport_view'])?$_REQUEST['transport_view']:1,
												"delete"=>isset($_REQUEST['transport_delete'])?$_REQUEST['transport_delete']:0
									  ],
									  "notice"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/notice.png'),
									   'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/notice.png'),
												  "menu_title"=>'Notice Board',
												  "page_link"=>'notice',
												 "own_data" => isset($_REQUEST['notice_own_data'])?$_REQUEST['notice_own_data']:1,
												 "add" => isset($_REQUEST['notice_add'])?$_REQUEST['notice_add']:0,
												"edit"=>isset($_REQUEST['notice_edit'])?$_REQUEST['notice_edit']:0,
												"view"=>isset($_REQUEST['notice_view'])?$_REQUEST['notice_view']:1,
												"delete"=>isset($_REQUEST['notice_delete'])?$_REQUEST['notice_delete']:0
									  ],
									  "message"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/message.png'),
									  'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/message.png'),
												"menu_title"=>'Message',
												"page_link"=>'message',
												 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
												 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
												"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
												"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
												"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
									  ],
									  "holiday"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/holiday.png'),
									   'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/holiday.png'),
												 "menu_title"=>'Holiday',
												 "page_link"=>'holiday',
												 "own_data" => isset($_REQUEST['holiday_own_data'])?$_REQUEST['holiday_own_data']:0,
												 "add" => isset($_REQUEST['holiday_add'])?$_REQUEST['holiday_add']:0,
												"edit"=>isset($_REQUEST['holiday_edit'])?$_REQUEST['holiday_edit']:0,
												"view"=>isset($_REQUEST['holiday_view'])?$_REQUEST['holiday_view']:1,
												"delete"=>isset($_REQUEST['holiday_delete'])?$_REQUEST['holiday_delete']:0
									  ],
									  
									   "library"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/library.png'),
									   'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/library.png'),
											   "menu_title"=>'Library',
											   "page_link"=>'library',
												 "own_data" => isset($_REQUEST['library_own_data'])?$_REQUEST['library_own_data']:1,
												 "add" => isset($_REQUEST['library_add'])?$_REQUEST['library_add']:0,
												"edit"=>isset($_REQUEST['library_edit'])?$_REQUEST['library_edit']:0,
												"view"=>isset($_REQUEST['library_view'])?$_REQUEST['library_view']:1,
												"delete"=>isset($_REQUEST['library_delete'])?$_REQUEST['library_delete']:0
									  ],
									  
									   "account"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/account.png'),
									   'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/account.png'),
												"menu_title"=>'Account',
												"page_link"=>'account',
												 "own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:1,
												 "add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:0,
												"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:0,
												"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
												"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
									  ],
									  
									   "report"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/report.png'),
                                        'app_icone'=>plugins_url( 'school-management/assets/images/icons/app_icon/report.png'),									   
												 "menu_title"=>'Report',
												 "page_link"=>'report',
												 "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
												 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
												"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
												"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:0,
												"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
									  ]
									];
					$role_access_right_teacher = array();
					$role_access_right_teacher['teacher'] = [
									"teacher"=>["menu_icone"=>plugins_url('school-management/assets/images/icons/teacher.png'),
											   "menu_title"=>'Teacher',
											   "page_link"=>'teacher',
											   "own_data" =>isset($_REQUEST['teacher_own_data'])?$_REQUEST['teacher_own_data']:1,
											   "add" =>isset($_REQUEST['teacher_add'])?$_REQUEST['teacher_add']:0,
												"edit"=>isset($_REQUEST['teacher_edit'])?$_REQUEST['teacher_edit']:0,
												"view"=>isset($_REQUEST['teacher_view'])?$_REQUEST['teacher_view']:1,
												"delete"=>isset($_REQUEST['teacher_delete'])?$_REQUEST['teacher_delete']:0
												],
														
								   "student"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/student-icon.png'),
											  "menu_title"=>'Student',
											  "page_link"=>'student',
											 "own_data" => isset($_REQUEST['student_own_data'])?$_REQUEST['student_own_data']:1,
											 "add" => isset($_REQUEST['student_add'])?$_REQUEST['student_add']:0,
											 "edit"=>isset($_REQUEST['student_edit'])?$_REQUEST['student_edit']:0,
											 "view"=>isset($_REQUEST['student_view'])?$_REQUEST['student_view']:1,
											 "delete"=>isset($_REQUEST['student_delete'])?$_REQUEST['student_delete']:0
								  ],
											  
									"parent"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/parents.png'),
											"menu_title"=>'Parent',
											"page_link"=>'parent',
											 "own_data" => isset($_REQUEST['parent_own_data'])?$_REQUEST['parent_own_data']:0,
											 "add" => isset($_REQUEST['parent_add'])?$_REQUEST['parent_add']:0,
											"edit"=>isset($_REQUEST['parent_edit'])?$_REQUEST['parent_edit']:0,
											"view"=>isset($_REQUEST['parent_view'])?$_REQUEST['parent_view']:1,
											"delete"=>isset($_REQUEST['parent_delete'])?$_REQUEST['parent_delete']:0
								  ],
											  
									  "subject"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/subject.png'),
												"menu_title"=>'Subject',
												"page_link"=>'subject',
												"own_data" => isset($_REQUEST['subject_own_data'])?$_REQUEST['subject_own_data']:1,
												 "add" => isset($_REQUEST['subject_add'])?$_REQUEST['subject_add']:1,
												 "edit"=>isset($_REQUEST['subject_edit'])?$_REQUEST['subject_edit']:1,
												"view"=>isset($_REQUEST['subject_view'])?$_REQUEST['subject_view']:1,
												"delete"=>isset($_REQUEST['subject_delete'])?$_REQUEST['subject_delete']:1
									  ],
									  "class"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/class.png'),
												"menu_title"=>'Class',
												"page_link"=>'class',
												"own_data" => isset($_REQUEST['class_own_data'])?$_REQUEST['class_own_data']:1,
												 "add" => isset($_REQUEST['class_add'])?$_REQUEST['class_add']:0,
												 "edit"=>isset($_REQUEST['class_edit'])?$_REQUEST['class_edit']:0,
												"view"=>isset($_REQUEST['class_view'])?$_REQUEST['class_view']:1,
												"delete"=>isset($_REQUEST['class_delete'])?$_REQUEST['class_delete']:0
									  ],
									  
									  "virtual_classroom"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/virtual_classroom.png'),							       
												 "menu_title"=>'virtual_classroom',
												 "page_link"=>'virtual_classroom',
												 "own_data" => isset($_REQUEST['virtual_classroom_own_data'])?$_REQUEST['virtual_classroom_own_data']:0,
												 "add" => isset($_REQUEST['virtual_classroom_add'])?$_REQUEST['virtual_classroom_add']:1,
												"edit"=>isset($_REQUEST['virtual_classroom_edit'])?$_REQUEST['virtual_classroom_edit']:1,
												"view"=>isset($_REQUEST['virtual_classroom_view'])?$_REQUEST['virtual_classroom_view']:1,
												"delete"=>isset($_REQUEST['virtual_classroom_delete'])?$_REQUEST['virtual_classroom_delete']:1
									  ],

									  "schedule"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/class-route.png'),
												 "menu_title"=>'Class Routine',
												 "page_link"=>'schedule',
												 "own_data" => isset($_REQUEST['schedule_own_data'])?$_REQUEST['schedule_own_data']:1,
												 "add" => isset($_REQUEST['schedule_add'])?$_REQUEST['schedule_add']:0,
												"edit"=>isset($_REQUEST['schedule_edit'])?$_REQUEST['schedule_edit']:0,
												"view"=>isset($_REQUEST['schedule_view'])?$_REQUEST['schedule_view']:1,
												"delete"=>isset($_REQUEST['schedule_delete'])?$_REQUEST['schedule_delete']:0
									  ],
									  "attendance"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/attandance.png'),
												   "menu_title"=>'Attendance',
												   "page_link"=>'attendance',
												 "own_data" => isset($_REQUEST['attendance_own_data'])?$_REQUEST['attendance_own_data']:1,
												 "add" => isset($_REQUEST['attendance_add'])?$_REQUEST['attendance_add']:0,
												"edit"=>isset($_REQUEST['attendance_edit'])?$_REQUEST['attendance_edit']:0,
												"view"=>isset($_REQUEST['attendance_view'])?$_REQUEST['attendance_view']:1,
												"delete"=>isset($_REQUEST['attendance_delete'])?$_REQUEST['attendance_delete']:0
									  ],
									  
										"exam"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/exam.png'),
												 "menu_title"=>'Exam',
												 "page_link"=>'exam',
												 "own_data" => isset($_REQUEST['exam_own_data'])?$_REQUEST['exam_own_data']:1,
												 "add" => isset($_REQUEST['exam_add'])?$_REQUEST['exam_add']:1,
												"edit"=>isset($_REQUEST['exam_edit'])?$_REQUEST['exam_edit']:1,
												"view"=>isset($_REQUEST['exam_view'])?$_REQUEST['exam_view']:1,
												"delete"=>isset($_REQUEST['exam_delete'])?$_REQUEST['exam_delete']:1
									  ],
									  
									  
										"hostel"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/hostel.png'),
												 "menu_title"=>'Hostel',
												 "page_link"=>'hostel',
												 "own_data" => isset($_REQUEST['hostel_own_data'])?$_REQUEST['hostel_own_data']:0,
												 "add" => isset($_REQUEST['hostel_add'])?$_REQUEST['hostel_add']:0,
												"edit"=>isset($_REQUEST['hostel_edit'])?$_REQUEST['hostel_edit']:0,
												"view"=>isset($_REQUEST['hostel_view'])?$_REQUEST['hostel_view']:1,
												"delete"=>isset($_REQUEST['hostel_delete'])?$_REQUEST['hostel_delete']:0
									  ],
										"homework"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/homework.png'),
												 "menu_title"=>'Home Work',
												 "page_link"=>'homework',
												 "own_data" => isset($_REQUEST['homework_own_data'])?$_REQUEST['homework_own_data']:1,
												 "add" => isset($_REQUEST['homework_add'])?$_REQUEST['homework_add']:1,
												"edit"=>isset($_REQUEST['homework_edit'])?$_REQUEST['homework_edit']:1,
												"view"=>isset($_REQUEST['homework_view'])?$_REQUEST['homework_view']:1,
												"delete"=>isset($_REQUEST['homework_delete'])?$_REQUEST['homework_delete']:1
									  ],
										"manage_marks"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/mark-manage.png'),
												  "menu_title"=>'Mark Manage',
												  "page_link"=>'manage_marks',
												 "own_data" => isset($_REQUEST['manage_marks_own_data'])?$_REQUEST['manage_marks_own_data']:1,
												 "add" => isset($_REQUEST['manage_marks_add'])?$_REQUEST['manage_marks_add']:1,
												"edit"=>isset($_REQUEST['manage_marks_edit'])?$_REQUEST['manage_marks_edit']:1,
												"view"=>isset($_REQUEST['manage_marks_view'])?$_REQUEST['manage_marks_view']:1,
												"delete"=>isset($_REQUEST['manage_marks_delete'])?$_REQUEST['manage_marks_delete']:0
									  ],
									  
									  "feepayment"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/fee.png'),
												 "menu_title"=>'Fee Payment',
												 "page_link"=>'feepayment',
												 "own_data" => isset($_REQUEST['feepayment_own_data'])?$_REQUEST['feepayment_own_data']:1,
												 "add" => isset($_REQUEST['feepayment_add'])?$_REQUEST['feepayment_add']:1,
												"edit"=>isset($_REQUEST['feepayment_edit'])?$_REQUEST['feepayment_edit']:1,
												"view"=>isset($_REQUEST['feepayment_view'])?$_REQUEST['feepayment_view']:1,
												"delete"=>isset($_REQUEST['feepayment_delete'])?$_REQUEST['feepayment_delete']:0
									  ],
									  
									  "payment"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/payment.png'),
												 "menu_title"=>'Payment',
												 "page_link"=>'payment',
												 "own_data" => isset($_REQUEST['payment_own_data'])?$_REQUEST['payment_own_data']:0,
												 "add" => isset($_REQUEST['payment_add'])?$_REQUEST['payment_add']:0,
												"edit"=>isset($_REQUEST['payment_edit'])?$_REQUEST['payment_edit']:0,
												"view"=>isset($_REQUEST['payment_view'])?$_REQUEST['payment_view']:0,
												"delete"=>isset($_REQUEST['payment_delete'])?$_REQUEST['payment_delete']:0
									  ],
									  "transport"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/transport.png'),
											   "menu_title"=>'Transport',
											   "page_link"=>'transport',
												 "own_data" => isset($_REQUEST['transport_own_data'])?$_REQUEST['transport_own_data']:0,
												 "add" => isset($_REQUEST['transport_add'])?$_REQUEST['transport_add']:0,
												"edit"=>isset($_REQUEST['transport_edit'])?$_REQUEST['transport_edit']:0,
												"view"=>isset($_REQUEST['transport_view'])?$_REQUEST['transport_view']:1,
												"delete"=>isset($_REQUEST['transport_delete'])?$_REQUEST['transport_delete']:0
									  ],
									  "notice"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/notice.png'),
												  "menu_title"=>'Notice Board',
												  "page_link"=>'notice',
												 "own_data" => isset($_REQUEST['notice_own_data'])?$_REQUEST['notice_own_data']:1,
												 "add" => isset($_REQUEST['notice_add'])?$_REQUEST['notice_add']:0,
												"edit"=>isset($_REQUEST['notice_edit'])?$_REQUEST['notice_edit']:0,
												"view"=>isset($_REQUEST['notice_view'])?$_REQUEST['notice_view']:1,
												"delete"=>isset($_REQUEST['notice_delete'])?$_REQUEST['notice_delete']:0
									  ],
									  "message"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/message.png'),
												"menu_title"=>'Message',
												"page_link"=>'message',
												 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
												 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
												"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
												"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
												"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
									  ],
									  "holiday"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/holiday.png'),
												 "menu_title"=>'Holiday',
												 "page_link"=>'holiday',
												 "own_data" => isset($_REQUEST['holiday_own_data'])?$_REQUEST['holiday_own_data']:0,
												 "add" => isset($_REQUEST['holiday_add'])?$_REQUEST['holiday_add']:1,
												"edit"=>isset($_REQUEST['holiday_edit'])?$_REQUEST['holiday_edit']:1,
												"view"=>isset($_REQUEST['holiday_view'])?$_REQUEST['holiday_view']:1,
												"delete"=>isset($_REQUEST['holiday_delete'])?$_REQUEST['holiday_delete']:1
									  ],
									  
									   "library"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/library.png'),
											   "menu_title"=>'Library',
											   "page_link"=>'library',
												 "own_data" => isset($_REQUEST['library_own_data'])?$_REQUEST['library_own_data']:1,
												 "add" => isset($_REQUEST['library_add'])?$_REQUEST['library_add']:0,
												"edit"=>isset($_REQUEST['library_edit'])?$_REQUEST['library_edit']:0,
												"view"=>isset($_REQUEST['library_view'])?$_REQUEST['library_view']:1,
												"delete"=>isset($_REQUEST['library_delete'])?$_REQUEST['library_delete']:0
									  ],
									  
									   "account"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/account.png'),
												"menu_title"=>'Account',
												"page_link"=>'account',
												 "own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:1,
												 "add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:0,
												"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:0,
												"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
												"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
									  ],
									  
									   "report"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/report.png'),							       
												 "menu_title"=>'Report',
												 "page_link"=>'report',
												 "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:1,
												 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
												"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
												"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:1,
												"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
									  ],
									];
				$role_access_right_parent = array();
				$role_access_right_parent['parent'] = [
									"teacher"=>["menu_icone"=>plugins_url('school-management/assets/images/icons/teacher.png'),
											   "menu_title"=>'Teacher',
											   "page_link"=>'teacher',
											   "own_data" =>isset($_REQUEST['teacher_own_data'])?$_REQUEST['teacher_own_data']:1,
											   "add" =>isset($_REQUEST['teacher_add'])?$_REQUEST['teacher_add']:0,
												"edit"=>isset($_REQUEST['teacher_edit'])?$_REQUEST['teacher_edit']:0,
												"view"=>isset($_REQUEST['teacher_view'])?$_REQUEST['teacher_view']:1,
												"delete"=>isset($_REQUEST['teacher_delete'])?$_REQUEST['teacher_delete']:0
												],
														
								   "student"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/student-icon.png'),
											  "menu_title"=>'Student',
											  "page_link"=>'student',
											 "own_data" => isset($_REQUEST['student_own_data'])?$_REQUEST['student_own_data']:1,
											 "add" => isset($_REQUEST['student_add'])?$_REQUEST['student_add']:0,
											 "edit"=>isset($_REQUEST['student_edit'])?$_REQUEST['student_edit']:0,
											 "view"=>isset($_REQUEST['student_view'])?$_REQUEST['student_view']:1,
											 "delete"=>isset($_REQUEST['student_delete'])?$_REQUEST['student_delete']:0
								  ],
											  
									"parent"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/parents.png'),
											"menu_title"=>'Parent',
											"page_link"=>'parent',
											 "own_data" => isset($_REQUEST['parent_own_data'])?$_REQUEST['parent_own_data']:1,
											 "add" => isset($_REQUEST['parent_add'])?$_REQUEST['parent_add']:0,
											"edit"=>isset($_REQUEST['parent_edit'])?$_REQUEST['parent_edit']:0,
											"view"=>isset($_REQUEST['parent_view'])?$_REQUEST['parent_view']:1,
											"delete"=>isset($_REQUEST['parent_delete'])?$_REQUEST['parent_delete']:0
								  ],
											  
									  "subject"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/subject.png'),
												"menu_title"=>'Subject',
												"page_link"=>'subject',
												"own_data" => isset($_REQUEST['subject_own_data'])?$_REQUEST['subject_own_data']:1,
												 "add" => isset($_REQUEST['subject_add'])?$_REQUEST['subject_add']:0,
												 "edit"=>isset($_REQUEST['subject_edit'])?$_REQUEST['subject_edit']:0,
												"view"=>isset($_REQUEST['subject_view'])?$_REQUEST['subject_view']:1,
												"delete"=>isset($_REQUEST['subject_delete'])?$_REQUEST['subject_delete']:0
									  ],
									  
									  "schedule"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/class-route.png'),
												 "menu_title"=>'Class Routine',
												 "page_link"=>'schedule',
												 "own_data" => isset($_REQUEST['schedule_own_data'])?$_REQUEST['schedule_own_data']:1,
												 "add" => isset($_REQUEST['schedule_add'])?$_REQUEST['schedule_add']:0,
												"edit"=>isset($_REQUEST['schedule_edit'])?$_REQUEST['schedule_edit']:0,
												"view"=>isset($_REQUEST['schedule_view'])?$_REQUEST['schedule_view']:1,
												"delete"=>isset($_REQUEST['schedule_delete'])?$_REQUEST['schedule_delete']:0
									  ],

									  "virtual_classroom"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/virtual_classroom.png'),							       
												 "menu_title"=>'virtual_classroom',
												 "page_link"=>'virtual_classroom',
												 "own_data" => isset($_REQUEST['virtual_classroom_own_data'])?$_REQUEST['virtual_classroom_own_data']:1,
												 "add" => isset($_REQUEST['virtual_classroom_add'])?$_REQUEST['virtual_classroom_add']:0,
												"edit"=>isset($_REQUEST['virtual_classroom_edit'])?$_REQUEST['virtual_classroom_edit']:0,
												"view"=>isset($_REQUEST['virtual_classroom_view'])?$_REQUEST['virtual_classroom_view']:1,
												"delete"=>isset($_REQUEST['virtual_classroom_delete'])?$_REQUEST['virtual_classroom_delete']:0
									  ],

									  "attendance"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/attandance.png'),
												   "menu_title"=>'Attendance',
												   "page_link"=>'attendance',
												 "own_data" => isset($_REQUEST['attendance_own_data'])?$_REQUEST['attendance_own_data']:0,
												 "add" => isset($_REQUEST['attendance_add'])?$_REQUEST['attendance_add']:0,
												"edit"=>isset($_REQUEST['attendance_edit'])?$_REQUEST['attendance_edit']:0,
												"view"=>isset($_REQUEST['attendance_view'])?$_REQUEST['attendance_view']:0,
												"delete"=>isset($_REQUEST['attendance_delete'])?$_REQUEST['attendance_delete']:0
									  ],
									  
										"exam"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/exam.png'),
												 "menu_title"=>'Exam',
												 "page_link"=>'exam',
												 "own_data" => isset($_REQUEST['exam_own_data'])?$_REQUEST['exam_own_data']:1,
												 "add" => isset($_REQUEST['exam_add'])?$_REQUEST['exam_add']:0,
												"edit"=>isset($_REQUEST['exam_edit'])?$_REQUEST['exam_edit']:0,
												"view"=>isset($_REQUEST['exam_view'])?$_REQUEST['exam_view']:1,
												"delete"=>isset($_REQUEST['exam_delete'])?$_REQUEST['exam_delete']:0
									  ],
									  
										"hostel"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/hostel.png'),
												 "menu_title"=>'Hostel',
												 "page_link"=>'hostel',
												 "own_data" => isset($_REQUEST['hostel_own_data'])?$_REQUEST['hostel_own_data']:0,
												 "add" => isset($_REQUEST['hostel_add'])?$_REQUEST['hostel_add']:0,
												"edit"=>isset($_REQUEST['hostel_edit'])?$_REQUEST['hostel_edit']:0,
												"view"=>isset($_REQUEST['hostel_view'])?$_REQUEST['hostel_view']:1,
												"delete"=>isset($_REQUEST['hostel_delete'])?$_REQUEST['hostel_delete']:0
									  ],
										"homework"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/homework.png'),
												 "menu_title"=>'Home Work',
												 "page_link"=>'homework',
												 "own_data" => isset($_REQUEST['homework_own_data'])?$_REQUEST['homework_own_data']:1,
												 "add" => isset($_REQUEST['homework_add'])?$_REQUEST['homework_add']:0,
												"edit"=>isset($_REQUEST['homework_edit'])?$_REQUEST['homework_edit']:0,
												"view"=>isset($_REQUEST['homework_view'])?$_REQUEST['homework_view']:1,
												"delete"=>isset($_REQUEST['homework_delete'])?$_REQUEST['homework_delete']:0
									  ],
										"manage_marks"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/mark-manage.png'),
												  "menu_title"=>'Mark Manage',
												  "page_link"=>'manage_marks',
												 "own_data" => isset($_REQUEST['manage_marks_own_data'])?$_REQUEST['manage_marks_own_data']:0,
												 "add" => isset($_REQUEST['manage_marks_add'])?$_REQUEST['manage_marks_add']:0,
												"edit"=>isset($_REQUEST['manage_marks_edit'])?$_REQUEST['manage_marks_edit']:0,
												"view"=>isset($_REQUEST['manage_marks_view'])?$_REQUEST['manage_marks_view']:0,
												"delete"=>isset($_REQUEST['manage_marks_delete'])?$_REQUEST['manage_marks_delete']:0
									  ],
									  
									  "feepayment"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/fee.png'),
												 "menu_title"=>'Fee Payment',
												 "page_link"=>'feepayment',
												 "own_data" => isset($_REQUEST['feepayment_own_data'])?$_REQUEST['feepayment_own_data']:1,
												 "add" => isset($_REQUEST['feepayment_add'])?$_REQUEST['feepayment_add']:0,
												"edit"=>isset($_REQUEST['feepayment_edit'])?$_REQUEST['feepayment_edit']:0,
												"view"=>isset($_REQUEST['feepayment_view'])?$_REQUEST['feepayment_view']:1,
												"delete"=>isset($_REQUEST['feepayment_delete'])?$_REQUEST['feepayment_delete']:0
									  ],
									  
									  "payment"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/payment.png'),
												 "menu_title"=>'Payment',
												 "page_link"=>'payment',
												 "own_data" => isset($_REQUEST['payment_own_data'])?$_REQUEST['payment_own_data']:1,
												 "add" => isset($_REQUEST['payment_add'])?$_REQUEST['payment_add']:0,
												"edit"=>isset($_REQUEST['payment_edit'])?$_REQUEST['payment_edit']:0,
												"view"=>isset($_REQUEST['payment_view'])?$_REQUEST['payment_view']:1,
												"delete"=>isset($_REQUEST['payment_delete'])?$_REQUEST['payment_delete']:0
									  ],
									  "transport"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/transport.png'),
											   "menu_title"=>'Transport',
											   "page_link"=>'transport',
												 "own_data" => isset($_REQUEST['transport_own_data'])?$_REQUEST['transport_own_data']:0,
												 "add" => isset($_REQUEST['transport_add'])?$_REQUEST['transport_add']:0,
												"edit"=>isset($_REQUEST['transport_edit'])?$_REQUEST['transport_edit']:0,
												"view"=>isset($_REQUEST['transport_view'])?$_REQUEST['transport_view']:1,
												"delete"=>isset($_REQUEST['transport_delete'])?$_REQUEST['transport_delete']:0
									  ],
									  "notice"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/notice.png'),
												  "menu_title"=>'Notice Board',
												  "page_link"=>'notice',
												 "own_data" => isset($_REQUEST['notice_own_data'])?$_REQUEST['notice_own_data']:1,
												 "add" => isset($_REQUEST['notice_add'])?$_REQUEST['notice_add']:0,
												"edit"=>isset($_REQUEST['notice_edit'])?$_REQUEST['notice_edit']:0,
												"view"=>isset($_REQUEST['notice_view'])?$_REQUEST['notice_view']:1,
												"delete"=>isset($_REQUEST['notice_delete'])?$_REQUEST['notice_delete']:0
									  ],
									  "message"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/message.png'),
												"menu_title"=>'Message',
												"page_link"=>'message',
												 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
												 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
												"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
												"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
												"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
									  ],
									  "holiday"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/holiday.png'),
												 "menu_title"=>'Holiday',
												 "page_link"=>'holiday',
												 "own_data" => isset($_REQUEST['holiday_own_data'])?$_REQUEST['holiday_own_data']:0,
												 "add" => isset($_REQUEST['holiday_add'])?$_REQUEST['holiday_add']:0,
												"edit"=>isset($_REQUEST['holiday_edit'])?$_REQUEST['holiday_edit']:0,
												"view"=>isset($_REQUEST['holiday_view'])?$_REQUEST['holiday_view']:1,
												"delete"=>isset($_REQUEST['holiday_delete'])?$_REQUEST['holiday_delete']:0
									  ],
									  
									   "library"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/library.png'),
											   "menu_title"=>'Library',
											   "page_link"=>'library',
												 "own_data" => isset($_REQUEST['library_own_data'])?$_REQUEST['library_own_data']:1,
												 "add" => isset($_REQUEST['library_add'])?$_REQUEST['library_add']:0,
												"edit"=>isset($_REQUEST['library_edit'])?$_REQUEST['library_edit']:0,
												"view"=>isset($_REQUEST['library_view'])?$_REQUEST['library_view']:1,
												"delete"=>isset($_REQUEST['library_delete'])?$_REQUEST['library_delete']:0
									  ],
									  
									   "account"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/account.png'),
												"menu_title"=>'Account',
												"page_link"=>'account',
												 "own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:1,
												 "add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:0,
												"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:0,
												"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
												"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
									  ],
									  
									   "report"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/report.png'),							       
												 "menu_title"=>'Report',
												 "page_link"=>'report',
												 "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
												 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
												"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
												"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:0,
												"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
									  ]
									];
				$role_access_right_support_staff = array();
				$role_access_right_support_staff['supportstaff'] = [
									"teacher"=>["menu_icone"=>plugins_url('school-management/assets/images/icons/teacher.png'),
											   "menu_title"=>'Teacher',
											   "page_link"=>'teacher',
											   "own_data" =>isset($_REQUEST['teacher_own_data'])?$_REQUEST['teacher_own_data']:0,
											   "add" =>isset($_REQUEST['teacher_add'])?$_REQUEST['teacher_add']:1,
												"edit"=>isset($_REQUEST['teacher_edit'])?$_REQUEST['teacher_edit']:1,
												"view"=>isset($_REQUEST['teacher_view'])?$_REQUEST['teacher_view']:1,
												"delete"=>isset($_REQUEST['teacher_delete'])?$_REQUEST['teacher_delete']:1
												],
														
								   "student"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/student-icon.png'),
											  "menu_title"=>'Student',
											  "page_link"=>'student',
											 "own_data" => isset($_REQUEST['student_own_data'])?$_REQUEST['student_own_data']:0,
											 "add" => isset($_REQUEST['student_add'])?$_REQUEST['student_add']:1,
											 "edit"=>isset($_REQUEST['student_edit'])?$_REQUEST['student_edit']:1,
											 "view"=>isset($_REQUEST['student_view'])?$_REQUEST['student_view']:1,
											 "delete"=>isset($_REQUEST['student_delete'])?$_REQUEST['student_delete']:1
								  ],
											  
									"parent"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/parents.png'),
											"menu_title"=>'Parent',
											"page_link"=>'parent',
											 "own_data" => isset($_REQUEST['parent_own_data'])?$_REQUEST['parent_own_data']:0,
											 "add" => isset($_REQUEST['parent_add'])?$_REQUEST['parent_add']:1,
											"edit"=>isset($_REQUEST['parent_edit'])?$_REQUEST['parent_edit']:1,
											"view"=>isset($_REQUEST['parent_view'])?$_REQUEST['parent_view']:1,
											"delete"=>isset($_REQUEST['parent_delete'])?$_REQUEST['parent_delete']:1
								  ],
											  
									  "subject"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/subject.png'),
												"menu_title"=>'Subject',
												"page_link"=>'subject',
												"own_data" => isset($_REQUEST['subject_own_data'])?$_REQUEST['subject_own_data']:0,
												 "add" => isset($_REQUEST['subject_add'])?$_REQUEST['subject_add']:1,
												 "edit"=>isset($_REQUEST['subject_edit'])?$_REQUEST['subject_edit']:1,
												"view"=>isset($_REQUEST['subject_view'])?$_REQUEST['subject_view']:1,
												"delete"=>isset($_REQUEST['subject_delete'])?$_REQUEST['subject_delete']:1
									  ],
									  
									  "schedule"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/class-route.png'),
												 "menu_title"=>'Class Routine',
												 "page_link"=>'schedule',
												 "own_data" => isset($_REQUEST['schedule_own_data'])?$_REQUEST['schedule_own_data']:0,
												 "add" => isset($_REQUEST['schedule_add'])?$_REQUEST['schedule_add']:0,
												"edit"=>isset($_REQUEST['schedule_edit'])?$_REQUEST['schedule_edit']:0,
												"view"=>isset($_REQUEST['schedule_view'])?$_REQUEST['schedule_view']:1,
												"delete"=>isset($_REQUEST['schedule_delete'])?$_REQUEST['schedule_delete']:0
									  ],

									  "class"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/class.png'),
											"menu_title"=>'Class',
											"page_link"=>'class',
											"own_data" => isset($_REQUEST['class_own_data'])?$_REQUEST['class_own_data']:0,
											"add" => isset($_REQUEST['class_add'])?$_REQUEST['class_add']:0,
											"edit"=>isset($_REQUEST['class_edit'])?$_REQUEST['class_edit']:0,
											"view"=>isset($_REQUEST['class_view'])?$_REQUEST['class_view']:1,
											"delete"=>isset($_REQUEST['class_delete'])?$_REQUEST['class_delete']:0
										],
 

									  "virtual_classroom"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/virtual_classroom.png'),							       
												 "menu_title"=>'virtual_classroom',
												 "page_link"=>'virtual_classroom',
												 "own_data" => isset($_REQUEST['virtual_classroom_own_data'])?$_REQUEST['virtual_classroom_own_data']:0,
												 "add" => isset($_REQUEST['virtual_classroom_add'])?$_REQUEST['virtual_classroom_add']:1,
												"edit"=>isset($_REQUEST['virtual_classroom_edit'])?$_REQUEST['virtual_classroom_edit']:1,
												"view"=>isset($_REQUEST['virtual_classroom_view'])?$_REQUEST['virtual_classroom_view']:1,
												"delete"=>isset($_REQUEST['virtual_classroom_delete'])?$_REQUEST['virtual_classroom_delete']:1
									  ],

									  "attendance"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/attandance.png'),
												   "menu_title"=>'Attendance',
												   "page_link"=>'attendance',
												 "own_data" => isset($_REQUEST['attendance_own_data'])?$_REQUEST['attendance_own_data']:0,
												 "add" => isset($_REQUEST['attendance_add'])?$_REQUEST['attendance_add']:0,
												"edit"=>isset($_REQUEST['attendance_edit'])?$_REQUEST['attendance_edit']:0,
												"view"=>isset($_REQUEST['attendance_view'])?$_REQUEST['attendance_view']:1,
												"delete"=>isset($_REQUEST['attendance_delete'])?$_REQUEST['attendance_delete']:0
									  ],
									  
										"exam"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/exam.png'),
												 "menu_title"=>'Exam',
												 "page_link"=>'exam',
												 "own_data" => isset($_REQUEST['exam_own_data'])?$_REQUEST['exam_own_data']:0,
												 "add" => isset($_REQUEST['exam_add'])?$_REQUEST['exam_add']:1,
												"edit"=>isset($_REQUEST['exam_edit'])?$_REQUEST['exam_edit']:1,
												"view"=>isset($_REQUEST['exam_view'])?$_REQUEST['exam_view']:1,
												"delete"=>isset($_REQUEST['exam_delete'])?$_REQUEST['exam_delete']:1
									  ],
									  
										"hostel"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/hostel.png'),
												 "menu_title"=>'Hostel',
												 "page_link"=>'hostel',
												 "own_data" => isset($_REQUEST['hostel_own_data'])?$_REQUEST['hostel_own_data']:0,
												 "add" => isset($_REQUEST['hostel_add'])?$_REQUEST['hostel_add']:1,
												"edit"=>isset($_REQUEST['hostel_edit'])?$_REQUEST['hostel_edit']:1,
												"view"=>isset($_REQUEST['hostel_view'])?$_REQUEST['hostel_view']:1,
												"delete"=>isset($_REQUEST['hostel_delete'])?$_REQUEST['hostel_delete']:1
									  ],
										"homework"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/homework.png'),
												 "menu_title"=>'Home Work',
												 "page_link"=>'homework',
												 "own_data" => isset($_REQUEST['homework_own_data'])?$_REQUEST['homework_own_data']:0,
												 "add" => isset($_REQUEST['homework_add'])?$_REQUEST['homework_add']:1,
												"edit"=>isset($_REQUEST['homework_edit'])?$_REQUEST['homework_edit']:1,
												"view"=>isset($_REQUEST['homework_view'])?$_REQUEST['homework_view']:1,
												"delete"=>isset($_REQUEST['homework_delete'])?$_REQUEST['homework_delete']:1
									  ],
										"manage_marks"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/mark-manage.png'),
												  "menu_title"=>'Mark Manage',
												  "page_link"=>'manage_marks',
												 "own_data" => isset($_REQUEST['manage_marks_own_data'])?$_REQUEST['manage_marks_own_data']:0,
												 "add" => isset($_REQUEST['manage_marks_add'])?$_REQUEST['manage_marks_add']:1,
												"edit"=>isset($_REQUEST['manage_marks_edit'])?$_REQUEST['manage_marks_edit']:1,
												"view"=>isset($_REQUEST['manage_marks_view'])?$_REQUEST['manage_marks_view']:1,
												"delete"=>isset($_REQUEST['manage_marks_delete'])?$_REQUEST['manage_marks_delete']:0
									  ],
									  
									  "feepayment"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/fee.png'),
												 "menu_title"=>'Fee Payment',
												 "page_link"=>'feepayment',
												 "own_data" => isset($_REQUEST['feepayment_own_data'])?$_REQUEST['feepayment_own_data']:0,
												 "add" => isset($_REQUEST['feepayment_add'])?$_REQUEST['feepayment_add']:1,
												"edit"=>isset($_REQUEST['feepayment_edit'])?$_REQUEST['feepayment_edit']:1,
												"view"=>isset($_REQUEST['feepayment_view'])?$_REQUEST['feepayment_view']:1,
												"delete"=>isset($_REQUEST['feepayment_delete'])?$_REQUEST['feepayment_delete']:1
									  ],
									  
									  "payment"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/payment.png'),
												 "menu_title"=>'Payment',
												 "page_link"=>'payment',
												 "own_data" => isset($_REQUEST['payment_own_data'])?$_REQUEST['payment_own_data']:0,
												 "add" => isset($_REQUEST['payment_add'])?$_REQUEST['payment_add']:1,
												"edit"=>isset($_REQUEST['payment_edit'])?$_REQUEST['payment_edit']:1,
												"view"=>isset($_REQUEST['payment_view'])?$_REQUEST['payment_view']:1,
												"delete"=>isset($_REQUEST['payment_delete'])?$_REQUEST['payment_delete']:1
									  ],
									  "transport"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/transport.png'),
											   "menu_title"=>'Transport',
											   "page_link"=>'transport',
												 "own_data" => isset($_REQUEST['transport_own_data'])?$_REQUEST['transport_own_data']:0,
												 "add" => isset($_REQUEST['transport_add'])?$_REQUEST['transport_add']:1,
												"edit"=>isset($_REQUEST['transport_edit'])?$_REQUEST['transport_edit']:1,
												"view"=>isset($_REQUEST['transport_view'])?$_REQUEST['transport_view']:1,
												"delete"=>isset($_REQUEST['transport_delete'])?$_REQUEST['transport_delete']:1
									  ],
									  "notice"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/notice.png'),
												  "menu_title"=>'Notice Board',
												  "page_link"=>'notice',
												 "own_data" => isset($_REQUEST['notice_own_data'])?$_REQUEST['notice_own_data']:0,
												 "add" => isset($_REQUEST['notice_add'])?$_REQUEST['notice_add']:1,
												"edit"=>isset($_REQUEST['notice_edit'])?$_REQUEST['notice_edit']:1,
												"view"=>isset($_REQUEST['notice_view'])?$_REQUEST['notice_view']:1,
												"delete"=>isset($_REQUEST['notice_delete'])?$_REQUEST['notice_delete']:0
									  ],
									  "message"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/message.png'),
												"menu_title"=>'Message',
												"page_link"=>'message',
												 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
												 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
												"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
												"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
												"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
									  ],
									  "holiday"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/holiday.png'),
												 "menu_title"=>'Holiday',
												 "page_link"=>'holiday',
												 "own_data" => isset($_REQUEST['holiday_own_data'])?$_REQUEST['holiday_own_data']:0,
												 "add" => isset($_REQUEST['holiday_add'])?$_REQUEST['holiday_add']:1,
												"edit"=>isset($_REQUEST['holiday_edit'])?$_REQUEST['holiday_edit']:1,
												"view"=>isset($_REQUEST['holiday_view'])?$_REQUEST['holiday_view']:1,
												"delete"=>isset($_REQUEST['holiday_delete'])?$_REQUEST['holiday_delete']:1
									  ],
									  
									   "library"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/library.png'),
											   "menu_title"=>'Library',
											   "page_link"=>'library',
												 "own_data" => isset($_REQUEST['library_own_data'])?$_REQUEST['library_own_data']:0,
												 "add" => isset($_REQUEST['library_add'])?$_REQUEST['library_add']:1,
												"edit"=>isset($_REQUEST['library_edit'])?$_REQUEST['library_edit']:1,
												"view"=>isset($_REQUEST['library_view'])?$_REQUEST['library_view']:1,
												"delete"=>isset($_REQUEST['library_delete'])?$_REQUEST['library_delete']:1
									  ],
									  
									   "account"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/account.png'),
												"menu_title"=>'Account',
												"page_link"=>'account',
												 "own_data" => isset($_REQUEST['account_own_data'])?$_REQUEST['account_own_data']:1,
												 "add" => isset($_REQUEST['account_add'])?$_REQUEST['account_add']:0,
												"edit"=>isset($_REQUEST['account_edit'])?$_REQUEST['account_edit']:0,
												"view"=>isset($_REQUEST['account_view'])?$_REQUEST['account_view']:1,
												"delete"=>isset($_REQUEST['account_delete'])?$_REQUEST['account_delete']:0
									  ],
									  
									   "report"=>['menu_icone'=>plugins_url( 'school-management/assets/images/icons/report.png'),							       
												 "menu_title"=>'Report',
												 "page_link"=>'report',
												 "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
												 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
												"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
												"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:1,
												"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
									  ]
									  
									];

		$options=array(
			"smgt_school_name"=> esc_attr__( 'School Title Here' ,'school-mgt'),
			"smgt_staring_year"=>"",
			"smgt_school_address"=>"",
			"smgt_contact_number"=>"",
			"smgt_contry"=>"United States",
			"smgt_email"=>"",
			"smgt_datepicker_format"=>'yy/mm/dd',
			"smgt_school_logo"=>plugins_url( 'school-management/assets/images/finel-logo6.png' ),
			"smgt_school_background_image"=>plugins_url('school-management/assets/images/school_life.jpg' ),
			"smgt_student_thumb"=>plugins_url('school-management/assets/images/finel-logo6.png' ),
			"smgt_parent_thumb"=>plugins_url('school-management/assets/images/finel-logo6.png' ),
			"smgt_teacher_thumb"=>plugins_url('school-management/assets/images/finel-logo6.png' ),
			"smgt_supportstaff_thumb"=>plugins_url('school-management/assets/images/finel-logo6.png' ),
			"smgt_driver_thumb"=>plugins_url('school-management/assets/images/finel-logo6.png' ),
			"smgt_principal_signature"=>plugins_url('school-management/assets/images/Signature Stamp.png' ),
			"smgt_access_right_student"=>$role_access_right_student,	
			"smgt_access_right_teacher"=>$role_access_right_teacher,	
			"smgt_access_right_parent"=>$role_access_right_parent,	
			"smgt_access_right_supportstaff"=>$role_access_right_support_staff,	
			"smgt_sms_service"=>"",
			//PAY MASTER OPTION//
			"smgt_paymaster_pack"=>"no",
			"smgt_mail_notification"=>1,
			"smgt_notification_fcm_key"=>"",
			"smgt_sms_service_enable"=> 0,
			"student_approval"=> 1,
			"smgt_sms_template"=>"Hello [SMS_USER_NAME] ",
			"smgt_clickatell_sms_service"=>array(),
			"smgt_twillo_sms_service"=>array(),
			"parent_send_message"=>1,
			"smgt_enable_total_student"=>1,
			"smgt_enable_total_teacher"=>1,
			"smgt_enable_total_parent"=>1,
			"smgt_enable_homework_mail"=>0,
			"smgt_enable_total_attendance"=>1,
			"smgt_enable_sandbox"=>'yes',
			"smgt_virtual_classroom_client_id"=>'',
			"smgt_virtual_classroom_client_secret_id"=>'',
			"smgt_virtual_classroom_access_token"=>'',
			"smgt_enable_virtual_classroom"=>'no',
			"smgt_paypal_email"=>'',
			"smgt_currency_code"=>'USD',
			"smgt_teacher_manage_allsubjects_marks"=>'yes',
			"registration_title"=>'Student Registration',
			"student_activation_title"=>'Student Approved',
			"fee_payment_title"=>'Fees Alert',
			"smgt_teacher_show_access"=>"own_class",
			"admissiion_title"=>'Request For Admission',
			"exam_receipt_subject"=>'Exam Receipt Generate',
			"bed_subject"=>'Hostel Bed Assigned',
			"add_approve_admisson_mail_subject"=>'Admission Approved',
			"student_assign_teacher_mail_subject"=>"New Student has been assigned to you.",
			"smgt_enable_virtual_classroom_reminder"=>"yes",
			"smgt_enable_sms_virtual_classroom_reminder"=>"yes",
			"smgt_virtual_classroom_reminder_before_time"=>"30",
			
			"student_assign_teacher_mail_content"=>"Dear {{teacher_name}},

         New Student {{student_name}} has been assigned to you.
 
Regards From {{school_name}}.",

					"generate_invoice_mail_subject"=>"Generate Invoice",
					"generate_invoice_mail_content"=>"Dear {{student_name}},

        Your have a new invoice.  You can check the invoice attached here.
 
Regards From {{school_name}}.",
//------------ ADD USER ---------------//
		"add_user_mail_subject" => 'Your have been assigned role of {{role}} in {{school_name}}.',
		"add_user_mail_content"=>"Dear {{user_name}},

         You are Added by admin in {{school_name}} . Your have been assigned role of {{role}} in {{school_name}}.  You can sign in using this link. {{login_link}}

UserName : {{username}}
Password : {{Password}}

Regards From {{school_name}}.",

//------- Registration Successfully ----------//					
		"registration_mailtemplate"=>"Hello {{student_name}} ,

Your registration has been successful with {{school_name}}. You will be able to access your account after the school admin approves it. 

User Name : {{user_name}}
Class Name : {{class_name}}
Email : {{email}}


Regards From {{school_name}}.",

//------- Request for  Admission ----------//
		"admission_mailtemplate_content"=>"Hello {{student_name}} ,

Your admission request has been successful with {{school_name}}. You will be able to access your account after school admin approves it and we will send username and password shortly. 

Student Name : {{user_name}} 
Email : {{email}}

Regards From {{school_name}}.",

//------- Exam Receipt GENERATE----------//
		"exam_receipt_content"=>"Hello {{student_name}} ,

		your exam hall receipt has been generated.

Regards From {{school_name}}.",


//------- Hostel Bed Assigned ----------//
		"bed_content"=>"Hello {{student_name}} ,

		You have been assigned new hostel bed in {{school_name}}.

Hostel Name : {{hostel_name}}
Room Number : {{room_id}}
Bed Number : {{bed_id}}

Regards From {{school_name}}.",

//------- Approved Admission ----------//
		"add_approve_admission_mail_content"=>"Hello {{user_name}} ,

Your admission has been successful approved with {{school_name}}. Your have been assigned role of {{role}} in {{school_name}}.  You can signin using this link. {{login_link}}

UserName : {{username}}
Password : {{Password}}
Class Name : {{class_name}}
Email : {{email}}

Regards From {{school_name}}.",

//----------- Student Activation --------------//

		"student_activation_mailcontent"=>"Hello {{student_name}},
                 Your account with {{school_name}} is approved. You can access student account using your login details. Your other details are given bellow.

User Name : {{user_name}}
Class Name : {{class_name}}
Email : {{email}}

Regards From {{school_name}}.",
  
//--------------- FEES PAYMENT --------------//  
		"fee_payment_mailcontent"=>"Dear {{parent_name}},

        You have a new invoice.  You can check the invoice attached here.
.",
//------------------ MESSAGE RECEIVED ---------------//
'message_received_mailcontent'=>'Dear {{receiver_name}},

        You have received new message {{message_content}}.
 
Regards From {{school_name}}.',
'message_received_mailsubject'=>'You have received new message from {{from_mail}} at {{school_name}}',
//------------------ CHILD ABSENT -------------------//
'absent_mail_notification_subject'=>'Your Child {{child_name}} is absent today',
'absent_mail_notification_content'=>"Your Child {{child_name}} is absent today.

Regards From {{school_name}}.",
//----------------- ASSIGNED TEACHER ------------------//
'student_assign_to_teacher_subject'=>'You have been Assigned {{teacher_name}} at {{school_name}}',
'student_assign_to_teacher_content'=>'Dear {{student_name}},

         You are assigned to  {{teacher_name}}. {{teacher_name}} belongs to {{class_name}}.
 
Regards From {{school_name}}.',

'payment_recived_mailsubject'=>'Payment Received against Invoice',
'payment_recived_mailcontent'=>'Dear {{student_name}},

        Your have successfully paid your invoice {{invoice_no}}. You can check the invoice attached here.
 
Regards From {{school_name}}.',
'notice_mailsubject'	=>	'New Notice For You',
'notice_mailcontent'	=>	'New Notice For You.

Notice Title : {{notice_title}}

Notice Date  : {{notice_date}}

Notice For  : {{notice_for}}

Notice Comment :  {{notice_comment}}

Regards From {{school_name}}
',

/*   -------Parent mail notification template------- */
'parent_homework_mail_subject'=>'New Homework Assigned',
'parent_homework_mail_content'	=>	'Dear {{parent_name}},

	New homework has been assign to you/your child.
	
Student name : {{student_name}} 
Homework Title : {{title}}
Submission Date : {{submition_date}}


Regards From {{school_name}}
',
/*   -------student mail notification template------- */

'homework_title'=>'New Homework Assigned',

'homework_mailcontent'	=>	'Dear {{student_name}},

		New homework has been assign to you
			
Homework Title : {{title}}
Submission Date : {{submition_date}}

Regards From {{school_name}}
',
//-------------- HOLIDAY MAILTEMPLATE -----------//
'holiday_mailsubject'=>'Holiday Announcement',
'holiday_mailcontent'=>'Holiday Announcement

Holiday Title : {{holiday_title}}

Holiday Date : {{holiday_date}}

Regards From {{school_name}}
',
//----------------------- SCHOOL BUS ALLOCATION ------//
'school_bus_alocation_mail_subject'=>'School Bus Allocation',
'school_bus_alocation_mail_content'=>'School Bus Allocation
	
	Route Name : {{route_name}}
	
	Vehicle Identifier : {{vehicle_identifier}}
	
	Vehicle Registration Number : {{vehicle_registration_number}}
	
	Driver Name : {{driver_name}}
	
	Driver Phone Number : {{driver_phone_number}}
	
	Driver Address : {{driver_address}}
	
	Route Fare  : {{route_fare}}
	
	Regards From {{school_name}}

',
//----------------------- VIRTUAL CLASSROOM TEACHER INVITE MAIL ------//
'virtual_class_invite_teacher_mail_subject'=>'Inviting you to a scheduled Zoom meeting',
'virtual_class_invite_teacher_mail_content'=>'Inviting you to a scheduled Zoom meeting
	
	Class Name : {{class_name}}

	Time : {{time}}
	
	Virtual Class ID : {{virtual_class_id}}
	
	Password : {{password}}
	
	Join Zoom Virtual Class : {{join_zoom_virtual_class}}
	
	Start Zoom Virtual Class : {{start_zoom_virtual_class}}
	
	Regards From {{school_name}}
',
//----------------------- VIRTUAL CLASSROOM TEACHER REMINDER MAIL ------//
'virtual_class_teacher_reminder_mail_subject'=>'Your virtual class just start',
'virtual_class_teacher_reminder_mail_content'=>'Dear {{teacher_name}}

	Your virtual class just start
	
	Class Name : {{class_name}}

	subject Name : {{subject_name}}

	Date : {{date}}
	
	Time : {{time}}
	
	Virtual Class ID : {{virtual_class_id}}
	
	Password : {{password}}
	
	{{start_zoom_virtual_class}}
	
	Regards From {{school_name}}
',
//----------------------- VIRTUAL CLASSROOM STUDENT REMINDER MAIL ------//
'virtual_class_student_reminder_mail_subject'=>'Your virtual class just start',
'virtual_class_student_reminder_mail_content'=>'Dear {{student_name}}
	
	Your virtual class just start
	
	Class Name : {{class_name}}

	Subject Name : {{subject_name}}

	Teacher Name : {{teacher_name}}

	Date : {{date}}
	
	Time : {{time}}
	
	Virtual Class ID : {{virtual_class_id}}
	
	Password : {{password}}
	
	{{join_zoom_virtual_class}}
	
	Regards From {{school_name}}
',
//----------------- Fee Payment Reminder Mail ---------------------//
'fee_payment_reminder_title'=>'Fees Payment Reminder',
'fee_payment_reminder_mailcontent'=>'
Dear {{parent_name}},

We just wanted to send you a reminder that the tuition fee has not been paid against your son/daughter {{student_name}} of class {{class_name}} .the total amount is {{total_amount}} and the due amount is {{due_amount}}.

Regards From 
{{school_name}}',

//----------------- Assign Subject Mail ---------------------//
'assign_subject_title'=>'New subject has been assigned to you.',
'assign_subject_mailcontent'=>'
Dear {{teacher_name}},

New subject {{subject_name}} has been assigned to you.

Regards From 
{{school_name}}',

//----------------- Issue Book  Mail ---------------------//

'issue_book_title'=>'New book has been issue to you.',
'issue_book_mailcontent'=>'
Dear {{student_name}},

New book {{book_name}} has been issue to you.

Regards From 
{{school_name}}'

);


		return $options;
	}
	add_action('admin_init','mj_smgt_general_setting');
	function mj_smgt_general_setting()
	{
		$options=mj_smgt_option();
		foreach($options as $key=>$val)
		{
			add_option($key,$val);			
		}
	}
	function mj_smgt_call_script_page()
	{
		$page_array = array('smgt_school','smgt_admission','smgt_setup','smgt_student','smgt_student_homewrok','smgt_teacher','smgt_parent','smgt_Subject','smgt_class','smgt_route','smgt_attendence','smgt_exam',
				'smgt_grade','smgt_result','smgt_transport','smgt_notice','smgt_message','smgt_hall','smgt_fees','smgt_fees_payment','smgt_payment','smgt_holiday','smgt_report',
				'smgt_Migration','smgt_sms-setting','smgt_gnrl_settings','smgt_supportstaff','smgt_library','custom_field','smgt_access_right','smgt_hostel','smgt_view-attendance','smgt_email_template','smgt_show_infographic','smgt_notification','smgt_homework','smgt_virtual_classroom');
		return  $page_array;
	}
function mj_smgt_change_adminbar_css($hook) {
	$current_page = $_REQUEST['page'];
	$page_array = mj_smgt_call_script_page();
	if(in_array($current_page,$page_array))
    {	
     wp_enqueue_style( 'smgt-calender-css11', 'https://appsforoffice.microsoft.com/fabric/fabric-core/4.0.0/fabric.min.css');
	 
	wp_enqueue_style( 'smgt-calender-css', plugins_url( '/assets/css/fullcalendar.min.css', __FILE__) );
	wp_enqueue_style( 'smgt-datatable-min-css', plugins_url( '/assets/css/dataTables.min.css', __FILE__) );
	wp_enqueue_style( 'smgt-datatable-jq-css', plugins_url( '/assets/css/jquery.dataTables.min.css', __FILE__) );
	wp_enqueue_style( 'smgt-admin-style-css', plugins_url( '/admin/css/admin-style.css', __FILE__) );
	wp_enqueue_style( 'smgt-style-css', plugins_url( '/assets/css/style.css', __FILE__) );
	wp_enqueue_style( 'smgt-newversion', plugins_url( '/assets/css/newversion.css', __FILE__) );
	 	
	wp_enqueue_style( 'smgt-dashboard-css', plugins_url( '/assets/css/dashboard.css', __FILE__) );
	wp_enqueue_style( 'smgt-popup-css', plugins_url( '/assets/css/popup.css', __FILE__) );
	wp_enqueue_style( 'smgt-datable-responsive-css', plugins_url( '/assets/css/dataTables.responsive.css', __FILE__) );
	wp_enqueue_style( 'smgt-multiselect-css', plugins_url( '/assets/css/bootstrap-multiselect.css', __FILE__) );
	wp_enqueue_style( 'timepicker-min-css', plugins_url( '/assets/css/bootstrap-timepicker.min.css', __FILE__) );	
	
	wp_enqueue_script('smgt-defaultscript_ui', plugins_url( '/assets/js/jquery-ui.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
	wp_enqueue_script('smgt-timeago-js', plugins_url('/assets/js/jquery.timeago.js', __FILE__ ) );
	
	wp_enqueue_style( 'smgt-google-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans', false );
	
	wp_enqueue_script('smgt-calender_moment', plugins_url( '/assets/js/moment.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
	wp_enqueue_script('smgt-calender', plugins_url( '/assets/js/fullcalendar.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
	 
	/*--------Full calendar multilanguage---------*/
	$lancode=get_locale();
	$code=substr($lancode,0,2);
	wp_enqueue_script('smgt-calender-es', plugins_url( '/assets/js/calendar-lang/'.$code.'.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );	
	
	if(isset($_REQUEST['tab']))
	{
		if($_REQUEST['tab'] != 'view_all_message' && $_REQUEST['tab'] != 'view_all_message_reply')
		{		
			wp_enqueue_script('smgt-datatable-css', plugins_url( '/assets/js/datatables.min.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);
			wp_enqueue_script('smgt-datatable-jq', plugins_url( '/assets/js/jquery.dataTables.min.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);
		}
	}
    else
	{
		wp_enqueue_script('smgt-datatable', plugins_url( '/assets/js/jquery.dataTables.min.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);

	}
	wp_enqueue_script('smgt-datatable-button', plugins_url( '/assets/js/dataTables.buttons.min.js',__FILE__ ), array( 'jquery' ), '1.5.6', true);
	wp_enqueue_script('vfs_fonts', plugins_url( '/assets/js/vfs_fonts.js', __FILE__ ), array( 'jquery' ), '0.1.53', true );
	wp_enqueue_script('pdfmake-min', plugins_url( '/assets/js/pdfmake_min.js', __FILE__ ), array( 'jquery' ), '0.1.53', true );
	wp_enqueue_script('smgt-buttons-html5', plugins_url( '/assets/js/buttons.html5.min.js', __FILE__ ), array( 'jquery' ), '1.6.5', true );

	wp_enqueue_script('smgt-buttons-colVis-min', plugins_url( '/assets/js/buttons.colVis.min.js', __FILE__ ), array( 'jquery' ), '1.7.0', true );


	wp_enqueue_script('smgt-customjs', plugins_url( '/assets/js/smgt_custom.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
	wp_enqueue_script('smgt-icheckjs', plugins_url( '/assets/js/icheck.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
	wp_enqueue_script('smgt-popper-js',plugins_url( '/assets/js/popper.min.js', __FILE__ ));
	wp_enqueue_script('smgt-multiselect', plugins_url( '/assets/js/bootstrap-multiselect.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
	//Print and PDF
	wp_enqueue_script('smgt-dataTables-buttons-min', plugins_url( '/assets/js/smgt-dataTables-buttons-min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
	wp_enqueue_script('smgt-buttons-print-min', plugins_url( '/assets/js/smgt-buttons-print-min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );

	wp_enqueue_script('smgt-popup', plugins_url( '/assets/js/popup.js', __FILE__ ));
	wp_localize_script( 'smgt-popup', 'smgt', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_script('jquery');
	wp_enqueue_media();
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');     
	wp_enqueue_script('smgt-image-upload', plugins_url( '/assets/js/image-upload.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
	//image upload file alert msg languages translation				
	wp_localize_script('smgt-image-upload', 'language_translate1', array(
			'allow_file_alert' => esc_attr__( 'Only jpg,jpeg,png File allowed', 'school-mgt' ),	
		)
	);
	wp_localize_script('smgt-popup', 'language_translate2', array(
			'edit_record_alert' => esc_attr__( 'Are you sure want to edit this record?', 'school-mgt' ),					
			'category_alert' => esc_attr__('You must fill out the field', 'school-mgt' ),					
			'class_limit_alert' => esc_attr__( 'Class Limit Is Full.', 'school-mgt' ),						
			'enter_room_alert' => esc_attr__( 'Please Enter Room Category Name.', 'school-mgt' ),						
			'enter_value_alert' => esc_attr__( 'Please Enter Value.', 'school-mgt' ),						
			'delete_record_alert' => esc_attr__( 'Are you sure want to delete this record?', 'school-mgt' ),					
			'select_hall_alert' => esc_attr__( 'Please Select Exam Hall', 'school-mgt' ),				
			'one_record_alert' => esc_attr__( 'Please Checked Atleast One Student', 'school-mgt' ),	
			
            'one_record_select_alert' => esc_attr__( 'Please select atleast one record', 'school-mgt' ),
			'one_class_select_alert' => esc_attr__( 'Please select atleast one class', 'school-mgt' ),
			'lower_starting_year_alert' => esc_attr__( 'You can not select year lower then starting year', 'school-mgt' ),
			'do_delete_record' => esc_attr__( 'Do you really want to delete this ?', 'school-mgt' ),
			'select_one_book_alert' => esc_attr__( 'Please select atleast one book', 'school-mgt' ),
			'select_different_student_alert' => esc_attr__( 'Please Select Different Student', 'school-mgt' ),
			
			'same_email_alert' => esc_attr__( 'you have used the same email', 'school-mgt' ),
			
			'image_forame_alert' => esc_attr__( "Only '.jpeg','.jpg', '.png', '.bmp' formats are allowed.", "school-mgt" ),
			
			'more_then_exam_date_time' => esc_attr__( "Fail! More than one subject exam date & time same.", "school-mgt" ),
			
			'single_entry_alert' => esc_attr__( "There is only single entry,You can not remove it.", "school-mgt" ),
			
			'one_teacher_alert' => esc_attr__( "Please select atleast one teacher", "school-mgt" ),
			
			'one_message_alert' => esc_attr__( "Please select atleast one message", "school-mgt" ),
			
			'large_file_Size_alert' => esc_attr__( "Too large file Size. Only file smaller than 10MB can be uploaded.", "school-mgt" ),
			
			'pdf_alert' => esc_attr__( "Only pdf formate are allowed.", "school-mgt" ),
			
			'starting_year_alert' => esc_attr__( "You can not select year lower then starting year", "school-mgt" ),
			
			'one_user_replys_alert' => esc_attr__( "Please select atleast one users to replys", "school-mgt" ),
			
			'csv_alert' => esc_attr__( "Problems with user: we are going to skip", "school-mgt" ),
			'select_user' => esc_attr__( "Select Users", "school-mgt" ),
			'select_all' => esc_attr__( "Select all", "school-mgt" )
			
		)
	);
	wp_enqueue_style( 'smgt-bootstrap-css', plugins_url( '/assets/css/bootstrap5.min.css', __FILE__) );
	wp_enqueue_style( 'smgt-font-awesome-css', plugins_url( '/assets/css/font-awesome.css', __FILE__) );
	wp_enqueue_style( 'smgt-white-css', plugins_url( '/assets/css/white.css', __FILE__) );
	wp_enqueue_style( 'smgt-schoolmgt-min-css', plugins_url( '/assets/css/schoolmgt.min.css', __FILE__) );
	wp_enqueue_style( 'jq-ui-css-m', plugins_url( '/assets/css/jquery-ui.css', __FILE__) );

	if (is_rtl())
	{
		wp_enqueue_style( 'smgt-bootstrap-rtl-css', plugins_url( '/assets/css/bootstrap-rtl.min.css', __FILE__) );			
		wp_enqueue_style( 'smgt-custome-rtl-css', plugins_url( '/assets/css/custome_rtl.css', __FILE__) );			
	    wp_enqueue_script('smgt-validationEngine-en-js', plugins_url( '/assets/js/jquery.validationEngine-ar.js', __FILE__ ) );
	}
		 
	wp_enqueue_style( 'smgt-responsive-css', plugins_url( '/assets/css/school-responsive.css', __FILE__) );
	wp_enqueue_style( 'smgt-buttons-dataTables-min-css', plugins_url( '/assets/css/buttons.dataTables.min.css', __FILE__) );
	
	wp_enqueue_script('smgt-bootstrap-js', plugins_url( '/assets/js/bootstrap5.min.js', __FILE__ ) );
	wp_enqueue_script('smgt-school-js', plugins_url( '/assets/js/schooljs.js', __FILE__ ) );
	wp_enqueue_script('smgt-waypoints-js', plugins_url( '/assets/js/jquery.waypoints.min.js', __FILE__ ) );
	wp_enqueue_script('smgt-counterup-js', plugins_url( '/assets/js/jquery.counterup.min.js', __FILE__ ) );
	wp_enqueue_script('jquery-ui-datepicker');	
	//Vlidation style And Script
	//validation lib
		
	wp_enqueue_style( 'wcwm-validate-css', plugins_url( '/lib/validationEngine/css/validationEngine.jquery.css', __FILE__) );
																							
	wp_register_script( 'jquery-validationEngine-'.$code.'', plugins_url( '/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js', __FILE__), array( 'jquery' ) );
	wp_enqueue_script( 'jquery-validationEngine-'.$code.'' );
	wp_register_script( 'jquery-validationEngine', plugins_url( '/lib/validationEngine/js/jquery.validationEngine.js', __FILE__), array( 'jquery' ) );
	wp_enqueue_script( 'jquery-validationEngine' );	
	//------MULTIPLE SELECT ITEM JS -------------
	wp_enqueue_style( 'smgt-select2-css', plugins_url( '/lib/select2-3.5.3/select2.css', __FILE__) );					
	wp_enqueue_script('smgt-select2', plugins_url( '/lib/select2-3.5.3/select2.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
		//------END MULTIPLE SELECT ITEM JS------
		
		
	 	if(isset($_REQUEST['page']) && ($_REQUEST['page'] == 'report' || $_REQUEST['page'] == 'school'))
	 	{
	 		wp_enqueue_script('smgt-customjs', plugins_url( '/assets/js/Chart.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
	 	}
	 	wp_enqueue_script('smgt-custom_jobj', plugins_url( '/assets/js/smgt_custom_confilict_obj.js', __FILE__ ), array( 'jquery' ), '4.1.1', false );
	 }
	
	}
	if(isset($_REQUEST['page']))
	add_action( 'admin_enqueue_scripts', 'mj_smgt_change_adminbar_css' );
}

function mj_smgt_upload_image() {
    global $pagenow;
	if(isset($_REQUEST['page']))
	{
	   if ($_REQUEST['page'] == 'smgt_school') {
	        // Now we'll replace the 'Insert into Post Button' inside Thickbox
	        add_filter( 'gettext', 'mj_smgt_replace_thickbox_text'  , 1, 3 );
	    }
	}
}
add_action( 'admin_init', 'mj_smgt_upload_image' );
 
function mj_smgt_replace_thickbox_text($translated_text, $text, $domain) {
    if ('Insert into Post' == $text) {
        $referer = strpos( wp_get_referer(), 'wptuts-settings' );
        if ( $referer != '' ) {
            return esc_attr__('Upload Image','school-mgt');
        }
    }
    return $translated_text;
}
function mj_smgt_domain_load(){
	load_plugin_textdomain( 'school-mgt', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );
}
add_action( 'plugins_loaded', 'mj_smgt_domain_load' );
function mj_smgt_install_login_page() 
{

	if ( !get_option('smgt_login_page') ) 
	{
		$curr_page = array(
			'post_title' => esc_attr__('School Management Login Page', 'school-mgt'),
			'post_content' => '[smgt_login]',
			'post_status' => 'publish',
			'post_type' => 'page',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_category' => array(1),
			'post_parent' => 0 );		

		$curr_created = wp_insert_post( $curr_page );
		update_option( 'smgt_login_page', $curr_created );
	}
}
function mj_smgt_install_student_registration_page() 
{
	if ( !get_option('mj_smgt_install_student_registration_page') ) 
	{
		$curr_page = array(
			'post_title' => esc_attr__('Student Registration', 'school-mgt'),
			'post_content' => '[smgt_student_registration]',
			'post_status' => 'publish',
			'post_type' => 'page',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_category' => array(1),
			'post_parent' => 0 );		

		$curr_created = wp_insert_post( $curr_page );
		update_option( 'mj_smgt_install_student_registration_page', $curr_created );		
	}
}

function mj_smgt_user_dashboard()
{	
	if(isset($_REQUEST['dashboard']))
	{		
		require_once SMS_PLUGIN_DIR. '/fronted_template.php';
		exit;
	}
	if(isset($_REQUEST['smgt_login']))
	{
		add_action( 'authenticate', 'mj_smgt_pu_blank_login');
	}
}

function mj_smgt_remove_all_theme_styles()
{
	global $wp_styles;
	$wp_styles->queue = array();
}
if(isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'user')
{
	add_action('wp_print_styles', 'mj_smgt_remove_all_theme_styles', 100);
}

function mj_smgt_load_script1()
{
	if(isset($_REQUEST['dashboard']) && $_REQUEST['dashboard'] == 'user')
	{
		wp_register_script('smgt-popup-front', plugins_url( 'assets/js/popup.js', __FILE__ ), array( 'jquery' ));
		wp_enqueue_script('smgt-popup-front');
		wp_localize_script( 'smgt-popup-front', 'smgt', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script('smgt-popup-front', 'language_translate2', array(
			'edit_record_alert' => esc_attr__( 'Are you sure want to edit this record?', 'school-mgt'),	
			'category_alert' => esc_attr__('You must fill out the field!', 'school-mgt' ),			
			'class_limit_alert' => esc_attr__( 'Class Limit Is Full.', 'school-mgt'),						
			'enter_room_alert' => esc_attr__( 'Please Enter Room Category Name.', 'school-mgt'),						
			'enter_value_alert' => esc_attr__( 'Please Enter Value.', 'school-mgt'),						
			'delete_record_alert' => esc_attr__( 'Are you sure want to delete this record?', 'school-mgt'),					
			'select_hall_alert' => esc_attr__( 'Please Select Exam Hall', 'school-mgt'),				
			'one_record_alert' => esc_attr__( 'Please Checked Atleast One Student', 'school-mgt'),
			
			//New Updated alert message//
            'one_record_select_alert' => esc_attr__( 'Please select atleast one record', 'school-mgt' ),
			'one_class_select_alert' => esc_attr__( 'Please select atleast one class', 'school-mgt' ),
			'lower_starting_year_alert' => esc_attr__( 'You can not select year lower then starting year', 'school-mgt' ),
			'do_delete_record' => esc_attr__( 'Do you really want to delete this ?', 'school-mgt' ),
			'select_one_book_alert' => esc_attr__( 'Please select atleast one book', 'school-mgt' ),
			'select_different_student_alert' => esc_attr__( 'Please Select Different Student', 'school-mgt' ),
			
			'same_email_alert' => esc_attr__( 'you have used the same email', 'school-mgt' ),
			
			'image_forame_alert' => esc_attr__( "Only '.jpeg','.jpg', '.png', '.bmp' formats are allowed.", "school-mgt" ),
			
			'more_then_exam_date_time' => esc_attr__( "Fail! More than one subject exam date & time same.", "school-mgt" ),
			
			'single_entry_alert' => esc_attr__( "There is only single entry,You can not remove it.", "school-mgt" ),
			
			'one_teacher_alert' => esc_attr__( "Please select atleast one teacher", "school-mgt" ),
			
			'one_message_alert' => esc_attr__( "Please select atleast one message", "school-mgt" ),
			
			'large_file_Size_alert' => esc_attr__( "Too large file Size. Only file smaller than 10MB can be uploaded.", "school-mgt" ),
			
			'pdf_alert' => esc_attr__( "Only pdf formate are allowed.", "school-mgt" ),
			
			'starting_year_alert' => esc_attr__( "You can not select year lower then starting year", "school-mgt" ),
			
			'one_user_replys_alert' => esc_attr__( "Please select atleast one users to replys", "school-mgt" ),
			
			'csv_alert' => esc_attr__( "Problems with user: we are going to skip", "school-mgt" )			
		)
	);
		wp_enqueue_script('jquery');	
	}
}
function mj_smgt_registration_form( $class_name,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$city_name,$state_name,$zip_code,$mobile_number,$alternet_mobile_number,$phone,$email,$username,$password,$smgt_user_avatar) 
{
	 wp_enqueue_script('smgt-defaultscript', plugins_url( '/assets/js/jquery-1.11.1.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );		
	$lancode=get_locale();
	
	$code=substr($lancode,0,2);		

	wp_enqueue_style( 'wcwm-validate-css', plugins_url( '/lib/validationEngine/css/validationEngine.jquery.css', __FILE__) );
	wp_register_script( 'jquery-1.8.2', plugins_url( '/lib/validationEngine/js/jquery-1.8.2.min.js', __FILE__), array( 'jquery' ) );
	wp_enqueue_script( 'jquery-1.8.2' );
	wp_register_script( 'jquery-validationEngine-en', plugins_url( '/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js', __FILE__), array( 'jquery' ) );
	wp_enqueue_script( 'jquery-validationEngine-'.$code.'' );
	wp_register_script( 'jquery-validationEngine', plugins_url( '/lib/validationEngine/js/jquery.validationEngine.js', __FILE__), array( 'jquery' ) );
	wp_enqueue_script( 'jquery-validationEngine' );
	wp_enqueue_script('icheck.min-js', plugins_url( '/assets/js/icheck.min.js', __FILE__ ) );
	wp_enqueue_script('jquery-ui-datepicker');
	// wp_enqueue_style( 'accordian-jquery-ui-css', plugins_url( '/assets/accordian/jquery-ui.css', __FILE__) );
	wp_enqueue_script('smgt-custom_jobj', plugins_url( '/assets/js/smgt_custom_confilict_obj.js', __FILE__ ), array( 'jquery' ), '4.1.1', false );
	 wp_enqueue_style( 'smgt-style-css', plugins_url( '/assets/css/style.css', __FILE__) );
	 wp_enqueue_style( 'smgt-responsive-css', plugins_url( '/assets/css/school-responsive.css', __FILE__) );
	if (is_rtl())
	{	
		wp_register_script( 'jquery-validationEngine-en', plugins_url( '/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js', __FILE__), array( 'jquery' ) );
		wp_enqueue_script('smgt-validationEngine-en-js', plugins_url( '/assets/js/jquery.validationEngine-ar.js', __FILE__ ) );
		wp_enqueue_style( 'css-custome_rtl-css', plugins_url( '/assets/css/custome_rtl.css', __FILE__) );
	}
	?>
	<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/settings/register.css'; ?>">

<!-- <script type="text/javascript" src="<?php echo esc_url( plugins_url() . '/school-management/assets/accordian/jquery-ui.js' ); ?>"></script>	 -->
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>
 <script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/settings/register.js'; ?>"></script> 

 <?php   
 $edit = 0;
 
 echo '
	<div class="student_registraion_form">
    <form id="registration_form" action="' . $_SERVER['REQUEST_URI'] . '" method="post" enctype="multipart/form-data">
	<div class="form-group">
		<label class="col-sm-2 control-label" for="class_name">'. esc_attr__('Class','school-mgt').'<span class="require-field">*</span></label>
		<div class="col-sm-8">				
			<select name="class_name" class="form-control validate[required]" id="class_name">
				<option value="">'. esc_attr__('Select Class','school-mgt').'</option>';
					$classval = $class_name;
					foreach(mj_smgt_get_allclass() as $classdata)
					{  
					?>
					 <option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
				<?php }?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-xs-12 col-sm-2 control-label dob_label_res" for="first_name"><?php esc_attr_e('First Name','school-mgt');?><span class="require-field">*</span></label>
		<div class="col-xs-12 col-sm-8">
			<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label dob_label_res" for="middle_name"><?php esc_attr_e('Middle Name','school-mgt');?></label>
		<div class="col-sm-8">
			<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
		</div>
	</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="last_name"><?php esc_attr_e('Last Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="gender"><?php esc_attr_e('Gender','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8 ">
			<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?> 
				<label class="radio-inline gender">
			     <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_attr_e('Male','school-mgt');?>
			     </label> 
			    <label class="radio-inline gender">
			      <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_attr_e('Female','school-mgt');?> 
			    </label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label dob_label_res" for="birth_date"><?php esc_attr_e('Date of birth','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="birth_date" class="form-control validate[required]" type="text"  name="birth_date" 
				value="<?php if($edit){ echo $user_info->birth_date;}elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>" readonly>
			</div>
		</div>		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="address"><?php esc_attr_e('Address','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text"  name="address" 
				value="<?php if($edit){ echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="city_name"><?php esc_attr_e('City','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
				value="<?php if($edit){ echo $user_info->city;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="state_name"><?php esc_attr_e('State','school-mgt');?></label>
			<div class="col-sm-8">
				<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" 
				value="<?php if($edit){ echo $user_info->state;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="zip_code"><?php esc_attr_e('Zip Code','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" maxlength="15" type="text"  name="zip_code" 
				value="<?php if($edit){ echo $user_info->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label dob_label_res" for="mobile_number"><?php esc_attr_e('Mobile Number','school-mgt');?></label>
			<div class="col-sm-1 pe-0" >
			<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control padding_10px country_code_res" name="phonecode">
			</div>
			<div class="col-sm-7">
				<input id="mobile_number" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="mobile_number" maxlength="10"
				value="<?php if($edit){ echo $user_info->mobile_number;}elseif(isset($_POST['mobile_number'])) echo $_POST['mobile_number'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label dob_label_res Alternate_res" for="mobile_number"><?php esc_attr_e('Alternate Number','school-mgt');?></label>
			<div class="col-sm-1 pe-0">
			<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control padding_10px country_code_res" name="alter_mobile_number">
			</div>
			<div class="col-sm-7">
				<input id="alternet_mobile_number" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="alternet_mobile_number" maxlength="10"
				value="<?php if($edit){ echo $user_info->alternet_mobile_number;}elseif(isset($_POST['alternet_mobile_number'])) echo $_POST['alternet_mobile_number'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="phone"><?php esc_attr_e('Phone','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="phone" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="phone" 
				value="<?php if($edit){ echo $user_info->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="email"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
				value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label dob_label_res" for="username"><?php esc_attr_e('User Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="username" class="form-control validate[required,custom[username_validation]]" maxlength="50" type="text"  name="username" 
				value="<?php if($edit){ echo $user_info->user_login;}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?>>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="password"><?php esc_attr_e('Password','school-mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
			<div class="col-sm-8">
				<input id="password" class="form-control <?php if(!$edit){ echo 'validate[required,minSize[8],maxSize[12]]'; }else{ echo 'validate[minSize[8],maxSize[12]]'; } ?>" type="password"  name="password" value="">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="photo"><?php esc_attr_e('Image','school-mgt');?></label>
			<div class="col-sm-8">				
				<input type="file"  class="form-control" onchange="fileCheck(this);" name="smgt_user_avatar">				
			</div>		
		</div>
		
		<?php
		//Get Module Wise Custom Field Data
		$custom_field_obj =new Smgt_custome_field;
		
		$module='student';	
		 
		$compact_custom_field=$custom_field_obj->mj_smgt_getCustomFieldByModule($module);
		
		if(!empty($compact_custom_field))
		{	
			?>		
			<div class="header">
				<h3><?php esc_html_e('Custom Fields','school-mgt');?></h3>
			</div>						
			 
					<?php
					foreach($compact_custom_field as $custom_field)
					{
						if($edit)
						{
							$custom_field_id=$custom_field->id;
							
							$module_record_id=$_REQUEST['student_id'];
							 
							$custom_field_value=$custom_field_obj->mj_smgt_get_single_custom_field_meta_value($module,$module_record_id,$custom_field_id);
						}
						
						// Custom Field Validation // 
						$exa = explode('|',$custom_field->field_validation);
						$min = "";
						$max = "";
						$required = "";
						$red = "";
						$limit_value_min = "";
						$limit_value_max = "";
						$numeric = "";
						$alpha = "";
						$space_validation = "";
						$alpha_space = "";
						$alpha_num = "";
						$email = "";
						$url = "";
						$minDate="";
						$maxDate="";
						$file_types="";
						$file_size="";
						$datepicker_class="";
						foreach($exa as $key=>$value)
						{
							if (strpos($value, 'min') !== false)
							{
							   $min = $value;
							   $limit_value_min = substr($min,4);
							}
							elseif(strpos($value, 'max') !== false)
							{
							   $max = $value;
							   $limit_value_max = substr($max,4);
							}
							elseif(strpos($value, 'required') !== false)
							{
								$required="required";
								$red="*";
							}
							elseif(strpos($value, 'numeric') !== false)
							{
								$numeric="number";
							}
							elseif($value == 'alpha')
							{
								$alpha="onlyLetterSp";
								$space_validation="space_validation";
							}
							elseif($value == 'alpha_space')
							{
								$alpha_space="onlyLetterSp";
							}
							elseif(strpos($value, 'alpha_num') !== false)
							{
								$alpha_num="onlyLetterNumber";
							}
							elseif(strpos($value, 'email') !== false)
							{
								$email = "email";
							}
							elseif(strpos($value, 'url') !== false)
							{
								$url="url";
							}
							elseif(strpos($value, 'after_or_equal:today') !== false )
							{
								$minDate=1;
								$datepicker_class='after_or_equal';
							}
							elseif(strpos($value, 'date_equals:today') !== false )
							{
								$minDate=$maxDate=1;
								$datepicker_class='date_equals';
							}
							elseif(strpos($value, 'before_or_equal:today') !== false)
							{	
								$maxDate=1;
								$datepicker_class='before_or_equal';
							}	
							elseif(strpos($value, 'file_types') !== false)
							{	
								$types = $value;													
							   
								$file_types=substr($types,11);
							}
							elseif(strpos($value, 'file_upload_size') !== false)
							{	
								$size = $value;
								$file_size=substr($size,17);
							}
						}
						$option =$custom_field_obj->mj_smgt_getDropDownValue($custom_field->id);
						$data = 'custom.'.$custom_field->id;
						$datas = 'custom.'.$custom_field->id;											
						
						if($custom_field->field_type =='text')
						{
							?>	
							 
							<div class="form-group">	
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label dob_label_res" for="<?php echo $custom_field->id; ?>"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 has-feedback">
									<input class="form-control hideattar<?php echo $custom_field->form_name; ?> validate[<?php if(!empty($required)){ echo $required; ?>,<?php } ?><?php if(!empty($limit_value_min)){ ?> minSize[<?php echo $limit_value_min; ?>],<?php } if(!empty($limit_value_max)){ ?> maxSize[<?php echo $limit_value_max; ?>],<?php } if($numeric != '' || $alpha != '' || $alpha_space != '' || $alpha_num != '' || $email != '' || $url != ''){ ?> custom[<?php echo $numeric; echo $alpha; echo $alpha_space; echo $alpha_num; echo $email; echo $url; ?>]<?php } ?>] <?php echo $space_validation; ?>" type="text" name="custom[<?php echo $custom_field->id; ?>]" id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>" <?php if($edit){ ?> value="<?php echo $custom_field_value; ?>" <?php } ?>>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-0">
								</div>
							</div>
								 
							<?php
						}
						elseif($custom_field->field_type =='textarea')
						{
							?>
							<div class="form-group">	
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label "><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 has-feedback">
									<textarea rows="3"
										class="form-control hideattar<?php echo $custom_field->form_name; ?> validate[<?php if(!empty($required)){ echo $required; ?>,<?php } ?><?php if(!empty($limit_value_min)){ ?> minSize[<?php echo $limit_value_min; ?>],<?php } if(!empty($limit_value_max)){ ?> maxSize[<?php echo $limit_value_max; ?>],<?php } if($numeric != '' || $alpha != '' || $alpha_space != '' || $alpha_num != '' || $email != '' || $url != ''){ ?> custom[<?php echo $numeric; echo $alpha; echo $alpha_space; echo $alpha_num; echo $email; echo $url; ?>]<?php } ?>] <?php echo $space_validation; ?>" 
										name="custom[<?php echo $custom_field->id; ?>]" 
										id="<?php echo $custom_field->id; ?>"
										label="<?php echo $custom_field->field_label; ?>"
										><?php if($edit){ echo $custom_field_value; } ?></textarea>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-0">
								</div>
							</div>
							<?php 
						}
						elseif($custom_field->field_type =='date')
						{
							?>	
							<div class="form-group">
								 <label for="bdate" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
							 
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 has-feedback">
									<input  class=" ln-h-35-px form-control error custom_datepicker <?php echo $datepicker_class; ?> hideattar<?php echo $custom_field->form_name; ?> <?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>"name="custom[<?php echo $custom_field->id; ?>]"<?php if($edit){ ?> value="<?php echo $custom_field_value; ?>" <?php } ?>id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>">
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-0">
								</div>
							</div>
								
							<?php 
						}
						elseif($custom_field->field_type =='dropdown')
						{
							?>	
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label" for="<?php echo $custom_field->id; ?>"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
								  
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 has-feedback">
									<select class="form-control hideattar<?php echo $custom_field->form_name; ?> 
									<?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>" name="custom[<?php echo $custom_field->id; ?>]"	id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>"
									>
									<option value=""> <?php esc_attr_e('Select','school-mgt');?></option>
										<?php
										if(!empty($option))
										{															
											foreach ($option as $options)
											{
												?>
												<option value="<?php echo $options->option_label; ?>" <?php if($edit){ echo selected($custom_field_value,$options->option_label); } ?>> <?php echo $options->option_label; ?></option>
												<?php
											}
										}
										?>
									</select>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-0">
								</div>
							</div>
							 
							<?php 
						}
						elseif($custom_field->field_type =='checkbox')
						{
							?>	
								<div class="form-group">
									<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
								 
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 has-feedback">
										<?php
										if(!empty($option))
										{
											foreach ($option as $options)
											{ 
												if($edit)
												{
													$custom_field_value_array=explode(',',$custom_field_value);
												}
												?>	
												<div class="mr-1 d-inline-block custom-control custom-checkbox">
													<input type="checkbox" value="<?php echo $options->option_label; ?>"  <?php if($edit){  echo checked(in_array($options->option_label,$custom_field_value_array)); } ?> class="custom-control-input hideattar<?php echo $custom_field->form_name; ?>" name="custom[<?php echo $custom_field->id; ?>][]" >
													<label class="custom-control-label" for="colorCheck1"><?php echo $options->option_label; ?></label>
												</div>
												<?php
											}
										}
										?>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-0">
									</div>
								</div>
							<?php 
						}
						elseif($custom_field->field_type =='radio')
						{
							?>
							
							<div class="form-group">
									<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
									
									 
								 
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 has-feedback">
										<?php
										if(!empty($option))
										{
											foreach ($option as $options)
											{
												?>
												<input type="radio"  value="<?php echo $options->option_label; ?>" <?php if($edit){ echo checked( $options->option_label, $custom_field_value); } ?> name="custom[<?php echo $custom_field->id; ?>]"  class="custom-control-input hideattar<?php echo $custom_field->form_name; ?> error " id="<?php echo $options->option_label; ?>">
												
												<label class="mr-1 custom-control-label" for="<?php echo $options->option_label; ?>"><?php echo $options->option_label; ?></label>
												<?php
											}
										}
										?>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-0">
									</div>
								</div>
							<?php
						}
						elseif($custom_field->field_type =='file')
						{
							?>	
							<div class="form-group">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
								 
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
									<input type="file"  onchange="mj_smgt_custom_filed_fileCheck(this);" Class="hideattar<?php echo $custom_field->form_name; if($edit){ if(!empty($required)){ if($custom_field_value==''){ ?> validate[<?php echo $required; ?>] <?php } } }else{ if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } } ?>" name="custom_file[<?php echo $custom_field->id;?>]" <?php if($edit){ ?> value="<?php echo $custom_field_value; ?>" <?php } ?> id="<?php echo $custom_field->id; ?>" file_types="<?php echo $file_types; ?>" file_size="<?php echo $file_size; ?>">
									<p><?php esc_html_e('Please upload only ','school-mgt'); echo $file_types; esc_html_e(' file','school-mgt');?> </p>
								</div>
									<input type="hidden" name="hidden_custom_file[<?php echo $custom_field->id; ?>]" value="<?php if($edit){ echo $custom_field_value; } ?>">
									<label class="label_file"><?php if($edit){ echo $custom_field_value; } ?></label>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-0">
								</div>
							</div>
						<?php
						}
					}	
					?>	 
		<?php
		}
		?>
		<?php wp_nonce_field( 'save_student_frontend_shortcode_nonce' ); ?>
		
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php esc_attr_e('Registration','school-mgt');?>" name="save_student_front" class="btn btn-success"/>
        </div>
    </form>
	</div>
    <?php
}
function mj_smgt_complete_registration($class_name,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$city_name,$state_name,$zip_code,$mobile_number,$alternet_mobile_number,$phone,$email,$username,$password,$smgt_user_avatar,$wp_nonce) {
    global $reg_errors;
	$custom_field_obj =new Smgt_custome_field;
	if ( wp_verify_nonce( $wp_nonce, 'save_student_frontend_shortcode_nonce' ) )
	{
		if ( 1 > count( $reg_errors->get_error_messages() ) ) {
					
			$userdata = array(
				'user_login'    =>   $username,
				'user_email'    =>   $email,
				'user_pass'     =>   $password,
				'user_url'      =>   NULL,
				'first_name'    =>   $first_name,
				'last_name'     =>   $last_name,
				'nickname'      =>   NULL        
			);		
			$user_id = wp_insert_user( $userdata );		
			
		// Custom Field File Insert //
		$custom_field_file_array=array();
		if(!empty($_FILES['custom_file']['name']))
		{
			$count_array=count($_FILES['custom_file']['name']);
			
			for($a=0;$a<$count_array;$a++)
			{			
				foreach($_FILES['custom_file'] as $image_key=>$image_val)
				{
					foreach($image_val as $image_key1=>$image_val2)
					{
						if($_FILES['custom_file']['name'][$image_key1]!='')
						{  	
							$custom_file_array[$image_key1]=array(
							'name'=>$_FILES['custom_file']['name'][$image_key1],
							'type'=>$_FILES['custom_file']['type'][$image_key1],
							'tmp_name'=>$_FILES['custom_file']['tmp_name'][$image_key1],
							'error'=>$_FILES['custom_file']['error'][$image_key1],
							'size'=>$_FILES['custom_file']['size'][$image_key1]
							);							
						}	
					}
				}
			}			
			if(!empty($custom_file_array))
			{
				foreach($custom_file_array as $key=>$value)		
				{	
					global $wpdb;
					$wpnc_custom_field_metas = $wpdb->prefix . 'custom_field_metas';
	
					$get_file_name=$custom_file_array[$key]['name'];	
					
					$custom_field_file_value=mj_smgt_load_documets_new($value,$value,$get_file_name);		
					
					//Add File in Custom Field Meta//
					$custom_meta_data['module']='student';
					$custom_meta_data['module_record_id']=$user_id;
					$custom_meta_data['custom_fields_id']=$key;
					$custom_meta_data['field_value']=$custom_field_file_value;
					$custom_meta_data['created_at']=date("Y-m-d H:i:s");
					$custom_meta_data['updated_at']=date("Y-m-d H:i:s");	
					 
					$insert_custom_meta_data=$wpdb->insert($wpnc_custom_field_metas, $custom_meta_data );		
				} 	
			}		 		
		}
		$add_custom_field=$custom_field_obj->mj_smgt_add_custom_field_metas('student',$_POST['custom'],$user_id);		
		$user = new WP_User($user_id);
		  $user->set_role('student');
		  $smgt_avatar = '';
		if($_FILES['smgt_user_avatar']['size'] > 0)
		{
			 $smgt_avatar_image = mj_smgt_user_avatar_image_upload('smgt_user_avatar');
			 $smgt_avatar = content_url().'/uploads/school_assets/'.$smgt_avatar_image;
		}
		else {
			$smgt_avatar = '';
		}
		$usermetadata=array(
			'roll_id' => '',						
			'middle_name'=>$middle_name,
			'gender'=>$gender,
			'birth_date'=>$birth_date,
			'address'=>$address,
			'city'=>$city_name,
			'state'=>$state_name,
			'zip_code'=>$zip_code,
			'class_name'=>$class_name,
			'phone'=>$phone,
			'mobile_number'=>$mobile_number,
			'alternet_mobile_number'=>$alternet_mobile_number,
			'smgt_user_avatar'=>$smgt_avatar );
			//var_dump($usermetadata);
			foreach($usermetadata as $key=>$val)
			{		
				$result=update_user_meta( $user_id, $key,$val );	
			}
			
			if(get_option('student_approval') == '1')
			{
				$hash = md5( rand(0,1000) );
				$result123=update_user_meta( $user_id, 'hash', $hash );
			}
			$class_name=get_user_meta($user_id,'class_name',true);
			$user_info = get_userdata($user_id);
			$to = $user_info->user_email;   
			if(get_option('student_approval') == '1')
			{			
				$subject = get_option('registration_title'); 
				$search=array('{{student_name}}','{{user_name}}','{{class_name}}','{{email}}','{{school_name}}');
				$replace = array($user_info->display_name,$user_info->user_login,mj_smgt_get_class_name($class_name),$to,get_option( 'smgt_school_name' ));
				$message = str_replace($search, $replace,get_option('registration_mailtemplate'));
			}
			else
			{
				$roll_no =rand(0,100000);
				$result_roll=update_user_meta( $user_id, 'roll_id', $roll_no );
				$student_name=$user_info->display_name;
				$user_name=$user_info->user_login;
				$class_name1=mj_smgt_get_class_name($class_name);
				$school_name=get_option( 'smgt_school_name' );
				$subject ="Student Registration"; 
				$message ="Hello $student_name ,

Your registration has been successful with $school_name. You can access student account using your login details. Your other details are given bellow. 

User Name : $user_name
Class Name : $class_name1
Email : $to


Regards From $school_name.";
			}
				
			if($result){
				if(get_option('smgt_mail_notification') == '1')
				{
					if(get_option('student_approval') == '1')
					{			
						wp_mail($to, $subject, $message); 
						
						esc_attr_e('Registration complete.Your account active after admin can approve.','school-mgt'); 
					}
					else
					{
						wp_mail($to, $subject, $message); 
						
						//----------- STUDENT ASSIGNED TEACHER MAIL ------------//
						$TeacherIDs = mj_smgt_check_class_exits_in_teacher_class($class_name);			
						$TeacherEmail = array();
						$string['{{school_name}}']  = get_option('smgt_school_name');
						$string['{{student_name}}'] =  $user_info->display_name;
						$subject = get_option('student_assign_teacher_mail_subject');
						$MessageContent = get_option('student_assign_teacher_mail_content');			
						foreach($TeacherIDs as $teacher)
						{		
							$TeacherData = get_userdata($teacher);		
							//$TeacherData->user_email;
							$string['{{teacher_name}}']= mj_smgt_get_display_name($TeacherData->ID);
							$message = mj_smgt_string_replacement($string,$MessageContent);				
							mj_smgt_send_mail($TeacherData->user_email,$subject,$message);
						}
						?>
						<span class="admission_successfully_message"> 
						<?php 
						esc_attr_e('Registration complete.You can access student account using your login details.','school-mgt');
  						?>
						</span>
						<?php 
					}
				}
				
				return $user_id;
			}
		}
    }
	else
	{
		die( 'Security check' );
	}
}
function mj_smgt_registration_validation($class_name,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$city_name,$state_name,$zip_code,$mobile_number,$alternet_mobile_number,$phone,$email,$username,$password,$smgt_user_avatar )  
{
	global $reg_errors;
	$reg_errors = new WP_Error;
	if ( empty( $class_name )  || empty( $first_name ) || empty( $last_name ) || empty( $birth_date ) || empty( $address ) || empty( $city_name ) || empty( $zip_code ) || empty( $phone ) || empty( $email ) || empty( $username ) || empty( $password ) ) 
	{
    $reg_errors->add('field', 'Required form field is missing' );
	}
	if ( 4 > strlen( $username ) ) {
    $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
	}
	if ( username_exists( $username ) )
		$reg_errors->add('user_name', 'Sorry, that username already exists!');
	if ( ! validate_username( $username ) ) {
    $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
	}
	
	if ( !is_email( $email ) ) {
    $reg_errors->add( 'email_invalid', 'Email is not valid' );
	}
	if ( email_exists( $email ) ) {
    $reg_errors->add( 'email', 'Email Already in use' );
	}
	
	if ( is_wp_error( $reg_errors ) ) {
 
    foreach ( $reg_errors->get_error_messages() as $error ) 
	{
        echo '<div class="student_reg_error">';
        echo '<strong> ' . esc_attr__("ERROR","school-mgt"). '</strong> : ';
        echo '<span class="error"> '. esc_attr__("$error","school-mgt"). ' </span><br/>';
        echo '</div>';
    }
 
}	

}
function smgt_student_registration_function(){
	   global $class_name,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$city_name,$state_name,$zip_code,$mobile_number,$alternet_mobile_number,$phone,$email,$username,$password,$smgt_user_avatar;
	    $class_name = isset($_POST['class_name'])?$_POST['class_name']:'';
	   
    if ( isset($_POST['save_student_front'] ) ) {
        mj_smgt_registration_validation(
		$_POST['class_name'],		
		$_POST['first_name'],
		$_POST['middle_name'],
		$_POST['last_name'],
		$_POST['gender'],
		$_POST['birth_date'],
		$_POST['address'],
		$_POST['city_name'],
		$_POST['state_name'],
		$_POST['zip_code'],
		$_POST['mobile_number'],
		$_POST['alternet_mobile_number'],
		$_POST['phone'],
		$_POST['email'],
        mj_smgt_strip_tags_and_stripslashes($_POST['username']),
        $_POST['password'],        
        isset($_FILE['smgt_user_avatar'])        
    );
         
		 
        // sanitize user form input
        global $class_name,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$city_name,$state_name,$zip_code,$mobile_number,$alternet_mobile_number,$phone,$email,$username,$password,$smgt_user_avatar;
        if(isset($_POST['class_name'])){ $class_name =$_POST['class_name']; } else { echo $class_name =""; } 
		$first_name =    mj_smgt_strip_tags_and_stripslashes($_POST['first_name']);
		$middle_name =   mj_smgt_strip_tags_and_stripslashes($_POST['middle_name']);
		$last_name =  mj_smgt_strip_tags_and_stripslashes($_POST['last_name']);
		$gender =   mj_smgt_strip_tags_and_stripslashes($_POST['gender']);
		$birth_date =   mj_smgt_strip_tags_and_stripslashes($_POST['birth_date']);
		$address =   mj_smgt_strip_tags_and_stripslashes($_POST['address']);
		$city_name =    mj_smgt_strip_tags_and_stripslashes($_POST['city_name']);
		$state_name =   mj_smgt_strip_tags_and_stripslashes($_POST['state_name']);
		$zip_code =   mj_smgt_strip_tags_and_stripslashes($_POST['zip_code']);
		$mobile_number =   mj_smgt_strip_tags_and_stripslashes($_POST['mobile_number']);
		$alternet_mobile_number =  mj_smgt_strip_tags_and_stripslashes($_POST['alternet_mobile_number']) ;
		$phone =   mj_smgt_strip_tags_and_stripslashes($_POST['phone']);		
		$username   =    mj_smgt_strip_tags_and_stripslashes($_POST['username']);
        $password   =    strip_tags($_POST['password']);
        $email      =    $_POST['email'];
        $wp_nonce     =   $_POST['_wpnonce'];
        
 
        // call @function complete_registration to create the user
        // only when no WP_error is found
		mj_smgt_complete_registration(
        $class_name,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$city_name,$state_name,$zip_code,$mobile_number,$alternet_mobile_number,$phone,$email,$username,$password,$smgt_user_avatar,$wp_nonce
    );
   
	 }
    mj_smgt_registration_form(
       $class_name,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$city_name,$state_name,$zip_code,$mobile_number,$alternet_mobile_number,$phone,$email,$username,$password,$smgt_user_avatar
    );
}
function mj_smgt_activat_mail_link()
{
	if(isset($_REQUEST['haskey']) && isset($_REQUEST['id']))
	{		
	
		global $wpdb;
		$table_users=$wpdb->prefix.'users';
		$user = get_userdatabylogin($_REQUEST['id']);
		$user_id =  $user->ID; // prints the id of the user
		if( get_user_meta($user_id, 'hash', true))
		{
		
			if(get_user_meta($user_id, 'hash', true) == $_REQUEST['haskey'])
			{
				delete_user_meta($user_id, 'hash');
				$curr_args = array(
			'page_id' => get_option('smgt_login_page'),
			'smgt_activate' => 1
	);
	//print_r($curr_args);
	$referrer_faild = add_query_arg( $curr_args, get_permalink( get_option('smgt_login_page') ) );
				wp_redirect($referrer_faild);
				exit;
			}
			else
			{
				$curr_args = array(
			'page_id' => get_option('smgt_login_page'),
			'smgt_activate' => 2
	);
	//print_r($curr_args);
	$referrer_faild = add_query_arg( $curr_args, get_permalink( get_option('smgt_login_page') ) );
				wp_redirect($referrer_faild);
				exit;
			}
			
			
		}
		wp_redirect(home_url('/'));
				exit;
		
			
		
	}
}
//add user authenticate filter
add_filter('wp_authenticate_user', function($user)
{
$havemeta = get_user_meta($user->ID, 'hash', true);
if($havemeta)
{
	$WP_Error = new WP_Error();
	$referrer = $_SERVER['HTTP_REFERER'];
	$curr_args = array(
			'page_id' => get_option('smgt_login_page'),
			'smgt_activate' => 'smgt_activate'
	);
	$referrer_faild = add_query_arg( $curr_args, get_permalink( get_option('smgt_login_page') ) );
	wp_redirect( $referrer_faild );
	exit();
}
return $user;
}, 10, 2);

add_action('wp_enqueue_scripts','mj_smgt_load_script1');
add_action('init','mj_smgt_install_login_page');
add_action('init','mj_smgt_install_student_registration_page');
add_action('init','mj_smgt_install_student_admission_page');
add_action('wp_head','mj_smgt_user_dashboard');
add_shortcode( 'smgt_login','mj_smgt_login_link' );
add_action('init','mj_smgt_output_ob_start');
// Register a new shortcode: [cr_custom_registration]
add_shortcode( 'smgt_student_registration', 'mj_smgt_custom_registration_shortcode' );
add_shortcode( 'smgt_student_admission', 'mj_smgt_custom_admission_shortcode' );
// The callback function that will replace [book]
function mj_smgt_custom_registration_shortcode() {
    ob_start();
    smgt_student_registration_function();
    return ob_get_clean();
}
function mj_smgt_custom_admission_shortcode() {
    ob_start();
    smgt_student_admisiion_function();
    return ob_get_clean();
}
function mj_smgt_output_ob_start()
{
	ob_start();
}

add_action('init','mj_smgt_generate_pdf');
function mj_smgt_generate_pdf()
{
	if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'pdf' && isset($_REQUEST['student']))
	{
		ob_start();
		$obj_mark = new Marks_Manage();
		$uid = $_REQUEST['student'];
		
		$user =get_userdata( $uid );
		$user_meta =get_user_meta($uid);
		$class_id = $user_meta['class_name'][0];
		$subject = $obj_mark->mj_smgt_student_subject($class_id);
		$total_subject=count($subject);
		$exam_id =$_REQUEST['exam_id'];
		$total = 0;
		$grade_point = 0;
		$umetadata=mj_smgt_get_user_image($uid);
		?>

		<div class="container" style="margin-bottom:8px;">
			<div style="border: 2px solid;">	
				<div style="padding:20px;">
					<div style="float:left;width:100%; ">
						<div style="float:left;width:25%;">
							<div class="asasa" style="float:letf;border-radius:50px;">
								<!-- <img src="<?php echo get_option( 'smgt_school_logo' ) ?>" style="border-radius:50px !important;" /> -->
								<div style="width: 150px;background-image: url('<?php echo get_option( 'smgt_school_logo' ) ?>');height: 150px;border-radius: 50%;background-repeat:no-repeat;background-size:cover;"></div>	
							</div>
						</div>
						<div style="float:left; width:55%;font-size:24px;padding-top:50px;"> 
							<b style="color:#307994;align-item:center;"><?php echo get_option( 'smgt_school_name' );?></b>
						</div>	 
						<div style="float:left;width:15%;padding-top:55px;">
							<?php
							$term_id=$obj_mark->mj_smgt_get_exam_term($exam_id);
							?>
							<b> <?php echo get_the_title($term_id); ?> <?php esc_attr_e('Term Exam Result','school-mgt');?></b>
						</div>
					</div>
				</div>
			</div>
		</div> 


	
			<div style="border: 2px solid;background-color:#f5c6cc;margin-bottom:8px;">
				<div style="float:left;width:100%;">
					<div class="123" style="padding:10px;">
					<div style="float:left;width:33%;"><?php esc_attr_e('Student Name','school-mgt');?>: <b><?php echo get_user_meta($uid, 'first_name',true); ?>&nbsp;<?php echo get_user_meta($uid, 'last_name',true); ?></div>
					<div style="float:left;width:33%;"><?php esc_attr_e("Father's Name","school-mgt");?>: <b><?php 
					$parent_id= get_user_meta($uid, 'parent_id',true);
					if(!empty($parent_id))
					{					
						foreach($parent_id as $id)
						{
							$parentinfo=get_userdata($id);
						}
						echo  $parentinfo->display_name;
					}
					else
					{
						echo "-";
					}
					?> </b></div>
					<div style="float:left;width:33%;"><?php esc_attr_e("Roll No","school-mgt");?>: 
					<b><?php echo get_user_meta($uid, 'roll_id',true); ?> </b></div>
					</div>
				</div>
				
				<div style="float:left;width:100%;">
				<div class="123" style="padding:10px;">	
					<div style="float:left;width:33%;"><?php esc_attr_e('Class','school-mgt');?>: <b><?php $class_id=get_user_meta($uid, 'class_name',true);
								echo $classname=mj_smgt_get_class_name($class_id); ?></b></div>
					<div style="float:left;width:33%;"><?php esc_attr_e('Section','school-mgt');?>:
					 <b><?php 
					 $section_name=get_user_meta($uid, 'class_section',true);
					 if($section_name!=""){
						 echo mj_smgt_get_section_name($section_name); 
					 }
					 else
					 {
						 esc_attr_e('No Section','school-mgt');;
					 }
					 ?></b></div>
					<div style="float:left;width:33%;"><?php esc_attr_e('Exam Name','school-mgt');?>:
					<b><?php echo mj_smgt_get_exam_name_id($exam_id); ?>
						</b></div>
						</div>
				</div>

			</div>

		
		  <table style="float:left;width:100%;border:1px solid #000;margin-bottom:8px;" cellpadding="10" cellspacing="0">
			<thead>
				<tr style="border-bottom: 1px solid #000;background-color:#b8daff;">
					<th style="border-bottom: 1px solid #000;text-align:left;border-right: 1px solid #000;"><?php esc_attr_e('Subject','school-mgt')?></th>
					<th style="border-bottom: 1px solid #000;text-align:left;border-right: 1px solid #000;"><?php esc_attr_e('Max Marks','school-mgt')?></th>
					<th style="border-bottom: 1px solid #000;text-align:left;border-right: 1px solid #000;"><?php esc_attr_e('Pass Marks','school-mgt')?></th>
					<th style="border-bottom: 1px solid #000;text-align:left;border-right: 1px solid #000;"><?php esc_attr_e('Obtain Mark','school-mgt')?></th>
					<th style="border-bottom: 1px solid #000;text-align:left;border-right: 1px solid #000;"><?php esc_attr_e('Grade','school-mgt')?></th>
				</tr>
			</thead>
			<tbody>
			  <?php
				$i=1;
				foreach($subject as $sub)
				{
					$total_pass_mark += $obj_mark->mj_smgt_get_pass_marks($exam_id); 
				?>
			  <tr style="border-bottom: 1px solid #000;">
				<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $sub->sub_name;?></td>
				<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $obj_mark->mj_smgt_get_max_marks($exam_id);?> </td>
				<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $obj_mark->mj_smgt_get_pass_marks($exam_id);?></td>
				<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php  echo $obj_mark->mj_smgt_get_marks($exam_id,$class_id,$sub->subid,$uid);?></td>
				<td style="border-bottom: 1px solid #000;border-right: 1px solid #000;"><?php echo $obj_mark->mj_smgt_get_grade($exam_id,$class_id,$sub->subid,$uid);?></td>
			  </tr>
			  <?php
					$i++;
					$total +=  $obj_mark->mj_smgt_get_marks($exam_id,$class_id,$sub->subid,$uid);
					$total_max_mark += $obj_mark->mj_smgt_get_max_marks($exam_id);
				}				
				?>
			</tbody>
			<tfoot>
				<tr style="border-bottom: 1px solid #000;background-color:#b8daff;">
					<th><?php esc_attr_e('TOTAL MARKS','school-mgt')?></th>
					<th><?php 
					if(!empty($total_max_mark))
					{
						echo $total_max_mark; 
					}
					else
					{
						echo "-";
					}
					?></th>
					<th><?php 
					if(!empty($total_pass_mark))
					{
						echo $total_pass_mark; 
					}
					else
					{
						echo "-";
					}
					?></th>
					<th><?php 
					if(!empty($total))
					{
						echo $total; 
					}
					else
					{
						echo "-";
					}
					?></th>
					<th></th>
				</tr>
			</tfoot>
		  </table>
	
		  <div style="border: 2px solid #8b8b8b;background-color:#eacf80;width:100%;float: left;margin-bottom:8px;">
				<div class="row" style="">	
					<div style="float:left;width: 60%;margin: 10px;">	
						<b class="" style="text-align: left"><?php esc_attr_e('Percentage','school-mgt'); ?> : </b>
						<?php 
							$percentage=$total/$total_max_mark*100;
							if(!empty($percentage))
							{
								echo $percentage;
							}
							else
							{
								echo "-";
							}
						?>
					</div>
					<div style="float:right;width: 20%;margin: 0px;">
						<b style="text-align: right;"><?php esc_attr_e('Result','school-mgt'); ?> : </b> 
						<?php
							/* $obtian_mark=array();
							foreach($subject as $sub)
							{
								$obtian_mark[]=$obj_mark->mj_smgt_get_marks($exam_id,$class_id,$sub->subid,$uid);
							}	
							if( in_array($obj_mark->mj_smgt_get_pass_marks($exam_id), $obtian_mark) )
							{
								echo  esc_attr_e('Fail','school-mgt');
							}
							else
							{
								echo  esc_attr_e('Fail','school-mgt');
							}  */
							foreach($subject as $sub)
							{
								$result=array();
								if($obj_mark->mj_smgt_get_marks($exam_id,$class_id,$sub->subid,$uid) >= $obj_mark->mj_smgt_get_pass_marks($exam_id))
								{
									$result[] = "pass";
								}
								else 
								{
									$result1[] = "fail";
								}
							}	 
							
								if(isset($result) && in_array("pass", $result) && isset($result1) && in_array("fail", $result1))
								{
									echo  esc_attr_e('Fail','school-mgt');
								}
								elseif(isset($result) && in_array("pass", $result))
								{
									echo  esc_attr_e('Pass','school-mgt');
								}
								elseif(isset($result1) && in_array("fail", $result1))
								{
									echo  esc_attr_e('Fail','school-mgt');
								}
								else 
								{
									echo "-";
								}
						?>
					</div>
				</div>
				<hr>
				<div class="aaa" style="direction: rtl;margin-right: 20px;">
					<br>
					<div style="float:right;margin-right:0px;margin-left: auto;">
						<div>	
						<img src="<?php echo get_option( 'smgt_principal_signature' ) ?>" style="width:100px; margin-right:15px;" />
						</div>
						<div style="border: 1px solid  !important;width: 150px;margin-top: 5px;"></div>
						<div style="margin-right:10px;margin-bottom:10px;">
						<?php esc_attr_e('Principal Signature','school-mgt'); ?>
						</div>
					</div>
				</div>
		  </div>
		<?php
		error_reporting(0);
		$out_put = ob_get_contents();
		echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap_min.css', __FILE__).'"></link>';
		echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap_min.js', __FILE__).'"></script>';
		ob_clean();
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="result"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		require_once SMS_PLUGIN_DIR . '/lib/mpdf/vendor/autoload.php';
		$stylesheet1 = file_get_contents(SMS_PLUGIN_DIR. '/assets/css/style.css'); // Get css content
			$mpdf = new Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [250, 236]]);
			$mpdf->SetDisplayMode('fullwidth');
			$mpdf->autoScriptToLang = true;
			$mpdf->autoLangToFont = true;
		
		if (is_rtl())
		{
			$mpdf->autoScriptToLang = true;
			$mpdf->autoLangToFont = true;
			$mpdf->SetDirectionality('rtl');
		}   
		$mpdf->WriteHTML($stylesheet1,1); // Writing style to pdf
		$mpdf->WriteHTML($out_put);
		$mpdf->Output();
		unset( $out_put );
		unset( $mpdf );
		exit;	 
	}
	if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'pdf' && isset($_REQUEST['invoice_type']))
	{
		error_reporting(0);
		mj_smgt_student_invoice_pdf($_REQUEST['invoice_id']);
		$out_put = ob_get_contents();
		ob_clean();
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="result"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		
		require_once SMS_PLUGIN_DIR . '/lib/mpdf/vendor/autoload.php';
		$mpdf = new Mpdf\Mpdf;
		$mpdf->SetTitle('Payment');
		$mpdf->autoScriptToLang = true;
		$mpdf->autoLangToFont = true;
		
		if (is_rtl())
		{
			$mpdf->autoScriptToLang = true;
			$mpdf->autoLangToFont = true;
			$mpdf->SetDirectionality('rtl');
		}   
		
		$mpdf->WriteHTML($out_put);
		$mpdf->Output();
		unset( $out_put );
		unset( $mpdf );
		exit;
	}
	 if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'pdf' && isset($_REQUEST['fee_paymenthistory']))
	 {	
		error_reporting(0);	
		mj_smgt_student_paymenthistory_pdf($_REQUEST['payment_id']);			
		$out_put = ob_get_contents();
		ob_clean();
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="feepaymenthistory"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		require_once SMS_PLUGIN_DIR . '/lib/mpdf/vendor/autoload.php';
		$mpdf = new Mpdf\Mpdf;
		$mpdf->SetTitle('Fees Payment');
		$mpdf->autoScriptToLang = true;
		$mpdf->autoLangToFont = true;
		
		if (is_rtl())
		{
			$mpdf->autoScriptToLang = true;
			$mpdf->autoLangToFont = true;
			$mpdf->SetDirectionality('rtl');
		}   
		
		$mpdf->WriteHTML($out_put);
		$mpdf->Output();
		unset( $out_put );
		unset( $mpdf );
		exit;
	}
	if(isset($_REQUEST['student_exam_receipt_pdf']) && $_REQUEST['student_exam_receipt_pdf'] == 'student_exam_receipt_pdf')
	{	
		error_reporting(0);	
		mj_smgt_student_exam_receipt_pdf($_REQUEST['student_id'],$_REQUEST['exam_id']);			
		$out_put = ob_get_contents();
		ob_clean();
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="examreceipt"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		require_once SMS_PLUGIN_DIR . '/lib/mpdf/vendor/autoload.php';
		$mpdf = new Mpdf\Mpdf;
		$mpdf->autoScriptToLang = true;
		$mpdf->autoLangToFont = true;
		if (is_rtl())
		{
			$mpdf->autoScriptToLang = true;
			$mpdf->autoLangToFont = true;
			$mpdf->SetDirectionality('rtl');
		}   
		
		$mpdf->WriteHTML($out_put);
		$mpdf->Output();
		unset( $out_put );
		unset( $mpdf );
		exit;
	}
}

/**
 * Authenticate a user, confirming the username and password are valid.
 *
 * @since 2.8.0
 *
 * @param WP_User|WP_Error|null $user     WP_User or WP_Error object from a previous callback. Default null.
 * @param string                $username Username for authentication.
 * @param string                $password Password for authentication.
 * @return WP_User|WP_Error WP_User on success, WP_Error on failure.
 */
//add_filter( 'authenticate', 'wp_authenticate_username_password_new', 20, 3 );

function mj_smgt_wp_authenticate_username_password_new( $user, $username, $password )
{
	if ( $user instanceof WP_User ) {
		return $user;
	}

	if ( empty( $username ) || empty( $password ) ) {
		if ( is_wp_error( $user ) ) {
			return $user;
		}

		$error = new WP_Error();

		if ( empty( $username ) ) {
			$error->add( 'empty_username', esc_attr__( '<strong>ERROR</strong>: The username field is empty.' ) );
		}

		if ( empty( $password ) ) {
			$error->add( 'empty_password', esc_attr__( '<strong>ERROR</strong>: The password field is empty.' ) );
		}

		return $error;
	}

	$user = get_user_by( 'login', $username );

	/**
	 * Filters whether the given user can be authenticated with the provided $password.
	 *
	 * @since 2.5.0
	 *
	 * @param WP_User|WP_Error $user     WP_User or WP_Error object if a previous
	 *                                   callback failed authentication.
	 * @param string           $password Password to check against the user.
	 */
	$user = apply_filters( 'wp_authenticate_user', $user, $password );
	if ( is_wp_error( $user ) ) {
		return $user;
	}

	return $user;
}

add_filter( 'auth_cookie_expiration', 'mj_smgt_keep_me_logged_in_60_minutes' );
function mj_smgt_keep_me_logged_in_60_minutes( $expirein ) {
    return 7200; // 1 hours
}

//Auto Fill Feature is Enabled  wp login page//
add_action('login_form', function($args) {
  $login = ob_get_contents();
  ob_clean();
  $login = str_replace('id="user_pass"', 'id="user_pass" autocomplete="off"', $login);
  $login = str_replace('id="user_login"', 'id="user_login" autocomplete="off"', $login);
  echo $login; 
}, 9999);

// Wordpress User Information Dislclosure//
//Remove for page and ad edit post issue//

 ////X-Frame-Options Header Not Set//
function mj_smgt_block_frames() {
header( 'X-FRAME-OPTIONS: SAMEORIGIN' );
}
add_action( 'send_headers', 'mj_smgt_block_frames', 10 );
// add_action( 'send_headers', 'send_frame_options_header', 10, 0 );


if (!empty($_SERVER['HTTPS'])) {
  function mj_smgt_add_hsts_header($headers) {
    $headers['strict-transport-security'] = 'max-age=31536000; includeSubDomains';
    return $headers;
  }
add_filter('wp_headers', 'mj_smgt_add_hsts_header');
}
//------------- STUDENT ADMISSION PAGE --------------//
function mj_smgt_install_student_admission_page() 
{
	if ( !get_option('smgt_student_admission_page') ) 
	{
		$curr_page = array(
			'post_title' => esc_attr__('Student Admission', 'school-mgt'),
			'post_content' => '[smgt_student_admission]',
			'post_status' => 'publish',
			'post_type' => 'page',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_category' => array(1),
			'post_parent' => 0 );		

		$curr_created = wp_insert_post( $curr_page );
		update_option( 'smgt_student_admission_page', $curr_created );		
	}
}

function smgt_student_admisiion_function()
{
	   global $admission_no,$admission_date,$first_name,$middle_name,$last_name,$birth_date,$gender,$address,$state_name,$city_name,$zip_code,$phone_code,$mobile_number,$alternet_mobile_number,$email,$username,$password,$preschool_name,$smgt_user_avatar,$sibling_information,$p_status,$fathersalutation,$father_first_name,$father_middle_name,$father_last_name,$fathe_gender,$father_birth_date,$father_address,$father_city_name,$father_state_name,$father_zip_code,$father_email,$father_mobile,$father_school,$father_medium,$father_education,$fathe_income,$father_occuption,$father_doc,$mothersalutation,$mother_first_name,$mother_middle_name,$mother_last_name,$mother_gender,$mother_birth_date,$mother_address,$mother_city_name,$mother_state_name,$mother_zip_code,$mother_email,$mother_mobile,$mother_school,$mother_medium,$mother_education,$mother_income,$mother_occuption,$mother_doc;
	    
		if ( isset($_POST['save_student_front_admission'] ) ) 
		{
			  
		     mj_smgt_admission_validation(
			$_POST['email'],
			$_POST['email'],
			$_POST['father_email'],
			$_POST['mother_email']
		); 
        // sanitize user form input
       global $admission_no,$admission_date,$first_name,$middle_name,$last_name,$birth_date,$gender,$address,$state_name,$city_name,$zip_code,$phone_code,$mobile_number,$alternet_mobile_number,$email,$username,$password,$preschool_name,$smgt_user_avatar,$sibling_information,$p_status,$fathersalutation,$father_first_name,$father_middle_name,$father_last_name,$fathe_gender,$father_birth_date,$father_address,$father_city_name,$father_state_name,$father_zip_code,$father_email,$father_mobile,$father_school,$father_medium,$father_education,$fathe_income,$father_occuption,$father_doc,$mothersalutation,$mother_first_name,$mother_middle_name,$mother_last_name,$mother_gender,$mother_birth_date,$mother_address,$mother_city_name,$mother_state_name,$mother_zip_code,$mother_email,$mother_mobile,$mother_school,$mother_medium,$mother_education,$mother_income,$mother_occuption,$mother_doc;
	   $sibling_value=array();	
	   
	   if(isset($_FILES['father_doc']) && !empty($_FILES['father_doc']) && $_FILES['father_doc']['size'] !=0)
		{			
			if($_FILES['father_doc']['size'] > 0)
				$upload_docs=mj_smgt_load_documets_new($_FILES['father_doc'],$_FILES['father_doc'],$_POST['father_document_name']);		
		}
		else
		{
			$upload_docs='';
		}
		 
		$father_document_data=array();
		if(!empty($upload_docs))
		{
			$father_document_data[]=array('title'=>$_POST['father_document_name'],'value'=>$upload_docs);
		}
		else
		{
			$father_document_data[]='';
		}
		
		if(isset($_FILES['mother_doc']) && !empty($_FILES['mother_doc']) && $_FILES['mother_doc']['size'] !=0)
		{			
			if($_FILES['mother_doc']['size'] > 0)
				$upload_docs1=mj_smgt_load_documets_new($_FILES['mother_doc'],$_FILES['mother_doc'],'mother_doc');		
		}
		else
		{
			$upload_docs1='';
		}
		$mother_document_data=array();
		if(!empty($upload_docs1))
		{
			$mother_document_data[]=array('title'=>$_POST['mother_document_name'],'value'=>$upload_docs1);
		}
		else
		{
			$mother_document_data[]='';
		}
		if(isset($_POST['smgt_user_avatar']) && $_POST['smgt_user_avatar'] != "")
		{
			$photo	=	$_POST['smgt_user_avatar'];
		}
		else
		{
			$photo	=	"";
		}
		if($_POST['password'] != "")
		{
			$user_pass=mj_smgt_password_validation($_POST['password']);
		}
		else
		{
			$user_pass=wp_generate_password();
		}
		if(!empty($_POST['siblingsname']))
		{
			foreach($_POST['siblingsname'] as $key=>$value)
			{
				$sibling_value[]=array("siblinggender" => $_POST['siblinggender'][$key],"siblingsname" => mj_smgt_onlyLetter_specialcharacter_validation($value), "siblingage" =>$_POST['siblingage'][$key],"sibling_standard" => $_POST['sibling_standard'][$key], "siblingsid" => $_POST['siblingsid'][$key]);				  
			}	
		}
        $admission_no=	mj_smgt_address_description_validation($_POST['admission_no']);
		$admission_date=$_POST['admission_date'];
		$first_name= mj_smgt_onlyLetter_specialcharacter_validation($_POST['first_name']);
		$middle_name=mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']);
		$last_name=mj_smgt_onlyLetter_specialcharacter_validation($_POST['last_name']);
		$birth_date=$_POST['birth_date'];
		$gender=mj_smgt_onlyLetterSp_validation($_POST['gender']);
		$address=mj_smgt_address_description_validation($_POST['address']);
		$state_name=mj_smgt_city_state_country_validation($_POST['state_name']);
		$city_name=mj_smgt_city_state_country_validation($_POST['city_name']);
		$zip_code=mj_smgt_onlyLetterNumber_validation($_POST['zip_code']);
		$phone_code=$_POST['phone_code'];
		$mobile_number=mj_smgt_phone_number_validation($_POST['phone']);
		$alternet_mobile_number=mj_smgt_phone_number_validation($_POST['alternet_mobile_number']);
		$email=mj_smgt_username_validation($_POST['email']);
		$username=mj_smgt_username_validation($_POST['email']);
		$password=$user_pass;
		$preschool_name=$_POST['preschool_name'];
		$smgt_user_avatar=$photo;
		$sibling_information=json_encode($sibling_value);
		$p_status=$_POST['pstatus'];
		$fathersalutation=mj_smgt_onlyLetter_specialcharacter_validation($_POST['fathersalutation']);
		$father_first_name=mj_smgt_onlyLetter_specialcharacter_validation($_POST['father_first_name']);
		$father_middle_name=mj_smgt_onlyLetter_specialcharacter_validation($_POST['father_middle_name']);
		$father_last_name=mj_smgt_onlyLetter_specialcharacter_validation($_POST['father_last_name']);
		$fathe_gender=$_POST['fathe_gender'];
		$father_birth_date=$_POST['father_birth_date'];
		$father_address=mj_smgt_address_description_validation($_POST['father_address']);
		$father_city_name=mj_smgt_city_state_country_validation($_POST['father_city_name']);
		$father_state_name=mj_smgt_city_state_country_validation($_POST['father_state_name']);
		$father_zip_code=mj_smgt_onlyLetterNumber_validation($_POST['father_zip_code']);
		$father_email=mj_smgt_email_validation($_POST['father_email']);
		$father_mobile=	mj_smgt_phone_number_validation($_POST['father_mobile']);
		$father_school=mj_smgt_onlyLetter_specialcharacter_validation($_POST['father_school']);
		$father_medium=$_POST['father_medium'];
		$father_education=$_POST['father_education'];
		$fathe_income=$_POST['fathe_income'];
		$father_occuption=$_POST['father_occuption'];
		$father_doc=json_encode($father_document_data);
		$mothersalutation=	mj_smgt_onlyLetter_specialcharacter_validation($_POST['mothersalutation']);
		$mother_first_name=mj_smgt_onlyLetter_specialcharacter_validation($_POST['mother_first_name']);
		$mother_middle_name=mj_smgt_onlyLetter_specialcharacter_validation($_POST['mother_middle_name']);
		$mother_last_name=mj_smgt_onlyLetter_specialcharacter_validation($_POST['mother_last_name']);
		$mother_gender=$_POST['mother_gender'];
		$mother_birth_date=$_POST['mother_birth_date'];
		$mother_address=mj_smgt_address_description_validation($_POST['mother_address']);
		$mother_city_name=mj_smgt_city_state_country_validation($_POST['mother_city_name']);
		$mother_state_name=mj_smgt_city_state_country_validation($_POST['mother_state_name']);
		$mother_zip_code=mj_smgt_onlyLetterNumber_validation($_POST['mother_zip_code']);
		$mother_email=mj_smgt_email_validation($_POST['mother_email']);
		$mother_mobile=mj_smgt_phone_number_validation($_POST['mother_mobile']);
		$mother_school=mj_smgt_onlyLetter_specialcharacter_validation($_POST['mother_school']);
		$mother_medium=$_POST['mother_medium'];
		$mother_education=$_POST['mother_education'];
		$mother_income=$_POST['mother_income'];
		$mother_occuption=$_POST['mother_occuption'];
		$mother_doc=json_encode($mother_document_data);
        $wp_nonce     =   $_POST['_wpnonce'];
      
 
        // call @function smgt_complete_admission to create the user
        // only when no WP_error is found
    mj_smgt_complete_admission($admission_no,$admission_date,$first_name,$middle_name,$last_name,$birth_date,$gender,$address,$state_name,$city_name,$zip_code,$phone_code,$mobile_number,$alternet_mobile_number,$email,$username,$password,$preschool_name,$smgt_user_avatar,$sibling_information,$p_status,$fathersalutation,$father_first_name,$father_middle_name,$father_last_name,$fathe_gender,$father_birth_date,$father_address,$father_city_name,$father_state_name,$father_zip_code,$father_email,$father_mobile,$father_school,$father_medium,$father_education,$fathe_income,$father_occuption,$father_doc,$mothersalutation,$mother_first_name,$mother_middle_name,$mother_last_name,$mother_gender,$mother_birth_date,$mother_address,$mother_city_name,$mother_state_name,$mother_zip_code,$mother_email,$mother_mobile,$mother_school,$mother_medium,$mother_education,$mother_income,$mother_occuption,$mother_doc,$wp_nonce);
   
	}
    mj_smgt_admission_form($admission_no,$admission_date,$first_name,$middle_name,$last_name,$birth_date,$gender,$address,$state_name,$city_name,$zip_code,$phone_code,$mobile_number,$alternet_mobile_number,$email,$username,$password,$preschool_name,$smgt_user_avatar,$sibling_information,$p_status,$fathersalutation,$father_first_name,$father_middle_name,$father_last_name,$fathe_gender,$father_birth_date,$father_address,$father_city_name,$father_state_name,$father_zip_code,$father_email,$father_mobile,$father_school,$father_medium,$father_education,$fathe_income,$father_occuption,$father_doc,$mothersalutation,$mother_first_name,$mother_middle_name,$mother_last_name,$mother_gender,$mother_birth_date,$mother_address,$mother_city_name,$mother_state_name,$mother_zip_code,$mother_email,$mother_mobile,$mother_school,$mother_medium,$mother_education,$mother_income,$mother_occuption,$mother_doc);
}

function mj_smgt_admission_form(  $admission_no,$admission_date,$first_name,$middle_name,$last_name,$birth_date,$gender,$address,$state_name,$city_name,$zip_code,$phone_code,$mobile_number,$alternet_mobile_number,$email,$username,$password,$preschool_name,$smgt_user_avatar,$sibling_information,$p_status,$fathersalutation,$father_first_name,$father_middle_name,$father_last_name,$fathe_gender,$father_birth_date,$father_address,$father_city_name,$father_state_name,$father_zip_code,$father_email,$father_mobile,$father_school,$father_medium,$father_education,$fathe_income,$father_occuption,$father_doc,$mothersalutation,$mother_first_name,$mother_middle_name,$mother_last_name,$mother_gender,$mother_birth_date,$mother_address,$mother_city_name,$mother_state_name,$mother_zip_code,$mother_email,$mother_mobile,$mother_school,$mother_medium,$mother_education,$mother_income,$mother_occuption,$mother_doc) 
{
	wp_enqueue_script('smgt-defaultscript', plugins_url( '/assets/js/jquery-3.6.0.min.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
	wp_enqueue_script('smgt-bootstrap-js', plugins_url( '/assets/js/bootstrap.min.js', __FILE__ ) );	
	$lancode=get_locale();
	$code=substr($lancode,0,2);		
	wp_enqueue_style( 'wcwm-validate-css', plugins_url( '/lib/validationEngine/css/validationEngine.jquery.css', __FILE__) );
	wp_register_script( 'jquery-1.8.2', plugins_url( '/lib/validationEngine/js/jquery-1.8.2.min.js', __FILE__), array( 'jquery' ) );
	wp_enqueue_script( 'jquery-1.8.2' );
	wp_register_script( 'jquery-validationEngine-'.$code.'', plugins_url( '/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js', __FILE__), array( 'jquery' ) );
	 
	wp_enqueue_script( 'jquery-validationEngine-'.$code.'' );
	wp_register_script( 'jquery-validationEngine', plugins_url( '/lib/validationEngine/js/jquery.validationEngine.js', __FILE__), array( 'jquery' ) );
	wp_enqueue_script( 'jquery-validationEngine' );
	wp_enqueue_style( 'smgt-style-css', plugins_url( '/assets/css/style.css', __FILE__) );
	wp_enqueue_style( 'smgt-bootstrap-css', plugins_url( '/assets/css/bootstrap.min.css', __FILE__) );
	wp_enqueue_style( 'smgt-responsive-css', plugins_url( '/assets/css/school-responsive.css', __FILE__) );
	if (is_rtl())
	{	
		wp_register_script( 'jquery-validationEngine-en', plugins_url( '/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js', __FILE__), array( 'jquery' ) );
		wp_enqueue_script('smgt-validationEngine-en-js', plugins_url( '/assets/js/jquery.validationEngine-ar.js', __FILE__ ) );
		wp_enqueue_style( 'css-custome_rtl-css', plugins_url( '/assets/css/custome_rtl.css', __FILE__) );
	}
	wp_enqueue_script('jquery-ui-datepicker');
	wp_register_script( 'font-awsome-js', plugins_url( '/assets/font-awesome.min.js', __FILE__) );
	wp_enqueue_script('smgt-custom_jobj', plugins_url( '/assets/js/smgt_custom_confilict_obj.js', __FILE__ ), array( 'jquery' ), '4.1.1', false );
	wp_enqueue_style( 'css-custome_rtl-css', plugins_url( '/assets/css/custome_rtl.css', __FILE__) );
	wp_enqueue_style( 'admission-css', plugins_url( '/assets/css/settings/admission.css', __FILE__) );

	?>
	<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/jquery-3.6.0.min.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/settings/admission.js'; ?>"></script>

	<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>
<script type="text/javascript">
//add multiple Sibling //
var value = 1;
function mj_smgt_add_sibling()
{	
	value++;
	
	$("#sibling_div").append('<div class="form-group"><div class="form-group"><div class="col-sm-12 col-lg-12 col-md-12 col-xs-12"><label class="radio-inline custom_radio"><input type="radio" name="siblinggender[]" value="Brother" id="txtNumHours2"><?php esc_attr_e('Brother','school-mgt'); ?></label><label class="radio-inline custom_radio"><input type="radio" name="siblinggender[]" value="Sister" id="txtNumHours2"><?php  esc_attr_e('Sister','school-mgt');?></label></div></div><div class="col-sm-12 col-lg-12 col-md-12 col-xs-12"><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><input id="txtNumHours" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input margin_top_10" maxlength="50" type="text"  name="siblingsname[]" placeholder="<?php esc_attr_e('Enter Full Name','school-mgt');?>"></div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><input id="txtNumHours1" class="form-control validate[custom[onlyNumberSp],maxSize[3],max[100]] text-input margin_top_10" type="number" maxlength="3" name="siblingage[]" placeholder="<?php  esc_attr_e('Enter Age','school-mgt');?>"></div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><select class="form-control standard_category margin_top_10" name="sibling_standard[]" id="txtNumHours3"><option value=""><?php  esc_attr_e('Select Standard','school-mgt');?></option><?php $activity_category=mj_smgt_get_all_category('standard_category');if(!empty($activity_category)){ foreach ($activity_category as $retrive_data){ ?><option value="<?php echo $retrive_data->ID;?>"><?php echo esc_attr($retrive_data->post_title); ?> </option><?php } } ?> </select></div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><input id="txtNumHours4" class="form-control validate[custom[onlyNumberSp],maxSize[6]] text-input margin_top_10"  placeholder="<?php  esc_attr_e('Enter SID Number','school-mgt');?>" type="number"  name="siblingsid[]"></div></div><div class="col-sm-12 col-lg-12 col-md-12 col-xs-12"><input type="button" value="<?php esc_html_e('Delete','school-mgt');?>" onclick="mj_smgt_deleteParentElement(this)" class="margin_top_10 remove_cirtificate btn btn-danger padding_5px"></div></div>');
}	
</script>
<?php 
$role='student_temp'; 
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
<div class="student_admission_form max_width_100rem">
		<form id="admission_form" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="role" value="<?php echo $role;?>"  />
		  <!--- Hidden User and password --------->
		<input id="username" type="hidden"  name="username">
		<input id="password" type="hidden"  name="password">
		<div class="panel panel-white">
			<div class="panel-body padding_0px">
				<div class="panel-group" id="accordion">
					<div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title margin_5px">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOnenew" style="font-weight:800;">
							  <?php esc_attr_e('Student Info', 'school-mgt'); ?>
							</a>
						  </h4>
						</div>
						<div id="collapseOnenew" class="panel-collapse collapse in">
						  <div class="panel-body">
							<div class="form-group">
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="admission_no"><?php esc_attr_e('Admission Number','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<input id="admission_no" class="form-control validate[required] text-input" type="text" value="<?php echo mj_smgt_generate_admission_number();?>"  name="admission_no" readonly>		
									</div>
								</div>
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="admission_date"><?php esc_attr_e('Admission Date','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<input id="admission_date" class="form-control validate[required]" type="text"  placeholder="<?php esc_html_e('Enter Admission Date','school-mgt');?>" name="admission_date" readonly>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="first_name"><?php esc_attr_e('First Name','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="first_name" placeholder="<?php esc_html_e('Enter First Name','school-mgt');?>" >
									</div>
								</div>
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="middle_name"><?php esc_attr_e('Middle Name','school-mgt');?></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text"  name="middle_name"  placeholder="<?php esc_attr_e('Enter Middle Name','school-mgt');?>">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="last_name"><?php esc_attr_e('Last Name','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  name="last_name" placeholder="<?php esc_attr_e('Enter Last Name','school-mgt');?>">
									</div>
								</div>
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="birth_date"><?php esc_attr_e('Date of birth','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<input id="birth_date" class="form-control validate[required] birth_date" placeholder="<?php esc_attr_e('Enter Date of birth','school-mgt');?>" type="text"  name="birth_date"  readonly>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="gender"><?php esc_attr_e('Gender','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<label class="radio-inline custom_radio">
									     	<input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_attr_e('Male','school-mgt');?>
									    </label>
									    <label class="radio-inline custom_radio">
									      	<input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_attr_e('Female','school-mgt');?> 
									    </label>
										<label class="radio-inline custom_radio">
									      	<input type="radio" value="other" class="tog validate[required]" name="gender"   <?php  checked( 'other', $genderval);  ?> /><?php esc_attr_e('Other','school-mgt');?>
									    </label>
									</div>
								</div>
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="address"><?php esc_attr_e('Address','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<input id="address" class="form-control validate[required,custom[address_description_validation]]" placeholder="<?php esc_attr_e('Enter Address','school-mgt');?>" maxlength="150" type="text"  name="address">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="state_name"><?php esc_attr_e('State','school-mgt');?></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<input id="state_name" class="form-control validate[custom[city_state_country_validation]]"  placeholder="<?php esc_attr_e('Enter State','school-mgt');?>"  maxlength="50" type="text"  name="state_name" >
									</div>
								</div>
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="city_name"><?php esc_attr_e('City','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50"  placeholder="<?php esc_attr_e('Enter City','school-mgt');?>" type="text"  name="city_name" >
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="zip_code"><?php esc_attr_e('Zip Code','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" maxlength="15"   placeholder="<?php esc_attr_e('Enter Zip Code','school-mgt');?>"  type="text"  name="zip_code">
									</div>
								</div>
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="mobile_number"><?php esc_attr_e('Mobile Number','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 margin_bottom_5">
										<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control padding_10px " name="phonecode">
									</div>
									<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
										<input id="phone" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input padding_10px" placeholder="<?php esc_attr_e('Enter Mobile Number','school-mgt');?>" type="text"  name="phone" >
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="email"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<input id="email" class="form-control validate[required,custom[email]] text-input email" maxlength="100" type="text"  name="email" placeholder="<?php esc_attr_e('Enter Email','school-mgt');?>">
									</div>
								</div>
								<div class="col-sm-6 col-lg-6 col-md-6 col-xs-12">
									<label class="col-lg-5 col-md-5 col-sm-5 col-xs-12 control-label" for="group"><?php esc_attr_e('Previous School','school-mgt');?><span class="require-field"></span></label>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
										<input id="preschool_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="preschool_name" placeholder="<?php esc_attr_e('Enter Previous School Name','school-mgt');?>" >
									</div>
								</div>
							</div>
						  </div>
						</div>
					</div> 
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title margin_5px">
								<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsefour_new" style="font-weight:800;">
									<?php esc_attr_e('Siblings Information', 'school-mgt'); ?>
								</a>
							</h4>
						</div>
						<div id="collapsefour_new" class="panel-collapse collapse">
							<div class="panel-body">
								<div class="form-group">
									<div class="col-md-12 col-sm-12 col-xs-12" style="display: inline-flex;" id="relationid">		
										<input type="checkbox" id="chkIsTeamLead" /><span>
										&nbsp;&nbsp;<?php  esc_attr_e('In case of no sibling click here','school-mgt'); ?> </span>
									</div>
								</div>
								<div id="sibling_div">
									<div class="form-group">
										<div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
											<label class="radio-inline custom_radio">
												<input type="radio" name="siblinggender[]" value="Brother" id="txtNumHours2"><?php esc_attr_e('Brother','school-mgt'); ?>
											</label>
											<label class="radio-inline custom_radio">
											  <input type="radio" name="siblinggender[]" value="Sister" id="txtNumHours2"><?php  esc_attr_e('Sister','school-mgt');?>
											</label>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<input id="txtNumHours" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input margin_top_10" maxlength="50" type="text"  name="siblingsname[]" placeholder="<?php esc_attr_e('Enter Full Name','school-mgt');?>">
											</div>
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<input id="txtNumHours1" class="form-control validate[custom[onlyNumberSp],maxSize[3],max[100]] text-input margin_top_10" type="number" maxlength="3" name="siblingage[]" placeholder="<?php  esc_attr_e('Enter Age','school-mgt');?>">
											</div>
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<select class="form-control standard_category margin_top_10" name="sibling_standard[]" id="txtNumHours3">
												<option value=""><?php  esc_attr_e('Select Standard','school-mgt');?></option>
												<?php 
												$activity_category=mj_smgt_get_all_category('standard_category');
												if(!empty($activity_category))
												{
													foreach ($activity_category as $retrive_data)
													{ 		 	
													?>
														<option value="<?php echo $retrive_data->ID;?>"><?php echo esc_attr($retrive_data->post_title); ?> </option>
													<?php }
												} 
												?> 
											</select>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<input id="txtNumHours4" class="form-control validate[custom[onlyNumberSp],maxSize[6]] text-input margin_top_10"  placeholder="<?php  esc_attr_e('Enter SID Number','school-mgt');?>" type="number"  name="siblingsid[]">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-12 col-lg-12 col-md-12 col-xs-12">
											<input type="button" value="<?php  esc_attr_e('Add More','school-mgt') ?>" id="add_more_sibling" onclick="mj_smgt_add_sibling()" class="add_cirtificate btn btn-info margin_bottom_15 margin_top_10 padding_5px">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
					    <div class="panel-heading">
						    <h4 class="panel-title margin_5px">
						        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="font-weight:800;"><?php esc_attr_e('Family Information', 'school-mgt'); ?></a>
						    </h4>
					    </div>
					    <div id="collapseOne" class="panel-collapse collapse">
					      <div class="panel-body">
					      	<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="form-group">
									<label class="col-sm-2 control-label" for="gender"><?php esc_attr_e('Parental Status','school-mgt');?></label>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<?php $pstatus = "Both";?>
										<label class="radio-inline margin_left_10_res custom_radio" id="sinfather">
										  <input type="radio" name="pstatus" class="tog" value="Father"  <?php  checked( 'Father', $pstatus);  ?>><?php esc_attr_e('Single Father','school-mgt');?> 
										</label>
										<label class="radio-inline custom_radio" id="sinmother">
										  <input type="radio" name="pstatus" class="tog " value="Mother" <?php  checked( 'Mother', $pstatus);  ?>><?php esc_attr_e('Single Mother','school-mgt');?> 
										</label>
										<label class="radio-inline custom_radio" id="boths">
										  <input type="radio" name="pstatus" class="tog" value="Both"  <?php  checked( 'Both', $pstatus);  ?>><?php esc_attr_e('Both','school-mgt');?> 
										</label>
									</div>
								</div>	
							</div>
					        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 father_div">
								<div class="row">
									<div id="fatid">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Relationship"><?php esc_attr_e('Relationship','school-mgt');?></label> 
											<div class="col-md-8 col-sm-8 col-xs-12 labfat"><label class="control-label" for="FATHER"><b><?php esc_attr_e('FATHER','school-mgt');?></b></label></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid1">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Salutation"><?php esc_attr_e('Salutation','school-mgt');?></label> 
											<div class="col-md-8 col-sm-8 col-xs-12">
												<select class="form-control validate[required]" name="fathersalutation" id="fathersalutation">
													<option value="Mr"><?php esc_attr_e('Mr','school-mgt');?></option>
												</select>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-12"></div>
										</div>		
									</div>		
								</div>
								<div class="row">
									<div id="fatid2">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="First Name"><?php esc_attr_e('First Name','school-mgt');?></label> 
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="father_first_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter First Name','school-mgt');?>" maxlength="50" type="text" name="father_first_name">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid3">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Middle Name"><?php esc_attr_e('Middle Name','school-mgt');?></label> 
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="father_middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter Middle Name','school-mgt');?>" maxlength="50" type="text" name="father_middle_name">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid4">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Last Name"><?php esc_attr_e('Last Name','school-mgt');?></label> 
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="father_last_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter Last Name','school-mgt');?>" maxlength="50" type="text" name="father_last_name">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid13">	
										<div class="form-group">
											<label class="col-md-4 col-sm-4 col-xs-12 control-label" for="father_gender"><?php esc_attr_e('Gender','school-mgt');?></label>
											<?php $father_gender = "male";?>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<label class="radio-inline custom_radio margin_left_10_res">
												 <input type="radio" value="male" class="tog" name="fathe_gender" <?php  checked( 'male', $father_gender);  ?>/><?php esc_attr_e('Male','school-mgt');?>
												</label>
												<label class="radio-inline custom_radio">
												  <input type="radio" value="female" class="tog" name="fathe_gender" <?php  checked( 'female', $father_gender);  ?> /><?php esc_attr_e('Female','school-mgt');?> 
												</label>
												 <label class="radio-inline custom_radio">
												  <input type="radio" value="other" class="tog" name="fathe_gender" <?php  checked( 'other', $father_gender);  ?> /><?php esc_attr_e('Other','school-mgt');?> 
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid14">	
										<div class="form-group">
											<label class="col-md-4 col-sm-4 col-xs-12 control-label" for="gender"><?php esc_attr_e('Date of birth','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="father_birth_date" class="form-control birth_date" placeholder="<?php esc_attr_e('Enter Date of birth','school-mgt');?>" type="text"  name="father_birth_date"  readonly>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid15">	
										<div class="form-group">
											<label class="col-md-4 col-sm-4 col-xs-12 control-label" for="Address"><?php esc_attr_e('Address','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="father_address" class="form-control validate[custom[address_description_validation]]" maxlength="150" placeholder="<?php esc_attr_e('Enter Address','school-mgt');?>" type="text"  name="father_address" >
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid16">	
										<div class="form-group">
											<label class="col-md-4 col-sm-4 col-xs-12 control-label" for="State"><?php esc_attr_e('State','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="father_state_name" class="form-control validate[custom[city_state_country_validation]]" placeholder="<?php esc_attr_e('Enter State','school-mgt');?>" maxlength="50" type="text"  name="father_state_name" >
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid17">	
										<div class="form-group">
											<label class="col-md-4 col-sm-4 col-xs-12 control-label" for="City"><?php esc_attr_e('City','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="father_city_name" class="form-control validate[custom[city_state_country_validation]]" placeholder="<?php esc_attr_e('Enter City','school-mgt');?>"   maxlength="50" type="text"  name="father_city_name">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid18">	
										<div class="form-group">
											<label class="col-md-4 col-sm-4 col-xs-12 control-label" for="Zip Code"><?php esc_attr_e('Zip Code','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="father_zip_code" class="form-control  validate[custom[onlyLetterNumber]]" placeholder="<?php esc_attr_e('Enter Zip Code','school-mgt');?>"   maxlength="15" type="text"  name="father_zip_code">
											</div>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div id="fatid5">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12 " for="Email"><?php esc_attr_e('Email','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="father_email" class="form-control validate[custom[email]] text-input father_email" maxlength="100" placeholder="<?php esc_attr_e('Enter Email','school-mgt');?>" type="text"  name="father_email">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid6">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="father_mobile"><?php esc_attr_e('Mobile No','school-mgt');?></label>
											<div class="col-md-2 col-sm-2 col-xs-5">
												<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control padding_10px" name="phone_code">
											</div>	
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input id="father_mobile" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]"  placeholder="<?php esc_attr_e('Enter Mobile No','school-mgt');?>"  type="text"  name="father_mobile">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid7">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="father_school"><?php esc_attr_e('School Name ','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<div id="fatherschoolother">
													<input id="father_school" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter School Name','school-mgt');?>" maxlength="50" type="text" name="father_school">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid8">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="father_school"><?php esc_attr_e('Medium of Instruction','school-mgt');?></label>	
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="father_medium" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter Medium of Instruction','school-mgt');?>" maxlength="50" type="text" name="father_medium">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid9">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Educational Qualification"><?php esc_attr_e('Educational Qualification','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="father_education" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter Educational Qualification','school-mgt');?>" maxlength="50" type="text" name="father_education">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid10">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="fathe_income"><?php esc_attr_e('Annual Income','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="fathe_income" class="form-control validate[custom[onlyNumberSp],maxSize[8],min[0]] text-input" placeholder="<?php esc_attr_e('Enter Annual Income','school-mgt');?>" maxlength="50" type="text" name="fathe_income">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid9">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Occupation"><?php esc_attr_e('Occupation','school-mgt');?></label>	
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="father_occuption" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter Occupation','school-mgt');?>" maxlength="50" type="text" name="father_occuption">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="fatid12">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Occupation"><?php esc_attr_e('Proof of Qualification','school-mgt');?></label>	
											<div class="col-md-2 col-sm-2 col-xs-12 margin_bottom_5">
												<input type="text"  name="father_document_name" id="title_value" class="form-control validate[custom[onlyLetter_specialcharacter],maxSize[50]] margin_cause"/>
											</div>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="file" name="father_doc" class="col-md-2 col-sm-2 col-xs-12 form-control file_validation input-file father_doc">	
											</div>
										</div>
									</div>
								</div> 
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mother_div">
								<div class="row">
									<div id="motid">
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Relationship"><?php esc_attr_e('Relationship','school-mgt');?></label> 
											<div class="col-md-8 col-sm-8 col-xs-12 labfat"><label class="control-label" for="MOTHER"><b><?php esc_attr_e('MOTHER','school-mgt');?></b></label></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid1">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Salutation"><?php esc_attr_e('Salutation','school-mgt');?></label> 
											<div class="col-md-8 col-sm-8 col-xs-12">
												<select class="form-control validate[required]" name="mothersalutation" id="mothersalutation">
												<option value="Ms"><?php esc_attr_e('Ms','school-mgt'); ?></option>
												<option value="Mrs"><?php esc_attr_e('Mrs','school-mgt'); ?></option>
												<option value="Miss"><?php esc_attr_e('Miss','school-mgt');?></option>
												</select>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-12"></div>
										</div>		
									</div>		
								</div>
								<div class="row">
									<div id="motid2">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="First Name"><?php esc_attr_e('First Name','school-mgt');?></label> 
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_first_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter First Name','school-mgt');?>"  maxlength="50" type="text" name="mother_first_name">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid3">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Middle Name"><?php esc_attr_e('Middle Name','school-mgt');?></label> 
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter Middle Name','school-mgt');?>" maxlength="50" type="text" name="mother_middle_name">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid4">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Last Name"><?php esc_attr_e('Last Name','school-mgt');?></label> 
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_last_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter Last Name','school-mgt');?>" maxlength="50" type="text" name="mother_last_name">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid13">	
									<?php $mother_gender = "female";?>			
										<div class="form-group">
											<label class="col-md-4 col-sm-4 col-xs-12 control-label" for="father_gender"><?php esc_attr_e('Gender','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<label class="radio-inline custom_radio margin_left_20_res">
													<input type="radio" value="male" class="tog" name="mother_gender" <?php  checked( 'male', $mother_gender);  ?>/><?php esc_attr_e('Male','school-mgt');?>
												</label>
												<label class="radio-inline custom_radio">
													<input type="radio" value="female" class="tog" name="mother_gender" <?php  checked( 'female', $mother_gender);  ?> /><?php esc_attr_e('Female','school-mgt');?> 
												</label>
												<label class="radio-inline custom_radio">
													<input type="radio" value="other" class="tog" name="mother_gender" <?php  checked( 'other', $mother_gender);  ?> /><?php esc_attr_e('Other','school-mgt');?> 
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid14">	
										<div class="form-group">
											<label class="col-md-4 col-sm-4 col-xs-12 control-label" for="gender"><?php esc_attr_e('Date of birth','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_birth_date" class="form-control birth_date" placeholder="<?php esc_attr_e('Enter Date of birth','school-mgt');?>" type="text"  name="mother_birth_date"  readonly>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid15">	
										<div class="form-group">
											<label class="col-md-4 col-sm-4 col-xs-12 control-label" for="Address"><?php esc_attr_e('Address','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_address" class="form-control validate[custom[address_description_validation]]" placeholder="<?php esc_attr_e('Enter Address','school-mgt');?>"  maxlength="150" type="text"  name="mother_address" >
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid16">	
										<div class="form-group">
											<label class="col-md-4 col-sm-4 col-xs-12 control-label" for="State"><?php esc_attr_e('State','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_state_name" class="form-control validate[custom[city_state_country_validation]]" placeholder="<?php esc_attr_e('Enter State','school-mgt');?>"  maxlength="50" type="text"  name="mother_state_name">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid17">	
										<div class="form-group">
											<label class="col-md-4 col-sm-4 col-xs-12 control-label" for="City"><?php esc_attr_e('City','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_city_name" class="form-control validate[custom[city_state_country_validation]]" placeholder="<?php esc_attr_e('Enter City','school-mgt');?>"  maxlength="50" type="text"  name="mother_city_name">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid18">	
										<div class="form-group">
											<label class="col-md-4 col-sm-4 col-xs-12 control-label" for="Zip Code"><?php esc_attr_e('Zip Code','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_zip_code" class="form-control  validate[custom[onlyLetterNumber]]" placeholder="<?php esc_attr_e('Enter Zip Code','school-mgt');?>"  maxlength="15" type="text"  name="mother_zip_code">
											</div>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div id="motid5">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12 " for="Email"><?php esc_attr_e('Email','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_email" class="form-control  validate[custom[email]]  text-input mother_email" maxlength="100"  placeholder="<?php esc_attr_e('Enter Email','school-mgt');?>"  type="text"  name="mother_email">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid6">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="father_mobile"><?php esc_attr_e('Mobile No ','school-mgt');?></label>
											<div class="col-md-2 col-sm-2 col-xs-5 margin_bottom_5">
												<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control padding_10px" name="phone_code">
											</div>	
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input id="mother_mobile" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]"  placeholder="<?php esc_attr_e('Enter Mobile No','school-mgt');?>"  type="text"  name="mother_mobile">
											</div>
										</div>
									</div>
								</div>
								<?php wp_nonce_field( 'save_admission_form' ); ?>
								<div class="row">
									<div id="motid7">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="father_school"><?php esc_attr_e('School Name ','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<div id="fatherschoolother">
													<input id="mother_school" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter School Name','school-mgt');?>" maxlength="50" type="text" name="mother_school">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid8">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="father_school"><?php esc_attr_e('Medium of Instruction','school-mgt');?></label>	
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_medium" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter Medium of Instruction','school-mgt');?>" maxlength="50" type="text" name="mother_medium">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid9">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Educational Qualification"><?php esc_attr_e('Educational Qualification','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_education" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter Educational Qualification','school-mgt');?>" maxlength="50" type="text" name="mother_education">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid10">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="fathe_income"><?php esc_attr_e('Annual Income','school-mgt');?></label>
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_income" class="form-control validate[custom[onlyNumberSp],maxSize[8],min[0]] text-input" placeholder="<?php esc_attr_e('Enter Annual Income','school-mgt');?>" maxlength="50" type="text" name="mother_income">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid9">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Occupation"><?php esc_attr_e('Occupation','school-mgt');?></label>	
											<div class="col-md-8 col-sm-8 col-xs-12">
												<input id="mother_occuption" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e('Enter Occupation','school-mgt');?>" maxlength="50" type="text" name="mother_occuption">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div id="motid12">	
										<div class="form-group">
											<label class="control-label col-md-4 col-sm-4 col-xs-12" for="Occupation"><?php esc_attr_e('Proof of Qualification','school-mgt');?></label>	
											<div class="col-md-2 col-sm-2 col-xs-12 margin_bottom_5">
												<input type="text"  name="mother_document_name" id="title_value" class="form-control validate[custom[onlyLetter_specialcharacter],maxSize[50]] margin_cause"/>
											</div>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="file" name="mother_doc" class="col-md-2 col-sm-2 col-xs-12 form-control file_validation input-file father_doc">	
											</div>
										</div>
									</div>
								</div> 	
							</div>
					      </div>
					    </div>
					  </div>
				</div>
			</div>
		</div>
		<div class="offset-sm-2 col-sm-8">
        	<input type="submit" value="<?php esc_attr_e('New Admission','school-mgt');?>" name="save_student_front_admission" class="btn btn-success"/>
        </div>
        
        </form>
	</div>
    <?php
}
function mj_smgt_complete_admission($admission_no,$admission_date,$first_name,$middle_name,$last_name,$birth_date,$gender,$address,$state_name,$city_name,$zip_code,$phone_code,$mobile_number,$alternet_mobile_number,$email,$username,$password,$preschool_name,$smgt_user_avatar,$sibling_information,$p_status,$fathersalutation,$father_first_name,$father_middle_name,$father_last_name,$fathe_gender,$father_birth_date,$father_address,$father_city_name,$father_state_name,$father_zip_code,$father_email,$father_mobile,$father_school,$father_medium,$father_education,$fathe_income,$father_occuption,$father_doc,$mothersalutation,$mother_first_name,$mother_middle_name,$mother_last_name,$mother_gender,$mother_birth_date,$mother_address,$mother_city_name,$mother_state_name,$mother_zip_code,$mother_email,$mother_mobile,$mother_school,$mother_medium,$mother_education,$mother_income,$mother_occuption,$mother_doc,$wp_nonce)
{
 
    global $reg_errors;
	 
	if ( wp_verify_nonce( $wp_nonce, 'save_admission_form' ) )
	{
		
		if ( 1 > count( $reg_errors->get_error_messages() ) )
		{
			 
			$userdata 	= 	array(
			'user_login'	=>	$email,			
			'user_nicename'	=>	NULL,
			'user_email'	=>	$email,
			'user_url'		=>	NULL,
			'display_name'	=>	$first_name." ".$last_name,
			);
			if($password != "")
			{
				$userdata['user_pass']=mj_smgt_password_validation($password);
			}
			else
			{
				$userdata['user_pass']=wp_generate_password();
			}
			$role="student_temp";
			$status="Not Approved";
			// ADD USER META //
			$usermetadata	=	array(
				'admission_no'	=>	$admission_no,
				'admission_date'	=>$admission_date,
				'role'	=>$role,
				'status'	=>$status,
				'roll_id'	=>"",
				'middle_name'	=>$middle_name,
				'gender'	=>$gender,
				'birth_date'=>$birth_date,
				'address'	=>	$address,
				'city'		=>	$city_name,
				'state'		=>	$state_name,
				'zip_code'	=>	$zip_code,
				'preschool_name'	=>$preschool_name,
				'phone_code'		=>$phone_code,
				'phone'		=>$mobile_number,
				'alternet_mobile_number'	=>$alternet_mobile_number,
				'sibling_information'	=>json_encode($sibling_information),
				'parent_status'	=>$p_status,
				'fathersalutation'	=>	$fathersalutation,
				'father_first_name'	=>	$father_first_name,
				'father_middle_name'	=>	$father_middle_name,
				'father_last_name'	=>	$father_last_name,
				'fathe_gender'	=>$fathe_gender,
				'father_birth_date'	=>$father_birth_date,
				'father_address'=>$father_address,
				'father_city_name'=>$father_city_name,
				'father_state_name'=>$father_state_name,
				'father_zip_code'=>$father_zip_code,
				'father_email'	=>	$father_email,
				'father_mobile'	=>	$father_mobile,
				'father_school'	=>	$father_school,
				'father_medium'	=>$father_medium,
				'father_education'	=>$father_education,
				'fathe_income'	=>$fathe_income,
				'father_occuption'	=>$father_occuption,
				'father_doc'	=>json_encode($father_doc),
				'mothersalutation'	=>	$mothersalutation,
				'mother_first_name'	=>	$mother_first_name,
				'mother_middle_name'	=>	$mother_middle_name,
				'mother_last_name'	=>	$mother_last_name,
				'mother_gender'	=>$mother_gender,
				'mother_birth_date'	=>$mother_birth_date,
				'mother_address'=>$mother_address,
				'mother_city_name'=>$mother_city_name,
				'mother_state_name'=>$mother_state_name,
				'mother_zip_code'=>$mother_zip_code,
				'mother_email'	=>	$mother_email,
				'mother_mobile'	=>	$mother_mobile,
				'mother_school'	=>	$mother_school,
				'mother_medium'	=>$mother_medium,
				'mother_education'	=>$mother_education,
				'mother_income'	=>$mother_income,
				'mother_occuption'	=>$mother_occuption,
				'mother_doc'	=>json_encode($mother_doc),
				'smgt_user_avatar'	=>$smgt_user_avatar					
			);
	 
			$returnval;
			$user_id = wp_insert_user( $userdata );
			$user = new WP_User($user_id);
			$user->set_role($role);
			foreach($usermetadata as $key=>$val)
			{		
				$returnans=add_user_meta( $user_id, $key,$val, true );		
			}
			$returnval=update_user_meta( $user_id, 'first_name', $first_name );
			$returnval=update_user_meta( $user_id, 'last_name', $last_name );
			$hash = md5( rand(0,1000) );
			$returnval=update_user_meta( $user_id, 'hash', $hash );
			if($user_id)
			{
				//---------- ADMISSION REQUEST MAIL ---------//
				$string = array();
				$string['{{student_name}}']   = mj_smgt_get_display_name($user_id);
				$string['{{user_name}}']   =  $first_name .' '.$last_name;
				$string['{{email}}']   =  $userdata['user_email'];
				$string['{{school_name}}'] =  get_option('smgt_school_name');
				$MsgContent                =  get_option('admission_mailtemplate_content');		
				$MsgSubject				   =  get_option('admissiion_title');
				$message = mj_smgt_string_replacement($string,$MsgContent);
				$MsgSubject = mj_smgt_string_replacement($string,$MsgSubject);
			
				$email= $email;
				mj_smgt_send_mail($email,$MsgSubject,$message);  
				?>
				<span class="admission_successfully_message">
				<?php
				esc_attr_e('Request For Admission Successfully. You will be able to access your account after the school admin approves it.','school-mgt'); 
				?>
				</span>
				<?php
		    }
			return $returnval;	
		}
    }
	else
	{
		die( 'Security check' );
	}
}
function mj_smgt_admission_validation($email,$username,$father_email,$mother_email)  
{
	global $reg_errors;
	$reg_errors = new WP_Error;
	if ( 4 > strlen( $username ) ) {
    $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
	}
	if ( username_exists( $username ) )
		$reg_errors->add('user_name', 'Sorry, that username already exists!');
	if ( ! validate_username( $username ) ) {
    $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
	}
	
	if ( !is_email( $email ) ) {
    $reg_errors->add( 'email_invalid', 'Email is not valid' );
	}
	if ( email_exists( $email ) ) {
    $reg_errors->add( 'email', 'Email Already in use' );
	}
	
	if ( is_wp_error( $reg_errors ) ) {
 
    foreach ( $reg_errors->get_error_messages() as $error ) 
	{
        echo '<div class="student_reg_error">';
        echo '<strong>' . esc_attr__("ERROR","school-mgt"). '</strong> : ';
        echo '<span class="error"> '. esc_attr__("$error","school-mgt"). ' </span><br/>';
        echo '</div>'; 
    }
}	
}

?>