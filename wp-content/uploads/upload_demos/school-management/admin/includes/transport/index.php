<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
	var table =  jQuery('#transport_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
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

	$('#transport_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
});
</script>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content top_color">
		<div class="modal-content">
			<div class="view_popup"></div>     
		</div>
    </div>    
</div>
<!-- End POP-UP Code -->
<?php 
	// This is Class at admin side!!!!!!!!! 
	//----------Add-update record---------------------//
	$tablename="transport";
	if(isset($_POST['save_transport']))
	{	
        $nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_transpoat_admin_nonce' ) )
		{
			if(isset($_POST['smgt_user_avatar']) && $_POST['smgt_user_avatar'] != "")
			{
				$photo=$_POST['smgt_user_avatar'];
			}
			else
			{
				$photo="";
			}
			
			$route_data=array(
				'route_name'=>mj_smgt_address_description_validation($_POST['route_name']),
				'number_of_vehicle'=>mj_smgt_onlyNumberSp_validation($_POST['number_of_vehicle']),
				'vehicle_reg_num'=>mj_smgt_address_description_validation($_POST['vehicle_reg_num']),
				'smgt_user_avatar'=>$photo,
				'driver_name'=>mj_smgt_onlyLetter_specialcharacter_validation($_POST['driver_name']),
				'driver_phone_num'=>mj_smgt_phone_number_validation($_POST['driver_phone_num']),
				'driver_address'=>mj_smgt_address_description_validation($_POST['driver_address']),
				'route_description'=>mj_smgt_address_description_validation($_POST['route_description']),					
				'route_fare'=>mj_smgt_address_description_validation($_POST['route_fare']),
				'created_by'=>get_current_user_id()
			);
					
			//table name without prefix
			$tablename="transport";
			if($_REQUEST['action']=='edit')
			{
				$transport_id=	array('transport_id'=>$_REQUEST['transport_id']);
				$result	=	mj_smgt_update_record($tablename,$route_data,$transport_id);
				wp_redirect ( admin_url().'admin.php?page=smgt_transport&tab=transport&message=2');

			}
			else
			{		
				$result	=	mj_smgt_insert_record($tablename,$route_data);
				
				if($result)
				{	
					$SearchArr['{{route_name}}']	=	$_POST['route_name'];
					$SearchArr['{{vehicle_identifier}}']	=	$_POST['number_of_vehicle'];
					$SearchArr['{{vehicle_registration_number}}']	=	$_POST['vehicle_reg_num'];
					$SearchArr['{{driver_name}}']	=	$_POST['driver_name'];
					$SearchArr['{{driver_phone_number}}']	=	$_POST['driver_phone_num'];
					$SearchArr['{{driver_address}}']	=	$_POST['driver_address'];
					$SearchArr['{{route_fare}}']	=	$_POST['route_fare'];
					$SearchArr['{{school_name}}']	=	 get_option('smgt_school_name');
					$MSG = mj_smgt_string_replacement($SearchArr,get_option('school_bus_alocation_mail_content'));
					
					$AllUsr = mj_smgt_get_all_user_in_plugin();
					foreach($AllUsr as $key=>$usr)
					{
						 $to[] = $usr->user_email;
					}
					mj_smgt_send_mail($to,get_option('school_bus_alocation_mail_subject'),$MSG);
				   	wp_redirect ( admin_url().'admin.php?page=smgt_transport&tab=transport&message=1');
				 }
			}
	    }
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=mj_smgt_delete_transport($tablename,$id);
		if($result)
			{ 
				wp_redirect ( admin_url().'admin.php?page=smgt_transport&tab=transport&message=3');				
			}
	}
	//----------Delete record---------------------------
		$tablename="transport";
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=mj_smgt_delete_transport($tablename,$_REQUEST['transport_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_transport&tab=transport&message=3');					
			}
	}
$active_tab = isset($_GET['tab'])?$_GET['tab']:'transport';
	?>
<div class="page-inner">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>

