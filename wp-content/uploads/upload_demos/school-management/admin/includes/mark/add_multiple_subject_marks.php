<div class="mt-4 panel-body p"> 
    <form method="post" id="select_data">  
    <div class="row">
		
		<div class="form-group col-md-3">
			<label for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
				<?php $class_id="";
							if(isset($_REQUEST['class_id'])){
								$class_id=$_REQUEST['class_id'];
								}?>
		   <select name="class_id"  id="class_list" class="form-control class_id_exam validate[required] text-input">
						<option value=" "><?php esc_attr_e('Select Class Name','school-mgt');?></option>
						<?php
							  foreach(mj_smgt_get_allclass() as $classdata)
							  {  
							  ?>
							   <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
						 <?php }?>
					</select>
		</div>
		<div class="form-group col-md-3">
			<label for="class_id"><?php esc_attr_e('Select Section','school-mgt');?></label>			
			<?php 
			$class_section="";
			if(isset($_REQUEST['class_section'])){ $class_section=$_REQUEST['class_section']; }elseif(isset($_REQUEST['section_id'])){ $class_section=$_REQUEST['section_id']; } ?>
					<select name="class_section" class="form-control section_id_exam" id="class_section">
							<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
						<?php if(isset($_REQUEST['class_section'])){
								$class_section=$_REQUEST['class_section']; 
								foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
								{  ?>
								 <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php } 
							}?>		
		
					</select>
			</div>
			<div class="form-group col-md-3">
			<label for="exam_id"><?php esc_attr_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
				<select name="exam_id" class="form-control exam_list validate[required] text-input">
					<?php
					if(isset($_POST['exam_id']))
					{
						$exam_data=mj_smgt_get_all_exam_by_class_id_all($_POST['class_id']);
						if(!empty($exam_data))
						{
							foreach ($exam_data as $retrieved_data)
							{
							?>
								<option value="<?php echo $retrieved_data->exam_id;?>" <?php selected($_POST['exam_id'], $retrieved_data->exam_id);  ?>><?php echo $retrieved_data->exam_name;?></option>
							<?php 
							}
						}
						?>
					 <?php  
					}
					else 
					{?>
						<option value=""><?php esc_attr_e('Select Exam','school-mgt');?></option>
						<?php
					}
					?>
				</select>
		</div>
			<?php	
			$school_obj = new School_Management ( get_current_user_id () );
			if($school_obj->role == 'teacher' || $school_obj->role == 'supportstaff')
			{
				if($user_access['add'] == '1')
				{
					$access = 1;
				}
				else
				{
					$access = 0;
				}
			}
			else
			{
				$access = 1;
			}		
			
			if($access == 1)
			{
				?>
				<div class="form-group col-md-3 button-possition">
					<label for="subject_id">&nbsp;</label>
					<input type="submit" value="<?php esc_attr_e('Go','school-mgt');?>" name="add_multiple_subject_marks"  class="btn btn-info"/>
				</div>
		<?php
			}
			?>
	</div>
      </form>
	  </div>		
<?php
$current_date = date("Y-m-d H:i:s");
$school_obj = new School_Management ( get_current_user_id () );
if(isset($_REQUEST['add_single_student_mark']))
{
	$user_id =(int)$_REQUEST['add_single_student_mark'];
	
	$section_id = $_REQUEST['section_id'];
	$subject_list = $obj_marks->mj_smgt_student_subject($class_id,$section_id);
	$current_date = date("Y-m-d H:i:s");
	foreach($subject_list as $sub_id)
	{				
		$marks = $_REQUEST['marks_'.$user_id.'_'.$sub_id->subid.'_mark'];
		$comment = $_REQUEST['marks_'.$user_id.'_'.$sub_id->subid.'_comment'];
		$grade_id = $obj_marks->mj_smgt_get_grade_id($marks);
		if(!$grade_id)
		{
			$grade_id = 0;
		}
		$mark_detail = $obj_marks->mj_smgt_subject_makrs_detail_byuser($_REQUEST['exam_id'],$class_id,$sub_id->subid,$user_id);
		$mark_data = array('exam_id'=>$_REQUEST['exam_id'],
						'class_id'=>$_REQUEST['class_id'],
						'section_id'=>$_REQUEST['section_id'],
						'subject_id'=>$sub_id->subid,
						'marks'=>$marks,				
						'grade_id'=>$grade_id,
						'student_id'=>$user_id,
						'marks_comment'=>$comment,
						'created_date'=>$current_date,
						'created_by'=>get_current_user_id());
		
		if($mark_detail)
		{			
			$mark_id =$_REQUEST['marks_'.$user_id.'_'.$sub_id->subid.'_mark_id'];
			$mark_id=array('mark_id'=>$mark_id);
			$result=$obj_marks->mj_smgt_update_marks($mark_data,$mark_id);							
		}
		else
		{
			$result = $obj_marks->mj_smgt_save_marks($mark_data);			
		}
	}
	if (is_super_admin ()) 
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_result&tab=multiple_subject_marks&message=4');
	}
	else
	{
		wp_redirect ( home_url() . '?dashboard=user&page=manage_marks&tab=multiple_subject_marks&message=4');	
	}
	exit;
}

