<?php  
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$holiday_data= mj_smgt_get_holiday_by_id($_REQUEST['holiday_id']);
	}
?>
<div class="panel-body">
    <form name="holiday_form" action="" method="post" class="form-horizontal" id="holiday_form">
       <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
        <div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="holiday_title"><?php esc_attr_e('Holiday Title','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="holiday_title" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $holiday_data->holiday_title;}?>" name="holiday_title">
				<input type="hidden" name="holiday_id"   value="<?php if($edit){ echo $holiday_data->holiday_id;}?>"/> 
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="description"><?php esc_attr_e('Description','school-mgt');?></label>
			<div class="col-sm-8">
				<input id="holiday_title" class="form-control validate[custom[address_description_validation]]" maxlength="150" type="text" value="<?php if($edit){ echo $holiday_data->description;}?>" name="description">				
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="date"><?php esc_attr_e('Start Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="date" class="datepicker form-control validate[required] text-input" type="text" value="<?php if($edit){ echo date("Y-m-d",strtotime($holiday_data->date)); }?>" name="date" readonly>				
			</div>
		</div>
		<?php wp_nonce_field( 'save_holiday_admin_nonce' ); ?>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="date"><?php esc_attr_e('End Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="end_date" class="datepicker form-control validate[required] text-input" type="text" value="<?php if($edit){ echo date("Y-m-d",strtotime($holiday_data->end_date));}?>" name="end_date" readonly>				
			</div>
		</div>
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Holiday','school-mgt'); }else{ esc_attr_e('Add Holiday','school-mgt');}?>" name="save_holiday" class="btn btn-success" />
        </div>        
    </form>
</div>