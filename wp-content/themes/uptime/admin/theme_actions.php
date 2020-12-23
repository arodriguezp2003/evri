<?php 

if(!( function_exists( 'tommusrhodus_elementor_progress_bar' ) )){
	function tommusrhodus_elementor_progress_bar(){
		
		global $post;
		
		if( isset( $post->ID ) ){
			
			if( 'elementor_header_footer' == get_page_template_slug( $post ) && 'yes' == get_post_meta( $post->ID, '_tommusrhodus_progress_bar', 1 ) ){
				get_template_part( 'inc/content-post', 'progress' );
			}
		
		}
		
	}
	add_action( 'wp_body_open', 'tommusrhodus_elementor_progress_bar', 10 );
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function tommusrhodus_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'tommusrhodus_pingback_header' );

function tommusrhodus_login_header_markup(){
    
    $output = '<section class="min-vh-100 py-2">';
    
    if( $image = get_theme_mod( 'login_background_image' ) ){
    	
    	$output = '
			<section class="row no-gutters min-vh-100 p-0">
				<div class="col-lg-4 bg-primary-3 d-flex justify-content-end">
				
					<img src="'. esc_url( $image ) .'" alt="Image" class="bg-image">
					
					<div class="divider divider-vertical d-none d-lg-block">
						<svg width="100%" height="100%" version="1.1" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none">
							<path d="M0,0 L100,0 L100,100 L0,100 C66.6666667,83.3333333 100,66.6666667 100,50 C100,33.3333333 66.6666667,16.6666667 0,0 Z" fill="#ffffff"></path>
						</svg>
					</div>
					
				</div>
				
				<div class="col px-5 position-relative d-flex align-items-center">
					<div class="row justify-content-center w-100">
						<div class="col-md-8 col-lg-7 col-xl-6">
    	';
    	
    }
    
	echo ( false == $output ) ? false : $output;
	
}
add_action( 'login_header', 'tommusrhodus_login_header_markup', 10 );

function tommusrhodus_login_footer_markup(){

	$output = '</section>';
	
	if( $image = get_theme_mod( 'login_background_image' ) ){		
		$output = '</div></div></div></section>';
	}
	
	echo ( false == $output ) ? false : $output;
	
}
add_action( 'login_footer', 'tommusrhodus_login_footer_markup', 10 );