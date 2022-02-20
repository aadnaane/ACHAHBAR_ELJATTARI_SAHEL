<?php
$obj_hostel=new smgt_hostel;
 ?>
	<?php 
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$hostel_data=$obj_hostel->mj_smgt_get_hostel_by_id($_REQUEST['hostel_id']);
		}
		?>
       
		<div class="panel-body">
        <form name="hostel_form" action="" method="post" class="form-horizontal" id="hostel_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="hostel_id" value="<?php if($edit){ echo $hostel_data->id;}?>"/> 
         <div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="hostel_name"><?php esc_attr_e('Hostel Name','school-mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="hostel_name" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hostel_data->hostel_name;}?>" name="hostel_name">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="hostel_type"><?php esc_attr_e('Hostel Type','school-mgt');?></label>
			<div class="col-sm-8">
				<input id="hostel_type" class="form-control validate[custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hostel_data->hostel_type;}?>" name="hostel_type">
			</div>
		</div>
		<?php wp_nonce_field( 'save_hostel_admin_nonce' ); ?>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="Description"><?php esc_attr_e('Description','school-mgt');?></label>
			<div class="col-sm-8">
				<textarea name="Description" id="Description" maxlength="150" class="form-control validate[custom[address_description_validation]]"><?php if($edit){ echo $hostel_data->Description;}?></textarea>
			</div>
		</div>
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Hostel','school-mgt'); }else{ esc_attr_e('Add Hostel','school-mgt');}?>" name="save_hostel" class="btn btn-success" />
        </div>
       
        </form>
        </div>