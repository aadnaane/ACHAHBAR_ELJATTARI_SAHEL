<?php 
// This is Dashboard at admin side!!!!!!!!! 
$role='student_temp';  
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
 
<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/common.js'; ?>" ></script>

<script>
	var value = 1;
	function mj_smgt_add_sibling()
	{	
		value++;
		$("#sibling_div").append('<div class="form-group row mb-3"><div class="col-md-2 col-sm-2 col-xs-12"><label class="radio-inline"><input type="radio" name="siblinggender_'+ value +'[]" value="Brother" id="txtNumHours2"><?php esc_attr_e('Brother','school-mgt'); ?></label><label class="radio-inline"><input type="radio" name="siblinggender_'+ value +'[]" value="Sister" id="txtNumHours2"><?php  esc_attr_e('Sister','school-mgt');?></label></div><div class="col-md-2 col-sm-3 col-xs-12"><input id="txtNumHours" class="form-control margin_top_10 validate[custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  name="siblingsname[]" placeholder="<?php esc_html_e('Enter Full Name','school-mgt');?>"></div><div class="col-md-2 col-sm-3 col-xs-12"><input id="txtNumHours1" class="form-control margin_top_10 validate[custom[onlyNumberSp],maxSize[3],max[100]] text-input"  type="number"  name="siblingage[]" placeholder="<?php esc_html_e('Enter Age','school-mgt');?>"></div><div class="col-md-2 col-sm-3 col-xs-12">	<select class="form-control margin_top_10 standard_category" name="sibling_standard[]" id="txtNumHours3"><option value=""><?php esc_html_e('Select Standard','school-mgt');?></option><?php $activity_category=mj_smgt_get_all_category('standard_category'); if(!empty($activity_category)){ foreach ($activity_category as $retrive_data) { ?><option value="<?php echo $retrive_data->ID;?>"><?php echo esc_attr($retrive_data->post_title); ?> </option> <?php } } ?> </select>	</div><div class="col-md-2 col-sm-2 col-xs-12"><input id="txtNumHours4" class="form-control  margin_top_10 validate[custom[onlyNumberSp],maxSize[6]] text-input" maxlength="50" type="number"  name="siblingsid[]"></div><div class="col-md-1 col-sm-1 col-xs-12"><input type="button" value="<?php esc_html_e('Delete','school-mgt');?>" onclick="mj_smgt_deleteParentElement(this)" class="remove_cirtificate margin_top_10 btn btn-danger"></div></div>');

	}
	
	function mj_smgt_deleteParentElement(n)
	{
		alert("<?php esc_html_e('Do you really want to delete this ?','school-mgt'); ?>");
		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);				
	}
