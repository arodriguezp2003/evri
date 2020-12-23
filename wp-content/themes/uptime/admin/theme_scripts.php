<?php 

if(!( function_exists( 'tommusrhodus_wp_login_styles' ) )){
	function tommusrhodus_wp_login_styles() {
		
		// Get theme data for cache busting
		$theme_data    = wp_get_theme();
		$version       = $theme_data->get( 'Version' );
		$custom_css    = '';
		
		wp_enqueue_style( 
			'tommusrhodus-theme', 
			get_theme_file_uri( 'style/css/theme.css' ), 
			array(), 
			$version 
		);
		
	    wp_enqueue_style( 
	    	'tommusrhodus-wp-login', 
	    	get_theme_file_uri( 'style/css/login.css' ), 
	    	array(), 
	    	$version
	    );
	    
	    if( $logo_image_url = get_theme_mod( 'login_logo' ) ){
	    	$custom_css .= '
	    		.login h1 a {
	    			background-image: url('. esc_url( $logo_image_url ) .');
	    		}
	    	';
	    }
	    
	    wp_add_inline_style( 'tommusrhodus-wp-login', $custom_css );
	
	}
	add_action( 'login_enqueue_scripts', 'tommusrhodus_wp_login_styles', 80 );
}

if(!( function_exists( 'tommusrhodus_generate_skin' ) )){
	function tommusrhodus_generate_skin() {

		global $post;
		
		$colours = array(
			'primary'          => '#3755BE',
			'primary_hover'    => '#2e48a0',
			'secondary'        => '#6c757d',
			'light'            => '#f8f9fa',
			'dark'             => '#212529',
			'primary_2_hover'  => '#FF8E88',
			'primary_2'        => '#FF8E88',
			'primary_3'        => '#1B1F3B',
			'bg_primary'       => '#3755BE',
			'bg_primary_alt'   => '#f3f5fb',
			'bg_secondary'     => '#6c757d',
			'bg_light'         => '#f8f9fa',
			'bg_dark'          => '#212529',
			'bg_primary_2'     => '#FF8E88',
			'bg_primary_2_alt' => '#f4f2f9',
			'bg_primary_3'     => '#1B1F3B'
		);
		
		foreach( $colours as $colour => $default ){
			${$colour} = get_theme_mod( $colour, $default );
		}
		
		if( isset( $post->ID ) ){
			
			$custom_colours = get_post_meta( $post->ID, '_tommusrhodus_custom_colours', 1 );
			
			if( !( 'no' == $custom_colours )){
				foreach( $colours as $colour => $default ){
					
					// Grab the post meta for this colour
					$check = get_post_meta( $post->ID, '_tommusrhodus_'. $colour .'_override', 1 );
					
					if( $check != '' ){
					
						// Compare the post meta
						if( strtolower( $check ) != strtolower( $default ) ){
							${$colour} = $check;
						}
					
					}
					
				}
			}
		
		}
		
		$body_text      = get_theme_mod( 'body_text', '#495057' );
		$body_font_name = get_theme_mod( 'body_font_name' );
		
		if( !$body_font_name ){
			$body_font_name = 'Inter UI';
		}		

		$logo_height = str_replace( 'px', '', get_theme_mod( 'logo_height', '26px' ) ) . 'px';

		$skin = '
			body, .elementor-widget-text-editor {
				font-family: "'. $body_font_name .'", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
				color: '. $body_text .';
			}
			.navbar-brand img {
				max-height: '. $logo_height .';
				width: auto;
			}			
			a:not(.card):not(.btn-primary):not(.nav-link):not(.text-white):not(.dropdown-item):not(.btn-outline-primary):not(.btn):not(.elementor-button):hover, section:not(.text-light) .nav-link, footer:not(.text-light) .nav-link, .widget a {
			    color: '. $primary .';
			}
			.btn-primary, .wp-block-button__link {
				color: #fff;
				background-color: '. $primary .';
				border-color: '. $primary .';
			}
			.btn-primary:hover, .wp-block-button__link:hover, a.badge-primary:hover, a.badge-primary:focus {
				color: #fff !important;
				background-color: '. $primary_hover .';
				border-color: '. $primary_hover .';
			}
			.btn-primary:focus, .btn-primary.focus {
				box-shadow: 0 0 0 0.2rem rgba(85, 111, 200, 0.5);
			}
			.btn-primary.disabled, .btn-primary:disabled {
				color: #fff;
				background-color: '. $primary .';
				border-color: '. $primary .';
			}
			.btn-primary:not(:disabled):not(.disabled):active, .btn-primary:not(:disabled):not(.disabled).active, .show>.btn-primary.dropdown-toggle {
				color: #fff;
				background-color: '. $primary_hover .';
				border-color: '. $primary_hover .';
			}
			.btn-primary:not(:disabled):not(.disabled):active:focus, .btn-primary:not(:disabled):not(.disabled).active:focus, .show>.btn-primary.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(85, 111, 200, 0.5);
			}
			.btn-secondary {
				color: #fff;
				background-color: '. $secondary .';
				border-color: '. $secondary .';
			}
			.btn-secondary:hover {
				color: #fff;
				background-color: #5a6268;
				border-color: #545b62;
			}
			.btn-secondary:focus, .btn-secondary.focus {
				box-shadow: 0 0 0 0.2rem rgba(130, 138, 145, 0.5);
			}
			.btn-secondary.disabled, .btn-secondary:disabled {
				color: #fff;
				background-color: '. $secondary .';
				border-color: '. $secondary .';
			}
			.btn-secondary:not(:disabled):not(.disabled):active, .btn-secondary:not(:disabled):not(.disabled).active, .show>.btn-secondary.dropdown-toggle {
				color: #fff;
				background-color: #545b62;
				border-color: #4e555b;
			}
			.btn-secondary:not(:disabled):not(.disabled):active:focus, .btn-secondary:not(:disabled):not(.disabled).active:focus, .show>.btn-secondary.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(130, 138, 145, 0.5);
			}
			.btn-light {
				color: '. $body_text .';
				background-color: '. $light .';
				border-color: '. $light .';
			}
			.btn-light:hover {
				color: '. $dark .';
				background-color: #e2e6ea;
				border-color: #dae0e5;
			}
			.btn-light:focus, .btn-light.focus {
				box-shadow: 0 0 0 0.2rem rgba(216, 217, 219, 0.5);
			}
			.btn-light.disabled, .btn-light:disabled {
				color: '. $dark .';
				background-color: '. $light .';
				border-color: '. $light .';
			}
			.btn-light:not(:disabled):not(.disabled):active, .btn-light:not(:disabled):not(.disabled).active, .show>.btn-light.dropdown-toggle {
				color: '. $dark .';
				background-color: #dae0e5;
				border-color: #d3d9df;
			}
			.btn-light:not(:disabled):not(.disabled):active:focus, .btn-light:not(:disabled):not(.disabled).active:focus, .show>.btn-light.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(216, 217, 219, 0.5);
			}
			.btn-dark {
				color: #fff;
				background-color: '. $dark .';
				border-color: '. $dark .';
			}
			.btn-dark:hover {
				color: #fff;
				background-color: #101214;
				border-color: #0a0c0d;
			}
			.btn-dark:focus, .btn-dark.focus {
				box-shadow: 0 0 0 0.2rem rgba(66, 70, 73, 0.5);
			}
			.btn-dark.disabled, .btn-dark:disabled {
				color: #fff;
				background-color: '. $dark .';
				border-color: '. $dark .';
			}
			.btn-dark:not(:disabled):not(.disabled):active, .btn-dark:not(:disabled):not(.disabled).active, .show>.btn-dark.dropdown-toggle {
				color: #fff;
				background-color: #0a0c0d;
				border-color: #050506;
			}
			.btn-dark:not(:disabled):not(.disabled):active:focus, .btn-dark:not(:disabled):not(.disabled).active:focus, .show>.btn-dark.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(66, 70, 73, 0.5);
			}
			.btn-primary-2 {
			    color: #fff;
				background-color: '. $primary_2 .';
				border-color: '. $primary_2 .';
			}
			.btn-primary-2:hover {
				color: #fff;
				background-color: #ff6a62;
				border-color: #ff5e55;
			}
			.btn-primary-2:focus, .btn-primary-2.focus {
				box-shadow: 0 0 0 0.2rem rgba(222, 126, 122, 0.5);
			}
			.btn-primary-2.disabled, .btn-primary-2:disabled {
				color: '. $dark .';
				background-color: '. $primary_2 .';
				border-color: '. $primary_2 .';
			}
			.btn-primary-2:not(:disabled):not(.disabled):active, .btn-primary-2:not(:disabled):not(.disabled).active, .show>.btn-primary-2.dropdown-toggle {
				color: #fff;
				background-color: #ff5e55;
				border-color: #ff5148;
			}
			.btn-primary-2:not(:disabled):not(.disabled):active:focus, .btn-primary-2:not(:disabled):not(.disabled).active:focus, .show>.btn-primary-2.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(222, 126, 122, 0.5);
			}
			.btn-primary-3 {
				color: #fff;
				background-color: '. $primary_3 .';
				border-color: '. $primary_3 .';
			}
			.btn-primary-3:hover {
				color: #fff;
				background-color: #0f1121;
				border-color: #0b0d18;
			}
			.btn-primary-3:focus, .btn-primary-3.focus {
				box-shadow: 0 0 0 0.2rem rgba(61, 65, 88, 0.5);
			}
			.btn-primary-3.disabled, .btn-primary-3:disabled {
				color: #fff;
				background-color: '. $primary_3 .';
				border-color: '. $primary_3 .';
			}
			.btn-primary-3:not(:disabled):not(.disabled):active, .btn-primary-3:not(:disabled):not(.disabled).active, .show>.btn-primary-3.dropdown-toggle {
				color: #fff;
				background-color: #0b0d18;
				border-color: #07080f;
			}
			.btn-primary-3:not(:disabled):not(.disabled):active:focus, .btn-primary-3:not(:disabled):not(.disabled).active:focus, .show>.btn-primary-3.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(61, 65, 88, 0.5);
			}
			.btn-outline-primary {
				color: '. $primary .';
				border-color: '. $primary .';
			}
			.btn-outline-primary:hover {
				color: #fff;
				background-color: '. $primary .';
				border-color: '. $primary .';
			}
			.btn-outline-primary:focus, .btn-outline-primary.focus {
				box-shadow: 0 0 0 0.2rem rgba(55, 85, 190, 0.5);
			}
			.btn-outline-primary.disabled, .btn-outline-primary:disabled {
				color: '. $primary .';
				background-color: transparent;
			}
			.btn-outline-primary:not(:disabled):not(.disabled):active, .btn-outline-primary:not(:disabled):not(.disabled).active, .show>.btn-outline-primary.dropdown-toggle {
				color: #fff;
				background-color: '. $primary .';
				border-color: '. $primary .';
			}
			.btn-outline-primary:not(:disabled):not(.disabled):active:focus, .btn-outline-primary:not(:disabled):not(.disabled).active:focus, .show>.btn-outline-primary.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(55, 85, 190, 0.5);
			}
			.btn-outline-secondary {
				color: '. $secondary .';
				border-color: '. $secondary .';
			}
			.btn-outline-secondary:hover {
				color: #fff;
				background-color: '. $secondary .';
				border-color: '. $secondary .';
			}
			.btn-outline-secondary:focus, .btn-outline-secondary.focus {
				box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5);
			}
			.btn-outline-secondary.disabled, .btn-outline-secondary:disabled {
				color: '. $secondary .';
				background-color: transparent;
			}
			.btn-outline-secondary:not(:disabled):not(.disabled):active, .btn-outline-secondary:not(:disabled):not(.disabled).active, .show>.btn-outline-secondary.dropdown-toggle {
				color: #fff;
				background-color: '. $secondary .';
				border-color: '. $secondary .';
			}
			.btn-outline-secondary:not(:disabled):not(.disabled):active:focus, .btn-outline-secondary:not(:disabled):not(.disabled).active:focus, .show>.btn-outline-secondary.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5);
			}
			.btn-outline-light {
				color: '. $light .';
				border-color: '. $light .';
			}
			.btn-outline-light:hover {
				color: '. $dark .';
				background-color: '. $light .';
				border-color: '. $light .';
			}
			.btn-outline-light:focus, .btn-outline-light.focus {
				box-shadow: 0 0 0 0.2rem rgba(248, 249, 250, 0.5);
			}
			.btn-outline-light.disabled, .btn-outline-light:disabled {
				color: '. $light .';
				background-color: transparent;
			}
			.btn-outline-light:not(:disabled):not(.disabled):active, .btn-outline-light:not(:disabled):not(.disabled).active, .show>.btn-outline-light.dropdown-toggle {
				color: '. $dark .';
				background-color: '. $light .';
				border-color: '. $light .';
			}
			.btn-outline-light:not(:disabled):not(.disabled):active:focus, .btn-outline-light:not(:disabled):not(.disabled).active:focus, .show>.btn-outline-light.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(248, 249, 250, 0.5);
			}
			.btn-outline-dark {
				color: '. $dark .';
				border-color: '. $dark .';
			}
			.btn-outline-dark:hover {
				color: #fff;
				background-color: '. $dark .';
				border-color: '. $dark .';
			}
			.btn-outline-dark:focus, .btn-outline-dark.focus {
				box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.5);
			}
			.btn-outline-dark.disabled, .btn-outline-dark:disabled {
				color: '. $dark .';
				background-color: transparent;
			}
			.btn-outline-dark:not(:disabled):not(.disabled):active, .btn-outline-dark:not(:disabled):not(.disabled).active, .show>.btn-outline-dark.dropdown-toggle {
				color: #fff;
				background-color: '. $dark .';
				border-color: '. $dark .';
			}
			.btn-outline-dark:not(:disabled):not(.disabled):active:focus, .btn-outline-dark:not(:disabled):not(.disabled).active:focus, .show>.btn-outline-dark.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.5);
			}
			.btn-outline-primary-2 {
				color: '. $primary_2 .';
				border-color: '. $primary_2 .';
			}
			.btn-outline-primary-2:hover {
				color: '. $dark .';
				background-color: '. $primary_2 .';
				border-color: '. $primary_2 .';
			}
			.btn-outline-primary-2:focus, .btn-outline-primary-2.focus {
				box-shadow: 0 0 0 0.2rem rgba(255, 142, 136, 0.5);
			}
			.btn-outline-primary-2.disabled, .btn-outline-primary-2:disabled {
				color: '. $primary_2 .';
				background-color: transparent;
			}
			.btn-outline-primary-2:not(:disabled):not(.disabled):active, .btn-outline-primary-2:not(:disabled):not(.disabled).active, .show>.btn-outline-primary-2.dropdown-toggle {
				color: '. $dark .';
				background-color: '. $primary_2 .';
				border-color: '. $primary_2 .';
			}
			.btn-outline-primary-2:not(:disabled):not(.disabled):active:focus, .btn-outline-primary-2:not(:disabled):not(.disabled).active:focus, .show>.btn-outline-primary-2.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(255, 142, 136, 0.5);
			}
			.btn-outline-primary-3 {
				color: '. $primary_3 .';
				border-color: '. $primary_3 .';
			}
			.btn-outline-primary-3:hover {
				color: #fff;
				background-color: '. $primary_3 .';
				border-color: '. $primary_3 .';
			}
			.btn-outline-primary-3:focus, .btn-outline-primary-3.focus {
				box-shadow: 0 0 0 0.2rem rgba(27, 31, 59, 0.5);
			}
			.btn-outline-primary-3.disabled, .btn-outline-primary-3:disabled {
				color: '. $primary_3 .';
				background-color: transparent;
			}
			.btn-outline-primary-3:not(:disabled):not(.disabled):active, .btn-outline-primary-3:not(:disabled):not(.disabled).active, .show>.btn-outline-primary-3.dropdown-toggle {
				color: #fff;
				background-color: '. $primary_3 .';
				border-color: '. $primary_3 .';
			}
			.btn-outline-primary-3:not(:disabled):not(.disabled):active:focus, .btn-outline-primary-3:not(:disabled):not(.disabled).active:focus, .show>.btn-outline-primary-3.dropdown-toggle:focus {
				box-shadow: 0 0 0 0.2rem rgba(27, 31, 59, 0.5);
			}
			.btn-link {
				font-weight: 400;
				color: '. $primary .';
				text-decoration: none;
			}
			.btn-link:hover {
				color: #263a83;
				text-decoration: none;
			}
			.btn-link:focus, .btn-link.focus {
				text-decoration: none;
				box-shadow: none;
			}
			.btn-link:disabled, .btn-link.disabled {
				color: '. $secondary .';
				pointer-events: none;
			}
			.bg-primary {
				background-color: '. $bg_primary .' !important;
			}
			.bg-primary-alt {
				background-color: '. $bg_primary_alt .' !important;
			}
			a.bg-primary:hover, a.bg-primary:focus, button.bg-primary:hover, button.bg-primary:focus {
				background-color: #2c4396 !important;
			}
			.bg-secondary {
				background-color: '. $bg_secondary .' !important;
			}
			.bg-light {
  				background-color: '. $bg_light .' !important; 
			}
			.bg-dark {
			  	background-color: '. $bg_dark .' !important; 
			}
			.navbar[data-sticky="top"].scrolled.navbar-dark {
				background: '. $bg_dark .' !important; 
			}
			a.bg-secondary:hover, a.bg-secondary:focus, button.bg-secondary:hover, button.bg-secondary:focus {
				background-color: #545b62 !important;
			}
			.bg-primary-2, .badge-primary-2 {
				background-color: '. $bg_primary_2 .' !important;
			}
			.bg-primary-2-alt {
				background-color: '. $bg_primary_2_alt .' !important;
			}
			a.bg-primary-2:hover, a.bg-primary-2:focus, button.bg-primary-2:hover, button.bg-primary-2:focus {
				background-color: #ff5e55 !important;
			}
			.bg-primary-3 {
				background-color: '. $bg_primary_3 .' !important;
			}
			a.bg-primary-3:hover, a.bg-primary-3:focus, button.bg-primary-3:hover, button.bg-primary-3:focus {
				background-color: #0b0d18 !important;
			}
			.border-primary {
				border-color: '. $bg_primary .' !important;
			}
			.border-secondary {
				border-color: '. $bg_secondary .' !important;
			}
			.border-light {
				border-color: '. $bg_light .' !important;
			}
			.border-dark {
				border-color: '. $bg_dark .' !important;
			}
			.border-primary-2 {
				border-color: '. $bg_primary_2 .' !important;
			}
			.border-primary-3 {
				border-color: '. $bg_primary_3 .' !important;
			}
			.elementor svg.icon *, .nav-link.btn-light .icon * {
				fill: '. $primary .'
			}			
			svg.bg-primary *, .accordion-panel-title[aria-expanded="true"] > * path, .accordion-panel-title:hover > * path {
			    fill: '. $bg_primary .'
			}
			svg.bg-primary-2 * {
			    fill: '. $bg_primary_2 .'
			}
			svg.bg-primary-3 * {
			    fill: '. $bg_primary_3 .'
			}
			svg.bg-secondary * {
			    fill:  '. $bg_secondary .'
			}
			svg.bg-light * {
			    fill: '. $bg_light .' !important;
			}
			svg.bg-white *, .btn.btn-primary * {
			    fill: #fff !important;
			}
			svg.bg-warning * {
			    fill: #ffc107 !important;
			}
			.icon-round.bg-primary {
			    background: rgba('. tommusrhodus_hex2rgb ( $bg_primary ) .', 0.1) !important;
			}
			.icon-round.bg-primary-2 {
			    background: rgba('. tommusrhodus_hex2rgb ( $bg_primary_2 ) .', 0.1) !important;
			}
			.icon-round.bg-primary-3 {
			    background: rgba('. tommusrhodus_hex2rgb ( $bg_primary_3 ) .', 0.1) !important;
			}
			.badge-primary, .process-circle.bg-primary:after  {
			    background-color: '. $primary .';
			}
			.text-primary, .btn-white, .text-white p a {
			    color: '. $primary .' !important;
			}
			.text-primary-2 {
			    color: '. $primary_2 .' !important;
			}
			.text-primary, .accordion-panel-title[aria-expanded="true"] > *, .accordion-panel-title:hover > * {
				color: '. $primary .' !important;
			}
			.highlight {
			    background: rgba('. tommusrhodus_hex2rgb ( $bg_primary_2 ) .', 0.2) !important;
			}
			.article thead th, .comment thead th {
				background: '. $primary .';
			}
			progress.reading-position::-webkit-progress-value {
			  background-color: '. $bg_primary_2 .';
			}
			progress.reading-position::-moz-progress-bar {
			  background-color: '. $bg_primary_2 .';
			}
		';
		
		if( 'yes' == get_theme_mod( 'disable_page_fade', 'no' ) ){
			$skin .= '
				body > div.loader {
					display: none !important;
				}
				.fade-page {
					opacity: 1 !important;
				}
			';
		}

		return $skin;
				
	}
}

