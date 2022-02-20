<?php $obj_invoice= new Smgtinvoice(); ?>
<?php 	
	if($active_tab == 'addincome')
	{
        $income_id=0;
		if(isset($_REQUEST['income_id']))
			$income_id=$_REQUEST['income_id'];
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
				$edit=1;
				$result = $obj_invoice->mj_smgt_get_income_data($income_id);			
			} ?>
			
<script type="text/javascript">
$(document).ready(function() {
	$('#income_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#invoice_date').datepicker({
		dateFormat: "yy-mm-dd",
		  changeMonth: true,
	        changeYear: true,
	        dateFormat: 'yy-mm-dd',
	        yearRange:'-65:+25',
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			},
	        onChangeMonthYear: function(year, month, inst) {
	            $(this).val(month + "/" + year);
	        }
	});
});
</script>
		
       <div class="panel-body">
        <form name="income_form" action="" method="post" class="form-horizontal" id="income_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="income_id" value="<?php echo $income_id;?>">
		<input type="hidden" name="invoice_type" value="income">
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_id"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php
				if($edit){ $classval=$result->class_id; }else{$classval='';}?>
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
						   <?php } ?>
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
								<option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
						<?php } 
							} ?>
                </select>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="student_list"><?php esc_attr_e('Student','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>                     
                     <select name="supplier_name" id="student_list" class="form-control validate[required] max_width_100">                    
                     <?php if(isset($result->supplier_name)){ 
						$student=get_userdata($result->supplier_name);
					 ?>
                     <option value="<?php echo $result->supplier_name;?>" ><?php echo $student->first_name." ".$student->last_name;?></option>
                     <?php }
							else
							{ ?>
                    	<option value=""><?php esc_attr_e('Select student','school-mgt');?></option>
							<?php } ?>
                     </select>
			</div>
		</div>	
		
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="payment_status"><?php esc_attr_e('Status','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="payment_status" id="payment_status" class="form-control validate[required] max_width_100">
					<option value="Paid"
						<?php if($edit)selected('Paid',$result->payment_status);?> ><?php esc_attr_e('Paid','school-mgt');?></option>
					<option value="Part Paid"
						<?php if($edit)selected('Part Paid',$result->payment_status);?>><?php esc_attr_e('Part Paid','school-mgt');?></option>
						<option value="Unpaid"
						<?php if($edit)selected('Unpaid',$result->payment_status);?>><?php esc_attr_e('Unpaid','school-mgt');?></option>
			</select>
			</div>
		</div>
		<?php wp_nonce_field( 'save_income_fees_admin_nonce' ); ?>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="invoice_date"><?php esc_attr_e('Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="invoice_date" class="form-control " type="text"  value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($result->income_create_date);}elseif(isset($_POST['invoice_date'])){ echo mj_smgt_getdate_in_input_box($_POST['invoice_date']);}else{ echo date("Y-m-d");}?>" name="invoice_date" readonly>
			</div>
		</div>
		<hr>
		<div id="income_entry_main">
		<?php 			
			if($edit)
			{
				$all_entry=json_decode($result->entry);
			}
			else
			{
				if(isset($_POST['income_entry']))
				{					
					$all_data=$obj_invoice->mj_smgt_get_entry_records($_POST);
					$all_entry=json_decode($all_data);
				}					
			}
			if(!empty($all_entry))
			{
				$i=0;
				foreach($all_entry as $entry)
				{
					?>
					<div id="income_entry">
						<div class="form-group row mb-3 income_fld">
						<label class="col-sm-2 control-label col-form-label text-md-end" for="income_entry"><?php esc_attr_e('Income Entry','school-mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2">
							<input id="income_amount" class="form-control validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="<?php echo $entry->amount;?>" name="income_amount[]">
						</div>
						<div class="col-sm-4">
							<input id="income_entry" class="form-control btn_top validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php echo $entry->entry;?>" name="income_entry[]">
						</div>
						<?php
						if($i > 0 )
						{ 
					     ?>
						<div class="col-sm-2">
						<button type="button" class="btn btn-default btn_top" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php esc_attr_e('Delete','school-mgt');?></i>
						</button>
						</div>
						<?php 
						} ?>
						</div>	
					</div>
			<?php 
			$i++;
			}				
			}
			else
			{?>
					<div id="income_entry">
						<div class="form-group row mb-3 income_fld">
						<label class="col-sm-2 control-label col-form-label text-md-end" for="income_entry"><?php esc_attr_e('Income Entry','school-mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2">
							<input id="income_amount" class="form-control btn_top validate[required,min[0],maxSize[8]] text-input" type="number" step="0.01" value="" name="income_amount[]" placeholder="<?php esc_attr_e('Income Amount','school-mgt');?>">
						</div>
						<div class="col-sm-4">
							<input id="income_entry" class="form-control btn_top validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="" name="income_entry[]" placeholder="<?php esc_attr_e('Income Entry Label','school-mgt');?>">
						</div>						
						<div class="col-sm-2">
						<button type="button" class="btn btn-default btn_top" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php esc_attr_e('Delete','school-mgt');?></i>
						</button>
						</div>
						</div>	
					</div>
					
		<?php } ?>
		</div>
		
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="income_entry"></label>
			<div class="col-sm-3">
				
				<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php esc_attr_e('Add Income Entry','school-mgt'); ?>
				</button>
			</div>
		</div>
		<hr>
		<div class="offset-sm-2 col-sm-8">
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Income','school-mgt'); }else{ esc_attr_e('Create Income Entry','school-mgt');}?>" name="save_income" class="btn btn-success"/>
        </div>
        </form>
        </div>
	 <script>
		// CREATING BLANK INVOICE ENTRY
		var blank_income_entry ='';
		$(document).ready(function() 
		{
		blank_income_entry = '<div class="form-group row mb-3 income_fld"><label class="col-sm-2 control-label text-md-end" for="income_entry"><?php esc_attr_e('Income Entry','school-mgt');?><span class="require-field">*</span></label><div class="col-sm-2"><input id="income_amount" class="form-control validate[required,min[0],maxSize[8]] text-input"  type="number" step="0.01" value="" placeholder="<?php esc_attr_e("Income Amount","school-mgt");?>" name="income_amount[]"></div><div class="col-sm-4"><input id="income_entry" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_attr_e("Income Entry Label","school-mgt");?>" maxlength="50" type="text" value="" name="income_entry[]"></div><div class="col-sm-2"><button type="button" class="btn btn-default" onclick="deleteParentElement(this)"><i class="entypo-trash"><?php esc_attr_e('Delete','school-mgt');?></i></button></div></div>';			
		}); 

		function add_entry()
		{
			
			$("#income_entry_main").append(blank_income_entry);		
		}
		// REMOVING INVOICE ENTRY
		function deleteParentElement(n)
		{
			"use strict";
			alert("<?php esc_html_e('Do you really want to delete this ?','school-mgt');?>");
			n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		}
	</script> 
	   <?php 
	 }
 ?>