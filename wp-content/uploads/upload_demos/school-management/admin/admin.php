<?php 
 // This is adminside main First page of school management plugin 
add_action( 'admin_menu', 'mj_smgt_school_menu' );
function mj_smgt_school_menu()
{
if (function_exists('mj_smgt_school_menu'))  
	{
		add_menu_page( 'School Management', esc_attr__('School Management','school-mgt'), 'manage_options', 'smgt_school', 'mj_smgt_school_dashboard',plugins_url( 'school-management/assets/images/school-management-system-1.png' ), 7); 
		
		if($_SESSION['cmgt_verify'] == '')
		{ 
			add_submenu_page('smgt_school','Licence Settings',esc_attr__( 'Licence Settings', 'school-mgt' ),'manage_options','smgt_setup','mj_smgt_options_page');
		}
		add_submenu_page('smgt_school', esc_attr__( 'Dashboard', 'school-mgt' ), esc_attr__( 'Dashboard', 'school-mgt' ), 'administrator', 'smgt_school', 'mj_smgt_school_dashboard');

		add_submenu_page('smgt_school',  esc_attr__( 'Admission', 'school-mgt' ), esc_attr__( 'Admission', 'school-mgt' ), 'administrator', 'smgt_admission', 'mj_smgt_student_admission');
		add_submenu_page('smgt_school', esc_attr__( 'Student', 'school-mgt' ), esc_attr__( 'Student', 'school-mgt' ), 'administrator', 'smgt_student', 'mj_smgt_student_detail');
		
		add_submenu_page('smgt_school', esc_attr__( 'Teacher', 'school-mgt' ), esc_attr__( 'Teacher', 'school-mgt' ), 'administrator', 'smgt_teacher', 'mj_smgt_teacher');	
		add_submenu_page('smgt_school', esc_attr__( 'Support Staff', 'school-mgt' ), esc_attr__( 'Support Staff', 'school-mgt' ), 'administrator', 'smgt_supportstaff', 'mj_smgt_supportstaff');
		add_submenu_page('smgt_school',  esc_attr__( 'Parent', 'school-mgt' ), esc_attr__( 'Parent', 'school-mgt' ), 'administrator', 'smgt_parent', 'mj_smgt_parent');
		add_submenu_page('smgt_school',  esc_attr__( 'Subject', 'school-mgt' ), esc_attr__( 'Subject', 'school-mgt' ), 'administrator', 'smgt_Subject', 'mj_smgt_subject');
		add_submenu_page('smgt_school',  esc_attr__( 'Class', 'school-mgt' ), esc_attr__( 'Class', 'school-mgt' ), 'administrator', 'smgt_class', 'mj_smgt_class');
		if (get_option('smgt_enable_virtual_classroom') == 'yes')
		{
			add_submenu_page('smgt_school',  esc_attr__( 'Virtual Classroom', 'school-mgt' ), esc_attr__( 'Virtual Classroom', 'school-mgt' ), 'administrator', 'smgt_virtual_classroom', 'mj_smgt_virtual_classroom');
		}
		add_submenu_page('smgt_school', esc_attr__( 'Class Routine', 'school-mgt' ), esc_attr__( 'Class Routine', 'school-mgt' ), 'administrator', 'smgt_route', 'mj_smgt_route');
		add_submenu_page('smgt_school', esc_attr__( ' Attendance', 'school-mgt' ), esc_attr__( ' Attendance', 'school-mgt' ), 'administrator', 'smgt_attendence', 'mj_smgt_attendence');
		add_submenu_page('smgt_school',  esc_attr__('Exam', 'school-mgt' ), esc_attr__('Exam', 'school-mgt' ), 'administrator', 'smgt_exam', 'mj_smgt_exam');
		add_submenu_page('smgt_school', esc_attr__( 'Exam Hall', 'school-mgt' ), esc_attr__( 'Exam Hall', 'school-mgt' ), 'administrator', 'smgt_hall', 'mj_smgt_hall');
		add_submenu_page('smgt_school', esc_attr__( 'Grade', 'school-mgt' ), esc_attr__( 'Grade', 'school-mgt' ), 'administrator', 'smgt_grade', 'mj_smgt_grade');
		add_submenu_page('smgt_school', esc_attr__( 'Manage Marks', 'school-mgt' ), esc_attr__( 'Manage Marks', 'school-mgt' ), 'administrator', 'smgt_result', 'mj_smgt_result');
		
		add_submenu_page('smgt_school', esc_attr__( 'Homework', 'school-mgt' ), esc_attr__( 'Homework', 'school-mgt' ), 'administrator', 'smgt_student_homewrok', 'mj_smgt_student_homewrok');
		add_submenu_page('smgt_school',  esc_attr__( 'Hostel', 'school-mgt' ), esc_attr__( 'Hostel', 'school-mgt' ), 'administrator', 'smgt_hostel', 'mj_smgt_hostel');
		
		add_submenu_page('smgt_school', esc_attr__( 'Transport', 'school-mgt' ), esc_attr__( 'Transport', 'school-mgt' ), 'administrator', 'smgt_transport', 'mj_smgt_transport');
		add_submenu_page('smgt_school', esc_attr__( 'Notice', 'school-mgt' ), esc_attr__( 'Notice', 'school-mgt' ), 'administrator', 'smgt_notice', 'mj_smgt_notice');
		add_submenu_page('smgt_school',  esc_attr__( 'Message', 'school-mgt' ), esc_attr__( 'Message', 'school-mgt' ), 'administrator', 'smgt_message', 'mj_smgt_message');	
		add_submenu_page('smgt_school',  esc_attr__( 'Notification', 'school-mgt' ), esc_attr__( 'Notification', 'school-mgt' ), 'administrator', 'smgt_notification', 'mj_smgt_notification');
		
		add_submenu_page('smgt_school', esc_attr__( 'Fees Payment', 'school-mgt' ), esc_attr__( 'Fees Payment', 'school-mgt' ), 'administrator', 'smgt_fees_payment', 'mj_smgt_fees_payment');
		add_submenu_page('smgt_school',  esc_attr__( 'Payment', 'school-mgt' ), esc_attr__( 'Payment', 'school-mgt' ), 'administrator', 'smgt_payment', 'mj_smgt_payment');
		add_submenu_page('smgt_school', esc_attr__( 'Holiday', 'school-mgt' ), esc_attr__( 'Holiday', 'school-mgt' ), 'administrator', 'smgt_holiday', 'mj_smgt_holiday');
		add_submenu_page('smgt_school', esc_attr__( 'Library', 'school-mgt' ), esc_attr__( 'Library', 'school-mgt' ), 'administrator', 'smgt_library', 'mj_smgt_library');
		add_submenu_page('smgt_school', esc_attr__( 'Custom Fields', 'school-mgt' ), esc_attr__( 'Custom Fields', 'school-mgt' ), 'administrator', 'custom_field', 'mj_smgt_custom_field');
		add_submenu_page('smgt_school', esc_attr__( 'Report', 'school-mgt' ), esc_attr__( 'Report', 'school-mgt' ), 'administrator', 'smgt_report', 'mj_smgt_report');
		add_submenu_page('smgt_school', esc_attr__( 'Migration', 'school-mgt' ), esc_attr__( 'Migration', 'school-mgt' ), 'administrator', 'smgt_Migration', 'mj_smgt_migarion');
		add_submenu_page('smgt_school', esc_attr__( 'SMS Setting', 'school-mgt' ), esc_attr__( 'SMS Setting', 'school-mgt' ), 'administrator', 'smgt_sms-setting', 'mj_smgt_sms_setting');
		
		add_submenu_page('smgt_school',esc_attr__('Email Template','school-mgt'),esc_attr__('Email Template','school-mgt'),'administrator','smgt_email_template','mj_smgt_email_template');

		add_submenu_page('smgt_school',esc_attr__('Access Right','school-mgt'),esc_attr__('Access Right','school-mgt'),'administrator','smgt_access_right','mj_smgt_access_right');
		
		add_submenu_page('smgt_school',  esc_attr__( 'General Settings', 'school-mgt' ), esc_attr__( 'General Settings', 'school-mgt' ), 'administrator', 'smgt_gnrl_settings', 'mj_smgt_gnrl_settings');
	}  
	else
	{ 		 		
		die;
	}
}

