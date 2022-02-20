<script type="text/javascript">
jQuery(document).ready(function($){
	//"use strict";	
	$('#message_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	 $('#selected_users').multiselect({ 
		 nonSelectedText :"<?php esc_attr_e('Select Users','school-mgt');?>",
		includeSelectAllOption: true,
		selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       },
		// buttonContainer: '<div class="dropdown" />'         
     });
	 $('#selected_class').multiselect({ 
		 nonSelectedText :'<?php esc_attr_e('Select Class','school-mgt');?>',
         includeSelectAllOption: true,
		 selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       },
		buttonContainer: '<div class="dropdown" />'       
     });
	$("body").on("click",".save_message_selected_user",function()
	{		
		var class_selection_type = $(".class_selection_type").val();	
				
		if(class_selection_type == 'multiple')
		{
			var checked = $(".multiselect_validation1 .dropdown-menu input:checked").length;

			if(!checked)
			{
				alert(language_translate2.one_class_select_alert);
				return false;
			}	
		}			
	});  
	jQuery("body").on("change", ".input-file[type=file]", function ()
	{ 
		"use strict";
		var file = this.files[0]; 		
		var ext = $(this).val().split('.').pop().toLowerCase(); 
		//Extension Check 
		if($.inArray(ext, [,'pdf','doc','docx','xls','xlsx','ppt','pptx','gif','png','jpg','jpeg','']) == -1)
		{
			  alert('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed. '  + ext + ' formate are not allowed.');
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />');
			return false; 
		} 
		//File Size Check 
		if (file.size > 20480000) 
		{
			alert(language_translate2.large_file_Size_alert);
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />'); 
			return false; 
		}
	}); 

	jQuery('#message-replay').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	jQuery('span.timeago').timeago();
	jQuery("body").on("change", ".input-file[type=file]", function ()
	{ 
		"use strict";
		var file = this.files[0]; 		
		var ext = $(this).val().split('.').pop().toLowerCase(); 
		//Extension Check 
		if($.inArray(ext, [,'pdf','doc','docx','xls','xlsx','ppt','pptx','gif','png','jpg','jpeg']) == -1)
		{
			  alert('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed. '  + ext + ' formate are not allowed.');
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />');
			return false; 
		} 
		//File Size Check 
		if (file.size > 20480000) 
		{
			alert(language_translate2.large_file_Size_alert);
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />'); 
			return false; 
		}
	});

	$('.multiselect-search').removeClass('form-control',0);

function add_new_attachment()
{
	$(".attachment_div").append('<div class="form-group row mb-3"><label class="col-sm-2 control-label col-form-label text-md-end" for="photo">Attachment</label><div class="col-sm-4"><input  class="btn_top input-file" name="message_attachment[]" type="file" /></div><div class="col-sm-2"><input type="button" value="Delete" onclick="delete_attachment(this)" class="remove_cirtificate doc_label btn btn-danger"></div></div>');
}

