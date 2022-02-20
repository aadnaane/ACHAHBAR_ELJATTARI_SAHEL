<?php
?>
<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
	
jQuery('#students_list').DataTable({
			responsive: true,
			"order": [[ 1, "asc" ]],
			"aoColumns":[
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
					  {"bSortable": true},
			           {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false} ],
			language:<?php echo mj_smgt_datatable_multi_language();?>	
		});

$('#student_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	 
			$('#birth_date').datepicker({
				maxDate : 0,
				dateFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				yearRange:'-65:+25',
				onChangeMonthYear: function(year, month, inst) {
					$(this).val(month + "/" + year);
				}
			});

	$('.space_validation').on('keypress',function( e ) 
						{
						   if(e.which === 32) 
							 return false;
						});									
						//custom field datepicker
						$('.after_or_equal').datepicker({
							dateFormat: "yy-mm-dd",										
							minDate:0,
							beforeShow: function (textbox, instance) 
							{
								instance.dpDiv.css({
									marginTop: (-textbox.offsetHeight) + 'px'                   
								});
							}
						}); 
						$('.date_equals').datepicker({
							dateFormat: "yy-mm-dd",
							minDate:0,
							maxDate:0,										
							beforeShow: function (textbox, instance) 
							{
								instance.dpDiv.css({
									marginTop: (-textbox.offsetHeight) + 'px'                   
								});
							}
						}); 
						$('.before_or_equal').datepicker({
							dateFormat: "yy-mm-dd",
							maxDate:0,
							beforeShow: function (textbox, instance) 
							{
								instance.dpDiv.css({
									marginTop: (-textbox.offsetHeight) + 'px'                   
								});
							}
						}); 

	var table =  jQuery('#exam_list').DataTable({
		responsive: true,
		"aoColumns":[	                  
			{"bSortable": true},
			{"bSortable": false}],
		language:<?php echo mj_smgt_datatable_multi_language();?>
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

		var table =  jQuery('#parents_list').DataTable({
					responsive: true,
					"order": [[ 0, "asc" ]],
					"aoColumns":[	                  
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true}],	
					language:<?php echo mj_smgt_datatable_multi_language();?>						
				});

					$('.sdate').datepicker({dateFormat: "yy-mm-dd"}); 
				$('.edate').datepicker({dateFormat: "yy-mm-dd"}); 

	var table =  jQuery('#attendance_list').DataTable({
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
						{"bSortable": true},	           
						{"bSortable": false}],
						language:<?php echo mj_smgt_datatable_multi_language();?>							
					});			

	
});

////

//Custom Field File Validation//
function Smgt_custom_filed_fileCheck(obj)
{	
   "use strict";
	var fileExtension = $(obj).attr('file_types');
	var fileExtensionArr = fileExtension.split(',');
	var file_size = $(obj).attr('file_size');
	
	var sizeInkb = obj.files[0].size/1024;
	
	if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtensionArr) == -1)
	{										
		alert("Only "+fileExtension+" formats are allowed.");
		$(obj).val('');
	}	
	else if(sizeInkb > file_size)
	{										
		alert("Only "+file_size+" kb size is allowed.");
		$(obj).val('');	
	}
}

	function fileCheck(obj) {
				var fileExtension = ['jpeg', 'jpg', 'png', 'bmp',''];
				if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
				{
					alert(language_translate2.image_forame_alert);
					$(obj).val('');
				}	
	}
