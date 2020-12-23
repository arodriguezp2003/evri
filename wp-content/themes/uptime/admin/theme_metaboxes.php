<?php 

/**
 * tommusrhodus_custom_metaboxes()
 * 
 * Build the custom metaboxes for the theme.
 * Runs through the metabox framework CMB2.
 * 
 * @documentation https://github.com/CMB2/CMB2
 * @param $meta_boxes -- The metabox object of CMB2
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_custom_metaboxes' ) )){
	function tommusrhodus_custom_metaboxes( $meta_boxes ) {
		
		$prefix             		= '_tommusrhodus_';
		$social_options     		    = array();
		$single_portfolio_options 	= tommusrhodus_get_portfolio_single_layouts();
		$single_post_options		    = tommusrhodus_get_blog_single_layouts();
		$header_options				= tommusrhodus_get_header_layouts();
		$footer_options				= tommusrhodus_get_footer_layouts();

		$header_overrides['none'] = esc_html__( 'Do Not Override Header Option On This Page', 'uptime');
		foreach( $header_options as $key => $value ){
			$header_overrides[$key] = 'Override Header: ' . $value; 	
		}

		$footer_overrides['none'] = esc_html__( 'Do Not Override Footer Option On This Page', 'uptime');
		foreach( $footer_options as $key => $value ){
			$footer_overrides[$key] = 'Override Footer: ' . $value; 	
		}

		$single_portfolio_layouts['none'] = esc_html__( 'Do Not Override Layout Option On This Portfolio', 'uptime');
		foreach( $single_portfolio_options as $key => $value ){
			$single_portfolio_layouts[$key] = 'Override Layout: ' . $value; 	
		}

		$single_post_layouts['none'] = esc_html__( 'Do Not Override Laout Option On This Post', 'uptime');
		foreach( $single_post_options as $key => $value ){
			$single_post_layouts[$key] = 'Override Layout: ' . $value; 	
		}

		$meta_boxes[] = array(
			'id' => 'override_header_metabox',
			'title' => esc_html__('Page Overrides', 'uptime'),
			'object_types' => array( 'page', 'portfolio', 'team', 'post', 'career', 'product', 'testimonial', 'documentation', 'job_listing' ), // post type
			'context' => 'normal',
			'priority' => 'low',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name' => esc_html__( 'Override Logo?', 'uptime' ),
					'id'   => $prefix . 'logo_override',
					'desc' => esc_html__( 'Add an image here if you would like to show a custom logo for this page only.', 'uptime' ),
					'type' => 'file',
				),
				array(
					'name'         => esc_html__( 'Override Header?', 'uptime' ),
					'desc'         => esc_html__( 'Header Layout is set in "appearance" -> "customise". To override this for this page only, use this control.', 'uptime' ),
					'id'           => $prefix . 'header_override',
					'type'         => 'select',
					'options'      => $header_overrides,
					'std'          => 'none'
				),
				array(
					'name'         => esc_html__( 'Override Footer?', 'uptime' ),
					'desc'         => esc_html__( 'Footer Layout is set in "appearance" -> "customise". To override this for this page only, use this control.', 'uptime' ),
					'id'           => $prefix . 'footer_override',
					'type'         => 'select',
					'options'      => $footer_overrides,
					'std'          => 'none'
				),
				array(
					'name'         => esc_html__( 'Show progress bar?', 'uptime' ),
					'desc'         => esc_html__( 'Show the page reading progress bar. NOTE: This applies to the "Elementor Full Width" page template only.', 'uptime' ),
					'id'           => $prefix . 'progress_bar',
					'type'         => 'select',
					'options'      => array(
						'no'  => 'No',
						'yes' => 'Yes'
					),
					'std'          => 'no'
				),
				array(
					'name'         => esc_html__( 'Use custom colors?', 'uptime' ),
					'desc'         => esc_html__( 'Allow the page to use the custom colour controls below.', 'uptime' ),
					'id'           => $prefix . 'custom_colours',
					'type'         => 'select',
					'options'      => array(
						'yes' => 'Yes',
						'no'  => 'No'
					),
					'std'          => 'yes'
				),
				array(
					'name'    		=> esc_html__( 'Primary Colour', 'uptime' ),
					'id'      		=> $prefix . 'primary_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary Hover Colour', 'uptime' ),
					'id'      		=> $prefix . 'primary_hover_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Secondary Colour', 'uptime' ),
					'id'      		=> $prefix . 'secondary_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Light Colour', 'uptime' ),
					'id'      		=> $prefix . 'light_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Dark Colour', 'uptime' ),
					'id'      		=> $prefix . 'dark_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary 2 Colour', 'uptime' ),
					'id'      		=> $prefix . 'primary_2_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary 2 Hover Colour', 'uptime' ),
					'id'      		=> $prefix . 'primary_2_hover_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary 3 Colour', 'uptime' ),
					'id'      		=> $prefix . 'primary_3_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary Background Colour', 'uptime' ),
					'id'      		=> $prefix . 'bg_primary_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary Alt Background Colour', 'uptime' ),
					'id'      		=> $prefix . 'bg_primary_alt_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Secondary Background Colour', 'uptime' ),
					'id'      		=> $prefix . 'bg_secondary_override',
					'type'    		=> 'colorpicker',
					'default' 		=>  '',
				),
				array(
					'name'    		=> esc_html__( 'Light Background Colour', 'uptime' ),
					'id'      		=> $prefix . 'bg_light_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Dark Background Colour', 'uptime' ),
					'id'      		=> $prefix . 'bg_dark_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary 2 Background Colour', 'uptime' ),
					'id'      		=> $prefix . 'bg_primary_2_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary 2 Alt Background Colour', 'uptime' ),
					'id'      		=> $prefix . 'bg_primary_2_alt_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
				array(
					'name'    		=> esc_html__( 'Primary 3 Background Colour', 'uptime' ),
					'id'      		=> $prefix . 'bg_primary_3_override',
					'type'    		=> 'colorpicker',
					'default' 		=> '',
				),
			)
		);

		$meta_boxes[] = array(
			'id' => 'post_header_metabox',
			'title' => esc_html__('Post Overrides', 'uptime'),
			'object_types' => array( 'post' ), // post type
			'context' => 'normal',
			'priority' => 'low',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name'         => esc_html__( 'Override Post Hero Layout?', 'uptime' ),
					'desc'         => esc_html__( 'Hero Layout is set in "appearance" -> "customise". To override this for this post only, use this control.', 'uptime' ),
					'id'           => $prefix . 'post_single_hero_layout_override',
					'type'         => 'select',
					'options'      => $single_post_layouts,
					'std'          => 'none'
				),
			)
		);
	
		$meta_boxes[] = array(
			'id'           => 'portfolio_layout_metabox',
			'title'        => esc_html__( 'Portfolio Item Layout Options', 'uptime' ),
			'object_types' => array( 'portfolio' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'fields'       => array(
				array(
					'name' => esc_html__( 'Gallery Images', 'uptime' ),
					'id'   => $prefix . 'portfolio_item_images',
					'desc' => esc_html__( 'Shown on Single View', 'uptime' ),
					'type' => 'file_list',
				),
				array(
					'name' => esc_html__( 'Project Layout', 'uptime' ),
					'id'   => $prefix . 'portfolio_item_layout',
					'desc' => esc_html__( 'Set the layout for this portfolio item.', 'uptime' ),
					'type' => 'select',
					'show_option_none' => false,
					'default'          => 'study',
					'options'          => $single_portfolio_layouts,
				),
				array(
					'name' => esc_html__( 'Project Subtitle', 'uptime' ),
					'desc' => '',
					'id'   => $prefix . 'porfolio_item_subtitle',
					'type' => 'text',
				),
				array(
				    'id'          => $prefix . 'meta_repeat_group',
				    'type'        => 'group',
				    'description' => esc_html__( 'Meta Titles & Descriptions', 'uptime' ),
				    'options'     => array(
				        'add_button'    => esc_html__( 'Add Another Entry', 'uptime' ),
				        'remove_button' => esc_html__( 'Remove Entry', 'uptime' ),
				        'sortable'      => true, // beta
				    ),
				    'fields'       => array(
						array(
							'name' => esc_html__( 'Additional Item Title', 'uptime' ),
							'desc' => esc_html__( "Title of your Additional Meta", 'uptime' ),
							'id'   => $prefix . 'the_additional_title',
							'type' => 'text'
						),
						array(
							'name' => esc_html__( 'Additional Item Detail', 'uptime' ),
							'desc' => esc_html__( "Detail of your Additional Meta", 'uptime' ),
							'id'   => $prefix . 'the_additional_detail',
							'type' => 'text'
						),
				    ),
				),
				array(
					'name' 		=> esc_html__( 'Addition Detail Website Label', 'uptime' ),
					'desc' 		=> '',
					'id'   		=> $prefix . 'porfolio_item_website_label',					
					'default'	=> 'Website',
					'type' 		=> 'text',
				),
				array(
					'name' 		=> esc_html__( 'Addition Detail Website URL', 'uptime' ),
					'desc' 		=> '',
					'id'   		=> $prefix . 'porfolio_item_website_url',					
					'default'	=> 'linktosite.io',
					'type' 		=> 'text',
				),
			)
		);
		
		$meta_boxes[] = array(
			'id'           => 'team_metabox',
			'title'        => esc_html__( 'Team Member Details', 'uptime' ),
			'object_types' => array('team'), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name' => esc_html__( 'Job Title', 'uptime' ),
					'desc' => '(Optional) Enter a Job Title for this Team Member',
					'id'   => $prefix . 'the_job_title',
					'type' => 'text',
				),
				array(
					'name' => esc_html__( 'Label', 'uptime' ),
					'desc' => '(Optional, Only Used in Certain Team Feed Layouts) Enter a label for this Team Member',
					'id'   => $prefix . 'the_label',
					'type' => 'text',
				)
			)
		);
		
		$meta_boxes[] = array(
			'id'           => 'testimonial_layout_metabox',
			'title'        => esc_html__( 'Testimonial Item Layout Options', 'uptime' ),
			'object_types' => array( 'testimonial' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'fields'       => array(
				array(
					'name' => esc_html__( 'Company Logo', 'uptime' ),
					'id'   => $prefix . 'testimonial_logo',
					'type' => 'file'
				),
				array(
				    'id'          => $prefix . 'meta_repeat_group',
				    'type'        => 'group',
				    'description' => esc_html__( 'Meta Titles & Descriptions', 'uptime' ),
				    'options'     => array(
				        'add_button'    => esc_html__( 'Add Another Entry', 'uptime' ),
				        'remove_button' => esc_html__( 'Remove Entry', 'uptime' ),
				        'sortable'      => true, // beta
				    ),
				    'fields'       => array(
						array(
							'name' => esc_html__( 'Additional Item Title', 'uptime' ),
							'desc' => esc_html__( "Title of your Additional Meta", 'uptime' ),
							'id'   => 'meta_title',
							'type' => 'text'
						),
						array(
							'name' => esc_html__( 'Additional Item Detail', 'uptime' ),
							'desc' => esc_html__( "Detail of your Additional Meta", 'uptime' ),
							'id'   => 'meta_detail',
							'type' => 'textarea'
						),
				    ),
				),
			)
		);
		
		$meta_boxes[] = array(
			'id'           => 'client_metabox',
			'title'        => esc_html__( 'Client Logo Options', 'uptime' ),
			'object_types' => array( 'client' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'fields'       => array(
				array(
					'name' => esc_html__( 'Client URL', 'uptime' ),
					'id'   => $prefix . 'client_url',
					'type' => 'text'
				)
			)
		);

		$meta_boxes[] = array(
			'id'           => 'post_format_metabox',
			'title'        => esc_html__( 'Post Format Data', 'uptime' ),
			'object_types' => array( 'post' ), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name' => esc_html__( 'URL (Link Format)', 'uptime' ),
					'desc' => 'Enter a URL to link to',
					'id'   => $prefix . 'link_format_url',
					'type' => 'text',
				),
				array(
					'name' => esc_html__( 'Embed ID (Video Format) - eg 40842620', 'uptime' ),
					'desc' => 'Enter a video URL',
					'id'   => $prefix . 'video_url',
					'type' => 'text',
				),
				array(
					'name'         => esc_html__( 'Media Provider (Video Format)', 'uptime' ),
					'id'           => $prefix . 'video_provider',
					'type'         => 'select',
					'options'      => array(
						'vimeo' 		=> 'Vimeo',
						'youtube' 		=> 'YouTube'
					),
					'std'          => 'vimeo'
				),
				array(
					'name' => esc_html__( 'Author (Quote Format)', 'uptime' ),
					'desc' => 'Enter quote author',
					'id'   => $prefix . 'quote_format_author',
					'type' => 'text',
				),
			)
		);
		
		return $meta_boxes;
		
	}
	add_filter( 'cmb2_meta_boxes', 'tommusrhodus_custom_metaboxes' );
}

if(!( function_exists( 'tommusrhodus_taxonomy_metabox' ) )) {

	/**
	 * Hook in and add a metabox to add fields to taxonomy terms
	 */
	function tommusrhodus_taxonomy_metabox() {

		$prefix             		= '_tommusrhodus_';

		$cmb_term = new_cmb2_box( array(
			'id'               => $prefix . 'documentation_category_edit',
			'title'            => esc_html__( 'Category Metabox', 'uptime' ),
			'object_types'     => array( 'term' ),
			'taxonomies'       => array( 'documentation_category' ),
		) );

		$cmb_term->add_field( array(
			'name' => esc_html__( 'Icon Name', 'uptime' ),
			'id'   => $prefix . 'documentation_category_icon',
			'type' => 'text',
		) );

	}

	add_action( 'cmb2_admin_init', 'tommusrhodus_taxonomy_metabox' );

}