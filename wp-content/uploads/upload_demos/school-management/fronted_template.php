<?php
// render template//
global $current_user;
$user_roles = $current_user->roles;
$user_role = array_shift($user_roles);
if($user_role != 'teacher' && $user_role != 'student'  && $user_role != 'parent'  && $user_role != 'supportstaff')
{ 
	wp_redirect ( admin_url () . 'admin.php?page=smgt_school' );
	exit;
}
if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'pdf')
{
	$sudent_id = $_REQUEST['student'];
	mj_smgt_downlosd_smgt_result_pdf($sudent_id);
}
$obj_attend = new Attendence_Manage ();
$school_obj = new School_Management ( get_current_user_id () );
$obj_route = new Class_routine ();
$obj_virtual_classroom = new mj_smgt_virtual_classroom();
$notive_array = array ();

if($school_obj->role=='student')
{
	$class = $school_obj->class_info;
    $sectionname="";
    $section=0;
    $section = get_user_meta(get_current_user_id(),'class_section',true);
	if($section != "")
	{
		$sectionname = mj_smgt_get_section_name($section);
	}
	else
	{
		$section=0;
	}
	foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )
	{
		$period = $obj_route->mj_smgt_get_periad($class->class_id,$section,$daykey );
		if(!empty( $period ))
		{
			foreach ( $period as $period_data )
			{
				if (get_option('smgt_enable_virtual_classroom') == 'yes')
				{
					$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);
					if (!empty($meeting_data))
					{
						$color = 'rgb(46, 138, 194)';
					}
					else
					{
						$color = 'rgb(91,112,222)';
					}
				}
				else
				{
					$meeting_data = '';
					$color = 'rgb(91,112,222)';
				}
				$teacher_obj = new Smgt_Teacher;
				$classes = $teacher_obj->mj_smgt_get_singal_class_teacher($period_data->class_id);
				$stime = explode(":",$period_data->start_time);
				$start_hour=str_pad($stime[0],2,"0",STR_PAD_LEFT);
				$start_min=str_pad($stime[1],2,"0",STR_PAD_LEFT);
				$start_am_pm=$stime[2];
				$start_time = $start_hour.':'.$start_min.' '.$start_am_pm;
				$start_time_data = new DateTime($start_time); 
		   		$starttime=date_format($start_time_data,'H:i:s');	

		   		$etime = explode(":",$period_data->end_time);
				$end_hour=str_pad($etime[0],2,"0",STR_PAD_LEFT);
				$end_min=str_pad($etime[1],2,"0",STR_PAD_LEFT);
				$end_am_pm=$etime[2];
				$end_time = $end_hour.':'.$end_min.' '.$end_am_pm;
				$end_time_data = new DateTime($end_time); 
		   		$edittime=date_format($end_time_data,'H:i:s');
				$user = get_userdata( $classes->teacher_id );
				$cal_array [] = array (
				'type' =>  'class',
				'title' => mj_smgt_get_single_subject_name($period_data->subject_id),
				'class_name' => mj_smgt_get_class_name( $period_data->class_id ),
				'subject' => mj_smgt_get_single_subject_name($period_data->subject_id),
				'start' => $starttime,
				'end' => $edittime,
				'agenda' => $meeting_data->agenda,
				'teacher' => $user->display_name,
				'role' => 'student',
				'meeting_start_link' => $meeting_data->meeting_start_link,
				'meeting_join_link' => $meeting_data->meeting_join_link,
				'dow' => [ $daykey ] ,
				'color' => $color
				);
			}
		}
	}
    $class_name = $school_obj->class_info->class_id;
	$class_section = $school_obj->class_info->class_section;
	$notice_list_student = mj_smgt_student_notice_dashbord($class_name,$class_section);
	if (! empty ($notice_list_student)) 
	{
		foreach ($notice_list_student as $notice )
		{
			$notice_start_date=get_post_meta($notice->ID,'start_date',true);
			$notice_end_date=get_post_meta($notice->ID,'end_date',true);
				$i=1;
				$cal_array [] = array (
						'type' =>  'notice',
						'title' => $notice->post_title,
						'start' => mysql2date('Y-m-d', $notice_start_date ),
						'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days'))
				);	
		}
	}
	
}
if($school_obj->role=='parent')
{
	$chil_array =$school_obj->child_list;
	if(!empty($chil_array))
	{
		foreach($chil_array as $child_id)
		{
			$sectionname="";
			$section=0;
			$class = $school_obj->mj_smgt_get_user_class_id($child_id);
			$section = get_user_meta($child_id,'class_section',true);
			if($section!="")
			{
				$sectionname = mj_smgt_get_section_name($section);
			}
			else
			{
				$section=0;
			}
			foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )
			{
				$period = $obj_route->mj_smgt_get_periad($class->class_id,$section,$daykey );
				if(!empty( $period ))
				{
					foreach ( $period as $period_data )
					{
						if (get_option('smgt_enable_virtual_classroom') == 'yes')
						{
							$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);
							if (!empty($meeting_data))
							{
								$color = 'rgb(46, 138, 194)';
							}
							else
							{
								$color = 'rgb(91,112,222)';
							}
						}
						else
						{
							$meeting_data = '';
							$color = 'rgb(91,112,222)';
						}
						$teacher_obj = new Smgt_Teacher;
						$classes = $teacher_obj->mj_smgt_get_singal_class_teacher($period_data->class_id);
						$stime = explode(":",$period_data->start_time);
						$start_hour=str_pad($stime[0],2,"0",STR_PAD_LEFT);
						$start_min=str_pad($stime[1],2,"0",STR_PAD_LEFT);
						$start_am_pm=$stime[2];
						$start_time = $start_hour.':'.$start_min.' '.$start_am_pm;
						$start_time_data = new DateTime($start_time); 
				   		$starttime=date_format($start_time_data,'H:i:s');	

				   		$etime = explode(":",$route_data->end_time);
						$end_hour=str_pad($etime[0],2,"0",STR_PAD_LEFT);
						$end_min=str_pad($etime[1],2,"0",STR_PAD_LEFT);
						$end_am_pm=$etime[2];
						$end_time = $end_hour.':'.$end_min.' '.$end_am_pm;
						$end_time_data = new DateTime($end_time); 
				   		$edittime=date_format($end_time_data,'H:i:s');
						$user = get_userdata( $classes->teacher_id );
						$cal_array [] = array (
						'type' =>  'class',
						'title' => mj_smgt_get_single_subject_name($period_data->subject_id),
						'class_name' => mj_smgt_get_class_name( $period_data->class_id ),
						'subject' => mj_smgt_get_single_subject_name($period_data->subject_id),
						'start' => $starttime,
						'end' => $edittime,
						'agenda' => $meeting_data->agenda,
						'teacher' => $user->display_name,
						'role' => 'parent',
						'meeting_start_link' => $meeting_data->meeting_start_link,
						'meeting_join_link' => $meeting_data->meeting_join_link,
						'dow' => [ $daykey ] ,
						'color' => $color
						);
					}
				}
			}
		}
	}
	$notice_list_parent = mj_smgt_parent_notice_dashbord();
	if (!empty ($notice_list_parent)) 
	{
	    
		foreach ($notice_list_parent as $notice )
		{
			$notice_start_date=get_post_meta($notice->ID,'start_date',true);
			$notice_end_date=get_post_meta($notice->ID,'end_date',true);
			//echo $notice->post_title;
				$i=1;
				
				$cal_array [] = array (
						'type' =>  'notice',
						'title' => $notice->post_title,
						'start' => mysql2date('Y-m-d', $notice_start_date ),
						'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days'))
				);	
		}
	}
}
if($school_obj->role=='supportstaff')
{

	$notice_list_supportstaff = mj_smgt_supportstaff_notice_dashbord();
	if (! empty ($notice_list_supportstaff)) 
	{
		foreach ($notice_list_supportstaff as $notice ) 
		{
			$notice_start_date=get_post_meta($notice->ID,'start_date',true);
			$notice_end_date=get_post_meta($notice->ID,'end_date',true);
			//echo $notice->post_title;
				$i=1;
				
				$cal_array [] = array (
						'type' =>  'notice',
						'title' => $notice->post_title,
						'start' => mysql2date('Y-m-d', $notice_start_date ),
						'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days'))
				);	
		}
	}
}
if($school_obj->role=='teacher')
{
    $class_name = $school_obj->class_info->class_id;
	$class_section = $school_obj->class_info->class_section;
	$class_name = $school_obj->class_info->class_id;
	$class_section = $school_obj->class_info->class_section;
	$notice_list_teacher = mj_smgt_teacher_notice_dashbord($class_name);		
	foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )
	{
		$period = $obj_route->mj_smgt_get_periad_by_teacher(get_current_user_id(),$daykey);
		if(!empty( $period ))
		{
			foreach ( $period as $period_data )
			{
				if (get_option('smgt_enable_virtual_classroom') == 'yes')
				{
					$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);
					if (!empty($meeting_data))
					{
						$color = 'rgb(46, 138, 194)';
					}
					else
					{
						$color = 'rgb(91,112,222)';
					}
				}
				else
				{
					$meeting_data = '';
					$color = 'rgb(91,112,222)';
				}
				$stime = explode(":",$period_data->start_time);
				$start_hour=str_pad($stime[0],2,"0",STR_PAD_LEFT);
				$start_min=str_pad($stime[1],2,"0",STR_PAD_LEFT);
				$start_am_pm=$stime[2];
				$start_time = $start_hour.':'.$start_min.' '.$start_am_pm;
				$start_time_data = new DateTime($start_time); 
		   		$starttime=date_format($start_time_data,'H:i:s');	

		   		$etime = explode(":",$route_data->end_time);
				$end_hour=str_pad($etime[0],2,"0",STR_PAD_LEFT);
				$end_min=str_pad($etime[1],2,"0",STR_PAD_LEFT);
				$end_am_pm=$etime[2];
				$end_time = $end_hour.':'.$end_min.' '.$end_am_pm;
				$end_time_data = new DateTime($end_time); 
		   		$edittime=date_format($end_time_data,'H:i:s');
				$user = get_userdata( $period_data->teacher_id );
				$cal_array [] = array (
				'type' =>  'class',
				'title' => mj_smgt_get_single_subject_name($period_data->subject_id),
				'class_name' => mj_smgt_get_class_name( $period_data->class_id ),
				'subject' => mj_smgt_get_single_subject_name($period_data->subject_id),
				'start' => $starttime,
				'end' => $edittime,
				'agenda' => $meeting_data->agenda,
				'teacher' => $user->display_name,
				'role' => 'teacher',
				'meeting_start_link' => $meeting_data->meeting_start_link,
				'dow' => [ $daykey ] ,
				'color' => $color
				);
			}
		}
	}

	if (! empty ($notice_list_teacher)) {
		    
			foreach ($notice_list_teacher as $notice ) 
			{
				$notice_start_date=get_post_meta($notice->ID,'start_date',true);
				$notice_end_date=get_post_meta($notice->ID,'end_date',true);
					$i=1;
					
					$cal_array [] = array (
							'type' =>  'notice',
							'title' => $notice->post_title,
							'start' => mysql2date('Y-m-d', $notice_start_date ),
							'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days'))
					);	
			}
		}
}