</script>
<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$custom_field_obj =new Smgt_custome_field;
$obj_mark = new Marks_Manage();
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'studentlist';
$role='student';
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
//--------------- SAVE STUDENT -------------------//
if(isset($_POST['save_student']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_student_frontend_nonce' ) )
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
			$userdata['user_pass']=strip_tags($_POST['password']);
		
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
		
		$usermetadata=array('roll_id'=>mj_smgt_strip_tags_and_stripslashes($_POST['roll_id']),
				'middle_name'=>mj_smgt_strip_tags_and_stripslashes($_POST['middle_name']),
				'gender'=>$_POST['gender'],
				'birth_date'=>$_POST['birth_date'],
				'address'=>mj_smgt_strip_tags_and_stripslashes($_POST['address']),
				'city'=>mj_smgt_strip_tags_and_stripslashes($_POST['city_name']),
				'state'=>mj_smgt_strip_tags_and_stripslashes($_POST['state_name']),
				'zip_code'=>mj_smgt_strip_tags_and_stripslashes($_POST['zip_code']),
				'class_name'=>$_POST['class_name'],
				'class_section'=>$_POST['class_section'],
				'phone'=>$_POST['phone'],
				'mobile_number'=>$_POST['mobile_number'],
				'alternet_mobile_number'=>$_POST['alternet_mobile_number'],
				'smgt_user_avatar'=>$photo,
				'created_by'=>get_current_user_id()

		);
		 
		$userbyroll_no=get_users(
				array('meta_query'=>
						array('relation' => 'AND',
							array('key'=>'class_name','value'=>$_POST['class_name']),
							array('key'=>'roll_id','value'=>mj_smgt_strip_tags_and_stripslashes($_POST['roll_id']))
						),
						'role'=>'student'));
		$is_rollno = count($userbyroll_no);
		if($_REQUEST['action']=='edit')
		{
			$userdata['ID']=$_REQUEST['student_id'];
			$result=mj_smgt_update_user($userdata,$usermetadata,$firstname,$lastname,$role);
			// Custom Field File Update //
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
							$module='student';					
							$updated_at=date("Y-m-d H:i:s");
							$update_custom_meta_data =$wpdb->query($wpdb->prepare("UPDATE `$wpnc_custom_field_metas` SET `field_value` = '$custom_field_file_value',updated_at='$updated_at' WHERE `$wpnc_custom_field_metas`.`module` = %s AND  `$wpnc_custom_field_metas`.`module_record_id` = %d AND `$wpnc_custom_field_metas`.`custom_fields_id` = %d",$module,$result,$key));
						} 	
					}		 		
				}
			$update_custom_field=$custom_field_obj->mj_smgt_update_custom_field_metas('student',$_POST['custom'],$result);
			if($result)
			{ 
				wp_redirect ( home_url() . '?dashboard=user&page=student&&message=2'); 	
			}
		}
		else
		{
			if( !email_exists( $_POST['email'] ) && !username_exists( mj_smgt_strip_tags_and_stripslashes($_POST['username']))) 
			{			
				if($is_rollno)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=student&&message=3'); 	
				}
				else
				{
					$result=mj_smgt_add_newuser($userdata,$usermetadata,$firstname,$lastname,$role);
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
								$custom_meta_data['module_record_id']=$result;
								$custom_meta_data['custom_fields_id']=$key;
								$custom_meta_data['field_value']=$custom_field_file_value;
								$custom_meta_data['created_at']=date("Y-m-d H:i:s");
								$custom_meta_data['updated_at']=date("Y-m-d H:i:s");	
								 
								$insert_custom_meta_data=$wpdb->insert($wpnc_custom_field_metas, $custom_meta_data );
								 
							} 	
						}		 		
					}
					$add_custom_field=$custom_field_obj->mj_smgt_add_custom_field_metas('student',$_POST['custom'],$result);					
					if($result)
					{ 
						wp_redirect ( home_url() . '?dashboard=user&page=student&&message=1'); 	
					}
				}
			}
			else
			{
				wp_redirect ( home_url() . '?dashboard=user&page=student&&message=4'); 	
			}	 
		}
	}
}
// -----------Delete Student -------- //
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{		
	$childs=get_user_meta($_REQUEST['student_id'], 'parent_id', true);
	if(!empty($childs))
	{
		foreach($childs as $key=>$childvalue)
		{					
			$parents=get_user_meta($childvalue, 'child',true);					
			if(!empty($parents))
			{
				if(($key = array_search($_REQUEST['student_id'], $parents)) !== false) 
				{
					unset($parents[$key]);						
					update_user_meta( $childvalue,'child', $parents );							
				}					
			}				
		}
	}
		
	$result=mj_smgt_delete_usedata($_REQUEST['student_id']);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=student&tab=studentlist&message=5');
	}
}
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Student Added Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Student Updated Successfully.','school-mgt');
			break;
		case '3':
			$message_string = esc_attr__('Roll No Already Exist.','school-mgt');
			break;
		case '4':
			$message_string = esc_attr__('Student Username Or Emailid Already Exist.','school-mgt');
			break;
		case '5':
			$message_string = esc_attr__('Student Deleted Successfully.','school-mgt');
			break;
		case '6':
			$message_string = esc_attr__('Student CSV Successfully Uploaded.','school-mgt');
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
?>
<!-- POP up code -->	
<div class="popup-bg">
    <div class="overlay-content">   
		<div class="result"></div>
		<div class="view-parent"></div>   
    </div> 
