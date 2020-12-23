<?php 

/**
 * tommusrhodus_initialise_framework()
 * 
 * Add theme support items for the theme setup.
 * 
 * @since v1.0.0
 * @blame Tom Rhodes
 */

if(!( function_exists( 'tommusrhodus_initialise_framework' ) )){
	function tommusrhodus_initialise_framework() {

		$social_options = array_keys( tommusrhodus_get_svg_icons() );
		
		// Portfolio Post Type Options
		$framework_args['post_types']['portfolio'] = array(
			'labels' => array(
				'name'               => esc_html__( 'Portfolio', 'uptime' ),
				'singular_name'      => esc_html__( 'Portfolio', 'uptime' ),
				'add_new'            => esc_html__( 'Add New', 'uptime' ),
				'add_new_item'       => esc_html__( 'Add New Portfolio', 'uptime' ),
				'edit_item'          => esc_html__( 'Edit Portfolio', 'uptime' ),
				'new_item'           => esc_html__( 'New Portfolio', 'uptime' ),
				'view_item'          => esc_html__( 'View Portfolio', 'uptime' ),
				'search_items'       => esc_html__( 'Search Portfolios', 'uptime' ),
				'not_found'          => esc_html__( 'No portfolios found', 'uptime' ),
				'not_found_in_trash' => esc_html__( 'No portfolios found in Trash', 'uptime' ),
				'parent_item_colon'  => esc_html__( 'Parent Portfolio:', 'uptime' ),
				'menu_name'          => esc_html__( 'Portfolio', 'uptime' ),
			),
			'supports' => array( 
				'title', 
				'editor', 
				'thumbnail', 
				'post-formats', 
				'comments', 
				'author',
				'excerpt'
			),
			'taxonomies' => array( 
				'portfolio_category' // See line 90
			),
			'rewrite' => array( 
				'slug' => get_option( 'portfolio_post_type_slug', 'portfolio' ),
				'with_front' => false
			),
			'hierarchical'        => false,
			'description'         => esc_html__( 'Uptime Portfolio Entries', 'uptime' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-portfolio',
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'capability_type'     => 'post'
		);
		
		// Portfolio taxonomy type options
		$framework_args['taxonomy_types']['portfolio_category'] = array(
			'labels' => array(
				'name'              => esc_html__( 'Portfolio Categories', 'uptime' ),
				'singular_name'     => esc_html__( 'Portfolio Category', 'uptime' ),
				'search_items'      => esc_html__( 'Search Portfolio Categories', 'uptime' ),
				'all_items'         => esc_html__( 'All Portfolio Categories', 'uptime' ),
				'parent_item'       => esc_html__( 'Parent Portfolio Category', 'uptime' ),
				'parent_item_colon' => esc_html__( 'Parent Portfolio Category:', 'uptime' ),
				'edit_item'         => esc_html__( 'Edit Portfolio Category', 'uptime' ), 
				'update_item'       => esc_html__( 'Update Portfolio Category', 'uptime' ),
				'add_new_item'      => esc_html__( 'Add New Portfolio Category', 'uptime' ),
				'new_item_name'     => esc_html__( 'New Portfolio Category Name', 'uptime' ),
				'menu_name'         => esc_html__( 'Portfolio Categories', 'uptime' )
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => true,
			'for_post_types'    => array( 'portfolio' )
		);
		
		// Team Post Type Options
		$framework_args['post_types']['team'] = array(
			'labels' => array(
				'name'               => esc_html__( 'Team', 'uptime' ),
				'singular_name'      => esc_html__( 'Team', 'uptime' ),
				'add_new'            => esc_html__( 'Add New', 'uptime' ),
				'add_new_item'       => esc_html__( 'Add New Team', 'uptime' ),
				'edit_item'          => esc_html__( 'Edit Team', 'uptime' ),
				'new_item'           => esc_html__( 'New Team', 'uptime' ),
				'view_item'          => esc_html__( 'View Team', 'uptime' ),
				'search_items'       => esc_html__( 'Search Teams', 'uptime' ),
				'not_found'          => esc_html__( 'No Teams found', 'uptime' ),
				'not_found_in_trash' => esc_html__( 'No Teams found in Trash', 'uptime' ),
				'parent_item_colon'  => esc_html__( 'Parent Team:', 'uptime' ),
				'menu_name'          => esc_html__( 'Team', 'uptime' ),
			),
			'supports' => array( 
				'title', 
				'editor', 
				'thumbnail', 
				'post-formats', 
				'comments', 
				'author',
				'excerpt'
			),
			'taxonomies' => array( 
				'team_category'
			),
			'rewrite' => array( 
				'slug' => get_option( 'team_post_type_slug', 'team' ),
				'with_front' => false
			),
			'hierarchical'        => false,
			'description'         => esc_html__( 'Uptime Team Entries', 'uptime' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-id-alt',
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'capability_type'     => 'post'
		);
		
		// Team taxonomy type options
		$framework_args['taxonomy_types']['team_category'] = array(
			'labels' => array(
				'name'              => esc_html__( 'Team Categories', 'uptime' ),
				'singular_name'     => esc_html__( 'Team Category', 'uptime' ),
				'search_items'      => esc_html__( 'Search Team Categories', 'uptime' ),
				'all_items'         => esc_html__( 'All Team Categories', 'uptime' ),
				'parent_item'       => esc_html__( 'Parent Team Category', 'uptime' ),
				'parent_item_colon' => esc_html__( 'Parent Team Category:', 'uptime' ),
				'edit_item'         => esc_html__( 'Edit Team Category', 'uptime' ), 
				'update_item'       => esc_html__( 'Update Team Category', 'uptime' ),
				'add_new_item'      => esc_html__( 'Add New Team Category', 'uptime' ),
				'new_item_name'     => esc_html__( 'New Team Category Name', 'uptime' ),
				'menu_name'         => esc_html__( 'Team Categories', 'uptime' )
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => true,
			'for_post_types'    => array( 'team' )
		);
		
		// Documentation Post Type Options
		$framework_args['post_types']['documentation'] = array(
			'labels' => array(
				'name'               => esc_html__( 'Documentation', 'uptime' ),
				'singular_name'      => esc_html__( 'Documentation Item', 'uptime' ),
				'add_new'            => esc_html__( 'Add New', 'uptime' ),
				'add_new_item'       => esc_html__( 'Add New Documentation Item', 'uptime' ),
				'edit_item'          => esc_html__( 'Edit Documentation Item', 'uptime' ),
				'new_item'           => esc_html__( 'New Documentation Item', 'uptime' ),
				'view_item'          => esc_html__( 'View Documentation Item', 'uptime' ),
				'search_items'       => esc_html__( 'Search Documentation Items', 'uptime' ),
				'not_found'          => esc_html__( 'No Documentation Items found', 'uptime' ),
				'not_found_in_trash' => esc_html__( 'No Documentation Items found in Trash', 'uptime' ),
				'parent_item_colon'  => esc_html__( 'Parent Documentation Item:', 'uptime' ),
				'menu_name'          => esc_html__( 'Documentation', 'uptime' ),
			),
			'supports' => array( 
				'title', 
				'editor',  
				'post-formats', 
				'comments', 
				'author',
				'excerpt',
				'thumbnail'
			),
			'taxonomies' => array( 
				'documentation_category'
			),
			'rewrite' => array( 
				'slug' => get_option( 'documentation_post_type_slug', 'documentation' ),
				'with_front' => false
			),
			'hierarchical'        => false,
			'description'         => esc_html__( 'Uptime documentation Entries', 'uptime' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-media-document',
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'capability_type'     => 'post'
		);
		
		// Documentation taxonomy type options
		$framework_args['taxonomy_types']['documentation_category'] = array(
			'labels' => array(
				'name'              => esc_html__( 'Documentation Categories', 'uptime' ),
				'singular_name'     => esc_html__( 'Documentation Category', 'uptime' ),
				'search_items'      => esc_html__( 'Search Documentation Categories', 'uptime' ),
				'all_items'         => esc_html__( 'All Documentation Categories', 'uptime' ),
				'parent_item'       => esc_html__( 'Parent Documentation Category', 'uptime' ),
				'parent_item_colon' => esc_html__( 'Parent Documentation Category:', 'uptime' ),
				'edit_item'         => esc_html__( 'Edit Documentation Category', 'uptime' ), 
				'update_item'       => esc_html__( 'Update Documentation Category', 'uptime' ),
				'add_new_item'      => esc_html__( 'Add New Documentation Category', 'uptime' ),
				'new_item_name'     => esc_html__( 'New Documentation Category Name', 'uptime' ),
				'menu_name'         => esc_html__( 'Documentation Categories', 'uptime' )
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => true,
			'for_post_types'    => array( 'documentation' )
		);
		
		// Testimonial Post Type Options
		$framework_args['post_types']['testimonial'] = array(
			'labels' => array(
				'name'               => esc_html__( 'Testimonial', 'uptime' ),
				'singular_name'      => esc_html__( 'Testimonial Item', 'uptime' ),
				'add_new'            => esc_html__( 'Add New', 'uptime' ),
				'add_new_item'       => esc_html__( 'Add New Testimonial Item', 'uptime' ),
				'edit_item'          => esc_html__( 'Edit Testimonial Item', 'uptime' ),
				'new_item'           => esc_html__( 'New Testimonial Item', 'uptime' ),
				'view_item'          => esc_html__( 'View Testimonial Item', 'uptime' ),
				'search_items'       => esc_html__( 'Search Testimonial Items', 'uptime' ),
				'not_found'          => esc_html__( 'No Testimonial Items found', 'uptime' ),
				'not_found_in_trash' => esc_html__( 'No Testimonial Items found in Trash', 'uptime' ),
				'parent_item_colon'  => esc_html__( 'Parent Testimonial Item:', 'uptime' ),
				'menu_name'          => esc_html__( 'Testimonial', 'uptime' ),
			),
			'supports' => array( 
				'title', 
				'editor',  
				'post-formats', 
				'author',
				'excerpt',
				'thumbnail'
			),
			'taxonomies' => array( 
				'testimonial_category'
			),
			'rewrite' => array( 
				'slug' => get_option( 'testimonial_post_type_slug', 'testimonials' ),
				'with_front' => false
			),
			'hierarchical'        => true,
			'description'         => esc_html__( 'Uptime Testimonial Entries', 'uptime' ),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-format-quote',
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'capability_type'     => 'post'
		);
		
		// Testimonial taxonomy type options
		$framework_args['taxonomy_types']['testimonial_category'] = array(
			'labels' => array(
				'name'              => esc_html__( 'Testimonial Categories', 'uptime' ),
				'singular_name'     => esc_html__( 'Testimonial Category', 'uptime' ),
				'search_items'      => esc_html__( 'Search Testimonial Categories', 'uptime' ),
				'all_items'         => esc_html__( 'All Testimonial Categories', 'uptime' ),
				'parent_item'       => esc_html__( 'Parent Testimonial Category', 'uptime' ),
				'parent_item_colon' => esc_html__( 'Parent Testimonial Category:', 'uptime' ),
				'edit_item'         => esc_html__( 'Edit Testimonial Category', 'uptime' ), 
				'update_item'       => esc_html__( 'Update Testimonial Category', 'uptime' ),
				'add_new_item'      => esc_html__( 'Add New Testimonial Category', 'uptime' ),
				'new_item_name'     => esc_html__( 'New Testimonial Category Name', 'uptime' ),
				'menu_name'         => esc_html__( 'Testimonial Categories', 'uptime' )
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => true,
			'for_post_types'    => array( 'testimonial' )
		);
		
		// client Post Type Options
		$framework_args['post_types']['client'] = array(
			'labels' => array(
				'name'               => esc_html__( 'Client', 'uptime' ),
				'singular_name'      => esc_html__( 'Client', 'uptime' ),
				'add_new'            => esc_html__( 'Add New', 'uptime' ),
				'add_new_item'       => esc_html__( 'Add New Client', 'uptime' ),
				'edit_item'          => esc_html__( 'Edit Client', 'uptime' ),
				'new_item'           => esc_html__( 'New Client', 'uptime' ),
				'view_item'          => esc_html__( 'View Client', 'uptime' ),
				'search_items'       => esc_html__( 'Search Clients', 'uptime' ),
				'not_found'          => esc_html__( 'No Clients found', 'uptime' ),
				'not_found_in_trash' => esc_html__( 'No Clients found in Trash', 'uptime' ),
				'parent_item_colon'  => esc_html__( 'Parent Client:', 'uptime' ),
				'menu_name'          => esc_html__( 'Clients', 'uptime' ),
			),
			'supports' => array( 
				'title', 
				'thumbnail'
			),
			'taxonomies' => array( 
				'client_category' // See line 90
			),
			'rewrite' => false,
			'hierarchical'        => false,
			'description'         => esc_html__( 'Wingman client Entries', 'uptime' ),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-money',
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => false,
			'has_archive'         => false,
			'query_var'           => true,
			'can_export'          => true,
			'capability_type'     => 'post'
		);
		
		// client taxonomy type options
		$framework_args['taxonomy_types']['client_category'] = array(
			'labels' => array(
				'name'              => esc_html__( 'Client Categories', 'uptime' ),
				'singular_name'     => esc_html__( 'Client Category', 'uptime' ),
				'search_items'      => esc_html__( 'Search Client Categories', 'uptime' ),
				'all_items'         => esc_html__( 'All Client Categories', 'uptime' ),
				'parent_item'       => esc_html__( 'Parent Client Category', 'uptime' ),
				'parent_item_colon' => esc_html__( 'Parent Client Category:', 'uptime' ),
				'edit_item'         => esc_html__( 'Edit Client Category', 'uptime' ), 
				'update_item'       => esc_html__( 'Update Client Category', 'uptime' ),
				'add_new_item'      => esc_html__( 'Add New Client Category', 'uptime' ),
				'new_item_name'     => esc_html__( 'New Client Category Name', 'uptime' ),
				'menu_name'         => esc_html__( 'Client Categories', 'uptime' )
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => true,
			'for_post_types'    => array( 'client' )
		);
		
		if( did_action( 'elementor/loaded' ) ){
		
			// Enqueue Elementor Blocks
			$framework_args['elementor_blocks'] = array(
				'theme_name' => 'uptime',
				'blocks'     => array(
					'accordion-block',
					'alert-block',
					'blog-block',
					'breadcrumbs-block',
					'counter-block',
					'countdown-block',
					'card-block',
					'decorations-block',
					'section-decorations-block',
					'hero-header-block',
					'hero-header-cta-block',
					'image-collage-block',
					'image-carousel-block',
					'iphone-screenshot-block',
					'icon-text-block',
					'pricing-table-block',
					'pricing-card-block',
					'processes-block',
					'portfolio-block',
					'progress-block',
					'clients-block',
					'single-testimonial-block',
					'slider-block',
					'speaker-list-block',
					'tabbed-schedule-block',
					'tabs-block',
					'tabs-html-block',
					'team-block',
					'testimonial-carousel-block',
					'timeline-block',
					'typed-text-block',
					'twitter-slider-block',
					'video-lightbox-block',
					'video-carousel-block',
					'maps-block',
					'modal-block',
					'testimonial-block'
				)
			);
		
		}

		// Enqueue Widgets
		$framework_args['widgets'] = array(
			'theme_name' => 'uptime',
			'widgets'    => array(
				'contact-widget',
				'menu-widget',
				'twitter-widget',
				'popular-posts-widget',
				'recent-posts-widget',
				'sticky-widget'
			)
		);
		
		$divider_keys = array_keys( tommusrhodus_get_svg_dividers() );
		$dividers     = array_combine( $divider_keys, array_map( 'ucfirst', $divider_keys ) );
		
		$background_colours = array(
			'bg-primary'       => 'Primary Background',
			'bg-primary-alt'   => 'Primary Alt Background',
			'bg-secondary'     => 'Secondary Background',
			'bg-light'         => 'Light Background',
			'bg-dark'          => 'Dark Background',
			'bg-primary-2'     => 'Primary 2 Background',
			'bg-primary-2-alt' => 'Primary 2 Alt Background',
			'bg-primary-3'     => 'Primary 3 Background'
		);
		
		// Theme Options
		$framework_args['theme_options'] = array(
			array(
				'title'        => 'Sitewide Settings',
				'id'           => 'style_settings',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'site_settings',
						'title'       => 'Sitewide Settings',
						'description' => '',
						'options' => array(		
							array(
								'id'        => 'disable_page_fade',
								'title'     => esc_html__( 'Disable page fade effect on load?', 'uptime' ),
								'default'   => 'no',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'no'    => esc_html__( 'No, Keep Page Fading Effect', 'uptime' ),
									'yes'   => esc_html__( 'Yes, Remove Page Fading Effect', 'uptime' ),
								)
							),	
							array(
								'id'        => 'use_scroll_animations',
								'title'     => esc_html__( 'Use scroll animations from theme?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Keep Scroll Animations', 'uptime' ),
									'no'    => esc_html__( 'No, Remove Scroll Animations', 'uptime' ),
								)
							),				
						)
					),
					array(
						'id'          => 'tyography_settings',
						'title'       => 'Typography Settings',
						'description' => 'Here you can take control of your themes google fonts.',
						'options' => array(		
							array(
								'id'        => 'google_font_string',
								'title'     => esc_html__( 'Google Font URL' , 'uptime' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),	
							array(
								'id'        => 'body_font_name',
								'title'     => esc_html__( 'Body Font Name', 'uptime' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),					
						)
					),
					array(
						'id'          => 'smooth_scroll_settings',
						'title'       => 'Smooth Scroll Settings',
						'description' => 'Control the offset of your animated page scrolling. Use a positive number to make the page scroll further e.g 73<br />Use a negative number to make the page scroll shorter, e.g -73',
						'options' => array(		
							array(
								'id'        => 'smooth_scroll_offset',
								'title'     => esc_html__( 'Smooth Scroll Offset' , 'uptime' ),
								'default'   => '0',
								'type'      => 'text',
								'transport' => 'refresh',
								'choices'   => ''
							)				
						)
					)
				)
			),
			array(
				'title'        => 'Theme Colors',
				'id'           => 'theme_colors',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'theme_colors',
						'title'       => 'All Theme Colors',
						'description' => 'Here you can take control of your themes colours.',
						'options' => array(		
							array(
								'id'        => 'body_text',
								'title'     => esc_html__( 'Body Text Color', 'uptime' ),
								'default'   => '#495057',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'primary',
								'title'     => esc_html__( 'Primary Color', 'uptime' ),
								'default'   => '#3755BE',
								'type'      => 'color',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'primary_hover',
								'title'     => esc_html__( 'Primary Hover Color', 'uptime' ),
								'default'   => '#2e48a0',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'secondary',
								'title'     => esc_html__( 'Secondary Color', 'uptime' ),
								'default'   => '#6c757d',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'light',
								'title'     => esc_html__( 'Light Color', 'uptime' ),
								'default'   => '#f8f9fa',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'dark',
								'title'     => esc_html__( 'Dark Color', 'uptime' ),
								'default'   => '#212529',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'primary_2',
								'title'     => esc_html__( 'Primary 2 Color', 'uptime' ),
								'default'   => '#FF8E88',
								'type'      => 'color',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'primary_2_hover',
								'title'     => esc_html__( 'Primary 2 Hover Color', 'uptime' ),
								'default'   => '#FF8E88',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'primary_3',
								'title'     => esc_html__( 'Primary 3 Color', 'uptime' ),
								'default'   => '#1B1F3B',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_primary',
								'title'     => esc_html__( 'Primary Background Color', 'uptime' ),
								'default'   => '#3755BE',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_primary_alt',
								'title'     => esc_html__( 'Primary Alt Background Color', 'uptime' ),
								'default'   => '#f3f5fb',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_secondary',
								'title'     => esc_html__( 'Secondary Background Color', 'uptime' ),
								'default'   => '#6c757d',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_light',
								'title'     => esc_html__( 'Light Background Color', 'uptime' ),
								'default'   => '#f8f9fa',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_dark',
								'title'     => esc_html__( 'Dark Background Color', 'uptime' ),
								'default'   => '#212529',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_primary_2',
								'title'     => esc_html__( 'Primary 2 Background Color', 'uptime' ),
								'default'   => '#FF8E88',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_primary_2_alt',
								'title'     => esc_html__( 'Primary 2 Alt Background Color', 'uptime' ),
								'default'   => '#f4f2f9',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
							array(
								'id'        => 'bg_primary_3',
								'title'     => esc_html__( 'Primary 3 Background Color', 'uptime' ),
								'default'   => '#1B1F3B',
								'type'      => 'color',
								'transport' => 'postMessage'
							),	
						)
					)
				)
			),
			array(
				'title'        => 'Header Settings',
				'id'           => 'header',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'header',
						'title'       => 'Header Layout Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'header_layout',
								'title'     => esc_html__( 'Header Layout', 'uptime' ),
								'default'   => '1',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_header_layouts()
							),
							array(
								'id'        => 'use_sticky_header',
								'title'     => esc_html__( 'Use sticky menu?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Stick Menu on Scroll', 'uptime' ),
									'no'    => esc_html__( 'No, Menu Stays in Place', 'uptime' )
								)
							),
							array(
								'id'        => 'logo_height',
								'title'     => esc_html__( 'Logo Height (default 26px)', 'uptime' ),
								'default'   => '26px',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'mega_menu_background',
								'title'     => esc_html__( 'Mega Menu Background', 'uptime' ),
								'default'   => 'bg-primary-3 text-light',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => array(
									'bg-primary-3 text-light' => 'Dark Background',
									'bg-white' => 'Light Background'
								)
							),
							array(
								'id'        => 'menu_drop_type',
								'title'     => esc_html__( 'Menu dropdown method (Desktop)', 'uptime' ),
								'default'   => 'click-menu',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => array( 
									'click-menu'   => esc_html__( 'Click', 'uptime' ),
									'hover-menu'    => esc_html__( 'Hover', 'uptime' )
								)
							),
							array(
								'id'        => 'header_utility_bar',
								'title'     => esc_html__( 'Show header utility bar?', 'uptime' ),
								'default'   => 'no',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes', 'uptime' ),
									'no'    => esc_html__( 'No', 'uptime' )
								)
							),
							array(
								'id'        => 'utility_bar_left',
								'title'     => esc_html__( 'Utility bar left column text', 'uptime' ),
								'default'   => '',
								'type'      => 'textarea',
								'transport' => 'refresh',
								'choices'   => ''
							),
							array(
								'id'        => 'utility_bar_right',
								'title'     => esc_html__( 'Utility bar right column text', 'uptime' ),
								'default'   => '',
								'type'      => 'textarea',
								'transport' => 'refresh',
								'choices'   => ''
							),
						)
					),
					array(
						'id'          => 'header_button',
						'title'       => 'Header Button Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'header_button_label',
								'title'     => esc_html__( 'Button Label', 'uptime' ),
								'default'   => 'Login',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'header_button_url',
								'title'     => esc_html__( 'Button URL', 'uptime' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'refresh',
								'choices'   => ''
							),
							array(
								'id'        => 'header_button_label_2',
								'title'     => esc_html__( 'Button Label 2', 'uptime' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'header_button_url_2',
								'title'     => esc_html__( 'Button URL 2', 'uptime' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'refresh',
								'choices'   => ''
							),
						)
					)
				)
			),
			array(
				'title'        => 'Page View Settings',
				'id'           => 'page_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'page_view',
						'title'       => 'Page Layout Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'show_page_breadcrumbs',
								'title'     => esc_html__( 'Show Breadcrumbs on Pages with regular content?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Show Breadcrumbs', 'uptime' ),
									'no'    => esc_html__( 'No, Hide Breadcrumbs', 'uptime' )
								)
							),
						)
					)
				)
			),
			array(
				'title'        => 'Blog Single View Settings',
				'id'           => 'blog_single_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'blog_single',
						'title'       => 'Blog Single View Layout Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'post_single_hero_layout',
								'title'     => esc_html__( 'Blog Single Hero Layout', 'uptime' ),
								'default'   => 'basic',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_blog_single_layouts()
							),
							array(
								'id'        => 'show_post_sidebar',
								'title'     => esc_html__( 'Show sidebar on single post?', 'uptime' ),
								'default'   => 'no',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'no'    => esc_html__( 'No', 'uptime' ),
									'yes'   => esc_html__( 'Yes', 'uptime' )
								)
							),
						)
					)
				)
			),
			array(
				'title'        => 'Blog Archive View Settings',
				'id'           => 'blog_archive_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'blog_archive_header',
						'title'       => esc_html__( 'Blog Archive Header Settings', 'uptime' ),
						'description' => '',
						'options' => array(
							array(
								'id'        => 'post_archive_text_colour',
								'title'     => esc_html__( 'Post Archive Text Colour', 'uptime' ),
								'default'   => 'text-dark',
								'type'      => 'select',
								'transport' => 'postMessage',
								'choices'   => array( 
									'text-default'  => 'Dark Text',
									'text-white'    => 'Light Text'
								)
							),
							array(
								'id'        => 'post_archive_hero_blobs',
								'title'     => esc_html__( 'Post Archive Hero Blobs', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => array( 
									'yes'   => 'Yes, show hero area blobs',
									'no'    => 'No, remove hero area blobs'
								)
							),
							array(
								'id'        => 'post_archive_hero_divider',
								'title'     => esc_html__( 'Post Archive Hero Divider Shape', 'uptime' ),
								'default'   => 'pipe',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $dividers
							),
							array(
								'id'        => 'post_archive_hero_colour',
								'title'     => esc_html__( 'Post Archive Hero Background Colour', 'uptime' ),
								'default'   => 'bg-primary',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $background_colours
							),
							array(
								'id'        => 'post_archive_title',
								'title'     => esc_html__( 'Post Archive Title', 'uptime' ),
								'default'   => 'Blog',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'post_archive_subtitle',
								'title'     => esc_html__( 'Post Archive Subtitle', 'uptime' ),
								'default'   => get_bloginfo( 'description' ),
								'type'      => 'textarea',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'post_archive_background_image',
								'title'     => esc_html__( 'Post Archive Header Background', 'uptime' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'post_archive_header_layout',
								'title'     => esc_html__( 'Post Archive Header Layout', 'uptime' ),
								'default'   => 'white',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_header_layouts()
							),
							array(
								'id'        => 'post_archive_logo',
								'title'     => esc_html__( 'Post Archive Logo', 'uptime' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),						
						)
					),
					array(
						'id'          => 'blog_archive',
						'title'       => 'Blog Archive Layout Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'blog_layout',
								'title'     => esc_html__( 'Blog Layout', 'uptime' ),
								'default'   => 'card',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_blog_layouts()
							),
							array(
								'id'        => 'show_post_archive_author',
								'title'     => esc_html__( 'Show Post Archive Author?', 'uptime' ),
								'default'   => true,
								'type'      => 'toggle',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'post_archive_featured_posts_category',
								'title'     => esc_html__( 'Post Archive Featured Posts Category', 'uptime' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'postMessage',
								'choices'   => tommusrhodus_get_post_categories(),
							),
						)
					),
					array(
						'id'          => 'blog_archive_cta',
						'title'       => 'Blog Archive CTA',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'blog_cta',
								'title'     => esc_html__( 'Blog Call To Action Shortcode', 'uptime' ),
								'default'   => '',
								'type'      => 'textarea',
								'transport' => 'refresh',
								'choices'   => ''
							)
						)
					)
				)
			),
			array(
				'title'        => 'Portfolio Single View Settings',
				'id'           => 'portfolio_single_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'portfolio_single',
						'title'       => 'Portfolio Single View Layout Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'portfolio_single_layout',
								'title'     => esc_html__( 'Portfolio Single View Layout', 'uptime' ),
								'default'   => 'study',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_portfolio_single_layouts()
							),
						)
					)
				)
			),
			array(
				'title'        => 'Portfolio Archive View Settings',
				'id'           => 'portfolio_archive_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'portfolio_archive_header',
						'title'       => esc_html__( 'Portfolio Archive Header Settings', 'uptime' ),
						'description' => '',
						'options' => array(
							array(
								'id'        => 'show_portfolio_archive_breadcrumbs',
								'title'     => esc_html__( 'Show Breadcrumbs on Portfolio Archive?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Show Breadcrumbs', 'uptime' ),
									'no'    => esc_html__( 'No, Hide Breadcrumbs', 'uptime' )
								)
							),
							array(
								'id'        => 'portfolio_archive_title',
								'title'     => esc_html__( 'Portfolio Archive Title', 'uptime' ),
								'default'   => 'Our Portfolio',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'portfolio_archive_subtitle',
								'title'     => esc_html__( 'Portfolio Archive Subtitle', 'uptime' ),
								'default'   => 'Showcase projects in various grid arrangements with these stylish portfolio pages.',
								'type'      => 'textarea',
								'transport' => 'postMessage',
								'choices'   => ''
							)
						)
					),
					array(
						'id'          => 'portfolio_archive',
						'title'       => 'Portfolio Archive Layout Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'portfolio_layout',
								'title'     => esc_html__( 'Portfolio Layout', 'uptime' ),
								'default'   => '2-columns',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_portfolio_layouts()
							)
						)
					)
				)
			),			
			array(
				'title'        => 'Documentation Single View Settings',
				'id'           => 'documentation_settings',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'documentation_single',
						'title'       => esc_html__( 'Documentation Single View', 'uptime' ),
						'description' => '',
						'options' => array(
							array(
								'id'        => 'show_documentation_single_breadcrumbs',
								'title'     => esc_html__( 'Show Breadcrumbs on Single Documentation View?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Show Breadcrumbs', 'uptime' ),
									'no'    => esc_html__( 'No, Hide Breadcrumbs', 'uptime' )
								)
							),
							array(
								'id'        => 'show_single_documentation_hero',
								'title'     => esc_html__( 'Show Hero on Single Documentation View?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Show Hero', 'uptime' ),
									'no'    => esc_html__( 'No, Hide Hero', 'uptime' )
								)
							),
							array(
								'id'        => 'show_documentation_single_meta',
								'title'     => esc_html__( 'Show Meta on Single Documentation View?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Show Meta', 'uptime' ),
									'no'    => esc_html__( 'No, Hide Meta', 'uptime' )
								)
							),
							array(
								'id'        => 'show_documentation_single_cats',
								'title'     => esc_html__( 'Show Categories on Single Documentation View?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Show Categories', 'uptime' ),
									'no'    => esc_html__( 'No, Hide Categories', 'uptime' )
								)
							),
							array(
								'id'        => 'show_documentation_single_voting',
								'title'     => esc_html__( 'Show Voting on Single Documentation View?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Show Voting', 'uptime' ),
									'no'    => esc_html__( 'No, Hide Voting', 'uptime' )
								)
							),
							array(
								'id'        => 'documentation_single_support_text',
								'title'     => esc_html__( '"Still have questions?" Text', 'uptime' ),
								'default'   => 'Still have questions? <a href="#">Open a Support Ticket</a>',
								'type'      => 'text',
								'transport' => 'postMessage',
							),
						)
					)
				)
			),			
			array(
				'title'        => 'Documentation Archive View Settings',
				'id'           => 'documentation_archive_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'documentation_archive',
						'title'       => esc_html__( 'Documentation Archive View', 'uptime' ),
						'description' => '',
						'options' => array(
							array(
								'id'        => 'documentation_archive_title',
								'title'     => esc_html__( 'Documentation Archive Title', 'uptime' ),
								'default'   => 'Search the Knowledgebase',
								'type'      => 'text',
								'transport' => 'postMessage',
							),
							array(
								'id'        => 'show_documentation_archive_breadcrumbs',
								'title'     => esc_html__( 'Show Breadcrumbs on Single Documentation View?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Show Breadcrumbs', 'uptime' ),
									'no'    => esc_html__( 'No, Hide Breadcrumbs', 'uptime' )
								)
							),
							array(
								'id'        => 'documentation_archive_background_image',
								'title'     => esc_html__( 'Documentation Archive Header Background', 'uptime' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'documentation_archive_header_layout',
								'title'     => esc_html__( 'Documentation Archive Header Layout', 'uptime' ),
								'default'   => 'white',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_header_layouts()
							),
							array(
								'id'        => 'documentation_archive_logo',
								'title'     => esc_html__( 'Documentation Archive Logo', 'uptime' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'documentation_archive_popular_searches',
								'title'     => esc_html__( 'Popular Searches (Comma Seperated)', 'uptime' ),
								'default'   => '',
								'type'      => 'textarea',
								'transport' => 'postMessage',
								'choices'   => ''
							),
						)
					)
				)
			),
			array(
				'title'        => 'Team Archive View Settings',
				'id'           => 'team_archive_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'team_archive_header',
						'title'       => esc_html__( 'Team Archive Header Settings', 'uptime' ),
						'description' => '',
						'options' => array(
							array(
								'id'        => 'team_archive_title',
								'title'     => esc_html__( 'Team Archive Title', 'uptime' ),
								'default'   => 'Our Team',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'team_archive_subtitle',
								'title'     => esc_html__( 'Team Archive Subtitle', 'uptime' ),
								'default'   => 'Introduce your company on a more personal level with these attractive team pages.',
								'type'      => 'textarea',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'team_archive_background_image',
								'title'     => esc_html__( 'Team Archive Title Background', 'uptime' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'team_archive_text_colour',
								'title'     => esc_html__( 'Team Archive Text Colour', 'uptime' ),
								'default'   => 'text-light',
								'type'      => 'select',
								'transport' => 'postMessage',
								'choices'   => array( 
									'text-dark'  	=> 'Dark Text',
									'text-light'    => 'Light Text'
								)
							),
							array(
								'id'        => 'team_archive_header_layout',
								'title'     => esc_html__( 'Team Archive Header Layout', 'uptime' ),
								'default'   => 'white',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_header_layouts()
							),
							array(
								'id'        => 'team_archive_logo',
								'title'     => esc_html__( 'Team Archive Logo', 'uptime' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
						)
					),
					array(
						'id'          => 'team_archive',
						'title'       => 'Team Archive Layout Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'team_layout',
								'title'     => esc_html__( 'Team Layout', 'uptime' ),
								'default'   => '4-columns',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_team_layouts()
							)
						)
					),
				)
			),
			array(
				'title'        => 'Testimonials Archive View Settings',
				'id'           => 'testimonial_archive_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'testimonial_archive',
						'title'       => esc_html__( 'Testimonial Archive View', 'uptime' ),
						'description' => '',
						'options' => array(
							array(
								'id'        => 'testimonials_archive_title',
								'title'     => esc_html__( 'Testimonial Archive Title', 'uptime' ),
								'default'   => 'Customer Stories',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'testimonials_archive_description',
								'title'     => esc_html__( 'Testimonial Archive Subtitle', 'uptime' ),
								'default'   => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa.',
								'type'      => 'textarea',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'show_testimonial_archive_breadcrumbs',
								'title'     => esc_html__( 'Show Breadcrumbs on Testimonial Archive View?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Show Breadcrumbs', 'uptime' ),
									'no'    => esc_html__( 'No, Hide Breadcrumbs', 'uptime' )
								)
							),
							array(
								'id'        => 'testimonial_archive_header_layout',
								'title'     => esc_html__( 'Testimonial Archive Header Layout', 'uptime' ),
								'default'   => 'white',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_header_layouts()
							),
							array(
								'id'        => 'testimonial_archive_logo',
								'title'     => esc_html__( 'testimonial Archive Logo', 'uptime' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
						)
					)
				)
			),
			array(
				'title'        => '404 Settings',
				'id'           => '404_view',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => '404_cta',
						'title'       => '404 Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => '404_logo',
								'title'     => esc_html__( '404 Logo', 'uptime' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),		
						)
					)
				)
			),
			array(
				'title'        => 'WP Login Settings',
				'id'           => 'wp_login_settings',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'wp_login_images',
						'title'       => esc_html__( 'Login Page Images', 'uptime' ),
						'description' => '',
						'options' => array(
							array(
								'id'        => 'login_logo',
								'title'     => esc_html__( 'WP Login Logo', 'uptime' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'login_background_image',
								'title'     => esc_html__( 'WP Login Background', 'uptime' ),
								'default'   => '',
								'type'      => 'image',
								'transport' => 'postMessage',
								'choices'   => ''
							)
						)
					)
				)
			),
			array(
				'title'        => 'Footer Settings',
				'id'           => 'footer',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'footer',
						'title'       => 'Footer Layout Settings',
						'description' => '',
						'options' => array(
							array(
								'id'        => 'footer_layout',
								'title'     => esc_html__( 'Footer Layout', 'uptime' ),
								'default'   => '1',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => tommusrhodus_get_footer_layouts()
							),
							array(
								'id'        => 'footer_cta',
								'title'     => esc_html__( 'Default Footer: CTA (Example - <div class="h3 text-center mb-md-0">Start building beautiful websites</div><a href="#" class="btn btn-lg btn-white">Purchase Now</a></div>', 'uptime' ),
								'default'   => '',
								'type'      => 'textarea',
								'transport' => 'postMessage',
								'choices'   => ''
							),		
							array(
								'id'        => 'footer_copyright',
								'title'     => esc_html__( 'Footer Copyright Text - Use *copy* for copyright symbol & *current_year* for the current year.', 'uptime' ),
								'default'   => '*copy* *current_year* Leap. By <a href="http://www.tommusrhodus.com">TommusRhodus</a>',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),		
							array(
								'id'        => 'show_back_to_top',
								'title'     => esc_html__( 'Show back to top button?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'refresh',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes, Show Button', 'uptime' ),
									'no'    => esc_html__( 'No, Hide Button', 'uptime' )
								)
							),		
						)
					),
					array(
						'id'          => 'footer_social',
						'title'       => 'Footer Social Icons',
						'description' => 'Use this area to control the footer social icons.',
						'options' => array(
							array(
								'id'        => 'footer_social_icon_1',
								'title'     => esc_html__( 'Footer Social Icon 1', 'uptime' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $social_options
							),
							array(
								'id'        => 'footer_social_url_1',
								'title'     => esc_html__( 'Footer Social URL 1', 'uptime' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'footer_social_icon_2',
								'title'     => esc_html__( 'Footer Social Icon 2', 'uptime' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $social_options
							),
							array(
								'id'        => 'footer_social_url_2',
								'title'     => esc_html__( 'Footer Social URL 2', 'uptime' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'footer_social_icon_3',
								'title'     => esc_html__( 'Footer Social Icon 3', 'uptime' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $social_options
							),
							array(
								'id'        => 'footer_social_url_3',
								'title'     => esc_html__( 'Footer Social URL 3', 'uptime' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'footer_social_icon_4',
								'title'     => esc_html__( 'Footer Social Icon 4', 'uptime' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $social_options
							),
							array(
								'id'        => 'footer_social_url_4',
								'title'     => esc_html__( 'Footer Social URL 4', 'uptime' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'footer_social_icon_5',
								'title'     => esc_html__( 'Footer Social Icon 5', 'uptime' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $social_options
							),
							array(
								'id'        => 'footer_social_url_5',
								'title'     => esc_html__( 'Footer Social URL 5', 'uptime' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage'
							),
							array(
								'id'        => 'footer_social_icon_6',
								'title'     => esc_html__( 'Footer Social Icon 6', 'uptime' ),
								'default'   => '',
								'type'      => 'select',
								'transport' => 'refresh',
								'choices'   => $social_options
							),
							array(
								'id'        => 'footer_social_url_6',
								'title'     => esc_html__( 'Footer Social URL 6', 'uptime' ),
								'default'   => '',
								'type'      => 'text',
								'transport' => 'postMessage'
							),
						)
					)
				)
			),		
			array(
				'title'        => 'Social Sharing Settings',
				'id'           => 'social_sharing_settings',
				'description'  => '',
				'sections' => array(
					array(
						'id'          => 'sharing_settings',
						'title'       => esc_html__( 'Enable/Disabling Share Buttons', 'uptime' ),
						'description' => '',
						'options' => array(
							array(
								'id'        => 'show_sharing_buttons',
								'title'     => esc_html__( 'Show Sharing Buttons?', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes', 'uptime' ),
									'no'    => esc_html__( 'No', 'uptime' )
								)
							),
							array(
								'id'        => 'share_text',
								'title'     => esc_html__( 'Share Text', 'uptime' ),
								'default'   => 'Share This',
								'type'      => 'text',
								'transport' => 'postMessage',
								'choices'   => ''
							),
							array(
								'id'        => 'show_twitter_sharing',
								'title'     => esc_html__( 'Show Twitter Share Button', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes', 'uptime' ),
									'no'    => esc_html__( 'No', 'uptime' )
								)
							),
							array(
								'id'        => 'show_facebook_sharing',
								'title'     => esc_html__( 'Show Facebook Share Button', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes', 'uptime' ),
									'no'    => esc_html__( 'No', 'uptime' )
								)
							),
							array(
								'id'        => 'show_linkedin_sharing',
								'title'     => esc_html__( 'Show LinkedIn Share Button', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes', 'uptime' ),
									'no'    => esc_html__( 'No', 'uptime' )
								)
							),
							array(
								'id'        => 'show_pinterest_sharing',
								'title'     => esc_html__( 'Show Pinterest Share Button', 'uptime' ),
								'default'   => 'yes',
								'type'      => 'radio',
								'transport' => 'postMessage',
								'choices'   => array( 
									'yes'   => esc_html__( 'Yes', 'uptime' ),
									'no'    => esc_html__( 'No', 'uptime' )
								)
							),
						)
					)
				)
			),
		);

		add_theme_support( 'tommusrhodus-framework', apply_filters( 'tommusrhodus_framework_theme_args', $framework_args ) );
		
	}
	add_action( 'after_setup_theme', 'tommusrhodus_initialise_framework', 5 );
}