$holiday_list = mj_smgt_get_all_data( 'holiday' );
if (! empty ( $holiday_list )) {
	foreach ( $holiday_list as $notice ) {
		$notice_start_date=$notice->date;
		$notice_end_date=$notice->end_date;
		$i=1;
			
		$cal_array [] = array (
				'type' =>  'holiday',
				'title' => $notice->holiday_title,
				'start' => mysql2date('Y-m-d', $notice_start_date ),
				'end' => date('Y-m-d',strtotime($notice_end_date.' +'.$i.' days')),
				'color' => 'rgb(91,192,222)'
		);
	}
}
if (! is_user_logged_in ()) {
	$page_id = get_option ( 'smgt_login_page' );
	
	wp_redirect ( home_url () . "?page_id=" . $page_id );
}
if (is_super_admin ()) { 
		wp_redirect ( admin_url () . 'admin.php?page=smgt_school' );
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/bootstrap-multiselect.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/dataTables.min.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/jquery.dataTables.min.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/jquery-ui.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/font-awesome.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/popup.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/style.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/dashboard.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/fullcalendar.min.css'; ?>">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/bootstrap5.min.css'; ?>">	
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/white.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/schoolmgt.min.css'; ?>">
<?php if (is_rtl()) 
{?>
	<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/bootstrap-rtl.min.css'; ?>">
	<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/custome_rtl.css'; ?>">
	<?php  
}?>
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/simple-line-icons.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/css/validationEngine.jquery.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/school-responsive.css'; ?>">
<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/dataTables.responsive.css'; ?>">


<?php 
if(@file_exists(get_stylesheet_directory().'/css/smgt-customcss.css')) {
	?>
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/smgt-customcss.css" type="text/css" />
	<?php 
}
else 
{
	?>
	<link rel="stylesheet"	href="<?php echo SMS_PLUGIN_URL.'/assets/css/smgt-customcss.css'; ?>">
	<?php 
}
?>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/jquery-3.6.0.min.js'; ?>"></script>

<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/popper.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/bootstrap-multiselect.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/jquery.timeago.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/jquery-ui.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/moment.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/fullcalendar.min.js'; ?>"></script>
<?php /*--------Full calendar multilanguage---------*/
	$lancode=get_locale();
	$code=substr($lancode,0,2);?>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/calendar-lang/'.$code.'.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/datatables.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/jquery.dataTables.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/dataTables.tableTools.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/dataTables.editor.min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/dataTables.responsive.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/bootstrap5.min.js'; ?>"></script>
<!-- Print -->
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/smgt-dataTables-buttons-min.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/assets/js/smgt-buttons-print-min.js'; ?>"></script>
<!--  -->


<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo SMS_PLUGIN_URL.'/lib/validationEngine/js/jquery.validationEngine.js'; ?>"></script>


<script>
	$ = jQuery.noConflict();
   var calendar_laungage ="<?php echo mj_smgt_calander_laungage();?>";
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

		events: <?php echo json_encode($cal_array);?>,
		
	});
		calendar.render();
	});

	
