<script>
jQuery(document).ready(function($){
"use strict";	
$('.sdate').datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		maxDate: 0,
		beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		}); 
	$('.edate').datepicker({
		dateFormat: "yy-mm-dd",
		maxDate: 0,
		changeMonth: true,
		changeYear: true,
		beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		});	

		var table =  jQuery('#attendance_list').DataTable({
		responsive: true,
		"order": [[ 0, "asc" ]],
		dom: 'Bfrtip',
				buttons: [
				{
            extend: 'print',
			title: 'View Attendance',},
			{
			extend: 'pdf',
			title: 'View Attendance',
			}
				],
		"aoColumns":[	                  
	    {"bSortable": true},
	    {"bSortable": true},
	    {"bSortable": true},
	    {"bSortable": true},
	    {"bSortable": true},	           
	    {"bSortable": false}],	
		language:<?php echo mj_smgt_datatable_multi_language();?>		
	});

	var table =  jQuery('#students_list').DataTable({
        responsive: true,
		"order": [[ 2, "asc" ]],
		"dom": 'Bfrtip',
		"buttons": [
			'colvis'
		], 
		"aoColumns":[	                  
			{"bSortable": false},
			{"bSortable": false},
			{"bSortable": true},
			{"bSortable": true},
			{"bSortable": true},
			 {"bSortable": true},	                
			{"bSortable": true},
			{"bSortable": true},              
			{"bSortable": false}],
		   language:<?php echo mj_smgt_datatable_multi_language();?>		
		});
	jQuery('#checkbox-select-all').on('click', function(){     
		var rows = table.rows({ 'search': 'applied' }).nodes();
		jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
	}); 
   
	 $("#delete_selected").on('click', function()
		{	
			if ($('.select-checkbox:checked').length == 0 )
			{
				alert(language_translate2.one_record_select_alert);
				return false;
			}
		else{
				var alert_msg=confirm(language_translate2.delete_record_alert);
				if(alert_msg == false)
				{
					return false;
				}
				else
				{
					return true;
				}
			}
	});

	$('#upload_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	

	jQuery('#student_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	 
		jQuery('#birth_date').datepicker({
			dateFormat: "yy-mm-dd",
			maxDate : 0,
			changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+25',
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			},    
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "/" + year);
	        }                    
		}); 

		$('.space_validation').on('keypress',function( e ) 
					{
					   if(e.which === 32) 
						 return false;
					});									
					//custom field datepicker
					$('.after_or_equal').datepicker({
						dateFormat: "yy-mm-dd",										
						minDate:0,
						beforeShow: function (textbox, instance) 
						{
							instance.dpDiv.css({
								marginTop: (-textbox.offsetHeight) + 'px'                   
							});
						}
					}); 
					$('.date_equals').datepicker({
						dateFormat: "yy-mm-dd",
						minDate:0,
						maxDate:0,										
						beforeShow: function (textbox, instance) 
						{
							instance.dpDiv.css({
								marginTop: (-textbox.offsetHeight) + 'px'                   
							});
						}
					}); 
					$('.before_or_equal').datepicker({
						dateFormat: "yy-mm-dd",
						maxDate:0,
						beforeShow: function (textbox, instance) 
						{
							instance.dpDiv.css({
								marginTop: (-textbox.offsetHeight) + 'px'                   
							});
						}
					}); 


	$('#upload_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	

	var table =  jQuery('#exam_list').DataTable({
						responsive: true,
						"aoColumns":[	                  
							{"bSortable": true},
							{"bSortable": false}],
						 language:<?php echo mj_smgt_datatable_multi_language();?>
	});

	$(".view_more_details_div").on("click", ".view_more_details", function(event)
	{
		$('.view_more_details_div').removeClass("d-block");
		$('.view_more_details_div').addClass("d-none");

		$('.view_more_details_less_div').removeClass("d-none");
		$('.view_more_details_less_div').addClass("d-block");

		$('.user_more_details').removeClass("d-none");
		$('.user_more_details').addClass("d-block");

	});		
	$(".view_more_details_less_div").on("click", ".view_more_details_less", function(event)
	{
		$('.view_more_details_div').removeClass("d-none");
		$('.view_more_details_div').addClass("d-block");

		$('.view_more_details_less_div').removeClass("d-block");
		$('.view_more_details_less_div').addClass("d-none");

		$('.user_more_details').removeClass("d-block");
		$('.user_more_details').addClass("d-none");
	});

	var table =  jQuery('#parents_list').DataTable({
			responsive: true,
			"order": [[ 0, "asc" ]],
			"aoColumns":[	                  
			{"bSortable": true},
			{"bSortable": true},
			{"bSortable": true},
			{"bSortable": true},
			{"bSortable": true}],		
			language:<?php echo mj_smgt_datatable_multi_language();?>	
		});

});

