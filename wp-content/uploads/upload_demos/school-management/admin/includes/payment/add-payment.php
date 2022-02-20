<script type="text/javascript">
$(document).ready(function() {
	$('#payment_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
} );
</script>
<?php
$edit = 0;
if (isset ( $_REQUEST ['action'] ) && $_REQUEST ['action'] == 'edit')
{	
  $edit = 1;
  $payment_data = mj_smgt_get_payment_by_id($_REQUEST['payment_id']);
} 
?>
  
<div class="panel-body">
<form name="payment_form" action="" method="post" class="form-horizontal" id="payment_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="payment_title"><?php esc_attr_e('Title','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="payment_title" class="form-control validate[required,custom[popup_category_validation]]" maxlength="50" type="text" value="<?php if($edit){ echo $payment_data->payment_title;}?>" name="payment_title"/>
				<input
				type="hidden" name="payment_id"
				value="<?php if($edit){ echo $payment_data->payment_id;}?>" />
				
			</div>
		</div>
				<div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_id"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php
				if($edit){ $classval=$payment_data->class_id; }else{$classval='';}?>
                     <select name="class_id" id="class_list" class="form-control validate[required] max_width_100">
                     <?php if($addparent){ 
					 		$classdata=mj_smgt_get_class_by_id($student->class_name);
						?>
                     <option value="<?php echo $student->class_name;?>" ><?php echo $classdata->class_name;?></option>
                     <?php }?>
                    	<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
                            <?php
								foreach(mj_smgt_get_allclass() as $classdata)
								{ ?>
								 <option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
						   <?php }?>
                     </select>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
			<div class="col-sm-8">
				<?php if($edit){ $sectionval=$payment_data->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
                        <select name="class_section" class="form-control max_width_100" id="class_section">
                        	<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
                            <?php
							if($edit){
								foreach(mj_smgt_get_class_sections($payment_data->class_id) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php } 
							}?>
                        </select>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="student_list"><?php esc_attr_e('Student','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php if($edit){ $classval=$payment_data->class_id; }else{$classval='';}?>
                     
                     <select name="student_id" id="student_list" class="form-control validate[required] max_width_100">
                    
                     <?php if(isset($payment_data->student_id)){ 
						$student=get_userdata($payment_data->student_id);
					 ?>
                     <option value="<?php echo $payment_data->student_id;?>" ><?php echo $student->first_name." ".$student->last_name;?></option>
                     <?php }
							else
							{?>
                    	<option value=""><?php esc_attr_e('Select student','school-mgt');?></option>
							<?php } ?>
                     </select>
			</div>
		</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="amount"><?php esc_attr_e('Amount','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="amount" class="form-control validate[required,min[0],maxSize[12]]" type="number" step="0.01" value="<?php if($edit){ echo $payment_data->amount;}?>" name="amount">
			</div>
		</div>
		<?php wp_nonce_field( 'save_payment_admin_nonce' ); ?>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="payment_status"><?php esc_attr_e('Status','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="payment_status" id="payment_status" class="form-control max_width_100">
					<option value="Paid"
						<?php if($edit)selected('Paid',$payment_data->payment_status);?> class="validate[required]"><?php esc_attr_e('Paid','school-mgt');?></option>
					<option value="Part Paid"
						<?php if($edit)selected('Part Paid',$payment_data->payment_status);?> class="validate[required]"><?php esc_attr_e('Part Paid','school-mgt');?></option>
						<option value="Unpaid"
						<?php if($edit)selected('Unpaid',$payment_data->payment_status);?> class="validate[required]"><?php esc_attr_e('Unpaid','school-mgt');?></option>
			</select>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="description"><?php esc_attr_e('Description','school-mgt');?></label>
			<div class="col-sm-8">
				<textarea name="description" id="description" class="form-control validate[custom[address_description_validation]]" maxlength="150"><?php if($edit){ echo $payment_data->description;}?></textarea>
			</div>
		</div>
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Payment','school-mgt'); }else{ esc_attr_e('Add Payment','school-mgt');}?>" name="save_payment" class="btn btn-success" />
        </div>
	

</form>
</div>

<?php

?>