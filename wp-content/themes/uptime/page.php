<?php
	get_header();
	the_post();
	
	// Reading Progress Counter
	get_template_part( 'inc/content-post', 'progress' );
 	
 	// Page specific Breadcrumbs
 	get_template_part( 'inc/content-page', 'breadcrumbs' );
 	
 	// Page specific content markup
 	get_template_part( 'inc/content-page', 'content' );
 	
	// Standard Comments and Sharing
	comments_template();
	
	get_footer();