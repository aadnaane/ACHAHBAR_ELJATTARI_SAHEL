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
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="view_popup"></div>
			<div class="invoice_data"></div>
			<div class="category_list"></div> 					
		</div>
    </div>    
</div>
<!-- End POP-UP Code -->
<?php 
	$obj_lib= new Smgtlibrary();
	//--------------Delete code-------------------------------
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		$result=$obj_lib->mj_smgt_delete_book($_REQUEST['book_id']);
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=1');
		}
	}
	if(isset($_REQUEST['delete_selected_book']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=$obj_lib->mj_smgt_delete_book($id);
		if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=1');
			}
	}
	//----------------------- ISSUE BOOK DELETE ------------------------//
	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete' && $_REQUEST['tab']=='issuelist' && isset($_REQUEST['issuebook_id']))
	{
			
		$result=$obj_lib->mj_smgt_delete_issuebook($_REQUEST['issuebook_id']);
		if($result)
		{
			wp_redirect ( home_url() . '?dashboard=user&page=library&tab=issuelist&message=2');
		}
	}
	//-------------------------- DELETE SELECTED ISSUEBOOK ---------------------//
	if(isset($_REQUEST['delete_selected_issuebook']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=$obj_lib->mj_smgt_delete_issuebook($id);
		if($result)
			{
				wp_redirect ( home_url() . '?dashboard=user&page=library&tab=issuelist&message=2');
			}
	}
	//------------------Edit-Add code ------------------------------
	if(isset($_POST['save_book']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'save_book_frontend_nonce' ) )
		{
			if($_REQUEST['action']=='edit')
			{
				$result=$obj_lib->mj_smgt_add_book($_POST);
				if($result)
				{
					wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=4');	
				}
			}
			else
			{
				$result=$obj_lib->mj_smgt_add_book($_POST);
				if($result)
				{ 
					wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=3');	
				}
			}
		}	
	}
	//--------------------------- SAVE ISSUE BOOK ----------------------//
	if(isset($_POST['save_issue_book']))
	{
		$nonce = $_POST['_wpnonce'];
		if ( wp_verify_nonce( $nonce, 'issue_book_frontend_nonce' ) )
		{
			if($_REQUEST['action']=='edit')
			{
				$result=$obj_lib->mj_smgt_add_issue_book($_POST);
				if($result)
				{ 
					wp_redirect ( home_url() . '?dashboard=user&page=library&tab=issuelist&message=5');
				}
			}
			else
			{
				$result=$obj_lib->mj_smgt_add_issue_book($_POST);
				if($result)
				{ 
					wp_redirect ( home_url() . '?dashboard=user&page=library&tab=issuelist&message=6'); 
				}
			}
		}
	}
	//------------------ SUBMIT BOOK ------------------------//
	if(isset($_POST['submit_book']))
	{
		$result=$obj_lib->mj_smgt_submit_return_book($_POST);
		if($result)
		{ ?>
			<div class="alert_msg alert alert-success alert-dismissible " role="alert">
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<?php esc_attr_e('Book Submitted Successfully','school-mgt');?>
			</div>
		<?php 
		}
	}	
	/* Save Book Import Data */

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
				wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=7'); 
			}
			if($file_size > 2097152)
			{
				$errors[]='File size limit 2 MB';
				wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=7');
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
				wp_redirect ( home_url() . '?dashboard=user&page=library&tab=booklist&message=7');
			} 
		}
	}


	$active_tab = isset($_GET['tab'])?$_GET['tab']:'memberlist';
