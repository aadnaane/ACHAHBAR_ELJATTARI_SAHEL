<?php
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
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
	$tablename="holiday";
	//--------------------- DELETE HOLIDAY --------------//
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=mj_smgt_delete_holiday($tablename,$_REQUEST['holiday_id']);
		if($result){ 
			wp_redirect ( home_url() . '?dashboard=user&page=holiday&tab=holidaylist&message=3'); 	
		}
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=mj_smgt_delete_holiday($tablename,$id);
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=holiday&tab=holidaylist&message=3'); 	
		}
	}
	//------------------- SAVE HOLIDAYS --------------------/
	if(isset($_POST['save_holiday']))
	{
		$nonce = $_POST['_wpnonce'];
	    if ( wp_verify_nonce( $nonce, 'save_holiday_admin_nonce' ) )
		{
			$start_date = date('Y-m-d',strtotime($_REQUEST['date']));
			$end_date = date('Y-m-d',strtotime($_REQUEST['end_date']));
			if($start_date > $end_date )
			{ ?>
				<script type="text/javascript">
				  alert("End Date should be greater than the Start Date");
				</script>
				<?php
			}
			else
			{
				$haliday_data=array(
					'holiday_title'=>mj_smgt_popup_category_validation($_POST['holiday_title']),
					'description'=>mj_smgt_address_description_validation($_POST['description']),
					'date'=>date('Y-m-d', strtotime($_POST['date'])),
					'end_date'=>date('Y-m-d', strtotime($_POST['end_date'])),
					'created_by'=>get_current_user_id(),
					'created_date'=>date('Y-m-d H:i:s')
				);
				//table name without prefix
				$tablename="holiday";		
				if($_REQUEST['action']=='edit')
				{
					$holiday_id=array('holiday_id'=>$_REQUEST['holiday_id']);			
					$result=mj_smgt_update_record($tablename,$haliday_data,$holiday_id);
					if($result)
					{ 
						wp_redirect ( home_url() . '?dashboard=user&page=holiday&tab=holidaylist&message=2'); 	
					}
				}
				else
				{
					$startdate = strtotime($_POST['date']);
					$enddate = strtotime($_POST['end_date']);
					if($startdate==$enddate)
					{
						$date = $_POST['date'];
					}
					else
					{
						$date = $_POST['date'] ." To ".$_POST['end_date'];
					}
					$AllUsr = mj_smgt_get_all_user_in_plugin();
					foreach($AllUsr as $key=>$usr)
					{
						$to[] = $usr->user_email;
					}
					
					
					$result=mj_smgt_insert_record($tablename,$haliday_data);
					if($result)
					{
						$Search['{{holiday_title}}'] 	= 	mj_smgt_strip_tags_and_stripslashes($_POST['holiday_title']);
						$Search['{{holiday_date}}'] 	= 	$date;
						$Search['{{school_name}}'] 		= 	get_option('smgt_school_name');
					
						$message 	=	 mj_smgt_string_replacement($Search,get_option('holiday_mailcontent'));
						mj_smgt_send_mail($to,get_option('holiday_mailsubject'),$message);
						wp_redirect ( home_url() . '?dashboard=user&page=holiday&tab=holidaylist&message=1'); 	
					}
				}
			}
		}
	}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'holidaylist';
?>
<script type="text/javascript" >
jQuery(document).ready(function($){
	"use strict";
	 $('#holiday_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
				"aoColumns":[          
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},							 
							  {"bSortable": false},							 
							],
		    language:<?php echo mj_smgt_datatable_multi_language();?>	
    });	

	  $('#holiday_form_template').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
				  
				  $('#start_date').datepicker({		
					dateFormat: "yy-mm-dd",
					minDate:0,
					onSelect: function (selected) {
						var dt = new Date(selected);
						dt.setDate(dt.getDate() + 0);
						$("#end_date").datepicker("option", "minDate", dt);
					}
				}); 
				$('#end_date').datepicker({		
					dateFormat: "yy-mm-dd",
					minDate:0,
					onSelect: function (selected) {
						var dt = new Date(selected);
						dt.setDate(dt.getDate() - 0);
						$("#start_date").datepicker("option", "maxDate", dt);
					}
				}); 	 

});
</script>

