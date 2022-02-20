<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
	$('#rout_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
});
</script>
<?php 
$obj_route = new Class_routine();	
$obj_virtual_classroom = new mj_smgt_virtual_classroom();	
if(isset($_POST['save_route']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_root_admin_nonce' ) )
	{
		$teacherid = mj_smgt_get_teacherid_by_subjectid($_POST['subject_id']);
		foreach($teacherid as $teacher_id)
		{
			$route_data=array('subject_id'=>mj_smgt_onlyNumberSp_validation($_POST['subject_id']),
					'class_id'=>mj_smgt_onlyNumberSp_validation($_POST['class_id']),
					'section_name'=>mj_smgt_onlyNumberSp_validation($_POST['class_section']),
					'teacher_id'=>$teacher_id,
					'start_time'=>$_POST['start_time'].':'.$_POST['start_min'].':'.$_POST['start_ampm'],
					'end_time'=>mj_smgt_onlyNumberSp_validation($_POST['end_time']).':'.mj_smgt_onlyNumberSp_validation($_POST['end_min']).':'.mj_smgt_onlyLetterSp_validation($_POST['end_ampm']),
					'weekday'=>mj_smgt_onlyNumberSp_validation($_POST['weekday'])
			);
	 
			if($_REQUEST['action']=='edit')
			{
				$route_id=array('route_id'=>$_REQUEST['route_id']); 
				$obj_route->mj_smgt_update_route($route_data,$route_id); 
				wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=2');
				exit;
			}
			else
			{
				$retuen_val = $obj_route->mj_smgt_is_route_exist($route_data);
				
				if($retuen_val == 'success')
				{
					$obj_route->mj_smgt_save_route($route_data);
					wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=1');
					exit;
				}
				elseif($retuen_val == 'duplicate')
				{       
					wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=4');
					exit;
				}
				elseif($retuen_val == 'teacher_duplicate')
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=5');
					exit;
				}                
			} 
		}
	}
}
if(isset($_POST['create_meeting']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'create_meeting_admin_nonce' ) )
	{
		
		$result = $obj_virtual_classroom->mj_smgt_create_meeting_in_zoom($_POST);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=smgt_virtual_classroom&tab=meeting_list&message=1');
		}
			
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$tablenm = "smgt_time_table";
	$result=mj_smgt_delete_route($tablenm,$_REQUEST['route_id']);
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_route&tab=route_list&message=3');
		exit;
	}
} ?>				
<div class="popup-bg">
    <div class="overlay-content">
		<a href="#" class="close-btn">X</a>
		<div class="edit_perent"></div>
		<div class="view-parent"></div>
		<a href="#" class="close-btn"><?php esc_attr_e('Close','school-mgt');?></a>
    </div>   
</div>
<div class="popup-bg">
    <div class="overlay-content">
		<div class="create_meeting_popup"></div>
    </div>   
