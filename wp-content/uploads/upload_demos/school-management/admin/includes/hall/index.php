<script type="text/javascript">
jQuery(document).ready(function($)
{
	"use strict";	
	
	$('#hall_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#receipt_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});		
			 
			 
				  jQuery('.exam_hall_table').DataTable({
					responsive: true,
					bPaginate: false,
					bFilter: false, 
					bInfo: false,
				});   
			 
			$("body").on("click", "#checkbox-select-all", function()
			{
				if($(this).is(':checked',true))  
				{
					$(".my_check").prop('checked', true);  
				}  
				else  
				{  
					$(".my_check").prop('checked',false);  
				}
			});
			$("body").on("click", ".my_check", function()
			{
				if(false == $(this).prop("checked"))
				{
					$("#checkbox-select-all").prop('checked', false);
				}
				if ($('.my_check:checked').length == $('.my_check').length )
				{
					$("#checkbox-select-all").prop('checked', true);
				}
			});

		var table =  jQuery('#hall_list_admin').DataTable({
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
	//------- Send Mail For exam receipt ---------------//
	if(isset($_POST['send_mail_exam_receipt']))
	{
		$exam_id=$_POST['exam_id'];
	 //---------- Asigned Student Data --------//
		global $wpdb;
		$table_name_smgt_exam_hall_receipt = $wpdb->prefix . "smgt_exam_hall_receipt";
		$student_data_asigned = $wpdb->get_results( "SELECT user_id FROM $table_name_smgt_exam_hall_receipt where exam_id=".$exam_id);
		
		//------- SEND MAIL FOR EXAM RECEIPT GENERATED ---------------//
		if(!empty($student_data_asigned))
		{
			foreach($student_data_asigned as $student_id)
			{
				$headers='';
				$headers .= 'From: '.get_option('smgt_school_name').' <noreplay@gmail.com>' . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
				$userdata=get_userdata($student_id->user_id);
				$exam_data= mj_smgt_get_exam_by_id($exam_id);
				$student_email = $userdata->user_email;
				$string = array();
				$string['{{student_name}}']   = $userdata->display_name;
				$string['{{school_name}}'] =  get_option('smgt_school_name');
				$msgcontent                =  get_option('exam_receipt_content');		
				$msgsubject				   =  get_option('exam_receipt_subject');
				$message = mj_smgt_string_replacement($string,$msgcontent);
				$student_id_new=$student_id->user_id;
				mj_smgt_send_mail_receipt_pdf($student_email,$msgsubject,$message,$student_id_new,$exam_id);  
			}
			wp_redirect ( admin_url().'admin.php?page=smgt_hall&tab=exam_hall_receipt&message=4');
		}
	}
	// This is Class at admin side!!!!!!!!! 
	//---------delete record--------------------
	$tablename="hall";
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=mj_smgt_delete_hall($tablename,$_REQUEST['hall_id']);
		if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_hall&tab=hall_list&message=3');
			}
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
				$result=mj_smgt_delete_hall($tablename,$id);
		if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_hall&tab=hall_list&message=3');
			}
	}
	//----------insert and update--------------------
	if(isset($_POST['save_hall']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_hall_admin_nonce' ) )
		{
			$created_date = date("Y-m-d H:i:s");
			$hall_data=array('hall_name'=>mj_smgt_popup_category_validation($_POST['hall_name']),
							'number_of_hall'=>mj_smgt_onlyNumberSp_validation($_POST['number_of_hall']),
							'hall_capacity'=>mj_smgt_onlyNumberSp_validation($_POST['hall_capacity']),
							'description'=>mj_smgt_address_description_validation($_POST['description']),
							'date'=>$created_date
							);
			//table name without prefix
			$tablename="hall";
			
			if($_REQUEST['action']=='edit')
			{
				$transport_id=array('hall_id'=>$_REQUEST['hall_id']);
				$result=mj_smgt_update_record($tablename,$hall_data,$transport_id);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_hall&tab=hall_list&message=2');
				}
			}
			else
			{
				$result=mj_smgt_insert_record($tablename,$hall_data);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=smgt_hall&tab=hall_list&message=1');
				}
			}
	    }
	}	
$active_tab = isset($_GET['tab'])?$_GET['tab']:'hall_list';
	?>
<div class="page-inner">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div id="main-wrapper"  class=" class_list"> 
<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Hall Added Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Hall Updated Successfully.','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('Hall Deleted Successfully.','school-mgt');
			break;
		case '4':
			$message_string = esc_attr__('Mail Send Successfully.','school-mgt');
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
    	<a href="?page=smgt_hall&tab=hall_list" class="nav-tab margin_bottom <?php echo $active_tab == 'hall_list' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Exam Hall List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
       <a href="?page=smgt_hall&tab=addhall&action=edit&notice_id=<?php echo $_REQUEST['hall_id'];?>" class="nav-tab margin_bottom <?php echo $active_tab == 'addhall' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Hall', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_hall&tab=addhall" class="nav-tab margin_bottom <?php echo $active_tab == 'addhall' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'. esc_attr__('Add Exam Hall', 'school-mgt'); ?></a>  
        <?php } ?>
		<a href="?page=smgt_hall&tab=exam_hall_receipt" class="nav-tab margin_bottom <?php echo $active_tab == 'exam_hall_receipt' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Exam Hall Receipt', 'school-mgt'); ?></a>  
    </h2>
    <?php
	
	if($active_tab == 'hall_list')
	{	
	?>	
   		
    	
         <?php 
		 $retrieve_class = mj_smgt_get_all_data($tablename);
		?>
</script>
        <div class="table-responsive">
		<form id="frm-example" name="frm-example" method="post">
        <table id="hall_list_admin" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>      
				<th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>          
                <th><?php esc_attr_e('Hall Name','school-mgt');?></th>
                <th><?php esc_attr_e('Hall Numeric Value','school-mgt');?></th>
                <th><?php esc_attr_e('Capacity','school-mgt');?></th>
                <th><?php esc_attr_e('Description','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?> </th>               
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
             	<th><?php esc_attr_e('Hall Name','school-mgt');?></th>
                <th><?php esc_attr_e('Hall Numeric Value','school-mgt');?></th>
                <th><?php esc_attr_e('Capacity','school-mgt');?></th>
                <th><?php esc_attr_e('Description','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?> </th>        
            </tr>
        </tfoot>
 
        <tbody>
          <?php 	
		 	foreach ($retrieve_class as $retrieved_data){ 		
		 ?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->hall_id;?>"></td>
                <td><?php echo $retrieved_data->hall_name;?></td>
                <td><?php echo $retrieved_data->number_of_hall;?></td>
                <td><?php echo $retrieved_data->hall_capacity;?></td>
                <td><?php echo $retrieved_data->description;?></td>          
               <td><a href="?page=smgt_hall&tab=addhall&action=edit&hall_id=<?php echo $retrieved_data->hall_id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
               <a href="?page=smgt_hall&tab=hall_list&action=delete&hall_id=<?php echo $retrieved_data->hall_id;?>" class="btn btn-danger" 
               onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><?php esc_attr_e('Delete','school-mgt');?></a></td>
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
	if($active_tab == 'addhall')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/hall/add-hall.php';
		
	 }
	 if($active_tab == 'exam_hall_receipt')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/hall/exam_hall_receipt.php';
		
	 }
	 ?>
	 		</div>
	 	</div>
	 </div>
</div>