<div class=" transport_list" id="main-wrapper"> 
<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Transport Added successfully','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Transport Updated successfully','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('Transport Delete Successfully','school-mgt');
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
    	<a href="?page=smgt_transport&tab=transport" class="nav-tab <?php echo $active_tab == 'transport' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.esc_attr__('Transport List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
       <a href="?page=smgt_transport&tab=addtransport&action=edit&transport_id=<?php echo $_REQUEST['transport_id'];?>" class="nav-tab <?php echo $active_tab == 'addtransport' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Transport', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_transport&tab=addtransport" class="nav-tab margin_bottom <?php echo $active_tab == 'addtransport' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.esc_attr__('Add Transport', 'school-mgt'); ?></a>  
        <?php } ?>
    </h2>
    <?php
	
	if($active_tab == 'transport')
	{	
	?>	
   <?php 
		 	$retrieve_class = mj_smgt_get_all_data($tablename);
			
		?>
		<div class="panel-body">

        	<div class="table-responsive">
			<form id="frm-example" name="frm-example" method="post">	
        <table id="transport_list" class="display admin_transport_datatable" cellspacing="0" width="100%">
        	 <thead>
            <tr>         
				<th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>      
				<th><?php echo esc_attr_e( 'Photo', 'school-mgt' ) ;?></th>
                <th><?php echo esc_attr_e( 'Route Name', 'school-mgt' ) ;?></th>
                <th><?php echo esc_attr_e( 'Vehicle Identifier', 'school-mgt' ) ;?></th>
                <th><?php echo esc_attr_e( 'Vehicle Registration Number', 'school-mgt' ) ;?></th>
                <th><?php echo esc_attr_e( 'Driver Name', 'school-mgt' ) ;?></th>
                <th><?php echo esc_attr_e( 'Driver Phone Number', 'school-mgt' ) ;?></th>
				<th><?php echo esc_attr_e( 'Route Fare', 'school-mgt' ) ;?>(<?php echo mj_smgt_get_currency_symbol();?>)</th>
                <th><?php echo esc_attr_e( 'Action', 'school-mgt' ) ;?></th>               
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
				<th><?php echo esc_attr_e( 'Photo', 'school-mgt' ) ;?></th>
            	<th><?php echo esc_attr_e( 'Route Name', 'school-mgt' ) ;?></th>
                <th><?php echo esc_attr_e( 'Vehicle Identifier', 'school-mgt' ) ;?></th>
                <th width="150px"><?php echo esc_attr_e( 'Vehicle Registration Number', 'school-mgt' ) ;?></th>				
                <th><?php echo esc_attr_e( 'Driver Name', 'school-mgt' ) ;?></th>
                <th><?php echo esc_attr_e( 'Driver Phone Number', 'school-mgt' ) ;?></th>
                <th><?php echo esc_attr_e( 'Route Fare', 'school-mgt' ) ;?>(<?php echo mj_smgt_get_currency_symbol();?>)</th>
                <th><?php echo esc_attr_e( 'Action', 'school-mgt' ) ;?></th>  
            </tr>
        </tfoot>
 
        <tbody>
          <?php 
		 foreach ($retrieve_class as $retrieved_data){ 
		?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->transport_id;?>"></td>
				
				
						<td class="user_image"><?php $tid=$retrieved_data->transport_id;
							$umetadata=mj_smgt_get_user_driver_image($tid);
						
							if(empty($umetadata) || $umetadata['smgt_user_avatar'] == "")
							{	
								echo '<img src="'.get_option( 'smgt_driver_thumb' ).'" height="50px" width="50px" class="img-circle" />';
							}
							else
							echo '<img src='.$umetadata['smgt_user_avatar'].' height="50px" width="50px" class="img-circle" />';?>				
				
				</td>
				<td><?php echo $retrieved_data->route_name;?></td>
                <td><?php echo $retrieved_data->number_of_vehicle;?></td> 
				<td><?php echo $retrieved_data->vehicle_reg_num;?></td>				
                 <td><?php echo $retrieved_data->driver_name;?></td>
                  <td><?php echo $retrieved_data->driver_phone_num;?></td>
               <td><?php echo $retrieved_data->route_fare;?></td>         
               <td>
			   <a href="#" id="<?php echo $retrieved_data->transport_id;?>" type="transport_view" class="view_details_popup btn btn-success"><?php esc_attr_e('View','school-mgt');?></a>
			   <a href="?page=smgt_transport&tab=addtransport&action=edit&transport_id=<?php echo $retrieved_data->transport_id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
               <a href="?page=smgt_transport&tab=transport&action=delete&transport_id=<?php echo $retrieved_data->transport_id;?>" class="btn btn-danger" 
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
	if($active_tab == 'addtransport')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/transport/add-transport.php';
		
	 }
	 ?>
	 </div>
	 </div>
	 </div>
</div>
<?php ?>