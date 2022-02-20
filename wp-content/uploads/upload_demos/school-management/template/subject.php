<script type="text/javascript">
jQuery(document).ready(function($)
{
	"use strict";	
	 jQuery('#add_subject_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
     jQuery('#subject_list').DataTable({
        responsive: true,
		language:<?php echo mj_smgt_datatable_multi_language();?>	
      });	 
	$("#subject_teacher").multiselect({ 
         nonSelectedText :'<?php esc_attr_e( 'Select Teacher', 'school-mgt' ) ;?>',
         includeSelectAllOption: true ,
		selectAllText : '<?php esc_attr_e( 'Select all', 'school-mgt' ) ;?>',
		templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       }
     });
   jQuery("body").on("change", "#subject_syllabus", function ()
	{ 
	
		 "use strict";
		var file = this.files[0]; 		
		var ext = $(this).val().split('.').pop().toLowerCase(); 
		//Extension Check 
		if($.inArray(ext, [,'pdf','']) == -1)
		{
			 alert('Only pdf formate are allowed. '  + ext + ' formate are not allowed.');
			 $("#subject_syllabus").val("");
			 return false; 
		} 
		//File Size Check 
		if (file.size > 20480000) 
		{
			alert(language_translate2.large_file_Size_alert);
			$("#subject_syllabus").val("");
			return false; 
		} 
	});	 
});
</script>
<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$obj_subject=new smgt_subject;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'subjectlist';
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
//=============== SAVE SUBJECT =================//
if(isset($_POST['subject']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'add_subject_front_nonce' ) )
	{
	
		$syllabus='';
		if(isset($_FILES['subject_syllabus']) && !empty($_FILES['subject_syllabus']['name']))
		{
			$value = explode(".", $_FILES['subject_syllabus']['name']);
			$file_ext = strtolower(array_pop($value));
			$extensions = array("pdf");
			
			if(in_array($file_ext,$extensions )=== false)
			{				
				wp_redirect (home_url()."?dashboard=user&page=subject&message=3");
				exit;
			}
			if($_FILES['subject_syllabus']['size'] > 0)
			{
			 $syllabus=inventory_image_upmj_smgt_load($_FILES['subject_syllabus']);
			}	
			else {
				$syllabus=$_POST['sylybushidden'];
			}
			//------TEMPRORY ADD RECORD FOR SET SYLLABUS----------		
		}
		
		$subjects=array(
						'subject_code'=>mj_smgt_onlyNumberSp_validation($_POST['subject_code']),
						'sub_name'=>mj_smgt_address_description_validation($_POST['subject_name']),
						'class_id'=>mj_smgt_onlyNumberSp_validation($_POST['subject_class']),
						'section_id'=>mj_smgt_onlyNumberSp_validation($_POST['class_section']),
						'teacher_id'=>0,
						'edition'=>mj_smgt_address_description_validation($_POST['subject_edition']),
						'author_name'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['subject_author']),			
						'syllabus'=>$syllabus,
						'created_by'=>get_current_user_id()
		);
		if(isset($_FILES['subject_syllabus']) && empty($_FILES['subject_syllabus']['name']))
		{
			unset($subjects['syllabus']);
		}
		$tablename="subject";
			$selected_teachers = isset($_REQUEST['subject_teacher'])?$_REQUEST['subject_teacher']:array();
		
		if($_REQUEST['action']=='edit')
		{
			//------------ SUBJECT CODE CHECK ------------//
				$sub_id=$_REQUEST['subject_id'];
				$class_id=$_POST['subject_class'];
				global $wpdb;
				 
				$table_name_subject = $wpdb->prefix .'subject';
				
				$result_sub =$wpdb->get_results("SELECT * FROM $table_name_subject WHERE class_id=$class_id and subid !=".$sub_id);
				
				if(!empty($result_sub))
				{
					foreach($result_sub as $sub_code)
					{
						$subject_code[]=$sub_code->subject_code;
					}
					$check=in_array($_POST['subject_code'], $subject_code);
					if($check)
					{
						wp_redirect (home_url().'?dashboard=user&page=subject&tab=addsubject&action=edit&subject_id='.$sub_id.'&message=5');
						die;
					}
				}
				global $wpdb;
				$table_smgt_subject = $wpdb->prefix. 'teacher_subject';  
			//---------------------------------// 
				$subid=array('subid'=>$_REQUEST['subject_id']);
				$result=mj_smgt_update_record($tablename,$subjects,$subid);
				$wpdb->delete( 
					$table_smgt_subject,      // table name 
					array( 'subject_id' => $_REQUEST['subject_id'] ),  // where clause 
					array( '%s' )      // where clause data type (string)
				);
									
							
				if(!empty($selected_teachers))
				{
					$teacher_subject = $wpdb->prefix .'teacher_subject';
					foreach($selected_teachers as $teacher_id)
					{
						$wpdb->insert($teacher_subject,
							array( 
								'teacher_id' => $teacher_id,
								'subject_id' => $_REQUEST['subject_id'],
								'created_date' => time(),
								'created_by' => get_current_user_id()
							)
						); 
					}
				}
				wp_safe_redirect(home_url()."?dashboard=user&page=subject&message=2");
		}
		else
		{  
			$subject_code=$_POST['subject_code'];
			$class_id=$_POST['subject_class'];
				global $wpdb;
				 
				$table_name_subject = $wpdb->prefix .'subject';
				
				$result_sub =$wpdb->get_results("SELECT * FROM $table_name_subject WHERE class_id=$class_id and subject_code=".$subject_code);
				 
				if(!empty($result_sub))
				{
					wp_redirect ( admin_url().'?dashboard=user&page=subject&tab=addsubject&message=5');
					die;
				}	
			$result=mj_smgt_insert_record($tablename,$subjects);
			$lastid = $wpdb->insert_id;
			if(!empty($selected_teachers))
			{
				$teacher_subject = $wpdb->prefix .'teacher_subject';
				foreach($selected_teachers as $teacher_id)
				{
					$wpdb->insert( 
					$teacher_subject, 
					array( 
						'teacher_id' => $teacher_id,
						'subject_id' => $lastid,
						'created_date' => time(),
						'created_by' => get_current_user_id()
						)
					);
	 
				}
			}
			if($result)
			{
				wp_safe_redirect(home_url()."?dashboard=user&page=subject&message=1");
			}	
		}
	}
}

