<?php
if($active_tab == 'exam_time_table')
{
?>
    <div class="panel-body">	
        <form name="exam_form" action="" method="post" class="mb-3 form-horizontal" enctype="multipart/form-data" id="exam_form">
			<div class="form-group row">
				<label for="exam_id" class="col-md-2 col-sm-2 col-xs-12 width_120 col-form-label"><?php esc_attr_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
				<div class="col-md-4 col-sm-4 col-xs-12">
				<?php
					$tablename="exam"; 
					$retrieve_class = mj_smgt_get_all_data($tablename);
					$exam_id="";
					if(isset($_REQUEST['exam_id']))
					{
						$exam_id=$_REQUEST['exam_id']; 
					}
					?>
					<select name="exam_id" class="form-control validate[required] width_100">
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
							<option value="<?php echo $retrieved_data->exam_id;?>" <?php selected($retrieved_data->exam_id,$exam_id)?>><?php echo $retrieved_data->exam_name.' ( '.$clasname.' )'.' ( '.esc_attr__("$section_name","school-mgt").' )';?></option>
						<?php	
						}
						?>
					</select>
				</div>
				<div class="col-md-2 col-sm-2 col-xs-12">        	
					<input type="submit" id="save_exam_time_table" value="<?php  esc_attr_e('Manage Exam Time','school-mgt');?>" name="save_exam_time_table" class="btn btn-success" />
				</div>   
			</div>
        </form>
  <?php
	if(isset($_POST['save_exam_time_table']))
	{
		$exam_data= mj_smgt_get_exam_by_id($_POST['exam_id']);
		$school_obj= new School_Management;
		if($exam_data->section_id != 0)
		{
			$subject_data=$school_obj->mj_smgt_subject_list_with_calss_and_section($exam_data->class_id,$exam_data->section_id);
		}
		else
		{
			$subject_data=$school_obj->mj_smgt_subject_list($exam_data->class_id);
		}
		$start_date=$exam_data->exam_start_date;
		$end_date=$exam_data->exam_end_date;
		 
		?>
			<input type="hidden" id="start" value="<?php echo date("Y-m-d",strtotime($start_date));?>">
			<input type="hidden" id="end" value="<?php echo date("Y-m-d",strtotime($end_date));?>">
				<div class="form-group">
					<div class="col-md-12">
						<div class="row">
							<table class="table width_200" style="border: 1px solid #000000;text-align: center;margin-bottom: 0px;border-collapse: separate;">
								<thead>
									<tr>
										<th  style="border-top: medium none;border-right: 1px solid #000000;background-color: #e5e5e5;border-bottom: 1px solid #000000;text-align: center;"><?php  esc_attr_e('Exam','school-mgt');?></th>
										<th  style="border-right: 1px solid #000000;background-color: #e5e5e5;border-bottom: 1px solid #000000;text-align: center;"><?php  esc_attr_e('Class','school-mgt');?></th>							
										<th  style="border-right: 1px solid #000000;background-color: #e5e5e5;border-bottom: 1px solid #000000;text-align: center;"><?php  esc_attr_e('Section','school-mgt');?></th>							
										<th  style="border-right: 1px solid #000000;background-color: #e5e5e5;border-bottom: 1px solid #000000;text-align: center;"><?php  esc_attr_e('Term','school-mgt');?></th>							
										<th  style="border-right: 1px solid #000000;background-color: #e5e5e5;border-bottom: 1px solid #000000;text-align: center;"><?php  esc_attr_e('Start Date','school-mgt');?></th>							
										<th  style="background-color: #e5e5e5;border-bottom: 1px solid #000000;text-align: center;"><?php  esc_attr_e('End Date','school-mgt');?></th>							
									</tr>
								</thead>
								<tfoot></tfoot>
								<tbody>							
									<tr>
										<td style="border-right: 1px solid #000000;"><?php echo $exam_data->exam_name;?></td>							
										<td style="border-right: 1px solid #000000;"><?php echo mj_smgt_get_class_name($exam_data->class_id);?></td>
										<td style="border-right: 1px solid #000000;"><?php if($exam_data->section_id!=0){ echo mj_smgt_get_section_name($exam_data->section_id); }else { esc_attr_e('No Section','school-mgt');}?></td>
										<td style="border-right: 1px solid #000000;"><?php echo get_the_title($exam_data->exam_term);?></td>
										<td style="border-right: 1px solid #000000;"><?php echo mj_smgt_getdate_in_input_box($start_date);?></td>
										<td style=""><?php echo mj_smgt_getdate_in_input_box($end_date);?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>			
				</div>
				<?php
				if(isset($subject_data))
				{
				?>
				<div class="col-md-12 margin_top_40">
					<div class="row">
						<form id="exam_form2" name="exam_form2" method="post">	
						<input type='hidden' name='subject_data' id="subject_data" value='<?php echo json_encode($subject_data);?>'>
						<input type="hidden" name="class_id" value="<?php echo $exam_data->class_id;?>">
						<input type="hidden" name="section_id" value="<?php echo $exam_data->section_id;?>">
						<input type="hidden" name="exam_id" value="<?php echo $exam_data->exam_id;?>">
							<div class="table-responsive">
								<table id="exam_timelist" style="border: 1px solid #000000;text-align: center;margin-bottom: 0px;border-collapse: separate;" class="cell-border exam_timelist_admin" >
									<thead>
										<tr>    
											<th style="border-top: medium none;border-right: 1px solid #000000;background-color: #e5e5e5;border-bottom: 1px solid #000000;text-align: center;"><?php esc_attr_e('Subject Code','school-mgt');?></th>
											<th style="border-right: 1px solid #000000;background-color: #e5e5e5;border-bottom: 1px solid #000000;text-align: center;"><?php esc_attr_e('Subject Name','school-mgt');?></th>
											<th style="border-right: 1px solid #000000;background-color: #e5e5e5;border-bottom: 1px solid #000000;text-align: center;"><?php esc_attr_e('Exam Date','school-mgt');?></th>
											<th style="border-right: 1px solid #000000;background-color: #e5e5e5;border-bottom: 1px solid #000000;text-align: center;"><?php esc_attr_e('Exam Start Time','school-mgt');?></th>
											<th  style="background-color: #e5e5e5;border-bottom: 1px solid #000000;text-align: center;"><?php esc_attr_e('Exam End Time','school-mgt');?></th>
										</tr>
									</thead>
								<tbody>
								  <?php 
								  $obj_exam=new smgt_exam;
								  $i = 1;
									foreach ($subject_data as $retrieved_data) { 
									//------- View Exam Time Table Data ------------//
									$exam_time_table_data=$obj_exam->mj_smgt_check_exam_time_table($exam_data->class_id,$exam_data->exam_id,$retrieved_data->subid);
								 ?>
									<script>
										$(document).ready(function(){
											var start = $( "#start" ).val();
											var end = $( "#end" ).val();
											$(".exam_date").datepicker({ 
												minDate: start,
												maxDate: end,
												dateFormat: "yy-mm-dd",
												//console.log(minDate),
											});  
										});
									</script>
									<tr>
										<input type="hidden" name="subject_id" value="<?php echo $retrieved_data->subid;?>">
										<td class="" style="border-right: 1px solid #000000;" ><input type="hidden" name = "subject_code_<?php echo $retrieved_data->subid;?>"  value="<?php echo $retrieved_data->subject_code;?>"><?php echo $retrieved_data->subject_code;?></td>
										<td style="border-right: 1px solid #000000;"><input type="hidden" name = "subject_name_<?php echo $retrieved_data->subid;?>" value="<?php echo $retrieved_data->sub_name;?>"><?php echo $retrieved_data->sub_name;?></td>
										<td style="border-right: 1px solid #000000; position: relative;">
											<input id="exam_date_<?php echo $retrieved_data->subid; ?>" class="datepicker form-control validate[required] text-input exam_date width_165" type="text" name="exam_date_<?php echo $retrieved_data->subid; ?>" value="<?php if(!empty($exam_time_table_data->exam_date)) { echo mj_smgt_getdate_in_input_box($exam_time_table_data->exam_date); } ?>" readonly>
										</td>
										<td style="border-right: 1px solid #000000;">
											<div class="col-md-12 col-sm-12 col-xs-12"><div class="row">
												<div class="col-md-4 col-sm-12 col-xs-12">
													<?php
													if(!empty($exam_time_table_data->start_time))
													{													
														$start_time_data = explode(":", $exam_time_table_data->start_time);
													}
													?>
													<select id="start_time_<?php echo $retrieved_data->subid; ?>" name="start_time_<?php echo $retrieved_data->subid; ?>" class="form-control validate[required] width_60_responsive">
													<?php 
													for($i =0 ; $i <= 12 ; $i++)
													{
													?>
														<option value="<?php echo $i;?>" <?php  if(!empty($start_time_data))
														{ selected($start_time_data[0],$i);  } ?>><?php echo $i;?></option>
													<?php
													}
													?>
													</select>
												</div>
												<div class="col-md-4 col-sm-12 col-xs-12">
													 <select  id="start_min_<?php echo $retrieved_data->subid; ?>" name="start_min_<?php echo $retrieved_data->subid; ?>" class="form-control validate[required] width_60_responsive">
													 <?php 
														for($i =0 ; $i <= 59 ; $i++)
														{
														?>
														<option value="<?php echo $i;?>" <?php   if(!empty($start_time_data))
														{ selected($start_time_data[1],$i);  } ?>><?php echo $i;?></option>
														<?php
														}
													 ?>
													 </select>
												</div>
												<div class="col-md-4 col-sm-12 col-xs-12">
													 <select  id="start_ampm_<?php echo $retrieved_data->subid?>" name="start_ampm_<?php echo $retrieved_data->subid?>" class="form-control validate[required] width_60_responsive">
														<option value="am" <?php if(isset($start_time_data[2])) selected($start_time_data[2],'am');  ?>><?php esc_attr_e('A.M.','school-mgt');?></option>
														<option value="pm" <?php  if(isset($start_time_data[2])) selected($start_time_data[2],'pm');  ?>><?php esc_attr_e('P.M.','school-mgt');?></option>
													 </select>
												</div>
											</div>
											</div>
										</td>
										<td>
											<div class="col-md-12 col-sm-12 col-xs-12"><div class="row">
												<div class="col-md-4 col-sm-12 col-xs-12">
													<?php
													if(!empty($exam_time_table_data->end_time))
													{													
														$end_time_data = explode(":", $exam_time_table_data->end_time);
													}
													?>
													 
													<select id="end_time_<?php echo $retrieved_data->subid; ?>" name="end_time_<?php echo $retrieved_data->subid; ?>" class="form-control validate[required] width_60_responsive">
													<?php 
													for($i =0 ; $i <= 12 ; $i++)
													{
													?>
														<option value="<?php echo $i;?>" <?php  if(!empty($end_time_data))
														{  selected($end_time_data[0],$i); } ?>><?php echo $i;?></option>
													<?php
													}
													?>
													</select>
												</div>
												<div class="col-md-4 col-sm-12 col-xs-12">
													 <select id="end_min_<?php echo $retrieved_data->subid; ?>" name="end_min_<?php echo $retrieved_data->subid; ?>" class="form-control validate[required] width_60_responsive">
													 <?php 
														for($i =0 ; $i <= 59 ; $i++)
														{
														?>
														<option value="<?php echo $i;?>" <?php if(!empty($end_time_data))
														{ selected($end_time_data[1],$i); } ?>><?php echo $i;?></option>
														<?php
														}
													 ?>
													 </select>
												</div>
												<div class="col-md-4 col-sm-12 col-xs-12">
													 <select  id="end_ampm_<?php echo $retrieved_data->subid; ?>" name="end_ampm_<?php echo $retrieved_data->subid;?>" class="form-control validate[required] width_60_responsive">
														<option value="am" <?php  if(isset($end_time_data[2])) selected($end_time_data[2],'am');  ?>><?php esc_attr_e('A.M.','school-mgt');?></option>
														<option value="pm" <?php  if(isset($end_time_data[2])) selected($end_time_data[2],'pm');  ?>><?php esc_attr_e('P.M.','school-mgt');?></option>
													 </select>
												</div>
											</div>
											</div>
										</td>
										 
									</tr>
									<?php 
									$i++;
									} ?>
								</tbody>
								</table>
								
							</div>
							<?php
							if(!empty($subject_data))
							{
							?>
								<div class="print-button pull-left">
									<input type="submit" id="save_exam_time" value="<?php  esc_attr_e('Save Time Table','school-mgt');?>" name="save_exam_table" class="btn btn-success" />
								</div>
							<?php
							}
							?>
						</form>
					</div>
				</div>
				<?php
				}
				?>
	<?php 
	}
}
?>