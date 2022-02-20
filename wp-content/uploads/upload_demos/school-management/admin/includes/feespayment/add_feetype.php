<?php 	
	if($active_tab == 'addfeetype')
	{
        $fees_id=0;
		if(isset($_REQUEST['fees_id']))
			$fees_id=$_REQUEST['fees_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_fees->mj_smgt_get_single_feetype_data($fees_id);
		} ?>
		
    <div class="panel-body">
        <form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="fees_id" value="<?php echo $fees_id;?>">
		<input type="hidden" name="invoice_type" value="expense">
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end fees_type_label" for="category_data"><?php esc_attr_e('Fee Type','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select class="form-control validate[required] smgt_feetype margin_top_10 max_width_100" name="fees_title_id" id="category_data">
						<option value=""><?php esc_attr_e('Select Fee Type','school-mgt');?></option>
						<?php 
						$activity_category=mj_smgt_get_all_category('smgt_feetype');
						if(!empty($activity_category))
						{
							if($edit)
							{
								$fees_val=$result->fees_title_id; 
							}
							else
							{
								$fees_val=''; 
							}
						
							foreach ($activity_category as $retrive_data)
							{ 		 	
							?>
								<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$fees_val);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>
							<?php }
						} 
						?> 
					</select>			
			</div>
			<div class="col-sm-2">
				<button id="addremove_cat" class="btn btn-info margin_top_10" model="smgt_feetype"><?php esc_attr_e('Add','school-mgt');?></button>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php $classval = 0;
				if($edit)
				$classval = $result->class_id;?>
				<select name="class_id" class="form-control validate[required] max_width_100" id="class_list">
					<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
					<?php
						foreach(mj_smgt_get_allclass() as $classdata)
						{  
						?>
						 <option value="<?php echo $classdata['class_id'];?>" <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?></option>
					<?php }?>
				</select>
			</div>
		</div>
		<?php wp_nonce_field( 'save_fees_type_admin_nonce' ); ?>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
			<div class="col-sm-8">
				<?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
				<select name="class_section" class="form-control max_width_100" id="class_section">
					<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
					<?php
					if($edit){
						foreach(mj_smgt_get_class_sections($result->class_id) as $sectiondata)
						{  ?>
						 <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
					<?php } 
					}?>
				</select>
			</div>
		</div>
		
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="fees_amount"><?php esc_attr_e('Amount','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="fees_amount" class="form-control validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="<?php if($edit){ echo $result->fees_amount;}elseif(isset($_POST['fees_amount'])) echo $_POST['fees_amount'];?>" name="fees_amount">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="description"><?php esc_attr_e('Description','school-mgt');?></label>
			<div class="col-sm-8">
				<textarea name="description" class="form-control validate[custom[address_description_validation]]" maxlength="150"> <?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?> </textarea>				
			</div>
		</div>
		
		<div class="offset-sm-2 col-sm-8">
        	 <input type="submit" value="<?php if($edit){ esc_attr_e('Save Fee Type','school-mgt'); }else{ esc_attr_e('Create Fee Type','school-mgt');}?>" name="save_feetype" class="btn btn-success"/>
        </div>
    </form>
</div>

<?php } ?>