</div>
<div class="p-4 panel-body panel-white">
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="nav-item">
			<a href="?dashboard=user&page=student&tab=studentlist" class="p-2 px-3 nav-link nav-tab2  <?php if($active_tab=='studentlist'){?>active<?php }?>">
				<i class="fa fa-align-justify"></i> <?php  if($school_obj->role == 'parent') { esc_attr_e('Child List', 'school-mgt');}else {  esc_attr_e('Student List', 'school-mgt'); } ?></a>
			</a>
		</li>
      
		<li class="nav-item ">
			<?php if($user_access['add']=='1')
			{	?>
				<a href="?dashboard=user&page=student&tab=addstudent" class="p-2 px-3 nav-link nav-tab2 margin_bottom <?php if($active_tab=='addstudent'){?>active<?php }?>">
					<i class="fa fa-plus-circle"></i> 
					<?php 
					if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					esc_attr_e('Edit Student', 'school-mgt');
					else 
					esc_attr_e('Add Student', 'school-mgt');
				  ?> 
				</a>
			<?php
			}
			?>
		</li>
      <?php 
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_student')
		{?>
			<li class="nav-item">
				<a href="?dashboard=user&page=student&tab=view_student&action=view_student&student_id=<?php echo $_REQUEST['student_id'];?>" class="p-2 px-3 nav-link nav-tab2 <?php if($active_tab=='view_student'){?>active<?php }?>">
					<i class="fa fa-eye"></i> <?php if($school_obj->role == 'parent') { esc_attr_e('View Child', 'school-mgt');}else {  esc_attr_e('View Student', 'school-mgt'); } ?> 
				</a>
			</li>
	  <?php
		}
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_receipt')
		{?>
		<li class="nav-item">
			<a href="?dashboard=user&page=student&tab=view_exam_receipt&action=view_receipt&student_id=<?php echo $_REQUEST['student_id'];?>" class="p-2 px-3 nav-link nav-tab2 <?php if($active_tab=='view_exam_receipt'){?>active<?php }?>">
				<i class="fa fa-align-justify"></i> <?php esc_attr_e('Exam Receipt List', 'school-mgt'); ?></a>
			</a>
		</li>
	  <?php
		}
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_attendance_student')
		{
	   ?>
	   <li class="nav-item">
          <a href="?dashboard=user&page=student&tab=stud_attendance&action=view_attendance_student&student_id=<?php echo $student_id;?>" class="p-2 px-3 nav-link nav-tab2  <?php if($active_tab=='stud_attendance'){?>active<?php }?>">
             <i class="fa fa-align-justify"></i> <?php esc_attr_e('Attendance', 'school-mgt'); ?></a>
          </a>
      </li>
	  <?php
		}
		?>
	</ul>

	<div class="tab-content">
		<?php if($active_tab == 'studentlist')
		{
		?>
			<div class="panel-body"> 
				<?php
				if($school_obj->role != 'student' && $school_obj->role != 'parent')
				{ ?>
					<form method="post">  
						<div class="row">
							<div class="form-group col-md-3">
								<label for="class_id"><?php esc_attr_e('Select Class','school-mgt');?></label>
								<?php $class_id="";
								if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>
								<select name="class_id"  id="class_list"  class="form-control ">
									<option value=""><?php esc_attr_e('Select class Name','school-mgt');?></option>
									<?php
									foreach(mj_smgt_get_allclass() as $classdata)
									{
									?>
										<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
								   <?php }?>
								</select>
							</div>
							<div class="form-group col-md-3">
								<label for="class_id"><?php esc_attr_e('Select Class Section','school-mgt');?></label>
								<?php
								$class_section="";
								if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
										<select name="class_section" class="form-control validate[required]" id="class_section">
											<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
											<?php if(isset($_REQUEST['class_section'])){
													$class_section=$_REQUEST['class_section'];
													foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
													{  ?>
													 <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
												<?php }
												}?>
										</select>
							</div>
							<div class="form-group col-md-3 button-possition">
								<label for="subject_id">&nbsp;</label>
								<input type="submit" value="<?php esc_attr_e('Go','school-mgt');?>" name="filter_class"  class="btn btn-info"/>
							</div>
						</div>     
					</form> 
				<?php 
				} ?>
			</div>
			<?php 
			if(isset($_REQUEST['filter_class']) )
			{
				$exlude_id = mj_smgt_approve_student_list();
				if(empty($_REQUEST['class_id']) && empty($_REQUEST['class_section']))
				{
					$exlude_id = mj_smgt_approve_student_list();
					$studentdata =get_users(array('role'=>'student'));
					
				}
				elseif(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != "")
				{
					$class_id =$_REQUEST['class_id'];
					$class_section =$_REQUEST['class_section'];
					 $studentdata = get_users(array('meta_key' => 'class_section', 'meta_value' =>$class_section,
						'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
				}
				elseif(isset($_REQUEST['class_id']) && $_REQUEST['class_section'] == "")
				{
					$class_id =$_REQUEST['class_id'];
					$studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));	
				}	
			}	
			else 
			{
				//------- STUDENT DATA FOR STUDENT ---------//
				if($school_obj->role == 'student')
				{
					$own_data=$user_access['own_data'];
					if($own_data == '1')
					{ 
						$user_id=get_current_user_id();	
						$studentdata[] =get_userdata($user_id);
					}
					else
					{
						$studentdata	=	mj_smgt_get_usersdata('student');
					}
				}
				//------- STUDENT DATA FOR TEACHER ---------//
				elseif($school_obj->role == 'teacher')
				{
					$own_data=$user_access['own_data'];
					if($own_data == '1')
					{ 
						$user_id=get_current_user_id();		
						
						$class_id=get_user_meta($user_id,'class_name',true);
					
						$studentdata=$school_obj->mj_smgt_get_teacher_student_list($class_id);
					}
					else
					{
						$studentdata	=	mj_smgt_get_usersdata('student');
					}
				}
				//------- STUDENT DATA FOR PARENT ---------//
				elseif($school_obj->role == 'parent')
				{
					$own_data=$user_access['own_data'];
					if($own_data == '1')
					{ 
						$child_data = $school_obj->child_list;
					}
					else
					{
						$studentdata	=	mj_smgt_get_usersdata('student');
					}
				}
				else
				{
					$own_data=$user_access['own_data'];
					$user_id=get_current_user_id();		
					if($own_data == '1')
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
					}
					else
					{
						$studentdata	=	mj_smgt_get_usersdata('student');
					}
				}
			}
			?>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="students_list" class="display dataTable student_datatable" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th><?php echo esc_attr_e( 'Photo', 'school-mgt' ) ;?></th>
								<?php if($school_obj->role == 'parent')
								{ ?>
									<th><?php echo esc_attr_e( 'Child Name', 'school-mgt' ) ;?></th>
								<?php 
								} 
								else
								{ ?>
									<th><?php echo esc_attr_e( 'Student Name', 'school-mgt' ) ;?></th>
									<?php
								} ?>
								<th> <?php echo esc_attr_e( 'Roll No.', 'school-mgt' ) ;?></th>
								<th> <?php echo esc_attr_e( 'Class', 'school-mgt' ) ;?></th>
				                 <th> <?php echo esc_attr_e( 'Section', 'school-mgt' ) ;?></th>
								<th> <?php echo esc_attr_e( 'Email', 'school-mgt' ) ;?></th>
								<th><?php echo esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
							</tr>
						</thead>
 
						<tfoot>
							<tr>
								<th><?php echo esc_attr_e( 'Photo', 'school-mgt' ); ?></th>
								<?php if($school_obj->role == 'parent')
								{ ?>
									<th><?php echo esc_attr_e( 'Child Name', 'school-mgt' ) ;?></th>
								<?php 
								} 
								else
								{ ?>
									<th><?php echo esc_attr_e( 'Student Name', 'school-mgt' ) ;?></th>
									<?php
								} ?>
								<th> <?php echo esc_attr_e( 'Roll No.', 'school-mgt' ) ;?></th>
								<th> <?php echo esc_attr_e( 'Class', 'school-mgt' ) ;?></th>
				                 <th> <?php echo esc_attr_e( 'Section', 'school-mgt' ) ;?></th>
								<th> <?php echo esc_attr_e( 'Email', 'school-mgt' ) ;?></th>
								<th><?php echo esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
							</tr>
						</tfoot>
				 
						<tbody>
						<?php 
						if(!empty($studentdata))
						{
							foreach ($studentdata as $retrieved_data)
							{
								?>
								<tr>
									<td class="text-center user_image">
									<?php 
									$uid=$retrieved_data->ID;
									$umetadata=mj_smgt_get_user_image($uid);
									if(empty($umetadata))
									{
										echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle rounded-circle " />';
									}
									else
										echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle rounded-circle "/>';
									?>
									</td>
									<td class="name"><?php echo $retrieved_data->display_name;?></td>
									<td class="roll_no"><?php echo get_user_meta($retrieved_data->ID, 'roll_id',true);?></td>
									<td class="name"><?php $class_id=get_user_meta($retrieved_data->ID, 'class_name',true);
										echo $classname=mj_smgt_get_class_name($class_id);
									?></td>
									<td class="name">
									<?php 
										$section_name=get_user_meta($retrieved_data->ID, 'class_section',true);
										if($section_name!=""){
											echo mj_smgt_get_section_name($section_name); 
										}
										else
										{
											esc_attr_e('No Section','school-mgt');;
										}
									?>
				                   </td>
									<td class="email"><?php echo $retrieved_data->user_email;?></td>
									<td class="action">
										<a href="?dashboard=user&page=student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-success"><?php esc_attr_e('View','school-mgt');?></a>
										<?php
										if($school_obj->role == 'student')
										{
											if ($uid == get_current_user_id())
											{
											?>
												<a href="?dashboard=user&page=student&action=result&student_id=<?php echo $retrieved_data->ID;?>" class="show-popup btn btn-default" idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-bar-chart"></i> <?php esc_attr_e('View Result', 'school-mgt');?></a> 
												<a href="?dashboard=user&page=student&tab=stud_attendance&action=view_attendance_student&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-default" idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php esc_attr_e('View Attendance','school-mgt');?> </a>
											<?php
											}
										}
										?>
										<?php if($school_obj->role != 'student')
								        { ?>
										<?php
										if($user_access['edit']=='1')
										{
										?>
										<a href="?dashboard=user&page=student&tab=addstudent&action=edit&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"><?php esc_attr_e('Edit', 'school-mgt');?></a>
										<?php 
										}  
										if($user_access['delete']=='1')
										{
										?>
										<a href="?dashboard=user&page=student&tab=studentlist&action=delete&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><?php esc_attr_e('Delete','school-mgt');?></a> 
										<?php
										}
										$result=mj_smgt_student_exam_receipt_check($retrieved_data->ID);
										if($result)
										{
										?>
											<a href=		"?dashboard=user&page=student&tab=view_exam_receipt&action=view_receipt&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-primary"><?php esc_attr_e('Hall Ticket','school-mgt');?></a>  
										<?php	
										}
										?>
										<a href="?dashboard=user&page=student&action=result&student_id=<?php echo $retrieved_data->ID;?>" class="show-popup btn btn-default" idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-bar-chart"></i> <?php esc_attr_e('View Result', 'school-mgt');?></a> 

										<a href="?dashboard=user&page=student&tab=stud_attendance&action=view_attendance_student&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-default" idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php esc_attr_e('View Attendance','school-mgt');?> </a>
										<?php } ?>
									</td>			  
								</tr>	
							<?php 
							} 
						}
						if(!empty($child_data))
						{
							foreach ($school_obj->child_list as $child_id)
							{ 
								$retrieved_data= get_userdata($child_id);
								if($retrieved_data)
								{ ?>
									<tr>
										<td class="text-center user_image"><?php $uid=$retrieved_data->ID;
													$umetadata=mj_smgt_get_user_image($uid);
													if(empty($umetadata['meta_value']))
														echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle rounded-circle " />';
													else
													echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle rounded-circle "/>';
										?>
										</td>
										<td class="name"><?php echo $retrieved_data->display_name;?></td>
										<td><?php echo get_user_meta($retrieved_data->ID, 'roll_id',true);?></td>
										<td class="name"><?php $class_id=get_user_meta($retrieved_data->ID, 'class_name',true);
										echo $classname=mj_smgt_get_class_name($class_id);
									?></td>
									<td class="name">
									<?php 
										$section_name=get_user_meta($retrieved_data->ID, 'class_section',true);
										if($section_name!=""){
											echo mj_smgt_get_section_name($section_name); 
										}
										else
										{
											esc_attr_e('No Section','school-mgt');;
										}
									?>
				                   </td>
										<td class="email"><?php echo $retrieved_data->user_email;?></td>
										
										<td class="action"> 
											<a href="?dashboard=user&page=student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-success"><?php esc_attr_e('View','school-mgt');?></a>
											<?php
											if($user_access['edit']=='1')
											{
											?>
											<a href="?dashboard=user&page=student&tab=addstudent&action=edit&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"><?php esc_attr_e('Edit', 'school-mgt');?></a>
											<?php 
											}  
											if($user_access['delete']=='1')
											{
											?>
											<a href="?dashboard=user&page=student&tab=studentlist&action=delete&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
											onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"
											><?php esc_attr_e('Delete','school-mgt');?></a> 
											<?php
											}?>
											<a href="?dashboard=user&page=student&action=result&student_id=<?php echo $retrieved_data->ID;?>" class="show-popup btn btn-default"  
											idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-bar-chart"></i> <?php esc_attr_e('View Result','school-mgt');?></a> 
											<a href="?dashboard=user&page=student&tab=stud_attendance&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-default"  
											idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php esc_attr_e('View Attendance','school-mgt');?> </a>
										</td>
									   
									</tr>
								<?php 
								}
							}
						}							
						?>
						</tbody>        
					</table>
				</div>       
			</div>
	</div>
