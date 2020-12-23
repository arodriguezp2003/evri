<ul class="list-unstyled list-articles">
	<?php 
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'loop/content-post-list' );
			
		endwhile;	
		else : 
			
			get_template_part( 'loop/content', 'none' );
			
		endif;
		
		get_template_part( 'inc/content', 'pagination' );
	?>
</ul>