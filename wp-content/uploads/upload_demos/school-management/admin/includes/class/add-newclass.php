	 <script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/class.js'; ?>" ></script>
        <?php 
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$classdata= mj_smgt_get_class_by_id($_REQUEST['class_id']);
			} 
		?>
       
    <div class="panel-body">	
        <form name="class_form" action="" method="post" class="form-horizontal" id="class_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
        <div class="form-group row mb-3">
			<label class="pt-2 col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="class_name" class="form-control validate[required,custom[popup_category_validation]]" maxlength="50" type="text" value="<?php if($edit){ echo $classdata->class_name;}?>" name="class_name">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="pt-2 col-sm-2 control-label col-form-label text-md-end" for="class_num_name"><?php esc_attr_e('Numeric  Class Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="class_num_name" class="form-control validate[required,min[0],maxSize[4]] text-input" oninput="this.value = Math.abs(this.value)"  type="number" value="<?php if($edit){ echo $classdata->class_num_name;}?>" name="class_num_name" >
			</div>
		</div>
        <?php wp_nonce_field( 'save_class_admin_nonce' ); ?>		
		
		<div class="form-group row mb-3">
			<label class="pt-2 col-sm-2 control-label col-form-label text-md-end" for="class_capacity"><?php esc_attr_e('Student Capacity In Section','school-mgt');?> </label>
			<div class="col-sm-8">
				<input id="class_capacity" oninput="this.value = Math.abs(this.value)" class="form-control validate[min[0],maxSize[4]]" type="number" value="<?php if($edit){ echo $classdata->class_capacity;}?>" name="class_capacity">
			</div>
		</div>
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Class','school-mgt'); }else{ esc_attr_e('Add Class','school-mgt');}?>" name="save_class" class="btn btn-success" />
        </div>        
        </form>
    </div>