</script>
 
</head>
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
<!-- CLASS BOOK IN CALANDER POPUP HTML CODE -->
<div id="eventContent" class="modal-body display_none height_auto"><!--MODAL BODY DIV START-->
	
	<p class="margin_0px"><b><?php esc_html_e('Class Name:','school-mgt');?></b> <span id="class_name"></span></p><br>
	<p class="margin_0px"><b><?php esc_html_e('Subject:','school-mgt');?></b> <span id="subject"></span></p><br>
	<p class="margin_0px"><b><?php esc_html_e('Date:','school-mgt');?> </b> <span id="date"></span></p><br>
	<p class="margin_0px"><b><?php esc_html_e('Time:','school-mgt');?> </b> <span id="time"></span></p><br>
	<p class="margin_0px"><b><?php esc_html_e('Teacher Name:','school-mgt');?></b> <span id="teacher_name"></span></p><br>
	<p id="agenda" class="class_schedule_topic margin_0px"></p><br>
	<p id="meeting_start_link" class="margin_0px"></p>
</div>
<!--MODAL BODY DIV END-->
<body class="schoo-management-content">
  <?php
		$user = wp_get_current_user ();
		?>
  <div class="container-fluid mainpage">
  
  <div class="navbar row">	
	<div class="col-md-8 col-sm-8 col-xs-6 navbar_row_with_50">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle rounded-circle head_logo" width="40" height="40" />
		<span><?php echo get_option( 'smgt_school_name' );?> </span>
		</h3>
	</div>
	<div class="col-md-4 col-sm-4 col-xs-6 full_with_50_per">	
		<ul class="nav navbar-right col-xs-6">				
			<li class="dropdown ms-auto">
			<!-- <a id="dropdownMenufront" data-toggle="dropdown" aria-expanded="false"	class="dropdown-toggle" href="javascript:;"> -->
			<a id="dropdownMenufront" data-toggle="dropdown" class="border-0 btn btn-default dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
			<?php
				$umetadata = mj_smgt_get_user_image ( $user->ID );
				if (empty ( $umetadata )){
					echo '<img src='.get_option( 'smgt_student_thumb' ).' height="40px" width="40px" class="rounded-circle" />';
				}
				else
					echo '<img src=' . $umetadata . ' height="40px" width="40px" class="img-circle rounded-circle">';
				?>
				<span><?php echo $user->display_name;?> </span> <b class="caret"></b>
			</a>
			<ul class="dropdown-menu extended logout ms-auto" aria-labelledby="dropdownMenufront">
				<li><a class="dropdown-item" href="?dashboard=user&page=account"><i class="fa fa-user"></i>
					<?php esc_attr_e('My Profile','school-mgt');?></a></li>
				<li><a class="dropdown-item" href="<?php echo wp_logout_url(home_url()); ?>"><i class="fa fa-sign-out-alt m-r-xs"></i><?php esc_attr_e('Log Out','school-mgt');?> </a></li>
			</ul>
			</li><!-- END USER LOGIN DROPDOWN -->
		</ul>
		</div>
	</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-2 col-md-2 nopadding school_left nav-side-menu">
				<div class="brand"><?php esc_attr_e('Menu','school-mgt');?>
					<i class="fa fa-bars fa-2x toggle-btn avbar-toggler"  data-bs-toggle="collapse" data-bs-target="#menu-content" aria-expanded="false" ></i>
				</div>
<?php
$role = $school_obj->role;
	if($role=='student')
	{
		$menu = get_option( 'smgt_access_right_student');
	}
	elseif($role=='parent')
	{
		$menu = get_option( 'smgt_access_right_parent');
	}
	elseif($role=='supportstaff')
	{
		$menu = get_option( 'smgt_access_right_supportstaff');
	}
	elseif($role=='teacher')
	{
		$menu = get_option( 'smgt_access_right_teacher');
	}
	$class = "";
	if (! isset ( $_REQUEST ['page'] ))	
		$class = "active"; 
