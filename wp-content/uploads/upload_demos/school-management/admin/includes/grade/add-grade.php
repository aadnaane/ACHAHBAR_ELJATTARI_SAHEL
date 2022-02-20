<?php  
$edit=0;
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
{
	 
	$edit=1;
	$grade_data= mj_smgt_get_grade_by_id($_REQUEST['grade_id']);
}
?>
<div class="panel-body">	
        <form name="grade_form" action="" method="post" class="form-horizontal" id="grade_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
        <div class="form-group row mb-3">
			<label class="col-sm-2 control-label  col-form-label text-md-end" for="grade_name"><?php esc_attr_e('Grade Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="grade_name" class="form-control validate[required,custom[address_description_validation]]" type="text" value="<?php if($edit){ echo $grade_data->grade_name;}?>" maxlength="50" name="grade_name">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label  col-form-label text-md-end" for="grade_point"><?php esc_attr_e('Grade Point','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="grade_point" class="form-control validate[required,custom[onlyNumberSp],maxSize[3],max[100]] text-input" type="number" value="<?php if($edit){ echo $grade_data->grade_point;}?>" name="grade_point">
			</div>
		</div>
		<?php wp_nonce_field( 'save_grade_admin_nonce' ); ?>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label  col-form-label text-md-end" for="mark_from "><?php esc_attr_e('Mark From','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="mark_from" class="form-control validate[required,custom[onlyNumberSp],maxSize[3],max[100]] text-input" type="number" value="<?php if($edit){ echo $grade_data->mark_from;}?>" name="mark_from">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label  col-form-label text-md-end" for="mark_upto"><?php esc_attr_e('Mark Upto','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="mark_upto" class="form-control validate[required,custom[onlyNumberSp],maxSize[3],max[100]] text-input" type="number" value="<?php if($edit){ echo $grade_data->mark_upto;}?>" name="mark_upto">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label  col-form-label text-md-end" for="grade_comment"><?php esc_attr_e('Comment','school-mgt');?></label>
			<div class="col-sm-8">
			<textarea name="grade_comment" class="form-control validate[custom[address_description_validation]]" maxlength="150" id="grade_comment"><?php if($edit){ echo $grade_data->grade_comment;}?></textarea>
				
			</div>
		</div>
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Grade','school-mgt'); }else{ esc_attr_e('Add Grade','school-mgt');}?>" name="save_grade" class="btn btn-success" />
        </div>
       
        </form>
</div>
<?php
?>