<div class="p-4 mt-4 panel-body p panel-white">
	<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Holiday Added Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Holiday Updated Successfully.','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('Holiday Deleted Successfully.','school-mgt');
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
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="nav-item">
			<a href="?dashboard=user&page=holiday&tab=holidaylist" class="nav-tab2 p-2 px-3 nav-link  <?php echo $active_tab == 'holidaylist' ? 'active' : ''; ?>"> <strong>
				<i class="fa fa-align-justify"> </i> <?php esc_attr_e('Holiday List', 'school-mgt'); ?></strong>
			</a>
		</li>
		<li class="nav-item">
		<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
			<a href="?dashboard=user&page=holiday&tab=addholiday&action=edit&notice_id=<?php echo $_REQUEST['holiday_id'];?>" class="nav-tab2 p-2 px-3 nav-link <?php echo $active_tab == 'addholiday' ? 'nav-tab-active active' : ''; ?>">
		<?php esc_attr_e('Edit Holiday', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{
			if($user_access['add']=='1')
			{ ?>
				<a href="?dashboard=user&page=holiday&tab=addholiday" class="nav-tab2 p-2 px-3 nav-link  <?php echo $active_tab == 'addholiday' ? 'nav-tab-active active' : ''; ?>"><?php echo '<span class="fa fa-plus-circle"></span>'.esc_attr__(' Add Holiday', 'school-mgt'); ?></a>  
        <?php 
			}
		}?>
		</li>
	</ul>
	<?php 
	if($active_tab=='holidaylist')
	{
		
		$user_id=get_current_user_id();
		if($school_obj->role == 'supportstaff')
		{
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$retrieve_class = mj_smgt_get_all_holiday_created_by($user_id);
			}
			else
			{
				$retrieve_class = mj_smgt_get_all_data( 'holiday' );
			}
		}
		else
		{
			$retrieve_class = mj_smgt_get_all_data( 'holiday' );
		}
		?>
			<div class="mt-4 panel-body p">
				<div class="table-responsive">
					<table id="holiday_list" class="display dataTable" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th><?php esc_attr_e('Holiday Title','school-mgt');?></th>
								<th><?php esc_attr_e('Description','school-mgt');?></th>
								<th><?php esc_attr_e('Start Date','school-mgt');?></th>
								<th><?php esc_attr_e('End Date','school-mgt');?></th>
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
								<th><?php esc_attr_e('Holiday Title','school-mgt');?></th>
								<th><?php esc_attr_e('Description','school-mgt');?></th>
								<th><?php esc_attr_e('Start Date','school-mgt');?></th>
								<th><?php esc_attr_e('End Date','school-mgt');?></th>
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
							foreach ( $retrieve_class as $retrieved_data ) 
							{
							?>
							<tr>
								<td><?php echo $retrieved_data->holiday_title;?></td>
								<td><?php echo $retrieved_data->description;?></td>
								<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->date);?></td>
								<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);?></td>
								<?php
								if($user_access['edit']=='1' || $user_access['delete']=='1')
								{
									?>
									<td>
									<?php
									if($user_access['edit']=='1')
									{
									?>
										<a href="?dashboard=user&page=holiday&tab=addholiday&action=edit&holiday_id=<?php echo $retrieved_data->holiday_id;?>"class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
									<?php
									}
									if($user_access['delete']=='1')
									{ ?>
										<a href="?dashboard=user&page=holiday&tab=holidaylist&action=delete&holiday_id=<?php echo $retrieved_data->holiday_id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a>
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
	if($active_tab=='addholiday')
	{   ?>
		
		<?php  
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$holiday_data= mj_smgt_get_holiday_by_id($_REQUEST['holiday_id']);
			}
		?>
		<div class="mt-4 panel-body p">
			<form name="holiday_form" action="" method="post" class="form-horizontal" id="holiday_form_template">
			   <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="holiday_title"><?php esc_attr_e('Holiday Title','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="holiday_title" class="form-control validate[required,custom[popup_category_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo $holiday_data->holiday_title;}?>" name="holiday_title">
						<input type="hidden" name="holiday_id"   value="<?php if($edit){ echo $holiday_data->holiday_id;}?>"/> 
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="description"><?php esc_attr_e('Description','school-mgt');?></label>
					<div class="col-sm-8">
						<input id="holiday_title" class="form-control validate[custom[address_description_validation]]" maxlength="150" type="text" value="<?php if($edit){ echo $holiday_data->description;}?>" name="description">				
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="date"><?php esc_attr_e('Start Date','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="start_date" class="datepicker form-control validate[required] text-input" type="text" value="<?php if($edit){ echo date("Y-m-d",strtotime($holiday_data->date)); }?>" name="date" readonly>				
					</div>
				</div>
				<?php wp_nonce_field( 'save_holiday_admin_nonce' ); ?>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="date"><?php esc_attr_e('End Date','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="end_date" class="datepicker form-control validate[required] text-input" type="text" value="<?php if($edit){ echo date("Y-m-d",strtotime($holiday_data->end_date));}?>" name="end_date" readonly>				
					</div>
				</div>
				<div class="offset-sm-2 col-sm-8">        	
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Holiday','school-mgt'); }else{ esc_attr_e('Add Holiday','school-mgt');}?>" name="save_holiday" class="btn btn-success" />
				</div>        
			</form>
		</div>
	<?php
	}
	?>
</div>
<?php ?> 