<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'hostel_list';
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
$obj_hostel=new smgt_hostel;
$tablename='smgt_hostel'; 
//----------insert and update--------------------
	if(isset($_POST['save_hostel']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_hostel_admin_nonce' ) )
		{
			if($_REQUEST['action']=='edit')
			{
				$result=$obj_hostel->mj_smgt_insert_hostel($_POST);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=hostel_list&message=2'); 			
				}
			}
			else
			{
				$result=$obj_hostel->mj_smgt_insert_hostel($_POST);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=hostel_list&message=1'); 	
				}
			}
	    }
	}
//---------delete record--------------------
	 
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=$obj_hostel->mj_smgt_delete_hostel($_REQUEST['hostel_id']);
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=hostel_list&message=3'); 	
		}
	}
//----------insert and update ROOM--------------------
	if(isset($_POST['save_room']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_room_admin_nonce' ) )
		{
			if($_REQUEST['action']=='edit_room')
			{
				$result=$obj_hostel->mj_smgt_insert_room($_POST);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=room_list&message=5'); 	
				}
			}
			else
			{
				$result=$obj_hostel->mj_smgt_insert_room($_POST);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=room_list&message=4'); 	
				}
			}
	    }
	}
//--------- delete record ROOM --------------------
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_room')
	{
		$result=$obj_hostel->mj_smgt_delete_room($_REQUEST['room_id']);
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=room_list&message=6'); 	
		}
	}
	//----------insert and update Beds--------------------
	if(isset($_POST['save_bed']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_bed_admin_nonce' ) )
		{
		
			if($_REQUEST['action']=='edit_bed')
			{
				$bed_id=$_REQUEST['bed_id'];
				$room_id=$_REQUEST['room_id'];
				 
				global $wpdb;
				$table_smgt_beds=$wpdb->prefix.'smgt_beds';
				$result_bed =$wpdb->get_results("SELECT * FROM $table_smgt_beds WHERE room_id=$room_id and id !=".$bed_id);
				$bed=count($result_bed);
				$bed_capacity=mj_smgt_get_bed_capacity_by_id($room_id);
				if($bed < $bed_capacity)
				{
					$result=$obj_hostel->mj_smgt_insert_bed($_POST);
					if($result)
					{
						wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=bed_list&message=8'); 	
					}
				}
				else
				{
					wp_redirect ( home_url().'?dashboard=user&page=hostel&tab=add_bed&action=edit_bed&bed_id='.$bed_id.'&message=10');
					die;
				}
			}
			else
			{
				$assign_bed=mj_smgt_hostel_room_bed_count($_POST['room_id']);
				$bed_capacity=mj_smgt_get_bed_capacity_by_id($_POST['room_id']);
				$bed_capacity_int=(int)$bed_capacity;
				 
				if($assign_bed >= $bed_capacity_int)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=bed_list&message=10'); 	
					die;
				}
				else
				{
					$result=$obj_hostel->mj_smgt_insert_bed($_POST);
					if($result)
					{
						wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=bed_list&message=7'); 	
					}
				}
			}
	    }
	}
	//--------- delete record BED --------------------
	 
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_bed')
	{
		$result=$obj_hostel->mj_smgt_delete_bed($_REQUEST['bed_id']);
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=bed_list&message=9'); 	
		}
	}
	//---------- Assign Beds -------------------
	if(isset($_POST['assign_room']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_assign_room_admin_nonce' ) )
		{
			$result=$obj_hostel->mj_smgt_assign_room($_POST);
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=room_list&message=11'); 	
			}
		} 
	}	
	//--------- delete Assign BED --------------------
	 
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_assign_bed')
	{
		$result=$obj_hostel->mj_smgt_delete_assigned_bed($_REQUEST['room_id'],$_REQUEST['bed_id'],$_REQUEST['student_id']);
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=hostel&tab=room_list&message=12'); 	
		}
	}
