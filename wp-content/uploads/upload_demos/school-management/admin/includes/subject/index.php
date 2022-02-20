<script>
jQuery(document).ready(function($)
{
"use strict";	
var table =  jQuery('#subject_list').DataTable({
	responsive: true,
	 'order': [1, 'asc'],
	 "dom": 'Bfrtip',
		"buttons": [
			'colvis'
		], 
	 "aoColumns":[
					  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false}],
	  language:<?php echo mj_smgt_datatable_multi_language();?>		
       });	   
	   
   $('#checkbox-select-all').on('click', function(){
     
      var rows = table.rows({ 'search': 'applied' }).nodes();
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
   });
	
	 $("#delete_selected").on('click', function()
		{	
			if ($('.select-checkbox:checked').length == 0 )
			{
				alert(language_translate2.one_record_select_alert);
				return false;
			}
		else{
				var alert_msg=confirm(language_translate2.delete_record_alert);
				if(alert_msg == false)
				{
					return false;
				}
				else
				{
					return true;
				}
			}
	});

	$('#subject_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
    
    $("#subject_teacher").multiselect({ 
         nonSelectedText :'<?php esc_html_e('Select Teacher','school-mgt');?>',
         includeSelectAllOption: true ,
		selectAllText : '<?php esc_html_e('Select all','school-mgt');?>',
		templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       },
     });	
	 $(".teacher_for_alert").on('click',function()
	 {	
		var checked = $(".form-check-input:checked").length;
		if(!checked)
		{
		  alert(language_translate2.one_teacher_alert);
		  return false;
		}	
	 }); 
});
</script>
<?php 
	// This is Dashboard at admin side!!!!!!!!! 
	//--------------Delete code-------------------------------
	$teacher_obj = new Smgt_Teacher;
	$tablename="subject";
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			
			$result=mj_smgt_delete_subject($tablename,$_REQUEST['subject_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=4');
			}
		}
	/*Delete selected Subject*/
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $subject_id)
			$result=mj_smgt_delete_subject($tablename,$subject_id);
		if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=4');
			}
	}
	//------------------Edit-Add code ------------------------------
if(isset($_POST['subject']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_subject_admin_nonce' ) )
	{
	
		$syllabus='';
		if(isset($_FILES['subject_syllabus']) && !empty($_FILES['subject_syllabus']['name']))
		{
			$value = explode(".", $_FILES['subject_syllabus']['name']);
			$file_ext = strtolower(array_pop($value));
			$extensions = array("pdf");
			
			if(in_array($file_ext,$extensions )=== false)
			{				
				wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=3');
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
						wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=addsubject&action=edit&subject_id='.$sub_id.'&message=5');
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
					/* Send Assign Subject Mail */	
					if(isset($_POST['smgt_mail_service_enable']))
					{
						foreach($_POST['subject_teacher'] as $teacher_id)
						{
							$smgt_mail_service_enable = $_POST['smgt_mail_service_enable'];
							if($smgt_mail_service_enable)
							{	
								$search['{{teacher_name}}']	 	= 	mj_smgt_get_teacher($teacher_id);
								$search['{{subject_name}}'] 	= 	$_POST['subject_name'];						
								$search['{{school_name}}'] 		= 	get_option('smgt_school_name');								
								$message = mj_smgt_string_replacement($search,get_option('assign_subject_mailcontent'));			
								if(get_option('smgt_mail_notification') == '1')
								{
									wp_mail(mj_smgt_get_emailid_byuser_id($teacher_id),get_option('assign_subject_title'),$message);
								}	
							}
						}
					}		

					wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=2');
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
					wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=addsubject&message=5');
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
				/* Send Assign Subject Mail */
				if(isset($_POST['smgt_mail_service_enable']))
				{
					foreach($_POST['subject_teacher'] as $teacher_id)
					{
						$smgt_mail_service_enable = $_POST['smgt_mail_service_enable'];
						if($smgt_mail_service_enable)
						{	
							$search['{{teacher_name}}']	 	= 	mj_smgt_get_teacher($teacher_id);
							$search['{{subject_name}}'] 	= 	$_POST['subject_name'];						
							$search['{{school_name}}'] 		= 	get_option('smgt_school_name');								
							$message = mj_smgt_string_replacement($search,get_option('assign_subject_mailcontent'));			
							if(get_option('smgt_mail_notification') == '1')
							{
								wp_mail(mj_smgt_get_emailid_byuser_id($teacher_id),get_option('assign_subject_title'),$message);
							}	
						}
					}
				}	
				wp_redirect ( admin_url().'admin.php?page=smgt_Subject&tab=Subject&message=1');
			}	
		}
	}
}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'Subject';
	?>

<div class="page-inner">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div id="main-wrapper">
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
		<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
			<p><?php echo $message_string;?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
<?php } ?>
	<div class="panel panel-white">
					<div class="panel-body"> 
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_Subject&tab=Subject" class="nav-tab <?php echo $active_tab == 'Subject' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>',esc_attr__('Subject List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
         <a href="?page=smgt_Subject&tab=addsubject&&action=edit&subject_id=<?php echo $_REQUEST['subject_id'];?>" class="nav-tab <?php echo $active_tab == 'addsubject' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Subject', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_Subject&tab=addsubject" class="nav-tab margin_bottom <?php echo $active_tab == 'addsubject' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.esc_attr__('Add Subject', 'school-mgt'); ?></a> 
        <?php }?> 
        
    </h2>
    <?php
	if($active_tab == 'Subject')
	{
		$retrieve_subjects=mj_smgt_get_all_data($tablename);?>
			<div class="panel-body">

	<form id="frm-example" name="frm-example" method="post">
		<div class="table-responsive">
			<table id="subject_list" class="display datatable" cellspacing="0" width="100%">
				 <thead>
				<tr>
					<th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" 
					type="checkbox" /></th>
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
					<th></th>
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
				foreach ($retrieve_subjects as $retrieved_data)
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
					<td><input type="checkbox" class="select-checkbox" name="id[]" 
					value="<?php echo $retrieved_data->subid;?>"></td>
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
					<td> <a href="?page=smgt_Subject&tab=addsubject&action=edit&subject_id=<?php echo $retrieved_data->subid;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?> </a>
					<a href="?page=smgt_Subject&tab=Subject&action=delete&subject_id=<?php echo $retrieved_data->subid;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a> 
					 <?php if($retrieved_data->syllabus!='') { ?><a href="<?php echo content_url().'/uploads/school_assets/'.$retrieved_data->syllabus;?>" class="btn btn-default" target="_blank"><i class="fa fa-download"></i><?php esc_attr_e('Syllabus','school-mgt');?></a>
					<?php } ?>
					</td>
				</tr>
				<?php } ?>	
		 
			</tbody>
			
			</table>
		</div>
        <div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected" class="btn btn-danger delete_selected"/>
			
		</div>
		</form>
        </div>
  
     <?php 
	 }
	if($active_tab == 'addsubject')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/subject/add-newsubject.php';
	 }
	 ?>
	 
	 </div>
	 </div>
	 </div>
</div>
<?php ?>