//--------------Delete SUBJECT -------------------------------
$teacher_obj = new Smgt_Teacher;
$tablename="subject";
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	$result=mj_smgt_delete_subject($tablename,$_REQUEST['subject_id']);
	if($result)
	{
		wp_redirect (home_url()."?dashboard=user&page=subject&message=4");
	}
}
?>
<div class="p-4 panel-body panel-white">
<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Subject Added Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Subject Updated Successfully.','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('This File Type Is Not Allowed, Please Upload Only Pdf File.','school-mgt');
			break;	
		case '4':
			$message_string = esc_attr__('Subject Deleted Successfully.','school-mgt');
			break;		
		case '5':
			$message_string = esc_attr__('Please Enter Unique Subject Code','school-mgt');
			break;		
	}
	
	if($message)
	{ ?>
		<div class="alert_msg alert alert-success alert-dismissible " role="alert">
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<?php echo $message_string;?>
		</div>
<?php } ?>
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="nav-item">
			<a href="?dashboard=user&page=subject&tab=subjectlist" class="p2- px-3 nav-link tab <?php echo $active_tab == 'subjectlist' ? 'active' : ''; ?>">
				<i class="fa fa-align-justify"></i><?php esc_attr_e(' Subject List', 'school-mgt'); ?></a>
			</a>
		</li>
		<li class="nav-item">
		  <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{?>
				<a href="?dashboard=user&page=subject&tab=addsubject&&action=edit&subject_id=<?php echo $_REQUEST['subject_id'];?>" class="p2- px-3 nav-link tab <?php echo $active_tab == 'addsubject' ? 'active' : ''; ?>">
				<i class="fa"></i> <?php esc_attr_e('Edit Subject', 'school-mgt'); ?></a>
			 <?php 
			}
			else
			{
				if($user_access['add']=='1')
				{			
				?>				
					<a href="?dashboard=user&page=subject&tab=addsubject&action=insert" class="p2- px-3 nav-link tab <?php echo $active_tab == 'addsubject' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Add Subject', 'school-mgt'); ?></a>
				<?php
				}
			}
			?>	  
		</li>
	</ul>
    <?php
	if($active_tab=='subjectlist')
	{ ?>
	<div class="panel-body">
        <div class="table-responsive">
			<table id="subject_list" class="display dataTable dataTable1" cellspacing="0" width="100%">
				<thead>
					<tr>                
						<th><?php esc_attr_e('Subject Code','school-mgt');?></th>
						<th><?php esc_attr_e('Subject Name','school-mgt');?></th>
						<th><?php esc_attr_e('Teacher Name','school-mgt');?></th>
						<th><?php esc_attr_e('Class Name','school-mgt');?></th>
						<th><?php esc_attr_e('Section Name','school-mgt');?></th>
						<th><?php esc_attr_e('Author Name','school-mgt');?></th>
						<th><?php esc_attr_e('Edition','school-mgt');?></th>
						<th><?php esc_attr_e('Action','school-mgt');?></th>	
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php esc_attr_e('Subject Code','school-mgt');?></th>
						<th><?php esc_attr_e('Subject Name','school-mgt');?></th>
						<th><?php esc_attr_e('Teacher Name','school-mgt');?></th>
						<th><?php esc_attr_e('Class Name','school-mgt');?></th>
						<th><?php esc_attr_e('Section Name','school-mgt');?></th>
						<th><?php esc_attr_e('Author Name','school-mgt');?></th>
						<th><?php esc_attr_e('Edition','school-mgt');?></th>
						<th><?php esc_attr_e('Action','school-mgt');?></th>
					</tr>
				</tfoot>
	 
				<tbody>
					<?php 
					$user_id=get_current_user_id();
					//------- SUBJECT DATA FOR STUDENT ---------//
					if($school_obj->role == 'student')
					{
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{ 
							$subjects = $school_obj->subject;	
							
						}
						else
						{
							$subjects = mj_smgt_get_all_data('subject');
						}
					}
					//------- SUBJECT DATA FOR TEACHER ---------//
					elseif($school_obj->role == 'teacher')
					{
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{ 
							$subjects=array();
							$subjects_data =$obj_subject->mj_smgt_get_teacher_own_subject($user_id);
							foreach($subjects_data as $s_id)
							{
								$subjects[]=mj_smgt_get_subject($s_id->subject_id);
							}  
						}
						else
						{
							$subjects = mj_smgt_get_all_data('subject');
						}
					} 
					//------- SUBJECT DATA FOR PARENT ---------//
					elseif($school_obj->role == 'parent')
					{
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{ 
							$chid_array =$school_obj->child_list;
							foreach ($chid_array as $child_id)
							{
								$class_info = $school_obj->mj_smgt_get_user_class_id($child_id);
								$subjects= $school_obj->mj_smgt_subject_list($class_info->class_id);
							}
						}
						else
						{
							$subjects = mj_smgt_get_all_data('subject');
						}
					}
					//------- SUBJECT DATA FOR SUPPORT STAFF ---------//
					else
					{ 
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{ 
							$subjects = mj_smgt_get_all_own_subject_data('subject',);
						}
						else
						{
							$subjects = mj_smgt_get_all_data('subject');
						}
					} 
						foreach ($subjects as $retrieved_data)
						{ 
							$teacher_group = array();
							$teacher_ids = mj_smgt_teacher_by_subject($retrieved_data);
							foreach($teacher_ids as $teacher_id)
							{
								$teacher_group[] = mj_smgt_get_teacher($teacher_id);
							}
							$teachers = implode(',',$teacher_group);
					 ?>
						<tr>
						   <td><?php
							if(!empty($retrieved_data->subject_code))
							{
								echo $retrieved_data->subject_code;
							}
							else
							{
								echo "-";
							}?></td>

							<td><?php echo $retrieved_data->sub_name;?></td>
							<td><?php echo $teachers;?></td>
							<td><?php $cid=$retrieved_data->class_id;
								echo  $clasname=mj_smgt_get_class_name($cid);
							?></td>
							<!--<td><?php if($retrieved_data->section_id!=""){ echo  mj_smgt_get_section_name($retrieved_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?></td>-->
							  <td><?php if($retrieved_data->section_id!=0){ echo mj_smgt_get_section_name($retrieved_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?></td>
							
							<td><?php echo $retrieved_data->author_name;?></td>
							<td><?php echo $retrieved_data->edition;?></td>    
						   <td>
						   <?php
							if($user_access['edit']=='1')
							{
							?>
								<a href="?dashboard=user&page=subject&tab=addsubject&action=edit&subject_id=<?php echo $retrieved_data->subid;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?> </a>
						   <?php
							}
							if($user_access['delete']=='1')
							{
							?>
							  <a href="?dashboard=user&page=subject&tab=Subject&action=delete&subject_id=<?php echo $retrieved_data->subid;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a> 
							<?php
							}
							if($retrieved_data->syllabus!='')
							{?>
							   <a href="<?php echo content_url().'/uploads/school_assets/'.$retrieved_data->syllabus;?>" class="btn btn-default" target="_blank"><i class="fa fa-download"></i><?php esc_attr_e('Syllabus','school-mgt');?></a>
						   <?php 
							}
						   ?>
						   </td>
						</tr> 
						<?php 
						} 
					?>
				</tbody>
			
			</table>
         </div>
	</div>
	<?php
	}
	if($active_tab=='addsubject')
	{ 
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{	
			$edit=1;
			
			$subject=mj_smgt_get_subject($_REQUEST['subject_id']);
		}
	?>					
	<div class="mt-4 panel-body">	
		<form name="student_form" action="" method="post" class="form-horizontal" id="add_subject_form" enctype="multipart/form-data">
			 <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_name"><?php esc_attr_e('Subject Name','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-2">
					<input id="subject_code"class="form-control validate[required,custom[onlyNumberSp],maxSize[8],min[0]] text-input" placeholder="<?php esc_html_e('Enter Subject Code','school-mgt');?>" type="text" maxlength="50" value="<?php if($edit){ echo $subject->subject_code;}?>" name="subject_code">
				</div>
				<div class="col-sm-6">
					<input id="subject_name" class="form-control validate[required,custom[address_description_validation]] margin_top_10_res" type="text" maxlength="50" value="<?php if($edit){ echo $subject->sub_name;}?>" placeholder="<?php esc_html_e('Enter Subject Name','school-mgt');?>" name="subject_name">
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_class"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<?php if($edit){ $classval=$subject->class_id; }else{$classval='';}?>
							<select name="subject_class" class="form-control validate[required] class_by_teacher" id="class_list">
								<option value=""><?php esc_attr_e('Select Class', 'school-mgt');?></option>
								<?php
									foreach(mj_smgt_get_allclass() as $classdata)
									{ ?>
									 <option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
								<?php } ?>
						</select>
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
				<div class="col-sm-8">
					<?php if($edit){ $sectionval=$subject->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
							<select name="class_section" class="form-control" id="class_section">
								<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
								<?php
								if($edit){
									foreach(mj_smgt_get_class_sections($subject->class_id) as $sectiondata)
									{  ?>
									 <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
								<?php } 
								}?>
							</select>
				</div>
			</div>
			<?php
			if($school_obj->role == 'teacher')
			{ 
				$user_id=get_current_user_id();
			?>
			<div class="mb-3 form-group row">
				<input type="hidden" name="subject_teacher[]" value="<?php echo $user_id;?>">
			</div>
			<?php
			}
			else
			{
			?>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_teacher"><?php esc_attr_e('Teacher','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8 multiselect_validation_teacher">
					<?php 
					$teachval = array();
					if($edit){      
					$teachval = mj_smgt_teacher_by_subject($subject);  
					}
					?>
					<select name="subject_teacher[]" multiple="multiple" id="subject_teacher" class="form-control validate[required] teacher_list">               
					   <?php 
							foreach(mj_smgt_get_usersdata('teacher') as $teacherdata)
							{ ?>
							 <option value="<?php echo $teacherdata->ID;?>" <?php echo $teacher_obj->mj_smgt_in_array_r($teacherdata->ID, $teachval) ? 'selected' : ''; ?>><?php echo $teacherdata->display_name;?></option>
						<?php }?>
					</select>
				</div>
			</div>
			<?php
			} ?>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_edition"><?php esc_attr_e('Edition','school-mgt');?></label>
				<div class="col-sm-8">
					<input id="subject_edition" class="form-control validate[custom[address_description_validation]]" maxlength="50" type="text" value="<?php if($edit){ echo $subject->edition;}?>" name="subject_edition">
				</div>
			</div>
			<?php wp_nonce_field( 'add_subject_front_nonce' ); ?>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_author"><?php esc_attr_e('Author Name','school-mgt');?></label>
				<div class="col-sm-8">
					<input id="subject_author" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="100" type="text" value="<?php if($edit){ echo $subject->author_name;}?>" name="subject_author">
				</div>
			</div>
			<?php
				if($edit)
				{
					$syllabus=$subject->syllabus;
				?>	
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_syllabus"><?php esc_attr_e('Syllabus','school-mgt');?></label>
					<div class="col-sm-10">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
							<input type="file" accept=".pdf" name="subject_syllabus"  id="subject_syllabus"/>	
						</div>
						 <input type="hidden" name="sylybushidden" value="<?php if($edit){ echo $subject->syllabus;} else echo "";?>">
					<?php if(!empty($syllabus))
					{ ?>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
							<a target="blank"  class="status_read btn btn-default" href="<?php print content_url().'/uploads/school_assets/'.$syllabus; ?>" record_id="<?php echo $subject->subject;?>"><i class="fa fa-download"></i><?php echo $syllabus;?></a>
						</div>
				<?php	
					} ?>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						   <p class="help-block"><?php esc_attr_e('Upload syllabus in PDF','school-mgt');?></p>  
						</div>
					</div>
				</div>
				<?php
				}
				else
				{
				?>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_syllabus"><?php esc_attr_e('Syllabus','school-mgt');?></label>
					<div class="col-sm-8">
						 <input type="file" accept=".pdf" name="subject_syllabus"  id="subject_syllabus"/>				 
						   <p class="help-block"><?php esc_attr_e('Upload syllabus in PDF','school-mgt');?></p>     
					</div>
				</div>
				<?php
				}
				?>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end"></label>
				<div class="col-sm-8">
				<input type="submit" value="<?php if($edit){ esc_attr_e('Save Subject','school-mgt'); }else{ esc_attr_e('Add Subject','school-mgt');}?>" name="subject" class="btn btn-success teacher_for_alert"/>
				</div>
			</div>
		</form>
	</div>
	<?php
	}
	?>
</div>
<?php
?>