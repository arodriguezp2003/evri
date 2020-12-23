<?php
/**
 * Page controls for the Notification bar builder templates
 */
function nbbfeml_add_elementor_page_settings_controls( $document_controls ) {
	$post_type = '';

	if(is_singular()){
		$queried_object = get_queried_object();
		$post_type = $queried_object->post_type;
	}

	if( $post_type != 'nbbfem_template' ){
		return;
	}

	if(class_exists('WooCommerce')){
		$display_on_options = [
	        'entire_website' => esc_html__( 'Entire Website', 'nbbfem' ),
	        'only_home' => esc_html__( 'Only Homepage', 'nbbfem' ),
	        'only_shop' => esc_html__( 'Only Shop Page', 'nbbfem' ),
	        'specific_ids' => esc_html__( 'Specific page/post/product ID\'s', 'nbbfem' ),
	        'all_pages' => esc_html__( 'All Pages', 'nbbfem' ),
	        'all_posts' => esc_html__( 'All Posts', 'nbbfem' ),
	        'all_products' => esc_html__( 'All Products', 'nbbfem' ),
	    ];
	} else {
		$display_on_options = [
	        'entire_website' => esc_html__( 'Entire Website', 'nbbfem' ),
	        'only_home' => esc_html__( 'Only Homepage', 'nbbfem' ),
	        'specific_ids' => esc_html__( 'Specific page/post ID\'s', 'nbbfem' ),
	        'all_pages' => esc_html__( 'All Pages', 'nbbfem' ),
	        'all_posts' => esc_html__( 'All Posts', 'nbbfem' ),
	    ];
	}
    $document_controls->start_controls_section(
        'nbbfem_options_group',
        [
            'label' => esc_html__( 'Notification Options', 'nbbfem' ),
            'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
        ]
    );

        $document_controls->add_control(
            'nbbfem_display_on_heading',
            [
                'label' => esc_html__( 'Display On For:', 'nbbfem' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'description'	=>	__('Locations for where this notification should appear', 'nbbfem'),
                'separator' => 'before',
            ]
        );

        $document_controls->add_control(
            'nbbfem_display_on',
            [
                'label_block'	=> true,
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $display_on_options,
            ]
        );

        $document_controls->add_control(
            'entire_website_exclude',
            [
                'label' => __( 'Exclude IDs <a href="https://bit.ly/381a8Df" class="nbbfem_pro_label" target="_blank" title="Get Pro Version">(Pro)</a>', 'nbbfem' ),
                'label_block'   => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'description'	=>	esc_html__('Write the page/post/product IDs here seperated by comma. e.g: 3,5,10', 'nbbfem'),
                'classes' => 'nbbfem_lock',
                'condition' => [
                    'nbbfem_display_on'    =>  'entire_website',
                ]
            ]
        );

        $document_controls->add_control(
            'specific_ids',
            [
                'label' => esc_html__( 'Page/Post/Products ID\'s', 'nbbfem' ),
                'label_block'   => true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'description'	=>	esc_html__('Write the page/post/product IDs here seperated by comma. e.g: 3,5,10', 'nbbfem'),
                'condition' => [
                    'nbbfem_display_on'    =>  'specific_ids',
                ]
            ]
        );

        $document_controls->add_control(
            'all_pages_exclude',
            [
                'label' => __( 'Exclude Page IDs <a href="https://bit.ly/381a8Df" class="nbbfem_pro_label" target="_blank" title="Get Pro Version">(Pro)</a>', 'nbbfem' ),
                'label_block'	=> true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'description'	=>	esc_html__('Write the page IDs here seperated by comma. e.g: 3,5,10', 'nbbfem'),
                'classes' => 'nbbfem_lock',
                'condition' => [
                    'nbbfem_display_on'    =>  'all_pages',
                ]
            ]
        );

        $document_controls->add_control(
            'all_posts_exclude',
            [
                'label' => __( 'Exclude Post IDs <a href="https://bit.ly/381a8Df" class="nbbfem_pro_label" target="_blank" title="Get Pro Version">(Pro)</a>', 'nbbfem' ),
                'label_block'	=> true,
                'type' => \Elementor\Controls_Manager::TEXT,
                'description'	=>	esc_html__('Write the post IDs here seperated by comma. e.g: 3,5,10', 'nbbfem'),
                'classes' => 'nbbfem_lock',
                'condition' => [
                    'nbbfem_display_on'    =>  'all_posts',
                ]
            ]
        );

        if(class_exists('WooCommerce')){
        	$document_controls->add_control(
        	    'all_products_exclude',
        	    [
                    'label' => __( 'Exclude Product IDs <a href="https://bit.ly/381a8Df" class="nbbfem_pro_label" target="_blank" title="Get Pro Version">(Pro)</a>', 'nbbfem' ),
        	        'label_block'	=> true,
        	        'type' => \Elementor\Controls_Manager::TEXT,
        	        'description'	=>	esc_html__('Write the products IDs here seperated by comma. e.g: 3,5,10', 'nbbfem'),
                    'classes' => 'nbbfem_lock',
        	        'condition' => [
        	            'nbbfem_display_on'    =>  'all_products',
        	        ]
        	    ]
        	);
        }

        $document_controls->add_control(
            'dont_show_after_close_status',
            [
                'label' => esc_html__( 'Allow User To Close Notice', 'nbbfem' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'description' => __('Don\'t show the notice again after close.', 'nbbfem'),
                'label_on' => esc_html__( 'Yes', 'nbbfem' ),
                'label_off' => esc_html__( 'No', 'nbbfem' ),
                'return_value' => 'yes',
                'default' => '',
                'separator' => 'before',
            ]
        );

        $document_controls->add_control(
            'coockie_age',
            [
                'label' => esc_html__( 'Appear Again Duration', 'nbbfem' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'description' => esc_html__('Default:7 (days). This means the users will see the notice again after 7 days.', 'nbbfem'),
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 7,
                'separator' => 'before',
                'condition' => [
                    'dont_show_after_close_status'    =>  'yes',
                ]
            ]
        );

        $document_controls->add_control(
            'position',
            [
                'label' => esc_html__( 'Nofification Position', 'nbbfem' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'top'   => esc_html__('Top', 'nbbfem'),
                    'bottom'=> esc_html__('Bottom', 'nbbfem'),
                ],
                'default' =>'top',

            ]
        );

        $document_controls->add_control(
            'close_icon_position',
            [
                'label' => __( 'Close Icon Position <a href="https://bit.ly/381a8Df" class="nbbfem_pro_label" target="_blank" title="Get Pro Version">(Pro)</a>', 'nbbfem' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'top_right'   => esc_html__('Top Right', 'nbbfem'),
                    'top_left'=> esc_html__('Top Left', 'nbbfem'),
                    'middle_right'=> esc_html__('Middle Right', 'nbbfem'),
                    'middle_left'=> esc_html__('Middle Left', 'nbbfem'),
                ],
                'classes' => 'nbbfem_lock',
                'default' =>'top_right',
            ]
        );

    $document_controls->end_controls_section();
}

add_action( 'elementor/documents/register_controls', 'nbbfeml_add_elementor_page_settings_controls', 10, 1 );