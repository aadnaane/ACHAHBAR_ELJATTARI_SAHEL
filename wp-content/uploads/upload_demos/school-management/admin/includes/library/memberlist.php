<?php 
if($active_tab == 'memberlist')
{
	$school_obj = new School_Management ();?>
		<div class="panel-body">
		<div class="table-responsive">
			<table id="example123" class="display admin_memebrlist_datatable" cellspacing="0" width="100%">
				 <thead>
				<tr>
					<th><?php esc_attr_e('Photo','school-mgt');?></th>
					<th><?php esc_attr_e('Student Name','school-mgt');?></th>
					<th><?php esc_attr_e('Class','school-mgt');?></th>
				   <th><?php esc_attr_e('Roll No','school-mgt');?></th>
					<th><?php esc_attr_e('Student Email','school-mgt');?></th>
					<th><?php esc_attr_e('Action','school-mgt');?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><?php esc_attr_e('Photo','school-mgt');?></th>
				 <th><?php esc_attr_e('Student Name','school-mgt');?></th>
					<th><?php esc_attr_e('Class','school-mgt');?></th>
				   <th><?php esc_attr_e('Roll No','school-mgt');?></th>
					<th><?php esc_attr_e('Student Email','school-mgt');?></th>
					<th><?php esc_attr_e('Action','school-mgt');?></th>
				</tr>
			</tfoot>
			<tbody>
			 <?php
				$studentdata =$school_obj->mj_smgt_get_all_student_list();
				if(!empty($studentdata))
				{
					foreach ($studentdata as $retrieved_data)
					{ 
						$book_issued = mj_smgt_check_book_issued($retrieved_data->ID);
						if(!empty($book_issued))
						{ ?>
							<tr>
								<td class="user_image text-center"><?php $uid=$retrieved_data->ID;
										$umetadata=mj_smgt_get_user_image($uid);
										if(empty($umetadata['meta_value'])){
											echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle" />';
										}
										else
										echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle"/>';
							?></td>
								<td class="name"><?php echo $retrieved_data->display_name;?></td>
								<td class="name"><?php $class_id=get_user_meta($retrieved_data->ID, 'class_name',true);
								echo $classname=mj_smgt_get_class_name($class_id);?></td>
								<td class="roll_no"><?php echo get_user_meta($retrieved_data->ID, 'roll_id',true);?></td>
								<td class="email"><?php echo $retrieved_data->user_email;?></td>
								 
								<td> <a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="view_member_bookissue_popup" class="btn btn-info"><?php esc_attr_e('View','school-mgt');?> </a>
								<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="accept_returns_book_popup" class="btn btn-success"><?php esc_attr_e('Accept Returns','school-mgt');?> </a>
								
								</td>
							   
							</tr>
					<?php } 
					} 
				}?>	
		 
			</tbody>
			
			</table>
        </div>
        </div>
<?php } ?>