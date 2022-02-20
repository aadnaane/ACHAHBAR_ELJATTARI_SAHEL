<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
	jQuery('#user_account_info').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	 
	jQuery('#user_other_info').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	 
	jQuery(document).ready(function($)
	{
	  jQuery("body").on("change", ".profile_file", function ()
		{ 
		
			 "use strict";
			var file = this.files[0]; 		
			var ext = $(this).val().split('.').pop().toLowerCase(); 
			//Extension Check 
			if($.inArray(ext, ['jpeg', 'jpg', 'png', 'bmp','']) == -1)
			{
				 alert('Only jpeg,jpg,png and bmp formate are allowed. '  + ext + ' formate are not allowed.');
				 $(".profile_file").val("");
				 return false; 
			} 
		});	
	  
	});

});
</script>
<?php 
$school_obj = new School_Management ( get_current_user_id () );
$user = wp_get_current_user ();
	$user_info=get_userdata($user->ID);
	$user_data =get_userdata( $user->ID);
	require_once ABSPATH . 'wp-includes/class-phpass.php';
	$wp_hasher = new PasswordHash( 8, true );
		if(isset($_POST['save_change']))
		{
			$nonce = $_POST['_wpnonce'];
			if (  wp_verify_nonce( $nonce, 'password_save_change_nonce' ) )
			{
				$referrer = $_SERVER['HTTP_REFERER'];
				
				$success=0;
				if($wp_hasher->CheckPassword($_REQUEST['current_pass'],$user_data->user_pass))
				{
					
					if(isset($_REQUEST['new_pass'])==$_REQUEST['conform_pass'])
					{
						 wp_set_password( $_REQUEST['new_pass'], $user->ID);
							$success=1;
					}
					else
					{
						wp_redirect($referrer.'&sucess=2');
					}			
				}
				else{
					
					wp_redirect($referrer.'&sucess=3');
				}
				if($success==1)
				{
					 wp_cache_delete($user->ID,'users');
					wp_cache_delete($user_data->user_login,'userlogins');
					wp_logout();
					if(wp_signon(array('user_login'=>$user_data->user_login,'user_password'=>$_REQUEST['new_pass']),false)):
						$referrer = $_SERVER['HTTP_REFERER'];
						
						wp_redirect($referrer.'&sucess=1');
					endif;
					ob_start();
				}else{
					wp_set_auth_cookie($user->ID, true);
				}
			
		    }
		}
	 if(isset($_POST['save_change_new']))
		{
			$nonce = $_POST['_wpnonce'];
			if (  wp_verify_nonce( $nonce, 'password_save_change_nonce_new' ) )
			{
				$referrer = $_SERVER['HTTP_REFERER'];
				
				$success=0;
				if($wp_hasher->CheckPassword($_REQUEST['current_pass'],$user_data->user_pass))
				{
					
					if(isset($_REQUEST['new_pass'])==$_REQUEST['conform_pass'])
					{
						 wp_set_password( $_REQUEST['new_pass'], $user->ID);
							$success=1;
					}
					else
					{
						wp_redirect($referrer.'&sucess=2');
					}			
				}
				else{
					
					wp_redirect($referrer.'&sucess=3');
				}
				if($success==1)
				{
					 wp_cache_delete($user->ID,'users');
					wp_cache_delete($user_data->user_login,'userlogins');
					wp_logout();
					if(wp_signon(array('user_login'=>$user_data->user_login,'user_password'=>$_REQUEST['new_pass']),false)):
						$referrer = $_SERVER['HTTP_REFERER'];
						
						wp_redirect($referrer.'&sucess=1');
					endif;
					ob_start();
				}else{
					wp_set_auth_cookie($user->ID, true);
				}
			
		    }
		}
	$coverimage=get_option( 'smgt_school_background_image' );
	if($coverimage!="")
	{
		?>
		
<?php 
	} ?>
	
	<!-- POP up code -->
	<div class="popup-bg">
		<div class="overlay-content">
			<div class="modal-content">
				<div class="profile_picture">
				 </div>
			</div>
	   </div> 
	</div>
	<!-- End POP-UP Code -->
