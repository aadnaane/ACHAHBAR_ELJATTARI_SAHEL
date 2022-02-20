<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'setup';
?>
<div id="cmgt_imgSpinner1">
	
</div>
<div class="cmgt_ajax-ani"></div>
<div class="cmgt_ajax-img"><img src="<?php echo SMS_PLUGIN_URL.'/assets/images/loading.gif';?>" height="50px" width="50px"></div>
<div class="page-inner" style="min-height:1088px !important">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'smgt_school_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'smgt_school_name' );?>
		</h3>
	</div>
<?php 
if(isset($_REQUEST['varify_key']))
{
	 if($verify_result['cmgt_verify'] != '0')
     $verify_result = mj_smgt_submit_setupform($_POST);
	{
		echo '<div id="message" class="alert updated notice notice-success is-dismissible alert-dismissible"><p>'.$verify_result['message'].'</p>
	 <button class="notice-dismiss" type="button" data-dismiss="alert"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	}
}
?>
<script type="text/javascript" src="<?php echo SMS_PLUGIN_URL.'/assets/js/pages/setup.js'; ?>" ></script>

<?php 
if(isset($_SESSION['cmgt_verify']) && $_SESSION['cmgt_verify'] == '3')
{
	?>
<div id="message" class="updated notice notice-success">
<?php esc_attr_e('There seems to be some problem please try after sometime or contact us on sales@dasinfomeida.com','school-mgt');?>
	</div>
<?php 
}
elseif(isset($_SESSION['cmgt_verify']) && $_SESSION['cmgt_verify'] == '1')
{
	?>
<div id="message" class="updated notice notice-success">
<?php esc_attr_e('Please provide correct Envato purchase key.','school-mgt');?>
</div>
<?php 
}
else
{
?>
<div id="message" class="updated notice notice-success" style="display:none;"></div>
<?php }?>

	<div id="main-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-body">
	
  <form name="verification_form" action="" method="post" class="form-horizontal" id="verification_form">
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end " for="domain_name">
			<?php esc_attr_e('Domain','school-mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="server_name" class="form-control validate[required]" type="text" 
				value="<?php echo $_SERVER['SERVER_NAME'];?>" name="domain_name" readonly>
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="licence_key"><?php esc_attr_e('Envato License key','school-mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="licence_key" class="form-control validate[required]" type="text"  value="" name="licence_key">
			</div>
		</div>
		<div class="mb-3 form-group row">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="enter_email"><?php esc_attr_e('Email','school-mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="enter_email" class="form-control validate[required,custom[email]]" type="text"  value="" name="enter_email">
			</div>
		</div>
		<div class="offset-sm-2 col-sm-8">
        	<input type="submit" value="<?php esc_attr_e('Submit','school-mgt');?>" name="varify_key" id="varify_key_new" class="btn btn-success"/>
        </div>
	</form>
	
</div>
			
	</div>
	</div>
</div>
<div>
