<section class="controls-inside controls-light p-0" data-flickity='{ "imagesLoaded": true, "wrapAround": true }'>

	<?php 
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
			get_template_part( 'loop/content-post' , 'slider' );
			
		endwhile;	
		else : 
			
			get_template_part( 'loop/content', 'none' );
			
		endif;
	?>
		
</section>