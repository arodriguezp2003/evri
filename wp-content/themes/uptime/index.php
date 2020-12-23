<?php 
	get_header(); 
	
	// Posts Archive Hero Area
	get_template_part( 'inc/content-post', 'hero' );
?>

<section>
	<div class="container">
		<?php 
			// Get the blog layout based on theme option
			get_template_part( 'loop/loop-post', get_theme_mod( 'blog_layout', 'sidebar' ) ); 
		?>
	</div>
</section>
    
<?php 
	// Posts specific Call To Action
	get_template_part( 'inc/content-post', 'cta' );
	
	get_footer();