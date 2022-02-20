<div class="mailbox-content">
	<h2><?php 
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		echo esc_html( esc_attr__( 'Edit Message', 'school-mgt') );
		$edit=1;
		 $exam_data= mj_smgt_get_exam_by_id($_REQUEST['exam_id']);
	}
	?></h2>     
	

<form name="class_form" action="" method="post" class="form-horizontal" id="message_form" enctype="multipart/form-data">
    <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
	<input type="hidden" name="action" value="<?php echo $action;?>">
    <div class="form-group row mb-3">
        <label class="col-sm-2 control-label col-form-label text-md-end" for="to"><?php esc_attr_e('Message To','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="receiver" class="form-control validate[required] text-input min_width_100" id="send_to">						
					<option value="student"><?php esc_attr_e('Students','school-mgt');?></option>	
					<option value="teacher"><?php esc_attr_e('Teachers','school-mgt');?></option>	
					<option value="parent"><?php esc_attr_e('Parents','school-mgt');?></option>	
					<option value="supportstaff"><?php esc_attr_e('Support Staff','school-mgt');?></option>	
				</select>
			</div>	
    </div>
	<div class="form-group row mb-3 class_selection">
        <label class="col-sm-2 control-label col-form-label text-md-end" for="to"><?php esc_attr_e('Class Selection Type','school-mgt');?></label>
		<div class="col-sm-8">
			<select name="class_selection_type" class="form-control text-input class_selection_type min_width_100">						
				<option value="single"><?php esc_attr_e('Single','school-mgt');?></option>	
				<option value="multiple"><?php esc_attr_e('Multiple','school-mgt');?></option>	
			</select>
		</div>	
    </div>
	<div class="form-group row mb-3 multiple_class_div">
		<label class="col-sm-2 control-label col-form-label text-md-end" ><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
		<div class="col-sm-8 multiselect_validation1">			
			 <select name="multi_class_id[]" class="form-control" id="selected_class" multiple="multiple">
				<?php
				  foreach(mj_smgt_get_allclass() as $classdata)
				  {  
					?>
						<option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
					<?php 
				  }
				?>
			</select>
		</div>
	</div>
    <div id="smgt_select_class" class="single_class_div">
		<div class="form-group row mb-3 class_list_id">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?></label>
			<div class="col-sm-8">			
				 <select name="class_id"  id="class_list_id" class="form-control min_width_100">
                	<option value=""><?php esc_attr_e('All','school-mgt');?></option>
                    <?php
					  foreach(mj_smgt_get_allclass() as $classdata)
					  {  
					  ?>
					   <option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
				 <?php }?>
                </select>
			</div>
		</div>
	</div>
		
	<div class="form-group row mb-3 class_section_id">
		<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
		<div class="col-sm-8">
			<?php if(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
				<select name="class_section" class="form-control min_width_100" id="class_section_id">
					<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
					<?php
					if($edit){
						foreach(mj_smgt_get_class_sections($user_info->class_name) as $sectiondata)
						{  ?>
						 <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
					<?php } 
					}?>
				</select>
		</div>
	</div>
	<div class="form-group row mb-3 single_class_div support_staff_user_div">
		<div id="messahe_test"></div>
		<label class="col-sm-2 control-label col-form-label text-md-end"><?php esc_attr_e('Select Users','school-mgt');?></label>
		<div class="col-sm-8">
		<span class="user_display_block">
			<select name="selected_users[]" id="selected_users" class="form-control min_width_250px" multiple="true">					
				<?php 
				$student_list = mj_smgt_get_all_student_list();
				foreach($student_list  as $retrive_data)
				{
					echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->display_name.'</option>';
				}
				?>
			</select>
		</span>
		</div>
	</div>
		<div id="class_student_list"></div>
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end" for="subject"><?php esc_attr_e('Subject','school-mgt');?><span class="require-field">*</span></label>
            <div class="col-sm-8">
				<input id="subject" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" name="subject" >
            </div>
		</div>
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end" for="subject"><?php esc_attr_e('Message Comment','school-mgt');?><span class="require-field">*</span></label>
            <div class="col-sm-8">
                <textarea name="message_body" id="message_body" maxlength="150" class="form-control validate[required,custom[address_description_validation]] text-input"></textarea>
            </div>
		</div>
		<div class="attachment_div">
			<div class="form-group row mb-3">
				<label class="col-sm-2 control-label col-form-label text-md-end" for="photo"><?php esc_attr_e('Attachment ','school-mgt');?></label>
				<div class="col-sm-4">
				 <input  class="btn_top input-file" name="message_attachment[]" type="file" />
				</div>										
			</div>							
       	</div>	
		<div class="form-group row mb-3">		
			<div class="offset-sm-2 col-sm-10">
				<input type="button" value="<?php esc_attr_e('Add More Attachment','school-mgt') ?>"  onclick="add_new_attachment()" class="btn more_attachment btn-default">
			</div>	
		</div>									
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end " for="enable"><?php esc_attr_e('Send SMS','school-mgt');?></label>
			<div class="col-sm-8">
				 <div class="checkbox">
				 	<label>
  						<input id="chk_sms_sent" type="checkbox"  value="1" name="smgt_sms_service_enable">
  					</label>
  				</div>
			</div>
		</div>
		<div id="hmsg_message_sent" class="hmsg_message_none">
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="sms_template"><?php esc_attr_e('SMS Text','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea name="sms_template" class="form-control validate[required]" maxlength="160"></textarea>
				<label><?php esc_attr_e('Max. 160 Character','school-mgt');?></label>
			</div>
		</div>
		</div>			
        <div class="form-group row mb-3">
            <div class="col-sm-10 offset-sm-2">
                <div class="pull-right">
                    <input type="submit" value="<?php if($edit){ esc_attr_e('Save Message','school-mgt'); }else{ esc_attr_e('Send Message','school-mgt');}?>" name="save_message" class="btn btn-success save_message_selected_user"/>
                </div>
            </div>
        </div>       
    </form>
</div>