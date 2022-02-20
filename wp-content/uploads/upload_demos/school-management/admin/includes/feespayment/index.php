<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
	$('#expense_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('#invoice_date').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
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

    	var blank_income_entry ='';
    	var blank_expense_entry ='';
   		blank_expense_entry = $('#expense_entry').html();   	
  	

   	$('#expense_form').validationEngine({
        promptPosition: "bottomRight",
        maxErrorsPerField: 1
    });

    $("#fees_data").multiselect({
        nonSelectedText: '<?php esc_attr_e( 'Select Fees Type', 'school-mgt' ) ;?>',
        includeSelectAllOption: true,
        selectAllText: '<?php esc_attr_e( 'Select all', 'school-mgt' ) ;?>',
        templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       }
    });
	
    $('#invoice_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        yearRange: '-65:+25',
        beforeShow: function(textbox, instance) {
            instance.dpDiv.css({
                marginTop: (-textbox.offsetHeight) + 'px'
            });
        },
        onChangeMonthYear: function(year, month, inst) {
            $(this).val(month + "/" + year);
        }
    });
    $('#end_year').on('change',function() {

        var end_value = parseInt($('#end_year option:selected').val());
        var start_value = parseInt($('#start_year option:selected').attr("id"));
        if (start_value > end_value) {
            $("#end_year option[value='']").attr('selected', 'selected');
            alert(language_translate2.starting_year_alert);
            return false;
        }
    });

    var blank_income_entry = '';
    var blank_expense_entry = '';
	blank_expense_entry = $('#expense_entry').html();

	function add_entry() {
	    $("#expense_entry").append(blank_expense_entry);
	}

	function deleteParentElement(n) {
	    alert(language_translate2.do_delete_record);
	    n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
	}

	var table =  jQuery('#feetype_list').DataTable({
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
	        {"bSortable": false}],
		 language:<?php echo mj_smgt_datatable_multi_language();?>
	 });

	jQuery('#checkbox-select-all').on('click', function(){     
		var rows = table.rows({ 'search': 'applied' }).nodes();
		jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
	}); 
   
	 var table =  jQuery('#fee_paymnt').DataTable({
		responsive: true,
		"order": [[ 8, "desc" ]],
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
				var alert_msg=confirm("Are you sure you want to delete this record?");
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
	
	$("#fees_reminder").on('click', function()
	{	
			if ($('.select-checkbox:checked').length == 0 )
			{
				alert(language_translate2.one_record_select_alert);
				return false;
			}
		else
		{
				var alert_msg=confirm("Are you sure you want to send a mail reminder?");
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
$obj_fees= new Smgt_fees();
$obj_feespayment= new mj_smgt_feespayment();
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
{
	if(isset($_REQUEST['fees_id']))
	{
		$result=$obj_fees->mj_smgt_delete_feetype_data($_REQUEST['fees_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=smgt_fees_payment&tab=feeslist&message=feetype_del');
		}
	}
	if(isset($_REQUEST['fees_pay_id']))
	{
		$result=$obj_feespayment->mj_smgt_delete_feetpayment_data($_REQUEST['fees_pay_id']);
		if($result)
		{
			wp_redirect ( admin_url() . 'admin.php?page=smgt_fees_payment&tab=feespaymentlist&message=fee_del');
		}
	}	
}

if(isset($_REQUEST['delete_selected_feetype']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
			$result=$obj_feespayment->mj_smgt_delete_feetype_data($id);
	if($result){ ?>
		<div id="message" class="updated below-h2">
			<p><?php esc_attr_e('Fee Type Successfully Delete','school-mgt');?></p>
		</div>
	<?php 
	}
}
if(isset($_REQUEST['delete_selected_feelist']))
{		
	if(!empty($_REQUEST['id']))
	foreach($_REQUEST['id'] as $id)
			$result=$obj_feespayment->mj_smgt_delete_feetpayment_data($id);
	if($result)
	{ ?>
		<div id="message" class="updated below-h2">
			<p><?php esc_attr_e('Fee Successfully Delete!','school-mgt');?></p>
		</div>
	<?php 
	}
}
	
if(isset($_POST['save_feetype']))
{	
    $nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_fees_type_admin_nonce' ) )	
	{		
		if($_REQUEST['action']=='edit')
		{	
			$result=$obj_fees->mj_smgt_add_fees($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_fees_payment&tab=feeslist&message=fee_edit');
			}
		}
		else
		{
			if(!$obj_fees->mj_smgt_is_duplicat_fees($_POST['fees_title_id'],$_POST['class_id']))
			{
				$result=$obj_fees->mj_smgt_add_fees($_POST);			
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=smgt_fees_payment&tab=feeslist&message=feetype_add');
				}
			}
			else
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_fees_payment&tab=feeslist&message=fee_dub');
			}
		}	
    }	
}	
if(isset($_POST['add_feetype_payment']))
{		
	$result=$obj_feespayment->mj_smgt_add_feespayment_history($_POST);			
	if($result)
	{
		wp_redirect ( admin_url() . 'admin.php?page=smgt_fees_payment&tab=feespaymentlist&message=1');
	}
}

if(isset($_POST['save_feetype_payment']))
{	
    $nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_payment_fees_admin_nonce' ) )
    {	
		if(isset($_REQUEST['smgt_enable_feesalert_mail']))
			update_option( 'smgt_enable_feesalert_mail', 1 );
		else
			update_option( 'smgt_enable_feesalert_mail', 0 );
			
		if($_REQUEST['action']=='edit')
		{
			 
			$result=$obj_feespayment->mj_smgt_add_feespayment($_POST);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_fees_payment&tab=feespaymentlist&message=feetype_edit');
			}
		}
		else
		{		
			$result=$obj_feespayment->mj_smgt_add_feespayment($_POST);			
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_fees_payment&tab=feespaymentlist&message=fee_add');
			}			
		}
    }	
}
/* Fees Reminder for Student and Parent */
if(isset($_REQUEST['action']) && $_REQUEST['action']=='reminder' && isset($_REQUEST['fees_pay_id']))
{
	$fees_id=$_REQUEST['fees_pay_id'];
	$data=$obj_feespayment->mj_smgt_get_single_fee_mj_smgt_payment($fees_id);
	$student_id=$data->student_id;
	$studentinfo=get_userdata($student_id);
	$student_mail=$studentinfo->user_email;
	$student_name=$studentinfo->display_name;
	$parent_id= get_user_meta($student_id, 'parent_id',true);
	foreach($parent_id as $id)
	{
		$parentinfo=get_userdata($id);
	}
	$parent_mail=$parentinfo->user_email;
	$parent_name=$parentinfo->display_name;
	$to=$parent_mail;
	$Due_amt = $data->total_amount-$data->fees_paid_amount;
	$due_amount=number_format($Due_amt,2);

	/* SMS Notification */
	$current_sms_service = get_option( 'smgt_sms_service');										
	if(!empty($parent_id))
	{
		$parent_number=array();
		foreach ($parent_id as $user_id)
		{
			$parent_number[] = "+".mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);
			$reciever_number = mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' )).get_user_meta($user_id, 'mobile_number',true);	
		}
		$message_content = "We just wanted to send you a reminder that the tuition fee has not been paid against your child ".$student_name;
		if(is_plugin_active('sms-pack/sms-pack.php'))
		{								
			$args = array();
			$args['mobile']=$parent_number;
			$args['message']=$message_content;					
			$args['message_from']='Feeslist';					
			$args['message_side']='front';					
			if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' ||$current_sms_service=='ViaNettSMS' || $current_sms_service=='africastalking')
			{
				$send = send_sms($args);
			}
		}
		else
		{											
			if($current_sms_service == 'clickatell')
			{
				$clickatell=get_option('smgt_clickatell_sms_service');
				$to = $reciever_number;
				$message = str_replace(" ","%20",$message_content);
				$username = $clickatell['username']; //clickatell username
				$password = $clickatell['password']; // clickatell password
				$api_key = $clickatell['api_key'];//clickatell apikey
				$baseurl ="http://api.clickatell.com";									
				$url = "$baseurl/http/auth?user=$username&password=$password&api_id=$api_key";									
				
				$ret = file_get_contents($url);							
				$sess = explode(":",$ret);
				if ($sess[0] == "OK")
				{
					$sess_id = trim($sess[1]); // remove any whitespace
					$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$reciever_number&text=".urlencode($message_content);									
					$ret = file_get_contents($url);
					$send = explode(":",$ret[0]);								
				}				
			}
			if($current_sms_service == 'twillo')
			{
				require_once SMS_PLUGIN_DIR. '/lib/twilio/Services/Twilio.php';
				$twilio=get_option( 'smgt_twillo_sms_service');
				$account_sid = $twilio['account_sid']; //Twilio SID
				$auth_token = $twilio['auth_token']; // Twilio token
				$from_number = $twilio['from_number'];//My number
				$receiver = $reciever_number; //Receiver Number
				$message = $message_content; // Message Text									
				$client = new Services_Twilio($account_sid, $auth_token);
				$message_sent = $client->account->messages->sendMessage(
					$from_number, // From a valid Twilio number
					$receiver, // Text this number
					$message
				);				
			}
			if($current_sms_service == 'msg91')
			{
				$country_code=mj_smgt_get_countery_phonecode(get_option( 'smgt_contry' ));
				$message = $message_content; // Message Text
				$send=smgt_msg91_send_mail_function($reciever_number,$message,$country_code);
			}								
		} 
	} 
	/* Mail Notification */
	$subject	= 	get_option('fee_payment_reminder_title'); 
	$Seach['{{student_name}}']	     =	 $student_name;
	$Seach['{{parent_name}}']		 =	 $parent_name;
	$Seach['{{total_amount}}']	 	 =	 $data->total_amount;
	$Seach['{{due_amount}}']		 =	 $due_amount;
	$Seach['{{class_name}}']		 =  	mj_smgt_get_class_name($data->class_id);
	$Seach['{{school_name}}']	     =	 get_option( 'smgt_school_name' );			
	$MsgContent 	= 	mj_smgt_string_replacement($Seach,get_option('fee_payment_reminder_mailcontent'));

	$from		= 	get_option('smgt_school_name');
	$fromemail		= 	get_option('smgt_email');
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
	if(get_option('smgt_mail_notification') == '1')
	{
		$send=wp_mail($to,$subject,$MsgContent,$headers);	
	}
	if($send)
	{
		wp_redirect ( admin_url() . 'admin.php?page=smgt_fees_payment&tab=feespaymentlist&message=mail_success');
	}
		wp_redirect ( admin_url() . 'admin.php?page=smgt_fees_payment&tab=feespaymentlist');
}

if(isset($_REQUEST['fees_reminder_feeslist']))
{		
	if(!empty($_REQUEST['id']))
	{
		foreach($_REQUEST['id'] as $id)
		{
			$fees_id=$id;
			$data=$obj_feespayment->mj_smgt_get_single_fee_mj_smgt_payment($fees_id);
			$student_id=$data->student_id;
			$studentinfo=get_userdata($student_id);
			$student_mail=$studentinfo->user_email;
			$student_name=$studentinfo->display_name;
			$parent_id= get_user_meta($student_id, 'parent_id',true);
			if (is_array($parent_id) || is_object($parent_id))
			{
				foreach($parent_id as $id)
				{
					$parentinfo=get_userdata($id);
				}
			}
			$parent_mail=$parentinfo->user_email;
			$parent_name=$parentinfo->display_name;
			$to=$parent_mail;
			$Due_amt = $data->total_amount-$data->fees_paid_amount;
			$due_amount=number_format($Due_amt,2);
			$subject	= 	get_option('fee_payment_reminder_title'); 
			$Seach['{{student_name}}']	     =	 $student_name;
			$Seach['{{parent_name}}']		 =	 $parent_name;
			$Seach['{{total_amount}}']	 	 =	 $data->total_amount;
			$Seach['{{due_amount}}']		 =	 $due_amount;
			$Seach['{{class_name}}']		 =  	mj_smgt_get_class_name($data->class_id);
			$Seach['{{school_name}}']	     =	 get_option( 'smgt_school_name' );			
			$MsgContent 	= 	mj_smgt_string_replacement($Seach,get_option('fee_payment_reminder_mailcontent'));

			$from		= 	get_option('smgt_school_name');
			$fromemail		= 	get_option('smgt_email');
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
			if(get_option('smgt_mail_notification') == '1')
			{
				$mail_send=wp_mail($to,$subject,$MsgContent,$headers);	
			}
			if($mail_send)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_fees_payment&tab=feespaymentlist&message=mail_success');
			}
		}
	}	
}


