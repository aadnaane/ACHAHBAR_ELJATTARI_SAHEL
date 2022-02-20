<?php
/**
 * Template for displaying profile header.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

$profile = LP_Profile::instance();
$user    = $profile->get_user();
$user_id = $user->get_id();
global $ecademy_opt;
$massage_btn_text = !empty($ecademy_opt['massage_btn_text']) ? $ecademy_opt['massage_btn_text'] : '';

if ( ! isset( $user ) ) {
	return;
}

$bio = $user->get_description();
?>

<div class="lp-profile-right">

	<div class="lp-profile-username">
		<?php echo $user->get_display_name(); ?>
	</div>
	<?php if ( $bio ) : ?>
		<div class="lp-profile-user-bio">
			<?php echo wpautop( $bio ); ?>
		</div>
	<?php endif; ?>

    <ul class="social-link">
        <?php
        if( have_rows('user_social_links', 'user_'. $user_id ) ):
            while ( have_rows('user_social_links', 'user_'. $user_id ) ) : the_row();
            ?>
                <li><a href="<?php echo esc_url( the_sub_field('user_social_link')); ?>" class="d-block" target="_blank"><i class="<?php echo esc_attr( the_sub_field('user_select_social_icon') ); ?>"></i></a></li>
            <?php
            endwhile;
        endif;
        ?>
    </ul>
    <?php if( $massage_btn_text != '' ):
        $massage_btn_link = get_page_link($ecademy_opt['massage_btn_link']);
        ?>
        <a href="<?php echo esc_url( $massage_btn_link ); ?>" class="default-btn">
            <i class="flaticon-user"></i>
            <?php  echo esc_html( $massage_btn_text ); ?><span></span>
        </a>
    <?php endif; ?>
</div>