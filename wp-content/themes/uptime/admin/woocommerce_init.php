<?php 

if(!( function_exists( 'tommusrhodus_change_template' ) )){
	function tommusrhodus_change_template( $template ) {
	 	
	 	global $post;
	 	
	 	if( !isset( $post->ID ) ){
	 		return $template;
	 	}
	 	
	    if( 
	    	wc_get_page_id( 'cart' ) == $post->ID || 
	    	wc_get_page_id( 'checkout' ) == $post->ID || 
	    	wc_get_page_id( 'myaccount' ) == $post->ID 
	    ) {
	
	        //Check theme directory first
	        $new_template = locate_template( array( 'woocommerce-custom.php' ) );
	        if( '' != $new_template ){
	            return $new_template;
	        }
	        
	    }
	
	    //Fall back to original template
	    return $template;
	
	}
	add_action( 'template_include', 'tommusrhodus_change_template', 10 );
}