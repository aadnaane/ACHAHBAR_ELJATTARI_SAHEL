<?php
/**
 * Template for displaying archive course content.
 *
 * This template can be overridden by copying it to ecademy/learnpress/content-archive-course.php
 *
 * @author  EnvyTheme
 * @package LearnPress/Templates
 * @version 4.0.0
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

/**
 * @since 4.0.0
 *
 * @see LP_Template_General::template_header()
 */
do_action( 'learn-press/template-header' );

global $post, $wp_query, $lp_tax_query, $wp_query;

$total = $wp_query->found_posts;

if ( $total == 0 ) {
	$message = '<p class="message message-error">' . esc_html__( 'No courses found!', 'ecademy' ) . '</p>';
	$index   = esc_html__( 'There are no available courses!', 'ecademy' );
} elseif ( $total == 1 ) {
	$index = esc_html__( 'Showing only one result', 'ecademy' );
} else {
	$courses_per_page = absint( LP()->settings->get( 'archive_course_limit' ) );
	$paged            = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

	$from = 1 + ( $paged - 1 ) * $courses_per_page;
	$to   = ( $paged * $courses_per_page > $total ) ? $total : $paged * $courses_per_page;

	if ( $from == $to ) {
		$index = sprintf(
			esc_html__( 'Showing last course of %s results', 'ecademy' ),
			$total
		);
	} else {
		$index = sprintf(
			esc_html__( 'Showing %s-%s of %s results', 'ecademy' ),
			$from,
			$to,
			$total
		);
	}
}

global $ecademy_opt;

if( isset( $ecademy_opt['enable_lazyloader'] ) ):
	$is_lazyloader = $ecademy_opt['enable_lazyloader'];
else:
	$is_lazyloader = true;
endif;

$is_breadcrumb      	= isset( $ecademy_opt['is_breadcrumb']) ? $ecademy_opt['is_breadcrumb'] : '1';
$is_shape_image     	= isset( $ecademy_opt['enable_shape_images']) ? $ecademy_opt['enable_shape_images'] : '1';
$course_page_bg_image   = isset( $ecademy_opt['course_page_bg_image']['url']) ? $ecademy_opt['course_page_bg_image']['url'] : '';

/**
 * LP Hook
 */
do_action( 'learn-press/before-main-content' );

$page_title = learn_press_page_title( false );
?>
<?php if ( $page_title ) : ?>
	<div class="page-title-area" style="background-image:url(<?php echo esc_url($course_page_bg_image); ?>);">
		<div class="container">
			<div class="page-title-content">
				<h2><?php echo esc_html($page_title); ?></h2>
				<?php do_action( 'lp/template/archive-course/description' ); ?>

				<?php if( $is_breadcrumb == '1' ): ?>
					<?php if(class_exists( 'bbPress' ) && is_bbpress()) { ?>
						<div class="bbpress-breadcrumbs"></div>
						<?php
					}elseif ( function_exists('yoast_breadcrumb') ) {
						yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
					}else{ ?>
						<ul>
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e('Home', 'ecademy'); ?></a></li>
							<li><?php echo esc_html($page_title); ?></li>
						</ul>
					<?php } ?>
				<?php endif; ?>
			</div>
		</div>
		<?php if( $is_shape_image == '1' && isset( $ecademy_opt['shape_image1']['url'] )): ?>
        <?php if( $ecademy_opt['shape_image1']['url'] != '' ): ?>
            <div class="shape9">
                <?php if( $is_lazyloader == true ): ?>
                    <img sm-src="<?php echo esc_url( $ecademy_opt['shape_image1']['url'] ); ?>" alt="<?php esc_attr_e( 'Shape Image One', 'ecademy' ); ?>">
                <?php else: ?>
                    <img src="<?php echo esc_url( $ecademy_opt['shape_image1']['url'] ); ?>" alt="<?php esc_attr_e( 'Shape Image One', 'ecademy' ); ?>">
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
	</div>

<?php endif; ?>

<div class="courses-area courses-section pt-100 pb-70 lp-content-area">
	<div class="container">
		<div class="row">
			<?php if ( is_active_sidebar( 'archive-courses-sidebar' ) ): ?>
				<div class="col-lg-8 col-md-12">
			<?php else: ?>
				<div class="col-lg-12 col-md-12">
			<?php endif; ?>
			<div class="ecademy-grid-sorting row align-items-center">
				<div class="col-lg-6 col-md-6 result-count">
					<p><?php echo wp_kses_post( $index ); ?></p>
				</div>

				<div class="col-lg-6 col-md-6 ordering">
					<div class="topbar-search">
						<form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'lp_course' ) ); ?>">
							<label><i class="bx bx-search"></i></label>
							<input type="text" value="" name="s" placeholder="<?php esc_attr_e( 'Search our courses', 'ecademy' ) ?>" class="input-search" autocomplete="off" />
							<input type="hidden" value="course" name="ref" />
							<input type="hidden" name="post_type" value="lp_course">
						</form>
					</div>
				</div>
			</div>
				<div class="row justify-content-center">
					<?php

					LP()->template( 'course' )->begin_courses_loop();

					if ( lp_is_archive_course_load_via_api() ) {
						echo '<div class="lp-archive-course-skeleton" style="width:100%">';
						echo lp_skeleton_animation_html( 10, 'random', 'height:20px', 'width:100%' );
						echo '</div>';
					} else {
						if ( have_posts() ) {
							while ( have_posts() ) :
								the_post();

								learn_press_get_template_part( 'content', 'course' );

							endwhile;
						} else {
							LP()->template( 'course' )->no_courses_found();
						}
					}

					LP()->template( 'course' )->end_courses_loop();

					/**
					 * @since 3.0.0
					 */
					do_action( 'learn-press/after-courses-loop' );
					?>
				</div>
			</div>

			<?php if ( is_active_sidebar( 'archive-courses-sidebar' ) ): ?>
				<div class="col-lg-4 col-md-12">
					<div id="secondary" class="sidebar">
						<?php dynamic_sidebar('archive-courses-sidebar'); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php
			/**
			 * LP Hook
			 */
			do_action( 'learn-press/after-main-content' );
			?>
		</div>
	</div>
</div>

<?php
/**
 * @since 4.0.0
 *
 * @see   LP_Template_General::template_footer()
 */
do_action( 'learn-press/template-footer' );