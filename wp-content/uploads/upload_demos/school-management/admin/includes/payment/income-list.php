<?php 
$obj_invoice= new Smgtinvoice();
if($active_tab == 'incomelist')
{ ?>
<script type="text/javascript">
$(document).ready(function() {
	var table = jQuery('#tblincome_admin').DataTable({
	responsive: true,
		"order": [[ 4, "Desc" ]],
		"aoColumns":[
			{"bSortable": false},
	        {"bSortable": true},
	        {"bSortable": true},
	        {"bSortable": true},
	        {"bSortable": true}, 
	        {"bSortable": false}
	    ],
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

     <div class="panel-body">
        	<div class="table-responsive">
			<form id="frm-example" name="frm-example" method="post">
        <table id="tblincome_admin" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
				<th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>  
				<th> <?php esc_attr_e( 'Roll No.', 'school-mgt' ) ;?></th>
				<th> <?php esc_attr_e( 'Student Name', 'school-mgt' ) ;?></th>
				<th> <?php esc_attr_e( 'Amount', 'school-mgt' ) ;?></th>
				<th> <?php esc_attr_e( 'Date', 'school-mgt' ) ;?></th>
                <th><?php  esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
				<th></th>
				<th> <?php esc_attr_e( 'Roll No.', 'school-mgt' ) ;?></th>
				<th> <?php esc_attr_e( 'Student Name', 'school-mgt' ) ;?></th>
				<th> <?php esc_attr_e( 'Amount', 'school-mgt' ) ;?></th>
				<th> <?php esc_attr_e( 'Date', 'school-mgt' ) ;?></th>
                <th><?php  esc_attr_e( 'Action', 'school-mgt' ) ;?></th>
            </tr>
        </tfoot>
 
        <tbody>
         <?php 
		
		 	foreach ($obj_invoice->mj_smgt_get_all_income_data() as $retrieved_data){ 
				$all_entry=json_decode($retrieved_data->entry);
				$total_amount=0;
				foreach($all_entry as $entry){
					$total_amount+=$entry->amount;
				}
		 ?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->income_id;?>"></td>
				<td class="patient"><?php echo get_user_meta($retrieved_data->supplier_name, 'roll_id',true);?></td>
				<td class="patient_name"><?php echo mj_smgt_get_user_name_byid($retrieved_data->supplier_name);?></td>
				<td class="income_amount"><?php echo "<span> ". mj_smgt_get_currency_symbol() ." </span>" .number_format($total_amount,2);?></td>
                <td class="status"><?php echo mj_smgt_getdate_in_input_box($retrieved_data->income_create_date);?></td>
                
               	<td class="action">
				<a  href="#" class="show-invoice-popup btn btn-default" idtest="<?php echo $retrieved_data->income_id; ?>" invoice_type="income">
				<i class="fa fa-eye"></i> <?php esc_attr_e('View Income', 'school-mgt');?></a>
				<a href="?page=smgt_payment&tab=addincome&action=edit&income_id=<?php echo $retrieved_data->income_id;?>" class="btn btn-info"> <?php esc_attr_e('Edit', 'school-mgt' ) ;?></a>
                <a href="?page=smgt_payment&tab=incomelist&action=delete&income_id=<?php echo $retrieved_data->income_id;?>" class="btn btn-danger" 
                onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');">
                <?php esc_attr_e( 'Delete', 'school-mgt' ) ;?> </a>
                </td>
            </tr>
            <?php } ?>     
        </tbody>        
        </table>
		<div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected_income" class="btn btn-danger delete_selected"/>			
		</div>
		</form>
        </div>
    </div>
	 <?php  } ?>