?>
<script type="text/javascript" >
jQuery(document).ready(function($){
	"use strict";	
	jQuery('.datepicker').datepicker({		
			minDate:0
		}); 
		jQuery('#bookissue_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
		
		jQuery('#member_list').DataTable({
				responsive: true,
				"order": [[ 1, "asc" ]],
				 "aoColumns":[
					  {"bSortable": false},
					  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": true},
					  {"bSortable": false}],
				language:<?php echo mj_smgt_datatable_multi_language();?>
			});

		var table =  jQuery('#book_list').DataTable({
				responsive: true,
				"order": [[ 1, "asc" ]],				
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

		  jQuery('#book_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});


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
				}
			}
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
		   
		

	   jQuery('#issue_date').datepicker({	
				dateFormat: "yy-mm-dd",
				minDate:0,
				beforeShow: function (textbox, instance) 
				{
					instance.dpDiv.css({
						marginTop: (-textbox.offsetHeight) + 'px'                   
					});
				}
			}); 
			jQuery('#return_date').datepicker({	
				dateFormat: "yy-mm-dd",
				minDate:0,
				beforeShow: function (textbox, instance) 
				{
					instance.dpDiv.css({
						marginTop: (-textbox.offsetHeight) + 'px'                   
					});
				}  
			}); 	
			jQuery('#book_list1').multiselect({
				nonSelectedText :'<?php esc_attr_e( 'Select Book', 'school-mgt' ) ;?>',
				includeSelectAllOption: true,
				selectAllText : '<?php esc_attr_e( 'Select all', 'school-mgt' ) ;?>',
				templates: {
					button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
				}
			 });
			jQuery(".book_for_alert").on('click',function()
			{	
				checked = $(".multiselect_validation_book .dropdown-menu input:checked").length;
				if(!checked)
				{
				 alert(language_translate2.select_one_book_alert);
				  return false;
				}	
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
		
<div class="p-4 panel-body panel-white">
<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Book successfully Delete!','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Issue Book successfully Delete!','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('Record Successfully Inserted.','school-mgt');
			break;
		case '4':
			$message_string = esc_attr__('Book Updated Successfully.','school-mgt');
			break;
		case '5':
			$message_string = esc_attr__('Issue Book Updated Successfully.','school-mgt');
			break;
		case '6':
			$message_string = esc_attr__('Issue Book Added Successfully.','school-mgt');
			break;
		case '7':
			$message_string = esc_attr__('Book Uploaded Successfully.','school-mgt');
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
		<li class=nav-item">
			<a href="?dashboard=user&page=library&tab=memberlist"  class="p-2 px-3 nav-link nav-tab2 <?php echo $active_tab == 'memberlist' ? 'active' : ''; ?>">
				<i class="fa fa-align-justify"></i> <?php esc_attr_e('Member List', 'school-mgt'); ?></a>
			</a>
		</li>
		<li class=nav-item">
			<a href="?dashboard=user&page=library&tab=booklist"  class="p-2 px-3 nav-link nav-tab2 margin_bottom  <?php echo $active_tab == 'booklist' ? 'active' : ''; ?>">
				<i class="fa fa-align-justify"></i> <?php esc_attr_e('Book List', 'school-mgt'); ?></a>
			</a>
		</li>
        <?php
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'addbook')
		{
			?>
			<li class=nav-item">
				<a href="?dashboard=user&page=library&tab=addbook&action=edit&book_id=<?php echo $_REQUEST['book_id'];?>"  class="p-2 px-3 nav-link nav-tab2 <?php echo $active_tab == 'addbook' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Edit Book', 'school-mgt'); ?></a>
				</a>
			</li>
		<?php 
		}
		else
		{
			if($user_access['add']=='1')
			{
			?>
				<li class=nav-item">
					<a href="?dashboard=user&page=library&tab=addbook"  class="p-2 px-3 nav-link nav-tab2 <?php echo $active_tab == 'addbook' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Add Book', 'school-mgt'); ?></a>
					</a>
				</li>
        <?php 
			}
		} 
		if($school_obj->role=='supportstaff' || $school_obj->role=='teacher' )
		{?> 
			<li class=nav-item">
				<a href="?dashboard=user&page=library&tab=issuelist"  class="p-2 px-3 nav-link nav-tab2 <?php echo $active_tab == 'issuelist' ? 'active' : ''; ?>">
					<i class="fa fa-align-justify"></i> <?php esc_attr_e('Issue List', 'school-mgt'); ?></a>
				</a>
			</li>
        <?php
		}
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'issuebook')
		{?>
			<li class=nav-item">
				<a href="?dashboard=user&page=library&tab=issuebook&action=edit&issuebook_id=<?php echo $_REQUEST['issuebook_id'];?>"  class="p-2 px-3 nav-link nav-tab2 <?php echo $active_tab	 == 'issuebook' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Edit Issue Book', 'school-mgt'); ?></a>
				</a>
			</li>
		<?php 
		}
		else
		{
			if($user_access['add']=='1')
			{ ?>
				<li class=nav-item">
					<a href="?dashboard=user&page=library&tab=issuebook"  class="p-2 px-3 nav-link nav-tab2 margin_bottom <?php echo $active_tab	 == 'issuebook' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php esc_attr_e('Issue Book', 'school-mgt'); ?></a>
					</a>
				</li>
        <?php 
			}
		}
		?> 
    </ul>
    <div class="tab-content">
    <?php
	if($active_tab == 'booklist')
	{ ?>
		<div class="panel-body">
			<form id="frm-example" name="frm-example" method="post">
				<input type="button" value="<?php esc_html_e('Import CSV','school-mgt');?>" name="import_csv" class="btn btn-success importdata margin_bottom_15px"/> 
				<div class="table-responsive">
					<table id="book_list" class="display dataTable booklist_datatable" cellspacing="0" width="100%">
						<thead>
							<tr>
								<?php if($school_obj->role=='supportstaff'){?>
								<th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" 
								type="checkbox" /></th>
								<?php } ?>
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
								<?php if($school_obj->role=='supportstaff'){?>
								<th></th>
								<?php } ?>
								<th><?php esc_attr_e('ISBN','school-mgt');?></th>
								<th><?php esc_attr_e('Book Name','school-mgt');?></th>
								<th><?php esc_attr_e('Author Name','school-mgt');?></th>
								<th><?php esc_attr_e('Rack Location','school-mgt');?></th>
								<th><?php esc_attr_e('Quantity','school-mgt');?></th>
								<th><?php esc_attr_e('Action','school-mgt');?></th>
								 
							</tr>
						</tfoot>
						<tbody>
						 <?php 
						$user_id=get_current_user_id();
						//------- BOOK DATA FOR STUDENT ---------//
						if($school_obj->role == 'supportstaff')
						{ 
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$retrieve_books=$obj_lib->mj_smgt_get_all_books_creted_by($user_id);
							}
							else
							{
								$retrieve_books=$obj_lib->mj_smgt_get_all_books(); 
							}
						}
						else
						{
							$retrieve_books=$obj_lib->mj_smgt_get_all_books(); 
						}
						
							if(!empty($retrieve_books))
							{
								foreach ($retrieve_books as $retrieved_data){ ?>
								<tr>
								 <?php if($school_obj->role=='supportstaff'){?>
									<td><input type="checkbox" class="select-checkbox" name="id[]" 
								value="<?php echo $retrieved_data->id;?>"></td>
								<?php }?>
									<td><?php echo $retrieved_data->ISBN;?></td>
									<td><?php echo stripslashes($retrieved_data->book_name);?></td>
									<td><?php echo stripslashes($retrieved_data->author_name);?></td>
									<td><?php echo get_the_title($retrieved_data->rack_location);?></td>
									<td><?php echo $retrieved_data->quentity;?></td>
									<td> 
									<a href="#" id="<?php echo $retrieved_data->id;?>" type="booklist_view" class="view_details_popup btn btn-success"><?php esc_attr_e('View','school-mgt');?></a>
									<?php if($school_obj->role=='supportstaff')
									{ 
										if($user_access['edit']=='1')
										{?>
											<a href="?dashboard=user&page=library&tab=addbook&action=edit&book_id=<?php echo $retrieved_data->id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?> </a>
										<?php
										}
										if($user_access['delete']=='1')
										{ ?>
											<a href="?dashboard=user&page=library&tab=booklist&action=delete&book_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a> 
									<?php
										}
									}
									?>
									</td>
								</tr>
								<?php  
								}
							}?>	
					 
						</tbody>
					</table> 
				</div>
				<?php if($school_obj->role=='supportstaff')
				{
					if($user_access['delete']=='1')
					{ ?>
						<div class="print-button pull-left">
							<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected_book" class="btn btn-danger delete_selected"/>
							
						</div>
				<?php
					}
				} ?>
			</form>
        </div>
     <?php 
	}
	if($active_tab == 'addbook')
	{ ?>
		
		<?php $bookid=0;
			if(isset($_REQUEST['book_id']))
				$bookid=$_REQUEST['book_id'];
			$edit=0;
		 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			 $edit=1;
			 $result=$obj_lib->mj_smgt_get_single_books($bookid);
			 
		}?>
        <div class="panel-body">	
			<form name="book_form" action="" method="post" class="mt-3 form-horizontal" id="book_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input type="hidden" name="action" value="<?php echo $action;?>">
				<input type="hidden" name="book_id" value="<?php echo $bookid;?>">
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="isbn"><?php esc_attr_e('ISBN','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="isbn" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="50" value="<?php if($edit){ echo $result->ISBN;}?>" name="isbn">
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="category_data"><?php esc_attr_e('Book Category','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select name="bookcat_id" id="" class="form-control smgt_bookcategory validate[required]">
							<option value=""><?php esc_attr_e('Select Book Category','school-mgt');?></option>
								<?php 
								$activity_category=mj_smgt_get_all_category('smgt_bookcategory');
								if(!empty($activity_category))
								{
									if($edit)
									{
										$fees_val=$result->cat_id; 
									}
									else
									{
										$fees_val=''; 
									}
								
									foreach ($activity_category as $retrive_data)
									{ 		 	
									?>
										<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$fees_val);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>
									<?php }
								} 
								?> 
					</select>
					</div>
					<div class="col-sm-2">
						<button id="addremove_cat" class="btn btn-info margin_top_10" model="smgt_bookcategory"><?php esc_attr_e('Add','school-mgt');?></button>
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="book_name"><?php esc_attr_e('Book Name','school-mgt');?><span class="require-field"><span class="require-field">*</span></span></label>
					<div class="col-sm-8">
						<input id="book_name" class="form-control validate[required,custom[address_description_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo stripslashes($result->book_name);}?>" name="book_name">
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="author_name"><?php esc_attr_e('Author Name','school-mgt');?><span class="require-field"><span class="require-field">*</span></span></label>
					<div class="col-sm-8">
						<input id="author_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo stripslashes($result->author_name);}?>" name="author_name">
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="category_data"><?php esc_attr_e('Rack Location','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select name="rack_id" id="rack_category_data" class="form-control smgt_rack validate[required]">
							<option value=""><?php esc_attr_e('Select Book Category','school-mgt');?></option>
								<?php 
								$activity_category=mj_smgt_get_all_category('smgt_rack');
								if(!empty($activity_category))
								{
									if($edit)
									{
										$rank_val=$result->rack_location; 
									}
									else
									{
										$rank_val=''; 
									}
								
									foreach ($activity_category as $retrive_data)
									{ 		 	
									?>
										<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$rank_val);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>
									<?php }
								} 
								?> 
					</select> 
					</div>
					<div class="col-sm-2">
						<button id="addremove_cat" class="btn btn-info margin_top_10" model="smgt_rack"><?php esc_attr_e('Add','school-mgt');?></button>
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="book_price"><?php esc_attr_e('Price','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="book_price" class="form-control validate[required,min[0],maxSize[8]]" type="number" step="0.01" value="<?php if($edit){ echo $result->price;}?>" name="book_price" >
					</div>
				</div>
				<?php wp_nonce_field( 'save_book_frontend_nonce' ); ?>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="class_capacity"><?php esc_attr_e('Quantity','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="quentity" class="form-control validate[required,min[0],maxSize[5]]" type="number" value="<?php if($edit){ echo $result->quentity;}?>" name="quentity">
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="description"><?php esc_attr_e('Description','school-mgt');?></label>
					<div class="col-sm-8">
						<textarea id="description" name="description" class="form-control" ><?php if($edit){ echo $result->description;}?>  </textarea>
					</div>
				</div>
				<div class="offset-sm-2 col-sm-8">        	
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Book','school-mgt'); }else{ esc_attr_e('Add Book','school-mgt');}?>" name="save_book" class="btn btn-success" />
				</div>
			</form>
        </div>
       <?php
	}
	if($active_tab == 'issuelist')
	{ ?>
		<div class="panel-body">
			<form id="frm-example" name="frm-example" method="post">
				<div class="table-responsive">
					<table id="issue_list" class="display dataTable issuelist_datatable" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" 
								type="checkbox" /></th> 
								<th><?php esc_attr_e('Student Name','school-mgt');?></th>
								<th><?php esc_attr_e('Book Name','school-mgt');?></th>
								<th><?php esc_attr_e('Issue Date','school-mgt');?></th>
								<th><?php esc_attr_e('Return Date ','school-mgt');?></th>
								<th><?php esc_attr_e('Period','school-mgt');?></th>
								<th><?php esc_attr_e('Action','school-mgt');?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th></th>
							  <th><?php esc_attr_e('Student Name','school-mgt');?></th>
								<th><?php esc_attr_e('Book Name','school-mgt');?></th>
								<th><?php esc_attr_e('Issue Date','school-mgt');?></th>
								<th><?php esc_attr_e('Return Date ','school-mgt');?></th>
								<th><?php esc_attr_e('Period','school-mgt');?></th>
								<th><?php esc_attr_e('Action','school-mgt');?></th>
							</tr>
						</tfoot>
						<tbody>
						 <?php 
						 $user_id=get_current_user_id();
						//------- BOOK DATA FOR STUDENT ---------//
						if($school_obj->role == 'supportstaff')
						{ 
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$retrieve_issuebooks=$obj_lib->mj_smgt_get_all_issuebooks_created_by($user_id);
							}
							else
							{
								$retrieve_issuebooks=$obj_lib->mj_smgt_get_all_issuebooks(); 
							}
						}
						else
						{
							$retrieve_issuebooks=$obj_lib->mj_smgt_get_all_issuebooks(); 
						}
							if(!empty($retrieve_issuebooks))
							{
								foreach ($retrieve_issuebooks as $retrieved_data)
								{ ?>
									<tr>
										<td><input type="checkbox" class="select-checkbox" name="id[]" 
									value="<?php echo $retrieved_data->id;?>"></td>
										<td><?php  $student=get_userdata($retrieved_data->student_id);
												echo $student->display_name;?></td>
										<td><?php echo stripslashes(mj_smgt_get_bookname($retrieved_data->book_id));?></td>
										<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->issue_date);?></td>
										<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);?></td>
										<td><?php echo get_the_title($retrieved_data->period);?></td>
										<td>
										<?php
										if($user_access['edit']=='1')
										{
										?>
											<a href="?dashboard=user&page=library&tab=issuebook&action=edit&issuebook_id=<?php echo $retrieved_data->id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?> </a>
										<?php
										}
										if($user_access['delete']=='1')
										{
										?>
											<a href="?dashboard=user&page=library&tab=issuelist&action=delete&issuebook_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a> 
										<?php
										} ?>
										</td>
									</tr>
								<?php 
								} 
							}?>	
					 
						</tbody>
					</table>
				</div>
				<div class="print-button pull-left">
					<?php
					if($user_access['delete']=='1')
					{ ?>
						<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected_issuebook" class="btn btn-danger delete_selected"/>
					<?php
					}?>
				</div>
			</form>
        </div>
	<?php
	}
	if($active_tab == 'issuebook')
	{
	?>
			<?php $issuebook_id=0;
				if(isset($_REQUEST['issuebook_id']))
					$issuebook_id=$_REQUEST['issuebook_id'];
				$edit=0;
			 if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
			{
				$edit=1;
				$result=$obj_lib->mj_smgt_get_single_issuebooks($issuebook_id);
			}?>
        <div class="panel-body">	
			<form name="bookissue_form" action="" method="post" class="form-horizontal" id="bookissue_form">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input type="hidden" name="action" value="<?php echo $action;?>">
			<input type="hidden" name="issue_id" value="<?php echo $issuebook_id;?>">
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="class_id"><?php esc_attr_e('Class','school-mgt');?></label>
					<div class="col-sm-8">
					<?php if($edit){ $classval=$result->class_id; }else{$classval='';}?>
						<select name="class_id" id="class_list" class="form-control">
							<option value=""><?php esc_attr_e('Select Class','school-mgt');?></option>
									<?php
										foreach(mj_smgt_get_allclass() as $classdata)
										{ ?>
										 <option value="<?php echo $classdata['class_id'];?>" <?php selected($classval,$classdata['class_id']);?>><?php echo $classdata['class_name'];?></option>
								   <?php }?>
							 </select>
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="class_name"><?php esc_attr_e('Class Section','school-mgt');?></label>
					<div class="col-sm-8">
						<?php if($edit){ $sectionval=$result->section_id; }elseif(isset($_POST['class_section'])){$sectionval=$_POST['class_section'];}else{$sectionval='';}?>
								<select name="class_section" class="form-control" id="class_section">
									<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
									<?php
									if($edit){
										foreach(mj_smgt_get_class_sections($result->class_id) as $sectiondata)
										{  ?>
										 <option value="<?php echo $sectiondata->id;?>" <?php selected($sectionval,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
									<?php } 
									}?>
								</select>
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="student_list"><?php esc_attr_e('Student','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
							 <select name="student_id" id="student_list" class="form-control validate[required]">
							
							 <?php if(isset($result->student_id)){ 
								$student=get_userdata($result->student_id);
							?>
							 <option value="<?php echo $result->student_id;?>" ><?php echo $student->first_name." ".$student->last_name;?></option>
							 <?php }
									else
									{?>
								<option value=""><?php esc_attr_e('Select student','school-mgt');?></option>
									<?php } ?>
							 </select>
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="issue_date"><?php esc_attr_e('Issue Date','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="issue_date" class="datepicker form-control validate[required] text-input" type="text" name="issue_date" value="<?php if($edit){ echo $result->issue_date;}else{echo date('Y-m-d');}?>" readonly>
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="period"><?php esc_attr_e('Period','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select name="period_id" class="form-control period_type issue_period validate[required]">
							<option value = ""><?php esc_attr_e('Select Period','school-mgt');?></option>
							<?php 
							if($edit)
								$period_id = $result->period;
								$category_data = $obj_lib->mj_smgt_get_periodlist();
						
							if(!empty($category_data))
							{
								foreach ($category_data as $retrieved_data)
								{
									echo '<option value="'.$retrieved_data->ID.'" '.selected($period_id,$retrieved_data->ID).'>'.$retrieved_data->post_title.' '.esc_attr__("Days","school-mgt").'</option>';
								}
							}
							?>
					</select>
					</div>
					<div class="col-sm-2 mt_10_res">
						<button id="addremove_cat" class="btn btn-info" model="period_type"><?php esc_attr_e('Add','school-mgt');?></button>
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="return_date"><?php esc_attr_e('Return Date','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="return_date" class="form-control validate[required] text-input" type="text" name="return_date" value="<?php if($edit){ echo mj_smgt_getdate_in_input_box($result->end_date);}?>" readonly>
					</div>
				</div>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="category_data validate[required]"><?php esc_attr_e('Book Category','school-mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select name="bookcat_id" id="bookcat_list" class="form-control">
							<option value = ""><?php esc_attr_e('Select Category','school-mgt');?></option>
						<?php if($edit)
								$book_cat = $result->cat_id;
								$category_data = $obj_lib->mj_smgt_get_bookcat();
						
							if(!empty($category_data))
							{
								foreach ($category_data as $retrieved_data)
								{
									echo '<option value="'.$retrieved_data->ID.'" '.selected($book_cat,$retrieved_data->ID).'>'.$retrieved_data->post_title.'</option>';
								}
							}
							?>
					</select>
					</div>
				</div>
				<?php wp_nonce_field( 'issue_book_frontend_nonce' ); ?>
				<div class="mb-3 form-group row">
					<label class="col-sm-2 control-label col-form-label text-md-end" for="book_name"><?php esc_attr_e('Book Name','school-mgt');?><span class="require-field"><span class="require-field">*</span></span></label>
					<div class="col-sm-8 multiselect_validation_book">
						<?php if($edit){ $book_id=$result->book_id; }else{$book_id=0;}?>
							 <select name="book_id[]" id="book_list1" multiple="multiple" class="form-control validate[required]">
							<?php $books_data=$obj_lib->mj_smgt_get_all_books();
							 foreach($books_data as $book){?>
								  <option value="<?php echo $book->id;?>" <?php selected($book_id,$book->id);?> <?php if($books_data->quentity >= '0'){ ?> disabled <?php } ?>><?php echo stripslashes($book->book_name);?></option>
							 <?php } ?>
							 </select>
					</div>
				</div>
				<div class="offset-sm-2 col-sm-8">        	
					<input type="submit" value="<?php if($edit){ esc_attr_e('Save Issue Book','school-mgt'); }else{ esc_attr_e('Issue Book','school-mgt');}?>" name="save_issue_book" class="btn btn-success book_for_alert" />
				</div>
			</form>
        </div>
    <?php 
	}
	if($active_tab == 'memberlist')
	{ ?>
		<div class="panel-body">
			<div class="table-responsive">
				<table id="member_list" class="display dataTable member_list_datatable" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php esc_attr_e('Photo','school-mgt');?></th>
							<th><?php esc_attr_e('Student Name','school-mgt');?></th>
							<th><?php esc_attr_e('Class','school-mgt');?></th>
							<th><?php esc_attr_e('Roll No','school-mgt');?></th>
							<th><?php esc_attr_e('Student Email','school-mgt');?></th>
							<th><?php esc_attr_e('Action','school-mgt');?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php esc_attr_e('Photo','school-mgt');?></th>
							<th><?php esc_attr_e('Student Name','school-mgt');?></th>
							<th><?php esc_attr_e('Class','school-mgt');?></th>
							<th><?php esc_attr_e('Roll No','school-mgt');?></th>
							<th><?php esc_attr_e('Student Email','school-mgt');?></th>
							<th><?php esc_attr_e('Action','school-mgt');?></th>
						</tr>
					</tfoot>
					<tbody>
					 <?php
						$user_id=get_current_user_id();
						//------- MEMBER DATA FOR STUDENT ---------//
						if($school_obj->role == 'student')
						{
							$studentdata =$school_obj->student;
						}
						//------- MEMBER DATA FOR TEACHER ---------//
						elseif($school_obj->role == 'teacher')
						{
							$studentdata =$school_obj->student;
						}
						//------- EXAM DATA FOR PARENT ---------//
						elseif($school_obj->role == 'parent')
						{
							$studentdata1 =$school_obj->child_list;
							foreach($studentdata1 as $data)
							{
								$studentdata[] =get_userdata($data);
							}
						}
						//------- EXAM DATA FOR SUPPORT STAFF ---------//
						else
						{ 
							$studentdata =$school_obj->student;
						} 
						if(!empty($studentdata))
						{
							foreach ($studentdata as $retrieved_data)
							{
								$book_issued = mj_smgt_check_book_issued($retrieved_data->ID);
								if(!empty($book_issued))
								{ 	?>
									<tr>
										<td class="text-center user_image"><?php $uid=$retrieved_data->ID;
												$umetadata=mj_smgt_get_user_image($uid);
												if(empty($umetadata['meta_value']))
												{
													echo '<img src='.get_option( 'smgt_student_thumb' ).' height="50px" width="50px" class="img-circle" />';
												}
												else
												echo '<img src='.$umetadata['meta_value'].' height="50px" width="50px" class="img-circle"/>';?></td>
										<td class="name"><?php echo $retrieved_data->display_name;?></td>
										<td class="name"><?php $class_id=get_user_meta($retrieved_data->ID, 'class_name',true);
										echo $classname=mj_smgt_get_class_name($class_id);?></td>
										<td class="roll_no"><?php echo get_user_meta($retrieved_data->ID, 'roll_id',true);?></td>
										<td class="email"><?php echo $retrieved_data->user_email;?></td>
										<td>
										<?php $stud_id=get_current_user_id();
										if($school_obj->role=='student')
										{
											if($stud_id==$retrieved_data->ID){?>
										<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="view_member_bookissue_popup" class="btn btn-info"><?php esc_attr_e('View','school-mgt');?></a>
										<?php } 
										}
										else
										{ ?> 
											<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="view_member_bookissue_popup" class="btn btn-info"><?php esc_attr_e('View','school-mgt');?></a>
											<?php if($school_obj->role=='supportstaff')
											{ ?>
											<a href="?dashboard=user&page=library&tab=memberlist&member_id=<?php echo $retrieved_data->ID;?>" idtest=<?php echo $retrieved_data->ID;?> id="accept_returns_book_popup" class="btn btn-success"><?php esc_attr_e('Accept Returns','school-mgt');?> </a>
											
											<?php }
										} ?> 
										</td>
									</tr>
							<?php 
								}
							} 
						}?>	
					</tbody>
				</table>
			</div>
        </div>
	<?php
	}
	?>
	</div>
</div>
<?php ?>