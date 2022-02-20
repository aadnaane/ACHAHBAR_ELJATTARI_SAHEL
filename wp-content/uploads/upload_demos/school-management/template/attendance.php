<?php
?>
<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
	$('#student_attendance').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#curr_date_sub123').datepicker({maxDate:'0',dateFormat: "yy-mm-dd"});
	$('#subject_attendance').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#curr_date_sub').datepicker({maxDate:'0',dateFormat: "yy-mm-dd"});
});
</script>
<?php
if($school_obj->role == 'parent' || $school_obj->role == 'student')
{
	echo "403 : Access Denied.";
	die;
}
$active_tab=isset($_REQUEST['tab'])?$_REQUEST['tab']:'daily_attendence';
$obj_attend=new Attendence_Manage();
$current_date = date("y-m-d");
$class_id =0;
$MailCon = get_option('absent_mail_notification');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
//--------------- SAVE ATTENDANCE ---------------------//
if(isset($_REQUEST['save_attendence']))
{	 
    $nonce = $_POST['_wpnonce'];
	if ( ! wp_verify_nonce( $nonce, 'save_attendence_front_nonce' ) )
	{
		die( 'Failed security check' );
	}
	else
	{
		$class_id=$_POST['class_id'];
		$attend_by=get_current_user_id();	
		
		$exlude_id = mj_smgt_approve_student_list();
		$students = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
		foreach($students as $stud)
		{
			if(isset($_POST['attendanace_'.$stud->ID]))
			{
				if(isset($_POST['smgt_sms_service_enable']))
				{
					$current_sms_service = get_option( 'smgt_sms_service');
					if($_POST['attendanace_'.$stud->ID] == 'Absent')
					{
						$parent_list = mj_smgt_get_student_parent_id($stud->ID);
						if(!empty($parent_list))
						{
							$parent_number =array();
							foreach ($parent_list as $user_id)
							{
								$parent_number[] = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
							}						
							$message_content = "Your Child ".mj_smgt_get_user_name_byid($stud->ID)." is absent today.";
							if(is_plugin_active('sms-pack/sms-pack.php'))
							{				
								$args = array();
								$args['mobile']=$parent_number;
								$args['message']=$message_content;					
								$args['message_from']='attendanace';					
								$args['message_side']='front';					
							if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' ||$current_sms_service=='nimbow' || $current_sms_service=='africastalking')
								{					
									$send = send_sms($args);					
								}
							}
							
							foreach ($parent_list as $user_id)
							{
								$parent = get_userdata($user_id);
								$reciever_number = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);				
								$message_content = "Your Child ".mj_smgt_get_user_name_byid($stud->ID)." is absent today.";
								if($current_sms_service == 'clickatell')
								{				
									$clickatell=get_option('smgt_clickatell_sms_service');
									$to = $reciever_number;
									$message = str_replace(" ","%20",$message_content);
									$username = $clickatell['username']; //clickatell username
									$password = $clickatell['password']; // clickatell password
									$api_key = $clickatell['api_key'];//clickatell apikey
									$baseurl ="http://api.clickatell.com";									
									$url = "$baseurl/http/auth?user=$username&password=$password&api_id=$api_key";									
									$ret = file($url);									
									$sess = explode(":",$ret[0]);
									if ($sess[0] == "OK")
									{											
										$sess_id = trim($sess[1]); // remove any whitespace
										$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$message";									
										$ret = file($url);
										$send = explode(":",$ret[0]);										
									}				
								}
								if($current_sms_service == 'twillo')
								{									
									require_once SMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
									$twilio=get_option( 'smgt_twillo_sms_service');
									$account_sid = $twilio['account_sid']; //Twilio SID
									$auth_token = $twilio['auth_token']; // Twilio token
									$from_number = $twilio['from_number'];//My number
									$receiver = $reciever_number; //Receiver Number
									$message = $message_content; // Message Text									
									$client = new Services_Twilio($account_sid, $auth_token);
									$message_sent = $client->account->messages->sendMessage(
										$from_number, // From a valid Twilio number
										$receiver, // Text this number
										$message
									);				
								}
								if($current_sms_service == 'msg91')
								{
									//MSG91
									$mobile_number=get_user_meta($user_id, 'mobile_number',true);
									$country_code="+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));
									$message = $message_content; // Message Text
									smgt_msg91_send_mail_function($mobile_number,$message,$country_code);
								}		
							}

							$MailArr['{{child_name}}'] = mj_smgt_get_display_name($stud->ID);
							$Mail = mj_smgt_string_replacement($MailArr,$MailCon);
							$email = $parent->user_email;
							mj_smgt_send_mail($email,$Mail,$Mail);
						}
					}
				}
				$savedata = $obj_attend->mj_smgt_insert_student_attendance($_POST['curr_date'],$class_id,$stud->ID,$attend_by,$_POST['attendanace_'.$stud->ID],$_POST['attendanace_comment_'.$stud->ID]);
			}					
		}
	?>
	
		<div class="alert_msg alert alert-success alert-dismissible  margin_left_right_0"  role="alert">
					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
					</button>
					<?php esc_attr_e('Attendance successfully saved!','school-mgt');?>
		</div>
<?php 
	}
}
//------------------------ SAVE SUBJECT WISE ATTENDANCE ---------------------//
if(isset($_REQUEST['save_sub_attendence']))
{
	
	$nonce = $_POST['_wpnonce'];
	if ( ! wp_verify_nonce( $nonce, 'save_sub_attendence_front_nonce' ) )
	{
		die( 'Failed security check' );
	}
	else
	{
		$class_id=$_POST['class_id'];
		$parent_list = mj_smgt_get_user_notice('parent',$class_id);		
		$attend_by=get_current_user_id();
			
		$exlude_id = mj_smgt_approve_student_list();
		$students = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
		foreach($students as $stud)
		{
			if(isset($_POST['attendanace_'.$stud->ID]))
			{
				if(isset($_POST['smgt_sms_service_enable']))
				{
					$current_sms_service = get_option( 'smgt_sms_service');
					if($_POST['attendanace_'.$stud->ID] == 'Absent')
					{
						$parent_list = mj_smgt_get_student_parent_id($stud->ID);
						if(!empty($parent_list))
						{
							foreach ($parent_list as $user_id)
							{
								foreach ($parent_list as $user_id)
								{
									$parent_number[] = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
								}
								$parent = get_userdata($user_id);
								$reciever_number = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);								
								$message_content = "Your Child ".mj_smgt_get_user_name_byid($stud->ID)." is absent today.";
								
								if(is_plugin_active('sms-pack/sms-pack.php'))
								{				
									$args = array();
									$args['mobile']=$parent_number;
									$args['message']=$message_content;					
									$args['message_from']='attendanace';					
									$args['message_side']='front';					
									if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' ||$current_sms_service=='nimbow' || $current_sms_service=='africastalking')
									{					
										$send = send_sms($args);					
									}
								}
								
								if($current_sms_service == 'clickatell')
								{									
									$clickatell=get_option('smgt_clickatell_sms_service');
									$to = $reciever_number;
									$message = str_replace(" ","%20",$message_content);
									$username = $clickatell['username']; //clickatell username.
									$password = $clickatell['password']; // clickatell password.
									$api_key = $clickatell['api_key'];//clickatell apikey.
									$baseurl ="http://api.clickatell.com";
											
									// auth call.
									$url = "$baseurl/http/auth?user=$username&password=$password&api_id=$api_key";
											
									// do auth call.
									$ret = file($url);
											
									// explode our response. return string is on first line of the data returned.
									$sess = explode(":",$ret[0]);
									if ($sess[0] == "OK")
									{
										$sess_id = trim($sess[1]); // remove any whitespace
										$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$message";											
										$ret = file($url);
										$send = explode(":",$ret[0]);										
									}							
									
								}
								if($current_sms_service == 'twillo')
								{
									require_once SMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
									$twilio=get_option( 'smgt_twillo_sms_service');
									$account_sid = $twilio['account_sid']; //Twilio SID
									$auth_token = $twilio['auth_token']; // Twilio token
									$from_number = $twilio['from_number'];//My number
									$receiver = $reciever_number; //Receiver Number
									$message = $message_content; // Message Text
									//twilio object
									$client = new Services_Twilio($account_sid, $auth_token);
									$message_sent = $client->account->messages->sendMessage(
										$from_number, // From a valid Twilio number
										$receiver, // Text this number
										$message
									);								
								}
								if($current_sms_service == 'msg91')
								{
									//MSG91
									$mobile_number=get_user_meta($user_id, 'mobile_number',true);
									$country_code="+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));
									$message = $message_content; // Message Text
									smgt_msg91_send_mail_function($mobile_number,$message,$country_code);
								}		
							}
							$MailArr['{{child_name}}'] = mj_smgt_get_display_name($stud->ID);
							$Mail = mj_smgt_string_replacement($MailArr,$MailCon);								
							mj_smgt_send_mail($parent->user_email,$Mail,$Mail);
						}
					}
				}
				$savedata = $obj_attend->mj_smgt_insert_subject_wise_attendance($_POST['curr_date'],$class_id,$stud->ID,$attend_by,$_POST['attendanace_'.$stud->ID],$_POST['sub_id'],$_POST['attendanace_comment_'.$stud->ID]);
			}					
		}
	}
	?>
	<div class="alert_msg alert alert-success alert-dismissible  margin_left_right_0" role="alert">
					<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
					</button>
					<?php esc_attr_e('Attendance successfully saved!','school-mgt');?>
		</div>
	  <?php 
}
?>
<div class="panel-body panel-white p-4">
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="nav-item">
			<a href="?dashboard=user&page=attendance&tab=daily_attendence" class="nav-link nav-tab2 <?php if($active_tab=='daily_attendence'){?>active<?php }?>"">
				<i class="fa fa-align-justify"></i> <?php esc_attr_e('Attendance', 'school-mgt'); ?></a>
			</a>
		</li>
		<li class="nav-item">
			<a href="?dashboard=user&page=attendance&tab=sub_attendence" class="nav-link nav-tab2 margin_bottom  <?php if($active_tab=='sub_attendence'){?>active<?php }?>">
				<i class="fa fa-align-justify"></i> <?php esc_attr_e('Subject Wise Attendance', 'school-mgt'); ?></a>
			</a>
		</li>	
	</ul>
