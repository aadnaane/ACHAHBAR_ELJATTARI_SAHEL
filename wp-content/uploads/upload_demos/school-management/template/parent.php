<?php 
?>
<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
	
	function deleteParentElement(n){
					n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
				}
				$('#add-another_item').on('click',function(event) {
					event.preventDefault();
					var $this = $(this);
					var $last = $this.prev(); // $this.parents('.something').prev() also useful
					var $clone = $last.clone(true);
					var $inputs = $clone.find('input,textarea,select');
					$last.after($clone);
					$inputs.eq(0).focus();
					
					var numItems = $('.parents_child').length;
					if(numItems > 1)
					{
						 $('#revove_item').show();
					}
					
				});		
				$('#revove_item').on('click',function(event) {
					event.preventDefault();
					var numItems = $('.parents_child').length;
					if(numItems > 1)
					{
						 $(this).prev().prev().remove();
						 if(numItems == 2)
							 $('#revove_item').hide();
					}
					else
					{ $('#revove_item').hide();}
				});	


	$(".view_more_details_div").on("click", ".view_more_details", function(event)
	{
		$('.view_more_details_div').removeClass("d-block");
		$('.view_more_details_div').addClass("d-none");

		$('.view_more_details_less_div').removeClass("d-none");
		$('.view_more_details_less_div').addClass("d-block");

		$('.user_more_details').removeClass("d-none");
		$('.user_more_details').addClass("d-block");

	});		
	$(".view_more_details_less_div").on("click", ".view_more_details_less", function(event)
	{
		$('.view_more_details_div').removeClass("d-none");
		$('.view_more_details_div').addClass("d-block");

		$('.view_more_details_less_div').removeClass("d-block");
		$('.view_more_details_less_div').addClass("d-none");

		$('.user_more_details').removeClass("d-block");
		$('.user_more_details').addClass("d-none");
	});

	var table =  jQuery('#child_list').DataTable({
							responsive: true,
							"order": [[ 0, "asc" ]],
							"aoColumns":[	                  
							{"bSortable": false},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": true},
							{"bSortable": true}],	
							language:<?php echo mj_smgt_datatable_multi_language();?>	
						});

	$('#parent_list').DataTable({
			responsive: true,
			language:<?php echo mj_smgt_datatable_multi_language();?>	
		});


	$('#parent_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
				 $('#birth_date').datepicker({
					 maxDate : 0,
					 dateFormat: "yy-mm-dd",
					changeMonth: true,
					changeYear: true,
					yearRange:'-65:+25',
					beforeShow: function (textbox, instance) 
					{
						instance.dpDiv.css({
							marginTop: (-textbox.offsetHeight) + 'px'                   
						});
					},
					onChangeMonthYear: function(year, month, inst) {
						$(this).val(month + "/" + year);
					}
				}); 
				
				var numItems = $('.parents_child').length;
				if(numItems == 1)
				{$('#revove_item').hide();}
	
});
</script>
<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'parentlist';
//--------------- ACCESS WISE ROLE -----------//
$user_access=mj_smgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		mj_smgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				mj_smgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
//--------------------------  SAVE PARENT ----------------------//
	if(isset($_POST['save_parent']))
	{
		$role='parent';
		$nonce = $_POST['_wpnonce'];
	    if ( wp_verify_nonce( $nonce, 'save_parent_admin_nonce' ) )
		{			
			$firstname=mj_smgt_onlyLetter_specialcharacter_validation($_POST['first_name']);
			$lastname=mj_smgt_onlyLetter_specialcharacter_validation($_POST['last_name']);
			$userdata = array(
				'user_login'=>mj_smgt_username_validation($_POST['username']),			
				'user_nicename'=>NULL,
				'user_email'=>mj_smgt_email_validation($_POST['email']),
				'user_url'=>NULL,
				'display_name'=>$firstname." ".$lastname,
			);
				
			if($_POST['password'] != "")
				$userdata['user_pass']=mj_smgt_password_validation($_POST['password']);
			
			if(isset($_FILES['upload_user_avatar_image']) && !empty($_FILES['upload_user_avatar_image']) && $_FILES['upload_user_avatar_image']['size'] !=0)
		{
			if($_FILES['upload_user_avatar_image']['size'] > 0)
				$member_image=mj_smgt_load_documets($_FILES['upload_user_avatar_image'],'upload_user_avatar_image','pimg');
				$photo=content_url().'/uploads/school_assets/'.$member_image;
		}
		else
		{
			if(isset($_REQUEST['hidden_upload_user_avatar_image']))
			$member_image=$_REQUEST['hidden_upload_user_avatar_image'];
			$photo=$member_image;
		}
			$usermetadata	=	array(
				'middle_name'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['middle_name']),
				'gender'=>mj_smgt_onlyLetterSp_validation($_POST['gender']),
				'birth_date'=>$_POST['birth_date'],
				'address'=>mj_smgt_address_description_validation($_POST['address']),
				'city'=>mj_smgt_city_state_country_validation($_POST['city_name']),
				'state'=>mj_smgt_city_state_country_validation($_POST['state_name']),
				'zip_code'=>mj_smgt_onlyLetterNumber_validation($_POST['zip_code']),
				'phone'=>mj_smgt_phone_number_validation($_POST['phone']),
				'mobile_number'=>mj_smgt_phone_number_validation($_POST['mobile_number']),
				'relation'=>mj_smgt_onlyLetterSp_validation($_POST['relation']),
				'smgt_user_avatar'=>$photo,	
				'created_by'=>get_current_user_id()
			);
		
			if($_REQUEST['action']=='edit')
			{			
				$userdata['ID']=$_REQUEST['parent_id'];			
				$result=mj_smgt_update_user($userdata,$usermetadata,$firstname,$lastname,$role);
				if($result)
				{ 
					wp_redirect ( home_url() . '?dashboard=user&page=parent&tab=parentlist&message=1'); 		
				}
			}
			else
			{
				if( !email_exists($_POST['email']) && !username_exists(mj_smgt_strip_tags_and_stripslashes($_POST['username']))) 
				{
					$result=mj_smgt_add_newuser($userdata,$usermetadata,$firstname,$lastname,$role);
					if($result)
					{ 
						wp_redirect ( home_url() . '?dashboard=user&page=parent&tab=parentlist&message=2'); 		
					} 
				}
				else 
				{ 
					wp_redirect ( home_url() . '?dashboard=user&page=parent&tab=parentlist&message=3'); 		
				}		  
			}
	    }
	}
	$addparent	=	0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'addparent')
	{
		if(isset($_REQUEST['student_id']))
		{			
			$student=get_userdata($_REQUEST['student_id']);
			$addparent=1;
		}
	}
	//------------------------ DELETE PARENT ------------------//
	 if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			$childs=get_user_meta($_REQUEST['parent_id'], 'child', true);
			if(!empty($childs))
			{
				foreach($childs as $childvalue)
				{
					$parents=get_user_meta($childvalue, 'parent_id', true);
					if(!empty($parents))
					{
						if(($key = array_search($_REQUEST['parent_id'], $parents)) !== false) {
							unset($parents[$key]);
							update_user_meta( $childvalue,'parent_id', $parents );
						}
					}
				}
			}
			$result=mj_smgt_delete_usedata($_REQUEST['parent_id']);	
			if($result)
			{ 
				wp_redirect ( home_url() . '?dashboard=user&page=parent&tab=parentlist&message=4'); 		
			}
		}
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Parent Updated Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Parent Inserted Successfully.','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('Username Or Emailid Already Exist.','school-mgt');
			break;	
		case '4':
			$message_string = esc_attr__('Parent Deleted Successfully.','school-mgt');
			break;	
		case '5':
			$message_string = esc_attr__('Parent CSV Successfully Uploaded.','school-mgt');
			break;			
	}
	
	if($message)
	{ ?>
		<div class="alert_msg alert alert-success alert-dismissible " role="alert">
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<?php echo $message_string;?>
		</div>
<?php } ?>
<div class="p-4 panel-body panel-white">
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="nav-item">
			<a href="?dashboard=user&page=parent&tab=parentlist" class="p-2 px-3 nav-link nav-tab2 <?php if($active_tab=='parentlist'){?>active<?php }?>">
				<i class="fa fa-align-justify"></i> <?php esc_attr_e('Parent List', 'school-mgt'); ?></a>
			</a>
		</li>
		<li class="nav-item">
		  <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
			?>
				<a href="?dashboard=user&page=parent&tab=addparent&&action=edit&parent_id=<?php echo $_REQUEST['parent_id'];?>" class="p-2 px-3 nav-link nav-tab2 <?php echo $active_tab == 'addparent' ? 'active' : ''; ?>">
				<i class="fa"></i> <?php esc_attr_e('Edit Parent', 'school-mgt'); ?></a>
			 <?php 
			}
			else
			{
				if($user_access['add']=='1')
				{			
				?>				
					<a href="?dashboard=user&page=parent&tab=addparent&action=insert" class="p-2 px-3 nav-link nav-tab2 <?php echo $active_tab == 'addparent' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Add New Parent', 'school-mgt'); ?></a>
				<?php
				}
			}
			?>	  
		</li>
	 <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_parent')
	   {?>
	  <li class="nav-item">
			<a href="?dashboard=user&page=parent&tab=view_parent&action=view_parent&parent_id=<?php echo $_REQUEST['parent_id'];?>" class="p-2 px-3 nav-link nav-tab2  <?php if($active_tab=='view_parent'){?>active<?php }?>">
				<i class="fa fa-eye"></i> <?php esc_attr_e('View Parent', 'school-mgt'); ?></a>
			</a>
      </li>
	  <?php
	   } ?>
	</ul>
	<div class="tab-content">
     <?php 
		if($active_tab == 'parentlist')		
        { ?>
        	<div class="panel-body">
				<form name="wcwm_report" action="" method="post">
					<div class="table-responsive">
						<table id="parent_list" class="display dataTable" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th width="75px"><?php echo esc_attr_e('Photo', 'school-mgt' ) ;?></th>
									<th><?php echo esc_attr_e( 'Parent Name', 'school-mgt' ) ;?></th>
									<th> <?php echo esc_attr_e( 'Parent Email', 'school-mgt' ) ;?></th>
									<th> <?php echo esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
									
								</tr>
							</thead>
							<tfoot>
								<tr>
								   <th width="75px"><?php echo esc_attr_e('Photo', 'school-mgt' ) ;?></th>
									<th><?php echo esc_attr_e( 'Parent Name', 'school-mgt' ) ;?></th>
									<th> <?php echo esc_attr_e( 'Parent Email', 'school-mgt' ) ;?></th>
									 <th> <?php echo esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
								 </tr>
							</tfoot>
							<tbody>
							 <?php 
							 	$user_id=get_current_user_id();
								//------- PARENT DATA FOR STUDENT ---------//
								if($school_obj->role == 'student')
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{ 
										$parentdata1=$school_obj->parent_list;
										foreach($parentdata1 as $pid)
										{
											$parentdata[]=get_userdata($pid);
										}
									}
									else
									{
										$parentdata=mj_smgt_get_usersdata('parent');
									}
								}
								//------- PARENT DATA FOR TEACHER ---------//
								elseif($school_obj->role == 'teacher')
								{
									$parentdata=mj_smgt_get_usersdata('parent');
								}
								//------- PARENT DATA FOR PARENT ---------//
								elseif($school_obj->role == 'parent')
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{ 
										$parentdata[]=get_userdata($user_id);	
									}
									else
									{
										$parentdata=mj_smgt_get_usersdata('parent');
									}
								}
								//------- PARENT DATA FOR SUPPORT STAFF ---------//
								else
								{ 
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{ 
										$parentdata= get_users(
																 array(
																		'role' => 'parent',
																		'meta_query' => array(
																		array(
																				'key' => 'created_by',
																				'value' => $user_id,
																				'compare' => '='
																			)
																		)
																));	
									}
									else
									{
										$parentdata=mj_smgt_get_usersdata('parent');
									}
								}
								if($parentdata)
								{
									foreach ($parentdata as $retrieved_data)
									{ ?>	
									<tr>
										<td class="user_image "><?php $uid=$retrieved_data->ID;
											$umetadata=mj_smgt_get_user_image($uid);
											if(empty($umetadata))
											{
												echo '<img src='.get_option( 'smgt_parent_thumb' ).' height="50px" width="50px" class="img-circle rounded-circle" />';
											}
											else
											{
												echo '<img src='.$umetadata.' height="50px" width="50px" class="img-circle rounded-circle"/>';
											}
										?>
										</td>
										<td class="name"><a href="#"><?php echo $retrieved_data->display_name;?></a></td>
										<td class="email"><?php echo $retrieved_data->user_email;?></td>
										<td class="action">
											<a href="?dashboard=user&page=parent&tab=view_parent&action=view_parent&parent_id=<?php echo $retrieved_data->ID;?>" class="btn btn-success"><?php esc_attr_e('View','school-mgt');?></a>	
											<?php
											if($user_access['edit']=='1')
											{
											?>
											<a href="?dashboard=user&page=parent&tab=addparent&action=edit&parent_id=<?php echo $retrieved_data->ID;?>" class="btn btn-info"> <?php echo esc_attr_e( ' Edit', 'school-mgt' ) ;?></a>
											<?php
											}
											if($user_access['delete']=='1')
											{ ?>
											<a href="?dashboard=user&page=parent&tab=parentlist&action=delete&parent_id=<?php echo $retrieved_data->ID;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><?php echo esc_attr_e( ' Delete', 'school-mgt' ) ;?> </a>
											<?php
											}
											?>
										</td>
								</tr>
									<?php 
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</form>
			</div>
	 <?php
		}
		if($active_tab == 'addparent')
		{
			$students = mj_smgt_get_student_groupby_class();
			$role='parent';
			?>
		<?php 
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' ) {
				$edit=1;	
				$user_info = get_userdata($_REQUEST['parent_id']);
			} ?>       
			<div class="panel-body">
			<form name="parent_form" action="" method="post" class="mt-3 form-horizontal" id="parent_form" enctype="multipart/form-data">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="role" value="<?php echo $role;?>"  />
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="first_name"><?php esc_attr_e('First Name','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $user_info->first_name;}elseif(isset($_POST['first_name'])) echo $_POST['first_name'];?>" name="first_name">
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="middle_name"><?php esc_attr_e('Middle Name','school-mgt');?></label>
				<div class="col-sm-8">
					<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->middle_name;}elseif(isset($_POST['middle_name'])) echo $_POST['middle_name'];?>" name="middle_name">
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="last_name"><?php esc_attr_e('Last Name','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo $user_info->last_name;}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="gender"><?php esc_attr_e('Gender','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
				<?php $genderval = "male"; if($edit){ $genderval=$user_info->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
					<label class="radio-inline">
					 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_attr_e('Male','school-mgt');?> 
					</label>
					<label class="radio-inline">
					  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_attr_e('Female','school-mgt');?> 
					</label>
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="birth_date"><?php esc_attr_e('Date of birth','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="birth_date" class="form-control validate[required]" type="text"  name="birth_date" 
					value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($user_info->birth_date);}elseif(isset($_POST['birth_date'])) echo mj_smgt_getdate_in_input_box($_POST['birth_date']);?>" readonly>
				</div>
			</div>	
			  <?php 
					if($edit)
					{
					   $parent_data = get_user_meta($user_info->ID, 'child', true);
					   if(!empty($parent_data)) 	
						{
							foreach($parent_data as $id1)
							{ ?>
								<div class="mb-3 form-group row parents_child">
									<label class="col-sm-2 control-label col-form-lable text-md-end" for="student_list"><?php esc_attr_e('Child','school-mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
										<select name="chield_list[]" id="student_list" class="form-control validate[required]">
										<option value=""><?php esc_attr_e('Select Child','school-mgt');?></option>
										<?php
										foreach ($students as $label => $opt){ ?>
											<optgroup label="<?php echo "Class : ".$label; ?>">
												<?php foreach ($opt as $id => $name): ?>
												<option value="<?php echo $id; ?>" <?php selected($id, $id1);  ?> ><?php echo $name; ?></option>
												<?php endforeach; ?>
											</optgroup>
											<?php } ?>
										</select>
									</div>
								</div>
					<?php 
							}
						}
						else
						{ ?>
							<div class="mb-3 form-group row parents_child">
								<label class="col-sm-2 control-label col-form-lable text-md-end" for="student_list"><?php esc_attr_e('Child','school-mgt');?><span class="require-field">*</span></label>
								<div class="col-sm-8">                  
									<select name="chield_list[]" id="student_list" class="form-control validate[required]">
									<option value=""><?php esc_attr_e('Select Child','school-mgt');?></option>
									 <?php 
										foreach ($students as $label => $opt)
										{ ?>
											
											<optgroup label="<?php echo "Class : ".$label; ?>">
											<?php foreach ($opt as $id => $name): ?>
												<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
											<?php endforeach; ?>
											</optgroup>
									<?php }  ?>
								   </select>
								</div>
							</div>
							
						<?php }
					}
				else
				{ 	?>
			<div class="mb-3 form-group row parents_child">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="student_list"><?php esc_attr_e('Child','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">                  
					<select name="chield_list[]" id="student_list" class="form-control validate[required]">
					<option value=""><?php esc_attr_e('Select Child','school-mgt');?></option>
					 <?php 
						foreach ($students as $label => $opt)
						{ ?>
							
							<optgroup label="<?php echo "Class : ".$label; ?>">
							<?php foreach ($opt as $id => $name): ?>
								<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
							<?php endforeach; ?>
							</optgroup>
					<?php }  ?>
				   </select>
				</div>
			</div>
			<?php } ?>		
			 <a href="" id="add-another_item"><?php esc_attr_e('Add Other Child','school-mgt');?> </a>
			 <a href="#" id="revove_item"> <?php esc_attr_e('Remove','school-mgt');?> </a>
			 <div class="marginbottom"></div>
				 
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="relation"><?php esc_attr_e('Relation','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<?php if($edit){ $relationval=$user_info->relation; }elseif(isset($_POST['relation'])){$relationval=$_POST['relation'];}else{$relationval='';}?>
						 <select name="relation" class="form-control validate[required]" id="relation">
							<option value=""><?php esc_attr_e('select relation','school-mgt');?></option>
							<option value="<?php esc_attr_e('Father','school-mgt');?>" <?php selected( $relationval, 'Father'); ?>><?php esc_attr_e('Father','school-mgt');?></option>
							<option value="<?php esc_attr_e('Mother','school-mgt');?>" <?php selected( $relationval, 'Mother'); ?>><?php esc_attr_e('Mother','school-mgt');?></option>
						 </select>
				</div>	
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="address"><?php esc_attr_e('Address','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="address" class="form-control validate[required,custom[address_description_validation]]" maxlength="150" type="text"  name="address" 
					value="<?php if($edit){ echo $user_info->address;}elseif(isset($_POST['address'])) echo $_POST['address'];?>">
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="city_name"><?php esc_attr_e('City','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
					value="<?php if($edit){ echo $user_info->city;}elseif(isset($_POST['city_name'])) echo $_POST['city_name'];?>">
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="state_name"><?php esc_attr_e('State','school-mgt');?></label>
				<div class="col-sm-8">
					<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="state_name" 
					value="<?php if($edit){ echo $user_info->state;}elseif(isset($_POST['state_name'])) echo $_POST['state_name'];?>">
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="zip_code"><?php esc_attr_e('Zip Code','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="zip_code" class="form-control  validate[required,custom[onlyLetterNumber]]" maxlength="15" type="text"  name="zip_code" 
					value="<?php if($edit){ echo $user_info->zip_code;}elseif(isset($_POST['zip_code'])) echo $_POST['zip_code'];?>">
				</div>
			</div>
			
			<?php wp_nonce_field( 'save_parent_admin_nonce' ); ?>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="mobile_number"><?php esc_attr_e('Mobile Number','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-1">
				
				<input type="text" readonly value="+<?php echo mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));?>"  class="form-control country_code phonecode" name="phonecode">
				</div>
				<div class="col-sm-7">
					<input id="mobile_number" class="form-control btn_top validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="mobile_number" maxlength="10"
					value="<?php if($edit){ echo $user_info->mobile_number;}elseif(isset($_POST['mobile_number'])) echo $_POST['mobile_number'];?>">
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end " for="phone"><?php esc_attr_e('Phone','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="phone" class="form-control validate[required,custom[phone_number],minSize[6],maxSize[15]] text-input" type="text"  name="phone" 
					value="<?php if($edit){ echo $user_info->phone;}elseif(isset($_POST['phone'])) echo $_POST['phone'];?>">
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end " for="email"><?php esc_attr_e('Email','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="100" type="text"  name="email" 
					value="<?php if($edit){ echo $user_info->user_email;}elseif(isset($_POST['email'])) echo $_POST['email'];?>">
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="username"><?php esc_attr_e('User Name','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="username" class="form-control validate[required,custom[username_validation]]" maxlength="50" type="text"  name="username" 
					value="<?php if($edit){ echo $user_info->user_login;}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?>>
				</div>
			</div>
			<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="password"><?php esc_attr_e('Password','school-mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
				<div class="col-sm-8">
					<input id="password" class="form-control <?php if(!$edit){ echo 'validate[required,minSize[8],maxSize[12]]'; }else{ echo 'validate[minSize[8],maxSize[12]]'; } ?>" type="password"  name="password" value="">
				</div>
			</div>
			
		<div class="mb-3 form-group row">
				<label class="col-sm-2 control-label col-form-lable text-md-end" for="photo"><?php esc_attr_e('Image','school-mgt');?></label>
				<div class="col-sm-2">
					<input type="text" id="amgt_user_avatar_url" class="form-control" name="smgt_user_avatar"  
					value="<?php if($edit)echo esc_url( $user_info->smgt_user_avatar );elseif(isset($_POST['smgt_user_avatar'])) echo $_POST['smgt_user_avatar']; ?>" />
					<input type="hidden" class="form-control" name="hidden_upload_user_avatar_image"  
					value="<?php if($edit)echo esc_url( $user_info->smgt_user_avatar );elseif(isset($_POST['hidden_upload_user_avatar_image'])) echo $_POST['hidden_upload_user_avatar_image']; ?>" />
				</div>	
					<div class="col-sm-3">
						 <input id="upload_user_avatar" class="btn_top" name="upload_user_avatar_image" onchange="fileCheck(this);" type="file" />
				</div>
				<div class="clearfix"></div>
				
				<div class="offset-sm-2 col-sm-8">
					<div id="upload_user_avatar_preview" >
						<?php if($edit) 
						{
							if($user_info->smgt_user_avatar == "")
							{ ?>
								<img class="image_preview_css" src="<?php echo get_option( 'smgt_student_thumb' ); ?>">
						<?php }
						else { ?>
							<img class="image_preview_css" src="<?php if($edit)echo esc_url( $user_info->smgt_user_avatar ); ?>" />
						<?php }
						}
					else { 	?>
							<img class="image_preview_css" src="<?php echo get_option( 'smgt_student_thumb' ); ?>">
				 <?php } ?>
					</div>
				</div>
			</div>
			<div class="offset-sm-2 col-sm-8">        	
				<input type="submit" value="<?php if($edit){ esc_attr_e('Save Parent','school-mgt'); }else{ esc_attr_e('Add Parent','school-mgt');}?>" name="save_parent" class="btn btn-success"/>
			</div>      
			</form>
			</div>
		<?php
		}
		if($active_tab == 'view_parent')
		{
			?>
			
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
								echo '<img class="img-circle img-responsive member-profile user_height_width" src='.get_option( 'smgt_student_thumb' ).'>';
							}
							else
								echo '<img class="img-circle img-responsive member-profile user_height_width" src='.$umetadata['meta_value'].'>';
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
						<div class="view-more view_more_details_div d-block">
							<h4><?php esc_attr_e( 'View More', 'school-mgt' ) ;?></h4>
								<i class="fa fa-angle-down fa-2x bounce view_more_details"></i>
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
											<p class="user-info">: <?php echo $parent_data->display_name;?></p>
										</div>
										
									<!--</div>
									<div class="row">-->							
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
										
										<p class="user-info">: <?php 
											if($parent_data->gender=='male')
											{
												echo esc_attr__('Male','school-mgt');
											}
											elseif($parent_data->gender=='female');
											{
												echo esc_attr__('Female','school-mgt');
											}
										?>
										</p>
										</div>
																	
										 <div class="col-md-2">
												<p class="user-lable"><?php esc_attr_e( 'Relation', 'school-mgt' ) ;?></p>
											</div>
										<div class="col-md-4">
												<p class="user-info">: <?php echo esc_html__("$parent_data->relation","school-mgt");?></p>
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
						<li class="nav-item active"><a data-toggle="tab" href="#Section1" class="p-2 px-3 nav-link active"><i class="fa fa-user"></i><b><?php esc_attr_e( ' Child', 'school-mgt' ); ?></b></a></li>
					</ul>
					<div class="tab-content">
						<div id="Section1" class="tab-pane active">
							<div class="row">
								<div class="col-lg-12">
									<div class="card">
										<div class="card-content">
											 <div class="table-responsive">
												  <table id="child_list" class="table display" cellspacing="0" width="100%">
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
															echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle" />';
														}
														else
															echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle"/>';?></td>
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
		 
<?php
		}
?>
	</div>
</div>