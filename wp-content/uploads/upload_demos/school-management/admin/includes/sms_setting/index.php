<script type="text/javascript">
jQuery(document).ready(function($)
{
	"use strict";	
	 $('#sms_setting_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
});
</script>
<?php 
$current_sms_service_active =get_option( 'smgt_sms_service');
if(isset($_REQUEST['save_sms_setting']))
{
	if(isset($_REQUEST['select_serveice']) && $_REQUEST['select_serveice'] == 'clickatell')
	{
		$custm_sms_service = array();
		$result=get_option( 'smgt_clickatell_sms_service');
		
		$custm_sms_service['username'] = trim($_REQUEST['username']);
		$custm_sms_service['password'] = $_REQUEST['password'];
		$custm_sms_service['api_key'] = $_REQUEST['api_key'];
		$custm_sms_service['sender_id'] = $_REQUEST['sender_id'];
		$result=update_option( 'smgt_clickatell_sms_service',$custm_sms_service );
	}
	if(isset($_REQUEST['select_serveice']) && $_REQUEST['select_serveice'] == 'twillo')
	{
		$custm_sms_service = array();
		$result=get_option( 'smgt_twillo_sms_service');
		$custm_sms_service['account_sid'] = trim($_REQUEST['account_sid']);
		$custm_sms_service['auth_token'] = trim($_REQUEST['auth_token']);
		$custm_sms_service['from_number'] = $_REQUEST['from_number'];
		$result=update_option( 'smgt_twillo_sms_service',$custm_sms_service );
	}
	if(isset($_REQUEST['select_serveice']) && $_REQUEST['select_serveice'] == 'msg91')
	{
		$custm_sms_service = array();
		$result=get_option( 'smgt_msg91_sms_service');
		$custm_sms_service['msg91_senderID'] = trim($_REQUEST['msg91_senderID']);
		$custm_sms_service['sms_auth_key'] = trim($_REQUEST['sms_auth_key']);
		$custm_sms_service['wpnc_sms_route'] = $_REQUEST['wpnc_sms_route'];
		$result=update_option( 'smgt_msg91_sms_service',$custm_sms_service );
	}
	
	update_option( 'smgt_sms_service',$_REQUEST['select_serveice'] );

	wp_redirect ( admin_url() . 'admin.php?page=smgt_sms-setting&message=1');
}
?>
<div class="page-inner">
<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?></h3>
	</div>
	<div  id="main-wrapper" class="marks_list">
<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_attr__('SMS Settings Updated Successfully.','school-mgt');
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
    	<a href="?page=smgt_sms-setting" class="nav-tab margin_bottom  nav-tab-active">
		<?php echo '<span class="dashicons dashicons-awards"></span>'.esc_attr__('SMS Setting', 'school-mgt'); ?></a>
    </h2>
    
	<div class="panel-body"> 
    <form action="" method="post" class="form-horizontal" id="sms_setting_form">  
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end  " for="enable"><?php esc_attr_e('Select Message Service','school-mgt');?></label>
			<div class="col-sm-8">
				<div class="radio">
				 	<label>
  						<input id="checkbox" type="radio" <?php echo checked($current_sms_service_active,'clickatell');?>  name="select_serveice" class="label_set" value="clickatell"> <?php esc_attr_e('Clickatell','school-mgt');?> 
  					</label> 
  					&nbsp;&nbsp;&nbsp;&nbsp;
  					<label>
  						<input id="checkbox" type="radio"  <?php echo checked($current_sms_service_active,'msg91');?> name="select_serveice" class="label_set" value="msg91">  <?php esc_attr_e('MSG91','school-mgt');?>
  					</label>
  				</div>
			</div>
		</div>
    	
		<div id="sms_setting_block">
		<?php 
		if($current_sms_service_active == 'clickatell')
		{
			$clickatell=get_option( 'smgt_clickatell_sms_service');
			?>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end  " for="username"><?php esc_attr_e('Username','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="username" class="form-control validate[required]" type="text" value="<?php echo $clickatell['username'];?>" name="username">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end  " for="password"><?php esc_attr_e('Password','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="password" class="form-control validate[required]" type="text" value="<?php echo $clickatell['password'];?>" name="password">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end  " for="api_key"><?php esc_attr_e('API Key','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="api_key" class="form-control validate[required]" type="text" value="<?php echo $clickatell['api_key'];?>" name="api_key">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end  " for="sender_id"><?php esc_attr_e('Sender Id','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="sender_id" class="form-control validate[required]" type="text" value="<?php echo $clickatell['sender_id'];?>" name="sender_id">
			</div>
		</div>
		<?php 
		}
		if($current_sms_service_active == 'twillo')
		{
		}
		if($current_sms_service_active == 'msg91')
		{
			$msg91=get_option( 'smgt_msg91_sms_service');
			?>
			<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end  " for="sms_auth_key"><?php esc_attr_e('Authentication Key','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="sms_auth_key" class="form-control validate[required]" type="text" value="<?php echo $msg91['sms_auth_key'];?>" name="sms_auth_key">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end " for="msg91_senderID"><?php esc_attr_e('SenderID','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="msg91_senderID" class="form-control validate[required] text-input" type="text" name="msg91_senderID" value="<?php echo $msg91['msg91_senderID'];?>">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end " for="wpnc_sms_route"><?php esc_attr_e('Route','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="wpnc_sms_route" class="form-control validate[required] text-input" type="text" name="wpnc_sms_route" value="<?php echo $msg91['wpnc_sms_route'];?>">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-10 control-label col-form-label text-md-end " for="wpnc_sms_route"><b><?php esc_attr_e('If your operator supports multiple routes then give one route name. Eg: route=1 for promotional, route=4 for transactional SMS.','school-mgt');?></b></label>
		</div>	
		<?php 
		}
		?>
		</div>
    	
    	<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php  esc_attr_e('Save','school-mgt');?>" name="save_sms_setting" class="btn btn-success" />
        </div>
   
    </form>
</div>
    <div class="clearfix"> </div>
	 </div>
	 </div>
	 </div>    
</div>
<?php ?>