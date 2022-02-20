<!doctype html>
<?php if( ecademy_rtl() == true ): ?><html dir="rtl" <?php language_attributes(); ?>>
<?php else: ?><html <?php language_attributes(); ?>><?php endif; ?>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<?php ecademy_preloader(); ?>

	<?php
	$header_style = '';
	global $ecademy_opt;
	if ( !empty($ecademy_opt['header_style']) && ($ecademy_opt['header_style'] != '' ) ):
		$header_style 			= new WP_Query(array(
			'post_type' 		=> 'header',
			'posts_per_page' 	=> -1,
			'p' 				=> $ecademy_opt['header_style'],
		));
	endif;

	if ( $header_style != '' ):
		if ( $header_style->have_posts() ):
			while ( $header_style->have_posts() ) : $header_style->the_post();
				the_content();
			endwhile;
			wp_reset_postdata();
		endif;
	else:
		ecademy_header_area(); // Header Area
	endif;