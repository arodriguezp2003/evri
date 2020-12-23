<div class="row mb-4" data-isotope-collection data-sort-ascending="true">	
	<?php 
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
			get_template_part( 'loop/content-post-masonry', get_post_format() );
			
		endwhile;	
		else : 
			
			get_template_part( 'loop/content', 'none' );
			
		endif;
	?>
</div>

<?php get_template_part( 'inc/content', 'pagination' );