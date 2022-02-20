<?php
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$post = get_post($_REQUEST['notice_id']);
	}
?>
    <div class="panel-body"> 
	<form name="class_form" action="" method="post" class="form-horizontal" id="notice_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
        <div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="notice_title"><?php esc_attr_e('Notice Title','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="notice_title" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $post->post_title;}?>" name="notice_title">
				 <input type="hidden" name="notice_id"   value="<?php if($edit){ echo $post->ID;}?>"/> 
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="notice_content"><?php esc_attr_e('Notice Comment','school-mgt');?></label>
			<div class="col-sm-8">
			<textarea name="notice_content" class="form-control validate[custom[address_description_validation]]" maxlength="150" id="notice_content"><?php if($edit){ echo $post->post_content;}?></textarea>
				
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="notice_content"><?php esc_attr_e('Notice Start Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<input id="notice_Start_date" class="datepicker form-control validate[required] text-input" type="text" value="<?php if($edit){ echo date("Y-m-d",strtotime(get_post_meta($post->ID,'start_date',true)));}?>" name="start_date" readonly>
				
			</div>
		</div>
		<?php wp_nonce_field( 'save_notice_admin_nonce' ); ?>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="notice_content"><?php esc_attr_e('Notice End Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<input id="notice_end_date" class="datepicker form-control validate[required] text-input" type="text" value="<?php if($edit){ echo date("Y-m-d",strtotime(get_post_meta($post->ID,'end_date',true)));}?>" name="end_date" readonly>
				
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end " for="notice_for"><?php esc_attr_e('Notice For','school-mgt');?></label>
			<div class="col-sm-8">
				<select name="notice_for" id="notice_for" class="form-control notice_for_ajax max_width_100">
				   <option value = "all"><?php esc_attr_e('All','school-mgt');?></option>
				   <option value="teacher" <?php if($edit) echo selected(get_post_meta( $post->ID, 'notice_for',true),'teacher');?>><?php esc_attr_e('Teacher','school-mgt');?></option>
				   <option value="student" <?php if($edit) echo selected(get_post_meta( $post->ID, 'notice_for',true),'student');?>><?php esc_attr_e('Student','school-mgt');?></option>
				   <option value="parent" <?php if($edit) echo selected(get_post_meta( $post->ID, 'notice_for',true),'parent');?>><?php esc_attr_e('Parent','school-mgt');?></option>
				   <option value="supportstaff" <?php if($edit) echo selected(get_post_meta( $post->ID, 'notice_for',true),'supportstaff');?>><?php esc_attr_e('Support Staff','school-mgt');?></option>
				</select>				
			</div>
		</div>
		
		<div id="smgt_select_class">
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?></label>
			<div class="col-sm-8">
			<?php if($edit){ $classval=get_post_meta( $post->ID, 'smgt_class_id',true); }elseif(isset($_POST['class_id'])){$classval=$_POST['class_id'];}else{$classval='';}?>
				 <select name="class_id"  id="class_list" class="form-control max_width_100">
                	<option value="all"><?php esc_attr_e('All','school-mgt');?></option>
                    <?php
					  foreach(mj_smgt_get_allclass() as $classdata)
					  {  
					  ?>
					   <option  value="<?php echo $classdata['class_id'];?>" <?php echo selected($classval,$classdata['class_id']);?>><?php echo $classdata['class_name'];?></option>
				 <?php }?>
                </select>
			</div>
		</div>
		</div>
		<div class="form-group row mb-3" id="smgt_select_section">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
			<div class="col-sm-8">
				<?php if($edit){ $sectionval=get_post_meta( $post->ID, 'smgt_section_id',true); }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
				<select name="class_section" class="form-control max_width_100" id="class_section">
					<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
					<?php
					if($edit){
						foreach(mj_smgt_get_class_sections($classval) as $sectiondata)
						{  ?>
						 <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
					<?php } 
					}?>
				</select>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end " for="enable"><?php esc_attr_e('Send Mail','school-mgt');?></label>
			<div class="col-sm-8">
				 <div class="checkbox">
				 	<label>
  						<input id="chk_sms_sent_mail" type="checkbox" <?php $smgt_mail_service_enable = 0;if($smgt_mail_service_enable) echo "checked";?> value="1" name="smgt_mail_service_enable">
  					</label>
  				</div>				 
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end " for="enable"><?php esc_attr_e('Send SMS','school-mgt');?></label>
			<div class="col-sm-8">
				 <div class="checkbox">
				 	<label>
  						<input id="chk_sms_sent" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="smgt_sms_service_enable">
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
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Notice','school-mgt'); }else{ esc_attr_e('Add Notice','school-mgt');}?>" name="save_notice" class="btn btn-success" />
        </div>
    </form>
    </div>
