<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
//--------------- ACCESS WISE ROLE -----------//
$user_access=mj_smgt_get_userrole_wise_access_right_array();
//Subject
if(isset($_REQUEST['message']))
{
	$message =$_REQUEST['message'];
	if($message == 1)
	{?>
			<div id="message" class="alert_msg alert alert-success alert-dismissible " role="alert"">
			<p>
			<?php 
				esc_attr_e('Message sent successfully','school-mgt');
			?>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
			</p></div>
			<?php 
		
	}
	elseif($message == 2)
	{?>
		<div id="message" class="alert_msg alert alert-success alert-dismissible" role="alert">
			<p>
			<?php 
				echo esc_attr_e('Message deleted successfully','school-mgt'); ?>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
			</p>
		</div>
		<?php 
	}
}	
if($_REQUEST['from']=='sendbox')
{
	$message = get_post($_REQUEST['id']);
	mj_smgt_change_read_status_reply($_REQUEST['id']);
	$author = $message->post_author;	
	if(isset($_REQUEST['delete']))
	{
	wp_delete_post($_REQUEST['id']);
	wp_safe_redirect(home_url()."?dashboard=user&page=message&tab=sendbox" );
	exit();
	}
	
	$box='sendbox';
}
if($_REQUEST['from']=='inbox')
{
	$message = mj_smgt_get_message_by_id($_REQUEST['id']);
	$message1 = get_post($message->post_id);
	$author = $message1->post_author;	
	mj_smgt_change_read_status($_REQUEST['id']);
	mj_smgt_change_read_status_reply($message->post_id);
	$box='inbox';
}
	if(isset($_REQUEST['delete']))
	{
		echo $_REQUEST['delete'];
		
		mj_smgt_delete_message('smgt_message',$_REQUEST['id']);
		wp_safe_redirect(home_url()."?dashboard=user&page=message&tab=inbox" );
		exit();
	}

if(isset($_POST['replay_message']))
{
	$message_id=$_REQUEST['id'];
	$message_from=$_REQUEST['from'];
	
	$result=mj_smgt_send_replay_message($_POST);
	if($result)
		wp_safe_redirect(home_url()."?dashboard=user&page=message&tab=view_message&from=".$message_from."&id=$message_id&message=1" );
}	
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete-reply')
{
	$message_id=$_REQUEST['id'];
	$message_from=$_REQUEST['from'];
	$result=mj_smgt_delete_reply($_REQUEST['reply_id']);
	if($result)
	{
		wp_redirect ( home_url().'?dashboard=user&page=message&tab=view_message&action=delete-reply&from='.$message_from.'&id='.$message_id.'&message=2');
	}
}

