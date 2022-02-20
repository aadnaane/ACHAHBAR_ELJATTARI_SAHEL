<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 */

defined( 'ABSPATH' ) || exit;
?>
<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package eCademy
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="page-content" class="page-content ptb-50 buddypress-page-eca">
	<div id="buddypress" class="container buddypress-wrap bp-dir-hori-nav">

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
                    <?php dynamic_sidebar( 'groups_sidebar' ); ?>
                </div>
            </div>
		</div>

	</div>
</div>