<div class="tab-content">
<?php if($active_tab == 'daily_attendence'){ ?>
    <div class="panel-body">
		<form method="post" id="student_attendance">  
			<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />  
			<div class="row">
				<div class="form-group col-md-2">
					<label class="control-label" for="curr_date"><?php esc_attr_e('Date','school-mgt');?></label>
					<input id="curr_date_sub123" class="form-control" type="text" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" name="curr_date" readonly>
				</div>
				<div class="form-group col-md-2">
					<label for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
					<?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>
					<select name="class_id"  id="class_list"  class="form-control validate[required]">
						<option value=" "><?php esc_attr_e('Select class','school-mgt');?></option>
						<?php
						  foreach(mj_smgt_get_allclass() as $classdata)
						  {
						  ?>
						   <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
					 <?php }?>
					</select>
				</div>
				<div class="form-group col-md-2">
					<label for="class_id"><?php esc_attr_e('Select Class Section','school-mgt');?></label>
					<?php
					$class_section="";
					if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
					<select name="class_section" class="form-control" id="class_section">
							<option value=""><?php esc_attr_e('Select Section','school-mgt');?></option>
						<?php if(isset($_REQUEST['class_section'])){
								$class_section=$_REQUEST['class_section'];
								foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php }
							} ?>
					</select>
				</div>
				<div class="form-group col-md-3 button-possition">                    
					<input type="submit" value="<?php esc_attr_e('Take/View  Attendance','school-mgt');?>" name="attendence" class="btn btn-success"/>
				</div> 
			</div>

		</form>
	<div class="clearfix"></div>
    <?php 
    if(isset($_REQUEST['attendence']) || isset($_REQUEST['save_attendence']))
    {
		$attendanace_date=$_REQUEST['curr_date'];
		$holiday_dates=mj_smgt_get_all_date_of_holidays();
		if (in_array($attendanace_date, $holiday_dates))
		{
			?>
			<div class=" alert alert-warning alert-dismissible " role="alert">
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
        {
        ?>
         <div class="panel-heading">
         	<h4 class="panel-title"><?php esc_attr_e('Please Select Class','school-mgt');?></h4>
		</div>
        <?php 
		}
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
			
		<form method="post" class="form-horizontal mt-4">        
			<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
			<input type="hidden" name="class_section" value="<?php echo $_REQUEST['class_section'];?>" />
			<input type="hidden" name="curr_date" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" />
        
			<div class="panel-heading">
				<h4 class="panel-title"> <?php esc_attr_e('Class','school-mgt')?> : <?php echo mj_smgt_get_class_name($class_id);?> , 
				<?php esc_attr_e('Date','school-mgt')?> : <?php echo mj_smgt_getdate_in_input_box($_POST['curr_date']);?></h4>
			</div>
        
          <div class="col-md-12">
			<div class="table-responsive">
			<table class="table">
				<tr>
					<th><?php esc_attr_e('Srno','school-mgt');?></th>
					<th><?php esc_attr_e('Roll No.','school-mgt');?></th>
					<th><?php esc_attr_e('Student','school-mgt');?></th>
					<th><?php esc_attr_e('Attendance','school-mgt');?></th>
					<th><?php esc_attr_e('Comment','school-mgt');?></th>
				</tr>
				<?php
				$date = $_POST['curr_date'];
				$i = 1;

				foreach ( $student as $user )
				{
					$date = $_POST['curr_date'];
					$check_attendance = $obj_attend->mj_smgt_check_attendence($user->ID,$class_id,$date);
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
				<td><input type="text" name="attendanace_comment_<?php echo $user->ID?>" class="form-control" value="<?php if(!empty($check_attendance)) echo $check_attendance->comment;?>"></td><?php 
                
                echo '</tr>';
                $i++; } ?>                   
			</table>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-4 control-label col-form-label text-md-end" for="enable"><?php esc_attr_e('If student absent then Send  SMS to his/her parents','school-mgt');?></label>
			<div class="col-sm-2 pt-2 ps-0">
				 <div class="checkbox">
				 	<label>
  						<input id="chk_sms_sent1" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="smgt_sms_service_enable">
  					</label>
  				</div>				 
			</div>
		</div>
	</div>
	<?php wp_nonce_field( 'save_attendence_front_nonce' ); ?>
	<div class="col-sm-12"> 
        <input type="submit" value="<?php esc_attr_e('Save  Attendance','school-mgt');?>" name="save_attendence" class="btn btn-success" />
    </div>      
</form>		
<?php }  
 } 
	} ?>
