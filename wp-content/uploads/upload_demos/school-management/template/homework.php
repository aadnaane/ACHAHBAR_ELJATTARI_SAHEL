<script type="text/javascript">
jQuery(document).ready(function($)
{
	"use strict";	
	$('#homework_form_tempalte').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	
 	  $('#view_submition_form_front').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	
    $('#homework_list_front').DataTable({
		responsive: true,
		language:<?php echo mj_smgt_datatable_multi_language();?>	
	});

		  $('#class_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#class_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
		$('.datepicker').datepicker({
			minDate:0,
			dateFormat: 'yy-mm-dd' 
		});

		var table =  jQuery('#submission_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[	                  
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
	 jQuery('#checkbox-select-all').on('click', function(){
     
      var rows = table.rows({ 'search': 'applied' }).nodes();
      jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
   }); 
   
	 $("#delete_selected").on('click', function()
		{	
			if ($('.select-checkbox:checked').length == 0 )
			{
				alert(language_translate2.one_record_select_alert);
				return false;
			}
		else{
				var alert_msg=confirm("Are you sure you want to delete this record?");
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

});

	jQuery("body").on("change", "#file", function ()
	{
        "use strict";		
		var file = this.files[0]; 
		var ext = $(this).val().split('.').pop().toLowerCase(); 
		//Extension Check 
		if($.inArray(ext, ['pdf','doc','docx','xls','xlsx','ppt','pptx','gif','png','jpg','jpeg']) == -1)
		{
			alert('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed. '  + ext + ' formate are not allowed.');
			$(this).replaceWith('<input type="file" name="file" class="form-control validate[required]">');
			return true; 
		} 
	 //File Size Check 
	if (file.size > 20480000) 
	{
			 
			alert(language_translate2.large_file_Size_alert);
			$(this).replaceWith('<input type="file" name="file" class="form-control validate[required]">'); 
			return false; 
		 
	}
	});
</script>
<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
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
	require_once SMS_PLUGIN_DIR. '/school-management-class.php';
	$homewrk=new Smgt_Homework();
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'homeworklist';
?>

<!-- Nav tabs -->
<?php 
if(isset($_GET['success']) && $_GET['success'] == 1 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('Homework Upload successfully !','school-mgt');?>
	</div>
<?php
}	
if(isset($_GET['filesuccess']) && $_GET['filesuccess'] == 1 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('File Extension Invalid !','school-mgt');?>
	</div>
	 
<?php
}	
if(isset($_GET['addsuccess']) && $_GET['addsuccess'] == 1 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('Homework Added Successfully.','school-mgt');?>
	</div>
<?php
}
if(isset($_GET['deletesuccess']) && $_GET['deletesuccess'] == 1 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('Homework Deleted Successfully','school-mgt');?>
	</div>
<?php 
}
if(isset($_GET['updatesuccess']) && $_GET['updatesuccess'] == 1 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('Homework Updated Successfully','school-mgt');?>
	</div>
<?php 
}					
?>
<div class="p-4 panel-body panel-white">
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class=" nav-item">
			<a href="?dashboard=user&page=homework&tab=homeworklist" class="nav-link nav-tab2 <?php if($active_tab=='homeworklist'){?>active<?php }?>">
				<i class="fa fa-align-justify"></i> <?php esc_attr_e('Homework List', 'school-mgt'); ?>
		    </a>
        </li>
		<?php 
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
		{?>
			<li class="nav-item">
				<a href="?dashboard=user&page=homework&tab=Viewhomework&action=view&homework_id=<?php echo $_REQUEST['homework_id'];?> " class="nav-link nav-tab2 <?php if($active_tab=='Viewhomework'){?>active<?php }?>">
					<i class="fa fa-upload"></i> <?php esc_attr_e('Upload Homework', 'school-mgt'); ?></a>
				</a>
			</li>
		 <?php 
		}?>
		<?php 
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
			<li class="nav-item">
				<a href="?dashboard=user&page=homework&tab=addhomework&&action=edit&homework_id=<?php echo $_REQUEST['homework_id'];?>" class="nav-link nav-tab2 <?php if($active_tab=='addhomework'){?>active<?php }?>">
					<i class="fa fa-align-justify"></i> <?php esc_attr_e('Edit Homework', 'school-mgt'); ?>
				</a>
			</li>
			<?php 
		}
		else
		{
			if($user_access['add']=='1')
			{?>
				<li class="nav-item">
					<a href="?dashboard=user&page=homework&tab=addhomework" class="nav-link nav-tab2 <?php if($active_tab=='addhomework'){?>active<?php }?>">
						<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Add Homework', 'school-mgt'); ?>
					</a>
				</li>
				<li class="nav-item">
					<a href="?dashboard=user&page=homework&tab=view_stud_detail"c class="nav-link nav-tab2 <?php if($active_tab=='view_stud_detail'){?>active<?php }?>">
						<i class="fa fa-eye"></i> <?php esc_attr_e('View Submission', 'school-mgt'); ?>
					</a>
				</li>
		<?php 
			} 
		}?>	
	</ul> 
    <!-- Tab panes -->
	<?php
	if($active_tab == 'addhomework')
	{
		require_once SMS_PLUGIN_DIR. '/template/add-studentHomework.php';
	}
	if($active_tab == 'view_stud_detail')
	{	
		$homework=new Smgt_Homework();
		if($school_obj->role=='teacher')
		{
			$res = $homework->mj_smgt_get_teacher_homeworklist();
		}
		else
		{
			$res = $homework->mj_smgt_get_all_homeworklist();
		}	
			
	   ?>
			<div class="mt-4 panel-body">	
				<form name="view_submition_form_front" action="" method="post" class="form-horizontal" id="class_form">
					<div class="mb-3 form-group row">
						<label class="col-sm-2 control-label col-form-label text-md-end" for="homewrk"><?php esc_attr_e('Select Homework','school-mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-4">
							<select name="homewrk" class="form-control validate[required]" id="homewrk">
								<option value=""><?php esc_attr_e('Select Homework','school-mgt');?></option>
								<?php
								if(isset($_POST['homewrk']))
								{
									$classval=$_POST['homewrk'];
								}
								foreach($res as $classdata)
								{  
								?>
									<option value="<?php echo $classdata->homework_id;?>" <?php selected($classdata->homework_id,$classval);  ?>><?php echo $classdata->title;?></option>
								<?php 
								}?>
							</select>
						</div>
						<div class="col-sm-4">
							<label for="subject_id">&nbsp;</label>
							<input type="submit" value="<?php esc_attr_e('View','school-mgt');?>" name="view"  class="btn btn-info margin_top_10_res"/>
						</div>
					</div>    
						   <?php
							$obj=new Smgt_Homework();
							if(isset($_POST['homewrk']))
							{
							  $data=$_POST['homewrk'];
							  $retrieve_class=$obj-> mj_smgt_view_submission($data);
							require_once SMS_PLUGIN_DIR. '/template/viewsubmission.php';
							}
							else
							{
							  if(isset($_REQUEST['homework_id']))
							  {
								 $data=$_REQUEST['homework_id'];
								 $retrieve_class=$obj-> mj_smgt_view_submission($data);
								 require_once SMS_PLUGIN_DIR. '/template/viewsubmission.php';
							  }
							}
							?>
				</form>
			</div>
       <?php
    }
	 ?>
    <div class="tab-content">
	<?php 
	if($active_tab=="homeworklist")
	{?>
		<div class="tab-pane active" id="examlist">         
			<?php 
			//------- HomeWork DATA FOR STUDENT ---------//
			if($school_obj->role=='student')
			{
				$result=$homewrk->mj_smgt_student_view_detail();
			}
			//------- HomeWork DATA FOR PARENT ---------//
			elseif($school_obj->role=='parent')
			{
				global $user_ID;
				$result=mj_smgt_get_parents_child_id($user_ID);		
				$result = implode(",",$result);
				$result = $homewrk->mj_smgt_parent_view_detail($result);
			}
			//------- HomeWork DATA FOR TEACHER ---------//
			elseif($school_obj->role=='teacher')
			{
				$result=$homewrk->mj_smgt_get_all_homeworklist();
			}
			//------- HomeWork DATA FOR SUPPORT STAFF ---------//
			else
			{
				$own_data=$user_access['own_data'];
				if($own_data == '1')
				{ 
			   $result=$homewrk->mj_smgt_get_all_own_homeworklist();
				}
				else
				{
					$result=$homewrk->mj_smgt_get_all_homeworklist();
				}
				
			}
				
			?>
			<div class="panel-body">
				<div class="table-responsive">
						<table id="homework_list_front" class="display dataTable" cellspacing="0" width="100%">
							<thead>
								<tr>                
									<th><?php esc_attr_e('Homework Title','school-mgt');?></th>
									 <?php if($school_obj->role=='parent') {?>
									 <th><?php esc_attr_e('Student','school-mgt');?></th>
									 <?php }?>
									<th><?php esc_attr_e('Class','school-mgt');?></th>
									<th><?php esc_attr_e('Subject','school-mgt');?></th>
									<?php   
										if($school_obj->role=='student' || $school_obj->role=='parent')
										{ ?>
										<th><?php esc_attr_e('Status','school-mgt');?></th>
										<?php 
										}?>
						            <th><?php esc_attr_e('Submission Date','school-mgt');?></th>
									<th><?php esc_attr_e('Created Date','school-mgt');?></th>
									<th><?php esc_attr_e('Action','school-mgt');?></th>               
								</tr>
							</thead>
					 
							<tfoot>
								  <tr>                
									<th><?php esc_attr_e('Homework Title','school-mgt');?></th>
									 <?php if($school_obj->role=='parent') {?>
									 <th><?php esc_attr_e('Student','school-mgt');?></th>
									 <?php }?>
									<th><?php esc_attr_e('Class','school-mgt');?></th>
									<th><?php esc_attr_e('Subject','school-mgt');?></th>
									<?php  if($school_obj->role=='student' || $school_obj->role=='parent')
									{ ?>
									<th><?php esc_attr_e('Status','school-mgt');?></th>
									
								<?php }?>
								    <th><?php esc_attr_e('Submission Date','school-mgt');?></th>
									<th><?php esc_attr_e('Created Date','school-mgt');?></th>
									<th><?php esc_attr_e('Action','school-mgt');?></th>               
								</tr>
							</tfoot>
				 
							<tbody>
							  <?php 
								foreach ($result as $retrieved_data)
								{ 			
							     ?>
									<tr>
										<td><?php echo $retrieved_data->title;?></td>
										<?php 
										if($school_obj->role=='parent')
										{?>
											<td><?php echo mj_smgt_get_user_name_byid($retrieved_data->student_id);?></td>	
											<?php 
										}?>
											<td><?php echo mj_smgt_get_class_name($retrieved_data->class_name);?></td>  
											<td><?php echo mj_smgt_get_single_subject_name($retrieved_data->subject);?></td>
										<?php  
										if($school_obj->role=='student' || $school_obj->role=='parent')
										{ ?>
											<?php if($retrieved_data->status==1)
											{
												if(date('Y-m-d',strtotime($retrieved_data->uploaded_date)) <= $retrieved_data->submition_date)
												{
												 ?>
												 <td><label class="green_color"><?php esc_attr_e('Submitted','school-mgt'); ?></label></td>
												 <?php
												}
												else
												{
												 ?><td><label class="green_color"><?php esc_attr_e('Late-Submitted','school-mgt');?></label></td><?php
												}
											}
											else
											{
										  ?>
												<td><label class="color-red"><?php esc_attr_e('Pending','school-mgt');?></label></td>
										  <?php			     
											} 
										} ?>
											<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->submition_date);?></td>
											<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->created_date);?></td>
										  <td>
										  <?php   
											if($school_obj->role=='student' || $school_obj->role=='parent')
											{ ?>			
												<a href="?dashboard=user&page=homework&tab=Viewhomework&action=view&homework_id=<?php echo $retrieved_data->homework_id;?>&student_id=<?php echo $retrieved_data->student_id;?>" class="btn btn-info"> <?php echo '<i class="fa fa-eye"></i>  '.esc_attr__('View','school-mgt');?></a>
										   <?php 
											}
											?>			     
											<?php
											if($user_access['edit']=='1')
											{
											?>
												<a href="?dashboard=user&page=homework&tab=addhomework&action=edit&homework_id=<?php echo $retrieved_data->homework_id;?>" class="btn btn-info"> <?php esc_attr_e('Edit','school-mgt');?></a>
											<?php
											}
											if($user_access['delete']=='1')
											{
											?>
											   <a href="?dashboard=user&page=homework&tab=homeworklist&action=delete&homework_id=<?php echo $retrieved_data->homework_id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a>
											<?php
											}
											if($user_access['add']=='1')
											{
											?>
											   <a href="?dashboard=user&page=homework&tab=view_stud_detail&action=viewsubmission&homework_id=<?php echo $retrieved_data->homework_id;?>" class="btn btn-default"> <?php echo '<span class="fa fa-eye"></span> '.esc_attr__('View Submission','school-mgt');?></a>
											   <?php
											} ?>
											 <?php
											   $doc_data=json_decode($retrieved_data->homework_document);
												if(!empty($doc_data[0]->value))
												{
												?>
													<a download href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>"  class="status_read btn btn-default" record_id="<?php echo $retrieved_data->homework_id;?>"><i class="fa fa-download"></i><?php esc_html_e('Download Document', 'school-mgt');?></a>
												
													<a target="blank" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" class="status_read btn btn-default" record_id="<?php echo $retrieved_data->homework_id;?>"><i class="fa fa-eye"></i><?php esc_html_e(' View Document', 'school-mgt');?></a>
											   <?php
												}
												?>
											</td>
									</tr>
								 <?php 
								} ?>
						    </tbody>
						</table>
				</div>
			</div>
		</div>
	  <?php
	} 
	$view=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
	{
		
		$view=1;
		$objj=new Smgt_Homework();
		$classdata= $objj->mj_smgt_parent_update_detail($_GET['homework_id'],$_GET['student_id']);
		$data = $classdata[0];
	} 
	if($active_tab=="Viewhomework")
	{ 
	  ?>

		  <div class="tab-pane  id="examresult">
		   <li class="nav-item">
			  <a href="?dashboard=user&page=homework&tab=Viewhomework" role="tab" data-toggle="tab" class="nav-link active">
				<i class="fa fa-align-justify"></i> <?php esc_attr_e('Homework File Upload', 'school-mgt'); ?></a>
			  </a>
			  </li>
		  </div>
		  <div class="tab-pane active" id="">
				<div class="mt-4 panel-body">	
			        <form name="class_form" action="" method="post" class="form-horizontal" id="homework_form_tempalte" enctype="multipart/form-data">
						<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
							<input type="hidden" name="action" value="<?php echo $action;?>">
							<input type="hidden" id="stu_homework_id" name="stu_homework_id" value="<?php if($view){ echo $data->stu_homework_id;}?>">
							<input type="hidden" id="homework_id" name="homework_id" value="<?php if($view){ echo $data->homework_id;}?>">
							<input type="hidden" id="status" name="status" value="<?php if($view){ echo $data->status;}?>">    
							<input type="hidden" id="student_id" name="student_id" value="<?php if($view){ echo $data->student_id;}?>">       		
					    <div class="mb-3 form-group row">
							<label class="col-sm-2 control-label col-form-label text-md-end " for="class_name"><?php esc_attr_e('Title','school-mgt');?></label>
							<div class="col-sm-8">
								<input id="title" class="form-control validate[required]"  value="<?php if($view){ echo $data->title;}?>" name="title" readonly>
							</div>
						</div>
						<div class="mb-3 form-group row">
							<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Subject','school-mgt');?></label>
							<div class="col-sm-8">
								<input id="subject" class="form-control validate[required]"  value="<?php if($view){ echo mj_smgt_get_single_subject_name($data->subject);}?>" name="subject" readonly>
							</div>
						</div>
						<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.1/tinymce.jquery.min.js"></script>
							<script>
							  
								  tinymce.init({
								  selector: 'textarea',
								   menubar:false,
								  toolbar: false,
								  readonly : 1
								   });
							</script>
				  
						<div class="mb-3 form-group row">
							<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Content','school-mgt');?></label>
							<div class="col-sm-8" id='conten'>
							<?php $str = $data->content; ?>
							
								<textarea id="content" class="form-control validate[required] w-100 h-200-px" value="" name="content" readonly><?php if($view){ echo '<pre>'.$str.'</pre>'; }?></textarea>
							 
							   <?php //wp_editor(isset($view)?stripslashes($str) : '','content'); ?>
							</div>
						</div>
						<div class="mb-3 form-group row">
							<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Submission Date','school-mgt');?></label>
							<div class="col-sm-8">
								<input id="submition_date" class="form-control validate[required]"  value="<?php if($view){ echo $data->submition_date;}?>" name="submition_date" readonly>
							</div>
						</div>
						<?php
						    if($data->status == 0)
						    {
								  ?>
								<div class="mb-3 form-group row">
									<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Upload Homework File','school-mgt');?></label>
									<div class="col-sm-8">
										<input id="file" type='file' class="form-control validate[required]"  value="<?php if($view){ echo $data->submition_date;}?>" name="file">
									</div>
								</div>		
								<div class="offset-sm-2 col-sm-8">        	
									<input type="submit" value="<?php  if($view) esc_attr_e('Save Homework','school-mgt');?>" name="Save_Homework" class="btn btn-success" />
								</div> 
								<?php 
							}
							else
							{?>
								<div class="offset-sm-2 col-sm-8">        	
									<label class="col-sm-6 control-label col-form-label text-md-end color_green" for="class_name"><?php esc_attr_e('HOMEWORK SUBMITTED !','school-mgt');?></label>
								</div> 
								<?php 
							}?>
				</form>
			</div>
		  </div> 
     <?php 
	}?>
    </div>
 </div>   
 <?php
	if(isset($_POST['save_homework_front']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_homework_front_nonce' ) )
		{
			$insert=new Smgt_Homework();
			if($_POST['action'] == 'edit')
			{
				if(isset($_FILES['homework_document']) && !empty($_FILES['homework_document']) && $_FILES['homework_document']['size'] !=0)
				{		
					if($_FILES['homework_document']['size'] > 0)
						$upload_docs1=mj_smgt_load_documets_new($_FILES['homework_document'],$_FILES['homework_document'],$_POST['document_name']);		
				}
				else
				{
					if(isset($_REQUEST['old_hidden_homework_document']))
					$upload_docs1=$_REQUEST['old_hidden_homework_document'];
				}
				 
				$document_data=array();
				if(!empty($upload_docs1))
				{
					$document_data[]=array('title'=>$_POST['document_name'],'value'=>$upload_docs1);
				}
				else
				{
					$document_data[]='';
				}
				
				$update_data=$insert->mj_smgt_add_homework($_POST,$document_data);
				if($update_data)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=homework&tab=homeworklist&updatesuccess=1');
					exit;
				}
			}
			else
			{
				$args = array( 'meta_query' => array( array( 'key' => 'class_name', 'value' => $_POST['class_name'], 'compare' => '=' ) ), 'count_total' => true ); 
				$users = new WP_User_Query($args); 
				if ($users->get_total() == 0)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_student_homewrok&tab=homeworklist&message=4');
				}
				else
				{
					if(isset($_FILES['homework_document']) && !empty($_FILES['homework_document']) && $_FILES['homework_document']['size'] !=0)
					{		
						if($_FILES['homework_document']['size'] > 0)
							$upload_docs1=mj_smgt_load_documets_new($_FILES['homework_document'],$_FILES['homework_document'],$_POST['document_name']);		
					}
					else
					{
						$upload_docs1='';
					}
					 
					$document_data=array();
					if(!empty($upload_docs1))
					{
						$document_data[]=array('title'=>$_POST['document_name'],'value'=>$upload_docs1);
					}
					else
					{
						$document_data[]='';
					}
					
					$insert_data=$insert->mj_smgt_add_homework($_POST,$document_data);
					if($insert_data)
					{
						wp_redirect ( home_url() . '?dashboard=user&page=homework&tab=homeworklist&addsuccess=1');
						exit;
					}
				}
			}
		}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$delete=new Smgt_Homework();
	    $dele=$delete->mj_smgt_get_delete_record($_REQUEST['homework_id']);
			if($dele)
			{
			header("Location: ?dashboard=user&page=homework&tab=homeworklist&deletesuccess=1");
			
			}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == "download")
	{
		$assign_id = $_REQUEST['stud_homework_id'];
		$homework_obj=new Smgt_Homework();
		$filedata = $homework_obj->mj_smgt_check_uploaded($assign_id);
		if($filedata != false)
		{
			$file = $filedata;
		}
		$upload = wp_upload_dir();
		$upload_dir_path = $upload['basedir'];
		$file = $upload_dir_path . '/homework_file/'.$file;
		if (file_exists($file)) 
		{
		   header('Content-Description: File Transfer');
		   header("Content-type: application/pdf",true,200);
		   header('Content-Disposition: attachment; filename='.basename($file));
		   header('Content-Transfer-Encoding: binary');
		   header('Expires: 0');
		   header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		   header('Pragma: public');
		   header('Content-Length: ' . filesize($file));
		   ob_clean();
		   flush();
		   readfile($file);
		   exit;	
		}	
	}
if($school_obj->role=='student' || $school_obj->role=='parent')
{
	if(isset($_POST['Save_Homework']))
    {
		$uploadfile=array('stu_homework_id'=>mj_smgt_onlyNumberSp_validation($_POST['stu_homework_id']),
		'homework_id'=>mj_smgt_onlyNumberSp_validation($_POST['homework_id']),
		'status'=>mj_smgt_onlyNumberSp_validation($_POST['status']),
		'title'=>mj_smgt_address_description_validation($_POST['title']),
		'subject'=>mj_smgt_address_description_validation($_POST['subject']),
		'content'=>$_POST['content'],
		'submition_date'=>$_POST['submition_date'],
		'upload_file'=>$_FILES['file']
				);
		if(!empty($uploadfile))
		{
			if(isset($_FILES['file']))
			{
				$randm = mt_rand(5,15);
		        $file_name = "H".$randm."_".$_FILES['file']['name'];
				$file_tmp =$_FILES['file']['tmp_name'];
				
				$upload = wp_upload_dir();
				$upload_dir_path = $upload['basedir'];
				$upload_dir = $upload_dir_path . '/homework_file';
				
				if (! is_dir($upload_dir)) 
				{
				   mkdir( $upload_dir, 0700 );
				}
				$up=move_uploaded_file($file_tmp,$upload_dir.'/'.$file_name);
				global $wpdb;
				$mj_smgt_student_homework = $wpdb->prefix."mj_smgt_student_homework";
				$stud_homework_id=$_POST['stu_homework_id'];
				$stud_id=$_POST['student_id'];
				$homework_id=$_POST['homework_id'];
				$status = 1 ;
				$uploaded_date=date("Y-m-d H:i:s");
				$result=$wpdb->update($mj_smgt_student_homework, array( 
				'homework_id' => $homework_id,	// string
				'student_id' => $stud_id,	// integer (number) 
				'status' => $status,
				'uploaded_date' => $uploaded_date,
				'file' => $file_name), 
					array( 'stu_homework_id' => $stud_homework_id ), 
					array( '%d','%d','%d','%s','%s'), 
					array( '%d' ));
				if($result)
				{
					header("Location: ?dashboard=user&page=homework&tab=homeworklist&success=1");
				}
			}
			else
			{
				echo "File Not Upload";
			}
		}
	}
}
?>