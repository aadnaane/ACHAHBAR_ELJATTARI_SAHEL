<script type="text/javascript">
jQuery(document).ready(function()
{
	"use strict";	
	$('.sdate').datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		maxDate: 0,
		beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		}); 
		$('.edate').datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		maxDate: 0,
		beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		}); 

	var table =  jQuery('#attendance_teacher_list').DataTable({
						responsive: true,
						"order": [[ 0, "asc" ]],
						dom: 'Bfrtip',
							buttons: [
							{
						extend: 'print',
						title: 'View Attendance',},
						{
						extend: 'pdf',
						title: 'View Attendance',
						}
							],
						"aoColumns":[	                  
						{"bSortable": true},
						{"bSortable": true},					           
						{"bSortable": true},					           
						{"bSortable": true},					           
						{"bSortable": false}],	
						language:<?php echo mj_smgt_datatable_multi_language();?>	
					});

	jQuery('#teacher_list1').DataTable({
        responsive: true,
		language:<?php echo mj_smgt_datatable_multi_language();?>	
    });

    $('#teacher_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

	$('#class_id').multiselect(
	{
			nonSelectedText :'<?php esc_attr_e( 'Select Class', 'school-mgt' ) ;?>',
			includeSelectAllOption: true,
			selectAllText : '<?php esc_attr_e( 'Select all', 'school-mgt' ) ;?>',
			templates: {
           		button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       		}
	});
		$('#birth_date').datepicker({
			 maxDate : 0,
			 dateFormat: "yy-mm-dd",
			  changeMonth: true,
				changeYear: true,
				yearRange:'-65:+25',
				beforeShow: function (textbox, instance) 
				{
					instance.dpDiv.css({
						marginTop: (-textbox.offsetHeight) + 'px'                   
					});
				},
				onChangeMonthYear: function(year, month, inst) {
					$(this).val(month + "/" + year);
				},
		}); 

		$(".class_for_alert").click(function()
		{	
			let checked = $(".form-check-input:checked").length;
			if(!checked)
			{
			  alert(language_translate2.one_class_select_alert);
			  return false;
			}	
		});

	$(".view_more_details_div").on("click", ".view_more_details", function(event)
	{
		$('.view_more_details_div').removeClass("d-block");
		$('.view_more_details_div').addClass("d-none");

		$('.view_more_details_less_div').removeClass("d-none");
		$('.view_more_details_less_div').addClass("d-block");

		$('.user_more_details').removeClass("d-none");
		$('.user_more_details').addClass("d-block");

	});		
	$(".view_more_details_less_div").on("click", ".view_more_details_less", function(event)
	{
		$('.view_more_details_div').removeClass("d-none");
		$('.view_more_details_div').addClass("d-block");

		$('.view_more_details_less_div').removeClass("d-block");
		$('.view_more_details_less_div').addClass("d-none");

		$('.user_more_details').removeClass("d-block");
		$('.user_more_details').addClass("d-none");
	});		
});
</script>
<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'teacherlist';
$teacher_obj = new Smgt_Teacher;
$role='teacher';
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
//------------- SAVE TEACHER -------------//
if(isset($_POST['save_teacher']))
{	
	$firstname=mj_smgt_onlyLetter_specialcharacter_validation($_POST['first_name']);
	$lastname=mj_smgt_onlyLetter_specialcharacter_validation($_POST['last_name']);
	$userdata = array(
		'user_login'=>mj_smgt_username_validation($_POST['username']),			
		'user_nicename'=>NULL,
		'user_email'=>mj_smgt_email_validation($_POST['email']),
		'user_url'=>NULL,
		'display_name'=>$firstname." ".$lastname,
	);
	if($_POST['password'] != "")
		$userdata['user_pass']=mj_smgt_password_validation($_POST['password']);
	if(isset($_FILES['upload_user_avatar_image']) && !empty($_FILES['upload_user_avatar_image']) && $_FILES['upload_user_avatar_image']['size'] !=0)
	{
		if($_FILES['upload_user_avatar_image']['size'] > 0)
			$member_image=mj_smgt_load_documets($_FILES['upload_user_avatar_image'],'upload_user_avatar_image','pimg');
			$photo=content_url().'/uploads/school_assets/'.$member_image;
	}
	else
	{
		if(isset($_REQUEST['hidden_upload_user_avatar_image']))
		$member_image=$_REQUEST['hidden_upload_user_avatar_image'];
		$photo=$member_image;
	}
	$attechment='';
	if(!empty($_POST['attachment']))
	{
		$attechment=implode(',',$_POST['attachment']);
	}
	$usermetadata=array(
		'middle_name'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']),
		'gender'=>mj_smgt_onlyLetterSp_validation($_POST['gender']),
		'birth_date'=>$_POST['birth_date'],
		'address'=>mj_smgt_address_description_validation($_POST['address']),
		'city'=>mj_smgt_city_state_country_validation($_POST['city_name']),
		'state'=>mj_smgt_city_state_country_validation($_POST['state_name']),
		'zip_code'=>mj_smgt_onlyLetterNumber_validation($_POST['zip_code']),
		'class_name'=>$_POST['class_name'],
		'phone'=>mj_smgt_phone_number_validation($_POST['phone']),
		'mobile_number'=>mj_smgt_phone_number_validation($_POST['mobile_number']),
		'alternet_mobile_number'=>mj_smgt_phone_number_validation($_POST['alternet_mobile_number']),
		'working_hour'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['working_hour']),
		'possition'=>mj_smgt_address_description_validation($_POST['possition']),
		'smgt_user_avatar'=>$photo,
		'attachment'=>$attechment,
		'created_by'=>get_current_user_id()
	);
	if($_REQUEST['action']=='edit')
	{		
		$userdata['ID']=$_REQUEST['teacher_id'];
		$result=mj_smgt_update_user($userdata,$usermetadata,$firstname,$lastname,$role);
		$result1 = $teacher_obj->mj_smgt_update_multi_class($_POST['class_name'],$_REQUEST['teacher_id']);
		wp_redirect ( home_url() . '?dashboard=user&page=teacher&tab=teacherlist&message=2'); 		
	}
	else
	{
		if( !email_exists( $_POST['email'] ) && !username_exists( mj_smgt_strip_tags_and_stripslashes($_POST['username']))) 
		{
			$result=mj_smgt_add_newuser($userdata,$usermetadata,$firstname,$lastname,$role);
			$result1 = $teacher_obj->mj_smgt_add_muli_class($_POST['class_name'],mj_smgt_strip_tags_and_stripslashes($_POST['username']));
			wp_redirect ( home_url() . '?dashboard=user&page=teacher&tab=teacherlist&message=1'); 					
		}
		else 
		{
		?>
			<div class="alert_msg alert alert-success alert-dismissible " role="alert">
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<?php esc_attr_e('Username Or Emailid All Ready Exist.','school-mgt');?>
			</div>
	<?php 
		}
	}
}
//-------------------- DELETE TEACHER ---------------------//
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{				
	$result=mj_smgt_delete_usedata($_REQUEST['teacher_id']);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=teacher&tab=teacherlist&message=5'); 			
	}
}
//------------------ MULTIPLE DELETE TEACHER -------------//
if(isset($_REQUEST['delete_selected']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
		$result=mj_smgt_delete_usedata($id);
	if($result)
	{ 
		wp_redirect ( home_url() . '?dashboard=user&page=teacher&tab=teacherlist&message=5'); 	
	}
}
 
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Teacher Added Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Teacher Updated Successfully.','school-mgt');
			break;
		case '3':
			$message_string = esc_attr__('Roll No Already Exist.','school-mgt');
			break;
		case '4':
			$message_string = esc_attr__('Teacher Username Or Emailid Already Exist.','school-mgt');
			break;
		case '5':
			$message_string = esc_attr__('Teacher Deleted Successfully.','school-mgt');
			break;
		case '6':
			$message_string = esc_attr__('Teacher CSV Successfully Uploaded.','school-mgt');
			break;
		case '7':
			$message_string = esc_attr__('Student Activated Auccessfully.','school-mgt');
			break;
	}
	if($message)
	{
		?>
		<div class="alert_msg alert alert-success alert-dismissible " role="alert">
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<?php echo $message_string;?>
		</div>
		<?php
	}
