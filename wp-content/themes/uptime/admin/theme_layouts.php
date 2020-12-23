<?php 

if(!( function_exists( 'tommusrhodus_get_client_layouts' ) )){
	function tommusrhodus_get_client_layouts(){
	
		$options = array(
			'Small Logos' 					=> 'small',
			'Small Logos, No Margin Bottom' => 'small-no-margin',
			'Large Logos' 					=> 'large',
			'Large Logos, No Margin Bottom' => 'large-no-margin'
		);
		
		$options = apply_filters( 'tommusrhodus_add_client_layouts', $options );
		
		return $options;
		
	}
}

function tommusrhodus_get_divider_layouts(){

	$options = array(
		'none'  	=> 'No Divider',
		'ramp'  	=> 'Ramp',
		'pipe'  	=> 'Half Pipe',
		'pipe-alt'  => 'Half Pipe Alternative Background',
		'curve' 	=> 'Curve',
		'slope' 	=> 'Slope',
		'fan'   	=> 'Fan'
	);
	
	$options = apply_filters( 'tommusrhodus_add_divider_layouts', $options );
	
	return $options;
	
}

/**
 * tommusrhodus_get_team_layouts()
 * 
 * Grabs a list of team layouts, can be filtered with
 * the filter seen within this function.
 * 
 * @return Returns an array of layouts
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_get_team_layouts' ) )){
	function tommusrhodus_get_team_layouts(){
	
		$options = array(
			'4-columns' 			=> '4 Column Grid',
			'3-columns' 			=> '3 Column Grid',
			'2-columns' 			=> '2 Column Grid',		
		);
		
		$options = apply_filters( 'tommusrhodus_add_team_layouts', $options );
		
		return $options;
		
	}
}

/**
 * tommusrhodus_get_testimonial_layouts()
 * 
 * Grabs a list of testimonial layouts, can be filtered with
 * the filter seen within this function.
 * 
 * @return Returns an array of layouts
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_get_testimonial_layouts' ) )){
	function tommusrhodus_get_testimonial_layouts(){
	
		$options = array(
			'1' => 'Large Testimonial and Profile Image',
			'2' => 'Testimonials Carousel',
			'3' => 'Large Testimonial and Background Image'
		);
		
		$options = apply_filters( 'tommusrhodus_add_testimonial_layouts', $options );
		
		return $options;
		
	}
}

/**
 * tommusrhodus_get_portfolio_layouts()
 * 
 * Grabs a list of portfolio layouts, can be filtered with
 * the filter seen within this function.
 * 
 * @return Returns an array of layouts
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_get_portfolio_layouts' ) )){
	function tommusrhodus_get_portfolio_layouts(){
	
		$options = array(
			'2-columns' => '2 Columns',
			'3-columns' => '3 Columns',
			'4-columns' => '4 Columns'
		);
		
		$options = apply_filters( 'tommusrhodus_add_portfolio_layouts', $options );
		
		return $options;
		
	}
}

/**
 * tommusrhodus_get_blog_layouts()
 * 
 * Grabs a list of blog layouts, can be filtered with
 * the filter seen within this function.
 * 
 * @return Returns an array of layouts
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_get_blog_layouts' ) )){
	function tommusrhodus_get_blog_layouts(){
	
		$options = array(
			'card' 				=> 'Blog Cards',
			'masonry' 			=> 'Blog Masonry',
			'sidebar' 			=> 'Blog Sidebar',
			'wide' 				=> 'Blog Wide',
			'list' 				=> 'Blog List',
			'featured' 			=> 'Blog Feature (Shows 5 Posts Only)',
			'slider'			=> 'Fullwidth Slider',
		);
		
		$options = apply_filters( 'tommusrhodus_add_blog_layouts', $options );
		
		return $options;
		
	}
}

/**
 * tommusrhodus_get_blog_single_layouts()
 * 
 * Grabs a list of blog layouts, can be filtered with
 * the filter seen within this function.
 * 
 * @return Returns an array of layouts
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_get_blog_single_layouts' ) )){
	function tommusrhodus_get_blog_single_layouts(){
	
		$options = array(
			'basic' 	=> 'Basic Hero',
			'image' 	=> 'Image Hero',
			'parallax' 	=> 'Parallax Hero',
		);
		
		$options = apply_filters( 'tommusrhodus_add_blog_single_layouts', $options );
		
		return $options;
		
	}
}

/**
 * tommusrhodus_get_portfolio_single_layouts()
 * 
 * Grabs a list of portfolio layouts, can be filtered with
 * the filter seen within this function.
 * 
 * @return Returns an array of layouts
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_get_portfolio_single_layouts' ) )){
	function tommusrhodus_get_portfolio_single_layouts(){
	
		$options = array(
			'study' 	=> 'Study',
			'sidebar' 	=> 'Sidebar',
			'slider' 	=> 'Slider',
			'parallax'	=> 'Parallax'
		);
		
		$options = apply_filters( 'tommusrhodus_add_portfolio_single_layouts', $options );
		
		return $options;
		
	}
}

/**
 * tommusrhodus_get_header_layouts()
 * 
 * Grabs a list of header layouts, can be filtered with
 * the filter seen within this function.
 * 
 * @return Returns an array of layouts
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_get_header_layouts' ) )){
	function tommusrhodus_get_header_layouts(){
	
		$options = array(			
			'white' 							=> 'White Background Header',
			'white-not-sticky' 					=> 'White Background Header, Not Sticky',
			'light' 							=> 'Light Background Header',
			'light-not-sticky'					=> 'Light Background Header, Not Sticky',
			'dark' 								=> 'Dark Background Header',
			'primary' 							=> 'Primary Background Header',			
			'primary-3'							=> 'Primary Background 3 Header',		
			'primary-centered'					=> 'Primary Background 3 Header, Centered',
			'overlay' 							=> 'Overlay Header',
			'overlay-not-sticky'				=> 'Overlay Header, Not Sticky',
			'white-centered'					=> 'White Background Centered Menu Header',
			'transparent-overlay'				=> 'Transparent Background Header',
			'transparent-overlay-not-sticky'	=> 'Transparent Background Header, Not Sticky',
			'transparent-overlay-white-btn'		=> 'Transparent Background Header, White Button',
			'no-header' 						=> 'No Header',
		);
		
		$options = apply_filters( 'tommusrhodus_add_header_layouts', $options );
		
		return $options;
		
	}
}

/**
 * tommusrhodus_get_footer_layouts()
 * 
 * Grabs a list of footer layouts, can be filtered with
 * the filter seen within this function.
 * 
 * @return Returns an array of layouts
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_get_footer_layouts' ) )){
	function tommusrhodus_get_footer_layouts(){
	
		$options = array(
			'1' 					=> 'Default Footer',			
			'1-gradient-3-bg'		=> 'Default Footer, Gradient 3 Background',
			'2' 					=> 'Vanilla Footer',			
			'3-primary-bg' 			=> 'Centered Footer, Primary Background',
			'3' 					=> 'Centered Footer, Primary 3 Background',
			'4' 					=> 'Short Footer',
			'no-footer'				=> 'No Footer',
		);
		
		$options = apply_filters( 'tommusrhodus_add_footer_layouts', $options );
		
		return $options;
		
	}
}

/**
 * tommusrhodus_get_documentation_layouts()
 * 
 * Grabs a list of documentation layouts, can be filtered with
 * the filter seen within this function.
 * 
 * @return Returns an array of layouts
 * @since v1.0.0
 * @blame Tom Rhodes
 */
if(!( function_exists( 'tommusrhodus_get_documentation_layouts' ) )){
	function tommusrhodus_get_documentation_layouts(){
	
		$options = array(
			'1' => 'Simple List',
			'2' => 'Simple List with Categories',
		);
		
		$options = apply_filters( 'tommusrhodus_add_documentation_layouts', $options );
		
		return $options;
		
	}
}