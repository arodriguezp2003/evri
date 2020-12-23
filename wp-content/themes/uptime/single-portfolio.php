<?php
	get_header();
	the_post();
	
	// Portfolio specific layout with Option Switch
	get_template_part( 'inc/layout-portfolio-single', get_theme_mod( 'portfolio_single_layout', 'study' ) );
	
	// Portfolio Specific Related Posts
	get_template_part( 'inc/content-portfolio', 'related' );
	
	get_footer();