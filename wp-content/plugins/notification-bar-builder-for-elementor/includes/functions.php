<?php
/**
 * Get Elementor template contents by ID
 */
function nbbfeml_template_by_id( $post_id ){
	if( !class_exists('\Elementor\Plugin') ){
	    return '';
	}

	if( empty($post_id) ){
	    return '';
	}

	$response = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post_id );

	return $response;
}

/**
 * Get elementor document meta value
 */
function nbbfeml_get_elementor_document_meta( $post_id, $key ){
    // Get the page settings manager
    $page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );

    // Get the settings model for current post
    $page_settings_model = $page_settings_manager->get_model( $post_id );

    // Retrieve value
    $value = $page_settings_model->get_settings( $key );

    return $value;
}

/**
 * Generate markup to send localized js
 */
function nbbfeml_prepare_markup_to_render(){
	$args = array(
		'post_type' => 'nbbfem_template',
		'posts_per_page' => 100,
	);

	$current_page_id = get_the_ID();
	$html = '';
	$dont_show_after_close_status = '';
	$coockie_age = '';

	$templates = get_posts($args);
	if ( $templates ) {
		foreach ( $templates as $template ) {
		setup_postdata( $template );

			$html .= nbbfeml_get_notice_markup_by_condition($template->ID, $current_page_id);
			$dont_show_after_close_status = nbbfeml_get_elementor_document_meta($template->ID, 'dont_show_after_close_status');
			$coockie_age = nbbfeml_get_elementor_document_meta($template->ID, 'coockie_age');

    	} // foreach

    	/* Restore original Post Data */
    	wp_reset_postdata();

  	} //if

	$localized_vars = array();
	$localized_vars['markup'] = $html;
	wp_localize_script( "nbbfem-main", "nbbfem_localize", $localized_vars );
}


/**
 * Generate notice markup 
 */
function nbbfeml_get_notice_markup_by_condition($post_id, $current_page_id){
	$html = '';
	$post_status = get_post_status($post_id);
	$display_on = nbbfeml_get_elementor_document_meta($post_id, 'nbbfem_display_on');

	if($post_status != 'publish' || !$display_on){
		return;
	}

	// home page check
	if($display_on == 'only_home'){
		$home_page_id = get_option( 'page_for_posts' );
		if( !is_front_page() ){
			return;
		}
	}

	// shop page check
	if($display_on == 'only_shop' && function_exists('is_shop')){
		$shop_page_id = get_option( 'woocommerce_shop_page_id' );
		if( !is_shop() ){
			return;
		}
	}

	if($display_on == 'specific_ids'){
		$specific_ids = nbbfeml_get_elementor_document_meta($post_id, 'specific_ids');
		$specific_ids = explode(',', $specific_ids);
		if( !in_array($current_page_id, $specific_ids) ){
			return;
		}
	}

	if($display_on == 'all_pages' ){
		$exclude_ids = nbbfeml_get_elementor_document_meta($post_id, 'all_pages_exclude');
		$exclude_ids = explode(',', $exclude_ids);
		if( get_post_type($current_page_id) != 'page'){
			return;
		}
	}

	if($display_on == 'all_posts' ){
		$exclude_ids = nbbfeml_get_elementor_document_meta($post_id, 'all_posts_exclude');
		$exclude_ids = explode(',', $exclude_ids);
		if( get_post_type($current_page_id) != 'post'){
			return;
		}
	}

	$position = nbbfeml_get_elementor_document_meta($post_id, 'position');
	$dont_show_after_close_status = nbbfeml_get_elementor_document_meta($post_id, 'dont_show_after_close_status');
	$coockie_age = nbbfeml_get_elementor_document_meta($post_id, 'coockie_age');
	$close_icon_position = nbbfeml_get_elementor_document_meta($post_id, 'close_icon_position');
	$notice_class_arr = array(
	    'nbbfem_notice',
	    'nbbfem_notice_pos__'. $position,
	    'nbbfem_close_status__'. $dont_show_after_close_status,
	    'nbbfem_close_pos__'. $close_icon_position,
	);

	// products
	if($display_on == 'all_products' ){
		$exclude_ids = nbbfeml_get_elementor_document_meta($post_id, 'all_products_exclude');
		$exclude_ids = explode(',', $exclude_ids);
		if( get_post_type($current_page_id) != 'product'){
			return;
		}
	}

	$html = '<div id="nbbfem_notice" class="'. esc_attr( implode(' ', $notice_class_arr) ) .'" data-nbbfem_dont_show_after_close_status=" '. esc_attr($dont_show_after_close_status) .'" data-nbbfem_cookie_age="'. esc_attr($coockie_age) .'">';
	if($dont_show_after_close_status == 'yes'){
		$html .= '<div class="nbbfem_close_btn"><span>x</span></div>';
	}
	$html .= nbbfeml_template_by_id($post_id) .'</div>';
	return $html;
}

/**
 * Canvas template wrapper open
 */
function nbbfeml_canvas_wrapper_open(){
	echo '<div class="nbbfem_notice">';
}

/**
 * Canvas template wrapper close
 */
function nbbfeml_canvas_wrapper_close(){
	echo '</div><!-- .nbbfem_notice -->';
}