</div>
 <?php 
	}
	if($active_tab == 'addstudent')
	{
		$role='student';
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$user_info = get_userdata($_REQUEST['student_id']);
		}
		?>
		<div class="panel-body">
			<form name="student_form" action="" method="post" class="mt-3 form-horizontal" id="student_form" enctype="multipart/form-data">
			 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="role" value="<?php echo $role;?>"  />
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<?php if($edit){ $classval=$user_info->class_name; }elseif(isset($_POST['class_name'])){$classval=$_POST['class_name'];}else{$classval='';}?>
						<select name="class_name" class="form-control validate[required]" id="class_list">
							<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
							<?php
							foreach(mj_smgt_get_allclass() as $classdata)
							{  
							?>
								<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
							<?php }?>
						</select>
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
				<div class="col-sm-8">
					<?php if($edit){ $sectionval=$user_info->class_section; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
							<select name="class_section" class="form-control" id="class_section">
								<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
								<?php
								if($edit){
									foreach(mj_smgt_get_class_sections($user_info->class_name) as $sectiondata)
									{  ?>
									 <option value="<?php echo $sectiondata->section_name;?>" <?php selected($sectionval,$sectiondata->section_name);  ?>><?php echo $sectiondata->section_name;?></option>
								<?php } 
								} ?>
							</select>
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="roll_id"><?php esc_attr_e('Roll Number','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="roll_id" class="form-control validate[required,custom[username_validation]]" maxlength="50" type="text" 
					value="<?php if($edit){ echo $user_info->roll_id;}elseif(isset($_POST['roll_id'])) echo $_POST['roll_id'];?>"  name="roll_id">
				</div>
			</div>
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
				<label class="col-sm-2 control-label col-form-label text-md-end" for="mobile_number"><?php esc_attr_e('Mobile Number','school-mgt');?></label>
				<div class="col-sm-1">
				
				<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="phonecode">
				</div>
				<div class="col-sm-7">
					<input id="mobile_number" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="mobile_number"
					value="<?php if($edit){ echo $user_info->mobile_number;}elseif(isset($_POST['mobile_number'])) echo $_POST['mobile_number'];?>">
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="mobile_number"><?php esc_attr_e('Alternate Mobile Number','school-mgt');?></label>
				<div class="col-sm-1">
				
				<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="alter_mobile_number">
				</div>
				<div class="col-sm-7">
					<input id="alternet_mobile_number" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="alternet_mobile_number" maxlength="10"
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
			<?php wp_nonce_field( 'save_student_frontend_nonce' ); ?>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end " for="email"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
					value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
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
					<input id="password" class="form-control <?php if(!$edit){ echo 'validate[required,minSize[8],maxSize[12]]'; }else{ echo 'validate[minSize[8],maxSize[12]]'; }?>" type="password"  name="password" value="">
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
					<hr>
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
									$numeric="onlyNumberSp";
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
								 
								<div class="mb-3 form-group row">	
									<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="<?php echo $custom_field->id; ?>"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
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
								<div class="mb-3 form-group row">	
									<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end "><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
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
								<div class="mb-3 form-group row">
									 <label for="bdate" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
								 
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 has-feedback">
										<input class="form-control error custom_datepicker <?php echo $datepicker_class; ?> hideattar<?php echo $custom_field->form_name; ?> <?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>"name="custom[<?php echo $custom_field->id; ?>]"<?php if($edit){ ?> value="<?php echo $custom_field_value; ?>" <?php } ?>id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>">
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-0">
									</div>
								</div>
									
								<?php 
							}
							elseif($custom_field->field_type =='dropdown')
							{
								?>	
								<div class="mb-3 form-group row">
									<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="<?php echo $custom_field->id; ?>"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
									<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 has-feedback">
										<select class="form-control width_100 hideattar<?php echo $custom_field->form_name; ?> 
										<?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>" name="custom[<?php echo $custom_field->id; ?>]"	id="<?php echo $custom_field->id; ?>" label="<?php echo $custom_field->field_label; ?>"
										>
										<option value=""><?php esc_attr_e( 'Select', 'school-mgt' ); ?></option>
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
									<div class="mb-3 form-group row">
										<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
									 
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
														<input type="checkbox" value="<?php echo $options->option_label; ?>"  <?php if($edit){  echo checked(in_array($options->option_label,$custom_field_value_array)); } ?> class="custom-control-input hideattar<?php echo $custom_field->form_name; ?> <?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?>" name="custom[<?php echo $custom_field->id; ?>][]" >
														<label class="custom-control-label col-form-label text-md-end mt-7-px" for="colorCheck1"><?php echo $options->option_label; ?></label>
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
								
								<div class="mb-3 form-group row">
										<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
										
										 
									 
										<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 has-feedback">
											<?php
											if(!empty($option))
											{
												foreach ($option as $options)
												{
													?>
													<input type="radio"  value="<?php echo $options->option_label; ?>" <?php if($edit){ echo checked( $options->option_label, $custom_field_value); } ?> name="custom[<?php echo $custom_field->id; ?>]"  class="custom-control-input hideattar<?php echo $custom_field->form_name; ?> <?php if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } ?> error " id="<?php echo $options->option_label; ?>">
													<label class="mr-1 custom-control-label col-form-label text-md-end mt-7-px" for="<?php echo $options->option_label; ?>"><?php echo $options->option_label; ?></label>
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
								<div class="mb-3 form-group row">
									<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end"><?php echo $custom_field->field_label; ?><span class="required red"><?php echo $red; ?></span></label>
									 
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 has-feedback">
										<input type="file"  onchange="mj_smgt_custom_filed_fileCheck(this);" Class="hideattar<?php echo $custom_field->form_name; if($edit){ if(!empty($required)){ if($custom_field_value==''){ ?> validate[<?php echo $required; ?>] <?php } } }else{ if(!empty($required)){ ?> validate[<?php echo $required; ?>] <?php } } ?>" name="custom_file[<?php echo $custom_field->id;?>]" <?php if($edit){ ?> value="<?php echo $custom_field_value; ?>" <?php } ?> id="<?php echo $custom_field->id; ?>" file_types="<?php echo $file_types; ?>" file_size="<?php echo $file_size; ?>">
										<p><?php esc_html_e('Please upload only ','wpnc'); echo $file_types; esc_html_e(' file','wpnc');?> </p>
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
			<div class="offset-sm-2 col-sm-8">        	
				<input type="submit" value="<?php if($edit){ esc_attr_e('Save Student','school-mgt'); }else{ esc_attr_e('Add Student','school-mgt');}?>" name="save_student" class="btn btn-success"/>
			</div> 

			</form>
		</div>
		<?php 
	}	 
	if($active_tab == 'view_exam_receipt')
	{
		$student_id=$_REQUEST['student_id'];
		$exam_data=mj_smgt_student_exam_receipt_check($student_id);
?>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<form id="frm-example" name="frm-example" method="post">
						<div class="table-responsive">
							<table id="exam_list" class="display admin_student_datatable" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th><?php echo esc_attr_e( 'Exam Name', 'school-mgt' ) ;?></th>
										<th><?php echo esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
									</tr>
								</thead>
					 
								<tfoot>
									<tr>
										<th> <?php echo esc_attr_e( 'Exam Name', 'school-mgt' ) ;?></th>
										<th><?php echo esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
									</tr>
								</tfoot>
					 
								<tbody>
									<?php
										if(!empty($exam_data))
										{
											foreach($exam_data as $retrived_data)
											{
											?>
												<tr>
													<td> <?php echo mj_smgt_get_exam_name_id($retrived_data->exam_id) ;?></td>
													<td class="action">
														<a  href="?page=smgt_student&student_exam_receipt=student_exam_receipt&student_id=<?php echo $student_id;?>&exam_id=<?php echo $retrived_data->exam_id;?>" target="_blank"class="btn btn-success"><?php esc_attr_e('Print','school-mgt');?></a>
														<a  href="?page=smgt_student&student_exam_receipt_pdf=student_exam_receipt_pdf&student_id=<?php echo $student_id;?>&exam_id=<?php echo $retrived_data->exam_id;?>" target="_blank"class="btn btn-success"><?php esc_attr_e('PDF','school-mgt');?></a>
													</td>
												</tr>
											<?php
											}
										}
									?>
								</tbody>        
							</table>
						</div>
					</form>
				</div>
			</div>
		</div>
<?php 
	}
	if($active_tab == 'view_student')
	{	
	?>
	
		<?php
		 $student_data=get_userdata($_REQUEST['student_id']);
		 $user_meta =get_user_meta($_REQUEST['student_id'], 'parent_id', true); 
		 $custom_field_obj = new Smgt_custome_field;								
		 $module='student';	
		 $user_custom_field=$custom_field_obj->mj_smgt_getCustomFieldByModule($module);
		?>
	<div class="panel-body">	
		<div class="box-body">
			<div class="row">
				<div class="col-md-3 col-sm-4 col-xs-12">	
					<?php
					$umetadata=mj_smgt_get_user_image($student_data->ID);
					if(empty($umetadata))
					{
						echo '<img class="img-circle rounded-circle img-responsive member-profile user_height_width" src='.get_option( 'smgt_student_thumb' ).'>';
					}
					else
					{
						echo '<img class="img-circle rounded-circle img-responsive member-profile user_height_width" src='.$umetadata.'>';
					}
					?>
				</div>
				
				<div class="col-md-9 col-sm-8 col-xs-12 ">
					<div class="row">
						<h2><?php echo $student_data->display_name;?></h2>
					</div>
					<div class="row">
						<div class="col-md-4 col-sm-3 col-xs-12">
							<i class="fa fa-envelope"></i>&nbsp;
							<span class="email-span"><?php echo $student_data->user_email;?></span>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12">
							<i class="fa fa-phone"></i>&nbsp;
							<span><?php echo $student_data->phone;?></span>
						</div>
						<div class="col-md-5 col-sm-3 col-xs-12 no-padding">
							<i class="fa fa-map-marker"></i>&nbsp;
							<span><?php echo $student_data->address;?></span>
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
									<p class="user-info">: <?php echo $student_data->display_name;?></p>
								</div>
								
								<div class="col-md-2">
									<p class="user-lable"><?php esc_attr_e( 'Birth Date', 'school-mgt' ) ;?></p>
								</div>
								<div class="col-md-4">
									<p class="user-info">: <?php echo mj_smgt_getdate_in_input_box($student_data->birth_date);?></p>
								</div>
								<div class="col-md-2">
										<p class="user-lable"><?php esc_attr_e( 'Gender', 'school-mgt' ) ;?></p>
									</div>
								<div class="col-md-4">
										<!--<p class="user-info">: <?php echo $student_data->gender;?></p>-->
										<p class="user-info">: <?php 
													if($student_data->gender=='male') 
														echo esc_attr__('Male','school-mgt');
													elseif($student_data->gender=='female') 
														echo esc_attr__('Female','school-mgt');
													?></p>
								</div>
															
								<div class="col-md-2">
									<p class="user-lable"><?php esc_attr_e( 'Roll No', 'school-mgt' );?></p>
								</div>
								<div class="col-md-4">
									<p class="user-info">: <?php echo $student_data->roll_id;?></p> 
								</div>
								
								<div class="col-md-2">
									<p class="user-lable"><?php esc_attr_e( 'Class Name', 'school-mgt' );?></p>
								</div>
								<div class="col-md-4">
									<p class="user-info">: <?php echo mj_smgt_get_class_name($student_data->class_name);?></p> 
								</div>
								
								<div class="col-md-2">
									<p class="user-lable"><?php esc_attr_e( 'Section Name', 'school-mgt' );?></p>
								</div>
								<div class="col-md-4">
									<p class="user-info">: <?php 
										if(($student_data->class_section)!="")
										{
											echo mj_smgt_get_section_name($student_data->class_section); 
										}
										else
										{
											esc_attr_e('No Section','school-mgt');;
										}?>
									</p> 
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
									<p class="user-info">: <?php echo $student_data->address;?><br></p>
								</div>
								<div class="col-md-2">
									<p class="user-lable"><?php esc_attr_e( 'City', 'school-mgt' ) ;?></p>
								</div>
								<div class="col-md-4">
									<p class="user-info">: <?php echo $student_data->city;?></p>
								</div>
								<div class="col-md-2">
									<p class="user-lable"><?php esc_attr_e( 'State', 'school-mgt' ) ;?></p>
								</div>
								<div class="col-md-4">
									<p class="user-info">: <?php echo $student_data->state;?></p>
								</div>
								<div class="col-md-2">
									<p class="user-lable"><?php esc_attr_e( 'Zipcode', 'school-mgt' ) ;?></p>
								</div>
								<div class="col-md-4">
									<p class="user-info">: <?php echo $student_data->zip_code;?></p>
								</div>
								<div class="col-md-2">
									<p class="user-lable"><?php esc_attr_e( 'Phone Number', 'school-mgt' ) ;?></p>
								</div>
								<div class="col-md-4">
									<p class="user-info">: <?php echo $student_data->phone;?></p>
								</div>
							</div>											
						</div>
						<?php
						if(!empty($user_custom_field))
						{	?>
						<div class="card-head">
							<i class="fa fa-bars"></i>
							<span> <b><?php esc_attr_e( 'Other Information', 'school-mgt' ) ;?> </b></span>
						</div>
						<div class="card-body">
							<div class="row">
								 <?php
										foreach($user_custom_field as $custom_field)
										{
											$custom_field_id=$custom_field->id;
										 
											$module_record_id=$_REQUEST['student_id'];
											 
											$custom_field_value=$custom_field_obj->mj_smgt_get_single_custom_field_meta_value($module,$module_record_id,$custom_field_id);
											?>
											<div class="col-xl-2 col-lg-2">
											<p class="user-lable"><?php esc_attr_e(''.$custom_field->field_label.'','school-mgt'); ?></p>
											</div>	
											<?php
											if($custom_field->field_type =='date')
											{	
												?>
												<div class="col-xl-4 col-lg-4">
												<p class="user-info">: <?php if(!empty($custom_field_value)){ echo mj_smgt_getdate_in_input_box($custom_field_value); }else{ echo '-'; } ?>
												</p></div>	
												<?php
											}
											elseif($custom_field->field_type =='file')
											{
												if(!empty($custom_field_value))
												{
												?>
												<div class="col-xl-4 col-lg-4"><p class="user-info">
												<a target="blank" href="<?php echo content_url().'/uploads/school_assets/'.$custom_field_value;?>"><button class="btn btn-default view_document" type="button">
														<i class="fa fa-eye"></i> <?php esc_attr_e('View','school-mgt');?></button></a>
															
														<a target="" href="<?php echo content_url().'/uploads/school_assets/'.$custom_field_value;?>" download="CustomFieldfile"><button class="btn btn-default view_document" type="button">
														<i class="fa fa-download"></i> <?php esc_attr_e('Download','school-mgt');?></button></a></p>
												</div>		
												<?php 
												}
												else
												{
													echo '-';
												}
											}
											else
											{
												?>
												<div class="col-xl-4 col-lg-4">
											<p class="user-info"><?php if(!empty($custom_field_value)){ echo $custom_field_value; }else{ echo '-'; } ?></p>
												</div>	
												<?php		
											}									
										}
										?>	
							</div>											
						</div>
					<?php
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="row">	
			<ul class="nav nav-tabs">
				<li class="nav-item active"><a data-toggle="tab" href="#Section1" class="nav-link active"><i class="fa fa-user"></i><b><?php esc_attr_e( ' Parents', 'school-mgt' ); ?></b></a></li>
			</ul>
			<div class="tab-content">
				<div id="Section1" class="tab-pane active">
					<div class="row">
						<div class="col-lg-12">
							<div class="card">
								<div class="card-content">
									 <div class="table-responsive">
										  <table id="parents_list" class="table display" cellspacing="0" width="100%">
											<thead>
												<tr>
												  <th><?php esc_attr_e('Photo','school-mgt');?></th>
												  <th><?php esc_attr_e('Name','school-mgt');?></th>
												  <th><?php esc_attr_e('Email','school-mgt');?></th>
												  <th><?php esc_attr_e('Phone number','school-mgt');?></th>
												  <th> <?php esc_attr_e('Relation','school-mgt');?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th><?php esc_attr_e('Photo','school-mgt');?></th>
													<th><?php esc_attr_e('Name','school-mgt');?></th>
													<th><?php esc_attr_e('Email','school-mgt');?></th>
													<th><?php esc_attr_e('Phone number','school-mgt');?></th>
													<th> <?php esc_attr_e('Relation','school-mgt');?></th>
												</tr>
											</tfoot>
											<tbody>
											<?php
											if(!empty($user_meta))
											{
												foreach($user_meta as $parentsdata)
												{
												$parent=get_userdata($parentsdata);?>
											<tr>
											  <td><?php 
												if($parentsdata)
												{
													$umetadata=mj_smgt_get_user_image($parentsdata);
												}
												if(empty($umetadata))
												{
													echo '<img src='.get_option( 'smgt_parent_thumb' ).' height="50px" width="50px" class="img-circle rounded-circle " />';
												}
												else
												{
													echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle rounded-circle "/>';
												}?></td>
												 <td><?php echo $parent->first_name." ".$parent->last_name;?></td>
												 <td><?php echo $parent->user_email;?></td> 
												 <td><?php echo $parent->phone;?></td>
											  <td><?php if($parent->relation=='Father'){ echo esc_attr__('Father','school-mgt'); }elseif($parent->relation=='Mother'){ echo esc_attr__('Mother','school-mgt');} ?></td>
											</tr>
											<?php
												}
											}
											?>
										</tbody>
										</table>
									</div>
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
	if($active_tab=='stud_attendance')
	{
		$student_data=get_userdata($_REQUEST['student_id']);
	?> 
		<div class="panel-body">
			<form name="wcwm_report" action="" method="post">
				<input type="hidden" name="attendance" value=1> 
				<input type="hidden" name="user_id" value=<?php echo $_REQUEST['student_id'];?>>  
					<div class="row">
						<div class="col-md-3 col-sm-4 col-xs-12">	
							<?php
							$umetadata=mj_smgt_get_user_image($_REQUEST['student_id']);
							if(empty($umetadata))
							{
								echo '<img class="img-circle rounded-circle img-responsive member-profile w-150-px h-150-px" src='.get_option( 'smgt_student_thumb').'>';
							}
							else
							{
								echo '<img class="img-circle rounded-circle img-responsive member-profile w-150-px h-150-px" src='.$umetadata.' />';
							}
							?>
						</div>
						
						<div class="col-md-9 col-sm-8 col-xs-12 ">
							<div class="row">
								<h2><?php echo $student_data->display_name;?></h2>
							</div>
							<div class="row">
								<div class="col-md-4 col-sm-3 col-xs-12">
									<i class="fa fa-envelope"></i>&nbsp;
									
									<span class="email-span"><?php echo $student_data->user_email;?></span>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									<i class="fa fa-phone"></i>&nbsp;
									<span><?php echo $student_data->phone;?></span>
								</div>
								<div class="col-md-5 col-sm-3 col-xs-12 no-padding">
									<i class="fa fa-list-alt"></i>&nbsp;
									<span><?php echo $student_data->roll_id;?></span>
								</div>
							</div>					
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-3">
							<label for="exam_id"><?php esc_attr_e('Start Date','school-mgt');?></label>
						   <input type="text"  class="form-control sdate" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'] ;else echo date('Y-m-d');?>" readonly>
						</div>
						<div class="form-group col-md-3">
							<label for="exam_id"><?php esc_attr_e('End Date','school-mgt');?></label>
							<input type="text" class="form-control edate" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate']; else echo date('Y-m-d');?>" readonly>
						</div>
						<div class="form-group col-md-3 button-possition">
							<label for="subject_id">&nbsp;</label>
							<input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info view_attendance_button"/>
						</div>
					</div>	
			</form>
			<div class="clearfix"></div>
				<?php if(isset($_REQUEST['view_attendance']))
				{
					
					$start_date = $_REQUEST['sdate'];			
					$end_date = $_REQUEST['edate'];			
					$user_id = $_REQUEST['user_id'];
					 
					 $period = new DatePeriod(
						 new DateTime($start_date),
						 new DateInterval('P1D'),
						 new DateTime($end_date)
					); 			
					$attendance = mj_smgt_view_student_attendance($start_date,$end_date,$user_id);			
					$curremt_date = $start_date;
				?>
					<div class="panel-body">
						<div class="table-responsive">
							<table id="attendance_list" class="display" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th><?php esc_attr_e('Student Name','school-mgt');?></th>
										<th><?php esc_attr_e('Class Name','school-mgt');?></th>
										<th><?php esc_attr_e('Date','school-mgt');?></th>
										<th><?php esc_attr_e('Day','school-mgt');?></th>
										<th><?php esc_attr_e('Attendance','school-mgt');?></th>
										<th><?php esc_attr_e('Comment','school-mgt');?></th>
									</tr>
								</thead> 
								<tfoot>
									<tr>
										<th><?php esc_attr_e('Student Name','school-mgt');?></th>
										<th><?php esc_attr_e('Class Name','school-mgt');?></th>
										<th><?php esc_attr_e('Date','school-mgt');?></th>
										<th><?php esc_attr_e('Day','school-mgt');?></th>
										<th><?php esc_attr_e('Attendance','school-mgt');?></th>
										<th><?php esc_attr_e('Comment','school-mgt');?></th>
									</tr>
								</tfoot> 
								<tbody>
									<?php
										
										foreach($attendance as $attendance_data)
										{
											$attendance_day=date("D", strtotime($attendance_data->attendence_date));
												
											echo '<td>';
											echo mj_smgt_get_display_name($attendance_data->user_id);
											echo '</td>';
											
											
											echo '<td>';
											echo mj_smgt_get_class_name_by_id(get_user_meta($attendance_data->user_id, 'class_name',true));
											echo '</td>';
											
											echo '<td>';
											echo mj_smgt_getdate_in_input_box($attendance_data->attendence_date);
											echo '</td>';
											

											echo '<td>';
											echo  esc_html__( "$attendance_day", "school-mgt" );
											echo '</td>';
											
											$attendance_status = $attendance_data->status;
											if(!empty($attendance_status))
											{
												echo '<td>';
												if($attendance_status=="Present")
												{
													echo esc_attr__('Present','school-mgt');
												}
												elseif($attendance_status=="Late")
												{
													echo esc_attr__('Late','school-mgt');
												}
												else
												{
													echo esc_attr__('Absent','school-mgt');
												}
												echo '</td>';
											}
											else 
											{
												echo '<td>';
												echo esc_attr__('Absent','school-mgt');
												echo '</td>';
											}
											
											echo '<td>';
											echo $attendance_data->comment;
											echo '</td>';
											echo '</tr>';
										}
									?>
								</tbody>        
							</table>
						</div>
					</div>
				<?php } ?>
		</div>
	<?php 
	}
	?>