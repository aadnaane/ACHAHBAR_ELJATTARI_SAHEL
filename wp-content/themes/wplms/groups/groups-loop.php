<?php

/**
 * BuddyPress - Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
if ( !defined( 'ABSPATH' ) ) exit;
?>

<?php do_action( 'bp_before_groups_loop' ); ?>

<?php if ( bp_get_current_group_directory_type() ) : ?>
	<p class="current-group-type"><?php bp_current_group_directory_type_message() ?></p>
<?php endif; ?>

<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' )) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="group-dir-count-top">

			<?php bp_groups_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="group-dir-pag-top">

			<?php bp_groups_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_directory_groups_list' ); ?>

	<ul id="groups-list" class="item-list" role="main">

	<?php while ( bp_groups() ) : bp_the_group(); ?>

		<li <?php bp_group_class(); ?>>
			<?php
			$view = apply_filters('wplms_directory_single_group_view','',bp_get_current_group_id());
			if(empty($view)){
			?>
			<div class="row">
				<div class="col-md-2 col-sm-3">
					<div class="item-avatar">
						<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=150&height=150' ); ?></a>
					</div>
				</div>
				<div class="col-md-10 col-sm-9">	
					<div class="item">
						<div class="item-title"><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></div>
						<div class="meta">
							<?php bp_group_type(); ?> / <?php bp_group_member_count(); ?>
						</div>
						<div class="item-meta"><span class="activity"><?php printf( __( 'active %s', 'vibe' ), bp_get_group_last_active() ); ?></span></div>
						<div class="item-desc"><?php bp_group_description_excerpt(); ?></div>
						<?php do_action( 'bp_directory_groups_item' ); ?>
						<div class="action">
							<?php do_action( 'bp_directory_groups_actions' ); ?>
						</div>
					</div>						
				</div>
			</div>
		</li>

	<?php }
	endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_groups_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="group-dir-count-bottom">

			<?php bp_groups_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="group-dir-pag-bottom">

			<?php bp_groups_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'There were no groups found.', 'vibe' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_groups_loop' ); ?>
