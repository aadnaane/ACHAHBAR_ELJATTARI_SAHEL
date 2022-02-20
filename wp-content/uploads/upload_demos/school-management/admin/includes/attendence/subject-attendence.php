<?php	
	if($active_tab == 'subject_attendence')
	{		
?>
 
<div class="panel-body"> 
    <form method="post" id="subject_attendance">  
        <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
        <div class="row">
			<div class="form-group col-md-2">
						<label class="control-label" for="curr_date"><?php esc_attr_e('Date','school-mgt');?></label>			
						<input id="curr_date_subject" class="form-control curr_date" type="text" value="<?php if(isset($_POST['curr_date'])) echo $_POST['curr_date']; else echo  date("Y-m-d");?>" name="curr_date" readonly>		
					</div>
					<div class="form-group col-md-2">
						<label for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>			
						<?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>
					<select name="class_id"  id="class_list"  class="form-control validate[required]">
						<option value=" "><?php esc_attr_e('Select class Name','school-mgt');?></option>
						<?php
						foreach(mj_smgt_get_allclass() as $classdata)
						{ ?>
									<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
					<?php }?>
					</select>			
					</div>
					<div class="form-group col-md-2">
						<label for="class_id"><?php esc_attr_e('Select Section','school-mgt');?></label>			
					<?php 
					$class_section="";
					if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
						<select name="class_section" class="form-control" id="class_section">
					   <option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
							<?php if(isset($_REQUEST['class_section'])){
								$class_section=$_REQUEST['class_section']; 
								foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
								{  ?>
									<option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
								<?php } 
								} ?>		
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="class_id"><?php esc_attr_e('Select Subject','school-mgt');?><span class="require-field">*</span></label>
					<select name="sub_id"  id="subject_list"  class="form-control validate[required]">
						<option value=" "><?php esc_attr_e('Select Subject','school-mgt');?></option>
								<?php $sub_id=0;
								if(isset($_POST['sub_id']))
								{
									$sub_id=$_POST['sub_id'];
								?>
								<?php $allsubjects = mj_smgt_get_subject_by_classid($_POST['class_id']);
						foreach($allsubjects as $subjectdata){ ?>
									<option value="<?php echo $subjectdata->subid;?>" <?php selected($subjectdata->subid,$sub_id); ?>><?php echo $subjectdata->sub_name;?></option>
						 <?php }
								}
								?>
					</select>			
					</div>
					
				<div class="form-group col-md-3 button-possition">
					<label for="subject_id">&nbsp;</label>
					  <input type="submit" value="<?php esc_attr_e('Take/View  Attendance','school-mgt');?>" name="attendence"  class="btn btn-success"/>
				</div>
		</div>      
</form>
</div>
<div class="clearfix"> </div>
<?php 
    if(isset($_REQUEST['attendence']) || isset($_REQUEST['save_sub_attendence']))
	{
		$attendanace_date=$_REQUEST['curr_date'];
		$holiday_dates=mj_smgt_get_all_date_of_holidays();
		if (in_array($attendanace_date, $holiday_dates))
		{
			?>
			<div class="alert_msg alert alert-warning alert-dismissible " role="alert">
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<?php esc_attr_e('This day is holiday you are not able to take attendance','school-mgt');?>
			</div>
		<?php 
		}
		else
		{
      	if(isset($_REQUEST['class_id']) && $_REQUEST['class_id'] != " ")
			$class_id =$_REQUEST['class_id'];
        else 
			$class_id = 0;
        if($class_id == 0)
        { ?>
    <div class="panel-heading">
      	<h4 class="panel-title"><?php esc_attr_e('Please Select Class','school-mgt');?></h4>
    </div>
    <?php }
    else
	{
						
		if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != "")
		{
			$exlude_id = mj_smgt_approve_student_list();
			$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],
				'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
				sort($student);
		}
		else
		{ 
			$exlude_id = mj_smgt_approve_student_list();
			$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
			sort($student);
		}
		?>
<div class="panel-body">  
    <form method="post"  class="form-horizontal"> 
		<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
        <input type="hidden" name="sub_id" value="<?php echo $sub_id;?>" />
        <input type="hidden" name="class_section" value="<?php echo $_REQUEST['class_section'];?>" />
        <input type="hidden" name="curr_date" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" />
        
        <div class="panel-heading">
         	<h4 class="panel-title"> <?php esc_attr_e('Class','school-mgt')?> : <?php echo mj_smgt_get_class_name($class_id);?> , 
         	<?php esc_attr_e('Date','school-mgt')?> : <?php echo mj_smgt_getdate_in_input_box($_POST['curr_date']);?>,<?php esc_attr_e('Subject')?> : <?php echo mj_smgt_get_subject_byid($_POST['sub_id']); ?></h4>
        </div>
        
        <div class="col-md-12 padding_payment">
			<div class="table-responsive">
				<table class="table">
					<tr>
						  <th><?php esc_attr_e('Srno','school-mgt');?></th>
						  <th><?php esc_attr_e('Roll No.','school-mgt');?></th>
						<th><?php esc_attr_e('Student Name','school-mgt');?></th>
						 <th><?php esc_attr_e('Attendance','school-mgt');?></th>
						   <th><?php esc_attr_e('Comment','school-mgt');?></th>
					</tr>
					<?php
					$date = $_POST['curr_date'];
					$i = 1;

					 foreach ( $student as $user ) {
						$date = $_POST['curr_date'];
						   
							$check_attendance = $obj_attend->mj_smgt_check_sub_attendence($user->ID,$class_id,$date,$_POST['sub_id']);
						   
							$attendanc_status = "Present";
							if(!empty($check_attendance))
							{
								$attendanc_status = $check_attendance->status;
								
							}
						   
						echo '<tr>';
					  
						echo '<td>'.$i.'</td>';
						echo '<td><span>' .get_user_meta($user->ID, 'roll_id',true). '</span></td>';
						echo '<td><span>' .$user->first_name.' '.$user->last_name. '</span></td>';
						?>
						<td><label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Present" <?php checked( $attendanc_status, 'Present' );?>>
						<?php esc_attr_e('Present','school-mgt');?></label>
						<label class="radio-inline"> <input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Absent" <?php checked( $attendanc_status, 'Absent' );?>>
						<?php esc_attr_e('Absent','school-mgt');?></label>
						<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Late" <?php checked( $attendanc_status, 'Late' );?>>
						<?php esc_attr_e('Late','school-mgt');?></label></td>
						<td><input type="text" name="attendanace_comment_<?php echo $user->ID?>" class="form-control" 
						value="<?php if(!empty($check_attendance)) echo $check_attendance->comment;?>"></td><?php 
						
						echo '</tr>';
						$i++; }?>
						   
				</table>
			</div>
		<div class="d-flex">

			<div class="form-group row mb-3">
				<label class="col-sm-8 control-label " for="enable"><?php esc_attr_e('If student absent then Send Mail','school-mgt');?></label>
				<div class="col-sm-2 ps-0">
					<div class="checkbox">
						<label>
							<input id="chk_sms_sent1" type="checkbox" <?php $smgt_subject_mail_service_enable = 0;if($smgt_subject_mail_service_enable) echo "checked";?> value="1" name="smgt_subject_mail_service_enable">
						</label>
					</div>				 
				</div>
			</div>				

			<div class="form-group row mb-3">
				<label class="col-sm-10 control-label " for="enable"><?php esc_attr_e('If student absent then Send  SMS to his/her parents','school-mgt');?></label>
				<div class="col-sm-2 ps-0">
					<div class="checkbox">
						<label>
							<input id="chk_sms_sent1" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="smgt_sms_service_enable">
						</label>
					</div>				 
				</div>
			</div>
		</div>
		</div>
	<div class="col-sm-12"> 
	   	<input type="submit" value="<?php esc_attr_e("Save Attendance","school-mgt");?>" name="save_sub_attendence" class="btn btn-success" />
    </div>
    </form>
</div>
<?php }
  }
	}
	}
?>