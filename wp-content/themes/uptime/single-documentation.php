<?php
	get_header();
	the_post();
	
	// Reading Progress Counter
	get_template_part( 'inc/content-post', 'progress' );
	
	// Documentation Specific Hero Area
	if( 'yes' == get_theme_mod( 'show_single_documentation_hero', 'yes' ) ){
		get_template_part( 'inc/content-documentation', 'hero' );
	}
	
	// Standard Post Hero Area
	get_template_part( 'inc/content-post-single', 'hero-basic' );
	
	// Standard Post Content
	get_template_part( 'inc/content' );
	
	// Documentation Specific Voting
	get_template_part( 'inc/content-documentation', 'voting' );
	
	// Standard Comments and Sharing
	comments_template();
	
	// Documentation Specific Related Posts
	get_template_part( 'inc/content-documentation', 'related' );
	
	get_footer();