<div class="row mb-4">
	
	<div class="col-md-12">
		<?php 
			if ( have_posts() ) : while ( have_posts() ) : the_post();

				get_template_part( 'loop/content-post-sidebar', get_post_format() );
				
			endwhile;	
			else : 
				
				get_template_part( 'loop/content', 'none' );
				
			endif;
			
			get_template_part( 'inc/content', 'pagination' );
		?>
	</div>

</div>