</div>
<?php }
if($active_tab == 'sub_attendence')
{ ?>		
		
<div class="panel-body"> 
    <form method="post" id="subject_attendance">  
        <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
        <div class="row">
			<div class="form-group col-md-2">
						<label class="control-label" for="curr_date"><?php esc_attr_e('Date','school-mgt');?></label>
							<input id="curr_date_sub" class="form-control" type="text" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" name="curr_date" readonly>			
					</div>
					<div class="form-group col-md-2">
						<label for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>			
						<?php if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>                 
						<select name="class_id"  id="class_list"  class="form-control validate[required]">
							<option value=" "><?php esc_attr_e('Select class','school-mgt');?></option>
							<?php 
							  foreach(mj_smgt_get_allclass() as $classdata)
							  {  
							  ?>
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
								<option value=""><?php esc_attr_e('Select Section','school-mgt');?></option>
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
									if(isset($_POST['sub_id'])){
											$sub_id=$_POST['sub_id'];
									  ?>
								<?php $allsubjects = mj_smgt_get_subject_by_classid($_POST['class_id']);
								 foreach($allsubjects as $subjectdata)
								  {?>
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
			{
			?>
				<div class="panel-heading">
					<h4 class="panel-title"><?php esc_attr_e('Please Select Class','school-mgt');?></h4>
				</div>
		   <?php  
			}
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
					<form method="post"  class="form-horizontal mt-4 mt-4">
						<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
						<input type="hidden" name="sub_id" value="<?php echo $sub_id;?>" />
						<input type="hidden" name="class_section" value="<?php echo $_REQUEST['class_section'];?>" />
						<input type="hidden" name="curr_date" value="<?php if(isset($_POST['curr_date'])) echo mj_smgt_getdate_in_input_box($_POST['curr_date']); else echo  date("Y-m-d");?>" />
				
						 <div class="panel-heading">
							<h4 class="panel-title"> <?php esc_attr_e('Class','school-mgt')?> : <?php echo mj_smgt_get_class_name($class_id);?> , 
							<?php esc_attr_e('Date','school-mgt')?> : <?php echo mj_smgt_getdate_in_input_box($_POST['curr_date']);?>,<?php esc_attr_e('Subject','school-mgt')?> : <?php echo mj_smgt_get_subject_byid($_POST['sub_id']); ?></h4>
						 </div>
				
						<div class="col-md-12">
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
									 foreach ( $student as $user ) 
									 {
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
										<td><input type="text" name="attendanace_comment_<?php echo $user->ID?>" class="form-control" value="<?php if(!empty($check_attendance)) echo $check_attendance->comment;?>"></td><?php 
										
										echo '</tr>';
										$i++; } ?>
								</table>
							</div>
						<?php wp_nonce_field( 'save_sub_attendence_front_nonce' ); ?>
						<div class="form-group row mb-3">
							<label class="col-sm-4 control-label col-form-label text-md-end" for="enable"><?php esc_attr_e('If student absent then Send  SMS to his/her parents','school-mgt');?></label>
							<div class="col-sm-2 pt-2 ps-0">
								 <div class="checkbox">
									<label>
										<input id="chk_sms_sent1" type="checkbox" <?php $smgt_sms_service_enable = 0;if($smgt_sms_service_enable) echo "checked";?> value="1" name="smgt_sms_service_enable">
									</label>
								</div>				 
							</div>
						</div>
						</div>
						<div class="col-sm-12"> 
							<input type="submit" value="<?php esc_attr_e("Save  Attendance","school-mgt");?>" name="save_sub_attendence" class="btn btn-success" />
						</div>       
					</form>
				</div>
		 <?php 
			}
		}
	}
} ?>
</div>	
</div>
<?php 
?> 