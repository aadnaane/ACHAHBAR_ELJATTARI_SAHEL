<?php 
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$objj=new Smgt_Homework();
			$homeowrkdata= $objj->mj_smgt_get_edit_record($_REQUEST['homework_id']);
		} 
?>
<div class="mt-4 panel-body">	
        <form name="class_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="class_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Title','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="title" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="100" value="<?php if($edit){ echo $homeowrkdata->title;}?>" name="title">
					</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<?php if($edit){ $classval=$homeowrkdata->class_name; }elseif(isset($_POST['class_name'])){$classval=$_POST['class_name'];}else{$classval='';}?>
						<select name="class_name" class="form-control validate[required]" id="class_list">
							<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
							<?php
								foreach(mj_smgt_get_allclass() as $classdata)
								{ ?>
								<option value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$classval);  ?>><?php echo $classdata['class_name'];?></option> 
							<?php } ?>
						</select>
					</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
				<div class="col-sm-8">
					<?php if($edit){ $sectionval=$homeowrkdata->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
					<select name="class_section" class="form-control" id="class_section">
						<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
						<?php
						if($edit){
							foreach(mj_smgt_get_class_sections($homeowrkdata->class_name) as $sectiondata)
							{  ?>
							 <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
						<?php } 
						}?>
					</select>
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Select Subject','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<?php
					   $subject = ($edit)?mj_smgt_get_subject_by_classid($classval):array();
					   ?>
					<select name="subject_id" id="subject_list" class="form-control validate[required] text-input">
					   <?php
					    if($edit)
					    {
							foreach($subject as $record)
							{
								$select = ($record->subid == $homeowrkdata->subject)?"selected":"";
							 ?>
								<option value="<?php echo $record->subid;?>" <?php echo $select; ?>><?php echo $record->sub_name; ?></option>
							  <?php
							}
					    }
					    else
					    {
						   echo "<option>". esc_attr__('Select Subject','school-mgt') ."</option>";
					    }
					     ?>
					</select>
				</div>
			</div>
			<?php
			if($edit)
			{ 
				$doc_data=json_decode($homeowrkdata->homework_document);
			?>
				<div class="mb-3 form-group row">	
						<label class="control-label col-form-label text-md-end col-md-2 col-sm-2 col-xs-12" for="Exam Syllabu"><?php esc_attr_e('Homework Document','school-mgt');?></label>		
						<div class="col-md-2 col-sm-2 col-xs-12 margin_bottom_5">
							<input type="text"  name="document_name" id="title_value" placeholder="<?php esc_html__('Enter Documents Title','school-mgt');?>" value="<?php if(!empty($doc_data[0]->title)) { echo esc_attr($doc_data[0]->title);}elseif(isset($_POST['document_name'])) echo esc_attr($_POST['document_name']);?>"  class="form-control validate[custom[onlyLetter_specialcharacter],maxSize[50]] margin_cause"/>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12">		
							<input type="file" name="homework_document" class="form-control file_validation input-file"/>						
							<input type="hidden" name="old_hidden_homework_document" value="<?php if(!empty($doc_data[0]->value)){ echo esc_attr($doc_data[0]->value);}elseif(isset($_POST['homework_document'])) echo esc_attr($_POST['homework_document']);?>">					
						</div>
						<?php
						if(!empty($doc_data[0]->value))
						{
						?>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<a target="blank"  class="status_read btn btn-default" href="<?php print content_url().'/uploads/school_assets/'.$doc_data[0]->value; ?>" record_id="<?php echo $exam_data->exam_id;?>">
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
			<label class="control-label col-form-label text-md-end col-md-2 col-sm-2 col-xs-12" for="Exam Syllabu"><?php esc_attr_e('Homework Document','school-mgt');?></label>	
			<div class="col-md-4 col-sm-4 col-xs-12 margin_bottom_5">
				<input type="text"  name="document_name" id="title_value"  placeholder="<?php esc_html__('Enter Documents Title','school-mgt');?>"  class="form-control validate[custom[onlyLetter_specialcharacter],maxSize[50]] margin_cause"/>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<input type="file" name="homework_document" class="col-md-2 col-sm-2 col-xs-12 form-control file_validation input-file ">	
			</div>
		</div>
			<?php 
			}
			?>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.1/tinymce.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.1/tinymce.jquery.min.js"></script>
				<script>
					  tinymce.init({
					  selector: 'textarea',
					  menubar: false
					  });
				</script>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="class_capacity"><?php esc_attr_e('Content','school-mgt');?> </label>
				<div class="col-sm-8">
				<?php 
				$setting=array(
				'media_buttons' => true
				);
				if(!empty($classdata))
				{
					$content=$homeowrkdata->content;
					 
				}
				else
				{
					$content="";
				}
				wp_editor(isset($edit)?stripslashes($content) : '','content',$setting); ?>
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="class_capacity"><?php esc_attr_e('Submission Date','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-3">
					<input id="sdate" value="<?php if($edit){ echo $homeowrkdata->submition_date;}?>" class="datepicker form-control validate[required] text-input" type="text" value="" name="sdate" readonly>
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="smgt_enable_homework_mail"><?php esc_attr_e('Enable Send  Mail To Parents And Students','school-mgt');?></label>
					<div class="col-sm-8">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="smgt_enable_homework_mail"  value="1" <?php echo checked(get_option('smgt_enable_homework_mail'),'yes');?>/><?php esc_attr_e('Enable','school-mgt');?>
						    </label>
						</div>
					</div>
			</div>
			<?php wp_nonce_field( 'save_homework_front_nonce' ); ?>
			<div class="offset-sm-2 col-sm-8">        	
				<input type="submit" value="<?php if($edit){ esc_attr_e('Save Homework','school-mgt'); }else{ esc_attr_e('Save Homework','school-mgt');}?>" name="save_homework_front" class="btn btn-success" />
			</div>        
        </form>
</div>