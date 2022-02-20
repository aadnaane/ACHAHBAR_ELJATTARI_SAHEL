<script type="text/javascript">
jQuery(document).ready(function($){
	"use strict";	
	$('#grade_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
	var table =  jQuery('#grade_list').DataTable({
        responsive: true,
		"order": [[ 2, "desc" ]],
		"dom": 'Bfrtip',
		"buttons": [
			'colvis'
		], 
		"aoColumns":[	                  
	                  {"bSortable": false},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                  
	                  {"bSortable": false}],
		language:<?php echo mj_smgt_datatable_multi_language();?>
    });
	 jQuery('#checkbox-select-all').on('click', function(){
     
      var rows = table.rows({ 'search': 'applied' }).nodes();
      jQuery('input[type="checkbox"]', rows).prop('checked', this.checked);
   }); 
	jQuery('#delete_selected').on('click', function()
	{
		var c=confirm(language_translate2.delete_record_alert);
		if(c)
		{
			jQuery('#frm-example').submit();
		}
	});

});
</script>
<?php 
	// This is Class at admin side!!!!!!!!! 
	$tablename="grade";
	if(isset($_POST['save_grade']))
	{
		$nonce = $_POST['_wpnonce'];
	    if ( wp_verify_nonce( $nonce, 'save_grade_admin_nonce' ) )
		{
			$created_date = date("Y-m-d H:i:s");
			
			$mark_from=$_POST['mark_from'];
			$mark_upto=$_POST['mark_upto'];
			if($mark_upto > $mark_from)
			{
			$gradedata=array('grade_name'=>mj_smgt_address_description_validation($_POST['grade_name']),
							'grade_point'=>mj_smgt_onlyNumberSp_validation($_POST['grade_point']),
							'mark_from'=>mj_smgt_onlyNumberSp_validation($_POST['mark_from']),
							'mark_upto'=>mj_smgt_onlyNumberSp_validation($_POST['mark_upto']),
							'grade_comment'=>mj_smgt_address_description_validation($_POST['grade_comment']),	
							'creater_id'=>get_current_user_id(),
							'created_date'=>$created_date
							
			);
			//table name without prefix
			$tablename="grade";
			
			if($_REQUEST['action']=='edit')
			{
				$grade_id=array('grade_id'=>$_REQUEST['grade_id']);
				$result=mj_smgt_update_record($tablename,$gradedata,$grade_id);
				if($result)
				{
				wp_redirect ( admin_url().'admin.php?page=smgt_grade&tab=gradelist&message=2');
				}
			}
			else
			{
				$grade_name=mj_smgt_get_grade_by_name($_POST['grade_name']);
				if(empty($grade_name))
				{
					 $result=mj_smgt_insert_record($tablename,$gradedata);
					 if($result)
					 {
						wp_redirect ( admin_url().'admin.php?page=smgt_grade&tab=gradelist&message=1');
					 }
				}
				else
				{
					?>
						<div id="message" class="alert updated_top below-h2 notice is-dismissible alert-dismissible">
				<p><?php esc_html_e('Grade Name All Ready Exist.','school-mgt');?></p>
				<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text"><?php esc_html_e('Dismiss this notice.','school-mgt');?></span></button>
			      </div>				
				   
				<?php
				}
					
			}
		}
		else
		{ ?>
			<div id="message" class="alert updated below-h2 notice is-dismissible alert-dismissible">
			<p><?php echo esc_html_e('You can not add a Mark upto lower than the Mark from.','school-mgt');?></p>
			<button type="button" class="btn-default notice-dismiss" data-bs-dismiss="alert" aria-label="Close"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
		<?php
	    }
	}
	}
	if(isset($_REQUEST['delete_selected']))
	{		
		if(!empty($_REQUEST['id']))
		foreach($_REQUEST['id'] as $id)
			$result=mj_smgt_delete_grade($tablename,$id);
		if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_grade&tab=gradelist&message=3');
			}
	}
	$tablename="grade";
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete')
	{
		$result=mj_smgt_delete_grade($tablename,$_REQUEST['grade_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=smgt_grade&tab=gradelist&message=3');
			}
	}
		
$active_tab = isset($_GET['tab'])?$_GET['tab']:'gradelist';
	?>


