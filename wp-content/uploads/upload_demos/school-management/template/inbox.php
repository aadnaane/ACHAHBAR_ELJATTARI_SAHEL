<div class="mailbox-content">
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr> 				
					<th class="text-right" colspan="10">
				   <?php 				
					$message = mj_smgt_count_inbox_item(get_current_user_id());				
					$max = 10;
					if(isset($_GET['pg']))
					{
						$p = $_GET['pg'];
					}
					else
					{
						$p = 1;
					}
					$limit = ($p - 1) * $max;
					$prev = $p - 1;
					$next = $p + 1;
					$limits = (int)($p - 1) * $max;
					$totlal_message =count($message);
					$totlal_message = ceil($totlal_message / $max);
					$lpm1 = $totlal_message - 1;
					$offest_value = ($p-1) * $max;
					echo mj_smgt_inbox_pagination($totlal_message,$p,$lpm1,$prev,$next);
					?>
					</th>
				</tr>
			</thead>
			<tbody>
			<tr> 		
				<th>
					<span><?php esc_attr_e('Message From','school-mgt');?></span>
				</th>
				<th>
					<span><?php esc_attr_e('Message For','school-mgt');?></span>
				</th>
				<th><?php esc_attr_e('Subject','school-mgt');?></th>
				 <th>
					  <?php esc_attr_e('Description','school-mgt');?>
				</th>
				<th>
					  <?php esc_attr_e('Attachment','school-mgt');?>
				</th>
				<th>
					  <?php esc_attr_e('Date & Time','school-mgt');?>
				</th>
				</tr>
			<?php 
			$message = mj_smgt_get_inbox_message(get_current_user_id(),$limit,$max);
			
			foreach($message as $msg)
			{			
				$message_for=get_post_meta($msg->post_id,'message_for',true);
				$attchment=get_post_meta( $msg->post_id, 'message_attachment',true);
				if($message_for=='student' || $message_for=='supportstaff' || $message_for=='teacher' || $message_for=='parent')
				{ 
					$post_id='';
					if($post_id==$msg->post_id)
					{
						continue;
					}
					else
					{ 				
					?>
						<tr>
						<td><a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none"><?php 
						$auth = get_post($msg->post_id);
						$authid = $auth->post_author;
						echo mj_smgt_get_display_name($authid);
						?>
						</a></td>	
						<td><a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none">
						<?php 
						$check_message_single_or_multiple=mj_smgt_send_message_check_single_user_or_multiple($msg->post_id);	
						if($check_message_single_or_multiple == 1)
						{	
							global $wpdb;
							$tbl_name = $wpdb->prefix .'smgt_message';
							$post_id=$msg->post_id;
							$get_single_user = $wpdb->get_row("SELECT * FROM $tbl_name where post_id = $post_id");
							
							echo mj_smgt_get_display_name($get_single_user->receiver);
						}
						else
						{					
							echo get_post_meta( $msg->post_id, 'message_for',true);
						}
						?>
						</a></td>
						 <td class="width_100px">
							 <a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none">
							  <?php 
								 $subject_char=strlen($msg->subject);
				                if($subject_char <= 10)
				                {
				                    echo $msg->subject;
				                }
				                else
				                {
				                    $char_limit = 10;
				                    $subject_body= substr(strip_tags($msg->subject), 0, $char_limit)."...";
				                    echo $subject_body;
				                }
							?>
							<?php if(mj_smgt_count_reply_item($msg->post_id)>=1){ ?><span class="badge badge-success pull-right"><?php echo mj_smgt_count_reply_item($msg->post_id);?></span><?php } ?></a>
						</td>
						<td class="width_400px">
						<?php
						$body_char=strlen($msg->message_body);
			            if($body_char <= 60)
			            {
			                echo $msg->message_body;
			            }
			            else
			            {
			                $char_limit = 60;
			                $msg_body= substr(strip_tags($msg->message_body), 0, $char_limit)."...";
			                echo $msg_body;
			            }
						?>
						</td>
						<td>	
						<?php			
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
						else
						{
							 esc_attr_e('No Attachment','school-mgt');
						}
						?>				
						</td>
						<td><a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none">	
							<?php echo mj_smgt_convert_date_time($msg->date );?>
						</a></td>
						</tr>
					<?php 
					}			
				}
				else
				{ ?>
					<tr>
					<td><a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none"><?php 
						$auth = get_post($msg->post_id);
						$authid = $auth->post_author;
						echo mj_smgt_get_display_name($authid);
						?>
					</a></td>	
					<td><a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none"><?php 
						$check_message_single_or_multiple=mj_smgt_send_message_check_single_user_or_multiple($msg->post_id);	
						if($check_message_single_or_multiple == 1)
						{	
							global $wpdb;
							$tbl_name = $wpdb->prefix .'smgt_message';
							$post_id=$msg->post_id;
							$get_single_user = $wpdb->get_row("SELECT * FROM $tbl_name where post_id = $post_id");
							
							echo mj_smgt_get_display_name($get_single_user->receiver);
						}
						else
						{					
							echo get_post_meta( $msg->post_id, 'message_for',true);
						}
						?></a></td>
					 <td>
						 <a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none"> 
						 	<?php echo $msg->subject;?>
					 	<?php 
							 $subject_char=strlen($msg->subject);
			                if($subject_char <= 10)
			                {
			                    echo $msg->subject;
			                }
			                else
			                {
			                    $char_limit = 10;
			                    $subject_body= substr(strip_tags($msg->subject), 0, $char_limit)."...";
			                    echo $subject_body;
			                }
						?>
						<?php if(mj_smgt_count_reply_item($msg->post_id)>=1){ ?><span class="badge badge-success pull-right"><?php echo mj_smgt_count_reply_item($msg->post_id);?></span><?php } ?></a>
					</td>
					<td><a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none">
						<?php
						$body_char=strlen($msg->message_body);
			            if($body_char <= 60)
			            {
			                echo $msg->message_body;
			            }
			            else
			            {
			                $char_limit = 60;
			                $msg_body= substr(strip_tags($msg->message_body), 0, $char_limit)."...";
			                echo $msg_body;
			            }
						?>
					</a></td>
					<td>	
					<?php			
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
					else
					{
						 esc_attr_e('No Attachment','school-mgt');
					}
					?>				
					</td>
					<td><a href="?dashboard=user&page=message&tab=view_message&from=inbox&id=<?php echo $msg->message_id;?>" class="text_decoration_none">
						<?php  echo mj_smgt_convert_date_time($msg->date );?>
					</a></td>
					</tr>
					<?php 
				}
				$post_id=$msg->post_id;
			}
			?>
			</tbody>
		</table>
 	</div>
 </div>
 <?php ?>