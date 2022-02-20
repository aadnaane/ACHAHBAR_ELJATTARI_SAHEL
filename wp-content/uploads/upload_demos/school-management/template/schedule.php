<script type="text/javascript">
jQuery(document).ready(function($)
{
  "use strict";	
  $('#rout_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
});
</script>
<?php
// Schedule
$obj_route = new Class_routine ();
$obj_virtual_classroom = new mj_smgt_virtual_classroom();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'schedulelist';
if(isset($_POST['create_meeting']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'create_meeting_admin_nonce' ) )
	{
		$result = $obj_virtual_classroom->mj_smgt_create_meeting_in_zoom($_POST);
		if($result)
		{
			wp_redirect ( home_url().'?dashboard=user&page=virtual_classroom&tab=meeting_list&message=1');
		}	
	}
}
mj_smgt_browser_javascript_check();
//--------------- ACCESS WISE ROLE -----------//
$user_access=mj_smgt_get_userrole_wise_access_right_array();
 
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		mj_smgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$tablenm = "smgt_time_table";
	$result=mj_smgt_delete_route($tablenm,$_REQUEST['route_id']);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=schedule&tab=schedulelist&message=5');
		exit;
	}
}
if(isset($_GET['message']) && $_GET['message'] == 1 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('Routine Added Successfully.','school-mgt');?>
	</div>
<?php
}	
if(isset($_GET['message']) && $_GET['message'] == 2 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('Routine Alredy Added For This Time Period.Please Try Again.','school-mgt');?>
	</div>
	 
<?php
}	
if(isset($_GET['message']) && $_GET['message'] == 3 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('Teacher Is Not Available.','school-mgt');?>
	</div>
<?php
}
if(isset($_GET['message']) && $_GET['message'] == 4 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('Routine Updated Successfully.','school-mgt');?>
	</div>
<?php
}
if(isset($_GET['message']) && $_GET['message'] == 5 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('Routine Deleted Successfully.','school-mgt');?>
	</div>
<?php
}
?>
<div class="popup-bg">
    <div class="overlay-content">
		<div class="create_meeting_popup"></div>
    </div>   
