<?php
/**
 * Template for displaying Enroll button in single course.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/single-course/buttons/enroll.php.
 *
 * @author  ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

if ( ! isset( $course ) ) {
	$course = learn_press_get_course();
}

global $ecademy_opt;
if( isset( $ecademy_opt['buy_course_title'] ) ):
    $buy_course_title   = $ecademy_opt['buy_course_title'];
else:
    $buy_course_title   = esc_html__('Buy Course', 'ecademy');
endif;

if( get_field( 'course_buy_now_title' ) != '' ):
    $buy_course_title   = get_field( 'course_buy_now_title' );
endif;
?>

<?php do_action( 'learn-press/before-enroll-form' ); ?>

<form name="enroll-course" class="enroll-course" method="post" enctype="multipart/form-data">

	<?php do_action( 'learn-press/before-enroll-button' ); ?>

	<input type="hidden" name="enroll-course" value="<?php echo esc_attr( $course->get_id() ); ?>"/>

	<button class="lp-button button button-enroll-course default-btn">
        <i class="flaticon-tag"></i>
		<?php echo esc_html( apply_filters( 'learn-press/enroll-course-button-text', esc_html( $buy_course_title) ) ); ?>
        <span></span>
	</button>

	<?php do_action( 'learn-press/after-enroll-button' ); ?>

</form>

<?php do_action( 'learn-press/after-enroll-form' ); ?>
