<?php 

/**
 * Queue up all admin theme files.
 * Filters and functions etc. are logically split for easier management.
 */
require_once get_parent_theme_file_path( '/admin/theme_icons.php' );
require_once get_parent_theme_file_path( '/admin/theme_layouts.php' );
require_once get_parent_theme_file_path( '/admin/theme_functions.php' );
require_once get_parent_theme_file_path( '/admin/theme_filters.php' );
require_once get_parent_theme_file_path( '/admin/theme_actions.php' );
require_once get_parent_theme_file_path( '/admin/theme_menus_widgets.php' );
require_once get_parent_theme_file_path( '/admin/theme_support.php' );
require_once get_parent_theme_file_path( '/admin/theme_scripts.php' );
require_once get_parent_theme_file_path( '/admin/tr_framework_init.php' );
require_once get_parent_theme_file_path( '/admin/customizer_init.php' );

/**
 * Some parts of the framework only need to run on admin views.
 * These would be those.
 * Calling these only on admin saves some operation time for the theme, everything in the name of speed.
 */
if( is_admin() ){

	if (!( class_exists( 'TGM_Plugin_Activation' ) )){
		require_once get_parent_theme_file_path( '/admin/class-tgm-plugin-activation.php' );
	}
		
	require_once get_parent_theme_file_path( '/admin/theme_metaboxes.php' );
	
	/**
	 * Configure Merlin - The theme auto starter
	 */
	require_once get_parent_theme_file_path( '/admin/merlin_config.php' );
	
}

if( class_exists( 'WooCommerce' ) ){
	require_once get_parent_theme_file_path( '/admin/woocommerce_init.php' );
}