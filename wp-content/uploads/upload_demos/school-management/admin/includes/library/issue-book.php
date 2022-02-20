<?php $obj_lib = new Smgtlibrary();	?>
<?php
	$issuebook_id=0;
	if(isset($_REQUEST['issuebook_id']))
		$issuebook_id=$_REQUEST['issuebook_id'];
		$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
	{
		$edit=1;
		$result=$obj_lib->mj_smgt_get_single_issuebooks($issuebook_id);
	}
	?>
       
<div class="panel-body">	
    <form name="book_form" action="" method="post" class="form-horizontal" id="book_form">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="issue_id" value="<?php echo $issuebook_id;?>">
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_id"><?php esc_attr_e('Class','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
				<select name="class_id" id="class_list" class="form-control validate[required] max_width_100">
                    <option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
                        <?php
						foreach(mj_smgt_get_allclass() as $classdata)
						{ ?>
							<option value="<?php echo $classdata['class_id'];?>" <?php selected($classval,$classdata['class_id']);?>><?php echo $classdata['class_name'];?></option>
						<?php }?>
                </select>
			</div>
		</div>
		<div class="mb-3 form-group row">
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
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="student_list"><?php esc_attr_e('Student','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">                     
                <select name="student_id" id="student_list" class="form-control validate[required] max_width_100">
					<?php if(isset($result->student_id)){ 
						$student=get_userdata($result->student_id);
					?>
                     <option value="<?php echo $result->student_id;?>" ><?php echo $student->first_name." ".$student->last_name;?></option>
                     <?php }
					else
					{?>
                    	<option value=""><?php esc_attr_e('Select student','school-mgt');?></option>
					<?php } ?>
                </select>
			</div>
		</div>		
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="issue_date"><?php esc_attr_e('Issue Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="issue_date" class="datepicker form-control validate[required] text-input" type="text" name="issue_date" value="<?php if($edit){ echo $result->issue_date;}else{echo date('m/d/Y');}?>" readonly>
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="period"><?php esc_attr_e('Period','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="period_id" id="category_data" class="form-control issue_period validate[required] max_width_100 period_type">
					<option value = ""><?php esc_attr_e('Select Period','school-mgt');?></option>
					<?php 
					if($edit)
						$period_id = $result->period;
						$category_data = $obj_lib->mj_smgt_get_periodlist();
				
					if(!empty($category_data))
					{
						foreach ($category_data as $retrieved_data)
						{
							echo '<option value="'.$retrieved_data->ID.'" '.selected($period_id,$retrieved_data->ID).'>'.$retrieved_data->post_title.' '. esc_attr__('Days','school-mgt').'</option>';
						}
					}
					?>
			</select>
			</div>
			<div class="col-sm-2 mt_10_res">
				<button id="addremove_cat" class="btn btn-info" model="period_type"><?php esc_attr_e('Add','school-mgt');?></button>
			</div>
		</div>
		<?php wp_nonce_field( 'save_issuebook_admin_nonce' ); ?>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="return_date"><?php esc_attr_e('Return Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="return_date" class="form-control validate[required] text-input" type="text" name="return_date" value="<?php if($edit){ echo $result->end_date;}?>" readonly>
			</div>
		</div>
		
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="category_data validate[required]"><?php esc_attr_e('Book Category','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="bookcat_id" id="bookcat_list" class="form-control max_width_100">
					<option value = ""><?php esc_attr_e('Select Category','school-mgt');?></option>
					<?php if($edit)
						$book_cat = $result->cat_id;
						$category_data = $obj_lib->mj_smgt_get_bookcat();
					
						if(!empty($category_data))
						{
							foreach ($category_data as $retrieved_data)
							{
								echo '<option value="'.$retrieved_data->ID.'" '.selected($book_cat,$retrieved_data->ID).'>'.$retrieved_data->post_title.'</option>';
							}
						}
					?>
				</select>
			</div>
		</div>
		
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="book_name"><?php esc_attr_e('Book Name','school-mgt');?><span class="require-field"><span class="require-field">*</span></span></label>
			<div class="col-sm-8 multiselect_validation_book">
				<?php if($edit)
				{ 
			       $book_id=$result->book_id;
				   $books_data=$obj_lib->mj_smgt_get_all_books();
			    }
				else
				{
					$book_id=0;
					$books_data=$obj_lib->mj_smgt_get_all_books();
				}
				
				?>
				 <select name="book_id[]" id="book_list1" multiple="multiple" class="form-control validate[required]">
				 <?php 
				 if($edit)
				 { 
				      
					 foreach($books_data as $book)
					 {?>
						  <option value="<?php echo $book->id;?>" <?php selected($book_id,$book->id);?>><?php echo stripslashes($book->book_name);?></option>
					 <?php 
					 } 
				 }
				 ?>
				 </select>			
			</div>
		</div>		

		<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-label text-md-end " for="enable"><?php esc_attr_e('Send Mail','school-mgt');?></label>
				<div class="col-sm-8 ps-0">
					<div class="checkbox">
						<label>
							<input id="chk_sms_sent1" type="checkbox" <?php $smgt_issue_book_mail_service_enable = 0;if($smgt_issue_book_mail_service_enable) echo "checked";?> value="1" name="smgt_issue_book_mail_service_enable">
						</label>
					</div>				 
				</div>
			</div>			
		
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Issue Book','school-mgt'); }else{ esc_attr_e('Issue Book','school-mgt');}?>" name="save_issue_book" class="btn btn-success book_for_alert" />
        </div>        
    </form>
</div>