?>
	<ul class="nav nav-pills nav-stacked collapse in responsive_menu" id="menu-content">  
		<li><a href="<?php echo site_url();?>" class="text-decoration-none"><span class="icone"><img class="h-auto w-18-px" src="<?php echo SMS_PLUGIN_URL .'/assets/images/icons/home.png'?>"/></span><span class="title"><?php esc_attr_e('Home','school-mgt');?></span></a></li>
		<li <?php echo $class;?>><a href="?dashboard=user" class="text-decoration-none"><span class="icone"><img class="h-auto w-18-px" src="<?php echo SMS_PLUGIN_URL .'/assets/images/icons/dashboard.png'?>"/></span>
			<span class="title"><?php esc_attr_e('Dashboard','school-mgt');?></span></a> </li>
        <?php
			$role = $school_obj->role;
			$access_page_view_array=array();	
			foreach ($menu as $key1=>$value1) 
			{

				foreach ( $value1 as $key=>$value ) 
				{
					if($value['view']=='1')
					{
						if(get_option('smgt_enable_virtual_classroom') == 'no')
						{
							if($key == 'virtual_classroom')
							{
								
								$menu_class = 'display_none_dashboard';
							}
							else
							{
								$menu_class = '';
							}
						}
						$access_page_view_array[]=$value ['page_link'];
						if($key == 'schedule')
						{
							if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $value ['page_link'])
								$class = "active";
							else
								$class = "";
								echo '<li class="'.$class.' '.$menu_class.'"><a href="?dashboard=user&page=' . $value ['page_link'] . '" class="text-decoration-none"><span class="icone"> <img class="h-auto w-18-px"  src="' .$value ['menu_icone'].'" /></span><span class="title class_routine">'.mj_smgt_change_menutitle($key).'</span></a></li>';	
						}
						elseif($key == 'message')
						{
							if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $value ['page_link'])
								$class = "active";
							else
								$class = "";
								echo '<li class="'.$class.' '.$menu_class.'"><a href="?dashboard=user&page=' . $value ['page_link'] . '" class="text-decoration-none"><span class="icone"> <img class="h-auto w-18-px"  src="' .$value ['menu_icone'].'" /></span><span class="title class_routine">'.mj_smgt_change_menutitle($key).'</span><span class="badge badge-success">'.mj_smgt_count_unread_message(get_current_user_id()).'</span></a></li>';	
						} 
						
						else
						{
							if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $value ['page_link'])
								$class = "active";
							else
								$class = "";
							echo '<li class="'.$class.' '.$menu_class.'"><a href="?dashboard=user&page=' . $value ['page_link'] . '" class="text-decoration-none"><span class="icone"> <img class="h-auto w-18-px"  src="' .$value ['menu_icone'].'" /></span><span class="title">'.mj_smgt_change_menutitle($key).'</span></a></li>';	
							
						}
					}
				}
			}
		?>							
    </ul>