</script>

     <?php 	
	if($active_tab == 'admission_form')
	{
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$student_data = get_userdata($_REQUEST['id']);
			$user_ID = (int)$_REQUEST['id'];
			$key = 'status';
			$single = true;
			$user_status = get_user_meta( $user_ID, $key, $single );
			$sibling_data = $student_data->sibling_information;
			$sibling = json_decode($sibling_data);
		}
    ?>
		
       <div class="panel-body">
        <form name="admission_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="admission_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="role" value="<?php echo $role;?>"  />
		<input type="hidden" name="user_id" value="<?php if($edit){ echo $_REQUEST['id'];}?>"  />
		<input type="hidden" name="status" value="<?php if($edit){ echo $user_status;}?>"  />

		  <!--- Hidden User and password --------->
		<input id="username" type="hidden"  name="username">
		<input id="password" type="hidden"  name="password">
		 
		<div class="form-group row mb-3">
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="admission_no"><?php esc_attr_e('Admission Number','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<input id="admission_no" class="form-control validate[required] text-input" type="text" value="<?php if($edit){ echo $student_data->admission_no;}elseif(isset($_POST['admission_no'])){ echo mj_smgt_generate_admission_number(); }else{ echo mj_smgt_generate_admission_number(); }?>"  name="admission_no" readonly>		
			</div>
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="admission_date"><?php esc_attr_e('Admission Date','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<input id="admission_date" class="form-control validate[required]" type="text"  name="admission_date" readonly value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($student_data->admission_date);}elseif(isset($_POST['admission_date'])) echo $_POST['admission_date'];?>">
			</div>
		</div>
		<legend class="frm-legend"><?php  esc_attr_e('Student Info','school-mgt');?></legend>
		<div class="form-group row mb-3">
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="first_name"><?php esc_attr_e('First Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="first_name" placeholder="<?php esc_html_e('Enter First Name','school-mgt');?>" value="<?php if($edit){ echo $student_data->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>">
			</div>
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="middle_name"><?php esc_attr_e('Middle Name','school-mgt');?></label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text"  name="middle_name"  placeholder="<?php esc_html_e('Enter Middle Name','school-mgt');?>" value="<?php if($edit){ echo $student_data->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>">
			</div>
		</div>
		 
		<div class="form-group row mb-3">
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="last_name"><?php esc_attr_e('Last Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  name="last_name" placeholder="<?php esc_html_e('Enter Last Name','school-mgt');?>" value="<?php if($edit){ echo $student_data->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>">
			</div>
			<label class="col-sm-2 control-label col-form-label text-md-end" for="birth_date"><?php esc_attr_e('Date of birth','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<input id="birth_date" class="form-control validate[required] birth_date" type="text"  name="birth_date"  readonly value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($student_data->birth_date);}elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="gender"><?php esc_attr_e('Gender','school-mgt');?><span class="require-field">*</span></label>
			<?php $genderval = "male"; if($edit){ $genderval=$student_data->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 ">
				<label class="radio-inline custom_radio">
			     <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_attr_e('Male','school-mgt');?>
			    </label>
			    <label class="radio-inline custom_radio">
			      <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_attr_e('Female','school-mgt');?> 
			    </label>
				 <label class="radio-inline custom_radio">
			      <input type="radio" value="other" class="tog validate[required]" name="gender"   <?php  checked( 'other', $genderval);  ?> /><?php esc_attr_e('Other','school-mgt');?> 
			    </label>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="address"><?php esc_attr_e('Address','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text"  name="address" value="<?php if($edit){ echo $student_data->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
			</div>
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="state_name"><?php esc_attr_e('State','school-mgt');?></label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" value="<?php if($edit){ echo $student_data->state;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="city_name"><?php esc_attr_e('City','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" value="<?php if($edit){ echo $student_data->city;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
			</div>
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="zip_code"><?php esc_attr_e('Zip Code','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" maxlength="15" type="text"  name="zip_code" value="<?php if($edit){ echo $student_data->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="mobile_number"><?php esc_attr_e('Mobile Number','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-lg-1 col-md-1 col-sm-1 col-xs-6 margin_bottom_5">
				<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="phonecode">
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
				<input id="phone" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="phone" value="<?php if($edit){ echo $student_data->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>">
			</div>
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="mobile_number"><?php esc_attr_e('Alternate Mobile Number','school-mgt');?></label>
			<div class="col-lg-1 col-md-1 col-sm-1 col-xs-6 margin_bottom_5">		
				<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="alter_mobile_number">
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
				<input id="alternet_mobile_number" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]" type="text"  name="alternet_mobile_number" value="<?php if($edit){ echo $student_data->alternet_mobile_number;}elseif(isset($_POST['alternet_mobile_number'])) echo $_POST['alternet_mobile_number'];?>">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end " for="email"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<input id="email" class="form-control validate[required,custom[email]] text-input email" maxlength="100" type="text"  name="email" placeholder="<?php esc_html_e('Enter Email','school-mgt');?>" value="<?php if($edit){ echo $student_data->user_email;}elseif(isset($_POST['user_email'])) echo $_POST['user_email'];?>">
			</div>
			<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="group"><?php esc_html_e('Previous School','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">		
				<input id="preschool_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" name="preschool_name" placeholder="<?php esc_html_e('Enter Previous School Name','school-mgt');?>" value="<?php if($edit){ echo $student_data->preschool_name;}elseif(isset($_POST['preschool_name'])) echo $_POST['preschool_name'];?>">		
			</div>
		</div>
		<?php wp_nonce_field( 'save_admission_form' ); ?>
		<div class="form-group row mb-3">
			
		</div>
		<legend class="frm-legend"><?php  esc_attr_e('Siblings Information','school-mgt');?></legend>
		 
			<div class="form-group row mb-3">
				<div class="col-md-6 col-sm-6 col-xs-12 d-inline-flex" id="relationid">		
					<input type="checkbox" id="chkIsTeamLead" <?php if($edit){ if(empty($sibling)){ ?> checked <?php } } ?> />
					&nbsp;&nbsp;&nbsp; <?php esc_attr_e('In case of no sibling click here','school-mgt'); ?>
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12 relat hidden-xs row">	
				<div class="col-md-2 col-sm-3 col-xs-12 px-4"><?php  esc_attr_e('Relation Type','school-mgt');?></div>
				<div class="col-md-2 col-sm-3 col-xs-12 px-4"><?php  esc_attr_e('Full Name','school-mgt');?></div>
				<div class="col-md-2 col-sm-3 col-xs-12 px-4"><?php  esc_attr_e('Age','school-mgt');?></div>
				<div class="col-md-3 col-sm-6 col-xs-12 px-4"><?php  esc_attr_e('Standard','school-mgt');?></div>
				<div class="col-md-2 col-sm-3 col-xs-12 px-4"><?php  esc_attr_e('SID','school-mgt');?></div>
			</div>
			<div id="sibling_div">				
				<div class="form-group row mb-3">
					<div class="col-md-2 col-sm-2 col-xs-12">	
						<label class="radio-inline">
							<input type="radio" name="siblinggender[]" value="Brother" id="txtNumHours2" <?php if($edit){ ?> disabled <?php } ?>><?php esc_attr_e('Brother','school-mgt'); ?>
						</label>
						<label class="radio-inline">
						  <input type="radio" name="siblinggender[]" value="Sister" id="txtNumHours2" <?php if($edit){ ?> disabled <?php } ?>><?php  esc_attr_e('Sister','school-mgt');?>
						</label>						
					</div>
					<div class="col-md-2 col-sm-3 col-xs-12">
						<input id="txtNumHours" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input margin_top_10" maxlength="50" type="text"  name="siblingsname[]" placeholder="<?php esc_html_e('Enter Full Name','school-mgt');?>" <?php if($edit){ ?> disabled <?php } ?>>
					</div>
					<div class="col-md-2 col-sm-3 col-xs-12">	
						<input id="txtNumHours1" class="form-control validate[custom[onlyNumberSp],maxSize[3],max[100]] text-input margin_top_10" type="number" maxlength="3" name="siblingage[]" placeholder="<?php esc_html_e('Enter Age','school-mgt');?>" <?php if($edit){ ?> disabled <?php } ?>>
					</div>
					<div class="col-md-2 col-sm-3 col-xs-12">	
						<select class="form-control standard_category margin_top_10" name="sibling_standard[]" id="txtNumHours3" <?php if($edit){ ?> disabled <?php } ?>>
							<option value=""><?php esc_html_e('Select Standard','school-mgt');?></option>
							<?php 
							$activity_category=mj_smgt_get_all_category('standard_category');
							if(!empty($activity_category))
							{
								foreach ($activity_category as $retrive_data)
								{ 		 	
								?>
									<option value="<?php echo $retrive_data->ID;?>"><?php echo esc_attr($retrive_data->post_title); ?> </option>
								<?php }
							} 
							?> 
						</select>			
					</div>
					<div class="col-md-1 col-sm-1 col-xs-12">
						<button id="addremove_cat" class="btn btn-info sibling_add_remove margin_top_10" model="standard_category"><?php esc_attr_e('Add','school-mgt');?></button>		
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12">	
						<input id="txtNumHours4" class="form-control validate[custom[onlyNumberSp],maxSize[6]] text-input margin_top_10" type="number"  name="siblingsid[]" placeholder="<?php esc_html_e('Enter SID Number','school-mgt');?>" <?php if($edit){ ?> disabled <?php } ?>> 
					</div>
					<div class="col-md-1 col-sm-1 col-xs-12">	
						<input type="button" value="<?php esc_html_e('Add More','school-mgt') ?>" id="add_more_sibling" onclick="mj_smgt_add_sibling()" class="add_cirtificate btn btn-info margin_top_10">
					</div>
				</div>	
			</div>
			<?php 
			if (!empty($student_data->sibling_information)) 
			{
				$sibling_data = $student_data->sibling_information;
				$sibling = json_decode($sibling_data);
				if (!empty($sibling))
				{
					foreach ($sibling as $value) 
					{
					?>
						<div id="sibling_div">				
							<div class="form-group row mb-3">
								<div class="col-md-2 col-sm-2 col-xs-12">
								<!-- <?php if($edit){ $genderval=$value->siblinggender; }elseif(isset($_POST['siblinggender'])) {$genderval=$_POST['siblinggender'];}?> -->	
									<label class="radio-inline">
										<input type="radio" name="siblinggender[]" value="Brother" id="txtNumHours2" <?php  checked( 'Brother', $genderval);  ?>><?php esc_attr_e('Brother','school-mgt'); ?>
									</label>
									<label class="radio-inline">
									  <input type="radio" name="siblinggender[]" value="Sister" id="txtNumHours2" <?php  checked( 'Sister', $genderval);  ?>><?php  esc_attr_e('Sister','school-mgt');?>
									</label>						
								</div>
								<div class="col-md-2 col-sm-3 col-xs-12">
									<input id="txtNumHours" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input margin_top_10" maxlength="50" type="text"  name="siblingsname[]" placeholder="<?php esc_html_e('Enter Full Name','school-mgt');?>" value="<?php echo $value->siblingsname; ?>">
								</div>
								<div class="col-md-2 col-sm-3 col-xs-12">	
									<input id="txtNumHours1" class="form-control validate[custom[onlyNumberSp],maxSize[3],max[100]] text-input margin_top_10" type="number" maxlength="3" name="siblingage[]" placeholder="<?php esc_html_e('Enter Age','school-mgt');?>" value="<?php echo $value->siblingage; ?>">
								</div>
								<div class="col-md-2 col-sm-3 col-xs-12">	
									<select class="form-control standard_category margin_top_10" name="sibling_standard[]" id="txtNumHours3">
										<option value=""><?php esc_html_e('Select Standard','school-mgt');?></option>
										<?php 
										$activity_category=mj_smgt_get_all_category('standard_category');
										if(!empty($activity_category))
										{
											foreach ($activity_category as $retrive_data)
											{ 		 	
											?>
												<option value="<?php echo $retrive_data->ID;?>" <?php echo selected($value->sibling_standard,$retrive_data->post_title); ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>
											<?php }
										} 
										?> 
									</select>			
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12">	
									<input id="txtNumHours4" class="form-control validate[custom[onlyNumberSp],maxSize[6]] text-input margin_top_10" type="number"  name="siblingsid[]" placeholder="<?php esc_html_e('Enter SID','school-mgt');?>" value="<?php echo $value->siblingsid; ?>">
								</div>
								<div class="col-md-1 col-sm-1 col-xs-12"><input type="button" value="<?php esc_html_e('Delete','school-mgt');?>" onclick="mj_smgt_deleteParentElement(this)" class="remove_cirtificate margin_top_10 btn btn-danger"></div>
							</div>	
						</div>
					<?php
					}
				}
				?>
				<?php
			}
			?>
		<legend class="frm-legend"><?php  esc_attr_e('Family Information','school-mgt');?></legend>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group row mb-3">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="gender"><?php esc_attr_e('Parental Status','school-mgt');?></label>
					<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<?php $pstatus = "Both"; if($edit){ $pstatus=$student_data->parent_status; }elseif(isset($_POST['pstatus'])) {$pstatus=$_POST['pstatus'];}?>
						<label class="radio-inline margin_left_10_res" id="sinfather">
						  <input type="radio" name="pstatus" class="tog" value="Father"  <?php  checked( 'Father', $pstatus);  ?>><?php esc_attr_e('Single Father','school-mgt');?> 
						</label>
						<label class="radio-inline" id="sinmother">
						  <input type="radio" name="pstatus" class="tog" value="Mother" <?php  checked( 'Mother', $pstatus);  ?>><?php esc_attr_e('Single Mother','school-mgt');?> 
						</label>
						<label class="radio-inline" id="boths">
						  <input type="radio" name="pstatus" class="tog" value="Both"  <?php  checked( 'Both', $pstatus);  ?>><?php esc_attr_e('Both','school-mgt');?> 
						</label>
					</div>
				</div>	
			</div>	
			<?php
			if($edit)
			{
				$pstatus = $student_data->parent_status;
				if($pstatus == 'Father') 
				{
				 	$m_display_none = 'display_none';
				}
				elseif($pstatus == 'Mother')
				{
					$f_display_none = 'display_none';
				}
			}
			?>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 father_div <?php echo $f_display_none;  ?>">
				<div class="row">
					<div id="fatid">
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end  col-md-4 col-sm-4 col-xs-12" for="Relationship"><?php esc_attr_e('Relationship','school-mgt');?></label> 
							<div class="col-md-8 col-sm-8 col-xs-12 labfat"><label class="control-label col-form-label text-md-end" for="FATHER"><b><?php esc_attr_e('FATHER','school-mgt');?></b></label></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid1">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end  col-md-4 col-sm-4 col-xs-12" for="Salutation"><?php esc_attr_e('Salutation','school-mgt');?></label> 
							<div class="col-md-8 col-sm-8 col-xs-12">
								<select class="form-control validate[required]" name="fathersalutation" id="fathersalutation">
									<option value="Mr"><?php esc_attr_e('Mr','school-mgt');?></option>
								</select>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
						</div>		
					</div>		
				</div>
				<div class="row">
					<div id="fatid2">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="First Name"><?php esc_attr_e('First Name','school-mgt');?></label> 
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="father_first_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter First Name','school-mgt');?>" maxlength="50" type="text" name="father_first_name" value="<?php if($edit){ echo $student_data->father_first_name;}elseif(isset($_POST['father_first_name'])) echo $_POST['father_first_name'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid3">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="Middle Name"><?php esc_attr_e('Middle Name','school-mgt');?></label> 
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="father_middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter Middle Name','school-mgt');?>" maxlength="50" type="text" name="father_middle_name" value="<?php if($edit){ echo $student_data->father_middle_name;}elseif(isset($_POST['father_middle_name'])) echo $_POST['father_middle_name'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid4">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="Last Name"><?php esc_attr_e('Last Name','school-mgt');?></label> 
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="father_last_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter Last Name','school-mgt');?>" maxlength="50" type="text" name="father_last_name" value="<?php if($edit){ echo $student_data->father_last_name;}elseif(isset($_POST['father_last_name'])) echo $_POST['father_last_name'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid13">	
						<div class="form-group row mb-3">
							<label class="col-md-4 col-sm-4 col-xs-12 control-label col-form-label text-md-end" for="father_gender"><?php esc_attr_e('Gender','school-mgt');?></label>
							<?php $father_gender = "male"; if($edit){ $father_gender=$student_data->fathe_gender; }elseif(isset($_POST['fathe_gender'])) {$father_gender=$_POST['fathe_gender'];}?>
							<?php ?>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<label class="radio-inline custom_radio margin_left_10_res">
								 <input type="radio" value="male" class="tog" name="fathe_gender" <?php  checked( 'male', $father_gender);  ?>/><?php esc_attr_e('Male','school-mgt');?>
								</label>
								<label class="radio-inline custom_radio">
								  <input type="radio" value="female" class="tog" name="fathe_gender" <?php  checked( 'female', $father_gender);  ?> /><?php esc_attr_e('Female','school-mgt');?> 
								</label>
								 <label class="radio-inline custom_radio">
								  <input type="radio" value="other" class="tog" name="fathe_gender" <?php  checked( 'other', $father_gender);  ?> /><?php esc_attr_e('Other','school-mgt');?> 
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid14">	
						<div class="form-group row mb-3">
							<label class="col-md-4 col-sm-4 col-xs-12 control-label col-form-label text-md-end" for="gender"><?php esc_attr_e('Date of birth','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="father_birth_date" class="form-control birth_date" type="text"  name="father_birth_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($student_data->father_birth_date);}elseif(isset($_POST['father_birth_date'])) echo $_POST['father_birth_date'];?>" readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid15">	
						<div class="form-group row mb-3">
							<label class="col-md-4 col-sm-4 col-xs-12 control-label col-form-label text-md-end" for="Address"><?php esc_attr_e('Address','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="father_address" class="form-control validate[custom[address_description_validation]]" maxlength="150" type="text"  name="father_address" value="<?php if($edit){ echo $student_data->father_address;}elseif(isset($_POST['father_address'])) echo $_POST['father_address'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid16">	
						<div class="form-group row mb-3">
							<label class="col-md-4 col-sm-4 col-xs-12 control-label col-form-label text-md-end" for="State"><?php esc_attr_e('State','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="father_state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="father_state_name" value="<?php if($edit){ echo $student_data->father_state_name;}elseif(isset($_POST['father_state_name'])) echo $_POST['father_state_name'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid17">	
						<div class="form-group row mb-3">
							<label class="col-md-4 col-sm-4 col-xs-12  control-label col-form-label text-md-end" for="City"><?php esc_attr_e('City','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="father_city_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="father_city_name" value="<?php if($edit){ echo $student_data->father_city_name;}elseif(isset($_POST['father_city_name'])) echo $_POST['father_city_name'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid18">	
						<div class="form-group row mb-3">
							<label class="col-md-4 col-sm-4 col-xs-12 control-label col-form-label text-md-end" for="Zip Code"><?php esc_attr_e('Zip Code','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="father_zip_code" class="form-control  validate[custom[onlyLetterNumber]]" maxlength="15" type="text" name="father_zip_code" value="<?php if($edit){ echo $student_data->father_zip_code;}elseif(isset($_POST['father_zip_code'])) echo $_POST['father_zip_code'];?>">
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div id="fatid5">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12 " for="Email"><?php esc_attr_e('Email','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="father_email" class="form-control validate[custom[email]] text-input father_email" maxlength="100" placeholder="<?php esc_html_e('Enter Email','school-mgt');?>" type="text"  name="father_email" value="<?php if($edit){ echo $student_data->father_email;}elseif(isset($_POST['father_email'])) echo $_POST['father_email'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid6">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end  col-md-4 col-sm-4 col-xs-12" for="father_mobile"><?php esc_attr_e('Mobile No ','school-mgt');?></label>
							<div class="col-md-2 col-sm-2 col-xs-5 margin_bottom_5">
								<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="phone_code">
							</div>	
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="father_mobile" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]"  placeholder="<?php esc_html_e('Enter Mobile No','school-mgt');?>"  type="text"  name="father_mobile" value="<?php if($edit){ echo $student_data->father_mobile;}elseif(isset($_POST['father_mobile'])) echo $_POST['father_mobile'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid7">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end  col-md-4 col-sm-4 col-xs-12" for="father_school"><?php esc_attr_e('School Name ','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<div id="fatherschoolother">
									<input id="father_school" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter School Name','school-mgt');?>" maxlength="50" type="text" name="father_school" value="<?php if($edit){ echo $student_data->father_school;}elseif(isset($_POST['father_school'])) echo $_POST['father_school'];?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid8">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="father_school"><?php esc_attr_e('Medium of Instruction','school-mgt');?></label>	
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="father_medium" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter Medium of Instruction','school-mgt');?>" maxlength="50" type="text" name="father_medium" value="<?php if($edit){ echo $student_data->father_medium;}elseif(isset($_POST['father_medium'])) echo $_POST['father_medium'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid9">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="Educational Qualification"><?php esc_attr_e('Educational Qualification','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="father_education" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter Educational Qualification','school-mgt');?>" maxlength="50" type="text" name="father_education" value="<?php if($edit){ echo $student_data->father_education;}elseif(isset($_POST['father_education'])) echo $_POST['father_education'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid10">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="fathe_income"><?php esc_attr_e('Annual Income','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="fathe_income" class="form-control validate[custom[onlyNumberSp],maxSize[8],min[0]] text-input" placeholder="<?php esc_html_e('Enter Annual Income','school-mgt');?>" maxlength="50" type="text" name="fathe_income" value="<?php if($edit){ echo $student_data->fathe_income;}elseif(isset($_POST['fathe_income'])) echo $_POST['fathe_income'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid9">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="Occupation"><?php esc_attr_e('Occupation','school-mgt');?></label>	
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="father_occuption" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter Occupation','school-mgt');?>" maxlength="50" type="text" name="father_occuption" value="<?php if($edit){ echo $student_data->father_occuption;}elseif(isset($_POST['father_occuption'])) echo $_POST['father_occuption'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="fatid12">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="Occupation"><?php esc_attr_e('Proof of Qualification','school-mgt');?></label>	
							<div class="col-md-4 col-sm-4 col-xs-12 margin_bottom_5">
								<input type="text"  name="father_document_name" id="title_value" class="form-control validate[custom[onlyLetter_specialcharacter],maxSize[50]] margin_cause" value="<?php if($edit){ echo $student_data->father_document_name;}elseif(isset($_POST['father_document_name'])) echo $_POST['father_document_name'];?>"/>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<input type="file" name="father_doc" class="col-md-2 col-sm-2 col-xs-12 form-control file_validation input-file " value="<?php if($edit){ echo $student_data->father_doc;}elseif(isset($_POST['father_doc'])) echo $_POST['father_doc'];?>">	
							</div>
						</div>
					</div>
				</div> 
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mother_div <?php echo $m_display_none;  ?>">
				<div class="row">
					<div id="motid">
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end  col-md-4 col-sm-4 col-xs-12" for="Relationship"><?php esc_attr_e('Relationship','school-mgt');?></label> 
							<div class="col-md-8 col-sm-8 col-xs-12 labfat"><label class="control-label col-form-label text-md-end" for="MOTHER"><b><?php esc_attr_e('MOTHER','school-mgt');?></b></label></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid1">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end  col-md-4 col-sm-4 col-xs-12" for="Salutation"><?php esc_attr_e('Salutation','school-mgt');?></label> 
							<div class="col-md-8 col-sm-8 col-xs-12">
								<select class="form-control validate[required]" name="mothersalutation" id="mothersalutation">
								<option value="Ms"><?php esc_attr_e('Ms','school-mgt'); ?></option>
								<option value="Mrs"><?php esc_attr_e('Mrs','school-mgt'); ?></option>
								<option value="Miss"><?php esc_attr_e('Miss','school-mgt');?></option>
								</select>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
						</div>		
					</div>		
				</div>
				<div class="row">
					<div id="motid2">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="First Name"><?php esc_attr_e('First Name','school-mgt');?></label> 
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_first_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter First Name','school-mgt');?>"  maxlength="50" type="text" name="mother_first_name" value="<?php if($edit){ echo $student_data->mother_first_name;}elseif(isset($_POST['mother_first_name'])) echo $_POST['mother_first_name'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid3">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="Middle Name"><?php esc_attr_e('Middle Name','school-mgt');?></label> 
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter Middle Name','school-mgt');?>" maxlength="50" type="text" name="mother_middle_name" value="<?php if($edit){ echo $student_data->mother_middle_name;}elseif(isset($_POST['mother_middle_name'])) echo $_POST['mother_middle_name'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid4">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="Last Name"><?php esc_attr_e('Last Name','school-mgt');?></label> 
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_last_name" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter Last Name','school-mgt');?>" maxlength="50" type="text" name="mother_last_name" value="<?php if($edit){ echo $student_data->mother_last_name;}elseif(isset($_POST['mother_last_name'])) echo $_POST['mother_last_name'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid13">	
					<?php $mother_gender = "female"; if($edit){ $mother_gender=$student_data->mother_gender; }elseif(isset($_POST['mother_gender'])) {$mother_gender=$_POST['mother_gender'];}?>
						<div class="form-group row mb-3">
							<label class="col-md-4 col-sm-4 col-xs-12 control-label col-form-label text-md-end" for="father_gender"><?php esc_attr_e('Gender','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<label class="radio-inline custom_radio margin_left_20_res">
									<input type="radio" value="male" class="tog" name="mother_gender" <?php  checked( 'male', $mother_gender);  ?>/><?php esc_attr_e('Male','school-mgt');?>
								</label>
								<label class="radio-inline custom_radio">
									<input type="radio" value="female" class="tog" name="mother_gender" <?php  checked( 'female', $mother_gender);  ?> /><?php esc_attr_e('Female','school-mgt');?> 
								</label>
								<label class="radio-inline custom_radio">
									<input type="radio" value="other" class="tog" name="mother_gender" <?php  checked( 'other', $mother_gender);  ?> /><?php esc_attr_e('Other','school-mgt');?> 
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid14">	
						<div class="form-group row mb-3">
							<label class="col-md-4 col-sm-4 col-xs-12 control-label col-form-label text-md-end" for="gender"><?php esc_attr_e('Date of birth','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_birth_date" class="form-control birth_date" type="text" name="mother_birth_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($student_data->mother_birth_date);}elseif(isset($_POST['mother_birth_date'])) echo $_POST['mother_birth_date'];?>" readonly>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid15">	
						<div class="form-group row mb-3">
							<label class="col-md-4 col-sm-4 col-xs-12 control-label col-form-label text-md-end" for="Address"><?php esc_attr_e('Address','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_address" class="form-control validate[custom[address_description_validation]]" maxlength="150" type="text"  name="mother_address" value="<?php if($edit){ echo $student_data->mother_address;}elseif(isset($_POST['mother_address'])) echo $_POST['mother_address'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid16">	
						<div class="form-group row mb-3">
							<label class="col-md-4 col-sm-4 col-xs-12 control-label col-form-label text-md-end" for="State"><?php esc_attr_e('State','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="mother_state_name" value="<?php if($edit){ echo $student_data->mother_state_name;}elseif(isset($_POST['mother_state_name'])) echo $_POST['mother_state_name'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid17">	
						<div class="form-group row mb-3">
							<label class="col-md-4 col-sm-4 col-xs-12  control-label col-form-label text-md-end" for="City"><?php esc_attr_e('City','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_city_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="mother_city_name" value="<?php if($edit){ echo $student_data->mother_city_name;}elseif(isset($_POST['mother_city_name'])) echo $_POST['mother_city_name'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid18">	
						<div class="form-group row mb-3">
							<label class="col-md-4 col-sm-4 col-xs-12 control-label col-form-label text-md-end" for="Zip Code"><?php esc_attr_e('Zip Code','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_zip_code" class="form-control  validate[custom[onlyLetterNumber]]" maxlength="15" type="text"  name="mother_zip_code" value="<?php if($edit){ echo $student_data->mother_zip_code;}elseif(isset($_POST['mother_zip_code'])) echo $_POST['mother_zip_code'];?>">
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div id="motid5">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12 " for="Email"><?php esc_attr_e('Email','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_email" class="form-control  validate[custom[email]]  text-input mother_email" maxlength="100"  placeholder="<?php esc_html_e('Enter Email','school-mgt');?>"  type="text"  name="mother_email" value="<?php if($edit){ echo $student_data->mother_email;}elseif(isset($_POST['mother_email'])) echo $_POST['mother_email'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid6">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end  col-md-4 col-sm-4 col-xs-12" for="father_mobile"><?php esc_attr_e('Mobile No ','school-mgt');?></label>
							<div class="col-md-2 col-sm-2 col-xs-5 margin_bottom_5">
								<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control phonecode" name="phone_code">
							</div>	
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="mother_mobile" class="form-control text-input validate[custom[phone_number],minSize[6],maxSize[15]]"  placeholder="<?php esc_html_e('Enter Mobile No','school-mgt');?>"  type="text"  name="mother_mobile" value="<?php if($edit){ echo $student_data->mother_mobile;}elseif(isset($_POST['mother_mobile'])) echo $_POST['mother_mobile'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid7">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end  col-md-4 col-sm-4 col-xs-12" for="father_school"><?php esc_attr_e('School Name ','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<div id="fatherschoolother">
									<input id="mother_school" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter School Name','school-mgt');?>" maxlength="50" type="text" name="mother_school" value="<?php if($edit){ echo $student_data->mother_school;}elseif(isset($_POST['mother_school'])) echo $_POST['mother_school'];?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid8">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="father_school"><?php esc_attr_e('Medium of Instruction','school-mgt');?></label>	
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_medium" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter Medium of Instruction','school-mgt');?>" maxlength="50" type="text" name="mother_medium" value="<?php if($edit){ echo $student_data->mother_medium;}elseif(isset($_POST['mother_medium'])) echo $_POST['mother_medium'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid9">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="Educational Qualification"><?php esc_attr_e('Educational Qualification','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_education" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter Educational Qualification','school-mgt');?>" maxlength="50" type="text" name="mother_education" value="<?php if($edit){ echo $student_data->mother_education;}elseif(isset($_POST['mother_education'])) echo $_POST['mother_education'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid10">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="fathe_income"><?php esc_attr_e('Annual Income','school-mgt');?></label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_income" class="form-control validate[custom[onlyNumberSp],maxSize[8],min[0]] text-input" placeholder="<?php esc_html_e('Enter Annual Income','school-mgt');?>"  type="text" name="mother_income" value="<?php if($edit){ echo $student_data->mother_income;}elseif(isset($_POST['mother_income'])) echo $_POST['mother_income'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid9">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="Occupation"><?php esc_attr_e('Occupation','school-mgt');?></label>	
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input id="mother_occuption" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" placeholder="<?php esc_html_e('Enter Occupation','school-mgt');?>" maxlength="50" type="text" name="mother_occuption" value="<?php if($edit){ echo $student_data->mother_occuption;}elseif(isset($_POST['mother_occuption'])) echo $_POST['mother_occuption'];?>">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="motid12">	
						<div class="form-group row mb-3">
							<label class="control-label col-form-label text-md-end col-md-4 col-sm-4 col-xs-12" for="Occupation"><?php esc_attr_e('Proof of Qualification','school-mgt');?></label>	
							<div class="col-md-4 col-sm-4 col-xs-12 margin_bottom_5">
								<input type="text"  name="mother_document_name" id="title_value" class="form-control validate[custom[onlyLetter_specialcharacter],maxSize[50]] margin_cause" value="<?php if($edit){ echo $student_data->mother_document_name;}elseif(isset($_POST['mother_document_name'])) echo $_POST['mother_document_name'];?>"/>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<input type="file" name="mother_doc" class="col-md-2 col-sm-2 col-xs-12 form-control file_validation input-file " value="<?php if($edit){ echo $student_data->mother_doc;}elseif(isset($_POST['mother_doc'])) echo $_POST['mother_doc'];?>">	
							</div>
						</div>
					</div>
				</div> 	
			</div>
		</div>
		 
		<div class="offset-sm-2 col-sm-8">
        	<input type="submit" value="<?php esc_attr_e('New Admission','school-mgt');?>" name="student_admission" class="btn btn-success"/>
        </div>
        
        </form>
    </div>
<?php 
	}
?>