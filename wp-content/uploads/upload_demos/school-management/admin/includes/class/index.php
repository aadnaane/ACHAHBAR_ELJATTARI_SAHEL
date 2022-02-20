<script type="text/javascript">
jQuery(document).ready(function($)
{
	"use strict";	
	$('#class_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	var table =  jQuery('#class_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[	                  
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false}],
		language:<?php echo mj_smgt_datatable_multi_language();?>
    });
	 jQuery('#checkbox-select-all').on('click', function(){
     
      var rows = table.rows({ 'search': 'applied' }).nodes();
      jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
   }); 
   
	 $("#delete_selected").on('click', function()
		{	
			if ($('.select-checkbox:checked').length == 0 )
			{
				alert(language_translate2.one_record_select_alert);
				return false;
			}
		   else
		   {
				var alert_msg=confirm(language_translate2.delete_record_alert);
				if(alert_msg == false)
				{
					return false;
				}
				else
				{
					return true;
				}
			}
	});
});
</script>
<?php 
	// This is Class at admin side!!!!!!!!! 
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
					wp_redirect ( admin_url().'admin.php?page=smgt_class&tab=classlist&message=2');
				 }
			}
			else
			{
				$result=mj_smgt_insert_record($tablename,$classdata);
				if($result)
				{
				wp_redirect ( admin_url().'admin.php?page=smgt_class&tab=classlist&message=1');
				 }
			}
		}
	}
	$tablename="smgt_class";
	/*Delete selected Subject*/
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=mj_smgt_delete_class($tablename,$id);
		if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_class&tab=classlist&message=3'); 
			}
	}
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=mj_smgt_delete_class($tablename,$_REQUEST['class_id']);
		if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_class&tab=classlist&message=3');
			}
	}

$active_tab = isset($_GET['tab'])?$_GET['tab']:'classlist';
	?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
    <div class="modal-content">
    <div class="invoice_data">
     </div>
     
    </div>
    </div> 
    
</div>
<!-- End POP-UP Code -->
<div class="page-inner">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div  id="main-wrapper" class="class_list">
<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Class Added Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Class Updated Successfully.','school-mgt');
			break;
		case '3':
			$message_string = esc_attr__('Class Deleted Successfully.','school-mgt');
			break;
	}
	
	if($message)
	{ ?>
		<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
			<p><?php echo $message_string;?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
<?php } ?>
	<div class="panel panel-white">
			<div class="panel-body">		
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_class&tab=classlist" class="nav-tab margin_bottom <?php echo $active_tab == 'classlist' ? 'nav-tab-active active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Class List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
         <a href="?page=smgt_class&tab=addclass&action=edit&class_id=<?php echo $_REQUEST['class_id'];?>" class="nav-tab <?php echo $active_tab == 'addclass' ? 'nav-tab-active active' : ''; ?>">
		<?php esc_attr_e('Edit Class', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_class&tab=addclass" class="nav-tab margin_bottom <?php echo $active_tab == 'addclass' ? 'nav-tab-active active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'. esc_attr__('Add Class', 'school-mgt'); ?></a>  
        <?php } ?>
    </h2>
    
    <?php
	
	if($active_tab == 'classlist')
	{	
	?>	
   		 
    	
         <?php 
		 	$retrieve_class = mj_smgt_get_all_data($tablename);
			
			
			
		?>
		<div class="panel-body">
        <div class="table-responsive">
		<form id="frm-example" name="frm-example" method="post">
        <table id="class_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
                <th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>
                <th><?php esc_attr_e('Class Name','school-mgt');?></th>
                <th><?php esc_attr_e('Class Numeric Name','school-mgt');?></th>
                <!--<th><?php esc_attr_e('Section','school-mgt');?></th>-->
                <th><?php esc_attr_e('Capacity','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?></th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
                <th><?php esc_attr_e('Class Name','school-mgt');?></th>
                <th><?php esc_attr_e('Class Numeric Name','school-mgt');?></th>
              <!--  <th><?php esc_attr_e('Section','school-mgt');?></th>-->
                <th><?php esc_attr_e('Capacity','school-mgt');?></th>
                <td><?php esc_attr_e('Action','school-mgt');?></td>
            </tr>
        </tfoot>
 
        <tbody>
          <?php 
		 	foreach ($retrieve_class as $retrieved_data){ 
			
		 ?>
            <tr>
				 <td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->class_id;?>"></td>
                <td><?php echo $retrieved_data->class_name;?></td>
                <td><?php echo $retrieved_data->class_num_name;?></td>
                <!--<td><?php echo $retrieved_data->class_section;?></td>-->
                <td><?php echo $retrieved_data->class_capacity;?></td>
               <td><a href="?page=smgt_class&tab=addclass&action=edit&class_id=<?php echo $retrieved_data->class_id;?>" class="btn btn-info"> <?php esc_attr_e('Edit','school-mgt');?></a>
               <a href="?page=smgt_class&tab=classlist&action=delete&class_id=<?php echo $retrieved_data->class_id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a>
				<a class="btn btn-default" href="#" id="addremove" class_id="<?php echo $retrieved_data->class_id;?>" model="class_sec"><?php esc_attr_e('View Or Add Section','school-mgt');?></a>
				
				</td>
		  </tr>
            <?php } ?>
     
        </tbody>
        
        </table>
		 <div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected" class="btn btn-danger delete_selected"/>
			
		</div>
		</form>
        </div>
        </div>
       </div>
     <?php 
	 }
	if($active_tab == 'addclass')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/class/add-newclass.php';
		
	 }
	 ?>
	 </div>
</div>
</div>
<?php ?>