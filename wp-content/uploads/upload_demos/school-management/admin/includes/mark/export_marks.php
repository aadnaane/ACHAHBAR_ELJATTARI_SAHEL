<div class="mt-4 panel-body p"> 
    <form method="post" id="select_data">  
   
		<div class="row">
			<div class="form-group col-md-3">
					<label for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
				<select name="class_id"  id="class_list" class="form-control validate[required] class_id_exam text-input">
								<option value=" "><?php esc_attr_e('Select Class Name','school-mgt');?></option>
								<?php
						foreach(mj_smgt_get_allclass() as $classdata)
						{
						  ?>
						   <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
					 <?php
						} ?>
						</select>
			</div>
			<div class="form-group col-md-3">
				<label for="class_id"><?php esc_attr_e('Select Section','school-mgt');?></label>
				<?php
				$class_section="";
				if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
					<select name="class_section" class="form-control section_id_exam" id="class_section">
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
			<div class="form-group col-md-3">
				<label for="exam_id"><?php esc_attr_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
			
				<select name="exam_id" class="form-control validate[required] text-input exam_list">
					<option value=""><?php esc_attr_e('Select Exam','school-mgt');?></option>
				</select>
			</div>
				<?php	
				$school_obj = new School_Management ( get_current_user_id () );
				if($school_obj->role == 'teacher' || $school_obj->role == 'supportstaff')
				{
			if($user_access['add'] == '1')
			{
				$access = 1;
			}
			else
			{
				$access = 0;
			}
				}
				else
				{
			$access = 1;
				}		
				
				if($access == 1)
				{
			?>
			<div class="form-group col-md-3 button-possition">
				<label for="subject_id">&nbsp;</label>
				<input type="submit" value="<?php esc_attr_e('Export Marks','school-mgt');?>" name="export_marks"  class="btn btn-info"/>
			</div>
			<?php
				}
				?>
		</div>
      </form>
	  </div>		
<?php

?>