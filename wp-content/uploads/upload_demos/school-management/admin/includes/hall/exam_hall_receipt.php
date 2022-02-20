<div class="panel-body">	
	<form name="exam_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data" id="exam_form">
		<div class="form-group row mb-3">
			<label for="exam_id" class="col-md-2 col-sm-2 col-xs-12 width_120 col-form-label text-md-end"><?php esc_attr_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-md-3 col-sm-3 col-xs-12">
			<?php
				$tablename="exam"; 
				$retrieve_class = mj_smgt_get_all_data($tablename);
				$exam_id="";
				if(isset($_REQUEST['exam_id']))
				{
					$exam_id=$_REQUEST['exam_id']; 
				}
				?>
				<select name="exam_id" class="form-control validate[required] exam_hall_receipt" id="exam_id">
					<option value=" "><?php esc_attr_e('Select Exam Name','school-mgt');?></option>
					<?php
					foreach($retrieve_class as $retrieved_data)
					{
						$cid=$retrieved_data->class_id;
						$clasname=mj_smgt_get_class_name($cid);
						if($retrieved_data->section_id!=0)
						{
							$section_name=mj_smgt_get_section_name($retrieved_data->section_id); 
						}
						else
						{
							$section_name=esc_attr__('No Section', 'school-mgt');
						}
					?>
						<option value="<?php echo $retrieved_data->exam_id;?>" <?php selected($retrieved_data->exam_id,$exam_id)?>><?php echo $retrieved_data->exam_name.' ( '.$clasname.' )'.' ( '.$section_name.' )';?></option>
					<?php	
					}
					?>
				</select>
			</div>
		</div>
	</form>
	
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="exam_hall_receipt_div"></div>
	</div>
	
</div> 