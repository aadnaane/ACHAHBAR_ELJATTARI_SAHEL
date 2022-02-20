<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
	$('#failed_report').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});	
	$("#sdate").datepicker({
        dateFormat: "yy-mm-dd",
		changeYear: true,
		changeMonth: true,
		maxDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $("#edate").datepicker("option", "minDate", dt);
        }
    });

	
    $("#edate").datepicker({
       dateFormat: "yy-mm-dd",
	   changeYear: true,
	   changeMonth: true,
	   maxDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 0);
            $("#sdate").datepicker("option", "maxDate", dt);
        }
    });

     $('#fee_payment_report').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	 $('#example5').DataTable({
        responsive: true,
		language:<?php echo mj_smgt_datatable_multi_language();?>	
    });

    $('.sdate').datepicker({dateFormat: "yy-mm-dd",changeYear: true,changeMonth:true}); 
    $('.edate').datepicker({dateFormat: "yy-mm-dd",changeMonth: true,changeMonth:true}); 

    var table = jQuery('#tblexpence').DataTable({
				"responsive": true,
				"order": [[ 2, "Desc" ]],
				"dom": 'Bfrtip',
				buttons: [
				{
				extend: 'print',
				title: ' Expense Report List',
				},
			],
				"aoColumns":[
					{"bSortable": true},
					{"bSortable": true},
					{"bSortable": true}
				],
				language:<?php echo mj_smgt_datatable_multi_language();?>
			});

	 $('#fee_payment_report').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	 $('#sdate').datepicker({
		 dateFormat: "yy-mm-dd",
		 changeYear: true,
		 changeMonth: true,
		 maxDate : 0,
		 beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		 }); 
	 $('#edate').datepicker({
		 dateFormat: "yy-mm-dd",
		 changeYear: true,
		 changeMonth: true,
		 maxDate : 0,
		 beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			}
		 }); 
	 $('#example4').DataTable({
        responsive: true,
		language:<?php echo mj_smgt_datatable_multi_language();?>	
    });

});
</script>
<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'report1';
$obj_marks = new Marks_Manage();
if($active_tab == 'report1')
{
$chart_array = array();
$chart_array[] = array( esc_attr__('Class','school-mgt'),esc_attr__('No. of Student Fail','school-mgt'));

if(isset($_REQUEST['report_1']))
{
	global $wpdb;
	$table_marks = $wpdb->prefix .'marks';
	$table_users = $wpdb->prefix .'users';
	$exam_id = $_REQUEST['exam_id'];
	$class_id = $_REQUEST['class_id'];
	if(isset($_REQUEST['class_section']) && $_REQUEST['class_section']!=""){
		$section_id = $_REQUEST['class_section'];	
		$report_1 =$wpdb->get_results("SELECT * , count( student_id ) as count
			FROM $table_marks as m, $table_users as u
			WHERE m.marks <40
			AND m.exam_id = $exam_id
			AND m.Class_id = $class_id
			AND m.section_id = $section_id
			AND m.student_id = u.id
			GROUP BY subject_id");
	}
	else
	{		
		$report_1 =$wpdb->get_results("SELECT * , count( student_id ) as count
			FROM $table_marks as m, $table_users as u
			WHERE m.marks <40
			AND m.exam_id = $exam_id
			AND m.Class_id = $class_id
			AND m.student_id = u.id
			GROUP BY subject_id");
	}
	
if(!empty($report_1))
foreach($report_1 as $result)
{	
	$subject =mj_smgt_get_single_subject_name($result->subject_id);
	$chart_array[] = array("$subject",(int)$result->count);
}
$options = Array(
		'title' => esc_attr__('Exam Failed Report','school-mgt'),
		'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
		'legend' =>Array('position' => 'right',
				'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),
			
		'hAxis' => Array(
				'title' =>  esc_attr__('Subject','school-mgt'),
				'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
				'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
				'maxAlternation' => 2
		),
		'vAxis' => Array(
				'title' =>  esc_attr__('No of Student','school-mgt'),
				'minValue' => 0,
				'maxValue' => 5,
				'format' => '#',
				'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
				'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
		),
		'colors' => array('#22BAA0')
);

}
}
if($active_tab == 'report2')
{
	$chart_array[] = array(esc_attr__('Class','school-mgt'),esc_attr__('Present','school-mgt'),esc_attr__('Absent','school-mgt'));
if(isset($_REQUEST['report_2']))
{
	
	global $wpdb;
	$table_attendance = $wpdb->prefix .'attendence';
	$table_class = $wpdb->prefix .'smgt_class';
	$sdate = $_REQUEST['sdate'];
	$edate = $_REQUEST['edate'];
	
	$report_2 =$wpdb->get_results("SELECT  at.class_id, 
SUM(case when `status` ='Present' then 1 else 0 end) as Present, 
SUM(case when `status` ='Absent' then 1 else 0 end) as Absent 
from $table_attendance as at,$table_class as cl where `attendence_date` BETWEEN '$sdate' AND '$edate' AND at.class_id = cl.class_id AND at.role_name = 'student' GROUP BY at.class_id") ;
	if(!empty($report_2))
		foreach($report_2 as $result)
		{	
			$class_id =mj_smgt_get_class_name($result->class_id);
			$chart_array[] = array("$class_id",(int)$result->Present,(int)$result->Absent);
		}

	$options = Array(
			'title' => esc_attr__('Attendance Report','school-mgt'),
			'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
			'legend' =>Array('position' => 'right',
					'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),
				
			'hAxis' => Array(
					'title' =>  esc_attr__('Class','school-mgt'),
					'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
					'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
					'maxAlternation' => 2


			),
			'vAxis' => Array(
					'title' =>  esc_attr__('No of Student','school-mgt'),
					'minValue' => 0,
					'maxValue' => 5,
					'format' => '#',
					'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
					'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
			),
			'colors' => array('#22BAA0','#f25656')
	);

}

}

if($active_tab == 'report3')
{
	$chart_array[] = array(esc_attr__('Teacher','school-mgt'),esc_attr__('fail','school-mgt'));
		global $wpdb;
		$table_subject = $wpdb->prefix .'subject';
		$table_name_mark = $wpdb->prefix .'marks';
		$table_name_users = $wpdb->prefix .'users';
		$table_teacher_subject = $wpdb->prefix .'teacher_subject';		
		$teachers = get_users(array("role"=>"teacher"));
		$report_3 = array();
		if(!empty($teachers))
		{
			foreach($teachers as $teacher)
			{
				$report_3[$teacher->ID] = mj_smgt_get_subject_id_by_teacher($teacher->ID);
			}		
		}
		 
		if(!empty($report_3))
		{
			foreach($report_3 as $teacher_id=>$subject)
			{
				
				if(!empty($subject))
				{
					$sub_str = implode(",",$subject);
					$count = $wpdb->get_results("SELECT COUNT(*) as count FROM {$table_name_mark} WHERE marks < 40 AND subject_id in ({$sub_str}) GROUP by subject_id",ARRAY_A);
					$total_fail = array_sum(array_column($count,"count"));	
				}
				else
				{
					$total_fail =0;
				}
				$teacher_name = mj_smgt_get_display_name($teacher_id);
				$chart_array[] = [$teacher_name , $total_fail];
			}
		}
		
		$options = Array(
			'title' => esc_attr__('Teacher Perfomance Report','school-mgt'),
			'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
			'legend' =>Array('position' => 'right',
				'textStyle'=> Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')),
				'hAxis' => Array(
					'title' =>  esc_attr__('Teacher Name','school-mgt'),
					'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
					'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
					'maxAlternation' => 2
				),
				'vAxis' => Array(
					'title' =>  esc_attr__('No of Student','school-mgt'),
					'minValue' => 0,
					'maxValue' => 5,
					'format' => '#',
					'titleTextStyle' => Array('color' => '#4e5e6a','fontSize' => 16,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;'),
					'textStyle' => Array('color' => '#4e5e6a','fontSize' => 13,'bold'=>false,'italic'=>false,'fontName' =>'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;')
				),
				'colors' => array('#22BAA0')
			);
}
require_once SMS_PLUGIN_DIR. '/lib/chart/GoogleCharts.class.php';
$GoogleCharts = new GoogleCharts;
?>
	<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="invoice_data"></div>		 
		</div>
    </div>
</div>
<!-- End POP-UP Code -->
<div class="page-inner">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
<div class=" transport_list" id="main-wrapper"> 
<div class="panel panel-white">
	<div class="panel-body"> 
	<h2 class="nav-tab-wrapper">
    	<a href="?page=smgt_report&tab=report1" class="nav-tab <?php echo $active_tab == 'report1' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-chart-bar"></span> '. esc_attr__('Student Failed Report', 'school-mgt'); ?></a>
        
    	<a href="?page=smgt_report&tab=report2" class="nav-tab <?php echo $active_tab == 'report2' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-chart-bar"></span> '. esc_attr__('Attendance Report', 'school-mgt'); ?></a>  
		
		<a href="?page=smgt_report&tab=report3" class="nav-tab <?php echo $active_tab == 'report3' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-chart-bar"></span> '. esc_attr__('Teacher Performance Report', 'school-mgt'); ?></a>  
		<a href="?page=smgt_report&tab=report4" class="nav-tab <?php echo $active_tab == 'report4' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-chart-bar"></span> '. esc_attr__('Fee Payment Report', 'school-mgt'); ?></a> 
		<a href="?page=smgt_report&tab=report5" class="nav-tab margin_bottom <?php echo $active_tab == 'report5' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-chart-bar"></span> '. esc_attr__('Result Report', 'school-mgt'); ?></a> 

		<a href="?page=smgt_report&tab=report10" class="nav-tab margin_bottom <?php echo $active_tab == 'report10' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-chart-bar"></span> '. esc_attr__('Income Report', 'school-mgt'); ?></a> 
        
		<a href="?page=smgt_report&tab=report6" class="nav-tab margin_bottom <?php echo $active_tab == 'report6' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-chart-bar"></span> '. esc_attr__('Expense Report', 'school-mgt'); ?></a> 
      
       
        
    </h2>
    <?php 
    if($active_tab == 'report1')
    {
    ?>

<div class="panel-body">
    <form method="post" id="failed_report">  
		<div class="row">
			<div class="form-group col-md-3">
				<label for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
				<?php
					$class_id="";
					if(isset($_REQUEST['class_id']))
					{
						$class_id=$_REQUEST['class_id'];
					}
					?>
				<select name="class_id"  id="class_list" class="form-control validate[required] class_id_exam">
					<option value=" "><?php esc_attr_e('Select Class Name','school-mgt');?></option>
					<?php
					foreach(mj_smgt_get_allclass() as $classdata)
					{
					?>
						<option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?>><?php echo $classdata['class_name'];?></option>
					<?php
					}
					?>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label for="class_id"><?php esc_attr_e('Select Section','school-mgt');?></label>
				<?php
				$class_section="";
				if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
					<select name="class_section" class="form-control" id="class_section">
								  <option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
						<?php if(isset($_REQUEST['class_section']))
						{
							echo $class_section=$_REQUEST['class_section'];
							foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
							{  ?>
								<option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
							<?php
							}
						}
						?>	
							</select>
			</div>
			<div class="form-group col-md-3">
				<label for="exam_id"><?php esc_attr_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
				<?php
					$tablename="exam";
					$retrieve_class = mj_smgt_get_all_data($tablename);
					$exam_id="";
					if(isset($_REQUEST['exam_id']))
					{
						$exam_id=$_REQUEST['exam_id'];
					}
					?>
					<select name="exam_id" class="form-control exam_list validate[required]">
						<option value=" "><?php esc_attr_e('Select Exam Name','school-mgt');?></option>
						<?php
						foreach($retrieve_class as $retrieved_data)
						{
						?>
							<option value="<?php echo $retrieved_data->exam_id;?>" <?php selected($retrieved_data->exam_id,$exam_id)?>><?php echo $retrieved_data->exam_name;?></option>
						<?php
						}
						?>
					</select>
			</div>
			
			<div class="form-group col-md-3 button-possition">
				<label for="subject_id">&nbsp;</label>
				<input type="submit" name="report_1" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info"/>
			</div>
		</div> 	
    </form>
</div>
    	 <div class="clearfix"> </div>
    	  <div class="clearfix"> </div>
    	  <?php if(isset($_REQUEST['report_1']))
    	  {
			 
    	  	if(!empty($report_1))
    	  	{				
				$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
			}
    	  else 
    	  	echo esc_attr_e('result not found','school-mgt');
    	  
    	  }
    	  	?>
    	 <div id="chart_div" class="w-100 h-100"></div>
  
  <!-- Javascript --> 
  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <script type="text/javascript">
			<?php echo $chart;?>
		</script>
    	<?php 
    }
    if($active_tab == 'report2')
    {
    ?>
    <div class="clearfix"> </div>
     <div class="panel-body">
	 <form method="post">  
    <div class="row">
		<div class="form-group col-md-3">
			<label for="exam_id"><?php esc_attr_e('Start Date','school-mgt');?></label>
					<input type="text"  id="sdate" class="form-control" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d');?>" readonly>
		
		</div>
		<div class="form-group col-md-3">
			<label for="exam_id"><?php esc_attr_e('End Date','school-mgt');?></label>
					<input type="text"  id="edate" class="form-control" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>" readonly>
		
		</div>
		<div class="form-group col-md-3 button-possition">
			<label for="subject_id">&nbsp;</label>
			  <input type="submit" name="report_2" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info"/>
		</div>
	</div>
    	
    	</form></div>
    	 <div class="clearfix"> </div>
    	  <div class="clearfix"> </div>
    	  <?php if(isset($_REQUEST['report_2']))
    	  {
    	  	if(!empty($report_2))
    	  	{	$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
    	  }
    	  else 
    	  	esc_attr_e('result not found','school-mgt');
    	  
    	  }
    	  	?>
    	 <div id="chart_div" class="w-100 h-100"></div>
  
  <!-- Javascript --> 
  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <script type="text/javascript">
			<?php echo $chart;?>
		</script>
    <?php 
    }
    if($active_tab == 'report3')
    {
    ?>
    <div class="clearfix"> </div>
    <?php 
      	if(!empty($report_3))
      	{
			$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
    	}
    	else 
    		esc_attr_e('result not found','school-mgt');
    	 ?>
    	<div id="chart_div" class="w-100 h-100"></div>
  
  <!-- Javascript --> 
  <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
  <script type="text/javascript">
			<?php echo $chart;?>
		</script>
    	<?php } 


				
		if($active_tab == 'report4')
		{
		$active_tab = isset($_GET['tab1'])?$_GET['tab1']:'report13'; 
		?>
		<h3 class="nav-tab-wrapper">
		<ul id="myTab" class="sub_menu_css line case_nav nav nav-tabs border-bottom-0" role="tablist">
			<li role="presentation" class="mb-0 <?php echo $active_tab == 'report13' ? 'active' : ''; ?> menucss">
					<a href="?page=smgt_report&tab=report4&tab1=report13" class="nav-tab">
						<?php echo '<span class="dashicons dashicons-chart-bar"></span> '.esc_html__('Fees Payment Datatable', 'school-mgt'); ?>
					</a>
			</li>
			<li role="presentation" class="mb-0 <?php echo $active_tab == 'report14' ? 'active' : ''; ?> menucss">
					<a href="?page=smgt_report&tab=report4&tab1=report14" class="nav-tab">
						<?php echo '<span class="dashicons dashicons-chart-bar"></span> '.esc_html__('Fees Payment Graph', 'school-mgt'); ?>
					</a>
			</li>
		</ul>	
		</h3>
		<div class="clearfix panel-body">
		<?php 
		if($active_tab == 'report13')
		{ 				
			require_once SMS_PLUGIN_DIR.'/admin/includes/report/data_table_fees.php';
		} 
		if($active_tab == 'report14')
		{ 				
			require_once SMS_PLUGIN_DIR.'/admin/includes/report/graph_fees.php';
		} 
		?>
		</div>
		<?php
		}
		if($active_tab == 'report5')
		{ ?>
		<div class="panel-body">
    	 <form method="post" id="fee_payment_report">  
			<div class="row">
				<div class="form-group col-md-3">
					<label for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
				   <select name="class_id"  id="class_list" class="form-control class_id_exam validate[required]">
								<?php $class_id="";
								if(isset($_REQUEST['class_id'])){
									$class_id=$_REQUEST['class_id'];
									}?>
								<option value=" "><?php esc_attr_e('Select Class Name','school-mgt');?></option>
								<?php
								  foreach(mj_smgt_get_allclass() as $classdata)
								  {
								  ?>
								   <option  value="<?php echo $classdata['class_id'];?>" <?php selected($classdata['class_id'],$class_id)?> ><?php echo $classdata['class_name'];?></option>
							 <?php }?>
							</select>
				</div>
				<div class="form-group col-md-3">
						<label for="class_id"><?php esc_attr_e('Select Section','school-mgt');?></label>
						<?php
						$class_section="";
						if(isset($_REQUEST['class_section'])) $class_section=$_REQUEST['class_section']; ?>
								<select name="class_section" class="form-control" id="class_section">
										<option value=""><?php esc_attr_e('Select Class Section','school-mgt');?></option>
									<?php if(isset($_REQUEST['class_section'])){
											echo $class_section=$_REQUEST['class_section'];
											foreach(mj_smgt_get_class_sections($_REQUEST['class_id']) as $sectiondata)
											{  ?>
											 <option value="<?php echo $sectiondata->id;?>" <?php selected($class_section,$sectiondata->id);  ?>><?php echo $sectiondata->section_name;?></option>
										<?php }
										}?>
				
								</select>
					</div>
				<div class="form-group col-md-3">
					<label for="exam_id"><?php esc_attr_e('Select Exam','school-mgt');?><span class="require-field">*</span></label>
					<?php
						$tablename="exam";
						$retrieve_class = mj_smgt_get_all_data($tablename);?>
						<?php
						$exam_id="";
						if(isset($_REQUEST['exam_id'])){
									$exam_id=$_REQUEST['exam_id'];
						} ?>
						<select name="exam_id" class="form-control exam_list validate[required]">
							<option value=" "><?php esc_attr_e('Select Exam Name','school-mgt');?></option>
							<?php
							foreach($retrieve_class as $retrieved_data)
							{
							?>
								<option value="<?php echo $retrieved_data->exam_id;?>" <?php selected($retrieved_data->exam_id,$exam_id)?>><?php echo $retrieved_data->exam_name;?></option>
							<?php
							}
							?>
						</select>
				</div>
				 <div class="form-group col-md-3 button-possition">
					<label for="subject_id">&nbsp;</label>
					<input type="submit" name="report_5" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info"/>
				</div>
			</div>
    	
    	</form>
		</div>
    	<div class="clearfix panel-body">
			
		<?php if(isset($_POST['report_5']))
			{ 
				$exam_id=$_REQUEST['exam_id'];
				$class_id=$_REQUEST['class_id'];
				if(isset($_REQUEST['class_section']) && $_REQUEST['class_section'] != ""){
					$subject_list = $obj_marks->mj_smgt_student_subject($_REQUEST['class_id'],$_REQUEST['class_section']);
					$exlude_id = mj_smgt_approve_student_list();
					$student = get_users(array('meta_key' => 'class_section', 'meta_value' =>$_REQUEST['class_section'],
									 'meta_query'=> array(array('key' => 'class_name','value' =>$_REQUEST['class_id'],'compare' => '=')),'role'=>'student','exclude'=>$exlude_id));	
				}
				else
				{ 
					$subject_list = $obj_marks->mj_smgt_student_subject($_REQUEST['class_id']);
					$exlude_id = mj_smgt_approve_student_list();
					$student = get_users(array('meta_key' => 'class_name', 'meta_value' => $_REQUEST['class_id'],'role'=>'student','exclude'=>$exlude_id));
				} ?>
				
				<div class="table-responsive">
					<table id="example5" class="display" cellspacing="0" width="100%">
						 <thead>
						<tr>                
							<th><?php esc_attr_e('Roll No.','school-mgt');?></th>  
							<th><?php esc_attr_e('Student Name','school-mgt');?></th>
							<?php 
						   if(!empty($subject_list))
							{			
								foreach($subject_list as $sub_id)
								{
									
									echo "<th> ".$sub_id->sub_name." </th>";
								}
							} ?>
							<th><?php esc_attr_e('Total','school-mgt');?></th>  
					</thead>
			 
					<tfoot>
						<tr>
							<th><?php esc_attr_e('Roll No.','school-mgt');?></th>  
							<th><?php esc_attr_e('Student Name','school-mgt');?></th>
							<?php 
								if(!empty($subject_list))
								{			
									foreach($subject_list as $sub_id)
									{
										
										echo "<th> ".$sub_id->sub_name." </th>";
									}
								} 
							?> 
							<th><?php esc_attr_e('Total','school-mgt');?></th>     
						</tr>
					</tfoot>
			 
					<tbody>
					<?php 
					if(!empty($student))
					{
						foreach ($student as $user)
						{ 
						$total=0;
					    ?>
						<tr>
							<td><?php echo $user->roll_id;?></td>
							<td><?php echo mj_smgt_get_user_name_byid($user->ID);?></td>
							<?php 
							if(!empty($subject_list))
							{		
								foreach($subject_list as $sub_id)
								{
									$mark_detail = $obj_marks->mj_smgt_subject_makrs_detail_byuser($exam_id,$class_id,$sub_id->subid,$user->ID);
									if($mark_detail)
								{
									$mark_id=$mark_detail->mark_id;
									$marks=$mark_detail->marks;
									$total+=$marks;
									
									
								}
								else
								{
									$marks=0;
									$attendance=0;
									$marks_comment="";
									$total+=0;
									$mark_id="0";
								}
									echo '<td>'.$marks.'</td>';
								}
								echo '<td>'.$total.'</td>';
							}
							else
							{
								echo '<td>'.$total.'</td>';
							}
							?>
						</tr>
						<?php 
						}
					}
					
					?>
					</tbody>
					</table>
				   </div>
			</div> <!-- end panel body div -->
			<?php
			}
		}
		if($active_tab == 'report6')
		{
		$active_tab = isset($_GET['tab1'])?$_GET['tab1']:'report8'; 
		?>
		<h3 class="nav-tab-wrapper">
		<ul id="myTab" class="sub_menu_css line case_nav nav nav-tabs border-bottom-0" role="tablist">
			<li role="presentation" class="mb-0 <?php echo $active_tab == 'report8' ? 'active' : ''; ?> menucss">
					<a href="?page=smgt_report&tab=report6&tab1=report8" class="nav-tab">
						<?php echo '<span class="dashicons dashicons-chart-bar"></span> '.esc_html__('Expense Report Datatable', 'school-mgt'); ?>
					</a>
			</li>
			<li role="presentation" class="mb-0 <?php echo $active_tab == 'report9' ? 'active' : ''; ?> menucss">
					<a href="?page=smgt_report&tab=report6&tab1=report9" class="nav-tab">
						<?php echo '<span class="dashicons dashicons-chart-bar"></span> '.esc_html__('Expense Report Graph', 'school-mgt'); ?>
					</a>
			</li>
		</ul>	
		</h3>
		<div class="clearfix panel-body">
		 <?php 
		 if($active_tab == 'report8')
		 { 				
			 require_once SMS_PLUGIN_DIR.'/admin/includes/report/data_table_expense.php';
		 } 
		 if($active_tab == 'report9')
		 { 				
			 require_once SMS_PLUGIN_DIR.'/admin/includes/report/graph_expense.php';
		 } 
		 ?>
		 </div>
		 <?php
		}
		if($active_tab == 'report10')
		{
		$active_tab = isset($_GET['tab1'])?$_GET['tab1']:'report11'; 
		?>
		<h3 class="nav-tab-wrapper">
		<ul id="myTab" class="sub_menu_css line case_nav nav nav-tabs border-bottom-0" role="tablist">
			<li role="presentation" class="mb-0 <?php echo $active_tab == 'report11' ? 'active' : ''; ?> menucss">
					<a href="?page=smgt_report&tab=report10&tab1=report11" class="nav-tab">
						<?php echo '<span class="dashicons dashicons-chart-bar"></span> '.esc_html__('Income Report Datatable', 'school-mgt'); ?>
					</a>
			</li>
			<li role="presentation" class="mb-0 <?php echo $active_tab == 'report12' ? 'active' : ''; ?> menucss">
					<a href="?page=smgt_report&tab=report10&tab1=report12" class="nav-tab">
						<?php echo '<span class="dashicons dashicons-chart-bar"></span> '.esc_html__('Income Report Graph', 'school-mgt'); ?>
					</a>
			</li>
		</ul>	
		</h3>
		<div class="clearfix panel-body">
		 <?php 
		 if($active_tab == 'report11')
		 { 				
			 require_once SMS_PLUGIN_DIR.'/admin/includes/report/data_table_income.php';
		 } 
		 if($active_tab == 'report12')
		 { 				
			 require_once SMS_PLUGIN_DIR.'/admin/includes/report/graph_income.php';
		 } 
		 ?>
		 </div>
		 <?php
		}
		?>
 		</div>
 	</div>
 </div>