</div>
		
	<div class="page-inner full_with_100_per">
	<div id="main-wrapper_fronend">
	<div class="right_side <?php if(isset($_REQUEST['page']))echo $_REQUEST['page'];?>">
   <?php
		if (isset ( $_REQUEST ['page'] )) 
		{
			if(in_array($_REQUEST ['page'],$access_page_view_array))
			{	
				require_once(SMS_PLUGIN_DIR . '/template/' . $_REQUEST ['page'] . '.php');			
				return false;
			} 
			else
			{
				?><h2><?php print "404 ! Page did not found."; die;?></h2><?php
			}
		}
	?>
	<div class="row">
		<?php
			$page='student';
			$student=mj_smgt_page_access_rolewise_accessright_dashboard($page);
			if($student==1)
			{
			?>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<a class="anchor_css" href="<?php print site_url().'/?dashboard=user&page=student' ?>">
						<div class="panel info-box panel-white">
							<div class="panel-body student">
								<span class="info-box-icon bg-aqua">
									<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/student.png"?>"></i>
								</span>
								<div class="info-box-stats">
									<?php
									$user_student_access=mj_smgt_get_userrole_wise_filter_access_right_array('student');	
									if($school_obj->role == 'student')
									{
										if($user_student_access['own_data'] == '1')
										{ 
											$student_count =1;
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'student' ) );
											$student_count = (int) $user_query->get_total();
										}
									}
									elseif($school_obj->role == 'teacher')
									{
										if($user_student_access['own_data'] == '1')
										{ 
											$user_id=get_current_user_id();		
											$class_id=get_user_meta($user_id,'class_name',true);
											$studentdata=$school_obj->mj_smgt_get_teacher_student_list($class_id);
											$student_count =count($studentdata);
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'student' ) );
											$student_count = (int) $user_query->get_total();
										}
									}
									//------- STUDENT DATA FOR PARENT ---------//
									elseif($school_obj->role == 'parent')
									{
										if($user_student_access['own_data'] == '1')
										{ 
											$child_data = $school_obj->child_list;
											$student_count =count($child_data);
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'student' ) );
											$student_count = (int) $user_query->get_total();
										}
									}
									else
									{
										if($user_student_access['own_data'] == '1')
										{ 
											$studentdata= get_users(
												 array(
														'role' => 'student',
														'meta_query' => array(
														array(
																'key' => 'created_by',
																'value' => $user_id,
																'compare' => '='
															)
														)
												));	
											$student_count =count($studentdata);
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'student' ) );
											$student_count = (int) $user_query->get_total();
										}
									}
									?>
									<span class="info-box-title all_box"><?php echo esc_html( esc_attr__( 'Students', 'school-mgt' ) );?></span>
									<p class="counter"><?php echo $student_count;?></p>
								</div>
							</div>
						</div>
					</a>
				</div>
	<?php 	}
			$page='teacher';
			$teacher=mj_smgt_page_access_rolewise_accessright_dashboard($page);
			if($teacher==1)
			{
			?>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<a class="anchor_css" href="<?php print site_url().'/?dashboard=user&page=teacher' ?>">
						<div class="panel info-box panel-white">
							<div class="panel-body teacher">
								<span class="info-box-icon bg-aqua">
									<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/teacher.png"?>"></i>
								</span>
								<div class="info-box-stats">
									<?php
									$user_teacher_access=mj_smgt_get_userrole_wise_filter_access_right_array('teacher');	
									//------- TEACHER DATA FOR STUDENT ---------//
									if($school_obj->role == 'student')
									{
										if($user_teacher_access['own_data'] == '1')
										{ 
											$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);			
											$teacherdata	= 	mj_smgt_get_teacher_by_class_id($class_id);	
											$teacher_count = count($teacherdata);
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'teacher' ) );
											$teacher_count = (int) $user_query->get_total();
										}
									}
									//------- TEACHER DATA FOR TEACHER ---------//
									elseif($school_obj->role == 'teacher')
									{
										if($user_teacher_access['own_data'] == '1')
										{ 
											$teacher_count =1;
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'teacher' ) );
											$teacher_count = (int) $user_query->get_total();
										}
									}
									//------- TEACHER DATA FOR PARENT ---------//
									elseif($school_obj->role == 'parent')
									{
										$teacherdata_data=array();
										$child 	= 	get_user_meta(get_current_user_id(),'child',true);
										foreach($child as $c_id)
										{
											$class_id 	= 	get_user_meta($c_id,'class_name',true);
											$teacherdata_data1	= 	mj_smgt_get_teacher_by_class_id($class_id);	
											$teacherdata_data = array_merge($teacherdata_data,$teacherdata_data1);
										}
										if($user_teacher_access['own_data'] == '1')
										{ 
											$teacherdata_created_by= get_users(
												 array(
														'role' => 'teacher',
														'meta_query' => array(
														array(
																'key' => 'created_by',
																'value' => $user_id,
																'compare' => '='
															)
														)
												));	
											$teacherdata=array_merge($teacherdata_data,$teacherdata_created_by);
											$teacher_count = count($teacherdata);
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'teacher' ) );
											$teacher_count = (int) $user_query->get_total();
										}
									}	
									//------- TEACHER DATA FOR SUPPORT STAFF ---------//
									else
									{ 
										$own_data=$user_access['own_data'];
										if($own_data == '1')
										{ 
											$teacherdata_created_by= get_users(
												 array(
														'role' => 'teacher',
														'meta_query' => array(
														array(
																'key' => 'created_by',
																'value' => $user_id,
																'compare' => '='
															)
														)
												));	
											$teacherdata=$teacherdata_created_by;
											$teacher_count = count($teacherdata);											
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'teacher' ) );
											$teacher_count = (int) $user_query->get_total();
										}
									}
									?>
									<span class="info-box-title all_box"><?php echo esc_html( esc_attr__( 'teachers', 'school-mgt' ) );?></span>
									<p class="counter"><?php echo $teacher_count;?></p>
								</div>
							</div>
						</div>
					</a>
				</div>
			<?php
			} 
			$page='parent';
			$parent=mj_smgt_page_access_rolewise_accessright_dashboard($page);
			if($parent==1)
			{ 
			?>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<a class="anchor_css" href="<?php print site_url().'/?dashboard=user&page=parent' ?>">
						<div class="panel info-box panel-white">
							<div class="panel-body parent">
								<span class="info-box-icon bg-aqua">
									<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/parents.png"?>"></i>
								</span>
								<div class="info-box-stats">
									<?php
									$user_parent_access=mj_smgt_get_userrole_wise_filter_access_right_array('parent');	
									//------- PARENT DATA FOR STUDENT ---------//
									if($school_obj->role == 'student')
									{
										if($user_parent_access['own_data'] == '1')
										{ 
											$parentdata1=$school_obj->parent_list;
											if (!empty($parentdata1))
											{
												foreach($parentdata1 as $pid)
												{
													$parentdata[]=get_userdata($pid);
												}
												$parent_count = count($parentdata);
											}
											else
											{
												$parent_count = 0;
											}
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'parent' ) );
											$parent_count = (int) $user_query->get_total();
										}
									}
									//------- PARENT DATA FOR TEACHER ---------//
									elseif($school_obj->role == 'teacher')
									{
										$parentdata=mj_smgt_get_usersdata('parent');
										$parent_count = count($parentdata);
									}
									//------- PARENT DATA FOR PARENT ---------//
									elseif($school_obj->role == 'parent')
									{
										if($user_parent_access['own_data'] == '1')
										{ 
											$parent_count =1;	
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'parent' ) );
											$parent_count = (int) $user_query->get_total();
										}
									}
									//------- PARENT DATA FOR SUPPORT STAFF ---------//
									else
									{ 
										if($user_parent_access['own_data'] == '1')
										{
											$parentdata= get_users(
												array(
														'role' => 'parent',
														'meta_query' => array(
														array(
																'key' => 'created_by',
																'value' => $user_id,
																'compare' => '='
															)
														)
												));	
											$parent_count = count($parentdata);
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'parent' ) );
											$parent_count = (int) $user_query->get_total();
										}
									}									
									?>
									<span class="info-box-title all_box"><?php echo esc_html( esc_attr__( 'parents', 'school-mgt' ) );?></span>
									<p class="counter"><?php echo $parent_count;?></p>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
	<div class="row">


		<?php
			}
			$attendance=get_option('smgt_enable_total_attendance');
			if($attendance==1)
			{
		?>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<?php if($school_obj->role=='teacher')
				{ ?>
					<a href="<?php print site_url().'/?dashboard=user&page=attendance' ?>">
				<?php 
				}
				?>
					<div class="panel info-box panel-white">
						<div class="panel-body attendence">
							<span class="info-box-icon bg-aqua">
								<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/attendance.png"?>"></i>
							</span>
							<div class="info-box-stats">
								<span class="info-box-title all_box"><?php echo esc_html( esc_attr__( 'Today Attendance', 'school-mgt' ) );?></span>
								<p class="counter"><?php echo $obj_attend->mj_smgt_today_presents();?></p>
							</div>
						</div>
					</div>
					</a>
				</div>
		<?php 
			}
			$page='notice';
			$notice=mj_smgt_page_access_rolewise_accessright_dashboard($page);
			if($notice==1)
			{
		?>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<a class="anchor_css" href="<?php print site_url().'/?dashboard=user&page=notice' ?>">
						<div class="panel info-box panel-white">
							<div class="panel-body notices">
								<span class="info-box-icon bg-aqua">
									<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/notice_new.png"?>"></i>
								</span>
								<div class="info-box-stats">
									<?php 
										$user_notice_access=mj_smgt_get_userrole_wise_filter_access_right_array('notice');	
											//------- NOTICE DATA FOR STUDENT ---------//
											if($school_obj->role == 'student')
											{
												$own_data=$user_notice_access['own_data'];
												if($own_data == '1')
												{ 
													$class_name  	= 	get_user_meta(get_current_user_id(),'class_name',true);		
													$class_section  = 	get_user_meta(get_current_user_id(),'class_section',true);	
													$notice_list = mj_smgt_student_notice_dashbord($class_name,$class_section);
													$notice_count = count($notice_list);
												}
												else
												{
													global $wpdb;
													$table_post= $wpdb->prefix. 'posts';
													$total_notice = $wpdb->get_row("SELECT COUNT(*) as  total_notice FROM $table_post where post_type='notice' ");
													$notice_count=$total_notice->total_notice;
												}
												 
											}
											//------- NOTICE DATA FOR TEACHER ---------//
											elseif($school_obj->role == 'teacher')
											{
												$own_data=$user_notice_access['own_data'];
												if($own_data == '1')
												{ 
													$notice_list =mj_smgt_teacher_notice_dashbord();
													$notice_count = count($notice_list);
												}
												else
												{
													global $wpdb;
													$table_post= $wpdb->prefix. 'posts';
													$total_notice = $wpdb->get_row("SELECT COUNT(*) as  total_notice FROM $table_post where post_type='notice' ");
													$notice_count=$total_notice->total_notice;
												}
											}
											//------- NOTICE DATA FOR PARENT ---------//
											elseif($school_obj->role == 'parent')
											{
												$own_data=$user_notice_access['own_data'];
												if($own_data == '1')
												{  
													$notice_list = mj_smgt_parent_notice_dashbord();
													$notice_count = count($notice_list);
												}
												else
												{
													global $wpdb;
													$table_post= $wpdb->prefix. 'posts';
													$total_notice = $wpdb->get_row("SELECT COUNT(*) as  total_notice FROM $table_post where post_type='notice' ");
													$notice_count=$total_notice->total_notice;
												}
											}
											//------- NOTICE DATA FOR SUPPORT STAFF ---------//
											else
											{ 
												$own_data=$user_notice_access['own_data'];
												if($own_data == '1')
												{ 
													$notice_list = mj_smgt_supportstaff_notice_dashbord();
													$notice_count = count($notice_list);
												}
												else
												{
													global $wpdb;
													$table_post= $wpdb->prefix. 'posts';
													$total_notice = $wpdb->get_row("SELECT COUNT(*) as  total_notice FROM $table_post where post_type='notice' ");
													$notice_count=$total_notice->total_notice;													
												}
											} 
										
										?>
										<span class="info-box-title all_box"><?php echo esc_html( esc_attr__( 'Notice', 'school-mgt' ) );?></span>
										<p class="counter"><?php echo $notice_count;?></p>
								</div>
							</div>
						</div>
					</a>
				</div>
	<?php 
			} ?>
			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<a class="anchor_css" href="<?php print site_url().'/?dashboard=user&page=message&tab=inbox' ?>">
					<div class="panel info-box panel-white">
						<div class="panel-body message">
						<span class="info-box-icon bg-aqua">
							<i class="ion ion-ios-gear-outline"><img src="<?php echo SMS_PLUGIN_URL."/assets/images/email_new.png"?>"></i>
						</span>
							<div class="info-box-stats">
								<span class="info-box-title all_box"><?php echo esc_html( esc_attr__( 'Messages', 'school-mgt' ) );?></span>
								<p class="counter"><?php echo count(mj_smgt_count_inbox_item(get_current_user_id()));?>&nbsp;<span class="frunted_message_unreded font_size_17px"><?php echo mj_smgt_count_unread_message(get_current_user_id());?></span></p>
							</div>
						</div>
					</div>
				</a>
			</div>
	</div>
	<!---------------- CALENDAR ------------------------------->
	<div class="row">
		<div class="col-md-8 col-sm-8 col-xs-12 ">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ps-0 pe-0" >
				<div class="panel panel-white cln">
					<div class="panel-body">
						<div id="calendar"></div><br>
						<mark class="calendar-notice">&nbsp;&nbsp;&nbsp;</mark><span> &nbsp;<?php esc_attr_e('Notice','school-mgt') ?><span>&nbsp;
						<mark class="calendar-holiday">&nbsp;&nbsp;&nbsp;</mark><span> &nbsp;<?php esc_attr_e('Holiday','school-mgt') ?><span>&nbsp;
						<mark style="calendar-schedule">&nbsp;&nbsp;&nbsp;</mark><span> &nbsp;<?php esc_attr_e('Class Schedule','school-mgt') ?><span>
						<?php
						if (get_option('smgt_enable_virtual_classroom') == 'yes')
						{
						?>
						<mark class="calendar-classroom">&nbsp;&nbsp;&nbsp;</mark><span> &nbsp;<?php esc_attr_e('Virtual Classroom','school-mgt') ?><span>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-12 ">
			<div class="row">
				<?php  
				$page='exam';
				$exam=mj_smgt_page_access_rolewise_accessright_dashboard($page);
				if($exam==1)
				{
				?>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="panel panel-white exam list_en">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-book" aria-hidden="true"></i> <?php esc_attr_e('Exam List','school-mgt');?></h3>
							<ul class="nav navbar-right panel_toolbox float-end">
								<li class=""><a href="<?php echo site_url().'/?dashboard=user&page=exam';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
								</li>                  
							</ul>
						</div>
						<div class="panel-body">
							<div class="events">
									<?php 
									global $wpdb;
									$smgt_exam = $wpdb->prefix. 'exam';
									$user_exam_access=mj_smgt_get_userrole_wise_filter_access_right_array('exam');	
									$obj_exam= new smgt_exam;
									$user_id=get_current_user_id();
									//------- EXAM DATA FOR STUDENT ---------//
									if($school_obj->role == 'student')
									{
										if($user_exam_access['own_data'] == '1')
										{ 
											$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);			
											$section_id 	= 	get_user_meta(get_current_user_id(),'class_section',true);	
											if(isset($class_id) && $section_id =='')
											{
												$retrieve_class	= $obj_exam->mj_smgt_get_all_exam_by_class_id_dashboard($class_id);
											}
											else
											{
												$retrieve_class	= $obj_exam->mj_smgt_get_all_exam_by_class_id_and_section_id_array_dashboard($class_id,$section_id);
											}
										}
										else
										{
											$retrieve_class = $wpdb->get_results("SELECT * FROM $smgt_exam ORDER BY exam_id DESC limit 3");	
										}
									}
									//------- EXAM DATA FOR TEACHER ---------//
									elseif($school_obj->role == 'teacher')
									{
										if($user_exam_access['own_data'] == '1')
										{ 
											$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);	
											$retrieve_class	= $obj_exam->mj_smgt_get_all_exam_by_class_id_created_by_dashboard($class_id,$user_id);
										}
										else
										{
											$retrieve_class = $wpdb->get_results("SELECT * FROM $smgt_exam ORDER BY exam_id DESC limit 3");		
										}
									}
									//------- EXAM DATA FOR PARENT ---------//
									elseif($school_obj->role == 'parent')
									{
										if($user_exam_access['own_data'] == '1')
										{
											$user_meta =get_user_meta($user_id, 'child', true);
											foreach($user_meta as $c_id)
											{
												$classdata[]=get_user_meta($c_id,'class_name',true);
												$section_id = get_user_meta(get_current_user_id(),'class_section',true);	
												if(!empty($classdata) && $section_id =='')
												{
													$retrieve_class	= $obj_exam->mj_smgt_get_all_exam_by_class_id_array_dashboard($classdata);
												}
												else
												{
													$retrieve_class	= $obj_exam->mj_smgt_get_all_exam_by_class_id_and_section_id_array_dashboard($class_id,$section_id);
												}					
											}
										}
										else
										{
											$retrieve_class = $wpdb->get_results("SELECT * FROM $smgt_exam ORDER BY exam_id DESC limit 3");		
										}
									}
									//------- EXAM DATA FOR SUPPORT STAFF ---------//
									else
									{ 
										if($user_exam_access['own_data'] == '1')
										{
											$retrieve_class	= $obj_exam->mj_smgt_get_all_exam_created_by_dashboard($user_id);
										}
										else
										{
											$retrieve_class = $wpdb->get_results("SELECT * FROM $smgt_exam ORDER BY exam_id DESC limit 3");	
										}
									} 
									if(!empty($retrieve_class)) 
									{
										foreach($retrieve_class as $retrieved_data)
										{
										?>				
											<div class="calendar-event view-complaint"> 
												<p class="cursor_effect Bold show_task_event" id="<?php echo $retrieved_data->exam_id;?>" model="Exam Details" > <?php esc_attr_e('Exam Title : ','school-mgt');?> 
												<?php echo 	$retrieved_data->exam_name;  ?>
												</p> 
												<p class="remainder_date"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_start_date); ?> | <?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_end_date);?></p>
												<p class="remainder_title_pr_new_template">
												<?php 
													$strlength= strlen($retrieved_data->exam_comment);
													if($strlength > 90)
													{
														echo substr($retrieved_data->exam_comment, 0,90).'...';
													}
													else
													{
														echo $retrieved_data->exam_comment; 
													}
													?>
												</p>
											</div>
									<?php
										}	
									} 
									else 
										{
											?>
											<div class="eror_msg"> 
											<?php
												esc_attr_e("No Upcoming Exam",'school-mgt');
											?>
											</div>
											<?php
										}
										?>		
							</div>
						</div>
					</div>
				</div>
				<?php 
				}  
				$page='notice';
				$notice=mj_smgt_page_access_rolewise_accessright_dashboard($page);
				if($notice==1)
				{
				?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="panel panel-white nt list_en">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-calendar-o" aria-hidden="true"></i> <?php esc_attr_e('Notice board','school-mgt');?></h3>
								<ul class="nav navbar-right panel_toolbox float-end">
									<li class=""><a href="<?php echo site_url().'/?dashboard=user&page=notice';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
									</li>                  
								</ul>
							</div>
							<div class="panel-body">
								<div class="events">
										<?php 
										$user_notice_access=mj_smgt_get_userrole_wise_filter_access_right_array('notice');	
										$obj_exam= new smgt_exam;
										$user_id=get_current_user_id();
										//------- NOTICE DATA FOR STUDENT ---------//
										if($school_obj->role == 'student')
										{
											if($user_notice_access['own_data'] == '1')
											{
												$class_name  	= 	get_user_meta(get_current_user_id(),'class_name',true);		
												$class_section  = 	get_user_meta(get_current_user_id(),'class_section',true);	
												$notice_list = mj_smgt_student_notice_board($class_name,$class_section);
											}
											else
											{
												$args['post_type'] = 'notice';
												$args['posts_per_page'] = 3;
												$args['post_status'] = 'public';
												$q = new WP_Query();
												$notice_list = $q->query( $args );
											}
										}
										//------- NOTICE DATA FOR TEACHER ---------//
										elseif($school_obj->role == 'teacher')
										{
											if($user_notice_access['own_data'] == '1')
											{
												$class_name  	= 	get_user_meta(get_current_user_id(),'class_name',true);	
												$notice_list = mj_smgt_teacher_notice_board($class_name);
											}
											else
											{
												$args['post_type'] = 'notice';
												$args['posts_per_page'] = 3;
												$args['post_status'] = 'public';
												$q = new WP_Query();
												$notice_list = $q->query( $args );
											}
										}
										//------- NOTICE DATA FOR PARENT ---------//
										elseif($school_obj->role == 'parent')
										{
											if($user_notice_access['own_data'] == '1')
											{ 
												$notice_list = mj_smgt_parent_notice_board();
											}
											else
											{
												$args['post_type'] = 'notice';
												$args['posts_per_page'] = 3;
												$args['post_status'] = 'public';
												$q = new WP_Query();
												$notice_list = $q->query( $args );
											}
										}
										//------- NOTICE DATA FOR SUPPORT STAFF ---------//
										else
										{ 
											if($user_notice_access['own_data'] == '1')
											{ 
												$notice_list = mj_smgt_supportstaff_notice_board();
											}
											else
											{
												$args['post_type'] = 'notice';
												$args['posts_per_page'] = 3;
												$args['post_status'] = 'public';
												$q = new WP_Query();
												$notice_list = $q->query( $args );
											}
										}
										if(!empty($notice_list))
										{
											foreach ($notice_list as $postid)
											{
												$retrieved_data=get_post($postid);
											?>
													<div class="calendar-event"> 
														<p class="remainder_title Bold show_task_event" id="<?php echo $retrieved_data->ID;?>" model="Noticeboard Details">	
														<?php echo 	$retrieved_data->post_title;  ?>
														</p>
														<p class="remainder_date"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->start_date); ?> | <?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);?></p>
														<p class="remainder_title_pr_new_template"><?php 
														$strlength= strlen($retrieved_data->post_content);
														if($strlength > 90)
														{
															echo substr($retrieved_data->post_content, 0,90).'...';
														}
														else
														{
															echo $retrieved_data->post_content; 
														}
													?>
													</p>
													</div>
											<?php
											}	
										}
										else 
										{
											?>
											<div class="eror_msg"> 
											<?php
												esc_attr_e("No Upcoming Notice",'school-mgt');
											?>
											</div>
											<?php
										}
										?>
								</div>
							</div>
						</div>
					</div>
					<?php  
				}   
				$page='holiday';
				$holiday=mj_smgt_page_access_rolewise_accessright_dashboard($page);
				if($holiday==1)
				{
					?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="panel panel-white event">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-calendar" aria-hidden="true"></i> <?php esc_attr_e('Holiday List','school-mgt');?></h3>	
								<ul class="nav navbar-right panel_toolbox float-end">
									<li class=""><a href="<?php echo site_url().'/?dashboard=user&page=holiday';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
									</li>                  
								</ul>				
							</div>
							<div class="panel-body">
								<div class="events">
									<?php 
									global $wpdb;
									$smgt_holiday = $wpdb->prefix. 'holiday';
									$user_holiday_access=mj_smgt_get_userrole_wise_filter_access_right_array('holiday');	
									$user_id=get_current_user_id();
									if($school_obj->role == 'supportstaff')
									{
										if($user_holiday_access['own_data'] == '1')
										{
											$result = mj_smgt_get_all_holiday_created_by_dashboard($user_id);
										}
										else
										{
											$result = $wpdb->get_results("SELECT * FROM $smgt_holiday ORDER BY holiday_id DESC limit 3");
										}
									}
									else
									{
										$result = $wpdb->get_results("SELECT * FROM $smgt_holiday ORDER BY holiday_id DESC limit 3");
									}
									if(!empty($result)) 
									{
										foreach ($result as $retrieved_data)
										{	
									?>
										<div class="calendar-event"> 
											<p class="cursor_effect Bold show_task_event" id="<?php echo $retrieved_data->holiday_id;?>" model="holiday Details" > 
											<?php echo 	$retrieved_data->holiday_title;  ?>
											</p>
											<p class="remainder_date"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->date); ?> | <?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);?></p>
											<p class="remainder_title_pr_new_template"><?php 
											$strlength= strlen($retrieved_data->description);
											if($strlength > 90)
											{
												echo substr($retrieved_data->description, 0,90).'...';
											}
											else
											{
												echo $retrieved_data->description; 
											}
											?></p>
										</div>
										<?php
										}	
									} 
									else 
									{
										?>
										<div class="eror_msg"> 
										<?php
											esc_attr_e("No Upcoming Holiday",'school-mgt');
										?>
										</div>
										<?php
									}
									?>
								</div>
							</div>
						</div>
					</div>
			<?php  
				}   
				$page='feepayment';
				$feepayment=mj_smgt_page_access_rolewise_accessright_dashboard($page);
				if($feepayment==1)
				{
					?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="panel panel-white invoice list_en">
								<div class="panel-heading">
									<h3 class="panel-title"><i class="fa fa-money" aria-hidden="true"></i> <?php esc_attr_e('Invoice','school-mgt');?></h3>
									<ul class="nav navbar-right panel_toolbox float-end">
										<li class=""><a href="<?php echo site_url().'/?dashboard=user&page=feepayment';?>"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										</li>                  
									</ul>
								</div>
								<div class="panel-body">
									<div class="events">
											<table class="table table-borderless invoice_table">
											<?php
												$user_payment_access=mj_smgt_get_userrole_wise_filter_access_right_array('feepayment');											  
												$user_id=get_current_user_id();
												//------- Payment DATA FOR STUDENT ---------//
												if($school_obj->role == 'student')
												{
													$get_fees_payment_dashboard=$school_obj->feepayment;
												}
												//------- Payment DATA FOR TEACHER ---------//
												elseif($school_obj->role == 'teacher')
												{
													if($user_payment_access['own_data'] == '1')
													{				
														global $wpdb;
														$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);	
														$table_name = $wpdb->prefix .'smgt_fees_payment';
														$get_fees_payment_dashboard =$wpdb->get_results("SELECT * FROM $table_name WHERE class_id in (".implode(',', $class_id).")");
													}
													else
													{
														$get_fees_payment_dashboard=$school_obj->feepayment;
													}
												}
												//------- Payment DATA FOR PARENT ---------//
												elseif($school_obj->role == 'parent')
												{
													$get_fees_payment_dashboard=$school_obj->feepayment;
												}
												//------- Payment DATA FOR SUPPORT STAFF ---------//
												else
												{ 
													$get_fees_payment_dashboard=$school_obj->feepayment;
												} 
												if(!empty($get_fees_payment_dashboard)) 
												{
													?>	
													<thead>
														<tr>
															<th scope="col" class="calendar-cst"><?php esc_attr_e('Fees Type','school-mgt');?></th>
															<th scope="col" class="calendar-cst"><?php esc_attr_e('Student Name','school-mgt');?></th>
															<th scope="col" class="calendar-cst"><?php esc_attr_e('Total Amount','school-mgt');?></th>
															<th scope="col" class="calendar-cst"><?php esc_attr_e('Payment Status','school-mgt');?></th>
														</tr>
													</thead>
													<tbody>
													<?php
													foreach($get_fees_payment_dashboard as $retrieved_data)
														{  
														?>		
														<tr>
															<td class="unit"><?php echo mj_smgt_get_fees_term_name($retrieved_data->fees_id);?></td>
															<td class="unit"><?php echo mj_smgt_get_user_name_byid($retrieved_data->student_id);?></td>
															<td class="unit"><?php echo "<span> ". mj_smgt_get_currency_symbol() ." </span>" . number_format($retrieved_data->total_amount,2); ?></td>
															<td class="unit"><span class="btn-xs"> 
															<?php $smgt_get_payment_status=mj_smgt_get_payment_status($retrieved_data->fees_pay_id);
															if($smgt_get_payment_status == 'Not Paid')
															{
															 echo "<span class='btn btn-danger btn-xs'>";
															}
															elseif($smgt_get_payment_status == 'Partially Paid')
															{
																echo "<span style='background-color: rgb(50 122 183);' class='btn btn-xs'>";
															}
															else
															{
																echo "<span class='btn btn-success btn-xs'>";
															}
															echo esc_html__("$smgt_get_payment_status","school-mgt");
															echo "</span>";
															
															?> </span></td>
														</tr>
														<?php
														}	
														?>			
													</tbody>
													<?php
												} 
												else 
												{
													?>
													<div class="eror_msg"> 
													<?php
														esc_attr_e("No Upcoming Invoice",'school-mgt');
													?>
													</div>
													<?php
												}
													?>
											</table>
									</div>
								</div>
						</div>
					</div>
					<?php  
				}  ?>		
			</div>
		</div>
	</div>
	<!---End new dashboard------>
  <?php		
	if($school_obj->role == 'teacher')
	{
	?>
	<div class="panel1"> 	
		<div class="row dashboard">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="panel panel-white">
					<div class="panel-heading my_table margin_bottom_20">
                    	<h4 class="panel-title"><?php esc_attr_e('My Time Table','school-mgt')?></h4>
                    </div>
                    <div class="panel-body">
						<table class="table table-bordered" cellspacing="0" cellpadding="0" border="0">
							<?php 
							$obj_route = new Class_routine();
							$i = 0;
							$i++;
							foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)
							{
							?>
							<tr>
								<th width="100"><?php echo $dayname;?></th>
								<td>
									<?php
									$period = $obj_route->mj_smgt_get_periad_by_teacher(get_current_user_id(),$daykey);
									if(!empty($period))
										foreach($period as $period_data)
										{
											
											$start_time_data = explode(":", $period_data->start_time);
											$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
											$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
											$start_am_pm=$start_time_data[2];
											$start_time=$start_hour.':'.$start_min.' '.$start_am_pm;

											$end_time_data = explode(":", $period_data->end_time);
											$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
											$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
											$end_am_pm=$end_time_data[2];
											
											 
											echo '<button class="btn btn-primary"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id);
											echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';
											echo '<span>'.mj_smgt_get_class_name($period_data->class_id).'</span>';
											echo '</span></button>';								
										}
										?>
								</td>
							</tr>
							<?php	
							}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
</div>
</div>
</div>
</div>
</div>
</body>
</html>