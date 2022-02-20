<?php 
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'Student';
?>
<!-- View Popup Code start -->	
<div class="popup-bg">
    <div class="overlay-content">
    	<div class="notice_content"></div>    
    </div> 
</div>	
<!-- View Popup Code end -->
	
<!-- page inner div start-->
<div class="page-inner access-right">
	<!-- Page Title div start -->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<!-- Page Title div end -->
	<!--  main-wrapper div start  -->
	<div  id="main-wrapper" class="notice_page font_size_access">
	<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Record Updated Successfully.','school-mgt');
			break;		
	}
	if($message)
	{ ?>
		<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
			<p><?php echo $message_string;?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
<?php } ?>
		<!-- panel-white div start  -->
		<div class="panel panel-white">
			<!-- panel-body div start  -->
			<div class="panel-body">
				<h2 class="nav-tab-wrapper">
					<a href="?page=smgt_access_right&tab=Student" class="nav-tab <?php echo $active_tab == 'Student' ? 'nav-tab-active' : ''; ?>">
					<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Student', 'school-mgt'); ?></a>

					<a href="?page=smgt_access_right&tab=Teacher" class="nav-tab <?php echo $active_tab == 'Teacher' ? 'nav-tab-active' : ''; ?>">
					<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Teacher', 'school-mgt'); ?></a> 
			 
					<a href="?page=smgt_access_right&tab=Parent" class="nav-tab <?php echo $active_tab == 'Parent' ? 'nav-tab-active' : ''; ?>">
					<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Parent', 'school-mgt'); ?></a> 
			  
					<a href="?page=smgt_access_right&tab=Support_staff" class="nav-tab <?php echo $active_tab == 'Support_staff' ? 'nav-tab-active' : ''; ?>">
					<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Support Staff', 'school-mgt'); ?></a> 
					
				</h2>
				<div class="clearfix"></div>
				<?php
				if($active_tab == 'Student')
				 {
					require_once SMS_PLUGIN_DIR. '/admin/includes/access_right/student.php';					
				 }
				 
				 elseif($active_tab == 'Teacher')
				 {
					require_once SMS_PLUGIN_DIR. '/admin/includes/access_right/teacher.php';
				 }
				 
				 elseif($active_tab == 'Parent')
				 {
					require_once SMS_PLUGIN_DIR. '/admin/includes/access_right/parent.php';
				 }
				 
				 elseif($active_tab == 'Support_staff')
				 {
					require_once SMS_PLUGIN_DIR. '/admin/includes/access_right/support_staff.php';
				 }	
				 ?> 
			</div>
			<!-- panel-body div end -->
	 	</div>
		<!-- panel-white div end -->
	</div>
	<!--  main-wrapper div end -->
</div>
<!-- page inner div end -->
<?php ?>