//Custom Field File Validation//
function Smgt_custom_filed_fileCheck(obj)
{	
   "use strict";
	var fileExtension = $(obj).attr('file_types');
	var fileExtensionArr = fileExtension.split(',');
	var file_size = $(obj).attr('file_size');
	
	var sizeInkb = obj.files[0].size/1024;
	
	if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtensionArr) == -1)
	{										
		alert("Only "+fileExtension+" formats are allowed.");
		$(obj).val('');
	}	
	else if(sizeInkb > file_size)
	{										
		alert("Only "+file_size+" kb size is allowed.");
		$(obj).val('');	
	}
}
//Custom Field File Validation//
</script>
<?php 
$custom_field_obj =new Smgt_custome_field;
	$obj_mark = new Marks_Manage(); 
	$role	='student';
	if(isset($_POST['active_user']))
	{		
		$class = get_user_meta($_REQUEST['act_user_id'],'class_name',true);		
		$args = array('meta_query'	=>
			array('relation' => 'AND',
				array('key'	=>'class_name','value'=>$class),
				array('key'=>'roll_id','value'=>$_REQUEST['roll_id'])
			),
			'role'=>'student');
				
		$userbyroll_no	=	get_users($args);		
		$is_rollno = count($userbyroll_no);	
		
		if($is_rollno)
		{
			wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=3'); 
		}
		else
		{	
			update_user_meta( $_POST['act_user_id'], "roll_id", $_POST['roll_id'] );	
			if(isset($_POST['smgt_student_mail_service_enable']) || isset($_POST['smgt_studnet_sms_service_enable']) )
			{
				if(isset($_POST['smgt_student_mail_service_enable']))
				{
					$active_user_id		= 	$_REQUEST['act_user_id'];			
					$class_name		=	get_user_meta($active_user_id,'class_name',true);
					$user_info 	= 	get_userdata($_POST['act_user_id']);
						$to 	= 	$user_info->user_email;           
						$subject	= 	get_option('student_activation_title'); 
						$Seach['{{student_name}}']	 =	 $user_info->display_name;
						$Seach['{{user_name}}']		 =	 $user_info->user_login;
						$Seach['{{class_name}}']	 =	 mj_smgt_get_class_name($class_name);
						$Seach['{{email}}']			 =	 $to;
						$Seach['{{school_name}}']	 =	 get_option( 'smgt_school_name' );			
						$MsgContent 	= 	mj_smgt_string_replacement($Seach,get_option('student_activation_mailcontent'));
						mj_smgt_send_mail($to,$subject,$MsgContent);
					//----------- STUDENT ASSIGNED TEACHER MAIL ------------//
					$TeacherIDs = mj_smgt_check_class_exits_in_teacher_class($class_name);			
					$TeacherEmail = array();
					$string['{{school_name}}']  = get_option('smgt_school_name');
					$string['{{student_name}}'] =  mj_smgt_get_display_name($_POST['act_user_id']);
					$subject = get_option('student_assign_teacher_mail_subject');
					$MessageContent = get_option('student_assign_teacher_mail_content');			
					foreach($TeacherIDs as $teacher)
					{		
						$TeacherData = get_userdata($teacher);		
						$string['{{teacher_name}}']= mj_smgt_get_display_name($TeacherData->ID);
						$message = mj_smgt_string_replacement($string,$MessageContent);	
						mj_smgt_send_mail($TeacherData->user_email,$subject,$message);
					}
				}
				/* Approved SMS Notification */
				if(isset($_POST['smgt_studnet_sms_service_enable'])) 
				{
					$user_info 	= 	get_userdata($_POST['act_user_id']);
					$number=get_user_meta($_POST['act_user_id'], 'mobile_number',true);
					$student_number= "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).$number;
					$message_content = " Your account with ".get_option( 'smgt_school_name' )." is approved";
					$current_sms_service 	= 	get_option( 'smgt_sms_service');	
					if(is_plugin_active('sms-pack/sms-pack.php'))
					{								
						$args = array();
						$args['mobile']=$student_number;
						$args['message']=$message_content;					
						$args['message_from']='attendanace';					
						$args['message_side']='front';					
						if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' ||$current_sms_service=='ViaNettSMS' || $current_sms_service=='africastalking')
						{
							$send = send_sms($args);					
						}
					}
					else
					{							
						$reciever_number = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($_POST['act_user_id'], 'mobile_number',true);		
						$message_content = " Your account with ".get_option( 'smgt_school_name' )." is approved";
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
							$mobile_number=get_user_meta($_POST['act_user_id'], 'mobile_number',true);
							$country_code="+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));
							$message = $message_content; // Message Text
							smgt_msg91_send_mail_function($mobile_number,$message,$country_code);
						}								
					} 
				}	
			}		
			$active_user_id	= $_REQUEST['act_user_id'];
			if(get_user_meta($active_user_id, 'hash', true))
				delete_user_meta($active_user_id, 'hash');
			wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=7');			
		}
	}
	 
	if(isset($_POST['exportstudentin_csv']))
	{
		
		if($_POST['class_name'] != "" && $_POST['class_section'] == "")
		{			
			$student_list = get_users(array('meta_key' => 'class_name', 'meta_value' => $_POST['class_name'], 'role'=>'student'));
		}
		elseif($_POST['class_section'] != "")
		{
			$args = array(
				'role'=>'student',
				'meta_query' => array(
				array(
					'key' => 'class_name',
					'value' => $_POST['class_name'],					
				),
				array(
					'key' => 'class_section',
					'value' =>$_POST['class_section'] 					
				)
				)
			);			
			$student_list = get_users($args);
		}		
		else
		{
			$student_list = get_users(array('role'=>'student'));
		}
		
		if(!empty($student_list))
		{
			$header = array();			
			$header[] = 'Username';
			$header[] = 'Email';
			$header[] = 'Roll No';
			$header[] = 'Class Name';
			$header[] = 'First Name';
			$header[] = 'Middle Name';
			$header[] = 'Last Name';			
			$header[] = 'Gender';
			$header[] = 'Birth Date';
			$header[] = 'Address';
			$header[] = 'City Name';
			$header[] = 'State Name';
			$header[] = 'Zip Code';
			$header[] = 'Mobile Number';
			$header[] = 'Alternate Mobile Number';			
			$header[] = 'Phone Number';	
			$filename='Reports/export_student.csv';
			$fh = fopen(SMS_PLUGIN_DIR.'/admin/'.$filename, 'w') or die("can't open file");
			fputcsv($fh, $header);
			
			foreach($student_list as $retrive_data)
			{
				$row = array();
				$user_info = get_userdata($retrive_data->ID);
				
				$row[] = $user_info->user_login;
				$row[] = $user_info->user_email;
				$row[] =  get_user_meta($retrive_data->ID, 'roll_id',true);				
				$class_id=  get_user_meta($retrive_data->ID, 'class_name',true);	
				$classname=mj_smgt_get_class_name($class_id);	
				$row[] = $classname;	
				$row[] =  get_user_meta($retrive_data->ID, 'first_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'middle_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'last_name',true);
				$row[] =  get_user_meta($retrive_data->ID, 'gender',true);
				$row[] =  get_user_meta($retrive_data->ID, 'birth_date',true);
				$row[] =  get_user_meta($retrive_data->ID, 'address',true);
				$row[] =  get_user_meta($retrive_data->ID, 'city',true);
				$row[] =  get_user_meta($retrive_data->ID, 'state',true);
				$row[] =  get_user_meta($retrive_data->ID, 'zip_code',true);
				$row[] =  get_user_meta($retrive_data->ID, 'mobile_number',true);
				$row[] =  get_user_meta($retrive_data->ID, 'alternet_mobile_number',true);
				$row[] =  get_user_meta($retrive_data->ID, 'phone',true);				
				fputcsv($fh, $row);				
			}
			
			fclose($fh);
	
		//download csv file.
		ob_clean();
		$file=SMS_PLUGIN_DIR.'/admin/Reports/export_student.csv';//file location
		
		$mime = 'text/plain';
		header('Content-Type:application/force-download');
		header('Pragma: public');       // required
		header('Expires: 0');           // no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Content-Transfer-Encoding: binary');
		//header('Content-Length: '.filesize($file_name));      // provide file size
		header('Connection: close');
		readfile($file);		
		exit;	
			
	}
	else
	{
		echo "<div class='parent-error'>Records not found.</div>";
	}
}