//-------------- VIEW ATTENDANCE TEACHER ------------------//
if(isset($_REQUEST['attendance']) && $_REQUEST['attendance'] == 1)
{ ?>

	
<div class="p-4 panel-body panel-white">
<ul class="nav nav-tabs panel_tabs" role="tablist">
    <li class="nav-item">
        <a href="#child" role="tab" class="nav-link tab active">
            <i class="fa fa-align-justify"></i> <?php esc_attr_e('Attendance', 'school-mgt'); ?></a>
        </a>
    </li>
</ul>  
<div class="tab-content">      
	<div class="panel-body">
		<form name="wcwm_report" action="" method="post">
			<input type="hidden" name="attendance" value=1> 
			<input type="hidden" name="user_id" value=<?php echo $_REQUEST['teacher_id'];?>>       
				<div class="row">
					<div class="form-group col-md-3">
						<label for="exam_id"><?php esc_attr_e('Start Date','school-mgt');?></label>
						<input type="text"  class="form-control sdate" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d');?>" >
					</div>
				
				<div class="form-group col-md-3">
					<label for="exam_id"><?php esc_attr_e('End Date','school-mgt');?></label>
						<input type="text"  class="form-control edate" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>">            	
				</div>
				<div class="form-group col-md-3 button-possition">
					<label for="subject_id">&nbsp;</label>
					<input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info"/>
				</div>
				</div>	
		</form>
		<div class="clearfix"></div>
			<?php if(isset($_REQUEST['view_attendance']))
			{
				$start_date = $_REQUEST['sdate'];
				$end_date = $_REQUEST['edate'];
				$user_id = $_REQUEST['user_id'];
				$attendance = mj_smgt_view_student_attendance($start_date,$end_date,$user_id);	
				$curremt_date =$start_date;
			?>	
			
				<div class="panel-body">
					<div class="table-responsive">
						<table id="attendance_teacher_list" class="display dataTable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><?php esc_attr_e('Teacher Name','school-mgt');?></th>
									<th><?php esc_attr_e('Class Name','school-mgt');?></th>
									<th><?php esc_attr_e('Date','school-mgt');?></th>
									<th><?php esc_attr_e('Day','school-mgt');?></th>
									<th><?php esc_attr_e('Attendance','school-mgt');?></th>				
								</tr>
							</thead>
					 
							<tfoot>
								<tr>
									<th><?php esc_attr_e('Teacher Name','school-mgt');?></th>
									<th><?php esc_attr_e('Class Name','school-mgt');?></th>
									<th><?php esc_attr_e('Date','school-mgt');?></th>
									<th><?php esc_attr_e('Day','school-mgt');?></th>
									<th><?php esc_attr_e('Attendance','school-mgt');?></th>				
								</tr>
							</tfoot> 
							<tbody>
								<?php 
								while ($end_date >= $curremt_date)
								{
									echo '<tr>';
									echo '<td>';
									echo mj_smgt_get_display_name($user_id);
									echo '</td>';
									
									echo '<td>';
										$class='';
										foreach(get_user_meta($user_id, 'class_name',true) as $class_id)
										{
											$class .= mj_smgt_get_class_name_by_id($class_id).", ";
										}
										$space_trim = rtrim($class, ' '); 					
										$class_name = rtrim($space_trim, ','); 					
										echo $class_name;
								
									echo '</td>';
									
									echo '<td>';
									echo $curremt_date;
									echo '</td>';
									
									$attendance_status = mj_smgt_get_attendence($user_id,$curremt_date);
									echo '<td>';
									$day=date("D", strtotime($curremt_date));
									echo esc_attr__("$day","school-mgt"); 
									echo '</td>';
									
									if(!empty($attendance_status))
									{
										echo '<td>';
										echo mj_smgt_get_attendence($user_id,$curremt_date);
										echo '</td>';
									}
									else 
									{
										echo '<td>';
										echo esc_attr__('Absent','school-mgt');
										echo '</td>';
									}
									
									echo '</tr>';
									$curremt_date = strtotime("+1 day", strtotime($curremt_date));
									$curremt_date = date("Y-m-d", $curremt_date);
								}
							?>
							</tbody>        
						</table>
					</div>
				</div>
			<?php 
			} ?>
	</div>
</div>
</div>
<?php 
}
else 
{?>
<div class="p-4 panel-body panel-white">
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="nav-item">
			<a href="?dashboard=user&page=teacher&tab=teacherlist" class=" nav-link tab <?php echo $active_tab == 'teacherlist' ? 'active' : ''; ?>">
				<i class="fa fa-align-justify"></i><?php esc_attr_e(' Teacher List', 'school-mgt'); ?></a>
			</a>
		</li>
		<li class="nav-item <?php if($active_tab=='addteacher'){?>active<?php }?>">
		  <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
			?>
				<a href="?dashboard=user&page=teacher&tab=addteacher&&action=edit&teacher_id=<?php echo $_REQUEST['teacher_id'];?>" class=" nav-link tab <?php echo $active_tab == 'addteacher' ? 'active' : ''; ?>">
				<i class="fa"></i> <?php esc_attr_e('Edit Teacher', 'school-mgt'); ?></a>
			 <?php 
			}
			else
			{
				if($user_access['add']=='1')
				{			
				?>				
					<a href="?dashboard=user&page=teacher&tab=addteacher&action=insert" class="nav-link tab  <?php echo $active_tab == 'addteacher' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Add New Teacher', 'school-mgt'); ?></a>
				<?php
				}
			}
			?>	  
		</li>
		<li class="nav-item <?php if($active_tab=='view_teacher'){?>active<?php }?>">
		  <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_teacher')
			{
			?>
				<a href="?dashboard=user&page=teacher&tab=view_teacher&action=view_teacher&teacher_id=<?php echo $_REQUEST['teacher_id'];?>" class="nav-link tab <?php echo $active_tab == 'view_teacher' ? 'active' : ''; ?>">
				<i class="fa fa-eye"></i> <?php esc_attr_e('View Teacher', 'school-mgt'); ?></a>
			 <?php 
			}
			?>
		</li>
	</ul>

	 
 <?php 
	//------------ TEACHER LIST ---------------//
	if($active_tab == 'teacherlist')
	{ 
	?>	

<div class="tab-content">      
    <div class="panel-body">
		<div class="table-responsive">
        <table id="teacher_list1" class="display dataTable teacher_datatable" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th width="75px"><?php echo esc_attr_e( 'Photo', 'school-mgt' ) ;?></th>
					<th><?php echo esc_attr_e( 'Teacher Name', 'school-mgt' ) ;?></th>
					<th> <?php echo esc_attr_e( 'Teacher Email', 'school-mgt' ) ;?></th>
					<th> <?php echo esc_attr_e( 'Mobile No', 'school-mgt' ) ;?></th>
					<th> <?php echo esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><?php echo esc_attr_e( 'Photo', 'school-mgt' ) ;?></th>
					<th><?php echo esc_attr_e( 'Teacher Name', 'school-mgt' ) ;?></th>
					<th> <?php echo esc_attr_e( 'Teacher Email', 'school-mgt' ) ;?></th>
					<th> <?php echo esc_attr_e( 'Mobile No', 'school-mgt' ) ;?></th>
					<th> <?php echo esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
				</tr>
			</tfoot> 
			<tbody>
				<?php 
				$user_id=get_current_user_id();
				//------- TEACHER DATA FOR STUDENT ---------//
				if($school_obj->role == 'student')
				{
					$own_data=$user_access['own_data'];
					if($own_data == '1')
					{ 
						$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);			
						$teacherdata	= 	mj_smgt_get_teacher_by_class_id($class_id);	
					}
					else
					{
						$teacherdata	=	mj_smgt_get_usersdata('teacher');
					}
				}
				//------- TEACHER DATA FOR TEACHER ---------//
				elseif($school_obj->role == 'teacher')
				{
					$own_data=$user_access['own_data'];
					if($own_data == '1')
					{ 
						$user_id=get_current_user_id();		
						
						$teacher_own=array();
						$teacherdata_created_by=array();
						
						$teacher_own[]=get_userdata($user_id);					
							
						$teacherdata_created_by[]= get_users(
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
						$teacherdata1=array_merge($teacher_own,$teacherdata_created_by);
						 
						$teacherdata=array_unique($teacherdata1, SORT_NUMERIC );
					}
					else
					{
						$teacherdata	=	mj_smgt_get_usersdata('teacher');
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
						$teacherdata=array_merge($teacherdata_data,$teacherdata_created_by);
					}
					else
					{
						$teacherdata	=	mj_smgt_get_usersdata('teacher');
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
					}
					else
					{
						$teacherdata	=	mj_smgt_get_usersdata('teacher');
					}
				} 
				if(!empty($teacherdata))
				{
					foreach ($teacherdata as $retrieved_data)
					{   
						if(! username_exists($retrieved_data->user_login)){ continue; } /* IF Teacher not exists then we dont want to print emprt row. */
					?>
					<tr>
						<td class="text-center user_image"><?php $uid=$retrieved_data->ID;
							$umetadata=mj_smgt_get_user_image($uid);
							if(empty($umetadata))
							{
								echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle" />';
							}
							else
							{
								echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle"/>';
							}
						?></td>
						<td class="name"><?php echo $retrieved_data->display_name;?></td>
						<td class="email"><?php echo $retrieved_data->user_email;?></td>
						<td class=""><?php echo get_user_meta($uid,'phone',true);?></td>
					   <td>
						<a href="?dashboard=user&page=teacher&tab=view_teacher&action=view_teacher&teacher_id=<?php echo $retrieved_data->ID;?>" class="btn btn-success"><?php esc_attr_e('View','school-mgt');?></a>
						<?php
						if($user_access['edit']=='1')
						{
						?>
							<a href="?dashboard=user&page=teacher&tab=addteacher&action=edit&teacher_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"> <?php esc_attr_e('Edit', 'school-mgt' ) ;?></a>
					   <?php
						}
						if($user_access['delete']=='1')
						{
						?>
						   <a href="?dashboard=user&page=teacher&tab=teacherlist&action=delete&teacher_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
						onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
						<?php esc_attr_e( 'Delete', 'school-mgt' ) ;?> </a>
						<?php
						}?>
						 <?php if($retrieved_data->ID == get_current_user_id() || $school_obj->role == 'supportstaff')
						{ ?>
						<a href="?dashboard=user&page=teacher&teacher_id=<?php echo $retrieved_data->ID;?>&attendance=1" class="btn btn-default" idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php esc_attr_e('View Attendance','school-mgt');?> </a>
						<?php
						}?>
					   </td>
					</tr>
					<?php 
					}
				} ?>     
			</tbody>        
        </table>
		</div>
	</div>
	</div>
</div>
<?php 
	}
	if($active_tab == 'addteacher')
	{  
		$role='teacher'; ?>
	<?php 
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
		$edit=1;
		$user_info = get_userdata($_REQUEST['teacher_id']);
	}
	?>
    <div class="panel-body">
       	<form name="teacher_form" action="" method="post" class="mt-3 form-horizontal" id="teacher_form" enctype="multipart/form-data">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="role" value="<?php echo $role;?>"  />
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="first_name"><?php esc_attr_e('First Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="middle_name"><?php esc_attr_e('Middle Name','school-mgt');?></label>
			<div class="col-sm-8">
				<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="last_name"><?php esc_attr_e('Last Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="gender"><?php esc_attr_e('Gender','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
				<label class="radio-inline">
			     <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_attr_e('Male','school-mgt');?> 
			    </label>
			    <label class="radio-inline">
			      <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_attr_e('Female','school-mgt');?> 
			    </label>
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="birth_date"><?php esc_attr_e('Date of birth','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="birth_date" class="form-control validate[required]" type="text"  name="birth_date" 
				value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($user_info->birth_date);}elseif(isset($_POST['birth_date'])) echo mj_smgt_getdate_in_input_box($_POST['birth_date']);?>" readonly>
			</div>
		</div>	
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="address"><?php esc_attr_e('Address','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text"  name="address" 
				value="<?php if($edit){ echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="city_name"><?php esc_attr_e('City','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
				value="<?php if($edit){ echo $user_info->city;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="state_name"><?php esc_attr_e('State','school-mgt');?></label>
			<div class="col-sm-8">
				<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" 
				value="<?php if($edit){ echo $user_info->state;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="zip_code"><?php esc_attr_e('Zip Code','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" maxlength="15" type="text"  name="zip_code" 
				value="<?php if($edit){ echo $user_info->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8 multiselect_validation_class">
				<?php
				 if($edit){ $classval=$user_info->class_name; }elseif(isset($_POST['class_name'])){ $classval=$_POST['class_name'];}else{$classval='';}
				 $classes = array();
				 if(isset($_REQUEST['teacher_id']))
				 $classes = $teacher_obj->mj_smgt_get_class_by_teacher($_REQUEST['teacher_id']);
				 
				 ?>
                   
                <select name="class_name[]" multiple="multiple" id="class_id" class="form-control validate[required]">
                    <?php
						foreach(mj_smgt_get_allclass() as $classdata)
						{ ?>
							<option value="<?php echo $classdata['class_id'];?>"<?php echo $teacher_obj->mj_smgt_in_array_r($classdata['class_id'], $classes) ? 'selected' : ''; ?>><?php echo $classdata['class_name'];?></option>
						<?php 
						}
						?>
                </select>
					 
					 
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="mobile_number"><?php esc_attr_e('Mobile Number','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-1">
			
			<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="phonecode">
			</div>
			<div class="col-sm-7">
				
				<input id="mobile_number" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="mobile_number"
				value="<?php if($edit){ echo $user_info->mobile_number;}elseif(isset($_POST['mobile_number'])) echo $_POST['mobile_number'];?>">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="mobile_number"><?php esc_attr_e('Alternate Mobile Number','school-mgt');?></label>
			<div class="col-sm-1">
			
			<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="alter_mobile_number">
			</div>
			<div class="col-sm-7">
				<input id="alternet_mobile_number" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="alternet_mobile_number"
				value="<?php if($edit){ echo $user_info->alternet_mobile_number;}elseif(isset($_POST['alternet_mobile_number'])) echo $_POST['alternet_mobile_number'];?>">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end " for="phone"><?php esc_attr_e('Phone','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="phone" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="phone" 
				value="<?php if($edit){ echo $user_info->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end " for="email"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
				value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="working_hour"><?php esc_attr_e('Working Hour','school-mgt');?></label>
			<div class="col-sm-8">
				<?php if($edit){ $workrval=$user_info->working_hour; }elseif(isset($_POST['working_hour'])){$workrval=$_POST['working_hour'];}else{$workrval='';}?>
                     <select name="working_hour" class="form-control " id="working_hour">
                     	<option value=""><?php esc_attr_e('select job time','school-mgt');?></option>
                        <option value="full_time" <?php selected( $workrval, 'full_time'); ?>><?php esc_attr_e('Full Time','school-mgt');?></option>
                        <option value="half_day" <?php selected( $workrval, 'half_day'); ?>><?php esc_attr_e('Part time','school-mgt');?></option>
                     </select>
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="possition "><?php esc_attr_e('Position','school-mgt');?></label>
			<div class="col-sm-8">
				<input id="possition" class="form-control validate[custom[address_description_validation]]" maxlength="50" type="text"  name="possition" 
				value="<?php if($edit){ echo $user_info->possition;}elseif(isset($_POST['possition'])) echo $_POST['possition'];?>">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="username"><?php esc_attr_e('User Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="username" class="form-control validate[required,custom[username_validation]]" maxlength="50" type="text"  name="username" 
				value="<?php if($edit){ echo $user_info->user_login;}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?>>
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="password"><?php esc_attr_e('Password','school-mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
			<div class="col-sm-8">
				<input id="password" class="form-control <?php if(!$edit){ echo 'validate[required,minSize[8],maxSize[12]]'; }else{ echo 'validate[minSize[8],maxSize[12]]'; } ?>" type="password"  name="password" value="">
			</div>
		</div>
		<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="photo"><?php esc_attr_e('Image','school-mgt');?></label>
				<div class="col-sm-2">
					<input type="text" id="amgt_user_avatar_url" class="form-control" name="smgt_user_avatar"  
					value="<?php if($edit)echo esc_url( $user_info->smgt_user_avatar );elseif(isset($_POST['smgt_user_avatar'])) echo $_POST['smgt_user_avatar']; ?>" />
					<input type="hidden" class="form-control" name="hidden_upload_user_avatar_image"  
					value="<?php if($edit)echo esc_url( $user_info->smgt_user_avatar );elseif(isset($_POST['hidden_upload_user_avatar_image'])) echo $_POST['hidden_upload_user_avatar_image']; ?>" />
				</div>	
					<div class="col-sm-3">
						 <input id="upload_user_avatar" class="btn_top" name="upload_user_avatar_image" onchange="fileCheck(this);" type="file" />
				</div>
				<div class="clearfix"></div>
				
				<div class="offset-sm-2 col-sm-8">
					<div id="upload_user_avatar_preview" >
						<?php if($edit) 
						{
							if($user_info->smgt_user_avatar == "")
							{ ?>
								<img class="image_preview_css" src="<?php echo get_option( 'smgt_student_thumb' ); ?>">
						<?php }
						else { ?>
							<img class="image_preview_css" src="<?php if($edit)echo esc_url( $user_info->smgt_user_avatar ); ?>" />
						<?php }
						}
					else { 	?>
							<img class="image_preview_css" src="<?php echo get_option( 'smgt_student_thumb' ); ?>">
				 <?php } ?>
					</div>
				</div>
		</div>
		 
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="attachment"><?php esc_attr_e('Submitted Documents','school-mgt');?></label>
			<div class="col-sm-8">
				 <?php if($edit){	 $attachval=explode(',',$user_info->attachment); }
						
					?>
					<div class="checkbox">
				<label>
              <input type="checkbox" name="attachment[]"  value="cv" <?php if($edit){ if(in_array("cv", $attachval)){ echo "checked=\'checked\'"; } } ?> /><?php esc_attr_e('curriculum vitae','school-mgt');?>
              </label>
              </div>
              <div class="checkbox">
              <label>
              <input type="checkbox" name="attachment[]"  value="edu_certificate" <?php if($edit){ if(in_array("edu_certificate", $attachval)) {echo  "checked=\'checked\'"; } } ?>/><?php esc_attr_e('Education Certificate','school-mgt');?>
              </label>
              </div>
              <div class="checkbox">
              <label>
              <input type="checkbox" name="attachment[]"  value="experience_certificate" <?php if($edit){ if(in_array("experience_certificate", $attachval)) { echo "checked=\'checked\'"; }  }?> /><?php esc_attr_e('Experience Certificate','school-mgt');?>
              </label>
              </div>
                     
			</div>
		</div>
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Teacher','school-mgt'); }else{ esc_attr_e('Add Teacher','school-mgt');}?>" name="save_teacher" class="btn btn-success class_for_alert"/>
        </div>
        </form>
	</div>
<?php
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_teacher')
	{  
	?>
			
			<?php
				$teacher_obj = new Smgt_Teacher;
				$obj_route = new Class_routine();	
				$teacher_data=get_userdata($_REQUEST['teacher_id']);
			?>
			<div class="mt-4 panel-body">	
				<div class="box-body">
					<div class="row">
						<div class="col-md-3 col-sm-4 col-xs-12">	
							<?php
							$umetadata=mj_smgt_get_user_image($teacher_data->ID);
							if(empty($umetadata['meta_value']))
							{
								echo '<img class="img-circle rounded-circle img-responsive member-profile user_height_width" src='.get_option( 'smgt_student_thumb' ).'>';
							}
							else
								echo '<img class="img-circle rounded-circle img-responsive member-profile user_height_width" src='.$umetadata['meta_value'].'>';
							?>
						</div>
						
						<div class="col-md-9 col-sm-8 col-xs-12 ">
							<div class="row">
								<h2><?php echo $teacher_data->display_name;?></h2>
							</div>
							<div class="row">
								<div class="col-md-4 col-sm-3 col-xs-12">
									<i class="fa fa-envelope"></i>&nbsp;
									<span class="email-span"><?php echo $teacher_data->user_email;?></span>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									<i class="fa fa-phone"></i>&nbsp;
									<span><?php echo $teacher_data->phone;?></span>
								</div>
								<div class="col-md-5 col-sm-3 col-xs-12 no-padding">
									<i class="fa fa-map-marker"></i>&nbsp;
									<span><?php echo $teacher_data->address;?></span>
								</div>
							</div>					
						</div>
					</div>
						
					<div class="row">
						<div class="view-more view_more_details_div d-block">
							<h4><?php esc_attr_e( 'View More', 'school-mgt' ) ;?></h4>
								<i class="fa fa-angle-down bounce fa-2x view_more_details"></i>
						</div>
						<div class="view-more view_more_details_less_div d-none">
							<h4><?php esc_attr_e( 'View Less', 'school-mgt' ) ;?></h4>
								<i class="fa fa-angle-up fa-2x view_more_details_less"></i>
						</div>
					</div>
					<hr>
						<div class="user_more_details d-none">
							<div class="card">
								<div class="card-head">
									<i class="fa fa-user"></i>
									<span><b><?php esc_attr_e( 'Personal Information', 'school-mgt' ) ;?></b></span>
								</div>
								<div class="card-body">
									<div class="row">							
										<div class="col-md-2">
											<p class="user-lable"><?php esc_attr_e( 'Name', 'school-mgt' ) ;?></p>
										</div>
										<div class="col-md-4">
											<p class="user-info">: <?php echo $teacher_data->display_name;?></p>
										</div>
										
										<div class="col-md-2">
											<p class="user-lable"><?php esc_attr_e( 'Birth Date', 'school-mgt' ) ;?></p>
										</div>
										<div class="col-md-4">
											<p class="user-info">: <?php echo mj_smgt_getdate_in_input_box($teacher_data->birth_date);?></p>
										</div>
										<div class="col-md-2">
												<p class="user-lable"><?php esc_attr_e( 'Gender', 'school-mgt' ) ;?></p>
											</div>
										<div class="col-md-4">
												<p class="user-info">: <?php 
													if($teacher_data->gender=='male') 
														echo esc_attr__('Male','school-mgt');
													elseif($teacher_data->gender=='female') 
														echo esc_attr__('Female','school-mgt');
													?></p>
										</div>
																	
										<div class="col-md-2">
											<p class="user-lable"><?php esc_attr_e( 'Class Name', 'school-mgt' );?></p>
										</div>
										<?php
										$classes="";
										$classes = $teacher_obj->mj_smgt_get_class_by_teacher($teacher_data->ID);
										$classname = "";
										foreach($classes as $class)
										{
											$classname .= mj_smgt_get_class_name($class['class_id']).",";
										}
										$classname = trim($classname,",");
										?>
										<div class="col-md-4">
											<p class="user-info">: <?php echo $classname;?></p> 
										</div>
										
										<div class="col-md-2">
											<p class="user-lable"><?php esc_attr_e( 'Subject Name', 'school-mgt' );?></p>
										</div>
										<?php
										$subjectname=mj_smgt_get_subject_name_by_teacher($teacher_data->ID); 
										?>
										<div class="col-md-4">
											<p class="user-info">: <?php echo rtrim($subjectname,", ");?></p> 
										</div>
									</div>						
								</div>
								
								<div class="card-head">
									<i class="fa fa-map-marker"></i>
									<span> <b><?php esc_attr_e( 'Contact Information', 'school-mgt' ) ;?> </b></span>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-md-2">
											<p class="user-lable"><?php esc_attr_e( 'Address', 'school-mgt' ) ;?></p>
										</div>
										<div class="col-md-4">
											<p class="user-info">: <?php echo $teacher_data->address;?><br></p>
										</div>
										<div class="col-md-2">
											<p class="user-lable"><?php esc_attr_e( 'City', 'school-mgt' ) ;?></p>
										</div>
										<div class="col-md-4">
											<p class="user-info">: <?php echo $teacher_data->city;?></p>
										</div>
										<div class="col-md-2">
											<p class="user-lable"><?php esc_attr_e( 'State', 'school-mgt' ) ;?></p>
										</div>
										<div class="col-md-4">
											<p class="user-info">: <?php echo $teacher_data->state;?></p>
										</div>
										<div class="col-md-2">
											<p class="user-lable"><?php esc_attr_e( 'Zipcode', 'school-mgt' ) ;?></p>
										</div>
										<div class="col-md-4">
											<p class="user-info">: <?php echo $teacher_data->zip_code;?></p>
										</div>
										<div class="col-md-2">
											<p class="user-lable"><?php esc_attr_e( 'Phone Number', 'school-mgt' ) ;?></p>
										</div>
										<div class="col-md-4">
											<p class="user-info">: <?php echo $teacher_data->phone;?></p>
										</div>
									</div>											
								</div>
							</div>
						</div>
					</div>
			</div>
			   
			<div class="panel-body">
				<div class="row">	
					<ul class="nav nav-tabs">
						<li class="nav-item active"><a data-toggle="tab" href="#Section1" class="nav-link active"><i class="fa fa-calendar "></i><b><?php esc_attr_e( ' Time Table', 'school-mgt' ); ?></b></a></li>
					</ul>
					<div class="tab-content class_schedule_tab_content">
						<div id="Section1" class="tab-pane active">
							<div class="row">
								<div class="col-lg-12">
									<div class="card class_schedule_tab_content_card">
										<div class="card-content">
											<table class="table table-bordered">
												<?php 
												foreach(mj_smgt_sgmt_day_list() as $daykey => $dayname)
												{	?>
												<tr>
											   <th width="100"><?php echo $dayname;?></th>
												<td>
													 <?php
														$period = $obj_route->mj_smgt_get_periad_by_teacher($teacher_data->ID,$daykey);
														 
														if(!empty($period))
															foreach($period as $period_data)
															{
																echo '<div class="btn-group m-b-sm">';
																echo '<button class="btn btn-primary" aria-expanded="false"><span class="period_box" id='.$period_data->route_id.'>'.mj_smgt_get_single_subject_name($period_data->subject_id);
																
																$start_time_data = explode(":", $period_data->start_time);
																$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
																$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
																$start_am_pm=$start_time_data[2];
																
																$end_time_data = explode(":", $period_data->end_time);
																$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
																$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
																$end_am_pm=$end_time_data[2];
																echo '<span class="time"> ('.$start_hour.':'.$start_min.' '.$start_am_pm.' - '.$end_hour.':'.$end_min.' '.$end_am_pm.') </span>';
																
																echo '<span>'.mj_smgt_get_class_name($period_data->class_id).'</span>';
																echo '</span></span></button>';
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
							</div>
						</div>
					<div id="Section2" class="tab-pane fade">
								 
					</div>
					 
					</div>
				</div>
			</div>

	<?php
	}
}
?> 