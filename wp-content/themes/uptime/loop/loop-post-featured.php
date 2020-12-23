<?php
	$i      = 0;
	$paged  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$offset = ( 1 == $paged ) ? 1 : 0;
?>

<div class="row">

	<div class="col-md-7 col-lg-8 d-flex">

		<?php if( 1 == $paged ) : ?>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

				get_template_part( 'loop/content', 'post-featured' );

				break;
				endwhile;	
				endif;
				
				rewind_posts();
			?>

		<?php endif; ?>

	</div>

	<div class="col-md-5 col-lg-4">
		<ul class="list-unstyled list-articles">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
			
			if( $i >= $offset ) {
				get_template_part( 'loop/content', 'post-list' );
			}
			
			$i++;

			endwhile;	
			else : 
				
				get_template_part( 'loop/content', 'none' );
				
			endif; ?>

		</ul>
	</div>

</div>