if(isset($_POST['save_student']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_teacher_admin_nonce' ) )
	{
		$firstname	=	mj_smgt_onlyLetter_specialcharacter_validation($_POST['first_name']);
		$lastname	=	mj_smgt_strip_tags_and_stripslashes($_POST['last_name']);
		$userdata 	= 	array(
			'user_login'	=>	mj_smgt_username_validation($_POST['username']),			
			'user_nicename'	=>	NULL,
			'user_email'	=>	mj_smgt_email_validation($_POST['email']),
			'user_url'		=>	NULL,
			'display_name'	=>	$firstname." ".$lastname,
		);
		
		if($_POST['password'] != "")
			$userdata['user_pass']=mj_smgt_password_validation($_POST['password']);
			
		if(isset($_POST['smgt_user_avatar']) && $_POST['smgt_user_avatar'] != "")
		{
			$photo	=	$_POST['smgt_user_avatar'];
		}
		else
		{
			$photo	=	"";
		}
		if (get_option( 'smgt_enable_virtual_classroom' ) == 'yes')
		{
			$zoom_add_status = 'yes';
		}
		else
		{
			$zoom_add_status = 'no';
		}
		$usermetadata	=	array(
			'roll_id'	=>	mj_smgt_address_description_validation($_POST['roll_id']),
			'middle_name'	=>	mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']),
			'gender'	=>	mj_smgt_onlyLetterSp_validation($_POST['gender']),
			'birth_date'=>	$_POST['birth_date'],
			'address'	=>	mj_smgt_address_description_validation($_POST['address']),
			'city'		=>	mj_smgt_city_state_country_validation($_POST['city_name']),
			'state'		=>	mj_smgt_city_state_country_validation($_POST['state_name']),
			'zip_code'	=>	mj_smgt_onlyLetterNumber_validation($_POST['zip_code']),
			'class_name'	=>	mj_smgt_onlyNumberSp_validation($_POST['class_name']),
			'class_section'	=>	mj_smgt_onlyNumberSp_validation($_POST['class_section']),
			'phone'		=>	mj_smgt_phone_number_validation($_POST['phone']),
			'mobile_number'	=>	mj_smgt_phone_number_validation($_POST['mobile_number']),
			'alternet_mobile_number'	=>	mj_smgt_phone_number_validation($_POST['alternet_mobile_number']),
			'smgt_user_avatar'	=>	$photo,	
			'zoom_add_status'	=>	$zoom_add_status,		
			'created_by'=>get_current_user_id()			
		);
		$userbyroll_no	=	get_users(
			array('meta_query'	=>
				array('relation' => 'AND',
					array('key'	=>'class_name','value'=>$_POST['class_name']),
					array('key'=>'roll_id','value'=>mj_smgt_strip_tags_and_stripslashes($_POST['roll_id']))
				),
				'role'=>'student')
		);
		$is_rollno = count($userbyroll_no);	
		if($_REQUEST['action']=='edit')
		{
			$userdata['ID']	=	$_REQUEST['student_id'];
			$roll_no_cheack = mj_smgt_cheack_student_rollno_exist_or_not($_POST['roll_id'],$_REQUEST['student_id']);
			 if($roll_no_cheack == 1)
			{ 
				$result	=	mj_smgt_update_user($userdata,$usermetadata,$firstname,$lastname,$role);
				// Custom Field File Update //
				$custom_field_file_array=array();
				 
				if(!empty($_FILES['custom_file']['name']))
				{
					$count_array=count($_FILES['custom_file']['name']);
					 
					for($a=0;$a<$count_array;$a++)
					{			
						foreach($_FILES['custom_file'] as $image_key=>$image_val)
						{
							foreach($image_val as $image_key1=>$image_val2)
							{
								if($_FILES['custom_file']['name'][$image_key1]!='')
								{ 
									$custom_file_array[$image_key1]=array(
									'name'=>$_FILES['custom_file']['name'][$image_key1],
									'type'=>$_FILES['custom_file']['type'][$image_key1],
									'tmp_name'=>$_FILES['custom_file']['tmp_name'][$image_key1],
									'error'=>$_FILES['custom_file']['error'][$image_key1],
									'size'=>$_FILES['custom_file']['size'][$image_key1]
									);							
								}						
							}
						}
					}	
					if(!empty($custom_file_array))
					{
						foreach($custom_file_array as $key=>$value)		
						{
						 			
							global $wpdb;
							$wpnc_custom_field_metas = $wpdb->prefix . 'custom_field_metas';
			
							$get_file_name=$custom_file_array[$key]['name'];
						 
							$custom_field_file_value=mj_smgt_load_documets_new($value,$value,$get_file_name);	
							//Add File in Custom Field Meta//				
							$module='student';					
							$updated_at=date("Y-m-d H:i:s");
							$update_custom_meta_data =$wpdb->query($wpdb->prepare("UPDATE `$wpnc_custom_field_metas` SET `field_value` = '$custom_field_file_value',updated_at='$updated_at' WHERE `$wpnc_custom_field_metas`.`module` = %s AND  `$wpnc_custom_field_metas`.`module_record_id` = %d AND `$wpnc_custom_field_metas`.`custom_fields_id` = %d",$module,$result,$key));
						} 	
					}		 		
				}
			
				$update_custom_field=$custom_field_obj->mj_smgt_update_custom_field_metas('student',$_POST['custom'],$result);
				
				wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=2');
			} 
			else
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=3');
			} 
										
		}
		else
		{
			if( !email_exists( $_POST['email'] ) && !username_exists( mj_smgt_strip_tags_and_stripslashes($_POST['username'] )))
			{			
				if($is_rollno)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=3'); 
				}
				else 
				{						
					$result	= mj_smgt_add_newuser($userdata,$usermetadata,$firstname,$lastname,$role);
					
					// Custom Field File Insert //
					$custom_field_file_array=array();
					if(!empty($_FILES['custom_file']['name']))
					{
						$count_array=count($_FILES['custom_file']['name']);
						
						for($a=0;$a<$count_array;$a++)
						{			
							foreach($_FILES['custom_file'] as $image_key=>$image_val)
							{
								foreach($image_val as $image_key1=>$image_val2)
								{
									if($_FILES['custom_file']['name'][$image_key1]!='')
									{  	
										$custom_file_array[$image_key1]=array(
										'name'=>$_FILES['custom_file']['name'][$image_key1],
										'type'=>$_FILES['custom_file']['type'][$image_key1],
										'tmp_name'=>$_FILES['custom_file']['tmp_name'][$image_key1],
										'error'=>$_FILES['custom_file']['error'][$image_key1],
										'size'=>$_FILES['custom_file']['size'][$image_key1]
										);							
									}	
								}
							}
						}			
						if(!empty($custom_file_array))
						{
							foreach($custom_file_array as $key=>$value)		
							{	
								global $wpdb;
								$wpnc_custom_field_metas = $wpdb->prefix . 'custom_field_metas';
				
								$get_file_name=$custom_file_array[$key]['name'];	
								
								$custom_field_file_value=mj_smgt_load_documets_new($value,$value,$get_file_name);		
								
								//Add File in Custom Field Meta//
								$custom_meta_data['module']='student';
								$custom_meta_data['module_record_id']=$result;
								$custom_meta_data['custom_fields_id']=$key;
								$custom_meta_data['field_value']=$custom_field_file_value;
								$custom_meta_data['created_at']=date("Y-m-d H:i:s");
								$custom_meta_data['updated_at']=date("Y-m-d H:i:s");	
								 
								$insert_custom_meta_data=$wpdb->insert($wpnc_custom_field_metas, $custom_meta_data );
								 
							} 	
						}		 		
					}
					$add_custom_field=$custom_field_obj->mj_smgt_add_custom_field_metas('student',$_POST['custom'],$result);					
					 
					if($result)
					{ 
						wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=1'); 	  
					}
				}
			}
			else 
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=4');
			}
		}
    }
}	
	// -----------Delete Code--------
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
{
	$childs=get_user_meta($_REQUEST['student_id'], 'parent_id', true);
	if(!empty($childs))
	{
		foreach($childs as $key=>$childvalue)
		{					
			$parents=get_user_meta($childvalue, 'child',true);
			if(!empty($parents))
			{
				if(($key = array_search($_REQUEST['student_id'], $parents)) !== false) 
				{
					unset($parents[$key]);						
					update_user_meta( $childvalue,'child', $parents );							
				}					
			}				
		}
	}
		
	$result=mj_smgt_delete_usedata($_REQUEST['student_id']);
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=5');
	}
}

