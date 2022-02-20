<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package eCademy
 * @since   4.9.2
 */

defined( 'ABSPATH' ) || exit;

global $post;
$d = $post->ID;
?>
<div id="page-content" class="page-content ptb-50 buddypress-page-eca">
	<div id="buddypress" class="container buddypress-wrap bp-dir-hori-nav">
		<?php if ( is_active_sidebar( 'activity_top_sidebar' ) ) : ?>
			<?php dynamic_sidebar( 'activity_top_sidebar' ); ?>
		<?php endif; ?>

		<div class="row tm-sticky-parent">
            <div class="col-lg-8">
                <div id="page-main-content" class="page-main-content">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php the_content(); ?>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="page-sidebar page-sidebar-right">
                    <?php dynamic_sidebar( 'activity_sidebar' ); ?>
                </div>
            </div>
		</div>

	</div>
</div>