</div>
<?php $active_tab = isset($_GET['tab'])?$_GET['tab']:'route_list';	?>
<div class="page-inner">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div id="main-wrapper" class="grade_page">
<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Routine Added Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Routine Updated Successfully.','school-mgt');
			break;		
		case '3':
			$message_string = esc_attr__('Routine Deleted Successfully.','school-mgt');
			break;			
		case '4':
			$message_string = esc_attr__('Routine Alredy Added For This Time Period.Please Try Again.','school-mgt');
			break;			
		case '5':
			$message_string = esc_attr__('Teacher Is Not Available.','school-mgt');
			break;			
	}
	
	if($message)
	{ ?>
		<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
			<p><?php echo $message_string;?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
<?php } ?>
<div class="panel panel-white">
<div class="panel-body">
<div class=" class_list">  
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_route&tab=route_list" class="nav-tab <?php echo $active_tab == 'route_list' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Route List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{ ?>
       <a href="?page=smgt_route&tab=addroute&action=edit&route_id=<?php echo $_REQUEST['route_id'];?>" class="nav-tab <?php echo $active_tab == 'addroute' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Route', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_route&tab=addroute" class="nav-tab <?php echo $active_tab == 'addroute' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'. esc_attr__('Add Route', 'school-mgt'); ?></a>  
        <?php } ?>
        <a href="?page=smgt_route&tab=teacher_timetable" class="nav-tab margin_bottom <?php echo $active_tab == 'teacher_timetable' ? 'nav-tab-active' : ''; ?>">
        <?php echo '<span class="dashicons dashicons-calendar-alt"></span>'. esc_attr__('Teacher Time Table','school-mgt');?>
        </a>
    </h2>
    <?php	
	if($active_tab == 'route_list')
	{	
	?>	
    <div class="panel panel-white">         
    <div class="panel-body">
        <div id="accordion" class="panel-group accordion accordion-flush" id="accordionFlushExample" aria-multiselectable="true" role="tablist">
        <?php
		$retrieve_class = mj_smgt_get_all_data('smgt_class');		
		$i=0;
		if(!empty($retrieve_class))
		{
			foreach($retrieve_class as $class)
			{
				if(!empty($class))
				{	 ?>
					<div class="mt-1 accordion-item">
						<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">
							<!-- <a class="collapsed" aria-controls="collapse_<?php echo $i;?>" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse">
							<?php esc_attr_e('Class', 'school-mgt'); ?> : <?php echo $class->class_name; ?> </a> -->
							<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">
							<?php esc_attr_e('Class', 'school-mgt'); ?> : <?php echo $class->class_name; ?> </a>
							</button>			
						</h4>
					<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionFlushExample">
					  <div class="panel-body">
					<table class="table table-bordered">
					<?php			
					$sectionid=0;
					foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)
					{ ?>
					<tr>
						<th width="100"><?php echo $dayname;?></th>
						<td>
						<?php
							$period = $obj_route->mj_smgt_get_periad($class->class_id,$sectionid,$daykey);
							if(!empty($period))
								foreach($period as $period_data)
								{
									echo '<div class="btn-group m-b-sm">';
									echo '<button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id);
									$start_time_data = explode(":", $period_data->start_time);
									$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
									$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
									$start_am_pm=$start_time_data[2];
									
									$end_time_data = explode(":", $period_data->end_time);
									$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
									$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
									$end_am_pm=$end_time_data[2];
									echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';
									$create_meeting = '';
									$update_meeting = '';
									$delete_meeting = '';
									$meeting_statrt_link = '';
									if (get_option('smgt_enable_virtual_classroom') == 'yes')
									{
										$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);
										if(empty($meeting_data))
										{
											$create_meeting = '<li class="pb-1 ps-1"><a class="text-decoration-none show-popup" href="#" id="'.$period_data->route_id.'">'. esc_attr__('Create Virtual Class','school-mgt').'</a></li>';
										}
										else
										{
											$create_meeting = '';
										}

										if(!empty($meeting_data))
										{
											$update_meeting = '<li class="pb-1 ps-1"><a class="text-decoration-none" href="admin.php?page=smgt_virtual_classroom&tab=edit_meeting&action=edit&meeting_id='.$meeting_data->meeting_id.'">'. esc_attr__('Edit Virtual Class','school-mgt').'</a></li>';
											$delete_meeting = '<li class="pb-1 ps-1"><a class="text-decoration-none" href="admin.php?page=smgt_virtual_classroom&tab=meeting_list&action=delete&meeting_id='.$meeting_data->meeting_id.'" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'. esc_attr__('Delete Virtual Class','school-mgt').'</a></li>';
											$meeting_statrt_link = '<li class="pb-1 ps-1"><a class="text-decoration-none" href="'.$meeting_data->meeting_start_link.'" target="_blank">'. esc_attr__('Virtual Class Start','school-mgt').'</a></li>';
										}
										else
										{
											$update_meeting = '';
											$delete_meeting = '';
											$meeting_statrt_link = '';
										}
									}
									//echo '<span class="time"> ('.$period_data->start_time.'- '.$period_data->end_time.') </span>';
									echo '</span><span class="caret"></span></button>';
									echo '<ul role="menu" class="pt-2 dropdown-menu">
											<li class="pb-1 ps-1"><a class="text-decoration-none" href="?page=smgt_route&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'. esc_attr__('Edit Route','school-mgt').'</a></li>
											<li class="pb-1 ps-1"><a class="text-decoration-none" href="?page=smgt_route&tab=route_list&action=delete&route_id='.$period_data->route_id.'">'. esc_attr__('Delete Route','school-mgt').'</a></li>'.$create_meeting .''.$update_meeting.''.$delete_meeting.''.$meeting_statrt_link.'
										</ul>';
									echo '</div>';							
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
				<?php
				}
				$sectionname="";
				$sectionid="";
				$class_sectionsdata=mj_smgt_get_class_sections($class->class_id);
				if(!empty($class_sectionsdata))
				{				
					foreach($class_sectionsdata as $section)
					{ 
						$i++;
						$sectionname=$section->section_name;
						$sectionid=$section->id;
						?>
						<div class="mt-1 accordion-item">
						<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">
						<!-- <a class="collapsed" aria-controls="collapse_<?php echo $i;?>" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse"> -->
						<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_<?php echo $i;?>" aria-controls="flush-collapse_<?php echo $i;?>">
						<?php esc_attr_e('Class', 'school-mgt'); ?> : <?php echo $class->class_name; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						<?php 
						if(!empty($section->section_name))
						{ ?>
						<?php esc_attr_e('Section', 'school-mgt'); ?> : <?php echo $section->section_name; ?>
						<?php }
						?>					
						</button>

						</h4>
					   <div id="flush-collapse_<?php echo $i;?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $i;?>" data-bs-parent="#accordionFlushExample" >
					   <div class="panel-body">
					   <table class="table table-bordered">
					<?php
						foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)
						{ ?>
							<tr>
								<th width="100"><?php echo $dayname;?></th>
								<td>
									<?php
									$period = $obj_route->mj_smgt_get_periad($class->class_id,$section->id,$daykey);				
									if(!empty($period))
									foreach($period as $period_data)
									{
										echo '<div class="btn-group m-b-sm">';
										echo '<button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id);
										
										$start_time_data = explode(":", $period_data->start_time);
										$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
										$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
										$start_am_pm=$start_time_data[2];
										
										$end_time_data = explode(":", $period_data->end_time);
										$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
										$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
										$end_am_pm=$end_time_data[2];
										if (get_option('smgt_enable_virtual_classroom') == 'yes')
										{
											$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);
											if(empty($meeting_data))
											{
												$create_meeting = '<li class="pb-1 ps-1"><a class="text-decoration-none show-popup" href="#" id="'.$period_data->route_id.'">'. esc_attr__('Create Virtual Class','school-mgt').'</a></li>';
											}
											else
											{
												$create_meeting = '';
											}

											if(!empty($meeting_data))
											{
												$update_meeting = '<li class="pb-1 ps-1"><a class="text-decoration-none" href="admin.php?page=smgt_virtual_classroom&tab=edit_meeting&action=edit&meeting_id='.$meeting_data->meeting_id.'">'. esc_attr__('Edit Virtual Class','school-mgt').'</a></li>';
												$delete_meeting = '<li class="pb-1 ps-1"><a class="text-decoration-none" href="admin.php?page=smgt_virtual_classroom&tab=meeting_list&action=delete&meeting_id='.$meeting_data->meeting_id.'" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'. esc_attr__('Delete Virtual Class','school-mgt').'</a></li>';
												$meeting_statrt_link = '<li class="pb-1 ps-1"><a class="text-decoration-none" href="'.$meeting_data->meeting_start_link.'" target="_blank">'. esc_attr__('Start Virtual Class','school-mgt').'</a></li>';
											}
											else
											{
												$update_meeting = '';
												$delete_meeting = '';
												$meeting_statrt_link = '';
											}
										}
										//echo '<span class="time"> ('.$period_data->start_time.' - '.$period_data->end_time.') </span>';
										echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';
										echo '</span><span class="caret"></span></button>';
										echo '<ul class="pt-2 dropdown-menu edit_delete_drop">
												<li class="pb-1 ps-1"><a class="text-decoration-none" href="?page=smgt_route&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'. esc_attr__('Edit','school-mgt').'</a></li>
												<li class="pb-1 ps-1"><a class="text-decoration-none" onclick="return confirm(\'Do you want to to delet route?\');" href="?page=smgt_route&tab=route_list&action=delete&route_id='.$period_data->route_id.'">'. esc_attr__('Delete','school-mgt').'</a></li>
												'.$create_meeting .''.$update_meeting.''.$delete_meeting.''.$meeting_statrt_link.'
											</ul>';
										echo '</div>';							
									}
								 ?>
								</td>
							</tr>
					<?php 
						} ?>
					</table> 
					</div>
					</div>
					</div>		   
					<?php
					} 			
				}
				$i++; 
			}
		}
		else
		{
			esc_attr_e( 'Class data not avilable', 'school-mgt' );
		}
		?>
		</div>
        </div>
        </div>
       
     <?php 
		
	 }
	if($active_tab == 'addroute')
	{
		require_once SMS_PLUGIN_DIR. '/admin/includes/routine/add-route.php';		
	}
	 if($active_tab == 'teacher_timetable')
	 {
		?>
		 <div class="panel panel-white">
        	<div class="clearfix panel-heading">
				<h3 class="panel-title"><?php esc_attr_e('Teacher Time Table','school-mgt');?></h3>
			</div>
		 <div class="panel-body">
		 <div id="accordion" class="panel-group accordion accordion-flush" aria-multiselectable="true" role="tablist">
        <?php 
		
		$teacherdata=mj_smgt_get_usersdata('teacher');
		if(!empty($teacherdata))
		{	
			$i=0;
			
		foreach($teacherdata as $retrieved_data)
		{ ?>
        <div class="mt-1 accordion-item">
			<h4 class="accordion-header" id="flush-heading<?php echo $i;?>">
					<!-- <a class="collapsed" aria-controls="collapse_<?php echo $i;?>" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse"> -->
					<!-- <?php //esc_attr_e('Teacher','school-mgt');?>: <?php //echo $retrieved_data->display_name;?> </a> -->
					<button class="accordion-button collapsed bg-gray" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_collapse_<?php echo $i;?>" aria-controls="flush-heading<?php echo $i;?>">
							<?php esc_attr_e('Teacher', 'school-mgt'); ?> : <?php echo $retrieved_data->display_name;?> </a>
					</button>
								
				</h4>
			
        <!-- <div id="collapse_<?php echo $i;?>" class="panel-collapse collapse h-0-px" aria-labelledby="heading_<?php echo $i;?>" role="tabpanel" > -->
		<div id="flush-collapse_collapse_<?php echo $i;?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $i;?>" role="tabpanel" data-bs-parent="#accordionFlushExample">

             <div class="panel-body">
        <table class="table table-bordered">
        <?php 
        $i++;
		foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)
		{	?>
		<tr>
       <th width="100"><?php echo $dayname;?></th>
        <td>
        	 <?php
			 	$period = $obj_route->mj_smgt_get_periad_by_teacher($retrieved_data->ID,$daykey);
				if(!empty($period))
					foreach($period as $period_data)
					{
						echo '<div class="btn-group m-b-sm">';
						echo '<button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id);
						
						$start_time_data = explode(":", $period_data->start_time);
						$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
						$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
						$start_am_pm=$start_time_data[2];
						
						$end_time_data = explode(":", $period_data->end_time);
						$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
						$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
						$end_am_pm=$end_time_data[2];
						echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';
						$create_meeting = '';
						$update_meeting = '';
						$delete_meeting = '';
						$meeting_statrt_link = '';
						if (get_option('smgt_enable_virtual_classroom') == 'yes')
						{
							$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);
							if(empty($meeting_data))
							{
								$create_meeting = '<li class="pb-1 ps-1"><a class="text-decoration-none show-popup" href="#" id="'.$period_data->route_id.'">'. esc_attr__('Create Virtual Class','school-mgt').'</a></li>';
							}
							else
							{
								$create_meeting = '';
							}

							if(!empty($meeting_data))
							{
								$update_meeting = '<li class="pb-1 ps-1"><a class="text-decoration-none" href="admin.php?page=smgt_virtual_classroom&tab=edit_meeting&action=edit&meeting_id='.$meeting_data->meeting_id.'">'. esc_attr__('Edit Virtual Class','school-mgt').'</a></li>';
								$delete_meeting = '<li class="pb-1 ps-1"><a class="text-decoration-none" href="admin.php?page=smgt_virtual_classroom&tab=meeting_list&action=delete&meeting_id='.$meeting_data->meeting_id.'" onclick="return confirm(\''. esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'. esc_attr__('Delete Virtual Class','school-mgt').'</a></li>';
								$meeting_statrt_link = '<li class="pb-1 ps-1"><a class="text-decoration-none" href="'.$meeting_data->meeting_start_link.'" target="_blank">'. esc_attr__('Virtual Class Start','school-mgt').'</a></li>';
							}
							else
							{
								$update_meeting = '';
								$delete_meeting = '';
								$meeting_statrt_link = '';
							}
						}
						echo '<span>'.mj_smgt_get_class_name($period_data->class_id).'</span>';
						echo '</span></span><span class="caret"></span></button>';
						echo '<ul role="menu" class="pt-2 dropdown-menu">
                                <li class="pb-1 ps-1"><a class="text-decoration-none" href="?page=smgt_route&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'. esc_attr__('Edit','school-mgt').'</a></li>
                                <li class="pb-1 ps-1"><a class="text-decoration-none" href="?page=smgt_route&tab=route_list&action=delete&route_id='.$period_data->route_id.'">'. esc_attr__('Delete','school-mgt').'</a></li>
                                '.$create_meeting .''.$update_meeting.''.$delete_meeting.''.$meeting_statrt_link.'
						    </ul>';
						echo '</div>';					
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
         
	<?php }	}
		else
		{
			esc_attr_e( 'Teacher data not avilable', 'school-mgt' );
		}
		?>
	</div>
	</div>
	<?php } ?>	
</div>
</div>
</div>
</div>
</div>