<div>
	<div class="profile-cover">
		<div class="row">
			<div class="col-md-3 profile-image">
				<div class="profile-image-container">
				<?php 
					$umetadata=mj_smgt_get_user_image($user->ID);
					if(empty($umetadata))
					{
						echo '<img src='.get_option( 'smgt_student_thumb' ).' height="150px" width="150px" class="img-circle" />';
					}
					else
					{
						echo '<img src='.$umetadata.' height="150px" width="150px" class="img-circle" />';
					}
				?>
				</div>
				<div class="col-md-1 update_dp">
					<button class="btn btn-default btn-file w m-3" type="file" name="profile_change" id="profile_change"><?php esc_attr_e('Update Profile','school-mgt');?></button>
				</div>
			</div>
		</div>
	</div>
	
	<?php 
		if(($school_obj->role)=='teacher')
		{
			$teacher_id=$user->ID;
		}
	?>
	<div Id="main-wrapper_fronend"> 
		<div class="row">
			<div class="col-md-3 user-profile">
				<h3 class="text-center account_name">
					<?php 
						echo $user_data->display_name;
					?>
				</h3>
				<p class="text-center">
				<?php 
				if(isset($teacher_id)){
					echo '<strong>'.esc_attr__('Teach Subject','school-mgt').' : </strong>'.rtrim(mj_smgt_get_subject_name_by_teacher($teacher_id),", ");
					$user_info=get_userdata($user->ID);
				}
				if(($school_obj->role)=='student'){
					$user_info=get_userdata($user->ID);
					
					echo "". esc_html__('Class','school-mgt')." : ".mj_smgt_get_class_name($user_info->class_name);
					
				} ?></p>
				<hr>
				<ul class="list-unstyled text-center">
				<li>
				<p><i class="fa fa-map-marker m-r-xs"></i>
					<a href="#"><?php echo $user_data->address.",".$user_data->city;?></a></p>
				</li>	
				<li><i class="fa fa-envelope m-r-xs"></i>
							<a href="#"><?php echo 	$user_data->user_email;?></a></p>
				</p></li>
				</ul>
			</div>
			<?php 
			if(($school_obj->role)!='teacher')
			{
				 if(($school_obj->role)=='student')
				 {
					 $user_meta =get_user_meta($user->ID, 'parent_id', true); 
					 $title="Parent";
				 }
				 else if(($school_obj->role)=='parent')
				 {
					 $user_meta =get_user_meta($user->ID, 'child', true); 
					 $title="Child";
				 }
				 else
				 {
					  $user_meta = NULL;
				 }
				?>
				<div class="col-md-6 m-t-lg">
					<div class="panel panel-white">
						<div class="panel-heading">
							<div class="panel-title"><?php esc_attr_e('Account Settings ','school-mgt');?>	</div>
						</div>
						<div class="panel-body ">
							<form class="form-horizontal" action="#" id="user_account_info" method="post">
								<div class="form-group row mb-3">
									<label  class="control-label col-form-label text-md-end clo-md-6 col-sm-6 col-xs-12"></label>
									<div class="col-xs-10">	
										<p>
										<?php 
										if(isset($_REQUEST['sucess']))
										{ 
											if($_REQUEST['sucess']==1)
											{
												?>
												<h4 class="bg-success p-10-px">
												<?php
												echo esc_attr_e('Password successfully changed','school-mgt'); 
												?>
												</h4>
												<?php
											}
											elseif($_REQUEST['sucess']==3)
											{
												?>
												<h4 class="bg-danger p-10-px">
												<?php
												echo esc_attr_e('Please Enter correct current password','school-mgt'); 
												?>
												</h4>
												<?php												
											}
											elseif($_REQUEST['sucess']==5)
											{
												?>
												<h4 class="bg-success p-10-px">
												<?php
												echo esc_attr_e('Profile successfully changed','school-mgt'); 
												?>
												</h4>
												<?php												
											}
										}
										?>
										</p>
									</div>
								</div>
								<div class="form-group row mb-3">
									<label for="inputEmail" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Name','school-mgt');?></label>
									<div class="clo-md-8 col-sm-8 col-xs-12">
										<input type="Name" class="form-control " id="name" placeholder="Full Name" value="<?php echo $user->display_name; ?>" readonly>
									</div>
								</div>
								<div class="form-group row mb-3">
									<label for="inputEmail" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Username','school-mgt');?></label>
									<div class="clo-md-8 col-sm-8 col-xs-12">
										<input type="username" class="form-control " id="name" placeholder="<?php esc_attr_e('Full Name','school-mgt') ?>" value="<?php echo $user->user_login; ?>" readonly>
									</div>
								</div>
								<div class="form-group row mb-3">
									<label for="inputPassword" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12 "><?php esc_attr_e('Current Password','school-mgt');?><span class="require-field">*</span></label>
									<div class="clo-md-8 col-sm-8 col-xs-12">
										<input type="password" class="form-control validate[required]" id="inputPassword" placeholder="<?php esc_attr_e('Password','school-mgt'); ?>" name="current_pass">
									</div>
								</div>
								<div class="form-group row mb-3">
									<label for="inputPassword" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('New Password','school-mgt');?><span class="require-field">*</span></label>
									<div class="clo-md-8 col-sm-8 col-xs-12">
										<input type="password" class="validate[required,minSize[8],maxSize[12],equals[new_pass]] form-control" id="new_pass" placeholder="<?php esc_attr_e('New Password','school-mgt'); ?>" name="new_pass">
									</div>
								</div>
								<div class="form-group row mb-3">
									<label for="inputPassword" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Confirm Password','school-mgt'); ?><span class="require-field">*</span></label>
									<div class="clo-md-8 col-sm-8 col-xs-12">
										<input type="password" class="validate[required,minSize[8],maxSize[12]] form-control" id="inputPassword" placeholder="<?php esc_attr_e('Confirm Password','school-mgt') ?>" name="conform_pass">
									</div>
								</div>
								<div class="form-group row mb-3">
									<div class="offset-sm-2 clo-md-8 col-sm-8 col-xs-12">
										<button type="submit" class="btn btn-success" name="save_change"><?php esc_attr_e('Save','school-mgt');?></button>
									</div>
								</div>
								<?php wp_nonce_field( 'password_save_change_nonce' ); ?>
							</form>
						</div>
					</div>
					
					<?php 
						$edit=1;
						
						?>
					<div class="panel panel-white">
					<div class="panel-heading">
							<div class="panel-title"><?php esc_attr_e('Other Information','school-mgt');?>	</div>
							</div>
							<div class="panel-body">
							<form class="form-horizontal" id="user_other_info" action="#" method="post">
								<div class="form-group row mb-3">
									<label  class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"></label>
									<div class="clo-md-8 col-sm-8 col-xs-12">	
										<p>
										<?php 
										if(isset($_REQUEST['sucess']))
										{ 
											if($_REQUEST['sucess']==4)
											{
												?>
												<h4 class="bg-success p-10-px">
												<?php
												echo esc_attr_e('Record updated successfully.','school-mgt'); 
												?>
												</h4>
												<?php
											}																				
										}
										?>
									</p>
									</div>
								</div>	
								<div class="form-group row mb-3">

									<label for="inputEmail" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Address','school-mgt');?><span class="require-field">*</span></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input id="address" class="form-control validate[required,custom[address_description_validation]]" type="text"  name="address" maxlength="150" value="<?php if($edit){ echo $user_info->address;}?>">

									</div>

								</div>
								<div class="form-group row mb-3">

									<label for="inputEmail" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('City','school-mgt');?><span class="require-field">*</span></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" type="text"  name="city_name" maxlength="50" value="<?php if($edit){ echo $user_info->city;}?>">

									</div>

								</div>
								<div class="form-group row mb-3">

									<label for="inputstate" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('State','school-mgt');?></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" value="<?php if($edit){ echo $user_info->state;}?>">

									</div>

								</div>
								<div class="form-group row mb-3">

									<label for="inputEmail" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Phone','school-mgt');?><span class="require-field">*</span></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input id="phone" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="phone" value="<?php if($edit){ echo $user_info->phone;}?>">

									</div>

								</div>
								<div class="form-group row mb-3">

									<label for="inputEmail" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input id="email" class="form-control validate[required,custom[email]] text-input"  type="text" maxlength="100" name="email" value="<?php if($edit){ echo $user_info->user_email;}?>">

									</div>

								</div>
								<div class="form-group row mb-3">

									<div class="offset-sm-2 clo-md-8 col-sm-8 col-xs-12">

										<button type="submit" class="btn btn-success" name="profile_save_change_new"><?php esc_attr_e('Save','school-mgt');?></button>

									</div>
								</div>
								<?php wp_nonce_field( 'profile_save_change_nonce_new' ); ?>
								
							</form>
							</div>
							</div>
							
			</div>
			<?php	
				if(!empty($user_meta))
				{
					?>
					<div class="col-md-3 m-t-lg">
						<div class="panel panel-white">
							<div class="panel-heading">
								<div class="panel-title"><?php esc_html_e("$title","school-mgt"); ?></div>
							</div>
							<div class="panel-body d-inline-block">
								<div class="team">
									<?php 
										foreach($user_meta as $parentsdata)
										{
											$parent=get_userdata($parentsdata);
											if($parent)
											{
										?>
												<div class="team-member margin_top_10">
												<?php 
													if($parentsdata)
													{
														$umetadata=mj_smgt_get_user_image($parentsdata);
													}
													if(empty($umetadata['meta_value']) || $umetadata['meta_value'] == "")
													{ 
														echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle" />';
													}
													else
													echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle"/>';?></td>
													<span>
													<?php echo $parent->display_name;?> </span><br>
													<small>
													<?php
													if($title=='Parent')
													{
														echo $parent->relation;
													}
													if($title=='child')
													{
														echo "Class : ".mj_smgt_get_class_name($parent->class_name) ;
													}
													?> 
													</small>
												</div>
								<?php
											}
										}?>
								</div>
							</div>
						</div>
					</div>
				<?php 
				} ?>
		</div>
			<?php 
			}
			else
			{
			?>
			<div class="col-md-9 m-t-lg">
				<div class="panel panel-white">
					<div class="panel-heading">
											<div class="panel-title"><?php esc_attr_e('Account Settings','school-mgt');?> </div>
										</div>
										<div class="panel-body">
							<form class="form-horizontal" id="user_account_info" action="#" method="post">
									<div class="form-group row mb-3">
										<label  class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"></label>
										<div class="clo-md-8 col-sm-8 col-xs-12">	
											<p>
											<?php 
											if(isset($_REQUEST['sucess']))
											{ 
												if($_REQUEST['sucess']==1)
												{
													?>
													<h4 class="bg-success p-10-px">
													<?php
													echo esc_attr_e('Password successfully changed','school-mgt'); 
													?>
													</h4>
													<?php
												}
												elseif($_REQUEST['sucess']==3)
												{
													?>
													<h4 class="bg-danger p-10-px">
													<?php
													echo esc_attr_e('Please Enter correct current password','school-mgt'); 
													?>
													</h4>
													<?php												
												}											
											}
											?>
										</p>
										</div>
									</div>
								<div class="form-group row mb-3">

									<label for="inputEmail" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Name','school-mgt');?></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input type="Name" class="form-control" id="name" placeholder="Full Name" value="<?php echo $user->display_name; ?>" readonly>

									</div>

								</div>
								<div class="form-group row mb-3">

									<label for="inputEmail" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Name','school-mgt');?></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input type="Name" class="form-control" id="name" placeholder="Full Name" value="<?php echo $user->user_login; ?>" readonly>

									</div>

								</div>

								<div class="form-group row mb-3">

									<label for="inputPassword" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Current Password','school-mgt');?><span class="require-field">*</span></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input type="password" class="form-control validate[required]" id="inputPassword" placeholder="<?php esc_attr_e('Password','school-mgt');?>" name="current_pass">

									</div>

								</div>
						<div class="form-group row mb-3">

									<label for="inputPassword" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('New Password','school-mgt');?><span class="require-field">*</span></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input type="password" class="validate[required,minSize[8],maxSize[12]] form-control" id="new_pass" placeholder="<?php esc_attr_e('New Password','school-mgt');?>" name="new_pass">

									</div>

								</div><div class="form-group row mb-3">

									<label for="inputPassword" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Confirm Password','school-mgt');?><span class="require-field">*</span></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input type="password" class="validate[required,minSize[8],maxSize[12],equals[new_pass]] form-control" id="inputPassword" placeholder="<?php esc_attr_e('Confirm Password','school-mgt');?>" name="conform_pass">

									</div>

								</div>
								

								<div class="form-group row mb-3">

									<div class="col-xs-offset-2 clo-md-8 col-sm-8 col-xs-12">

										<button type="submit" class="btn btn-success" name="save_change_new"><?php esc_attr_e('Save','school-mgt');?></button>

									</div>

								</div>
						<?php wp_nonce_field( 'password_save_change_nonce_new' ); ?>
							</form>
							</div>
							</div>
								
								<?php 
						$edit=1;
						?>
					<div class="panel panel-white">
					<div class="panel-heading">
							<div class="panel-title"><?php esc_attr_e('Other Information','school-mgt');?>	</div>
							</div>
							<div class="panel-body">
							<form class="form-horizontal" id="user_other_info" action="#" method="post">
								<div class="form-group row mb-3">
									<label  class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"></label>
									<div class="clo-md-8 col-sm-8 col-xs-12">	
										<p>
										<?php 
										if(isset($_REQUEST['sucess']))
										{ 
											if($_REQUEST['sucess']==4)
											{
												?>
												<h4 class="bg-success p-10-px">
												<?php
												echo esc_attr_e('Record updated successfully.','school-mgt'); 
												?>
												</h4>
												<?php
											}																				
										}
										?>
									</p>
									</div>
								</div>	
								<div class="form-group row mb-3">

									<label for="inputEmail" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Address','school-mgt');?><span class="require-field">*</span></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input id="address" class="form-control validate[required,custom[address_description_validation]]" type="text"  name="address" maxlength="150" value="<?php if($edit){ echo $user_info->address;}?>">

									</div>

								</div>
								<div class="form-group row mb-3">

									<label for="inputEmail" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('City','school-mgt');?><span class="require-field">*</span></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" type="text"  name="city_name" maxlength="50" value="<?php if($edit){ echo $user_info->city;}?>">

									</div>

								</div>
								<div class="form-group row mb-3">

									<label for="inputstate" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('State','school-mgt');?></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" value="<?php if($edit){ echo $user_info->state;}?>">

									</div>

								</div>
								<div class="form-group row mb-3">

									<label for="inputEmail" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Phone','school-mgt');?><span class="require-field">*</span></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input id="phone" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="phone" value="<?php if($edit){ echo $user_info->phone;}?>">

									</div>

								</div>
								<div class="form-group row mb-3">

									<label for="inputEmail" class="control-label col-form-label text-md-end clo-md-2 col-sm-2 col-xs-12"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>

									<div class="clo-md-8 col-sm-8 col-xs-12">

										<input id="email" class="form-control validate[required,custom[email]] text-input"  type="text" maxlength="100" name="email" value="<?php if($edit){ echo $user_info->user_email;}?>">

									</div>

								</div>
								<div class="form-group row mb-3">

									<div class="col-xs-offset-2 clo-md-8 col-sm-8 col-xs-12">

										<button type="submit" class="btn btn-success" name="profile_save_change"><?php esc_attr_e('Save','school-mgt');?></button>

									</div>
								</div>
								<?php wp_nonce_field( 'profile_save_change_nonce' ); ?>
							</form>
							</div>
							</div>
			</div>
					<?php 
			} ?>
	</div>
