<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
	 $('#select_data').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
});
</script>
<?php 
// This is Class at admin side!!!!!!!!! 
if(isset($_REQUEST['migration']))
{
	$nonce = $_POST['_wpnonce'];
	if ( wp_verify_nonce( $nonce, 'save_migration_admin_nonce' ) )
	{
		$current_class = mj_smgt_onlyNumberSp_validation($_REQUEST['current_class']);
		$next_class = mj_smgt_onlyNumberSp_validation($_REQUEST['next_class']);
		if($current_class != $next_class)
		{
		$exam_id = mj_smgt_onlyNumberSp_validation($_REQUEST['exam_id']);
		$passing_marks = mj_smgt_onlyNumberSp_validation($_REQUEST['passing_marks']);
		$student_fail = mj_smgt_fail_student_list($current_class,$next_class,$exam_id,$passing_marks);
		$update = mj_smgt_migration($current_class,$next_class,$exam_id,$student_fail);
		  wp_redirect ( admin_url().'admin.php?page=smgt_Migration&message=1');
		}
		else
		{ ?>
			<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
			<p><?php echo esc_attr__('Current Class and Next Class can not be same.','school-mgt');?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
			
		<?php 
		}
	}
}
?>
<div class="page-inner min-h-1631-px">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div  id="main-wrapper" class="marks_list">
	<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Migration Completed Successfully.','school-mgt');
			break;	
	}
	
	if($message)
	{ ?>
		<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
			<p><?php echo $message_string;?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
<?php } ?>
	
	<div class="panel panel-white">
	<div class="panel-body">  
 
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_Migration" class="nav-tab margin_bottom  nav-tab-active">
		<?php echo '<span class="dashicons dashicons-awards"></span>'. esc_attr__('Migration', 'school-mgt'); ?></a>
        
    </h2>
    <?php
	$tablename="marks";
	?>	 
	<div class="panel-body"> 
    <form method="post" id="select_data">  
  <div class="row">
	  
	   <div class="form-group col-md-3">
		   <label for="current_class"><?php esc_attr_e('Select Current Class','school-mgt');?><span class="require-field">*</span></label>
		  <select name="current_class"  id="current_class" class="form-control validate[required] text-input">
					   <option value=" "><?php esc_attr_e('Select Current Class Name','school-mgt');?></option>
					   <?php
							foreach(mj_smgt_get_allclass() as $classdata)
							{  
							?>
							 <option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
					   <?php }?>
				   </select>
	   </div>
	   <div class="form-group col-md-3">
		   <label for="next_class"><?php esc_attr_e('Select Next Class','school-mgt');?><span class="require-field">*</span></label>
		  <select name="next_class"  id="next_class" class="form-control validate[required] text-input">
					   <option value=" "><?php esc_attr_e('Select Class Name','school-mgt');?></option>
					   <?php
							foreach(mj_smgt_get_allclass() as $classdata)
							{  
							?>
							 <option  value="<?php echo $classdata['class_id'];?>" ><?php echo $classdata['class_name'];?></option>
					   <?php }?>
				   </select>
	   </div>
		<div class="form-group col-md-3">
		   <label for="exam_id"><?php esc_attr_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
		   <?php
						  $tablename="exam"; 
						  $retrieve_class = mj_smgt_get_all_data($tablename);
					  
						  ?>
				   <select name="exam_id" class="form-control validate[required] text-input">
					   <option value=" "><?php esc_attr_e('Select Exam Name','school-mgt');?></option>
					   <?php
						  foreach($retrieve_class as $retrieved_data)
						  {
							  
						  ?>
					   <option value="<?php echo $retrieved_data->exam_id;?>"><?php echo $retrieved_data->exam_name;?></option>
						  <?php	
						  }
						  ?>
				   </select>
	   </div>
		  <?php wp_nonce_field( 'save_migration_admin_nonce' ); ?>
	   <div class="form-group col-md-2">
		   <label for="next_class" class="passing_mark"><?php esc_attr_e('Passing Marks','school-mgt');?><span class="require-field">*</span></label>
		  <input type="number" name="passing_marks" value=""  class="form-control validate[required,min[0],maxSize[5]]">
	   </div>
	   <div class="form-group col-md-1 button-possition button_possition_padding ">
		   <label for="subject_id">&nbsp;</label>
			 <input type="submit" value="<?php esc_attr_e('Go','school-mgt');?>" name="migration"  class="btn btn-info"/>
	   </div>
  </div>
   
      </form>
	  </div>
      <div class="clearfix"> </div>
    
	 
   
	 </div>
	 </div>
	 </div>    
</div>