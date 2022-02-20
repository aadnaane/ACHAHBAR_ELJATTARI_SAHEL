<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
		 $('#book_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});

		 var table =  jQuery('#book_list').DataTable({
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

			 $('#book_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	$('.datepicker').datepicker({
		dateFormat: "yy-mm-dd",
		minDate:0,
		beforeShow: function (textbox, instance) 
		{
			instance.dpDiv.css({
				marginTop: (-textbox.offsetHeight) + 'px'                   
			});
		}
	}); 
	 $('#book_list1').multiselect({
			nonSelectedText :'<?php esc_attr_e( 'Select Book', 'school-mgt' ) ;?>',
			includeSelectAllOption: true,
			selectAllText : '<?php esc_attr_e( 'Select all', 'school-mgt' ) ;?>',
			templates: {
				button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
			}
		 });
	$(".book_for_alert").click(function()
	{	
		checked = $(".multiselect_validation_book .dropdown-menu input:checked").length;
		if(!checked)
		{
		 alert(language_translate2.select_one_book_alert);
		  return false;
		}	
	}); 

	var table =  jQuery('#issue_list').DataTable({
        responsive: true,
		"order": [[ 1, "asc" ]],
		dom: 'Bfrtip',
			buttons: [
				{
			extend: 'print',
			title: 'Library Issued Book List',
			exportOptions: {
                    columns: [ 0, 1, 2,3, 4 ,5]
                },
                customize: function ( win ) {
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
				},
			},
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

	 var table =  jQuery('#example123').DataTable({
		responsive: true,
		"order": [[ 1, "desc" ]],
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

	// START select student class wise
	$("body").on("change", "#class_list_lib", function(){	
		$('#class_section_lib').html('');
		$('#class_section_lib').append('<option value="remove">Loading..</option>');
		 var selection = $("#class_list_lib").val();
		 var optionval = $(this);
		var curr_data = {
			action: 'mj_smgt_load_class_section',
			class_id: selection,			
			dataType: 'json'
		};
		$.post(smgt.ajax, curr_data, function(response) 
		{
			$("#class_section_lib option[value='remove']").remove();
			$('#class_section_lib').append(response);	
		});					
					
	});

	// START select student class wise
	$("#class_section_lib").on('change',function(){
		 var selection = $(this).val();
		 if(selection != ''){
			$('#student_list').html('');
			var optionval = $(this);
			var curr_data = {
				action: 'mj_smgt_load_section_user',
				section_id: selection,			
				dataType: 'json'
			};
					
			$.post(smgt.ajax, curr_data, function(response) 
			{
				$('#student_list').append(response);	
			});
		 }
		
	});

});
</script>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="view_popup"></div>  
			<div class="invoice_data"></div>
			<div class="category_list">
			</div> 			
		</div>
    </div>    
</div>
<!-- End POP-UP Code -->
<?php 
$obj_lib= new Smgtlibrary();
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		$result=$obj_lib->mj_smgt_delete_book($_REQUEST['book_id']);
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=6'); 
		}
	}
	
	if(isset($_REQUEST['delete_selected_book']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=$obj_lib->mj_smgt_delete_book($id);
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=6'); 
		}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete' && $_REQUEST['tab']=='issuelist' && isset($_REQUEST['issuebook_id']))
	{
		$result=$obj_lib->mj_smgt_delete_issuebook($_REQUEST['issuebook_id']);
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=issuelist&message=6');
		}
	}
	if(isset($_REQUEST['delete_selected_issuebook']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=$obj_lib->mj_smgt_delete_issuebook($id);
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=issuelist&message=6');
		}
	}
	
	//upload booklist csv	
	if(isset($_REQUEST['upload_csv_file']))
	{		
		if(isset($_FILES['csv_file']))
		{				
			$errors= array();
			$file_name = $_FILES['csv_file']['name'];
			$file_size =$_FILES['csv_file']['size'];
			$file_tmp =$_FILES['csv_file']['tmp_name'];
			$file_type=$_FILES['csv_file']['type'];			
			$value = explode(".", $_FILES['csv_file']['name']);
			$file_ext = strtolower(array_pop($value));
			$extensions = array("csv");
			$upload_dir = wp_upload_dir();
			if(in_array($file_ext,$extensions )=== false)
			{
				$errors[]="this file not allowed, please choose a CSV file.";
				wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=7');
			}
			if($file_size > 2097152)
			{
				$errors[]='File size limit 2 MB';
				wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=7');
			}			
			if(empty($errors)==true)
			{	
				$rows = array_map('str_getcsv', file($file_tmp));		
				$header = array_map('strtolower',array_shift($rows));
				
				$csv = array();
				foreach ($rows as $row) 
				{
					$csv = array_combine($header, $row);
					
					if(isset($csv['isbn']))
						$bookdata['isbn']=$csv['isbn'];
					if(isset($csv['book_name']))
						$bookdata['book_name']=$csv['book_name'];
					if(isset($csv['author_name']))
						$bookdata['author_name']=$csv['author_name'];
					if(isset($csv['rack_location']))
						$bookdata['rack_location']=$csv['rack_location'];
					if(isset($csv['cat_id']))
						$bookdata['cat_id']=$csv['cat_id'];
					if(isset($csv['price']))
						$bookdata['price']=$csv['price'];							
					if(isset($csv['quentity']))
						$bookdata['quentity']=$csv['quentity'];							
					if(isset($csv['description']))
						$bookdata['description']=$csv['description'];
					$bookdata['added_by']=get_current_user_id();
					$bookdata['added_date']=date('Y-m-d');
									
					global $wpdb;
					$table_smgt_library_book = $wpdb->prefix. 'smgt_library_book';
					$all_book = $wpdb->get_results("SELECT * FROM $table_smgt_library_book");	
					$book_name=array();
					$book_isbn=array();
					
					foreach ($all_book as $book_data) 
					{
						$book_name[]=$book_data->book_name;
						$book_isbn[]=$book_data->ISBN;
					}
					
					if (in_array($bookdata['book_name'], $book_name) && in_array($bookdata['isbn'], $book_isbn))
					{
						$import_book_name=$bookdata['book_name'];
						$import_isbn=$bookdata['isbn'];
						
						$existing_book_data = $wpdb->get_row("SELECT id FROM $table_smgt_library_book where book_name='$import_book_name' AND ISBN='$import_isbn'");

						$id['id']=$existing_book_data->id;
												
						$wpdb->update( $table_smgt_library_book, $bookdata,$id);	
						
						$success = 1;	
					}
					else
					{ 	
						$wpdb->insert( $table_smgt_library_book, $bookdata );	
						$success = 1;	
					}	
				}
			}
			else
			{
				foreach($errors as &$error) echo $error;
			}
			
			if(isset($success))
			{			
				wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=7');
			} 
		}
	}

	if(isset($_POST['save_book']))
	{   
        $nonce = $_POST['_wpnonce'];
	    if ( wp_verify_nonce( $nonce, 'save_book_admin_nonce' ) )
		{
			if($_REQUEST['action']=='edit')
			{		
				$result=$obj_lib->mj_smgt_add_book($_POST);
				if($result)
				{ 
				wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=1');
				 }		
			}
			else
			{
				$result=$obj_lib->mj_smgt_add_book($_POST);
				if($result)
				{ 
				 wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=booklist&message=2');
				 }				
			}
	    }		
	}
	if(isset($_POST['save_issue_book']))
	{
		/* var_dump($_POST);
		die; */
		$nonce = $_POST['_wpnonce'];
	/* 	global $wpdb;
		$issue_date=$_POST['issue_date'];
		$return_date=$_POST['return_date'];
		$student_id=$_POST['student_id'];
		//$book_id=$_POST['book_id'];
		$table_issue	=	$wpdb->prefix.'smgt_library_book_issue';
		$booking_data=array();
		
		foreach($_POST['book_id'] as $book)
		{
			$booking_data[] = $wpdb->get_row("SELECT * FROM $table_issue WHERE issue_date BETWEEN '$issue_date' AND '$return_date' 
            AND end_date BETWEEN '$issue_date' AND '$return_date' AND student_id=$student_id AND book_id=$book");
		} */
		
	    if ( wp_verify_nonce( $nonce, 'save_issuebook_admin_nonce' ) )
		{
			if($_REQUEST['action']=='edit')
			{		
				$result=$obj_lib->mj_smgt_add_issue_book($_POST);
				if($result)
				{ 
					if(isset($_REQUEST['smgt_issue_book_mail_service_enable']))
					{
						foreach($_REQUEST['book_id'] as $book_id)
						{
							$smgt_issue_book_mail_service_enable = $_REQUEST['smgt_issue_book_mail_service_enable'];
							if($smgt_issue_book_mail_service_enable)
							{	
								$search['{{student_name}}']	 	= 	mj_smgt_get_teacher($_POST['student_id']);
								$search['{{book_name}}'] 	    = 	mj_smgt_get_bookname($book_id);						
								$search['{{school_name}}'] 		= 	get_option('smgt_school_name');								
								$message = mj_smgt_string_replacement($search,get_option('issue_book_mailcontent'));
								$mail_id=mj_smgt_get_emailid_byuser_id($_POST['student_id']);	
								if(get_option('smgt_mail_notification') == '1')
								{
									wp_mail($mail_id,get_option('issue_book_title'),$message);
								}	
							}
						}
					}

					wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=issuelist&message=3');
				}			
			}
			else
			{
				$result=$obj_lib->mj_smgt_add_issue_book($_POST);
				if($result)
				{ 
					if(isset($_POST['smgt_issue_book_mail_service_enable']))
					{
						foreach($_POST['book_id'] as $book_id)
						{
							$smgt_issue_book_mail_service_enable = $_POST['smgt_issue_book_mail_service_enable'];
							if($smgt_issue_book_mail_service_enable)
							{	
								$search['{{student_name}}']	 	= 	mj_smgt_get_teacher($_POST['student_id']);
								$search['{{book_name}}'] 	    = 	mj_smgt_get_bookname($book_id);						
								$search['{{school_name}}'] 		= 	get_option('smgt_school_name');								
								$message = mj_smgt_string_replacement($search,get_option('issue_book_mailcontent'));
								$mail_id=mj_smgt_get_emailid_byuser_id($_POST['student_id']);		
								if(get_option('smgt_mail_notification') == '1')
								{
									wp_mail($mail_id,get_option('issue_book_title'),$message);
								}	
							}
						}
					}

					wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=issuelist&message=4');
				}				
			}
	    }		
	}

	if(isset($_POST['submit_book']) && isset($_POST['books_return']))
	{
		$result=$obj_lib->mj_smgt_submit_return_book($_POST);
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_library&tab=issuelist&message=5');
		}
	}
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'memberlist';
?>

<div class="page-inner">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div id="main-wrapper">
<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Book Updated Successfully.','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Book Added Successfully.','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('Issue Book Record Updated Successfully.','school-mgt');
			break;	
		case '4':
			$message_string = esc_attr__('Book Issued Successfully.','school-mgt');
			break;	
		case '5':
			$message_string = esc_attr__('Book Submitted Successfully.','school-mgt');
			break;	
		case '6':
			$message_string = esc_attr__('Issue Book Deleted Successfully.','school-mgt');
			break;
		case '7':
			$message_string = esc_attr__('Book Uploaded Successfully.','school-mgt');
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
		<a href="?page=smgt_library&tab=memberlist" class="nav-tab <?php echo $active_tab =='memberlist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>',esc_attr__('Member List', 'school-mgt'); ?></a>
    	<a href="?page=smgt_library&tab=booklist" class="nav-tab <?php echo $active_tab == 'booklist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>',esc_attr__('Book List', 'school-mgt'); ?></a>
        
		
		<?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['book_id']) )
		{?>
         <a href="?page=smgt_library&tab=addbook&action=edit&issuebook_id=<?php echo $_REQUEST['book_id'];?>" class="nav-tab <?php echo $active_tab == 'addbook' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Book', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_library&tab=addbook" class="nav-tab <?php echo $active_tab == 'addbook' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'. esc_attr__('Add Book', 'school-mgt'); ?></a> 
        <?php }
		?> 
        <a href="?page=smgt_library&tab=issuelist" class="nav-tab <?php echo $active_tab == 'issuelist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>',esc_attr__('Issue List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'issuebook' )
		{ ?>
         <a href="?page=smgt_library&tab=issuebook&action=edit&issuebook_id=<?php echo $_REQUEST['issuebook_id'];?>" class="nav-tab <?php echo $active_tab == 'issuebook' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Issue Book', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{ ?>
    	<a href="?page=smgt_library&tab=issuebook" class="nav-tab margin_bottom <?php echo $active_tab == 'issuebook' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'. esc_attr__('Issue Book', 'school-mgt'); ?></a> 
        <?php } ?> 
    </h2>
    
    <?php
	if($active_tab == 'booklist')
	{?>
<div class="panel-body">
	<div class="table-responsive">
		<form id="frm-example" name="frm-example" method="post">
			<input type="button" value="<?php esc_html_e('Import CSV','hospital_mgt');?>" name="import_csv" class="btn btn-success importdata margin_bottom_15px"/> 
			<table id="book_list" class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" 	type="checkbox" /></th>
					<th><?php esc_attr_e('ISBN','school-mgt');?></th>
					<th><?php esc_attr_e('Book Name','school-mgt');?></th>
					<th><?php esc_attr_e('Author Name','school-mgt');?></th>
					<th><?php esc_attr_e('Rack Location','school-mgt');?></th>
					<th><?php esc_attr_e('Quantity','school-mgt');?></th>
					<th><?php esc_attr_e('Action','school-mgt');?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th></th>
					<th><?php esc_attr_e('ISBN','school-mgt');?></th>
					<th><?php esc_attr_e('Book Name','school-mgt');?></th>
					<th><?php esc_attr_e('Author Name','school-mgt');?></th>
					<th><?php esc_attr_e('Rack Location','school-mgt');?></th>
					<th><?php esc_attr_e('Quantity','school-mgt');?></th>
					<th><?php esc_attr_e('Action','school-mgt');?></th>
				</tr>
			</tfoot>
			<tbody>
			 <?php $retrieve_books=$obj_lib->mj_smgt_get_all_books(); 
				if(!empty($retrieve_books))
				{
					foreach ($retrieve_books as $retrieved_data){ ?>
					<tr>
						<td><input type="checkbox" class="select-checkbox" name="id[]"	value="<?php echo $retrieved_data->id;?>"></td>
						<td><?php echo $retrieved_data->ISBN;?></td>
						<td><?php echo stripslashes($retrieved_data->book_name);?></td>
						<td><?php echo stripslashes($retrieved_data->author_name);?></td>
						<td><?php echo get_the_title($retrieved_data->rack_location);?></td>
						<td><?php echo $retrieved_data->quentity;?></td>
						<td> 
						<a href="#" id="<?php echo $retrieved_data->id;?>" type="booklist_view" class="view_details_popup btn btn-success"><?php esc_attr_e('View','school-mgt');?></a>
						<a href="?page=smgt_library&tab=addbook&action=edit&book_id=<?php echo $retrieved_data->id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?> </a>
						<a href="?page=smgt_library&tab=booklist&action=delete&book_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a> 
						</td>			   
					</tr>
					<?php } 
				} ?>	
		 
			</tbody>        
			</table>
			<div class="print-button pull-left">
				<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected_book" class="btn btn-danger delete_selected"/>			
			</div>
		</form>
</div>
  
    <?php 
	}
	if($active_tab == 'addbook')
	{
		require_once SMS_PLUGIN_DIR. '/admin/includes/library/add-newbook.php';
	}
	if($active_tab == 'issuelist')
	{
		require_once SMS_PLUGIN_DIR. '/admin/includes/library/issuelist.php';
	}
	if($active_tab == 'issuebook')
	{
		require_once SMS_PLUGIN_DIR. '/admin/includes/library/issue-book.php';
	}
	if($active_tab == 'memberlist')
	{
		require_once SMS_PLUGIN_DIR. '/admin/includes/library/memberlist.php';
	}
	 ?>	 
	 </div>
	 </div>
</div>
</div>