</div>
</div>
</div>

<?php 
	if(isset($_POST['profile_save_change']))
	{
        $nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'profile_save_change_nonce' ) )
		{		
			$usermetadata=array(
							'address'=>mj_smgt_address_description_validation($_POST['address']),
							'city'=>mj_smgt_city_state_country_validation($_POST['city_name']),
							'state'=>mj_smgt_city_state_country_validation($_POST['state_name']),
							'phone'=>mj_smgt_phone_number_validation($_POST['phone']));
		
			$userdata = array('user_email'=>mj_smgt_email_validation($_POST['email']));
				
			$userdata['ID']=$user->ID;
			
			$result=mj_smgt_update_user_profile($userdata,$usermetadata);
				
			wp_safe_redirect(home_url()."?dashboard=user&page=account&sucess=4" );
	 		
	    }
	}
	if(isset($_POST['profile_save_change_new']))
	{
        $nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'profile_save_change_nonce_new' ) )
		{		
			$usermetadata=array(
							'address'=>mj_smgt_address_description_validation($_POST['address']),
							'city'=>mj_smgt_city_state_country_validation($_POST['city_name']),
							'state'=>mj_smgt_city_state_country_validation($_POST['state_name']),
							'phone'=>mj_smgt_phone_number_validation($_POST['phone']));
		
			$userdata = array('user_email'=>mj_smgt_email_validation($_POST['email']));
				
			$userdata['ID']=$user->ID;
			
			$result=mj_smgt_update_user_profile($userdata,$usermetadata);
			
			wp_safe_redirect(home_url()."?dashboard=user&page=account&sucess=4" );
	 		
	    }
	}
//SAVE PROFILE PICTURE
if(isset($_POST['save_profile_pic']))
{
	$referrer = $_SERVER['HTTP_REFERER'];
	if($_FILES['profile']['size'] > 0)
	{
		$user_image=mj_smgt_load_documets($_FILES['profile'],'profile','pimg');
		$photo_image_url=content_url().'/uploads/school_assets/'.$user_image;
	}
	
 	$returnans=update_user_meta($user->ID,'smgt_user_avatar',$photo_image_url);
	if($returnans)
	{
		wp_redirect($referrer.'&sucess=5');
	}   
}
?>