?>
<div class="mailbox-content">
 	<div class="message-header">
		<h3><span><?php esc_attr_e('Subject','school-mgt')?> :</span> <?php if($box=='sendbox'){ echo $message->post_title; } else{ echo $message->subject; } ?> </h3>
        <p class="message-date"><?php  
		if($box=='sendbox')
		{ 
			$date_view=$message->post_date;
		}
		else
		{
			$date_view=$message->date;
		}
		echo mj_smgt_convert_date_time($date_view);  
		?></p>
	</div>
	<div class="message-sender">                                
    	<p><?php 
		if($box=='sendbox')
		{ 
			$message_for=get_post_meta($_REQUEST['id'],'message_for',true);
			echo "".esc_html__('From','school-mgt')." : ".mj_smgt_get_display_name($message->post_author)."<span>&lt;".mj_smgt_get_emailid_byuser_id($message->post_author)."&gt;</span><br>";
			
			$check_message_single_or_multiple=mj_smgt_send_message_check_single_user_or_multiple($_REQUEST['id']);	
			if($check_message_single_or_multiple == 1)
			{
				global $wpdb;
				$tbl_name = $wpdb->prefix .'smgt_message';
				$post_id=$_REQUEST['id'];
				$get_single_user = $wpdb->get_row("SELECT * FROM $tbl_name where post_id = $post_id");
				
				echo "".esc_html__('To','school-mgt')." : ".mj_smgt_get_display_name($get_single_user->receiver)."<span>&lt;".mj_smgt_get_emailid_byuser_id($get_single_user->receiver)."&gt;</span><br>";
			}
			else
			{				
				echo "".esc_html__('To','school-mgt')." : ".get_post_meta($_REQUEST['id'], 'message_for',true);
			}
		} 
		else
		{ 
	
			echo "".esc_html__('From','school-mgt')." : ".mj_smgt_get_display_name($message->sender)."<span>&lt;".mj_smgt_get_emailid_byuser_id($message->sender)."&gt;</span><br>";
			$check_message_single_or_multiple=mj_smgt_send_message_check_single_user_or_multiple($message->post_id);	
					
			if($check_message_single_or_multiple == 1)
			{
				global $wpdb;
				$tbl_name = $wpdb->prefix .'smgt_message';
				$post_id=$message->post_id;
				$get_single_user = $wpdb->get_row("SELECT * FROM $tbl_name where post_id = $post_id");
				
				echo "".esc_html__('To','school-mgt')." : ".mj_smgt_get_display_name($get_single_user->receiver)."<span>&lt;".mj_smgt_get_emailid_byuser_id($get_single_user->receiver)."&gt;</span><br>";
			}
			else
			{				
				echo "".esc_html__('To','school-mgt')." : ".get_post_meta($message->post_id, 'message_for',true);
			}								
		} 
		?></p>
    </div>
    <div class="message-content">
    	<p><?php $receiver_id=0;
		if($box=='sendbox')
		{
			echo $message->post_content; 
			echo '</br>';
			echo '</br>';			
			$attchment=get_post_meta( $message->ID, 'message_attachment',true);
			if(!empty($attchment))
			{
				$attchment_array=explode(',',$attchment);
				
				foreach($attchment_array as $attchment_data)
				{
					?>
						<a target="blank" href="<?php echo content_url().'/uploads/school_assets/'.$attchment_data; ?>" class="btn btn-default"><i class="fa fa-download"></i><?php esc_attr_e('View Attachment','school-mgt');?></a>
					<?php								
				}
			}
			$receiver_id=(get_post_meta($_REQUEST['id'],'message_smgt_user_id',true));
		}
		else
		{
			
			echo $message->message_body; 
			echo '</br>';
			echo '</br>';			
			$attchment=get_post_meta( $message->post_id, 'message_attachment',true);
			if(!empty($attchment))
			{
				$attchment_array=explode(',',$attchment);
				
				foreach($attchment_array as $attchment_data)
				{
					?>
						<a target="blank" href="<?php echo content_url().'/uploads/school_assets/'.$attchment_data; ?>" class="btn btn-default"><i class="fa fa-download"></i><?php esc_attr_e('View Attachment','school-mgt');?></a>
					<?php								
				}
			}
			$receiver_id=$message->sender;
		}
		?>
		</p>
   
    <div class="message-options pull-right">
		<?php
		if($user_access['delete']=='1')
		{
		?>
			<a class="btn btn-default" href="?dashboard=user&page=message&tab=view_message&id=<?php echo $_REQUEST['id'];?>&delete=1" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
			<i class="fa fa-trash m-r-xs"></i><?php esc_attr_e('Delete','school-mgt');?></a> 
		<?php
		} ?>
   </div>
    </div>
   <?php 
	if(isset($_REQUEST['from']) && $_REQUEST['from']=='inbox')
	{
		$allreply_data=mj_smgt_get_all_replies_frontend($message->post_id);
	}
	else
	{
		$allreply_data=mj_smgt_get_all_replies_frontend($_REQUEST['id']);
	}
	if (!empty($allreply_data)) {
		foreach($allreply_data as $reply)
		{
			if($reply->sender_id == get_current_user_id() || $reply->receiver_id == get_current_user_id())
			{
				?>
				<div class="message-content">
				<p><?php echo $reply->message_comment;?>
				<?php
					$reply_attchment=$reply->message_attachment;
					if(!empty($reply_attchment))
					{
						echo '</br>';
						echo '</br>';
						$reply_attchment_array=explode(',',$reply_attchment);
						
						foreach($reply_attchment_array as $attchment_data1)
						{
							?>
								<a target="blank" href="<?php echo content_url().'/uploads/school_assets/'.$attchment_data1; ?>" class="btn btn-default"><i class="fa fa-download"></i><?php esc_attr_e('View Attachment','school-mgt');?></a>
							<?php								
						}
					}
					?>
					<br><h5>
					<?php esc_attr_e('Reply By : ','school-mgt'); 
					echo mj_smgt_get_display_name($reply->sender_id); 
					esc_attr_e(' || ','school-mgt'); 	
					esc_attr_e('Reply To : ','school-mgt'); 
					echo mj_smgt_get_display_name($reply->receiver_id); 
					esc_attr_e(' || ','school-mgt'); 	
					?>
					<span class="timeago"  title="<?php echo mj_smgt_convert_date_time($reply->created_date);?>"></span>
					<?php
					if($reply->sender_id == get_current_user_id())
					{ 
						if($user_access['delete']=='1')
						{
						?>
							<span class="comment-delete">
							<a href="?dashboard=user&page=message&tab=view_message&action=delete-reply&from=<?php echo $_REQUEST['from'];?>&id=<?php echo $_REQUEST['id'];?>&reply_id=<?php echo $reply->id;?>" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><?php esc_attr_e('Delete','school-mgt');?></a></span> 
							<?php 
						} 
					}
					?>
					</h5>
					</p>
				</div>
				<?php
			}
		}
	}
		
	?>
	 <form name="message-replay" method="post" id="message-replay" enctype="multipart/form-data">
   <input type="hidden" name="message_id" value="<?php if($_REQUEST['from']=='sendbox') echo $_REQUEST['id']; else echo $message->post_id;?>">
   <input type="hidden" name="user_id" value="<?php echo get_current_user_id();?>">
   
    <div class="message-content">
		<?php
	global $wpdb;
	$tbl_name = $wpdb->prefix .'smgt_message';
	$current_user_id=get_current_user_id();
	if((string)$current_user_id == $author)
	{		
		if($_REQUEST['from']=='sendbox')
		{
			$msg_id=$_REQUEST['id']; 
			$msg_id_integer=(int)$msg_id;
			$reply_to_users =$wpdb->get_results("SELECT *  FROM $tbl_name where post_id = $msg_id_integer");			
		}
		else
		{
			$msg_id=$message->post_id;			
			$msg_id_integer=(int)$msg_id;
			$reply_to_users =$wpdb->get_results("SELECT *  FROM $tbl_name where post_id = $msg_id_integer");			
		}		
	}
	else
	{
		$reply_to_users=array();
		$reply_to_users[]=(object)array('receiver'=>$author);
	}
	?>
	<div class="message-options pull-right">
		<button type="button" name="replay_message_btn" class="btn btn-default replay_message_btn" id="replay_message_btn"><i class="fa fa-reply m-r-xs"></i><?php esc_html_e('Reply','school-mgt')?></button>
 	</div>
    <div class="message-content float_left_width_100 replay_message_div">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group row" >
					<label class="col-sm-2 control-label" ><?php esc_attr_e('Select user to reply','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-10 mb-20-px">
						 <select name="receiver_id[]" class="form-control" id="selected_users" multiple="true">
							<?php
							foreach($reply_to_users as $reply_to_user)
							{
								$user_data=get_userdata($reply_to_user->receiver);
								if(!empty($user_data))
								{
									if($reply_to_user->receiver != get_current_user_id())
									{
										?>
										<option  value="<?php echo $reply_to_user->receiver;?>" ><?php echo mj_smgt_get_display_name($reply_to_user->receiver); ?></option>
										<?php
									}
								}
							}
							?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group row">
					<label class="col-sm-2 control-label" for="photo"><?php esc_attr_e('Message Comment','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8 mb-10-px">
						<textarea name="replay_message_body" id="replay_message_body" class="validate[required] form-control text-input"></textarea>
					</div>
				</div>
			</div>
		</div>	  
		<div class="row">
			<div class="attachment_div col-sm-12">
				<div class="form-group row">
					<label class="col-sm-2 control-label" for="photo"><?php esc_attr_e('Attachment ','school-mgt');?></label>
					<div class="col-sm-10 mb-10-px">
					 <input  class="btn_top input-file" name="message_attachment[]" type="file" />
					</div>
				</div>							
					   </div>
		</div>
		<div class="row">
			<div class="offset-sm-2 col-sm-10 mb-5-px">
				<input type="button" value="<?php esc_attr_e('Add More Attachment','school-mgt') ?>"  onclick="add_new_attachment2()" class="bg-gray btn more_attachment">
			</div>
		</div>	
	   <div class="message-options pull-right reply-message-btn">
	  <?php
		if($user_access['add']=='1')
		{ ?>
			<button type="submit" name="replay_message" class="btn btn-success" id="check_reply_user"><?php esc_attr_e('Send Message','school-mgt')?></button>
		<?php
		}
		?>
	   </div>
    </div>
	</form>
 </div>
 <?php ?>