if(isset($_POST['save_all_multiple_subject_marks']))
{
	$subject_list = $obj_marks->mj_smgt_student_subject($class_id,$_REQUEST['section_id']);
	
	if(isset($_REQUEST['section_id']) && $_REQUEST['section_id'] != ""){
		$exlude_id = mj_smgt_approve_student_list();
		$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['section_id'],
						 'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
		}
		else
		{ 
			$exlude_id = mj_smgt_approve_student_list();
			$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
		} 	
		
	foreach($student as $user)
	{
		foreach($subject_list as $sub_id)
		{			
			$mark_detail = $obj_marks->mj_smgt_subject_makrs_detail_byuser($_REQUEST['exam_id'],$class_id,$sub_id->subid,$user->ID);
			$marks = $_REQUEST['marks_'.$user->ID.'_'.$sub_id->subid.'_mark'];
			$comment = $_REQUEST['marks_'.$user->ID.'_'.$sub_id->subid.'_comment'];
			$grade_id = $obj_marks->mj_smgt_get_grade_id($marks);
			
			$mark_data = array('exam_id'=>$_REQUEST['exam_id'],
							'class_id'=>$_REQUEST['class_id'],
							'section_id'=>$_REQUEST['section_id'],
							'subject_id'=>$sub_id->subid,
							'marks'=>$marks,				
							'grade_id'=>$grade_id,
							'student_id'=>$user->ID,
							'marks_comment'=>$comment,
							'created_date'=>$current_date,
							'created_by'=>get_current_user_id());		
			
			if($mark_detail)
			{			
				$mark_id =$_REQUEST['marks_'.$user->ID.'_'.$sub_id->subid.'_mark_id'];
				$mark_id=array('mark_id'=>$mark_id);
				$result=$obj_marks->mj_smgt_update_marks($mark_data,$mark_id);			
			}
			else
			{			
				$result = $obj_marks->mj_smgt_save_marks($mark_data);			
			}
		}
	}
	if (is_super_admin ()) 
	{
		wp_redirect ( admin_url().'admin.php?page=smgt_result&tab=multiple_subject_marks&message=4');
	}
	else
	{
		wp_redirect ( home_url() . '?dashboard=user&page=manage_marks&tab=multiple_subject_marks&message=4');	
	}	
	exit;
	
}