function mj_smgt_options_page()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/setupform/index.php';
}
function mj_smgt_school_dashboard()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/dasboard.php';
	
}
function mj_smgt_student_admission()
 {
	require_once SMS_PLUGIN_DIR. '/admin/includes/admission/index.php';
}	
 function mj_smgt_student_detail()
 {
	require_once SMS_PLUGIN_DIR. '/admin/includes/student/index.php';
}
 function mj_smgt_gnrl_settings()
 {
	require_once SMS_PLUGIN_DIR. '/admin/includes/general-settings.php';
}
function mj_smgt_subject()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/subject/index.php';
} 
function mj_smgt_teacher()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/teacher/index.php';
}
function mj_smgt_supportstaff()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/supportstaff/index.php';
}
function mj_smgt_parent()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/parent/index.php';
}
function mj_smgt_class()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/class/index.php';
}
function mj_smgt_virtual_classroom()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/virtual_classroom/index.php';
}
function mj_smgt_grade()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/grade/index.php'; }
function mj_smgt_exam()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/exam/index.php';}
function mj_smgt_result()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/mark/index.php';}
function mj_smgt_attendence()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/attendence/index.php';}
function mj_smgt_message()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/message/index.php';}
function mj_smgt_notice()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/notice/index.php';}
function mj_smgt_transport()
{require_once SMS_PLUGIN_DIR. '/admin/includes/transport/index.php';}
function mj_smgt_hall()
{require_once SMS_PLUGIN_DIR. '/admin/includes/hall/index.php';}
function mj_smgt_fees()
{require_once SMS_PLUGIN_DIR. '/admin/includes/fees/index.php';}
function mj_smgt_fees_payment()
{require_once SMS_PLUGIN_DIR. '/admin/includes/feespayment/index.php';}
function mj_smgt_payment()
{require_once SMS_PLUGIN_DIR. '/admin/includes/payment/index.php';}
function mj_smgt_holiday()
{require_once SMS_PLUGIN_DIR. '/admin/includes/holiday/index.php';}
function mj_smgt_route()
{require_once SMS_PLUGIN_DIR. '/admin/includes/routine/index.php';}
function mj_smgt_report()
{require_once SMS_PLUGIN_DIR. '/admin/includes/report/index.php';}
function mj_smgt_library()
{require_once SMS_PLUGIN_DIR. '/admin/includes/library/index.php';}
function mj_smgt_migarion()
{require_once SMS_PLUGIN_DIR. '/admin/includes/migration/index.php';}
function mj_smgt_sms_setting()
{require_once SMS_PLUGIN_DIR. '/admin/includes/sms_setting/index.php';}
function mj_smgt_email_template()
{require_once SMS_PLUGIN_DIR. '/admin/includes/email-template/index.php';}
function mj_smgt_notification()
{ require_once SMS_PLUGIN_DIR. '/admin/includes/notification/index.php';}	
function mj_smgt_show_infographic()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/infographic/index.php';
}
function mj_smgt_access_right()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/access_right/index.php';
}

function mj_smgt_student_homewrok()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/student_HomeWork/index.php';
}
function mj_smgt_hostel()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/hostel/index.php';
}
function mj_smgt_custom_field()
{
	require_once SMS_PLUGIN_DIR. '/admin/includes/customfield/index.php';
}
?>