$active_tab = isset($_GET['tab'])?$_GET['tab']:'feeslist';
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class=" invoice_data"></div>
			<div class="category_list">
			</div>     
		</div>
    </div>
</div>
<!-- End POP-UP Code -->
<div class="page-inner">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div  id="main-wrapper" class=" payment_list"> 
<?php
$message_string="";
if(isset($_REQUEST['message']))
{	
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'';
	switch($message)
	{
		case 'feetype_del':
			$message_string = esc_attr__('Fee Type Deleted Successfully.','school-mgt');
			break;
		case 'fee_del':
			$message_string = esc_attr__('Fee Deleted Successfully.','school-mgt');
			break;
		case 'fee_edit':
			$message_string = esc_attr__('Fee Updated Successfully.','school-mgt');
			break;
		case 'fee_add':
			$message_string = esc_attr__('Fee added Successfully.','school-mgt');
			break;
		case 'fee_dub':
			$message_string = esc_attr__('Duplicate Fee.','school-mgt');
			break;
		case 'feetype_edit':
			$message_string = esc_attr__('Fee Type updated Successfully.','school-mgt');
			break;
		case 'feetype_add':
			$message_string = esc_attr__('Fee Type added Successfully.','school-mgt');
			break;
		case 'mail_success':
			$message_string = esc_attr__('Fee Payment reminder sent successfully.','school-mgt');
			break;
		default:
			$message_string = esc_attr__('Payment Successfully Done.','school-mgt');
	}		
	?>
		<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
			<p><?php echo $message_string;?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
<?php				
} ?>

	<div class="panel panel-white">
	<div class="panel-body">
	<h2 class="nav-tab-wrapper">
		<a href="?page=smgt_fees_payment&tab=feeslist" class="nav-tab <?php echo $active_tab == 'feeslist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.esc_attr__('Fees Type List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'addfeetype')
		{?>
       <a href="?page=smgt_fees_payment&tab=addfeetype&action=edit&fees_id=<?php echo $_REQUEST['fees_id'];?>" class="nav-tab <?php echo $active_tab == 'addfeetype' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Fees Type', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_fees_payment&tab=addfeetype" class="nav-tab <?php echo $active_tab == 'addfeetype' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.esc_attr__('Add Fee Type', 'school-mgt'); ?></a>  
        <?php } ?>
    	<a href="?page=smgt_fees_payment&tab=feespaymentlist" class="nav-tab <?php echo $active_tab == 'feespaymentlist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'.esc_attr__('Fees List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'addpaymentfee')
		{?>
       <a href="?page=smgt_fees_payment&tab=addpaymentfee&action=edit&fees_pay_id=<?php echo $_REQUEST['fees_pay_id'];?>" class="nav-tab <?php echo $active_tab == 'addpaymentfee' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Invoice', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_fees_payment&tab=addpaymentfee" class="nav-tab margin_bottom <?php echo $active_tab == 'addpaymentfee' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'.esc_attr__('Generate Invoice', 'school-mgt'); ?></a>  
        <?php } ?>      
    </h2>
    <?php
	if($active_tab == 'feeslist')
	{	
		$retrieve_class = $obj_fees->mj_smgt_get_all_fees();
	?>
<div class="panel-body">

<div class="table-responsive">
	<form id="frm-example" name="frm-example" method="post">
       <table id="feetype_list" class="display admin_feestype_datatable" cellspacing="0" width="100%">
        <thead>
            <tr>
				<th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" type="checkbox" /></th>
                <th><?php esc_attr_e('Fee Type','school-mgt');?></th>
                <th><?php esc_attr_e('Class','school-mgt');?> </th> 
                <th><?php esc_attr_e('Section','school-mgt');?> </th>
                <th><?php esc_attr_e('Amount','school-mgt');?></th>
                <th><?php esc_attr_e('Description','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?></th>
            </tr>
        </thead> 
        <tfoot>
            <tr>
				<th></th>
				<th><?php esc_attr_e('Fee Type','school-mgt');?></th>
                <th><?php esc_attr_e('Class','school-mgt');?> </th> 
				<th><?php esc_attr_e('Section','school-mgt');?> </th>
                <th><?php esc_attr_e('Amount','school-mgt');?></th>
                <th><?php esc_attr_e('Description','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?></th>
            </tr>
        </tfoot>
        <tbody>
			<?php 
				foreach ($retrieve_class as $retrieved_data)
				{ 
			?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" value="<?php echo $retrieved_data->fees_id;?>"></td>
				<td><?php echo get_the_title($retrieved_data->fees_title_id);?></td>
				<td><?php echo mj_smgt_get_class_name($retrieved_data->class_id);?></td>
				<td><?php if($retrieved_data->section_id!=0){ echo mj_smgt_get_section_name($retrieved_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?></td>				
				<td><?php echo "<span>".mj_smgt_get_currency_symbol()."</span> ".number_format($retrieved_data->fees_amount,2); ?></td>
				<td><?php echo $retrieved_data->description;?></td>              
				<td>             
					<a href="?page=smgt_fees_payment&tab=addfeetype&action=edit&fees_id=<?php echo $retrieved_data->fees_id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
					<a href="?page=smgt_fees_payment&tab=feeslist&action=delete&fees_id=<?php echo $retrieved_data->fees_id;?>" class="btn btn-danger"
					onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a>
				</td>
            </tr>
        <?php } ?>     
        </tbody>        
        </table>
		<div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected_feetype" class="btn btn-danger delete_selected"/>			
		</div>
	</form>
</div>
	</div>
     <?php 
	}
	if($active_tab == 'addfeetype')
	{
		require_once SMS_PLUGIN_DIR. '/admin/includes/feespayment/add_feetype.php';
	}
	if($active_tab == 'feespaymentlist')
	{	
		$retrieve_class = $obj_feespayment->mj_smgt_get_all_fees();	
	?>

	<div class="panel-body">
        <div class="table-responsive">
		<form id="frm-example" name="frm-example" method="post">	
        <table id="fee_paymnt" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>        
				 <th style="width: 20px;"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>        
                <th><?php esc_attr_e('Fee Type','school-mgt');?></th>  
				<th><?php esc_attr_e('Student Name','school-mgt');?></th>  
				<th><?php esc_attr_e('Roll No','school-mgt');?></th>  
                <th><?php esc_attr_e('Class','school-mgt');?> </th>  
                <th><?php esc_attr_e('Section','school-mgt');?> </th>  
				<th><?php esc_attr_e('Payment Status','school-mgt'); ?></th>
                <th><?php esc_attr_e('Amount','school-mgt');?></th>
				 <th><?php esc_attr_e('Due Amount','school-mgt');?></th>
				<th><?php esc_attr_e('Year','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?></th>                 
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
				<th><?php esc_attr_e('Fee Type','school-mgt');?></th>  
				<th><?php esc_attr_e('Student Name','school-mgt');?></th>
				<th><?php esc_attr_e('Roll No','school-mgt');?></th>  
                <th><?php esc_attr_e('Class','school-mgt');?> </th>  
				<th><?php esc_attr_e('Section','school-mgt');?> </th>  
				<th><?php esc_attr_e('Payment Status','school-mgt'); ?></th>
                <th><?php esc_attr_e('Amount','school-mgt');?></th>
				 <th><?php esc_attr_e('Due Amount','school-mgt');?></th>
				<th><?php esc_attr_e('Year','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?></th>         
            </tr>
        </tfoot>
 
        <tbody>
          <?php 			
		 	foreach ($retrieve_class as $retrieved_data){ 
			 ?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->fees_pay_id;?>"></td>
					<td><?php
					$fees_id=explode(',',$retrieved_data->fees_id);
					$fees_type=array();
					foreach($fees_id as $id)
					{ 
						$fees_type[] = mj_smgt_get_fees_term_name($id);
					}
					echo implode(" , " ,$fees_type);	
					 ?></td>
					<td><?php echo mj_smgt_get_user_name_byid($retrieved_data->student_id);?></td>
					<td><?php echo get_user_meta($retrieved_data->student_id, 'roll_id',true);?></td>
					<td><?php echo mj_smgt_get_class_name($retrieved_data->class_id);?></td>
					<td><?php if($retrieved_data->section_id!=0){ echo mj_smgt_get_section_name($retrieved_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?></td>
				<td>
					<?php 
					$smgt_get_payment_status=mj_smgt_get_payment_status($retrieved_data->fees_pay_id);
					if($smgt_get_payment_status == 'Not Paid')
					{
					 echo "<span class='btn btn-danger btn-xs'>";
					}
					elseif($smgt_get_payment_status == 'Partially Paid')
					{
						echo "<span style='background-color: rgb(50 122 183);' class='btn btn-xs'>";
					}
					else
					{
						echo "<span class='btn btn-success btn-xs'>";
					}
					 
                       echo esc_html__("$smgt_get_payment_status","school-mgt");					 
					echo "</span>";						
					?>
				</td>
				   <td><?php echo "<span> ". mj_smgt_get_currency_symbol() ." </span>" . number_format($retrieved_data->total_amount,2); ?></td>
					<?php 
					$Due_amt = $retrieved_data->total_amount-$retrieved_data->fees_paid_amount;
					$due_amount=number_format($Due_amt,2);
					?>
					 <td><?php echo "<span> ". mj_smgt_get_currency_symbol() ." </span>" .$due_amount; ?></td>
					 <td><?php echo $retrieved_data->start_year.'-'.$retrieved_data->end_year;?></td>
               <td>
			   <?php
			   
			   if($retrieved_data->fees_paid_amount < $retrieved_data->total_amount || $retrieved_data->fees_paid_amount == 0)
			    { 
			    ?>
			 		<a href="#" class="show-payment-popup btn btn-default" idtest="<?php echo $retrieved_data->fees_pay_id; ?>" view_type="payment" due_amount="<?php echo $due_amount; ?>"><?php esc_attr_e('Pay','school-mgt');?></a>
					<a  href="?page=smgt_fees_payment&tab=feespaymentlist&action=reminder&fees_pay_id=<?php echo $retrieved_data->fees_pay_id; ?> " name="fees_reminder" class="btn btn-primary"><?php esc_attr_e('Fees Reminder','school-mgt');?></a>
					<input type="hidden" name="fees_pay_id" value="<?php echo $retrieved_data->fees_pay_id; ?>"/>
				   <?php 
			    } ?>
				<a href="#" class="show-view-payment-popup btn btn-default" idtest="<?php echo $retrieved_data->fees_pay_id; ?>" view_type="view_payment"><?php esc_attr_e('View','school-mgt');?></a>
               <a href="?page=smgt_fees_payment&tab=addpaymentfee&action=edit&fees_pay_id=<?php echo $retrieved_data->fees_pay_id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
               <a href="?page=smgt_fees_payment&tab=feespaymentlist&action=delete&fees_pay_id=<?php echo $retrieved_data->fees_pay_id;?>" class="btn btn-danger"
               onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a></td>
            </tr>
            <?php } ?>     
        </tbody>       
        </table>
		 <div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected_feelist" class="btn btn-danger delete_selected"/>			
		</div>
		 <div class="print-button pull-left">
			<input id="fees_reminder" type="submit" value="<?php esc_attr_e('Fees Reminder Selected','school-mgt');?>" name="fees_reminder_feeslist" class="btn btn-primary fees_reminder"/>			
		</div>
		</form>
       </div>
	</div>
     <?php 
	}
	if($active_tab == 'addpaymentfee')
	{
		require_once SMS_PLUGIN_DIR. '/admin/includes/feespayment/add_paymentfee.php';		
	}	 
	 ?>
	</div>
	</div>
	</div>
</div>