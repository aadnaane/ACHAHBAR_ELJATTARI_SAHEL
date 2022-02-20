<div class="panel-body">
        <div class="table-responsive">
			<form id="frm-example" name="frm-example" method="post">
			
				<table id="submission_list" class="display" cellspacing="0" width="100%">
				    <thead>
						<tr>
							<th><?php esc_attr_e('Homework Title','school-mgt');?></th>
							<th><?php esc_attr_e('Class','school-mgt');?></th>
							<th><?php esc_attr_e('Student','school-mgt');?></th>
							<th><?php esc_attr_e('Subject','school-mgt');?></th>
							<th><?php esc_attr_e('Status','school-mgt');?></th>
							<th><?php esc_attr_e('Submitted Date','school-mgt');?></th>
							<th><?php esc_attr_e('Date','school-mgt');?></th>
							<td><?php esc_attr_e('Action','school-mgt');?></td>
						</tr>
					</thead>
		 
					<tfoot>
						<tr>
							<th><?php esc_attr_e('Homework Title','school-mgt');?></th>
							<th><?php esc_attr_e('Class','school-mgt');?></th>
							<th><?php esc_attr_e('Student','school-mgt');?></th>
							<th><?php esc_attr_e('Subject','school-mgt');?></th>
							<th><?php esc_attr_e('Status','school-mgt');?></th>
							<th><?php esc_attr_e('Submitted Date','school-mgt');?></th>
							<th><?php esc_attr_e('Date','school-mgt');?></th>
							<td><?php esc_attr_e('Action','school-mgt');?></td>
						</tr>
					</tfoot>
			 
					<tbody>
					  <?php 
						foreach ($retrieve_class as $retrieved_data)
						{ ?>
							<tr>
								<td><?php echo $retrieved_data->title;?></td>
								<td><?php echo mj_smgt_get_class_name($retrieved_data->class_name);?></td>
								<td><?php echo mj_smgt_get_user_name_byid($retrieved_data->student_id);?></td>
								<td><?php echo mj_smgt_get_single_subject_name($retrieved_data->subject);?></td>
								<?php  
										if($retrieved_data->status==1)
										{
											if(date('Y-m-d',strtotime($retrieved_data->uploaded_date)) <= $retrieved_data->submition_date)
											{
											 ?>
											 <td><label class="green_color"><?php esc_attr_e('Submitted','school-mgt'); ?></label></td>
											 <?php
											 }
											 else
											 {
											 ?><td><label class="green_color"><?php esc_attr_e('Late-Submitted','school-mgt');?></label></td><?php
											 }
										}
										else 
										{?>
											  <td><label class="color-red"><?php esc_attr_e('Pending','school-mgt');?></label></td>
											  <?php
												 
										}?>
									   <?php  
										if($retrieved_data->uploaded_date==0000-00-00)
										{
											 ?>
											<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "NA ";?></td>
											<?php 
										} 
										else
										{?>
											<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->uploaded_date);?></td>
											<?php 
										}?>
											<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->created_date);?></td>
										   <?php 
										if($retrieved_data->status == 1)
										{ 
									    ?>
											<td> 
											<a download href="<?php print content_url().'/uploads/homework_file/'.$retrieved_data->file; ?>" class="status_read btn btn-info" record_id="<?php echo $retrieved_data->stu_homework_id;?>" download><?php esc_html_e(' Download', 'school-mgt');?></a></td>
										<?php 
										} 
										else 
										{ 
										?>
											<td><a   href="<?php echo SMS_PLUGIN_URL;?>/uploadfile/<?php echo $retrieved_data->file;?>" disabled="disabled" class="btn btn-info btn-disabled"> <?php esc_attr_e('Download','school-mgt');?></a></td><?php 
										}?>
							</tr>
							<?php 
						} ?>
					</tbody>
				</table>
			</form>
        </div>
        </div>
       </div>