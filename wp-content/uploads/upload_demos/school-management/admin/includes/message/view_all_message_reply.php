<script type="text/javascript">
jQuery(document).ready(function($)
{
	var table =jQuery('#view_all_message_reply_list').DataTable({		
		dom: 'Bfrtip',
         buttons: [
			{
                extend: 'print',
                text:'Print',
				title: 'Message Reply Data',
				exportOptions: {
                    columns: [1,2,3,5]
                }
            }
        ],  
		 "bProcessing": true,
		 "bServerSide": true,
		 "sAjaxSource": ajaxurl+'?action=mj_smgt_view_all_relpy',
		 "bDeferRender": true, 		
		responsive: true,
		"order": [[ 1, "asc" ]],
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
		
		table.on('page.dt', function() {
		  $('html, body').animate({
			scrollTop: $(".dataTables_wrapper").offset().top
		   }, 'slow');
		});
	
});

</script>	
<?php
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_users_reply_message')	
{		
	global $wpdb;
	$tablename		=	"smgt_message_replies";
	$table_name = $wpdb->prefix . $tablename;
	$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id= %d",$_REQUEST['users_reply_message_id']));
	
	if($result)
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=view_all_message_reply&message=2');
	}
}
if(isset($_POST['delete_selected']))
{		
	global $wpdb;
	$tablename		=	"smgt_message_replies";
	$table_name = $wpdb->prefix . $tablename;
	
	if(!empty($_REQUEST['id']))
	{
		foreach($_REQUEST['id'] as $id)
		{
			$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id= %d",$id));
		}
		if($result)
		{ 
			wp_redirect ( admin_url().'admin.php?page=smgt_message&tab=view_all_message_reply&message=2');
		}
	}
}
?>
<div class="mailbox-content">
 <script type='text/javascript' src='https://code.jquery.com/jquery-3.3.1.js'></script>
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
	
<form id="frm-example1" name="frm-example1" method="post">	
        <table id="view_all_message_reply_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr> 
				<th class="w-20-px"><input name="select_all" value="all" id="select_all" 
				type="checkbox" /></th>                 
                <th><?php esc_attr_e('Sender','school-mgt');?></th>
                <th><?php esc_attr_e('Receiver','school-mgt');?></th>                
                <th><?php esc_attr_e('Description','school-mgt');?></th>                                            
                <th><?php esc_attr_e('Attachment','school-mgt');?></th>               
                <th><?php esc_attr_e('Date & Time','school-mgt');?></th>               
                <th><?php esc_attr_e('Action','school-mgt');?></th>               
            </tr>
        </thead>
        <tfoot>
            <tr>
				<th></th>
                <th><?php esc_attr_e('Sender','school-mgt');?></th>
                <th><?php esc_attr_e('Receiver','school-mgt');?></th>                
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