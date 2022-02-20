<!--Group POP up code -->
<div class="popup-bg">
	<div class="overlay-content admission_popup">
		<div class="modal-content">
			<div class="category_list">
			</div>     
		</div>
	</div>     
</div>
<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/add-exam.js'; ?>" ></script>

<?php
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$exam_data= mj_smgt_get_exam_by_id($_REQUEST['exam_id']);
	}
?>
    <div class="panel-body">	
        <form name="exam_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="exam_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
        <div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="exam_name"><?php esc_attr_e('Exam Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="exam_name" class="form-control validate[required,custom[popup_category_validation]]" maxlength="50" type="text" value="<?php if($edit){ echo $exam_data->exam_name;}?>"  placeholder="<?php esc_html_e('Enter Exam Name','school-mgt');?>"  name="exam_name">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for=" for="class_id"><?php esc_attr_e('Class Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="class_id" class="form-control validate[required] width_100" id="class_list">
					<option value=""><?php echo esc_attr_e( 'Select Class', 'school-mgt' ) ;?></option>
					<?php $classval='';
					if($edit){  
						$classval=$exam_data->class_id; 
						foreach(mj_smgt_get_allclass() as $class)
						{ ?>
						<option value="<?php echo $class['class_id'];?>" <?php selected($class['class_id'],$classval);  ?>>
						<?php echo mj_smgt_get_class_name($class['class_id']);?></option> 
					<?php }
					}else
					{
						foreach(mj_smgt_get_allclass() as $classdata)
						{ ?>
						<option value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$classval);  ?>><?php echo $classdata['class_name'];?></option> 
					<?php }
					}
					?>
				</select>
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for=" for="class_name"><?php esc_attr_e('Section Name','school-mgt');?></label>
			<div class="col-sm-8">
				<?php if($edit){ $sectionval=$exam_data->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
					<select name="class_section" class="form-control width_100" id="class_section">
						<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
						<?php
						if($edit){
							foreach(mj_smgt_get_class_sections($exam_data->class_id) as $sectiondata)
							{  ?>
							 <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
						<?php }
						}?>
					</select>
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for=" for="Exam Term"><?php esc_attr_e('Exam Term','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-md-8 col-sm-8 col-xs-12">	
				<?php if($edit){ $sectionval1=$exam_data->exam_term; }elseif(isset($_POST['exam_term'])){$sectionval1=$_POST['exam_term'];}else{$sectionval1='';}?>
				<select class="form-control validate[required] term_category margin_top_10 width_100" name="exam_term">
					<option value=""><?php esc_html_e('Select Term','school-mgt');?></option>
					<?php 
					$activity_category=mj_smgt_get_all_category('term_category');
					if(!empty($activity_category))
					{
						foreach ($activity_category as $retrive_data)
						{ 		 	
						?>
							<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$sectionval1);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>
						<?php }
					} 
					?> 
				</select>			
			</div>
			<div class="col-md-2 col-sm-2 col-xs-12">
				<button id="addremove_cat" class="btn btn-info sibling_add_remove margin_top_10" model="term_category"><?php esc_attr_e('Add','school-mgt');?></button>		
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="Passing Marks"><?php esc_attr_e('Passing Marks','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-3">
				<input id="passing_mark" class="form-control text-input onlyletter_number_space_validation validate[required]" type="number" value="<?php if($edit){ echo $exam_data->passing_mark;}?>" placeholder="<?php esc_html_e('Enter Passing Marks','school-mgt');?>"  name="passing_mark">
			</div>
			<label class="col-sm-2 control-label col-form-label text-md-end" for="Total Marks"><?php esc_attr_e('Total Marks','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-3">
				<input id="total_mark" class="form-control validate[required] onlyletter_number_space_validation text-input" type="number" value="<?php if($edit){ echo $exam_data->total_mark;}?>" placeholder="<?php esc_html_e('Enter Total Marks','school-mgt');?>"  name="total_mark">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for=" for="exam_start_date"><?php esc_attr_e('Exam Start Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-3">
				<input id="exam_start_date" class="datepicker form-control validate[required] text-input" type="text" placeholder="<?php esc_html_e('Enter Exam Start Date','school-mgt');?>" name="exam_start_date" value="<?php if($edit){ echo date("Y-m-d",strtotime($exam_data->exam_start_date)); }?>" readonly>
			</div>
			<label class="col-sm-2 control-label col-form-label text-md-end" for=" for="exam_end_date"><?php esc_attr_e('Exam End Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-3">
				<input id="exam_end_date" class="datepicker form-control validate[required] text-input" type="text" placeholder="<?php esc_html_e('Enter Exam End Date','school-mgt');?>" name="exam_end_date" value="<?php if($edit){ echo date("Y-m-d",strtotime($exam_data->exam_end_date)); }?>" readonly>
			</div>
		</div>
		<?php wp_nonce_field( 'save_exam_admin_nonce' ); ?>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for=" for="exam_comment"><?php esc_attr_e('Exam Comment','school-mgt');?></label>
			<div class="col-sm-8">
			 <textarea name="exam_comment" class="form-control validate[custom[address_description_validation]]" placeholder="<?php esc_html_e('Enter Exam Comment','school-mgt');?>" maxlength="150" id="exam_comment"><?php if($edit){ echo $exam_data->exam_comment;}?></textarea>
				
			</div>
		</div>
		<?php
		if($edit)
			{ 
				$doc_data=json_decode($exam_data->exam_syllabus);
			?>
				<div class="mb-3 form-group row">	
						<label class="control-label col-form-label text-md-end col-md-2 col-sm-2 col-xs-12" for="Exam Syllabu"><?php esc_attr_e('Exam Syllabus','school-mgt');?></label>		
						<div class="col-md-2 col-sm-2 col-xs-12 margin_bottom_5">
							<input type="text"  name="document_name" id="title_value" placeholder="<?php esc_html_e('Enter Documents Title','school-mgt');?>" value="<?php if(!empty($doc_data[0]->title)) { echo esc_attr($doc_data[0]->title);}elseif(isset($_POST['document_name'])) echo esc_attr($_POST['document_name']);?>"  class="form-control validate[custom[onlyLetter_specialcharacter],maxSize[50]] margin_cause"/>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12">		
							<input type="file" name="exam_syllabus" class="form-control file_validation input-file"/>						
							<input type="hidden" name="old_hidden_exam_syllabus" value="<?php if(!empty($doc_data[0]->value)){ echo esc_attr($doc_data[0]->value);}elseif(isset($_POST['exam_syllabus'])) echo esc_attr($_POST['exam_syllabus']);?>">					
						</div>
						<?php
						if(!empty($doc_data[0]->value))
						{
						?>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<a target="blank"  class="status_read btn btn-default" href="<?php print content_url().'/uploads/document_upload/'.$doc_data[0]->value; ?>" record_id="<?php echo $exam_data->exam_id;?>">
							<i class="fa fa-download"></i><?php echo $doc_data[0]->value;?></a>
						</div>
							<?php
						}
					?>
					</div>
			<?php 
			}
			else 
			{
			?>
		<div class="mb-3 form-group row">
			<label class="control-label col-form-label text-md-end col-md-2 col-sm-2 col-xs-12" for="Exam Syllabu"><?php esc_attr_e('Exam Syllabus','school-mgt');?></label>	
			<div class="col-md-4 col-sm-4 col-xs-12 margin_bottom_5">
				<input type="text"  name="document_name" id="title_value"  placeholder="<?php esc_html_e('Enter Documents Title','school-mgt');?>"  class="form-control validate[custom[onlyLetter_specialcharacter],maxSize[50]] margin_cause"/>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<input type="file" name="exam_syllabus" class="col-md-2 col-sm-2 col-xs-12 form-control file_validation input-file ">	
			</div>
		</div>
			<?php 
			}
			?>
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" id="save_exam" value="<?php if($edit){ esc_attr_e('Save Exam','school-mgt'); }else{ esc_attr_e('Add Exam','school-mgt');}?>" name="save_exam" class="btn btn-success" />
        </div>        
        </form>