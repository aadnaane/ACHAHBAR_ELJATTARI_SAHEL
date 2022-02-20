<?php

if ( ! function_exists( 'ecademy_remove_learnpress_hooks' ) ) {
	function ecademy_remove_learnpress_hooks() {

		add_action( 'ecademy_single_course_payment', LP()->template( 'course' )->func( 'course_pricing' ), 5 );
		add_action( 'ecademy_single_course_payment', LP()->template( 'course' )->func('course_buttons'), 15 );

		add_action(
			'init', function () {

			if ( class_exists( 'LP_Addon_Wishlist' ) && is_user_logged_in() && ecademy_is_version_addons_wishlist( '3' ) ) {
				$instance_addon = LP_Addon_Wishlist::instance();
				remove_action( 'learn-press/after-course-buttons', array( $instance_addon, 'wishlist_button' ), 100 );
				add_action( 'ecademy_after_course_info', array( $instance_addon, 'wishlist_button' ), 10 );
				add_action( 'ecademy_inner_thumbnail_course', array( $instance_addon, 'wishlist_button' ), 10 );
			}
			if ( class_exists( 'LP_Addon_bbPress' )  ) {
				$instance_addon = LP_Addon_bbPress::instance();
				remove_action( 'learn-press/single-course-summary', array( $instance_addon, 'forum_link' ), 0 );
			}
			if ( class_exists( 'LP_Addon_Woo_Payment' )  ) {
				$instance_addon = LP_Addon_Woo_Payment::instance();
				remove_action(
					'learn-press/before-course-buttons', array(
						$instance_addon,
						'purchase_course_notice'
					)
				);
				remove_action( 'learn-press/after-course-buttons', array( $instance_addon, 'after_course_buttons' ) );
			}
 			if ( class_exists( 'LP_Addon_Assignment' ) ) {
				$instance_addon = LP_Addon_Assignment::instance();
				remove_action(
					'learn-press/course-section-item/before-lp_assignment-meta', array(
					$instance_addon,
					'learnpress_assignment_show_duration'
				), 10
				);
				add_action( 'learn-press/course-section-item/before-lp_assignment-meta', 'ecademy_assignment_show_duration', 10 );
				if ( ! function_exists( 'ecademyecademy_assignment_show_duration_assignment_show_duration' ) ) {
					function ecademy_assignment_show_duration( $item ) {
						$duration = get_post_meta( $item->get_id(), '_lp_duration', true );
						if ( absint( $duration ) > 1 ) {
							$duration .= 's';
						}
						$duration_number = absint( $duration );
						$time            = trim( str_replace( $duration_number, '', $duration ) );
						switch ( $time ) {
							case 'minutes' :
								$time = _x( "minutes", 'duration', 'eduma' );
								break;
							case 'hours' :
								$time = _x( "hours", 'duration', 'eduma' );
								break;
							case 'days' :
								$time = _x( "days", 'duration', 'eduma' );
								break;
							case 'weeks':
								$time = _x( "weeks", 'duration', 'eduma' );
								break;
							case 'minute' :
								$time = _x( "minute", 'duration', 'eduma' );
								break;
							default:
								$time = _x( "week", 'duration', 'eduma' );
						}
						echo '<span class="meta duration">' . $duration_number . ' ' . $time . '</span>';
					}
				}
			}

			if ( class_exists( 'LP_Addon_Coming_Soon_Courses' )  ) {
 				$instance_addon = LP_Addon_Coming_Soon_Courses::instance();
  				remove_action( 'learn-press/course-content-summary', array( $instance_addon, 'coming_soon_countdown' ), 10 );
  				add_action( 'learn-press/single-course-summary', array( $instance_addon, 'coming_soon_countdown' ), 5 );
  			}
		}, 99
		);


	}
}
add_action( 'after_setup_theme', 'ecademy_remove_learnpress_hooks', 15 );