<div class="mailbox-content overflow-hidden">

<form name="class_form" action="" method="post" class="form-horizontal" id="notification_form">
   
    
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="sms_template"><?php esc_attr_e('Select Class','school-mgt');?></label>
			<div class="col-sm-8">
			
				 <select name="class_id"  id="notification_class_list_id" class="form-control max_width_100">
                	<option value="All"><?php esc_attr_e('All','school-mgt');?></option>
                    <?php
					  foreach(mj_smgt_get_allclass() as $classdata)
					  {  
					  ?>
					   <option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
				 <?php }?>
                </select>
			</div>
		</div>
		
			<div class="form-group row mb-3 notification_class_section_id">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
			<div class="col-sm-8">
                        <select name="class_section" class="form-control max_width_100" id="notification_class_section_id">
                        	<option value="All"><?php esc_attr_e('All','school-mgt');?></option>
                        </select>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end"><?php esc_attr_e('Select Users','school-mgt');?></label>
			<div class="col-sm-8">
			<span class="notification_user_display_block">
				<select name="selected_users" id="notification_selected_users" class="form-control max_width_100">
				<option value="All"><?php esc_attr_e('All','school-mgt');?></option>				
				</select>
			</span>
			</div>
		</div>
		<?php wp_nonce_field( 'save_notice_admin_nonce' ); ?>
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end" for="subject"><?php esc_attr_e('Title','school-mgt');?><span class="require-field">*</span></label>
            <div class="col-sm-8">
				<input id="title" class="form-control validate[required,custom[popup_category_validation]] text-input" type="text" maxlength="50" name="title" >
            </div>
		</div>
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end" for="message"><?php esc_attr_e('Message','school-mgt');?><span class="require-field">*</span></label>
            <div class="col-sm-8">
                <textarea name="message_body" id="message_body" maxlength="150" class="form-control validate[required,custom[address_description_validation]] text-input"></textarea>
            </div>
		</div>
											
        <div class="form-group row mb-3">
            <div class="col-sm-10">
                <div class="pull-right">
                    <input type="submit" value="<?php esc_attr_e('Save Notification','school-mgt') ?>" name="save_notification" class="btn btn-success"/>
                </div>
            </div>
        </div>       
    </form>
</div>