<div class="page-inner">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div  id="main-wrapper" class="grade_page">
	<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('Grade Added successfully','school-mgt');
			break;
		case '2':
			$message_string = esc_attr__('Grade successfully Updated!','school-mgt');
			break;	
		case '3':
			$message_string = esc_attr__('Grade Delete Successfully !','school-mgt');
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
    	<a href="?page=smgt_grade&tab=gradelist" class="nav-tab <?php echo $active_tab == 'gradelist' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-menu"></span>'. esc_attr__('Grade List', 'school-mgt'); ?></a>
         <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{?>
       <a href="?page=smgt_grade&tab=addgrade&action=edit&class_id=<?php echo $_REQUEST['grade_id'];?>" class="nav-tab <?php echo $active_tab == 'addgrade' ? 'nav-tab-active' : ''; ?>">
		<?php esc_attr_e('Edit Grade', 'school-mgt'); ?></a>  
		<?php 
		}
		else
		{?>
    	<a href="?page=smgt_grade&tab=addgrade" class="nav-tab margin_bottom <?php echo $active_tab == 'addgrade' ? 'nav-tab-active' : ''; ?>">
		<?php echo '<span class="dashicons dashicons-plus-alt"></span>'. esc_attr__('Add Grade', 'school-mgt'); ?></a>  
        <?php } ?>
    </h2>
    <?php
	
	if($active_tab == 'gradelist')
	{	
	?>	
   
         <?php 
		 	$retrieve_class = mj_smgt_get_all_data($tablename);?>
        <div class="panel-body">
        <div class="table-responsive">
		<form id="frm-example" name="frm-example" method="post">	
        <table id="grade_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
				<th class="w-20-px"><input name="select_all" value="all" id="checkbox-select-all" 
				type="checkbox" /></th>         
                <th><?php esc_attr_e('Grade Name','school-mgt');?></th>
                <th><?php esc_attr_e('Grade Point','school-mgt');?></th>
                <th><?php esc_attr_e('Mark From','school-mgt');?></th>
                <th><?php esc_attr_e('Mark Upto','school-mgt');?></th>
                <th><?php esc_attr_e('Comment','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?></th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th></th>
               <th><?php esc_attr_e('Grade Name','school-mgt');?></th>
                <th><?php esc_attr_e('Grade Point','school-mgt');?></th>
                <th><?php esc_attr_e('Mark From','school-mgt');?></th>
                <th><?php esc_attr_e('Mark Upto','school-mgt');?></th>
                <th><?php esc_attr_e('Comment','school-mgt');?></th>	
                <th><?php esc_attr_e('Action','school-mgt');?></th>
            </tr>
        </tfoot>
 
        <tbody>
          <?php 
		 	foreach ($retrieve_class as $retrieved_data){ 
			
		 ?>
            <tr>
				<td><input type="checkbox" class="select-checkbox" name="id[]" 
				value="<?php echo $retrieved_data->grade_id;?>"></td>
                <td><?php echo $retrieved_data->grade_name;?></td>
                <td><?php echo $retrieved_data->grade_point;?></td>
                <td><?php echo $retrieved_data->mark_from;?></td>
                <td><?php echo $retrieved_data->mark_upto;?></td>
                <td><?php echo $retrieved_data->grade_comment;?></td>
               <td><a href="?page=smgt_grade&tab=addgrade&action=edit&grade_id=<?php echo $retrieved_data->grade_id;?>" class="btn btn-info"><?php esc_attr_e('Edit','school-mgt');?></a>
               <a href="?page=smgt_grade&tab=gradelist&action=delete&grade_id=<?php echo $retrieved_data->grade_id;?>" class="btn btn-danger" 
               onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this record?','school-mgt');?>');"><?php esc_attr_e('Delete','school-mgt');?></a></td>
            </tr>
            <?php } ?>
     
        </tbody>
        
        </table>
		<div class="print-button pull-left">
			<input id="delete_selected" type="submit" value="<?php esc_attr_e('Delete Selected','school-mgt');?>" name="delete_selected" class="btn btn-danger delete_selected"/>
			
		</div>
		</form>
        </div>
        </div>
       
     <?php 
	 }
	if($active_tab == 'addgrade')
	 {
		require_once SMS_PLUGIN_DIR. '/admin/includes/grade/add-grade.php';
		
	 }
	 ?>
	 		</div>
	 	</div>
	 </div>
</div>
<?php ?>