function delete_attachment(n)
{
	n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);				
}
var table = jQuery('#all_message_list').DataTable({
		 "responsive": true,
		 dom: 'Bfrtip',
         buttons: [
			{
                extend: 'print',
                text:'Print',
				title: 'Message Data',
				exportOptions: 
				{
                    columns: [1,2,3,4,5,6,8],
					modifier: {
                        selected: null
                    }
                }
            }
        ], 
		"bProcessing": true,
		//  "bServerSide": true,
		 "sAjaxSource": ajaxurl+'?action=mj_smgt_view_all_message',
		 "bDeferRender": true, 		
		"order": [[ 1, "asc" ]],
	    "aoColumns":[
		  {"bSortable": false},
		  {"bSortable": false},
		  {"bSortable": true},
		  {"bSortable": true},
		  {"bSortable": true},              	                 
		  {"bSortable": true},       
		  {"bSortable": true},              	                 
		  {"bSortable": true},              	                 
		  {"bSortable": false},              	                 
		  {"bSortable": false}],
		  language:<?php echo mj_smgt_datatable_multi_language();?>
		  });
		  
		table.on('page.dt', function() {
		  $('html, body').animate({
			scrollTop: $(".dataTables_wrapper").offset().top
		   }, 'slow');
		}); 
		
		$(".delete_check").on('click', function()
		{	
			if ($('.sub_chk:checked').length == 0 )
			{
				 alert(language_translate2.one_message_alert);
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

      jQuery('#select_all').on('click', function(e)
		{
			 if($(this).is(':checked',true))  
			 {
				$(".sub_chk").prop('checked', true);  
			 }
			 else  
			 {
				$(".sub_chk").prop('checked',false);  
			 }
		});
		$("body").on("change", ".sub_chk", function(event)
		{ 
			if(false == $(this).prop("checked"))
			{ 
				$("#select_all").prop('checked', false); 
			}
			if ($('.sub_chk:checked').length == $('.sub_chk').length )
			{
				$("#select_all").prop('checked', true);
			}
		});
});		
</script>
<?php 
if(isset($_POST['save_message']))
{	
	$created_date 	= 	date("Y-m-d H:i:s");
	$subject 		= 	mj_smgt_popup_category_validation($_POST['subject']);
	$message_body 	= 	mj_smgt_address_description_validation($_POST['message_body']);	
	$created_date 	=	 date("Y-m-d H:i:s");
	$tablename		=	"smgt_message";
	$smgt_sms_service_enable	=	isset($_REQUEST['smgt_sms_service_enable'])?$_REQUEST['smgt_sms_service_enable']:0;
	$role	=	$_POST['receiver'];
	$MailBody  		= 	get_option('message_received_mailcontent');	
	$SchoolName 	=  	get_option('smgt_school_name');
	$SubArr['{{school_name}}'] 	= 	$SchoolName;
	$SubArr['{{from_mail}}'] 	= 	mj_smgt_get_display_name(get_current_user_id());
	$MailSub 	= 	mj_smgt_string_replacement($SubArr,get_option('message_received_mailsubject'));
	
	if(isset($_REQUEST['class_id']))
		$class_id 	= 	$_REQUEST['class_id'];
	
	$role 	= 	$_REQUEST['receiver'];
	$class_id 	= 	isset($_REQUEST['class_id'])?$_REQUEST['class_id']:'';
	$class_section 		= 	isset($_REQUEST['class_section'])?$_REQUEST['class_section']:'';
	$selected_users 	= 	isset($_REQUEST['selected_users'])?$_REQUEST['selected_users']:array();
	$selected_users 	= 	array_unique($selected_users);
	
	$upload_docs_array=array();	
	if(!empty($_FILES['message_attachment']['name']))
	{
		$count_array=count($_FILES['message_attachment']['name']);

		for($a=0;$a<$count_array;$a++)
		{			
			foreach($_FILES['message_attachment'] as $image_key=>$image_val)
			{		
				$document_array[$a]=array(
				'name'=>$_FILES['message_attachment']['name'][$a],
				'type'=>$_FILES['message_attachment']['type'][$a],
				'tmp_name'=>$_FILES['message_attachment']['tmp_name'][$a],
				'error'=>$_FILES['message_attachment']['error'][$a],
				'size'=>$_FILES['message_attachment']['size'][$a]
				);							
			}
		}				
		foreach($document_array as $key=>$value)		
		{	
			$get_file_name=$document_array[$key]['name'];	
			
			$upload_docs_array[]=mj_smgt_load_multiple_documets($value,$value,$get_file_name);				
		} 				
	}
	$upload_docs_array_filter=array_filter($upload_docs_array);	
	if(!empty($upload_docs_array_filter))
	{
		$attachment=implode(',',$upload_docs_array_filter);
	}
	else
	{
		$attachment='';
	}
	
	if(!empty($selected_users))
	{		
		$post_id = wp_insert_post( array(
			'post_status' => 'publish',
			'post_type' => 'message',
			'post_title' => $subject,
			'post_content' =>$message_body
		));
		
		$result=add_post_meta($post_id, 'message_for',$role);
		$result=add_post_meta($post_id, 'smgt_class_id',$_REQUEST['class_id']);
		$result=add_post_meta($post_id, 'message_attachment',$attachment);
		$m=0;
		$reci_number =array();
		foreach($selected_users as $user_id)
		{
			$user_info = get_userdata($user_id);				 	
			$reci_number[]= "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
		}
		
		foreach($selected_users as $user_id)
		{			
			$message_content = $_POST['sms_template'];						
			 $current_sms_service = get_option( 'smgt_sms_service');	
			
			if($smgt_sms_service_enable)
			{				
				if(is_plugin_active('sms-pack/sms-pack.php'))
				{					
					$args = array();
					$args['mobile']=$reci_number;
					$args['message_from']="message";
					$args['message']=str_replace(" ","%20",$message_content);
					if($current_sms_service =="MSG91")
					{
						$args['message']	=	$message_content;
					}
					if($current_sms_service=='telerivet' || $current_sms_service =="MSG91" || $current_sms_service=='sendpk' || $current_sms_service=='ViaNettSMS' || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='africastalking' || $current_sms_service=='bulksmsnigeria')
					{	
						$send = send_sms($args);			
					}					
				}			
				$user_info = get_userdata($user_id);				 	
				$reciever_number = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
		
				if($current_sms_service == 'clickatell')
			 	{					
			 		$clickatell=get_option('smgt_clickatell_sms_service');
			 		$to = $reciever_number;
			 		$message = str_replace(" ","%20",$message_content);
			 		$username = $clickatell['username']; //clickatell username
					$password = $clickatell['password']; // clickatell password
					$api_key = $clickatell['api_key'];//clickatell apikey
					$sender_id = $clickatell['sender_id'];//clickatell sender_id
					$baseurl ="http://api.clickatell.com";
					$url = "http://api.clickatell.com/http/auth?user={$username}&password={$password}&api_id={$api_key}";
					$ret = file($url);
					$sess = explode(":",$ret[0]);
					if ($sess[0] == "OK")
					{
			 			$sess_id = trim($sess[1]); // remove any whitespace
			 			$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$message&from=$sender_id";
						$ret = file($url);
				 		$send = explode(":",$ret[0]);				 					
				 	}
				 }
				 if($current_sms_service == 'twillo')
				 {
				 	//Twilio lib
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
			
			$message_data=array(
				'sender'=>get_current_user_id(),
				'receiver'=>$user_id,
				'subject'=>$subject,
				'message_body'=>$message_body,
				'date'=>$created_date,
				'post_id'=>$post_id,
				'status' =>0
			);
			
			mj_smgt_insert_record($tablename,$message_data);
			$user_info 		= 	get_userdata($user_id);
			$to				= 	$user_info->user_email; 
			$MesArr['{{receiver_name}}']	=	mj_smgt_get_display_name($user_id);
			$MesArr['{{message_content}}']	=	$message_body;
			$MesArr['{{school_name}}']		=	$SchoolName;
			$messg = mj_smgt_string_replacement($MesArr,$MailBody);
			if(!empty($upload_docs_array_filter))
			{
				$mailattachment=array();
				foreach($upload_docs_array_filter as $attachment_data)
				{
					$mailattachment[]= WP_CONTENT_DIR . '/uploads/school_assets/'.$attachment_data;					
				}
				
				$headers="";
				$headers .= 'From: '.get_option('smgt_school_name').' <noreplay@gmail.com>' . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
				if(get_option('smgt_mail_notification') == '1')
				{
					wp_mail($to, $MailSub, $messg,$headers,$mailattachment); 
				}
			}
			else
			{ 
				if(get_option('smgt_mail_notification') == '1')
				{	
					wp_mail($to, $MailSub, $messg); 
				}
			}
			
			$m++;
		} 				
	}
	else
	{		
		$user_list = array();
		$class_list = $class_id ;
		$query_data['role']=$role;
		$exlude_id = mj_smgt_approve_student_list();
		$multi_class_id=$_POST['multi_class_id'];
		if($role == 'student')
		{
			if($_POST['class_selection_type'] == 'single')
			{
				$query_data['exclude']=$exlude_id;
				if($class_section)
				{
					$query_data['meta_key'] = 'class_section';
					$query_data['meta_value'] = $class_section;
					$query_data['meta_query'] = array(array('key' => 'class_name','value' => $class_list,'compare' => '=')
								 );
				}
				elseif($class_list != '')
				{
					$query_data['meta_key'] = 'class_name';
					$query_data['meta_value'] = $class_list;
				}	
			}
			else
			{
				$query_data['exclude']=$exlude_id;
				
				$query_data['meta_query'] = array(array('key' => 'class_name','value' => $multi_class_id,'compare' => 'IN')
								 );
			}			
			$results = get_users($query_data);
		}
		
		if($role == 'teacher')
		{
			if($_POST['class_selection_type'] == 'single')
			{
				if($class_list != '')
				{
					global $wpdb;
					$table_smgt_teacher_class = $wpdb->prefix. 'smgt_teacher_class';	
					$teacher_list = $wpdb->get_results("SELECT * FROM $table_smgt_teacher_class where class_id = $class_list");
					if($teacher_list)
					{
						foreach($teacher_list as $teacher)
						{
							$user_list[] = $teacher->teacher_id;
						}
					}				
				}
				else
				{
					$results = get_users($query_data);
				}
			}
			else
			{
				global $wpdb;
				$table_smgt_teacher_class = $wpdb->prefix. 'smgt_teacher_class';	
				$teacher_list = $wpdb->get_results("SELECT * FROM $table_smgt_teacher_class where class_id IN (".implode(',', $multi_class_id).")");
				if($teacher_list)
				{
					foreach($teacher_list as $teacher)
					{
						$user_list[] = $teacher->teacher_id;
					}
				}
			}
		}
		if($role == 'supportstaff')
		{		
			$results = get_users($query_data);
		}
		if($role == 'parent')
		{	
			if($_POST['class_selection_type'] == 'single')
			{
				if($class_list == '')
				{
					$results = get_users($query_data);
				}
				else
				{
					$query_data['role'] = 'student';
					$query_data['exclude']=$exlude_id;
					if($class_section)
					{
						$query_data['meta_key'] = 'class_section';
						$query_data['meta_value'] = $class_section;
						$query_data['meta_query'] = array(array('key' => 'class_name','value' => $class_list,'compare' => '=')
									 );
					}
					elseif($class_list != '')
					{
						$query_data['meta_key'] = 'class_name';
						$query_data['meta_value'] = $class_list;
					}
							
					$userdata=get_users($query_data);
					foreach($userdata as $users)
					{
						$parent = get_user_meta($users->ID, 'parent_id', true);					
						if(!empty($parent))
						foreach($parent as $p)
						{
							$user_list[]=$p;
						}
					}				
				}
			}
			else
			{
				$query_data['role'] = 'student';
				$query_data['exclude']=$exlude_id;
				
				$query_data['meta_query'] = array(array('key' => 'class_name','value' => $multi_class_id,'compare' => 'IN')
								 );
				$userdata=get_users($query_data);				
				
				foreach($userdata as $users)
				{
					$parent_data = get_user_meta($users->ID, 'parent_id', true);
					
					if(!empty($parent_data))
					{
						foreach($parent_data as $p_data)
						{
							$user_list[]=$p_data;
						}
					}
				}				
			}
		}
		if(isset($results))
		{
			foreach($results as $user_datavalue)
			{
				$user_list[] = $user_datavalue->ID;
			}
		}
		$user_data_list = array_unique($user_list);		
		if(!empty($user_data_list))
		{			
			$post_id = wp_insert_post( array(
				'post_status' => 'publish',
				'post_type' => 'message',
				'post_title' => $subject,
				'post_content' =>$message_body
			) );
				$result=add_post_meta($post_id, 'message_for',$role);
				if($_POST['class_selection_type'] == 'single')
				{
					$result=add_post_meta($post_id, 'smgt_class_id',$_REQUEST['class_id']);
				}
				else
				{
					$result=add_post_meta($post_id, 'smgt_class_id',implode(',',$multi_class_id));
				}	
				$result=add_post_meta($post_id, 'message_attachment',$attachment);
				$reci_number =array();
				foreach($user_data_list as $user_id)
				{
					$user_info = get_userdata($user_id);				 	
					$reci_number[]= "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
				}
				
			foreach($user_data_list as $user_id)
			{			
				$user_info = get_userdata($user_id);				 	
				$reciever_number = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
				$message_content = $_POST['sms_template'];						
				$current_sms_service = get_option( 'smgt_sms_service');
				if($smgt_sms_service_enable)
				{					
					if(is_plugin_active('sms-pack/sms-pack.php'))
					{						
						$args = array();
						$args['mobile']=$reci_number;
						$args['message_from']="message";
						$args['message']=str_replace(" ","%20",$message_content);						
						if($current_sms_service=='telerivet' || $current_sms_service =='MSG91' || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='africastalking' || $current_sms_service=='bulksmsnigeria')
						{
							
							$send = send_sms($args);
						}					
					} 
				
					if($current_sms_service == 'clickatell')
				 	{
				 		$clickatell=get_option('smgt_clickatell_sms_service');
				 		$to = $reciever_number;
				 		$message = str_replace(" ","%20",$message_content);
				 		$username = $clickatell['username']; //clickatell username
				 		$password = $clickatell['password']; // clickatell password
				 		$api_key = $clickatell['api_key'];//clickatell apikey
				 		$sender_id = $clickatell['sender_id'];//clickatell sender_id
				 		$baseurl ="http://api.clickatell.com";
				 		$ret = file($url);
				 		$sess = explode(":",$ret[0]);
				 		if ($sess[0] == "OK")
						{				 		
				 			$sess_id = trim($sess[1]); // remove any whitespace
				 			$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$message&from=$sender_id";
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
				$message_data=array(
					'sender'=>get_current_user_id(),
					'receiver'=>$user_id,
					'subject'=>$subject,
					'message_body'=>$message_body,
					'date'=>$created_date,
					'post_id'=>$post_id,
					'status' =>0
				);
				mj_smgt_insert_record($tablename,$message_data);
				$user_info = get_userdata($user_id);
				$to = $user_info->user_email;
				$MesArr['{{receiver_name}}']	= 	mj_smgt_get_display_name($user_id);	
				$MesArr['{{message_content}}']	=	$message_body;
				$MesArr['{{school_name}}']		=	$SchoolName;
				$messg = mj_smgt_string_replacement($MesArr,$MailBody);
				if(!empty($upload_docs_array_filter))
				{
					$mailattachment=array();
					foreach($upload_docs_array_filter as $attachment_data)
					{
						$mailattachment[]= WP_CONTENT_DIR . '/uploads/school_assets/'.$attachment_data;					
					}
					
					$headers="";
					$headers .= 'From: '.get_option('smgt_school_name').' <noreplay@gmail.com>' . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
					if(get_option('smgt_mail_notification') == '1')
					{
						wp_mail($to, $MailSub, $messg,$headers,$mailattachment); 
					}
				}
				else
				{
					if(get_option('smgt_mail_notification') == '1')
					{
						wp_mail($to,$MailSub,$messg); 
					}
				}
			} 			
		}		
	}
}


if(isset($result)){
	wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=compose&message=1'); 
	}	?>


<?php $active_tab = isset($_GET['tab'])?$_GET['tab']:'inbox';?>
<div class="page-inner">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div id="main-wrapper">
		<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Message Sent Successfully!','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Message deleted successfully','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('','school-mgt');
			break;
	}
	
	if($message)
	{ ?>
		<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
			<p><?php echo $message_string;?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
<?php } ?>
		<div class="row mailbox-header">
            <div class="col-md-2">
                <a class="btn btn-success btn-block w-100" href="?page=smgt_message&tab=compose"><?php esc_attr_e('Compose','school-mgt');?></a>
            </div>
            <div class="col-md-6">
				<h2>
                <?php
				if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
                    echo esc_html( esc_attr__( 'Inbox', 'school-mgt' ) );
				else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'sentbox')
					echo esc_html( esc_attr__( 'Sent Item', 'school-mgt' ) );
				else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'compose')
					echo esc_html( esc_attr__( 'Compose', 'school-mgt' ) );
				?>
				</h2>
            </div>
        </div>
		<div class="row">
			<div class="col-md-2">
						<ul class="list-unstyled mailbox-nav">
							<li <?php if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox')){?>class="active"<?php }?>>
								<a href="?page=smgt_message&tab=inbox"><i class="fa fa-inbox"></i> <?php esc_attr_e('Inbox','school-mgt');?><span class="badge badge-success pull-right ms-1"><?php echo mj_smgt_count_unread_message(get_current_user_id());?></span></a></li>
						   <li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox'){?>class="active"<?php }?>><a href="?page=smgt_message&tab=sentbox"><i class="fas fa-sign-out-alt"></i><?php esc_attr_e('Sent','school-mgt');?></a></li>
					<li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'view_all_message'){?>class="active"<?php }?>><a href="?page=smgt_message&tab=view_all_message"><i class="fa fa-eye"></i><?php esc_attr_e('View All Message','school-mgt');?></a></li>
					<li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'view_all_message_reply'){?>class="active"<?php }?>><a href="?page=smgt_message&tab=view_all_message_reply"><i class="fa fa-eye"></i><?php esc_attr_e('View All Reply Message','school-mgt');?></a></li>			   
					  </ul>
					</div>
				 <div class="col-md-10">
				 <?php
			if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox')
				require_once SMS_PLUGIN_DIR. '/admin/includes/message/sendbox.php';
			if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
				require_once SMS_PLUGIN_DIR. '/admin/includes/message/inbox.php';
			if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'compose'))
				require_once SMS_PLUGIN_DIR. '/admin/includes/message/composemail.php';
			if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'view_message'))
				require_once SMS_PLUGIN_DIR. '/admin/includes/message/view_message.php';
			if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'view_all_message'))
				require_once SMS_PLUGIN_DIR. '/admin/includes/message/view_all_message.php';
			if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'view_all_message_reply'))
				require_once SMS_PLUGIN_DIR. '/admin/includes/message/view_all_message_reply.php';
			?>
				 </div>
		</div>
</div><!-- Main-wrapper -->
</div><!-- Page-inner -->