<?php
$obj_hostel=new smgt_hostel;
 ?>
<!--Group POP up code -->
<div class="popup-bg">
	<div class="overlay-content admission_popup">
		<div class="modal-content">
			<div class="category_list">
			</div>     
		</div>
	</div>     
</div>
	<?php 
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_bed')
		{
			$edit=1;
			$bed_data=$obj_hostel->mj_smgt_get_bed_by_id($_REQUEST['bed_id']);
		}
		?>
       
		<div class="panel-body">
        <form name="bed_form" action="" method="post" class="form-horizontal" id="bed_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="bed_id" value="<?php if($edit){ echo $bed_data->id;}?>"/> 
         <div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="bed_unique_id"><?php esc_attr_e('Bed Unique ID','school-mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="bed_unique_id" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo $bed_data->bed_unique_id; } else { echo mj_smgt_generate_bed_code(); } ?>"  name="bed_unique_id" readonly>		
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="room_id"><?php esc_attr_e('Room Unique ID','school-mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="room_id" class="form-control validate[required] width_100" id="room_id">
					<option value=""><?php echo esc_attr_e( 'Select Room Unique ID', 'school-mgt' ) ;?></option>
					<?php $roomval='';
					$room_data=$obj_hostel->mj_smgt_get_all_room();
					if($edit){  
						$roomval=$bed_data->room_id; 
						foreach($room_data as $room)
						{ ?>
						<option value="<?php echo $room->id;?>" <?php selected($room->id,$roomval);  ?>>
						<?php echo $room->room_unique_id;?></option> 
					<?php }
					}else
					{
						foreach($room_data as $room)
						{ ?>
						<option value="<?php echo $room->id;?>" <?php selected($room->id,$roomval);  ?>><?php echo $room->room_unique_id;?></option> 
					<?php }
					}
					?>
				</select>
			</div>
		</div>
		<?php wp_nonce_field( 'save_bed_admin_nonce' ); ?>
		 
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="bed_description"><?php esc_attr_e('Description','school-mgt');?></label>
			<div class="col-sm-8">
				<textarea name="bed_description" id="bed_description" maxlength="150" class="form-control validate[custom[address_description_validation]]"><?php if($edit){ echo $bed_data->bed_description;}?></textarea>		
			</div>
		</div>
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Bed','school-mgt'); }else{ esc_attr_e('Add Bed','school-mgt');}?>" name="save_bed" class="btn btn-success" />
        </div>
       
        </form>
        </div>