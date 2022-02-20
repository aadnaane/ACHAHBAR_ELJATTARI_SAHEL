<?php 
class mj_smgt_feespayment
{		
	public function mj_smgt_delete_fee_type($cat_id)
	{
		$result=wp_delete_post($cat_id);		
		return $result;
	}
	
public function mj_smgt_add_feespayment($data)
{
	global $wpdb;
	$table_smgt_fees_payment 	= $wpdb->prefix. 'smgt_fees_payment';	
	$feedata['class_id']    	=	mj_smgt_onlyNumberSp_validation($_POST['class_id']);
	$feedata['section_id']		=	mj_smgt_onlyNumberSp_validation($_POST['class_section']);	
	$feedata['fees_id']		    =	implode(',',(array)$_POST['fees_id']);
	$feedata['total_amount']	=	$_POST['fees_amount'];	
	$feedata['description']		=	mj_smgt_address_description_validation($_POST['description']);	
	$feedata['start_year']		=	mj_smgt_onlyNumberSp_validation($_POST['start_year']);	
	$feedata['end_year']		=	mj_smgt_onlyNumberSp_validation($_POST['end_year']);	
	$feedata['paid_by_date']	=	date("Y-m-d");		
	$feedata['created_date']	=	date("Y-m-d H:i:s");
	$feedata['created_by']		=	get_current_user_id();
	
	$email_subject				=	get_option('fee_payment_title');		
	$SchoolName 				= 	get_option('smgt_school_name');
	if(isset($data['fees_id']))
		$single_fee				=	$this->mj_smgt_get_single_feetype_data($data['fees_id']);
		$fee_title				=	get_the_title($single_fee->fees_title_id);
	if($data['action']=='edit')
	{
		$feedata['student_id']	=	$data['student_id'];				
		$fees_id['fees_pay_id']	=	$data['fees_pay_id'];
		$result=$wpdb->update($table_smgt_fees_payment,$feedata,$fees_id);			
		return $result;
	}
	else
	{

		if(isset($_POST['class_section']) && $_POST['class_section']!="")
		{
			$student = get_users(
				array(
					'meta_key' => 'class_section',
					'meta_value' =>$_POST['class_section'],
					'meta_query'=> array(
						array(
							'key' => 'class_name',
							'value' => $_POST['class_id'],
							'compare' => '=')),
							'role'=>'student')
			);	
		}
		else
		{
			$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $_POST['class_id'],'role'=>'student'));
		}
		
