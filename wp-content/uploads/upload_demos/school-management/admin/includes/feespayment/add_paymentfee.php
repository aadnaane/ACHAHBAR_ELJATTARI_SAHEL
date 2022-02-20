<?php 	
	if($active_tab == 'addpaymentfee')
	{
        $fees_pay_id=0;
		if(isset($_REQUEST['fees_pay_id']))
			$fees_pay_id=$_REQUEST['fees_pay_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result = $obj_feespayment->mj_smgt_get_single_fee_mj_smgt_payment($fees_pay_id);
		}
		?>
<div class="panel-body">
    <form name="expense_form" action="" method="post" class="form-horizontal" id="expense_form">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
        <input type="hidden" name="action" value="<?php echo $action;?>">
        <input type="hidden" name="fees_pay_id" value="<?php echo $fees_pay_id;?>">
        <input type="hidden" name="invoice_type" value="expense">
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end" for="class_id"><?php esc_attr_e('Class','school-mgt');?><span
                    class="require-field">*</span></label>
            <div class="col-sm-8">
                <?php
				if($edit){ $classval=$result->class_id; }else{$classval='';}?>
                <select name="class_id" id="class_list" class="form-control validate[required] load_fees max_width_100">
                    <?php if($addparent){ 
					 		$classdata=mj_smgt_get_class_by_id($student->class_name);
						?>
                    <option value="<?php echo $student->class_name;?>"><?php echo $classdata->class_name;?></option>
                    <?php }?>
                    <option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
                    <?php
								foreach(mj_smgt_get_allclass() as $classdata)
								{ ?>
                    <option value="<?php echo $classdata['class_id'];?>"
                        <?php selected($classval, $classdata['class_id']);  ?>><?php echo $classdata['class_name'];?>
                    </option>
                    <?php }?>
                </select>
            </div>
        </div>
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
                    <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>>
                        <?php echo $sectiondata->section_name;?></option>
                    <?php } 
							}?>
                </select>
            </div>
        </div>
        <?php if($edit){ ?>
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end" for="student_list"><?php esc_attr_e('Student','school-mgt');?><span
                    class="require-field">*</span></label>
            <div class="col-sm-8">
                <?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
                <select name="student_id" id="student_list" class="form-control validate[required] max_width_100">
                    <option value=""><?php esc_attr_e('Select student','school-mgt');?></option>
                    <?php 
						if($edit)
						{
							echo '<option value="'.$result->student_id.'" '.selected($result->student_id,$result->student_id).'>'.mj_smgt_get_user_name_byid($result->student_id).'</option>';
						}
					?>
                </select>
            </div>
        </div>
        <?php }
		else{
			?>
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end" for="student_list"><?php esc_attr_e('Student','school-mgt');?></label>
            <div class="col-sm-8">
                <?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
                <select name="student_id" id="student_list" class="form-control max_width_100">
                    <option value=""><?php esc_attr_e('Select Student','school-mgt');?></option>
                    <?php 
								if($edit)
								{
									echo '<option value="'.$result->student_id.'" '.selected($result->student_id,$result->student_id).'>'.mj_smgt_get_user_name_byid($result->student_id).'</option>';
								}
							?>
                </select>
                <p><i><?php 
						esc_attr_e('Note : Please select a student to generate invoice for the single student or it will create the invoice for all students for selected class and section.','school-mgt');
						?></i>
                </p>
            </div>
        </div>
        <?php
		}
	
		?>
        <?php wp_nonce_field( 'save_payment_fees_admin_nonce' ); ?>
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end" for="category_data"><?php esc_attr_e('Fee Type','school-mgt');?><span
                    class="require-field">*</span></label>
				<div class="col-sm-8">
					<select name="fees_id[]" multiple="multiple" id="fees_data" class="form-control validate[required] max_width_100">
						<?php 	
						if($edit)
						{
                            $fees_id=explode(',',$result->fees_id);
							foreach($fees_id as $id)
							{
								echo '<option value="'.$id.'" '.selected($id,$id).'>'.mj_smgt_get_fees_term_name($id).'</option>';
							}
						}
						?>
					</select>
           	 </div>
        </div>
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end"
                for="fees_amount"><?php esc_attr_e('Amount','school-mgt');?>(<?php echo mj_smgt_get_currency_symbol();?>)<span
                    class="require-field">*</span></label>
            <div class="col-sm-8">
                <input id="fees_amount" class="form-control validate[required,min[0],maxSize[8]] text-input" type="tax"
                    value="<?php if($edit){ echo $result->total_amount;}elseif(isset($_POST['fees_amount'])) echo $_POST['fees_amount'];?>"
                    name="fees_amount" readonly>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end" for="description"><?php esc_attr_e('Description','school-mgt');?></label>
            <div class="col-sm-8">
                <textarea name="description" class="form-control validate[custom[address_description_validation]]"
                    maxlength="150"> <?php if($edit){ echo $result->description;}elseif(isset($_POST['description'])) echo $_POST['description'];?> </textarea>
            </div>
        </div>
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end" for="start_year"><?php esc_attr_e('Year','school-mgt');?><span
                    class="require-field">*</span></label>
            <div class="col-sm-4">
                <select name="start_year" id="start_year" class="form-control validate[required]">
                    <option value=""><?php esc_attr_e('Starting year','school-mgt');?></option>
                    <?php 
					$start_year = 0;
					$x = 00;
					if($edit)
					$start_year = $result->start_year;
					for($i=2000 ;$i<2030;$i++)
					{
						echo '<option value="'.$i.'" '.selected($start_year,$i).' id="'.$x.'">'.$i.'</option>';
						$x++;
					} ?>
                </select>
            </div>
            <div class="col-sm-4">
                <select name="end_year" id="end_year" class="form-control validate[required] margin_top_10_res">
                    <option value=""><?php esc_attr_e('Ending year','school-mgt');?></option>
                    <?php 
					$end_year = '';
					if($edit)
						$end_year = $result->end_year;
						for($i=00 ;$i<31;$i++)
						{
							echo '<option value="'.$i.'" '.selected($end_year,$i).'>'.$i.'</option>';
						}
					?>
                </select>
            </div>
        </div>
        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end"
                for="smgt_enable_feesalert_mail"><?php esc_attr_e('Enable Send  Mail To Parents','school-mgt');?></label>
            <div class="col-sm-8">
                <div class="checkbox">
                    <label><input type="checkbox" class="margin_right_checkbox margin_right_5px_checkbox margin_right_checkbox_css" name="smgt_enable_feesalert_mail" value="1"
                            <?php echo checked(get_option('smgt_enable_feesalert_mail'),'yes');?> /><?php esc_attr_e('Enable','school-mgt');?>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-sm-2 control-label col-form-label text-md-end" for="smgt_enable_feesalert_sms"><?php esc_attr_e('Enable Send SMS To Parents','school-mgt');?></label>
            <div class="col-sm-8">
                <div class="checkbox">
                    <label><input type="checkbox" class="margin_right_checkbox margin_right_5px_checkbox margin_right_checkbox_css"					name="smgt_enable_feesalert_sms"  value="1" <?php echo checked(get_option('smgt_enable_feesalert_sms'),'yes');?>/><?php esc_attr_e('Enable','school-mgt');?>
                    </label>
                </div>
            </div>
        </div>                
        <div class="offset-sm-2 col-sm-8">
            <input type="submit"
                value="<?php if($edit){ esc_attr_e('Save Invoice','school-mgt'); }else{ esc_attr_e('Create Invoice','school-mgt');}?>"
                name="save_feetype_payment" class="btn btn-success" />
        </div>
    </form>
</div>
<?php  } ?>