/**
 * tommusrhodus_load_scripts()
 * 
 * Properly Enqueues Scripts & Styles for the theme
 * 
 * @documentation https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * @documentation https://developer.wordpress.org/reference/functions/wp_enqueue_script/
 * @since v1.0.0
 * @blame Tom Rhodes
 */ 
if(!( function_exists( 'tommusrhodus_load_scripts' ) )){
	function tommusrhodus_load_scripts() {
		
		// Get theme data for cache busting
		$theme_data    = wp_get_theme();
		$version       = $theme_data->get( 'Version' );

		// Enqueue Styles
		if( $font_url = tommusrhodus_google_fonts_url() ){
			wp_enqueue_style( 
				'tommusrhodus-google-font', 
				$font_url, 
				array(), 
				$version 
			);
		}
		
		wp_enqueue_style( 
			'flickity', 
			get_theme_file_uri( 'style/css/flickity.min.css' ), 
			array(), 
			$version 
		);
		
		wp_enqueue_style( 
			'tommusrhodus-theme', 
			get_theme_file_uri( 'style/css/theme.css' ), 
			array(), 
			$version 
		);
		
		wp_style_add_data( 'tommusrhodus-theme', 'rtl', 'replace' );
		
		if( class_exists( 'WP_Job_Manager' ) ){
			wp_enqueue_style( 
				'tommusrhodus-wp-job-manager', 
				get_theme_file_uri( 'style/css/wp-job-manager.css' ), 
				array(), 
				$version 
			);
		}
		
		wp_enqueue_style( 
			'tommusrhodus-style', 
			get_stylesheet_uri(), 
			array(), 
			$version 
		);
		
		// Enqueue Scripts
		wp_enqueue_script( 
			'bootstrap', 
			get_theme_file_uri( 'style/js/bootstrap.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		if( 'yes' == get_theme_mod( 'use_scroll_animations', 'yes' ) ){
		
			wp_enqueue_style( 
				'aos', 
				get_theme_file_uri( 'style/css/aos.css' ), 
				array(), 
				$version 
			);
			
			wp_enqueue_script( 
				'aos', 
				get_theme_file_uri( 'style/js/aos.js' ), 
				array('jquery'), 
				$version, 
				true  
			);
		
		}
		
		wp_enqueue_script( 
			'fancybox', 
			get_theme_file_uri( 'style/js/jquery.fancybox.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'flickity', 
			get_theme_file_uri( 'style/js/flickity.pkgd.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'ion-range-slider', 
			get_theme_file_uri( 'style/js/ion.rangeSlider.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'isotope', 
			get_theme_file_uri( 'style/js/isotope.pkgd.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
	
		wp_enqueue_script( 
			'countdown', 
			get_theme_file_uri( 'style/js/jquery.countdown.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'plyr', 
			get_theme_file_uri( 'style/js/plyr.polyfilled.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'prism', 
			get_theme_file_uri( 'style/js/prism.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'scroll-monitor', 
			get_theme_file_uri( 'style/js/scrollMonitor.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'smartwizard', 
			get_theme_file_uri( 'style/js/smartwizard.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'smooth-scroll', 
			get_theme_file_uri( 'style/js/smooth-scroll.polyfills.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'twitter-fetcher', 
			get_theme_file_uri( 'style/js/twitterFetcher_min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'typed', 
			get_theme_file_uri( 'style/js/typed.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		wp_enqueue_script( 
			'fitvids', 
			get_theme_file_uri( 'style/js/fitvids.js' ), 
			array('jquery'), 
			$version, 
			true  
		);

		if( function_exists('tommusrhodus_social_sharing') ) {
			wp_enqueue_script( 
				'goodshare', 
				get_theme_file_uri( 'style/js/goodshare.js' ), 
				array('jquery'), 
				$version, 
				true  
			);
		}

		wp_enqueue_script( 
			'tommusrhodus-scripts', 
			get_theme_file_uri( 'style/js/theme.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'tommusrhodus-wp-scripts', 
			get_theme_file_uri( 'style/js/wp-scripts.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'jarallax', 
			get_theme_file_uri( 'style/js/jarallax.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'jarallax-video', 
			get_theme_file_uri( 'style/js/jarallax-video.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		wp_enqueue_script( 
			'jarallax-element', 
			get_theme_file_uri( 'style/js/jarallax-element.min.js' ), 
			array('jquery'), 
			$version, 
			true  
		);
		
		
		// Enqueue Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		wp_add_inline_style( 'tommusrhodus-style', tommusrhodus_generate_skin() );
		
		// Localise script data
		$script_array = array( 
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'tommusrhodus-voting' )
		);
		wp_localize_script( 'tommusrhodus-wp-scripts', 'uptime_data', $script_array );
		
	}
	add_action( 'wp_enqueue_scripts', 'tommusrhodus_load_scripts', 110 );
}

/**
 * tommusrhodus_google_fonts_url()
 * 
 * Properly Enqueues Scripts & Styles for the theme
 * 
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_google_fonts_url' ) )){
	function tommusrhodus_google_fonts_url(){
	
	    $font_url = '';
	    if ( 'off' !== _x( 'on', 'Google font: on or off', 'uptime' ) && get_theme_mod( 'google_font_string' ) ) {
	        $font_url = add_query_arg( 
				'family', 
				urlencode( 
					str_replace( '+', ' ', get_theme_mod( 'google_font_string' ) ) 
				), 
				"//fonts.googleapis.com/css" 
	        );
	    }
	    
	    return $font_url;
	    
	}
}

/**
 * tommusrhodus_admin_load_scripts()
 * 
 * Properly enqueue styles and scripts to show on admin screens.
 * 
 * @documentation https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * @documentation https://developer.wordpress.org/reference/functions/wp_enqueue_script/
 * @since v1.0.0
 * @blame Tom Rhodes
 */ 
if(!( function_exists( 'tommusrhodus_admin_load_scripts' ) )){
	function tommusrhodus_admin_load_scripts(){
	
		wp_enqueue_style( 
			'tommusrhodus-theme-admin-css', 
			get_theme_file_uri( 'admin/tommusrhodus-theme-admin.css' ) 
		);
		
		wp_enqueue_script( 
			'tommusrhodus-theme-admin-js', 
			get_theme_file_uri( 'admin/tommusrhodus-theme-admin.js' ), 
			array('jquery'), 
			false, 
			true
		);
		
	}
	add_action( 'admin_enqueue_scripts', 'tommusrhodus_admin_load_scripts', 200 );
}

// Add backend styles for Gutenberg.
add_action( 'enqueue_block_editor_assets', 'tommusrhodus_load_gutenberg_assets' );

/**
 * Load Gutenberg stylesheet.
 */
if(!( function_exists( 'tommusrhodus_load_gutenberg_assets' ) )){
	function tommusrhodus_load_gutenberg_assets(){
	
		// Load the theme styles within Gutenberg.
		if( $font_url = tommusrhodus_google_fonts_url() ){
			wp_enqueue_style( 
				'tommusrhodus-google-font', 
				$font_url, 
				array(), 
				$version 
			);
		}

		wp_enqueue_style( 
			'tommusrhodus-gutenberg', 
			get_theme_file_uri( '/style/css/gutenberg-editor-style.css' ), 
			false 
		);

		if( get_theme_mod( 'body_font_name' ) ) {
			$body_font = get_theme_mod( 'body_font_name' );
		} else {
			$body_font = 'Inter UI';
		}

		$body_text = get_theme_mod( 'body_text', '#495057' );

		if( isset($post->ID) && get_post_meta( $post->ID, '_tommusrhodus_primary_override', 1 ) ) {
			$primary = get_post_meta( $post->ID, '_tommusrhodus_primary_override', 1 );
		} else {
			$primary = get_theme_mod( 'primary', '#3755BE' );
		}		

		$custom_css = '
			.editor-writing-flow, 
			.wp-block-paragraph, 
			.wp-block-table, 
			.wp-block-freeform,
			.mce-content-body:not(.wp-block-button__link) {
			    font-family: "'. $body_font .'", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
		        font-size: 1rem;
			    font-weight: 400;
			    line-height: 1.5;
			}
			.editor-post-title__block .editor-post-title__input {
				font-family: "'. $body_font .'", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
			}
	        .wp-block h1, 
	        .wp-block h2, 
	        .wp-block h3, 
	        .wp-block h4, 
	        .wp-block h5, 
	        .wp-block h6, 
	        .wp-block-freeform.block-library-rich-text__tinymce blockquote p,
	        .block-editor blockquote p {
	            color: '. $body_text .';
	        }
	        .wp-block p {
	            color: '. $body_text .';
	        }
	        .article a, .block-editor-rich-text__editable a, .wp-block-freeform.block-library-rich-text__tinymce a, .editor-styles-wrapper li a {
				color: '. $primary .';
				text-decoration: none;
			}
			.wp-block-button__link {				
			    color: #fff;
			    background-color: '. $primary .';
			    border-color: '. $primary .';
			}
			.wp-block .mce-content-body thead th, .wp-block-table thead th {
				background: '. $primary .' !important;
			}
        ';
        wp_add_inline_style( 'tommusrhodus-gutenberg', $custom_css );
		
	}
	add_action( 'enqueue_block_editor_assets', 'tommusrhodus_load_gutenberg_assets', 200 );
}