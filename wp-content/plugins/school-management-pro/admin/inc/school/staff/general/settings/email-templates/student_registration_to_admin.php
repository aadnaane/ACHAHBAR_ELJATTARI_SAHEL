<?php
defined( 'ABSPATH' ) || die();

// Email Student Registration to Admin settings.
$settings_email_student_registration_to_admin = WLSM_M_Setting::get_settings_email_student_registration_to_admin( $school_id );
$email_student_registration_to_admin_enable   = $settings_email_student_registration_to_admin['enable'];
$email_student_registration_to_admin_subject  = $settings_email_student_registration_to_admin['subject'];
$email_student_registration_to_admin_body     = $settings_email_student_registration_to_admin['body'];

$email_student_registration_to_admin_placeholders = WLSM_Email::student_registration_to_admin_placeholders();
?>
<button type="button" class="mt-2 btn btn-block btn-primary" data-toggle="collapse" data-target="#wlsm_email_student_registration_to_admin_fields" aria-expanded="true" aria-controls="wlsm_email_student_registration_to_admin_fields">
	<?php esc_html_e( 'Student Registration to Admin Email Template', 'school-management' ); ?>
</button>

<div class="collapse border border-top-0 border-primary p-3" id="wlsm_email_student_registration_to_admin_fields">

	<div class="wlsm_email_template wlsm_email_student_registration_to_admin">
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_email_student_registration_to_admin_enable" class="wlsm-font-bold">
					<?php esc_html_e( 'Student Registration to Admin Email', 'school-management' ); ?>:
				</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<label for="wlsm_email_student_registration_to_admin_enable" class="wlsm-font-bold">
						<input <?php checked( $email_student_registration_to_admin_enable, true, true ); ?> type="checkbox" name="email_student_registration_to_admin_enable" id="wlsm_email_student_registration_to_admin_enable" value="1">
						<?php esc_html_e( 'Enable', 'school-management' ); ?>
					</label>
				</div>
			</div>
		</div>
	</div>

	<div class="wlsm_email_template wlsm_email_student_registration_to_admin mb-3">
		<div class="row">
			<div class="col-md-12">
				<span class="wlsm-font-bold text-dark"><?php esc_html_e( 'You can use the following variables:', 'school-management' ); ?></span>
				<div class="row">
					<?php foreach ( $email_student_registration_to_admin_placeholders as $key => $value ) { ?>
					<div class="col-sm-6 col-md-3 pb-1 pt-1 border">
						<span class="wlsm-font-bold text-secondary"><?php echo esc_html( $value ); ?></span>
						<br>
						<span><?php echo esc_html( $key ); ?></span>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<div class="wlsm_email_template wlsm_email_student_registration_to_admin">
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_email_student_registration_to_admin_subject" class="wlsm-font-bold"><?php esc_html_e( 'Email Subject', 'school-management' ); ?>:</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<input name="email_student_registration_to_admin_subject" type="text" id="wlsm_email_student_registration_to_admin_subject" value="<?php echo esc_attr( $email_student_registration_to_admin_subject ); ?>" class="form-control" placeholder="<?php esc_attr_e( 'Email Subject', 'school-management' ); ?>">
				</div>
			</div>
		</div>
	</div>

	<div class="wlsm_email_template wlsm_email_student_registration_to_admin">
		<div class="row">
			<div class="col-md-3">
				<label for="wlsm_email_student_registration_to_admin_body" class="wlsm-font-bold"><?php esc_html_e( 'Email Body', 'school-management' ); ?>:</label>
			</div>
			<div class="col-md-9">
				<div class="form-group">
					<?php
					$settings = array(
						'media_buttons' => false,
						'textarea_name' => 'email_student_registration_to_admin_body',
						'textarea_rows' => 10,
						'wpautop'       => false,
					);
					wp_editor( wp_kses_post( stripslashes( $email_student_registration_to_admin_body ) ), 'wlsm_email_student_registration_to_admin_body', $settings );
					?>
				</div>
			</div>
		</div>
	</div>

	<?php
	$email_template = 'student_registration_to_admin';
	require WLSM_PLUGIN_DIR_PATH . 'admin/inc/school/staff/general/settings/email-templates/test_email.php';
	?>

</div>