if(isset($_POST['add_multiple_subject_marks']) || 
isset($_POST['add_single_student_mark']) || 
isset($_POST['save_all_multiple_subject_marks']))
{
	$class_teacher=0;
	$role = $school_obj->role;
	$teacher_id=get_current_user_id ();
	$class_name=get_user_meta($teacher_id,'class_name',true);
	
		if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != "")
		{
			$subject_list = $obj_marks->mj_smgt_student_subject($class_id,$_REQUEST['class_section']);
			$exlude_id = mj_smgt_approve_student_list();
			$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],'meta_query'=> array(array('key' => 'class_name','value' => $class_id,'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
		}
		else
		{ 
			$subject_list = $obj_marks->mj_smgt_student_subject($class_id);
			$exlude_id = mj_smgt_approve_student_list();
			$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $class_id,'role'=>'student','exclude'=>$exlude_id));
		} 
	 
	$exam_id = $_REQUEST['exam_id'];
	if($class_teacher==1)
	{?>
		<div class="panel-heading">
         	<h4 class="panel-title"><?php esc_attr_e('You cant change marks of other subjects','school-mgt');?></h4>
         </div>
	<?php }
	else{ ?>
	<div class="clearfix mt-4 panel-body p">
      <form method="post" class="form-inline" id="marks_form" enctype="multipart/form-data">  
	 <input type="hidden" name="exam_id" value="<?php echo $exam_id;?>" />
      <input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
      <input type="hidden" name="section_id" value="<?php echo $_REQUEST['class_section'];?>" />
      <input type="hidden" name="class_section" value="<?php echo $_REQUEST['class_section'];?>" />
	  <div class="table-responsive">
	 <table class="table col-md-12">
    	<tr>
	       	<th><?php esc_attr_e('Roll No.','school-mgt');?></th>
        	<th><?php esc_attr_e('Name','school-mgt');?></th>         
			
           <?php 
		   	if(!empty($subject_list))
			{			
				foreach($subject_list as $sub_id)
				{
					
					echo "<th> ".$sub_id->sub_name." </th>";
				}
			}  ?>
            
            <th>&nbsp;</th>
        </tr>
		
	<?php
	foreach($student as $user)
	{
		$button_text = esc_attr__('Add/Save Mark','school-mgt');		
		$mark_detail = $obj_marks->mj_smgt_subject_makrs_detail_byuser($exam_id,$class_id,$subject_id,$user->ID);
		echo "<tr>";
		echo '<td>'.$user->roll_id.'</td>';
		echo '<td><span>' .mj_smgt_get_user_name_byid($user->ID). '</span></td>';
		
		if(!empty($subject_list))
		{			
			foreach($subject_list as $sub_id)
			{
				$mark_detail = $obj_marks->mj_smgt_subject_makrs_detail_byuser($exam_id,$class_id,$sub_id->subid,$user->ID);
				if($mark_detail)
			{
				$mark_id=$mark_detail->mark_id;
				$marks=$mark_detail->marks;
				
				$marks_comment=$mark_detail->marks_comment;
				
			}
			else
			{
				$marks=0;
				$attendance=0;
				$marks_comment="";
				
				$mark_id="0";
			}
				echo '<td id="position_relative"><label>'. esc_attr__('Mark','school-mgt').'</label><BR><input type="text" name="marks_'.$user->ID.'_'.$sub_id->subid.'_mark" value="'.$marks.'" class="w-auto form-control validate[required,custom[onlyNumberSp],min[0],max[100]] text-input"><BR><label>'. esc_attr__('Comment','school-mgt').'</label><BR><input type="text" maxlength="50" name="marks_'.$user->ID.'_'.$sub_id->subid.'_comment" value="'.$marks_comment.'" class="w-auto form-control text-input"><input type="hidden" value="'.$mark_id.'" name="marks_'.$user->ID.'_'.$sub_id->subid.'_mark_id"></td>';
			}
		}
		echo '<td><button type="submit" name="add_single_student_mark" value="'.$user->ID.'" class="p-2 btn btn-success">'.$button_text.'</button></td>';
		echo "</tr>";
	}
	echo '</table>';
	?>
	</div>
	<?php	
	$school_obj = new School_Management ( get_current_user_id () );
	if($school_obj->role == 'teacher' || $school_obj->role == 'supportstaff')
	{
		if($user_access['edit'] == '1')
		{
			$access = 1;
		}
		else
		{
			$access = 0;
		}
	}
	else
	{
		$access = 1;
	}		
	
	if($access == 1)
	{
		?>
		<div class="mt-3 col-sm-12">
			<div class="pull-right">
				<input type="submit" class="btn btn-success" name="save_all_multiple_subject_marks" value="<?php esc_attr_e('Update All Marks','school-mgt');?>">
			</div>
	   </div>
	   <?php
    }
	?>
	</form>
	</div>
	<?php }
		
}
?>