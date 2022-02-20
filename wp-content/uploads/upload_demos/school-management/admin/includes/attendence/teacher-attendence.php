<?php 	
	$obj_attend=new Attendence_Manage();
	$class_id =0;
	$current_date = date("y-m-d");
?>
<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/common.js'; ?>" ></script>

<div class="panel-body"> 
	<form method="post" id="teacher_attendance">           
        <div class="row">
			<div class="form-group col-md-3">
				<label class="col-sm-2 control-label" for="curr_date"><?php esc_attr_e('Date','school-mgt');?></label>			
				<input id="curr_date_teacher" class="form-control" type="text" value="<?php if(isset($_POST['tcurr_date'])) echo $_POST['tcurr_date']; else echo  date("Y-m-d");?>" name="tcurr_date" readonly>			
			</div>
			<div class="form-group col-md-3 button-possition">
				<label for="subject_id">&nbsp;</label>
				<input type="submit" value="<?php esc_attr_e('Take/View  Attendance','school-mgt');?>" name="teacher_attendence"  class="btn btn-info"/>
			</div>
		</div>
    </form>
</div>
<div class="clearfix"> </div>
<?php 
    if(isset($_REQUEST['teacher_attendence']) || isset($_REQUEST['save_teach_attendence']))
	{	
		$attendanace_date=$_REQUEST['tcurr_date'];
		$holiday_dates=mj_smgt_get_all_date_of_holidays();
		if (in_array($attendanace_date, $holiday_dates))
		{
			?>
			<div class="alert_msg alert alert-warning alert-dismissible " role="alert">
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<?php esc_attr_e('This day is holiday you are not able to take attendance','school-mgt');?>
			</div>
		<?php 
		}
		else
		{
    ?>
	<div class="panel-body"> 
        <form method="post">        
			<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
			<input type="hidden" name="tcurr_date" value="<?php echo $_POST['tcurr_date'];?>" />
            <div class="panel-heading">
				<h4 class="panel-title"><?php esc_attr_e('Teacher Attendance','school-mgt');?> , 
				<?php esc_attr_e('Date','school-mgt')?> : <?php echo $_POST['tcurr_date'];?></h4>
			</div>
			<div class="col-md-12 padding_payment">
				<div class="table-responsive">
					<table class="table">
						<tr>
							<th><?php esc_attr_e('Srno','school-mgt');?></th>
							<th><?php esc_attr_e('Teacher','school-mgt');?></th>
							<th><?php esc_attr_e('Attendance','school-mgt');?></th>
							<th><?php esc_attr_e('Comment','school-mgt');?></th>
						</tr>
						<?php 
						$date = $_POST['tcurr_date'];
						$i=1;
						$teacher = get_users(array('role'=>'teacher'));
						foreach ($teacher as $user)
						{
							$class_id=0;
							$check_attendance = $obj_attend->mj_smgt_check_attendence($user->ID,$class_id,$date);
							
							$attendanc_status = "Present";
							if(!empty($check_attendance))
							{
								$attendanc_status = $check_attendance->status;
								 
							}
							echo '<tr>';  
							echo '<tr>';
						  
							echo '<td>'.$i.'</td>';
							echo '<td><span>' .$user->first_name.' '.$user->last_name. '</span></td>';
							?>
							<td><label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Present" <?php checked( $attendanc_status, 'Present' );?>>
							<?php esc_attr_e('Present','school-mgt');?></label>
							<label class="radio-inline"> <input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Absent" <?php checked( $attendanc_status, 'Absent' );?>>
							<?php esc_attr_e('Absent','school-mgt');?></label>
							<label class="radio-inline"><input type="radio" name = "attendanace_<?php echo $user->ID?>" value ="Late" <?php checked( $attendanc_status, 'Late' );?>>
							<?php esc_attr_e('Late','school-mgt');?></label></td>
							<td><input type="text" name="attendanace_comment_<?php echo $user->ID?>" class="form-control" 
							value="<?php if(!empty($check_attendance)) echo $check_attendance->comment;?>"></td><?php 
							
							echo '</tr>';
							$i++;
						}
						?>   
					</table>
				</div>
			</div>		
        <div class="cleatrfix"></div>
        <div class="col-sm-8">    
        	<input type="submit" value="<?php esc_attr_e("Save  Attendance","school-mgt");?>" name="save_teach_attendence" class="btn btn-success" />
        </div>       
        </form>
       </div>
       <?php }
	}	   ?>