<script>
jQuery(document).ready(function() {
	var table =  jQuery('#payment_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
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
				alert("<?php esc_html_e('Please select atleast one record','school-mgt');?>");
				return false;
			}
		else{
				var alert_msg=confirm("<?php esc_html_e('Are you sure you want to delete this record?','school-mgt');?>");
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
	$tablename="smgt_payment";
	$obj_invoice= new Smgtinvoice();
	
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		
		if(isset($_REQUEST['payment_id'])){
		
		$result=delete_mj_smgt_payment($tablename,$_REQUEST['payment_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=payment&message=payment_del');
			}
		}
		if(isset($_REQUEST['income_id'])){
			$result=$obj_invoice->mj_smgt_delete_income($_REQUEST['income_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=incomelist&message=income_del');
			}
		}
		if(isset($_REQUEST['expense_id'])){
			$result=$obj_invoice->mj_smgt_delete_expense($_REQUEST['expense_id']);
			if($result)
			{
				wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=expense_del');
			}
		}
	}
	
	
	if(isset($_REQUEST['delete_selected_payment']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
				$result=delete_mj_smgt_payment($tablename,$id);
		if($result)
		{ 
			wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=3');		
		}
	}
	
	
	if(isset($_REQUEST['delete_selected_income']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
				$result=$obj_invoice->mj_smgt_delete_income($id);
		if($result)
		{ 
			wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=3');		
		}
	}
	
	
	if(isset($_REQUEST['delete_selected_expense']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
				$result=$obj_invoice->mj_smgt_delete_expense($id);
		if($result)
		{ 
			wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=3');		
		}
	}
	
	//----------update and delete record---------------------
	if(isset($_POST['save_payment']))
	{
		$nonce = $_POST['_wpnonce'];
	    if ( wp_verify_nonce( $nonce, 'save_payment_admin_nonce' ) )
		{
		
			if(isset($_POST['class_section']))
			{
				$section_id=mj_smgt_onlyNumberSp_validation($_POST['class_section']);	
			}
			else
			{
				$section_id=0;
			}
			$created_date = date("Y-m-d H:i:s");
			$payment_data=array('student_id'=>mj_smgt_onlyNumberSp_validation($_POST['student_id']),
				'class_id'=>mj_smgt_onlyNumberSp_validation($_POST['class_id']),
				'section_id'=>$section_id,
				'payment_title'=>mj_smgt_popup_category_validation($_POST['payment_title']),
				'description'=>mj_smgt_address_description_validation($_POST['description']),
				'amount'=>$_POST['amount'],
				'payment_status'=>mj_smgt_onlyNumberSp_validation($_POST['payment_status']),
				'date'=>$created_date,					
				'payment_reciever_id'=>get_current_user_id(),				
				'created_by'=>get_current_user_id()					
			);
			$tablename="smgt_payment";
			if($_REQUEST['action']=='edit')
			{
				$transport_id=array('payment_id'=>$_REQUEST['payment_id']);
				$result=mj_smgt_update_record($tablename,$payment_data,$transport_id);
				if($result){ 
					wp_redirect ( admin_url().'admin.php?page=smgt_payment&tab=payment&message=2');
				 }
			}
			else
			{
				
				$result=mj_smgt_insert_record($tablename,$payment_data);
				 
				if($result)
				{ 
					wp_redirect ( admin_url().'admin.php?page=smgt_payment&tab=payment&message=1');
				 }
					
			}
	    }
	}
	
	if(isset($_POST['save_income']))
	{
        $nonce = $_POST['_wpnonce'];
	    if ( wp_verify_nonce( $nonce, 'save_income_fees_admin_nonce' ) )	
        {			
			if($_REQUEST['action']=='edit')
			{	
				$result=$obj_invoice->mj_smgt_add_income($_POST);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=incomelist&message=income_edit');
				}
			}
			else
			{
				$result=$obj_invoice->mj_smgt_add_income($_POST);			
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=incomelist&message=income_add');
				}
			}
	    }
	}
	//--------save Expense-------------
	if(isset($_POST['save_expense']))
	{
        $nonce = $_POST['_wpnonce'];
	    if ( wp_verify_nonce( $nonce, 'save_expense_fees_admin_nonce' ) )
		{			
			if($_REQUEST['action']=='edit')
			{	
				$result=$obj_invoice->mj_smgt_add_expense($_POST);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=expense_edit');
				}
			}
			else
			{
				$result=$obj_invoice->mj_smgt_add_expense($_POST);
				if($result)
				{
					wp_redirect ( admin_url() . 'admin.php?page=smgt_payment&tab=expenselist&message=expense_add');
				}
			}			
	    }	
	}
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'payment';
?>

<div class="popup-bg">
    <div class="overlay-content popup_payment">
		<div class="modal-content">
			<div class="invoice_data invoice_bg_image"></div>
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
if(isset($_REQUEST['message']))
	{
		
		$message = isset($_REQUEST['message'])?$_REQUEST['message']:0;
		$message_string = "";
		switch($message)
		{
			case '1':
				$message_string = esc_attr__('Payment added Successfully.','school-mgt');
				break;
			case '2':
				$message_string = esc_attr__('Payment Updated Successfully.','school-mgt');
				break;	
			case '3':
				$message_string = esc_attr__('Expense Delete Successfully.','school-mgt');
				break;
			case 'payment_del':
				$message_string = esc_attr__('Payment Delete Successfully.','school-mgt');
				break;
			case 'income_del':
				$message_string = esc_attr__('Income Delete Successfully.','school-mgt');
			break;
			case 'expense_del':
				$message_string = esc_attr__('Expense Delete Successfully.','school-mgt');
			break;
			case 'income_add':
				$message_string = esc_attr__('Income Added Successfully.','school-mgt');
			break;
			case 'income_edit':
				$message_string = esc_attr__('Income Updated Successfully.','school-mgt');
			break;
			case 'expense_add':
				$message_string = esc_attr__('Expense Added Successfully.','school-mgt');
			break;
			case 'expense_edit':
				$message_string = esc_attr__('Expense Updated Successfully.','school-mgt');
			break;
				
		} 
		if($message)
		{ ?>
			<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
				<p><?php echo $message_string;?></p>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
			</div>
	<?php 
		}
	} ?>
	<div class="panel panel-white">
					<div class="panel-body">     
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_payment&tab=payment" class="nav-tab <?php echo $active_tab == 'payment' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Payment List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'addpayment')
		{?>
       <a href="?page=smgt_payment&tab=addpayment&action=edit&payment_id=<?php echo $_REQUEST['payment_id'];?>" class="nav-tab <?php echo $active_tab == 'addpayment' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Payment', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_payment&tab=addpayment" class="nav-tab <?php echo $active_tab == 'addpayment' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'. esc_attr__('Add Payment', 'school-mgt'); ?></a>  
        <?php } ?>
        
       <a href="?page=smgt_payment&tab=incomelist" class="nav-tab <?php echo $active_tab == 'incomelist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span> '. esc_attr__('Income List', 'school-mgt'); ?></a>
		 <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['income_id']))
		{?>
        <a href="?page=smgt_payment&tab=addincome&action=edit&income_id=<?php echo $_REQUEST['income_id'];?>" class="nav-tab <?php echo $active_tab == 'addincome' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Income', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
			<a href="?page=smgt_payment&tab=addincome" class="nav-tab <?php echo $active_tab == 'addincome' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span> '. esc_attr__('Add Income', 'school-mgt'); ?></a>  
		<?php  }?>
		<a href="?page=smgt_payment&tab=expenselist" class="nav-tab <?php echo $active_tab == 'expenselist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span> '. esc_attr__('Expense List', 'school-mgt'); ?></a>
		 <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['expense_id']))
		{?>
        <a href="?page=smgt_payment&tab=addexpense&action=edit&expense_id=<?php echo $_REQUEST['expense_id'];?>" class="nav-tab <?php echo $active_tab == 'addexpense' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Expense', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
			<a href="?page=smgt_payment&tab=addexpense" class="nav-tab margin_bottom <?php echo $active_tab == 'addexpense' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span> '. esc_attr__('Add Expense', 'school-mgt'); ?></a>  
		<?php  }?>
    </h2>
	<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/payment.js'; ?>" ></script>

    <?php
	
	if($active_tab == 'payment')
	{	
	$retrieve_class = get_mj_smgt_payment_list();
	?>
		 <div class="panel-body">

    <div class="table-responsive">
		<form id="frm-example" name="frm-example" method="post">
        <table id="payment_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>      
				 <th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>             
                <th><?php esc_attr_e('Student Name','school-mgt');?></th>
                <th><?php esc_attr_e('Roll No.','school-mgt');?></th>
                <th><?php esc_attr_e('Class','school-mgt');?> </th>
                <th><?php esc_attr_e('Title','school-mgt');?></th>
               
                <th><?php esc_attr_e('Amount','school-mgt');?></th>
                <th><?php esc_attr_e('Status','school-mgt');?></th>
                 <th><?php esc_attr_e('Date','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?></th>             
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
            	 <th><?php esc_attr_e('Student Name','school-mgt');?></th>
             	 <th><?php esc_attr_e('Roll No.','school-mgt');?></th>
                <th><?php esc_attr_e('Class','school-mgt');?> </th>
                <th><?php esc_attr_e('Title','school-mgt');?></th>
               
                <th><?php esc_attr_e('Amount','school-mgt');?></th>
                <th><?php esc_attr_e('Status','school-mgt');?></th>
                 <th><?php esc_attr_e('Date','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?></th>   
            </tr>
        </tfoot> 
        <tbody>
          <?php			
		 	foreach ($retrieve_class as $retrieved_data){ 			
		 ?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->payment_id;?>"></td>
                <td><?php echo mj_smgt_get_user_name_byid($retrieved_data->student_id);?></td>
                <td><?php echo get_user_meta($retrieved_data->ID, 'roll_id',true);?></td>
                <td><?php echo mj_smgt_get_class_name($retrieved_data->class_id);?></td>
                <td><?php echo $retrieved_data->payment_title;?></td>               
                <td><?php echo "<span> ". mj_smgt_get_currency_symbol() ." </span>" . number_format($retrieved_data->amount,2);?></td>
                <td><?php 
					if($retrieved_data->payment_status=='Paid') 
							echo esc_attr__('Paid','school-mgt');
						elseif($retrieved_data->payment_status=='Part Paid')
							echo esc_attr__('Part Paid','school-mgt');
						else
							echo esc_attr__('Unpaid','school-mgt');	?></td>
                <td><?php  echo mj_smgt_getdate_in_input_box($retrieved_data->date);?></td>         
               <td>
               <a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->payment_id; ?>" invoice_type="invoice">
				<i class="fa fa-eye"></i> <?php esc_attr_e('View Payment', 'school-mgt');?></a>
               <a href="?page=smgt_payment&tab=addpayment&action=edit&payment_id=<?php echo $retrieved_data->payment_id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
               <a href="?page=smgt_payment&tab=payment&action=delete&payment_id=<?php echo $retrieved_data->payment_id;?>" class="btn btn-danger"
               onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a></td>
            </tr>
            <?php } ?>
     
        </tbody>
        
        </table>
		<div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected_payment" class="btn btn-danger delete_selected"/>
			
		</div>
		</form>
       </div></div>
     <?php 
	 }
	if($active_tab == 'addpayment')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/payment/add-payment.php';
		
	 }
	 if($active_tab == 'incomelist')
	 {
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/payment/income-list.php';
	 }
	 if($active_tab == 'addincome')
	 {
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/payment/add_income.php';
	 }
	 if($active_tab == 'expenselist')
	 {
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/payment/expense-list.php';
	 }
	 if($active_tab == 'addexpense')
	 {
	 	require_once SMS_PLUGIN_DIR. '/admin/includes/payment/add_expense.php';
	 }
	 ?>
	 		</div>
	 	</div>
	 </div>
</div>