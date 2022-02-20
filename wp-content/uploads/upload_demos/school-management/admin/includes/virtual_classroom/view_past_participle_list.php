<?php
$past_participle_list = $obj_virtual_classroom->mj_smgt_view_past_participle_list_in_zoom($_REQUEST['meeting_uuid']);
?>

<div class="panel-body">
	<form id="frm-example" name="frm-example" method="post">
		<div class="table-responsive">
			<table id="past_participle_list" class="display datatable" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><?php esc_attr_e('Name','school-mgt');?></th>
						<th><?php esc_attr_e('Email','school-mgt');?></th>
					</tr>
				</thead>
	 
				<tfoot>
					<tr>
						<th><?php esc_attr_e('Name','school-mgt');?></th>
						<th><?php esc_attr_e('Email','school-mgt');?></th>
					</tr>
				</tfoot>
				<tbody>
				<?php 
				if (!empty($past_participle_list->participants))
				{
					foreach($past_participle_list->participants as $retrieved_data)
					{
					?>
						<tr>
							<td><?php echo $retrieved_data->name;?></td>
							<td><?php echo $retrieved_data->user_email;?></td>
						</tr>
					<?php 
					}
				}
				?>
				</tbody>
			</table>
		</div>
	</form>
</div>