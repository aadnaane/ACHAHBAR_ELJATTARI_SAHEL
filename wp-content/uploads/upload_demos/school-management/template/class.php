<script type="text/javascript">
jQuery(document).ready(function($)
{
	"use strict";	
	$('#class_list').DataTable({
        responsive: true,
		language:<?php echo mj_smgt_datatable_multi_language();?>	
    }); 
    $('#class_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
});
</script>
<?php 
//-------- CHECK BROWSER JAVA SCRIPT ----------//
mj_smgt_browser_javascript_check();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'classlist';
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
if(isset($_POST['save_class']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_class_admin_nonce' ) )
	{
		$created_date = date("Y-m-d H:i:s");
		$classdata=array('class_name'=>mj_smgt_popup_category_validation($_POST['class_name']),
						'class_num_name'=>mj_smgt_onlyNumberSp_validation($_POST['class_num_name']),
						'class_capacity'=>mj_smgt_onlyNumberSp_validation($_POST['class_capacity']),	
						'creater_id'=>get_current_user_id(),
						'created_date'=>$created_date
						
		);
		$tablename="smgt_class";
		if($_REQUEST['action']=='edit')
		{
			$classid=array('class_id'=>$_REQUEST['class_id']);
			$result=mj_smgt_update_record($tablename,$classdata,$classid);
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=class&tab=classlist&message=2');
				exit;
			}
		}
		else
		{
			$result=mj_smgt_insert_record($tablename,$classdata);
			if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=class&tab=classlist&message=1');
				exit;
			}
		}
	}
}
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	$tablename="smgt_class";
	$result=mj_smgt_delete_class($tablename,$_REQUEST['class_id']);
	if($result)
	{
		wp_redirect ( home_url() . '?dashboard=user&page=class&tab=classlist&message=3');
		exit;
	}
}
if(isset($_GET['message']) && $_GET['message'] == 1 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('Class Added Successfully.','school-mgt');?>
	</div>
<?php
}
if(isset($_GET['message']) && $_GET['message'] == 2 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('Class Updated Successfully.','school-mgt');?>
	</div>
<?php
}
if(isset($_GET['message']) && $_GET['message'] == 3 )
{
?>
	<div class="alert_msg alert alert-success alert-dismissible " role="alert">
		<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		<?php esc_attr_e('Class Deleted Successfully.','school-mgt');?>
	</div>
<?php
}
?>
<!-- Nav tabs -->
<div class="panel-body panel-white p-4">
	
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		<li class="nav-item">
			<a href="?dashboard=user&page=class&tab=classlist" class="nav-link nav-tab2 <?php if($active_tab=='classlist'){?>active<?php }?>">
				<i class="fa fa-align-justify"></i>  <?php esc_attr_e('Class List', 'school-mgt'); ?></a>
			</a>
		</li>
		<?php 
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{ ?>
			<li class="nav-item">
				<a href="?dashboard=user&page=class&tab=addclass&&action=edit&class_id=<?php echo $_REQUEST['class_id'];?>" class="nav-link nav-tab2 <?php if($active_tab=='addclass'){?>active<?php }?>">
					<i class="fa fa-align-justify"></i> <?php esc_attr_e('Edit Class', 'school-mgt'); ?>
				</a>
			</li>
			<?php 
		}
		else
		{
			if($user_access['add']=='1')
			{ ?>
				<li class="nav-item">
					<a href="?dashboard=user&page=class&tab=addclass" class="nav-link nav-tab2  <?php if($active_tab=='addclass') { ?> active <?php } ?>">
						<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Add Class', 'school-mgt'); ?>
					</a>
				</li>
		<?php 
			} 
		} ?>	
	</ul>
    <!-- Tab panes -->
	<?php
	if($active_tab == 'classlist')
	{
		$tablename="smgt_class";
		$user_id=get_current_user_id();
		 
		//------- EXAM DATA FOR TEACHER ---------//
		if($school_obj->role == 'teacher')
		{
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$class_id 	= 	get_user_meta(get_current_user_id(),'class_name',true);	
				$retrieve_class	=mj_smgt_get_all_class_data_by_class_array($class_id);
			}
			else
			{
				$retrieve_class = mj_smgt_get_all_data($tablename);			
			}
		}
		//------- EXAM DATA FOR SUPPORT STAFF ---------//
		else
		{ 
			$retrieve_class = mj_smgt_get_all_data($tablename);	
		} 
		?>
		<div class="panel-body">
			<div class="table-responsive">
				<table id="class_list" class="display dataTable exam_datatable" cellspacing="0" width="100%">
					 <thead>
						 <tr>
							<th><?php esc_attr_e('Class Name','school-mgt');?></th>
							<th><?php esc_attr_e('Class Numeric Name','school-mgt');?></th>
							<!--<th><?php esc_attr_e('Section','school-mgt');?></th>-->
							<th><?php esc_attr_e('Capacity','school-mgt');?></th>
							<th><?php esc_attr_e('Action','school-mgt');?></th>
						</tr>
					</thead>
		 
					<tfoot>
						 <tr>
							<th><?php esc_attr_e('Class Name','school-mgt');?></th>
							<th><?php esc_attr_e('Class Numeric Name','school-mgt');?></th>
							<!--<th><?php esc_attr_e('Section','school-mgt');?></th>-->
							<th><?php esc_attr_e('Capacity','school-mgt');?></th>
							<th><?php esc_attr_e('Action','school-mgt');?></th>
						</tr>
					</tfoot>
		 
					<tbody>
					 <?php 
					foreach ($retrieve_class as $retrieved_data)
					{ 
					 ?>
						<tr>
							<td><?php echo $retrieved_data->class_name;?></td>
							<td><?php echo $retrieved_data->class_num_name;?></td>
							<!--<td><?php echo $retrieved_data->class_section;?></td>-->
							<td><?php echo $retrieved_data->class_capacity;?></td>
							
							<td>
							<?php
							if($user_access['edit']=='1')
							{
							?>
							<a href="?dashboard=user&page=class&tab=addclass&action=edit&class_id=<?php echo $retrieved_data->class_id;?>" class="btn btn-info"> <?php esc_attr_e('Edit','school-mgt');?></a>
							<?php
							}
							if($user_access['delete']=='1')
							{
							?>
							<a href="?dashboard=user&page=class&tab=classlist&action=delete&class_id=<?php echo $retrieved_data->class_id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a>
							<?php
							}
							if($user_access['add']=='1')
							{
							?>
							<a class="btn btn-default" href="#" id="addremove" class_id="<?php echo $retrieved_data->class_id;?>" model="class_sec"><?php esc_attr_e('View Or Add Section','school-mgt');?></a>
							<?php
							}
							?>	
							</td>

					  </tr>
						<?php 
					} ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php
	} ?>

