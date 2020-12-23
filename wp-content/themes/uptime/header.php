<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="//schema.org/WebPage" data-smooth-scroll-offset="<?php echo esc_attr( get_theme_mod( 'smooth_scroll_offset', '0' ) ); ?>">
	
<?php 
	// WP 5.2 new action hook
	do_action( 'wp_body_open' );
	
	get_template_part( 'inc/content', 'preloader' ); 

	/*
	* Elementor Pro Compatibility
	* 
	* Checks first that Elementor Pro is functioning, 
	* then checks if a custom header has been set.
	* 
	* If neither of these are true, we fall back to the default theme header.
	*/
	if ( !function_exists( 'elementor_theme_do_location' ) || !elementor_theme_do_location( 'header' ) ) : 
?>

<div class="navbar-container">
	<?php 
		// Header utility bar
		if( 'yes' == get_theme_mod( 'header_utility_bar', 'no' ) ){
			get_template_part( 'inc/content', 'header-utility' );
		}
		
		/**
		 * Get header layout by theme option
		 * Overrides handled by theme_filters by pre-filtering the theme_mod call
		 */
		get_template_part( 'inc/layout-header', get_theme_mod( 'header_layout', 'dark' ) ); 
	?>
</div>

<?php endif;