<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
	$('#exam_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	 $("#exam_start_date").datepicker({
        dateFormat: "yy-mm-dd",
		minDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $("#exam_end_date").datepicker("option", "minDate", dt);
        }
    });
    $("#exam_end_date").datepicker({
       dateFormat: "yy-mm-dd",
	   minDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $("#exam_start_date").datepicker("option", "maxDate", dt);
        }
    });
	jQuery("body").on("change", ".input-file[type=file]", function ()
	{ 
		var file = this.files[0]; 
		var file_id = jQuery(this).attr('id'); 
		var ext = $(this).val().split('.').pop().toLowerCase(); 
		//Extension Check 
		if($.inArray(ext, ['pdf']) == -1)
		{
			  alert(language_translate2.pdf_alert);
			$(this).replaceWith('<input type="file" name="exam_syllabus" class="form-control file_validation input-file">');
			return false; 
		} 
		 //File Size Check 
		 if (file.size > 20480000) 
		 {
			alert(language_translate2.large_file_Size_alert);
			$(this).replaceWith('<input type="file" name="exam_syllabus" class="form-control file_validation input-file">'); 
			return false; 
		 }
	 });

	jQuery('.onlyletter_number_space_validation').on('keypress',function(e) 
	{     
		var regex = new RegExp("^[0-9a-zA-Z \b]+$");
		var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);
		if (!regex.test(key)) 
		{
			event.preventDefault();
			return false;
		} 
   });

	var table =  jQuery('#exam_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
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

	$('#exam_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#exam_form2').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('.width_200').DataTable({
		responsive: true,
		bPaginate: false,
		bFilter: false, 
		bInfo: false,
	});

	$( "#save_exam_time" ).on("click",function(e) {
		var subject_data = $("#subject_data").val();
		var suj = JSON.parse(subject_data);
		var productIds = [];
		 jQuery.each( suj, function( i, val ) {
			
			var exdt = $("#exam_date_"+val.subid).val();
			
			var strh = $("#start_time_"+val.subid).val();
			var strm = $("#start_min_"+val.subid).val();
			var strap = $("#start_ampm_"+val.subid).val();
			
			var endh = $("#end_time_"+val.subid).val();
			var endm = $("#end_min_"+val.subid).val();
			var endap = $("#end_ampm_"+val.subid).val();
			
			var exsdtfull = exdt+strh+strm+strap;
			var exedtfull = exdt+endh+endm+endap;
			
			if ($.inArray(exsdtfull, productIds) == -1) {
				productIds.push(exsdtfull);
			}
			/* else{
				alert(language_translate2.more_then_exam_date_time);
				e.preventDefault(e);
			} */
			if ($.inArray(exedtfull, productIds) == -1) {
				productIds.push(exedtfull);
			}
			/* else{
				alert(language_translate2.more_then_exam_date_time);
				e.preventDefault(e);
			} */
			
			var strfull = strh+":"+strm+" "+strap;
			var endfull = endh+":"+endm+" "+endap;
			 
            var st = Converttimeformat(strfull);
            var et = Converttimeformat(endfull);
			 
            if (st >= et) {
				alert('Subject '+val.sub_name+' '+'End time must be greater than start time.');
				e.preventDefault(e);
            }
 
		});  
	});
	function Converttimeformat(strfull) {
		var hrs = Number(strfull.match(/^(\d+)/)[1]);
		var mnts = Number(strfull.match(/:(\d+)/)[1]);
		var format = strfull.match(/\s(.*)$/)[1];
		if (format == "pm" && hrs < 12) hrs = hrs + 12;
		if (format == "am" && hrs == 12) hrs = hrs - 12;
		var hours = hrs.toString();
		var minutes = mnts.toString();
		if (hrs < 10) hours = "0" + hours;
		if (mnts < 10) minutes = "0" + minutes;
		return hours + ":" + minutes;
	}

	$('#exam_timelist').DataTable({
		responsive: true,
		bPaginate: false,
		bFilter: false, 
		bInfo: false,
		language:<?php echo mj_smgt_datatable_multi_language();?>
	});
	$('.exam_table').DataTable({
		responsive: true,
		bPaginate: false,
		bFilter: false, 
		bInfo: false,
	});

});
</script>
 <?php
	$tablename="exam";
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=mj_smgt_delete_exam($tablename,$_REQUEST['exam_id']);
		if($result){
			wp_redirect ( admin_url().'admin.php?page=smgt_exam&tab=examlist&message=3');
		}
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
		{
			$result=mj_smgt_delete_exam($tablename,$id);
		}
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_exam&tab=examlist&message=3');
		}
	}
	//-----------SAVE EXAM -------------------------//
	if(isset($_POST['save_exam']))
	{
        $nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_exam_admin_nonce' ) )
		{
			$created_date = date("Y-m-d H:i:s");
			$examdata=array('exam_name'=>mj_smgt_popup_category_validation($_POST['exam_name']),
				'class_id'=>$_POST['class_id'],
				'section_id'=>$_POST['class_section'],
				'exam_term'=>$_POST['exam_term'],
				'passing_mark'=>$_POST['passing_mark'],
				'total_mark'=>$_POST['total_mark'],
				'exam_start_date'=>date('Y-m-d', strtotime($_POST['exam_start_date'])),
				'exam_end_date'=>date('Y-m-d', strtotime($_POST['exam_end_date'])),
				'exam_comment'=>mj_smgt_address_description_validation($_POST['exam_comment']),					
				'exam_creater_id'=>get_current_user_id(),
				'created_date'=>$created_date						
			);		
			 
			if ($_POST['passing_mark'] >= $_POST['total_mark'])
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_exam&tab=examlist&message=6');
			}
			else
			{
				//table name without prefix
				$tablename="exam";
				if($_REQUEST['action']=='edit')
				{
					if(isset($_FILES['exam_syllabus']) && !empty($_FILES['exam_syllabus']) && $_FILES['exam_syllabus']['size'] !=0)
					{		
						if($_FILES['exam_syllabus']['size'] > 0)
							$upload_docs1=mj_smgt_load_documets_new($_FILES['exam_syllabus'],$_FILES['exam_syllabus'],$_POST['document_name']);		
					}
					else
					{
						if(isset($_REQUEST['old_hidden_exam_syllabus']))
						$upload_docs1=$_REQUEST['old_hidden_exam_syllabus'];
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
					 	
					$grade_id=array('exam_id'=>$_REQUEST['exam_id']);
					$modified_date_date = date("Y-m-d H:i:s");
					$examdata['modified_date']=$modified_date_date;
					$examdata['exam_syllabus']=json_encode($document_data);
					$result=mj_smgt_update_record($tablename,$examdata,$grade_id);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=smgt_exam&tab=examlist&message=2');
					}
				}
				else
				{
					if(isset($_FILES['exam_syllabus']) && !empty($_FILES['exam_syllabus']) && $_FILES['exam_syllabus']['size'] !=0)
					{		
						if($_FILES['exam_syllabus']['size'] > 0)
							$upload_docs1=mj_smgt_load_documets_new($_FILES['exam_syllabus'],$_FILES['exam_syllabus'],$_POST['document_name']);		
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
					$examdata['exam_syllabus']=json_encode($document_data);
				 				
					$result=mj_smgt_insert_record($tablename,$examdata);
					if($result)
					{ 
						wp_redirect ( admin_url().'admin.php?page=smgt_exam&tab=examlist&message=1');
					}				
				}
			}
		}		
	}
	if(isset($_POST['save_exam_table']))
	{	
		$obj_exam=new smgt_exam;
		$class_id=	$_POST['class_id'];
		$section_id=$_POST['section_id'];
		$exam_id=$_POST['exam_id'];
		if(isset($_POST['section_id']) && $_POST['section_id'] !=0)
		{
			$subject_data=$obj_exam->mj_smgt_get_subject_by_section_id($class_id,$section_id);
		}
		else
		{ 
			$subject_data=$obj_exam->mj_smgt_get_subject_by_class_id($class_id);
		}
	 
		if(!empty($subject_data))
		{
			foreach($subject_data as $subject)
			{	
				if(isset($_POST['subject_name_'.$subject->subid]))
				{
					$save_data = $obj_exam->mj_smgt_insert_sub_wise_time_table($class_id,$exam_id,$subject->subid,$_POST['exam_date_'.$subject->subid],$_POST['start_time_'.$subject->subid],$_POST['start_min_'.$subject->subid],$_POST['start_ampm_'.$subject->subid],$_POST['end_time_'.$subject->subid],$_POST['end_min_'.$subject->subid],$_POST['end_ampm_'.$subject->subid]);
				}
			}
			if($save_data)
			{ 
				wp_redirect ( admin_url().'admin.php?page=smgt_exam&tab=exam_time_table&message=5');
			}
		}
		
	}  
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'examlist';
?>
<div class="page-inner">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div  id="main-wrapper" class="grade_page">
		<?php
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Exam Added Successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Exam Updated Successfully.','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('Exam Delete Successfully.','school-mgt');
				break;
			case '4':
				$message_string = esc_attr__('This File Type Is Not Allowed, Please Upload Only Pdf File.','school-mgt');
				break;
			case '5':
				$message_string = esc_attr__('Exam Time Table Successfully Save.','school-mgt');
				break;
			case '6':
				$message_string = esc_attr__('Enter Total Marks Greater than Passing Marks.','school-mgt');
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
					<a href="?page=smgt_exam&tab=examlist" class="nav-tab margin_bottom <?php echo $active_tab == 'examlist' ? 'nav-tab-active active' : ''; ?>">
					<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Exam List', 'school-mgt'); ?></a>
					<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{?>
				<a href="?page=smgt_exam&tab=addexam&action=edit&exam_id=<?php echo $_REQUEST['exam_id'];?>" class="nav-tab margin_bottom<?php echo $active_tab == 'addexam' ? 'nav-tab-active active' : ''; ?>">
					<?php esc_attr_e('Edit Exam', 'school-mgt'); ?></a>  
					<?php 
					}
					else
					{?>
					<a href="?page=smgt_exam&tab=addexam" class="nav-tab margin_bottom <?php echo $active_tab == 'addexam' ? 'nav-tab-active active' : ''; ?>">
					<?php echo '<span class="dashicons dashicons-plus-alt"></span>'. esc_attr__('Add Exam', 'school-mgt'); ?></a>  
					<?php } ?>
					<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
					{?>
				<a href="?page=smgt_exam&tab=exam_time_table" class="nav-tab margin_bottom<?php echo $active_tab == 'viewexam' ? 'nav-tab-active active' : ''; ?>">
					<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Exam Time Table', 'school-mgt'); ?></a>  
					<?php 
					}
					else
					{ ?>
					<a href="?page=smgt_exam&tab=exam_time_table" class="nav-tab margin_bottom <?php echo $active_tab == 'exam_time_table' ? 'nav-tab-active active' : ''; ?>">
					<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Exam Time Table', 'school-mgt'); ?></a>
					<?php
					}
					?>
				</h2>
				<?php
				
				if($active_tab == 'examlist')
				{	
				?>	
				<?php 
						$retrieve_class = mj_smgt_get_all_data($tablename);
					?>
					<div class="panel-body">
						<div class="table-responsive">
							<form id="frm-example" name="frm-example" method="post">	
								<table id="exam_list" class="display" cellspacing="0" width="100%">
									<thead>
										<tr>    
											<th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" 
											type="checkbox" /></th>   
											<th><?php esc_attr_e('Exam Title','school-mgt');?></th>
											<th><?php esc_attr_e('Class Name','school-mgt');?></th>
											<th><?php esc_attr_e('Section Name','school-mgt');?></th>
											<th><?php esc_attr_e('Term Name','school-mgt');?></th>
											<th><?php esc_attr_e('Exam Start Date','school-mgt');?></th>
											<th><?php esc_attr_e('Exam End Date','school-mgt');?></th>
											<th><?php esc_attr_e('Exam Comment','school-mgt');?></th>
											<th><?php esc_attr_e('Action','school-mgt');?></th>               
										</tr>
									</thead>
						
								<tfoot>
									<tr>
										<th></th>
										<th><?php esc_attr_e('Exam Title','school-mgt');?></th>
										<th><?php esc_attr_e('Class Name','school-mgt');?></th>
										<th><?php esc_attr_e('Section Name','school-mgt');?></th>
										<th><?php esc_attr_e('Term Name','school-mgt');?></th>
										<th><?php esc_attr_e('Exam Start Date','school-mgt');?></th>
										<th><?php esc_attr_e('Exam End Date','school-mgt');?></th>
										<th><?php esc_attr_e('Exam Comment','school-mgt');?></th>
										<th><?php esc_attr_e('Action','school-mgt');?></th>            
									</tr>
								</tfoot>
						
								<tbody>
								<?php 
									foreach ($retrieve_class as $retrieved_data){ 
									
								?>
									<tr>
										<td><input type="checkbox" class="select-checkbox" name="id[]" 
										value="<?php echo $retrieved_data->exam_id;?>"></td>
										<td><?php echo $retrieved_data->exam_name;?></td>
										<td><?php $cid=$retrieved_data->class_id;
										if(!empty($cid))
										{
											echo  $clasname=mj_smgt_get_class_name($cid);
										}
										else
										{
											echo  "";
										}
										?></td>
										<td><?php if($retrieved_data->section_id!=0){ echo mj_smgt_get_section_name($retrieved_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?></td>
										<td><?php 
										if(!empty($retrieved_data->exam_term))
										{
											echo get_the_title($retrieved_data->exam_term);
										}
										else
										{
											echo  "";
										}
										?></td>
										<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_start_date);?></td>
										<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_end_date);?></td>
										<td><?php echo $retrieved_data->exam_comment;?></td>              
									<td>
									<?php  
										$doc_data=json_decode($retrieved_data->exam_syllabus);
									?>
										<a href="?page=smgt_exam&tab=viewexam&action=view&exam_id=<?php echo $retrieved_data->exam_id;?>" class="btn btn-primary"><?php esc_attr_e('View','school-mgt');?></a>
									<a href="?page=smgt_exam&tab=addexam&action=edit&exam_id=<?php echo $retrieved_data->exam_id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
									<a href="?page=smgt_exam&tab=examlist&action=delete&exam_id=<?php echo $retrieved_data->exam_id;?>" class="btn btn-danger" 
									onclick="return confirm('<?php esc_attr_e('will be lost all data for this exam. Are you sure you want to delete this record?','school-mgt');?>');"><?php esc_attr_e('Delete','school-mgt');?></a>
									<?php
										if(!empty($doc_data[0]->value))
										{
										?>
										<a download href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>"  class="status_read btn btn-default" record_id="<?php echo $retrieved_data->exam_id;?>"><i class="fa fa-download"></i><?php esc_html_e(' Download Syllabus', 'school-mgt');?></a>
										
									<a target="blank" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" class="status_read btn btn-default" record_id="<?php echo $retrieved_data->exam_id;?>"><i class="fa fa-eye"></i><?php esc_html_e(' View Syllabus', 'school-mgt');?></a>
									<?php
										}
										?>
									</td>
									
									</tr>
									<?php } ?>
								</tbody>
								
								</table>
								<div class="print-button pull-left">
									<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected" class="btn btn-danger delete_selected"/>			
								</div>
							</form>
						</div>
					<?php 
					}
					if($active_tab == 'viewexam')
					{
						if($_REQUEST['action']=='view')
						{
							$exam_data= mj_smgt_get_exam_by_id($_REQUEST['exam_id']);
							$start_date=$exam_data->exam_start_date;
							$end_date=$exam_data->exam_end_date;
							$obj_exam=new smgt_exam;
							$exam_time_table=$obj_exam->mj_smgt_get_exam_time_table_by_exam($_REQUEST['exam_id']);
						}
					?>
					
					<div class="panel-body">
						<div class="form-group">
							<div class="col-md-12">
								<div class="row exam_table_res">
									<table class="table exam_table">
										<thead>
											<tr>
												<th  class="exam-fst-th"><?php  esc_attr_e('Exam','school-mgt');?></th>
												<th  class="exam-mdl-th"><?php  esc_attr_e('Class','school-mgt');?></th>							
												<th  class="exam-mdl-th"><?php  esc_attr_e('Section','school-mgt');?></th>							
												<th  class="exam-mdl-th"><?php  esc_attr_e('Term','school-mgt');?></th>							
												<th  class="exam-mdl-th"><?php  esc_attr_e('Start Date','school-mgt');?></th>							
												<th  class="exam-lst-th"><?php  esc_attr_e('End Date','school-mgt');?></th>							
											</tr>
										</thead>
										<tfoot></tfoot>
										<tbody>							
											<tr>
												<td class="cst-border"><?php echo $exam_data->exam_name;?></td>							
												<td class="cst-border"><?php echo mj_smgt_get_class_name($exam_data->class_id);?></td>
												<td class="cst-border"><?php if($exam_data->section_id!=0){ echo mj_smgt_get_section_name($exam_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?></td>
												<td class="cst-border"><?php echo get_the_title($exam_data->exam_term);?></td>
												<td class="cst-border"><?php echo mj_smgt_getdate_in_input_box($start_date);?></td>
												<td ><?php echo mj_smgt_getdate_in_input_box($end_date);?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>			
						</div>
						<div class="col-md-12 margin_top_40">
							<div class="row">
								<table id="exam_timelist" class="display exam_timelist_css" cellspacing="0" width="100%" >
									<thead>
										<tr>    
											<th class="exam-mdl-th"><?php esc_attr_e('Subject Code','school-mgt');?></th>
											<th class="exam-mdl-th"><?php esc_attr_e('Subject Name','school-mgt');?></th>
											<th class="exam-mdl-th"><?php esc_attr_e('Exam Date','school-mgt');?></th>
											<th class="exam-mdl-th"><?php esc_attr_e('Exam Start Time','school-mgt');?></th>
											<th class="exam-mdl-th"><?php esc_attr_e('Exam End Time','school-mgt');?></th>
										</tr>
									</thead>
									<tbody>
									<?php
										if(!empty($exam_time_table))
										{
											foreach($exam_time_table  as $retrieved_data)
											{
											?>
												<tr>
													<td class="cst-border"><?php echo mj_smgt_get_single_subject_code($retrieved_data->subject_id); ?> </td>
													<td class="cst-border"><?php echo mj_smgt_get_single_subject_name($retrieved_data->subject_id);  ?> </td>
													<td class="cst-border"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->exam_date); ?> </td>
													<?php
													$start_time_data = explode(":", $retrieved_data->start_time);
													$start_hour=str_pad($start_time_data[0],2,"0",STR_PAD_LEFT);
													$start_min=str_pad($start_time_data[1],2,"0",STR_PAD_LEFT);
													$start_am_pm=$start_time_data[2];
													$start_time=$start_hour.':'.$start_min.' '.$start_am_pm;
													
													$end_time_data = explode(":", $retrieved_data->end_time);
													$end_hour=str_pad($end_time_data[0],2,"0",STR_PAD_LEFT);
													$end_min=str_pad($end_time_data[1],2,"0",STR_PAD_LEFT);
													$end_am_pm=$end_time_data[2];
													$end_time=$end_hour.':'.$end_min.' '.$end_am_pm;
													?>
													<td class="cst-border"><?php echo $start_time;?> </td>
													<td class="cst-border"><?php echo $end_time; ?> </td>
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
					<?php
					}
					if($active_tab == 'addexam')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/exam/add-exam.php';
					}
					if($active_tab == 'exam_time_table')
					{
						require_once SMS_PLUGIN_DIR. '/admin/includes/exam/exam_time_table.php';
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>