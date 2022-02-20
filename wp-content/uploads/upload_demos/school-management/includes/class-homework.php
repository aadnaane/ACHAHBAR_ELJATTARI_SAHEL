<?php 
class Smgt_Homework
{
	public function mj_smgt_check_valid_extension($filename)
	{
		$flag = 2;
		if($filename != '')
		{
			$flag = 0;
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$valid_extension = array('gif','png','jpg','jpeg');
			if(in_array($ext,$valid_extension) )
			{
				$flag = 1;
			}
		}
		return $flag;
	}
	function mj_smgt_get_delete_records($tablenm,$record_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . $tablenm;
		return $result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE homework_id= %d",$record_id));
	}
	public function mj_smgt_check_uploaded($assign_id)
	{
		global $wpdb;
		$table = $wpdb->prefix."mj_smgt_student_homework";
		$result = $wpdb->get_row("SELECT file FROM {$table} WHERE stu_homework_id = {$assign_id}",ARRAY_A);
			if($result['file'] != "")
			{
				return $result['file'];
			}
			else
			{ 
				return false;
			}
	}
	function mj_smgt_get_class_homework()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'mj_smgt_homework';
		return $result = $wpdb->get_results("SELECT * FROM $table_name");
	}
	function mj_smgt_view_submission($data){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mj_smgt_homework';
		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';
		return $result = $wpdb->get_results("SELECT * FROM $table_name as a LEFT JOIN $table_name2 as b ON a.`homework_id` = b.`homework_id` where a.`homework_id`= $data ");
		
	}
	function mj_smgt_parent_view_detail($child_ids){
		global $wpdb;
		$table_name = $wpdb->prefix . 'mj_smgt_homework';
		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';
		return $result = $wpdb->get_results("SELECT * FROM $table_name as a LEFT JOIN $table_name2 as b ON a.`homework_id` = b.`homework_id`WHERE b.student_id IN ({$child_ids})");
	}
	function mj_smgt_student_view_detail(){
		global $wpdb;
		global $user_ID;
		$table_name = $wpdb->prefix . 'mj_smgt_homework';
		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';
		return $result = $wpdb->get_results("SELECT * FROM $table_name as a LEFT JOIN $table_name2 as b ON a.`homework_id` = b.`homework_id`WHERE b.student_id = $user_ID");
	}
	function mj_smgt_parent_update_detail($data,$student_id){
		global $wpdb;
		global $user_ID;
		$table_name = $wpdb->prefix . 'mj_smgt_homework';
		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';
		return $result = $wpdb->get_results("SELECT * FROM $table_name as a LEFT JOIN $table_name2 as b ON a.`homework_id` = b.`homework_id` WHERE a.`homework_id`=$data AND b.student_id = $student_id");
	}
	
	function mj_smgt_parent_update_detail_api($data,$student_id)
	{
		global $wpdb;
		global $user_ID;
		$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';
		$result = $wpdb->get_row("SELECT * FROM $table_name2 where student_id=$student_id and homework_id=$data");
		return $result;
	}
	function mj_smgt_add_homework($data,$document_data)
	{
			global $current_user;
			global $wpdb;
			$user=$current_user->user_login;
			$table_name=$wpdb->prefix ."mj_smgt_homework";
			$table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';
			$homeworkdata['title']=mj_smgt_address_description_validation($data['title']);
			$homeworkdata['class_name']=$data['class_name'];
			$homeworkdata['section_id']=$data['class_section'];
			$homeworkdata['subject']=$data['subject_id'];
			$homeworkdata['content']=$data['content'];
			$homeworkdata['created_date']=date('Y-m-d H:i:s');
			$homeworkdata['submition_date']= date('Y-m-d',strtotime($data['sdate']));
			$homeworkdata['createdby']=get_current_user_id();
			if(!empty($_REQUEST['homework_id']))
			{
				$homework_id['homework_id']=$_REQUEST['homework_id'];
				$homeworkdata['homework_document']=json_encode($document_data);
				$result = $wpdb->update($table_name,$homeworkdata,$homework_id);
				if($result)
				{
					if(!empty($data['class_section']))
					{
						$class_id =$data['class_name'];
						$studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student'));
	
					}
					else
					{
					   $studentdata = get_users(array('meta_key' => 'class_section', 'meta_value' =>$data['class_section'],
					  'meta_query'=> array(array('key' => 'class_name','value' => $data['class_name'],'compare' => '=')),'role'=>'student'));
					}
					$last=$wpdb->insert_id;
					$homeworstud['homework_id']=$last;
					foreach($studentdata as $student)
					{
						 $homeworstud['student_id']=$student->ID;
						 $result = $wpdb->insert($table_name2,$homeworstud);
					}
					if(empty($studentdata))
					{
						if($data['smgt_enable_homework_mail']== '1')
						{
							foreach($studentdata as $userdata)
							{
								$student_id = $userdata->ID;
								$student_name = $userdata->display_name;
								$student_email = $userdata->user_email;
								
								//send mail notification for parent//
								$parent 		= 	get_user_meta($student_id, 'parent_id', true);
								
								if(!empty($parent))
								{
									foreach($parent as $p)
									{
										$user_info	 	=    get_userdata($p);
										$email_to[] 	=	 $user_info->user_email;		
									}
									foreach($email_to as $eamil)
									{
										$searchArr = array();
										$parent_homework_mail_content = get_option('parent_homework_mail_content');
										$parent_homework_mail_subject = get_option('parent_homework_mail_subject');
										$parerntdata = get_user_by('email',$eamil);							
										$searchArr['{{parent_name}}']	=	$parerntdata->display_name;
										$searchArr['{{student_name}}']	=	$student_name;
										$searchArr['{{title}}']   =  mj_smgt_address_description_validation($data['title']);
										$searchArr['{{submition_date}}']   = mj_smgt_getdate_in_input_box($data['sdate']);
										$searchArr['{{school_name}}']	=	get_option('smgt_school_name');
										$message = mj_smgt_string_replacement($searchArr,$parent_homework_mail_content);
										mj_smgt_send_mail($eamil,$parent_homework_mail_subject,$message);
															
									}
								}
								//send mail notification for student//
								$string = array();
								$string['{{student_name}}']   = $student_name;
								$string['{{title}}']   =  mj_smgt_address_description_validation($data['title']);
								$string['{{submition_date}}']   = mj_smgt_getdate_in_input_box($data['sdate']);
								$string['{{school_name}}'] =  get_option('smgt_school_name');
								$msgcontent                =  get_option('homework_mailcontent');		
								$msgsubject				   =  get_option('homework_title');
								$message = mj_smgt_string_replacement($string,$msgcontent);
								mj_smgt_send_mail($student_email,$msgsubject,$message);  
							}
						}
					}
				}
				return $result;
			}
			else
			{
				$homeworkdata['homework_document']=json_encode($document_data);
				$result=$wpdb->insert($table_name,$homeworkdata);
				if($result)
				{
					if(empty($data['class_section']))
					{
						$class_id =$data['class_name'];
						$studentdata = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student'));
						
					}
					else
					{
					   $studentdata = get_users(array('meta_key' => 'class_section', 'meta_value' =>$data['class_section'],
					  'meta_query'=> array(array('key' => 'class_name','value' => $data['class_name'],'compare' => '=')),'role'=>'student'));
					}
					if(!empty($studentdata))
					{
						$last=$wpdb->insert_id;
						$homeworstud['homework_id']=$last;
						$homeworstud['status']='0';
						$homeworstud['created_by']=get_current_user_id();
						$homeworstud['created_date']=date('Y-m-d H:i:s');
						foreach($studentdata as $student)
						{
							$homeworstud['student_id']=$student->ID;
							$insert = $wpdb->insert($table_name2,$homeworstud);
						}
						if($insert)
						{
							if(isset($data['smgt_enable_homework_mail']) == '1' || isset($data['smgt_enable_homework_sms']) == '1')
							{
								if(isset($data['smgt_enable_homework_mail']) == '1' && isset($data['smgt_enable_homework_sms']) == '1')
								{
									foreach($studentdata as $userdata)
									{
										$student_id = $userdata->ID;
										$student_name = $userdata->display_name;
										$student_email = $userdata->user_email;
										
										//send mail notification for parent//
										$parent 		= 	get_user_meta($student_id, 'parent_id', true);
										
										if(!empty($parent))
										{
											foreach($parent as $p)
											{
												$user_info	 	=    get_userdata($p);
												$email_to[] 	=	 $user_info->user_email;		
											}
											foreach($email_to as $eamil)
											{
												$searchArr = array();
												$parent_homework_mail_content = get_option('parent_homework_mail_content');
												$parent_homework_mail_subject = get_option('parent_homework_mail_subject');
												$parerntdata = get_user_by('email',$eamil);							
												$searchArr['{{parent_name}}']	=	$parerntdata->display_name;
												$searchArr['{{student_name}}']	=	$student_name;
												$searchArr['{{title}}']   =  mj_smgt_address_description_validation($data['title']);
												$searchArr['{{submition_date}}']   =  mj_smgt_getdate_in_input_box($data['sdate']);
												$searchArr['{{school_name}}']	=	get_option('smgt_school_name');
												$message = mj_smgt_string_replacement($searchArr,$parent_homework_mail_content);
												mj_smgt_send_mail($eamil,$parent_homework_mail_subject,$message);
																	
											}
										}
										//send mail notification for student//
										$string = array();
										$string['{{student_name}}']   = $student_name;
										$string['{{title}}']   =  mj_smgt_address_description_validation($data['title']);
										$string['{{submition_date}}']   =  mj_smgt_getdate_in_input_box($data['sdate']);
										$string['{{school_name}}'] =  get_option('smgt_school_name');
										$msgcontent                =  get_option('homework_mailcontent');		
										$msgsubject				   =  get_option('homework_title');
										$message = mj_smgt_string_replacement($string,$msgcontent);
										mj_smgt_send_mail($student_email,$msgsubject,$message);  

									

										$number=get_user_meta($userdata->ID, 'mobile_number',true);
										$student_number= "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).$number;
										$message_content = "New homework has been assign to you";
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
											$reciever_number = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($userdata->ID, 'mobile_number',true);		
											$message_content = "New homework has been assign to you";
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

								if(isset($data['smgt_enable_homework_mail']) == '1')
								{
									foreach($studentdata as $userdata)
									{
										$student_id = $userdata->ID;
										$student_name = $userdata->display_name;
										$student_email = $userdata->user_email;
										
										//send mail notification for parent//
										$parent 		= 	get_user_meta($student_id, 'parent_id', true);
										
										if(!empty($parent))
										{
											foreach($parent as $p)
											{
												$user_info	 	=    get_userdata($p);
												$email_to[] 	=	 $user_info->user_email;		
											}
											foreach($email_to as $eamil)
											{
												$searchArr = array();
												$parent_homework_mail_content = get_option('parent_homework_mail_content');
												$parent_homework_mail_subject = get_option('parent_homework_mail_subject');
												$parerntdata = get_user_by('email',$eamil);							
												$searchArr['{{parent_name}}']	=	$parerntdata->display_name;
												$searchArr['{{student_name}}']	=	$student_name;
												$searchArr['{{title}}']   =  mj_smgt_address_description_validation($data['title']);
												$searchArr['{{submition_date}}']   =  mj_smgt_getdate_in_input_box($data['sdate']);
												$searchArr['{{school_name}}']	=	get_option('smgt_school_name');
												$message = mj_smgt_string_replacement($searchArr,$parent_homework_mail_content);
												mj_smgt_send_mail($eamil,$parent_homework_mail_subject,$message);
																	
											}
										}
										//send mail notification for student//
										$string = array();
										$string['{{student_name}}']   = $student_name;
										$string['{{title}}']   =  mj_smgt_address_description_validation($data['title']);
										$string['{{submition_date}}']   =  mj_smgt_getdate_in_input_box($data['sdate']);
										$string['{{school_name}}'] =  get_option('smgt_school_name');
										$msgcontent                =  get_option('homework_mailcontent');		
										$msgsubject				   =  get_option('homework_title');
										$message = mj_smgt_string_replacement($string,$msgcontent);
										mj_smgt_send_mail($student_email,$msgsubject,$message);   
									}
								}

								if(isset($data['smgt_enable_homework_sms']) == '1')
								{
									foreach($studentdata as $userdata)
									{
										$number=get_user_meta($userdata->ID, 'mobile_number',true);
										$student_number= "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).$number;
										$message_content = "New homework has been assign to you";
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
											$reciever_number = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($userdata->ID, 'mobile_number',true);		
											$message_content = "New homework has been assign to you";
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
							}
						}
						
					}
				}
				return $result;
			}
    }	
	function mj_smgt_get_all_homeworklist()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "mj_smgt_homework";
		return $rows = $wpdb->get_results("SELECT * from $table_name");
	}
	function mj_smgt_get_all_own_homeworklist()
	{
		global $wpdb;
		$get_current_user_id=get_current_user_id();
		$table_name = $wpdb->prefix . "mj_smgt_homework";
		return $rows = $wpdb->get_results("SELECT * from $table_name where createdby =$get_current_user_id");
	}
	function mj_smgt_get_teacher_homeworklist()
	{
		global $wpdb;
		$class_name = array();
		$table_name = $wpdb->prefix . "mj_smgt_homework";
		$class_name=get_user_meta(get_current_user_id(),'class_name',true);
		return $rows = $wpdb->get_results("SELECT * from $table_name where class_name IN(".implode(',',$class_name).")");
	}	
	function mj_smgt_get_edit_record($homework_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "mj_smgt_homework";
		return $rows = $wpdb->get_row("SELECT * from $table_name where homework_id=".$homework_id);
	}
	function mj_smgt_get_delete_record($homework_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "mj_smgt_homework";
		return $rows = $wpdb->query("Delete from $table_name where homework_id=".$homework_id);
	}	
	
}
?>