
<?php
 $student_data=get_userdata($_REQUEST['student_id']);
$user_meta =get_user_meta($_REQUEST['student_id'], 'parent_id', true);
 $parent_list 	= 	mj_smgt_get_student_parent_id($_REQUEST['student_id']);	
 $custom_field_obj = new Smgt_custome_field;								
 $module='student';	
 $user_custom_field=$custom_field_obj->mj_smgt_getCustomFieldByModule($module);
?>
<div class="panel-body view_student_padding_0">	
	<div class="box-body">
		<div class="row">
			<div class="col-md-3 col-sm-4 col-xs-12">	
				<?php
				$umetadata=mj_smgt_get_user_image($student_data->ID);
				if(empty($umetadata))
				{
					echo '<img class="img-circle rounded-circle img-responsive member-profile h-150-px w-150-px" src='.get_option( 'smgt_student_thumb' ).' />';
				}
				else
					echo '<img class="img-circle rounded-circle img-responsive member-profile user_height_width" src='.$umetadata.'>';
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
						<i class="fa fa-map-marker"></i>&nbsp;
						<span><?php echo $student_data->address;?></span>
					</div>
				</div>					
			</div>
		</div>
			
		<div class="row">
			<div class="view-more view_more_details_div d-block">
				<h4><?php esc_attr_e( 'View More', 'school-mgt' ) ;?></h4>
					<i class="fa fa-angle-down bounce fa-2x view_more_details"></i>
			</div>
			<div class="view-more view_more_details_less_div d-none">
				<h4><?php esc_attr_e( 'View Less', 'school-mgt' ) ;?></h4>
					<i class="fa fa-angle-up fa-2x view_more_details_less"></i>
			</div>
		</div>
		<hr>
			<div class="user_more_details d-none">
				<div class="card">
					<div class="card-head">
						<i class="fa fa-user"></i>
						<span><b><?php esc_attr_e( 'Personal Information', 'school-mgt' ) ;?></b></span>
					</div>
					<div class="card-body">
						<div class="row">							
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'Name', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo $student_data->display_name;?></p>
							</div>
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'Birth Date', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo mj_smgt_getdate_in_input_box($student_data->birth_date);?></p>
							</div>
							<div class="col-md-2">
									<p class="user-lable"><?php esc_attr_e( 'Gender', 'school-mgt' ) ;?></p>
								</div>
							<div class="col-md-4">
									<p class="user-info">: <?php 
									if($student_data->gender=='male') 
										echo esc_attr__('Male','school-mgt');
									elseif($student_data->gender=='female') 
										echo esc_attr__('Female','school-mgt');
									?></p>
							</div>
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'Roll No', 'school-mgt' );?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo $student_data->roll_id;?></p> 
							</div>
							
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'Class Name', 'school-mgt' );?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo mj_smgt_get_class_name($student_data->class_name);?></p> 
							</div>
							
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'Section Name', 'school-mgt' );?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php 
									if(($student_data->class_section)!="")
									{
										echo mj_smgt_get_section_name($student_data->class_section); 
									}
									else
									{
										esc_attr_e('No Section','school-mgt');;
									}?>
								</p> 
							</div>
						</div>						
					</div>
					
					<div class="card-head">
						<i class="fa fa-map-marker"></i>
						<span> <b><?php esc_attr_e( 'Contact Information', 'school-mgt' ) ;?> </b></span>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'Address', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo $student_data->address;?><br></p>
							</div>
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'City', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo $student_data->city;?></p>
							</div>
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'State', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo $student_data->state;?></p>
							</div>
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'Zipcode', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo $student_data->zip_code;?></p>
							</div>
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'Phone Number', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo $student_data->phone;?></p>
							</div>
						</div>											
					</div>
					<?php
					if(!empty($user_custom_field))
					{	?>
					<div class="card-head">
						<i class="fa fa-bars"></i>
						<span> <b><?php esc_attr_e( 'Other Information', 'school-mgt' ) ;?> </b></span>
					</div>
					<div class="card-body">
						<div class="row">
							 <?php
									foreach($user_custom_field as $custom_field)
									{
										$custom_field_id=$custom_field->id;
									 
										$module_record_id=$_REQUEST['student_id'];
										 
										$custom_field_value=$custom_field_obj->mj_smgt_get_single_custom_field_meta_value($module,$module_record_id,$custom_field_id);
										?>
										<div class="col-xl-2 col-lg-2">
										<p class="user-lable"><?php esc_attr_e(''.$custom_field->field_label.'','school-mgt'); ?></p>
										</div>	
										<?php
										if($custom_field->field_type =='date')
										{	
											?>
											<div class="col-xl-4 col-lg-4">
											<p class="user-info">: <?php if(!empty($custom_field_value)){ echo mj_smgt_getdate_in_input_box($custom_field_value); }else{ echo '-'; } ?>
											</p></div>	
											<?php
										}
										elseif($custom_field->field_type =='file')
										{
											if(!empty($custom_field_value))
											{
											?>
											<div class="col-xl-4 col-lg-4"><p class="user-info">
											<a target="blank" href="<?php echo content_url().'/uploads/school_assets/'.$custom_field_value;?>"><button class="btn btn-default view_document" type="button">
													<i class="fa fa-eye"></i> <?php esc_attr_e('View','school-mgt');?></button></a>
														
													<a target="" href="<?php echo content_url().'/uploads/school_assets/'.$custom_field_value;?>" download="CustomFieldfile"><button class="btn btn-default view_document" type="button">
													<i class="fa fa-download"></i> <?php esc_attr_e('Download','school-mgt');?></button></a></p>
											</div>		
											<?php 
											}
											else
											{
												echo '-';
											}
										}
										else
										{
											?>
											<div class="col-xl-4 col-lg-4">
										<p class="user-info"><?php if(!empty($custom_field_value)){ echo $custom_field_value; }else{ echo '-'; } ?></p>
											</div>	
											<?php		
										}									
									}
									?>	
						</div>											
					</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
</div>
   
<div class="panel-body">
	<div class="row">	
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#Section1"><i class="fa fa-user"></i><b><?php esc_attr_e( ' Parents', 'school-mgt' ); ?></b></a></li>
		</ul>
		<div class="tab-content">
			<div id="Section1" class="">
				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-content">
								 <div class="table-responsive">
									  <table id="parents_list" class="display table" cellspacing="0" width="100%">
										<thead>
											<tr>
											  <th><?php esc_attr_e('Photo','school-mgt');?></th>
											  <th><?php esc_attr_e('Name','school-mgt');?></th>
											  <th><?php esc_attr_e('Email','school-mgt');?></th>
											  <th><?php esc_attr_e('Phone number','school-mgt');?></th>
											  <th> <?php esc_attr_e('Relation','school-mgt');?></th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th><?php esc_attr_e('Photo','school-mgt');?></th>
												<th><?php esc_attr_e('Name','school-mgt');?></th>
												<th><?php esc_attr_e('Email','school-mgt');?></th>
												<th><?php esc_attr_e('Phone number','school-mgt');?></th>
												<th> <?php esc_attr_e('Relation','school-mgt');?></th>
											</tr>
										</tfoot>
										<tbody>
										<?php
										if(!empty($user_meta))
										{
											foreach($user_meta as $parentsdata)
											{
												if(!empty($parentsdata->errors))
												{
													$parent = "";
												}
												else
												{
													$parent=get_userdata($parentsdata);
												}

												if (!empty($parent)) 
												{

												?>
												
										<tr>
										  <td><?php 
											if($parentsdata)
											{
												$umetadata=mj_smgt_get_user_image($parentsdata);
											}
											if(empty($umetadata['meta_value']))
											{
												echo '<img src='.get_option( 'smgt_parent_thumb' ).' height="50px" width="50px" class="img-circle rounded-circle" />';
											}
											else
												echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle rounded-circle"/>';?></td>
											 <td><?php echo $parent->first_name." ".$parent->last_name;?></td>
											 <td><?php echo $parent->user_email;?></td> 
											 <td><?php echo $parent->phone;?></td>
										  <td><?php if($parent->relation=='Father'){ echo esc_attr__('Father','school-mgt'); }elseif($parent->relation=='Mother'){ echo esc_attr__('Mother','school-mgt');} ?></td>
										</tr>
										<?php
									}
											}
										}
										?>
									</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<div id="Section2" class="tab-pane fade">
					 
		</div>
		 
		</div>
	</div>
</div>
 
	 
