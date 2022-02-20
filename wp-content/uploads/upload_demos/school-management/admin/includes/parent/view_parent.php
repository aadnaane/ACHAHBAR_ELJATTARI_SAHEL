<?php
 $parent_data=get_userdata($_REQUEST['parent_id']);
 $user_meta =get_user_meta($_REQUEST['parent_id'], 'child', true); 
?>
<div class="panel-body">	
	<div class="box-body">
		<div class="row">
			<div class="col-md-3 col-sm-4 col-xs-12">	
				<?php
				$umetadata=mj_smgt_get_user_image($parent_data->ID);
				if(empty($umetadata['meta_value']))
				{
					echo '<img class="img-circle rounded-circle img-responsive member-profile h-150-px w-150-px" src='.get_option( 'smgt_student_thumb' ).' />';
				}
				else
					echo '<img class="img-circle rounded-circle img-responsive member-profile user_height_width" src='.$umetadata['meta_value'].'>';
				?>
			</div>
			
			<div class="col-md-9 col-sm-8 col-xs-12 ">
				<div class="row">
					<h2><?php echo $parent_data->display_name;?></h2>
				</div>
				<div class="row">
					<div class="col-md-4 col-sm-3 col-xs-12">
						<i class="fa fa-envelope"></i>&nbsp;
						<span class="email-span"><?php echo $parent_data->user_email;?></span>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12">
						<i class="fa fa-phone"></i>&nbsp;
						<span><?php echo $parent_data->phone;?></span>
					</div>
					<div class="col-md-5 col-sm-3 col-xs-12 no-padding">
						<i class="fa fa-map-marker"></i>&nbsp;
						<span><?php echo $parent_data->address;?></span>
					</div>
				</div>					
			</div>
		</div>
			
		<div class="row">
			<div class="view-more view_more_details_div d-block" >
				<h4><?php esc_attr_e( 'View More', 'school-mgt' ) ;?></h4>
					<i class="fa fa-angle-down fa-2x bounce view_more_details"></i>
			</div>
			<div class="view-more view_more_details_less_div d-none">
				<h4><?php esc_attr_e( 'View Less', 'school-mgt' ) ;?></h4>
					<i class="fa fa-angle-up fa-2x view_more_details_less"></i>
			</div>
		</div>
		<hr>
			<div class="user_more_details d-none" >
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
								<p class="user-info">: <?php echo $parent_data->display_name;?></p>
							</div>
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'Birth Date', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo mj_smgt_getdate_in_input_box($parent_data->birth_date);?></p>
							</div>
							<div class="col-md-2">
									<p class="user-lable"><?php esc_attr_e( 'Gender', 'school-mgt' ) ;?></p>
								</div>
							<div class="col-md-4">
									<p class="user-info">: <?php echo $parent_data->gender;?></p>
							</div>
														
							 <div class="col-md-2">
									<p class="user-lable"><?php esc_attr_e( 'Relation', 'school-mgt' ) ;?></p>
								</div>
							<div class="col-md-4">
									<p class="user-info">: <?php echo $parent_data->relation;?></p>
							</div>
							
							 
						 
							</div>
						</div>						
					</div>
				<div class="card">
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
								<p class="user-info">: <?php echo $parent_data->address;?><br></p>
							</div>
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'City', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo $parent_data->city;?></p>
							</div>
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'State', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo $parent_data->state;?></p>
							</div>
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'Zipcode', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo $parent_data->zip_code;?></p>
							</div>
							<div class="col-md-2">
								<p class="user-lable"><?php esc_attr_e( 'Phone Number', 'school-mgt' ) ;?></p>
							</div>
							<div class="col-md-4">
								<p class="user-info">: <?php echo $parent_data->phone;?></p>
							</div>
						</div>											
					</div>
					 
				</div>
			</div>
		</div>
</div>
   
<div class="panel-body">
	<div class="row">	
		<ul class="nav nav-tabs">
			<li class="nav-item mb-0"><a data-toggle="tab" class="nav-link active" href="#Section1"><i class="fa fa-user"></i><b><?php esc_attr_e( ' Child', 'school-mgt' ); ?></b></a></li>
		</ul>
	
		<div class="tab-content">
			<div id="Section1" class="tab-pane  active">
				<div class="row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-content">
								 <div class="table-responsive">
									  <table id="child_list" class="display table" cellspacing="0" width="100%">
									  <thead>
											<tr>
											  <th><?php esc_attr_e('Photo','school-mgt');?></th>
											  <th><?php esc_attr_e('Child Name','school-mgt');?></th>
											  <th><?php esc_attr_e('Roll No','school-mgt');?></th>
											  <th><?php esc_attr_e('Class','school-mgt');?></th>
											  <th><?php esc_attr_e('Child Email','school-mgt');?></th>
											</tr>
										</thead>
										<tfoot>
											<tr>
											  <th><?php esc_attr_e('Photo','school-mgt');?></th>
											  <th><?php esc_attr_e('Child Name','school-mgt');?></th>
											  <th><?php esc_attr_e('Roll No','school-mgt');?></th>
											  <th><?php esc_attr_e('Class','school-mgt');?></th>
											  <th><?php esc_attr_e('Child Email','school-mgt');?></th>
											</tr>
										</tfoot>
										<tbody>
										<?php
										if(!empty($user_meta))
										{
											foreach($user_meta as $childsdata)
											{
											$child=get_userdata($childsdata);?>
										<tr>
										  <td><?php 
											if($childsdata)
											{
												$umetadata=mj_smgt_get_user_image($childsdata);
											}
											if(empty($umetadata['meta_value']))
											{
												echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle rounded-circle" />';
											}
											else
												echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle rounded-circle"/>';?></td>
										  <td><?php echo $child->first_name." ".$child->last_name;?></td>
										  <td><?php echo get_user_meta($child->ID, 'roll_id',true);?></td>
										  <td>
											<?php  $class_id=get_user_meta($child->ID, 'class_name',true);
											echo $classname=mj_smgt_get_class_name($class_id);?>
										  </td> 
										  <td><?php echo $child->user_email;?></td> 
										</tr>
										<?php
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
 
	 