if(isset($_REQUEST['delete_selected']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
	{
		$childs=get_user_meta($id, 'parent_id', true);			
		if(!empty($childs))
		{
			foreach($childs as $key=>$childvalue)
			{						
				$parents=get_user_meta($childvalue, 'child',true);						
				if(!empty($parents))
				{
					if(($key = array_search($id, $parents)) !== false)
					{
						unset($parents[$key]);						
						update_user_meta( $childvalue,'child', $parents );								
					}						
				}					
			}
		}			
		$result=mj_smgt_delete_usedata($id);
	}

if($result){
		wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=5');
	}
}

if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'pdf')
{
	$sudent_id = $_REQUEST['student'];
	mj_smgt_downlosd_smgt_result_pdf($sudent_id);
}

if(isset($_REQUEST['upload_csv_file']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'upload_teacher_admin_nonce' ) )
	{
		if(isset($_FILES['csv_file']))
		{				
			$errors= array();
			$file_name = $_FILES['csv_file']['name'];
			$file_size =$_FILES['csv_file']['size'];
			$file_tmp =$_FILES['csv_file']['tmp_name'];
			$file_type=$_FILES['csv_file']['type'];
			$value = explode(".", $_FILES['csv_file']['name']);
			$file_ext = strtolower(array_pop($value));				
			$extensions = array("csv");
			$upload_dir = wp_upload_dir();
			if(in_array($file_ext,$extensions )=== false)
			{
				$err=esc_attr__('This file not allowed, please choose a CSV file.','school-mgt');
				$errors[]=$err;
			}
			if($file_size > 2097152)
			{
				$errors[]='File size limit 2 MB';
			}
			
			if(empty($errors)==true)
			{	
				$rows = array_map('str_getcsv', file($file_tmp));
				$header = array_map('trim',array_map('strtolower',array_shift($rows)));
				$csv = array();
				foreach ($rows as $row) 
				{
					$csv = array_combine($header, $row);
					$username = $csv['username'];
					 
					$email = $csv['email'];
					$user_id = 0;
					if(isset($csv['password']))
					{
					  $password = $csv['password'];
					}
					else
					{
						$password = rand();
					}
					$problematic_row = false;
					$studentExists = true;
					if( username_exists($username) )
					{ // if user exists, we take his ID by login
						$user_object = get_user_by( "login", $username );
						$user_id = $user_object->ID;
						if( !empty($password) )
							wp_set_password( $password, $user_id );
					}
					elseif( email_exists( $email ) ){ // if the email is registered, we take the user from this
						$user_object = get_user_by( "email", $email );
						$user_id = $user_object->ID;					
						$problematic_row = true;
						if( !empty($password) )
							wp_set_password( $password, $user_id );
					}
					else
					{
						$studentExists = false;
						if( empty($password) ) // if user not exist and password is empty but the column is set, it will be generated
							$password = wp_generate_password();						
							$user_id = wp_create_user($username, $password, $email);
						
					}

					if( is_wp_error($user_id) ){ // in case the user is generating errors after this checks
						echo '<script>alert("Problems with user: ' . $username . ', we are going to skip");</script>';
						continue;
					}
					
					if(!(is_multisite() && is_super_admin( $user_id ) ))
						
						$studentClass = get_user_meta($user_id,"class_name",true);

						if($studentExists && $studentClass != $_POST['class_name']){
							echo $csv['email'] .'-';
							continue;
						}
						
						wp_update_user(array ('ID' => $user_id, 'role' => 'student')) ;
						update_user_meta( $user_id, "active", true );
						update_user_meta( $user_id, "class_name", $_POST['class_name']);					
					
						$userbyroll_no	=	get_users(
							array('meta_query'	=>
								array('relation' => 'AND',
									array('key'	=>'class_name','value'=>$_POST['class_name']),
									array('key'=>'roll_id','value'=>$csv['roll no'])
								),
								'role'=>'student'
							)
						);
						
						$rollReset = true;
						if($studentExists){
							$rollReset = false;
						}else{
							// student not exists
							if(count($userbyroll_no) || $csv['roll no'] == '')
							{  // roll exists
								$rollReset = true;
							}else{ // roll not exists
								$rollReset = false;
							}
						}

						if($rollReset)
						{
							$roll = "";	
							add_user_meta($user_id,'hash',rand());
						}
						else{						
							$roll = $csv['roll no'];
						}
					
						$user_id1 = wp_update_user( array( 'ID' => $user_id, 'display_name' =>$csv['first name'].' '.$csv['last name']) );
						if(isset($_POST['class_section']))
							update_user_meta( $user_id, "class_section", $_POST['class_section'] );
						if(isset($csv['roll no']))
							update_user_meta( $user_id, "roll_id", $roll );
						if(isset($csv['first name']))
							update_user_meta( $user_id, "first_name", $csv['first name'] );
						if(isset($csv['last name']))
							update_user_meta( $user_id, "last_name", $csv['last name'] );
						if(isset($csv['middle name']))
							update_user_meta( $user_id, "middle_name", $csv['middle name'] );
						if(isset($csv['gender']))
							update_user_meta( $user_id, "gender", $csv['gender'] );
						if(isset($csv['birth date']))
							update_user_meta( $user_id, "birth_date", $csv['birth date'] );
						if(isset($csv['address']))
							update_user_meta( $user_id, "address", $csv['address'] );
						if(isset($csv['city name']))
							update_user_meta( $user_id, "city", $csv['city name'] );
						if(isset($csv['state name']))
							update_user_meta( $user_id, "state", $csv['state name'] );						
						if(isset($csv['zip code']))
							update_user_meta( $user_id, "zip_code", $csv['zip code'] );
						if(isset($csv['mobile number']))
							update_user_meta( $user_id, "mobile_number", $csv['mobile number'] );
						if(isset($csv['alternate mobile number']))
							update_user_meta( $user_id, "alternet_mobile_number", $csv['alternate mobile number'] );						
						if(isset($csv['phone number']))
							update_user_meta( $user_id, "phone", $csv['phone number'] );					
						$success = 1;
				}
			}
			else
			{
				foreach($errors as &$error) echo $error;
			}
					
			if(isset($success))
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_student&tab=studentlist&message=6');
			} 
		}
    }
}
?>
<!-- POP up code -->