?>
<!-- Nav tabs -->
<div class="p-4 panel-body panel-white">
 <?php
 $message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Hostel Added Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Hostel Updated Successfully.','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('Hostel Deleted Successfully.','school-mgt');
			break;
		case '4':
			$message_string = esc_attr__('Room Added Successfully.','school-mgt');
			break;
		case '5':
			$message_string = esc_attr__('Room Updated Successfully.','school-mgt');
			break;	
		case '6':
			$message_string = esc_attr__('Room Deleted Successfully.','school-mgt');
			break;
		case '7':
			$message_string = esc_attr__('Bed Added Successfully.','school-mgt');
			break;
		case '8':
			$message_string = esc_attr__('Bed Updated Successfully.','school-mgt');
			break;	
		case '9':
			$message_string = esc_attr__('Bed Deleted Successfully.','school-mgt');
			break;
		case '10':
			$message_string = esc_attr__('This room has no extra bed capacity','school-mgt');
			break;
		case '11':
			$message_string = esc_attr__('Room Assign Successfully','school-mgt');
			break;
		case '12':
			$message_string = esc_attr__('Assigned Bed Deleted Successfully.','school-mgt');
			break;
	}
	
	if($message)
	{ ?>
		<div class="alert_msg alert alert-success alert-dismissible " role="alert">
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<?php echo $message_string;?>
		</div>
<?php 
	} ?> 
		<script type="text/javascript" >
		jQuery(document).ready(function($)
		{
	             "use strict";	
	              $('#hostel_list_frontend').DataTable({
					responsive: true,
					"order": [[ 1, "asc" ]],
					"aoColumns":[          
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": false}
					],
					language:<?php echo mj_smgt_datatable_multi_language();?>
				});

				 $('#room_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

              $('#bed_list').DataTable({
					"order": [[ 1, "asc" ]],
					"aoColumns":[	                  
								   {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": false}								  
					],
					language:<?php echo mj_smgt_datatable_multi_language();?>
				});

 				 $('#bed_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});


 				 $('.datepicker').datepicker({
						defaultDate: null,
						changeMonth: true,
						changeYear: true,
						yearRange:'-75:+10',
						dateFormat: 'yy-mm-dd'
					 });
					
					function checkselectvalue(value,i) {
					
						$('#assigndate_'+i).hide();
						$('.students_list_'+i).removeClass('student_check');
						$(".student_check").each(function()
						{
							var valueSelected1=$(this).val();
							if(valueSelected1 == value)
							{
								alert(language_translate2.select_different_student_alert);
								$('.students_list_'+i).val('0');	
								return false;	
							}
						});
						var value=$('.students_list_'+i).val();
						if(value =='0' )
						{
							$('#assigndate_'+i).hide();
							var name=0;
							$(".new_class").each(function()
							{
								var new_class=$(this).val();
								if(new_class != '0')
								{
									name=name+1;
								}
							});
							if(name < 1)
							{
								$("#Assign_bed").prop("disabled", true);
							}
						}
						else
						{
							$('#assigndate_'+i).show();
							$("#Assign_bed").prop("disabled", false);
						} 
						$('.students_list_'+i).addClass('student_check');
					}	

			$('#hostel_form_fornt').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

			 $('#room_list_fornt').DataTable({
					"order": [[ 1, "asc" ]],
					"aoColumns":[	                  
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": true},
								  {"bSortable": false}
					],
					language:<?php echo mj_smgt_datatable_multi_language();?>
				});

				$('body').on('change','.student_check',function(){
					// alert(this);
					let index = $(this).attr('data-index');
		
					
					if($('#students_list_'+index).val() != 0)
					{
						$('#assign_date_'+index).addClass('validate[required]');
					}else{
						$('#assign_date_'+index).removeClass('validate[required]');
		
					}
		
			});

	
});
</script>
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="nav-item">
			<a href="?dashboard=user&page=hostel&tab=hostel_list" class="p-2 px-3 nav-link nav-tab2 <?php echo $active_tab == 'hostel_list' ? 'active' : ''; ?>"> <strong>
			<i class="fa fa-align-justify"> </i> <?php esc_attr_e('Hostel List', 'school-mgt'); ?></strong></a>
		</li>
		<li class="nav-item">
		  <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
			?>
				<a href="?dashboard=user&page=hostel&tab=add_hostel&&action=edit&hostel_id=<?php echo $_REQUEST['hostel_id'];?>" class="p-2 px-3 nav-link nav-tab2 tab <?php echo $active_tab == 'add_hostel' ? 'active' : ''; ?>">
				<i class="fa"></i> <?php esc_attr_e('Edit Hostel', 'school-mgt'); ?></a>
			 <?php 
			}
			else
			{
				if($user_access['add']=='1')
				{			
				?>				
					<a href="?dashboard=user&page=hostel&tab=add_hostel&action=insert" class="p-2 px-3 nav-link nav-tab2 tab <?php echo $active_tab == 'add_hostel' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Add Hostel', 'school-mgt'); ?></a>
				<?php
				}
			}
			?>	  
		</li>
		<li class="nav-item">
			<a href="?dashboard=user&page=hostel&tab=room_list" class="p-2 px-3 nav-link nav-tab2 <?php echo $active_tab == 'room_list' ? 'active' : ''; ?>"> <strong>
			<i class="fa fa-align-justify"> </i> <?php esc_attr_e('Room List', 'school-mgt'); ?></strong></a>
		</li>
		<li class="nav-item">
		<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_assign_room')
			{
			?>
				<a href="?dashboard=user&page=hostel&tab=assign_room&&action=view_assign_room&room_id=<?php echo $_REQUEST['room_id'];?>" class="p-2 px-3 nav-link nav-tab2 tab <?php echo $active_tab == 'assign_room' ? 'active' : ''; ?>">
				<i class="fa"></i> <?php esc_attr_e('Assign Room', 'school-mgt'); ?></a>
			 <?php 
			} ?>
		</li>
		<li class="nav-item">
		  <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_room')
			{
			?>
				<a href="?dashboard=user&page=hostel&tab=add_room&&action=edit_room&room_id=<?php echo $_REQUEST['room_id'];?>" class="p-2 px-3 nav-link nav-tab2 tab <?php echo $active_tab == 'add_room' ? 'active' : ''; ?>">
				<i class="fa"></i> <?php esc_attr_e('Edit Room', 'school-mgt'); ?></a>
			 <?php 
			}
			else
			{
				if($user_access['add']=='1')
				{			
				?>				
					<a href="?dashboard=user&page=hostel&tab=add_room&action=insert" class="p-2 px-3 nav-link nav-tab2 tab <?php echo $active_tab == 'add_room' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Add Room', 'school-mgt'); ?></a>
				<?php
				}
			}
			?>	  
		</li>
		<li class="nav-item">
			<a href="?dashboard=user&page=hostel&tab=bed_list" class="p-2 px-3 nav-link nav-tab2  <?php echo $active_tab == 'bed_list' ? 'active' : ''; ?>"> <strong>
			<i class="fa fa-align-justify"> </i> <?php esc_attr_e('Beds List', 'school-mgt'); ?></strong></a>
		</li>
		<li class="nav-item">
		  <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_bed')
			{
			?>
				<a href="?dashboard=user&page=hostel&tab=add_bed&&action=edit_bed&room_id=<?php echo $_REQUEST['room_id'];?>" class="p-2 px-3 nav-link nav-tab2 tab <?php echo $active_tab == 'add_bed' ? 'active' : ''; ?>">
				<i class="fa"></i> <?php esc_attr_e('Edit Beds', 'school-mgt'); ?></a>
			 <?php 
			}
			else
			{
				if($user_access['add']=='1')
				{			
				?>				
					<a href="?dashboard=user&page=hostel&tab=add_bed&action=insert" class="p-2 px-3 nav-link nav-tab2 tab <?php echo $active_tab == 'add_bed' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Add Beds', 'school-mgt'); ?></a>
				<?php
				}
			}
			?>	  
		</li>
	</ul>	

    <!-- Tab panes -->
	<?php
	if($active_tab == 'hostel_list')
	{
		$tablename='smgt_hostel';
		$retrieve_class = mj_smgt_get_all_data($tablename);
		?>
		
		<div class="panel-body">
			<div class="table-responsive">
				<table id="hostel_list_frontend" class="display dataTable" width="100%" cellspacing="0" width="100%">
					 <thead>
						<tr>                
							<th><?php esc_attr_e('Hostel Name','school-mgt');?></th>
							<th><?php esc_attr_e('Hostel Type','school-mgt');?></th>
							<th><?php esc_attr_e('Description','school-mgt');?></th>
						 <?php
							if($user_access['edit']=='1' || $user_access['delete']=='1')
							{
							?>
							<th><?php esc_attr_e('Action','school-mgt');?></th>
							 <?php
								}
								?>
						</tr>
					</thead>
		 
					<tfoot>
						<tr>
							<th><?php esc_attr_e('Hostel Name','school-mgt');?></th>
							<th><?php esc_attr_e('Hostel Type','school-mgt');?></th>
							<th><?php esc_attr_e('Description','school-mgt');?></th>
							 <?php
								if($user_access['edit']=='1' || $user_access['delete']=='1')
								{
								?>
							<th><?php esc_attr_e('Action','school-mgt');?></th>
							 <?php
								}
								?>
						</tr>
					</tfoot>
		 
					<tbody>
					<?php 	
					foreach ($retrieve_class as $retrieved_data)
					{ 		
					 ?>
						<tr>
							<td><?php echo $retrieved_data->hostel_name;?></td>
							<td><?php echo $retrieved_data->hostel_type;?></td>
							<td><?php echo $retrieved_data->Description;?></td>
						<?php
							if($user_access['edit']=='1' || $user_access['delete']=='1')
							{
								?>
								<td>
								<?php
								if($user_access['edit']=='1')
								{
								?>
									<a href="?dashboard=user&page=hostel&tab=add_hostel&action=edit&hostel_id=<?php echo $retrieved_data->id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
									<?php
								}
								if($user_access['delete']=='1')
								{
								?>
									<a href="?dashboard=user&page=hostel&tab=hostel_list&action=delete&hostel_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><?php esc_attr_e('Delete','school-mgt');?>
									</a>
									<?php
								}
								?>
								</td>
							<?php
							}
								?>
						</tr>
						<?php
					} ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php
	}
	if($active_tab == 'add_hostel')
	{
		$obj_hostel=new smgt_hostel;
		?>
		<?php 
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$hostel_data=$obj_hostel->mj_smgt_get_hostel_by_id($_REQUEST['hostel_id']);
			}
			?>
		<div class="panel-body">
			<form name="hostel_form" action="" method="post" class="mt-3 form-horizontal" id="hostel_form_fornt">
			  <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="hostel_id" value="<?php if($edit){ echo $hostel_data->id;}?>"/> 
				 <div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="hostel_name"><?php esc_attr_e('Hostel Name','school-mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="hostel_name" class="form-control col-form-label validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hostel_data->hostel_name;}?>" name="hostel_name">
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="hostel_type"><?php esc_attr_e('Hostel Type','school-mgt');?></label>
					<div class="col-sm-8">
						<input id="hostel_type" class="form-control col-form-label  validate[custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $hostel_data->hostel_type;}?>" name="hostel_type">
					</div>
				</div>
				<?php wp_nonce_field( 'save_hostel_admin_nonce' ); ?>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="Description"><?php esc_attr_e('Description','school-mgt');?></label>
					<div class="col-sm-8">
						<textarea name="Description" id="Description" maxlength="150" class="form-control col-form-label  validate[custom[address_description_validation]]"><?php if($edit){ echo $hostel_data->Description;}?></textarea>
					</div>
				</div>
				<div class="offset-sm-2 col-sm-8">        	
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Hostel','school-mgt'); }else{ esc_attr_e('Add Hostel','school-mgt');}?>" name="save_hostel" class="btn btn-success" />
				</div>
			</form>
        </div>
	<?php
	}
    if($active_tab == 'room_list')
	{
		$tablename='smgt_room';
		$retrieve_class = mj_smgt_get_all_data($tablename);
		?>
		<div class="panel-body">
			<div class="table-responsive">
				<table id="room_list_fornt" class="display dataTable exam_datatable" cellspacing="0" width="100%">
					 <thead>
						<tr>                
							<th><?php esc_attr_e('Room Unique ID','school-mgt');?></th>
							<th><?php esc_attr_e('Hostel Name','school-mgt');?></th>
							<th><?php esc_attr_e('Room Category','school-mgt');?></th>
							<th><?php esc_attr_e('Bed Capacity','school-mgt');?></th>
							<th><?php esc_attr_e('Availability','school-mgt');?></th>
							<th><?php esc_attr_e('Description','school-mgt');?></th>
							<?php
								if($user_access['edit']=='1' || $user_access['delete']=='1')
								{
									?>
									<th><?php esc_attr_e('Action','school-mgt');?></th>
									<?php
								}
							?>
						</tr>
					</thead>
		 
					<tfoot>
						<tr>
							<th><?php esc_attr_e('Room Unique ID','school-mgt');?></th>
							<th><?php esc_attr_e('Hostel Name','school-mgt');?></th>
							<th><?php esc_attr_e('Room Category','school-mgt');?></th>
							<th><?php esc_attr_e('Bed Capacity','school-mgt');?></th>
							<th><?php esc_attr_e('Availability','school-mgt');?></th>
							<th><?php esc_attr_e('Description','school-mgt');?></th>
						<?php
							if($user_access['edit']=='1' || $user_access['delete']=='1')
							{
							?>
							<th><?php esc_attr_e('Action','school-mgt');?></th>
							<?php
							}
							?>
						</tr>
					</tfoot>
		 
					<tbody>
					<?php 	
					foreach ($retrieve_class as $retrieved_data)
					{ 		
					 ?>
						<tr>
							<td><?php echo $retrieved_data->room_unique_id;?></td>
							<td><?php echo mj_smgt_get_hostel_name_by_id($retrieved_data->hostel_id);?></td>
							<td><?php echo get_the_title($retrieved_data->room_category);?></td>
							<td><?php echo $retrieved_data->beds_capacity;?></td>
							<?php 
								$room_cnt =mj_smgt_hostel_room_status_check($retrieved_data->id);
								
								$bed_capacity=(int)$retrieved_data->beds_capacity;
								
								if($room_cnt >= $bed_capacity)
								{
								?> 
									<td><label class="occ-red"><?php esc_attr_e('Occupied','school-mgt');?></label></td>
								<?php
								}
								else 
								{?>
									<td><label class="occ-green"><?php esc_attr_e('Available','school-mgt');?></label></td>
								<?php 
								}
								?>
							<td><?php echo $retrieved_data->room_description;?></td>
							 <?php
							if($user_access['edit']=='1' || $user_access['delete']=='1')
							{
							?>
								<td>
								<?php
								if($user_access['edit']=='1')
								{
								?>
									<a href="?dashboard=user&page=hostel&tab=add_room&action=edit_room&room_id=<?php echo $retrieved_data->id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
								<?php
								}
								if($user_access['delete']=='1')
								{
								?>
									<a href="?dashboard=user&page=hostel&tab=room_list&action=delete_room&room_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" 
									onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><?php esc_attr_e('Delete','school-mgt');?></a>
								<?php
								}
								if($user_access['add']=='1')
								{ ?>
									<a href="?dashboard=user&page=hostel&tab=assign_room&action=view_assign_room&room_id=<?php echo $retrieved_data->id;?>" class="btn btn-primary"><?php esc_attr_e('View or Assign Room','school-mgt');?></a>
									<?php
								}?>
								</td>
							<?php
							}?>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}
	if($active_tab == 'add_room')
	{
		$obj_hostel=new smgt_hostel;
	?>
	<!--Group POP up code -->
	<div class="popup-bg">
		<div class="overlay-content admission_popup">
			<div class="modal-content">
				<div class="category_list">
				</div>     
			</div>
		</div>     
	</div>
	<?php 
	$edit=0;
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_room')
		{
			$edit=1;
			$room_data=$obj_hostel->mj_smgt_get_room_by_id($_REQUEST['room_id']);
		}
		?>
		<div class="panel-body">
			<form name="room_form" action="" method="post" class="mt-3 form-horizontal" id="room_form">
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="room_id" value="<?php if($edit){ echo $room_data->id;}?>"/> 
				 <div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="room_unique_id"><?php esc_attr_e('Room Unique ID','school-mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="room_unique_id" class="form-control col-form-label  validate[required] text-input" type="text" value="<?php if($edit){ echo $room_data->room_unique_id; } else { echo mj_smgt_generate_room_code(); } ?>"  name="room_unique_id" readonly>		
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="hostel_type"><?php esc_attr_e('Select Hostel','school-mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select name="hostel_id" class="form-control col-form-label  validate[required] width_100" id="hostel_id">
							<option value=""><?php echo esc_attr_e( 'Select Hostel', 'school-mgt' ) ;?></option>
							<?php $hostelval='';
							$hostel_data=$obj_hostel->mj_smgt_get_all_hostel();
							if($edit){  
								$hostelval=$room_data->hostel_id; 
								foreach($hostel_data as $hostel)
								{ ?>
								<option value="<?php echo $hostel->id;?>" <?php selected($hostel->id,$hostelval);  ?>>
								<?php echo $hostel->hostel_name;?></option> 
							<?php }
							}else
							{
								foreach($hostel_data as $hostel)
								{ ?>
								<option value="<?php echo $hostel->id;?>" <?php selected($hostel->id,$hostelval);  ?>><?php echo $hostel->hostel_name;?></option> 
							<?php }
							}
							?>
						</select>
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="hostel_type"><?php esc_attr_e('Room Category','school-mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control col-form-label  validate[required] room_category margin_top_10 width_100" name="room_category" id="room_category">
							<option value=""><?php esc_html_e('Select Standard','school-mgt');?></option>
							<?php 
							$activity_category=mj_smgt_get_all_category('room_category');
							if(!empty($activity_category))
							{
								if($edit)
								{
									$room_val=$room_data->room_category; 
								}
								else
								{
									$room_val=''; 
								}
								foreach ($activity_category as $retrive_data)
								{ 		 	
								?>
									<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$room_val);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>
								<?php }
							} 
							?> 
						</select>	
					</div>
					<div class="col-md-1 col-sm-1 col-xs-12">
						<button id="addremove_cat" class="btn btn-info sibling_add_remove margin_top_10" model="room_category"><?php esc_attr_e('Add','school-mgt');?></button>		
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="Bed Capacity"><?php esc_attr_e('Beds Capacity','school-mgt');?> <span class="require-field">*</span></label> 
					<div class="col-sm-8">
						<input id="beds_capacity" class="form-control col-form-label  validate[required,custom[onlyNumberSp],maxSize[2],min[1]] text-input" placeholder="<?php esc_html_e('Enter Beds Capacity','school-mgt');?>"  type="text" value="<?php if($edit){ echo $room_data->beds_capacity; } ?>"  name="beds_capacity">
					</div>
				</div>
				<?php wp_nonce_field( 'save_room_admin_nonce' ); ?>
				 
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="room_description"><?php esc_attr_e('Description','school-mgt');?></label>
					<div class="col-sm-8">
						<textarea name="room_description" id="room_description" maxlength="150" class="form-control col-form-label  validate[custom[address_description_validation]]"><?php if($edit){ echo $room_data->room_description;}?></textarea>		
					</div>
				</div>
				<div class="offset-sm-2 col-sm-8">        	
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Room','school-mgt'); }else{ esc_attr_e('Add Room','school-mgt');}?>" name="save_room" class="btn btn-success" />
				</div>
			</form>
		</div>
	<?php
	}
    if($active_tab == 'bed_list')
	{
		$tablename='smgt_beds';
		$retrieve_class = mj_smgt_get_all_data($tablename);
		?>
		<div class="panel-body">
			<div class="table-responsive">
				<table id="bed_list" class="display dataTable exam_datatable" cellspacing="0" width="100%">
					 <thead>
						<tr>                
							<th><?php esc_attr_e('Bed Unique ID','school-mgt');?></th>
							<th><?php esc_attr_e('Room Unique ID','school-mgt');?></th>
							<th><?php esc_attr_e('Availability','school-mgt');?></th>
							<th><?php esc_attr_e('Description','school-mgt');?></th>
							 <?php
								if($user_access['edit']=='1' || $user_access['delete']=='1')
								{
								?>
							<th><?php esc_attr_e('Action','school-mgt');?></th>
							<?php
								}?>
						</tr>
					</thead>
		 
					<tfoot>
						<tr>
							<th><?php esc_attr_e('Bed Unique ID','school-mgt');?></th>
							<th><?php esc_attr_e('Room Unique ID','school-mgt');?></th>
							<th><?php esc_attr_e('Availability','school-mgt');?></th>
							<th><?php esc_attr_e('Description','school-mgt');?></th>
							 <?php
								if($user_access['edit']=='1' || $user_access['delete']=='1')
								{
								?>
							<th><?php esc_attr_e('Action','school-mgt');?></th>
							<?php
								}?>
					</tfoot>
		 
					<tbody>
					<?php 	
					foreach ($retrieve_class as $retrieved_data)
					{ 		
					 ?>
						<tr>
							<td><?php echo $retrieved_data->bed_unique_id;?></td>
							<td><?php echo mj_smgt_get_room_unique_id_by_id($retrieved_data->room_id);?></td>
							<?php 
							if($retrieved_data->bed_status == '0')
							{	?>
								<td><label class="occ-green"><?php esc_attr_e('Available','school-mgt');?></label></td>
								<?php 
							}
							else 
							{?>
								<td><label class="occ-red"><?php esc_attr_e('Occupied','school-mgt');?></label></td>
							<?php 
							}?>
							<td><?php echo $retrieved_data->bed_description;?></td>
							 <?php
								if($user_access['edit']=='1' || $user_access['delete']=='1')
								{
								?>
								<td>
								<?php
								if($user_access['edit']=='1')
								{
								?>
									<a href="?dashboard=user&page=hostel&tab=add_bed&action=edit_bed&bed_id=<?php echo $retrieved_data->id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
									<?php
								}
								if($user_access['delete']=='1')
								{
								?>
									<a href="?dashboard=user&page=hostel&tab=bed_list&action=delete_bed&bed_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><?php esc_attr_e('Delete','school-mgt');?></a>
								<?php
								}
								?>
								</td>
							<?php
								}
								?>
						</tr>
						<?php
					} ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}
	if($active_tab == 'add_bed')
	{
		$obj_hostel=new smgt_hostel;
		 ?>
		<!--Group POP up code -->
		<div class="popup-bg">
			<div class="overlay-content admission_popup">
				<div class="modal-content">
					<div class="category_list">
					</div>     
				</div>
			</div>     
		</div>
		<?php 
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_bed')
		{
			$edit=1;
			$bed_data=$obj_hostel->mj_smgt_get_bed_by_id($_REQUEST['bed_id']);
		}
		?>
		<div class="panel-body">
			<form name="bed_form" action="" method="post" class="mt-3 form-horizontal" id="bed_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="bed_id" value="<?php if($edit){ echo $bed_data->id;}?>"/>  
				 <div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end " for="bed_unique_id"><?php esc_attr_e('Bed Unique ID','school-mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="bed_unique_id" class="form-control col-form-label  validate[required] text-input" type="text" value="<?php if($edit){ echo $bed_data->bed_unique_id; } else { echo mj_smgt_generate_bed_code(); } ?>"  name="bed_unique_id" readonly>		
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="room_id"><?php esc_attr_e('Room Unique ID','school-mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select name="room_id" class="form-control col-form-label  validate[required] width_100" id="room_id">
							<option value=""><?php echo esc_attr_e( 'Select Room Unique ID', 'school-mgt' ) ;?></option>
							<?php $roomval='';
							$room_data=$obj_hostel->mj_smgt_get_all_room();
							if($edit){  
								$roomval=$bed_data->room_id; 
								foreach($room_data as $room)
								{ ?>
								<option value="<?php echo $room->id;?>" <?php selected($room->id,$roomval);  ?>>
								<?php echo $room->room_unique_id;?></option> 
							<?php }
							}else
							{
								foreach($room_data as $room)
								{ ?>
								<option value="<?php echo $room->id;?>" <?php selected($room->id,$roomval);  ?>><?php echo $room->room_unique_id;?></option> 
							<?php }
							}
							?>
						</select>
					</div>
				</div>
				<?php wp_nonce_field( 'save_bed_admin_nonce' ); ?>
				 
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="bed_description"><?php esc_attr_e('Description','school-mgt');?></label>
					<div class="col-sm-8">
						<textarea name="bed_description" id="bed_description" maxlength="150" class="form-control col-form-label  validate[custom[address_description_validation]]"><?php if($edit){ echo $bed_data->bed_description;}?></textarea>		
					</div>
				</div>
				<div class="offset-sm-2 col-sm-8">        	
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Bed','school-mgt'); }else{ esc_attr_e('Add Bed','school-mgt');}?>" name="save_bed" class="btn btn-success" />
				</div>
			</form>
        </div>
	<?php
	}
	if($active_tab == 'assign_room')
	{
		$obj_hostel=new smgt_hostel;
		
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'view_assign_room')
		{
			$room_id=$_REQUEST['room_id'];
		}
		$bed_data=$obj_hostel->mj_smgt_get_all_bed_by_room_id($room_id);
		$hostel_id=$obj_hostel->mj_smgt_get_hostel_id_by_room_id($room_id);

		$exlude_id = mj_smgt_approve_student_list();
		$student_all= get_users(array('role'=>'student','exclude'=>$exlude_id));
		
		foreach($student_all as $aa)
		{
			$student_id[]=$aa->ID;
		}
		//--------- GET ASSIGNED STUDENT DATA -------//
		$assign_data=mj_smgt_all_assign_student_data();
		
		if(!empty($assign_data))
		{
			foreach($assign_data as $bb)
			{
				$student_new_id[]=$bb->student_id;
			} 
			$Student_result=array_diff($student_id,$student_new_id);
		}
		else
		{
			$Student_result=$student_id;
		}
		?>
		<div class="panel-body">
			<?php
			$i=0;
			if(!empty($bed_data))
			{
				foreach($bed_data as $data)
				{
					$student_data =mj_smgt_student_assign_bed_data($data->id);
				?>
				<form name="bed_form" action="" method="post" class="mt-3 form-horizontal" id="bed_form">
						<input type="hidden" name="room_id_new[]" value="<?php echo $data->room_id;?>">
						<input type="hidden" name="bed_id[]" value="<?php echo $data->id;?>">
						<input type="hidden" name="hostel_id" value="<?php echo $hostel_id;?>">
						<div class="mb-3 form-group row">
							<div class="row">
								<label class="col-md-2 col-sm-2 col-xs-12 control-label col-form-label text-md-end" for="bed_unique_id"><?php esc_attr_e( 'Bed Unique ID', 'school-mgt' ) ;?><span class="require-field"></span></label>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<input id="bed_unique_id_<?php echo $i;?>" class="form-control col-form-label  validate[required]" type="text" value="<?php echo $data->bed_unique_id;;?>" name="bed_unique_id[]" readonly>
								</div>
								<?php
										if(!empty($student_data))
										{
											$new_class='';
										}
										else
										{
											$new_class='new_class';
										}
										?>

								<div class="col-md-2 col-sm-2 col-xs-12">
									<select name="student_id[]" id="students_list_<?php echo $i ;?>" data-index="<?php echo $i;?>" class="form-control col-form-label  max_width_margin_top_10 student_check <?php echo $new_class; ?> students_list_<?php echo $i ;?>">
										<?php
										if(!empty($student_data))
										{
											$roll_no = get_user_meta( $student_data->student_id, 'roll_id' , true );
											$class_id = get_user_meta( $student_data->student_id, 'class_name' , true );
										?>
											<option value="<?php echo $student_data->student_id; ?>" ><?php echo mj_smgt_get_display_name($student_data->student_id).' ('.$roll_no.') ('.mj_smgt_get_class_name($class_id).')'; ?></option>
											<?php 
										}
										else
										{?>
											<option value="0"><?php  esc_attr_e( 'Select Student', 'school-mgt' );?></option>
											<?php foreach($Student_result as $student)
											{
												$roll_no = get_user_meta( $student, 'roll_id' , true );
												$class_id = get_user_meta( $student, 'class_name' , true );
											?>
												<option value="<?php echo $student; ?>"><?php echo mj_smgt_get_display_name($student).' ('.$roll_no.') ('.mj_smgt_get_class_name($class_id).')'; ?></option>
											<?php 
											}
										}
										?>
									</select>
								</div>
								<?php
								if(!empty($student_data))
								{
								?>
									<div class="col-md-2 col-sm-2 col-xs-12">
										<input id="assign_date_<?php echo $i ;?>"  value="<?php  echo mj_smgt_getdate_in_input_box($student_data->assign_date); ?>" class="form-control col-form-label  text-input margin_top_10_res" type="text" name="assign_date[]" readonly>
									</div>
								<?php
								}
								else
								{?>
								<div class="col-md-2 col-sm-2 col-xs-12 assigndate_<?php echo $i;?>" id="assigndate_<?php echo $i ;?>" name="assigndate" >
									<input id="assign_date_<?php echo $i;?>" placeholder="<?php esc_attr_e( 'Enter Date', 'school-mgt' );?>" class="datepicker form-control col-form-label  text-input margin_top_10_res" type="text" name="assign_date[]">
								</div>
								<?php
								}
								if($student_data)
								{
								?>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<label class="col-md-2 col-sm-2 col-xs-12 control-label col-form-label occupied" for="available" ><?php esc_attr_e( 'Occupied', 'school-mgt' );?></label>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<a href="?dashboard=user&page=hostel&tab=room_list&action=delete_assign_bed&room_id=<?php echo $data->room_id;?>&bed_id=<?php echo $data->id;?>&student_id=<?php echo $student_data->student_id;?>" class="btn btn-danger" 
									onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this bed?','school-mgt');?>');"><?php esc_attr_e('Delete','school-mgt');?></a>
								</div>
								<?php
								}
								else
								{?>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<label class="col-md-2 col-sm-2 col-xs-12 control-label col-form-label available" for="available" ><?php esc_attr_e( 'Available', 'school-mgt' );?></label>
								</div>
								<?php
								}
								?>
							</div>
						</div>
					<?php
					$i++;
				}
				?>
				<?php wp_nonce_field( 'save_assign_room_admin_nonce' ); ?>
				<div class="offset-sm-2 col-sm-8">        	
					<input type="submit" id="Assign_bed" value="<?php esc_attr_e('Assign Room','school-mgt');?>" name="assign_room" class="btn btn-success" />
				</div>
			</form>
			<?php
			}
			else
			{ ?>
				<h4 class="require-field"><?php esc_attr_e('No Bed Available','school-mgt');?></h4>
			<?php
			}
			?>
		</div>
	<?php
	}
	?>
	</div> 
<?php ?>