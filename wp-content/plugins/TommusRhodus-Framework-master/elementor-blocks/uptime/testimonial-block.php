<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_TommusRhodus_Testimonial_Block extends Widget_Base {
	
	//Return Class Name
	public function get_name() {
		return 'tommusrhodus-testimonial-block';
	}
	
	//Return Block Title (for blocks list)
	public function get_title() {
		return esc_html__( 'Testimonial Posts', 'tr-framework' );
	}
	
	//Return Block Icon (for blocks list)
	public function get_icon() {
		return 'eicon-post';
	}
	
	public function get_categories() {
		return [ 'uptime-elements' ];
	}
	
	/**
	 * Whether the reload preview is required or not.
	 *
	 * Used to determine whether the reload preview is required.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool Whether the reload preview is required.
	 */
	public function is_reload_preview_required() {
		return true;
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_my_custom', [
				'label' => esc_html__( 'Testimonial Posts', 'tr-framework' ),
			]
		);

		$this->add_control(
			'posts_per_page', [
				'label'   => esc_html__( 'Number of Posts', 'tr-framework' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '3'
			]
		);

		$this->add_control(
			'layout', [
				'label'   => esc_html__( 'Posts Layout', 'tr-framework' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'row',
				'options' => array(
					'row' => 'Row',
					'grid' => 'Grid'
				),
			]
		);

		$this->add_control(
			'posts_offset', [
				'label'   => esc_html__( 'Post Offset', 'tr-framework' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '0'
			]
		);
		
		// Category Selector
		if( taxonomy_exists('testimonial_category') ){
		
			$args = array(
				'orderby'      => 'name',
				'hide_empty'   => 0,
				'hierarchical' => 1,
				'taxonomy'     => 'testimonial_category'
			);
			
			$cats       = get_categories( $args );
			$final_cats = array( 'all' => 'Show all categories' );
		
			if( is_array( $cats ) ){
				foreach( $cats as $cat ){
					$final_cats[$cat->slug] = $cat->name;
				}
			}
		
			$this->add_control(
				'filter', [
					'label'   => esc_html__( 'Category', 'tr-framework' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'all',
					'options' => $final_cats,
				]
			);
		
		}	

		$this->end_controls_section();

	}

	protected function render() {
		
		global $wp_query, $post;

		extract( 
			shortcode_atts( 
				array(
					'posts_per_page' 	=> '6',
					'posts_offset' 		=> '0',
					'filter'		 	    => 'all',
					'layout'		 	    => 'row',
					'show_pagination'	=> 'hide'
				), $this->get_settings()
			) 
		);

		/**
		 * Setup post query
		 */
		$query_args = array(
			'post_type'      => 'testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page
		);
		
		if( $posts_offset ){
			$query_args[ 'offset' ] = $posts_offset;
		}
		
		if(!( $filter == 'all' )) {
			
			// Check for WPML
			if( has_filter('wpml_object_id') ){
			
				global $sitepress;
				
				// WPML recommended, remove filter, then add back after
				remove_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10, 4 );
				
				$filterClass    = get_term_by( 'slug', $filter, 'testimonial_category' );
				$ID             = (int) apply_filters( 'wpml_object_id', (int) $filterClass->term_id, 'testimonial_category', true );
				$translatedSlug = get_term_by( 'id', $ID, 'testimonial_category' );
				$filter          = $translatedSlug->slug;
				
				// Adding filter back
				add_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10, 4 );
				
			}
				
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'testimonial_category',
					'field'    => 'slug',
					'terms'    => $filter 
				)
			);	
			
		}
		
		$old_query = $wp_query;
		$old_post  = $post;
		$wp_query  = new \WP_Query( $query_args );

		get_template_part( 'loop/loop-testimonial', $layout );

		wp_reset_postdata();
		$wp_query = $old_query;
		$post     = $old_post;

	}

}

// Register our new widget
Plugin::instance()->widgets_manager->register_widget_type( new Widget_TommusRhodus_Testimonial_Block() );