		if($data['student_id'] == '')
		{	
								
			foreach($student as $user)
			{					
				$StdID = $user->ID;
				
				if(get_option( 'smgt_enable_feesalert_mail')==1)
				{	

					$headers  ="";
					$headers .= 'From: abc<noreplay@gmail.com>' . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
					
					$feedata['student_id']	=	$user->ID;	
					$result			=	$wpdb->insert($table_smgt_fees_payment,$feedata );
					$fees_pay_id 	= 	$wpdb->insert_id;	
					
					$parent 		= 	get_user_meta($StdID, 'parent_id', true);
					$roll_id 		= 	get_user_meta($StdID, 'roll_id', true);
					$class_name		=	get_user_meta($StdID,'class_name',true);
								
					if(!empty($parent))
					{
						foreach($parent as $p)
						{
							$user_info	 	=    get_userdata($p);
							$email_to[] 	=	 $user_info->user_email;							
						}
						foreach($email_to as $eamil)
						{
							$Cont = get_option('fee_payment_mailcontent');
							$ParerntData 					= 	get_user_by('email',$eamil);							
							$SearchArr['{{parent_name}}']	=	$ParerntData->display_name;
							$SearchArr['{{school_name}}']	=	get_option('smgt_school_name');
							$data = mj_smgt_string_replacement($SearchArr,get_option('fee_payment_mailcontent'));
							$headers='';
							$headers .= 'From: '.get_option('smgt_school_name').' <noreplay@gmail.com>' . "\r\n";
							$headers .= "MIME-Version: 1.0\r\n";
							$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
							$MessageContent ="";
							$MessageContent = mj_smgt_GetHTMLContent($fees_pay_id);	
							$MessageContent = $data. $MessageContent;
							if(get_option('smgt_mail_notification') == '1')
							{							
								wp_mail($eamil,get_option('fee_payment_title'),$MessageContent,$headers);	
							}								
						}
					}
				}
				else
				{
					$feedata['student_id']	=	$user->ID;	
					$result			=	$wpdb->insert($table_smgt_fees_payment,$feedata );
					$fees_pay_id 	= 	$wpdb->insert_id;					
				}
			}
		}
		else
		{
			$feedata['student_id'] = $data['student_id'];			
			if(get_option( 'smgt_enable_feesalert_mail')==1 || isset($_POST['smgt_enable_feesalert_sms']))
			{
				if(get_option( 'smgt_enable_feesalert_mail')==1 && isset($_POST['smgt_enable_feesalert_sms']))
				{
					$student 	= 	get_userdata($data['student_id']);
					$parent 	= 	get_user_meta($data['student_id'], 'parent_id', true);
					$roll_id 	= 	get_user_meta($data['student_id'], 'roll_id', true);
					$class_name	=	get_user_meta($data['student_id'],'class_name',true);
					$result	=	$wpdb->insert( $table_smgt_fees_payment, $feedata );
					$fees_pay_id 	= 	$wpdb->insert_id;		
					foreach($parent as $p)
					{
						$headers='';
						$headers .= 'From: '.get_option('smgt_school_name').' <noreplay@gmail.com>' . "\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
					
						$Cont = get_option('fee_payment_mailcontent');
						$user_info = get_userdata($p);
						$email = $user_info->user_email;					
						$SearchArr['{{parent_name}}']	=	$user_info->display_name;
						$SearchArr['{{school_name}}']	=	get_option('smgt_school_name');
						$data = mj_smgt_string_replacement($SearchArr,get_option('fee_payment_mailcontent'));
						$SearchArr['{{parent_name}}']	=	$user_info->display_name;
						$SearchArr['{{school_name}}']	=	get_option('smgt_school_name');					
						$MessageContent	= mj_smgt_GetHTMLContent($fees_pay_id);	
						$MessageContent = $data. $MessageContent;
						if(get_option('smgt_mail_notification') == '1')
						{
							wp_mail($email,'test mail',$MessageContent,$headers);
						}
						
						$number=get_user_meta($user_info->ID, 'mobile_number',true);
						$student_number= "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).$number;
						$message_content = "You have a new invoice";
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
							$reciever_number = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_info->ID, 'mobile_number',true);		
							$message_content = "You have a new invoice.";
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
				if(get_option( 'smgt_enable_feesalert_mail')==1 )
				{
					$student 	= 	get_userdata($data['student_id']);
					$parent 	= 	get_user_meta($data['student_id'], 'parent_id', true);
					$roll_id 	= 	get_user_meta($data['student_id'], 'roll_id', true);
					$class_name	=	get_user_meta($data['student_id'],'class_name',true);
					$result	=	$wpdb->insert( $table_smgt_fees_payment, $feedata );
					$fees_pay_id 	= 	$wpdb->insert_id;		
					foreach($parent as $p)
					{
						$headers='';
						$headers .= 'From: '.get_option('smgt_school_name').' <noreplay@gmail.com>' . "\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
					
						$Cont = get_option('fee_payment_mailcontent');
						$user_info = get_userdata($p);
						$email = $user_info->user_email;					
						$SearchArr['{{parent_name}}']	=	$user_info->display_name;
						$SearchArr['{{school_name}}']	=	get_option('smgt_school_name');
						$data = mj_smgt_string_replacement($SearchArr,get_option('fee_payment_mailcontent'));
						$SearchArr['{{parent_name}}']	=	$user_info->display_name;
						$SearchArr['{{school_name}}']	=	get_option('smgt_school_name');					
						$MessageContent	= mj_smgt_GetHTMLContent($fees_pay_id);	
						$MessageContent = $data. $MessageContent;
						if(get_option('smgt_mail_notification') == '1')
						{
							wp_mail($email,'test mail',$MessageContent,$headers);
						}
						 
					}
				} 
				if(isset($_POST['smgt_enable_feesalert_sms']))
				{
					$student 	= 	get_userdata($data['student_id']);
					$parent 	= 	get_user_meta($data['student_id'], 'parent_id', true);
					$roll_id 	= 	get_user_meta($data['student_id'], 'roll_id', true);
					$class_name	=	get_user_meta($data['student_id'],'class_name',true);
					$result	=	$wpdb->insert( $table_smgt_fees_payment, $feedata );
					$fees_pay_id 	= 	$wpdb->insert_id;		
					foreach($parent as $p)
					{
						echo "ccc";
						die;			
						$number=get_user_meta($user_info->ID, 'mobile_number',true);
						$student_number= "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).$number;
						$message_content = "You have a new invoice";
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
							$reciever_number = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_info->ID, 'mobile_number',true);		
							$message_content = "You have a new invoice.";
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
			else
			{
				$result	=	$wpdb->insert( $table_smgt_fees_payment, $feedata );
			}
		}
	}
	return $result;
}

	public function mj_smgt_get_all_student_fees_data($std_id)
	{		
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';
		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment WHERE student_id=$std_id");
		return $result;
	}
	
	public function mj_smgt_get_payment_histry_data($fees_pay_id)
	{		
		global $wpdb;
		$table_smgt_fee_payment_history = $wpdb->prefix. 'smgt_fee_payment_history';		
		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fee_payment_history WHERE fees_pay_id=$fees_pay_id");
		return $result;
	}

public function mj_smgt_add_feespayment_history($data)
{	
	global $wpdb;
	$table_smgt_fee_payment_history = $wpdb->prefix. 'smgt_fee_payment_history';
	$tbl_payment = $wpdb->prefix. 'smgt_fees_payment';
	//-------usersmeta table data--------------
	if(isset($_POST['fees_pay_id']))
		$fees_pay_id = $_POST['fees_pay_id'];
	else
		$fees_pay_id = $data['fees_pay_id'];		
		$feedata['fees_pay_id']=$fees_pay_id;
		$feedata['amount']=$data['amount'];
		$feedata['payment_method']=$data['payment_method'];	
		
		if(isset($data['trasaction_id']))
		{
			$feedata['trasaction_id']=$data['trasaction_id'] ;
		}
		$feedata['paid_by_date']=date("Y-m-d");
		
		$feedata['created_by']= get_current_user_id();
		
		$paid_amount = $this->mj_smgt_get_paid_amount_by_feepayid($feedata['fees_pay_id']);
		
		$uddate_data['fees_paid_amount'] = $paid_amount + $feedata['amount'];
		$uddate_data['payment_status'] = $this->mj_smgt_get_payment_status_name($data['fees_pay_id']);
		$uddate_data['fees_pay_id'] = $fees_pay_id;
		$this->mj_smgt_update_paid_fees_amount($uddate_data);
		$uddate_data1['payment_status'] = $this->mj_smgt_get_payment_status_name($fees_pay_id);
		$uddate_data1['fees_pay_id'] = $fees_pay_id;
		$this->mj_smgt_update_payment_status_fees_amount($uddate_data1);
		$result=$wpdb->insert( $table_smgt_fee_payment_history, $feedata );		
		
		$email_subject 	= 	get_option('payment_recived_mailsubject');
		$MailCont	= 	get_option('payment_recived_mailcontent');
		$feespaydata = $this->mj_smgt_get_single_fee_mj_smgt_payment($fees_pay_id);
		$StudentData = get_userdata($feespaydata->student_id);	
		
		$SearchArr['{{student_name}}'] 	= 	mj_smgt_get_display_name($feespaydata->student_id);
		$SearchArr['{{invoice_no}}']	= 	$feespaydata->fees_pay_id;
		$SearchArr['{{school_name}}'] 	= 	get_option('smgt_school_name');
		
		$email_to 	 = $StudentData->user_email;
		$search['{{school_name}}'] = get_option('smgt_school_name');						
		$email_message=mj_smgt_string_replacement($SearchArr,get_option('payment_recived_mailcontent'));
		if(get_option('smgt_mail_notification') == '1')
		{	
			mj_smgt_send_mail_paid_invoice_pdf($email_to,$email_subject,$email_message,$fees_pay_id);
		}			
		return $result;
}
public function mj_smgt_get_payment_status_name($fees_pay_id)
{	
	global $wpdb;
	$table_smgt_fees_payment = $wpdb->prefix .'smgt_fees_payment';	
	$result =$wpdb->get_row("SELECT * FROM $table_smgt_fees_payment WHERE fees_pay_id=".$fees_pay_id);
	if(!empty($result))
	{	
		if($result->fees_paid_amount == 0)
		{
			return 1;
		}
		elseif($result->fees_paid_amount < $result->total_amount)
		{
			return 1;
		}
		else
			return 2;
	}
	else
		return 0;
}
	public function mj_smgt_get_paid_amount_by_feepayid($fees_pay_id)
	{
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';
		$result = $wpdb->get_row("SELECT fees_paid_amount FROM $table_smgt_fees_payment where fees_pay_id = $fees_pay_id");
		return $result->fees_paid_amount;
	}
	public function mj_smgt_update_paid_fees_amount($data)
	{
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';
		$feedata['fees_paid_amount'] = $data['fees_paid_amount'];
		$feedata['payment_status'] = $data['payment_status'];
		$fees_id['fees_pay_id']=$data['fees_pay_id'];
			$result=$wpdb->update( $table_smgt_fees_payment, $feedata ,$fees_id);
	}
	public function mj_smgt_update_payment_status_fees_amount($data)
	{
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';
		
		$feedata['payment_status'] = $data['payment_status'];
		$fees_id['fees_pay_id']=$data['fees_pay_id'];
			$result=$wpdb->update( $table_smgt_fees_payment, $feedata ,$fees_id);
	}
	public function mj_smgt_get_all_fees()
	{
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';
	
		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment");
		return $result;
	}
	public function mj_smgt_get_all_fees_own()
	{
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';
	    $get_current_user_id=get_current_user_id();
		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment where created_by=$get_current_user_id");
		return $result;
	}
	public function mj_smgt_get_single_fee_payment($fees_pay_id)
	{
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';
		$result = $wpdb->get_row("SELECT * FROM $table_smgt_fees_payment where fees_pay_id = $fees_pay_id");
		return $result;
	}
	public function mj_smgt_get_single_fee_mj_smgt_payment($fees_pay_id)
	{
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';
		$result = $wpdb->get_row("SELECT * FROM $table_smgt_fees_payment where fees_pay_id = $fees_pay_id");
		return $result;
	}
	public function mj_smgt_get_single_feetype_data($fees_id)
	{
		global $wpdb;
		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';
	
		$result = $wpdb->get_row("SELECT * FROM $table_smgt_fees where fees_id = $fees_id ");
		return $result;
	}
	public function mj_smgt_delete_feetype_data($fees_id)
	{
		global $wpdb;
		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';
		$result = $wpdb->query("DELETE FROM $table_smgt_fees where fees_id= ".$fees_id);
		return $result;
	}
	public function mj_smgt_delete_feetpayment_data($fees_pay_id)
	{
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';
		$result = $wpdb->query("DELETE FROM $table_smgt_fees_payment where fees_pay_id= ".$fees_pay_id);
		return $result;
	}
	public function mj_smgt_get_fees_payment_dashboard()
	{		
		global $wpdb;
		$table_smgt_fees_payment = $wpdb->prefix. 'smgt_fees_payment';
		$result = $wpdb->get_results("SELECT * FROM $table_smgt_fees_payment ORDER BY fees_pay_id  DESC  limit 3");
		return $result;
	}
	public function mj_smgt_feetype_amount_data($fees_id)
	{
		global $wpdb;
		$table_smgt_fees = $wpdb->prefix. 'smgt_fees';
		$result = $wpdb->get_row("SELECT * FROM $table_smgt_fees where fees_id = $fees_id");
		return $result->fees_amount;
	}
	
}
?>