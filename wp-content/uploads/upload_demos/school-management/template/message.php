<script type="text/javascript">
jQuery(document).ready(function($){
"use strict";	
$('#message_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
$('#selected_users').multiselect({ 
	 nonSelectedText :"<?php esc_attr_e( 'Select Users', 'school-mgt' ) ;?>",
	includeSelectAllOption: true,
	selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
	enableFiltering: true,
	enableCaseInsensitiveFiltering: true,
	templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       },            
 });
 $('#selected_class').multiselect({ 
		 nonSelectedText :"<?php esc_attr_e( 'Select Class', 'school-mgt' ) ;?>",
        includeSelectAllOption: true,
		selectAllText: '<?php esc_attr_e('Select all','school-mgt');?>',
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		templates: {
           button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       },
     });

 $("body").on("click",".save_message_selected_user",function()
	{		
		var class_selection_type = $(".class_selection_type").val();	
				
		if(class_selection_type == 'multiple')
		{
			var checked = $(".multiselect_validation1 .dropdown-menu input:checked").length;

			if(!checked)
			{
				alert(language_translate2.one_class_select_alert);
				return false;
			}	
		}			
	}); 
	jQuery("body").on("change", ".input-file[type=file]", function ()
	{ 
		"use strict";
		var file = this.files[0]; 		
		var ext = $(this).val().split('.').pop().toLowerCase(); 
		//Extension Check 
		if($.inArray(ext, [,'pdf','doc','docx','xls','xlsx','ppt','pptx','gif','png','jpg','jpeg','']) == -1)
		{
			  alert('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed. '  + ext + ' formate are not allowed.');
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />');
			return false; 
		} 
		//File Size Check 
		if (file.size > 20480000) 
		{
			alert(language_translate2.large_file_Size_alert);
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />'); 
			return false; 
		}
	});	

	$('#selected_users').multiselect({ 
			 nonSelectedText :'<?php esc_attr_e( 'Select users to reply', 'school-mgt' ) ;?>',
			 includeSelectAllOption: true,
			 templates: {
           		button: '<button class="multiselect btn btn-default dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span><b class="caret"></b></button>',
       		},
		 });
		 $("body").on("click","#check_reply_user",function()
		 {
			var checked = $(".dropdown-menu input:checked").length;

			if(!checked)
			{
				alert(language_translate2.one_user_replys_alert);
				return false;
			}		
		}); 
		$("body").on("click","#replay_message_btn",function()
		{
			$(".replay_message_div").show();	
			$(".replay_message_btn").hide();	
		});  
	jQuery('#message-replay').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	jQuery('span.timeago').timeago();
	jQuery("body").on("change", ".input-file[type=file]", function ()
	{ 
		"use strict";
		var file = this.files[0]; 		
		var ext = $(this).val().split('.').pop().toLowerCase(); 
		//Extension Check 
		if($.inArray(ext, [,'pdf','doc','docx','xls','xlsx','ppt','pptx','gif','png','jpg','jpeg']) == -1)
		{
			  alert('Only pdf,doc,docx,xls,xlsx,ppt,pptx,gif,png,jpg,jpeg formate are allowed. '  + ext + ' formate are not allowed.');
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />');
			return false; 
		} 
		//File Size Check 
		if (file.size > 20480000) 
		{
			alert(language_translate2.large_file_Size_alert);
			$(this).replaceWith('<input class="btn_top input-file" name="message_attachment[]" type="file" />'); 
			return false; 
		}
	});

	$('.multiselect-search').removeClass('form-control',0);

});

function add_new_attachment()
{
	$(".attachment_div").append('<div class="mb-3 form-group row"><label class="col-sm-2 control-label col-form-label text-md-end" for="photo"><?php esc_attr_e( 'Attachment', 'school-mgt' ) ;?></label><div class="col-sm-3"><input  class="btn_top input-file" name="message_attachment[]" type="file" /></div><div class="col-sm-2"><input type="button" value="<?php esc_attr_e( 'Delete', 'school-mgt' ) ;?>" onclick="delete_attachment(this)" class="remove_cirtificate doc_label btn btn-danger"></div></div>');
}
function delete_attachment(n)
{
	n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);				
}


function add_new_attachment2()
{
	$(".attachment_div").append('<div class="form-group row" ><label class="col-sm-2 control-label" for="photo">Attachment</label><div class="col-sm-3" style="margin-bottom: 5px;"><input  class="btn_top input-file" name="message_attachment[]" type="file" /></div><div class="col-sm-7" style="margin-bottom: 5px;"><input type="button" value="Delete" onclick="delete_attachment(this)" class="remove_cirtificate doc_label btn btn-danger"></div></div>');
}
</script>

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
$active_tab = isset($_REQUEST['tab'])?$_REQUEST['tab']:'inbox'; 
?>
<div class="row mailbox-header">
		<div class="col-md-2">
		<?php
		if($user_access['add']=='1')
		{  ?>
			<a class="btn btn-success btn-block w-100" href="?dashboard=user&page=message&tab=compose">
				<?php esc_attr_e("Compose","school-mgt");?>
			</a>
		<?php
		} ?>
		</div>
		<div class="col-md-6">
			<h2>
				<?php
				if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
				{
					echo esc_html( esc_attr__( 'Inbox', 'school-mgt' ) );
				}
				else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'sentbox')
				{
					echo esc_html( esc_attr__( 'Sent Item', 'school-mgt' ) );
				}
				else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'compose')
				{
					echo esc_html( esc_attr__( 'Compose', 'school-mgt' ) );
				}
				else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'view_message')
				{
					echo esc_html( esc_attr__( 'View Message', 'school-mgt' ) );
				}
				?>
			</h2>
		</div>
</div>
<div class="row">
	<div class="col-md-2">
		<ul class="list-unstyled mailbox-nav">
			<li <?php if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox')){?>class="active"<?php }?>>
				<a href="?dashboard=user&page=message&tab=inbox">
					<i class="fa fa-inbox"></i><?php esc_attr_e("Inbox","school-mgt");?> <span class="badge badge-success pull-right">
					<?php echo mj_smgt_count_unread_message(get_current_user_id());?></span>
				</a>
			</li>
			<li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox'){?>class="active"<?php }?>><a href="?dashboard=user&page=message&tab=sentbox"><i class="fa fa-sign-out"></i><?php esc_attr_e("Sent","school-mgt");?></a></li>
		</ul>
	</div>
	 <div class="col-md-10">
	 <?php
		 if($active_tab == 'sentbox')
			 require_once SMS_PLUGIN_DIR. '/template/sendbox.php';
		 if($active_tab == 'inbox')
			 require_once SMS_PLUGIN_DIR. '/template/inbox.php';
		 if($active_tab == 'compose')
			 require_once SMS_PLUGIN_DIR. '/template/composemail.php';
		 if($active_tab == 'view_message')
			 require_once SMS_PLUGIN_DIR. '/template/view_message.php';
		 ?>
	 </div>
</div>
<?php  ?>