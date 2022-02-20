<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
	$('#holiday_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	  
	  $('#date').datepicker({		
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
            $("#date").datepicker("option", "maxDate", dt);
        }
	}); 	 

	var table =  jQuery('#holiday_list').DataTable({
        responsive: true,
		"order": [[ 3, "desc" ]],
		"dom": 'Bfrtip',
		"buttons": [
			'colvis'
		], 
		"aoColumns":[	                  
	                  {"bSortable": false},
	                  {"bSortable": true},
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
		else{
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
	$tablename="holiday";
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=mj_smgt_delete_holiday($tablename,$_REQUEST['holiday_id']);
		if($result){ 
			wp_redirect ( admin_url().'admin.php?page=smgt_holiday&tab=holidaylist&message=3');
		}
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=mj_smgt_delete_holiday($tablename,$id);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=smgt_holiday&tab=holidaylist&message=3');
		}
	}
	if(isset($_POST['save_holiday']))
	{
		$nonce = $_POST['_wpnonce'];
	    if ( wp_verify_nonce( $nonce, 'save_holiday_admin_nonce' ) )
		{
			$start_date = date('Y-m-d',strtotime($_REQUEST['date']));
			$end_date = date('Y-m-d',strtotime($_REQUEST['end_date']));
			if($start_date > $end_date )
			{
				echo '<script type="text/javascript">alert("'. esc_attr__('End Date should be greater than the Start Date','school-mgt').'");</script>';
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
						wp_redirect ( admin_url().'admin.php?page=smgt_holiday&tab=holidaylist&message=2');
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
					wp_redirect ( admin_url().'admin.php?page=smgt_holiday&tab=holidaylist&message=1');
					 }
				}
			}
		}
	}
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'holidaylist';
?>
<div class="page-inner">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div  id="main-wrapper" class=" holidays_list">
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
		<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
			<p><?php echo $message_string;?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
<?php } ?>
	<div class="panel panel-white">
		<div class="panel-body">    
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_holiday&tab=holidaylist" class="nav-tab <?php echo $active_tab == 'holidaylist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Holiday List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
       <a href="?page=smgt_holiday&tab=addholiday&action=edit&notice_id=<?php echo $_REQUEST['holiday_id'];?>" class="nav-tab <?php echo $active_tab == 'addholiday' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Holiday', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_holiday&tab=addholiday" class="nav-tab margin_bottom <?php echo $active_tab == 'addholiday' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'. esc_attr__('Add Holiday', 'school-mgt'); ?></a>  
        <?php } ?>
    </h2>
    <?php
	
	if($active_tab == 'holidaylist')
	{	
		$retrieve_class = mj_smgt_get_all_data($tablename);
	?>
<div class="panel-body">

        <div class="table-responsive">
		<form id="frm-example" name="frm-example" method="post">
        <table id="holiday_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>  
				<th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>              
               <th><?php esc_attr_e('Holiday Title','school-mgt');?></th>
                <th><?php esc_attr_e('Description','school-mgt');?></th>
                <th><?php esc_attr_e('Start Date','school-mgt');?></th>
                <th><?php esc_attr_e('End Date','school-mgt');?></th>                 
                <th><?php esc_attr_e('Action','school-mgt');?></th>             
            </tr>
        </thead>
 
        <tfoot>
            <tr>
			<th></th>
             <th><?php esc_attr_e('Holiday Title','school-mgt');?></th>
                <th><?php esc_attr_e('Description','school-mgt');?></th>
                <th><?php esc_attr_e('Start Date','school-mgt');?></th>
                <th><?php esc_attr_e('End Date','school-mgt');?></th>               
                <th><?php esc_attr_e('Action','school-mgt');?></th>            
            </tr>
        </tfoot>
 
        <tbody>
          <?php 	
		 	foreach ($retrieve_class as $retrieved_data){ 		
		 ?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" value="<?php echo $retrieved_data->holiday_id;?>"></td>
                <td><?php echo $retrieved_data->holiday_title;?></td>
                <td><?php echo $retrieved_data->description;?></td>
                <td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->date);?></td>
                 <td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);?></td>                   
               <td><a href="?page=smgt_holiday&tab=addholiday&action=edit&holiday_id=<?php echo $retrieved_data->holiday_id;?>"class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
               <a href="?page=smgt_holiday&tab=holidaylist&action=delete&holiday_id=<?php echo $retrieved_data->holiday_id;?>" class="btn btn-danger" 
               onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a></td>
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
       
     <?php 
	 }
	if($active_tab == 'addholiday')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/holiday/add-holiday.php';
		
	 }
	 ?>
	 		</div>
	 	</div>
	 </div>
</div>
<?php ?>