</div>
<div class="panel-body panel-white p-4">
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="nav-item">
			<a href="?dashboard=user&page=schedule&tab=schedulelist" class="nav-link nav-tab2  <?php if($active_tab=='schedulelist'){?>active<?php }?>">
				<i class="fa fa-align-justify"></i>  <?php esc_attr_e('Class Timetable', 'school-mgt'); ?></a>
			</a>
		</li>
		<?php 
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{ ?>
			<li class="nav-item">
				<a href="?dashboard=user&page=schedule&tab=addroute&&action=edit&route_id=<?php echo $_REQUEST['route_id'];?>" class="nav-link nav-tab2  <?php if($active_tab=='addroute'){?>active<?php }?>">
					<i class="fa fa-align-justify"></i> <?php esc_attr_e('Edit Route', 'school-mgt'); ?>
				</a>
			</li>
			<?php 
		}
		else
		{
			if($user_access['add']=='1')
			{ ?>
				<li class="nav-item">
					<a href="?dashboard=user&page=schedule&tab=addroute" class="nav-link nav-tab2  <?php if($active_tab=='addroute') { ?> active <?php } ?>">
						<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Add Route', 'school-mgt'); ?>
					</a>
				</li>
		<?php 
			} 
		} ?>	
	</ul>
	<div class="tab-content class_schedule_tab_content">
		<div class="panel-body">
			<div class="accordion" id="accordionExample">
			<?php
			$i = 0;
			if($school_obj->role == 'teacher' OR $school_obj->role == 'supportstaff')
			{
				if($active_tab=='schedulelist')
				{
				$retrieve_class = mj_smgt_get_allclass();
				$i=0;
				if(!empty($retrieve_class))
				{				
					foreach ( $retrieve_class as $class )
					{
						if(!empty($class))
						{ ?>
							<div class="accordion-item mt-1">
								<h4 class="accordion-header" id="heading<?php echo $i;?>">
									<a data-toggle="collapse" data-parent="#accordion"
										href="#collapse<?php echo $i;?>">
									<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
        
										<?php echo esc_attr_e( 'Class', 'school-mgt' ) ;?> : <?php echo $class['class_name'];?>
									</a>
								</h4>
								<div id="collapse<?php echo $i;?>" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
									<div class="panel-body">
										<table class="table table-bordered" cellspacing="0" cellpadding="0"
											border="0">
									<?php
											foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )
											{
											?>
											<tr>
												<th width="100"><?php echo $dayname;?></th>
												<td>
											<?php
												//------- NEW LINE ADDED FOR ERROR ---------//
													$sectionid=0;
												//-----------------------------------------//
													$period = $obj_route->mj_smgt_get_periad ( $class['class_id'],$sectionid, $daykey);
													 
													if (! empty ( $period ))
														foreach ( $period as $period_data ) 
														{ 
															echo '<div class="btn-group m-b-sm">';
															echo '<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="period_box" id=' . $period_data->route_id . '>' . mj_smgt_get_single_subject_name( $period_data->subject_id );
															
															$start_time_data = explode(":", $period_data->start_time);
															$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
															$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
															$start_am_pm=$start_time_data[2];
															
															$end_time_data = explode(":", $period_data->end_time);
															$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
															$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
															$end_am_pm=$end_time_data[2];
															echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';
															$virtual_classroom_page_name = 'virtual_classroom';
															$virtual_classroom_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($virtual_classroom_page_name);
															if (get_option('smgt_enable_virtual_classroom') == 'yes')
															{
																if ($virtual_classroom_access_right['view'] == '1')
																{
																	$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);
																	if(empty($meeting_data))
																	{
																		if ($virtual_classroom_access_right['add'] == '1') 
																		{
																			$create_meeting = '<li><a href="#" id="'.$period_data->route_id.'" class="show-popup">'.esc_attr__('Create Virtual Class','school-mgt').'</a></li>';
																		}
																	}
																	else
																	{
																		$create_meeting = '';
																	}

																	if(!empty($meeting_data))
																	{
																		if ($virtual_classroom_access_right['edit'] == '1') 
																		{
																			$update_meeting = '<li><a href="?dashboard=user&page=virtual_classroom&tab=edit_meeting&action=edit&meeting_id='.$meeting_data->meeting_id.'">'.esc_attr__('Edit Virtual Class','school-mgt').'</a></li>';
																		}
																		if ($virtual_classroom_access_right['delete'] == '1') 
																		{
																			$delete_meeting = '<li><a href="?dashboard=user&page=virtual_classroom&tab=meeting_list&action=delete&meeting_id='.$meeting_data->meeting_id.'" onclick="return confirm(\''.esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'.esc_attr__('Delete Virtual Class','school-mgt').'</a></li>';
																		}
																		$meeting_statrt_link = '<li><a href="'.$meeting_data->meeting_start_link.'" target="_blank">'.esc_attr__('Start Virtual Class','school-mgt').'</a></li>';
																	}
																	else
																	{
																		$update_meeting = '';
																		$delete_meeting = '';
																		$meeting_statrt_link = '';
																	}
																}
															}
															if($user_access['edit']=='1')
															{
																$edit_route='<li><a href="?dashboard=user&page=schedule&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'.esc_attr__('Edit Route','school-mgt').'</a></li>';
															}	
															else {
																$edit_route="";
															}
															if($user_access['delete']=='1')
															{
																$delete_route='<li><a onclick="return confirm(\'Do you want to to delet route?\');" href="?dashboard=user&page=schedule&tab=schedulelist&action=delete&route_id='.$period_data->route_id.'">'.esc_attr__('Delete','school-mgt').'</a></li>';
															}	
															else {
																$delete_route="";
															}
																echo "</span></span><span class='caret'></span></button>";
																					
																echo '<ul role="menu" class="dropdown-menu">
																	'.$edit_route.''.$delete_route.''.$create_meeting .''.$update_meeting.''.$delete_meeting.''.$meeting_statrt_link.'
																	
																</ul>';
																echo '</div>';
															
														}
													?>
												</td>
											</tr>
									<?php	} ?>
										</table>
									</div>
								</div>
							</div>
				 <?php 	}						
						$sectionname="";
						$sectionid="";
						$class_sectionsdata=mj_smgt_get_class_sections($class['class_id']);
						if(!empty($class_sectionsdata))
						{
							foreach($class_sectionsdata as $section)
							{  
								$i++;
								?>
								<div class="accordion-item mt-1">
										<h4 class="accordion-header" id="heading<?php echo $i;?>">
											<a data-toggle="collapse" data-parent="#accordion"
												href="#collapse<?php echo $i;?>">
											<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
        												<?php esc_attr_e('Class', 'school-mgt'); ?> : <?php echo $class['class_name']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
												<?php 
												if(!empty($section->section_name))
												{ ?>
												<?php esc_attr_e('Section', 'school-mgt'); ?> : <?php echo $section->section_name; ?>
												<?php }
												?>
											</a>
										</h4>
									<div id="collapse<?php echo $i;?>" class="accordion-collapse collapse" show" aria-labelledby="heading<?php echo $i;?>" data-bs-parent="#accordionExample">
										<div class="panel-body">
											<table class="table table-bordered table_left" cellspacing="0" cellpadding="0"
												border="0">
										<?php
												foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )
												{
												?>
													<tr>
														<th width="100"><?php echo $dayname;?></th>
															<td>
																<?php
																$period = $obj_route->mj_smgt_get_periad ( $class['class_id'],$section->id, $daykey );
																if (! empty ( $period ))
																	foreach ( $period as $period_data )
																	{?>
																	<?php 
																		echo '<div class="btn-group m-b-sm">';
																		echo '<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id);
																		
																		$start_time_data = explode(":", $period_data->start_time);
																		$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
																		$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
																		$start_am_pm=$start_time_data[2];
																		
																		$end_time_data = explode(":", $period_data->end_time);
																		$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
																		$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
																		$end_am_pm=$end_time_data[2];
																		echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';
																		echo "</span><span class='caret'></span></button>";
																		$virtual_classroom_page_name = 'virtual_classroom';
																	$virtual_classroom_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($virtual_classroom_page_name);
																	if (get_option('smgt_enable_virtual_classroom') == 'yes')
																	{
																		if ($virtual_classroom_access_right['view'] == '1')
																		{
																			$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);
																			if(empty($meeting_data))
																			{
																				if ($virtual_classroom_access_right['add'] == '1') 
																				{
																					$create_meeting = '<li><a href="#" id="'.$period_data->route_id.'" class="show-popup">'.esc_attr__('Create Virtual Class','school-mgt').'</a></li>';
																				}
																			}
																			else
																			{
																				$create_meeting = '';
																			}

																			if(!empty($meeting_data))
																			{
																				if ($virtual_classroom_access_right['edit'] == '1') 
																				{
																					$update_meeting = '<li><a href="?dashboard=user&page=virtual_classroom&tab=edit_meeting&action=edit&meeting_id='.$meeting_data->meeting_id.'">'.esc_attr__('Edit Virtual Class','school-mgt').'</a></li>';
																				}
																				if ($virtual_classroom_access_right['delete'] == '1') 
																				{
																					$delete_meeting = '<li><a href="?dashboard=user&page=virtual_classroom&tab=meeting_list&action=delete&meeting_id='.$meeting_data->meeting_id.'" onclick="return confirm(\''.esc_attr__( 'Are you sure you want to delete this record?', 'school-mgt' ).'\');">'.esc_attr__('Delete Virtual Class','school-mgt').'</a></li>';
																				}
																				$meeting_statrt_link = '<li><a href="'.$meeting_data->meeting_start_link.'" target="_blank">'.esc_attr__('Start Virtual Class','school-mgt').'</a></li>';
																			}
																			else
																			{
																				$update_meeting = '';
																				$delete_meeting = '';
																				$meeting_statrt_link = '';
																			}
																		}
																	}
																	if($user_access['edit']=='1')
																	{
																		$edit_route='<li><a href="?dashboard=user&page=schedule&tab=addroute&action=edit&route_id='.$period_data->route_id.'">'.esc_attr__('Edit Route','school-mgt').'</a></li>';
																	}	
																	else {
																		$edit_route="";
																	}
																	if($user_access['delete']=='1')
																	{
																		$delete_route='<li><a onclick="return confirm(\'Do you want to to delet route?\');" href="?dashboard=user&page=schedule&tab=schedulelist&action=delete&route_id='.$period_data->route_id.'">'.esc_attr__('Delete','school-mgt').'</a></li>';
																	}	
																	else {
																		$delete_route="";
																	}
																		echo "</span></span> </button>";
																							
																		echo '<ul role="menu" class="dropdown-menu">
																			'.$edit_route.''.$delete_route.''.$create_meeting .''.$update_meeting.''.$delete_meeting.''.$meeting_statrt_link.'
																			
																		</ul>';
																		echo '</div>';
																	}
																	?>
															</td>
													</tr>
										<?php	} ?>
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
				}
				if($active_tab=='addroute')
				{
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
									wp_redirect ( home_url() . '?dashboard=user&page=schedule&tab=schedulelist&message=4');
									exit;
								}
								else
								{
									$retuen_val = $obj_route->mj_smgt_is_route_exist($route_data);
									
									if($retuen_val == 'success')
									{
										$obj_route->mj_smgt_save_route($route_data);
										wp_redirect ( home_url() . '?dashboard=user&page=schedule&tab=schedulelist&message=1');
										exit;
									}
									elseif($retuen_val == 'duplicate')
									{       
										wp_redirect ( home_url() . '?dashboard=user&page=schedule&tab=schedulelist&message=2');
										exit;
									}
									elseif($retuen_val == 'teacher_duplicate')
									{
										wp_redirect ( home_url() . '?dashboard=user&page=schedule&tab=schedulelist&message=3');
										exit;
									}                
								} 
							}
						}
					}
				?>
				<div class="panel panel-white">
					<?php 	
						$edit=0;
						if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
						{
							$edit=1;
							$route_data= mj_smgt_get_route_by_id($_REQUEST['route_id']);
						}
					?>
						<div class="panel-body">   
							<form name="route_form" action="" method="post" class="form-horizontal" id="rout_form">
							<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
							<input type="hidden" name="action" value="<?php echo $action;?>">
							
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label col-form-label text-md-end" for="class_list"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-8">
								<?php if($edit){ $classval=$route_data->class_id; }elseif(isset($_POST['class_id'])){$classval=$_POST['class_id'];}else{$classval='';}?>
									<select name="class_id"  id="class_list" class="form-control validate[required] max_width_100">
										<option value=" "><?php esc_attr_e('Select class Name','school-mgt');?></option>
										<?php
										foreach(mj_smgt_get_allclass() as $classdata)
										{  
										?>
										<option  value="<?php echo $classdata['class_id'];?>" <?php   selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
									<?php }?>
									</select>
								</div>
							</div>
							<?php wp_nonce_field( 'save_root_admin_nonce' ); ?>
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
								<div class="col-sm-8">
									<?php if($edit){ $sectionval=$route_data->section_name; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
										<select name="class_section" class="form-control max_width_100 section_id_exam" id="class_section">
											<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
											<?php
											if($edit){
												foreach(mj_smgt_get_class_sections($route_data->class_id) as $sectiondata)
												{  ?>
													<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
											<?php } 
											}?>
										</select>
									</div>
							</div>
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_list"><?php esc_attr_e('Subject','school-mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-8">
								<?php if($edit){ $subject_id=$route_data->subject_id; }elseif(isset($_POST['subject_id'])){$subject_id=$_POST['subject_id'];}else{$subject_id='';}?>
									<select name="subject_id" id="subject_list" class="form-control validate[required] max_width_100">
									<?php
									if( $edit )
									{
										$subject = mj_smgt_get_subject_by_classid($route_data->class_id);
										if(!empty($subject))
										{
											foreach ($subject as $ubject_data)
											{
											?>
												<option value="<?php echo $ubject_data->subid ;?>" <?php selected($subject_id, $ubject_data->subid);  ?>><?php echo $ubject_data->sub_name;?></option>
											<?php 
											}
										}
									}
									else 
									{
									?>
										<option value=""><?php esc_attr_e('Select Subject','school-mgt');?></option>
									<?php
									}
									?>
									</select>
								</div>
							</div>
							
							
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label col-form-label text-md-end" for="weekday"><?php esc_attr_e('Day','school-mgt');?></label>
								<div class="col-sm-8">
								<?php if($edit){ $day_key=$route_data->weekday; }elseif(isset($_POST['weekday'])){$day_key=$_POST['weekday'];}else{$day_key='';}?>
									<select name="weekday" class="form-control validate[required] max_width_100" id="weekday">
										<?php 
										foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)
											echo '<option  value="'.$daykey.'" '.selected($day_key,$daykey).'>'.$dayname.'</option>';
										?>
									</select>
								</div>
							</div>
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label col-form-label text-md-end" for="weekday"><?php esc_attr_e('Start Time','school-mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-2">
									<?php 
									if($edit)
									{
										$start_time_data = explode(":", $route_data->start_time);
										
									}
									?>
									<select name="start_time" class="form-control validate[required]">
									<?php 
									for($i =0 ; $i <= 12 ; $i++)
									{
									?>
										<option value="<?php echo $i;?>" <?php  if($edit) selected($start_time_data[0],$i);  ?>><?php echo $i;?></option>
									<?php
									}
									?>
									</select>
								</div>
								<div class="col-sm-2">
									<select name="start_min" class="form-control validate[required] margin_top_10_res">
											<?php 
												for($i =0 ; $i <= 59 ; $i++)
												{
												?>
												<option value="<?php echo $i;?>" <?php  if($edit) selected($start_time_data[1],$i);  ?>><?php echo $i;?></option>
												<?php
												}
											?>
											</select>
								</div>
								<div class="col-sm-2">
									<select name="start_ampm" class="form-control validate[required] margin_top_10_res">
												<option value="am" <?php  if($edit) if(isset($start_time_data[2])) selected($start_time_data[2],'am');  ?>><?php esc_attr_e('A.M.','school-mgt');?></option>
												<option value="pm" <?php  if($edit) if(isset($start_time_data[2])) selected($start_time_data[2],'pm');  ?>><?php esc_attr_e('P.M.','school-mgt');?></option>
									</select>
								</div>
							</div>
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label col-form-label text-md-end" for="weekday"><?php esc_attr_e('End Time','school-mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-2">
								<?php 
								if($edit)
								{
									$end_time_data = explode(":", $route_data->end_time);
								} ?>
									<select name="end_time" class="form-control validate[required]">
									<?php 
										for($i =0 ; $i <= 12 ; $i++)
										{
										?>
											<option value="<?php echo $i;?>" <?php  if($edit) selected($end_time_data[0],$i);  ?>><?php echo $i;?></option>
										<?php
										}
										?>
									</select>
								</div>
								<div class="col-sm-2">
									<select name="end_min" class="form-control validate[required] margin_top_10_res">
									<?php 
										for($i =0 ; $i <= 59 ; $i++)
										{
										?>
											<option value="<?php echo $i;?>" <?php  if($edit) selected($end_time_data[1],$i);  ?>><?php echo $i;?></option>
										<?php
										}
										?>
									</select>
								</div>
								<div class="col-sm-2">
									<select name="end_ampm" class="form-control validate[required] margin_top_10_res">
										<option value="am" <?php  if($edit) if(isset($end_time_data[2])) selected($end_time_data[2],'am');  ?> ><?php esc_attr_e('A.M.','school-mgt');?></option>
										<option value="pm" <?php  if($edit) if(isset($end_time_data[2]))selected($end_time_data[2],'pm');  ?>><?php esc_attr_e('P.M.','school-mgt');?></option>
									</select>
								</div>
							</div>
							<div class="offset-sm-2 col-sm-8">        	
								<input type="submit" value="<?php if($edit){ esc_attr_e('Save Route','school-mgt'); }else{ esc_attr_e('Add Route','school-mgt');}?>" name="save_route" class="btn btn-success" />
							</div>        
						</form>
						</div>
					</div>     

				<?php	
				}



			}
			else if($school_obj->role == 'student')
			{
			    $class = $school_obj->class_info;
			    $sectionname="";
			    $section=0;
			    $section = get_user_meta(get_current_user_id(),'class_section',true);
				if($section!="")
				{
					$sectionname = mj_smgt_get_section_name($section);
				}
				else
				{
					$section=0;
				}
			?>
				<div class="accordion-item mt-1">
						<h4 class="accordion-header" id="heading<?php echo $i;?>">
							<a  class="class_section_a_tag" data-toggle="collapse" data-parent="#accordion"
								href="#collapse<?php echo $i;?>">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
       									<?php echo esc_attr_e( 'Class', 'school-mgt' ) ;?> : <?php echo $class->class_name; ?> &nbsp;&nbsp;
										<?php echo esc_attr_e( 'Section', 'school-mgt' ) ;?> : 
										<?php echo $sectionname; ?></a>
						</h4>
					<div id="collapse<?php echo $i;?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $i;?>" data-bs-parent="#accordionExample">
						<div class="panel-body">
							<table class="table table-bordered" cellspacing="0" cellpadding="0"
								border="0">
						<?php
								foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname ) 
								{ ?>
									<tr>
										<th width="100"><?php echo $dayname;?></th>
											<td>
												<?php
													$period = $obj_route->mj_smgt_get_periad ( $class->class_id,$section,$daykey );
													if (! empty ( $period ))
														foreach ( $period as $period_data )
														{
															$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);
															if(!empty($meeting_data))
															{
																$data_toggle = 'data-toggle="dropdown"';
															}
															else
															{
																$data_toggle = '';
															}
															echo '<div class="btn-group m-b-sm">';
															echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" '.$data_toggle.'><span class="period_box" id=' . $period_data->route_id . '>' . mj_smgt_get_single_subject_name( $period_data->subject_id );
															$start_time_data = explode(":", $period_data->start_time);
															$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
															$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
															$start_am_pm=$start_time_data[2];
															
															$end_time_data = explode(":", $period_data->end_time);
															$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
															$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
															$end_am_pm=$end_time_data[2];
															echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';
															$virtual_classroom_page_name = 'virtual_classroom';
															$virtual_classroom_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($virtual_classroom_page_name);
															if (get_option('smgt_enable_virtual_classroom') == 'yes')
															{
																if ($virtual_classroom_access_right['view'] == '1')
																{
																	if(!empty($meeting_data))
																	{
																		$meeting_join_link = '<li><a href="'.$meeting_data->meeting_join_link.'" target="_blank">'.esc_attr__('Join Virtual Class','school-mgt').'</a></li>';
																	}
																	else
																	{
																		$meeting_join_link = '';
																	}
																}
																echo "<span class='caret'></span></button>";
																echo '<ul role="menu" class="dropdown-menu">
																	'.$meeting_join_link.'
																</ul>';
																echo '</div>';
															}
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
			else if($school_obj->role == 'parent')
			{
				$chil_array =$school_obj->child_list;
				$i = 0;
				if(!empty($chil_array))
				{
					foreach($chil_array as $child_id)
					{
						$i++;
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
						?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a class="class_section_a_tag" data-bs-toggle="collapse" data-parent="#accordion"
										href="#collapse<?php echo $i;?>">
									   <?php echo esc_attr_e( 'Class', 'school-mgt' ) ;?> : <?php echo $class->class_name; ?> &nbsp;&nbsp;
										<?php echo esc_attr_e( 'Section', 'school-mgt' ) ;?> : 
										<?php echo $sectionname; ?></a>
								</h4>
							</div>
							<div id="collapse<?php echo $i;?>" class="panel-collapse collapse <?php if($i== 1) echo 'in';?>">
								<div class="panel-body">
									<table class="table table-bordered" cellspacing="0" cellpadding="0"
										border="0">
									<?php
										foreach ( mj_smgt_sgmt_day_list() as $daykey => $dayname )
										{
										?>
											<tr>
												<th width="100"><?php echo $dayname;?></th>
												<td>
													<?php  
													$period = $obj_route->mj_smgt_get_periad ( $class->class_id,$section,$daykey );
														if (! empty ( $period ))
															foreach ( $period as $period_data ) 
															{
																$meeting_data = $obj_virtual_classroom->mj_smgt_get_singal_meeting_by_route_data_in_zoom($period_data->route_id);
																if(!empty($meeting_data))
																{
																	$data_toggle = 'data-toggle="dropdown"';
																}
																else
																{
																	$data_toggle = '';
																}
																echo '<div class="btn-group m-b-sm">';
																echo '<button class="btn btn-primary dropdown-toggle" aria-expanded="false" '.$data_toggle.'><span class="period_box" id=' . $period_data->route_id . '>' . mj_smgt_get_single_subject_name( $period_data->subject_id );
																$start_time_data = explode(":", $period_data->start_time);
																$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
																$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
																$start_am_pm=$start_time_data[2];
																
																$end_time_data = explode(":", $period_data->end_time);
																$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
																$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
																$end_am_pm=$end_time_data[2];
																echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';
																$virtual_classroom_page_name = 'virtual_classroom';
																$virtual_classroom_access_right = mj_smgt_get_userrole_wise_filter_access_right_array($virtual_classroom_page_name);
																if (get_option('smgt_enable_virtual_classroom') == 'yes')
																{
																	if ($virtual_classroom_access_right['view'] == '1')
																	{
																		if(!empty($meeting_data))
																		{
																			$meeting_join_link = '<li><a href="'.$meeting_data->meeting_join_link.'" target="_blank">'.esc_attr__('Join Virtual Class','school-mgt').'</a></li>';
																		}
																		else
																		{
																			$meeting_join_link = '';
																		}
																	}
																	echo "<span class='caret'></span></button>";
																	echo '<ul role="menu" class="dropdown-menu">
																		'.$meeting_join_link.'
																	</ul>';
																	echo '</div>';
																}
															}
														?>
												</td>
											</tr>
								<?php 	} ?>
									</table>
								</div>
							</div>
						</div>
						<?php 
					}
				}
				else
				{
					esc_attr_e( 'Child data not avilable', 'school-mgt' );
				}
			} 
		?>		
		</div>
	</div>
</div>	
<?php ?>