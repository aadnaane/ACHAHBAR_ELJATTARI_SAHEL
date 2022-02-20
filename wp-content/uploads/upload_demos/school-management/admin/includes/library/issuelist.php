<?php 
$obj_lib= new Smgtlibrary();
if($active_tab == 'issuelist')
{ 
?>
<div class="panel-body">
<div class="table-responsive">
	<form id="frm-example" name="frm-example" method="post">
		<table id="issue_list" class="display" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" type="checkbox" /></th>  
				<th><?php esc_attr_e('Student Name','school-mgt');?></th>
				<th><?php esc_attr_e('Book Name','school-mgt');?></th>
				<th><?php esc_attr_e('Issue Date','school-mgt');?></th>
				<th><?php esc_attr_e('Return Date ','school-mgt');?></th>
				<th><?php esc_attr_e('Period','school-mgt');?></th>
				<th class="action_print"><?php esc_attr_e('Action','school-mgt');?></th>
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
				<th class="action_print"><?php esc_attr_e('Action','school-mgt');?></th>
			</tr>
		</tfoot>
		<tbody>
			<?php $retrieve_issuebooks=$obj_lib->mj_smgt_get_all_issuebooks(); 
			if(!empty($retrieve_issuebooks))
			{
				foreach ($retrieve_issuebooks as $retrieved_data){ ?>
				<tr>
					<td><input type="checkbox" class="select-checkbox" name="id[]" value="<?php echo $retrieved_data->id;?>"></td>
					<td><?php  $student=get_userdata($retrieved_data->student_id);
						echo $student->display_name;?></td>
					<td><?php echo stripslashes(mj_smgt_get_bookname($retrieved_data->book_id));?></td>
					<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->issue_date);?></td>
					<td><?php echo mj_smgt_getdate_in_input_box($retrieved_data->end_date);?></td>
					<td><?php echo get_the_title($retrieved_data->period);?></td>
					<td> <a href="?page=smgt_library&tab=issuebook&action=edit&issuebook_id=<?php echo $retrieved_data->id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?> </a>
					<a href="?page=smgt_library&tab=issuelist&action=delete&issuebook_id=<?php echo $retrieved_data->id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"> <?php esc_attr_e('Delete','school-mgt');?></a> 
					</td>
				</tr>
			<?php } 
			} ?>	
		</tbody>
	</table>
		<div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected_issuebook" class="btn btn-danger delete_selected"/>
		</div>
	</form>
</div>    
<?php } ?>