<?php 
if($active_tab == 'addclass')
{
?>

        <?php 
			$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$classdata= mj_smgt_get_class_by_id($_REQUEST['class_id']);
			} 
		?>
       
    <div class="panel-body">	
        <form name="class_form" action="" method="post" class="form-horizontal" id="class_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
        <div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end " for="class_name"><?php esc_attr_e('Class Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="class_name" class="form-control validate[required,custom[popup_category_validation]]" maxlength="50" type="text" value="<?php if($edit){ echo $classdata->class_name;}?>" name="class_name">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_num_name"><?php esc_attr_e('Numeric  Class Name','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="class_num_name" class="form-control validate[required,min[0],maxSize[4]] text-input" type="number" value="<?php if($edit){ echo $classdata->class_num_name;}?>" name="class_num_name" >
			</div>
		</div>
        <?php wp_nonce_field( 'save_class_admin_nonce' ); ?>				
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_capacity"><?php esc_attr_e('Student Capacity In Section','school-mgt');?> </label>
			<div class="col-sm-8">
				<input id="class_capacity" class="form-control validate[min[0],maxSize[4]]" type="number" value="<?php if($edit){ echo $classdata->class_capacity;}?>" name="class_capacity">
			</div>
		</div>
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Class','school-mgt'); }else{ esc_attr_e('Add Class','school-mgt');}?>" name="save_class" class="btn btn-success" />
        </div>        
        </form>
    </div>
<?php
}
?>
 </div> 