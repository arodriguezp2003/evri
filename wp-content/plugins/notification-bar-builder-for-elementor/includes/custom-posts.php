<?php
/**
 * Register Post type
 */
function nbbfeml_custom_posts() {
	$labels = array(
		'name'                  => _x( 'Notification Bars', 'Notification Bars', 'nbbfem' ),
		'singular_name'         => _x( 'Notification Bar', 'Notice', 'nbbfem' ),
		'menu_name'             => esc_html( 'Notification Bars', 'nbbfem' ),
		'name_admin_bar'        => esc_html( 'Notice', 'nbbfem' ),
		'archives'              => esc_html( 'Notice Archives', 'nbbfem' ),
		'attributes'            => esc_html( 'Notice Attributes', 'nbbfem' ),
		'parent_item_colon'     => esc_html( 'Parent Notice:', 'nbbfem' ),
		'all_items'             => esc_html( 'All Notice', 'nbbfem' ),
		'add_new_item'          => esc_html( 'Add New Notice', 'nbbfem' ),
		'add_new'               => esc_html( 'Add New', 'nbbfem' ),
		'new_item'              => esc_html( 'New Notice', 'nbbfem' ),
		'edit_item'             => esc_html( 'Edit Notice', 'nbbfem' ),
		'update_item'           => esc_html( 'Update Notice', 'nbbfem' ),
		'view_item'             => esc_html( 'View Notice', 'nbbfem' ),
		'view_items'            => esc_html( 'View Notice', 'nbbfem' ),
		'search_items'          => esc_html( 'Search Notice', 'nbbfem' ),
		'not_found'             => esc_html( 'Not found', 'nbbfem' ),
		'not_found_in_trash'    => esc_html( 'Not found in Trash', 'nbbfem' ),
		'items_list'            => esc_html( 'Notice list', 'nbbfem' ),
		'items_list_navigation' => esc_html( 'Notice list navigation', 'nbbfem' ),
		'filter_items_list'     => esc_html( 'Filter notice list', 'nbbfem' ),
	);
	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'show_in_nav_menus'   => false,
		'exclude_from_search' => true,
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'menu_icon'           => 'dashicons-megaphone',
		'supports'            => [ 'title', 'elementor' ],
	);
	register_post_type( 'nbbfem_template', $args );
	
}
add_action( 'init', 'nbbfeml_custom_posts');


/**
 * Set canvas template for our post type
 */
function nbbfeml_force_canvas_template_for_post_type() {
    global $post;

    // Check if its a correct post type/types to apply template
    if ( ! in_array( $post->post_type, [ 'nbbfem_template' ] ) || ! did_action( 'elementor/loaded' ) ) {
        return;
    }

    // Check that a template is not set already
    if ( '' !== $post->page_template ) {
        return;
    }

    // Make sure its not a page for posts
    if ( get_option( 'page_for_posts' ) === $post->ID ) {
        return;
    }

    //Finally set the page template
    $post->page_template = 'elementor_canvas';
    update_post_meta($post->ID, '_wp_page_template', 'elementor_canvas'); 
}
add_action('add_meta_boxes', 'nbbfeml_force_canvas_template_for_post_type', 10 );