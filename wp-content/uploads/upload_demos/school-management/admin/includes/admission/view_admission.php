<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/common.js'; ?>" ></script>
<?php
 $student_data=get_userdata($_REQUEST['id']);
 $user_meta =get_user_meta($_REQUEST['id'], 'parent_id', true); 
 $custom_field_obj = new Smgt_custome_field;								
 $module='student';	
 $user_custom_field=$custom_field_obj->mj_smgt_getCustomFieldByModule($module);
 $sibling_information_value=str_replace('"[','[',$student_data->sibling_information);
 $sibling_information_value1=str_replace(']"',']',$sibling_information_value);
 $sibling_information=json_decode($sibling_information_value1);
?>
<div class="panel-body">	
	<div class="box-body">
		<div class="row">
			<div class="col-md-3 col-sm-4 col-xs-12">	
				<?php
				$umetadata=mj_smgt_get_user_image($student_data->ID);
				if(empty($umetadata['meta_value']))
				{
					echo '<img class="img-circle img-responsive member-profile" src='.get_option( 'smgt_student_thumb' ).' class="w-150-px h-150-px"/>';
				}
				else
					echo '<img class="img-circle img-responsive member-profile user_height_width" src='.$umetadata['meta_value'].'>';
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
			<div class="view-more view_more_details_div">
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
								<div class="col-md-2 padding_0_responsive">
									<p class="user-lable"><?php esc_attr_e( 'Name', 'school-mgt' ) ;?></p>
								</div>
								<div class="col-md-4 padding_0_responsive">
									<p class="user-info">: <?php echo $student_data->display_name;?></p>
								</div>
							
						<!--</div>
						<div class="row">-->
							<div class="col-md-2 padding_0_responsive">
								<p class="user-lable"><?php esc_attr_e( 'Birth Date', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4 padding_0_responsive">
								<p class="user-info">: <?php echo mj_smgt_getdate_in_input_box($student_data->birth_date);?></p>
							</div>
						</div>
						<div class="row">	
							<div class="col-md-2 padding_0_responsive">
									<p class="user-lable"><?php esc_attr_e( 'Gender', 'school-mgt' ) ;?></p>
								</div>
							<div class="col-md-4 padding_0_responsive">
									<!--<p class="user-info">: <?php echo $student_data->gender;?></p>-->
									<p class="user-info">: <?php 
													if($student_data->gender=='male') 
														echo esc_attr__('Male','school-mgt');
													elseif($student_data->gender=='female') 
														echo esc_attr__('Female','school-mgt');
													?></p>
							</div>
						<!--</div>
						<div class="row">-->
							<div class="col-md-2 padding_0_responsive">
								<p class="user-lable"><?php esc_attr_e( 'Admission Number', 'school-mgt' );?></p>
							</div>
							<div class="col-md-4 padding_0_responsive">
								<p class="user-info">: <?php echo $student_data->admission_no;?></p> 
							</div>
						</div>
						<div class="row">	
							<div class="col-md-2 padding_0_responsive">
								<p class="user-lable"><?php esc_attr_e( 'Admission Date', 'school-mgt' );?></p>
							</div>
							<div class="col-md-4 padding_0_responsive">
								<p class="user-info">: <?php echo mj_smgt_getdate_in_input_box($student_data->admission_date);?></p> 
							</div>
						
							<div class="col-md-2 padding_0_responsive">
								<p class="user-lable"><?php esc_attr_e( 'Previous School', 'school-mgt' );?></p>
							</div>
							<div class="col-md-4 padding_0_responsive">
								<p class="user-info">: <?php echo $student_data->preschool_name;?></p> 
							</div>
						</div>
							
						</div>						
					
					<div class="card-head">
						<i class="fa fa-map-marker"></i>
						<span> <b><?php esc_attr_e( 'Contact Information', 'school-mgt' ) ;?> </b></span>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-2 padding_0_responsive">
								<p class="user-lable"><?php esc_attr_e( 'Address', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4 padding_0_responsive">
								<p class="user-info">: <?php echo $student_data->address;?><br></p>
							</div>
							<div class="col-md-2 padding_0_responsive">
								<p class="user-lable"><?php esc_attr_e( 'City', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4 padding_0_responsive">
								<p class="user-info">: <?php echo $student_data->city;?></p>
							</div>
							
						</div>
						<div class="row">
							<div class="col-md-2 padding_0_responsive">
								<p class="user-lable"><?php esc_attr_e( 'State', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4 padding_0_responsive">
								<p class="user-info">: <?php echo $student_data->state;?></p>
							</div>
							<div class="col-md-2 padding_0_responsive">
								<p class="user-lable"><?php esc_attr_e( 'Zipcode', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4 padding_0_responsive">
								<p class="user-info">: <?php echo $student_data->zip_code;?></p>
							</div>
						</div>
						<div class="row phone_number_res">
							<div class="col-md-2 padding_0_responsive">
								<p class="user-lable"><?php esc_attr_e( 'Phone Number', 'school-mgt' ) ;?> :</p>
							</div>
							<div class="col-md-4 padding_0_responsive">
								<p class="user-info"> <?php echo $student_data->phone;?></p>
							</div>
						</div>
						</div>											
					<?php		
					if(!empty($sibling_information))
					{	?>
					<div class="card-head">
						<i class="fa fa-user"></i>
						<span> <b><?php esc_attr_e( 'Siblings Information', 'school-mgt' ) ;?> </b></span>
					</div>
					<div class="card-body">
						<?php
						$i=0;
						foreach($sibling_information as $value)
						{
							$i=$i+1;
							?>
						<div class="row view_siblling_css">		
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Name', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $value->siblingsname;?><br></p>
									</div>
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Age', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $value->siblingage;?><br></p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e('Standard', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: 
										<?php
										 if(!empty($value->sibling_standard))
										 {
											echo $sibling_standard=get_the_title($value->sibling_standard);
										 }
										 
										?><br></p>
									</div>
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e('SID Number', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $value->siblingsid;?><br></p>
									</div>
								</div>
							
						</div>
							<?php
							}
							?>
					</div>
					<?php
					}
					if (!empty($student_data->father_first_name) OR !empty($student_data->mother_first_name))
					{
						?>
					<div class="card-head">
						<i class="fa fa-user"></i>
						<span> <b><?php esc_attr_e( 'Family Information', 'school-mgt' ) ;?> </b></span>
					</div>
				<?php } ?>
					<div class="card-body">
						<?php
						if($student_data->parent_status == 'Father' || $student_data->parent_status == 'Both')
						{
							if (!empty($student_data->father_first_name))
							{
						?>
							<div class="row">
								
								<div class="col-md-12">
								<h3 class="view_admission_heading"><b><?php esc_attr_e('Father Information', 'school-mgt'); ?> :</b></h3>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Name', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->fathersalutation.' '.$student_data->father_first_name.''.$student_data->father_last_name;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Gender', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->fathe_gender;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Date of birth', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo mj_smgt_getdate_in_input_box($student_data->father_birth_date); ?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Address', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->father_address;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'State', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->father_state_name;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'City', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->father_city_name;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Zip Code', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->father_zip_code;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Email', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->father_email;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Mobile No', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->father_mobile;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'School Name', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->father_school;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Medium of Instruction', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->father_medium;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Educational Qualification', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->father_education;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive clearfix">
										<p class="user-lable"><?php esc_attr_e( 'Annual Income', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->fathe_income;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Occupation', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->father_occuption;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
								<div class="col-md-2 padding_0_responsive">
									<p class="user-lable"><?php esc_attr_e( 'Proof of Qualification', 'school-mgt' ) ;?></p>
								</div>
								
								<div class="col-md-4 padding_0_responsive">
									<?php
									$father_doc=str_replace('"[','[',$student_data->father_doc);
									 $father_doc1=str_replace(']"',']',$father_doc);
									 $father_doc_info=json_decode($father_doc1);
									?>
									<p class="user-info">: 
									<?php if (!empty($father_doc_info[0]->value))
									{ ?>
									 <a download href="<?php print content_url().'/uploads/school_assets/'.'$father_doc_info[0]->value;' ?>"  class="status_read btn btn-default"><i class="fa fa-download"></i><?php
									if(!empty($father_doc_info[0]->title))
									{									
										echo $father_doc_info[0]->title;
									}
									else
									{
										 esc_html_e(' Download', 'school-mgt');
									}
									?></a>
								<?php } ?>
									<br></p>
								</div>
								</div>
								
							</div>
						<?php
							}
						}						
						if($student_data->parent_status == 'Mother' || $student_data->parent_status == 'Both')
						{
							if (!empty($student_data->mother_first_name))
							{
						?>
							<div class="row">
								<div class="col-md-12">
								<h3 class="view_admission_heading"><b><?php esc_attr_e( 'Mother Information', 'school-mgt' ) ;?> :</b></h3>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Name', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mothersalutation.' '.$student_data->mother_first_name.''.$student_data->mother_last_name;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Gender', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mother_gender;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Date of birth', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo mj_smgt_getdate_in_input_box($student_data->mother_birth_date); ?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Address', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mother_address;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'State', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mother_state_name;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'City', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mother_city_name;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Zip Code', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mother_zip_code;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Email', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mother_email;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Mobile No', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mother_mobile;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'School Name', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mother_school;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Medium of Instruction', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mother_medium;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Educational Qualification', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mother_education;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive clearfix">
										<p class="user-lable"><?php esc_attr_e( 'Annual Income', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mother_income;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
									<div class="col-md-2 padding_0_responsive">
										<p class="user-lable"><?php esc_attr_e( 'Occupation', 'school-mgt' ) ;?></p>
									</div>
									<div class="col-md-4 padding_0_responsive">
										<p class="user-info">: <?php echo $student_data->mother_occuption;?><br></p>
									</div>
								</div>
								<div class="dis_flex_res">
								<div class="col-md-2 padding_0_responsive">
									<p class="user-lable"><?php esc_attr_e( 'Proof of Qualification', 'school-mgt' ) ;?></p>
								</div>
								<div class="col-md-4 padding_0_responsive">
									<?php
									$mother_doc=str_replace('"[','[',$student_data->mother_doc);
									$mother_doc1=str_replace(']"',']',$mother_doc);
									$mother_doc_info=json_decode($mother_doc1);
									?>
									<p class="user-info">:  
									<?php if (!empty($mother_doc_info[0]->value))
									{ ?>
									<a download href="<?php print content_url().'/uploads/school_assets/'.'$mother_doc_info[0]->value;' ?>"  class=" btn btn-default" <?php if (empty($mother_doc_info[0])) { ?> disabled <?php } ?>><i class="fa fa-download"></i>
									<?php
									if(!empty($mother_doc_info[0]->title))
									{									
										echo $mother_doc_info[0]->title;
									}
									else
									{
										 esc_html_e(' Download', 'school-mgt');
									}
									?></a>
								<?php } ?>
									<br></p>
								</div>
								</div>
								
							</div>	
						<?php
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
</div>
	 
