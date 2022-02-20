<?php 	
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{	
	$edit=1;
	$subject=mj_smgt_get_subject($_REQUEST['subject_id']);
}
?>
        <div class="panel-body">
        <form name="student_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="subject_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_name"><?php esc_attr_e('Subject Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-2">
				<input id="subject_code"class="form-control validate[required,custom[onlyNumberSp],maxSize[8],min[0]] text-input" placeholder="<?php esc_html_e('Enter Subject Code','school-mgt');?>" type="text" maxlength="50" value="<?php if($edit){ echo $subject->subject_code;}?>" name="subject_code">
			</div>
			<div class="col-sm-6">
				<input id="subject_name" class="form-control validate[required,custom[address_description_validation]] margin_top_10_res" type="text" maxlength="50" value="<?php if($edit){ echo $subject->sub_name;}?>" placeholder="<?php esc_html_e('Enter Subject Name','school-mgt');?>" name="subject_name">
			</div>
		</div>
		
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_class"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
						 
				        <select name="subject_class" class="form-control validate[required] width_100 class_by_teacher" id="class_list">
                            <option value=""><?php echo esc_attr_e( 'Select Class', 'school-mgt' ) ;?></option>
                            <?php $classval='';
                            if($edit){  
                                $classval=$subject->class_id; 
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
		<?php wp_nonce_field( 'save_subject_admin_nonce' ); ?>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
			<div class="col-sm-8">
				<?php if($edit){ $sectionval=$subject->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
                        <select name="class_section" class="form-control width_100" id="class_section">
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
		<div class="form-group row mb-3">
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
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_edition"><?php esc_attr_e('Edition','school-mgt');?></label>
			<div class="col-sm-8">
				<input id="subject_edition" class="form-control validate[custom[address_description_validation]]"  placeholder="<?php esc_html_e('Enter Subject Edition','school-mgt');?>" maxlength="50" type="text" value="<?php if($edit){ echo $subject->edition;}?>" name="subject_edition">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_author"><?php esc_attr_e('Author Name','school-mgt');?></label>
			<div class="col-sm-8">
				<input id="subject_author" class="form-control validate[custom[onlyLetter_specialcharacter]]" placeholder="<?php esc_html_e('Enter Subject Author Name','school-mgt');?>" maxlength="100" type="text" value="<?php if($edit){ echo $subject->author_name;}?>" name="subject_author">
			</div>
		</div>
		<?php
		if($edit)
		{
			$syllabus=$subject->syllabus;
		?>	
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_syllabus"><?php esc_attr_e('Syllabus','school-mgt');?></label>
			<div class="col-sm-8">
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
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="subject_syllabus"><?php esc_attr_e('Syllabus','school-mgt');?></label>
			<div class="col-sm-8">
				 <input type="file" accept=".pdf" name="subject_syllabus"  id="subject_syllabus"/>				 
                   <p class="help-block"><?php esc_attr_e('Upload syllabus in PDF','school-mgt');?></p>     
			</div>
		</div>
		<?php
		}
		?>

		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end " for="enable"><?php esc_attr_e('Send Mail','school-mgt');?></label>
			<div class="col-sm-8">
				 <div class="checkbox">
				 	<label>
  						<input id="chk_subject_mail" type="checkbox" <?php $smgt_mail_service_enable = 0;if($smgt_mail_service_enable) echo "checked";?> value="1" name="smgt_mail_service_enable">
  					</label>
  				</div>				 
			</div>
		</div>

		<div class="offset-sm-2 col-sm-8">
        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Subject','school-mgt'); }else{ esc_attr_e('Add Subject','school-mgt');}?>" name="subject" class="btn btn-success teacher_for_alert"/>
        </div>
            	
        
        </form>
		</div>
<?php

?>