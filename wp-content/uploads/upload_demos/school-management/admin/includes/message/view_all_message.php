<?php
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_users_message')	
{		
	global $wpdb;
	$tablename		=	"smgt_message";
	$table_name = $wpdb->prefix . $tablename;
	
	$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE message_id= %d",$_REQUEST['users_message_id']));
	if($result)
	{	
		wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=view_all_message&message=2');
	}
}
if(isset($_POST['delete_selected']))
{		
	global $wpdb;
	$tablename		=	"smgt_message";
	$table_name = $wpdb->prefix . $tablename;
		
	if(!empty($_REQUEST['id']))
	{
		foreach($_REQUEST['id'] as $id)
		{
			$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE message_id= %d",$id));			
		}
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=view_all_message&message=2');
		}
	}
}
?>
<div class="mailbox-content">
 <script type='text/javascript' src='https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'></script>
 <script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js'></script>
 <script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js'></script>
 <script type='text/javascript' src='https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js'></script>
 <script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js'></script>
 <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>
 <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'></script>
 <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'></script>
 <script type='text/javascript' src='https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js'></script>
<div class="table-responsive">
<form id="frm-example" name="frm-example" method="post">	
        <table id="all_message_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr> 
				<th class="w-20-px"><input name="select_all" value="all" id="select_all" 
				type="checkbox" /></th> 
                <th><?php esc_attr_e('Message For','school-mgt');?></th>
                <th><?php esc_attr_e('Sender','school-mgt');?></th>
                <th><?php esc_attr_e('Receiver','school-mgt');?></th>
                <th><?php esc_attr_e('Class','school-mgt');?></th>
                <th class="width_100px"><?php esc_attr_e('Subject','school-mgt');?></th>
                <th class="width_400px"><?php esc_attr_e('Description','school-mgt');?></th>
                <th><?php esc_attr_e('Attachment','school-mgt');?></th>
                <th class="width_200px"><?php esc_attr_e('Date & Time','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?></th>               
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
                <th><?php esc_attr_e('Message For','school-mgt');?></th>
				<th><?php esc_attr_e('Sender','school-mgt');?></th>
                <th><?php esc_attr_e('Receiver','school-mgt');?></th>
                <th><?php esc_attr_e('Class','school-mgt');?></th>
                <th><?php esc_attr_e('Subject','school-mgt');?></th>
                <th><?php esc_attr_e('Description','school-mgt');?></th>
                <th><?php esc_attr_e('Attachment','school-mgt');?></th>
                <th><?php esc_attr_e('Date & Time','school-mgt');?></th>  
                <th><?php esc_attr_e('Action','school-mgt');?></th>  
            </tr>
        </tfoot>
        </table>
		<div class="form-group">		
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-0">	
				<input type="submit" class="btn delete_check delete_margin_bottom btn-danger" name="delete_selected"  value="<?php esc_html_e('Delete Selected', 'school-mgt' ) ;?> " />
			</div>
		</div>			
		</form>
</div>
</div>