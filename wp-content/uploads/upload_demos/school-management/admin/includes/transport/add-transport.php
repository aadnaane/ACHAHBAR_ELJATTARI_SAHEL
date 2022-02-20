<div class="add_transport">		
		<?php  
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$transport_data= mj_smgt_get_transport_by_id($_REQUEST['transport_id']);
			}
		?>
        
		<div class="panel-body">
        <form name="transport_form" action="" method="post" class="form-horizontal" id="transport_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
        <div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="route_name"><?php esc_attr_e('Route Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="route_name" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="50" value="<?php if($edit){ echo $transport_data->route_name;}?>" name="route_name">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="number_of_vehicle"><?php esc_attr_e('Vehicle Identifier','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="number_of_vehicle" class="form-control validate[required,custom[onlyNumberSp]]" maxlength="15" type="text" value="<?php if($edit){ echo $transport_data->number_of_vehicle;}?>" name="number_of_vehicle">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="vehicle_reg_num"><?php esc_attr_e('Vehicle Registration Number','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="vehicle_reg_num" class="form-control validate[required,custom[address_description_validation]]" maxlength="50" type="text" value="<?php if($edit){ echo $transport_data->vehicle_reg_num;}?>" name="vehicle_reg_num">
			</div>
		</div>
		<?php wp_nonce_field( 'save_transpoat_admin_nonce' ); ?>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="driver_name"><?php esc_attr_e('Driver Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="driver_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]]" maxlength="50" type="text" value="<?php if($edit){ echo $transport_data->driver_name;}?>" name="driver_name">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="driver_phone_num"><?php esc_attr_e('Driver Phone Number','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="driver_phone_num" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]]" type="text" value="<?php if($edit){ echo $transport_data->driver_phone_num;}?>" name="driver_phone_num">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="driver_address"><?php esc_attr_e('Driver Address','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<textarea name="driver_address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" id="driver_address"><?php if($edit){ echo $transport_data->driver_address;}?></textarea>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="driver_address"><?php esc_attr_e('Image','school-mgt');?></label>
			<div class="col-sm-8">
				 <input type="text" id="smgt_user_avatar_url" name="smgt_user_avatar" value="<?php if($edit)echo esc_url( $transport_data->smgt_user_avatar ); ?>" readonly />
       				 <input id="upload_user_avatar_button" type="button" class="button btn_top" value="<?php esc_attr_e( 'Upload image', 'school-mgt' ); ?>" />
       				 <span class="description"><?php esc_attr_e('Upload image', 'school-mgt' ); ?></span>
                     
                     <div id="upload_user_avatar_preview">
                     <?php if($edit) 
	                     	{
	                     	if($transport_data->smgt_user_avatar == "")
	                     	{
	                     		?><img alt="" class="image_preview_css" src="<?php echo get_option( 'smgt_driver_thumb' ) ?>"><?php 
	                     	}
	                     	else {
	                     		?>
	                     	
					        <img class="image_preview_css" src="<?php if($edit)echo esc_url( $transport_data->smgt_user_avatar ); ?>" />
					        <?php 
	                     	}
	                     	}
					        else {
					        	?>
					        	<img alt="" class="image_preview_css" src="<?php echo get_option( 'smgt_driver_thumb' ) ?>">
					        	<?php 
					        }?>  
        
    				</div>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="route_description"><?php esc_attr_e('Description','school-mgt');?></label>
			<div class="col-sm-8">
				 <textarea name="route_description" class="form-control validate[custom[address_description_validation]]" maxlength="150" id="route_description"><?php if($edit){ echo $transport_data->route_description;}?></textarea>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="route_fare"><?php esc_attr_e('Route Fare','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="route_fare" class="form-control validate[required,custom[onlyNumberSp],min[0],maxSize[10]]" type="text" value="<?php if($edit){ echo $transport_data->route_fare;}?>" name="route_fare">
			</div>
		</div>
		<div class="offset-sm-2 col-sm-8">
        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Transport','school-mgt'); }else{ esc_attr_e('Add Transport','school-mgt');}?>" name="save_transport" class="btn btn-success"/>
        </div>
       	
        </form>
        </div>
    </div>