<div class="popup-bg">
    <div class="overlay-content max_height_overflow">
		<div class="modal-content">
			<div class="result"></div>
			<div class="view-parent"></div>
		</div>
    </div>    
</div>
<?php 
if(isset($_REQUEST['attendance']) && $_REQUEST['attendance'] == 1)
{ ?>

<div class="page-inner">
	<div class="page-title"> 
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle rounded-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<?php
	$student_data=get_userdata($_REQUEST['student_id']);
	?>
	<div id="main-wrapper">
		<div class="row">
			<div class="panel panel-white">
				<div class="panel-body">
				<h2 class="nav-tab-wrapper">
			    	<a href="?page=smgt_student&student_id=<?php echo $_REQUEST['student_id'];?>&attendance=1" class="nav-tab nav-tab-active">
					<?php echo '<span class="dashicons dashicons-menu"></span>'.esc_attr__('View Attendance', 'school-mgt'); ?></a>
				</h2>
				<form name="wcwm_report" action="" method="post">
				
				<input type="hidden" name="attendance" value=1> 
				<input type="hidden" name="user_id" value=<?php echo $_REQUEST['student_id'];?>> 
					<div class="row">
						<div class="col-md-3 col-sm-4 col-xs-12">	
							<?php
							$umetadata=mj_smgt_get_user_image($_REQUEST['student_id']);
							if(empty($umetadata['meta_value']))
							{
								echo '<img class="img-circle rounded-circle img-responsive member-profile user_height_width" src='.get_option( 'smgt_student_thumb' ).'>';
							}
							else
								echo '<img class="img-circle rounded-circle img-responsive member-profile user_height_width" src='.$umetadata['meta_value'].'>';
							?>
						</div>
							
						<div class="col-md-9 col-sm-8 col-xs-12 ">
							<div class="row">
								<h2><?php echo $student_data->display_name;?></h2>
							</div>
							<div class="row">
								<div class="col-md-4 col-sm-3 col-xs-12">
									<i class="fa fa-envelope"></i>&nbsp;
									
									<span class="email-span"><?php echo $student_data->user_email;?></span>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
									<i class="fa fa-phone"></i>&nbsp;
									<span><?php echo $student_data->phone;?></span>
								</div>
								<div class="col-md-5 col-sm-3 col-xs-12 no-padding">
									<i class="fa fa-list-alt"></i>&nbsp;
									<span><?php echo $student_data->roll_id;?></span>
								</div>
							</div>					
						</div>
					
					<div class="mt-4 row">
						<div class="form-group col-md-3">
							<label for="exam_id"><?php esc_attr_e('Start Date','school-mgt');?></label>
							<input type="text"  class="form-control sdate" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d');?>" readonly>
						</div>
						<div class="form-group col-md-3">
							<label for="exam_id"><?php esc_attr_e('End Date','school-mgt');?></label>
							<input type="text"  class="form-control edate" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>" readonly>
						</div>
						<div class="form-group col-md-3 button-possition">
							<label for="subject_id">&nbsp;</label>
							<input type="submit" name="view_attendance" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info"/>
						</div>
					</div>	
				</form>
<div class="clearfix"></div>
<?php if(isset($_REQUEST['view_attendance']))
{	
	$start_date = $_REQUEST['sdate'];
	$end_date = $_REQUEST['edate'];
	$user_id = $_REQUEST['user_id'];
	$attendance = mj_smgt_view_student_attendance($start_date,$end_date,$user_id);	
	
	$curremt_date =$start_date;
?>	

	<div class="panel-body">
		
	<table id="attendance_list" class="display" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th width="200px"><?php esc_attr_e('Student Name','school-mgt');?></th>
				<th width="200px"><?php esc_attr_e('Class Name','school-mgt');?></th>
				<th width="200px"><?php esc_attr_e('Date','school-mgt');?></th>
				<th><?php esc_attr_e('Day','school-mgt');?></th>
				<th><?php esc_attr_e('Attendance','school-mgt');?></th>
				<th><?php esc_attr_e('Comment','school-mgt');?></th>
			</tr>
		</thead>
 
        <tfoot>
            <tr>
				<th width="200px"><?php esc_attr_e('Student Name','school-mgt');?></th>
				<th width="200px"><?php esc_attr_e('Class Name','school-mgt');?></th>
				<th width="200px"><?php esc_attr_e('Date','school-mgt');?></th>
				<th><?php esc_attr_e('Day','school-mgt');?></th>
				<th><?php esc_attr_e('Attendance','school-mgt');?></th>
				<th><?php esc_attr_e('Comment','school-mgt');?></th>
			</tr>
        </tfoot>
 
        <tbody>
			<?php
				foreach($attendance as $attendance_data)
				{
						
					echo '<td>';
					echo mj_smgt_get_display_name($attendance_data->user_id);
					echo '</td>';
					
					
					echo '<td>';
					echo mj_smgt_get_class_name_by_id(get_user_meta($attendance_data->user_id, 'class_name',true));
					echo '</td>';
					
					echo '<td>';
					echo mj_smgt_getdate_in_input_box($attendance_data->attendence_date);
					echo '</td>';
					

					echo '<td>';
					echo date("D", strtotime($attendance_data->attendence_date));
					echo '</td>';
					
					$attendance_status = $attendance_data->status;
					if(!empty($attendance_status))
					{
						echo '<td>';
						if($attendance_status=="Present")
						{
							echo esc_attr__('Present','school-mgt');
						}
						elseif($attendance_status=="Late")
						{
							echo esc_attr__('Late','school-mgt');
						}
						else
						{
							echo esc_attr__('Absent','school-mgt');
						}
						echo '</td>';
					}
					else 
					{
						echo '<td>';
						echo esc_attr__('Absent','school-mgt');
						echo '</td>';
					}
					
				    echo '<td>';
					echo $attendance_data->comment;
					echo '</td>';
					echo '</tr>';
					
				}
			?>
        </tbody>        
    </table>
	</div>
<?php } ?>
</div>
</div>
</div>
</div>
</div>
<?php 
}
else 
{
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'studentlist';
?>
<div class="page-inner">
	<div class="page-title"> 
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle rounded-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div id="main-wrapper">
	<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Student Added Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Student Updated Successfully.','school-mgt');
			break;
		case '3':
			$message_string = esc_attr__('Student Roll No. Already Exist.','school-mgt');
			break;
		case '4':
			$message_string = esc_attr__("Student's Username Or Email-id Already Exist.",'school-mgt');
			break;
		case '5':
			$message_string = esc_attr__('Student Deleted Successfully.','school-mgt');
			break;
		case '6':
			$message_string = esc_attr__('Student CSV Successfully Uploaded.','school-mgt');
			break;
		case '7':
			$message_string = esc_attr__('Student Activated Successfully.','school-mgt');
			break;
		
	}
	if($message)
	{ ?>
		<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
			<p><?php echo $message_string;?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
<?php } ?>
	<div class="row">
		<div class="col-md-12">
		<div class="panel panel-white">
			<div class="panel-body">
				<h2 class="nav-tab-wrapper">
				<a href="?page=smgt_student&tab=studentlist" class="nav-tab <?php echo $active_tab == 'studentlist' ? 'nav-tab-active' : ''; ?>">
				<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_attr__('Student List', 'school-mgt'); ?></a>
				 <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{ ?>
				<a href="?page=smgt_student&tab=addstudent&&action=edit&student_id=<?php echo $_REQUEST['student_id'];?>" class="nav-tab <?php echo $active_tab == 'addstudent' ? 'nav-tab-active' : ''; ?>">
				<?php esc_attr_e('Edit Student', 'school-mgt'); ?></a>  
				<?php 
				}
				else
				{?>
				<a href="?page=smgt_student&tab=addstudent" class="nav-tab <?php echo $active_tab == 'addstudent' ? 'nav-tab-active' : ''; ?>">
				<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_attr__('Add New student', 'school-mgt'); ?></a>  
				<?php }?>
				<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_student')
				{ ?>
				<a href="?page=smgt_student&tab=view_student&action=view_student&student_id=<?php echo $_REQUEST['student_id'];?>" class="nav-tab <?php echo $active_tab == 'view_student' ? 'nav-tab-active' : ''; ?>">
				<?php echo '<span class="fa fa-eye"></span> '.esc_attr__('View Student', 'school-mgt'); ?></a>
				<?php
				}
				?>
				<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_receipt')
				{ ?>
				<a href="?page=smgt_student&tab=view_exam_receipt&action=view_receipt&student_id=<?php echo $_REQUEST['student_id'];?>" class="nav-tab <?php echo $active_tab == 'view_exam_receipt' ? 'nav-tab-active' : ''; ?>">
				<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_attr__('Exam Receipt List', 'school-mgt'); ?></a>
				<?php
				}
				?>
				<a href="?page=smgt_student&tab=uploadstudent" class="nav-tab <?php echo $active_tab == 'uploadstudent' ? 'nav-tab-active' : ''; ?>">
				<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_attr__('Upload Student CSV', 'school-mgt'); ?></a>
			   
				 <a href="?page=smgt_student&tab=exportstudent" class="nav-tab margin_bottom <?php echo $active_tab == 'exportstudent' ? 'nav-tab-active' : ''; ?>">
				<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_attr__('Export Student', 'school-mgt'); ?></a>
		
        
			</h2>
     <?php 
	 if($active_tab == 'studentlist')
	 {
		
	//Report 1 
	?>
   <div class="panel-body"> 
   
        <form method="post">
	<div class="row">
   <div class="form-group col-md-3">
	<label for="class_id"><?php esc_attr_e('Select Class','school-mgt');?></label>			
	<?php 
	$class_id="";
	if(isset($_REQUEST['class_id'])) $class_id=$_REQUEST['class_id']; ?>                 
		<select name="class_id"  id="class_list"  class="form-control ">
			<option value=""><?php esc_attr_e('Select class Name','school-mgt');?></option>
			<?php 
			  foreach(mj_smgt_get_allclass() as $classdata)
			  {  
			  ?>
			   <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
		 <?php }?>
		</select>
	</div>
	<div class="form-group col-md-3">
		<label for="class_id"><?php esc_attr_e('Select Class Section','school-mgt');?></label>			
		<?php 
		$class_section="";
		?>
		<select name="class_section" class="form-control validate[required]" id="class_section">
			<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
			<?php
				if(isset($_REQUEST['class_section']))
				{
					$class_section=$_REQUEST['class_section']; 
					foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
					{  ?>
					 <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
				<?php } 
				}
				?>	
		</select>
	</div>
	<div class="form-group col-md-3 button-possition">
    	<label for="subject_id">&nbsp;</label>
      	<input type="submit" value="<?php esc_attr_e('Go','school-mgt');?>" name="filter_class"  class="btn btn-info"/>
    </div>   
	</div>    
	</form>
	</div>
		 <?php  
			if(isset($_REQUEST['filter_class']) )
			{
				if(empty($_REQUEST['class_id']) && empty($_REQUEST['class_section']))
				{
					$exlude_id = mj_smgt_approve_student_list();
					$studentdata =get_users(array('role'=>'student'));
					
				}
				elseif(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != "")
				{
					$class_id =$_REQUEST['class_id'];
					$class_section =$_REQUEST['class_section'];
					 $studentdata = get_users(array('meta_key' => 'class_section', 'meta_value' =>$class_section,'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student'));	
				}
				elseif(isset($_REQUEST['class_id']) && ($_REQUEST['class_section']) == "")
				{
					$class_id =$_REQUEST['class_id'];
					 $studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student'));	
				}
			}	
			else 
			{

				$exlude_id = mj_smgt_approve_student_list();
				$studentdata =get_users(array('role'=>'student'));
			}
         	?>  
    
        <div class="panel-body">
	
        	<div class="table-responsive">
			<form id="frm-example" name="frm-example" method="post">
        <table id="students_list" class="display admin_student_datatable" cellspacing="0" width="100%">
        	 <thead>
            <tr>
				<th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th> 
				<th><?php echo esc_attr_e( 'Photo', 'school-mgt' ) ;?></th>
                <th><?php echo esc_attr_e( 'Student Name', 'school-mgt' ) ;?></th>
                 <th> <?php echo esc_attr_e( 'Roll No.', 'school-mgt' ) ;?></th>
				<th> <?php echo esc_attr_e( 'Class', 'school-mgt' ) ;?></th>
				 <th> <?php echo esc_attr_e( 'Section', 'school-mgt' ) ;?></th>
                <th> <?php echo esc_attr_e( 'Student Email', 'school-mgt' ) ;?></th>
				<th> <?php echo esc_attr_e( 'Status', 'school-mgt' ) ;?></th>
                <th><?php echo esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
				 <th><?php echo esc_attr_e( 'Photo', 'school-mgt' ) ;?></th>
               <th><?php echo esc_attr_e( 'Student Name', 'school-mgt' ) ;?></th>
                <th> <?php echo esc_attr_e( 'Roll No.', 'school-mgt' ) ;?></th>
			    <th> <?php echo esc_attr_e( 'Class', 'school-mgt' ) ;?></th>
				<th> <?php echo esc_attr_e( 'Section', 'school-mgt' ) ;?></th>
                <th> <?php echo esc_attr_e( 'Student Email', 'school-mgt' ) ;?>
				<th> <?php echo esc_attr_e( 'Status', 'school-mgt' ) ;?></th>
               <th><?php echo esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
                
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		 
		 	if(!empty($studentdata))
			{
				foreach ($studentdata as $retrieved_data){ 
			?>
			<tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" value="<?php echo $retrieved_data->ID;?>"></td>
				<td class="user_image">
				<?php
					$uid=$retrieved_data->ID;
					$umetadata=mj_smgt_get_user_image($uid);
					if(empty($umetadata))
					{
						echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle rounded-circle" />';
					}
					else
					{
						echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle rounded-circle" />';
					}
				?>
				</td>
				<td class="name"><a href="?page=smgt_student&tab=addstudent&action=edit&student_id=<?php echo $retrieved_data->ID;?>">
				<?php echo $retrieved_data->display_name;?></a></td>
				<td class="roll_no">
					<?php 
						if(get_user_meta($retrieved_data->ID, 'roll_id', true))
						echo get_user_meta($retrieved_data->ID, 'roll_id',true);
					?>
				</td>
			    <td class="name"><?php $class_id=get_user_meta($retrieved_data->ID, 'class_name',true);
					echo $classname=mj_smgt_get_class_name($class_id);
				?></td>
				<td class="name">
				<?php 
					$section_name=get_user_meta($retrieved_data->ID, 'class_section',true);
					if($section_name!=""){
						echo mj_smgt_get_section_name($section_name); 
					}
					else
					{
						esc_attr_e('No Section','school-mgt');;
					}
				?>
				</td>
				<td class="email"><?php echo $retrieved_data->user_email;?></td>
				<td> <?php 
					if( get_user_meta($retrieved_data->ID, 'hash', true))
					{
						echo '<span class="btn btn-default active-user" idtest="'.$retrieved_data->ID.'"> ';
							esc_attr_e('Active', 'school-mgt' ) ;
						echo " </span>";
					}
					else
					{
						esc_attr_e( 'Approved', 'school-mgt' );
					}	
					?>
				</td>
				<?php
				 
				?>
				<td class="action"> 
				<a href="?page=smgt_student&tab=view_student&action=view_student&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-success"><?php esc_attr_e('View','school-mgt');?></a>  
				<a href="?page=smgt_student&tab=addstudent&action=edit&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>  
					<a href="?page=smgt_student&tab=studentlist&action=delete&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" 
					onclick="return confirm('Are you sure you want to delete this record?');"><?php esc_attr_e('Delete','school-mgt');?></a> 
					<?php
					$result=mj_smgt_student_exam_receipt_check($retrieved_data->ID);
					if($result)
					{
					?>
						<a href="?page=smgt_student&tab=view_exam_receipt&action=view_receipt&student_id=<?php echo $retrieved_data->ID;?>" class="btn btn-primary"><?php esc_attr_e('Hall Ticket','school-mgt');?></a>  
				<?php	
					}
					?>
					<a href="?page=smgt_student&tab=studentlist&action=result&student_id=<?php echo $retrieved_data->ID;?>" class="show-popup btn btn-default" 
					idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-bar-chart"></i> <?php esc_attr_e('View Result', 'school-mgt');?></a>
					<a href="?page=smgt_student&student_id=<?php echo $retrieved_data->ID;?>&attendance=1" class="btn btn-default" 
					idtest="<?php echo $retrieved_data->ID; ?>"><i class="fa fa-eye"></i> <?php esc_attr_e('View Attendance','school-mgt');?></a>											
				</td>
			</tr>
			<?php } 
			} ?>
          </tbody>        
        </table>
		<div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected" class="btn btn-danger delete_selected"/>
		</div>
		</form>
        	</div>
        </div>
		<?php 	}	
	if($active_tab == 'addstudent')
	{
		require_once SMS_PLUGIN_DIR. '/admin/includes/student/student.php';
	}
	if($active_tab == 'uploadstudent')
	{
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/student/uploadstudent.php';
	}
	if($active_tab == 'view_student')
	{
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/student/view_student.php';
	}
	if($active_tab == 'exportstudent')
	{
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/student/exportstudent.php';
	}
	if($active_tab == 'view_exam_receipt')
	{
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/student/view_exam_receipt.php';
	}
	?>
	</div>
</div>
</div>
</div>
</div>
<?php } ?>